<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="card-header">
        <h4 class="card-title">Tambah Pengguna</h4>
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-4"><label>Nama</label></div>
                <div class="col-md-8">
                    <input type="text" name="nama" class="form-control" placeholder="Nama">
                </div>

                <div class="col-md-4"><label>Email</label></div>
                <div class="col-md-8">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                </div>

                <div class="col-md-4"><label>Identitas</label></div>
                <div class="col-md-8">
                    <input type="text" name="identitas" class="form-control" placeholder="NIP/NIM/NIDN">
                </div>

                <div class="col-md-4"><label>Peran</label></div>
                <div class="col-md-8">
                    <select name="peran" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="sarpras">Sarpras</option>
                        <option value="teknisi">Teknisi</option>
                    </select>
                </div>

                <div class="col-md-4"><label>Password</label></div>
                <div class="col-md-8">
                    <input type="password" name="kata_sandi" class="form-control" placeholder="Password">
                </div>

                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function () {
    $("#form-tambah").validate({
        rules: {
            nama: { required: true, minlength: 3 },
            email: { required: true, email: true },
            identitas: { required: true },
            peran: { required: true },
            kata_sandi: { required: true, minlength: 6 }
        },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                method: form.method,
                data: $(form).serialize(),
                success: function (response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        if (typeof dataUser !== 'undefined') {
                            dataUser.ajax.reload();
                        } else {
                            location.reload(); // fallback
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                        $('.error-text').text('');
                        $.each(response.msgField, function (prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }
                }
            });
            return false;
        }
    });
});
</script>
