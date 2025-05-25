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
                                                    onclick="showDetailModal('{{ url('pelapor/laporan_saya/' . $item->laporan_id) }}')"
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
                                                    onclick="showDetailModal('{{ url('pelapor/laporan_saya/' . $item->laporan_id) }}')"
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
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-sarpras, #table-admin').DataTable({
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
    </script>
@endpush
