<!-- resources/views/kategori/edit.blade.php -->

@extends('layout.app')
@section('title', 'Edit Kategori') <!-- Judul halaman -->

@section('content')
<form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Nama Lengkap -->
    <div class="form-group mb-3">
        <label for="nama" class="col-md-4 col-form-label text-md-right">Nama Kategori *</label>
        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror"  value="{{ old('nama', $kategori->nama) }}" required>
        @error('nama') <!-- Ganti 'nama' menjadi 'name' -->
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- desc -->
    <div class="form-group mb-3">
        <label for="desc" class="col-md-4 col-form-label text-md-right">Deskripsi *</label>
        <input type="text" id="desc" name="desc"
              class="form-control @error('desc') is-invalid @enderror"
              value="{{ old('desc', $kategori->desc) }}" required>
        @error('desc')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Tombol Submit -->
    <div class="form-group mb-0">
        <button type="submit" class="btn btn-sm btn-primary">
            Simpan Perubahan
        </button>
        <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
</form>

@endsection
