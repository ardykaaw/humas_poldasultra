<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Absensi') }}</title>

        <style>
            body {
                margin: 0;
                padding: 0;
                background: #0a192f;
                min-height: 100vh;
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
                background: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 8px !important;
                padding: 12px !important;
                color: #fff !important;
                transition: all 0.3s ease !important;
                width: 100%;
                margin-bottom: 1rem;
            }

            .form-control:focus {
                background: rgba(255, 255, 255, 0.1) !important;
                border-color: #64ffda !important;
                box-shadow: none !important;
            }

            .btn-login {
                background: #64ffda !important;
                color: #0a192f !important;
                padding: 12px !important;
                border-radius: 8px !important;
                width: 100% !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                letter-spacing: 1px !important;
                transition: all 0.3s ease !important;
                border: none !important;
                cursor: pointer;
            }

            .btn-login:hover {
                background: #4cd7b0 !important;
                transform: translateY(-2px) !important;
            }

            .logo-container {
                text-align: center;
                margin-bottom: 20px;
                animation: fadeIn 1s ease-out;
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

            .remember-me {
                color: #fff;
                margin: 1rem 0;
            }

            label {
                color: #fff;
                display: block;
                margin-bottom: 0.5rem;
            }

            .filament-form-component {
                margin-bottom: 1rem;
            }
        </style>
    </head>
    <body>
        <canvas id="matrix" class="matrix-bg"></canvas>

        <div class="login-container">
            <div class="login-card">
                <div class="logo-container">
                    <img src="{{ asset('images/Logo_Humas_Polri.svg.png') }}" alt="Logo" class="logo-humas">
                    <h1 class="login-title">HUMAS POLRI</h1>
                </div>

                <form wire:submit.prevent="authenticate" class="space-y-8">
                    {{ $this->form }}

                    <button type="submit" class="btn-login">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

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

        @livewireScripts
        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html> 