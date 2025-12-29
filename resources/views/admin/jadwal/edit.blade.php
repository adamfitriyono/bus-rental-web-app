@extends('admin.main')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-edit me-2"></i>Edit Jadwal
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Warning if has bookings -->
    @if($jadwal->pemesanans->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas fa-exclamation-triangle me-2"></i>Peringatan!
        </h5>
        <p class="mb-0">
            Jadwal ini memiliki <strong>{{ $jadwal->pemesanans->count() }} pemesanan</strong>. 
            Perubahan pada jadwal ini dapat mempengaruhi pemesanan yang sudah ada.
        </p>
        <hr>
        <ul class="mb-0">
            @foreach($jadwal->pemesanans->take(5) as $pemesanan)
            <li>
                Pemesanan #{{ $pemesanan->id_pemesanan }} - 
                {{ $pemesanan->user->name }} - 
                <span class="badge bg-{{ $pemesanan->status_pemesanan == 'confirmed' ? 'success' : 'warning' }}">
                    {{ $pemesanan->status_pemesanan }}
                </span>
            </li>
            @endforeach
            @if($jadwal->pemesanans->count() > 5)
            <li>Dan {{ $jadwal->pemesanans->count() - 5 }} pemesanan lainnya...</li>
            @endif
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-edit me-2"></i>Form Edit Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jadwal.update', $jadwal->id_jadwal) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Rute Selection -->
                        <div class="mb-3">
                            <label for="id_rute" class="form-label">
                                Rute <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('id_rute') is-invalid @enderror" 
                                    id="id_rute" 
                                    name="id_rute" 
                                    required>
                                <option value="">Pilih Rute</option>
                                @foreach($rutes as $rute)
                                    <option value="{{ $rute->id_rute }}" 
                                            data-jarak="{{ $rute->jarak_km }}"
                                            data-estimasi="{{ $rute->estimasi_jam }}"
                                            data-harga="{{ $rute->harga_base }}"
                                            {{ old('id_rute', $jadwal->id_rute) == $rute->id_rute ? 'selected' : '' }}>
                                        {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }} 
                                        ({{ $rute->jarak_km }} km, ~{{ $rute->estimasi_jam }} jam)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_rute')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($jadwal->pemesanans->count() > 0)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Mengubah rute dapat mempengaruhi pemesanan yang sudah ada
                            </small>
                            @endif
                        </div>

                        <!-- Bus Selection -->
                        <div class="mb-3">
                            <label for="id_bus" class="form-label">
                                Bus <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('id_bus') is-invalid @enderror" 
                                    id="id_bus" 
                                    name="id_bus" 
                                    required>
                                <option value="">Pilih Bus</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id_bus }}" 
                                            data-kapasitas="{{ $bus->kapasitas_kursi }}"
                                            data-kelas="{{ $bus->jenis_kelas }}"
                                            {{ old('id_bus', $jadwal->id_bus) == $bus->id_bus ? 'selected' : '' }}>
                                        {{ $bus->nama_bus }} - {{ $bus->jenis_kelas }} ({{ $bus->kapasitas_kursi }} kursi)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_bus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($jadwal->pemesanans->count() > 0)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Mengubah bus dapat mempengaruhi kursi yang sudah dipesan
                            </small>
                            @endif
                        </div>

                        <!-- Tanggal -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">
                                Tanggal Keberangkatan <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($jadwal->pemesanans->count() > 0)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Mengubah tanggal dapat menyebabkan ketidaksesuaian dengan pemesanan
                            </small>
                            @endif
                        </div>

                        <!-- Jam Berangkat & Jam Tiba -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam_berangkat" class="form-label">
                                    Jam Berangkat <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('jam_berangkat') is-invalid @enderror" 
                                       id="jam_berangkat" 
                                       name="jam_berangkat" 
                                       value="{{ old('jam_berangkat', \Carbon\Carbon::parse($jadwal->jam_berangkat)->format('H:i')) }}"
                                       required>
                                @error('jam_berangkat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jam_tiba" class="form-label">
                                    Jam Tiba <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('jam_tiba') is-invalid @enderror" 
                                       id="jam_tiba" 
                                       name="jam_tiba" 
                                       value="{{ old('jam_tiba', \Carbon\Carbon::parse($jadwal->jam_tiba)->format('H:i')) }}"
                                       required>
                                @error('jam_tiba')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="durasi-hint"></small>
                            </div>
                        </div>

                        <!-- Harga Tiket -->
                        <div class="mb-3">
                            <label for="harga_tiket" class="form-label">
                                Harga Tiket <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('harga_tiket') is-invalid @enderror" 
                                       id="harga_tiket" 
                                       name="harga_tiket" 
                                       value="{{ old('harga_tiket', $jadwal->harga_tiket) }}"
                                       min="0"
                                       step="1000"
                                       required>
                                @error('harga_tiket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($jadwal->pemesanans->where('status_pemesanan', 'confirmed')->count() > 0)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Ada {{ $jadwal->pemesanans->where('status_pemesanan', 'confirmed')->count() }} 
                                pemesanan terkonfirmasi dengan harga lama
                            </small>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status', $jadwal->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($jadwal->pemesanans->count() > 0)
                            <small class="text-warning">
                                <i class="fas fa-info-circle me-1"></i>
                                Mengubah status ke "Dibatalkan" akan mempengaruhi semua pemesanan
                            </small>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-save me-2"></i>Update Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Info -->
        <div class="col-lg-4">
            <!-- Jadwal Meta -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>Informasi Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-hashtag me-2"></i>ID</td>
                            <td class="text-end"><strong>{{ $jadwal->id_jadwal }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-clock me-2"></i>Dibuat</td>
                            <td class="text-end">{{ $jadwal->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-edit me-2"></i>Diupdate</td>
                            <td class="text-end">{{ $jadwal->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-chair me-2"></i>Total Kursi</td>
                            <td class="text-end">{{ $jadwal->kursis->count() }} kursi</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-ticket-alt me-2"></i>Pemesanan</td>
                            <td class="text-end">
                                <span class="badge bg-primary">{{ $jadwal->pemesanans->count() }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-users me-2"></i>Penumpang</td>
                            <td class="text-end">
                                {{ $jadwal->pemesanans->sum('jumlah_kursi') }} orang
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Seat Status -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-couch me-2"></i>Status Kursi
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $tersedia = $jadwal->kursis->where('status_kursi', 'tersedia')->count();
                        $terpesan = $jadwal->kursis->where('status_kursi', 'terpesan')->count();
                        $terkunci = $jadwal->kursis->where('status_kursi', 'terkunci')->count();
                        $total = $jadwal->kursis->count();
                        $occupancy = $total > 0 ? (($terpesan + $terkunci) / $total) * 100 : 0;
                    @endphp
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h5 class="mb-0 text-success">{{ $tersedia }}</h5>
                                <small class="text-muted">Tersedia</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h5 class="mb-0 text-warning">{{ $terpesan }}</h5>
                                <small class="text-muted">Terpesan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h5 class="mb-0 text-danger">{{ $terkunci }}</h5>
                                <small class="text-muted">Terkunci</small>
                            </div>
                        </div>
                    </div>

                    <label class="form-label small mb-1">Occupancy Rate</label>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar {{ $occupancy > 80 ? 'bg-danger' : ($occupancy > 50 ? 'bg-warning' : 'bg-success') }}" 
                             role="progressbar" 
                             style="width: {{ $occupancy }}%">
                            {{ number_format($occupancy, 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Perhatian
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Mengubah bus akan mengubah jumlah kursi yang tersedia</li>
                        <li>Perubahan rute atau tanggal dapat menyebabkan konflik dengan pemesanan</li>
                        <li>Perubahan harga tidak akan mempengaruhi pemesanan yang sudah ada</li>
                        <li>Mengubah status ke "Dibatalkan" akan menonaktifkan jadwal ini</li>
                        <li>Pastikan menghubungi penumpang jika ada perubahan signifikan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jamBerangkat = document.getElementById('jam_berangkat');
        const jamTiba = document.getElementById('jam_tiba');

        // Calculate duration
        function updateDurasi() {
            if (jamBerangkat.value && jamTiba.value) {
                const berangkat = new Date('2000-01-01 ' + jamBerangkat.value);
                const tiba = new Date('2000-01-01 ' + jamTiba.value);
                const diff = (tiba - berangkat) / 1000 / 60; // minutes
                
                if (diff > 0) {
                    const hours = Math.floor(diff / 60);
                    const minutes = diff % 60;
                    document.getElementById('durasi-hint').innerHTML = 
                        '<i class="fas fa-clock me-1"></i>Durasi: ' + hours + ' jam ' + minutes + ' menit';
                } else {
                    document.getElementById('durasi-hint').innerHTML = 
                        '<i class="fas fa-exclamation-triangle me-1 text-danger"></i>Jam tiba harus lebih dari jam berangkat';
                }
            }
        }

        jamBerangkat.addEventListener('change', updateDurasi);
        jamTiba.addEventListener('change', updateDurasi);
        
        // Initial calculation
        updateDurasi();
    });
</script>
@endpush
@endsection
