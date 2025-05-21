@extends('layouts.template') 

@section('content')
<section id="laporan-detail">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detail Laporan</h4>
        </div>
        <div class="card-body">
            <!-- Tampilkan detail laporan -->
            <div class="row mb-2">
                <div class="col-4"><strong>Judul</strong></div>
                <div class="col-8">{{ $laporan->fasilitas->nama }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><strong>Deskripsi</strong></div>
                <div class="col-8">{{ $laporan->deskripsi }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><strong>Status</strong></div>
                <div class="col-8">{{ $laporan->status }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><strong>Tanggal Laporan</strong></div>
                <div class="col-8">{{ $laporan->created_at }}</div>
            </div>

            <!-- Pesan respons -->
            <div id="statusMessage" class="mt-3"></div>

            <!-- Tombol Aksi -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('teknisi.penugasan') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

                @if ($laporan->status === 'sedang diperbaiki')
                <button id="ajukanSelesai" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Ajukan Penyelesaian
                </button>
                @else
                <button class="btn btn-success" disabled>
                    Sudah Diajukan
                </button>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- AJAX -->
<script>
    document.getElementById('ajukanSelesai')?.addEventListener('click', function () {
        if (!confirm('Ajukan penyelesaian laporan ini?')) return;

        fetch('{{ route("teknisi.penugasan.update", $laporan->laporan_id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            const msg = document.getElementById('statusMessage');
            if (data.status) {
                msg.innerHTML = <div class="alert alert-success">${data.message}</div>;
                document.getElementById('ajukanSelesai').disabled = true;
                document.getElementById('ajukanSelesai').innerText = 'Sudah Diajukan';
            } else {
                msg.innerHTML = <div class="alert alert-danger">${data.message}</div>;
            }
        })
        .catch(error => {
            document.getElementById('statusMessage').innerHTML =
                <div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>;
        });
    });
</script>
@endsection