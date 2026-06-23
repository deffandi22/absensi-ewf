@extends('layouts.admin')

@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Data Karyawan</h3>
            <p class="text-muted mb-0">Kelola data karyawan berdasarkan divisi perusahaan.</p>
        </div>

        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> Tambah Karyawan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card compact-filter-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="compact-filter-title">
                Filter Data Karyawan
            </div>

            <form method="GET" action="/admin/employees" class="compact-filter row g-2 align-items-center">
                <div class="col-lg-5 col-md-6">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari ID, nama, atau email karyawan..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-lg-4 col-md-6">
                    <select name="division_id" class="form-select">
                        <option value="">Semua Divisi</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->division_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-12">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill" type="submit">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>

                        <a href="/admin/employees" class="btn btn-light flex-fill">
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
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>No. Telepon</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="fw-semibold">{{ $employee->id }}</td>
                                <td>{{ $employee->user->name ?? '-' }}</td>
                                <td>{{ $employee->user->email ?? '-' }}</td>
                                <td>{{ $employee->user->division->division_name ?? '-' }}</td>
                                <td>{{ $employee->position ?? '-' }}</td>
                                <td>{{ $employee->phone ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.employees.show', $employee) }}" class="btn btn-sm btn-light rounded-pill">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-primary rounded-pill">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus data karyawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger rounded-pill" type="submit">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    Belum ada data karyawan.
                                </td>
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