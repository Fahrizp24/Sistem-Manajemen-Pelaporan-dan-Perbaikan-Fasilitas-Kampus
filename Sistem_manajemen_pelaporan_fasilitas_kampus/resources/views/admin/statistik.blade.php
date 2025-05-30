@extends('layouts.template')

@section('content')
    <div class="page-content">
        <div class="row">
            {{-- Statistik Kerusakan Berdasarkan Tingkat --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Kerusakan Berdasarkan Tingkat</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Statistik Status Perbaikan --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Status Perbaikan</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Line Chart untuk status laporan
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        label: 'Jumlah Laporan per Status',
                        data: @json($statusData),
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Bar Chart untuk kerusakan berdasarkan urgensi
            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: @json($urgensiLabels),
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: @json($urgensiData),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',   // tinggi
                            'rgba(255, 206, 86, 0.6)',   // sedang
                            'rgba(75, 192, 192, 0.6)'    // rendah
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false // Menghilangkan legend (kotak warna)
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Laporan',
                            position: 'left', // Posisi title di kiri
                            align: 'center', 
                            font: {
                                size: 14
                            },
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Jumlah',
                        font: {
                            weight: 'bold'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tingkat Urgensi',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush