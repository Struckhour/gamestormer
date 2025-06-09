<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laravel 12 with Vue 3</title>
    @vite(['resources/js/app.js'])
</head>
    <body>
        <div style="position: fixed; top: 10px; right: 10px;">
            @if (Auth::check())
                <span>Welcome, {{ Auth::user()->name }}!</span>
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit" class="little-button">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endif
        </div>

        {{-- Your main app container (if using Vue) --}}
        <div id="app"></div>

        {{-- Yield your page content --}}
        @yield('content')
    </body>
</html>
