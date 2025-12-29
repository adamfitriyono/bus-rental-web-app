<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Ringkasan Pembayaran - Sistem Penjualan Tiket Bis Online" />
    <title>Pembayaran - Tiket Bis Online</title>
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
        }
        
        .step.completed .step-circle {
            background: #198754;
            border-color: #198754;
            color: white;
        }
        
        .step.active .step-circle {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .step-label {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .step.active .step-label {
            color: #0d6efd;
            font-weight: 600;
        }
        
        .payment-method-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #dee2e6;
        }
        
        .payment-method-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
            transform: translateY(-3px);
        }
        
        .payment-method-card.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        
        .payment-method-card input[type="radio"] {
            display: none;
        }
        
        .payment-icon {
            font-size: 2rem;
            color: #0d6efd;
        }
        
        .price-row {
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .price-row:last-child {
            border-bottom: none;
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

    <!-- Main Content -->
    <div class="container py-5">
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
            <div class="step active">
                <div class="step-circle">3</div>
                <div class="step-label">Pembayaran</div>
            </div>
            <div class="step">
                <div class="step-circle">4</div>
                <div class="step-label">Selesai</div>
            </div>
        </div>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Left Column - Payment Methods -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-credit-card me-2"></i>Pilih Metode Pembayaran
                        </h4>
                        
                        <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                            @csrf
                            
                            <!-- Transfer Bank -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-3">Transfer Bank</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="transfer_bank_bca" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">BCA</h6>
                                                <small class="text-muted">Transfer Manual</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="transfer_bank_mandiri" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">Mandiri</h6>
                                                <small class="text-muted">Transfer Manual</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="transfer_bank_bri" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">BRI</h6>
                                                <small class="text-muted">Transfer Manual</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="transfer_bank_bni" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">BNI</h6>
                                                <small class="text-muted">Transfer Manual</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- E-Wallet -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-3">E-Wallet</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="e_wallet_gopay" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">GoPay</h6>
                                                <small class="text-muted">Instan</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="e_wallet_ovo" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">OVO</h6>
                                                <small class="text-muted">Instan</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="e_wallet_dana" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">DANA</h6>
                                                <small class="text-muted">Instan</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Virtual Account -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-3">Virtual Account</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="virtual_account_bca" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-file-invoice"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">BCA Virtual Account</h6>
                                                <small class="text-muted">Otomatis</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="payment-method-card card h-100">
                                            <input type="radio" name="metode_pembayaran" value="virtual_account_mandiri" required>
                                            <div class="card-body text-center">
                                                <div class="payment-icon">
                                                    <i class="fas fa-file-invoice"></i>
                                                </div>
                                                <h6 class="mt-3 mb-0">Mandiri Virtual Account</h6>
                                                <small class="text-muted">Otomatis</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Credit Card -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Kartu Kredit/Debit</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="payment-method-card card">
                                            <input type="radio" name="metode_pembayaran" value="credit_card" required>
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="payment-icon">
                                                            <i class="fas fa-credit-card"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-0">Kartu Kredit/Debit</h6>
                                                        <small class="text-muted">Visa, Mastercard, JCB</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    Bayar Sekarang
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                        </h5>
                        
                        @php
                            $cart = session('booking_cart');
                        @endphp

                        @if($cart)
                        <!-- Journey Info -->
                        <div class="mb-3">
                            <h6 class="text-primary">
                                {{ $cart['jadwal']->rute->kota_asal }} 
                                <i class="fas fa-arrow-right mx-2"></i>
                                {{ $cart['jadwal']->rute->kota_tujuan }}
                            </h6>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-calendar me-2"></i>
                                {{ \Carbon\Carbon::parse($cart['jadwal']->tanggal)->format('d M Y') }}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-clock me-2"></i>
                                {{ \Carbon\Carbon::parse($cart['jadwal']->jam_berangkat)->format('H:i') }}
                            </p>
                        </div>
                        
                        <hr>
                        
                        <!-- Seats -->
                        <div class="mb-3">
                            <small class="text-muted">Kursi</small>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                @foreach($cart['nomor_kursi'] as $seat)
                                <span class="badge bg-primary">{{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Price Breakdown -->
                        <div class="price-row">
                            <div class="d-flex justify-content-between">
                                <span>Harga Tiket ({{ $cart['jumlah_kursi'] }}x)</span>
                                <span>Rp {{ number_format($cart['total_harga'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="price-row">
                            <div class="d-flex justify-content-between">
                                <span>Biaya Admin</span>
                                <span>Rp 0</span>
                            </div>
                        </div>
                        
                        <div class="price-row pt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="fs-5">Total</strong>
                                <strong class="text-primary fs-4">Rp {{ number_format($cart['total_harga'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @else
                        <p class="text-muted text-center">Data pemesanan tidak ditemukan</p>
                        @endif
                        
                        <!-- Payment Info -->
                        <div class="alert alert-info small mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong>
                            <ul class="mb-0 mt-2 ps-3">
                                <li>Pilih metode pembayaran untuk melanjutkan</li>
                                <li>Transfer Bank: Upload bukti transfer setelah pembayaran</li>
                                <li>E-Wallet & VA: Pembayaran otomatis terverifikasi</li>
                            </ul>
                        </div>
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
    
    <!-- Custom JS -->
    <script>
        // Payment method selection
        const paymentCards = document.querySelectorAll('.payment-method-card');
        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                paymentCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // Form submission
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
            if (!selectedMethod) {
                e.preventDefault();
                alert('Pilih metode pembayaran terlebih dahulu');
                return false;
            }
            
            // Show loading
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        });
    </script>
</body>
</html>
