<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Tambahkan ini -->
    <title>Login - FIXIT</title>
    <link rel="shortcut icon" href="{{ asset('mazer/distassets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/auth.css') }}">
</head>

<body>
    <script src="{{ asset('mazer/dist/assets/static/js/initTheme.js') }}"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <img src="{{ asset('mazer/dist/asset/logoo.png') }}" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">LOGIN</h1>
                    <p class="auth-subtitle mb-5">Masuklah dengan data yang telah terdaftar.</p>
                    
                    <form action="{{ url('login') }}" method="POST" id="form-login">
                        @csrf <!-- Pindahkan ke dalam form -->
                        
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" name="username" placeholder="Username"> <!-- Tambahkan name -->
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password" placeholder="Password"> <!-- Tambahkan name -->
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Login</button>
                    </form>
                    
                    <div class="text-center mt-5 text-lg fs-4">
                        <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>

    <!-- Pastikan jQuery dan library pendukung sudah dimuat -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Set CSRF token untuk AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Validasi form
            $("#form-login").validate({
                rules: {
                    username: { required: true, minlength: 4, maxlength: 20 },
                    password: { required: true, minlength: 6, maxlength: 20 }
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
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
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