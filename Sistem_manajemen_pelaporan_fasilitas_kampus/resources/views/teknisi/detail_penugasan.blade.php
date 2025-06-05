@empty($laporan)
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
                <a href="{{ url('/teknisi/penugasan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="card-body">
        <div class="row">
            <!-- Kolom Foto (Sepertiga Lebar) -->
            <div class="col-md-4">
                @if ($laporan->foto)
                    <div class="mb-3" style="max-width: 100%; max-height: 300px; overflow: hidden;">
                        <img src="{{ Storage::url('foto_laporan/' . $laporan->foto) }}" 
                             class="img-fluid rounded shadow-sm"
                             style="width: 100%; height: auto; object-fit: cover;">
                    </div>
                @else
                    <div class="alert alert-secondary text-center py-5">
                        <i class="fas fa-image fa-3x mb-3"></i><br>
                        Tidak ada foto laporan
                    </div>
                @endif
            </div>
            
            <!-- Kolom Informasi (Dua Pertiga Lebar) -->
            <div class="col-md-8">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th width="30%">Pelapor</th>
                        <td>{{ $laporan->pelapor->nama }}</td>
                    </tr>
                    <tr>
                        <th>Gedung</th>
                        <td>{{ $laporan->fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
                    </tr>
                    <tr>
                        <th>Lantai</th>
                        <td>{{ $laporan->fasilitas->ruangan->lantai->lantai_nama }}</td>
                    </tr>
                    <tr>
                        <th>Ruangan</th>
                        <td>{{ $laporan->fasilitas->ruangan->ruangan_nama }}</td>
                    </tr>
                    <tr>
                        <th>Fasilitas</th>
                        <td>{{ $laporan->fasilitas->fasilitas_nama }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $laporan->status == 'diterima' ? 'success' : 'warning' }}">
                                {{ $laporan->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ $laporan->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Oleh</th>
                        <td>{{ $laporan->sarpras->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Kepada</th>
                        <td>{{ $laporan->teknisi->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $laporan->deskripsi }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card-footer text-center mt-3">
            <form action="{{ url('/teknisi/penugasan/' . $laporan->laporan_id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success px-4" 
                    onclick="return confirm('Anda Yakin Untuk Mengkonfirmasi Laporan Ini?')">
                    <i class="fas fa-paper-plane me-2"></i> Ajukan ke Sarpras
                </button>
            </form>
        </div>
    </div>
@endempty
<script>
    $(document).ready(function() {
        $('form[action*="/teknisi/penugasan/telah_diperbaiki"]').validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#detailModal').fadeOut(300, function() {
                                $(this).modal('hide');
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                        } else {
                            $('.error-text').text('');
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>