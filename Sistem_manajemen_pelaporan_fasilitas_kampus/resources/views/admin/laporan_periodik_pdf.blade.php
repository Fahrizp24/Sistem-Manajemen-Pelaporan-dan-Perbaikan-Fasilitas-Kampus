<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kerusakan</title>
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/compiled/css/laporan-periodik.css') }}">
</head>
<body>

    <div class="kop">
        <img src="{{ asset('img/logo-polinema.png') }}" alt="Logo" width="80">
        <div class="text">
            <h1>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h1>
            <h2>POLITEKNIK NEGERI MALANG</h2>
            <h2>JURUSAN TEKNOLOGI INFORMASI</h2>
            <p>Jl. Soekarno Hatta No. 9, Jatimulyo, Lowokwaru, Malang 65141</p>
            <p>Telp. (0341) 404424 – 404425, Fax (0341) 404420</p>
            <p><i>http://www.polinema.ac.id</i></p>
        </div>
    </div>
    <hr>

    <div class="title">
        <h3>LAPORAN KERUSAKAN PER BULAN</h3>
        <p>Periode: {{ \Carbon\Carbon::create()->month($bulan_awal)->format('F') }} - {{ \Carbon\Carbon::create()->month($bulan_akhir)->format('F') }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Total Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($item->bulan)->format('F') }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
