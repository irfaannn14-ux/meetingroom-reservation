@extends('layout.main')
@section('title', 'Edit Profil')
@section('content')

<style>
    .main-content { padding: 80px 20px 20px; }
    .btn i {
        margin-right: 6px;
        font-size: 13px;
        vertical-align: middle;
    }
    .form-action-row {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
</style>

<div class="main-content">
    <div class="container d-flex justify-content-center mt-2">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 12px;">
            <h4 class="text-left mb-4 fw-bold">Edit Profil</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input value="{{ old('nama', $user->nama) }}" type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" required placeholder="ex: Farhan Nugraha">
                        @error('nama')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input value="{{ old('email', $user->email) }}" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required placeholder="ex: farhan@email.com">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                        <input value="{{ old('no_wa', $user->no_wa) }}" type="text" name="no_wa" id="no_wa" class="form-control @error('no_wa') is-invalid @enderror" required placeholder="ex: 08xxxxxxxxxx">
                        @error('no_wa')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="foto_profil" class="form-label">Foto Profil</label>
                        <input type="file" name="foto_profil" id="foto_profil" class="form-control @error('foto_profil') is-invalid @enderror" accept="image/*">
                        @error('foto_profil')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @if($user->foto_profil)
                            <div class="mt-2">
                                <small class="text-muted">Foto saat ini:</small><br>
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input value="{{ old('username', $user->username) }}" type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" required>
                    @error('username')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru <small>(Kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-action-row mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-dark px-4">Kembali</a>
                    <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>Update Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
