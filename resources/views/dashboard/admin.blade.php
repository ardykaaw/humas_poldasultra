@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard Admin</h2>
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

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-people fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted mb-1">Total Staff</div>
                            <h3 class="mb-0">{{ $totalStaff ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted mb-1">Hadir Hari Ini</div>
                            <h3 class="mb-0">{{ $todayAttendances->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-clock-history fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted mb-1">Belum Check Out</div>
                            <h3 class="mb-0">{{ $todayAttendances->where('check_out', null)->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Absensi Hari Ini</h5>
            <button class="btn btn-sm btn-outline-primary" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-2"></i>
                Refresh Data
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
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
                        @forelse($todayAttendances as $index => $attendance)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->user->nip }}</td>
                            <td>{{ $attendance->user->pangkat }}</td>
                            <td>{{ $attendance->user->jabatan }}</td>
                            <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i:s') : '-' }}</td>
                            <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i:s') : '-' }}</td>
                            <td>{{ $attendance->location ?: '-' }}</td>
                            <td>
                                @if($attendance->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($attendance->status === 'hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->signature)
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#signatureModal{{ $attendance->id }}">
                                        <i class="bi bi-eye me-1"></i>
                                        Lihat
                                    </button>

                                    <!-- Modal Tanda Tangan -->
                                    <div class="modal fade" id="signatureModal{{ $attendance->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tanda Tangan {{ $attendance->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ $attendance->signature }}" alt="Tanda Tangan" class="img-fluid border rounded">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">Belum ada data absensi hari ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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