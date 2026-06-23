@extends('layouts.employee')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Karyawan')

@section('content')
    @php
        $todayVerificationStatus = $todayAttendance->verification_status ?? 'valid';

        if (!$todayAttendance) {
            $displayAttendanceStatus = 'Belum Check-in';
        } elseif ($todayVerificationStatus === 'ditolak') {
            $displayAttendanceStatus = 'Ditolak';
        } elseif ($todayAttendance->status === 'terlambat') {
            $displayAttendanceStatus = 'Terlambat';
        } elseif ($todayAttendance->check_out_time) {
            $displayAttendanceStatus = 'Selesai';
        } elseif ($todayAttendance->check_in_time) {
            $displayAttendanceStatus = 'Belum Check-out';
        } else {
            $displayAttendanceStatus = $attendanceStatus ?? 'Belum Check-in';
        }

        $todayCheckIn = $todayAttendance && $todayAttendance->check_in_time
            ? $todayAttendance->check_in_time . ' WIB'
            : '-';

        $todayCheckOut = $todayAttendance && $todayAttendance->check_out_time
            ? $todayAttendance->check_out_time . ' WIB'
            : '-';
    @endphp

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Dashboard Karyawan</h3>
        <p class="text-muted mb-0">
            Pantau status absensi harian dan riwayat kehadiran Anda.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning rounded-4 border-0">
            {{ session('warning') }}
        </div>
    @endif

    @if ($todayAttendance && $todayVerificationStatus === 'ditolak')
        <div class="alert alert-danger rounded-4 border-0">
            <div class="fw-bold mb-1">
                <i class="bi bi-x-circle-fill me-1"></i>
                Absensi Hari Ini Ditolak
            </div>

            <div>
                {{ $todayAttendance->rejection_reason ?? 'Absensi ditolak oleh Ketua Ruangan.' }}
            </div>

            @if ($todayAttendance->rejectedBy)
                <small class="text-muted d-block mt-2">
                    Ditolak oleh: {{ $todayAttendance->rejectedBy->name }}
                </small>
            @endif
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-xl-12">
            <div class="status-box p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-2 opacity-75">Status Absensi Hari Ini</p>
                        <h2 class="fw-bold mb-3">{{ $displayAttendanceStatus }}</h2>

                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <small class="opacity-75 d-block">Tanggal</small>
                                <strong>{{ now('Asia/Jakarta')->format('d M Y') }}</strong>
                            </div>

                            <div>
                                <small class="opacity-75 d-block">Check-in</small>
                                <strong>{{ $todayCheckIn }}</strong>
                            </div>

                            <div>
                                <small class="opacity-75 d-block">Check-out</small>
                                <strong>{{ $todayCheckOut }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-md-end mt-4 mt-md-0">
                        @if (!$todayAttendance)
                            <a href="/employee/check-in" class="btn btn-light rounded-pill px-4 py-2 fw-semibold">
                                Check-in Sekarang
                            </a>
                        @elseif ($todayVerificationStatus === 'ditolak')
                            <button class="btn btn-light rounded-pill px-4 py-2 fw-semibold" disabled>
                                Absensi Ditolak
                            </button>
                        @elseif ($todayAttendance && !$todayAttendance->check_out_time)
                            <a href="/employee/check-out" class="btn btn-light rounded-pill px-4 py-2 fw-semibold">
                                Check-out Sekarang
                            </a>
                        @else
                            <button class="btn btn-light rounded-pill px-4 py-2 fw-semibold" disabled>
                                Absensi Selesai
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Check-in Hari Ini</p>
                            <h4 class="fw-bold mb-0">{{ $todayCheckIn }}</h4>
                        </div>
                        <div class="info-icon">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Check-out Hari Ini</p>
                            <h4 class="fw-bold mb-0">{{ $todayCheckOut }}</h4>
                        </div>
                        <div class="info-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Status</p>
                            <h4 class="fw-bold mb-0">{{ $displayAttendanceStatus }}</h4>
                        </div>
                        <div class="info-icon">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card h-100">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-2">Riwayat Absensi Terbaru</h4>
                    <p class="text-muted mb-0">
                        Menampilkan beberapa riwayat absensi terakhir.
                    </p>
                </div>

                <a href="/employee/history" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-clock-history me-1"></i>
                    Lihat Riwayat
                </a>
            </div>

            {{-- Tampilan Desktop / Tablet --}}
            <div class="table-responsive employee-history-table">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentAttendances as $attendance)
                            <tr class="{{ (($attendance->verification_status ?? 'valid') === 'ditolak') || $attendance->status === 'terlambat' ? 'table-danger' : '' }}">
                                <td>
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $attendance->check_in_time ? $attendance->check_in_time . ' WIB' : '-' }}
                                </td>

                                <td>
                                    {{ $attendance->check_out_time ? $attendance->check_out_time . ' WIB' : '-' }}
                                </td>

                                <td>
                                    @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                                        <span class="badge bg-dark rounded-pill px-3 py-2">
                                            Ditolak
                                        </span>
                                    @elseif ($attendance->status === 'terlambat')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            Terlambat
                                        </span>
                                    @elseif ($attendance->check_out_time)
                                        <span class="badge badge-soft-success rounded-pill px-3 py-2">
                                            Selesai
                                        </span>
                                    @elseif ($attendance->check_in_time)
                                        <span class="badge badge-soft-warning rounded-pill px-3 py-2">
                                            Belum Check-out
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                                            Belum Check-in
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada riwayat absensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tampilan Mobile --}}
            <div class="employee-history-mobile">
                @forelse ($recentAttendances as $attendance)
                    <div class="history-mobile-item {{ (($attendance->verification_status ?? 'valid') === 'ditolak') || $attendance->status === 'terlambat' ? 'history-late' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <small class="text-muted d-block">Tanggal</small>
                                <strong>
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                </strong>
                            </div>

                            <div>
                                @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                                    <span class="badge bg-dark rounded-pill px-3 py-2">
                                        Ditolak
                                    </span>
                                @elseif ($attendance->status === 'terlambat')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        Terlambat
                                    </span>
                                @elseif ($attendance->check_out_time)
                                    <span class="badge badge-soft-success rounded-pill px-3 py-2">
                                        Selesai
                                    </span>
                                @elseif ($attendance->check_in_time)
                                    <span class="badge badge-soft-warning rounded-pill px-3 py-2">
                                        Belum Check-out
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        Belum Check-in
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="history-time-box">
                                    <small>Check-in</small>
                                    <strong>
                                        {{ $attendance->check_in_time ? $attendance->check_in_time . ' WIB' : '-' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="history-time-box">
                                    <small>Check-out</small>
                                    <strong>
                                        {{ $attendance->check_out_time ? $attendance->check_out_time . ' WIB' : '-' }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                            <div class="alert alert-danger rounded-4 border-0 mt-3 mb-0 py-2 px-3">
                                <small class="fw-semibold">
                                    {{ $attendance->rejection_reason ?? 'Absensi ditolak oleh Ketua Ruangan.' }}
                                </small>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        Belum ada riwayat absensi.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection