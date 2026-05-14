<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMA Ananda Batam - Digital Portal</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        {{-- Logo --}}
        <div class="mb-6">
            <a href="/">
                <img src="{{ asset('images/Logo-SMA-ANANDA-BATAM.png') }}" class="h-24 w-auto" alt="SMA Ananda Batam">
            </a>
        </div>

        {{-- Card --}}
        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">SMA Ananda Portal</h1>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                    Manage classrooms, track attendance, and collaborate on tasks — all in one place.
                </p>
            </div>

            <div class="space-y-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Log In
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Register
                        </a>
                    @endif
                @endauth
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} SMA Ananda Batam. All rights reserved.
                </p>
            </div>
        </div>

    </div>

</body>
</html>
