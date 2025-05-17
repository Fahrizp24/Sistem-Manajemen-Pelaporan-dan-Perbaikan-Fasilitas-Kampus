<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="index.html">
                        <img src="{{ asset('assets/compiled/svg/logo.svg') }}" alt="Logo">
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--system-uicons" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <!-- SVG content -->
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                        viewBox="0 0 24 24">
                        <!-- SVG content -->
                    </svg>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <!-- Aktor: Dosen, Mahasiswa, dan Tendik -->
                <li class="sidebar-item">
                    <span class="sidebar-subtitle">Aktor: Dosen, Mahasiswa, dan Tendik</span>
                </li>
                <li class="sidebar-item">
                    <a href="/profile" class="sidebar-link">
                        <i class="bi bi-person-fill"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/laporkan-kerusakan" class="sidebar-link">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>Laporkan Kerusakan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/laporan-saya" class="sidebar-link">
                        <i class="bi bi-clipboard-check-fill"></i>
                        <span>Laporan Saya</span>
                    </a>
                </li>

                <!-- Aktor: Sarana Prasarana -->
                <li class="sidebar-item mt-3">
                    <span class="sidebar-subtitle">Aktor: Sarana Prasarana</span>
                </li>
                <li class="sidebar-item">
                    <a href="/laporan-masuk" class="sidebar-link">
                        <i class="bi bi-inbox-fill"></i>
                        <span>Laporan Masuk</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/sistem-rekomendasi" class="sidebar-link">
                        <i class="bi bi-lightbulb-fill"></i>
                        <span>Sistem Rekomendasi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/statistik" class="sidebar-link">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Statistik</span>
                    </a>
                </li>

                <!-- Aktor: Admin -->
                <li class="sidebar-item mt-3">
                    <span class="sidebar-subtitle">Aktor: Admin</span>
                </li>
                <li class="sidebar-item">
                    <a href="/laporan" class="sidebar-link">
                        <i class="bi bi-clipboard-data-fill"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/kelola-pengguna" class="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/fasilitas" class="sidebar-link">
                        <i class="bi bi-building-fill"></i>
                        <span>Fasilitas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/gedung" class="sidebar-link">
                        <i class="bi bi-house-fill"></i>
                        <span>Gedung</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/laporan-periodik" class="sidebar-link">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Laporan Periodik</span>
                    </a>
                </li>

                <!-- Aktor: Teknisi -->
                <li class="sidebar-item mt-3">
                    <span class="sidebar-subtitle">Aktor: Teknisi</span>
                </li>
                <li class="sidebar-item">
                    <a href="/penugasan" class="sidebar-link">
                        <i class="bi bi-tools"></i>
                        <span>Penugasan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/riwayat-penugasan" class="sidebar-link">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Penugasan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>