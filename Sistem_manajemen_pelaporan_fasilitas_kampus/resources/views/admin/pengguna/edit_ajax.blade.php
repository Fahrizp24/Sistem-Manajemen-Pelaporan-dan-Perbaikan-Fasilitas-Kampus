<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ url('/admin/pengguna/update', $user->pengguna_id) }}" method="POST" id="form-edit">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><label>Username</label></div>
                        <div class="col-md-8">
                            <input type="text" name="username" class="form-control" value="{{ $user->username }}"
                                placeholder="Username">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><label>Nama</label></div>
                        <div class="col-md-8">
                            <input type="text" name="nama" class="form-control" value="{{ $user->nama }}"
                                placeholder="Nama">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><label>Email</label></div>
                        <div class="col-md-8">
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                placeholder="Email">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><label>No Telp</label></div>
                        <div class="col-md-8">
                            <input type="text" name="no_telp" class="form-control" value="{{ $user->no_telp }}"
                                placeholder="Nomor Telepon">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><label>Peran</label></div>
                        <div class="col-md-8">
                            <select name="peran" class="form-control">
                                <option value="admin" {{ $user->peran == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="sarpras" {{ $user->peran == 'sarpras' ? 'selected' : '' }}>Sarpras</option>
                                <option value="teknisi" {{ $user->peran == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                                <option value="pelapor" {{ $user->peran == 'pelapor' ? 'selected' : '' }}>Mahasiswa
                                </option>
                                <option value="pelapor" {{ $user->peran == 'pelapor' ? 'selected' : '' }}>Dosen</option>
                                <option value="pelapor" {{ $user->peran == 'pelapor' ? 'selected' : '' }}>Tendik</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><label>Reset Password</label></div>
                        <div class="col-md-8">
                            <button type="button" class="btn btn-warning btnResetPassword"
                                data-id="{{ $user->pengguna_id }}">
                                Reset Password
                            </button>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#form-edit").validate({
            rules: {
                username: { required: true },
                nama: { required: true, minlength: 3 },
                email: { required: true, email: true },
                no_telp: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                peran: { required: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            dataUser.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                            dataUser.ajax.reload(null, false); // ✅ reload data table
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data'
                        });
                    }
                });
                return false;
            }
        });
    });
    $(document).on('click', '.btnResetPassword', function () {
        let id = $(this).data('id');
        let username = $(this).data('username');

        Swal.fire({
            title: 'Yakin reset password?',
            text: "Password akan direset menjadi username: " + username,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Reset!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/pengguna/reset_password/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mereset password.'
                        });
                    }
                });
            }
        });
    });
</script>