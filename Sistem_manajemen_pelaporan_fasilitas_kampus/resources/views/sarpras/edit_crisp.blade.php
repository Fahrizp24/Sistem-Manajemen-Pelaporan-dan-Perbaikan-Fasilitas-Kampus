@empty($crisp)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
            </div>
        </div>
    </div>
@else
<form action="{{ route('sarpras.update_crisp') }}" method="PUT" id="form-tambah">
    @csrf
    <div class="card-header">
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-4"><label>Kriteria Crisp</label></div>
                <div class="col-md-8">
                    <select name="kriteria_id" class="form-control">
                        <option value="{{ $crisp->kriteria->kriteria_id }}" disabled selected>{{ $crisp->kriteria->nama }}</option>
                    </select>
                </div>
                <div class="col-md-4"><label>Judul Crisp</label></div>
                <div class="col-md-8">
                    <input type="text" name="judul" class="form-control" placeholder="Judul" value="{{ $crisp->judul }}">
                </div>

                <div class="col-md-4"><label>Deskripsi</label></div>
                <div class="col-md-8">
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" value="{{ $crisp->deskripsi }}">
                </div>

                <div class="col-md-4"><label>Poin</label></div>
                <div class="col-md-8">
                    <input type="number" name="poin" id="poin" class="form-control" placeholder="Poin" value="{{ $crisp->poin }}">
                </div>

                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                kriteria_id: {
                    required: true
                },
                judul: {
                    required: true,
                    minlength: 3
                },
                deskripsi: {
                    required: true,
                    minlength: 5
                },
                poin: {
                    required: true,
                    number: true,
                    min: 0
                }
            },
            messages: {
                kriteria_id: {
                    required: "Kriteria harus dipilih"
                },
                judul: {
                    required: "Judul harus diisi",
                    minlength: "Judul minimal 3 karakter"
                },
                deskripsi: {
                    required: "Deskripsi harus diisi",
                    minlength: "Deskripsi minimal 5 karakter"
                },
                poin: {
                    required: "Poin harus diisi",
                    number: "Poin harus berupa angka",
                    min: "Poin tidak boleh kurang dari 0"
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
