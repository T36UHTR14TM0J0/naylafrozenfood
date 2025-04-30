<!-- resources/views/user/edit.blade.php -->

@extends('layout.app')
@section('title', 'Edit User') <!-- Judul halaman -->

@section('content')
<form action="{{ route('user.update', $user->id) }}" method="POST"> <!-- Rute diperbaiki untuk menyertakan ID pengguna -->
    @csrf
    @method('PUT')

    <!-- Nama Lengkap -->
    <div class="form-group mb-3">
        <label for="fullname" class="col-md-4 col-form-label text-md-right">Nama Lengkap</label>
        <input type="text" id="fullname" name="name" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', $user->name) }}" required>
        @error('name') <!-- Ganti 'fullname' menjadi 'name' -->
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Email -->
    <div class="form-group mb-3">
        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
        <input type="email" id="email" name="email"
              class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email', $user->email) }}" required>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Role -->
    <div class="form-group mb-3">
        <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
        <select class="form-select" id="role" name="role" required>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
        </select>
        @error('role')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Baru -->
    <div class="form-group mb-3">
        <label for="password" class="col-md-4 col-form-label text-md-right">Password Baru</label>
        <input type="password" id="password" name="password"
              class="form-control @error('password') is-invalid @enderror">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
    </div>

    <!-- Konfirmasi Password -->
    <div class="form-group mb-3">
        <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
              class="form-control">
    </div>

    <!-- Tombol Submit -->
    <div class="form-group mb-0">
        <button type="submit" class="btn btn-sm btn-primary">
            Simpan Perubahan
        </button>
        <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
</form>

@endsection
