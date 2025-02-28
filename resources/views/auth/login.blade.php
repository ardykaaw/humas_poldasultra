<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPIT - Sistem Pengolahan Informasi Terpadu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #0a192f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .matrix-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .login-title {
            color: #64ffda;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(100, 255, 218, 0.3);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #64ffda;
            box-shadow: none;
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #64ffda;
        }

        .btn-login {
            background: #64ffda;
            color: #0a192f;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-login:hover {
            background: #4cd7b0;
            transform: translateY(-2px);
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
            z-index: 1;
        }

        .footer a {
            color: #64ffda;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #4cd7b0;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
        }

        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            color: #64ffda;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #4cd7b0;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-humas {
            width: 120px;
            height: 120px;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 8px rgba(100, 255, 218, 0.3));
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            animation: fadeIn 1s ease-out;
        }
    </style>
</head>
<body>
    <canvas id="matrix" class="matrix-bg"></canvas>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('images/Logo_Humas_Polri.svg.png') }}" alt="Humas Polri" class="logo-humas">
                <h1 class="login-title">HUMAS POLRI</h1>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="email" class="form-control" name="email" placeholder="Username" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">Masuk</button>

                <div class="links">
                    <a href="#">Lupa kata sandi?</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div>Copyright © 2022 CREATIVE TECHNOLOGY - All Rights Reserved</div>
        <div class="mt-2">
            <a href="#">Privacy Policy</a> | <a href="#">Terms and Conditions</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <script>
        // Matrix Rain Animation
        const canvas = document.getElementById('matrix');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*';
        const charSize = 14;
        const columns = canvas.width / charSize;
        const drops = [];

        for (let i = 0; i < columns; i++) {
            drops[i] = 1;
        }

        function draw() {
            ctx.fillStyle = 'rgba(10, 25, 47, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.fillStyle = '#64ffda';
            ctx.font = `${charSize}px monospace`;

            for (let i = 0; i < drops.length; i++) {
                const text = chars[Math.floor(Math.random() * chars.length)];
                ctx.fillText(text, i * charSize, drops[i] * charSize);

                if (drops[i] * charSize > canvas.height && Math.random() > 0.975) {
                    drops[i] = 0;
                }
                drops[i]++;
            }
        }

        setInterval(draw, 35);

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html> 