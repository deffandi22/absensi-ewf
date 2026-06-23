@php
    $layout = match (auth()->user()->role) {
        'admin' => 'layouts.admin',
        'leader' => \Illuminate\Support\Facades\View::exists('layouts.leader') ? 'layouts.leader' : 'layouts.employee',
        default => 'layouts.employee',
    };

    $roleLabel = match ($user->role) {
        'admin' => 'Admin',
        'leader' => 'Ketua Ruangan',
        'employee' => 'Karyawan',
        default => '-',
    };
@endphp

@extends($layout)

@section('title', 'Profil')
@section('page-title', 'Profil')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Profil Pengguna</h3>
            <p class="text-muted mb-0">
                Menampilkan informasi akun pengguna yang sedang login.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-pencil-square me-1"></i>
                Edit Profil
            </a>

            <a href="{{ route('profile.password') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-lock me-1"></i>
                Ubah Password
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card table-card h-100">
                <div class="card-body p-4 text-center">
                    @if ($user->profile_photo)
                        <div class="mx-auto mb-3 rounded-circle border bg-white d-flex align-items-center justify-content-center"
                             style="width: 120px; height: 120px; overflow: hidden;">
                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                 alt="Foto Profil"
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        </div>
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px; font-size: 42px; background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1">
                        {{ $user->name }}
                    </h4>

                    <p class="text-muted mb-3">
                        {{ $user->email }}
                    </p>

                    <span class="badge rounded-pill px-4 py-2"
                          style="background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;">
                        {{ $roleLabel }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if ($user->role === 'admin')
                <div class="card table-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Admin</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Nama Admin</small>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Email Admin</small>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Role Sistem</small>
                                <div class="fw-semibold">Admin</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Panel Akses</small>
                                <div class="fw-semibold">Admin Panel</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Status Akun</small>
                                <div class="fw-semibold text-success">Aktif</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Tanggal Terdaftar</small>
                                <div class="fw-semibold">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card table-card mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Hak Akses Admin</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light">
                                    <div class="fw-semibold">
                                        <i class="bi bi-diagram-3 me-2 text-primary"></i>
                                        Kelola Data Divisi
                                    </div>
                                    <small class="text-muted">
                                        Menambah, mengubah, dan menghapus data divisi.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light">
                                    <div class="fw-semibold">
                                        <i class="bi bi-people me-2 text-primary"></i>
                                        Kelola Data Karyawan
                                    </div>
                                    <small class="text-muted">
                                        Mengelola data karyawan dan akun pengguna.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light">
                                    <div class="fw-semibold">
                                        <i class="bi bi-calendar-check me-2 text-primary"></i>
                                        Monitoring Absensi
                                    </div>
                                    <small class="text-muted">
                                        Melihat seluruh data absensi karyawan.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 bg-light">
                                    <div class="fw-semibold">
                                        <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                        Laporan Absensi
                                    </div>
                                    <small class="text-muted">
                                        Membuat dan mencetak laporan absensi.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($user->role === 'leader')
                <div class="card table-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Ketua Ruangan</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Nama</small>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Email</small>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Role</small>
                                <div class="fw-semibold">Ketua Ruangan</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Divisi yang Dipimpin</small>
                                <div class="fw-semibold">
                                    {{ $user->division->division_name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Status Akun</small>
                                <div class="fw-semibold text-success">Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info rounded-4 border-0 mt-4">
                    Ketua ruangan memiliki akses untuk memantau data karyawan dan absensi sesuai divisi yang dipimpinnya.
                </div>
            @else
                <div class="card table-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Karyawan</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Nama</small>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Email</small>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Role</small>
                                <div class="fw-semibold">Karyawan</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Divisi</small>
                                <div class="fw-semibold">
                                    {{ $user->division->division_name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Jabatan</small>
                                <div class="fw-semibold">
                                    {{ $user->employee->position ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">Nomor Telepon</small>
                                <div class="fw-semibold">
                                    {{ $user->employee->phone ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <small class="text-muted d-block">Alamat</small>
                                <div class="fw-semibold">
                                    {{ $user->employee->address ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info rounded-4 border-0 mt-4">
                    Data divisi dan jabatan karyawan hanya dapat diubah oleh admin melalui menu data karyawan.
                </div>
            @endif
        </div>
    </div>

    <div class="card table-card mt-4 d-lg-none">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-2 text-danger">
                <i class="bi bi-box-arrow-right me-1"></i>
                Keluar dari Sistem
            </h5>

            <p class="text-muted mb-3">
                Gunakan tombol berikut untuk keluar dari akun dan mengakhiri sesi login.
            </p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger rounded-pill px-4 w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection