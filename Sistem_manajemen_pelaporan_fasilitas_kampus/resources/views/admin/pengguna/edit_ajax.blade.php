<form action="{{ url('/admin/pengguna/update', $user->pengguna_id) }}" method="POST" id="form-edit">
    @csrf
    @method('POST')
    <div class="card-header">
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-4"><label>Nama</label></div>
                <div class="col-md-8">
                    <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" placeholder="Nama">
                </div>

                <div class="col-md-4"><label>Email</label></div>
                <div class="col-md-8">
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Email">
                </div>

                <div class="col-md-4"><label>Identitas</label></div>
                <div class="col-md-8">
                    <input type="text" name="identitas" class="form-control" value="{{ $user->identitas }}" placeholder="NIP/NIM/NIDN">
                </div>

                <div class="col-md-4"><label>Peran</label></div>
                <div class="col-md-8">
                    <select name="peran" class="form-control">
                        <option value="admin" {{ $user->peran == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="sarpras" {{ $user->peran == 'sarpras' ? 'selected' : '' }}>Sarpras</option>
                        <option value="teknisi" {{ $user->peran == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    </select>
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
    $("#form-edit").validate({
        rules: {
            nama: { required: true, minlength: 3 },
            email: { required: true, email: true },
            password: { required: true },
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
                            text: response.message
                        });
                        dataUser.ajax.reload(null, false);
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
                        text: 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
            return false;
        }
    });
});
</script>
