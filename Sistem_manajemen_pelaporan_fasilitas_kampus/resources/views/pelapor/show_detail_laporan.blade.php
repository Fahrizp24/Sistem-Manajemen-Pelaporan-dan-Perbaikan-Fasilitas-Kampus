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
                <a href="{{ url('/pelapor/laporan_saya') }}" class="btn btn-warning">Kembali</a>
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
                        @if($laporan->foto)
                            <div style="max-width: 200px; max-height: 200px; overflow: hidden;">
                                <img src="{{ Storage::url('foto_laporan/'.$laporan->foto) }}" 
                                     class="img-fluid"
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
        </div>
    </div>
@endempty
