@extends('admin.main')

@section('title', 'Manajemen Rute')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-route me-2"></i>Manajemen Rute
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rute</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.rute.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Rute Baru
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Rute List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Rute Perjalanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="ruteTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kota Asal</th>
                            <th></th>
                            <th>Kota Tujuan</th>
                            <th>Jarak</th>
                            <th>Estimasi Waktu</th>
                            <th>Harga Base</th>
                            <th>Status</th>
                            <th>Jadwal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rutes as $index => $rute)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-primary">{{ $rute->kota_asal }}</strong>
                            </td>
                            <td class="text-center">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </td>
                            <td>
                                <strong class="text-success">{{ $rute->kota_tujuan }}</strong>
                            </td>
                            <td>
                                <i class="fas fa-road me-1"></i>{{ $rute->jarak_km }} km
                            </td>
                            <td>
                                <i class="fas fa-clock me-1"></i>
                                {{ $rute->estimasi_jam }} jam
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    Rp {{ number_format($rute->harga_base, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($rute->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-aktif</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $rute->jadwals->count() }} jadwal
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.rute.show', $rute->id_rute) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.rute.edit', $rute->id_rute) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $rute->id_rute }})"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $rute->id_rute }}" 
                                      action="{{ route('admin.rute.destroy', $rute->id_rute) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-route fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data rute. Silakan tambah rute baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Rute
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rutes->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-route fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Rute Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $rutes->where('status', 'aktif')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Jadwal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $rutes->sum(function($rute) { return $rute->jadwals->count(); }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Jarak
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($rutes->sum('jarak_km'), 0, ',', '.') }} km
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-road fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Routes -->
    @if($rutes->count() > 0)
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-fire me-2"></i>Rute Populer (Berdasarkan Jumlah Jadwal)
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($rutes->sortByDesc(function($rute) { return $rute->jadwals->count(); })->take(5) as $rute)
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3">
                        <h6 class="mb-2">
                            <span class="text-primary">{{ $rute->kota_asal }}</span>
                            <i class="fas fa-arrow-right mx-2"></i>
                            <span class="text-success">{{ $rute->kota_tujuan }}</span>
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar-check me-1"></i>{{ $rute->jadwals->count() }} jadwal |
                            <i class="fas fa-road me-1 ms-2"></i>{{ $rute->jarak_km }} km |
                            <i class="fas fa-clock me-1 ms-2"></i>{{ $rute->estimasi_jam }}h
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus rute ini? Semua jadwal terkait juga akan terhapus.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Initialize DataTable if available
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof simpleDatatables !== 'undefined') {
            const table = document.getElementById('ruteTable');
            if (table) {
                new simpleDatatables.DataTable(table, {
                    searchable: true,
                    fixedHeight: false,
                    perPage: 10
                });
            }
        }
    });
</script>
@endpush
@endsection
