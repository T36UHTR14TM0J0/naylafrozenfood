<!-- resources/views/satuan/edit.blade.php -->

@extends('layout.app')
@section('title', 'Edit Satuan') <!-- Judul halaman -->

@section('content')
<form action="{{ route('satuan.update', $satuan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Nama Lengkap -->
    <div class="form-group mb-3">
        <label for="nama" class="col-md-4 col-form-label text-md-right">Nama Satuan *</label>
        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"  value="{{ old('nama', $satuan->nama) }}" required>
        @error('nama') <!-- Ganti 'nama' menjadi 'name' -->
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Tombol Submit -->

            <button type="submit" class="btn btn-sm btn-primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('satuan.index') }}" class="btn btn-sm btn-secondary">Kembali</a>

</form>

@endsection
