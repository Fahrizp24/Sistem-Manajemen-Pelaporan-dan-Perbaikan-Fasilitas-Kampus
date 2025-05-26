@extends('layouts.template')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-striped dataTable no-footer w-100" id="table1" aria-describedby="table1_info">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 150px;">Nama Pelapor</th>
                            <th style="width: 150px;">Nama Fasilitas</th>
                            <th style="width: 100px;">Skor</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($laporan_kerusakan as $key => $item)
                        <tr class="{{ $key % 2 == 0 ? 'even' : 'odd' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->user->name ?? 'N/A' }}</td>
                            <td>{{ $item->fasilitas }}</td>
                            <td>
                                @if(isset($item->skor))
                                    <span class="badge bg-{{ $item->skor >= 7 ? 'success' : ($item->skor >= 4 ? 'warning' : 'danger') }}">
                                        {{ $item->skor }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('laporan.detail', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    {{-- Showing {{ $laporan_kerusakan->firstItem() }} to {{ $laporan_kerusakan->lastItem() }} of {{ $laporan_kerusakan->total() }} entries --}}
                </div>
                <div class="col-md-6 text-end">
                    {{-- {{ $laporan_kerusakan->links('pagination::bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/parsley.js') }}"></script>
@endpush
