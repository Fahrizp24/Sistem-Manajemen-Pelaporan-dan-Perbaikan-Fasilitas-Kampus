@empty($laporan)
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Kesalahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                        Data yang anda cari tidak ditemukan
                    </div>
                    <a href="{{ url('/teknisi/penugasan') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered">
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
                    <td>{{ $laporan->status }}</td>
                </tr>
                <tr>
                    <th>Tanggal Laporan</th>
                    <td>{{ $laporan->created_at->format('d M Y H:i') }}</td>
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
                            <img src="{{ Storage::url('foto_laporan/' . $laporan->foto) }}" class="img-thumbnail"
                                style="max-width: 200px;">
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
        </div>

        <div class="mt-3 text-center">
            <form action="{{ url('/admin/laporan_masuk/' . $laporan->laporan_id) }}" method="POST">
                @csrf
                <div class="row justify-content-right">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success w-100" onclick="return confirmSubmit()">
                            Konfirmasi Laporan dan Laksanakan Penugasan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endempty
<script>
    $(document).ready(function() {

        // Form terima Laporan (Pelapor)
        $('form[action*="/sarpras/laporan_masuk/terima/"]').validate({
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

        $('form[action*="/sarpras/laporan_masuk/tolak/"]').validate({
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
                                title: 'Berhasil ditolak',
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

        // Form Pilih Teknisi (Admin)
        $('form[action*="/sarpras/laporan_masuk/pilih_teknisi/"]').validate({
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
                            $('#myModal').fadeOut(300, function() {
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

        // Form Selesaikan Penugasan (Teknisi)
        $('form[action*="/teknisi/penugasan/selesaikan/"]').on('submit', function(e) {
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
                            $('#myModal').fadeOut(300, function() {
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
