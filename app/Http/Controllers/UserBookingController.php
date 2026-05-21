<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    // List unconfirmed, uncancelled, and not completed bookings
    public function index()
    {
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->where('confirmed', false)
            ->where('canceled', false)
            ->where('complete', false)
            ->whereDate('date', '>=', now()->toDateString())
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    // List confirmed, uncancelled, and not completed bookings
    public function confirmed()
    {
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->where('confirmed', true)
            ->where('canceled', false)
            ->where('complete', false)
            ->whereDate('date', '>=', now()->toDateString())
            ->get();

        return view('bookings.confirmed', compact('bookings'));
    }

    // List past meetings (completed, canceled, or missed)
    public function past()
    {
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('complete', true)
                    ->orWhere('canceled', true)
                    ->orWhere('date', '<', now()->toDateString());
            })
            ->get()
            ->map(function ($booking) {
                // Add "missed" attribute dynamically
                $booking->missed = $booking->date < now() && !$booking->complete && !$booking->canceled;
                return $booking;
            });

        return view('bookings.past', compact('bookings'));
    }

    // Update a booking status to "complete"
    public function complete(Booking $booking)
    {
        // Authorization check
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->update(['complete' => true]);

        return redirect()->back()->with('success', 'Booking marked as completed.');
    }

    // Update a booking status to "confirmed" and add a meeting link
    public function confirm(Booking $booking, Request $request)
    {
        // Authorization check
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'meeting_link' => 'required|string|url',
        ]);

        $booking->update([
            'confirmed' => true,
            'meeting_link' => $request->meeting_link,
        ]);

        // Can't test with out SMTP server ⚠️
        // Send email to the booker
        // Mail::to($booking->booker_email)->send(new MeetingConfirmed($request->meeting_link));

        return redirect()->back()->with('success', 'Booking confirmed successfully.');
    }

    // Update a booking status to "canceled"
    public function cancel(Booking $booking)
    {
        // Authorization check
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->update(['canceled' => true]);

        return redirect()->back()->with('success', 'Booking canceled successfully.');
    }

    // List all meetings (upcoming and past)
    public function meetings()
    {
        $upcomingBookings = Booking::query()
            ->where('user_id', Auth::id())
            ->where('canceled', false)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $pastBookings = Booking::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('complete', true)
                    ->orWhere('canceled', true)
                    ->orWhere('date', '<', now()->toDateString());
            })
            ->whereDate('date', '<', now()->toDateString())
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get()
            ->map(function ($booking) {
                $booking->missed = $booking->date < now() && !$booking->complete && !$booking->canceled;
                return $booking;
            });

        return view('meetings.index', compact('upcomingBookings', 'pastBookings'));
    }
}
