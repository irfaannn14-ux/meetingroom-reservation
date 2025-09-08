<?php ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        .forgot-card {
            background: #fff;
            padding: 40px;
            border-radius: 6px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
            width: 300px;
            text-align: center;
        }

        h2{
            color: #1D64F2;
            margin-bottom: 5px;
        }
        p{
            font-size: 12px;
            color: #1D64F2;
            margin-bottom: 30px;
        }
        .forgot-card label{
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 12px;
            color: #565656;
        }

        .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
        margin-bottom: 20px;
        }
        .back {
        display: flex;
        text-align: center;
        font-size: 10px;
        margin-top: -15px;
        margin-bottom: 30px;
        gap: 6px;
        color: #555;
        text-decoration: none;
        cursor: pointer;
        }
        .back.arrow{
        margin-right: 6px;
        text-decoration: none;
        }

        .btn {
        width: 100%;
        background-color: #1D64F2;
        color: white;
        border: none;
        padding: 8px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 25px;
        }
        .back:hover {
        text-decoration: underline;
        }
        .btn:hover {
        background-color: #010D26;
        }

    </style>
</head>
<body>
    <div class="forgot-card">
        <a href="{{ url('/login') }}" class="back">
            <span class="arrow">&lt;</span>
            <span>Back</span>
        </a>
        <h2>Forgot Password?</h2>
        <p>Enter your email addres</p>
        <form>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Enter your email" required>
            </div>
        <button class="btn" type="submit" onclick="window.location.href='{{ url('/verifikasi_email') }}'">Send</button>
        </form>
    </div>
</body>
