<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Attributes\On;
use Livewire\Component;


class BookCallTray extends Component
{
    public $formData = [
        'name' => '',
        'email' => '',
        'title' => '',
        'description' => '',
        'duration' => 60,
        'date' => '',
        'time' => '',
    ];

    public function bookCall()
    {
        // Validate form data
        $validated = $this->validate([
            'formData.name' => ['required', 'string', 'max:255'],
            'formData.email' => ['required', 'email', 'max:255'],
            'formData.title' => ['required', 'string', 'max:255'],
            'formData.description' => ['nullable', 'string', 'max:1000'],
            'formData.duration' => ['required', 'integer', 'min:1'],
            'formData.date' => ['required', 'date', 'after_or_equal:today'],
            'formData.time' => ['required', 'string', 'regex:/^\d{2}:\d{2}:\d{2} - \d{2}:\d{2}:\d{2}$/'],
        ]);

        // Split the time into start and end times
        $timeParts = explode(' - ', $this->formData['time']);
        if (count($timeParts) !== 2) {
            $this->addError('formData.time', 'Invalid time format');
            return;
        }
        
        [$startTime, $endTime] = $timeParts;

        // Validate time format before using
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $startTime) || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $endTime)) {
            $this->addError('formData.time', 'Invalid time format');
            return;
        }

        // Check for double booking with proper overlap detection
        $existingBooking = Booking::where('user_id', session('user_id'))
            ->where('date', $this->formData['date'])
            ->where('canceled', false)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Existing booking starts before new booking ends AND ends after new booking starts
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->first();

        if ($existingBooking) {
            $this->addError('formData.time', 'This time slot is no longer available. Please select another time.');
            return;
        }

        // Insert the form data into the database
        Booking::create([
            'user_id' => session('user_id'),
            'client_name' => $this->formData['name'],
            'client_email' => $this->formData['email'],
            'title' => $this->formData['title'],
            'description' => $this->formData['description'],
            'meeting_link' => '',
            'duration' => $this->formData['duration'],
            'date' => $this->formData['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect('/booked')->with('message', 'booking complete')->with('bookingData', $this->formData);
    }

    #[On('inputChange')]
    public function inputChange($propertyName, $value)
    {
        $this->formData[$propertyName] = $value;
    }

    public function render()
    {
        return view('livewire.book-call-tray');
    }
}
