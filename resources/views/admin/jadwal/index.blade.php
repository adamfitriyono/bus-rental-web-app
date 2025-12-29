@extends('admin.main')

@section('title', 'Manajemen Jadwal')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i>Manajemen Jadwal
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Jadwal</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.jadwal.bulk') }}" class="btn btn-success me-2">
                <i class="fas fa-calendar-plus me-2"></i>Bulk Create
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Jadwal
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter me-2"></i>Filter Jadwal
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.jadwal.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ request('tanggal') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="rute" class="form-label">Rute</label>
                        <select class="form-select" id="rute" name="rute">
                            <option value="">Semua Rute</option>
                            @foreach($rutes as $rute)
                                <option value="{{ $rute->id_rute }}" 
                                        {{ request('rute') == $rute->id_rute ? 'selected' : '' }}>
                                    {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['tanggal', 'rute', 'status']))
                <div class="row mt-2">
                    <div class="col-12">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times me-2"></i>Reset Filter
                        </a>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Jadwal List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Jadwal</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Rute</th>
                            <th>Bus</th>
                            <th>Jam Berangkat</th>
                            <th>Jam Tiba</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</strong>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->diffForHumans() }}</small>
                            </td>
                            <td>
                                <span class="text-primary">{{ $jadwal->rute->kota_asal }}</span>
                                <i class="fas fa-arrow-right mx-1"></i>
                                <span class="text-success">{{ $jadwal->rute->kota_tujuan }}</span>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-road me-1"></i>{{ $jadwal->rute->jarak_km }} km
                                </small>
                            </td>
                            <td>
                                {{ $jadwal->bus->nama_bus }}
                                <br>
                                <small class="text-muted">{{ $jadwal->bus->jenis_kelas }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->jam_berangkat)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->jam_tiba)->format('H:i') }}</td>
                            <td>
                                <span class="badge bg-info">
                                    Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($jadwal->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($jadwal->status == 'selesai')
                                    <span class="badge bg-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.jadwal.show', $jadwal->id_jadwal) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id_jadwal) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $jadwal->id_jadwal }})"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $jadwal->id_jadwal }}" 
                                      action="{{ route('admin.jadwal.destroy', $jadwal->id_jadwal) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">
                                    @if(request()->hasAny(['tanggal', 'rute', 'status']))
                                        Tidak ada jadwal yang sesuai dengan filter
                                    @else
                                        Belum ada jadwal. Silakan tambah jadwal baru.
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($jadwals->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $jadwals->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection
