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

                <form action="{{ route('sarpras.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil -->
                    <div class="text-center mb-4">
                        <div 
                            class="profile-photo-wrapper" 
                            style="display: inline-block; position: relative; width: 120px; height: 120px;"
                        >
                            <label for="foto-input" style="cursor: pointer;">
                                <img 
                                    id="foto-profil" 
                                    class="profile-user-img img-fluid img-circle"
                                    src="{{ auth()->user()->foto_profil ? asset('storage/foto_profil/' . auth()->user()->foto_profil) : asset('storage/foto_profil/default.jpg') }}"
                                    alt="User profile picture" 
                                    style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #fff;"
                                >
                                <div 
                                    class="hover-overlay"
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: rgba(0, 0, 0, 0.5); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 24px; opacity: 0; transition: opacity 0.3s;"
                                >
                                    <i class="bi bi-camera-fill"></i>
                                </div>
                            </label>
                            <input 
                                type="file" 
                                id="foto-input" 
                                name="foto" 
                                accept="image/*" 
                                style="display: none;" 
                                onchange="uploadFoto()"
                            >
                        </div>
                    </div>

                    <!-- Informasi Profil -->
                    <h4 class="mb-4 border-bottom pb-3 text-center">Informasi Profil</h4>
                    
                    <div class="mb-4">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $sarpras->nama }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="{{ $sarpras->username }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peran</label>
                            <div>
                                <span class="badge bg-primary py-2 px-3 divider">{{ $sarpras->peran }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="prodi" class="form-label">Program Studi</label>
                            <input type="text" class="form-control" id="prodi" name="prodi" value="{{ $sarpras->prodi ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" value="{{ $sarpras->jurusan ?? '' }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="no_telp" name="no_telp" value="{{ $sarpras->no_telp ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $sarpras->email }}" required>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="row border-top pt-4 mt-3">
                        <div class="col-md-6 mb-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                        <div class="col-md-6 mb-2">
                            <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-key me-1"></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sarpras.updatePassword') }}" method="POST">
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

        fetch("{{ route('sarpras.updateFoto') }}", {
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
