<?php ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
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
        .card {
            background: #fff;
            padding: 40px;
            border-radius: 6px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
            width: 300px;
            text-align: center;
        }

        .card h2{
            color: #1D64F2;
            margin-bottom: 5px;
        }
        .info{
            font-size: 12px;
            color: #1D64F2;
            margin-bottom: 50px;
        }
        .info span{
        color: #000000;
        }
        .code-inputs {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 25px;
        }

        .code-inputs input {
        width: 30px;
        height: 30px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 20px;
        text-align: center;
        outline: none;
        transition: border 0.2s;
        }

        .code-inputs input:focus {
        border: 2px solid #1D64F2;
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
        .resend {
        font-size: 12px;
        margin-top: 15px;
        color: #555;
        }

        .resend a {
        color: #1D64F2;
        text-decoration: none;
        }

    </style>
</head>
<body>
    <div class="card">
        <a href="{{ url('/forgotpassword') }}" class="back" >
            <span class="arrow">&lt;</span>
            <span>Back</span>
        </a>
        <h2>Verify email address</h2>
        <p class="info">Verification code sent to <span>milindasasmita@gmailcom</span></p>
        <form>
             <div class="code-inputs">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
            </div>
        <button class="btn" type="submit">Confirm Password</button>
        <p class="resend">00:29 <a href="#">Resend Confirmation Code</a></p>
        </form>
    </div>
</body>
