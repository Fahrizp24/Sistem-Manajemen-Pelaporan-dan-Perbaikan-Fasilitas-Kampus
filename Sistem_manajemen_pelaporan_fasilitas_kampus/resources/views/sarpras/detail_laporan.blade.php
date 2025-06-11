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
        <div class="row">
            <!-- Kolom Foto (Kiri) -->
            <div class="col-md-4">
                @if ($laporan->foto)
                    <img src="{{ Storage::url('foto_laporan/' . $laporan->foto) }}" class="img-thumbnail w-100 mb-3"
                        style="max-height: 300px; object-fit: contain;">
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-image"></i> Tidak ada foto
                    </div>
                @endif
            </div>

            <!-- Kolom Informasi (Kanan) -->
            <div class="col-md-8">
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
                            <th>Deskripsi</th>
                            <td>{{ $laporan->deskripsi }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3 text-center">
            @if ($source == 'pelapor')
                <form class="form" method="POST"
                    action="{{ url('/sarpras/laporan_masuk/terima/' . $laporan->laporan_id) }}"
                    enctype="multipart/form-data" data-parsley-validate>
                    @csrf
                    <div class="row">
                        <div class="divider divider-left">
                            <h4 class="divider-text">Berikan Penilaian Kriteria</h4>
                        </div>

                        @foreach ($kriteria as $kriteriaItem)
                            <div class="col-12">
                                <div class="form-group mandatory mt-3 divider divider-left">
                                    <label for="kriteria_{{ $kriteriaItem->kriteria_id }}" class="form-label divider-text"
                                        style="padding:0">{{ $kriteriaItem->nama }}</label>
                                    <select id="kriteria_{{ $kriteriaItem->kriteria_id }}"
                                        name="kriteria[{{ $kriteriaItem->kriteria_id }}]" class="form-select"
                                        data-parsley-required="true" required>
                                        <option value="">Pilih {{ strtolower($kriteriaItem->nama) }}</option>
                                        @foreach ($crisp->where('kriteria_id', $kriteriaItem->kriteria_id) as $crispItem)
                                            <option value="{{ $crispItem->poin }}">{{ $crispItem->judul }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-success mt-3 gap-2"
                        onclick="return confirm('Anda Yakin Untuk Menerima Laporan Ini?')">
                        Terima Laporan
                    </button>
                </form>
                <form action="{{ url('/sarpras/laporan_masuk/tolak/' . $laporan->laporan_id) }}" method="POST">
                    @csrf
                    <div class="col-12">
                        <div class="form-group mandatory mt-3 divider divider-left">
                            <label for="alasan_ditolak" class="form-label divider-text" style="padding:0">Alasan
                                Penolakan</label>
                            <input type="text" id="alasan_ditolak" name="alasan_ditolak" class="form-control"
                                placeholder="Masukkan alasan penolakan" required>
                            <span class="error-text" id="error-alasan_tolak"></span>
                            <span class="text-muted">*Wajib diisi jika menolak laporan</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger mt-3"
                        onclick="return confirm('Anda Yakin Untuk Menolak Laporan Ini?')">
                        Tolak Laporan
                    </button>
                </form>
                </form>
            @elseif($source == 'admin')
                <form action="{{ url('/sarpras/laporan_masuk/pilih_teknisi/' . $laporan->laporan_id) }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <select class="form-select mb-2" name="teknisi" required>
                                <option value="" disabled selected>Pilih Teknisi</option>
                                @foreach ($teknisi as $item)
                                    <option value="{{ $item->pengguna_id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirmSubmit()">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            @elseif($source == 'teknisi')
                <span>Foto Hasil Pengerjaan</span>
                <img src="{{ Storage::url('foto_pengerjaan/' . $laporan->foto_pengerjaan) }}"
                    class="img-thumbnail w-150% mx-auto d-block" style="max-width: 100%; height: 100%; max-height: 400px;">
                <form action="{{ url('/sarpras/laporan_masuk/selesaikan/' . $laporan->laporan_id) }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <select class="form-select mb-2" name="hasil" required>
                                <option value="" disabled selected>Status Penyelesaian</option>
                                <option value="selesai">Tutup dan Selesai</option>
                                <option value="revisi">Revisi</option>
                            </select>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirmSubmit()">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
                <form action="{{ url('/sarpras/laporan_masuk/tolak/' . $laporan->laporan_id) }}" method="POST">
                    @csrf
                    <div class="col-12">
                        <div class="form-group mandatory mt-3 divider divider-left">
                            <label for="alasan_ditolak" class="form-label divider-text" style="padding:0">Alasan
                                Penolakan</label>
                            <input type="text" id="alasan_ditolak" name="alasan_ditolak" class="form-control"
                                placeholder="Masukkan alasan penolakan" required>
                            <span class="error-text" id="error-alasan_tolak"></span>
                            <span class="text-muted">*Wajib diisi jika menolak laporan</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger mt-3"
                        onclick="return confirm('Anda Yakin Untuk Menolak Laporan Ini?')">
                        Tolak Laporan
                    </button>
                </form>
            @elseif($source == 'ajukan')
                <form action="{{ url('/sarpras/ajukan_laporan/' . $laporan->laporan_id) }}" method="POST">
                    @csrf
                    <div class="row justify-content-right">
                        <div class="col-md-6 mx-auto">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirmSubmit()">
                                Ajukan Laporan ke Admin
                            </button>
                        </div>
                    </div>
                </form>
            @endif
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
