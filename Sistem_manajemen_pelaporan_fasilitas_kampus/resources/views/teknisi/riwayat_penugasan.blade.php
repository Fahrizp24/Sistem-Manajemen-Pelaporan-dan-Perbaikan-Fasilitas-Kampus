@extends('layouts.template')
@section('title', 'Riwayat Penugasan')

@section('content')
    <section class="section">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-gradient-indigo py-3">
                <h5 class="card-title mb-0 "><i class="bi bi-clock-history me-2"></i>Daftar Riwayat Penugasan</h5>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="table1">
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
                            @foreach ($laporan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->fasilitas->ruangan->lantai->gedung->gedung_nama }}</td>
                                    <td>{{ $item->fasilitas->fasilitas_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'diajukan'           => 'bg-secondary text-white',  // status awal, netral
                                                'diterima'           => 'bg-primary text-white',    // disetujui
                                                'tidak diterima'     => 'bg-danger text-white',     // ditolak
                                                'konfirmasi'         => 'bg-info text-white',       // menunggu proses
                                                'memilih teknisi'    => 'bg-dark text-white',       // proses internal
                                                'diperbaiki'         => 'bg-warning text-dark',     // proses berjalan
                                                'telah diperbaiki'   => 'bg-light text-dark',       // sudah selesai, tapi belum divalidasi
                                                'revisi'             => 'bg-body-secondary text-dark', // status revisi, berbeda dari warning/light
                                                'selesai'            => 'bg-success text-white',    // final, sukses
                                            ][$item->status] ?? 'bg-secondary text-white';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <button onclick="showDetailModal('{{ url('teknisi/riwayat_penugasan/'.$item->laporan_id) }}')" 
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye-fill me-1"></i> Detail
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
                        <h5 class="modal-title" id="detailModalLabel">Detail Riwayat Penugasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                        <!-- Content will be loaded here -->
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