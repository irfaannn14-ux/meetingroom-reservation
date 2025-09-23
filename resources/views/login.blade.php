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
            background: linear-gradient(135deg, #010D26 0%, #1a2b4a 30%, #4a6fa5 70%, #C9DFF2 100%);
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            width: 300px;
            text-align: center;
        }
        .logo{
            width: 50px;
            height: auto;
            margin-bottom: 15px;
        }
        .login-card h2{
            color: #1D64F2;
            margin-bottom: 5px;
            font-weight: 700;
        }
        .login-card p{
            font-size: 14px;
            color: #4a6fa5;
            margin-bottom: 25px;
        }
        .login-card label{
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 13px;
            color: #010D26;
            font-weight: 500;
        }
        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 93%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .login-card input[type="email"]:focus,
        .login-card input[type="password"]:focus {
            outline: none;
            border-color: #1D64F2;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 3px rgba(29, 100, 242, 0.1);
        }
        .forgot-password {
            display: block;
            text-align: right;
            font-size: 13px;
            margin-top: -10px;
            margin-bottom: 20px;
            color: #1D64F2;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #010D26;
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .alert-error {
            background: rgba(248, 215, 218, 0.9);
            color: #721c24;
            border-color: rgba(220, 53, 69, 0.3);
        }
        
        .alert-success {
            background: rgba(212, 237, 218, 0.9);
            color: #155724;
            border-color: rgba(40, 167, 69, 0.3);
        }

        .login-card button {
            width: 100%;
            background: linear-gradient(135deg, #1D64F2 0%, #010D26 100%);
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(29, 100, 242, 0.3);
        }

        .login-card button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 100, 242, 0.4);
        }
        
        .login-card button:active {
            transform: translateY(0);
        }

    </style>
</head>
<body>
    <div class="login-card">
        <img src="{{ asset('images/logoipsum.png') }}" alt="Logo" class="logo">
        <h2 style="color: #010D26;">Login to Your Account</h2>
        <p style="color: #010D26;">Enter your email & password to login</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div id="success-alert" class="alert alert-success" style="position: relative;">
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
            <div class="alert alert-error">
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
            >

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="off" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</php>
