@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="{{ url('/admin/laporan') }}" class="btn btn-sm btn-primary">← </a>
    </div>
    <div class="card-body">
        <h4>Detail Laporan</h4>
        <p><strong>Pelapor:</strong> {{ $laporan->pelapor->nama ?? '-' }}</p>
        <p><strong>Fasilitas:</strong> {{ $laporan->fasilitas->nama ?? '-' }}</p>
        <p><strong>Deskripsi:</strong> {{ $laporan->deskripsi }}</p>
        <p><strong>Urgensi:</strong> {{ $laporan->urgensi ?? '-' }}</p>
        <p><strong>Status:</strong>
            @switch($laporan->status)
                @case('diajukan') <span class="badge bg-secondary">Diajukan</span> @break
                @case('diproses') <span class="badge bg-warning">Diproses</span> @break
                @case('selesai') <span class="badge bg-success">Selesai</span> @break
                @case('ditolak') <span class="badge bg-danger">Ditolak</span> @break
                @default <span class="badge bg-light">{{ $laporan->status }}</span>
            @endswitch
        </p>

        @if ($laporan->status === 'diajukan')
            <form action="{{ route('admin.update_laporan', $laporan->laporan_id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="status" value="diproses">
                <button type="submit" class="btn btn-success">Terima</button>
            </form>

            <form action="{{ route('admin.update_laporan', $laporan->laporan_id) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="status" value="ditolak">
                <button type="submit" class="btn btn-danger">Tolak</button>
            </form>
        @endif
    </div>
</div>
@endsection
