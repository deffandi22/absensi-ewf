@extends('layouts.admin')

@section('title', 'Edit Ketua Ruangan')
@section('page-title', 'Edit Ketua Ruangan')

@section('content')
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Edit Ketua Ruangan</h3>
        <p class="text-muted mb-0">Perbarui data akun dan divisi ketua ruangan.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card table-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.leaders.update', $leader) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Ketua Ruangan</label>
                        <input type="text" name="name" class="form-control rounded-4"
                               value="{{ old('name', $leader->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control rounded-4"
                               value="{{ old('email', $leader->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-4"
                               placeholder="Kosongkan jika tidak diganti">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Divisi yang Dipimpin</label>
                        <select name="division_id" class="form-select rounded-4" required>
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ old('division_id', $leader->division_id) == $division->id ? 'selected' : '' }}>
                                    {{ $division->division_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text" name="position" class="form-control rounded-4"
                               value="{{ old('position', $leader->employee->position ?? 'Ketua Ruangan') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control rounded-4"
                               value="{{ old('phone', $leader->employee->phone ?? '') }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="address" class="form-control rounded-4" rows="4">{{ old('address', $leader->employee->address ?? '') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.leaders.index') }}" class="btn btn-light rounded-pill px-4">
                        Batal
                    </a>

                    <button class="btn btn-primary rounded-pill px-4" type="submit">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection