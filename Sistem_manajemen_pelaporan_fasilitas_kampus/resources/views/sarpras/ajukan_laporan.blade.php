@extends('layouts.template')
@section('title', 'Ajukan Laporan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Ajukan Laporan</h5>
            </div>

            {{-- Tabel Laporan Diterima --}}
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="laporanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gedung - Lt</th>
                            <th>Ruang</th>
                            <th>Fasilitas</th>
                            @foreach ($kriteria as $k)
                                <th>{{ explode(' ', $k->nama)[0] }}</th>
                            @endforeach
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <button class="btn btn-primary mt-2" id="btnProsesSPK">Sinkronisasi Sistem Rekomendasi</button>
            </div>
        </div>

        {{-- Tabel Hasil SPK (disembunyikan awalnya) --}}
        <div class="card mt-3" id="hasilSPKCard" style="display: none;">
            <div class="card-header">
                <strong>Hasil Sistem Rekomendasi (Metode TOPSIS)</strong>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped" id="hasilSPKTable">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Judul Laporan</th>
                            <th>Nilai D+</th>
                            <th>Nilai D-</th>
                            <th>Nilai V</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="hasilSPKBody">
                        <!-- Akan diisi lewat JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
<!-- Modal -->
@push('scripts')
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Form Gedung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">Memuat...</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const kriteriaList = @json($kriteria);

        function modalAction(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    $('#modalContent').html(res);
                    $('#myModal').modal('show');
                },
                error: function() {
                    $('#modalContent').html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }

        var dataLaporan;
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
                },
                {
                    data: 'ruangan',
                    name: 'ruangan'
                },
                {
                    data: 'fasilitas',
                    name: 'fasilitas'
                }
            ];

            // Tambahkan kolom kriteria (akses data nested)
            kriteriaList.forEach(kriteria => {
                columns.push({
                    data: 'kriteria.kriteria_' + kriteria.kriteria_id,
                    name: 'kriteria_' + kriteria.kriteria_id,
                    className: 'text-center'
                });
            });

            // Kolom aksi
            columns.push({
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false,
                className: 'text-center'
            });

            // Inisialisasi DataTable
            if ($.fn.DataTable.isDataTable('#laporanTable')) {
                $('#laporanTable').DataTable().clear().destroy();
            }

            dataLaporan = $('#laporanTable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('sarpras.data_laporan') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                },
                columns: columns,
                language: {
                    emptyTable: "Tidak ada data laporan yang diterima",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ fasilitas",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 fasilitas",
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

            const baseUrl = "{{ url('sarpras/laporan_masuk') }}";

            $('#btnProsesSPK').on('click', function() {
                $.ajax({
                    url: "{{ route('sarpras.proses_spk') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('#btnProsesSPK').prop('disabled', true).text('Memproses...');
                    },
                    success: function(response) {
                        $('#btnProsesSPK').prop('disabled', false).text('Proses SPK');

                        // Kosongkan isi tabel hasil SPK
                        $('#hasilSPKBody').empty();

                        // Tampilkan hasil dan isi ke dalam tabel
                        if (response && response.data && response.data.length > 0) {
                            response.data.forEach((item, index) => {
                                $('#hasilSPKBody').append(`
                                <tr>
                                    <td>${item.ranking}</td>
                                    <td>${item.judul}</td>
                                    <td>${item.D_plus}</td>
                                    <td>${item.D_minus}</td>
                                    <td>${item.V}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info" onclick="modalAction('${baseUrl}/${item.laporan_id}?source=ajukan')">Detail</button>
                                    </td>
                                </tr>
                            `);
                            }); 

                            $('#hasilSPKCard').show();
                        } else {
                            $('#hasilSPKBody').html(
                                '<tr><td colspan="3" class="text-center">Tidak ada hasil SPK</td></tr>'
                            );
                            $('#hasilSPKCard').show();
                        }
                    },
                    error: function(xhr) {
                        $('#btnProsesSPK').prop('disabled', false).text('Proses SPK');
                        alert("Terjadi kesalahan saat memproses SPK.");
                    }
                });
            });

        });
    </script>
@endpush
