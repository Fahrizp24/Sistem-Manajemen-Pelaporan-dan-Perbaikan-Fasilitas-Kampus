@empty($fasilitas)
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
            <!-- Kolom Informasi (Dua Pertiga Lebar) -->
            <div class="col-12">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>Gedung</th>
                        <td>{{ $fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
                    </tr>
                    <tr>
                        <th>Lantai</th>
                        <td>{{ $fasilitas->ruangan->lantai->lantai_nama }}</td>
                    </tr>
                    <tr>
                        <th>Ruangan</th>
                        <td>{{ $fasilitas->ruangan->ruangan_nama }}</td>
                    </tr>
                    <tr>
                        <th>Fasilitas</th>
                        <td>{{ $fasilitas->fasilitas_nama }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $fasilitas->laporan[0]->status == 'diterima' ? 'success' : 'warning' }}">
                                {{ ucfirst($fasilitas->laporan[0]->status) }}
                            </span>
                        </td>   
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ $fasilitas->laporan[0]->created_at->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Oleh</th>
                        <td>{{ $fasilitas->laporan[0]->sarpras->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Kepada</th>
                        <td>{{ $fasilitas->laporan[0]->teknisi->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $fasilitas->laporan[0]->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pelapor</th>
                        <td>
                            @foreach ($fasilitas->laporan as $laporan)
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset('storage/foto_laporan/' . $laporan->foto) }}" 
                                         width="40" height="40" 
                                         class="rounded-circle me-2">
                                    <span>{{ $laporan->pelapor->nama }}</span>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer text-center mt-3">
            <form action="{{ url('/teknisi/penugasan/' . $fasilitas->fasilitas_id) }}" method="POST" enctype="multipart/form-data" id="formBukti">
                @csrf
                <!-- Upload file -->
                <div class="form-group">
                    <label for="foto_pengerjaan">Upload Foto Bukti Pengerjaan</label>
                    <input type="file" name="foto_pengerjaan" id="foto_pengerjaan" class="form-control" required>
                    <span class="text-danger error-text" id="error-foto_pengerjaan"></span>
                </div>

                <!-- Tombol ajukan -->
                <button type="submit" class="btn btn-success px-4" id="btnAjukan">
                    <i class="fas fa-paper-plane me-2"></i> Ajukan ke Sarpras
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