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
                <a href="{{ url('/teknisi/riwayat_penugasan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="card-body">
        <div class="row">
            <!-- Kolom Informasi (Kanan) -->
            <div class="col-12">
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
                        <th>Deskripsi</th>
                        <td>{{ $laporan->deskripsi }}</td>
                    </tr>
                </table>
            </div>
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    @if ($laporan->foto)
                        <div class="card">
                            <div class="card-header bg-light">
                                <span class="fw-bold">Foto Sebelum Diperbaiki</span>
                            </div>
                            <div class="card-body p-2 text-center">
                                <img src="{{ Storage::url('foto_laporan/' . $laporan->foto) }}" class="img-fluid rounded"
                                    style="max-height: 250px; width: auto;">
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-image"></i> Tidak ada foto sebelum perbaikan
                        </div>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    @if ($laporan->foto_pengerjaan)
                        <div class="card">
                            <div class="card-header bg-light">
                                <span class="fw-bold">Foto Setelah Diperbaiki</span>
                            </div>
                            <div class="card-body p-2 text-center">
                                <img src="{{ Storage::url('foto_pengerjaan/' . $laporan->foto_pengerjaan) }}"
                                    class="img-fluid rounded" style="max-height: 250px; width: auto;">
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-image"></i> Tidak ada foto setelah perbaikan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endempty
