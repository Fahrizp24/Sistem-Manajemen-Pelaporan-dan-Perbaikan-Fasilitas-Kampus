@extends('layouts.template')

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
                {{-- kriteria --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Kriteria</h5>
                        <button type="button" class="btn btn-success mb-3"
                            onclick="modalAction('{{url('sarpras/kriteria/create_ajax')}}')">+ Tambah kriteria</button>
                    </div>
                    <div class="card-body">
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

                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- crisp --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Crisp</h5>
                        <button type="button" class="btn btn-success mb-3"
                            onclick="modalAction('{{url('sarpras/crisp/create_ajax')}}')">+ Tambah crisp</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0" id="crispTable">
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
            modalAction('/sarpras/crisp/edit_ajax/' + id);
        });

        var dataCrisp;
        $(document).ready(function () {
            dataCrisp = $('#crispTable').DataTable({
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