@extends('layout.app') <!-- Perhatikan penulisan 'layouts' (biasanya plural) -->
@section('title', 'Edit Akun') <!-- Titik koma dihapus setelah string -->

@section('content')
    <div class="card m-auto">
      <div class="card-header text-white" style="background-color:#5b47fb !important;">
          <h5 class="mb-0">Edit Profil Akun</h5>
      </div>

      <div class="card-body">
          <form action="{{ route('profile.update') }}" method="POST">
              @csrf
              @method('PUT')

              <!-- Nama Lengkap -->
              <div class="form-group row mb-3">
                  <label for="fullname" class="col-md-4 col-form-label text-md-right">Nama Lengkap</label>
                  <div class="col-md-6">
                      <input type="text" id="fullname" name="fullname" 
                            class="form-control @error('fullname') is-invalid @enderror" 
                            value="{{ old('fullname', $user->name) }}" required>
                      @error('fullname')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
              </div>

              <!-- Email -->
              <div class="form-group row mb-3">
                  <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                  <div class="col-md-6">
                      <input type="email" id="email" name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email', $user->email) }}" required>
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
              </div>

              <!-- Password Baru -->
              <div class="form-group row mb-3">
                  <label for="password" class="col-md-4 col-form-label text-md-right">Password Baru</label>
                  <div class="col-md-6">
                      <input type="password" id="password" name="password" 
                            class="form-control @error('password') is-invalid @enderror">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                  </div>
              </div>

              <!-- Konfirmasi Password -->
              <div class="form-group row mb-3">
                  <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Konfirmasi Password</label>
                  <div class="col-md-6">
                      <input type="password" id="password_confirmation" name="password_confirmation" 
                            class="form-control">
                  </div>
              </div>

              <!-- Tombol Submit -->
              <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                      <button type="submit" class="btn btn-indigo">
                          Simpan Perubahan
                      </button>
                  </div>
              </div>
          </form>
      </div>
  </div>

@endsection