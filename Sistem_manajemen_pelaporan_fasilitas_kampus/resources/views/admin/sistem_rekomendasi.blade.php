@extends('layouts.template')

@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ url('admin/sistem_rekomendasi/update') }}" method="POST">
                @csrf
                {{-- @foreach ($pengaturan as $item)
                    <div class="mb-3">
                        <label class="form-label">{{ ucwords(str_replace('_', ' ', $item->nama_pengaturan)) }}</label>
                        <input type="text" name="{{ $item->nama_pengaturan }}" class="form-control" value="{{ $item->nilai }}">
                    </div>
                @endforeach --}}
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</div>
@endsection
