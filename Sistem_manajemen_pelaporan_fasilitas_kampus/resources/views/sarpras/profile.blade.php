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
                    
                    <div class="row">
                        <!-- Left Column - Photo Section -->
                        <div class="col-md-4 text-center">
                            <div class="profile-photo-container mb-4">
                                <div class="profile-photo-wrapper">
                                    <img id="foto-profil" class="profile-user-img img-fluid img-circle"
                                    src="{{ auth()->user()->foto_profil ? asset('storage/foto_profil/' . auth()->user()->foto_profil) : asset('storage/foto_profil/default.jpg') }}"
                                    alt="User profile picture" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #fff;"">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Profile Information -->
                        <div class="col-md-8">
                            <h4 class="mb-4 border-bottom pb-3">Informasi Profil</h4>
                            
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
                        </div>
                    </div>
                    
                    <!-- Tombol-tombol action -->
                    <div class="row border-top pt-4 mt-3">
                        <div class="col-md-4 mb-2">
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#changeFotoModal">
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

<div class="modal fade" id="changeFotoModal" tabindex="-1" aria-labelledby="changeFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="changeFotoModalLabel">Ubah Foto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sarpras.updateFoto') }}" method="POST" id="form-upload" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Foto Profile</label>
                        <input type="file" name="foto" id="foto" class="form-control" required>
                        <small id="error-foto" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
@endsection

<script>
    // Menunggu sampai seluruh halaman selesai dimuat (DOM Ready)
    $(document).ready(function() {

        // Mengaktifkan validasi pada form dengan ID 'form-upload'
        $("#form-upload").validate({actions
            // Aturan validasi input
            rules: {
                foto: {
                    required: true, // Wajib diisi
                    extension: "jpg|jpeg|png", // Hanya file dengan ekstensi ini yang diizinkan
                    filesize: 2048 // Hanya file dengan ukuran maksimal 2048KB (2MB) yang diizinkan
                }
            },

            // Fungsi ini akan dijalankan jika form lolos validasi
            submitHandler: function(form) {
                // Mengirim data form menggunakan AJAX
                $.ajax({
                    url: form.action, // URL tujuan, diambil dari atribut 'action' pada form
                    type: form.method, // Metode pengiriman, diambil dari atribut 'method' (biasanya POST)
                    data: new FormData(form), // Mengambil data form sebagai FormData (agar bisa mengirim file)
                    cache: false,
                    contentType: false, // Agar jQuery tidak mengubah tipe konten (wajib untuk upload file)
                    processData: false, // Jangan proses data (biarkan FormData yang menangani)

                    // Jika request berhasil (HTTP 200)
                    success: function(response) {
                        if (response.status) {
                            // Menutup modal
                            $('#changeFotoModal').modal('hide');

                            // Menampilkan alert sukses dari SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            })
                            // Mengganti foto profil dengan foto baru
                            // `?t=` digunakan untuk menghindari cache (memaksa browser memuat ulang gambar)
                            $('#foto-profil').attr('src', response.foto_profil + '?t=' + new Date().getTime());
                        } else {
                            // Reset semua pesan error
                            $('.error-text').text('');

                            // Menampilkan pesan error spesifik berdasarkan field
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });

                            // Menampilkan alert error
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });

                // Mencegah form dikirim secara default (karena sudah pakai AJAX)
                return false;
            },

            // Customisasi elemen untuk pesan error
            errorElement: 'span',

            // Menentukan di mana letak pesan error ditampilkan
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback'); // Tambahkan class Bootstrap
                element.closest('.form-group').append(error); // Tempel error di bawah input
            },

            // Tambahkan class 'is-invalid' jika input error
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },

            // Hapus class 'is-invalid' jika input valid
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>