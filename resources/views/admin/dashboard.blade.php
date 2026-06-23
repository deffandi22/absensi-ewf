@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Dashboard Admin</h3>
        <p class="text-muted mb-0">
            Ringkasan data absensi karyawan PT. Equity World Futures Surabaya.
        </p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <p class="text-muted mb-1">Total Karyawan</p>
                        <h3 class="fw-bold mb-0">{{ $totalEmployees }}</h3>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <p class="text-muted mb-1">Total Divisi</p>
                        <h3 class="fw-bold mb-0">{{ $totalDivisions }}</h3>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <p class="text-muted mb-1">Absensi Hari Ini</p>
                        <h3 class="fw-bold mb-0">{{ $todayAttendances }}</h3>
                    </div>
                    <div class="stat-icon icon-yellow">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <p class="text-muted mb-1">Belum Absen</p>
                        <h3 class="fw-bold mb-0">{{ $notYetAttendances }}</h3>
                    </div>
                    <div class="stat-icon icon-red">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <p class="text-muted mb-1">Absen Ditolak</p>
                        <h3 class="fw-bold mb-0">{{ $rejectedAttendances }}</h3>
                    </div>
                    <div class="stat-icon icon-purple">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card table-card h-100">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Absensi Terbaru</h5>
                        <p class="text-muted mb-2 small">Data absensi terakhir yang masuk ke sistem.</p>
                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID Karyawan</th>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th>Tanggal</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAttendances as $attendance)
                                    <tr class="{{ (($attendance->verification_status ?? 'valid') === 'ditolak') || $attendance->status === 'terlambat' ? 'table-danger' : '' }}">
                                        <td class="fw-semibold">{{ $attendance->user->employee->id ?? '-' }}</td>
                                        <td class="fw-semibold">{{ $attendance->user->name ?? '-' }}</td>
                                        <td>{{ $attendance->user->division->division_name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                                        <td>{{ $attendance->check_in_time ? $attendance->check_in_time . ' WIB' : '-' }}</td>
                                        <td>{{ $attendance->check_out_time ? $attendance->check_out_time . ' WIB' : '-' }}</td>
                                        <td>
                                            @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                                                <span class="badge rounded-pill bg-dark px-3 py-2">Ditolak</span>
                                            @elseif ($attendance->status === 'terlambat')
                                                <span class="badge rounded-pill bg-danger px-3 py-2">Terlambat</span>
                                            @elseif ($attendance->check_out_time)
                                                <span class="badge rounded-pill badge-soft-success px-3 py-2">Selesai</span>
                                            @elseif ($attendance->check_in_time)
                                                <span class="badge rounded-pill badge-soft-primary px-3 py-2">Sudah Check-in</span>
                                            @else
                                                <span class="badge rounded-pill badge-soft-warning px-3 py-2">Belum Lengkap</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">Belum ada data absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card table-card mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Sistem</h5>
                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Role Aktif</span>
                        <span class="fw-semibold text-end">Admin</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Tanggal</span>
                        <span class="fw-semibold text-end">{{ now('Asia/Jakarta')->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-3 gap-3">
                        <span class="text-muted">Status Sistem</span>
                        <span class="badge badge-soft-success rounded-pill px-3 py-2">Aktif</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #1d4ed8, #0f172a);">
                <div class="card-body p-4 text-white">
                    <h5 class="fw-bold mb-2">Sistem Absensi Web</h5>
                    <p class="mb-4 opacity-75">Dashboard ini digunakan untuk memantau data karyawan, divisi, dan absensi secara terstruktur.</p>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check"></i>
                        <small>Validasi role dan jaringan aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
