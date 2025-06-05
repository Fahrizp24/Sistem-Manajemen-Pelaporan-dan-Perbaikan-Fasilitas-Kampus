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
        {{-- Tampilkan detail laporan --}}
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>Pelapor</th>
                <td>{{ $laporan->pelapor->nama }}</td>
            </tr>
            <tr>
                <th>Gedung</th>
                <td>{{ $laporan->fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
            </tr>
            <tr>
                <th>Fasilitas</th>
                <td>{{ $laporan->fasilitas->fasilitas_nama }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $laporan->status }}</td>
            </tr>
            <tr>
                <th>Tanggal Laporan</th>
                <td>{{ $laporan->created_at }}</td>
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
                <th>Foto</th>
                <td>
                    @if ($laporan->foto)
                        <div style="max-width: 200px; max-height: 200px; overflow: hidden;">
                            <img src="{{ Storage::url('foto_laporan/' . $laporan->foto) }}" class="img-fluid"
                                style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                    @else
                        <span class="text-muted">Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $laporan->deskripsi }}</td>
            </tr>
        </table>

        <h5>Bukti Perbaikan Fasilitas</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tbody>
                    <tr>
                        <th width="30%">Foto Bukti Perbaikan</th>
                        <td>
                            <div class="form-group">
                                <input type="file" id="foto" class="form-control-file" name="foto"
                                    accept="image/jpeg, image/png, image/jpg, image/gif" required>
                                <small class="form-text text-muted">Format: jpeg, png, jpg, gif (max: 2MB)</small>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi Singkat</th>
                        <td>
                            <div class="form-group">
                                <textarea id="deskripsi" class="form-control" name="deskripsi" rows="3"
                                    placeholder="Jelaskan kondisi fasilitas yang telah anda perbaiki" required></textarea>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer text-end">
            <form action="{{ url('/teknisi/penugasan/' . $laporan->laporan_id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('Anda Yakin Untuk Mengkonfirmasi Laporan Ini?')">
                    <i class="fas fa-paper-plane"></i> Ajukan ke Sarpras
                </button>
            </form>
        </div>
    </div>
@endempty
<script>
    $(document).ready(function () {
        $('form[action*="/teknisi/penugasan/telah_diperbaiki"]').validate({
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status) {
                            $('#detailModal').fadeOut(300, function () {
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
                                $.each(response.msgField, function (prefix, val) {
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
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>