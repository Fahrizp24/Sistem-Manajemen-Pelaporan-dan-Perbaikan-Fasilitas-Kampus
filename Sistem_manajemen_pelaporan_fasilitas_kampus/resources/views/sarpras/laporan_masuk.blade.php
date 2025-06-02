@extends('layouts.template')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <!-- Reports from Reporters -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-indigo">
                        <h5 class="card-title mb-0 text-blue"><i class="bi bi-megaphone me-2"></i>Daftar Laporan dari Pelapor</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0" id="table-sarpras">
                            <thead class="table-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Pelapor</th>
                                    <th>Gedung</th>
                                    <th>Fasilitas</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan_masuk_pelapor as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>{{ $item->pelapor->nama }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $item->fasilitas->gedung->nama }}</td>
                                        <td>{{ $item->fasilitas->nama }}</td>
                                        <td>
                                            <span class="card-title mb-0 text-blue">
                                                {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=pelapor')"
                                                class="btn btn-sm btn-primary text-white">
                                                <i class="bi bi-eye-fill me-1"></i> Detail
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-indigo-50">
                        <small class="text-indigo-600">{{ count($laporan_masuk_pelapor) }} laporan perlu diterima/ditolak</small>
                    </div>
                </div>

                <!-- Reports from Admin -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-teal">
                        <h5 class="card-title mb-0 text-blue"><i class="bi bi-clipboard-check me-2"></i>Daftar Laporan dari Admin</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0" id="table-admin">
                            <thead class="table-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Pelapor</th>
                                    <th>Fasilitas</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan_masuk_admin as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>{{ $item->pelapor->nama }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $item->fasilitas->nama }}</td>
                                        <td>
                                            <span class="card-title mb-0 text-blue">
                                                {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="card-title mb-0 text-yellow">Perlu Penugasan</span>
                                        </td>
                                        <td class="text-center">
                                            <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=admin')"
                                                class="btn btn-sm btn-primary text-white">
                                                <i class="bi bi-eye-fill me-1"></i> Detail
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-teal-50">
                        <small class="text-teal-600">{{ count($laporan_masuk_admin) }} laporan perlu penugasan teknisi</small>
                    </div>
                </div>

                <!-- Reports from Technicians -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-amber">
                        <h5 class="card-title mb-0 text-blue"><i class="bi bi-tools me-2"></i>Daftar Laporan dari Teknisi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0" id="table-teknisi">
                            <thead class="table-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Pelapor</th>
                                    <th>Fasilitas</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan_masuk_teknisi as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>{{ $item->pelapor->nama }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $item->fasilitas->nama }}</td>
                                        <td>
                                            <span class="card-title mb-0 text-orange">
                                                {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="card-title mb-0 text-orange">Perlu Ditelaah</span>
                                        </td>
                                        <td class="text-center">
                                           <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=teknisi')"
                                                class="btn btn-sm btn-primary text-white">
                                                <i class="bi bi-eye-fill me-1"></i> Detail
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-amber-50">
                        <small class="text-amber-600">{{ count($laporan_masuk_teknisi) }} laporan perlu ditelaah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-indigo text-blue">
                    <h4 class="modal-title" id="modalLabel">Detail Laporan</h4>
                    <button type="button" class="btn-close btn-close-blue" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">Memuat...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Inisialisasi DataTable
        $(document).ready(function() {
            $('#table-sarpras, #table-admin, #table-teknisi').DataTable({
                responsive: true
            });
        });

        function showDetailModal(url) {
            $('#modalContent').html('Memuat...');
            $('#detailModal').modal('show');
            
            $.get(url, function(data) {
                $('#modalContent').html(data);
            }).fail(function() {
                $('#modalContent').html('Gagal memuat data. Silakan coba lagi.');
            });
        }
    </script>
@endpush