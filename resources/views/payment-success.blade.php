<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Pembayaran Selesai - Sistem Penjualan Tiket Bis Online" />
    <title>Pembayaran Selesai - Tiket Bis Online</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .success-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .success-card {
            text-align: center;
            max-width: 600px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .success-header {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            padding: 40px 30px;
        }
        
        .success-icon {
            font-size: 60px;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }
        
        .success-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .success-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .success-body {
            padding: 40px 30px;
        }
        
        .transaction-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .info-value {
            color: #212529;
            font-weight: 500;
        }
        
        .progress-step {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .progress-step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #dee2e6;
            z-index: 0;
        }
        
        .step {
            text-align: center;
            position: relative;
            flex: 1;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #dee2e6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }
        
        .step.completed .step-circle {
            background: #198754;
            border-color: #198754;
            color: white;
        }
        
        .step-label {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .booking-details {
            background-color: #e7f5f0;
            border-left: 4px solid #198754;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .route-info {
            margin-bottom: 15px;
        }
        
        .route-text {
            font-size: 18px;
            font-weight: 600;
            color: #198754;
            margin-bottom: 5px;
        }
        
        .route-details {
            font-size: 14px;
            color: #6c757d;
        }
        
        .passengers-list {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: left;
        }
        
        .passenger-item {
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .passenger-item:last-child {
            border-bottom: none;
        }
        
        .price-breakdown {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .price-row:last-child {
            border-bottom: none;
            border-top: 2px solid #198754;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: 700;
            color: #198754;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }
        
        .action-buttons a {
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 576px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .success-container {
                padding: 20px;
            }
            
            .success-card {
                margin: 20px 0;
            }
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
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
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Success Container -->
    <div class="success-container">
        <div class="success-card">
            <!-- Header -->
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="success-title">Pembayaran Berhasil!</h1>
                <p class="success-subtitle">Pesanan Anda telah dikonfirmasi</p>
            </div>

            <!-- Body -->
            <div class="success-body">
                <!-- Progress Steps -->
                <div class="progress-step mb-4">
                    <div class="step completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Pilih Kursi</div>
                    </div>
                    <div class="step completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Data Penumpang</div>
                    </div>
                    <div class="step completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Pembayaran</div>
                    </div>
                    <div class="step completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Selesai</div>
                    </div>
                </div>

                <!-- Transaction Info -->
                <div class="transaction-info">
                    <div class="info-row">
                        <span class="info-label">Kode Transaksi</span>
                        <span class="info-value fw-bold text-success">{{ $pembayaran->kode_transaksi }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Metode Pembayaran</span>
                        <span class="info-value">{{ ucwords(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Pembayaran</span>
                        <span class="info-value">
                            <span class="badge bg-success">{{ $pembayaran->status_pembayaran }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Waktu Transaksi</span>
                        <span class="info-value">{{ $pembayaran->tanggal_pembayaran->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="booking-details">
                    <div class="route-info">
                        <div class="route-text">
                            {{ $pemesanan->jadwal->rute->kota_asal }} 
                            <i class="fas fa-arrow-right mx-2"></i>
                            {{ $pemesanan->jadwal->rute->kota_tujuan }}
                        </div>
                        <div class="route-details">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }} | 
                            <i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_berangkat)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_tiba)->format('H:i') }}
                        </div>
                        <div class="route-details mt-2">
                            <i class="fas fa-bus me-1"></i>
                            {{ $pemesanan->jadwal->bus->nama_bus }}
                        </div>
                    </div>

                    <!-- Seats -->
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(0,0,0,0.1);">
                        <small style="color: #6c757d; font-weight: 600;">Nomor Kursi</small>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            @foreach(explode(',', $pemesanan->nomor_kursi) as $seat)
                            <span class="badge bg-primary">{{ trim($seat) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Passengers -->
                <div class="passengers-list">
                    <h6 class="mb-3">
                        <i class="fas fa-users me-2"></i>Penumpang ({{ $pemesanan->penumpangs->count() }})
                    </h6>
                    @forelse($pemesanan->penumpangs as $penumpang)
                    <div class="passenger-item">
                        <div style="font-weight: 600; color: #212529;">{{ $penumpang->nama_penumpang }}</div>
                        <small style="color: #6c757d;">
                            {{ ucfirst($penumpang->tipe_identitas) }}: {{ $penumpang->nomor_identitas }} | 
                            {{ $penumpang->nomor_hp }}
                        </small>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Tidak ada data penumpang</p>
                    @endforelse
                </div>

                <!-- Price Breakdown -->
                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Harga Tiket ({{ $pemesanan->jumlah_kursi }}x)</span>
                        <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-row">
                        <span>Biaya Admin</span>
                        <span>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
                    </div>
                    <div class="price-row">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="action-buttons">
                    <a href="{{ route('user.history') }}" class="btn btn-primary">
                        <i class="fas fa-history me-2"></i>Lihat Riwayat
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Beranda
                    </a>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info mt-4 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi Penting:</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        <li>Pesanan Anda sudah dikonfirmasi dan dapat dilihat di riwayat pemesanan</li>
                        <li>Tiket akan dikirim via email (jika tersedia)</li>
                        <li>Simpan kode transaksi Anda untuk keperluan referensi</li>
                    </ul>
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
