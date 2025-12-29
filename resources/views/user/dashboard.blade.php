<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Dashboard - Sistem Penjualan Tiket Bis Online" />
    <title>Dashboard - Tiket Bis Online</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .stat-card {
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .trip-card {
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .trip-card:hover {
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-dot {
            position: absolute;
            left: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--primary-color);
        }
        
        .timeline-dot.success {
            background: var(--success-color);
            box-shadow: 0 0 0 2px var(--success-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-bus me-2"></i>Tiket Bis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-search me-1"></i>Cari Tiket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.history') }}">
                            <i class="fas fa-history me-1"></i>Riwayat
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('akun.pengaturan') }}">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-muted">Kelola pemesanan tiket bis Anda dengan mudah</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Tiket</h6>
                            <h3 class="mb-0">{{ $totalTiket }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Menunggu</h6>
                            <h3 class="mb-0">{{ $pendingBookings }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Dikonfirmasi</h6>
                            <h3 class="mb-0">{{ $confirmedBookings }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Mendatang</h6>
                            <h3 class="mb-0">{{ $upcomingTrips }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Upcoming Trips -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Perjalanan Mendatang
                            </h5>
                            <a href="{{ route('user.history') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($upcomingBookings as $booking)
                        <div class="trip-card card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-{{ $booking->status_pemesanan == 'dikonfirmasi' ? 'success' : 'warning' }} me-2">
                                                {{ ucfirst($booking->status_pemesanan) }}
                                            </span>
                                            <small class="text-muted">ID: #{{ $booking->id_pemesanan }}</small>
                                        </div>
                                        <h6 class="mb-2">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            {{ $booking->jadwal->rute->kota_asal }}
                                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                            {{ $booking->jadwal->rute->kota_tujuan }}
                                        </h6>
                                        <div class="text-muted small">
                                            <i class="fas fa-calendar me-2"></i>
                                            {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-clock me-2"></i>
                                            {{ \Carbon\Carbon::parse($booking->jadwal->jam_berangkat)->format('H:i') }}
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge bg-secondary">{{ $booking->jumlah_kursi }} Kursi</span>
                                            <span class="badge bg-info">{{ $booking->nomor_kursi }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                        <h5 class="text-primary mb-2">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </h5>
                                        <a href="{{ route('user.ticket.detail', $booking->id_pemesanan) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada perjalanan mendatang</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cari Tiket Sekarang
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cari Tiket
                            </a>
                            <a href="{{ route('user.history') }}" class="btn btn-outline-primary">
                                <i class="fas fa-history me-2"></i>Riwayat Pemesanan
                            </a>
                            <a href="{{ route('akun.pengaturan') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-user-edit me-2"></i>Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-history text-info me-2"></i>Aktivitas Terakhir
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($recentActivities as $activity)
                        <div class="timeline mb-3">
                            <div class="timeline-dot {{ $activity->status_pemesanan == 'dikonfirmasi' ? 'success' : '' }}"></div>
                            <div class="mb-1">
                                <strong>{{ $activity->jadwal->rute->kota_asal }} → {{ $activity->jadwal->rute->kota_tujuan }}</strong>
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($activity->tanggal_pemesanan)->diffForHumans() }}
                            </small>
                            <div class="mt-1">
                                <span class="badge bg-{{ $activity->status_pemesanan == 'dikonfirmasi' ? 'success' : 'warning' }} small">
                                    {{ ucfirst($activity->status_pemesanan) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center mb-0">Belum ada aktivitas</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Sistem Penjualan Tiket Bis Online. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
