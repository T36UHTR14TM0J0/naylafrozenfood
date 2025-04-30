@extends('layout.app')
@section('title', 'Tambah Data Supplier')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('supplier.store') }}">
        @csrf

        <!-- Nama Supplier -->
        <div class="form-group mb-3">
            <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    placeholder="Masukkan nama supplier"
                    value="{{ old('nama') }}"
                    >
            @error('nama')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Alamat -->
        <div class="form-group mb-3">
            <label class="form-label">Alamat <span class="text-danger">*</span></label>
            <textarea id="alamat" name="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        placeholder="Masukkan alamat supplier"
                        >{{ old('alamat') }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Nomor HP -->
        <div class="form-group mb-3">
            <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
            <input type="text" id="no_hp" name="no_hp"
                    class="form-control @error('no_hp') is-invalid @enderror"
                    placeholder="Contoh: 081234567890"
                    value="{{ old('no_hp') }}"
                    >
            @error('no_hp')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea id="desc" name="desc" rows="3"
                        class="form-control @error('desc') is-invalid @enderror"
                        placeholder="Masukkan deskripsi supplier (opsional)">{{ old('desc') }}</textarea>
            @error('desc')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Status -->
        <div class="form-group mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status"
                    class="form-control @error('status') is-invalid @enderror"
                    >
                <option value="">Pilih Status</option>
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            @error('status')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
