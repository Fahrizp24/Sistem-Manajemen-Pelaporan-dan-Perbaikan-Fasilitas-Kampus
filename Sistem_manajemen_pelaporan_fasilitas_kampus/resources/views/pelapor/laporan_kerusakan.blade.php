@extends('layouts.template')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan Kerusakan Fasilitas</h3>
                <p class="text-subtitle text-muted">
                    Formulir pelaporan kerusakan fasilitas kampus
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
                        <li class="breadcrumb-item active">Laporan Kerusakan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulir Laporan</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form class="form" method="POST" action="{{ route('laporan-kerusakan.store') }}" enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mandatory">
                                            <label for="fasilitas_id" class="form-label">Fasilitas</label>
                                            <select id="fasilitas_id" class="form-select" name="fasilitas_id" data-parsley-required="true">
                                                <option value="">Pilih Fasilitas</option>
                                                {{-- @foreach($facilities as $fasilitas)
                                                    <option value="{{ $fasilitas->id }}">{{ $fasilitas->nama_fasilitas }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="foto" class="form-label">Foto Kerusakan</label>
                                            <input type="file" id="foto" class="form-control" name="foto" accept="image/*">
                                            <small class="text-muted">Format: jpeg, png, jpg, gif (max: 2MB)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="deskripsi" class="form-label">Deskripsi Kerusakan</label>
                                            <textarea id="deskripsi" class="form-control" name="deskripsi" 
                                                rows="3" placeholder="Jelaskan kerusakan yang terjadi" 
                                                data-parsley-required="true"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mandatory mt-3">
                                            <label for="tingkat_urgensi" class="form-label">Tingkat Urgensi</label>
                                            <select id="tingkat_urgensi" name="tingkat_urgensi" class="form-select" data-parsley-required="true">
                                                <option value="">Pilih tingkat urgensi</option>
                                                <option value="rendah">Rendah</option>
                                                <option value="sedang">Sedang</option>
                                                <option value="tinggi">Tinggi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">
                                            Kirim Laporan
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/parsley.js') }}"></script>
@endpush