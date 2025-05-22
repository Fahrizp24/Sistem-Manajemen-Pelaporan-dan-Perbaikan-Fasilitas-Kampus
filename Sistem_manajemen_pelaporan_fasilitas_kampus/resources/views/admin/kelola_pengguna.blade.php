@extends('layouts.template')

@section('content')
<div class="page-heading">
    <h3>{{ $page->title }}</h3>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-12 mb-3">
            {{-- <form method="GET" action="{{ route('admin.kelola_pengguna.index') }}">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari NIP/NIM/NIDN..." value="{{ request('keyword') }}">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a href="{{ route('admin.kelola_pengguna.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form> --}}
        </div>

        <div class="col-12 mb-3">
            <a href="{{ url('admin/pengguna/create') }}" class="btn btn-success">+ Tambah Pengguna</a>
        </div>

        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
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
                                <a href="{{ url('admin/pengguna/edit', $user->pengguna_id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ url('admin/pengguna/destroy', $user->pengguna_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                <form action="{{ url('admin/pengguna/reset', $user->pengguna_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Reset kata sandi?')">
                                    @csrf
                                    <button class="btn btn-sm btn-warning">Reset Sandi</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
