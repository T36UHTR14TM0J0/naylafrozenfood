@extends('auth.layouts')  <!-- Menggunakan layout yang ada di auth.layouts -->
@section('title', 'Register')  <!-- Menetapkan judul halaman menjadi 'Register' -->

@section('content')  <!-- Menentukan bagian konten yang akan di-render -->

<!-- Menampilkan pesan sukses jika ada -->
@if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}  <!-- Menampilkan pesan sukses -->
  </div>
@endif

<!-- Formulir pendaftaran dimulai -->
<form action="{{ route('register') }}" method="POST">
  @csrf  <!-- Token CSRF untuk melindungi aplikasi dari serangan CSRF -->

  <!-- Bagian header form dengan judul dan link ke halaman login -->
  <div class="d-flex justify-content-between align-items-end mb-4">
    <h3 class="mb-0"><b>Register</b></h3>  <!-- Judul 'Register' -->
    <a href="{{ route('login') }}" class="link-primary">Sudah punya akun?</a>  <!-- Link untuk login -->
  </div>

  <!-- Input untuk Nama Lengkap -->
  <div class="form-group mb-3">
    <label class="form-label">Nama Lengkap *</label>  <!-- Label untuk nama lengkap -->
    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
    <!-- Menampilkan error jika ada kesalahan pada input nama -->
    @error('name')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Input untuk Email -->
  <div class="form-group mb-3">
    <label class="form-label">Email *</label>  <!-- Label untuk email -->
    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email" value="{{ old('email') }}">
    <!-- Menampilkan error jika ada kesalahan pada input email -->
    @error('email')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Input untuk Password -->
  <div class="form-group mb-3">
    <label class="form-label">Password</label>  <!-- Label untuk password -->
    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
    <!-- Menampilkan error jika ada kesalahan pada input password -->
    @error('password')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Input untuk Konfirmasi Password -->
  <div class="form-group mb-3">
    <label>Konfirmasi Password</label>  <!-- Label untuk konfirmasi password -->
    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Masukkan konfirmasi password">
    <!-- Menampilkan error jika ada kesalahan pada konfirmasi password -->
    @error('password_confirmation')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Input untuk Role Pengguna -->
  <div class="form-group mb-3">
    <label>Role</label>  <!-- Label untuk memilih role -->
    <select name="role" class="form-control @error('role') is-invalid @enderror">
      <option value="" disabled selected>Pilih salah satu</option>  <!-- Pilihan default yang dinonaktifkan -->
      <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>  <!-- Pilihan role Admin -->
      <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>  <!-- Pilihan role Owner -->
    </select>
    <!-- Menampilkan error jika ada kesalahan pada pemilihan role -->
    @error('role')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Tombol untuk submit form -->
  <div class="d-grid mt-3">
    <button type="submit" class="btn btn-primary">Register</button>  <!-- Tombol untuk submit form -->
  </div>

</form>
<!-- Formulir pendaftaran selesai -->

@endsection  <!-- Bagian konten selesai -->
