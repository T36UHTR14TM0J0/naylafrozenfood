@extends('auth.layouts')  <!-- Menggunakan layout yang ada di auth.layouts -->
@section('title', 'Reset Password')  <!-- Menetapkan judul halaman menjadi 'Reset Password' -->

@section('content')  <!-- Menentukan bagian konten yang akan di-render -->

<!-- Formulir untuk mereset password -->
<form method="POST" id="form-auth" action="{{ route('password.update') }}">
  @csrf  <!-- Token CSRF untuk melindungi aplikasi dari serangan CSRF -->
  <input type="hidden" name="token" value="{{ $token }}">  <!-- Token untuk validasi reset password -->

  <!-- Header form dengan judul -->
  <div class="d-flex justify-content-between align-items-end mb-4">
    <h3 class="mb-0"><b>Reset Password</b></h3>  <!-- Judul 'Reset Password' -->
  </div>

  <!-- Input untuk Email -->
  <div class="form-group mb-3">
    <label>Email</label>  <!-- Label untuk email -->
    <input id="email" type="email" 
           class="form-control @error('email') is-invalid @enderror" 
           name="email" value="{{ $email ?? old('email') }}" 
           required autocomplete="email" autofocus readonly>
    <!-- Menampilkan error jika ada kesalahan pada input email -->
    @error('email')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Input untuk Password Baru -->
  <div class="form-group mb-3">
    <label class="form-label">Password Baru</label>  <!-- Label untuk password baru -->
    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
    <!-- Menampilkan error jika ada kesalahan pada input password baru -->
    @error('password')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Input untuk Konfirmasi Password Baru -->
  <div class="form-group mb-3">
    <label>Konfirmasi Password Baru</label>  <!-- Label untuk konfirmasi password -->
    <input id="password-confirm" type="password" class="form-control" 
          name="password_confirmation" required autocomplete="new-password"
          placeholder="Ketik ulang password baru">
  </div>

  <!-- Tombol untuk submit form reset password -->
  <div class="d-grid mt-4">
    <button type="submit" class="btn btn-primary">Reset Password</button>
  </div>

</form>

@endsection  <!-- Bagian konten selesai -->
