@extends('layouts.template')

@section('content')
<div class="container">
    <h1>Data Fasilitas Kampus</h1>
    <a href="{{ route('fasilitas.create') }}" class="btn btn-primary mb-3">Tambah Fasilitas</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Gedung</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fasilitas as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->gedung->nama ?? '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>
                        <a href="{{ route('fasilitas.edit', $item->fasilitas_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('fasilitas.destroy', $item->fasilitas_id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus fasilitas ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
