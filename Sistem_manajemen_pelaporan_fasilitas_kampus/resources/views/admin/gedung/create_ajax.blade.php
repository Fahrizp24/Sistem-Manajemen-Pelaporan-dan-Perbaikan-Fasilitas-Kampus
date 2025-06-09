<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ route('admin.store_gedung') }}" method="POST" id="form-tambah">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Gedung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama">Nama Gedung</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
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
                nama: { required: true, minlength: 3 },
                deskripsi: { required: true, minlength: 10 },
            },
            messages: {
                nama: {
                    required: "nama wajib diisi",
                    minlength: "Minimal 3 karakter"
                },
                deskripsi: {
                    required: "deskripsi wajib diisi",
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
                            dataGedung.ajax.reload(null, false);
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