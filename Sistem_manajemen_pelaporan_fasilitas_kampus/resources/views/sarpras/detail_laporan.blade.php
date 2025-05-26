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
                    <form action="{{ url('/sarpras/laporan_masuk/konfirmasi/' . $laporan->laporan_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Anda Yakin Untuk Mengkonfirmasi Laporan Ini?')">
                            Konfirmasi Laporan
                        </button>
                    </form>
                @elseif($source == 'admin')
                    <form action="{{ url('/sarpras/laporan_masuk/pilih_teknisi/' . $laporan->laporan_id) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Pilih Teknisi
                        </button>
                    </form>
                @elseif($source == 'teknisi')
                    <form action="{{ url('/teknisi/penugasan/selesaikan/' . $laporan->laporan_id) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <select class="form-select" name="status_penugasan" id="statusPenugasan" required>
                                    <option value="" disabled selected>Status Penyelesaian</option>
                                    <option value="selesai">Selesai Diperbaiki</option>
                                    <option value="butuh_bahan">Butuh Bahan/Part</option>
                                    <option value="tidak_bisa">Tidak Bisa Diperbaiki</option>
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

        // Form Konfirmasi Laporan (Pelapor)
        $('form[action*="/sarpras/laporan_masuk/konfirmasi/"]').validate({
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

                            dataBarang.ajax.reload();
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
            e.preventDefault();

            const status = $('#statusPenugasan').val();
            let message = '';

            if (!status) {
                Swal.fire({
                    icon: 'error',
                    title: 'Peringatan',
                    text: 'Pilih status penyelesaian terlebih dahulu!',
                });
                return;
            }

            if (status === 'selesai') {
                message = 'Anda yakin menandai laporan ini sebagai SELESAI DIPERBAIKI?';
            } else if (status === 'butuh_bahan') {
                message = 'Anda yakin melaporkan bahwa perbaikan BUTUH BAHAN/PART?';
            } else if (status === 'tidak_bisa') {
                message = 'Anda yakin melaporkan bahwa perbaikan TIDAK BISA DILAKUKAN?';
            }

            Swal.fire({
                title: 'Konfirmasi Penyelesaian',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    handleFormSubmit($(this), 'Status penugasan berhasil diperbarui!');
                }
            });
        });
    });
</script>
