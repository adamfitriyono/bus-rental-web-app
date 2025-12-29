<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Pilih Kursi - Sistem Penjualan Tiket Bis Online" />
    <title>Pilih Kursi - Tiket Bis Online</title>
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
        
        .bus-layout {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        
        .bus-front {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .seat {
            aspect-ratio: 1;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            background: white;
        }
        
        .seat:hover:not(.occupied):not(.locked) {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .seat.available {
            border-color: #198754;
            color: #198754;
        }
        
        .seat.available:hover {
            background: #198754;
            color: white;
        }
        
        .seat.selected {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .seat.occupied {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .seat.locked {
            background: #ffc107;
            border-color: #ffc107;
            color: white;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .aisle {
            background: transparent !important;
            border: none !important;
            cursor: default !important;
        }
        
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-box {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: 2px solid;
        }
        
        .summary-card {
            position: sticky;
            top: 80px;
        }
        
        .selected-seats-list {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .selected-seat-badge {
            padding: 8px 15px;
            border-radius: 20px;
            background: #0d6efd;
            color: white;
            font-weight: 600;
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
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </li>
                    @auth
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
        <div class="row">
            <!-- Left Column - Seat Selection -->
            <div class="col-lg-8 mb-4">
                <!-- Journey Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    {{ $jadwal->rute->kota_asal }}
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    {{ $jadwal->rute->kota_tujuan }}
                                </h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock me-2"></i>
                                    {{ \Carbon\Carbon::parse($jadwal->jam_berangkat)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($jadwal->jam_tiba)->format('H:i') }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-bus me-2"></i>
                                    <strong>{{ $jadwal->bus->nama_bus }}</strong>
                                    <span class="badge bg-info ms-2">{{ $jadwal->bus->jenis_kelas }}</span>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <h5 class="text-muted mb-1">Harga per Kursi</h5>
                                <h3 class="text-primary mb-0">Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seat Selection -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-couch me-2"></i>Pilih Kursi Anda
                        </h5>
                        
                        <div class="bus-layout">
                            <!-- Bus Front -->
                            <div class="bus-front">
                                <i class="fas fa-steering-wheel fa-2x"></i>
                                <p class="mb-0 mt-2">SUPIR</p>
                            </div>
                            
                            <!-- Seat Grid -->
                            <div class="seat-grid" id="seatGrid">
                                <!-- Seats will be loaded dynamically -->
                                <div class="text-center" style="grid-column: 1 / -1;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat kursi...</p>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="legend">
                                <div class="legend-item">
                                    <div class="legend-box" style="border-color: #198754; background: white;"></div>
                                    <span class="small">Tersedia</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box" style="border-color: #0d6efd; background: #0d6efd;"></div>
                                    <span class="small">Dipilih</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box" style="border-color: #dc3545; background: #dc3545;"></div>
                                    <span class="small">Terisi</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box" style="border-color: #ffc107; background: #ffc107;"></div>
                                    <span class="small">Terkunci</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="col-lg-4">
                <div class="summary-card card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-clipboard-list me-2"></i>Ringkasan Pemesanan
                        </h5>
                        
                        <!-- Selected Seats -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Kursi yang Dipilih</h6>
                            <div class="selected-seats-list" id="selectedSeatsList">
                                <p class="text-muted small mb-0">Belum ada kursi dipilih</p>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted" id="seatCount">0 kursi dipilih</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Price Breakdown -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Harga per Kursi</span>
                                <span>Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Jumlah Kursi</span>
                                <span id="quantityDisplay">0</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total</strong>
                                <strong class="text-primary" id="totalPrice">Rp 0</strong>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <form action="{{ route('cart.add', $jadwal->id_jadwal) }}" method="POST" id="bookingForm">
                            @csrf
                            <input type="hidden" name="nomor_kursi[]" id="selectedSeatsInput">
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="continueBtn" disabled>
                                    <i class="fas fa-arrow-right me-2"></i>
                                    @guest
                                        Lanjutkan (Login Diperlukan)
                                    @else
                                        Lanjutkan ke Pembayaran
                                    @endguest
                                </button>
                            </div>
                        </form>
                        
                        <div class="alert alert-info mt-3 small mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            @guest
                                Pilih kursi dan Anda akan diminta login untuk melanjutkan
                            @else
                                Pilih minimal 1 kursi untuk melanjutkan
                            @endguest
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
        const jadwalId = {{ $jadwal->id_jadwal }};
        const hargaTiket = {{ $jadwal->harga_tiket }};
        let selectedSeats = [];
        let seatsData = [];

        // Load seats on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSeats();
        });

        // Load seat layout from server
        function loadSeats() {
            fetch(`/seat-layout/${jadwalId}`)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        seatsData = data.kursis;
                        renderSeats(data.kursis);
                    } else {
                        throw new Error(data.message || 'Failed to load seats');
                    }
                })
                .catch(error => {
                    console.error('Error loading seats:', error);
                    document.getElementById('seatGrid').innerHTML = `
                        <div class="text-center text-danger" style="grid-column: 1 / -1;">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>Gagal memuat kursi. Silakan refresh halaman.</p>
                        </div>
                    `;
                });
        }

        // Render seat grid
        function renderSeats(kursis) {
            const grid = document.getElementById('seatGrid');
            grid.innerHTML = '';
            
            kursis.forEach((kursi, index) => {
                const seatDiv = document.createElement('div');
                seatDiv.className = 'seat';
                seatDiv.dataset.nomorKursi = kursi.nomor_kursi;
                
                // Add aisle after seat 2 and 4 in each row (2-2 configuration)
                if ((index + 1) % 4 === 2) {
                    const aisleDiv = document.createElement('div');
                    aisleDiv.className = 'aisle';
                    grid.appendChild(aisleDiv);
                }
                
                // Set seat status
                if (kursi.status_kursi === 'tersedia') {
                    seatDiv.classList.add('available');
                    seatDiv.onclick = () => toggleSeat(kursi.nomor_kursi);
                } else if (kursi.status_kursi === 'terpesan') {
                    seatDiv.classList.add('occupied');
                } else if (kursi.status_kursi === 'terkunci') {
                    seatDiv.classList.add('locked');
                }
                
                seatDiv.innerHTML = kursi.nomor_kursi;
                grid.appendChild(seatDiv);
            });
        }

        // Toggle seat selection
        function toggleSeat(nomorKursi) {
            const seatIndex = selectedSeats.indexOf(nomorKursi);
            const seatElement = document.querySelector(`[data-nomor-kursi="${nomorKursi}"]`);
            
            if (seatIndex > -1) {
                // Deselect
                selectedSeats.splice(seatIndex, 1);
                seatElement.classList.remove('selected');
                seatElement.classList.add('available');
            } else {
                // Select
                selectedSeats.push(nomorKursi);
                seatElement.classList.remove('available');
                seatElement.classList.add('selected');
            }
            
            updateSummary();
        }

        // Update summary
        function updateSummary() {
            const count = selectedSeats.length;
            const total = count * hargaTiket;
            
            // Update selected seats list
            const listContainer = document.getElementById('selectedSeatsList');
            if (count > 0) {
                listContainer.innerHTML = selectedSeats
                    .map(seat => `<span class="selected-seat-badge">${seat}</span>`)
                    .join('');
            } else {
                listContainer.innerHTML = '<p class="text-muted small mb-0">Belum ada kursi dipilih</p>';
            }
            
            // Update count and price
            document.getElementById('seatCount').textContent = `${count} kursi dipilih`;
            document.getElementById('quantityDisplay').textContent = count;
            document.getElementById('totalPrice').textContent = `Rp ${total.toLocaleString('id-ID')}`;
            
            // Update form: satu input hidden, value join kursi dengan koma
            const input = document.getElementById('selectedSeatsInput');
            input.value = selectedSeats.join(',');
            
            // Enable/disable button
            document.getElementById('continueBtn').disabled = count === 0;
        }

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (selectedSeats.length === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 kursi');
                return false;
            }

            // Jika belum login, redirect ke login
            @guest
            e.preventDefault();
            window.location.href = '{{ route("login") }}';
            return false;
            @else
            // Sudah login, submit via AJAX
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            selectedSeats.forEach(seat => {
                formData.append('nomor_kursi[]', seat);
            });
            const btn = document.getElementById('continueBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(res => {
                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;
                if (data.success) {
                    window.location.href = '/passenger-data';
                } else {
                    alert(data.message || 'Terjadi kesalahan, silakan coba lagi.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Lanjutkan ke Pembayaran';
                }
            })
            .catch(() => {
                alert('Gagal terhubung ke server.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Lanjutkan ke Pembayaran';
            });
            @endguest
        });
    </script>
</body>
</html>
