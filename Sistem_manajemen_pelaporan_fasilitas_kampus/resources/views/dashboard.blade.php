<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIXIT - Sistem Pelaporan Fasilitas Kampus</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        :root {
            --primary: #435ebe;
            --secondary: #57caeb;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background-color: #f5f7fb;
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
            background-size: cover;
            opacity: 0.1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Features */
        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            font-size: 2rem;
        }

        /* How It Works */
        .step-container {
            position: relative;
            padding-left: 60px;
        }

        .step-item {
            position: relative;
            padding-bottom: 30px;
        }

        .step-item:last-child {
            padding-bottom: 0;
        }

        .step-number {
            position: absolute;
            left: -60px;
            top: 0;
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: -40px;
            top: 40px;
            width: 2px;
            height: calc(100% - 40px);
            background: #dee2e6;
        }

        /* Stats */
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Animations */
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }

        .bounce-animation {
            animation: bounce 2s ease infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Section Title */
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 2rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }

        /* Footer */
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .step-container {
                padding-left: 40px;
            }

            .step-number {
                left: -40px;
                width: 30px;
                height: 30px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a href="logo">
                <img src="{{ asset('mazer/dist/asset/logoSistem.png')}}" alt="Logo" style="width: 90px; height: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistik</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ url('/login') }}" class="btn btn-primary">Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title animate__animated animate__fadeInDown">FIXIT</h1>
                    <h2 class="hero-subtitle animate__animated animate__fadeInDown animate__delay-1s">
                        Sistem Pelaporan dan Perbaikan Fasilitas Kampus
                    </h2>
                    <p class="lead mb-4 animate__animated animate__fadeIn animate__delay-2s">
                        Platform terintegrasi untuk melaporkan kerusakan fasilitas kampus
                        dan memantau proses perbaikannya secara real-time.
                    </p>
                    <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-3s">
                        <a href="{{ url('/pelapor/laporan') }}"
                            class="btn btn-light btn-lg shadow-sm animate__animated animate__pulse animate__infinite animate__slower">
                            <i class="bi bi-plus-circle me-2"></i>Buat Laporan Baru
                        </a>

                    </div>
                </div>

                <!-- Kolom logo -->
                <div
                    class="col-lg-6 d-none d-lg-flex justify-content-center animate__animated animate__fadeInRight animate__delay-1s">
                    <img src="{{ asset('storage/dashboard/polinema_logo.png') }}" alt="Campus Repair"
                        class="img-fluid floating-animation" style="width: 400px; height: auto;">
                </div>
            </div>
        </div>
    </section>


    <!-- How It Works -->
    <section class="py-5 bg-light" id="how-it-works">
        <div class="container">
            <h2 class="section-title text-center animate__animated animate__fadeInUp">Bagaimana Cara Kerjanya?</h2>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="step-container animate__animated animate__fadeInLeft">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h5>Laporkan Kerusakan</h5>
                                <p>Isi formulir pelaporan dengan detail kerusakan yang ditemukan.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h5>Verifikasi Admin</h5>
                                <p>Tim admin akan memverifikasi dan memprioritaskan laporan Anda.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h5>Penugasan Teknisi</h5>
                                <p>Teknisi akan ditugaskan untuk menangani perbaikan.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h5>Perbaikan & Selesai</h5>
                                <p>Proses perbaikan dilakukan dan Anda akan mendapatkan notifikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gambar di sebelah kanan -->
                <div class="col-lg-6 d-none d-lg-block text-right"> <!-- Gunakan text-right atau text-end -->
                    <img src="{{ asset('storage/dashboard/workflow.jpg') }}" alt="workflow" class="img-fluid float-end"
                        style="max-width: 600px; height: auto;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" id="stats">
        <div class="container">
            <h2 class="section-title text-center animate__animated animate__fadeInUp">Sistem Kami dalam Angka</h2>
            <div class="row g-4">
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp">
                    <div class="card stat-card h-100">
                        <div class="card-body text-center p-3">
                            <h2 class="text-primary counter" data-target="{{$stats['laporan_diterima']}}">0</h2>
                            <p class="mb-0 text-muted">Laporan Diterima</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="card stat-card h-100">
                        <div class="card-body text-center p-3">
                            <h2 class="text-success counter" data-target="{{$stats['laporan_selesai']}}">0</h2>
                            <p class="mb-0 text-muted">Laporan Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="card stat-card h-100">
                        <div class="card-body text-center p-3">
                            <h2 class="text-warning counter" data-target="{{$stats['teknisi_aktif']}}">0</h2>
                            <p class="mb-0 text-muted">Teknisi Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="card stat-card h-100">
                        <div class="card-body text-center p-3">
                            <h2 class="text-info counter" data-target="{{$stats['total_pengguna']}}">0</h2>
                            <p class="mb-0 text-muted">Jumlah Pengguna</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4 animate__animated animate__fadeInUp">Siap Melaporkan Kerusakan Fasilitas?</h2>
            <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">Bergabung dengan ratusan pengguna
                yang telah memperbaiki fasilitas kampus bersama kami</p>
            <a href="{{ url('/pelapor/laporan') }}"
                class="btn btn-light btn-lg animate__animated animate__fadeInUp animate__delay-2s">
                <i class="bi bi-plus-circle me-2"></i>Buat Laporan Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="mb-3"><i class="bi bi-tools me-2"></i>FIXIT</h5>
                    <p>Sistem pelaporan dan perbaikan fasilitas kampus terintegrasi untuk menciptakan lingkungan kampus
                        yang lebih baik.</p>
                </div>
                <div class="col-lg-2 col-6 mb-4 mb-lg-0">
                    <h5 class="mb-3">Tautan</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="#features">Fitur</a></li>
                        <li class="mb-2"><a href="#how-it-works">Cara Kerja</a></li>
                        <li class="mb-2"><a href="#stats">Statistik</a></li>
                        <li class="mb-2"><a href="{{ url('/login') }}">Masuk</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6 mb-4 mb-lg-0">
                    <h5 class="mb-3">Kontak</h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> fixit@kampus.ac.id</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> (0341) 404424</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jl. Soekarno-Hatta No. 9</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-3">Sosial Media</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook" style="font-size: 1.5rem;"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-twitter" style="font-size: 1.5rem;"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram" style="font-size: 1.5rem;"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin" style="font-size: 1.5rem;"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">© 2023 FIXIT. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> for better campus</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Counter Up -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script>
        $(document).ready(function () {
            // Counter animation
            $('.counter').counterUp({
                delay: 10,
                time: 1000
            });

            document.querySelectorAll('.counter').forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let count = 0;
                const interval = setInterval(() => {
                    if (count >= target) {
                        clearInterval(interval);
                    }
                    counter.textContent = count++;
                }, 30);
            });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function (event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: $($(this).attr('href')).offset().top - 70
                }, 800);
            });

            // Add animation class on scroll
            $(window).scroll(function () {
                $('.animate-on-scroll').each(function () {
                    var position = $(this).offset().top;
                    var scroll = $(window).scrollTop();
                    var windowHeight = $(window).height();

                    if (scroll > position - windowHeight + 200) {
                        $(this).addClass('animate__fadeInUp');
                    }
                });
            });
        });
    </script>
</body>

</html>