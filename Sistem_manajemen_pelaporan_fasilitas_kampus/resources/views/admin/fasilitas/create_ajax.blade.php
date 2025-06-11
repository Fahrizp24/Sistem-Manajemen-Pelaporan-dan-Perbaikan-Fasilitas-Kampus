<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form action="{{ route('admin.store_fasilitas') }}" method="POST" id="form-tambah">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fasilitas_nama">Nama Fasilitas</label>
                            <input type="text" name="fasilitas_nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori">Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Jaringan">Jaringan</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Perlengkapan Kelas">Perlengkapan kelas</option>
                                <option value="Listrik">Listrik</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="fasilitas_deskripsi">Deskripsi</label>
                            <textarea name="fasilitas_deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ruangan_id">Ruangan</label>
                            <select name="ruangan_id" class="form-control" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($ruangan as $r)
                                    <option value="{{ $r->ruangan_id }}">{{ $r->ruangan_deskripsi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="normal">Normal</option>
                                <option value="rusak">Rusak</option>
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
        $("#form-tambah").validate({
            rules: {
                fasilitas_nama: { required: true, minlength: 3 },
                kategori: { required: true },
                fasilitas_deskripsi: { required: true, minlength: 10 },
                ruangan_id: { required: true },
                status: { required: true }
            },
            messages: {
                fasilitas_nama: {
                    required: "nama wajib diisi",
                    minlength: "Minimal 3 karakter"
                },
                fasilitas_deskripsi: {
                    required: "kategori wajib diisi",
                    minlength: "Minimal 10 karakter"
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.mb-3').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    dataType: 'json',
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
                            dataFasilitas.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
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