@php
    $layout = match (auth()->user()->role) {
        'admin' => 'layouts.admin',
        'leader' => \Illuminate\Support\Facades\View::exists('layouts.leader') ? 'layouts.leader' : 'layouts.employee',
        default => 'layouts.employee',
    };

    $profileInfo = null;

    if ($user->role === 'leader' && isset($user->leaderProfile)) {
        $profileInfo = $user->leaderProfile;
    } elseif ($user->employee) {
        $profileInfo = $user->employee;
    }
@endphp

@extends($layout)

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Edit Profil</h3>
            <p class="text-muted mb-0">
                Mengubah informasi dasar akun pengguna.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('warning') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            <div class="fw-bold mb-1">
                <i class="bi bi-x-circle-fill me-1"></i>
                Gagal Memperbarui Profil
            </div>
            <div>
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <div class="card table-card">
        <div class="card-body p-4">
            <form action="/profile" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                @csrf
                @method('PUT')

                <div class="mb-4 text-center">
                    <label class="form-label fw-semibold d-block">Foto Profil</label>

                    <div class="mb-3">
                        @if ($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                alt="Foto Profil"
                                class="rounded-circle border"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                style="width: 120px; height: 120px; font-size: 44px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                    </div>

                    <input type="file"
                        name="profile_photo"
                        class="form-control rounded-4 @error('profile_photo') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/webp">

                    <small class="text-muted d-block mt-2">
                        Format: JPG, PNG, WEBP. Maksimal 2 MB.
                    </small>

                    @error('profile_photo')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text"
                               name="name"
                               class="form-control rounded-4 @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>

                        <div class="input-group">
                            <input type="email"
                                   name="email"
                                   id="emailInput"
                                   class="form-control rounded-start-4 @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}">

                            <button type="button"
                                    class="btn btn-primary rounded-end-4"
                                    id="sendOtpButton">
                                <i class="bi bi-shield-lock-fill me-1"></i>
                                Kirim OTP
                            </button>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Jika email diubah, klik Kirim OTP. Kode OTP akan dikirim ke email lama sebagai verifikasi keamanan.
                        </small>

                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kode OTP Email Lama</label>
                        <input type="text"
                               name="email_otp"
                               class="form-control rounded-4 @error('email_otp') is-invalid @enderror"
                               value="{{ old('email_otp') }}"
                               maxlength="6"
                               inputmode="numeric"
                               placeholder="Masukkan 6 digit kode OTP">

                        <small class="text-muted d-block mt-2">
                            Kosongkan jika email tidak diubah.
                        </small>

                        @error('email_otp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @if ($profileInfo)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text"
                                   name="phone"
                                   class="form-control rounded-4 @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $profileInfo->phone) }}">

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <input type="text"
                                   name="address"
                                   class="form-control rounded-4 @error('address') is-invalid @enderror"
                                   value="{{ old('address', $profileInfo->address) }}">

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif
                </div>

                @if ($user->role !== 'admin')
                    <div class="alert alert-warning rounded-4 border-0 mt-4">
                        Role, divisi, dan jabatan tidak dapat diubah melalui halaman profil.
                    </div>
                @endif

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                    <a href="/profile" class="btn btn-light rounded-pill px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <form id="sendEmailOtpForm" action="{{ route('profile.email.sendOtp') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="email" id="otpEmailInput">
            </form>
        </div>
    </div>

    <script>
        const emailInput = document.getElementById('emailInput');
        const otpEmailInput = document.getElementById('otpEmailInput');
        const sendOtpButton = document.getElementById('sendOtpButton');
        const sendEmailOtpForm = document.getElementById('sendEmailOtpForm');

        if (emailInput && otpEmailInput && sendOtpButton && sendEmailOtpForm) {
            sendOtpButton.addEventListener('click', function () {
                const emailValue = emailInput.value.trim();

                if (!emailValue) {
                    alert('Email baru wajib diisi sebelum mengirim OTP.');
                    return;
                }

                otpEmailInput.value = emailValue;

                sendOtpButton.disabled = true;
                sendOtpButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mengirim...';

                sendEmailOtpForm.submit();
            });
        }
    </script>
@endsection