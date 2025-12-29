@extends('layouts.admin')

@section('title', 'Laporan Occupancy')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-chart-pie me-2 text-info"></i>Laporan Occupancy
            </h2>
            <p class="text-muted mb-0">Analisis tingkat hunian kursi dan utilisasi bus</p>
        </div>
        <div>
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.report.occupancy') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.report.occupancy') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Overall Occupancy -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white py-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-3">Tingkat Hunian Keseluruhan</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1 opacity-75">Total Kursi</p>
                                    <h2 class="mb-0">{{ number_format($totalSeats, 0, ',', '.') }}</h2>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 opacity-75">Kursi Terisi</p>
                                    <h2 class="mb-0">{{ number_format($occupiedSeats, 0, ',', '.') }}</h2>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 opacity-75">Kursi Tersedia</p>
                                    <h2 class="mb-0">{{ number_format($totalSeats - $occupiedSeats, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="position-relative d-inline-block">
                                <svg width="200" height="200">
                                    <circle cx="100" cy="100" r="90" stroke="#ffffff40" stroke-width="20" fill="none" />
                                    <circle cx="100" cy="100" r="90" 
                                            stroke="#ffffff" 
                                            stroke-width="20" 
                                            fill="none"
                                            stroke-dasharray="{{ 2 * 3.14159 * 90 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 90 * (1 - $occupancyRate / 100) }}"
                                            transform="rotate(-90 100 100)" />
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h1 class="display-3 mb-0">{{ number_format($occupancyRate, 1) }}%</h1>
                                    <p class="mb-0">Occupancy</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy by Route & Popular Routes -->
    <div class="row mb-4">
        <!-- Occupancy by Route -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Occupancy per Rute</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rute</th>
                                    <th class="text-center">Trips</th>
                                    <th class="text-center">Kapasitas</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Occupancy</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($occupancyByRoute as $route)
                                <tr>
                                    <td><strong>{{ $route->route }}</strong></td>
                                    <td class="text-center">{{ $route->total_trips }}</td>
                                    <td class="text-center">{{ number_format($route->total_capacity) }}</td>
                                    <td class="text-center">{{ number_format($route->occupied_seats) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 25px;">
                                                @php
                                                    $rate = $route->occupancy_rate;
                                                    $color = $rate >= 80 ? 'success' : ($rate >= 50 ? 'warning' : 'danger');
                                                @endphp
                                                <div class="progress-bar bg-{{ $color }}" 
                                                     style="width: {{ $rate }}%">
                                                    {{ number_format($rate, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Popular Routes -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Rute Terpopuler</h5>
                </div>
                <div class="card-body">
                    @forelse($popularRoutes as $index => $route)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <strong>{{ $index + 1 }}</strong>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="d-block">{{ $route->route }}</strong>
                            <small class="text-muted">
                                {{ $route->total_bookings }} pemesanan • 
                                {{ $route->total_seats }} kursi terjual
                            </small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-fire text-danger fa-2x"></i>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy by Bus & Peak Hours -->
    <div class="row mb-4">
        <!-- Occupancy by Bus -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Utilisasi Bus</h5>
                </div>
                <div class="card-body">
                    <canvas id="busOccupancyChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Peak Hours -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Jam Sibuk</h5>
                </div>
                <div class="card-body">
                    @forelse($peakHours->take(10) as $hour)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>{{ str_pad($hour->hour, 2, '0', STR_PAD_LEFT) }}:00</strong>
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ $hour->total_trips }} trips</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Bus Occupancy Chart (Horizontal Bar)
    const busCtx = document.getElementById('busOccupancyChart').getContext('2d');
    const busData = @json($occupancyByBus->where('total_trips', '>', 0));
    
    new Chart(busCtx, {
        type: 'bar',
        data: {
            labels: busData.map(b => b.nama_bus),
            datasets: [
                {
                    label: 'Kursi Terisi',
                    data: busData.map(b => b.occupied_seats),
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                },
                {
                    label: 'Kursi Tersedia',
                    data: busData.map(b => b.total_capacity - b.occupied_seats),
                    backgroundColor: 'rgba(220, 53, 69, 0.3)',
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const bus = busData[context.dataIndex];
                            return 'Occupancy: ' + bus.occupancy_rate.toFixed(1) + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                }
            }
        }
    });
</script>
@endpush
@endsection
