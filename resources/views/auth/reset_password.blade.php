@extends('auth.layouts')
@section('title', 'Reset Password')
@section('content')
<form method="POST" id="form-auth" action="{{ route('password.update') }}">
  @csrf
  <input type="hidden" name="token" value="{{ $token }}">
  <div class="d-flex justify-content-between align-items-end mb-4">
    <h3 class="mb-0"><b>Reset Password</b></h3>
  </div>

  <div class="form-group mb-3">
    <label>Email</label>
    <input id="email" type="email" 
           class="form-control @error('email') is-invalid @enderror" 
           name="email" value="{{ $email ?? old('email') }}" 
           required autocomplete="email" autofocus readonly>
    @error('email')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
    @enderror
  </div>

  <div class="form-group mb-3">
    <label class="form-label">Password Baru</label>
    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password">
    @error('password')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
    @enderror
  </div>

  <div class="form-group mb-3">
    <label>Konfirmasi Password Baru</label>
    <input id="password-confirm" type="password" class="form-control" 
          name="password_confirmation" required autocomplete="new-password"
          placeholder="Ketik ulang password baru">
  </div>

  <div class="d-grid mt-4">
    <button type="submit" class="btn btn-primary">Reset Password</button>
  </div>
</form>
@endsection