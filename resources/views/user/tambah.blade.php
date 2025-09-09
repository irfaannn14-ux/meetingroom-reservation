<?php ?>
@extends('layout.main')
@section('title', 'Form User')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
    .main-content { padding: 80px 20px 20px; }
    .btn i {
        margin-right: 6px;      /* jarak antara icon & teks */
        font-size: 13px;        /* default ukuran icon */
        vertical-align: middle; /* biar sejajar dengan teks */
    }
</style>
    <div class="main-content">
        <div class="container d-flex justify-content-center mt-4">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 12px;">
            <h4 class="text-left mb-4 fw-bold">
                {{ isset($user) ? 'Edit User' : 'Tambah User' }}
            </h4>

            <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}" method="POST">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="row mb-3">
                    <div class="col">
                        <label for="nama_apd" class="form-label">Nama</label>
                        <input value="{{ old('nama_apd', $user->nama_apd ?? '') }}"
                        type="text" name="nama_apd" id="nama_apd" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input value="{{ old('email', $user->email ?? '') }}"
                        type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input
                        value="{{ old('username', $user->username ?? '') }}"
                        type="text"
                        name="username"
                        id="username" class="form-control" required>
                    @error('username')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password
                        @if(isset($user)) <small>(Kosongkan jika tidak diubah)</small> @endif
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           {{ isset($user) ? '' : 'required' }}>
                </div>

                <div class="mb-3">
                    <label>Admin</label>
                    <select name="admin" id="admin" class="form-control">
                        <option value="1" {{ old('admin', $user->admin ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('admin', $user->admin ?? '') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Super Admin</label>
                    <select name="superadmin" id="superadmin" class="form-control">
                        <option value="1" {{ old('superadmin', $user->superadmin ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('superadmin', $user->superadmin ?? '') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-dark px-4">Cancel</a>
                    <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>{{ isset($user) ? 'Update' : 'Submit' }}</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
