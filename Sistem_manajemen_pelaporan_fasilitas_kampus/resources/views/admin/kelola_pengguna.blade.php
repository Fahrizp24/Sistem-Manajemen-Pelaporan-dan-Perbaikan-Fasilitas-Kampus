@extends('layouts.template')
@section('title', 'Kelola Pengguna')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-content">
        <div class="row">
            <div class="col-12 mb-3">
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pengguna</h5>
                        <div>
                            <button type="button" class="btn btn-success mb-3"
                                onclick="modalAction('{{ url('admin/pengguna/create_ajax') }}', $(this))">+ Tambah
                                Pengguna</button>

                            <button type="button" class="btn btn-success mb-3"
                                onclick="modalAction('{{ url('admin/pengguna/import_pengguna') }}', $(this))">+ Import
                                Pengguna</button>

                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped mb-0" id="penggunaTable">
                            <thead class="table-white">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Telp</th>
                                    <th>Peran</th>
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

@endsection
<!-- Modal -->
@push('scripts')
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-body">
                <div id="modalContent">Memuat...</div>
            </div>
        </div>
    </div>
    <script>
        function modalAction(url, button = null) {
            let originalHTML = null;
            if (button) {
                button.prop('disabled', true);
                originalHTML = button.html();
                button.html('<span class="spinner-border spinner-border-sm me-1"></span>Memuat...');
            }

            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    $('#modalContent').html(res);
                    $('#myModal').modal('show');
                },
                error: function() {
                    $('#modalContent').html('<p class="text-danger">Gagal memuat data.</p>');
                },
                complete: function() {
                    if (button) {
                        button.prop('disabled', false);
                        button.html(originalHTML);
                    }
                }
            });
        }

        $(document).on('click', '.btnEditPengguna', function() {
            var id = $(this).data('id');
            modalAction('/admin/pengguna/edit_ajax/' + id, $(this));
        });


        var dataUser;
        $(document).ready(function() {
            dataUser = $('#penggunaTable').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.data_pengguna') }}",
                    type: "POST"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp'
                    },
                    {
                        data: 'peran',
                        name: 'peran'
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
        $(document).on('submit', '#formDeletePengguna', function(e) {
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
