@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Data Absensi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Absensi</li>
                </ol>
            </nav>
        </div>
        <div class="text-end">
            <div class="fs-5 fw-semibold">{{ now()->format('l, d F Y') }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">Data Absensi Staff</h5>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" placeholder="Cari...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $attendances->firstItem() + $index }}</td>
                            <td>{{ date('d/m/Y', strtotime($attendance->date)) }}</td>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->check_in ? date('H:i', strtotime($attendance->check_in)) : '-' }}</td>
                            <td>{{ $attendance->check_out ? date('H:i', strtotime($attendance->check_out)) : '-' }}</td>
                            <td>
                                <span class="badge bg-success">{{ $attendance->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 