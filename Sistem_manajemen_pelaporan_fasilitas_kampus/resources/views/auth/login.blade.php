    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Login - FIXIT</title>
        <link rel="shortcut icon" href="{{ asset('mazer/dist/asset/logoSistemX.png') }}" type="image/x-icon">
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
                align-items: center;
                gap: 20px;
                margin-bottom: 2rem;
                flex-wrap: wrap;
            }

            .logo {
                height: 80px;
                object-fit: contain;
                max-width: 130px;
            }

            .login-title {
                text-align: center;
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }

            .login-title .thin-text {
                font-weight: 300;
                margin-right: 5px;
            }

            .spinner-border {
                vertical-align: middle;
                margin-right: 8px;
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
                    <span class="medium-text">Masuk ke</span>
                    <strong>PROFIL</strong>
                </h1>


                <p class="auth-subtitle">Masuklah dengan data yang telah terdaftar.</p>

                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl" name="username"
                            placeholder="Username">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl" name="password"
                            placeholder="Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary btn-lg shadow-lg" id="login-button">Login</button>
                </form>

                <div class="forgot-password">
                    <a class="font-bold" href="forgot_password">Forgot password?</a>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#form-login").validate({
                    rules: {
                        username: {
                            required: true,
                            minlength: 4,
                            maxlength: 20
                        },
                        password: {
                            required: true,
                            minlength: 6,
                            maxlength: 20
                        }
                    },
                    messages: {
                        username: {
                            required: "Username wajib diisi",
                            minlength: "Minimal 4 karakter",
                            maxlength: "Maksimal 20 karakter"
                        },
                        password: {
                            required: "Password wajib diisi",
                            minlength: "Minimal 6 karakter",
                            maxlength: "Maksimal 20 karakter"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    },
                    submitHandler: function(form) {
                        // Simpan teks asli tombol
                        const loginButton = $('#login-button');
                        const originalText = loginButton.text();

                        // Ubah teks tombol dan nonaktifkan
                        loginButton.html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memuat...
        `).prop('disabled', true);

                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: $(form).serialize(),
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                    }).then(() => {
                                        window.location.href = response.redirect;
                                    });
                                } else {
                                    // Kembalikan teks tombol jika gagal
                                    loginButton.text(originalText).prop('disabled', false);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Kembalikan teks tombol jika error
                                loginButton.text(originalText).prop('disabled', false);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan pada server'
                                });
                            }
                        });
                        return false;
                    }
                });
            });
        </script>
    </body>

    </html>
