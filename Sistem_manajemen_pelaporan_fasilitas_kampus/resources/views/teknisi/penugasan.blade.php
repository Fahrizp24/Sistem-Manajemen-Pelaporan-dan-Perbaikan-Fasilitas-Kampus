@extends('layouts.template')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Daftar Penugasan
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
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="No: activate to sort column ascending"
                                                style="width: 50px;">No</th>
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="Fasilitas: activate to sort column ascending"
                                                style="width: 50px;">Gedung</th>
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="Fasilitas: activate to sort column ascending"
                                                style="width: 100px;">Fasilitas</th>
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="Deskripsi: activate to sort column ascending"
                                                style="width: 200px;">Deskripsi</th>
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 50px;">Status</th>
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="Aksi: activate to sort column ascending"
                                                style="width: 200px;">Tanggal Laporan</th>
                                            <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1"
                                                colspan="1" aria-label="Aksi: activate to sort column ascending"
                                                style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laporan as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->fasilitas->gedung->nama }}</td>
                                                <td>{{ $item->fasilitas->nama }}</td>
                                                <td>{{ $item->deskripsi ?? 'N/A' }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>
                                                    <a href="{{ route('teknisi.penugasan.edit', $item->laporan_id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi-pencil-square"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="table1_info" role="status" aria-live="polite">
                                Showing {{ $laporan->firstItem() }} to {{ $laporan->lastItem() }} of {{ $laporan->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="table1_paginate">
                                {{ $laporan->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('/mazer/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/mazer/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('/mazer/assets/static/js/pages/parsley.js') }}"></script>
@endpush
