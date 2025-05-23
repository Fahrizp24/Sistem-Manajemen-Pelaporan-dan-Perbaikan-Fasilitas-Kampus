@extends('layouts.template')

@section('content')
<div class="page-content">
    <section class="row">
        <!-- Hero Section -->
        <div class="col-12">
            <div class="card animate__animated animate__fadeIn">
                <div class="card-body py-4 px-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-4 fw-bold text-primary mb-3">FIXIT</h1>
                            <h3 class="text-muted mb-4">Sistem Pelaporan dan Perbaikan Fasilitas Kampus</h3>
                            <p class="lead mb-4">
                                Platform terintegrasi untuk melaporkan kerusakan fasilitas kampus 
                                dan memantau proses perbaikannya secara real-time.
                            </p>
                            <div class="d-flex gap-3">
                                <a href="{{ url('/pelapor/laporan') }}" class="btn btn-primary btn-lg shadow-sm animate__animated animate__pulse animate__infinite animate__slower">
                                    <i class="bi bi-plus-circle me-2"></i>Buat Laporan Baru
                                </a>
                                <a href="{{ url('/pelapor/laporan_saya') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-list-check me-2"></i>Lihat Laporan Saya
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 d-none d-md-block">
                            <img src="{{ asset('mazer/dist/asset/campus-repair.svg') }}" alt="Campus Repair" class="img-fluid floating-animation">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="col-12 mt-5">
            <h2 class="section-title mb-4 animate__animated animate__fadeInUp">Mengapa Menggunakan SIPERBA?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card feature-card animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-light-primary rounded-circle p-3 mb-3 mx-auto">
                                <i class="bi bi-speedometer2 text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-3">Cepat dan Mudah</h4>
                            <p class="mb-0">
                                Laporkan kerusakan fasilitas dalam hitungan menit melalui platform kami yang sederhana.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-light-success rounded-circle p-3 mb-3 mx-auto">
                                <i class="bi bi-graph-up-arrow text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-3">Transparan</h4>
                            <p class="mb-0">
                                Pantau status laporan Anda secara real-time dari awal hingga selesai.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card animate__animated animate__fadeInUp animate__delay-3s">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-light-warning rounded-circle p-3 mb-3 mx-auto">
                                <i class="bi bi-chat-square-text text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-3">Kolaboratif</h4>
                            <p class="mb-0">
                                Sistem terintegrasi antara pelapor, admin, dan teknisi untuk solusi lebih cepat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="col-12 mt-5">
            <h2 class="section-title mb-4 animate__animated animate__fadeInUp">Bagaimana Cara Kerjanya?</h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="step-container">
                                        <div class="step-item animate__animated animate__fadeInLeft">
                                            <div class="step-number">1</div>
                                            <div class="step-content">
                                                <h5>Laporkan Kerusakan</h5>
                                                <p>Isi formulir pelaporan dengan detail kerusakan yang ditemukan.</p>
                                            </div>
                                        </div>
                                        <div class="step-item animate__animated animate__fadeInLeft animate__delay-1s">
                                            <div class="step-number">2</div>
                                            <div class="step-content">
                                                <h5>Verifikasi Admin</h5>
                                                <p>Tim admin akan memverifikasi dan memprioritaskan laporan Anda.</p>
                                            </div>
                                        </div>
                                        <div class="step-item animate__animated animate__fadeInLeft animate__delay-2s">
                                            <div class="step-number">3</div>
                                            <div class="step-content">
                                                <h5>Penugasan Teknisi</h5>
                                                <p>Teknisi akan ditugaskan untuk menangani perbaikan.</p>
                                            </div>
                                        </div>
                                        <div class="step-item animate__animated animate__fadeInLeft animate__delay-3s">
                                            <div class="step-number">4</div>
                                            <div class="step-content">
                                                <h5>Perbaikan & Selesai</h5>
                                                <p>Proses perbaikan dilakukan dan Anda akan mendapatkan notifikasi.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none d-md-block">
                                    <img src="{{ asset('mazer/dist/asset/workflow.svg') }}" alt="Workflow" class="img-fluid bounce-animation">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="col-12 mt-5">
            <h2 class="section-title mb-4 animate__animated animate__fadeInUp">Sistem Kami dalam Angka</h2>
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="card stat-card animate__animated animate__fadeInUp">
                        <div class="card-body text-center p-3">
                            <h2 class="text-primary counter" data-target="1254">0</h2>
                            <p class="mb-0 text-muted">Laporan Diterima</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card stat-card animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="card-body text-center p-3">
                            <h2 class="text-success counter" data-target="987">0</h2>
                            <p class="mb-0 text-muted">Laporan Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card stat-card animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="card-body text-center p-3">
                            <h2 class="text-warning counter" data-target="32">0</h2>
                            <p class="mb-0 text-muted">Teknisi Aktif</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card stat-card animate__animated animate__fadeInUp animate__delay-3s">
                        <div class="card-body text-center p-3">
                            <h2 class="text-info counter" data-target="24">0</h2>
                            <p class="mb-0 text-muted">Jam Respon Cepat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* Custom Animations */
    .floating-animation {
        animation: floating 3s ease-in-out infinite;
    }
    
    .bounce-animation {
        animation: bounce 2s ease infinite;
    }
    
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    /* Feature Cards */
    .feature-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Steps */
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
        background: #435ebe;
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
    
    /* Section Title */
    .section-title {
        position: relative;
        padding-bottom: 10px;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(to right, #435ebe, #57caeb);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
<script>
    $(document).ready(function(){
        // Counter animation
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
        
        // Add animation class on scroll
        $(window).scroll(function() {
            $('.animate-on-scroll').each(function(){
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
@endpush