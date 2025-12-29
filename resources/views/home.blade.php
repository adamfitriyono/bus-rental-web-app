<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="BisKu - Sistem Penjualan Tiket Bis Online Terpercaya" />
    <meta name="author" content="BisKu" />
    <title>BisKu - Pesan Tiket Bus Online</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .btn-login {
            padding: 8px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 600px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 100px 0 80px;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            color: white;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.95);
            margin-bottom: 40px;
            font-weight: 300;
        }
        
        .hero-illustration {
            position: relative;
            z-index: 1;
        }
        
        .hero-illustration img {
            width: 100%;
            max-width: 500px;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.2));
        }
        
        /* Search Box */
        .search-box-container {
            position: relative;
            z-index: 2;
            margin-top: -80px;
        }
        
        .search-box {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .search-box h3 {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 25px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            padding: 12px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }
        
        .btn-search {
            padding: 12px 50px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
        }
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background-color: #f8f9fa;
        }
        
        .section-title {
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--dark-color);
            margin-bottom: 15px;
            text-align: center;
        }
        
        .section-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            text-align: center;
            margin-bottom: 60px;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
            color: white;
        }
        
        .feature-card h4 {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .feature-card p {
            color: #6c757d;
            line-height: 1.6;
        }
        
        /* Why Choose Us Section */
        .why-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .why-section .section-title,
        .why-section .section-subtitle {
            color: white;
        }
        
        .stat-card {
            text-align: center;
            padding: 30px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Footer */
        .footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
        }
        
        /* Search Results */
        #searchResults {
            margin-top: 30px;
        }
        
        .jadwal-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .jadwal-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }
        
        .bus-name {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .time-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 0;
        }
        
        .time-item {
            text-align: center;
        }
        
        .time-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .time-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .duration {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .price-section {
            text-align: right;
        }
        
        .price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .seats-available {
            color: var(--success-color);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 20px;
        }
        
        /* Loading Spinner */
        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .search-box {
                padding: 25px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-bus"></i> BisKu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#layanan">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    @guest
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-primary btn-login" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-login" href="{{ route('daftar') }}">
                                <i class="fas fa-user-plus"></i> Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role == 1)
                                    <li><a class="dropdown-item" href="{{ route('admin.index') }}">
                                        <i class="fas fa-tachometer-alt"></i> Admin Panel
                                    </a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.history') }}">
                                        <i class="fas fa-history"></i> Riwayat Pesanan
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="GET">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Pesan Tiket Bus Jadi Lebih Mudah</h1>
                    <p class="hero-subtitle">
                        Temukan perjalanan terbaik Anda dengan harga terjangkau. 
                        Pesan tiket bus online kapan saja, di mana saja.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#cari-tiket" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-search"></i> Cari Tiket
                        </a>
                        <a href="#layanan" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-info-circle"></i> Pelajari
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 hero-illustration text-center mt-5 mt-lg-0">
                    <i class="fas fa-bus" style="font-size: 15rem; color: rgba(255,255,255,0.2);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Box -->
    <section class="search-box-container" id="cari-tiket">
        <div class="container">
            <div class="search-box">
                <h3><i class="fas fa-ticket-alt text-primary"></i> Cari Tiket Bus</h3>
                <form id="searchForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Kota Asal</label>
                            <select class="form-select" name="kota_asal" id="kotaAsal" required>
                                <option value="">Pilih Kota Asal</option>
                                @foreach($kotaAsal as $kota)
                                    <option value="{{ $kota }}">{{ $kota }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kota Tujuan</label>
                            <select class="form-select" name="kota_tujuan" id="kotaTujuan" required>
                                <option value="">Pilih Kota Tujuan</option>
                                @foreach($kotaTujuan as $kota)
                                    <option value="{{ $kota }}">{{ $kota }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Keberangkatan</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" 
                                   min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-search">
                                <i class="fas fa-search"></i> Cari Jadwal
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Search Results -->
                <div id="searchResults"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="layanan">
        <div class="container">
            <h2 class="section-title">Kenapa Pilih BisKu?</h2>
            <p class="section-subtitle">Kemudahan dan kenyamanan dalam satu platform</p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4>Pemesanan Cepat</h4>
                        <p>Pesan tiket hanya dalam hitungan menit dengan sistem yang mudah dan intuitif</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Aman & Terpercaya</h4>
                        <p>Transaksi Anda dijamin aman dengan sistem pembayaran yang terenkripsi</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h4>Harga Terbaik</h4>
                        <p>Dapatkan harga kompetitif dan berbagai promo menarik setiap bulannya</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>24/7 Tersedia</h4>
                        <p>Akses kapan saja, di mana saja tanpa batasan waktu operasional</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>E-Ticket Digital</h4>
                        <p>Tidak perlu cetak tiket, cukup tunjukkan e-ticket di smartphone Anda</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>Customer Support</h4>
                        <p>Tim support kami siap membantu Anda dengan respon yang cepat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-section" id="tentang">
        <div class="container">
            <h2 class="section-title">BisKu Dalam Angka</h2>
            <p class="section-subtitle">Kepercayaan pelanggan adalah prioritas kami</p>
            
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Pengguna Aktif</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Armada Bus</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Rute Tersedia</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Tingkat Kepuasan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-bus"></i> BisKu</h5>
                    <p class="mt-3">Platform pemesanan tiket bus online terpercaya di Indonesia. Membuat perjalanan Anda lebih nyaman dan mudah.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Perusahaan</h5>
                    <a href="#">Tentang Kami</a>
                    <a href="#">Karir</a>
                    <a href="#">Blog</a>
                    <a href="#">Press</a>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Layanan</h5>
                    <a href="#">Cari Tiket</a>
                    <a href="#">Rute Bus</a>
                    <a href="#">Jadwal</a>
                    <a href="#">Promo</a>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Bantuan</h5>
                    <a href="#">FAQ</a>
                    <a href="#">Kontak</a>
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Kebijakan Privasi</a>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Download App</h5>
                    <a href="#" class="btn btn-outline-light btn-sm mb-2 w-100">
                        <i class="fab fa-google-play"></i> Google Play
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm w-100">
                        <i class="fab fa-app-store"></i> App Store
                    </a>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; {{ date('Y') }} BisKu. All rights reserved. Made with <i class="fas fa-heart text-danger"></i> for Indonesia</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Search AJAX -->
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultsDiv = document.getElementById('searchResults');
            
            // Show loading
            resultsDiv.innerHTML = `
                <div class="text-center my-5">
                    <div class="spinner-border text-primary spinner-border-custom" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Mencari jadwal terbaik untuk Anda...</p>
                </div>
            `;
            
            fetch('{{ route('search') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(data.jadwals.length === 0) {
                        resultsDiv.innerHTML = `
                            <div class="alert alert-warning alert-custom mt-4" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Tidak ada jadwal tersedia</strong><br>
                                Maaf, tidak ada jadwal bus untuk rute dan tanggal yang Anda pilih.
                            </div>
                        `;
                    } else {
                        let html = '<h4 class="mt-5 mb-4"><i class="fas fa-list"></i> Jadwal Tersedia</h4>';
                        
                        data.jadwals.forEach(jadwal => {
                            html += `
                                <div class="jadwal-card">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="bus-name">
                                                <i class="fas fa-bus text-primary"></i> ${jadwal.bus.nama_bus}
                                            </div>
                                            <div class="mb-2">
                                                <span class="badge bg-primary">${jadwal.bus.jenis_kelas}</span>
                                                <span class="badge bg-secondary">${jadwal.bus.fasilitas || 'Standard'}</span>
                                            </div>
                                            <div class="time-info">
                                                <div class="time-item">
                                                    <div class="time-label">Berangkat</div>
                                                    <div class="time-value">${jadwal.jam_berangkat}</div>
                                                </div>
                                                <div>
                                                    <i class="fas fa-arrow-right text-primary"></i>
                                                </div>
                                                <div class="time-item">
                                                    <div class="time-label">Tiba</div>
                                                    <div class="time-value">${jadwal.jam_tiba}</div>
                                                </div>
                                            </div>
                                            <div class="duration">
                                                <i class="fas fa-clock"></i> Estimasi 5-6 jam
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="price-section">
                                                <div class="price">Rp ${parseInt(jadwal.harga_tiket).toLocaleString('id-ID')}</div>
                                                <div class="seats-available">
                                                    <i class="fas fa-chair"></i> ${jadwal.bus.kursi_tersedia} kursi tersedia
                                                </div>
                                                <a href="/bus/${jadwal.id_jadwal}" class="btn btn-primary btn-lg w-100">
                                                    <i class="fas fa-ticket-alt"></i> Pilih Kursi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        resultsDiv.innerHTML = html;
                    }
                } else {
                    resultsDiv.innerHTML = `
                        <div class="alert alert-danger alert-custom mt-4" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Error!</strong><br>
                            ${data.message || 'Terjadi kesalahan saat mencari jadwal.'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML = `
                    <div class="alert alert-danger alert-custom mt-4" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Error!</strong><br>
                        Terjadi kesalahan saat menghubungi server.
                    </div>
                `;
            });
        });

        // Set default date to today
        document.getElementById('tanggal').valueAsDate = new Date();
    </script>
</body>
</html>
