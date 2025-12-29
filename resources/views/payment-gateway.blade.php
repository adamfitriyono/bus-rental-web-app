<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Pembayaran Gateway - Sistem Penjualan Tiket Bis Online" />
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .payment-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 5rem;
            animation: scaleIn 0.5s ease-out;
        }
        
        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .waiting-icon {
            font-size: 5rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .payment-body {
            padding: 40px;
        }
        
        .info-row {
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .timeline-item {
            padding-left: 40px;
            position: relative;
            padding-bottom: 20px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 10px;
            top: 30px;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-item:last-child:before {
            display: none;
        }
        
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
        
        .timeline-icon.pending {
            background: #ffc107;
        }
        
        .qr-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container payment-container">
        <div class="payment-card">
            @php
                $pembayaran = \App\Models\Pembayaran::findOrFail($id);
                $pemesanan = $pembayaran->pemesanan;
                $isTransferBank = str_contains($pembayaran->metode_pembayaran, 'transfer_bank');
                $isSuccess = $pembayaran->status_pembayaran === 'Lunas';
            @endphp

            @if($isSuccess)
            <!-- Success State -->
            <div class="payment-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="mt-4 mb-2">Pembayaran Berhasil!</h2>
                <p class="mb-0">Terima kasih atas pembayaran Anda</p>
            </div>

            <div class="payment-body">
                <!-- Booking Details -->
                <div class="text-center mb-4">
                    <h5 class="text-muted mb-1">Kode Transaksi</h5>
                    <h3 class="text-primary"><code>{{ $pembayaran->kode_transaksi }}</code></h3>
                </div>

                <hr>

                <!-- Journey Info -->
                <div class="info-row">
                    <div class="row">
                        <div class="col-4">
                            <small class="text-muted">Rute</small>
                        </div>
                        <div class="col-8">
                            <strong>
                                {{ $pemesanan->jadwal->rute->kota_asal }} 
                                <i class="fas fa-arrow-right text-primary mx-2"></i>
                                {{ $pemesanan->jadwal->rute->kota_tujuan }}
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-4">
                            <small class="text-muted">Tanggal & Waktu</small>
                        </div>
                        <div class="col-8">
                            <strong>
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }}, 
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_berangkat)->format('H:i') }} WIB
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-4">
                            <small class="text-muted">Bus</small>
                        </div>
                        <div class="col-8">
                            <strong>{{ $pemesanan->bus->nama_bus }}</strong>
                            <span class="badge bg-info ms-2">{{ $pemesanan->bus->jenis_kelas }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-4">
                            <small class="text-muted">Kursi</small>
                        </div>
                        <div class="col-8">
                            @foreach(explode(',', $pemesanan->nomor_kursi) as $seat)
                            <span class="badge bg-primary me-1">{{ $seat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-4">
                            <small class="text-muted">Total Bayar</small>
                        </div>
                        <div class="col-8">
                            <h4 class="text-success mb-0">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- QR Code (if available) -->
                @if($pemesanan->tiket && $pemesanan->tiket->qr_code)
                <div class="text-center my-4">
                    <p class="text-muted mb-3">Scan QR Code ini saat boarding</p>
                    <div class="qr-container">
                        <img src="{{ asset('storage/' . $pemesanan->tiket->qr_code) }}" 
                             alt="QR Code" 
                             style="max-width: 200px;">
                    </div>
                </div>
                @endif

                <!-- Next Steps -->
                <div class="alert alert-success">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Langkah Selanjutnya:</h6>
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <strong>E-Ticket Terkirim</strong>
                        <p class="text-muted small mb-0">E-ticket telah dikirim ke email Anda</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <strong>Datang ke Terminal</strong>
                        <p class="text-muted small mb-0">Datang 30 menit sebelum keberangkatan</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon pending">
                            <i class="fas fa-bus"></i>
                        </div>
                        <strong>Boarding</strong>
                        <p class="text-muted small mb-0">Tunjukkan e-ticket atau scan QR code</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                    @if($pemesanan->tiket && $pemesanan->tiket->file_pdf)
                    <a href="{{ asset('storage/' . $pemesanan->tiket->file_pdf) }}" 
                       class="btn btn-primary btn-action" 
                       target="_blank">
                        <i class="fas fa-download me-2"></i>Download E-Ticket (PDF)
                    </a>
                    @endif
                    <a href="{{ route('user.ticket', $pemesanan->id_pemesanan) }}" 
                       class="btn btn-outline-primary btn-action">
                        <i class="fas fa-ticket-alt me-2"></i>Lihat Detail Tiket
                    </a>
                    <a href="{{ route('user.dashboard') }}" 
                       class="btn btn-outline-secondary btn-action">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>

            @else
            <!-- Waiting/Processing State -->
            <div class="payment-header">
                <div class="waiting-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h2 class="mt-4 mb-2">Menunggu Pembayaran</h2>
                <p class="mb-0">Silakan selesaikan pembayaran Anda</p>
            </div>

            <div class="payment-body">
                <!-- Booking Details -->
                <div class="text-center mb-4">
                    <h5 class="text-muted mb-1">Kode Transaksi</h5>
                    <h3 class="text-primary"><code>{{ $pembayaran->kode_transaksi }}</code></h3>
                </div>

                <hr>

                @if($isTransferBank)
                <!-- Bank Transfer Instructions -->
                <div class="alert alert-warning">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Instruksi Transfer Bank</h6>
                    <ol class="mb-0 ps-3">
                        <li class="mb-2">Transfer sejumlah <strong>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</strong></li>
                        <li class="mb-2">Ke rekening yang telah disediakan</li>
                        <li class="mb-2">Upload bukti transfer</li>
                        <li>Tunggu verifikasi (maks. 1x24 jam)</li>
                    </ol>
                </div>

                @if($pembayaran->status_pembayaran === 'Pending')
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('payment.upload', $pembayaran->id_pembayaran) }}" 
                       class="btn btn-primary btn-action">
                        <i class="fas fa-upload me-2"></i>Upload Bukti Transfer
                    </a>
                    <a href="{{ route('user.dashboard') }}" 
                       class="btn btn-outline-secondary btn-action">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
                @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-check-circle me-2"></i>
                    Bukti transfer telah diupload. Menunggu verifikasi admin.
                </div>
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('user.dashboard') }}" 
                       class="btn btn-primary btn-action">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
                @endif

                @else
                <!-- E-Wallet / Virtual Account / Credit Card -->
                <div class="alert alert-info">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Gateway Pembayaran</h6>
                    <p class="mb-2">Metode: <strong>{{ ucwords(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}</strong></p>
                    <p class="mb-2">Total: <strong class="text-primary fs-5">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</strong></p>
                </div>

                <!-- Midtrans Snap Payment Integration -->
                @if(isset($snapToken))
                <div class="card bg-light border-0 text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-credit-card text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="mb-3">Klik tombol di bawah untuk melanjutkan pembayaran</h5>
                    <button class="btn btn-primary btn-lg btn-action" id="pay-button">
                        <i class="fas fa-lock me-2"></i>Bayar Sekarang
                    </button>
                    <small class="text-muted mt-3 d-block">Secured by Midtrans</small>
                </div>
                @else
                <!-- Placeholder for Payment Gateway -->
                <div class="card bg-light border-0 text-center p-5">
                    <div class="mb-3">
                        <i class="fas fa-credit-card text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted">Integrasi Payment Gateway</h5>
                    <p class="text-muted mb-3">Midtrans Snap sedang dimuat...</p>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('user.dashboard') }}" 
                       class="btn btn-outline-secondary btn-action">
                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                    </a>
                </div>

                <div class="alert alert-info mt-3 small">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Pembayaran Aman:</strong> Transaksi Anda dilindungi dengan enkripsi SSL dan diproses melalui gateway pembayaran terpercaya.
                </div>
                @endif

                <hr>

                <!-- Journey Summary -->
                <div class="mt-4">
                    <h6 class="mb-3">Detail Perjalanan</h6>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4">
                                <small class="text-muted">Rute</small>
                            </div>
                            <div class="col-8">
                                <strong>
                                    {{ $pemesanan->jadwal->rute->kota_asal }} 
                                    <i class="fas fa-arrow-right text-primary mx-2"></i>
                                    {{ $pemesanan->jadwal->rute->kota_tujuan }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row">
                            <div class="col-4">
                                <small class="text-muted">Tanggal</small>
                            </div>
                            <div class="col-8">
                                <strong>{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row">
                            <div class="col-4">
                                <small class="text-muted">Kursi</small>
                            </div>
                            <div class="col-8">
                                @foreach(explode(',', $pemesanan->nomor_kursi) as $seat)
                                <span class="badge bg-primary me-1">{{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Back to Home Link -->
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-white text-decoration-none">
                <i class="fas fa-home me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Midtrans Snap JS -->
    @if(isset($snapToken))
    <script type="text/javascript" 
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
    
    <!-- Custom JS -->
    <script>
        @if(isset($snapToken))
        // Midtrans Snap Payment
        document.getElementById('pay-button').addEventListener('click', function(e) {
            e.preventDefault();
            
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    console.log('Payment success:', result);
                    window.location.href = '{{ route("user.dashboard") }}?payment=success';
                },
                onPending: function(result){
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route("user.dashboard") }}?payment=pending';
                },
                onError: function(result){
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function(){
                    console.log('Payment popup closed');
                }
            });
        });
        @endif

        // Auto-refresh for payment status (for bank transfer)
        @if(!$isSuccess && $isTransferBank && $pembayaran->status_pembayaran !== 'Pending')
        let checkPaymentStatus = setInterval(function() {
            // Check payment status via AJAX
            fetch('{{ route("payment.check.status", $pembayaran->id_pembayaran) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.status === 'Lunas') {
                        clearInterval(checkPaymentStatus);
                        location.reload();
                    }
                })
                .catch(error => console.error('Error checking payment status:', error));
        }, 10000); // Check every 10 seconds
        @endif
    </script>
</body>
</html>
