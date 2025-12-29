@extends('admin.main')

@section('title', 'Tambah Bus Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bus me-2"></i>Tambah Bus Baru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.bus.index') }}">Bus</a></li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Form Tambah Bus</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bus.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Nama Bus -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_bus" class="form-label">
                                    Nama Bus <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nama_bus') is-invalid @enderror" 
                                       id="nama_bus" 
                                       name="nama_bus" 
                                       value="{{ old('nama_bus') }}"
                                       placeholder="Contoh: Kalingga Jaya 001"
                                       required>
                                @error('nama_bus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Plat Nomor -->
                            <div class="col-md-6 mb-3">
                                <label for="plat_nomor" class="form-label">
                                    Plat Nomor <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('plat_nomor') is-invalid @enderror" 
                                       id="plat_nomor" 
                                       name="plat_nomor" 
                                       value="{{ old('plat_nomor') }}"
                                       placeholder="Contoh: K 1234 AB"
                                       required>
                                @error('plat_nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jenis Kelas -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelas" class="form-label">
                                    Jenis Kelas <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('jenis_kelas') is-invalid @enderror" 
                                        id="jenis_kelas" 
                                        name="jenis_kelas" 
                                        required>
                                    <option value="">Pilih Jenis Kelas</option>
                                    <option value="Ekonomi" {{ old('jenis_kelas') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                                    <option value="Bisnis" {{ old('jenis_kelas') == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                                    <option value="Eksekutif" {{ old('jenis_kelas') == 'Eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                                    <option value="VIP" {{ old('jenis_kelas') == 'VIP' ? 'selected' : '' }}>VIP</option>
                                    <option value="Super Executive" {{ old('jenis_kelas') == 'Super Executive' ? 'selected' : '' }}>Super Executive</option>
                                </select>
                                @error('jenis_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kapasitas Kursi -->
                            <div class="col-md-6 mb-3">
                                <label for="kapasitas_kursi" class="form-label">
                                    Kapasitas Kursi <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('kapasitas_kursi') is-invalid @enderror" 
                                       id="kapasitas_kursi" 
                                       name="kapasitas_kursi" 
                                       value="{{ old('kapasitas_kursi') }}"
                                       min="1"
                                       placeholder="Contoh: 40"
                                       required>
                                <small class="text-muted">Total jumlah kursi penumpang</small>
                                @error('kapasitas_kursi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tahun Pembuatan -->
                            <div class="col-md-6 mb-3">
                                <label for="tahun_pembuatan" class="form-label">
                                    Tahun Pembuatan
                                </label>
                                <input type="number" 
                                       class="form-control @error('tahun_pembuatan') is-invalid @enderror" 
                                       id="tahun_pembuatan" 
                                       name="tahun_pembuatan" 
                                       value="{{ old('tahun_pembuatan') }}"
                                       min="1900"
                                       max="{{ date('Y') }}"
                                       placeholder="Contoh: {{ date('Y') }}">
                                @error('tahun_pembuatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            </div>
                        </div>

                        <!-- Fasilitas -->
                        <div class="mb-3">
                            <label for="fasilitas" class="form-label">
                                Fasilitas
                            </label>
                            <textarea class="form-control @error('fasilitas') is-invalid @enderror" 
                                      id="fasilitas" 
                                      name="fasilitas" 
                                      rows="4"
                                      placeholder="Contoh: AC, WiFi, Charger, Toilet, Reclining Seat, Entertainment System">{{ old('fasilitas') }}</textarea>
                            <small class="text-muted">Pisahkan dengan koma (,) untuk setiap fasilitas</small>
                            @error('fasilitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.bus.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Bus
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
                        <i class="fas fa-info-circle me-2"></i>Informasi
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Panduan Pengisian:</h6>
                    <ul class="small">
                        <li><strong>Nama Bus:</strong> Nama identitas bus (contoh: Kalingga Jaya 001)</li>
                        <li><strong>Plat Nomor:</strong> Nomor plat kendaraan, harus unik</li>
                        <li><strong>Jenis Kelas:</strong> Kategori kelas bus sesuai fasilitas</li>
                        <li><strong>Kapasitas Kursi:</strong> Total kursi penumpang yang tersedia</li>
                        <li><strong>Tahun Pembuatan:</strong> Tahun produksi bus</li>
                        <li><strong>Fasilitas:</strong> Daftar fasilitas yang tersedia di bus</li>
                        <li><strong>Status:</strong> Aktif (dapat dijadwalkan) atau Non-aktif</li>
                    </ul>
                    
                    <div class="alert alert-warning mt-3">
                        <small>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Field yang bertanda <span class="text-danger">*</span> wajib diisi
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
