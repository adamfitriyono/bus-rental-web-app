@extends('admin.main')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-plus me-2"></i>Tambah Jadwal Baru
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-edit me-2"></i>Form Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jadwal.store') }}" method="POST" id="jadwalForm">
                        @csrf

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
                                            {{ old('id_rute') == $rute->id_rute ? 'selected' : '' }}>
                                        {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }} 
                                        ({{ $rute->jarak_km }} km, ~{{ $rute->estimasi_jam }} jam)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_rute')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                            {{ old('id_bus') == $bus->id_bus ? 'selected' : '' }}>
                                        {{ $bus->nama_bus }} - {{ $bus->jenis_kelas }} ({{ $bus->kapasitas_kursi }} kursi)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_bus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Hanya bus dengan status aktif yang ditampilkan
                            </small>
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
                                   value="{{ old('tanggal') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>Minimal hari ini
                            </small>
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
                                       value="{{ old('jam_berangkat') }}"
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
                                       value="{{ old('jam_tiba') }}"
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
                                       value="{{ old('harga_tiket') }}"
                                       min="0"
                                       step="1000"
                                       placeholder="0"
                                       required>
                                @error('harga_tiket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted" id="harga-hint">
                                <i class="fas fa-lightbulb me-1"></i>Pilih rute untuk melihat harga base
                            </small>
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
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Info Card -->
        <div class="col-lg-4">
            <!-- Route Preview Card -->
            <div class="card shadow-sm mb-3" id="route-preview" style="display: none;">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-route me-2"></i>Preview Rute
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-map-marker-alt text-primary me-2"></i>Asal</td>
                            <td class="text-end"><strong id="preview-asal">-</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-map-marker-alt text-success me-2"></i>Tujuan</td>
                            <td class="text-end"><strong id="preview-tujuan">-</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-road me-2"></i>Jarak</td>
                            <td class="text-end"><span id="preview-jarak">-</span> km</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-clock me-2"></i>Estimasi</td>
                            <td class="text-end"><span id="preview-estimasi">-</span> jam</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-tag me-2"></i>Harga Base</td>
                            <td class="text-end">Rp <span id="preview-harga">-</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Bus Preview Card -->
            <div class="card shadow-sm mb-3" id="bus-preview" style="display: none;">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bus me-2"></i>Preview Bus
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-bus me-2"></i>Nama</td>
                            <td class="text-end"><strong id="preview-bus-nama">-</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-chair me-2"></i>Kapasitas</td>
                            <td class="text-end"><span id="preview-kapasitas">-</span> kursi</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-star me-2"></i>Kelas</td>
                            <td class="text-end"><span id="preview-kelas" class="badge bg-info">-</span></td>
                        </tr>
                    </table>
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Kursi akan dibuat otomatis saat jadwal disimpan
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-question-circle me-2"></i>Panduan
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Pilih rute dan bus yang aktif</li>
                        <li>Tanggal minimal hari ini</li>
                        <li>Jam tiba harus lebih dari jam berangkat</li>
                        <li>Harga tiket akan diisi otomatis dari harga base rute</li>
                        <li>Kursi akan dibuat otomatis sesuai kapasitas bus</li>
                        <li>Pastikan tidak ada jadwal duplikat untuk rute, bus, dan tanggal yang sama</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ruteSelect = document.getElementById('id_rute');
        const busSelect = document.getElementById('id_bus');
        const hargaInput = document.getElementById('harga_tiket');
        const jamBerangkat = document.getElementById('jam_berangkat');
        const jamTiba = document.getElementById('jam_tiba');

        // Preview rute when selected
        ruteSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const preview = document.getElementById('route-preview');
            
            if (option.value) {
                const [asal, tujuan] = option.text.split(' → ');
                document.getElementById('preview-asal').textContent = asal;
                document.getElementById('preview-tujuan').textContent = tujuan.split(' (')[0];
                document.getElementById('preview-jarak').textContent = option.dataset.jarak;
                document.getElementById('preview-estimasi').textContent = option.dataset.estimasi;
                document.getElementById('preview-harga').textContent = parseInt(option.dataset.harga).toLocaleString('id-ID');
                
                // Auto-fill harga if empty
                if (!hargaInput.value || hargaInput.value == 0) {
                    hargaInput.value = option.dataset.harga;
                }
                
                document.getElementById('harga-hint').innerHTML = 
                    '<i class="fas fa-lightbulb me-1"></i>Harga base: Rp ' + 
                    parseInt(option.dataset.harga).toLocaleString('id-ID');
                
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });

        // Preview bus when selected
        busSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const preview = document.getElementById('bus-preview');
            
            if (option.value) {
                document.getElementById('preview-bus-nama').textContent = option.text.split(' - ')[0];
                document.getElementById('preview-kapasitas').textContent = option.dataset.kapasitas;
                document.getElementById('preview-kelas').textContent = option.dataset.kelas;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });

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

        // Trigger preview if form has old values
        if (ruteSelect.value) {
            ruteSelect.dispatchEvent(new Event('change'));
        }
        if (busSelect.value) {
            busSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection
