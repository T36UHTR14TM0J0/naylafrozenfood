@extends('auth.layouts')
@section('title', 'Reset Password')
@section('content')
<div class="az-signin-wrapper">
  <div class="az-card-signin">
    <div class="az-signin-header">
      <h2>Reset Password</h2>
      <h4>Masukkan password baru Anda</h4>

      @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <label>Email</label>
          <input id="email" type="email" 
                 class="form-control @error('email') is-invalid @enderror" 
                 name="email" value="{{ $email ?? old('email') }}" 
                 required autocomplete="email" autofocus>
          @error('email')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="form-group">
          <label>Password Baru</label>
          <input id="password" type="password" 
                 class="form-control @error('password') is-invalid @enderror" 
                 name="password" required autocomplete="new-password"
                 placeholder="Minimal 8 karakter">
          @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="form-group">
          <label>Konfirmasi Password Baru</label>
          <input id="password-confirm" type="password" class="form-control" 
                 name="password_confirmation" required autocomplete="new-password"
                 placeholder="Ketik ulang password baru">
        </div>

        <button type="submit" class="btn btn-az-primary btn-block">
          Reset Password
        </button>
      </form>

      <div class="az-signin-footer mt-3">
        <p>Ingat password Anda? <a href="{{ route('login') }}">Login</a></p>
      </div>
    </div>
  </div>
</div>
@endsection