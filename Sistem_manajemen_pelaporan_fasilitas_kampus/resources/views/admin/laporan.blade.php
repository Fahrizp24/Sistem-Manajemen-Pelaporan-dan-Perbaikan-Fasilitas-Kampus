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
                        <th>Foto</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @php $no = 1; @endphp
                            @forelse (array_merge($laporan_masuk->all(), $laporan_progress->all(), $laporan_selesai->all()) as $laporan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $laporan->pelapor->nama ?? '-' }}</td>
                                    <td>{{ $laporan->fasilitas->nama ?? '-' }}</td>
                                    <td>{{ $laporan->deskripsi }}</td>
                                    <td>
                                        @if ($laporan->foto)
                                            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto" width="80">
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        @switch($laporan->status)
                                            @case('diajukan')
                                                <span class="badge bg-secondary">Diajukan</span>
                                                @break
                                            @case('diproses')
                                                <span class="badge bg-warning">Diproses</span>
                                                @break
                                            @case('selesai')
                                                <span class="badge bg-success">Selesai</span>
                                                @break
                                            @case('ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($laporan->status === 'diajukan')
                                            <form action="{{ route('laporan.terima', $laporan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Terima</button>
                                            </form>
                                            <form action="{{ route('laporan.tolak', $laporan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Tolak</button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Tidak ada data laporan.</td></tr>
                            @endforelse --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection
