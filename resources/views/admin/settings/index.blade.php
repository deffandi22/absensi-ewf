@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Pengaturan Sistem</h3>
            <p class="text-muted mb-0">
                Mengatur informasi perusahaan, jaringan kantor, dan jam operasional absensi.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-lg-12">
                <div class="card table-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Perusahaan</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Perusahaan</label>
                            <input type="text"
                                   name="company_name"
                                   class="form-control rounded-4 @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $setting->company_name ?? 'PT. Equity World Futures Surabaya') }}">

                            @error('company_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">IP Jaringan Kantor</label>
                            <input type="text"
                                   name="office_ip"
                                   class="form-control rounded-4 @error('office_ip') is-invalid @enderror"
                                   placeholder="Contoh: 192.168.1.10 atau kosongkan untuk mode pengembangan"
                                   value="{{ old('office_ip', $setting->office_ip) }}">

                            <small class="text-muted">
                                Jika lebih dari satu IP, pisahkan dengan koma. Contoh: 192.168.1.10, 192.168.1.11
                            </small>

                            @error('office_ip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Latitude Kantor</label>
                                <input type="text"
                                    name="office_latitude"
                                    class="form-control rounded-4 @error('office_latitude') is-invalid @enderror"
                                    placeholder="Contoh: -7.257472"
                                    value="{{ old('office_latitude', $setting->office_latitude) }}">

                                @error('office_latitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Longitude Kantor</label>
                                <input type="text"
                                    name="office_longitude"
                                    class="form-control rounded-4 @error('office_longitude') is-invalid @enderror"
                                    placeholder="Contoh: 112.752090"
                                    value="{{ old('office_longitude', $setting->office_longitude) }}">

                                @error('office_longitude')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Allowed Radius Lokasi</label>
                                <input type="number"
                                    name="allowed_radius"
                                    class="form-control rounded-4 @error('allowed_radius') is-invalid @enderror"
                                    placeholder="Contoh: 100"
                                    value="{{ old('allowed_radius', $setting->allowed_radius) }}">

                                <small class="text-muted">
                                    Radius dihitung dalam meter. Contoh: 100 berarti karyawan hanya bisa absen maksimal 100 meter dari titik kantor.
                                </small>

                                @error('allowed_radius')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card table-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Jam Operasional Absensi</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jam Mulai Check-in</label>
                                <input type="time"
                                       name="check_in_start"
                                       class="form-control rounded-4 @error('check_in_start') is-invalid @enderror"
                                       value="{{ old('check_in_start', substr($setting->check_in_start ?? '07:00:00', 0, 5)) }}">

                                @error('check_in_start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Batas Check-in Normal</label>
                                <input type="time"
                                       name="check_in_end"
                                       class="form-control rounded-4 @error('check_in_end') is-invalid @enderror"
                                       value="{{ old('check_in_end', substr($setting->check_in_end ?? '07:59:59', 0, 5)) }}">

                                <small class="text-muted">
                                    Lewat dari jam ini akan ditandai terlambat.
                                </small>

                                @error('check_in_end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jam Mulai Check-out</label>
                                <input type="time"
                                       name="check_out_start"
                                       class="form-control rounded-4 @error('check_out_start') is-invalid @enderror"
                                       value="{{ old('check_out_start', substr($setting->check_out_start ?? '17:00:00', 0, 5)) }}">

                                @error('check_out_start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Batas Check-out</label>
                                <input type="time"
                                       name="check_out_end"
                                       class="form-control rounded-4 @error('check_out_end') is-invalid @enderror"
                                       value="{{ old('check_out_end', substr($setting->check_out_end ?? '19:59:59', 0, 5)) }}">

                                @error('check_out_end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info rounded-4 border-0 mt-4 mb-0">
                            Aturan saat ini: check-in normal dimulai pukul 07.00 sampai 07.59, check-in setelah itu ditandai terlambat. Check-out dimulai pukul 17.00 sampai 19.59.
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Simpan Pengaturan
                    </button>
                </div>

                <form action="{{ route('admin.settings.resetDefault') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin mengembalikan pengaturan sistem ke default?')">
                    @csrf

                    <button type="submit" class="reset-btn">
                        Reset Default
                    </button>
                </form>
            </div>
        </div>
    </form>
@endsection