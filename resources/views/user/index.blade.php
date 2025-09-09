<?php ?>
@extends('layout.main')
@section('title', 'User')
@section('content')
<style>
    /* CSS Styling untuk Halaman User */
    :root {
        --color-bg: #C9DFF2;
        --color-dark: #010D26;
        --color-light: #ffffff;
    }
    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
    }
    .main-content {
        padding: 80px 20px 20px;
        background-color: var(--color-bg);
        min-height: 100vh;
    }
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        color: var(--color-dark);
    }
    .add-button {
        background-color: var(--color-dark);
        color: var(--color-light);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
    }
    .add-button:hover {
        background-color: rgba(1, 13, 38, 0.8);
    }
    .user-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    .user-card {
        background-color: var(--color-light);
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Penting agar gambar tidak keluar dari sudut kartu */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        /* Animasi hover seperti dashboard */
    }
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .card-image {
        width: 100%;
        height: 180px; /* Tinggi gambar statis */
        overflow: hidden;
    }
    .user-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .user-name {
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--color-dark);
        margin: 0;
    }
    .user-role {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
    }
    .card-actions {
        display: flex;
        gap: 10px;
    }
    .action-button {
        background-color: transparent;
        border: 2px solid var(--color-dark);
        color: var(--color-dark);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s, color 0.2s;
        text-decoration: none; /* Hilangkan garis bawah pada link */
    }
    .action-button:hover {
        background-color: var(--color-dark);
        color: var(--color-light);
    }
</style>

<div class="main-content">
    <div class="header-content">
        <h1 class="page-title">Daftar Pengguna</h1>
        <a href="{{ route('user.tambah') }}" class="add-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Tambah Akun
        </a>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Grid pengguna --}}
    <div class="user-grid">
        @foreach ([1, 2, 3, 4, 5, 6] as $item) {{-- Contoh dummy data --}}
            <div class="user-card">
                <div class="card-image">
                    <img src="/images/dummyperson.jpg" alt="Foto Profil" class="user-photo">
                </div>
                <div class="card-content">
                    <h3 class="user-name">Jason Price</h3>
                    <p class="user-role">APD Probolinggo</p>
                    <div class="card-actions">
                        <a href="#" class="action-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        <a href="#" class="action-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
