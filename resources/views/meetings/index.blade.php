<x-user-layout>
    <div class="p-2">
        <h1 class="text-5xl font-bold text-black dark:text-white border border-zinc-700/80 rounded-md p-2">Meetings</h1>
    </div>
    <div class="flex flex-col gap-5 max-h-screen overflow-y-scroll p-6">
        
        <!-- Upcoming Meetings -->
        <div class="mb-8">
            <h2 class="text-3xl font-semibold text-black dark:text-white mb-4">Upcoming Meetings</h2>
            @if($upcomingBookings->isEmpty())
                <p class="text-zinc-400">No upcoming meetings scheduled.</p>
            @else
                @foreach($upcomingBookings as $booking)
                    <div class="border rounded-md px-2.5 py-3 mb-4">
                        <div class="top">
                            <h3 class="text-2xl font-semibold text-black dark:text-white">{{ ucfirst($booking->title) }}</h3>
                            <p class="text-zinc-300">{{ $booking->client_name }} - {{ $booking->client_email }}</p>
                        </div>
                        <div class="my-2">
                            <p class="text-lg text-zinc-200">{{ date('D, d M', strtotime($booking->date)) }}</p>
                            <p class="text-zinc-300">{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                        </div>
                        <p class="text-zinc-300 mb-2">{{ $booking->description }}</p>
                        
                        @if($booking->meeting_link)
                            <div class="mt-2">
                                <a href="{{ $booking->meeting_link }}" target="_blank" 
                                   class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                    Join Meeting
                                </a>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="mt-2">
                            @if($booking->confirmed)
                                <span class="px-3 py-1 bg-green-600 text-white text-sm font-semibold rounded-md">Confirmed</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-600 text-white text-sm font-semibold rounded-md">Pending Confirmation</span>
                            @endif
                            
                            @if($booking->complete)
                                <span class="px-3 py-1 bg-blue-600 text-white text-sm font-semibold rounded-md ml-2">Completed</span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @if(!$booking->complete && !$booking->canceled)
                            <div class="flex gap-3 rounded-md mt-3">
                                @if(!$booking->confirmed)
                                    <!-- Confirm Booking Button -->
                                    <x-confirmation-popup
                                        id="confirm-booking-{{ $booking->id }}"
                                        triggerText="Confirm Booking"
                                        popupHeader="Confirm Booking"
                                        popupMessage="Fill out the meeting link and confirm the booking."
                                        type="primary">
                                        <form method="POST" action="/bookings/{{ $booking->id }}/confirm">
                                            @csrf
                                            @method('PATCH')

                                            <div class="mt-4">
                                                <label for="meeting_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meeting Link</label>
                                                <input type="url" id="meeting_link" name="meeting_link" 
                                                       class="mt-1 p-1 block w-full rounded-sm border-gray-300 shadow-sm dark:bg-zinc-700 dark:border-zinc-600" 
                                                       placeholder="https://zoom.us/j/..." required>
                                            </div>

                                            <div class="mt-4 flex justify-end space-x-3">
                                                <button type="button"
                                                    class="cancel-btn px-4 py-1 bg-zinc-300 text-gray-900 font-semibold rounded-md hover:bg-gray-400">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-1 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                                    Confirm
                                                </button>
                                            </div>
                                        </form>
                                    </x-confirmation-popup>
                                @endif

                                @if($booking->confirmed && !$booking->complete)
                                    <!-- Mark as Complete Button -->
                                    <x-confirmation-popup
                                        id="complete-booking-{{ $booking->id }}"
                                        type="success"
                                        triggerText="Mark as Complete"
                                        popupHeader="Complete Booking"
                                        popupMessage="Are you sure you want to mark this booking as completed?">
                                        <form method="POST" action="/bookings/{{ $booking->id }}/complete">
                                            @csrf
                                            @method('PATCH')

                                            <div class="mt-4 flex justify-end space-x-3">
                                                <button type="button"
                                                    class="cancel-btn px-4 py-1 bg-zinc-300 text-gray-900 font-semibold rounded-md hover:bg-gray-400">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-1 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                                                    Complete
                                                </button>
                                            </div>
                                        </form>
                                    </x-confirmation-popup>
                                @endif

                                <!-- Cancel Booking Button -->
                                <x-confirmation-popup
                                    id="cancel-booking-{{ $booking->id }}"
                                    type="secondary"
                                    triggerText="Cancel Booking"
                                    popupHeader="Cancel Booking"
                                    popupMessage="Are you sure you want to cancel this booking? This action cannot be undone.">
                                    <form method="POST" action="/bookings/{{ $booking->id }}/cancel">
                                        @csrf
                                        @method('PATCH')

                                        <div class="mt-4 flex justify-end space-x-3">
                                            <button type="button"
                                                class="cancel-btn px-4 py-1 bg-zinc-300 text-gray-900 font-semibold rounded-md hover:bg-gray-400">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-1 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700">
                                                Confirm
                                            </button>
                                        </div>
                                    </form>
                                </x-confirmation-popup>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Past Meetings -->
        <div>
            <h2 class="text-3xl font-semibold text-black dark:text-white mb-4">Past Meetings</h2>
            @if($pastBookings->isEmpty())
                <p class="text-zinc-400">No past meetings found.</p>
            @else
                @foreach($pastBookings as $booking)
                    <div class="border rounded-md px-2.5 py-3 mb-4 opacity-75">
                        <div class="top">
                            <h3 class="text-2xl font-semibold text-black dark:text-white">{{ ucfirst($booking->title) }}</h3>
                            <p class="text-zinc-300">{{ $booking->client_name }} - {{ $booking->client_email }}</p>
                        </div>
                        <div class="my-2">
                            <p class="text-lg text-zinc-200">{{ date('D, d M', strtotime($booking->date)) }}</p>
                            <p class="text-zinc-300">{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                        </div>
                        <p class="text-zinc-300 mb-2">{{ $booking->description }}</p>

                        @if($booking->meeting_link)
                            <div class="mt-2">
                                <a href="{{ $booking->meeting_link }}" target="_blank" 
                                   class="inline-block px-4 py-2 bg-zinc-600 text-white font-semibold rounded-md hover:bg-zinc-700">
                                    View Meeting Link
                                </a>
                            </div>
                        @endif

                        <!-- Status Badges -->
                        <div class="mt-2 flex gap-2">
                            @if($booking->canceled)
                                <span class="px-3 py-1 bg-red-600 text-white text-sm font-semibold rounded-md">Canceled</span>
                            @elseif($booking->complete)
                                <span class="px-3 py-1 bg-green-600 text-white text-sm font-semibold rounded-md">Completed</span>
                            @elseif($booking->missed ?? false)
                                <span class="px-3 py-1 bg-orange-600 text-white text-sm font-semibold rounded-md">Missed</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</x-user-layout>
