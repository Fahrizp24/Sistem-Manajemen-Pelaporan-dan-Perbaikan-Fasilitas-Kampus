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
                        <div class="col-md-12 mb-3">
                            <label for="gedung_id">Gedung</label>
                            <select name="gedung_id" id="gedung_id" class="form-control" required>
                                <option value="">-- Pilih Gedung --</option>
                                @foreach ($gedung as $g)
                                    <option value="{{ $g->gedung_id }}">{{ $g->gedung_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="lantai_id">Lantai</label>
                            <select name="lantai_id" id="lantai_id" class="form-control" required disabled>
                                <option value="">-- Pilih Lantai --</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama">Nama Ruangan</label>
                            <input type="text" name="ruangan_nama" id="ruangan_nama" class="form-control" required disabled>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="ruangan_deskripsi" id="ruangan_deskripsi" class="form-control" rows="3" required disabled></textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" id="submit-btn" class="btn btn-primary me-1 mb-1"disabled>Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    // Inisialisasi - disable semua input kecuali gedung
    $('#lantai_id, #ruangan_nama, #ruangan_deskripsi, #submit-btn').prop('disabled', true);
    
    // Handler ketika gedung dipilih
    $('#gedung_id').change(function() {
        const gedungId = $(this).val();
        
        if (gedungId) {
            // Reset dan enable lantai
            $('#lantai_id').empty().append('<option value="">-- Pilih Lantai --</option>').prop('disabled', false);
            
            // Ambil data lantai berdasarkan gedung
            $.get(`/admin/ruangan/get-lantai/${gedungId}`, function(data) {
                $.each(data, function(key, value) {
                    $('#lantai_id').append(`<option value="${value.lantai_id}">${value.lantai_nama}</option>`);
                });
            });
            
            // Tetap disable input lainnya
            $('#ruangan_nama, #ruangan_deskripsi, #submit-btn').prop('disabled', true);
        } else {
            // Reset semua jika gedung tidak dipilih
            $('#lantai_id').empty().append('<option value="">-- Pilih Lantai --</option>').prop('disabled', true);
            $('#ruangan_nama, #ruangan_deskripsi, #submit-btn').prop('disabled', true);
        }
    });
    
    // Handler ketika lantai dipilih
    $('#lantai_id').change(function() {
        if ($(this).val()) {
            // Enable semua input form
            $('#ruangan_nama, #ruangan_deskripsi').prop('disabled', false);
            validateForm();
        } else {
            $('#ruangan_nama, #ruangan_deskripsi, #submit-btn').prop('disabled', true);
        }
    });
    
    // Validasi saat input berubah
    $('input, select, textarea').on('change input', function() {
        validateForm();
    });
    
    // Fungsi validasi form
    function validateForm() {
        const allFilled = $('#gedung_id').val() && 
                         $('#lantai_id').val() && 
                         $('#ruangan_nama').val().trim() && 
                         $('#ruangan_deskripsi').val().trim();
        
        $('#submit-btn').prop('disabled', !allFilled);
    }

    // Validasi form dengan jQuery Validate
    $("#form-tambah").validate({
        rules: {
            gedung_id: { required: true },
            lantai_id: { required: true },
            ruangan_nama: { required: true, minlength: 3 },
            ruangan_deskripsi: { required: true, minlength: 10 }
        },
        messages: {
            ruangan_nama: {
                required: "Nama wajib diisi",
                minlength: "Minimal 3 karakter"
            },
            ruangan_deskripsi: {
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
                        tableRuangan.ajax.reload(null, false);
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
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan pada server'
                        });
                    }
                }
            });
            return false;
        }
    });
});
</script>