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
                    pattern="[0-9]*"
                    minlength="10"
                    maxlength="15"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    >
            @error('no_hp')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Minimal 10 digit angka</small>
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
            <button type="submit" class="btn btn-sm btn-primary">
                Simpan
            </button>
            <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
    // Validasi tambahan untuk nomor HP
    document.getElementById('no_hp').addEventListener('input', function(e) {
        // Hanya menerima angka
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // // Validasi panjang nomor
        // if (this.value.length < 10) {
        //     this.setCustomValidity('Nomor HP minimal 10 digit');
        // } else {
        //     this.setCustomValidity('');
        // }
    });
</script>
@endsection