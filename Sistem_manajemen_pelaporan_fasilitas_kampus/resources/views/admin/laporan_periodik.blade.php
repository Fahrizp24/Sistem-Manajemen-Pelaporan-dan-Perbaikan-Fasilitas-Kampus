@extends('layouts.template')

@section('content')

<div class="page-content">
    <div class="row">

        {{-- Status Perbaikan --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header"><h5>Status Perbaikan Fasilitas</h5></div>
                <div class="card-body">
                    <ul class="list-group">
                        {{-- @foreach($laporan as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ ucfirst($item->status) }}</span>
                                <span class="badge bg-primary">{{ $item->total }}</span>
                            </li>
                        @endforeach --}}
                    </ul>
                </div>
            </div>
        </div>

        {{-- Kepuasan Pengguna --}}
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header"><h5>Kepuasan Pengguna</h5></div>
                <div class="card-body">
                    <ul class="list-group">
                        {{-- @foreach($kepuasan as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ ucfirst($item->tingkat_kepuasan) }}</span>
                                <span class="badge bg-success">{{ $item->total }}</span>
                            </li>
                        @endforeach --}}
                    </ul>
                </div>
            </div>
        </div>

        {{-- Tren Kerusakan --}}
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header"><h5>Tren Kerusakan per Bulan</h5></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Total Kerusakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($tren_kerusakan as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::create()->month($item->bulan)->format('F') }}</td>
                                    <td>{{ $item->total }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
