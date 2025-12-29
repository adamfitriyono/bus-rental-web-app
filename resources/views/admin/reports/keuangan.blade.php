@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-wallet me-2 text-success"></i>Laporan Keuangan
            </h2>
            <p class="text-muted mb-0">Analisis keuangan dan metode pembayaran</p>
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
            <form action="{{ route('admin.report.keuangan') }}" method="GET" class="row g-3">
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
                    <a href="{{ route('admin.report.keuangan') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Pendapatan</p>
                            <h4 class="mb-0 text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                            <small class="text-muted">Pembayaran Lunas</small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Pending Payment</p>
                            <h4 class="mb-0 text-warning">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</h4>
                            <small class="text-muted">Menunggu Verifikasi</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-clock text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Cancelled Revenue</p>
                            <h4 class="mb-0 text-danger">Rp {{ number_format($cancelledRevenue, 0, ',', '.') }}</h4>
                            <small class="text-muted">Pemesanan Dibatalkan</small>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-times-circle text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Transaksi</p>
                            <h4 class="mb-0 text-info">{{ number_format($totalTransactions, 0, ',', '.') }}</h4>
                            <small class="text-muted">{{ $pendingVerification }} butuh verifikasi</small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-exchange-alt text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Trend -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tren Pendapatan Harian</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Payment Method & Transaction Details -->
    <div class="row mb-4">
        <!-- Payment Method Breakdown -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pendapatan per Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart"></canvas>
                    
                    <!-- Method Details -->
                    <div class="mt-4">
                        @foreach($revenueByMethod as $method)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                            <div>
                                <i class="fas fa-circle text-primary me-2" style="font-size: 8px;"></i>
                                <strong>{{ ucwords(str_replace('_', ' ', $method->metode_pembayaran)) }}</strong>
                            </div>
                            <div class="text-end">
                                <div class="text-success fw-bold">Rp {{ number_format($method->total, 0, ',', '.') }}</div>
                                @php
                                    $percentage = $totalRevenue > 0 ? ($method->total / $totalRevenue) * 100 : 0;
                                @endphp
                                <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary Table -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ringkasan Keuangan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                                        <strong>Total Pendapatan (Lunas)</strong>
                                    </td>
                                    <td class="text-end py-3">
                                        <h5 class="text-success mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        Pending Payments
                                    </td>
                                    <td class="text-end py-3">
                                        <strong class="text-warning">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        Cancelled Revenue
                                    </td>
                                    <td class="text-end py-3">
                                        <strong class="text-danger">- Rp {{ number_format($cancelledRevenue, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <i class="fas fa-calculator text-info me-2"></i>
                                        Estimated Net Revenue
                                    </td>
                                    <td class="text-end py-3">
                                        @php
                                            $netRevenue = $totalRevenue - ($cancelledRevenue * 0.1); // Assuming 10% refund fee
                                        @endphp
                                        <strong class="text-info">Rp {{ number_format($netRevenue, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td class="py-3">
                                        <i class="fas fa-chart-line text-primary me-2"></i>
                                        <strong>Potential Total (with Pending)</strong>
                                    </td>
                                    <td class="text-end py-3">
                                        <h5 class="text-primary mb-0">Rp {{ number_format($totalRevenue + $pendingPayments, 0, ',', '.') }}</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Alert -->
                    @if($pendingVerification > 0)
                    <div class="alert alert-warning mb-0 mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ $pendingVerification }}</strong> transaksi menunggu verifikasi bukti transfer.
                        <a href="{{ route('admin.pemesanan.index') }}?status=pending" class="alert-link">Verifikasi Sekarang</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Daily Revenue Chart
    const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    const dailyData = @json($dailyRevenue);
    
    new Chart(dailyRevenueCtx, {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Pendapatan Harian',
                data: dailyData.map(d => d.total),
                backgroundColor: 'rgba(25, 135, 84, 0.8)',
                borderColor: 'rgb(25, 135, 84)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                        }
                    }
                }
            }
        }
    });

    // Payment Method Chart (Pie)
    const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const methodData = @json($revenueByMethod);
    
    new Chart(methodCtx, {
        type: 'pie',
        data: {
            labels: methodData.map(m => m.metode_pembayaran.replace(/_/g, ' ').toUpperCase()),
            datasets: [{
                data: methodData.map(m => m.total),
                backgroundColor: [
                    'rgba(13, 110, 253, 0.8)',  // blue
                    'rgba(25, 135, 84, 0.8)',   // green
                    'rgba(255, 193, 7, 0.8)',   // yellow
                    'rgba(220, 53, 69, 0.8)',   // red
                    'rgba(13, 202, 240, 0.8)',  // cyan
                    'rgba(111, 66, 193, 0.8)'   // purple
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection
