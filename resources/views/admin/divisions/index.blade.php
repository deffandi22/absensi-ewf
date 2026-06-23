@extends('layouts.admin')

@section('title', 'Data Divisi')
@section('page-title', 'Data Divisi')

@section('content')
<style>
    /* Khusus tabel divisi: hanya ID Divisi dan Aksi yang rata tengah */
    .table th:first-child,
    .table td:first-child,
    .table th:last-child,
    .table td:last-child {
        text-align: center !important;
        vertical-align: middle !important;
    }

    /* Kolom lainnya tetap rata kiri */
    .table th:not(:first-child):not(:last-child),
    .table td:not(:first-child):not(:last-child) {
        text-align: left !important;
        vertical-align: middle !important;
    }

    /* Tombol aksi tetap di tengah */
    .table td:last-child .d-flex {
        justify-content: center !important;
        align-items: center !important;
    }

    .table td:last-child form {
        margin: 0;
    }
</style>

    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Data Divisi</h3>
            <p class="text-muted mb-0">
                Mengelola data divisi pada PT. Equity World Futures Surabaya.
            </p>
        </div>

        <a href="/admin/divisions/create" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Divisi
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning rounded-4 border-0">
            {{ session('warning') }}
        </div>
    @endif

    <div class="card compact-filter-card mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="compact-filter-title">
                Filter Data Divisi
            </div>

            <form method="GET" action="/admin/divisions" class="compact-filter row g-2 align-items-center">
                <div class="col-lg-9 col-md-8">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama divisi atau keterangan..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-fill" type="submit">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>

                        <a href="/admin/divisions" class="btn btn-light flex-fill">
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
                <h5 class="fw-bold mb-1">Daftar Divisi</h5>
                <p class="text-muted mb-0">
                    Total data ditemukan: {{ $divisions->total() }}
                </p>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Divisi</th>
                            <th>Nama Divisi</th>
                            <th>Keterangan</th>
                            <th>Ketua</th>
                            <th>Karyawan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($divisions as $division)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $division->id }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $division->division_name }}
                                    </div>
                                </td>

                                <td>
                                    {{ $division->description ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge badge-soft-warning rounded-pill px-3 py-2">
                                        {{ $division->leaders_count }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-soft-success rounded-pill px-3 py-2">
                                        {{ $division->employees_count }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="/admin/divisions/{{ $division->id }}/edit"
                                           class="btn btn-sm btn-light rounded-pill px-3">
                                            Edit
                                        </a>

                                        <form action="/admin/divisions/{{ $division->id }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus divisi ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    Belum ada data divisi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $divisions->links() }}
            </div>
        </div>
    </div>
@endsection