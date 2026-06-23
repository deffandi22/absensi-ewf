@php
    $layout = match (auth()->user()->role) {
        'admin' => 'layouts.admin',
        'leader' => \Illuminate\Support\Facades\View::exists('layouts.leader') ? 'layouts.leader' : 'layouts.employee',
        default => 'layouts.employee',
    };
@endphp

@extends($layout)

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Ubah Password</h3>
            <p class="text-muted mb-0">
                Gunakan password yang kuat untuk menjaga keamanan akun.
            </p>
        </div>

        
    </div>

    <div class="card table-card">
        <div class="card-body p-4">
            <form action="/profile/password" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Lama</label>
                    <input type="password"
                           name="current_password"
                           class="form-control rounded-4 @error('current_password') is-invalid @enderror"
                           placeholder="Masukkan password lama">

                    @error('current_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru</label>
                    <input type="password"
                           name="password"
                           class="form-control rounded-4 @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter">

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control rounded-4"
                           placeholder="Ulangi password baru">
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="/profile" class="btn btn-light rounded-pill px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection