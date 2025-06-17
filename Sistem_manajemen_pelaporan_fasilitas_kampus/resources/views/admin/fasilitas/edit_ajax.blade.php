<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ url('/admin/fasilitas/update', $fasilitas->fasilitas_id) }}" method="POST" id="form-edit">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fasilitas_nama">Nama Fasilitas</label>
                            <input type="text" name="fasilitas_nama" class="form-control" value="{{ $fasilitas->fasilitas_nama }}"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="fasilitas_deskripsi">Deskripsi</label>
                            <textarea name="fasilitas_deskripsi" class="form-control" rows="3"
                                required>{{ $fasilitas->fasilitas_deskripsi }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gedung_id">Gedung</label>
                            <select name="gedung_id" class="form-control" required>
                                <option value="">-- Pilih Gedung --</option>
                                @foreach($gedung as $g)
                                    <option value="{{ $g->gedung_id }}" {{ $gedung_id == $g->gedung_id ? 'selected' : '' }}>
                                        {{ $g->gedung_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="normal" {{ $fasilitas->status == 'normal' ? 'selected' : '' }}>Normal
                                </option>
                                <option value="rusak" {{ $fasilitas->status == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
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
                fasilitas_nama: { required: true, minlength: 3 },
                fasilitas_deskripsi: { required: true, minlength: 10 },
                gedung_id: { required: true },
                status: { required: true }
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
                            fasilitasTable.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                            fasilitasTable.ajax.reload(null, false); // ✅ reload data table
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