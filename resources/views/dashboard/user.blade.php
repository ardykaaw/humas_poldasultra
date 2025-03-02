@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="bg-light rounded-3 p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="text-end">
                <div class="fs-5 fw-semibold text-primary" id="currentDate">{{ now()->format('l, d F Y') }}</div>
                <div class="text-muted" id="currentTime">{{ now()->format('H:i:s') }} WITA</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Attendance Status Card -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-check me-2"></i>
                            Status Absensi Hari Ini
                        </h5>
                        @if($attendance)
                            <span class="badge bg-success rounded-pill px-3">
                                <i class="bi bi-check-circle me-1"></i>
                                Sudah Absen
                            </span>
                        @else
                            <span class="badge bg-warning rounded-pill px-3">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Belum Absen
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($attendance)
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <div class="border rounded-3 p-4 bg-light h-100">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                                    <i class="bi bi-box-arrow-in-right fs-4 text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <div class="text-muted small mb-1">Waktu Check In</div>
                                                <h4 class="mb-0 fw-bold">{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : 'Belum' }}</h4>
                                                @if($attendance->check_in)
                                                    <small class="text-muted">{{ date('d F Y', strtotime($attendance->check_in)) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <div class="border rounded-3 p-4 bg-light h-100">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                                    <i class="bi bi-box-arrow-right fs-4 text-success"></i>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <div class="text-muted small mb-1">Waktu Check Out</div>
                                                <h4 class="mb-0 fw-bold">{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : 'Belum' }}</h4>
                                                @if($attendance->check_out)
                                                    <small class="text-muted">{{ date('d F Y', strtotime($attendance->check_out)) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <div class="d-flex flex-column align-items-center">
                                <div style="width: 450px; height: 450px; margin-bottom: -100px; transform: scale(1.6); margin-top: -80px;">
                                    <dotlottie-player
                                        src="{{ asset('animations/police-office.lottie') }}"
                                        background="transparent"
                                        speed="1"
                                        loop
                                        autoplay>
                                    </dotlottie-player>
                                </div>
                                <div style="margin-top: 30px; position: relative; z-index: 2;">
                                    <h5 class="text-muted mb-3">Anda belum melakukan absensi hari ini</h5>
                                    <p class="text-muted small mb-4">Silakan lakukan absensi untuk memulai aktivitas kerja Anda</p>
                                    <a href="{{ route('attendance.daily') }}" class="btn btn-primary btn-lg px-4">
                                        <i class="bi bi-clock me-2"></i>
                                        Absen Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="col-md-4">
            <div class="row g-4">
                <!-- Working Hours Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history me-2"></i>
                                Jam Kerja
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center p-3 border rounded-3 bg-light mb-3">
                                <i class="bi bi-calendar-check fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-semibold">Senin - Jumat</div>
                                    <small class="text-muted">08:00 - 16:00 WITA</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center p-3 border rounded-3 bg-light">
                                <i class="bi bi-calendar-x fs-4 text-danger me-3"></i>
                                <div>
                                    <div class="fw-semibold">Sabtu - Minggu</div>
                                    <small class="text-muted">Libur</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Info Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person me-2"></i>
                                Informasi Pegawai
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">NIP</small>
                                <div class="fw-semibold">{{ auth()->user()->nip }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Pangkat</small>
                                <div class="fw-semibold">{{ auth()->user()->pangkat }}</div>
                            </div>
                            <div>
                                <small class="text-muted d-block">Jabatan</small>
                                <div class="fw-semibold">{{ auth()->user()->jabatan }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<!-- Tambahkan dotLottie player script -->
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script>

<script>
    // Update waktu secara real-time
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