<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Sistem Penjualan Tiket Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar minimal (optional) -->
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-bus me-2"></i>Sistem Tiket Bus
            </a>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="login-section bg-white p-5 shadow-sm rounded">
                        <div class="row g-0"> 
                            <div class="col-md-6 pe-md-4 mb-4 mb-md-0">
                                <h3 class="mb-4">Belum Punya Akun?</h3>
                                <p>Daftar sekarang dan nikmati kemudahan pemesanan tiket bus online.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check-circle text-primary me-2"></i> Booking online 24/7</li>
                                    <li><i class="fas fa-check-circle text-primary me-2"></i> Riwayat pemesanan lengkap</li>
                                    <li><i class="fas fa-check-circle text-primary me-2"></i> E-Ticket otomatis</li>
                                    <li><i class="fas fa-check-circle text-primary me-2"></i> Proses cepat & aman</li>
                                </ul>
                                <a href="{{ route('daftar') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                </a>
                            </div>
                            <div class="col-md-6 ps-md-4 border-start">
                                <h3 class="mb-4">Login</h3>

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @include('partials.login') {{-- ini bagian form login --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
