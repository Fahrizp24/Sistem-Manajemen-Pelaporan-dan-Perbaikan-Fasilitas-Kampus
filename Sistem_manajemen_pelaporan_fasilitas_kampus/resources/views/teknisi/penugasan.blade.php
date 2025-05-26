@extends('layouts.template')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title">Daftar Penugasan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table dataTable no-footer" id="table-penugasan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gedung</th>
                                <th>Fasilitas</th>
                                <th>Tanggal Laporan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penugasan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->fasilitas->gedung->nama }}</td>
                                    <td>{{ $item->fasilitas->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'diajukan' => 'bg-secondary',
                                                'diterima' => 'bg-primary',
                                                'ditolak' => 'bg-danger',
                                                'diajukan sarpras' => 'bg-warning',
                                                'diterima admin' => 'bg-info',
                                                'dilaksanakan' => 'bg-success',
                                                'selesai' => 'bg-dark',
                                            ][$item->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <button onclick="showDetailModal('{{ url('teknisi/penugasan/'.$item->laporan_id) }}')" 
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title">Daftar Revisi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table dataTable no-footer" id="table-revisi">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gedung</th>
                                <th>Fasilitas</th>
                                <th>Tanggal Laporan</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($revisi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->fasilitas->gedung->nama }}</td>
                                    <td>{{ $item->fasilitas->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'diajukan' => 'bg-secondary',
                                                'diterima' => 'bg-primary',
                                                'ditolak' => 'bg-danger',
                                                'diajukan sarpras' => 'bg-warning',
                                                'diterima admin' => 'bg-info',
                                                'dilaksanakan' => 'bg-success',
                                                'selesai' => 'bg-dark',
                                            ][$item->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <button onclick="showDetailModal('{{ url('teknisi/penugasan/'.$item->laporan_id) }}')" 
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal Structure -->
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
    <script>
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