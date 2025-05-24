@extends('layouts.template')

@section('content')
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

            <div class="col-12 mb-3">
                <button id="btn-tambah" data-url="{{ url('/admin/pengguna/create_ajax') }}" class="btn btn-success">+ Tambah
                    Pengguna</button>
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="modal-content">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0">
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
                                            <button class="btn btn-sm btn-primary btn-edit"
                                                data-url="{{ url('admin/pengguna/edit_ajax', $user->pengguna_id) }}">
                                                Edit
                                            </button>

                                            <form action="{{ url('admin/pengguna/destroy', $user->pengguna_id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                            <form action="{{ url('admin/pengguna/reset', $user->pengguna_id) }}" method="POST"
                                                style="display:inline;" onsubmit="return confirm('Reset kata sandi?')">
                                                @csrf
                                                <button class="btn btn-sm btn-warning">Reset Sandi</button>
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
@push('scripts')
    <script>
        //tambah data
        $(document).on('click', '#btn-tambah', function () {
            let url = $(this).data('url');
            $.get(url, function (response) {
                $('#modal-content').html(response);
                $('#myModal').modal('show');
            });
        });
        //edit data
        $(document).on('click', '.btn-edit', function () {
        let url = $(this).data('url');
        $.get(url, function (response) {
            $('#modal-content').html(response);
            $('#myModal').modal('show');
        }).fail(function (xhr) {
            console.error('Gagal memuat form edit:', xhr.responseText);
            alert('Terjadi kesalahan saat memuat form edit.');
        });
    });
    </script>
@endpush