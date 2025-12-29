@extends('admin.main')

@section('title', 'Manajemen Pemesanan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-ticket-alt me-2"></i>Manajemen Pemesanan
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pemesanan</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.reports.penjualan') }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>Laporan
            </a>
        </div>
    </div>

    <!-- Success/Error Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pemesanan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Pemesanan::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Pembayaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Pemesanan::where('status_pemesanan', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Dikonfirmasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Pemesanan::where('status_pemesanan', 'dikonfirmasi')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Dibatalkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Pemesanan::where('status_pemesanan', 'dibatalkan')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter me-2"></i>Filter Pemesanan
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pemesanan.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Pemesanan</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal_dari" 
                               name="tanggal_dari" 
                               value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal_sampai" 
                               name="tanggal_sampai" 
                               value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="metode_pembayaran" name="metode_pembayaran">
                            <option value="">Semua Metode</option>
                            <option value="transfer_bank" {{ request('metode_pembayaran') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="e_wallet" {{ request('metode_pembayaran') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="virtual_account" {{ request('metode_pembayaran') == 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
                            <option value="credit_card" {{ request('metode_pembayaran') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'metode_pembayaran']))
                <div class="row mt-2">
                    <div class="col-12">
                        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times me-2"></i>Reset Filter
                        </a>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Pemesanan List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pemesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Pesan</th>
                            <th>Pemesan</th>
                            <th>Rute</th>
                            <th>Jadwal</th>
                            <th>Kursi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesanans as $pemesanan)
                        <tr>
                            <td><strong>#{{ $pemesanan->id_pemesanan }}</strong></td>
                            <td>
                                {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('H:i') }}</small>
                            </td>
                            <td>
                                {{ $pemesanan->user->name }}
                                <br>
                                <small class="text-muted">{{ $pemesanan->user->email }}</small>
                            </td>
                            <td>
                                <span class="text-primary">{{ $pemesanan->jadwal->rute->kota_asal }}</span>
                                <i class="fas fa-arrow-right mx-1"></i>
                                <span class="text-success">{{ $pemesanan->jadwal->rute->kota_tujuan }}</span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_berangkat)->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $pemesanan->jumlah_kursi }} kursi</span>
                                <br>
                                <small class="text-muted">{{ $pemesanan->nomor_kursi }}</small>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($pemesanan->status_pemesanan == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($pemesanan->status_pemesanan == 'dikonfirmasi')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @elseif($pemesanan->status_pemesanan == 'selesai')
                                    <span class="badge bg-info">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                @if($pemesanan->pembayaran)
                                    @if($pemesanan->pembayaran->status_pembayaran == 'Lunas')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Lunas
                                        </span>
                                    @elseif($pemesanan->pembayaran->status_pembayaran == 'Belum Lunas')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Belum Lunas
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $pemesanan->pembayaran->metode_pembayaran }}</small>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pemesanan.show', $pemesanan->id_pemesanan) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran == 'Belum Lunas' && $pemesanan->status_pemesanan == 'pending')
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            onclick="confirmVerify({{ $pemesanan->pembayaran->id_pembayaran }})"
                                            title="Verifikasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                    @if($pemesanan->status_pemesanan != 'dibatalkan' && $pemesanan->status_pemesanan != 'selesai')
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmCancel({{ $pemesanan->id_pemesanan }})"
                                            title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>

                                <!-- Verify Form -->
                                @if($pemesanan->pembayaran)
                                <form id="verify-form-{{ $pemesanan->pembayaran->id_pembayaran }}" 
                                      action="{{ route('admin.pemesanan.verify', $pemesanan->pembayaran->id_pembayaran) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                </form>
                                @endif

                                <!-- Cancel Form -->
                                <form id="cancel-form-{{ $pemesanan->id_pemesanan }}" 
                                      action="{{ route('admin.pemesanan.cancel', $pemesanan->id_pemesanan) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">
                                    @if(request()->hasAny(['status', 'tanggal_dari', 'tanggal_sampai', 'metode_pembayaran']))
                                        Tidak ada pemesanan yang sesuai dengan filter
                                    @else
                                        Belum ada pemesanan
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pemesanans->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $pemesanans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmVerify(id) {
        if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?\n\nPastikan pembayaran sudah masuk ke rekening.')) {
            document.getElementById('verify-form-' + id).submit();
        }
    }

    function confirmCancel(id) {
        if (confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?\n\nKursi akan dikembalikan dan status akan diubah menjadi dibatalkan.')) {
            document.getElementById('cancel-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection
