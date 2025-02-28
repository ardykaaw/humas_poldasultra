@extends('layouts.app')

@section('title', 'Absensi Harian')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Absensi Harian</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Absensi Harian</li>
                </ol>
            </nav>
        </div>
        <div class="text-end">
            <div class="fs-5 fw-semibold" id="currentDate">{{ now()->format('l, d F Y') }}</div>
            <div class="text-muted" id="currentTime">{{ now()->format('H:i:s') }} WITA</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    @if(!$attendance)
                        <div class="mb-4">
                            <h4>Generate QR Code untuk Absensi</h4>
                            <p class="text-muted">Klik tombol di bawah untuk generate QR Code absensi Anda</p>
                        </div>
                        <form action="{{ route('attendance.generate-qr') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-qr-code me-2"></i>
                                Generate QR Code
                            </button>
                        </form>
                    @else
                        <div class="mb-4">
                            <h4>Status Absensi Hari Ini</h4>
                            <div class="mt-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="text-muted mb-1">Check In</div>
                                                    <h5 class="mb-0">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i:s') : 'Belum Check In' }}</h5>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="text-muted mb-1">Check Out</div>
                                                    <h5 class="mb-0">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i:s') : 'Belum Check Out' }}</h5>
                                                </div>
                                                <div>
                                                    <div class="text-muted mb-1">Status</div>
                                                    <span class="badge bg-{{ $attendance->status === 'pending' ? 'warning' : 'success' }}">
                                                        {{ ucfirst($attendance->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($attendance->status === 'pending')
                            <div class="mb-4">
                                <div class="visible-print text-center border p-4 bg-white rounded">
                                    {!! QrCode::size(250)->margin(2)->generate(route('attendance.scan', $attendance->qr_code)) !!}
                                </div>
                                <p class="mt-3 text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Scan QR Code ini untuk melakukan absensi
                                </p>
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    QR Code hanya dapat digunakan satu kali dan akan tidak valid setelah digunakan
                                </div>
                            </div>
                        @endif

                        @if($attendance->check_in && !$attendance->check_out)
                            <form action="{{ route('attendance.check-out') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-box-arrow-left me-2"></i>
                                    Check Out
                                </button>
                            </form>
                        @endif

                        {{-- @if($attendance->status === 'hadir')
                            <form action="{{ route('attendance.reset-today') }}" method="POST" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                                    Reset Absensi Hari Ini (Testing)
                                </button>
                            </form>
                        @endif --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update waktu secara real-time
    function updateDateTime() {
        // Buat objek date dengan timezone WITA (UTC+8)
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

    // Update setiap detik
    setInterval(updateDateTime, 1000);
    updateDateTime(); // Update pertama kali
</script>
@endpush
@endsection 