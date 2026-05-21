<x-user-layout>
    <div class="p-2">
        <h1 class="text-5xl font-bold text-black dark:text-white border border-zinc-700/80 rounded-md p-2">Profile</h1>
    </div>
    <form method="POST" action="/profile" enctype="multipart/form-data" class="w-full p-2">
        @csrf
        @method('PATCH') <!-- Use PUT or PATCH for updating the profile -->
        <div class="p-2 md:p-4 border border-zinc-700/80 dark:border-zinc-700/80 rounded-md mb-6">
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                <!-- Avatar Upload -->
                <div class="sm:col-span-6 flex">
                    <div class="preview w-24 h-24 bg-white rounded-md overflow-hidden border border-gray-300">
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}"
                            alt="Profile Picture" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col justify-between p-2">
                        <x-form-label class="text-xl font-semibold">Avatar</x-form-label>
                        <div class="mt-2">
                            <label for="avatar" class="block w-32 py-1 bg-zinc-700 hover:bg-zinc-600 rounded-sm text-center text-sm font-semibold text-gray-700 dark:text-white cursor-pointer duration-100 ease-in-out">
                                Browse
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                class="hidden text-sm text-gray-900 border border-gray-300 rounded-sm cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <x-form-error name="avatar"></x-form-error>
                        </div>
                    </div>
                </div>

                <!-- Username Input -->
                <div class="sm:col-span-6">
                    <x-form-label>Username</x-form-label>
                    <div class="mt-2 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center pl-3 pr-1 rounded-l-md bg-zinc-50 text-gray-500 dark:bg-zinc-700 dark:text-white">bookcall.com/</span>
                        <x-form-input id="username" name="username" class="flex-1 block w-full rounded-none rounded-r-md" value="{{ $user->username }}" required></x-form-input>
                    </div>
                    <x-form-error name="username"></x-form-error>
                </div>

                <!-- First and Last Names -->
                <div class="sm:col-span-3">
                    <x-form-label>First Name</x-form-label>
                    <div class="mt-2">
                        <x-form-input id="first_name" name="first_name" value="{{ $user->first_name }}" required></x-form-input>
                        <x-form-error name="first_name"></x-form-error>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <x-form-label>Last Name</x-form-label>
                    <div class="mt-2">
                        <x-form-input id="last_name" name="last_name" value="{{ $user->last_name }}" required></x-form-input>
                        <x-form-error name="last_name"></x-form-error>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="sm:col-span-6">
                    <x-form-label>Email</x-form-label>
                    <div class="mt-2">
                        <x-form-input id="email" name="email" type="email" value="{{ $user->email }}" required></x-form-input>
                        <x-form-error name="email"></x-form-error>
                    </div>
                </div>

                <!-- Bio Input -->
                <div class="sm:col-span-6">
                    <x-form-label>Bio</x-form-label>
                    <div class="mt-2">
                        <textarea id="bio" name="bio" rows="4" class="block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-zinc-500/50 dark:bg-zinc-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">{{ $user->bio }}</textarea>
                        <x-form-error name="bio"></x-form-error>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="sm:col-span-6 flex justify-between gap-4">
                    <a href="#" class="flex justify-center w-full px-3 py-2 rounded-md text-center text-sm font-semibold bg-zinc-900 dark:bg-zinc-700 text-white dark:text-white hover:bg-zinc-800 dark:hover:bg-zinc-600">Cancel</a>
                    <button type="submit" class="w-full rounded-md bg-black dark:bg-white px-3 py-2 font-semibold text-white dark:text-black text-sm shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update Profile</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Account Section -->
    <div class="p-2 mt-6 border border-red-700/80 rounded-md">
        <h2 class="text-xl font-bold text-red-600 dark:text-red-400 mb-4">Delete Account</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
        <form method="POST" action="/profile" class="flex items-end gap-4">
            @csrf
            @method('DELETE')
            <div class="flex-1">
                <x-form-label>Password Confirmation</x-form-label>
                <x-form-input type="password" name="password" required placeholder="Enter your password"></x-form-input>
                <x-form-error name="password"></x-form-error>
            </div>
            <button type="submit" class="rounded-md bg-red-600 px-4 py-2 font-semibold text-white text-sm shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                Delete Account
            </button>
        </form>
    </div>
</x-user-layout>