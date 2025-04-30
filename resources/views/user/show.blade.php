<!-- resources/views/user/show.blade.php -->

@extends('layout.app')
@section('title', 'Detail User')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Nama Lengkap: {{ $user->name }}</h5>
            <p class="card-text">Email: {{ $user->email }}</p>
            <p class="card-text">Role: <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">{{ ucfirst($user->role) }}</span></p>
            <p class="card-text">Dibuat pada: {{ $user->created_at->translatedFormat('d F Y') }}</p>
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
