@extends('layouts.leader')

@section('title', 'Data Absensi Divisi')
@section('page-title', 'Data Absensi Divisi')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Data Absensi Divisi</h3>
            <p class="text-muted mb-0">
                Menampilkan data absensi karyawan pada divisi
                <strong>{{ $leader->division->division_name ?? 'Divisi Tidak Diketahui' }}</strong>.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card compact-filter-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="compact-filter-title">
                Filter Data Absensi
            </div>

            <form method="GET" action="/leader/attendances" class="compact-filter row g-2 align-items-center">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama atau email..."
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

                <div class="col-xl-2 col-lg-4 col-md-6">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="belum_checkout" {{ request('status') == 'belum_checkout' ? 'selected' : '' }}>Belum Check-out</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>

                        <a href="/leader/attendances" class="btn btn-light flex-fill">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
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
                                        <span class="badge rounded-pill bg-dark px-3 py-2">
                                            Ditolak
                                        </span>
                                    @elseif ($attendance->status === 'terlambat')
                                        <span class="badge rounded-pill bg-danger px-3 py-2">
                                            Terlambat
                                        </span>
                                    @elseif ($attendance->check_out_time)
                                        <span class="badge rounded-pill badge-soft-success px-3 py-2">
                                            Selesai
                                        </span>
                                    @elseif ($attendance->check_in_time)
                                        <span class="badge rounded-pill badge-soft-primary px-3 py-2">
                                            Sudah Check-in
                                        </span>
                                    @else
                                        <span class="badge rounded-pill badge-soft-warning px-3 py-2">
                                            Belum Lengkap
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <a href="/leader/attendances/{{ $attendance->id }}"
                                       class="btn btn-sm btn-primary rounded-pill px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    Belum ada data absensi pada divisi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
@endsection
