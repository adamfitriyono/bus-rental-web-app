<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Detail Tiket - Sistem Penjualan Tiket Bis Online" />
    <title>Detail Tiket #{{ $pemesanan->id_pemesanan }} - Tiket Bis Online</title>
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
        
        .ticket-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
        }
        
        .route-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0;
        }
        
        .route-dot {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: white;
        }
        
        .route-line {
            flex: 1;
            height: 2px;
            background: white;
            margin: 0 10px;
            position: relative;
        }
        
        .route-line::after {
            content: '';
            position: absolute;
            right: 0;
            top: -4px;
            width: 0;
            height: 0;
            border-left: 10px solid white;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
        }
        
        .info-row {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
            }
            
            .ticket-card {
                box-shadow: none;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top no-print">
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
                        <a class="nav-link" href="{{ route('user.history') }}">
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Ticket Card -->
                <div class="ticket-card card border-0 mb-4">
                    <!-- Ticket Header -->
                    <div class="ticket-header">
                        <div class="text-center mb-3">
                            <h4 class="mb-1">E-TICKET BUS</h4>
                            @if($pemesanan->tiket)
                            <h5 class="mb-0">{{ $pemesanan->tiket->kode_tiket }}</h5>
                            @endif
                        </div>
                        
                        <div class="route-display">
                            <div class="text-center">
                                <div class="route-dot"></div>
                                <h3 class="mt-2 mb-0">{{ $pemesanan->jadwal->rute->kota_asal }}</h3>
                                <small>{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_berangkat)->format('H:i') }}</small>
                            </div>
                            <div class="route-line"></div>
                            <div class="text-center">
                                <div class="route-dot ms-auto"></div>
                                <h3 class="mt-2 mb-0">{{ $pemesanan->jadwal->rute->kota_tujuan }}</h3>
                                <small>{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_tiba)->format('H:i') }}</small>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <span class="badge bg-light text-dark px-4 py-2">
                                <i class="fas fa-calendar me-2"></i>
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Ticket Body -->
                    <div class="card-body p-4">
                        <!-- Status -->
                        <div class="text-center mb-4">
                            <span class="badge bg-{{ $pemesanan->status_pemesanan == 'dikonfirmasi' ? 'success' : ($pemesanan->status_pemesanan == 'pending' ? 'warning' : ($pemesanan->status_pemesanan == 'selesai' ? 'info' : 'danger')) }} px-4 py-2">
                                {{ strtoupper($pemesanan->status_pemesanan) }}
                            </span>
                            @if($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran == 'Lunas')
                            <span class="badge bg-success px-4 py-2 ms-2">
                                <i class="fas fa-check-circle me-1"></i>LUNAS
                            </span>
                            @endif
                        </div>

                        <!-- Booking Info -->
                        <div class="info-row">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">ID Pemesanan</small>
                                    <p class="mb-0 fw-bold">#{{ $pemesanan->id_pemesanan }}</p>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Tanggal Pesan</small>
                                    <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bus Info -->
                        <div class="info-row">
                            <small class="text-muted d-block mb-2">Informasi Bus</small>
                            <div class="row">
                                <div class="col-6">
                                    <i class="fas fa-bus me-2 text-primary"></i>
                                    <strong>{{ $pemesanan->bus->nama_bus }}</strong>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-info">{{ $pemesanan->bus->jenis_kelas }}</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <i class="fas fa-id-card me-2 text-muted"></i>
                                <span>{{ $pemesanan->bus->plat_nomor }}</span>
                            </div>
                        </div>

                        <!-- Seat Info -->
                        <div class="info-row">
                            <small class="text-muted d-block mb-2">Informasi Kursi</small>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <i class="fas fa-couch me-2 text-primary"></i>
                                    <strong>{{ $pemesanan->jumlah_kursi }} Kursi</strong>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-secondary px-3 py-2">Nomor: {{ $pemesanan->nomor_kursi }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Passenger Info -->
                        <div class="info-row">
                            <small class="text-muted d-block mb-2">Informasi Penumpang ({{ $pemesanan->penumpangs->count() }})</small>
                            @forelse($pemesanan->penumpangs as $index => $penumpang)
                            <div class="d-flex justify-content-between align-items-center {{ $loop->last ? '' : 'mb-2' }}">
                                <div>
                                    <i class="fas fa-user me-2 text-muted"></i>
                                    <strong>{{ $penumpang->nama_penumpang }}</strong>
                                </div>
                                <span class="badge bg-light text-dark">{{ strtoupper($penumpang->tipe_identitas) }}</span>
                            </div>
                            @empty
                            <p class="text-muted mb-0"><i>Belum ada data penumpang</i></p>
                            @endforelse
                        </div>

                        <!-- Payment Info -->
                        <div class="info-row">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Total Pembayaran</small>
                                    <h4 class="text-primary mb-0">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h4>
                                </div>
                                <div class="col-6 text-end">
                                    @if($pemesanan->pembayaran)
                                    <small class="text-muted d-block">Metode Pembayaran</small>
                                    <p class="mb-0">{{ str_replace('_', ' ', ucwords($pemesanan->pembayaran->metode_pembayaran)) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        @if($pemesanan->tiket && $pemesanan->tiket->qr_code)
                        <div class="text-center mt-4">
                            <div class="qr-container d-inline-block">
                                <img src="{{ asset('storage/' . $pemesanan->tiket->qr_code) }}" 
                                     alt="QR Code" 
                                     class="img-fluid" 
                                     style="max-width: 200px;">
                                <p class="small text-muted mt-2 mb-0">Scan QR untuk verifikasi</p>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 mt-4 no-print">
                            @if($pemesanan->tiket && $pemesanan->tiket->file_pdf)
                            <a href="{{ asset('storage/' . $pemesanan->tiket->file_pdf) }}" 
                               class="btn btn-success btn-lg" 
                               target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Download PDF E-Ticket
                            </a>
                            @endif
                            
                            @if($pemesanan->status_pemesanan == 'pending' && $pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran == 'Belum Lunas')
                            <a href="{{ route('payment.upload', $pemesanan->pembayaran->id_pembayaran) }}" 
                               class="btn btn-warning btn-lg">
                                <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                            </a>
                            @endif
                            
                            <button onclick="window.print()" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-print me-2"></i>Cetak Tiket
                            </button>
                            
                            <a href="{{ route('user.history') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="card border-0 shadow-sm no-print">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-info-circle text-primary me-2"></i>Informasi Penting
                        </h6>
                        <ul class="small mb-0">
                            <li>Harap datang 30 menit sebelum waktu keberangkatan</li>
                            <li>Tunjukkan e-ticket ini (QR Code) saat akan naik bus</li>
                            <li>Pastikan membawa identitas yang sesuai dengan data penumpang</li>
                            <li>E-ticket ini tidak dapat dipindahtangankan</li>
                            <li>Untuk pembatalan tiket, hubungi customer service kami</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <button onclick="window.print()" class="btn btn-primary print-btn no-print" title="Cetak Tiket">
        <i class="fas fa-print"></i>
    </button>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5 no-print">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Sistem Penjualan Tiket Bis Online. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
