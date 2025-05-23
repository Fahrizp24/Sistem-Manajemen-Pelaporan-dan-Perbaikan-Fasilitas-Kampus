@extends('layouts.template')

@section('content')
    <section class="section">
        @empty($laporan)
            <div id="modal-master" class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                            Data yang anda cari tidak ditemukan
                        </div>
                        <a href="{{ url('/teknisi/penugasan/') }}" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
            </div>
        @else
            <form action="{{ url('/teknisi/penugasan/' . $laporan->laporan_id . '/') }}" method="POST" id="form-edit">
                @csrf
                @method('PUT')
                <div id="modal-master" class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Detail Laporan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">                
                            <table class="table table-bordered table-striped table-hover table-sm">
                            <tr>
                                <th>ID</th>
                                <td>{{ $laporan->laporan_id }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $laporan->fasilitas->gedung->nama }}</td>
                            </tr>
                            <tr>
                                <th>Kode</th>
                                <td>{{ $laporan->fasilitas->nama }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $laporan->deskripsi }}</td>
                            </tr>
                            <tr>
                                <th>Harga Beli</th>
                                <td>{{ $laporan->status }}</td>
                            </tr>
                            <tr>
                                <th>Harga Jual</th>
                                <td>{{ $laporan->created_at }}</td>
                            </tr>
                        </table>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-warning">Kembali</button>
                            <button type="submit" class="btn btn-primary">Ajukan ke Sarpras</button>
                        </div>
                    </div>
                </div>
            </form>
        @endempty
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('/mazer/assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/mazer/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('/mazer/assets/static/js/pages/parsley.js') }}"></script>
@endpush