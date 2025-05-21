@extends('layouts.template')

@section('content')
<div class="page-heading">
    <h3>{{ $title }}</h3>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Tambah Gedung --}}
    <div class="card">
        <div class="card-header">Tambah Gedung</div>
        <div class="card-body">
            <form method="POST" action="{{ route('gedung.store') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Nama Gedung</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Tabel Gedung --}}
    <div class="card mt-4">
        <div class="card-header">Daftar Gedung</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gedung</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gedung as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">Detail</a>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#editForm{{ $item->gedung_id }}">Edit</button>
                            <a href="{{ route('gedung.delete', $item->gedung_id) }}" onclick="return confirm('Hapus gedung ini?')" class="btn btn-danger btn-sm">Hapus</a>

                            {{-- Form Edit Collapse --}}
                            <div class="collapse mt-2" id="editForm{{ $item->gedung_id }}">
                                <form method="POST" action="{{ route('gedung.update', $item->gedung_id) }}">
                                    @csrf
                                    <input type="text" name="nama" value="{{ $item->nama }}" required class="form-control mb-1">
                                    <textarea name="deskripsi" class="form-control mb-1">{{ $item->deskripsi }}</textarea>
                                    <button class="btn btn-sm btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada data gedung.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
