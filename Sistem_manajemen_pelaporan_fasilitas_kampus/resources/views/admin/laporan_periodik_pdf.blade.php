<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Kerusakan</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 20px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        td,
        th {
            padding: 8px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        .d-block {
            display: block;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .font-bold {
            font-weight: bold;
        }

        .border-bottom-header {
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .border-all {
            border: 1px solid #000;
        }

        .border-all th,
        .border-all td {
            border: 1px solid #000;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 14pt;
        }

        .periode {
            margin-bottom: 15px;
            font-size: 11pt;
        }
    </style>
</head>

<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('storage/logo-polinema.png') }}" alt="Logo" class="logo">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN
                    TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341)
                    404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN KERUSAKAN PER BULAN</h3>
    <p class="text-center periode">Periode: {{ \Carbon\Carbon::create()->month($bulan_awal)->format('F') }} -
        {{ \Carbon\Carbon::create()->month($bulan_akhir)->format('F') }} {{ $tahun }}</p>

    <table class="border-all">
        <thead>
            <tr>
                <th width="10%" class="text-center">No</th>
                <th width="60%">Bulan</th>
                <th width="30%" class="text-center">Total Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($item->bulan)->format('F') }}</td>
                    <td class="text-center">{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="text-center">Kerusakan per Gedung</h3>
    <p class="text-center periode">Periode: {{ \Carbon\Carbon::create()->month($bulan_awal)->format('F') }} -
        {{ \Carbon\Carbon::create()->month($bulan_akhir)->format('F') }} {{ $tahun }}</p>
    <table class="border-all">
        <thead>
            <tr>
                <th width="10%" class="text-center">No</th>
                <th width="60%">Nama Gedung</th>
                <th width="30%" class="text-center">Total Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kerusakanPerGedung as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->gedung_nama }}</td>
                    <td class="text-center">{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>