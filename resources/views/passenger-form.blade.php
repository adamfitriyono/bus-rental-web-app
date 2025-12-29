<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Data Penumpang - Sistem Penjualan Tiket Bis Online" />
    <title>Data Penumpang - Tiket Bis Online</title>
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
        
        .passenger-card {
            border-left: 4px solid #0d6efd;
        }
        
        .form-floating > label {
            padding: 1rem 0.75rem;
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
            <div class="step active">
                <div class="step-circle">2</div>
                <div class="step-label">Data Penumpang</div>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <div class="step-label">Pembayaran</div>
            </div>
            <div class="step">
                <div class="step-circle">4</div>
                <div class="step-label">Selesai</div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Form -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">
                            <i class="fas fa-users me-2"></i>Data Penumpang
                        </h4>
                        
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('passenger.store') }}" method="POST" id="passengerForm">
                            @csrf
                            
                            @for($i = 0; $i < $jumlahKursi; $i++)
                            <div class="passenger-card card mb-4">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user me-2"></i>
                                        Penumpang {{ $i + 1 }} 
                                        <span class="badge bg-primary ms-2">Kursi {{ $selectedSeats[$i] }}</span>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Nama Lengkap -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" 
                                                       class="form-control @error('penumpang.'.$i.'.nama_penumpang') is-invalid @enderror" 
                                                       id="nama_{{ $i }}" 
                                                       name="penumpang[{{ $i }}][nama_penumpang]" 
                                                       placeholder="Nama Lengkap"
                                                       value="{{ old('penumpang.'.$i.'.nama_penumpang') }}"
                                                       required>
                                                <label for="nama_{{ $i }}">Nama Lengkap <span class="text-danger">*</span></label>
                                                @error('penumpang.'.$i.'.nama_penumpang')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Tipe Identitas -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select @error('penumpang.'.$i.'.tipe_identitas') is-invalid @enderror" 
                                                        id="tipe_{{ $i }}" 
                                                        name="penumpang[{{ $i }}][tipe_identitas]"
                                                        required>
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="ktp" {{ old('penumpang.'.$i.'.tipe_identitas') == 'ktp' ? 'selected' : '' }}>KTP</option>
                                                    <option value="sim" {{ old('penumpang.'.$i.'.tipe_identitas') == 'sim' ? 'selected' : '' }}>SIM</option>
                                                    <option value="paspor" {{ old('penumpang.'.$i.'.tipe_identitas') == 'paspor' ? 'selected' : '' }}>Paspor</option>
                                                    <option value="lainnya" {{ old('penumpang.'.$i.'.tipe_identitas') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                </select>
                                                <label for="tipe_{{ $i }}">Tipe Identitas <span class="text-danger">*</span></label>
                                                @error('penumpang.'.$i.'.tipe_identitas')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Nomor Identitas -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" 
                                                       class="form-control @error('penumpang.'.$i.'.nomor_identitas') is-invalid @enderror" 
                                                       id="nomor_identitas_{{ $i }}" 
                                                       name="penumpang[{{ $i }}][nomor_identitas]" 
                                                       placeholder="Nomor Identitas"
                                                       value="{{ old('penumpang.'.$i.'.nomor_identitas') }}"
                                                       required>
                                                <label for="nomor_identitas_{{ $i }}">Nomor Identitas <span class="text-danger">*</span></label>
                                                @error('penumpang.'.$i.'.nomor_identitas')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Nomor HP -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="tel" 
                                                       class="form-control @error('penumpang.'.$i.'.nomor_hp') is-invalid @enderror" 
                                                       id="nomor_hp_{{ $i }}" 
                                                       name="penumpang[{{ $i }}][nomor_hp]" 
                                                       placeholder="Nomor HP"
                                                       value="{{ old('penumpang.'.$i.'.nomor_hp') }}"
                                                       required>
                                                <label for="nomor_hp_{{ $i }}">Nomor HP <span class="text-danger">*</span></label>
                                                @error('penumpang.'.$i.'.nomor_hp')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Format: 08xxxxxxxxxx</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor

                            <!-- Contact Person Info -->
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Informasi Kontak</h6>
                                <p class="small mb-0">
                                    E-ticket dan informasi pemesanan akan dikirim ke email: 
                                    <strong>{{ Auth::user()->email }}</strong>
                                </p>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> yang berlaku
                                    <span class="text-danger">*</span>
                                </label>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Lanjut ke Pembayaran
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-clipboard-list me-2"></i>Ringkasan Pemesanan
                        </h5>
                        
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
                                {{ \Carbon\Carbon::parse($cart['jadwal']->jam_berangkat)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($cart['jadwal']->jam_tiba)->format('H:i') }}
                            </p>
                        </div>
                        
                        <hr>
                        
                        <!-- Bus Info -->
                        <div class="mb-3">
                            <small class="text-muted">Bus</small>
                            <p class="mb-0">
                                <strong>{{ $cart['jadwal']->bus->nama_bus }}</strong>
                                <span class="badge bg-info ms-2">{{ $cart['jadwal']->bus->jenis_kelas }}</span>
                            </p>
                        </div>
                        
                        <hr>
                        
                        <!-- Seats -->
                        <div class="mb-3">
                            <small class="text-muted">Kursi yang Dipilih</small>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                @foreach($selectedSeats as $seat)
                                <span class="badge bg-primary">{{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Price Breakdown -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Harga per Kursi</span>
                                <span>Rp {{ number_format($cart['jadwal']->harga_tiket, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Jumlah Kursi</span>
                                <span>{{ $jumlahKursi }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran</strong>
                                <strong class="text-primary fs-5">Rp {{ number_format($cart['total_harga'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        
                        <!-- Info Box -->
                        <div class="alert alert-warning small mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Penting!</strong> Pastikan data penumpang yang diisi sudah benar dan sesuai dengan identitas asli.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat dan Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Ketentuan Umum</h6>
                    <ul>
                        <li>Penumpang wajib hadir 30 menit sebelum waktu keberangkatan</li>
                        <li>Tunjukkan e-ticket dan identitas yang sesuai saat check-in</li>
                        <li>Barang bawaan maksimal 20kg per penumpang</li>
                    </ul>
                    
                    <h6>2. Pembatalan dan Refund</h6>
                    <ul>
                        <li>Pembatalan dapat dilakukan maksimal 24 jam sebelum keberangkatan</li>
                        <li>Biaya pembatalan 10% dari total pembayaran</li>
                        <li>Refund akan diproses dalam 7-14 hari kerja</li>
                    </ul>
                    
                    <h6>3. Tanggung Jawab</h6>
                    <ul>
                        <li>Perusahaan tidak bertanggung jawab atas keterlambatan akibat force majeure</li>
                        <li>Penumpang bertanggung jawab atas barang bawaan pribadi</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
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
        // Form validation
        document.getElementById('passengerForm').addEventListener('submit', function(e) {
            const agreeTerms = document.getElementById('agreeTerms');
            if (!agreeTerms.checked) {
                e.preventDefault();
                alert('Anda harus menyetujui syarat dan ketentuan');
                return false;
            }
            
            // Show loading
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        });

        // Auto format phone number
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 13) {
                    value = value.slice(0, 13);
                }
                e.target.value = value;
            });
        });
    </script>
</body>
</html>
