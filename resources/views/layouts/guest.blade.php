<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-start gap-2 items-center pt-6 sm:pt-12 bg-gray-100 bg-[url('/public/dark_forest.png')] bg-cover bg-center min-h-screen">
            <div class="shrink-0 flex items-center">
                <a href="{{ route('projects.index') }}" wire:navigate>
                    <img src="{{ asset('knight.png') }}" alt="My Company Logo" class="block h-24 w-auto">
                </a>
            </div>
            <div class="hidden sm:flex items-center">
                <a href="{{ route('projects.index') }}" class="font-semibold text-gray-200 text-5xl font-pixelify">SpriteBord</a>
            </div>
            <p class="text-white">A place to organize and collaborate on video game development.</p>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
