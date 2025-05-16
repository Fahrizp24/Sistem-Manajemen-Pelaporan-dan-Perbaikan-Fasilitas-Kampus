@extends('layouts.template')
@section('title', 'Tambah User Baru')

@section('content')
<div class="row">
    <!-- Line Chart Example -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Monthly Sales</h4>
            </div>
            <div class="card-body">
                <canvas id="lineChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Pie Chart Example -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Product Distribution</h4>
            </div>
            <div class="card-body">
                <canvas id="pieChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<!-- Add any chart-specific CSS here -->
<style>
    .chart-container {
        position: relative;
        margin: auto;
    }
</style>
@endpush

@push('js')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Monthly Sales',
                data: [65, 59, 80, 81, 56, 55],
                borderColor: '#4CAF50',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Electronics', 'Fashion', 'Home & Living', 'Beauty'],
            datasets: [{
                data: [30, 25, 20, 25],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@push('css')
    <!-- CSS tambahan spesifik untuk halaman ini -->
@endpush

@push('js')
    <!-- JavaScript tambahan spesifik untuk halaman ini -->
@endpush