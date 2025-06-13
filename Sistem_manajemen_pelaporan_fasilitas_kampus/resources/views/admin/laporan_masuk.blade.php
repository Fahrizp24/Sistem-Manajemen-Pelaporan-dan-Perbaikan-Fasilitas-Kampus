@extends('layouts.template')
@section('title', 'Laporan Masuk')

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="laporanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gedung</th>
                        <th>Lantai</th>
                        <th>Ruangan</th>
                        <th>Fasilitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-indigo text-blue">
                    <h4 class="modal-title" id="modalLabel">Detail Laporan</h4>
                    <button type="button" class="btn-close btn-close-blue" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">Memuat...</div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        function modalAction(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (res) {
                    $('#modalContent').html(res);
                    $('#detailModal').modal('show');
                },
                error: function () {
                    $('#modalContent').html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }

        $(document).ready(function() {

            const columns = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'gedung',
                    name: 'gedung'
                },{
                    data: 'lantai',
                    name: 'lantai'
                },{
                    data: 'ruangan',
                    name: 'ruangan'
                },
                {
                    data: 'fasilitas',
                    name: 'fasilitas'
                },
                
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ];

            if ($.fn.DataTable.isDataTable('#laporanTable')) {
                $('#laporanTable').DataTable().clear().destroy();
            }

            $('#laporanTable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.data_laporan') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                },
                columns: columns,
                language: {
                    emptyTable: "Tidak ada data laporan yang diterima",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ laporan",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 laporan",
                    loadingRecords: "Memuat data...",
                    processing: "Memproses...",
                    search: "Cari:",
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                },
                drawCallback: function(settings) {
                    if (settings.json && settings.json.recordsTotal === 0) {
                        $('#laporanTable tbody').html(
                            '<tr><td colspan="' + columns.length +
                            '" class="text-center">Tidak ada data laporan yang diterima</td></tr>'
                        );
                    }
                }
            });
        });
    </script>
@endpush
