<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
   @vite('resources/css/app.css')
</head>
<body class="form-body">
    <div class="form-wrapper">
        <h2>Register</h2>


        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input name="name" type="name" placeholder="Name" required>
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <input name="password_confirmation" type="password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>