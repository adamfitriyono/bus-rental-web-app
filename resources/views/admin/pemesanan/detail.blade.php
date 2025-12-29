@extends('admin.main')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-receipt me-2"></i>Detail Pemesanan #{{ $pemesanan->id_pemesanan }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pemesanan.index') }}">Pemesanan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
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

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Pemesanan Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pemesanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>ID Pemesanan</strong></td>
                                    <td>#{{ $pemesanan->id_pemesanan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pesan</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
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
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Kursi</strong></td>
                                    <td><span class="badge bg-secondary">{{ $pemesanan->jumlah_kursi }} kursi</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Kursi</strong></td>
                                    <td><strong>{{ $pemesanan->nomor_kursi }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Total Harga</strong></td>
                                    <td><h5 class="mb-0 text-primary">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h5></td>
                                </tr>
                                <tr>
                                    <td><strong>Harga/Tiket</strong></td>
                                    <td>Rp {{ number_format($pemesanan->jadwal->harga_tiket, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bus</strong></td>
                                    <td>
                                        {{ $pemesanan->bus->nama_bus }}
                                        <br>
                                        <small class="text-muted">{{ $pemesanan->bus->jenis_kelas }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Plat Nomor</strong></td>
                                    <td>{{ $pemesanan->bus->plat_nomor }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal & Rute Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-route me-2"></i>Jadwal Perjalanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Kota Asal</h6>
                                    <h5 class="mb-0">{{ $pemesanan->jadwal->rute->kota_asal }}</h5>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_berangkat)->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt fa-2x text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Kota Tujuan</h6>
                                    <h5 class="mb-0">{{ $pemesanan->jadwal->rute->kota_tujuan }}</h5>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($pemesanan->jadwal->jam_tiba)->format('H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-calendar-day text-muted mb-2"></i>
                            <h6 class="mb-0">Tanggal</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($pemesanan->jadwal->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-road text-muted mb-2"></i>
                            <h6 class="mb-0">Jarak</h6>
                            <p class="mb-0">{{ $pemesanan->jadwal->rute->jarak_km }} km</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-clock text-muted mb-2"></i>
                            <h6 class="mb-0">Estimasi</h6>
                            <p class="mb-0">{{ $pemesanan->jadwal->rute->estimasi_jam }} jam</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penumpang Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-users me-2"></i>Data Penumpang ({{ $pemesanan->penumpangs->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($pemesanan->penumpangs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tipe Identitas</th>
                                    <th>Nomor Identitas</th>
                                    <th>Nomor HP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pemesanan->penumpangs as $index => $penumpang)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $penumpang->nama_penumpang }}</td>
                                    <td>
                                        @if($penumpang->tipe_identitas == 'ktp')
                                            <span class="badge bg-primary">KTP</span>
                                        @elseif($penumpang->tipe_identitas == 'sim')
                                            <span class="badge bg-info">SIM</span>
                                        @elseif($penumpang->tipe_identitas == 'paspor')
                                            <span class="badge bg-success">Paspor</span>
                                        @else
                                            <span class="badge bg-secondary">{{ strtoupper($penumpang->tipe_identitas) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $penumpang->nomor_identitas }}</td>
                                    <td>{{ $penumpang->nomor_hp }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-user-slash me-2"></i>Data penumpang belum diisi
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Pemesan Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user me-2"></i>Data Pemesan
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-user me-2"></i>Nama</td>
                            <td class="text-end"><strong>{{ $pemesanan->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-envelope me-2"></i>Email</td>
                            <td class="text-end">{{ $pemesanan->user->email }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-phone me-2"></i>No. HP</td>
                            <td class="text-end">{{ $pemesanan->user->nomor_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-id-badge me-2"></i>Status</td>
                            <td class="text-end">
                                <span class="badge bg-{{ $pemesanan->user->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($pemesanan->user->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Pembayaran Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-credit-card me-2"></i>Informasi Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    @if($pemesanan->pembayaran)
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-barcode me-2"></i>Kode Transaksi</td>
                            <td class="text-end"><code>{{ $pemesanan->pembayaran->kode_transaksi }}</code></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-wallet me-2"></i>Metode</td>
                            <td class="text-end">
                                {{ str_replace('_', ' ', ucwords($pemesanan->pembayaran->metode_pembayaran)) }}
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-money-bill-wave me-2"></i>Jumlah</td>
                            <td class="text-end"><strong>Rp {{ number_format($pemesanan->pembayaran->jumlah, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-info-circle me-2"></i>Status</td>
                            <td class="text-end">
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
                            </td>
                        </tr>
                        @if($pemesanan->pembayaran->tanggal_pembayaran)
                        <tr>
                            <td><i class="fas fa-calendar-check me-2"></i>Tanggal Bayar</td>
                            <td class="text-end">{{ \Carbon\Carbon::parse($pemesanan->pembayaran->tanggal_pembayaran)->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                        @if($pemesanan->pembayaran->bukti_pembayaran)
                        <tr>
                            <td colspan="2" class="text-center pt-3">
                                <a href="{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-image me-2"></i>Lihat Bukti Transfer
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>

                    <!-- Verify Button -->
                    @if($pemesanan->pembayaran->status_pembayaran == 'Belum Lunas' && $pemesanan->status_pemesanan == 'pending')
                    <div class="d-grid mt-3">
                        <button type="button" 
                                class="btn btn-success" 
                                onclick="confirmVerify({{ $pemesanan->pembayaran->id_pembayaran }})">
                            <i class="fas fa-check-circle me-2"></i>Verifikasi Pembayaran
                        </button>
                    </div>
                    <form id="verify-form-{{ $pemesanan->pembayaran->id_pembayaran }}" 
                          action="{{ route('admin.pemesanan.verify', $pemesanan->pembayaran->id_pembayaran) }}" 
                          method="POST" 
                          style="display: none;">
                        @csrf
                    </form>
                    @endif
                    @else
                    <p class="text-muted text-center mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Data pembayaran belum tersedia
                    </p>
                    @endif
                </div>
            </div>

            <!-- E-Ticket Card -->
            @if($pemesanan->tiket)
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-ticket-alt me-2"></i>E-Ticket
                    </h6>
                </div>
                <div class="card-body text-center">
                    <h5 class="mb-3">{{ $pemesanan->tiket->kode_tiket }}</h5>
                    
                    @if($pemesanan->tiket->qr_code)
                    <img src="{{ asset('storage/' . $pemesanan->tiket->qr_code) }}" 
                         alt="QR Code" 
                         class="img-fluid mb-3" 
                         style="max-width: 200px;">
                    @endif

                    <div class="d-grid gap-2">
                        @if($pemesanan->tiket->file_pdf)
                        <a href="{{ asset('storage/' . $pemesanan->tiket->file_pdf) }}" 
                           class="btn btn-primary" 
                           target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF
                        </a>
                        @endif
                        <span class="badge bg-{{ $pemesanan->tiket->status_tiket == 'aktif' ? 'success' : 'secondary' }}">
                            Status: {{ ucfirst($pemesanan->tiket->status_tiket) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Card -->
            @if($pemesanan->status_pemesanan != 'dibatalkan' && $pemesanan->status_pemesanan != 'selesai')
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid">
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="confirmCancel({{ $pemesanan->id_pemesanan }})">
                            <i class="fas fa-times-circle me-2"></i>Batalkan Pemesanan
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Kursi akan dikembalikan dan status akan diubah menjadi dibatalkan
                    </small>

                    <form id="cancel-form-{{ $pemesanan->id_pemesanan }}" 
                          action="{{ route('admin.pemesanan.cancel', $pemesanan->id_pemesanan) }}" 
                          method="POST" 
                          style="display: none;">
                        @csrf
                    </form>
                </div>
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
