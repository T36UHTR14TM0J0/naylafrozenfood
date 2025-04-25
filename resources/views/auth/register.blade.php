@extends('auth.layouts')
@section('title', 'Register')
@section('content')
<div class="az-signin-wrapper">
  <div class="az-card-signin p-4 m-5">
    <div class="az-signin-header mt-5">
      <h2>Register</h2>
      <h6>Silahkan lakukan pendaftaran akun</h6>
      
      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      
      <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Input nama lengkap" value="{{ old('name') }}" >
          @error('name')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Input email" value="{{ old('email') }}" >
          @error('email')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->

        <div class="form-group">
          <label>Password (Minimal 8 karakter)</label>
          <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Input password" >
          @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->

        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Input konfirmasi password" >
          @error('password_confirmation')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->

        <div class="form-group">
          <label class="mg-b-10">Role</label>
          <select name="role" class="form-control select2-no-search @error('role') is-invalid @enderror" >
            <option value="" disabled selected>Pilih salah satu</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
          </select>
          @error('role')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div><!-- form-group -->

        <button type="submit" class="btn btn-az-primary btn-block">Register</button>
      </form>

      <div class="az-signin-footer mt-2">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
      </div><!-- az-signin-footer -->
    </div><!-- az-signup-header -->
  </div><!-- az-card-signin -->
</div><!-- az-signup-wrapper -->
@endsection