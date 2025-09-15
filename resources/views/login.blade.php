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
        width: 93%;
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

        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 12px;">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div id="success-alert" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 12px; position: relative;">
                {{ session('success') }}
                <button type="button" onclick="this.parentElement.style.display='none'" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #155724; font-size: 16px; cursor: pointer;">&times;</button>
            </div>
            <script>
                // Auto hide setelah 5 detik
                setTimeout(function() {
                    var alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            alert.style.display = 'none';
                        }, 500);
                    }
                }, 5000);
            </script>
        @endif

        @if(session('error'))
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 12px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Masukkan email Anda" 
                required
                style="padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px; background-color: #f8f9fa;"
            >

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="off" required>

            <a href="{{ url('/forgotpassword') }}" class="forgot-password">Forgot Password?</a>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</php>
