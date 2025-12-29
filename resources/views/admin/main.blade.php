<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Admin Panel Kancil Rental Kamera" />
    <meta name="author" content="Kancil Rental" />
    <title>Admin Area - Kancil Rental Kamera</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon.png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="/css/adminstyles.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary-color: #2e86de;
            --secondary-color: #222f3e;
            --accent-color: #54a0ff;
            --success-color: #10ac84;
            --warning-color: #ff9f43;
            --danger-color: #ee5253;
            --light-color: #f5f6fa;
            --dark-color: #2c3e50;
            --sidebar-width: 260px;
            --topbar-height: 60px;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }
        
        .topbar-brand {
            width: var(--sidebar-width);
            padding: 0 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .topbar-brand i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }
        
        .topbar-toggle {
            background: transparent;
            border: none;
            color: #6c757d;
            margin-right: 1rem;
            display: none;
        }
        
        .topbar-search {
            position: relative;
            margin-left: 1rem;
            margin-right: auto;
        }
        
        .topbar-search input {
            padding-left: 2.5rem;
            border-radius: 20px;
            border: 1px solid #e9ecef;
            background-color: #f8f9fa;
        }
        
        .topbar-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .topbar-nav {
            display: flex;
            align-items: center;
        }
        
        .topbar-nav-item {
            position: relative;
            margin-left: 1rem;
        }
        
        .topbar-nav-link {
            color: #6c757d;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .topbar-nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50px;
            transition: all 0.2s ease;
        }
        
        .topbar-user:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .topbar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
        }
        
        .topbar-user-info {
            display: flex;
            flex-direction: column;
            margin-right: 0.5rem;
        }
        
        .topbar-user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark-color);
        }
        
        .topbar-user-role {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            bottom: 0;
            z-index: 1020;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar-collapsed {
            left: calc(-1 * var(--sidebar-width));
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
        }
        
        .sidebar-heading {
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: #6c757d;
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }
        
        .sidebar-item.active {
            color: var(--primary-color);
            background-color: rgba(46, 134, 222, 0.1);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-icon {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1rem;
        }
        
        .sidebar-text {
            font-weight: 500;
        }
        
        .sidebar-btn {
            margin: 0 1.5rem;
            margin-bottom: 1rem;
        }
        
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .sidebar-footer-text {
            font-weight: 600;
            color: var(--dark-color);
            margin-top: 0.25rem;
        }
        
        /* Content */
        .content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
            transition: all 0.3s ease;
        }
        
        .content-full {
            margin-left: 0;
        }
        
        /* Footer */
        .footer {
            background-color: white;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-link {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        /* Modal */
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-footer {
            border-top: none;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1c75c8;
            border-color: #1c75c8;
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #0c9170;
            border-color: #0c9170;
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar-show {
                left: 0;
            }
            
            .content {
                margin-left: 0;
            }
            
            .topbar-toggle {
                display: block;
            }
            
            .topbar-brand {
                width: auto;
                padding: 0;
            }
        }
        
        @media (max-width: 767.98px) {
            .topbar-user-info {
                display: none;
            }
            
            .topbar-search {
                display: none;
            }
        }
        
        /* Utilities */
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        .shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .rounded-circle {
            border-radius: 50% !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }
        
        .dropdown-item:active {
            background-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Topbar -->
    <header class="topbar">
        <button class="topbar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <a href="{{ route('admin.index') }}" class="topbar-brand">
            <i class="fas fa-camera-retro"></i>
            <span>Kancil Admin</span>
        </a>
        
        <div class="topbar-search d-none d-md-block">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control form-control-sm" placeholder="Cari...">
        </div>
        
        <div class="topbar-nav">
            <div class="topbar-nav-item">
                <a href="{{ route('home') }}" class="topbar-nav-link" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Website">
                    <i class="fas fa-globe"></i>
                </a>
            </div>
            
            <div class="topbar-nav-item dropdown">
                <div class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="topbar-user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="topbar-user-info">
                        <div class="topbar-user-name">{{ Auth::user()->name }}</div>
                        <div class="topbar-user-role">
                            {{ Auth::user()->role == 2 ? 'Super Admin' : 'Admin' }}
                        </div>
                    </div>
                    <i class="fas fa-chevron-down ms-1 text-muted small"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="small text-muted">Login terakhir</div>
                            <div class="small fw-medium">{{ date('d M Y, H:i') }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('akun.pengaturan') }}">
                            <i class="fas fa-cog me-2 text-muted"></i>Pengaturan Akun
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt me-2 text-muted"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <a href="{{ route('admin.index') }}" class="sidebar-item {{ Route::is('admin.index') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            
            <div class="sidebar-heading">Manajemen Reservasi</div>
            
            <button type="button" class="btn btn-success w-100 sidebar-btn" data-bs-toggle="modal" data-bs-target="#cetakLaporanModal">
                <i class="fas fa-print me-2"></i>Cetak Laporan
            </button>
            
            <a href="{{ route('penyewaan.index') }}" class="sidebar-item {{ Route::is('penyewaan.index') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list sidebar-icon"></i>
                <span class="sidebar-text">Reservasi</span>
            </a>
            
            <a href="{{ route('riwayat-reservasi') }}" class="sidebar-item {{ Route::is('riwayat-reservasi') ? 'active' : '' }}">
                <i class="fas fa-history sidebar-icon"></i>
                <span class="sidebar-text">Riwayat Reservasi</span>
            </a>
            
            <div class="sidebar-heading">Manajemen Penyewa</div>
            
            <a href="{{ route('admin.user') }}" class="sidebar-item {{ Route::is('admin.user') ? 'active' : '' }}">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">Daftar Penyewa</span>
            </a>
            
            @if (Auth::user()->role == 2)
                <a href="{{ route('superuser.admin') }}" class="sidebar-item {{ Route::is('superuser.admin') ? 'active' : '' }}">
                    <i class="fas fa-user-shield sidebar-icon"></i>
                    <span class="sidebar-text">Manajemen Admin</span>
                </a>
                
                <div class="sidebar-heading">Manajemen Alat</div>
                
                <a href="{{ route('alat.index') }}" class="sidebar-item {{ Route::is('alat.index') ? 'active' : '' }}">
                    <i class="fas fa-camera sidebar-icon"></i>
                    <span class="sidebar-text">Alat</span>
                </a>
                
                <a href="{{ route('kategori.index') }}" class="sidebar-item {{ Route::is('kategori.index') ? 'active' : '' }}">
                    <i class="fas fa-tags sidebar-icon"></i>
                    <span class="sidebar-text">Kategori</span>
                </a>
            @endif
        </div>
        
        <div class="sidebar-footer">
            <div class="small">Login sebagai:</div>
            <div class="sidebar-footer-text">{{ Auth::user()->name }}</div>
        </div>
    </aside>
    
    <!-- Content -->
    <main class="content" id="content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted small">
                    &copy; {{ date('Y') }} Kancil Rental Kamera Purwokerto
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="#" class="footer-link small me-3">Privacy Policy</a>
                    <a href="#" class="footer-link small">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Print Report Modal -->
    <div class="modal fade" id="cetakLaporanModal" tabindex="-1" aria-labelledby="cetakLaporanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cetakLaporanModalLabel">
                        <i class="fas fa-print me-2"></i>Cetak Laporan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cetak') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Dari Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" name="dari" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Sampai Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" name="sampai" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-print me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/adminscripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="/js/datatables.js"></script>
    
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (window.innerWidth >= 992) {
                        sidebar.classList.toggle('sidebar-collapsed');
                        content.classList.toggle('content-full');
                    } else {
                        sidebar.classList.toggle('sidebar-show');
                    }
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992 && 
                    !sidebar.contains(event.target) && 
                    !sidebarToggle.contains(event.target) && 
                    sidebar.classList.contains('sidebar-show')) {
                    sidebar.classList.remove('sidebar-show');
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('sidebar-show');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    content.classList.remove('content-full');
                }
            });
        });
    </script>
</body>
</html>