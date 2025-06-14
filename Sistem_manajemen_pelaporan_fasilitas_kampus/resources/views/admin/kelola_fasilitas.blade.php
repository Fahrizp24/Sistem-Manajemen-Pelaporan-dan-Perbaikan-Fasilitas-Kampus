@extends('layouts.template')
@section('title', 'Fasilitas')

@section('content')
<div class="page-content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <a class="nav-link active" href="#fasilitasTab" data-bs-toggle="tab">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#gedungTab" data-bs-toggle="tab">Gedung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#lantaiTab" data-bs-toggle="tab">Lantai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#ruanganTab" data-bs-toggle="tab">Ruangan</a>
                </li>
            </ul>
        </div>

        <div class="card-body tab-content">
            <!-- Fasilitas Tab -->
            <div class="tab-pane fade show active" id="fasilitasTab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Fasilitas</h5>
                    <div>
                        <button type="button" class="btn btn-success mb-3"
                            onclick="modalAction('{{ url('admin/fasilitas/create_ajax') }}', 'modalContent')">
                            + Tambah Fasilitas
                        </button>
                        <button type="button" class="btn btn-success mb-3"
                            onclick="modalAction('{{ url('admin/fasilitas/import_fasilitas') }}', 'modalContent')">
                            + Import Fasilitas
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="fasilitasTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Gedung</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Gedung Tab -->
            <div class="tab-pane fade" id="gedungTab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Gedung</h5>
                    <button type="button" class="btn btn-success"
                        onclick="modalAction('{{ url('admin/gedung/create_gedung') }}', 'modalContent')">
                        + Tambah Gedung
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="gedungTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Gedung</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Lantai Tab -->
            <div class="tab-pane fade" id="lantaiTab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Lantai</h5>
                    <button type="button" class="btn btn-success"
                        onclick="modalAction('{{ route('admin.create_lantai') }}', 'modalContent')">
                        + Tambah Lantai
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableLantai">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lantai</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Ruangan Tab -->
            <div class="tab-pane fade" id="ruanganTab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Ruangan</h5>
                    <button type="button" class="btn btn-success"
                        onclick="modalAction('{{ route('admin.create_ruangan') }}', 'modalContent')">
                        + Tambah Ruangan
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableRuangan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruangan</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-body">
                <div id="modalContent">Memuat...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function modalAction(url, targetId) {
        $.ajax({
            url: url,
            type: "GET",
            success: function (res) {
                $('#' + targetId).html(res);
                $('#myModal').modal('show');
            },
            error: function () {
                $('#' + targetId).html('<p class="text-danger">Gagal memuat data.</p>');
            }
        });
    }

    $(document).ready(function () {
        $('#fasilitasTable').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: { url: "{{ route('admin.data_fasilitas') }}", type: "POST" },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'fasilitas_nama' },
                { data: 'fasilitas_deskripsi' },
                { data: 'ruangan.lantai.gedung.gedung_nama' },
                { data: 'status' },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });

        $('#gedungTable').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: { url: "{{ route('admin.data_gedung') }}", type: "POST" },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'gedung_nama' },
                { data: 'gedung_deskripsi' },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });

        $('#tableLantai').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: { url: "{{ route('admin.data_lantai') }}", type: "POST" },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'lantai_nama' },
                { data: 'lantai_deskripsi' },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });

        $('#tableRuangan').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: { url: "{{ route('admin.data_ruangan') }}", type: "POST" },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'ruangan_nama' },
                { data: 'ruangan_deskripsi' },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });
    });

    $(document).on('click', '.btnEditFasilitas', function () {
        modalAction('/admin/fasilitas/edit_ajax/' + $(this).data('id'), 'modalContent');
    });

    $(document).on('click', '.btnEditGedung', function () {
        modalAction('/admin/gedung/edit/' + $(this).data('id'), 'modalContent');
    });

    $(document).on('click', '.btnEditlantai', function () {
        modalAction('/admin/lantai/edit/' + $(this).data('id'), 'modalContent');
    });

    $(document).on('click', '.btnEditruangan', function () {
        modalAction('/admin/ruangan/edit/' + $(this).data('id'), 'modalContent');
    });

    $(document).on('submit', '#formDeleteFasilitas, #formDeleteGedung, #formDeleteLantai, #formDeleteRuangan', function (e) {
        e.preventDefault();
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
                form.submit();
            }
        });
    });
</script>
@endpush
