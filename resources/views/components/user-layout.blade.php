<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script src="{{ asset('js/tailwind.js') }}"></script>
</head>

<body class="min-h-screen font-sans antialiased dark:bg-black dark:text-white/50">
    <section class="flex flex-col sm:flex-row h-full min-h-screen">
        <div class="border border-zinc-600 rounded-md m-2 overflow-hidden min-w-[260px]">
            <!-- logo  -->
            <h1 class="block text-black dark:text-white text-3xl sm:text-4xl font-bold text-center mb-6 mt-8">BookCall</h1>
            <div class="flex flex-col h-[80%] justify-between p-2">
                <!-- nav links  -->
                <ul class="flex flex-col gap-3">
                    <li class="p-2 text-center font-semibold rounded-md 
    {{ request()->is('bookings') || request()->is('bookings/*') ? 'bg-white text-black' : 'bg-zinc-700/50 text-white' }}">
                        <a href="/bookings" class="block">Bookings</a>
                    </li>
                    <li class="p-2 text-center font-semibold rounded-md 
    {{ request()->is('meetings') ? 'bg-white text-black' : 'bg-zinc-700/50 text-white' }}">
                        <a href="/meetings" class="block">Meetings</a>
                    </li>
                    <li class="p-2 text-center font-semibold rounded-md 
    {{ request()->is('profile') ? 'bg-white text-black' : 'bg-zinc-700/50 text-white' }}">
                        <a href="/profile" class="block">Profile</a>
                    </li>

                    <li class="p-2 text-center font-semibold rounded-md 
    {{ request()->is('availability') ? 'bg-white text-black' : 'bg-zinc-700/50 text-white' }}">
                        <a href="/availability" class="block">Availability</a>
                    </li>
                </ul>
                <!-- log out   -->
                <div class="mt-5">
                    @auth
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="block w-full py-1 rounded-md text-center font-semibold cursor-pointer duration-100 ease-in-out bg-red-700/55 text-black dark:text-gray-200">Log Out</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
        <div class="flex-grow">
            {{$slot}}
        </div>
    </section>

</body>

</html>