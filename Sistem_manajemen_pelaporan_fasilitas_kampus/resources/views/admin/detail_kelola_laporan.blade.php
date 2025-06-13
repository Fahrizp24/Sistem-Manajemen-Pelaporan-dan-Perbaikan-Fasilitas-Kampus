@empty($laporan)
    <div class="alert alert-danger">
        <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
        Data yang anda cari tidak ditemukan
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th width="30%">Pelapor</th>
                <td>{{ $laporan->pelapor->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Gedung</th>
                <td>{{ $laporan->fasilitas->ruangan->lantai->gedung->gedung_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Lantai</th>
                <td>{{ $laporan->fasilitas->ruangan->lantai->lantai_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Ruangan</th>
                <td>{{ $laporan->fasilitas->ruangan->ruangan_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Fasilitas</th>
                <td>{{ $laporan->fasilitas->fasilitas_nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @switch($laporan->status)
                        @case('diajukan') <span class="badge bg-secondary">Diajukan</span> @break
                        @case('diproses') <span class="badge bg-warning">Diproses</span> @break
                        @case('selesai') <span class="badge bg-success">Selesai</span> @break
                        @case('ditolak') <span class="badge bg-danger">Ditolak</span> @break
                        @default <span class="badge bg-light">{{ $laporan->status }}</span>
                    @endswitch
                </td>
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

    <div class="row mt-4">
        <div class="col-12">
            @if ($laporan->foto)
                <img src="{{ Storage::url('foto_laporan/'.$laporan->foto ) }}" 
                     class="img-thumbnail w-150% mx-auto d-block"
                     style="max-width: 100%; height: 100%; max-height: 400px;">
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-image"></i> Tidak ada foto
                </div>
            @endif
        </div>
    </div>
@endempty