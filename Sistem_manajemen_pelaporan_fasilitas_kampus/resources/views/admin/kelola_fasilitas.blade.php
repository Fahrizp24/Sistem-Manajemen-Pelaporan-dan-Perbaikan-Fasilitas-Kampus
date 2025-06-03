@extends('layouts.template')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Fasilitas</h5>
                <div>
                    <button type="button" class="btn btn-success mb-3"
                        onclick="modalAction('{{ url('admin/fasilitas/create_ajax') }}')">
                        + Tambah Fasilitas
                    </button>
                    <button type="button" class="btn btn-success mb-3"
                        onclick="modalAction('{{ url('admin/fasilitas/import_fasilitas') }}')">
                        + Import Fasilitas
                    </button>
                </div>

            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0" id="fasilitasTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Gedung</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Form Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">Memuat...</div>
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
                success: function(res) {
                    $('#modalContent').html(res);
                    $('#myModal').modal('show');
                },
                error: function() {
                    $('#modalContent').html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }

        $(document).on('click', '.btnEditFasilitas', function() {
            var id = $(this).data('id');
            modalAction('/admin/fasilitas/edit_ajax/' + id);
        });

        var dataFasilitas;
        $(document).ready(function() {
            dataFasilitas = $('#fasilitasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.data_fasilitas') }}",
                    type: "POST"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'fasilitas_nama',
                        name: 'fasilitas_nama'
                    },
                    {
                        data: 'fasilitas_deskripsi',
                        name: 'fasilitas_deskripsi'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'ruangan.lantai.gedung.gedung_nama',
                        name: 'ruangan.lantai.gedung.gedung_nama'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        $(document).on('submit', '#formDeleteFasilitas', function(e) {
            e.preventDefault(); // Cegah form langsung submit
            let form = this;
            let url = $(form).attr('action');

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
