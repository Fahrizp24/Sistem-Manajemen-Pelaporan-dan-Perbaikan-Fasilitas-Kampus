@extends('layouts.template')
@section('title', 'Kelola Sistem Rekomendasi')

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
                {{-- Tab Navigasi --}}
                <ul class="nav nav-tabs" id="kriteriaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="kriteria-tab" data-bs-toggle="tab" data-bs-target="#kriteria" type="button" role="tab" aria-controls="kriteria" aria-selected="true">
                            Daftar Kriteria
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="crisp-tab" data-bs-toggle="tab" data-bs-target="#crisp" type="button" role="tab" aria-controls="crisp" aria-selected="false">
                            Daftar Crisp
                        </button>
                    </li>
                </ul>

                {{-- Tab Konten --}}
                <div class="tab-content pt-3" id="kriteriaTabContent">
                    {{-- Tab: Kriteria --}}
                    <div class="tab-pane fade show active" id="kriteria" role="tabpanel" aria-labelledby="kriteria-tab">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <button type="button" class="btn btn-success mb-3"
                                    onclick="modalAction('{{url('sarpras/kriteria/create_ajax')}}')">+ Tambah kriteria</button>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped mb-0" id="kriteriaTable">
                                    <thead class="table-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Jenis</th>
                                            <th>Bobot</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data diisi oleh script --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Tab: Crisp --}}
                    <div class="tab-pane fade" id="crisp" role="tabpanel" aria-labelledby="crisp-tab">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <button type="button" class="btn btn-success mb-3"
                                    onclick="modalAction('{{ url('sarpras/sistem_rekomendasi/create_crisp') }}')">
                                    + Tambah crisp
                                </button>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped w-100 mb-0" id="crispTable">
                                    <thead class="table-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Kriteria</th>
                                            <th>Judul</th>
                                            <th>Poin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
<!-- Modal -->
@push('scripts')
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">Memuat...</div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function modalAction(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (res) {
                    $('#modalContent').html(res);
                    $('#myModal').modal('show');
                },
                error: function () {
                    $('#modalContent').html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }

        $(document).on('click', '.btnEditKriteria', function () {
            var id = $(this).data('id');
            modalAction('/sarpras/kriteria/edit_ajax/' + id);
        });

        var dataKriteria;
        $(document).ready(function () {
            dataKriteria = $('#kriteriaTable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: false,
                ajax: { url: "{{ route('sarpras.data_kriteria') }}", type: "POST" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'kode', name: 'kode' },
                    { data: 'nama', name: 'nama' },
                    { data: 'jenis', name: 'jenis' },
                    { data: 'bobot', name: 'bobot' },                    
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });
        });
        $(document).on('submit', '#formDeleteKriteria', function (e) {
            e.preventDefault(); // Cegah form langsung submit

            let form = this;

            Swal.fire({
                title: "Yakin nih mau dihapus?",
                text: "Data gak bakal bisa dibalikin lagi loh kalo dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Gajadi deh",
                confirmButtonText: "Yup, Hapus ajalah!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form secara manual
                }
            });
        });

        //crisp
        $(document).on('click', '.btnEditCrisp', function () {
            var id = $(this).data('id');
            modalAction('/sarpras/sistem_rekomendasi/edit_crisp/' + id);
        });

        var dataCrisp;
        $(document).ready(function () {
            dataCrisp = $('#crispTable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: false,
                ajax: { url: "{{ route('sarpras.data_crisp') }}", type: "POST" },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'kriteria.nama', name: 'kriteria.nama' },
                    { data: 'judul', name: 'judul' },
                    { data: 'poin', name: 'poin' },             
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });
        });
        $(document).on('submit', '#formDeleteCrisp', function (e) {
            e.preventDefault(); // Cegah form langsung submit

            let form = this;

            Swal.fire({
                title: "Yakin nih mau dihapus?",
                text: "Data gak bakal bisa dibalikin lagi loh kalo dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Gajadi deh",
                confirmButtonText: "Yup, Hapus ajalah!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form secara manual
                }
            });
        });

    </script>
@endpush