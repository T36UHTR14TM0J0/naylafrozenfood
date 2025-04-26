@extends('auth.layouts')
@section('title', 'Lupa Password')
@section('content')
<div class="az-signin-wrapper">
  <div class="az-card-signin">
    <div class="az-signin-header mt-5">
      <h2>Lupa Password</h2>
      
      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
          <label>Email</label>
          <input id="email" name="email" type="email" 
                 class="form-control @error('email') is-invalid @enderror" 
                 placeholder="Masukkan email Anda" 
                 value="{{ old('email') }}" 
                 required autocomplete="email" autofocus>
          @error('email')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->
        
        <button type="submit" class="btn btn-az-primary btn-block mb-0">
          Kirim Link Reset Password
        </button>
      </form>

      <div class="az-signin-footer mt-2">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
      </div><!-- az-signin-footer -->
    </div><!-- az-signin-header -->
  </div><!-- az-card-signin -->
</div><!-- az-signin-wrapper -->
@endsection