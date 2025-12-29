@extends('admin.main')

@section('title', 'Detail Bus')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-bus me-2"></i>Detail Bus
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bus.index') }}">Bus</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.bus.edit', $bus->id_bus) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit Bus
            </a>
            <a href="{{ route('admin.bus.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Bus Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Bus
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Nama Bus</label>
                            <h5>{{ $bus->nama_bus }}</h5>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Plat Nomor</label>
                            <h5><span class="badge bg-secondary fs-6">{{ $bus->plat_nomor }}</span></h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Jenis Kelas</label>
                            <h5>{{ $bus->jenis_kelas }}</h5>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Status</label>
                            <h5>
                                @if($bus->status == 'aktif')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-times-circle me-1"></i>Non-aktif
                                    </span>
                                @endif
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="text-muted small">Kapasitas Kursi</label>
                            <h5><i class="fas fa-chair text-primary me-2"></i>{{ $bus->kapasitas_kursi }} kursi</h5>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="text-muted small">Kursi Tersedia</label>
                            <h5>
                                <i class="fas fa-couch {{ $bus->kursi_tersedia > 0 ? 'text-success' : 'text-danger' }} me-2"></i>
                                {{ $bus->kursi_tersedia }} kursi
                            </h5>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="text-muted small">Kursi Terisi</label>
                            <h5>
                                <i class="fas fa-user-friends text-warning me-2"></i>
                                {{ $bus->kapasitas_kursi - $bus->kursi_tersedia }} kursi
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Tahun Pembuatan</label>
                            <h5>{{ $bus->tahun_pembuatan ?? 'Tidak diketahui' }}</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-muted small">Fasilitas</label>
                            @if($bus->fasilitas)
                                <div class="mt-2">
                                    @foreach(explode(',', $bus->fasilitas) as $fasilitas)
                                        <span class="badge bg-info me-2 mb-2">
                                            <i class="fas fa-check me-1"></i>{{ trim($fasilitas) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Tidak ada fasilitas tercatat</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Bus -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="m-0">
                        <i class="fas fa-calendar-alt me-2"></i>Jadwal Bus
                    </h5>
                </div>
                <div class="card-body">
                    @if($bus->jadwals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rute</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bus->jadwals->sortBy('tanggal')->take(10) as $jadwal)
                                    <tr>
                                        <td>
                                            <strong>{{ $jadwal->rute->kota_asal }}</strong>
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            <strong>{{ $jadwal->rute->kota_tujuan }}</strong>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($jadwal->jam_berangkat)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($jadwal->jam_tiba)->format('H:i') }}
                                        </td>
                                        <td>Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}</td>
                                        <td>
                                            @if($jadwal->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($jadwal->status == 'selesai')
                                                <span class="badge bg-secondary">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">Dibatalkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($bus->jadwals->count() > 10)
                            <p class="text-center text-muted mt-3">
                                Menampilkan 10 dari {{ $bus->jadwals->count() }} jadwal
                            </p>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada jadwal untuk bus ini</p>
                            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Tambah Jadwal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics & Actions -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar me-2"></i>Statistik
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Tingkat Hunian</span>
                            <span class="small font-weight-bold">
                                {{ $bus->kapasitas_kursi > 0 ? round((($bus->kapasitas_kursi - $bus->kursi_tersedia) / $bus->kapasitas_kursi) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            @php
                                $occupancy = $bus->kapasitas_kursi > 0 ? (($bus->kapasitas_kursi - $bus->kursi_tersedia) / $bus->kapasitas_kursi) * 100 : 0;
                                $progressColor = $occupancy < 50 ? 'success' : ($occupancy < 80 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress-bar bg-{{ $progressColor }}" 
                                 role="progressbar" 
                                 style="width: {{ $occupancy }}%"
                                 aria-valuenow="{{ $occupancy }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $bus->jadwals->where('status', 'aktif')->count() }}</h4>
                                <small class="text-muted">Jadwal Aktif</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-ticket-alt fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $bus->pemesanans->count() }}</h4>
                                <small class="text-muted">Total Pemesanan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clock me-2"></i>Informasi Meta
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">ID Bus:</td>
                            <td class="text-end"><strong>{{ $bus->id_bus }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat:</td>
                            <td class="text-end">{{ $bus->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Update Terakhir:</td>
                            <td class="text-end">{{ $bus->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Usia Bus:</td>
                            <td class="text-end">
                                @if($bus->tahun_pembuatan)
                                    {{ date('Y') - $bus->tahun_pembuatan }} tahun
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.bus.edit', $bus->id_bus) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Bus
                        </a>
                        <a href="{{ route('admin.jadwal.create') }}?bus_id={{ $bus->id_bus }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus me-2"></i>Tambah Jadwal
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Hapus Bus
                        </button>
                    </div>
                    
                    <form id="delete-form" 
                          action="{{ route('admin.bus.destroy', $bus->id_bus) }}" 
                          method="POST" 
                          style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus bus ini? Semua jadwal terkait juga akan terhapus.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
@endsection
