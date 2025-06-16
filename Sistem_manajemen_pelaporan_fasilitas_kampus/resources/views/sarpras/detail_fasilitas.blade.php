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
    <!-- Modal untuk Foto Pelapor -->
    <div class="modal fade" id="fotoPelaporModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoPelaporModalLabel">Foto Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalFotoPelapor" src="" class="img-fluid" style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="table-responsive">
                <tr>
                    <span>Laporan ini dilaporkan oleh:</span>
                    <span>{{ $jumlahPelapor }} orang</span>
                </tr>
                <table class="table table-bordered">
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
                        <td>{{ $fasilitas->status }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Laporan</th>
                        <td>{{ $laporan[0]['id'] }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Oleh</th>
                        <td>{{ $laporan[0]['sarpras'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ditugaskan Kepada</th>
                        <td>{{ $laporan[0]['teknisi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $laporan[0]['deskripsi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pelapor</th>
                        <td>
                            @foreach ($laporan as $laporanItem)
                                <div class="d-flex align-items-center mb-2">
                                    <button class="btn btn-sm btn-outline-primary d-flex align-items-center"
                                        onclick="showFotoPelapor('{{ asset('storage/foto_laporan/' . $laporanItem['foto']) }}', '{{ $laporanItem['nama'] }}')">
                                        <img src="{{ asset('storage/foto_laporan/' . $laporanItem['foto']) }}"
                                            alt="Foto {{ $laporanItem['nama'] }}" width="40" height="40"
                                            class="square me-2">
                                        <span>{{ $laporanItem['nama'] }}</span>
                                    </button>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </table>
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
                <form action="{{ url('/sarpras/laporan_masuk/pilih_teknisi/' . $fasilitas->fasilitas_id) }}"
                    method="POST">
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
                                Tugaskan Teknisi
                            </button>
                        </div>
                    </div>
                </form>
            @elseif($source == 'teknisi')
                <span>Foto Hasil Pengerjaan</span>
                <img src="{{ Storage::url('foto_pengerjaan/' . $laporan[0]['foto_pengerjaan']) }}"
                    class="img-thumbnail w-150% mx-auto d-block"
                    style="max-width: 100%; height: 100%; max-height: 400px;">

                {{-- Form Untuk selesaikan laporan --}}
                <form action="{{ url('/sarpras/laporan_masuk/selesaikan/' . $fasilitas->fasilitas_id) }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirmSubmit()">
                                Selesaikan Laporan
                            </button>
                        </div>
                    </div>
                </form>
                <br>

                {{-- Form untuk revisi laporan --}}
                <form action="{{ url('/sarpras/laporan_masuk/revisi/' . $fasilitas->fasilitas_id) }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <textarea name="alasan_revisi" class="form-control mb-2" placeholder="Masukkan Alasan Revisi" required></textarea>
                            <span class="error-text" id="error-alasan_revisi"></span>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirmSubmit()">
                                Revisi
                            </button>
                        </div>
                    </div>
                </form>
            @elseif($source == 'ajukan')
                <form action="{{ url('/sarpras/ajukan_laporan/' . $fasilitas->fasilitas_id) }}" method="POST">
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
    <script>
        function showFotoPelapor(fotoUrl, namaPelapor) {
            document.getElementById('modalFotoPelapor').src = fotoUrl;
            document.getElementById('fotoPelaporModalLabel').textContent = 'Foto Pelapor: ' + namaPelapor;
            var modal = new bootstrap.Modal(document.getElementById('fotoPelaporModal'));
            modal.show();
        }

        // Untuk foto pengerjaan (jika ada)
        @if ($source == 'teknisi' && isset($laporan[0]['foto_pengerjaan']))
            // Ganti tampilan langsung dengan tombol
            document.querySelector('span:contains("Foto Hasil Pengerjaan")').parentElement.innerHTML = `
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#fotoPengerjaanModal">
                        <i class="fas fa-eye me-2"></i> Lihat Foto Hasil Pengerjaan
                    </button>

                    <!-- Modal Foto Pengerjaan -->
                    <div class="modal fade" id="fotoPengerjaanModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Foto Hasil Pengerjaan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ Storage::url('foto_pengerjaan/' . $laporan[0]['foto_pengerjaan']) }}" 
                                         class="img-fluid" 
                                         style="max-height: 70vh;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        @endif
    </script>
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

        // $('form[action*="/sarpras/laporan_masuk/tolak/"]').validate({
        //     submitHandler: function(form) {
        //         $.ajax({
        //             url: form.action,
        //             type: form.method,
        //             data: $(form).serialize(),
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function(response) {
        //                 if (response.status) {
        //                     $('#detailModal').fadeOut(300, function() {
        //                         $(this).modal('hide');
        //                     });

        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Berhasil ditolak',
        //                         text: response.message
        //                     });
        //                 } else {
        //                     $('.error-text').text('');
        //                     if (response.msgField) {
        //                         $.each(response.msgField, function(prefix, val) {
        //                             $('#error-' + prefix).text(val[0]);
        //                         });
        //                     }

        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Terjadi Kesalahan',
        //                         text: response.message
        //                     });
        //                 }
        //             }
        //         });
        //         return false;
        //     },
        //     errorElement: 'span',
        //     errorPlacement: function(error, element) {
        //         error.addClass('invalid-feedback');
        //         element.closest('.form-group').append(error);
        //     },
        //     highlight: function(element, errorClass, validClass) {
        //         $(element).addClass('is-invalid');
        //     },
        //     unhighlight: function(element, errorClass, validClass) {
        //         $(element).removeClass('is-invalid');
        //     }
        // });

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
        $('form[action*="/sarpras/laporan_masuk/revisi/"]').on('submit', function(e) {
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

        // Form Revisi Penugasan (Teknisi)
        $('form[action*="/teknisi/penugasan/revisi/"]').on('submit', function(e) {
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
