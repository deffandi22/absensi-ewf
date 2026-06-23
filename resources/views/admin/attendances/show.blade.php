@extends('layouts.admin')

@section('title', 'Detail Absensi')
@section('page-title', 'Detail Absensi')

@section('content')
    @php
        $profilePhoto = $attendance->user && $attendance->user->profile_photo
            ? asset('storage/' . $attendance->user->profile_photo)
            : null;
    @endphp
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Detail Absensi</h3>
            <p class="text-muted mb-0">
                Informasi lengkap data absensi karyawan.
            </p>
        </div>

        <a href="/admin/attendances" class="btn btn-light rounded-pill px-4">
          
            Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card table-card">
                <div class="card-body p-4 text-center">
                    <div class="detail-avatar-circle">
                        @if ($profilePhoto)
                            <img src="{{ $profilePhoto }}" alt="Foto Profil Karyawan">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-1">
                        {{ $attendance->user->name ?? '-' }}
                    </h4>

                    <p class="text-muted mb-3">
                        {{ $attendance->user->employee->position ?? '-' }}
                    </p>

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
                </div>
            </div>

            <div class="card table-card mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Data Karyawan</h5>

                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">ID Karyawan</span>
                        <span class="fw-semibold text-end">
                            {{ $attendance->user->employee->id ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Nama</span>
                        <span class="fw-semibold text-end">
                            {{ $attendance->user->name ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Email</span>
                        <span class="fw-semibold text-end">
                            {{ $attendance->user->email ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-3 gap-3">
                        <span class="text-muted">Divisi</span>
                        <span class="fw-semibold text-end">
                            {{ $attendance->user->division->division_name ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between py-3 gap-3">
                        <span class="text-muted">Jabatan</span>
                        <span class="fw-semibold text-end">
                            {{ $attendance->user->employee->position ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card table-card mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Absensi</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Tanggal Absensi</small>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Waktu Check-in</small>
                            <div class="fw-semibold">
                                {{ $attendance->check_in_time ? $attendance->check_in_time . ' WIB' : '-' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Waktu Check-out</small>
                            <div class="fw-semibold">
                                {{ $attendance->check_out_time ? $attendance->check_out_time . ' WIB' : '-' }}
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <small class="text-muted d-block">Status Absensi</small>

                            <div class="mt-1">
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
                            </div>

                            @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                                <div class="alert alert-danger rounded-4 border-0 mt-3 mb-0">
                                    <div class="fw-bold mb-2">
                                        <i class="bi bi-x-circle-fill me-1"></i>
                                        Absensi Ditolak oleh Ketua Ruangan
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted d-block">Alasan Penolakan</small>
                                        <div>
                                            {{ $attendance->rejection_reason ?? 'Tidak ada alasan penolakan.' }}
                                        </div>
                                    </div>

                                    @if ($attendance->rejectedBy)
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Ditolak Oleh</small>
                                            <div>
                                                {{ $attendance->rejectedBy->name }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($attendance->rejected_at)
                                        <div>
                                            <small class="text-muted d-block">Waktu Penolakan</small>
                                            <div>
                                                {{ \Carbon\Carbon::parse($attendance->rejected_at)->format('d-m-Y H:i') }} WIB
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card table-card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Foto Check-in</h5>

                            @if ($attendance->check_in_photo)
                                <img src="{{ asset('storage/' . $attendance->check_in_photo) }}"
                                     class="img-fluid rounded-4 border mb-3"
                                     alt="Foto Check-in"
                                     style="width: 100%; height: 260px; object-fit: contain; background: #f8fafc;">
                            @else
                                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center mb-3 text-center px-3"
                                     style="height: 260px;">
                                    <span class="text-muted">Foto check-in sudah tidak tersedia.</span>
                                </div>
                            @endif

                            <small class="text-muted d-block">Koordinat Check-in</small>
                            <div class="fw-semibold mb-3">
                                {{ $attendance->check_in_latitude ?? '-' }},
                                {{ $attendance->check_in_longitude ?? '-' }}
                            </div>

                            @if ($attendance->check_in_latitude && $attendance->check_in_longitude)
                                <a href="https://www.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}"
                                   target="_blank"
                                   class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Lihat Lokasi Check-in
                                </a>
                            @else
                                <button class="btn btn-light rounded-pill px-4" disabled>
                                    Lokasi Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card table-card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Foto Check-out</h5>

                            @if ($attendance->check_out_photo)
                                <img src="{{ asset('storage/' . $attendance->check_out_photo) }}"
                                     class="img-fluid rounded-4 border mb-3"
                                     alt="Foto Check-out"
                                     style="width: 100%; height: 260px; object-fit: contain; background: #f8fafc;">
                            @else
                                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center mb-3 text-center px-3"
                                     style="height: 260px;">
                                    <span class="text-muted">Foto check-out belum tersedia atau sudah dihapus.</span>
                                </div>
                            @endif

                            <small class="text-muted d-block">Koordinat Check-out</small>
                            <div class="fw-semibold mb-3">
                                {{ $attendance->check_out_latitude ?? '-' }},
                                {{ $attendance->check_out_longitude ?? '-' }}
                            </div>

                            @if ($attendance->check_out_latitude && $attendance->check_out_longitude)
                                <a href="https://www.google.com/maps?q={{ $attendance->check_out_latitude }},{{ $attendance->check_out_longitude }}"
                                   target="_blank"
                                   class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Lihat Lokasi Check-out
                                </a>
                            @else
                                <button class="btn btn-light rounded-pill px-4" disabled>
                                    Lokasi Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (($attendance->verification_status ?? 'valid') === 'ditolak')
                <div class="alert alert-danger rounded-4 border-0 mt-4">
                    Absensi ini telah <strong>ditolak oleh Ketua Ruangan</strong> karena data absensi dianggap tidak sesuai atau tidak valid.
                </div>
            @elseif ($attendance->status === 'terlambat')
                <div class="alert alert-danger rounded-4 border-0 mt-4">
                    Karyawan ini melakukan check-in melewati batas waktu yang ditentukan, sehingga status absensi ditandai sebagai <strong>Terlambat</strong>.
                </div>
            @endif
        </div>
    </div>
@endsection