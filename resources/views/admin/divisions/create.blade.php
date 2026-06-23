@extends('layouts.admin')

@section('title', 'Tambah Divisi')
@section('page-title', 'Tambah Divisi')

@section('content')
    <div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="fw-bold mb-1">Tambah Divisi</h3>
            <p class="text-muted mb-0">
                Menambahkan data divisi baru ke dalam sistem.
            </p>
        </div>

    </div>

    <div class="card table-card">
        <div class="card-body p-4">
            <form action="/admin/divisions" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Divisi</label>
                    <input type="text"
                           name="division_name"
                           class="form-control rounded-4 @error('division_name') is-invalid @enderror"
                           placeholder="Contoh: Marketing"
                           value="{{ old('division_name') }}">

                    @error('division_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="description"
                              class="form-control rounded-4 @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Masukkan keterangan divisi jika diperlukan...">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <a href="/admin/divisions" class="btn btn-light rounded-pill px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Simpan Divisi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection