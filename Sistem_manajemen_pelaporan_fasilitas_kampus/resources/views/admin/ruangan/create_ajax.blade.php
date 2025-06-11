<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ route('admin.store_ruangan') }}" method="POST" id="form-tambah">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama">Nama Ruangan</label>
                            <input type="text" name="ruangan_nama" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="ruangan_deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="lantai_id">Lantai</label>
                            <select name="lantai_id" class="form-control" required>
                                <option value="">-- Pilih Lantai --</option>
                                @foreach ($lantai as $l)
                                    <option value="{{ $l->lantai_id }}">{{ $l->lantai_nama }}</option>
                                @endforeach
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
                ruangan_nama: { required: true, minlength: 3 },
                ruangan_deskripsi: { required: true, minlength: 10 },
                lantai_id: { required: true }
            },
            messages: {
                ruangan_nama: {
                    required: "nama wajib diisi",
                    minlength: "Minimal 3 karakter"
                },
                ruangan_deskripsi: {
                    required: "deskripsi wajib diisi",
                    minlength: "Minimal 10 karakter"
                },
                lantai_id: {
                    required: "lantai wajib diisi"
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
                            $('#modalRuangan').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            dataRuangan.ajax.reload(null, false);
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