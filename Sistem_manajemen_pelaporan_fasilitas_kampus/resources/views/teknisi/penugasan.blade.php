@extends('layouts.template')
@section('title', 'Penugasan')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-3">Penugasan</h4>

            {{-- Horizontal Nav Tabs --}}
            <ul class="nav nav-pills nav-fill mb-4">
                <li class="nav-item">
                    <a class="nav-link active" href="#penugasan" data-bs-toggle="tab">Daftar Penugasan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#revisi" data-bs-toggle="tab">Daftar Revisi</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                {{-- Tab: Penugasan --}}
                <div class="tab-pane fade show active" id="penugasan" role="tabpanel" aria-labelledby="penugasan-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-penugasan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Gedung</th>
                                    <th>Lantai</th>
                                    <th>Ruangan</th>
                                    <th>Fasilitas</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penugasan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->ruangan->lantai->gedung->gedung_nama }}</td>
                                    <td>{{ $item->ruangan->lantai->lantai_nama }}</td>
                                    <td>{{ $item->ruangan->ruangan_nama }}</td>
                                    <td>{{ $item->fasilitas_nama }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
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
                                        <button onclick="showDetailModal('{{ url('teknisi/penugasan/'.$item->fasilitas_id) }}')" 
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

                {{-- Tab: Revisi --}}
                <div class="tab-pane fade" id="revisi" role="tabpanel" aria-labelledby="revisi-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-revisi">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Gedung</th>
                                    <th>Lantai</th>
                                    <th>Ruangan</th>
                                    <th>Fasilitas</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($revisi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->ruangan->lantai->gedung->gedung_nama }}</td>
                                    <td>{{ $item->ruangan->lantai->lantai_nama }}</td>
                                    <td>{{ $item->ruangan->ruangan_nama }}</td>
                                    <td>{{ $item->fasilitas_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</td>
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
                                        <button onclick="showDetailModal('{{ url('teknisi/penugasan/'.$item->fasilitas_id) }}')" 
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
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Penugasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="modalContent"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function showDetailModal(url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
                var modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();

                const form = document.querySelector('#detailModal form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
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
                                modal.hide();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: data.message || 'Aksi berhasil dilakukan',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
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
