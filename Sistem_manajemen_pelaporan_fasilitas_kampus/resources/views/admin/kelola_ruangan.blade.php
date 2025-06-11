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
                <h5 class="mb-0">Daftar Ruangan</h5>
                <button type="button" class="btn btn-success mb-3"
                    onclick="modalAction('{{ route('admin.create_ruangan') }}')">
                    + Tambah Ruangan
                </button>
            </div>
            <div class="card-body table-responsive"> 
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

    <!-- Modal -->
    <div class="modal fade" id="modalRuangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-body">
                <div id="contentModalRuangan">Memuat...</div>
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
                    $('#contentModalRuangan').html(res);
                    $('#modalRuangan').modal('show');
                },
                error: function () {
                    $('#contentModalRuangan').html('<p class="text-danger">Gagal memuat data.</p>');
                }
            });
        }

        $(document).on('click', '.btnEditruangan', function () {
            var id = $(this).data('id');
            modalAction('/admin/ruangan/edit/' + id);
        });
        var dataRuangan;
        $(document).ready(function () {
            dataRuangan = $('#tableRuangan').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.data_ruangan") }}',
                    type: 'POST',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'ruangan_nama', name: 'ruangan_nama' },
                    { data: 'ruangan_deskripsi', name: 'ruangan_deskripsi' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });
        });
        $(document).on('submit', '#formDeleteRuangan', function(e) {
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