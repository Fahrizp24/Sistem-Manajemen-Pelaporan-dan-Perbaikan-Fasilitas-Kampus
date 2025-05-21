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
                            <table class="table dataTable no-footer" id="table_penugasan" aria-describedby="table1_info">
                                <thead>
                                    <tr>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="No: activate to sort column ascending"
                                            style="width: 50px;">No</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Fasilitas: activate to sort column ascending"
                                            style="width: 150px;">Fasilitas</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Tanggal Laporan: activate to sort column ascending"
                                            style="width: 120px;">Tanggal Laporan</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Deskripsi: activate to sort column ascending"
                                            style="width: 250px;">Deskripsi</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Status: activate to sort column ascending"
                                            style="width: 150px;">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="table1" rowspan="1" colspan="1"
                                            aria-label="Aksi: activate to sort column ascending"
                                            style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function() {
            var dataPenugasan = $('#table_penugasan').DataTable({
                // serverSide: true, jika ingin menggunakan server-side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('penugasan/penugasan_list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.penugasan_kode = $('#penugasan_kode').val();
                    }

                },
                columns: [
                    // nomor urut dari laravel datatable addIndexColumn()
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "pengguna.nama",
                        className: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true
                    }, {
                        data: "fasilitas.nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    }, {
                        data: "pengguna.nama",
                        className: "",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "pengguna.nama",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "deskripsi",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "foto",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "status",
                        className: "",
                        orderable: false,
                        searchable: false
                    },{
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#penugasan_kode').on('change', function() {
                datapenugasan.ajax.reload();
            })
        });
    </script>
@endpush

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/parsley.js') }}"></script>
@endpush