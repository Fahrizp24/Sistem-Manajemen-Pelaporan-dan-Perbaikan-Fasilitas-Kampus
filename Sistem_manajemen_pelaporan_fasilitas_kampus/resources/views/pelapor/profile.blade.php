@extends('layouts.template')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-xl-8 col-lg-10">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <!-- Success/Error Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('pelapor.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Left Column - Photo Section -->
                        <div class="col-md-4 text-center">
                            <div class="profile-photo-container mb-4">
                                <div class="profile-photo-wrapper">
                                    <img src="{{ asset('storage/foto_profil/'.$pelapor->foto_profil.'.jpg') }}" 
                                         alt="Foto Profil" 
                                         class="profile-photo img-fluid">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Profile Information -->
                        <div class="col-md-8">
                            <h4 class="mb-4 border-bottom pb-3">Informasi Profil</h4>
                            
                            <div class="mb-4">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $pelapor->nama }}" required>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="{{ $pelapor->username }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Peran</label>
                                    <div>
                                        <span class="badge bg-primary py-2 px-3 divider">{{ $pelapor->peran }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="prodi" class="form-label">Program Studi</label>
                                    <input type="text" class="form-control" id="prodi" name="prodi" value="{{ $pelapor->prodi ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="jurusan" class="form-label">Jurusan</label>
                                    <input type="text" class="form-control" id="jurusan" name="jurusan" value="{{ $pelapor->jurusan ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="no_telp" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="no_telp" name="no_telp" value="{{ $pelapor->no_telp ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $pelapor->email }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol-tombol action -->
                    <div class="row border-top pt-4 mt-3">
                        <div class="col-md-4 mb-2">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                                <i class="bi bi-camera-fill me-2"></i> Ganti Foto
                            </button>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-key me-1"></i> Ubah Password
                            </button>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelapor.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* Profile Photo Styles */
   
    .profile-photo-container {
        position: relative;
        margin-bottom: 1.5rem;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .profile-photo-wrapper {
        width: 180px;       /* Lebar foto */
        height: 240px;      /* Tinggi foto (rasio 3:4) */
        border-radius: 10px; /* Sudut agak melengkung */
        overflow: hidden;
        border: 4px solid #f8f9fa;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa; /* Warna background jika foto kosong */
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;  /* Memastikan foto mengisi area tanpa distorsi */
        transition: transform 0.3s ease;
    }
    
    .profile-photo:hover {
        transform: scale(1.05);
    }
    
    /* Modal Photo Preview */
    .profile-photo-preview-container {
    width: 150px;                 /* Lebar tetap */
    height: 300px;                /* Tinggi diperpanjang */
    border-radius: 8px;           /* Sudut melengkung */
    overflow: hidden;
    border: 3px solid #f8f9fa;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-photo-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;            /* Pastikan foto mengisi area */
    object-position: center top;  /* Fokus ke bagian atas foto */
}
    
    /* Card and Form Styles */
    .card {
        border-radius: 12px;
        border: none;
    }
    
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }
    
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
    }
    
    .badge {
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .profile-photo-wrapper {
            width: 150px;
            height: 400px;
        }
        
        .col-md-4 {
            margin-bottom: 2rem;
            border-right: none !important;
            border-bottom: 1px solid #eee;
            padding-bottom: 2rem;
        }
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Photo preview when selecting new photo
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('photoPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush