@extends('layouts.template')
@section('title', 'Kelola Sistem Rekomendasi')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Tab Navigation -->
                        <ul class="nav nav-pills nav-fill mb-4">
                            <li class="nav-item">
                                <a class="nav-link active" id="kriteria-tab" data-bs-toggle="tab" data-bs-target="#kriteria" type="button" role="tab" aria-controls="kriteria" aria-selected="true">
                                    Daftar Kriteria
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="crisp-tab" data-bs-toggle="tab" data-bs-target="#crisp" type="button" role="tab" aria-controls="crisp" aria-selected="false">
                                    Daftar Crisp
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Kriteria Tab -->
                            <div class="tab-pane fade show active" id="kriteria" role="tabpanel" aria-labelledby="kriteria-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="kriteriaTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Jenis</th>
                                                <th>Bobot</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be filled by script -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Crisp Tab -->
                            <div class="tab-pane fade" id="crisp" role="tabpanel" aria-labelledby="crisp-tab">
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-success"
                                        onclick="modalAction('{{ url('sarpras/sistem_rekomendasi/create_crisp') }}')">
                                        <i class="fas fa-plus me-2"></i>Tambah Crisp
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="crispTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Kriteria</th>
                                                <th>Judul</th>
                                                <th>Poin</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be filled by script -->
                                        </tbody>
                                    </table>
                                </div>
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
            modalAction('/sarpras/sistem_rekomendasi/edit_kriteria/' + id);
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