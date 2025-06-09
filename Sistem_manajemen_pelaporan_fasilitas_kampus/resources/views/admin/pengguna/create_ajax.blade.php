<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ route('admin.pengguna.ajax') }}" method="POST" id="form-tambah">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <!-- Username -->
                <div class="row mb-3">
                    <div class="col-md-4"><label>Username</label></div>
                    <div class="col-md-8">
                        <input type="text" name="username" class="form-control" placeholder="Username">
                    </div>
                </div>

                <!-- Nama -->
                <div class="row mb-3">
                    <div class="col-md-4"><label>Nama</label></div>
                    <div class="col-md-8">
                        <input type="text" name="nama" class="form-control" placeholder="Nama">
                    </div>
                </div>

                <!-- Email -->
                <div class="row mb-3">
                    <div class="col-md-4"><label>Email</label></div>
                    <div class="col-md-8">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>

                <!-- Peran -->
                <div class="row mb-3">
                    <div class="col-md-4"><label>Peran</label></div>
                    <div class="col-md-8">
                        <select name="peran" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="sarpras">Sarpras</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pelapor">Dosen</option>
                            <option value="pelapor">Mahasiswa</option>
                            <option value="pelapor">Tendik</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div class="row mb-3">
                    <div class="col-md-4"><label>Password</label></div>
                    <div class="col-md-8">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#form-tambah").validate({
        rules: {
            username: { required: true, minlength: 3 },
            nama: { required: true, minlength: 3 },
            email: { required: true, email: true },
            peran: { required: true },
            password: { required: true, minlength: 6 }
        },
        messages: {
            username: {
                required: "Username wajib diisi",
                minlength: "Minimal 3 karakter"
            },
            password: {
                required: "Password wajib diisi",
                minlength: "Minimal 6 karakter"
            }
        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        dataUser.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#error-${key}`).text(value[0]);
                            $(`[name="${key}"]`).addClass('is-invalid');
                        });
                    }
                }
            });
            return false;
        }
    });
});
</script>