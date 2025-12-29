<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Upload Bukti Transfer - Sistem Penjualan Tiket Bis Online" />
    <title>Upload Bukti Transfer - Tiket Bis Online</title>
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
        
        .upload-area {
            border: 3px dashed #0d6efd;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .upload-area:hover {
            background: #e7f1ff;
            border-color: #0a58ca;
        }
        
        .upload-area.dragover {
            background: #cfe2ff;
            border-color: #0a58ca;
        }
        
        .upload-icon {
            font-size: 4rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        
        .preview-image {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .bank-info-card {
            border-left: 4px solid #0d6efd;
        }
        
        .account-number {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #0d6efd;
            cursor: pointer;
            user-select: all;
        }
        
        .copy-btn {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .copy-btn:hover {
            color: #0d6efd;
            transform: scale(1.2);
        }
        
        .countdown {
            font-size: 2rem;
            font-weight: bold;
            color: #dc3545;
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
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Left Column - Upload -->
            <div class="col-lg-8 mb-4">
                <!-- Payment Instructions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Instruksi Pembayaran
                        </h4>
                        
                        <!-- Payment Deadline -->
                        <div class="alert alert-warning">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-2"><i class="fas fa-clock me-2"></i>Selesaikan Pembayaran Sebelum:</h6>
                                    <p class="mb-0">{{ \Carbon\Carbon::now()->addHours(24)->format('d M Y, H:i') }} WIB</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted d-block">Sisa Waktu</small>
                                    <div class="countdown" id="countdown">23:59:59</div>
                                </div>
                            </div>
                        </div>

                        @php
                            $pembayaran = \App\Models\Pembayaran::find($id);
                            $metodePembayaran = $pembayaran->metode_pembayaran ?? 'transfer_bank_bca';
                            
                            // Determine bank details based on payment method
                            $bankDetails = [
                                'transfer_bank_bca' => ['name' => 'BCA', 'account' => '1234567890', 'holder' => 'PT Tiket Bis Online'],
                                'transfer_bank_mandiri' => ['name' => 'Mandiri', 'account' => '1122334455', 'holder' => 'PT Tiket Bis Online'],
                                'transfer_bank_bri' => ['name' => 'BRI', 'account' => '0099887766', 'holder' => 'PT Tiket Bis Online'],
                                'transfer_bank_bni' => ['name' => 'BNI', 'account' => '5566778899', 'holder' => 'PT Tiket Bis Online'],
                            ];
                            
                            $bank = $bankDetails[$metodePembayaran] ?? $bankDetails['transfer_bank_bca'];
                        @endphp

                        <!-- Bank Account Info -->
                        <div class="bank-info-card card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Transfer ke Rekening Berikut:</h5>
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-4">
                                        <h3 class="text-primary mb-0">{{ $bank['name'] }}</h3>
                                    </div>
                                    <div class="col-md-8">
                                        <small class="text-muted d-block">Nomor Rekening</small>
                                        <div class="d-flex align-items-center">
                                            <span class="account-number me-3" id="accountNumber">{{ $bank['account'] }}</span>
                                            <i class="fas fa-copy copy-btn" onclick="copyAccount()" title="Copy"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Atas Nama</small>
                                    </div>
                                    <div class="col-md-8">
                                        <strong>{{ $bank['holder'] }}</strong>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Total Transfer</small>
                                    </div>
                                    <div class="col-md-8">
                                        @if($pembayaran)
                                        <h4 class="text-primary mb-0">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</h4>
                                        @else
                                        <h4 class="text-primary mb-0">Rp 0</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Steps -->
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="mb-3">Langkah-langkah:</h6>
                                <ol class="mb-0">
                                    <li class="mb-2">Lakukan transfer sesuai nominal yang tertera</li>
                                    <li class="mb-2">Simpan bukti transfer Anda</li>
                                    <li class="mb-2">Upload bukti transfer di form di bawah</li>
                                    <li class="mb-2">Tunggu verifikasi (maksimal 1x24 jam)</li>
                                    <li>E-ticket akan dikirim ke email Anda setelah pembayaran terverifikasi</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-upload me-2"></i>Upload Bukti Transfer
                        </h4>
                        
                        <form action="{{ route('payment.upload.bukti', $id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            
                            <!-- Upload Area -->
                            <div class="upload-area mb-4" id="uploadArea">
                                <input type="file" 
                                       name="bukti_transfer" 
                                       id="fileInput" 
                                       accept="image/*" 
                                       class="d-none" 
                                       required>
                                <div id="uploadPlaceholder">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <h5 class="mb-2">Klik atau Drop File di Sini</h5>
                                    <p class="text-muted mb-0">Format: JPG, PNG (Max. 5MB)</p>
                                </div>
                                <div id="imagePreview" class="d-none">
                                    <img src="" alt="Preview" class="preview-image" id="previewImg">
                                    <button type="button" class="btn btn-sm btn-danger mt-3" onclick="removeImage()">
                                        <i class="fas fa-times me-2"></i>Hapus
                                    </button>
                                </div>
                            </div>

                            @error('bukti_transfer')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <!-- Additional Info -->
                            <div class="mb-4">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" name="catatan" rows="3" placeholder="Tulis catatan jika diperlukan..."></textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-home me-2"></i>Ke Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-check me-2"></i>Kirim Bukti Transfer
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
                            <i class="fas fa-receipt me-2"></i>Detail Pemesanan
                        </h5>
                        
                        @if($pembayaran && $pembayaran->pemesanan)
                        @php
                            $pemesanan = $pembayaran->pemesanan;
                        @endphp
                        
                        <div class="mb-3">
                            <small class="text-muted">Kode Transaksi</small>
                            <p class="mb-0"><code>{{ $pembayaran->kode_transaksi }}</code></p>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <h6 class="text-primary">
                                {{ $pemesanan->jadwal->rute->kota_asal }} 
                                <i class="fas fa-arrow-right mx-2"></i>
                                {{ $pemesanan->jadwal->rute->kota_tujuan }}
                            </h6>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-calendar me-2"></i>
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-clock me-2"></i>
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_berangkat)->format('H:i') }}
                            </p>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <small class="text-muted">Bus</small>
                            <p class="mb-0">{{ $pemesanan->bus->nama_bus }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Kursi</small>
                            <div class="d-flex gap-2 flex-wrap mt-1">
                                @foreach(explode(',', $pemesanan->nomor_kursi) as $seat)
                                <span class="badge bg-primary">{{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jumlah Kursi</span>
                            <span>{{ $pemesanan->jumlah_kursi }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <strong>Total</strong>
                            <strong class="text-primary fs-5">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</strong>
                        </div>
                        @else
                        <p class="text-muted text-center">Data pemesanan tidak ditemukan</p>
                        @endif
                        
                        <div class="alert alert-info small mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Bukti transfer akan diverifikasi dalam 1x24 jam. Anda akan menerima notifikasi via email.
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
        // Upload area interactions
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        // Click to upload
        uploadArea.addEventListener('click', function(e) {
            if (e.target.id !== 'fileInput' && !e.target.closest('button')) {
                fileInput.click();
            }
        });

        // File selected
        fileInput.addEventListener('change', function(e) {
            handleFile(e.target.files[0]);
        });

        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                fileInput.files = e.dataTransfer.files;
                handleFile(file);
            } else {
                alert('File harus berupa gambar');
            }
        });

        // Handle file
        function handleFile(file) {
            if (!file) return;
            
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploadPlaceholder.classList.add('d-none');
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }

        // Remove image
        function removeImage() {
            fileInput.value = '';
            uploadPlaceholder.classList.remove('d-none');
            imagePreview.classList.add('d-none');
            previewImg.src = '';
        }

        // Copy account number
        function copyAccount() {
            const accountNumber = document.getElementById('accountNumber').textContent;
            navigator.clipboard.writeText(accountNumber).then(function() {
                alert('Nomor rekening berhasil disalin!');
            });
        }

        // Countdown timer (24 hours)
        function startCountdown() {
            const countdownElement = document.getElementById('countdown');
            const endTime = new Date().getTime() + (24 * 60 * 60 * 1000);
            
            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    clearInterval(timer);
                    countdownElement.innerHTML = "EXPIRED";
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdownElement.innerHTML = 
                    String(hours).padStart(2, '0') + ":" + 
                    String(minutes).padStart(2, '0') + ":" + 
                    String(seconds).padStart(2, '0');
            }, 1000);
        }
        
        startCountdown();

        // Form submission
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Pilih file bukti transfer terlebih dahulu');
                return false;
            }
            
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
        });
    </script>
</body>
</html>
