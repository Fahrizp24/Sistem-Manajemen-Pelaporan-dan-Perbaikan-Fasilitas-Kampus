<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - FIXIT</title>
    <link rel="shortcut icon" href="{{ asset('mazer/dist/asset/logoSistemX.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/auth.css') }}">
    <style>
        body {
            background-image: url('{{ asset('storage/dashboard/login.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(74, 126, 210, 0.355);
            z-index: 0;
        }

        #auth {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo img {
            max-width: 150px;
        }

        .auth-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .auth-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .theme-toggle {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            /* Tengah horizontal */
            align-items: center;
            gap: 20px;
            /* Jarak antar logo */
            margin-bottom: 2rem;
            flex-wrap: wrap;
            /* Biar rapi di layar kecil */
        }

        .logo {
            height: 80px;
            /* Atur tinggi logo sesuai kebutuhan */
            object-fit: contain;
            max-width: 130px;
            /* Atur lebar maksimal */
        }

        .login-title {
            text-align: center;
            /* Tengah horizontal */
            font-size: 2rem;
            /* Ukuran keseluruhan judul */
            margin-bottom: 1.5rem;
        }

        .login-title .thin-text {
            font-weight: 300;
            /* Lebih tipis dari normal (400) */
            margin-right: 5px;
            /* Jarak sedikit dari kata PROFIL */
        }
    </style>
</head>

<body>
    <script src="{{ asset('mazer/dist/assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        <div class="auth-container">
            <div class="logo-container">
                <img src="{{ asset('storage/dashboard/polinema_logo.png') }}" alt="Polinema Logo" class="logo">
                <img src="{{ asset('mazer/dist/asset/logoSistem.png') }}" alt="FIXIT Logo" class="logo">
            </div>
            <h1 class="login-title">
                <span class="medium-text">Lupa Password</span>
            </h1>
            <form id="form-check-username" method="POST">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" name="username" id="username"
                        placeholder="Username" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Cek Username</button>
            </form>

            <div id="pertanyaan-section" style="display: none;">
                <form id="form-pertanyaan" method="POST" action="{{ url('forgot_password/pertanyaan') }}">
                    @csrf
                    <input type="hidden" name="username" id="hidden_username">
                    <div class="form-group mb-3">
                        <label id="label_masa_kecil"></label>
                        <input type="text" class="form-control" name="jawaban_masa_kecil" required>
                    </div>
                    <div class="form-group mb-3">
                        <label id="label_keluarga"></label>
                        <input type="text" class="form-control" name="jawaban_keluarga" required>
                    </div>
                    <div class="form-group mb-3">
                        <label id="label_tempat"></label>
                        <input type="text" class="form-control" name="jawaban_tempat" required>
                    </div>
                    <div class="form-group mb-3">
                        <label id="label_pengalaman"></label>
                        <input type="text" class="form-control" name="jawaban_pengalaman" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit Jawaban</button>
                </form>
            </div>

            <div id="reset-password-section" style="display: none;">
                <form id="form-reset-password" method="POST" action="{{ url('forgot_password/reset_password') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Password Baru</label>
                        <input type="password" class="form-control" name="password_baru" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="konfirmasi_password_baru" required>
                    </div>
                    <input type="hidden" name="username" id="reset_username">

                    <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
                </form>
            </div>


        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('mazer/dist/assets/static/js/components/dark.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#form-check-username').submit(function(e) {
                e.preventDefault();
                const username = $('#username').val();

                $.ajax({
                    url: '{{ url('forgot_password/cek_username') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        username: username
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#hidden_username').val(username);
                            $('#pertanyaan-section').show();

                            // Tampilkan pertanyaan yang diterima dari server
                            $('#label_masa_kecil').text(response.data.pertanyaan_masa_kecil);
                            $('#label_keluarga').text(response.data.pertanyaan_keluarga);
                            $('#label_tempat').text(response.data.pertanyaan_tempat);
                            $('#label_pengalaman').text(response.data.pertanyaan_pengalaman);
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                            $('#pertanyaan-section').hide();
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                        $('#pertanyaan-section').hide();
                    }
                });
            });
            $('#form-pertanyaan').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Validasi Berhasil',
                                text: 'Silakan ubah password Anda.'
                            });

                            $('#form-pertanyaan').hide();
                            $('#reset-password-section').show();
                            $('#reset_username').val($('#hidden_username').val());

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Jawaban Salah',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Coba lagi nanti.'
                        });
                    }
                });
            });

            $('#form-reset-password').submit(function(e) {
                e.preventDefault();

                const data = $(this).serializeArray();
                const passBaru = data.find(d => d.name === 'password_baru').value;
                const passKonfirm = data.find(d => d.name === 'konfirmasi_password_baru').value;

                if (passBaru !== passKonfirm) {
                    Swal.fire('Error', 'Password dan konfirmasi tidak sama', 'error');
                    return;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire('Berhasil', 'Password berhasil diubah. Silakan login.',
                                    'success')
                                .then(() => window.location.href = '/login');
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                    }
                });
            });

        });
    </script>

</body>

</html>
