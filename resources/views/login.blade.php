<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Montserrat, sans-serif;
            background-color: #C9DFF2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 6px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
            width: 300px;
            text-align: center;
        }
        .logo{
            width: 50px;
            height: auto;
            margin-bottom: 15px;
        }
        .login-card h2{
            color: 1D64F2;
            margin-bottom: 5px;
        }
        .login-card p{
            font-size: 12px;
            color: #1D64F2;
            margin-bottom: 20px;
        }
        .login-card label{
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 12px;
            color: #565656;
        }
        .login-card input[type="email"],
        .login-card input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 12px;
        }
        .forgot-password {
        display: block;
        text-align: right;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 20px;
        color: #1D64F2;
        text-decoration: none;
        }

        .forgot-password:hover {
        text-decoration: underline;
        }

        .login-card button {
        width: 100%;
        background-color: #1D64F2;
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        }

        .login-card button:hover {
        background-color: #010D26;
        }

    </style>
</head>
<body>
    <div class="login-card">
        <img src="{{ asset('images/logoipsum.png') }}" alt="Logo" class="logo">
        <h2>Login to Your Account</h2>
        <p>Enter your email & password to login</p>

        <form>
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Enter your email" required>

        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Enter your password" required>

        <a href="#" class="forgot-password">Forgot Password?</a>

        <button type="submit">Login</button>
        </form>
    </div>
</body>
</php>
