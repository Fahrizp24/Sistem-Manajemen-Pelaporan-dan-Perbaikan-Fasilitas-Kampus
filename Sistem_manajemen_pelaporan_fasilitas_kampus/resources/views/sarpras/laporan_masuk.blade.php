@extends('layouts.template')

@section('content')
    <section class="section">
        <!-- Reports from Reporters -->
        <div class="card card-report mb-4">
            <div class="card-header bg-gradient-indigo">
                <h5 class="card-title text-white"><i class="bi bi-megaphone me-2"></i>Daftar Laporan dari Pelapor</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-sarpras">
                        <thead class="bg-indigo-50">
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
                                            <div class="avatar avatar-sm me-2 bg-indigo-100 text-indigo-600">
                                                <span class="avatar-initial rounded-circle">{{ substr($item->pelapor->nama, 0, 1) }}</span>
                                            </div>
                                            <div>{{ $item->pelapor->nama }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->fasilitas->gedung->nama }}</td>
                                    <td>{{ $item->fasilitas->nama }}</td>
                                    <td>
                                        <span class="badge bg-indigo-100 text-indigo-800">
                                            {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=pelapor')"
                                            class="btn btn-sm bg-indigo-100 text-indigo-800 border-indigo-200 hover-bg-indigo-200">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-indigo-50">
                <small class="text-indigo-600">{{ count($laporan_masuk_pelapor) }} laporan perlu diterima/ditolak</small>
            </div>
        </div>

        <!-- Reports from Admin -->
        <div class="card card-report mb-4">
            <div class="card-header bg-gradient-teal">
                <h5 class="card-title text-white"><i class="bi bi-clipboard-check me-2"></i>Daftar Laporan dari Admin</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-admin">
                        <thead class="bg-teal-50">
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
                                            <div class="avatar avatar-sm me-2 bg-teal-100 text-teal-600">
                                                <span class="avatar-initial rounded-circle">{{ substr($item->pelapor->nama, 0, 1) }}</span>
                                            </div>
                                            <div>{{ $item->pelapor->nama }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->fasilitas->nama }}</td>
                                    <td>
                                        <span class="badge bg-teal-100 text-teal-800">
                                            {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-yellow-100 text-yellow-800">Perlu Penugasan</span>
                                    </td>
                                    <td class="text-center">
                                        <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=admin')"
                                            class="btn btn-sm bg-teal-100 text-teal-800 border-teal-200 hover-bg-teal-200">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-teal-50">
                <small class="text-teal-600">{{ count($laporan_masuk_admin) }} laporan perlu penugasan teknisi</small>
            </div>
        </div>

        <!-- Reports from Technicians -->
        <div class="card card-report">
            <div class="card-header bg-gradient-amber">
                <h5 class="card-title text-white"><i class="bi bi-tools me-2"></i>Daftar Laporan dari Teknisi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-teknisi">
                        <thead class="bg-amber-50">
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
                                            <div class="avatar avatar-sm me-2 bg-amber-100 text-amber-600">
                                                <span class="avatar-initial rounded-circle">{{ substr($item->pelapor->nama, 0, 1) }}</span>
                                            </div>
                                            <div>{{ $item->pelapor->nama }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $item->fasilitas->nama }}</td>
                                    <td>
                                        <span class="badge bg-amber-100 text-amber-800">
                                            {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-orange-100 text-orange-800">Perlu Ditelaah</span>
                                    </td>
                                    <td class="text-center">
                                        <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=teknisi')"
                                            class="btn btn-sm bg-amber-100 text-amber-800 border-amber-200 hover-bg-amber-200">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-amber-50">
                <small class="text-amber-600">{{ count($laporan_masuk_teknisi) }} laporan perlu ditelaah</small>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-indigo text-white">
                        <h5 class="modal-title" id="detailModalLabel">Detail Laporan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* Custom Color Palette */
        .bg-indigo-50 { background-color: #eef2ff; }
        .bg-indigo-100 { background-color: #e0e7ff; }
        .bg-indigo-200 { background-color: #c7d2fe; }
        .text-indigo-600 { color: #4f46e5; }
        .text-indigo-800 { color: #3730a3; }
        .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
        
        .bg-teal-50 { background-color: #f0fdfa; }
        .bg-teal-100 { background-color: #ccfbf1; }
        .bg-teal-200 { background-color: #99f6e4; }
        .text-teal-600 { color: #0d9488; }
        .text-teal-800 { color: #115e59; }
        .bg-gradient-teal { background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); }
        
        .bg-amber-50 { background-color: #fffbeb; }
        .bg-amber-100 { background-color: #fef3c7; }
        .bg-amber-200 { background-color: #fde68a; }
        .text-amber-600 { color: #d97706; }
        .text-amber-800 { color: #92400e; }
        .bg-gradient-amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        
        .bg-yellow-100 { background-color: #fef9c3; }
        .text-yellow-800 { color: #854d0e; }
        .bg-orange-100 { background-color: #ffedd5; }
        .text-orange-800 { color: #9a3412; }
        
        /* Card Styling */
        .card-report {
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .card-header {
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        
        /* Table Styling */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #4b5563;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        
        /* Avatar Styling */
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
        
        /* Button Styling */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s ease;
        }
        
        .hover-bg-indigo-200:hover { background-color: #c7d2fe !important; }
        .hover-bg-teal-200:hover { background-color: #99f6e4 !important; }
        .hover-bg-amber-200:hover { background-color: #fde68a !important; }
        
        /* Badge Styling */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
            font-size: 0.75rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-sarpras, #table-admin, #table-teknisi').DataTable({
                responsive: true,
                dom: '<"top"<"d-flex justify-content-between align-items-center"f<"ms-3"l>>>rt<"bottom"<"d-flex justify-content-between align-items-center"ip><"clear">>',
                pageLength: 10,
                language: {
                    search: '<i class="bi bi-search me-2"></i>',
                    searchPlaceholder: "Cari laporan...",
                    lengthMenu: "Tampilkan _MENU_ laporan per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ laporan",
                    infoEmpty: "Tidak ada laporan",
                    paginate: {
                        previous: '<i class="bi bi-chevron-left"></i>',
                        next: '<i class="bi bi-chevron-right"></i>'
                    }
                }
            });
        });

        function showDetailModal(url) {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error loading modal content:', error);
                    document.getElementById('modalContent').innerHTML = 
                        '<div class="alert alert-danger">Gagal memuat detail laporan</div>';
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                });
        }
    </script>
@endpush