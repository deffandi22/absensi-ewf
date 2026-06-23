@extends('layouts.employee')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')

@section('content')
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Riwayat Absensi</h3>
        <p class="text-muted mb-0">
            Menampilkan riwayat absensi pribadi karyawan.
        </p>
    </div>

    <div class="card compact-filter-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="compact-filter-title">
                Filter Riwayat Absensi
            </div>

            <form method="GET" action="/employee/history" class="compact-filter row g-2 align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="date-range-group">
                        <div class="date-input-wrap">
                            <input type="date"
                                   name="start_date"
                                   class="form-control"
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="date-range-separator">s/d</div>
                        <div class="date-input-wrap">
                            <input type="date"
                                   name="end_date"
                                   class="form-control"
                                   value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="belum_checkout" {{ request('status') == 'belum_checkout' ? 'selected' : '' }}>Belum Check-out</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>

                        <a href="/employee/history" class="btn btn-outline-primary w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-1">Data Riwayat Absensi</h5>
                <p class="text-muted mb-0">
                    Total data ditemukan: {{ $attendances->total() }}
                </p>
            </div>

            <div class="table-responsive employee-history-table">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($attendances as $attendance)
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

                                <td>
                                    @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                                        <div class="fw-semibold text-danger">
                                            {{ $attendance->rejection_reason ?? 'Absensi ditolak' }}
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="/employee/history/{{ $attendance->id }}"
                                       class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    Belum ada riwayat absensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="employee-history-mobile p-3">
                @forelse ($attendances as $attendance)
                    <div class="history-mobile-item {{ (($attendance->verification_status ?? 'valid') === 'ditolak') || $attendance->status === 'terlambat' ? 'history-late' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <div>
                                <div class="fw-bold">
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    Riwayat absensi harian
                                </small>
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
                                    {{ $attendance->rejection_reason ?? 'Absensi ditolak' }}
                                </small>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="/employee/history/{{ $attendance->id }}"
                               class="btn btn-primary rounded-pill w-100">
                                <i class="bi bi-eye me-1"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        Belum ada riwayat absensi.
                    </div>
                @endforelse
            </div>

            <div class="p-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
@endsection