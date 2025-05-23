@extends('layouts.template')

@section('content')
<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Tambah Gedung --}}
    <div class="col-12 mb-3">
            <a href="{{ url('admin/gedung/create') }}" class="btn btn-success">+ Tambah Gedung</a>
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
                            <a href="{{ url('admin/gedung/create', $item->gedung_id) }}" onclick="return confirm('Hapus gedung ini?')" class="btn btn-danger btn-sm">Hapus</a>

                            {{-- Form Edit Collapse --}}
                            <div class="collapse mt-2" id="editForm{{ $item->gedung_id }}">
                                <form method="POST" action="{{ url('admin/gedung/create', $item->gedung_id) }}">
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
