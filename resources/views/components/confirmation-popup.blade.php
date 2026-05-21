<!-- Confirmation Popup Component -->
<div>
    <!-- Trigger Button -->
    <button id="trigger-btn-{{ $id }}" class="block w-32 py-1 rounded-sm text-center text-sm font-bold cursor-pointer duration-100 ease-in-out
        {{ $type === 'primary' ? 'bg-black dark:bg-white text-black dark:text-zinc-900' : '' }}
        {{ $type === 'danger' ? 'bg-red-700/25 text-red-700 dark:text-red-700' : '' }}
        {{ $type === 'secondary' ? 'bg-zinc-400/25 text-black dark:text-gray-200' : '' }}">
        {{ $triggerText }}
    </button>

    <!-- Confirmation Popup -->
    <div id="confirmation-popup-{{ $id }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-zinc-800 p-6 rounded-md shadow-lg w-96">
            <!-- Dynamic Popup Header -->
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $popupHeader }}</h2>
            <p class="text-sm text-zinc-600 dark:text-gray-300 mt-2">
                {{ $popupMessage }}
            </p>

            <!-- Dynamic Form Slot -->
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    // Confirmation Popup Logic
    document.addEventListener('DOMContentLoaded', function() {
        const triggerButton = document.getElementById('trigger-btn-{{ $id }}');
        const confirmationPopup = document.getElementById('confirmation-popup-{{ $id }}');
        
        if (triggerButton && confirmationPopup) {
            triggerButton.addEventListener('click', () => {
                confirmationPopup.classList.remove('hidden');
            });

            // Close popup when clicking cancel button (using class selector for multiple instances)
            const cancelButtons = confirmationPopup.querySelectorAll('.cancel-btn');
            cancelButtons.forEach(cancelBtn => {
                cancelBtn.addEventListener('click', () => {
                    confirmationPopup.classList.add('hidden');
                });
            });

            // Close popup when clicking outside the modal
            confirmationPopup.addEventListener('click', (e) => {
                if (e.target === confirmationPopup) {
                    confirmationPopup.classList.add('hidden');
                }
            });
        }
    });
</script>