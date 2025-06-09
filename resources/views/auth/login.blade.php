<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
   @vite('resources/css/app.css')
</head>
<body class="form-body">
    <div class="form-wrapper">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="form-footer">
            <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        </div>
    </div>
</body>
</html>
