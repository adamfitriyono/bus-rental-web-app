<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Riwayat Pemesanan - Sistem Penjualan Tiket Bis Online" />
    <title>Riwayat Pemesanan - Tiket Bis Online</title>
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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .booking-card {
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .booking-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .filter-btn {
            border-radius: 20px;
            padding: 8px 20px;
            margin: 5px;
        }
        
        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-bus me-2"></i>Tiket Bis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-search me-1"></i>Cari Tiket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user.history') }}">
                            <i class="fas fa-history me-1"></i>Riwayat
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('account') }}">Profil Saya</a></li>
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Riwayat Pemesanan</h2>
                <p class="text-muted">Lihat semua pemesanan tiket Anda</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('user.history') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Dari Tanggal</label>
                            <input type="date" class="form-control" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                    @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai']))
                    <div class="mt-2">
                        <a href="{{ route('user.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Reset Filter
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Booking List -->
        <div class="row">
            @forelse($bookings as $booking)
            <div class="col-12 mb-3">
                <div class="booking-card card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Booking Info -->
                            <div class="col-lg-7">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="status-badge badge bg-{{ $booking->status_pemesanan == 'dikonfirmasi' ? 'success' : ($booking->status_pemesanan == 'pending' ? 'warning' : ($booking->status_pemesanan == 'selesai' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($booking->status_pemesanan) }}
                                        </span>
                                        @if($booking->pembayaran && $booking->pembayaran->status_pembayaran == 'Lunas')
                                        <span class="badge bg-success ms-2">
                                            <i class="fas fa-check me-1"></i>Lunas
                                        </span>
                                        @endif
                                    </div>
                                    <small class="text-muted">ID: #{{ $booking->id_pemesanan }}</small>
                                </div>
                                
                                <h5 class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    {{ $booking->jadwal->rute->kota_asal }}
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    {{ $booking->jadwal->rute->kota_tujuan }}
                                </h5>
                                
                                <div class="row text-muted small mb-2">
                                    <div class="col-md-6">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}
                                    </div>
                                    <div class="col-md-6">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ \Carbon\Carbon::parse($booking->jadwal->jam_berangkat)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($booking->jadwal->jam_tiba)->format('H:i') }}
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <i class="fas fa-bus me-2 text-muted"></i>
                                    <strong>{{ $booking->bus->nama_bus }}</strong>
                                    <span class="text-muted">({{ $booking->bus->jenis_kelas }})</span>
                                </div>
                                
                                <div>
                                    <span class="badge bg-secondary me-2">{{ $booking->jumlah_kursi }} Kursi</span>
                                    <span class="badge bg-info">Kursi: {{ $booking->nomor_kursi }}</span>
                                </div>
                            </div>
                            
                            <!-- Price & Actions -->
                            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Total Pembayaran</small>
                                    <h4 class="text-primary mb-0">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</h4>
                                    <small class="text-muted">
                                        Dipesan {{ \Carbon\Carbon::parse($booking->tanggal_pemesanan)->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('user.ticket.detail', $booking->id_pemesanan) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-eye me-2"></i>Lihat Detail
                                    </a>
                                    
                                    @if($booking->tiket && $booking->tiket->file_pdf)
                                    <a href="{{ asset('storage/' . $booking->tiket->file_pdf) }}" 
                                       class="btn btn-outline-success" 
                                       target="_blank">
                                        <i class="fas fa-download me-2"></i>Download E-Ticket
                                    </a>
                                    @endif
                                    
                                    @if($booking->status_pemesanan == 'pending' && $booking->pembayaran && $booking->pembayaran->status_pembayaran == 'Belum Lunas')
                                    <a href="{{ route('payment.upload', $booking->pembayaran->id_pembayaran) }}" 
                                       class="btn btn-outline-warning">
                                        <i class="fas fa-upload me-2"></i>Upload Bukti
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted mb-3">Belum ada riwayat pemesanan</h5>
                        <p class="text-muted mb-4">Mulai pesan tiket bis sekarang untuk perjalanan Anda!</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari Tiket
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
        @endif
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
