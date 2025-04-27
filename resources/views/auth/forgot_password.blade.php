@extends('auth.layouts')
@section('title', 'Lupa Password')
@section('content')
<form method="POST" id="form-auth" action="{{ route('password.email') }}">
  @csrf
<div class="d-flex justify-content-between align-items-end mb-4">
  <h3 class="mb-0"><b>Lupa Password</b></h3>
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
<div class="d-grid mt-4">
  <button type="submit" class="btn btn-primary">Kirim Email</button>
</div>
</form>
@endsection