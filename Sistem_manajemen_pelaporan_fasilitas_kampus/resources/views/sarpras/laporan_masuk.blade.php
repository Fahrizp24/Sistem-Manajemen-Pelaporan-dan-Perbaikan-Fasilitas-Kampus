@extends('layouts.template')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h4 class="mb-3">Laporan Masuk</h4>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <!-- Horizontal Tabs -->
                <ul class="nav nav-tabs mb-3" id="laporanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pelapor-tab" data-bs-toggle="tab" data-bs-target="#pelapor" type="button" role="tab">Laporan dari Pelapor</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab">Laporan dari Admin</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="teknisi-tab" data-bs-toggle="tab" data-bs-target="#teknisi" type="button" role="tab">Laporan dari Teknisi</button>
                    </li>
                </ul>

                <div class="tab-content" id="laporanTabsContent">
                    <!-- Tab Pelapor -->
                    <div class="tab-pane fade show active" id="pelapor" role="tabpanel" aria-labelledby="pelapor-tab">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="table-sarpras">
                                    <thead class="table-white">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Gedung</th>
                                            <th>Lantai</th>
                                            <th>Ruangan</th>
                                            <th>Fasilitas</th>
                                            <th>Tanggal</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fasilitas_diajukan as $item)
                                            <tr>
                                                
                                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                                <td>{{ $item->ruangan->lantai->gedung->gedung_nama }}</td>
                                                <td>{{ $item->ruangan->lantai->lantai_nama }}</td>
                                                <td>{{ $item->ruangan->ruangan_nama }}</td>
                                                <td>{{ $item->fasilitas_nama }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</td>
                                                <td class="text-center">
                                                    <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->fasilitas_id) }}?source=pelapor')" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <small class="text-indigo-600 d-block mt-2">{{ count($fasilitas_diajukan) }} laporan perlu diterima/ditolak</small>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Admin -->
                    <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="table-admin">
                                    <thead class="table-white">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Gedung</th>
                                            <th>Lantai</th>
                                            <th>Ruangan</th>
                                            <th>Fasilitas</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fasilitas_memilih_teknisi as $item)
                                            <tr>
                                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                                <td>{{ $item->ruangan->lantai->gedung->gedung_nama }}</td>
                                                <td>{{ $item->ruangan->lantai->lantai_nama }}</td>
                                                <td>{{ $item->ruangan->ruangan_nama }}</td>
                                                <td>{{ $item->fasilitas_nama }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</td>
                                                <td><span class="text-warning">Perlu Penugasan</span></td>
                                                <td class="text-center">
                                                    <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->fasilitas_id) }}?source=admin')" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <small class="text-teal-600 d-block mt-2">{{ count($fasilitas_memilih_teknisi) }} laporan perlu penugasan teknisi</small>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Teknisi -->
                    <div class="tab-pane fade" id="teknisi" role="tabpanel" aria-labelledby="teknisi-tab">
                         <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="table-teknisi">
                                    <thead class="table-white">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Gedung</th>
                                            <th>Lantai</th>
                                            <th>Ruangan</th>
                                            <th>Fasilitas</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fasilitas_telah_diperbaiki as $item)
                                            <tr>
                                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                                <td>{{ $item->ruangan->lantai->gedung->gedung_nama }}</td>
                                                <td>{{ $item->ruangan->lantai->lantai_nama }}</td>
                                                <td>{{ $item->ruangan->ruangan_nama }}</td>
                                                <td>{{ $item->fasilitas_nama }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}</td>
                                                <td><span class="text-orange">Perlu Ditelaah</span></td>
                                                <td class="text-center">
                                                    <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->fasilitas_id) }}?source=teknisi')" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <small class="text-amber-600 d-block mt-2">{{ count($fasilitas_telah_diperbaiki) }} laporan perlu ditelaah</small>
                            </div>
                        </div>
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
            // Fetch the content
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Insert the content into the modal
                    document.getElementById('modalContent').innerHTML = html;
                    
                    // Initialize the modal
                    var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();

                    const form = document.querySelector('#detailModal form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();

                            // Submit form via AJAX
                            fetch(form.action, {
                                    method: form.method,
                                    body: new FormData(form),
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Hide modal
                                        modal.hide();

                                        // Show success alert
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sukses',
                                            text: data.message || 'Aksi berhasil dilakukan',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });

                                        // Optional: refresh page or update table
                                        setTimeout(() => {
                                            location.reload();
                                        }, 2000);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: data.message || 'Terjadi kesalahan'
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Terjadi kesalahan saat mengirim data'
                                    });
                                });
                        });
                    }
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