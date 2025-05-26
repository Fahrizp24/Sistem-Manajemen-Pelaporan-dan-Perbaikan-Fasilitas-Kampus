@extends('layouts.template')

@section('content')
    {{-- ✅ Flash message success/error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="page-content">
        <div class="row">
            <div class="col-12 mb-3">
                {{-- <form method="GET" action="{{ route('admin.kelola_pengguna.index') }}">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" placeholder="Cari NIP/NIM/NIDN..."
                            value="{{ request('keyword') }}">
                        <button class="btn btn-primary" type="submit">Filter</button>
                        <a href="{{ route('admin.kelola_pengguna.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form> --}}
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pengguna</h5>
                        {{-- <button type="button" class="btn btn-success mb-3" id="btnTambahPengguna">+ Tambah
                            Pengguna</button> --}}
                        <button type="button" class="btn btn-success mb-3"
                            onclick="modalAction('{{url('admin/pengguna/create_ajax')}}')">+ Tambah Pengguna</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0" id="penggunaTable">
                            <thead class="table-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>NIP/NIM/NIDN</th>
                                    <th>Peran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengguna as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->identitas }}</td>
                                        <td>{{ ucfirst($user->peran) }}</td>
                                        <td>

                                            <button type="button" class="btn btn-sm btn-primary btnEditPengguna"
                                                data-id="{{ $user->pengguna_id }}">Edit</button>
                                            <form action="{{ route('admin.destroy', $user->pengguna_id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="modal-dialog modal-lg"> <!-- atau modal-md -->
        <div class="modal-content">
            <!-- konten dari AJAX akan disisipkan di sini -->
        </div>
    </div>
</div>
<!-- Modal Tambah Pengguna -->
{{-- <div class="modal fade" id="modalTambahPengguna" tabindex="-1" aria-labelledby="modalTambahLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTambahLabel">Tambah Pengguna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Konten form akan dimuat di sini melalui AJAX --}}
            {{-- </div>
        </div>
    </div>
</div> --}}
<!-- Modal Edit Pengguna -->
<div class="modal fade" id="modalEditPengguna" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalEditLabel">Edit Pengguna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Konten form akan dimuat di sini melalui AJAX --}}
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
        var dataUser;
        $(document).ready(function () {
            dataUser = $('#penggunaTable').DataTable();
            // $('#btnTambahPengguna').on('click', function () {
            //     $.ajax({
            //         url: "{{ url('admin/pengguna/create_ajax') }}",
            //         type: "GET",
            //         success: function (response) {
            //             $('#modalTambahPengguna .modal-body').html(response);
            //             $('#modalTambahPengguna').modal('show');
            //         },
            //         error: function () {
            //             alert('Gagal memuat form pengguna.');
            //         }
            //     });
            // });
            $(document).on('click', '.btnEditPengguna', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: "/admin/pengguna/edit_ajax/" + id,
                    type: "GET",
                    success: function (response) {
                        $('#modalEditPengguna .modal-body').html(response);
                        $('#modalEditPengguna').modal('show');
                    },
                    error: function () {
                        alert('Gagal memuat form pengguna.');
                    }
                });
            });
        });
        
    </script>
@endpush