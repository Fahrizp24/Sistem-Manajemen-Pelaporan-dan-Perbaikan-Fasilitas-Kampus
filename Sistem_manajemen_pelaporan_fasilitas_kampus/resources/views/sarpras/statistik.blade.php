@extends('layouts.template')

@section('content')

<section class="section">

</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/parsley.js') }}"></script>
@endpush@extends('layouts.template') 

@section('content')
<div class="header">
    <h1>Dashboard Monitoring Kerusakan</h1>
    <p>Statistik kerusakan dan respon perbaikan</p>
</div>

<div class="chart-row">
    <!-- Grafik Line Chart (Jumlah Kerusakan per Bulan) -->
    <div class="chart-box">
        <div class="chart-title">Jumlah Kerusakan per Bulan</div>
        <canvas id="lineChart" class="custom-line-chart"></canvas>
    </div>

    <!-- Grafik Pie Chart (Status Perbaikan) -->
    <div class="chart-box">
        <div class="chart-title">Status Perbaikan Kerusakan</div>
        <canvas id="pieChart"></canvas>
    </div>
</div>

<!-- Grafik Bar Chart (Tingkat Kepuasan Pelapor) -->
<div class="chart-box full-width">
    <div class="chart-title">Tingkat Kepuasan Pelapor Berdasarkan Rating</div>
    <div style="display: flex; gap: 10px; flex-direction: column; max-width: 400px; margin: 0 auto;">
        @php
            $ratings = [
                ['bintang' => 5, 'jumlah' => 31478],
                ['bintang' => 4, 'jumlah' => 4055],
                ['bintang' => 3, 'jumlah' => 2873],
                ['bintang' => 2, 'jumlah' => 778],
                ['bintang' => 1, 'jumlah' => 3666],
            ];
            $total = array_sum(array_column($ratings, 'jumlah'));
            $totalNilaiRating = array_reduce($ratings, fn($carry, $rate) => $carry + $rate['bintang'] * $rate['jumlah'], 0);
            $rataRataRating = round($totalNilaiRating / $total, 1);
            $fullStars = floor($rataRataRating);
            $halfStar = ($rataRataRating - $fullStars) >= 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp

        <div style="text-align: center; margin-bottom: 10px;">
            <div style="font-size: 36px; font-weight: bold;">
                {{ $rataRataRating }}
                <span>
                    @for ($i = 0; $i < $fullStars; $i++)
                        <span style="color: #fbc02d;">★</span>
                    @endfor
                    @if ($halfStar)
                        <span style="color: #fbc02d;">☆</span>
                    @endif
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <span style="color: #ccc;">★</span>
                    @endfor
                </span>
            </div>
            <div style="color: #666;">{{ number_format($total, 0, ',', '.') }} ulasan</div>
        </div>

        @foreach($ratings as $rate)
            @php $percentage = round(($rate['jumlah'] / $total) * 100, 2); @endphp
            <div class="rating-bar" role="img" aria-label="{{ $rate['jumlah'] }} ulasan untuk rating bintang {{ $rate['bintang'] }}">
                <div class="rating-star">{{ $rate['bintang'] }}</div>
                <div class="rating-track">
                    <div class="rating-fill" style="width: {{ $percentage }}%;" title="{{ $rate['jumlah'] }} ulasan"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        color: #333;
        padding: 20px;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .chart-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .chart-box {
        flex: 1;
        min-width: 300px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .full-width {
        width: 100%;
        margin-top: 20px;
    }

    canvas {
        width: 100% !important;
    }

    .custom-line-chart {
        height: 400px !important;
    }

    .rating-bar {
        display: flex;
        align-items: center;
    }

    .rating-star {
        width: 20px;
        text-align: center;
        font-weight: bold;
        color: #444;
    }

    .rating-track {
        flex: 1;
        background-color: #eee;
        height: 12px;
        border-radius: 6px;
        overflow: hidden;
        margin-left: 8px;
    }

    .rating-fill {
        height: 100%;
        background-color: #4CAF50;
    }

    #pieChart {
        width: 100% !important;
        height: auto !important;
        max-width: 400px;
        aspect-ratio: 1 / 1;
        display: block;
        margin: 0 auto;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const damageData = [15, 22, 18, 25, 30, 28, 35, 40, 32, 28, 25, 20];

    const repairStatus = ['Sudah Diperbaiki', 'Belum Diperbaiki'];
    const repairData = [75, 25];

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Kerusakan',
                data: damageData,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // agar tinggi bisa diatur via CSS
            plugins: {
                legend: {
                    position: 'bottom' 
                }
            }
        }
    });

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: repairStatus,
            datasets: [{
                data: repairData,
                backgroundColor: ['#2ecc71', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
=======
@extends('layouts.template')

@section('content')

<section class="section">

</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/parsley.js') }}"></script>
@endpush
>>>>>>> 2531cc1aff8a42748852f0e772e66bd842b0d2c5
