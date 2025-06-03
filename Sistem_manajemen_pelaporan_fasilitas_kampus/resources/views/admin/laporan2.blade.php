@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="laporanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelapor</th>
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
                                    data-id="{{ $item->laporan_id }}">Detail</button>
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
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Pelapor:</strong> <span id="modal-pelapor"></span></p>
                        <p><strong>Fasilitas:</strong> <span id="modal-fasilitas"></span></p>
                        <p><strong>Gedung:</strong> <span id="modal-gedung"></span></p>
                        <p><strong>Deskripsi:</strong> <span id="modal-deskripsi"></span></p>
                        <p><strong>Status:</strong> <span id="modal-status"></span></p>
                        <div id="modal-actions" class="mt-3 text-end" style="display: none;">
                            <form id="form-terima" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="btn btn-success btn-sm">Terima</button>
                            </form>
                            <form id="form-tolak" method="POST" class="d-inline ms-2">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#laporanTable').DataTable();

                $('.btn-detail').click(function() {
                    var id = $(this).data('id');

                    $.ajax({
                        url: "{{ route('admin.show_laporan2', '') }}/" + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            if (res) {
                                $('#modal-pelapor').text(res.pelapor?.nama || '-');
                                $('#modal-fasilitas').text(res.fasilitas?.nama || '-');
                                $('#modal-gedung').text(res.gedung || '-');
                                $('#modal-deskripsi').text(res.deskripsi || '-');
                                $('#modal-status').text(res.status || '-');
                                if (res.status === 'diajukan') {
                                    $('#modal-actions').show();

                                    // Set action form ke URL update
                                    let actionUrl = "{{ url('laporan2') }}/" + res.laporan_id;
                                    $('#form-terima').attr('action', actionUrl);
                                    $('#form-tolak').attr('action', actionUrl);
                                } else {
                                    $('#modal-actions').hide();
                                }

                                $('#detailModal').modal('show');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            alert('Error: ' + xhr.responseJSON?.message || 'Gagal memuat data');
                        }
                    });
                });
            });
        </script>
    @endpush
