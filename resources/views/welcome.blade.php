<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Telemetri Keselamatan dan Pemantauan Petugas Pemadam Kebakaran Hutan Kabupaten Ketapang Berbasis IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ url('public/komando/assets/img/favicon.png') }}">
    <style>
        :root {
            --primary-color: #ff4500;
            --secondary-color: #ff8c00;
            --dark-color: #2f1b14;
            --light-color: #fff8f0;
            --accent-color: #dc3545;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fff 100%);
            color: #333;
            line-height: 1.6;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.95) 0%, rgba(139, 0, 0, 0.95) 100%), url('public/komando/assets/img/forest-fire.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(255, 69, 0, 0.5) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 140, 0, 0.5) 0%, transparent 50%);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-content h1 {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar {
            background-color: rgba(47, 27, 20, 0.95) !important;
            transition: background-color 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 69, 0, 0.3);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        .feature-box {
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(255, 69, 0, 0.2);
        }

        .feature-icon {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
            font-weight: 700;
            color: var(--dark-color);
            text-align: center;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 5px;
        }

        .how-it-works-step {
            text-align: center;
            padding: 20px;
        }

        .step-number {
            display: inline-block;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .contact-form .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: border-color 0.3s ease;
        }

        .contact-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(255, 69, 0, 0.2);
        }

        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 20px;
        }

        .social-icons a {
            display: inline-block;
            width: 45px;
            height: 45px;
            line-height: 45px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 12px;
            color: white;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 12px;
        }

        .footer-links a:hover {
            color: var(--secondary-color);
            transform: translateX(5px);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .feature-box {
                margin-bottom: 30px;
            }

            .step-number {
                width: 50px;
                height: 50px;
                line-height: 50px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-fire me-2"></i>Telemetri Ketapang
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cara-kerja">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda" data-aos="fade-up">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Sistem Telemetri Keselamatan dan Pemantauan Petugas Pemadam Kebakaran Hutan Kabupaten Ketapang Berbasis IoT</h1>
                    <p class="lead mb-4">Sistem berbasis IoT dan LoRa untuk memantau kondisi lingkungan dan lokasi petugas secara real-time, meningkatkan keselamatan dan efisiensi penanganan kebakaran hutan di Ketapang.</p>
                    <div class="d-flex gap-3">
                        <a href="#fitur" class="btn btn-primary btn-lg">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-6 shadow bg-white px-2 py-2 border border-light rounded-3" data-aos="fade-left">
                    <img src="{{ url('public/komando/assets/img/dashboard.png') }}" alt="Dashboard Preview" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 my-5" id="fitur">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Fitur Utama</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3>Pemantauan Real-time</h3>
                        <p>Lacak lokasi petugas dengan GPS Neo-6M, suhu dan tekanan dengan BMP280, serta kualitas udara dengan MQ-135 melalui peta interaktif di Kabupaten Ketapang.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Peringatan Darurat</h3>
                        <p>Tombol darurat dan buzzer diaktifkan saat kondisi berbahaya terdeteksi, dengan data dikirim via LoRa ke pusat komando untuk respons cepat.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Koordinasi Petugas</h3>
                        <p>Pusat komando memantau lokasi dan status petugas secara real-time untuk mengelola insiden kebakaran dengan efisien.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-light" id="cara-kerja">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Cara Kerja Sistem</h2>
            <div class="row">
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="how-it-works-step">
                        <div class="step-number">1</div>
                        <h4>Deteksi Kondisi</h4>
                        <p>Sensor BMP280, MQ-135, dan GPS Neo-6M memantau suhu, tekanan, kualitas udara, dan lokasi petugas untuk mendeteksi potensi bahaya.</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="how-it-works-step">
                        <div class="step-number">2</div>
                        <h4>Notifikasi Darurat</h4>
                        <p>Data dikirim melalui LoRa ke pusat komando, dengan peringatan instan jika tombol darurat ditekan atau kondisi berbahaya terdeteksi.</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="how-it-works-step">
                        <div class="step-number">3</div>
                        <h4>Pemantauan Petugas</h4>
                        <p>Dashboard web berbasis Laravel dan Leaflet.js menampilkan data real-time untuk koordinasi optimal di Kabupaten Ketapang.</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-aos-delay="400">
                    <div class="how-it-works-step">
                        <div class="step-number">4</div>
                        <h4>Analisis Data</h4>
                        <p>Data telemetri digunakan untuk evaluasi dan pengambilan keputusan guna meningkatkan strategi penanganan kebakaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Manfaat</h2>
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="ps-lg-4 mt-4 mt-lg-0">
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                            <div>
                                <h4>Respons Cepat</h4>
                                <p>Minimalkan waktu respons terhadap kebakaran hutan di Ketapang dengan pemantauan real-time.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="ps-lg-4 mt-4 mt-lg-0">
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                            <div>
                                <h4>Keselamatan Petugas</h4>
                                <p>Pantau kondisi lingkungan dan lokasi petugas untuk memastikan keselamatan mereka di lapangan.</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                            <div>
                                <h4>Data Strategis</h4>
                                <p>Gunakan data untuk evaluasi, pengambilan keputusan, dan perencanaan pencegahan kebakaran yang lebih baik.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5" id="kontak">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Hubungi Kami</h2>
            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="contact-info mb-5 mb-lg-0">
                        <h3 class="mb-4">Informasi Kontak</h3>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h5>Alamat</h5>
                                <p>Politeknik Negeri Ketapang, Jl. Ahmad Yani, Ketapang, Kalimantan Barat, Indonesia</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-phone text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h5>Telepon</h5>
                                <p>+62 812 3456 7890</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-envelope text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h5>Email</h5>
                                <p>info@telemetri-ketapang.id</p>
                            </div>
                        </div>
                        <div class="social-icons mt-4">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="contact-form">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Masukkan email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input type="text" class="form-control" id="subject" placeholder="Masukkan subjek" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Masukkan pesan" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h3 class="mb-4"><i class="fas fa-fire me-2"></i>Telemetri Ketapang</h3>
                    <p>Sistem Telemetri Keselamatan dan Pemantauan Petugas Pemadam Kebakaran Hutan Kabupaten Ketapang Berbasis IoT untuk keselamatan petugas dan penanganan kebakaran yang efektif.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Tautan Cepat</h5>
                    <div class="footer-links">
                        <a href="#beranda">Beranda</a>
                        <a href="#fitur">Fitur</a>
                        <a href="#cara-kerja">Cara Kerja</a>
                        <a href="#kontak">Kontak</a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Layanan</h5>
                    <div class="footer-links">
                        <a href="#">Pemantauan</a>
                        <a href="#">Pelatihan</a>
                        <a href="#">Konsultasi</a>
                        <a href="#">Dukungan</a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">© 2025 Telemetri Ketapang. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white me-3">Kebijakan Privasi</a>
                    <a href="#" class="text-white">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Change navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(47, 27, 20, 0.98)';
                navbar.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.2)';
            } else {
                navbar.style.background = 'rgba(47, 27, 20, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });

        // Basic form validation
        document.querySelector('.contact-form form').addEventListener('submit', function(e) {
            e.preventDefault();
            const inputs = this.querySelectorAll('input[required], textarea[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = 'var(--accent-color)';
                    valid = false;
                } else {
                    input.style.borderColor = '#ddd';
                }
            });
            if (valid) {
                alert('Pesan berhasil dikirim!');
                this.reset();
            } else {
                alert('Harap isi semua kolom yang diperlukan.');
            }
        });
    </script>
</body>
</html>
