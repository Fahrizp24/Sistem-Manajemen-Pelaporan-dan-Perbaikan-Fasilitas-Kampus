@extends('layouts.template')

@section('content')
    {{-- ✅ Flash message success/error --}}
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
                <div class="card" style="box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);">
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
<!-- Modal -->
@push('scripts')
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Form Pengguna</h5>
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

        $(document).on('click', '.btnEditPengguna', function () {
            var id = $(this).data('id');
            modalAction('/admin/pengguna/edit_ajax/' + id);
        });

        var dataUser;
        $(document).ready(function () {
            dataUser = $('#penggunaTable').DataTable();
        });

    </script>
@endpush    