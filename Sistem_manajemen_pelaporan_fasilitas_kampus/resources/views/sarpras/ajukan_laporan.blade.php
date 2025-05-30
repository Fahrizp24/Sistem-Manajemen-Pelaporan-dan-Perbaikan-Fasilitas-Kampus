@extends('layouts.template')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Ajukan Laporan</h5>
        </div>

        {{-- Tabel Laporan Diterima --}}
        <div class="card-body">
            {{-- <input type="text" id="searchLaporan" class="form-control mb-2" placeholder="Cari laporan..."> --}}
            
            <table class="table table-bordered" id="laporanTable">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Judul Laporan</th>
                        <th>Pelapor</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="laporan-checkbox" value="{{ $item->id }}">
                            </td>
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->nama_pengguna }}</td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary mt-2" id="btnProsesSPK">Proses SPK</button>
        </div>
    </div>

    {{-- Tabel Hasil SPK (disembunyikan awalnya) --}}
    <div class="card mt-3" id="hasilSPKCard" style="display: none;">
        <div class="card-header">
            <strong>Hasil Sistem Rekomendasi</strong>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="hasilSPKTable">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Judul Laporan</th>
                        <th>Skor Akhir</th>
                    </tr>
                </thead>
                <tbody id="hasilSPKBody">
                    {{-- Akan diisi lewat JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Filter pencarian laporan
        // $("#searchLaporan").on("keyup", function () {
        //     var value = $(this).val().toLowerCase();
        //     $("#laporanTable tbody tr").filter(function () {
        //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        //     });
        // });

        // Proses SPK saat tombol diklik
        $('#btnProsesSPK').on('click', function () {
            let selected = [];
            $('.laporan-checkbox:checked').each(function () {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                alert("Pilih minimal satu laporan.");
                return;
            }

            // Kirim ke backend untuk proses SPK
            $.ajax({
                url: "{{ route('proses.spk') }}",
                type: "POST",
                data: {
                    laporan_ids: selected,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    let tbody = '';
                    response.data.forEach((item, index) => {
                        tbody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.judul}</td>
                                <td>${item.skor}</td>
                            </tr>`;
                    });
                    $('#hasilSPKBody').html(tbody);
                    $('#hasilSPKCard').show();
                },
                error: function () {
                    alert("Terjadi kesalahan saat memproses SPK.");
                }
            });
        });
    });
</script>
@endpush
