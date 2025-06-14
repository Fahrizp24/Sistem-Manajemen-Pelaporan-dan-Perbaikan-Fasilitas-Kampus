@extends('layouts.template')
@section('title', 'Profil')

@section('content')
    <div class="row justify-content-center py-5">
    <div class="col-xl-12 col-lg-16">
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

                <!-- Navbar -->
                <ul class="nav nav-pills nav-fill mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="#profile" data-bs-toggle="tab">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#editProfile" data-bs-toggle="tab">Edit Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#changePassword" data-bs-toggle="tab">Ganti Password</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profile">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3 w-100 text-center">
                                        <span class="fw-bold" style="font-size: 1.4rem; pb-3">Foto Profil</span>
                                        <div class="border-bottom mx-auto" style="width: 80px;"></div>
                                    </div>
                                    <div class="profile-photo-wrapper"
                                        style="display: inline-block; position: relative; width: 230px; height: 280px;">
                                       <img id="foto-profil" class="profile-user-img img-fluid"
                                                    src="{{ auth()->user()->foto_profil ? asset('storage/foto_profil/' . auth()->user()->foto_profil) : asset('storage/foto_profil/default.jpg') }}"
                                                    alt="User  profile picture"
                                                    style="position: absolute; top: 0; left: 0; width: 97%; height: 97%; object-fit: cover; border: 2px solid #fff;"
                                                    onerror="this.style.display='none'; document.getElementById('foto-fallback').style.display='flex';">
                                            <div 
                                                id="foto-fallback"
                                                style="display: none; width: 100%; height: 100%; background: #f0f0f0; color: #666; font-size: 5rem; font-weight: bold; justify-content: center; align-items: center; text-align: center;">
                                                {{ substr($pelapor->nama, 0, 1) }}
                                            </div>
                                        </div>
                                        <h4 class="mt-2">{{ $pelapor->nama }}</h4>
                                       <p class="text-muted mb-0">{{ $pelapor->username}}</p>
                            </div>

                            <div class="col-md-8">
                                <h4 class="mb-4 border-bottom pb-3">Informasi Profil</h4>

                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $pelapor->nama }}"
                                        required disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="{{ $pelapor->username }}"
                                        disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="no_telp" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="no_telp" name="no_telp"
                                        value="{{ $pelapor->no_telp ?? '-' }}" disabled>
                                </div>
                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $pelapor->email }}" required disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="editProfile">
                        <form action="{{ route('pelapor.updateProfile') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-3 w-100 text-center">
                                            <span class="fw-bold" style="font-size: 1.4rem; pb-3">Foto Profil</span>
                                            <div class="border-bottom mx-auto" style="width: 80px;"></div>
                                        </div>
                                        <div class="profile-photo-wrapper" style="display: inline-block; position: relative; width: 225px; height: 275px;">
                                            <label for="foto-input" style="cursor: pointer;">
                                                <img id="foto-profil" class="profile-user-img img-fluid"
                                                    src="{{ auth()->user()->foto_profil ? asset('storage/foto_profil/' . auth()->user()->foto_profil) : asset('storage/foto_profil/default.jpg') }}"
                                                    alt="User  profile picture"
                                                    style="position: absolute; top: 0; left: 0; width: 97%; height: 97%; object-fit: cover; border: 2px solid #fff;"
                                                    onerror="this.style.display='none'; document.getElementById('foto-fallback').style.display='flex';">
                                                <div 
                                                    id="foto-fallback"
                                                    style="display: none; position: absolute; top: 0; left: 0; width: 97%; height: 97%; background: #b4b4b4; color: #fff; font-weight: bold; justify-content: center; align-items: center; text-align: center; border: 2px solid #fff;">
                                                </div>
                                                <div 
                                                    class="hover-overlay"
                                                    style="position: absolute; top: 0; left: 0; width: 97%; height: 97%; background: rgba(0, 0, 0, 0.5); color: #fff; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s;">
                                                    <i class="bi bi-camera-fill" style="font-size: 2rem;"></i>
                                                </div>
                                            </label>
                                            <input type="file" id="foto-input" name="foto" accept="image/*" style="display: none;"
                                                onchange="uploadFoto()">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <h4 class="mb-4 border-bottom pb-3">Edit Profil</h4>

                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $pelapor->nama }}"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_telp" class="form-label">Nomor Telepon</label>
                                        <input type="tel" class="form-control" id="no_telp" name="no_telp"
                                            value="{{ $pelapor->no_telp ?? '-' }}">
                                    </div>
                
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $pelapor->email }}" required>
                                    </div>

                                    <div class="footer d-flex justify-content-end">
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="changePassword">
                        <form action="{{ route('pelapor.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    name="new_password_confirmation" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Password</button>
                            </div>
                        </form>
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