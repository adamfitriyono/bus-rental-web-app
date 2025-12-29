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
                    <i class="far fa-calendar-alt me-1"></i> {{ date('l, d F Y') }}
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
                                <i class="fas fa-clipboard-list text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Reservasi</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_penyewaan }}</h2>
                        <p class="text-muted small mb-0">Reservasi aktif dan selesai</p>
                        <div class="position-absolute top-0 end-0 mt-2 me-2">
                            <span class="badge bg-success rounded-pill">
                                <i class="fas fa-arrow-up me-1"></i>5%
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('penyewaan.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-medium">Kelola Reservasi</span>
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
                                <i class="fas fa-users text-success"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Penyewa</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_user }}</h2>
                        <p class="text-muted small mb-0">Pengguna terdaftar</p>
                        <div class="position-absolute top-0 end-0 mt-2 me-2">
                            <span class="badge bg-success rounded-pill">
                                <i class="fas fa-arrow-up me-1"></i>12%
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('admin.user') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-success fw-medium">Kelola Penyewa</span>
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
                                <i class="fas fa-camera text-info"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Alat</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_alat }}</h2>
                        <p class="text-muted small mb-0">Peralatan tersedia</p>
                        <div class="position-absolute top-0 end-0 mt-2 me-2">
                            <span class="badge bg-info rounded-pill">
                                <i class="fas fa-plus me-1"></i>3
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('alat.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-info fw-medium">Kelola Alat</span>
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
                                <i class="fas fa-tags text-warning"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Total Kategori</h6>
                        </div>
                        <h2 class="fw-bold mb-0">{{ $total_kategori }}</h2>
                        <p class="text-muted small mb-0">Kategori peralatan</p>
                        <div class="position-absolute top-0 end-0 mt-2 me-2">
                            <span class="badge bg-warning rounded-pill">
                                <i class="fas fa-equals me-1"></i>0
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="{{ route('kategori.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                            <span class="text-warning fw-medium">Kelola Kategori</span>
                            <i class="fas fa-arrow-right text-warning"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Calendar Section -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            <h5 class="fw-bold mb-0">Kalender Reservasi</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('partials.kalender')
                    </div>
                </div>
            </div>
            
            <!-- Statistics Section -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            <h5 class="fw-bold mb-0">Statistik</h5>
                        </div>
                    </div>
                    
                    <!-- Top Renters -->
                    <div class="card-body border-bottom pb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-trophy text-warning me-2"></i>5 Penyewa Terbanyak
                            </h6>
                            <a href="#" class="text-decoration-none small">Lihat Semua</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" width="40">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Telepon</th>
                                        <th scope="col" class="text-end">Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_user as $user)
                                    <tr>
                                        <td>
                                            <div class="avatar-circle bg-primary text-white">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $user->name }}</div>
                                        </td>
                                        <td>{{ $user->telepon }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                                {{ $user->payment_count }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Top Equipment -->
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-star text-warning me-2"></i>5 Alat Terfavorit
                            </h6>
                            <a href="#" class="text-decoration-none small">Lihat Semua</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" width="40">#</th>
                                        <th scope="col">Alat</th>
                                        <th scope="col" class="text-end">Reservasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_products as $product)
                                    <tr>
                                        <td>
                                            <div class="avatar-circle bg-info text-white">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $product->nama_alat }}</div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-info rounded-pill px-3 py-2">
                                                {{ $product->order_count }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Reservations -->
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                <h5 class="fw-bold mb-0">Reservasi Terbaru</h5>
                            </div>
                            <a href="{{ route('penyewaan.index') }}" class="btn btn-sm btn-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Invoice</th>
                                        <th>Penyewa</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dummy data for recent reservations -->
                                    <tr>
                                        <td><span class="fw-medium">INV-2023001</span></td>
                                        <td>Ahmad Fauzi</td>
                                        <td>{{ date('d M Y') }}</td>
                                        <td>Rp 350.000</td>
                                        <td>
                                            <span class="badge bg-warning">Sedang Ditinjau</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-medium">INV-2023002</span></td>
                                        <td>Budi Santoso</td>
                                        <td>{{ date('d M Y', strtotime('-1 day')) }}</td>
                                        <td>Rp 275.000</td>
                                        <td>
                                            <span class="badge bg-info">Belum Bayar</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-medium">INV-2023003</span></td>
                                        <td>Citra Dewi</td>
                                        <td>{{ date('d M Y', strtotime('-2 day')) }}</td>
                                        <td>Rp 420.000</td>
                                        <td>
                                            <span class="badge bg-success">Sudah Bayar</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Custom styles for dashboard */
    .avatar-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .card {
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-3px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .table-responsive {
            border: 0;
        }
    }
</style>
@endsection