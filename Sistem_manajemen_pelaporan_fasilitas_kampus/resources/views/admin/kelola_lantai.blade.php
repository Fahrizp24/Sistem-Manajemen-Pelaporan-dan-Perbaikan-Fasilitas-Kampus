@extends('layouts.template')

@section('content')
    <div class="page-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Lantai</h5>
                <button type="button" class="btn btn-success mb-3"
                    onclick="modalAction('{{ route('admin.create_lantai') }}')">
                    + Tambah Lantai
                </button>
            </div>
            <div class="card-body">
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalLantai" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-body">
                <div id="contentModalLantai">Memuat...</div>
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
                    $('#contentModalLantai').html(res);
                    $('#modalLantai').modal('show');
                },
                error: function() {
                    $('#contentModalLantai').html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }

        $(document).on('click', '.btnEditlantai', function() {
            var id = $(this).data('id');
            modalAction('/admin/edit_lantai/' + id);
        });
        var dataLantai;
        $(document).ready(function() {
            dataLantai = $('#tableLantai').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('admin.data_lantai') }}",
                    type: "POST"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lantai_nama',
                        name: 'lantai_nama'
                    },
                    {
                        data: 'lantai_deskripsi',
                        name: 'lantai_deskripsi'
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
        $(document).on('submit', '#formDeleteLantai', function(e) {
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
