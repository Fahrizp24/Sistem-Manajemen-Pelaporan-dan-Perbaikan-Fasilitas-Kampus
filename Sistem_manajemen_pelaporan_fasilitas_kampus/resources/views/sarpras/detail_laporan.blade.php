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
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>Pelapor</th>
                    <td>{{ $laporan->pelapor->nama }}</td>
                </tr>
                <tr>
                    <th>Gedung</th>
                    <td>{{ $laporan->fasilitas->gedung->nama }}</td>
                </tr>
                <tr>
                    <th>Fasilitas</th>
                    <td>{{ $laporan->fasilitas->nama }}</td>
                </tr>
                <tr>
                    <th>Status </th>
                    <td>{{ $laporan->status }}</td>
                </tr>
                <tr>
                    <th>Tanggal Laporan</th>
                    <td>{{ $laporan->created_at }}</td>
                </tr>
                <tr>
                    <th>Urgensi</th>
                    <td>{{ $laporan->urgensi }}</td>
                </tr>
                <tr>
                    <th>Ditugaskan Oleh</th>
                    <td>{{ $laporan->sarpras->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Ditugaskan Kepada</th>
                    <td>{{ $laporan->teknisi->nama ?? '-' }}</td>
                <tr>
                    <th>Foto</th>
                    <td>
                        @if ($laporan->foto)
                            <div style="max-width: 200px; max-height: 200px; overflow: ">
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
            <div class="mt-3 text-end">
                @if ($source == 'pelapor')
                    <form action="{{ url('/sarpras/laporan_masuk/terima/' . $laporan->laporan_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Anda Yakin Untuk Meneterima Laporan Ini?')">
                            Menerima Laporan
                        </button>
                    </form>
                    <form action="{{ url('/sarpras/laporan_masuk/tolak/' . $laporan->laporan_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Anda Yakin Untuk Menolak Laporan Ini?')">
                            Menolak Laporan
                        </button>
                    </form>
                @elseif($source == 'admin')
                    <form action="{{ url('/sarpras/laporan_masuk/pilih_teknisi/' . $laporan->laporan_id) }}"
                        method="POST">
                        @csrf
                        <select class="form-select" name="teknisi" id="teknisi" required>
                            <option value="" disabled selected>Pilih Teknisi</option>
                            @foreach ($teknisi as $item)
                                <option value="{{ $item->pengguna_id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success" onclick="return confirmSubmit()">
                                Submit
                            </button>
                        </div>
                    </form>
                @elseif($source == 'teknisi')
                    <form action="{{ url('/sarpras/laporan_masuk/selesaikan/' . $laporan->laporan_id) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <select class="form-select" name="hasil" id="hasil" required>
                                    <option value="" disabled selected>Status Penyelesaian</option>
                                    <option value="selesai">Tutup dan Selesai</option>
                                    <option value="revisi">Revisi</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success" onclick="return confirmSubmit()">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

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
