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
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer; /* Tambahkan cursor pointer untuk menunjukkan bahwa elemen ini dapat diklik */
    }
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .card-image {
        width: 100%;
        height: 180px;
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
        text-decoration: none;
    }
    .action-button:hover {
        background-color: var(--color-dark);
        color: var(--color-light);
    }
    .modal {
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        box-sizing: border-box;
    }
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 600px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .modal-body {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .modal-left {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-left img {
        max-height: 200px;
        width: auto;
        border-radius: 8px;
        border: 2px solid var(--color-dark);
    }
    .modal-right {
        flex: 2;
        text-align: left;
    }
    .close-button {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        background: none;
        border: none;
        color: #333;
        font-weight: bold;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .edit-button {
        background-color: var(--color-dark);
        color: var(--color-light);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
    }
    #modalFotoProfil {
        max-width: 350px;
        height: auto;
        display: block;
        margin: 20px auto;
        border-radius: 8px;
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
        @foreach ($all as $user)
            <div class="user-card" onclick="openModal({{ json_encode($user) }})">
                <div class="card-image">
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" class="user-photo">
                    @else
                        <img src="/images/dummyperson.jpg" alt="Foto Profil" class="user-photo">
                    @endif
                </div>
                <div class="card-content">
                    <h3 class="user-name">{{ $user->nama }}</h3>
                    <p class="user-role">{{ $user->role }}</p>
                    <div class="card-actions">
                        <a href="https://wa.me/62{{ ltrim($user->no_wa, '0') }}" target="_blank" class="action-button" title="Chat WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div id="userDetailModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Detail Akun</h2>
        <div class="modal-body">
            <div class="modal-left">
                <img id="modalFotoProfil" src="" alt="Foto Profil">
            </div>
            <div class="modal-right">
                <p><strong>Nama:</strong> <span id="modalNama"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Username:</strong> <span id="modalUsername"></span></p>
                <p><strong>Role:</strong> <span id="modalRole"></span></p>
                <p><strong>No. WhatsApp:</strong> <span id="modalNoWa"></span></p>
                <button id="editButton" class="edit-button">Edit</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(user) {
        document.getElementById('modalFotoProfil').src = user.foto_profil
            ? `/storage/${user.foto_profil}`
            : '/images/dummyperson.jpg';
        document.getElementById('modalNama').innerText = user.nama;
        document.getElementById('modalEmail').innerText = user.email || 'N/A';
        document.getElementById('modalUsername').innerText = user.username || 'N/A';
        document.getElementById('modalRole').innerText = user.role || 'N/A';
        document.getElementById('modalNoWa').innerText = user.no_wa || 'N/A';
        document.getElementById('editButton').onclick = function () {
            window.location.href = `/user/tambah?id=${user.id}`;
        };
        document.getElementById('userDetailModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('userDetailModal').style.display = 'none';
    }

    // Tambahkan event listener untuk menutup modal jika klik di luar modal-content
    document.getElementById('userDetailModal').addEventListener('click', function (event) {
        const modalContent = document.querySelector('.modal-content');
        if (!modalContent.contains(event.target)) {
            closeModal();
        }
    });
</script>
@endsection
