@extends('auth.layouts')
@section('title', 'Login')
@section('content')

<form method="POST" id="form-auth" action="{{ route('login') }}">
  @csrf
<div class="d-flex justify-content-between align-items-end mb-4">
  <h3 class="mb-0"><b>Login</b></h3>
  <a href="{{ route('register') }}" class="link-primary">Tidak punya akun?</a>
</div>
<div class="form-group mb-3">
  <label class="form-label">Email</label>
  <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan alamat email" value="{{ old('email') }}">
  @error('email')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="form-group mb-3">
  <label class="form-label">Password</label>
  <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
  @error('password')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
  @enderror
</div>
<div class="d-flex mt-1 justify-content-between">
  <h5 class="text-secondary f-w-400"><a href="{{ route('password.request') }}">Lupa password?</a></h5>
</div>
<div class="d-grid mt-4">
  <button type="submit" class="btn btn-primary">Login</button>
</div>
</form>
@endsection