@extends('layouts.template')

@section('content')
    <section class="section">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-gradient-indigo py-3">
                <h5 class="card-title mb-0 text-blue"><i class="bi bi-clipboard-data me-2"></i>Daftar Penugasan</h5>
            </div>
                <div class="card-body pt-3">
                    <table class="table table-bordered table-striped mb-0" id="table-teknisi">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
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
                                    <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
                                    <td>{{ $item->fasilitas->fasilitas_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'diajukan' => 'bg-secondary',
                                                'diterima' => 'bg-info',
                                                'tidak diterima' => 'bg-danger',
                                                'konfirmasi' => 'bg-primary',
                                                'memilih teknisi' => 'bg-info',
                                                'diperbaiki' => 'bg-info',
                                                'telah diperbaiki' => 'bg-Info',
                                                'revisi' => 'bg-warning',
                                                'selesai' => 'bg-success',
                                            ][$item->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>{{$item->foto}}</td>
                                    <td>
                                        <button onclick="showDetailModal('{{ url('teknisi/penugasan/'.$item->laporan_id) }}')" 
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye-fill"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-gradient-indigo py-3">
                <h5 class="card-title mb-0 text-blue"><i class="bi bi-journal-text me-2"></i>Daftar Revisi</h5>
            </div>
                <div class="card-body pt-3">
                    <table class="table table-bordered table-striped mb-0" id="table-sarpras">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
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
                                    <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama  }}</td>
                                    <td>{{ $item->fasilitas->fasilitas_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'diajukan' => 'bg-secondary',
                                                'diterima' => 'bg-info',
                                                'tidak diterima' => 'bg-danger',
                                                'konfirmasi' => 'bg-primary',
                                                'memilih teknisi' => 'bg-info',
                                                'diperbaiki' => 'bg-info',
                                                'telah diperbaiki' => 'bg-Info',
                                                'revisi' => 'bg-warning',
                                                'selesai' => 'bg-success',
                                            ][$item->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>{{$item->foto}}</td>
                                    <td>
                                        <button onclick="showDetailModal('{{ url('teknisi/penugasan/'.$item->laporan_id) }}')" 
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye-fill"></i> Detail
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