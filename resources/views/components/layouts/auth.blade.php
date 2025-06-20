<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pemadam Kebakaran Hutan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff4500 0%, #8b0000 50%, #2f1b14 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255, 69, 0, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 140, 0, 0.3) 0%, transparent 50%);
            z-index: -1;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 69, 0, 0.3);
            backdrop-filter: blur(10px);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #ff4500, #ff8c00, #ff4500);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { box-shadow: 0 0 5px rgba(255, 69, 0, 0.5); }
            to { box-shadow: 0 0 20px rgba(255, 69, 0, 0.8); }
        }

        .card-header {
            background: linear-gradient(135deg, #ff4500, #ff6347);
            color: white;
            text-align: center;
            padding: 30px 20px;
            border: none;
            position: relative;
        }

        .fire-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            animation: flicker 1.5s ease-in-out infinite alternate;
        }

        @keyframes flicker {
            0%, 100% { text-shadow: 0 0 10px #ff4500, 0 0 20px #ff4500; }
            50% { text-shadow: 0 0 5px #ff6347, 0 0 15px #ff6347; }
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 5px;
        }

        .card-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .card-body {
            padding: 40px 30px;
        }

        .form-label {
            color: #ff8c00;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 140, 0, 0.3);
            border-radius: 10px;
            color: white;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ff4500;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.3);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-group-text {
            background: rgba(255, 140, 0, 0.2);
            border: 2px solid rgba(255, 140, 0, 0.3);
            color: #ff8c00;
            border-right: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #ff4500, #ff6347);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #ff6347, #ff4500);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 69, 0, 0.4);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .emergency-badge {
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .smoke-effect {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: linear-gradient(0deg, rgba(128, 128, 128, 0.1), transparent);
            pointer-events: none;
        }

        .form-check-input {
            background-color: rgba(255, 140, 0, 0.2);
            border: 2px solid rgba(255, 140, 0, 0.5);
        }

        .form-check-input:checked {
            background-color: #ff8c00;
            border-color: #ff8c00;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 140, 0, 0.25);
        }

        .text-danger {
            color: #ff6b6b !important;
            font-weight: 500;
        }
            .login-card {
                margin: 10px;
                border-radius: 15px;
            }

            .card-header {
                padding: 25px 15px;
            }

            .card-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
       {{ $slot }}
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Add focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>

    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
</body>
</html>
