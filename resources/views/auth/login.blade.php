@extends('auth.layouts')  <!-- Menggunakan layout yang ada di auth.layouts -->
@section('title', 'Login')  <!-- Menetapkan judul halaman menjadi 'Login' -->

@section('content')  <!-- Menentukan bagian konten yang akan di-render -->

<!-- Form login dimulai -->
<form method="POST" id="form-auth" action="{{ route('login') }}">
  @csrf  <!-- Token CSRF untuk proteksi dari serangan CSRF -->

  <!-- Bagian header form dengan judul dan link ke halaman registrasi -->
  <div class="d-flex justify-content-between align-items-end mb-4">
    <h3 class="mb-0"><b>Login</b></h3>  <!-- Judul 'Login' -->
    <a href="{{ route('register') }}" class="link-primary">Tidak punya akun?</a>  <!-- Link untuk registrasi -->
  </div>

  <!-- Input untuk Email -->
  <div class="form-group mb-3">
    <label class="form-label">Email</label>  <!-- Label untuk email -->
    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan alamat email" value="{{ old('email') }}">
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
    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
    <!-- Menampilkan error jika ada kesalahan pada input password -->
    @error('password')
      <div class="invalid-feedback">
        {{ $message }}  <!-- Menampilkan pesan error -->
      </div>
    @enderror
  </div>

  <!-- Link untuk lupa password -->
  <div class="d-flex mt-1 justify-content-between">
    <h5 class="text-secondary f-w-400"><a href="{{ route('password.request') }}">Lupa password?</a></h5>
  </div>

  <!-- Tombol submit untuk login -->
  <div class="d-grid mt-4">
    <button type="submit" class="btn btn-primary">Login</button>
  </div>
</form>
<!-- Form login selesai -->

@endsection  <!-- Bagian konten selesai -->
