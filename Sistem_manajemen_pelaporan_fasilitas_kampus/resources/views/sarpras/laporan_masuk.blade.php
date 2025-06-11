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
                <!-- Accordion -->
                <div class="accordion" id="laporanAccordion">

                    <!-- Reports from Reporters -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPelapor">
                            <button class="accordion-button bg-gradient-indigo text-blue" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapsePelapor" aria-expanded="true" aria-controls="collapsePelapor">
                                <i class="bi bi-megaphone me-2"></i>Daftar Laporan dari Pelapor
                            </button>
                        </h2>
                        <div id="collapsePelapor" class="accordion-collapse collapse show" aria-labelledby="headingPelapor"
                            data-bs-parent="#laporanAccordion">
                            <div class="accordion-body">
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
                                                <td><div class="d-flex align-items-center">{{ $item->pelapor->nama }}</div></td>
                                                <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
                                                <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama .' - '.$item->fasilitas->ruangan->ruangan_nama .' - '. $item->fasilitas->fasilitas_nama }}</td>
                                                <td><span class="card-title mb-0 text-blue">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}</span></td>
                                                <td class="text-center">
                                                    <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=pelapor')" class="btn btn-sm btn-primary text-white">
                                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-2">
                                    <small class="text-indigo-600">{{ count($laporan_masuk_pelapor) }} laporan perlu diterima/ditolak</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports from Admin -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAdmin">
                            <button class="accordion-button collapsed bg-gradient-teal text-blue" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false"
                                aria-controls="collapseAdmin">
                                <i class="bi bi-clipboard-check me-2"></i>Daftar Laporan dari Admin
                            </button>
                        </h2>
                        <div id="collapseAdmin" class="accordion-collapse collapse" aria-labelledby="headingAdmin"
                            data-bs-parent="#laporanAccordion">
                            <div class="accordion-body">
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
                                                <td><div class="d-flex align-items-center">{{ $item->pelapor->nama }}</div></td>
                                                <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama .' - '.$item->fasilitas->ruangan->ruangan_nama .' - '. $item->fasilitas->fasilitas_nama }}</td>
                                                <td><span class="card-title mb-0 text-blue">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}</span></td>
                                                <td><span class="card-title mb-0 text-yellow">Perlu Penugasan</span></td>
                                                <td class="text-center">
                                                    <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=admin')" class="btn btn-sm btn-primary text-white">
                                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-2">
                                    <small class="text-teal-600">{{ count($laporan_masuk_admin) }} laporan perlu penugasan teknisi</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports from Technicians -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTeknisi">
                            <button class="accordion-button collapsed bg-gradient-amber text-blue" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseTeknisi" aria-expanded="false"
                                aria-controls="collapseTeknisi">
                                <i class="bi bi-tools me-2"></i>Daftar Laporan dari Teknisi
                            </button>
                        </h2>
                        <div id="collapseTeknisi" class="accordion-collapse collapse" aria-labelledby="headingTeknisi"
                            data-bs-parent="#laporanAccordion">
                            <div class="accordion-body">
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
                                                <td><div class="d-flex align-items-center">{{ $item->pelapor->nama }}</div></td>
                                                <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama .' - '.$item->fasilitas->ruangan->ruangan_nama .' - '. $item->fasilitas->fasilitas_nama }}</td>
                                                <td><span class="card-title mb-0 text-orange">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}</span></td>
                                                <td><span class="card-title mb-0 text-orange">Perlu Ditelaah</span></td>
                                                <td class="text-center">
                                                    <button onclick="showDetailModal('{{ url('sarpras/laporan_masuk/' . $item->laporan_id) }}?source=teknisi')" class="btn btn-sm btn-primary text-white">
                                                        <i class="bi bi-eye-fill me-1"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-2">
                                    <small class="text-amber-600">{{ count($laporan_masuk_teknisi) }} laporan perlu ditelaah</small>
                                </div>
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