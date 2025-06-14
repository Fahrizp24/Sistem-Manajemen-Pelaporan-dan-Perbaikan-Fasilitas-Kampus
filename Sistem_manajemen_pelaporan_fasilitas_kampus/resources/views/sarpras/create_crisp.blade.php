<form action="{{ route('sarpras.store_crisp') }}" method="POST" id="form-tambah">
    @csrf
    <div class="card-header">
    </div>
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kriteria Crisp</label>
                </div>
                <div class="col-md-8 mb-3"> <!-- Added mb-3 for bottom margin -->
                    <select name="kriteria_id" class="form-select">
                        <option value="">Pilih Kriteria</option>
                        @foreach ($kriteria as $item)
                            <option value="{{ $item->kriteria_id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-md-4">
                    <label class="form-label fw-semibold">Judul Crisp</label>
                </div>
                <div class="col-md-8 mb-3">
                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul crisp">
                </div>

                <!-- Deskripsi Input -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Deskripsi</label>
                </div>
                <div class="col-md-8 mb-3">
                    <textarea name="deskripsi" class="form-control" placeholder="Masukkan deskripsi" rows="3"></textarea>
                </div>

                <!-- Poin Input -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Poin</label>
                </div>
                <div class="col-md-8 mb-4"> 
                    <input type="number" name="poin" id="poin" class="form-control" placeholder="Masukkan poin">
                </div>
                <div class="col-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
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
