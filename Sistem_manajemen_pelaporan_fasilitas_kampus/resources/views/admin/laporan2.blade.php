@extends('layouts.template')
@section('title', 'Kelola Laporan')

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="laporanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelapor</th>
                        <th>Peran</th>
                        <th>Fasilitas</th>
                        <th>Gedung</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($laporan2 as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->pelapor->nama ?? '-' }}</td>
                            <td>{{ ucfirst($item->pelapor->peran ?? '-') }}</td>
                            <td>{{ $item->fasilitas->fasilitas_nama ?? '-' }}</td>
                            <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama ?? '-' }}</td>
                            <td>{{ $item->deskripsi }}</td>
                            <td>
                                @switch($item->status)
                                    @case('diajukan')
                                        <span class="badge bg-secondary">Diajukan</span>
                                    @break

                                    @case('diproses')
                                        <span class="badge bg-warning">Diproses</span>
                                    @break

                                    @case('selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @break

                                    @case('ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @break

                                    @default
                                        <span class="badge bg-light">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm btn-detail"
                                    data-url="{{ route('admin.show_laporan2', $item->laporan_id) }}">Detail</button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data laporan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Detail -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-indigo text-blue">
                        <h4 class="modal-title" id="modalLabel">Detail Laporan</h4>
                        <button type="button" class="btn-close btn-close-blue" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalContent">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('css')
    <style>
        .bg-gradient-indigo {
            background: linear-gradient(to right, #6610f2, #6f42c1);
        }
        .text-blue {
            color: white;
        }
        .btn-close-blue {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#laporanTable').DataTable();

                $('.btn-detail').click(function() {
                    var url = $(this).data('url');
                    modalAction(url);
                });

                function modalAction(url) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(res) {
                            $('#modalContent').html(res);
                            $('#detailModal').modal('show');
                        },
                        error: function() {
                            $('#modalContent').html('<p class="text-danger">Gagal memuat data.</p>');
                        }
                    });
                }
            });
        </script>
    @endpush