@extends('layouts.admin')

@section('title', 'Data Ketua Ruangan')
@section('page-title', 'Data Ketua Ruangan')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Data Ketua Ruangan</h3>
            <p class="text-muted mb-0">Kelola akun ketua ruangan berdasarkan divisi perusahaan.</p>
        </div>

        <a href="{{ route('admin.leaders.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> Tambah Ketua Ruangan
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
                Filter Data Ketua Ruangan
            </div>

            <form method="GET" action="{{ route('admin.leaders.index') }}" class="compact-filter row g-2 align-items-center">
                <div class="col-lg-5 col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama atau email ketua ruangan..."
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

                        <a href="{{ route('admin.leaders.index') }}" class="btn btn-light flex-fill">
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Divisi</th>
                            <th>Jabatan</th>
                            <th>No. Telepon</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($leaders as $leader)
                            <tr>
                                <td class="fw-semibold">{{ $leader->name }}</td>
                                <td>{{ $leader->email }}</td>
                                <td>{{ $leader->division->division_name ?? '-' }}</td>
                                <td>{{ $leader->leaderProfile->position ?? 'Ketua Ruangan' }}</td>
                                <td>{{ $leader->leaderProfile->phone ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.leaders.show', $leader) }}" class="btn btn-sm btn-light rounded-pill">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.leaders.edit', $leader) }}" class="btn btn-sm btn-primary rounded-pill">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.leaders.destroy', $leader) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus ketua ruangan ini?')">
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
                                <td colspan="6" class="text-center text-muted py-5">
                                    Belum ada data ketua ruangan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $leaders->links() }}
            </div>
        </div>
    </div>
@endsection