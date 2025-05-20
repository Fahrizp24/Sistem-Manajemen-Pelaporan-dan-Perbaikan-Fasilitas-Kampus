@extends('layouts.template')

@section('content')

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Daftar Laporan
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="table1_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="table1_length"><label>Show <select name="table1_length"
                                        aria-controls="table1" class="form-select form-select-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> entries</label></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="table1_filter" class="dataTables_filter"><label>Search:<input type="search"
                                        class="form-control form-control-sm" placeholder=""
                                        aria-controls="table1"></label></div>
                        </div>
                    </div>
                    <div class="row dt-row">
                        <div class="col-sm-12">
                            <table class="table dataTable no-footer" id="table1" aria-describedby="table1_info">
                                <thead>
                                    <tr>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="No: activate to sort column ascending"
                                            style="width: 50px;">No</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Nama Pelapor: activate to sort column ascending"
                                            style="width: 150px;">Nama Pelapor</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Fasilitas: activate to sort column ascending"
                                            style="width: 150px;">Nama Fasilitas</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Skor: activate to sort column ascending"
                                            style="width: 100px;">Skor</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Aksi: activate to sort column ascending"
                                            style="width: 100px;">Aksi</th>
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
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="table1_info" role="status" aria-live="polite">
                                {{-- Showing {{ $laporan_kerusakan->firstItem() }} to {{ $laporan_kerusakan->lastItem() }} of {{ $laporan_kerusakan->total() }} entries --}}
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="table1_paginate">
                                {{-- {{ $laporan_kerusakan->links('pagination::bootstrap-5') }} --}}
                            </div>
                        </div>
                    </div>
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