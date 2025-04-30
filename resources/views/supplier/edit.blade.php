@extends('layout.app')
@section('title', 'Edit Data Supplier')

@section('content')
<div class="container">
    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama Supplier -->
        <div class="form-group mb-3">
            <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    placeholder="Masukkan nama supplier"
                    value="{{ old('nama', $supplier->nama) }}"
                    required>
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
                        required>{{ old('alamat', $supplier->alamat) }}</textarea>
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
                    value="{{ old('no_hp', $supplier->no_hp) }}"
                    required>
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
                        placeholder="Masukkan deskripsi supplier (opsional)">{{ old('desc', $supplier->desc) }}</textarea>
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
                    required>
                <option value="">Pilih Status</option>
                <option value="aktif" {{ (old('status', $supplier->status) == 'aktif' ? 'selected' : '') }}>Aktif</option>
                <option value="tidak aktif" {{ (old('status', $supplier->status) == 'tidak aktif' ? 'selected' : '' )}}>Tidak Aktif</option>
            </select>
            @error('status')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-sm btn-primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
