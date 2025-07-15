<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | SIGAP-IO</title>
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

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-card {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 69, 0, 0.3);
            backdrop-filter: blur(10px);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .error-card::before {
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

        .error-icon {
            font-size: 4rem;
            margin-bottom: 15px;
            animation: shake 1.5s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(-5deg); }
            20%, 40%, 60%, 80% { transform: rotate(5deg); }
        }

        .error-code {
            font-size: 3.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 10px;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-bottom: 5px;
        }

        .card-body {
            padding: 40px 30px;
            color: #fff;
            text-align: center;
        }

        .error-message {
            margin-bottom: 30px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff4500, #ff8c00);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 69, 0, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 69, 0, 0.6);
            background: linear-gradient(135deg, #ff8c00, #ff4500);
        }

        @media (max-width: 576px) {
            .error-card {
                margin: 10px;
                border-radius: 15px;
            }

            .card-header {
                padding: 25px 15px;
            }

            .card-body {
                padding: 30px 20px;
            }

            .error-code {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="card-header">
                <div class="error-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="error-code">403</div>
                <h1 class="error-title">Akses Ditolak</h1>
            </div>
            <div class="card-body">
                <div class="error-message">
                    <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>

                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>