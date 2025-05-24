@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelapor</th>
                        <th>Fasilitas</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Urgensi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
@forelse ($laporan as $item)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $item->pelapor->nama ?? '-' }}</td>
        <td>{{ $item->fasilitas->nama ?? '-' }}</td>
        <td>{{ $item->deskripsi }}</td>
        
        
        <td>
            @switch($item->status)
                @case('diajukan') <span class="badge bg-secondary">Diajukan</span> @break
                @case('diproses') <span class="badge bg-warning">Diproses</span> @break
                @case('selesai') <span class="badge bg-success">Selesai</span> @break
                @case('ditolak') <span class="badge bg-danger">Ditolak</span> @break
                @default <span class="badge bg-light">{{ $item->status }}</span>
            @endswitch
        </td>
        <td>
            <a href="{{ url('/admin/laporan/' . $item->laporan_id) }}" class="btn btn-info btn-sm">Detail</a>
        </td>
        <td>
            {{ $item->urgensi ?? '-' }}
        </td>
    </tr>
@empty
    <tr><td colspan="8" class="text-center">Tidak ada data laporan.</td></tr>
@endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
