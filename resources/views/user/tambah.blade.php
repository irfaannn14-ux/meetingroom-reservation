<?php
$isEdit = isset($user);
?>

@extends('layout.main')
@section('title', $isEdit ? 'Edit User' : 'Tambah User')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
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
    .custom-dropdown-container {
    position: relative;
    display: block;
    }
    .custom-dropdown-button {
    background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #495057;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        cursor: pointer;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .custom-dropdown-content {
        display: none;
        position: absolute;
        background-color: #f6f6f6;
        min-width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 100;
        border: 1px solid #ddd;
        border-radius: 0.25rem;
        overflow: hidden;
        max-height: 200px; /* Menambahkan batas tinggi maksimum */
        overflow-y: auto; /* Mengaktifkan scroll vertikal */
    }
    .custom-dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .custom-dropdown-content a:hover {
        background-color: #ddd;
    }
    .custom-dropdown-input {
        box-sizing: border-box;
        font-size: 16px;
        padding: 14px 20px;
        border: 1px solid #ced4da;
        width: 100%;
        border-radius: 0;
    }
    .show-dropdown {
        display: block;
    }
</style>

<div class="main-content">
    <div class="container d-flex justify-content-center mt-2">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 12px;">
        <h4 class="text-left mb-4 fw-bold">
            {{ $isEdit ? 'Edit User' : 'Tambah User' }}
        </h4>

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

        <form action="{{ $isEdit ? route('user.update', $user->id) : route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            <input type="hidden" name="organization_id" id="organization-id-input" value="{{ old('organization_id', $user->organization_id ?? '') }}">
            <input type="hidden" name="role" id="role-input" value="{{ old('role', $user->role ?? '') }}">

            <div class="row mb-3">
                <div class="col">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input
                        value="{{ old('nama', $user->nama ?? '') }}"
                        type="text"
                        name="nama"
                        id="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        required
                        placeholder="ex: Farhan Nugraha">
                    @error('nama')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input
                        value="{{ old('email', $user->email ?? '') }}"
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                        placeholder="ex: farhan@email.com">
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                    <input
                        value="{{ old('no_wa', $user->no_wa ?? '') }}"
                        type="text"
                        name="no_wa"
                        id="no_wa"
                        class="form-control @error('no_wa') is-invalid @enderror"
                        required
                        placeholder="ex: 08xxxxxxxxxx">
                    @error('no_wa')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label for="foto_profil" class="form-label">Foto Profil</label>
                    <input
                        type="file"
                        name="foto_profil"
                        id="foto_profil"
                        class="form-control @error('foto_profil') is-invalid @enderror"
                        accept="image/*"
                        {{ isset($user) ? '' : 'required' }}>
                    @error('foto_profil')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @if(isset($user) && $user->foto_profil)
                        <div class="mt-2">
                            <small class="text-muted">Foto saat ini:</small><br>
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    value="{{ old('username', $user->username ?? '') }}"
                    type="text"
                    name="username"
                    id="username"
                    class="form-control @error('username') is-invalid @enderror"
                    required>
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
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Kosongkan jika tidak diubah">
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="role_or_org" class="form-label">Organisasi / Role</label>
                <div class="custom-dropdown-container">
                    <div class="custom-dropdown-button form-control" onclick="toggleDropdown()">
                        <span id="selected-option">{{ old('role', $user->role ?? '') == 'OPD' ? 'OPD - ' . ($user->organization->organization_name ?? 'Pilih Opsi') : old('role', $user->role ?? 'Pilih Opsi') }}</span>
                        <span>&#9660;</span>
                    </div>
                    <div id="option-dropdown-content" class="custom-dropdown-content">
                        <input type="text" class="custom-dropdown-input" onkeyup="filterOptions()" placeholder="Cari Opsi...">
                        {{-- PERBAIKAN: Loop tunggal untuk semua organisasi --}}
                        @foreach($organizations as $org)
                            @php
                                $isRole = in_array($org->organization_name, ['ADMIN', 'SUPER ADMIN']);
                                $displayText = $isRole ? ($org->organization_name == 'ADMIN' ? 'Admin' : 'Super Admin') : 'OPD - ' . $org->organization_name;
                                $role = $isRole ? ($org->organization_name == 'ADMIN' ? 'Admin' : 'Super Admin') : 'OPD';
                            @endphp
                            <a href="#" 
                               data-value="{{ $org->bkd_organization_id }}" 
                               data-role="{{ $role }}"
                               data-text="{{ $displayText }}">
                               {{ $displayText }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @error('role')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                @error('organization_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-action-row mt-4">
                <a href="{{ route('user.index') }}" class="btn btn-outline-dark px-4">Cancel</a>
                <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>{{ $isEdit ? 'Update' : 'Submit' }}</button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
function toggleDropdown() {
    document.getElementById("option-dropdown-content").classList.toggle("show-dropdown");
}

function filterOptions() {
    const input = event.target;
    const filter = input.value.toUpperCase();
    const div = document.getElementById("option-dropdown-content");
    const a = div.getElementsByTagName("a");
    for (let i = 0; i < a.length; i++) {
        const txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

document.getElementById("option-dropdown-content").addEventListener('click', function(event) {
    if (event.target.tagName === 'A') {
        event.preventDefault(); // Mencegah aksi default link

        // PERBAIKAN: Logika disederhanakan untuk menangani semua opsi dari organisasi
        const selectedOrgId = event.target.getAttribute('data-value');
        const selectedRole = event.target.getAttribute('data-role');
        const selectedText = event.target.getAttribute('data-text');

        // Set nilai untuk input hidden
        document.getElementById("organization-id-input").value = selectedOrgId;
        document.getElementById("role-input").value = selectedRole;

        // Update teks yang ditampilkan di tombol dropdown
        document.getElementById("selected-option").textContent = selectedText;
        
        // Tutup dropdown
        document.getElementById("option-dropdown-content").classList.remove("show-dropdown");
    }
});

window.onclick = function(event) {
    if (!event.target.matches('.custom-dropdown-button') && !event.target.matches('.custom-dropdown-input') && !event.target.closest('.custom-dropdown-button')) {
        const dropdown = document.getElementById("option-dropdown-content");
        if (dropdown.classList.contains('show-dropdown')) {
            dropdown.classList.remove('show-dropdown');
        }
    }
}

</script>

@endsection
