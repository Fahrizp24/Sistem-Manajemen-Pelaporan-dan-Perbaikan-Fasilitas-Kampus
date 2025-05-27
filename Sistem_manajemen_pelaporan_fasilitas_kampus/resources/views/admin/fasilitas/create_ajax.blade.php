<form action="{{ route('admin.store_fasilitas') }}" method="POST" id="form-tambah">
    @csrf
    <div class="card-header">
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama">Nama Fasilitas</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status">Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Pendingin">Pendingin</option>
                        <option value="Alat Tulis">Alat Tulis</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gedung_id">Gedung</label>
                    <select name="gedung_id" class="form-control" required>
                        <option value="">-- Pilih Gedung --</option>
                        @foreach($gedung as $g)
                            <option value="{{ $g->gedung_id }}">{{ $g->nama }}</option>
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
<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                nama: { required: true, minlength: 3 },
                kategori: { required: true },
                deskripsi: { required: true, minlength: 10 },
                gedung: { required: true },
                status: { required: true }
            },
            messages: {
                nama: {
                    required: "nama wajib diisi",
                    minlength: "Minimal 3 karakter"
                },
                deskripsi: {
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