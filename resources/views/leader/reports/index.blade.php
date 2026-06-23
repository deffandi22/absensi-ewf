@extends('layouts.leader')

@section('title', 'Laporan Absensi Divisi')
@section('page-title', 'Laporan Absensi Divisi')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Laporan Absensi Divisi</h3>
            <p class="text-muted mb-0">
                Menampilkan laporan absensi karyawan pada divisi
                <strong>{{ $leader->division->division_name ?? 'Divisi Tidak Diketahui' }}</strong>.
            </p>
        </div>
    </div>

    <div class="card compact-filter-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="compact-filter-title">
                Filter Laporan Absensi Divisi
            </div>

            <form method="GET" action="/leader/reports" class="compact-filter row g-2 align-items-center">
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <input type="number"
                           name="employee_id"
                           class="form-control"
                           placeholder="ID Karyawan"
                           value="{{ request('employee_id') }}">
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Nama/email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12">
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

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="belum_checkout" {{ request('status') == 'belum_checkout' ? 'selected' : '' }}>Belum Check-out</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>

                        <a href="/leader/reports" class="btn btn-light">
                            Reset
                        </a>

                        <a href="{{ url('/leader/reports/print') . '?' . http_build_query(request()->query()) }}"
                           target="_blank"
                           class="btn btn-primary">
                            <i class="bi bi-printer me-1"></i>
                            Cetak
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Data Laporan Absensi Divisi</h5>
                    <p class="text-muted mb-0">
                        Total data ditemukan: {{ $attendances->total() }}
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Tanggal</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr class="{{ (($attendance->verification_status ?? 'valid') === 'ditolak') || $attendance->status === 'terlambat' ? 'table-danger' : '' }}">
                                <td class="fw-semibold">
                                    {{ $attendance->user->employee->id ?? '-' }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $attendance->user->name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $attendance->user->email ?? '-' }}
                                    </small>
                                </td>

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

                                        @if ($attendance->rejectedBy)
                                            <small class="text-muted d-block">
                                                Oleh: {{ $attendance->rejectedBy->name }}
                                            </small>
                                        @endif

                                        @if ($attendance->rejected_at)
                                            <small class="text-muted d-block">
                                                {{ \Carbon\Carbon::parse($attendance->rejected_at)->format('d-m-Y H:i') }} WIB
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    Tidak ada data laporan absensi pada divisi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
@endsection