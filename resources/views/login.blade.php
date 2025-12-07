<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengajuan Ruangan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            height: 100vh;
            position: relative;
            overflow: hidden;
            background: #0f172a;
        }
        
        /* Particle System Container */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        
        /* Floating Geometric Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: 2;
            mix-blend-mode: screen;
            animation: floatShape 15s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.7) 0%, rgba(30, 58, 138, 0.3) 70%);
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.6) 0%, rgba(79, 70, 229, 0.2) 70%);
            bottom: 15%;
            right: 10%;
            animation-delay: -5s;
        }
        
        .shape-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.6) 0%, rgba(23, 113, 154, 0.2) 70%);
            top: 60%;
            left: 20%;
            animation-delay: -10s;
        }
        
        .shape-4 {
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.5) 0%, rgba(5, 105, 80, 0.1) 70%);
            bottom: 30%;
            left: 5%;
            animation-delay: -15s;
        }
        
        @keyframes floatShape {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(30px, -20px) rotate(90deg);
            }
            50% {
                transform: translate(-20px, 40px) rotate(180deg);
            }
            75% {
                transform: translate(10px, -30px) rotate(270deg);
            }
        }
        
        /* Pulsing Rings */
        .ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.1);
            animation: pulse 4s infinite;
            z-index: 3;
        }
        
        .ring-1 {
            width: 600px;
            height: 600px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 0s;
        }
        
        .ring-2 {
            width: 800px;
            height: 800px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 1s;
        }
        
        .ring-3 {
            width: 1000px;
            height: 1000px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 2s;
        }
        
        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0;
            }
        }
        
        /* Floating Stars */
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s infinite ease-in-out;
        }
        
        .star-1 {
            width: 3px;
            height: 3px;
            top: 15%;
            left: 25%;
            animation-delay: 0s;
        }
        
        .star-2 {
            width: 2px;
            height: 2px;
            top: 35%;
            right: 15%;
            animation-delay: 0.5s;
        }
        
        .star-3 {
            width: 4px;
            height: 4px;
            bottom: 20%;
            left: 40%;
            animation-delay: 1s;
        }
        
        .star-4 {
            width: 2px;
            height: 2px;
            bottom: 40%;
            right: 30%;
            animation-delay: 1.5s;
        }
        
        .star-5 {
            width: 3px;
            height: 3px;
            top: 60%;
            left: 10%;
            animation-delay: 2s;
        }
        
        @keyframes twinkle {
            0%, 100% {
                opacity: 0.2;
                transform: scale(0.8);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }
        
        /* Glowing Portal Effect */
        .portal {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, rgba(15, 23, 42, 0) 70%);
            z-index: 3;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulsePortal 8s infinite ease-in-out;
        }
        
        .portal::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, rgba(15, 23, 42, 0) 70%);
            animation: rotate 20s linear infinite;
        }
        
        .portal::after {
            content: '';
            position: absolute;
            width: 80%;
            height: 80%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.4) 0%, rgba(15, 23, 42, 0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: rotate 15s linear infinite reverse;
        }
        
        @keyframes pulsePortal {
            0%, 100% {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.5),
                            0 0 60px rgba(59, 130, 246, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.8),
                            0 0 80px rgba(59, 130, 246, 0.5);
            }
        }
        
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        /* Glassmorphism overlay for depth */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(15, 23, 42, 0.7) 0%, rgba(15, 23, 42, 0.95) 100%);
            z-index: 4;
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                0 0 32px rgba(59, 130, 246, 0.2),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(24px);
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.1) 0%,
                rgba(255, 255, 255, 0.05) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(45deg);
            animation: shine 15s infinite linear;
            z-index: -1;
        }
        
        @keyframes shine {
            0% {
                background-position: -100% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
        
        .login-card:hover {
            box-shadow: 
                0 12px 48px rgba(0, 0, 0, 0.4),
                0 0 40px rgba(59, 130, 246, 0.4),
                inset 0 0 0 1px rgba(255, 255, 255, 0.2);
            transform: translateY(-4px);
        }
        
        .login-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(
                90deg,
                #1e3a8a 0%,
                #3b82f6 15%,
                #8b5cf6 30%,
                #06b6d4 45%,
                #10b981 60%,
                #f59e0b 75%,
                #ef4444 90%,
                #1e3a8a 100%
            );
            background-size: 300% 100%;
            animation: dynamicGradientFlow 8s linear infinite;
            box-shadow: 0 2px 10px rgba(59, 130, 246, 0.5);
        }
        
        @keyframes dynamicGradientFlow {
            0% {
                background-position: 0% 50%;
                filter: hue-rotate(0deg) brightness(1.2);
            }
            50% {
                background-position: 100% 50%;
                filter: hue-rotate(45deg) brightness(1.4);
            }
            100% {
                background-position: 200% 50%;
                filter: hue-rotate(0deg) brightness(1.2);
            }
        }
        
        .logo {
            width: 80px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
            border-radius: 16px;
            box-shadow: 
                0 4px 15px rgba(0, 0, 0, 0.2),
                0 0 20px rgba(59, 130, 246, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }
        
        .login-card h2 {
            color: white;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 1.8rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .login-card p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 30px;
            line-height: 1.5;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .login-card label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            text-align: left;
            position: relative;
        }
        
        .login-card label::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(15, 23, 42, 0.7);
            font-size: 16px;
            transition: var(--transition);
            z-index: 5;
        }
        
        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 14px 15px 14px 42px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.08);
            transition: var(--transition);
            color: white;
            backdrop-filter: blur(8px);
        }
        
        .login-card input[type="email"]::placeholder,
        .login-card input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .login-card input[type="email"]:focus,
        .login-card input[type="password"]:focus {
            outline: none;
            border-color: rgba(59, 130, 246, 0.5);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            color: white;
        }
        
        .login-card input[type="email"]:focus ~ i,
        .login-card input[type="password"]:focus ~ i {
            color: #1e3a8a;
        }
        
        .forgot-password {
            display: block;
            text-align: center;
            font-size: 13px;
            margin-top: -12px;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            z-index: 5;
        }
        
        .forgot-password::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .forgot-password:hover {
            color: white;
            text-decoration: none;
        }
        
        .forgot-password:hover::after {
            transform: scaleX(1);
        }
        
        /* Enhanced notification styles */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .notification {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 16px 20px;
            border-radius: var(--border-radius);
            box-shadow: 
                0 4px 12px rgba(0, 0, 0, 0.15),
                0 0 15px rgba(0, 0, 0, 0.08);
            color: white;
            min-width: 300px;
            transform: translateX(110%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.hide {
            transform: translateX(110%);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        
        .notification-icon {
            font-size: 20px;
            min-width: 20px;
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 700;
            margin-bottom: 3px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .notification-message {
            font-size: 14px;
            opacity: 0.95;
            line-height: 1.5;
        }
        
        .notification-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .notification-close:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1);
        }
        
        .notification-close i {
            font-size: 12px;
            font-weight: bold;
        }
        
        .notification.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 105, 80, 0.9) 100%);
            border-left: 4px solid #10b981;
        }
        
        .notification.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(153, 27, 27, 0.9) 100%);
            border-left: 4px solid #ef4444;
        }
        
        .notification.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.95) 0%, rgba(161, 98, 7, 0.9) 100%);
            border-left: 4px solid #f59e0b;
        }
        
        .notification.info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.95) 0%, rgba(29, 78, 216, 0.9) 100%);
            border-left: 4px solid #3b82f6;
        }
        
        .login-card button {
            width: 100%;
            background: linear-gradient(90deg, rgba(30, 58, 138, 0.9) 0%, rgba(59, 130, 246, 0.9) 100%);
            color: white;
            border: none;
            padding: 14px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 
                0 4px 15px rgba(30, 58, 138, 0.4),
                0 0 25px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-card button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(45deg);
            transition: transform 0.5s ease;
        }
        
        .login-card button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 6px 20px rgba(30, 58, 138, 0.5),
                0 0 30px rgba(59, 130, 246, 0.4);
        }
        
        .login-card button:hover::before {
            transform: rotate(45deg) translateX(50%);
        }
        
        .login-card button:active {
            transform: translateY(0);
        }
        
        .login-card button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .login-card button .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        .login-card button.loading .spinner {
            display: inline-block;
        }
        
        .login-card button.loading span {
            opacity: 0;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .notification-container {
                top: 70px;
                right: 15px;
                left: 15px;
                max-width: calc(100% - 30px);
            }
            
            .notification {
                min-width: auto;
                width: 100%;
            }
            
            .login-container {
                padding: 15px;
            }
            
            .login-card {
                padding: 25px 20px;
                border-radius: 16px;
            }
            
            .logo {
                width: 60px;
                margin-bottom: 15px;
            }
        }
        
        /* Add animation for the rings */
        @keyframes pulsing {
            0% {
                transform: translate(-50%, -50%) scale(0.95);
                opacity: 0.7;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.05);
                opacity: 0.9;
            }
            100% {
                transform: translate(-50%, -50%) scale(0.95);
                opacity: 0.7;
            }
        }
        
        /* Enhance the shape animations */
        .shape {
            animation: floatShape 20s infinite ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Particle System -->
    <div id="particles-js"></div>
    
    <!-- Floating Geometric Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
    
    <!-- Pulsing Rings -->
    <div class="ring ring-1"></div>
    <div class="ring ring-2"></div>
    <div class="ring ring-3"></div>
    
    <!-- Floating Stars -->
    <div class="star star-1"></div>
    <div class="star star-2"></div>
    <div class="star star-3"></div>
    <div class="star star-4"></div>
    <div class="star star-5"></div>
    
    <!-- Glowing Portal -->
    <div class="portal"></div>
    
    <!-- Glassmorphism Overlay -->
    <div class="overlay"></div>
    
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <div class="login-container">
        <div class="login-card">
            <img src="{{ asset('images/logoipsum.png') }}" alt="Logo BKD Probolinggo" class="logo">
            <h2>Selamat Datang!</h2>
            <p>Silahkan masuk untuk melanjutkan <strong>Pengelolaan dan Pengajuan Ruangan</strong> rapat Anda!</p>

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="input-group">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Masukkan email Anda" 
                        required
                    >
                    <i class="fas fa-envelope"></i>
                </div>

                <div class="input-group">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password Anda" 
                        autocomplete="off" 
                        required
                    >
                    <i class="fas fa-lock"></i>
                </div>

                <a href="#" class="forgot-password">Lupa password?</a>

                <button type="submit" id="loginButton">
                    <span class="spinner"></span>
                    <span>Login!</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Initialize particles.js
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                particles: {
                    number: {
                        value: 100,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: '#ffffff'
                    },
                    shape: {
                        type: 'circle',
                        stroke: {
                            width: 0,
                            color: '#000000'
                        },
                        polygon: {
                            nb_sides: 5
                        },
                        image: {
                            src: 'img/github.svg',
                            width: 100,
                            height: 100
                        }
                    },
                    opacity: {
                        value: 0.15,
                        random: true,
                        anim: {
                            enable: false,
                            speed: 1,
                            opacity_min: 0.1,
                            sync: false
                        }
                    },
                    size: {
                        value: 3,
                        random: true,
                        anim: {
                            enable: false,
                            speed: 40,
                            size_min: 0.1,
                            sync: false
                        }
                    },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: '#5e72e4',
                        opacity: 0.1,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: 'none',
                        random: false,
                        straight: false,
                        out_mode: 'out',
                        bounce: false,
                        attract: {
                            enable: false,
                            rotateX: 600,
                            rotateY: 1200
                        }
                    }
                },
                interactivity: {
                    detect_on: 'canvas',
                    events: {
                        onhover: {
                            enable: true,
                            mode: 'repulse'
                        },
                        onclick: {
                            enable: true,
                            mode: 'push'
                        },
                        resize: true
                    },
                    modes: {
                        grab: {
                            distance: 400,
                            line_linked: {
                                opacity: 0.5
                            }
                        },
                        bubble: {
                            distance: 400,
                            size: 40,
                            duration: 2,
                            opacity: 0.8,
                            speed: 3
                        },
                        repulse: {
                            distance: 100,
                            duration: 0.4
                        },
                        push: {
                            particles_nb: 4
                        },
                        remove: {
                            particles_nb: 2
                        }
                    }
                },
                retina_detect: true
            });
        });

        // Notification system
        class NotificationSystem {
            constructor(containerId) {
                this.container = document.getElementById(containerId);
                this.notifications = [];
            }
            
            show(type, title, message, duration = 3000) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification ${type} show`;
                notification.innerHTML = `
                    <div class="notification-icon">
                        <i class="fas ${this.getIcon(type)}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${title}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <button class="notification-close" aria-label="Close notification">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                // Add to container
                this.container.appendChild(notification);
                
                // Add to tracking array
                const notificationId = Date.now();
                this.notifications.push({
                    id: notificationId,
                    element: notification,
                    timeout: setTimeout(() => this.remove(notificationId), duration)
                });
                
                // Setup close button
                notification.querySelector('.notification-close').addEventListener('click', () => {
                    this.remove(notificationId);
                });
                
                return notificationId;
            }
            
            getIcon(type) {
                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };
                return icons[type] || 'fa-bell';
            }
            
            remove(notificationId) {
                const index = this.notifications.findIndex(n => n.id === notificationId);
                if (index !== -1) {
                    const notification = this.notifications[index];
                    
                    // Clear timeout
                    clearTimeout(notification.timeout);
                    
                    // Animate removal
                    notification.element.classList.add('hide');
                    setTimeout(() => {
                        if (notification.element.parentNode) {
                            notification.element.parentNode.removeChild(notification.element);
                        }
                    }, 300);
                    
                    // Remove from array
                    this.notifications.splice(index, 1);
                }
            }
        }

        // Initialize notification system
        document.addEventListener('DOMContentLoaded', function() {
            const notificationSystem = new NotificationSystem('notificationContainer');
            
            // Show Laravel session messages
            @if(session('success'))
                notificationSystem.show('success', 'Berhasil!', '{{ session('success') }}');
            @endif
            
            @if(session('error'))
                notificationSystem.show('error', 'Gagal!', '{{ session('error') }}');
            @endif
            
            // Handle form submission
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            loginForm.addEventListener('submit', function(e) {
                // Basic validation feedback
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!email) {
                    e.preventDefault();
                    notificationSystem.show('error', 'Email Kosong', 'Silahkan masukkan alamat email Anda.');
                    return;
                }
                
                if (!password) {
                    e.preventDefault();
                    notificationSystem.show('error', 'Password Kosong', 'Silahkan masukkan password Anda.');
                    return;
                }
                
                // Add loading state
                loginButton.classList.add('loading');
                loginButton.disabled = true;
            });
            
            // Show validation errors as notifications
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    notificationSystem.show('error', 'Validasi Gagal', '{{ $error }}');
                @endforeach
            @endif
        });
    </script>
</body>
</html>