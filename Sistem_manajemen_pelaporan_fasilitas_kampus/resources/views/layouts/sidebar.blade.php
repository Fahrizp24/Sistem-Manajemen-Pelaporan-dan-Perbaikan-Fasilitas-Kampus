<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="index">
                        <img src="{{ asset('mazer/dist/asset/logoSistem.png')}}" alt="Logo"
                            style="width: 90px; height: auto;">
                    </a>
                </div>
                    <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                            <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                    opacity=".3"></path>
                                <g transform="translate(-210 -1)">
                                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                    <circle cx="220.5" cy="11.5" r="4"></circle>
                                    <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <div class="form-check form-switch fs-6">
                            <input class="form-check-input  me-0" type="checkbox" id="toggle-dark"
                                style="cursor: pointer">
                            <label class="form-check-label"></label>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                            </path>
                        </svg>
                    </div>
                    <div class="sidebar-toggler  x">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
            <ul class="menu">
                @php
                    $peran = Auth::user()->peran;
                    $currentUrl = url()->current();
                @endphp
        
                {{-- Dosen, Mahasiswa, Tendik --}}
                @if(in_array($peran, ['pelapor']))
                <li class="sidebar-title">Menu</li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/pelapor/profile') ? 'active' : '' }}">
                    <a href="{{ url('/pelapor/profile') }}" class='sidebar-link'>
                        <i class="bi bi-person"></i>
                        <span>Profil</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/pelapor/laporan') && !str_contains($currentUrl, '/pelapor/laporan_saya') ? 'active' : '' }}">
                    <a href="{{ url('pelapor/laporan') }}" class='sidebar-link'>
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>Laporkan Kerusakan</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/pelapor/laporan_saya') ? 'active' : '' }}">
                    <a href="{{ url('pelapor/laporan_saya') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Laporan Saya</span>
                    </a>
                </li>
                @endif
        
                {{-- Sarana Prasarana --}}
                @if($peran === 'sarpras')
                <li class="sidebar-title">Menu</li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/sarpras/laporan_masuk') ? 'active' : '' }}">
                    <a href="{{ url('sarpras/laporan_masuk') }}" class='sidebar-link'>
                        <i class="bi bi-inbox"></i>
                        <span>Laporan Masuk</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/sarpras/sistem_rekomendasi') ? 'active' : '' }}">
                    <a href="{{ url('sarpras/sistem_rekomendasi') }}" class='sidebar-link'>
                        <i class="bi bi-lightbulb"></i>
                        <span>Sistem Rekomendasi</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/sarpras/statistik') ? 'active' : '' }}">
                    <a href="{{ url('sarpras/statistik') }}" class='sidebar-link'>
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Statistik</span>
                    </a>
                </li>
                @endif
        
                {{-- Admin --}}
                @if($peran === 'admin')
                <li class="sidebar-title">Menu</li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/pengguna') ? 'active' : '' }}">
                    <a href="{{ url('/admin/pengguna') }}" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/fasilitas') ? 'active' : '' }}">
                    <a href="{{ url('admin/fasilitas') }}" class='sidebar-link'>
                        <i class="bi bi-building"></i>
                        <span>Fasilitas</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/gedung') ? 'active' : '' }}">
                    <a href="{{ url('admin/gedung') }}" class='sidebar-link'>
                        <i class="bi bi-house-door"></i>
                        <span>Gedung</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/statistik') ? 'active' : '' }}">
                    <a href="{{ url('admin/statistik') }}" class='sidebar-link'>
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Statistik</span>
                    </a>
                </li>
                <li class="sidebar-title">Laporan</li>

                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/laporan') && !str_contains($currentUrl, '/admin/laporan_periodik') ? 'active' : '' }}">
                    <a href="{{ url('admin/laporan') }}" class='sidebar-link'>
                        <i class="bi bi-file-text"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/sistem_rekomendasi') ? 'active' : '' }}">
                    <a href="{{ url('admin/sistem_rekomendasi') }}" class='sidebar-link'>
                        <i class="bi bi-lightbulb"></i>
                        <span>Sistem Rekomendasi</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/admin/laporan_periodik') ? 'active' : '' }}">
                    <a href="{{ url('admin/laporan_periodik') }}" class='sidebar-link'>
                        <i class="bi bi-calendar-range"></i>
                        <span>Laporan Periodik</span>
                    </a>
                </li>
                @endif
        
                {{-- Teknisi --}}
                @if($peran === 'teknisi')
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ str_contains($currentUrl, '/pelapor/profile') ? 'active' : '' }}">
                    <a href="{{ url('/teknisi/profile') }}" class='sidebar-link'>
                        <i class="bi bi-person"></i>
                        <span>Profil</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/teknisi/penugasan') ? 'active' : '' }}">
                    <a href="{{ url('teknisi/penugasan') }}" class='sidebar-link'>
                        <i class="bi bi-tools"></i>
                        <span>Penugasan</span>
                    </a>
                </li>
        
                <li class="sidebar-item {{ str_contains($currentUrl, '/teknisi/riwayat_penugasan') ? 'active' : '' }}">
                    <a href="{{ url('/teknisi/riwayat_penugasan') }}" class='sidebar-link'>
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Penugasan</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
                    {{-- Logout (semua peran) --}}
            <div class="logout-section">
                <ul class="menu">
                    <li class="sidebar-item">
                        <a href="{{ url('logout') }}" class="sidebar-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right text-warning"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ url('logout') }}" method="GET" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>


<style>

 /* Sidebar Structure */
    .sidebar-menu {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 120px); /* Adjust based on your header height */
    }
 
    .sidebar-title {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    
    /* Logout Section */
    .logout-section {
        margin-top: auto;
        padding: 0.1rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        position: sticky;
        bottom: 0;
        background: inherit;
    }
    
    /* Ensure proper spacing */
    .menu {
        padding-bottom: 1rem;
    }
    
    /* Style untuk menu aktif */
    .sidebar-item.active > .sidebar-link {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-left: 3px solid #fff;
    }
    
    .sidebar-item.active > .sidebar-link i {
        color: #fff;
    }
</style>