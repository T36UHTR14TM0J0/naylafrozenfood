@extends('auth.layouts')
@section('content')
<div class="az-signin-wrapper">
  <div class="az-card-signin">
    <div class="az-signin-header mt-5">
      <h2>Login</h2>
      <h6>Silakan login untuk masuk kesistem</h6>

      <form method="POST" id="form-auth" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <label>Email</label>
          <input id="email" name="email" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}">
          @error('email')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->
        <div class="form-group">
          <label>Password</label>
          <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password">
          @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->
        <button type="submit" class="btn btn-az-primary btn-block" style="margin-bottom:0px !important;">Sign In</button>
      </form>
      <div class="az-signin-footer mt-2">
        <p><a href="">Lupa password?</a></p>
        <p>Belum punya akun? <a href="{{ route('register') }}">Register</a></p>
      </div><!-- az-signin-footer -->
    </div><!-- az-signin-header -->
  </div><!-- az-card-signin -->
</div><!-- az-signin-wrapper -->
@endsection