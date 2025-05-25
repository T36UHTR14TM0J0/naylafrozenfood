@extends('auth.layouts')  <!-- Menggunakan layout yang ada di auth.layouts -->
@section('title', 'Lupa Password')  <!-- Menetapkan judul halaman menjadi 'Lupa Password' -->

@section('content')  <!-- Menentukan bagian konten yang akan di-render -->

<!-- Formulir untuk mengirim email reset password -->
<form method="POST" id="form-auth" action="{{ route('password.email') }}">
  @csrf  <!-- Token CSRF untuk melindungi aplikasi dari serangan CSRF -->

  <!-- Bagian header form dengan judul -->
  <div class="d-flex justify-content-between align-items-end mb-4">
    <h3 class="mb-0"><b>Lupa Password</b></h3>  <!-- Judul 'Lupa Password' -->
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

  <!-- Tombol untuk mengirim email reset password -->
  <div class="d-grid mt-4">
    <button type="submit" class="btn btn-primary">Kirim Email</button>  <!-- Tombol kirim email -->
  </div>
</form>

@endsection  <!-- Bagian konten selesai -->
