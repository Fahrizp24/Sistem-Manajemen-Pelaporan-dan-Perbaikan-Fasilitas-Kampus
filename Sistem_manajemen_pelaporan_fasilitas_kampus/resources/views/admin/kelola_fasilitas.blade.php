@extends('layouts.template')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Fasilitas</h5>
            <a href="{{ url('admin/fasilitas/create') }}" class="btn btn-success mb-3">+ Tambah Fasilitas</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0" id="fasilitasTable">
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
                                <a href="{{ url('admin/fasilitas/Edit', $item->fasilitas_id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ url('admin/fasilitas/destroy', $item->fasilitas_id) }}" method="POST" style="display:inline">
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
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#fasilitasTable').DataTable();
        });
</script>
@endpush