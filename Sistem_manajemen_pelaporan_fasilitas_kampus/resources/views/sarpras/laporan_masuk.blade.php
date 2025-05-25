@extends('layouts.template')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Laporan Yang Masuk Dari Pelapor</h5>
            </div>
            <div class="card-body">
                <!-- Tabel Kiri - Laporan Sarpras -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">Daftar Laporan Yang Butuh Dikonfirmasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-sarpras">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Pelapor</th>
                                        <th>Gedung</th>
                                        <th>Fasilitas</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan_masuk_pelapor as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->pelapor->nama }}</td>
                                            <td>{{ $item->fasilitas->gedung->nama }}</td>
                                            <td>{{ $item->fasilitas->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <button
                                                    onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=pelapor')"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Laporan Yang Masuk Dari Admin</h5>
            </div>
            <div class="card-body">
                <!-- Tabel Kanan - Laporan Admin -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title">Daftar Laporan Yang Butuh Penugasan Teknisi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-admin">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Pelapor</th>
                                        <th>Fasilitas</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan_masuk_admin as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->pelapor->nama }}</td>
                                            <td>{{ $item->fasilitas->gedung->nama }}</td>
                                            <td>{{ $item->fasilitas->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <button
                                                    onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=admin')"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Laporan Yang Masuk Dari Teknisi</h5>
            </div>
            <div class="card-body">
                <!-- Tabel Kanan - Laporan Admin -->
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="card-title">Daftar Laporan Yang Butuh Ditelaah Dari Teknisi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-admin">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Pelapor</th>
                                        <th>Fasilitas</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan_masuk_teknisi as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->pelapor->nama }}</td>
                                            <td>{{ $item->fasilitas->gedung->nama }}</td>
                                            <td>{{ $item->fasilitas->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <button
                                                    onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=teknisi')"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Penugasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-sarpras, #table-admin', '#table-teknisi').DataTable({
                responsive: true,
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                pageLength: 10,
                language: {
                    search: "INPUT",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan MENU data per halaman",
                    info: "Menampilkan START sampai END dari TOTAL data",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });
        });
        function showDetailModal(url) {
            // Fetch the content
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    
                    // Initialize the modal
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error loading modal content:', error);
                    document.getElementById('modalContent').innerHTML = 
                        '<div class="alert alert-danger">Error loading content</div>';
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                });
        }
    </script>
@endpush
