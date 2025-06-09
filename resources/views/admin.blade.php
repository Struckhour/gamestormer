<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Laravel + Vue App</title>
    @vite('resources/js/app.js')
</head>
<body>
    <div>Hi there, administrator!</div>
    <!-- Show this only if the user is logged in -->
    @if (Auth::check())
        <form method="POST" action="/logout" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endif
</body>
</html>
