@extends('admin.main')

@section('title', 'Edit Rute')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Edit Rute
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.rute.index') }}">Rute</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Form Edit Rute</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.rute.update', $rute->id_rute) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Kota Asal -->
                            <div class="col-md-6 mb-3">
                                <label for="kota_asal" class="form-label">
                                    Kota Asal <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('kota_asal') is-invalid @enderror" 
                                       id="kota_asal" 
                                       name="kota_asal" 
                                       value="{{ old('kota_asal', $rute->kota_asal) }}"
                                       placeholder="Contoh: Jepara"
                                       required>
                                @error('kota_asal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kota keberangkatan</small>
                            </div>

                            <!-- Kota Tujuan -->
                            <div class="col-md-6 mb-3">
                                <label for="kota_tujuan" class="form-label">
                                    Kota Tujuan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('kota_tujuan') is-invalid @enderror" 
                                       id="kota_tujuan" 
                                       name="kota_tujuan" 
                                       value="{{ old('kota_tujuan', $rute->kota_tujuan) }}"
                                       placeholder="Contoh: Semarang"
                                       required>
                                @error('kota_tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kota tujuan perjalanan</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jarak KM -->
                            <div class="col-md-6 mb-3">
                                <label for="jarak_km" class="form-label">
                                    Jarak (KM) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('jarak_km') is-invalid @enderror" 
                                           id="jarak_km" 
                                           name="jarak_km" 
                                           value="{{ old('jarak_km', $rute->jarak_km) }}"
                                           min="1"
                                           step="0.1"
                                           placeholder="Contoh: 85"
                                           required>
                                    <span class="input-group-text">km</span>
                                    @error('jarak_km')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Jarak tempuh perjalanan</small>
                            </div>

                            <!-- Estimasi Jam -->
                            <div class="col-md-6 mb-3">
                                <label for="estimasi_jam" class="form-label">
                                    Estimasi Waktu (Jam) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('estimasi_jam') is-invalid @enderror" 
                                           id="estimasi_jam" 
                                           name="estimasi_jam" 
                                           value="{{ old('estimasi_jam', $rute->estimasi_jam) }}"
                                           min="1"
                                           step="0.5"
                                           placeholder="Contoh: 2"
                                           required>
                                    <span class="input-group-text">jam</span>
                                    @error('estimasi_jam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Perkiraan waktu perjalanan</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Harga Base -->
                            <div class="col-md-6 mb-3">
                                <label for="harga_base" class="form-label">
                                    Harga Base <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('harga_base') is-invalid @enderror" 
                                           id="harga_base" 
                                           name="harga_base" 
                                           value="{{ old('harga_base', $rute->harga_base) }}"
                                           min="0"
                                           step="1000"
                                           placeholder="Contoh: 50000"
                                           required>
                                    @error('harga_base')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga dasar untuk rute ini</small>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="aktif" {{ old('status', $rute->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $rute->status) == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Aktif = dapat dijadwalkan</small>
                            </div>
                        </div>

                        <!-- Calculate Info -->
                        <div class="alert alert-info mt-3">
                            <h6 class="alert-heading">
                                <i class="fas fa-calculator me-2"></i>Perhitungan Otomatis
                            </h6>
                            <div id="calculation-result">
                                <p class="mb-2">
                                    <strong>Kecepatan rata-rata:</strong> <span id="avg-speed">{{ $rute->jarak_km && $rute->estimasi_jam ? round($rute->jarak_km / $rute->estimasi_jam, 2) : '-' }}</span> km/jam
                                </p>
                                <p class="mb-0">
                                    <strong>Harga per KM:</strong> Rp <span id="price-per-km">{{ $rute->jarak_km && $rute->harga_base ? number_format($rute->harga_base / $rute->jarak_km, 0, ',', '.') : '-' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.rute.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                <a href="{{ route('admin.rute.show', $rute->id_rute) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                </a>
                                <button type="submit" class="btn btn-warning text-white">
                                    <i class="fas fa-save me-2"></i>Update Rute
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>Informasi Rute
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID Rute:</strong></td>
                            <td>{{ $rute->id_rute }}</td>
                        </tr>
                        <tr>
                            <td><strong>Rute:</strong></td>
                            <td>
                                <span class="text-primary">{{ $rute->kota_asal }}</span>
                                <i class="fas fa-arrow-right mx-1"></i>
                                <span class="text-success">{{ $rute->kota_tujuan }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $rute->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Update Terakhir:</strong></td>
                            <td>{{ $rute->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Jadwal:</strong></td>
                            <td>{{ $rute->jadwals->count() }} jadwal</td>
                        </tr>
                    </table>
                    
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tips:</strong> Perubahan harga base akan diterapkan pada jadwal baru yang dibuat setelahnya.
                        </small>
                    </div>
                </div>
            </div>

            @if($rute->jadwals->count() > 0)
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Peringatan
                    </h6>
                </div>
                <div class="card-body">
                    <small>
                        Rute ini memiliki {{ $rute->jadwals->count() }} jadwal terkait. 
                        Perubahan pada rute akan mempengaruhi tampilan informasi di jadwal-jadwal tersebut.
                    </small>
                    
                    <div class="mt-3">
                        <h6 class="small"><strong>Jadwal Aktif:</strong></h6>
                        <ul class="small mb-0">
                            @foreach($rute->jadwals->where('status', 'aktif')->take(3) as $jadwal)
                            <li>
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($jadwal->jam_berangkat)->format('H:i') }}
                                ({{ $jadwal->bus->nama_bus }})
                            </li>
                            @endforeach
                            @if($rute->jadwals->where('status', 'aktif')->count() > 3)
                            <li class="text-muted">+ {{ $rute->jadwals->where('status', 'aktif')->count() - 3 }} jadwal lainnya</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Calculate average speed and price per km
    document.addEventListener('DOMContentLoaded', function() {
        const jarakInput = document.getElementById('jarak_km');
        const waktuInput = document.getElementById('estimasi_jam');
        const hargaInput = document.getElementById('harga_base');
        
        function calculate() {
            const jarak = parseFloat(jarakInput.value) || 0;
            const waktu = parseFloat(waktuInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            
            if (jarak > 0 && waktu > 0) {
                const avgSpeed = (jarak / waktu).toFixed(2);
                document.getElementById('avg-speed').textContent = avgSpeed;
            } else {
                document.getElementById('avg-speed').textContent = '-';
            }
            
            if (jarak > 0 && harga > 0) {
                const pricePerKm = (harga / jarak).toFixed(0);
                document.getElementById('price-per-km').textContent = Number(pricePerKm).toLocaleString('id-ID');
            } else {
                document.getElementById('price-per-km').textContent = '-';
            }
        }
        
        jarakInput.addEventListener('input', calculate);
        waktuInput.addEventListener('input', calculate);
        hargaInput.addEventListener('input', calculate);
    });
</script>
@endpush
@endsection
