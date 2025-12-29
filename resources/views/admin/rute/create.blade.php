@extends('admin.main')

@section('title', 'Tambah Rute Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-route me-2"></i>Tambah Rute Baru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.rute.index') }}">Rute</a></li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Form Tambah Rute</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.rute.store') }}" method="POST">
                        @csrf
                        
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
                                       value="{{ old('kota_asal') }}"
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
                                       value="{{ old('kota_tujuan') }}"
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
                                           value="{{ old('jarak_km') }}"
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
                                           value="{{ old('estimasi_jam') }}"
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
                                           value="{{ old('harga_base') }}"
                                           min="0"
                                           step="1000"
                                           placeholder="Contoh: 50000"
                                           required>
                                    @error('harga_base')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Harga dasar untuk rute ini (bisa disesuaikan di jadwal)</small>
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
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
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
                                    <strong>Kecepatan rata-rata:</strong> <span id="avg-speed">-</span> km/jam
                                </p>
                                <p class="mb-0">
                                    <strong>Harga per KM:</strong> Rp <span id="price-per-km">-</span>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.rute.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Rute
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>Panduan Pengisian
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Informasi Field:</h6>
                    <ul class="small">
                        <li><strong>Kota Asal:</strong> Kota keberangkatan bus</li>
                        <li><strong>Kota Tujuan:</strong> Kota tujuan perjalanan</li>
                        <li><strong>Jarak (KM):</strong> Jarak tempuh dalam kilometer</li>
                        <li><strong>Estimasi Jam:</strong> Perkiraan waktu perjalanan</li>
                        <li><strong>Harga Base:</strong> Harga dasar tiket (dapat disesuaikan per jadwal)</li>
                        <li><strong>Status:</strong> Aktif jika rute dapat dijadwalkan</li>
                    </ul>
                    
                    <div class="alert alert-warning mt-3">
                        <small>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Field yang bertanda <span class="text-danger">*</span> wajib diisi
                        </small>
                    </div>
                </div>
            </div>

            <!-- Example Routes -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-lightbulb me-2"></i>Contoh Rute
                    </h6>
                </div>
                <div class="card-body">
                    <small>
                        <p class="mb-2"><strong>Jepara - Semarang</strong></p>
                        <ul class="mb-3">
                            <li>Jarak: 85 km</li>
                            <li>Waktu: 2 jam</li>
                            <li>Harga: Rp 50.000</li>
                        </ul>

                        <p class="mb-2"><strong>Jepara - Kudus</strong></p>
                        <ul class="mb-3">
                            <li>Jarak: 35 km</li>
                            <li>Waktu: 1 jam</li>
                            <li>Harga: Rp 25.000</li>
                        </ul>

                        <p class="mb-2"><strong>Semarang - Surabaya</strong></p>
                        <ul class="mb-0">
                            <li>Jarak: 325 km</li>
                            <li>Waktu: 6 jam</li>
                            <li>Harga: Rp 150.000</li>
                        </ul>
                    </small>
                </div>
            </div>
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
                document.getElementById('price-per-km').textContent = pricePerKm.toLocaleString('id-ID');
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
