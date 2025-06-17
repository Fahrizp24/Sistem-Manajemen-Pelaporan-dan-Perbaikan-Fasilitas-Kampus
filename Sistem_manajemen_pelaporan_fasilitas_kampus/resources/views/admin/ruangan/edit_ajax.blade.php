<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ url('/admin/ruangan/update', $ruangan->ruangan_id) }}" method="POST" id="form-edit">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Edit Data Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama">Nama Ruangan</label>
                            <input type="text" name="ruangan_nama" class="form-control" value="{{ $ruangan->ruangan_nama }}"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="ruangan_deskripsi" class="form-control" rows="3"
                                required>{{ $ruangan->ruangan_deskripsi }}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                        </div>
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
                ruangan_nama: { required: true, minlength: 3 },
                ruangan_deskripsi: { required: true, minlength: 10 }
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
                            tableRuangan.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                            tableRuangan.ajax.reload(null, false); // ✅ reload data table
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
</script>