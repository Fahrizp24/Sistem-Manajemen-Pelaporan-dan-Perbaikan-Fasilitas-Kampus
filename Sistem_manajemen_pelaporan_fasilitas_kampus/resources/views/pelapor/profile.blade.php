@extends('layouts.template')
@section('title', 'Profil')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-xl-12 col-lg-16">
        <div class="card shadow-sm" style="background-color: #f8f9fa; border-radius: 10px;">
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

                <!-- Navbar -->
                <ul class="nav nav-pills nav-fill mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" style="background-color: #4e73df;" href="#profile" data-bs-toggle="tab">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #4e73df;" href="#editProfile" data-bs-toggle="tab">Edit Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #4e73df;" href="#changePassword" data-bs-toggle="tab">Ganti Password</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3 w-100 text-center">
                                        <h5 style="color: #4e73df;">Foto Profil</h5>
                                        <div class="border-bottom mx-auto" style="width: 80px; border-color: #4e73df;"></div>
                                    </div>
                                    <div class="profile-photo-wrapper mb-3"
                                        style="display: inline-block; position: relative; width: 200px; height: 200px; border-radius: 10px; overflow: hidden; background-color: #e0e6f8;">
                                        <img id="foto-profil" class="profile-user-img img-fluid"
                                            src="{{ auth()->user()->foto_profil ? asset('storage/foto_profil/' . auth()->user()->foto_profil) : asset('storage/foto_profil/default.jpg') }}"
                                            alt="User profile picture"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            onerror="this.style.display='none'; document.getElementById('foto-fallback').style.display='flex';">
                                        <div 
                                            id="foto-fallback"
                                            style="display: none; width: 100%; height: 100%; background: #e0e6f8; color: #4e73df; font-size: 5rem; font-weight: bold; display: flex; justify-content: center; align-items: center;">
                                            {{ substr($user->nama, 0, 1) }}
                                        </div>
                                    </div>
                                    <h4 style="color: #4e73df;">{{ $user->nama }}</h4>
                                    <p class="text-muted mb-0">{{ $user->role }}</p>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="border-bottom mb-4 pb-2">
                                    <h4 style="color: #4e73df;">Informasi Profil</h4>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Nama Lengkap</label>
                                    <div class="form-control-plaintext fw-bold" style="color: #4e73df;">{{ $user->nama }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Username</label>
                                    <div class="form-control-plaintext fw-bold" style="color: #4e73df;">{{ $user->username }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Nomor Telepon</label>
                                    <div class="form-control-plaintext fw-bold" style="color: #4e73df;">{{ $user->no_telp ?? '-' }}</div>
                                </div>
                
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <div class="form-control-plaintext fw-bold" style="color: #4e73df;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile and Change Password tabs remain the same -->
                    <!-- ... -->
                </div>
            </div>
            
            <!-- Footer -->
            <div class="card-footer text-center" style="background-color: #f8f9fa; border-top: 1px solid #e3e6f0;">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">2025 © Mazer</span>
                    <span class="text-muted">Crafted with by Group 4 PBL TI-2G</span>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        document.querySelectorAll('.profile-photo-wrapper').forEach(wrapper => {
            wrapper.addEventListener('mouseover', () => {
                wrapper.querySelector('.hover-overlay').style.opacity = '1';
            });
            wrapper.addEventListener('mouseout', () => {
                wrapper.querySelector('.hover-overlay').style.opacity = '0';
            });
        });

        function uploadFoto() {
            const input = document.getElementById('foto-input');
            const formData = new FormData();
            formData.append('foto', input.files[0]);

            fetch("{{ route('pelapor.updateFoto') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengunggah foto'
                    });
                });
        }
    </script>
@endsection