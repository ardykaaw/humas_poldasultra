<x-filament-panels::page.simple>
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

        .fi-simple-layout {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 100vh !important;
            background: #0a192f !important;
            padding: 0 !important;
        }

        .fi-simple-main {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            width: 100%;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 1s ease-out;
            background: transparent;
            padding: 0;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-humas {
            width: 120px;
            height: 120px;
            margin-bottom: 10px;
            filter: drop-shadow(0 0 15px rgba(100, 255, 218, 0.4));
            transition: all 0.3s ease;
        }

        .logo-container:hover .logo-humas {
            transform: scale(1.05);
            filter: drop-shadow(0 0 20px rgba(100, 255, 218, 0.6));
        }

        .login-title {
            color: #64ffda;
            text-align: center;
            margin: 0;
            font-size: 1.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(100, 255, 218, 0.3);
            font-weight: 600;
        }

        .fi-input {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(100, 255, 218, 0.2) !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            color: #64ffda !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            height: 45px !important;
            font-size: 1rem !important;
            letter-spacing: 0.5px !important;
        }

        .fi-input:focus {
            background: rgba(0, 0, 0, 0.3) !important;
            border-color: #64ffda !important;
            box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.2) !important;
            color: #fff !important;
        }

        .fi-input::placeholder {
            color: rgba(100, 255, 218, 0.5) !important;
            opacity: 1 !important;
        }

        /* Tambahkan style untuk autofill */
        .fi-input:-webkit-autofill,
        .fi-input:-webkit-autofill:hover,
        .fi-input:-webkit-autofill:focus {
            -webkit-text-fill-color: #64ffda !important;
            -webkit-box-shadow: 0 0 0px 1000px rgba(0, 0, 0, 0.2) inset !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        .fi-input:focus:-webkit-autofill {
            -webkit-text-fill-color: #fff !important;
        }

        .fi-btn {
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
            margin-top: 30px !important;
            height: 45px !important;
            font-size: 1rem !important;
        }

        .fi-btn:hover {
            background: #4cd7b0 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(100, 255, 218, 0.2) !important;
        }

        .fi-label {
            color: rgba(255, 255, 255, 0.9) !important;
            margin-bottom: 8px !important;
            display: block !important;
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            letter-spacing: 0.5px !important;
        }

        .fi-checkbox-label {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 0.9rem !important;
        }

        .fi-checkbox {
            border-color: rgba(255, 255, 255, 0.2) !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        .fi-checkbox:checked {
            background-color: #64ffda !important;
            border-color: #64ffda !important;
        }

        .fi-form-component {
            margin-bottom: 25px !important;
        }

        .fi-input-wrp {
            margin-bottom: 0 !important;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
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

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .fi-input-error {
            color: #ff6b6b !important;
            font-size: 0.8rem !important;
            margin-top: 4px !important;
        }

        /* Hide default Filament header */
        .fi-simple-header {
            display: none !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div>
        <canvas id="matrix" class="matrix-bg"></canvas>

        <div class="login-container">
            <div class="login-card">
                <div class="logo-container">
                    <img src="{{ asset('images/Logo_Humas_Polri.svg.png') }}" alt="Logo" class="logo-humas">
                    <h1 class="login-title">HUMAS POLRI</h1>
                </div>

                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament::button type="submit" class="fi-btn w-full">
                        Masuk
                    </x-filament::button>

                    <div class="links">
                        <a href="#">Lupa kata sandi?</a>
                    </div>
                </x-filament-panels::form>
            </div>
        </div>

        <footer class="footer">
            <div>Copyright © 2022 CREATIVE TECHNOLOGY - All Rights Reserved</div>
            <div class="mt-2">
                <a href="#">Privacy Policy</a> | <a href="#">Terms and Conditions</a>
            </div>
        </footer>

        <script>
            // Matrix Rain Animation
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('matrix');
                const ctx = canvas.getContext('2d');

                function resizeCanvas() {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                }

                resizeCanvas();

                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*';
                const charSize = 14;
                let columns = Math.floor(canvas.width / charSize);
                let drops = Array(columns).fill(1);

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

                const animation = setInterval(draw, 35);

                window.addEventListener('resize', () => {
                    resizeCanvas();
                    columns = Math.floor(canvas.width / charSize);
                    drops = Array(columns).fill(1);
                });
            });
        </script>
    </div>
</x-filament-panels::page.simple>
