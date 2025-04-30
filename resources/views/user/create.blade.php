<!-- resources/views/user/create.blade.php -->
@extends('layout.app')
@section('title', 'Tambah Data User')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('user.store') }}">
        @csrf
        <div class="form-group mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email" value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
            @error('password')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Masukkan konfirmasi password" >
            @error('password_confirmation')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div><!-- form-group -->

        <div class="form-group mb-3">
            <label>Role</label>
            <select name="role" class="form-control @error('role') is-invalid @enderror" >
              <option value="" disabled selected>Pilih salah satu</option>
              <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
            </select>
            @error('role')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
        </div><!-- form-group -->
        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </form>
</div>
@endsection
