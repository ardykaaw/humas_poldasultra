@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="text-end">
            <div class="fs-5 fw-semibold">{{ now()->format('l, d F Y') }}</div>
            <div class="text-muted">{{ now()->format('H:i') }} WITA</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Absensi Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if($attendance)
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-light">
                                    <div class="text-muted mb-2">Check In</div>
                                    <h4 class="mb-0">{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : 'Belum Check In' }}</h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 border rounded bg-light">
                                    <div class="text-muted mb-2">Check Out</div>
                                    <h4 class="mb-0">{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : 'Belum Check Out' }}</h4>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-4 border rounded bg-light">
                                    <div class="text-muted mb-2">Status</div>
                                    <h4 class="mb-0">
                                        <span class="badge bg-success">{{ $attendance->status }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">Anda belum melakukan absensi hari ini</div>
                            <a href="{{ route('attendance.daily') }}" class="btn btn-primary">
                                <i class="bi bi-clock me-2"></i>
                                Absen Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 