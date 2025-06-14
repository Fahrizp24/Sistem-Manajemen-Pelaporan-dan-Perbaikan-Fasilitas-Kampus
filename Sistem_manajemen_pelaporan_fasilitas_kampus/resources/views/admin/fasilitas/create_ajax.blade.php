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
                    <!-- Baris 1: Gedung dan Lantai -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gedung_id" class="form-label">Gedung</label>
                            <select name="gedung_id" id="gedung_id" class="form-select" required>
                                <option value="">-- Pilih Gedung --</option>
                                @foreach($gedung as $g)
                                    <option value="{{ $g->gedung_id }}">{{ $g->gedung_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lantai_id" class="form-label">Lantai</label>
                            <select name="lantai_id" id="lantai_id" class="form-select" required disabled>
                                <option value="">-- Pilih Lantai --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Baris 2: Ruangan (Full Width) -->
                    <div class="mb-3">
                        <label for="ruangan_id" class="form-label">Ruangan</label>
                        <select name="ruangan_id" id="ruangan_id" class="form-select" required disabled>
                            <option value="">-- Pilih Ruangan --</option>
                        </select>
                    </div>

                    <!-- Baris 3: Nama Fasilitas (Full Width) -->
                    <div class="mb-3">
                        <label for="fasilitas_nama" class="form-label">Nama Fasilitas</label>
                        <input type="text" name="fasilitas_nama" id="fasilitas_nama" class="form-control" required disabled>
                    </div>

                    <!-- Baris 4: Status (Full Width) -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required disabled>
                            <option value="normal">Normal</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>

                    <!-- Baris 5: Deskripsi (Full Width) -->
                    <div class="mb-3">
                        <label for="fasilitas_deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="fasilitas_deskripsi" id="fasilitas_deskripsi" class="form-control" rows="3" required disabled></textarea>
                    </div>

                    <!-- Baris 6: Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit-btn" class="btn btn-primary px-4" disabled>
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    // Inisialisasi - disable semua input kecuali gedung
    $('#lantai_id, #ruangan_id, #fasilitas_nama, #fasilitas_deskripsi, #status, #submit-btn').prop('disabled', true);
    
    // Handler ketika gedung dipilih
    $('#gedung_id').change(function() {
        const gedungId = $(this).val();
        
        if (gedungId) {
            // Reset dan disable lantai dan ruangan
            $('#lantai_id').empty().append('<option value="">-- Pilih Lantai --</option>').prop('disabled', false);
            $('#ruangan_id').empty().append('<option value="">-- Pilih Ruangan --</option>').prop('disabled', true);
            
            // Ambil data lantai berdasarkan gedung
            $.get(`/admin/fasilitas/get-lantai/${gedungId}`, function(data) {
                $.each(data, function(key, value) {
                    $('#lantai_id').append(`<option value="${value.lantai_id}">${value.lantai_nama}</option>`);
                });
            });
        } else {
            // Reset semua jika gedung tidak dipilih
            $('#lantai_id, #ruangan_id').empty().prop('disabled', true);
            $('#lantai_id').append('<option value="">-- Pilih Lantai --</option>');
            $('#ruangan_id').append('<option value="">-- Pilih Ruangan --</option>');
            disableFormInputs();
        }
    });
    
    // Handler ketika lantai dipilih
    $('#lantai_id').change(function() {
        const lantaiId = $(this).val();
        
        if (lantaiId) {
            // Reset dan enable ruangan
            $('#ruangan_id').empty().append('<option value="">-- Pilih Ruangan --</option>').prop('disabled', false);
            
            // Ambil data ruangan berdasarkan lantai
            $.get(`/admin/fasilitas/get-ruangan/${lantaiId}`, function(data) {
                $.each(data, function(key, value) {
                    $('#ruangan_id').append(`<option value="${value.ruangan_id}">${value.ruangan_deskripsi}</option>`);
                });
            });
        } else {
            // Reset ruangan jika lantai tidak dipilih
            $('#ruangan_id').empty().append('<option value="">-- Pilih Ruangan --</option>').prop('disabled', true);
            disableFormInputs();
        }
    });
    
    // Handler ketika ruangan dipilih
    $('#ruangan_id').change(function() {
        if ($(this).val()) {
            // Enable semua input form
            $('#fasilitas_nama, #fasilitas_deskripsi, #status').prop('disabled', false);
        } else {
            disableFormInputs();
        }
    });
    
    // Validasi saat input berubah
    $('input, select, textarea').on('change input', function() {
        validateForm();
    });
    
    // Fungsi untuk disable semua input form
    function disableFormInputs() {
        $('#fasilitas_nama, #fasilitas_deskripsi, #status, #submit-btn').prop('disabled', true);
    }
    
    // Fungsi validasi form
    function validateForm() {
        const allFilled = $('#gedung_id').val() && 
                         $('#lantai_id').val() && 
                         $('#ruangan_id').val() && 
                         $('#fasilitas_nama').val().trim() && 
                         $('#fasilitas_deskripsi').val().trim() && 
                         $('#status').val();
        
        $('#submit-btn').prop('disabled', !allFilled);
    }

    // Validasi form dengan jQuery Validate
    $("#form-tambah").validate({
        rules: {
            gedung_id: { required: true },
            lantai_id: { required: true },
            ruangan_id: { required: true },
            fasilitas_nama: { required: true, minlength: 3 },
            fasilitas_deskripsi: { required: true, minlength: 10 },
            status: { required: true }
        },
        messages: {
            fasilitas_nama: {
                required: "Nama wajib diisi",
                minlength: "Minimal 3 karakter"
            },
            fasilitas_deskripsi: {
                required: "Deskripsi wajib diisi",
                minlength: "Minimal 10 karakter"
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
            const btn = $('#submit-btn');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
            
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
                        dataFasilitas.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('Simpan');
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`[name="${key}"]`).addClass('is-invalid');
                            $(`.error-${key}`).remove();
                            $(`[name="${key}"]`).after(`<div class="error-${key} invalid-feedback">${value[0]}</div>`);
                        });
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Simpan');
                }
            });
            return false;
        }
    });
});
</script>