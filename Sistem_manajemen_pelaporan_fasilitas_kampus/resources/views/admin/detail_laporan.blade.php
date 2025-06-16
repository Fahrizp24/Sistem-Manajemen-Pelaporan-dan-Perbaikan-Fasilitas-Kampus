@empty($fasilitas)
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
    </div>
    <div class="modal-body">
        @dd($fasilitas)
        <!-- Kolom Informasi (Full width) -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        {{-- @dd($laporan[0]->id); --}}
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
                                @foreach ($laporan as $item)
                                    <div class="d-flex align-items-center mb-2">
                                        <button class="btn btn-sm btn-outline-primary d-flex align-items-center p-1"
                                            onclick="showFotoPelapor('{{ asset('storage/foto_laporan/' . $item['foto']) }}', '{{ $item['nama'] }}')">
                                            <img src="{{ asset('storage/foto_laporan/' . $item['foto']) }}"
                                                alt="Foto {{ $item['nama'] }}" width="40" height="40"
                                                class="square me-2">
                                            <span>{{ $item['nama'] }}</span>
                                        </button>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- Tombol Aksi -->
        <div class="mt-3 text-center">
            <form action="{{ url('/admin/laporan_masuk/' . $fasilitas->fasilitas_id) }}" method="POST">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <button type="submit" id="btn-submit-laporan" class="btn btn-success w-100">
                            Konfirmasi Laporan dan Laksanakan Penugasan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
@endempty
<script>
    function showFotoPelapor(fotoUrl, namaPelapor) {
        document.getElementById('modalFotoPelapor').src = fotoUrl;
        document.getElementById('fotoPelaporModalLabel').textContent = 'Foto Laporan: ' + namaPelapor;
        var modal = new bootstrap.Modal(document.getElementById('fotoPelaporModal'));
        modal.show();
    }

    $(document).ready(function() {
        const formSelector = 'form[action*="/admin/laporan_masuk/"]';

        $(formSelector).on('submit', function(e) {
            e.preventDefault(); 

            const form = this;

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
        });
    });
</script>
