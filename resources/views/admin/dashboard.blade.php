@extends('admin.main')
@section('content')
<main>
    <div class="container-fluid px-4">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Dashboard Admin</h2>
                <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}</p>
            </div>
            <div>
                <span class="badge bg-light text-dark p-2 shadow-sm">
                    <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                </span>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fas fa-ticket-alt text-primary fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Pemesanan</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_pemesanan }}</h2>
                        <p class="text-muted small mb-0">Pemesanan aktif</p>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('admin.pemesanan.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-medium">Kelola Pemesanan</span>
                            <i class="fas fa-arrow-right text-primary"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="fas fa-users text-success fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Pengguna</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_user }}</h2>
                        <p class="text-muted small mb-0">Pengguna terdaftar</p>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('admin.user') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-success fw-medium">Kelola Pengguna</span>
                            <i class="fas fa-arrow-right text-success"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                <i class="fas fa-bus text-info fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Bus</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_bus }}</h2>
                        <p class="text-muted small mb-0">Armada aktif</p>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('admin.bus.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-info fw-medium">Kelola Bus</span>
                            <i class="fas fa-arrow-right text-info"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="fas fa-route text-warning fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Rute</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_rute }}</h2>
                        <p class="text-muted small mb-0">Rute tersedia</p>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('admin.rute.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-warning fw-medium">Kelola Rute</span>
                            <i class="fas fa-arrow-right text-warning"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-2">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    Pendapatan Bulan Ini
                                </h5>
                                <h2 class="fw-bold text-success mb-0">Rp {{ number_format($pendapatan_bulan_ini, 0, ',', '.') }}</h2>
                                <p class="text-muted small mb-0">{{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('admin.report.keuangan') }}" class="btn btn-success">
                                    <i class="fas fa-chart-line me-2"></i>Lihat Laporan Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Pemesanan Pending -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Pemesanan Pending
                            </h5>
                            <span class="badge bg-warning">{{ count($pemesanan_pending) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($pemesanan_pending) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($pemesanan_pending as $pemesanan)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $pemesanan->user->name }}</h6>
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-route me-1"></i>
                                                    {{ $pemesanan->jadwal->rute->kota_asal }} → {{ $pemesanan->jadwal->rute->kota_tujuan }}
                                                </p>
                                                <p class="text-muted small mb-0">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->isoFormat('D MMM YYYY, HH:mm') }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <p class="fw-bold text-primary mb-1">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                                                <a href="{{ route('admin.pemesanan.show', $pemesanan->id_pemesanan) }}" class="btn btn-sm btn-outline-primary">
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">Tidak ada pemesanan pending</p>
                            </div>
                        @endif
                    </div>
                    @if(count($pemesanan_pending) > 0)
                        <div class="card-footer bg-white border-0 py-3 text-center">
                            <a href="{{ route('admin.pemesanan.index') }}?status=pending" class="text-decoration-none fw-medium">
                                Lihat Semua Pemesanan Pending
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Rute Populer -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-star text-warning me-2"></i>
                            Top 5 Rute Populer
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($top_rutes) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($top_rutes as $index => $rute)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <strong>{{ $index + 1 }}</strong>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1">{{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}</h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="fas fa-ticket-alt me-1"></i>
                                                    {{ $rute->pemesanan_count }} pemesanan
                                                </p>
                                            </div>
                                            <div>
                                                <span class="badge bg-success">Rp {{ number_format($rute->harga_base, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada data rute</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            Top 5 Pengguna Aktif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($top_users) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>No. HP</th>
                                            <th>Total Pemesanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_users as $index => $user)
                                            <tr>
                                                <td>
                                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <strong>{{ $index + 1 }}</strong>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->nomor_hp ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-success">{{ $user->pemesanans_count }} pemesanan</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada data pengguna</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-bolt text-primary me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('admin.jadwal.create') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-calendar-plus d-block mb-2" style="font-size: 2rem;"></i>
                                    Tambah Jadwal Baru
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.bus.create') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-bus d-block mb-2" style="font-size: 2rem;"></i>
                                    Tambah Bus Baru
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.rute.create') }}" class="btn btn-outline-warning w-100 py-3">
                                    <i class="fas fa-route d-block mb-2" style="font-size: 2rem;"></i>
                                    Tambah Rute Baru
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.report.penjualan') }}" class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-chart-bar d-block mb-2" style="font-size: 2rem;"></i>
                                    Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
