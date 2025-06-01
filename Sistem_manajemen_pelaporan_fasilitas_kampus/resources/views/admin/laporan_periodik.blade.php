@extends('layouts.template')

@section('content')
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h5>Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan_periodik') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="bulan">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                @foreach(range(date('Y') - 5, date('Y')) as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-danger" id="exportPdf">Export PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            {{-- Status Perbaikan --}}
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Status Perbaikan Fasilitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kepuasan Pengguna --}}
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Kepuasan Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-title">Tingkat Kepuasan Pelapor Berdasarkan Rating</div>
                        <div style="display: flex; gap: 10px; flex-direction: column; max-width: 400px; margin: 0 auto;">
                            <div style="text-align: center; margin-bottom: 10px;">
                                <div style="font-size: 36px; font-weight: bold;">
                                    {{ number_format($averageRating, 1) }}
                                    <span>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($averageRating))
                                                <span style="color: #fbc02d;">★</span>
                                            @elseif($i - 0.5 <= $averageRating)
                                                <span style="color: #fbc02d;">½</span>
                                            @else
                                                <span style="color: #ccc;">★</span>
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                                <div style="color: #666;">{{ $kepuasan->sum('total') }} ulasan</div>
                            </div>

                            @php
                                $totalRatings = $kepuasan->sum('total');
                                $ratings = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

                                foreach ($kepuasan as $item) {
                                    $ratings[$item->rating] = $item->total;
                                }
                            @endphp

                            @foreach($ratings as $star => $count)
                                <div class="rating-bar" role="img"
                                    aria-label="{{ $count }} ulasan untuk rating bintang {{ $star }}">
                                    <div class="rating-star">{{ $star }}</div>
                                    <div class="rating-track">
                                        <div class="rating-fill"
                                            style="width: {{ $totalRatings > 0 ? ($count / $totalRatings * 100) : 0 }}%;"
                                            title="{{ $count }} ulasan"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kerusakan --}}
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Kerusakan per Bulan Tahun {{ $tahun }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Total Kerusakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trenKerusakan as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ DateTime::createFromFormat('!m', $item->bulan)->format('F') }}</td>
                                            <td>{{ $item->total }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total</strong></td>
                                        <td><strong>{{ $trenKerusakan->sum('total') }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Export PDF -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Laporan Periodik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.export_laporan_periodik') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulan_awal" class="form-label">Bulan Awal</label>
                            <select name="bulan_awal" id="bulan_awal" class="form-control">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == 1 ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulan_akhir" class="form-label">Bulan Akhir</label>
                            <select name="bulan_akhir" id="bulan_akhir" class="form-control">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == 12 ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                @foreach(range(date('Y') - 5, date('Y')) as $y)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Export PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Status Perbaikan Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($statusPerbaikan->pluck('status')) !!},
                    datasets: [{
                        data: {!! json_encode($statusPerbaikan->pluck('total')) !!},
                        backgroundColor: [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b'
                        ],
                        borderWidth: 0, // Pastikan ini ada
                        borderColor: 'transparent', // Tambahkan ini
                        hoverBackgroundColor: [
                            '#2e59d9',
                            '#17a673',
                            '#2c9faf',
                            '#dda20a',
                            '#be2617'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Export PDF Button
            document.getElementById('exportPdf').addEventListener('click', function () {
                $('#exportModal').modal('show');
            });
        });
    </script>

    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-star {
            font-weight: bold;
            width: 20px;
            text-align: center;
        }

        .rating-track {
            flex-grow: 1;
            height: 10px;
            background-color: #eee;
            border-radius: 5px;
            overflow: hidden;
        }

        .rating-fill {
            height: 100%;
            background-color: #fbc02d;
        }
    </style>
@endpush