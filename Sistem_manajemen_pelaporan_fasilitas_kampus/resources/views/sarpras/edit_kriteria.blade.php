@empty($kriteria)
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
<form action="{{ route('sarpras.update_kriteria' ) }}" method="POST" id="form-tambah">
    @csrf
    <div class="card-header">
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <input type="hidden" name="id" value="{{ $kriteria->kriteria_id }}">
                <div class="col-md-4"><label>Kode Kriteria</label></div>
                <div class="col-md-8">
                    <input type="text" name="kode" class="form-control" placeholder="Kode Kriteria" value="{{ $kriteria->kode }}">
                </div>
                <div class="col-md-4"><label>Nama Kriteria</label></div>    
                <div class="col-md-8">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Kriteria" value="{{ $kriteria->nama }}">
                </div>
                <div class="col-md-4"><label>Jenis Kriteria</label></div>
                <div class="col-md-8">
                    <select name="jenis" class="form-control">
                        <option value="Benefit" {{ $kriteria->jenis == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                        <option value="Cost" {{ $kriteria->jenis == 'Cost' ? 'selected' : '' }}>Cost</option>
                    </select>
                </div>
                <div class="col-md-4"><label>Bobot Kriteria</label></div>
                <div class="col-md-8">
                    <input type="number" name="bobot" class="form-control" placeholder="Bobot Kriteria" value="{{ $kriteria->bobot }}" min="0" step="0.01" max="1">
                </div>
                <div class="col-md-4"><label>Deskripsi Kriteria</label></div>
                <div class="col-md-8">
                    <textarea name="deskripsi" class="form-control" placeholder="Deskripsi Kriteria">{{ $kriteria->deskripsi }}</textarea>
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
                kode: {
                    required: true,
                    minlength: 2
                },
                nama: {
                    required: true,
                    minlength: 3
                },
                jenis: {
                    required: true
                },
                bobot: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 1
                },
                deskripsi: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                kode: {
                    required: "Kode kriteria harus diisi",
                    minlength: "Kode minimal 3 karakter"
                },
                nama: {
                    required: "Nama kriteria harus diisi",
                    minlength: "Nama minimal 3 karakter"
                },
                jenis: {
                    required: "Jenis kriteria harus dipilih"
                },
                bobot: {
                    required: "Bobot kriteria harus diisi",
                    number: "Bobot harus berupa angka",
                    min: "Bobot tidak boleh kurang dari 0",
                    max: "Bobot tidak boleh lebih dari 1"
                },
                deskripsi: {
                    required: "Deskripsi kriteria harus diisi",
                    minlength: "Deskripsi minimal 5 karakter"
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
                    method: form.method,
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
