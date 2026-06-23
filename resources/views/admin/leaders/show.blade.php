@extends('layouts.admin')

@section('title', 'Detail Ketua Ruangan')
@section('page-title', 'Detail Ketua Ruangan')

@section('content')
    @php
        $profilePhoto = $leader->profile_photo
            ? asset('storage/' . $leader->profile_photo)
            : null;
    @endphp

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Detail Ketua Ruangan</h3>
        <p class="text-muted mb-2">Informasi lengkap data ketua ruangan.</p>

        <a href="{{ route('admin.leaders.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="card table-card">
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-md-4 text-center">
                    <div class="detail-avatar-circle">
                        @if ($profilePhoto)
                            <img src="{{ $profilePhoto }}" alt="Foto Profil Ketua Ruangan">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-1">{{ $leader->name }}</h4>
                    <p class="text-muted mb-0">{{ $leader->employee->position ?? 'Ketua Ruangan' }}</p>
                </div>

                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">ID User</small>
                            <div class="fw-semibold">{{ $leader->id }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <div class="fw-semibold">{{ $leader->email }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Divisi yang Dipimpin</small>
                            <div class="fw-semibold">{{ $leader->division->division_name ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Jabatan</small>
                            <div class="fw-semibold">{{ $leader->employee->position ?? 'Ketua Ruangan' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Nomor Telepon</small>
                            <div class="fw-semibold">{{ $leader->employee->phone ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Role</small>
                            <div>
                                <span class="badge bg-primary rounded-pill px-3 py-2">Ketua Ruangan</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <small class="text-muted d-block">Alamat</small>
                            <div class="fw-semibold">{{ $leader->employee->address ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.leaders.edit', $leader) }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection