<!-- resources/views/satuan/create.blade.php -->
@extends('layout.app') <!-- Pastikan ini sesuai dengan struktur folder Anda -->
@section('title', 'Tambah Data Satuan')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('satuan.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama satuan" value="{{ old('nama') }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>


        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        <a href="{{ route('satuan.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </form>
</div>
@endsection
