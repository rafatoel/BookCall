<?php

use App\Http\Controllers\BookCallController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserAvailabilityController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\UserProfileController;
use App\Livewire\DateSelector;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::get('/counter', DateSelector::class)->name('counter');

Route::get('/booked', function () {
    // Check if the message is exactly 'booking complete'
    if (session('message') !== 'booking complete') {
        // session('message', '');
        return redirect('/')->with('error', 'Unauthorized access.');
    }

    // If the message is correct, show the booked view
    return view('booked');
})->name('booked');





// Auth routes for guests
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    // Availability routes
    Route::get('/availability', [UserAvailabilityController::class, 'index'])->name('availability.index');
    Route::put('/availability', [UserAvailabilityController::class, 'update'])->name('availability.update');

    // Booking routes
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/confirmed', [UserBookingController::class, 'confirmed'])->name('bookings.confirmed');
    Route::get('/bookings/past', [UserBookingController::class, 'past'])->name('bookings.past');
    Route::patch('/bookings/{booking}/complete', [UserBookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('/bookings/{booking}/confirm', [UserBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

    // Meeting routes
    Route::get('/meetings', [UserBookingController::class, 'meetings'])->name('meetings.index');
});


// Wildcard route for booking calls
Route::get('/{username}', [BookCallController::class, 'index'])
    ->where('username', '[a-zA-Z0-9_-]+')
    ->name('bookcall');
