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
    cursor: pointer;
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
    width: auto;
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
.edit-button, .delete-button {
    background-color: var(--color-dark);
    color: var(--color-light);
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
}
.delete-button {
    background-color: #dc3545;
    margin-left: 10px;
}
#modalFotoProfil {
    max-width: 350px;
    height: auto;
    display: block;
    margin: 20px auto;
    border-radius: 8px;
}

/* New styles for delete confirmation modal */
.delete-confirm-modal .modal-content {
    max-width: 400px;
    padding: 30px;
    text-align: center;
}
.delete-confirm-modal h3 {
    margin-bottom: 15px;
    color: #dc3545;
}
.delete-confirm-modal p {
    margin-bottom: 25px;
}
.delete-confirm-modal .modal-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
}
.delete-confirm-modal .btn {
    padding: 10px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}
.delete-confirm-modal .btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}
.delete-confirm-modal .btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
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

{{-- Modal Detail Pengguna --}}
<div id="userDetailModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('userDetailModal')">&times;</span>
        <h2>Detail Akun</h2>
        <div class="modal-body">
            <div class="modal-left">
                <img id="modalFotoProfil" src="" alt="Foto Profil">
            </div>
            <div class="modal-right">
                <p><strong>Nama:</strong> <span id="modalNama"></span> </p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Username:</strong> <span id="modalUsername"></span></p>
                <p><strong>Role/Organisasi:</strong> <span id="modalRole"></span></p>
                <p><strong>No. WhatsApp:</strong> <span id="modalNoWa"></span></p>
                <div style="margin-top: 20px;">
                    <button id="editButton" class="edit-button">Edit</button>
                    <button id="deleteButton" class="delete-button">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="modal delete-confirm-modal" style="display: none;">
    <div class="modal-content">
        <h3>Konfirmasi Hapus Pengguna</h3>
        <p>Apakah Anda yakin ingin menghapus pengguna ini?</p>
        <div class="modal-actions">
            <button class="btn btn-danger" onclick="executeDelete()">Ya, Hapus</button>
            <button class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Batal</button>
        </div>
    </div>
</div>

{{-- Formulir tersembunyi untuk mengirim permintaan DELETE --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // Variable untuk menyimpan ID pengguna yang akan dihapus
    let userIdToDelete = null;

    function openModal(user) {
        // Update data di modal detail
        document.getElementById('modalFotoProfil').src = user.foto_profil
            ? `/storage/${user.foto_profil}`
            : '/images/dummyperson.jpg';
        document.getElementById('modalNama').innerText = user.nama;
        document.getElementById('modalEmail').innerText = user.email || 'N/A';
        document.getElementById('modalUsername').innerText = user.username || 'N/A';
        document.getElementById('modalNoWa').innerText = user.no_wa || 'N/A';

        let roleOrOrgText = 'N/A';
        if (user.role === 'OPD') {
            if (user.organization && user.organization.organization_name) {
                roleOrOrgText = `OPD - ${user.organization.organization_name}`;
            }
        } else if (user.role === 'Admin' || user.role === 'Super Admin') {
            roleOrOrgText = user.role;
        }
        document.getElementById('modalRole').innerText = roleOrOrgText;

        // Atur URL untuk tombol Edit
        document.getElementById('editButton').onclick = function () {
            window.location.href = `/user/tambah?id=${user.id}`;
        };

        // Atur tombol Hapus untuk membuka modal konfirmasi
        document.getElementById('deleteButton').onclick = function () {
            openDeleteModal(user.id);
        };

        // Tampilkan modal detail
        document.getElementById('userDetailModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Buka modal konfirmasi hapus dan simpan ID pengguna
    function openDeleteModal(userId) {
        userIdToDelete = userId;
        document.getElementById('userDetailModal').style.display = 'none';
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    // Jalankan aksi penghapusan
    function executeDelete() {
        if (userIdToDelete) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/user/${userIdToDelete}`;
            deleteForm.submit();
        }
    }

    // Menutup modal jika klik di luar modal-content
    document.getElementById('userDetailModal').addEventListener('click', function (event) {
        const modalContent = document.querySelector('#userDetailModal .modal-content');
        if (!modalContent.contains(event.target)) {
            closeModal('userDetailModal');
        }
    });

    document.getElementById('deleteConfirmModal').addEventListener('click', function (event) {
        const modalContent = document.querySelector('#deleteConfirmModal .modal-content');
        if (!modalContent.contains(event.target)) {
            closeModal('deleteConfirmModal');
        }
    });
</script>
@endsection
