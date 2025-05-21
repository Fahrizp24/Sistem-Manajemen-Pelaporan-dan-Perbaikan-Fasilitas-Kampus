@extends('layouts.template')

@section('content')
<div class="page-heading">
    <h3>{{ $title }}</h3>
</div>

<div class="page-content">
    <div class="row">
        {{-- Statistik Kerusakan Berdasarkan Tingkat --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5>Kerusakan Berdasarkan Tingkat</h5></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($kerusakan as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ ucfirst($item->tingkat_kerusakan) }}</span>
                                <span class="badge bg-primary">{{ $item->total }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Statistik Status Perbaikan --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h5>Status Perbaikan</h5></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($status_perbaikan as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ ucfirst($item->status_perbaikan) }}</span>
                                <span class="badge bg-success">{{ $item->total }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
