@extends('layouts.leader')

@section('title', 'Data Karyawan Divisi')
@section('page-title', 'Data Karyawan Divisi')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Data Karyawan Divisi</h3>
            <p class="text-muted mb-0">
                Menampilkan daftar karyawan pada divisi
                <strong>{{ $leader->division->division_name ?? 'Divisi Tidak Diketahui' }}</strong>.
            </p>
        </div>
    </div>

    <div class="card compact-filter-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="compact-filter-title">Filter Data Karyawan</div>
            <form method="GET" action="{{ route('leader.employees.index') }}" class="compact-filter row g-2 align-items-center">
                <div class="col-lg-9 col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Cari ID, nama, atau email karyawan..." value="{{ request('search') }}">
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill" type="submit">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('leader.employees.index') }}" class="btn btn-light flex-fill">Reset</a>
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
                            <th>Email</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>No. Telepon</th>
                            <th class="wrap-cell">Alamat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="fw-semibold">{{ optional($employee->employee)->id ?? '-' }}</td>
                                <td class="fw-semibold">{{ $employee->name ?? '-' }}</td>
                                <td>{{ $employee->email ?? '-' }}</td>
                                <td>{{ $employee->division->division_name ?? $leader->division->division_name ?? '-' }}</td>
                                <td>{{ optional($employee->employee)->position ?? '-' }}</td>
                                <td>{{ optional($employee->employee)->phone ?? '-' }}</td>
                                <td class="wrap-cell">{{ optional($employee->employee)->address ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('leader.employees.show', $employee) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">Belum ada data karyawan pada divisi ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
@endsection
