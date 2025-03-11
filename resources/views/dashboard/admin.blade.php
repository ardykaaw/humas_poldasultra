@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Header section -->
    <div class="bg-light rounded-3 p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 text-primary">Dashboard Admin</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="text-end">
                <div class="fs-5 fw-semibold" id="currentDate">{{ now()->format('l, d F Y') }}</div>
                <div class="text-muted" id="currentTime">{{ now()->format('H:i:s') }} WITA</div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-people fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Total Staff</div>
                            <h3 class="mb-0 fw-bold">{{ $totalStaff ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-check-circle fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Hadir Hari Ini</div>
                            <h3 class="mb-0 fw-bold">{{ $todayAttendances->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-clock-history fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small mb-1">Belum Check Out</div>
                            <h3 class="mb-0 fw-bold">{{ $todayAttendances->where('check_out', null)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px;" class="me-3">
                        <dotlottie-player
                            src="{{ asset('animations/data-analysis.lottie') }}"
                            background="transparent"
                            speed="1"
                            loop
                            autoplay>
                        </dotlottie-player>
                    </div>
                    <h5 class="card-title mb-0">Absensi Hari Ini</h5>
                </div>
                <button class="btn btn-sm btn-primary" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>
                    Refresh Data
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($todayAttendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NRP</th>
                                <th>Pangkat</th>
                                <th>Jabatan</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Tanda Tangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayAttendances as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $attendance->user->name }}</td>
                                <td><span class="text-muted">{{ $attendance->user->nip }}</span></td>
                                <td>{{ $attendance->user->pangkat }}</td>
                                <td>{{ $attendance->user->jabatan }}</td>
                                <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i:s') : '-' }}</td>
                                <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i:s') : '-' }}</td>
                                <td>{{ $attendance->location ?: '-' }}</td>
                                <td>
                                    @if($attendance->status === 'pending')
                                        <span class="badge bg-warning rounded-pill">Pending</span>
                                    @elseif($attendance->status === 'hadir')
                                        <span class="badge bg-success rounded-pill">Hadir</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->signature)
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#signatureModal{{ $attendance->id }}">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat
                                        </button>

                                        <!-- Modal Tanda Tangan -->
                                        <div class="modal fade" id="signatureModal{{ $attendance->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-bottom-0">
                                                        <h5 class="modal-title">Tanda Tangan {{ $attendance->user->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-4">
                                                        <img src="{{ $attendance->signature }}" alt="Tanda Tangan" class="img-fluid border rounded shadow-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div style="width: 200px; height: 200px; margin: 0 auto;">
                        <dotlottie-player
                            src="{{ asset('Animations/empty-data.lottie') }}"
                            background="transparent"
                            speed="1"
                            loop
                            autoplay>
                        </dotlottie-player>
                    </div>
                    <h5 class="text-muted mt-3">Belum ada data absensi hari ini</h5>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endpush

@push('scripts')
<!-- Tambahkan dotLottie player script -->
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script>

<!-- Existing scripts -->
<script>
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            timeZone: 'Asia/Makassar'
        };
        
        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: 'Asia/Makassar'
        };
        
        document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', timeOptions) + ' WITA';
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
@endpush
@endsection 