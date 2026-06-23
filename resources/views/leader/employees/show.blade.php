@extends('layouts.leader')

@section('title', 'Detail Karyawan Divisi')
@section('page-title', 'Detail Karyawan Divisi')

@section('content')
    @php
        $employee = $employeeUser->employee;
        $profilePhoto = $employeeUser->profile_photo
            ? asset('storage/' . $employeeUser->profile_photo)
            : null;
    @endphp

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Detail Karyawan Divisi</h3>
        <p class="text-muted mb-2">Informasi lengkap data karyawan pada divisi yang dipimpin.</p>
        <a href="{{ route('leader.employees.index') }}" class="btn btn-light rounded-pill px-4">
            Kembali
        </a>
    </div>

    <div class="card table-card">
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-md-4 text-center">
                    <div class="detail-avatar-circle">
                        @if ($profilePhoto)
                            <img src="{{ $profilePhoto }}" alt="Foto Profil Karyawan">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-1">{{ $employeeUser->name }}</h4>
                    <p class="text-muted mb-0">{{ $employee->position ?? '-' }}</p>
                </div>

                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">ID Karyawan</small>
                            <div class="fw-semibold">{{ $employee->id ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <div class="fw-semibold">{{ $employeeUser->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Divisi</small>
                            <div class="fw-semibold">{{ $employeeUser->division->division_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Jabatan</small>
                            <div class="fw-semibold">{{ $employee->position ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nomor Telepon</small>
                            <div class="fw-semibold">{{ $employee->phone ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Role</small>
                            <div>
                                <span class="badge bg-primary rounded-pill px-3 py-2">Karyawan</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <small class="text-muted d-block">Alamat</small>
                            <div class="fw-semibold">{{ $employee->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
