<?php
$isEdit = isset($user);
?>

@extends('layout.main')
@section('title', $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    :root {
        --primary-color: #1e3a8a;
        --secondary-color: #3b82f6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --light-color: #f8fafc;
        --dark-color: #1e293b;
        --border-radius: 12px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }
    
    body {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
        background: linear-gradient(135deg, #e6f0ff 0%, #f0f7ff 100%);
        color: var(--dark-color);
        padding-top: 60px;
        padding-left: 80px;
        min-height: 100vh;
    }
    
    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .form-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        max-width: 650px;
        margin: 0 auto;
        border: 1px solid #e2e8f0;
    }
    
    .form-header {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        color: white;
        padding: 24px 32px;
        position: relative;
    }
    
    .form-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--secondary-color);
    }
    
    .form-body {
        padding: 32px;
    }
    
    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .form-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 300;
        font-size: 1.05rem;
    }
    
    .form-section {
        margin-bottom: 28px;
        padding-bottom: 20px;
    }
    
    .form-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }
    
    .form-control {
        border: 1px solid #cbd5e1;
        padding: 12px 16px;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        transition: var(--transition);
        height: 48px;
    }
    
    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        outline: none;
    }
    
    .form-control::placeholder {
        color: #94a3b8;
        font-style: italic;
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
        padding: 16px;
        height: auto;
    }
    
    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.85rem;
        margin-top: 4px;
        display: block;
    }
    
    .input-hint {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 6px;
        display: block;
        line-height: 1.4;
    }
    
    .required-badge {
        color: var(--danger-color);
        font-weight: bold;
        margin-left: 4px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .file-upload-container {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        background-color: #f8fafc;
        transition: var(--transition);
        cursor: pointer;
        margin-top: 8px;
    }
    
    .file-upload-container:hover {
        border-color: var(--secondary-color);
        background-color: #eff6ff;
    }
    
    .file-upload-container.dragover {
        border-color: var(--primary-color);
        background-color: #dbeafe;
    }
    
    .file-upload-icon {
        font-size: 2.5rem;
        color: var(--secondary-color);
        margin-bottom: 12px;
    }
    
    .file-upload-text {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 8px;
    }
    
    .file-upload-subtext {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .file-upload-input {
        display: none;
    }
    
    .current-photo {
        margin-top: 16px;
        text-align: center;
    }
    
    .current-photo img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .photo-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--dark-color);
    }
    
    .custom-dropdown-container {
        position: relative;
        margin-top: 8px;
    }
    
    .custom-dropdown-button {
        background: white;
        border: 1px solid #cbd5e1;
        color: var(--dark-color);
        padding: 12px 16px;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 48px;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }
    
    .custom-dropdown-button:hover,
    .custom-dropdown-button:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    
    .custom-dropdown-button i {
        font-size: 0.9rem;
        margin-left: 8px;
        transition: transform 0.3s ease;
    }
    
    .dropdown-open .custom-dropdown-button i {
        transform: rotate(180deg);
    }
    
    .custom-dropdown-content {
        display: none;
        position: absolute;
        background: white;
        width: 100%;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 4px;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .dropdown-open .custom-dropdown-content {
        display: block;
        animation: dropdownSlide 0.2s ease;
    }
    
    @keyframes dropdownSlide {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .custom-dropdown-input {
        width: calc(100% - 32px);
        margin: 0 16px 12px;
        padding: 10px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
    }
    
    .role-item {
        padding: 12px 16px;
        display: block;
        color: var(--dark-color);
        text-decoration: none;
        transition: var(--transition);
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }
    
    .role-item:last-child {
        border-bottom: none;
    }
    
    .role-item:hover {
        background-color: #f0f7ff;
        color: var(--primary-color);
    }
    
    .role-item.admin {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .role-item.super-admin {
        background-color: #f0f9ff;
        color: #075985;
    }
    
    .role-item.opd {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 8px;
    }
    
    .badge-admin {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .badge-super-admin {
        background-color: #f0f9ff;
        color: #075985;
    }
    
    .badge-opd {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .btn-group {
        display: flex;
        gap: 16px;
        margin-top: 24px;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex: 1;
        height: 50px;
    }
    
    .btn-outline-dark {
        background: white;
        border: 2px solid var(--dark-color);
        color: var(--dark-color);
    }
    
    .btn-outline-dark:hover {
        background: var(--dark-color);
        color: white;
    }
    
    .btn-primary {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        border: none;
        color: white;
        box-shadow: 0 4px 6px rgba(30, 58, 138, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(30, 58, 138, 0.4);
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    .alert {
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        border: none;
        font-family: 'Poppins', sans-serif;
    }
    
    .alert-success {
        background-color: #f0fdf4;
        color: #166534;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background-color: #fef2f2;
        color: #b91c1c;
        border-left: 4px solid #ef4444;
    }
    
    .alert i {
        margin-right: 8px;
    }
    
    @media (max-width: 768px) {
        .form-header {
            padding: 20px;
        }
        
        .form-body {
            padding: 24px;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
    /* Client-side validation styles */
    .client-error {
        color: var(--danger-color);
        font-size: 0.85rem;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        animation: shakeIn 0.3s ease;
    }

    .is-invalid-client {
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }

    .field-error-highlight {
        border: 2px solid var(--danger-color) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }

    @keyframes shakeIn {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-3px); }
    }
</style>

<div class="page-container py-4">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="bi bi-person-plus"></i>
                @if($isEdit && isset($user) && $user->id == session('user_id'))
                    Edit Profil Saya
                @else
                    {{ $isEdit ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                @endif
            </h1>
            <p class="form-subtitle">Lengkapi data pengguna untuk manajemen sistem</p>
        </div>
        
        <div class="form-body">
            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <div class="ms-3">
                        <strong>Perhatian!</strong>
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                    <div class="ms-3">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <form action="{{ $isEdit && isset($user) && $user->id == session('user_id') ? route('profile.update') : ($isEdit ? route('user.update', $user->id) : route('user.store')) }}" method="POST" enctype="multipart/form-data" id="user-form" novalidate>
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                <input type="hidden" name="organization_id" id="organization-id-input" value="{{ old('organization_id', $user->organization_id ?? '') }}">
                <input type="hidden" name="role" id="role-input" value="{{ old('role', $user->role ?? '') }}">

                <div class="form-section">
                    <div class="form-grid">
                        <div class="mb-4">
                            <label for="nama" class="form-label">Nama Lengkap <span class="required-badge">*</span></label>
                            <input
                                value="{{ old('nama', $user->nama ?? '') }}"
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                required
                                placeholder="Contoh: Farhan Nugraha">
                            @error('nama')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <span class="input-hint">Nama lengkap yang akan ditampilkan di sistem</span>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email <span class="required-badge">*</span></label>
                            <input
                                value="{{ old('email', $user->email ?? '') }}"
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                placeholder="Contoh: farhan@email.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <span class="input-hint">Email aktif untuk notifikasi sistem</span>
                        </div>
                    </div>

                    <div class="form-section">
                    <div class="mb-4">
                        <label for="role_or_org" class="form-label">Role / Organisasi <span class="required-badge">*</span></label>
                        <div class="custom-dropdown-container" id="role-dropdown-container">
                            <div class="custom-dropdown-button" onclick="toggleDropdown()">
                                <span id="selected-option">
                                    {{ 
                                        old('role', $user->role ?? '') == 'OPD' 
                                        ? 'OPD - ' . ($user->organization->organization_name ?? 'Pilih Organisasi') 
                                        : (old('role', $user->role ?? 'Pilih Role/Organisasi') == 'Admin' ? 'Admin' : (old('role', $user->role ?? '') == 'Super Admin' ? 'Super Admin' : 'Pilih Role/Organisasi'))
                                    }}
                                </span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div id="option-dropdown-content" class="custom-dropdown-content">
                                <input type="text" class="custom-dropdown-input" onkeyup="filterOptions()" placeholder="Cari Role/Organisasi...">
                                @foreach($organizations as $org)
                                    @php
                                        $isRole = in_array($org->organization_name, ['ADMIN', 'SUPER ADMIN']);
                                        $displayText = $isRole 
                                            ? ($org->organization_name == 'ADMIN' ? 'Admin' : 'Super Admin') 
                                            : 'OPD - ' . $org->organization_name;
                                        $role = $isRole 
                                            ? ($org->organization_name == 'ADMIN' ? 'Admin' : 'Super Admin') 
                                            : 'OPD';
                                        $badgeClass = $isRole 
                                            ? ($org->organization_name == 'ADMIN' ? 'badge-admin' : 'badge-super-admin') 
                                            : 'badge-opd';
                                    @endphp
                                    <a href="#" 
                                       data-value="{{ $org->bkd_organization_id }}" 
                                       data-role="{{ $role }}"
                                       data-text="{{ $displayText }}"
                                       class="role-item {{ strtolower(str_replace(' ', '-', $role)) }}">
                                       {{ $displayText }}
                                       <span class="badge {{ $badgeClass }}">
                                           {{ $isRole ? $role : 'OPD' }}
                                       </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('organization_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <span class="input-hint">
                            <i class="bi bi-info-circle me-1"></i>
                            Pilih role pengguna: Admin atau OPD (Organisasi Perangkat Daerah)
                        </span>
                    </div>
                </div>
                </div>

                <div class="form-section">
                    <div class="form-grid">
                        <div class="mb-4">
                            <label for="no_wa" class="form-label">Nomor WhatsApp <span class="required-badge">*</span></label>
                            <input
                                value="{{ old('no_wa', $user->no_wa ?? '') }}"
                                type="text"
                                name="no_wa"
                                id="no_wa"
                                class="form-control @error('no_wa') is-invalid @enderror"
                                required
                                placeholder="Contoh: 081234567890">
                            @error('no_wa')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <span class="input-hint">Pastikan nomor dapat dihubungi via WhatsApp</span>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Foto Profil <span class="required-badge">{{ $isEdit ? '' : '*' }}</span></label>
                            <div class="file-upload-container" id="drop-area">
                                <div class="file-upload-icon">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <div class="file-upload-text">Tarik & Lepaskan Foto atau Klik di Sini</div>
                                <div class="file-upload-subtext">Format: JPG, PNG (Maks. 2MB)</div>
                                <input type="file" name="foto_profil" id="file-input" class="file-upload-input" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                            </div>
                            
                            @if(isset($user) && $user->foto_profil)
                                <div class="current-photo">
                                    <span class="photo-label">Foto Saat Ini:</span>
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil">
                                </div>
                            @endif
                            
                            <span class="input-hint mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ $isEdit ? 'Biarkan kosong untuk mempertahankan foto saat ini' : 'Unggah foto profil pengguna' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="mb-4">
                        <label for="username" class="form-label">Username <span class="required-badge">*</span></label>
                        <input
                            value="{{ old('username', $user->username ?? '') }}"
                            type="text"
                            name="username"
                            id="username"
                            class="form-control @error('username') is-invalid @enderror"
                            required
                            placeholder="Contoh: farhan_nugraha">
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <span class="input-hint">Username untuk login ke sistem</span>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password <span class="required-badge">{{ $isEdit ? '' : '*' }}</span>
                            @if($isEdit) <span class="text-muted">(Kosongkan jika tidak diubah)</span> @endif
                        </label>
                        <input type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Masukkan password minimal 8 karakter' }}">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <span class="input-hint">
                            <i class="bi bi-lock me-1"></i>
                            {{ $isEdit ? 'Minimal 8 karakter jika ingin mengubah password' : 'Password minimal 8 karakter dengan kombinasi huruf dan angka' }}
                        </span>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ ($isEdit && isset($user) && $user->id == session('user_id')) ? route('dashboard') : route('user.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleDropdown() {
        const dropdownContainer = document.getElementById('role-dropdown-container');
        dropdownContainer.classList.toggle('dropdown-open');
    }

    function filterOptions() {
        const filterText = event.target.value.toLowerCase();
        const items = document.querySelectorAll('.role-item');
        
        items.forEach(item => {
            const roleText = item.textContent.toLowerCase();
            item.style.display = roleText.includes(filterText) ? 'block' : 'none';
        });
    }

    document.getElementById("option-dropdown-content").addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (target) {
            event.preventDefault();

            const selectedOrgId = target.getAttribute('data-value');
            const selectedRole = target.getAttribute('data-role');
            const selectedText = target.getAttribute('data-text');

            // Set nilai untuk input hidden
            document.getElementById("organization-id-input").value = selectedOrgId;
            document.getElementById("role-input").value = selectedRole;

            // Update teks yang ditampilkan di tombol dropdown
            document.getElementById("selected-option").textContent = selectedText;
            
            // Tutup dropdown
            document.getElementById('role-dropdown-container').classList.remove('dropdown-open');
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdownContainer = document.getElementById('role-dropdown-container');
        if (!dropdownContainer.contains(event.target) && dropdownContainer.classList.contains('dropdown-open')) {
            dropdownContainer.classList.remove('dropdown-open');
        }
    });
    
    // File upload handling
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        dropArea.addEventListener('drop', handleDrop, false);
        dropArea.addEventListener('click', () => fileInput.click(), false);
        fileInput.addEventListener('change', handleFileSelect, false);
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight(e) {
            dropArea.classList.add('dragover');
        }
        
        function unhighlight(e) {
            dropArea.classList.remove('dragover');
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                fileInput.files = files;
                updateFilePreview(files[0]);
            }
        }
        
        function handleFileSelect(e) {
            if (e.target.files.length) {
                updateFilePreview(e.target.files[0]);
            }
        }
        
        function updateFilePreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'current-photo mt-3';
                preview.innerHTML = `
                    <span class="photo-label">Foto yang Dipilih:</span>
                    <img src="${e.target.result}" alt="Preview Foto" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                    <div class="mt-2">
                        <small class="text-muted">${file.name} (${formatFileSize(file.size)})</small>
                    </div>
                `;
                dropArea.insertAdjacentElement('afterend', preview);
            };
            reader.readAsDataURL(file);
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' bytes';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
        
        // Form submission with client-side validation
        const userForm = document.getElementById('user-form');
        const isEdit = {{ $isEdit ? 'true' : 'false' }};

        userForm.addEventListener('submit', function(e) {
            // Clear previous validation errors
            document.querySelectorAll('.client-error').forEach(el => el.remove());
            document.querySelectorAll('.is-invalid-client').forEach(el => el.classList.remove('is-invalid-client'));
            document.querySelectorAll('.field-error-highlight').forEach(el => el.classList.remove('field-error-highlight'));

            const errors = [];

            // 1. Nama Lengkap
            const nama = document.getElementById('nama');
            if (!nama.value.trim()) {
                errors.push({ field: nama, message: 'Nama lengkap wajib diisi.' });
            }

            // 2. Email
            const email = document.getElementById('email');
            if (!email.value.trim()) {
                errors.push({ field: email, message: 'Email wajib diisi.' });
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                errors.push({ field: email, message: 'Format email tidak valid.' });
            }

            // 3. Role / Organisasi
            const orgId = document.getElementById('organization-id-input');
            const roleInput = document.getElementById('role-input');
            const dropdownBtn = document.querySelector('#role-dropdown-container .custom-dropdown-button');
            if (!orgId.value || !roleInput.value) {
                errors.push({ field: dropdownBtn, message: 'Role / Organisasi wajib dipilih.' });
            }

            // 4. Nomor WhatsApp
            const noWa = document.getElementById('no_wa');
            if (!noWa.value.trim()) {
                errors.push({ field: noWa, message: 'Nomor WhatsApp wajib diisi.' });
            } else if (!/^[0-9]{10,15}$/.test(noWa.value.trim())) {
                errors.push({ field: noWa, message: 'Nomor WhatsApp harus 10-15 digit angka.' });
            }

            // 5. Foto Profil (wajib saat tambah baru)
            const fileInput = document.getElementById('file-input');
            if (!isEdit && (!fileInput.files || fileInput.files.length === 0)) {
                const dropArea = document.getElementById('drop-area');
                errors.push({ field: dropArea, message: 'Foto profil wajib diunggah.' });
            }

            // 6. Username
            const username = document.getElementById('username');
            if (!username.value.trim()) {
                errors.push({ field: username, message: 'Username wajib diisi.' });
            }

            // 7. Password (wajib saat tambah baru)
            const password = document.getElementById('password');
            if (!isEdit && !password.value) {
                errors.push({ field: password, message: 'Password wajib diisi.' });
            } else if (password.value && password.value.length < 8) {
                errors.push({ field: password, message: 'Password minimal 8 karakter.' });
            }

            // If errors found, display them
            if (errors.length > 0) {
                e.preventDefault();

                errors.forEach(err => {
                    // Add red highlight
                    err.field.classList.add('is-invalid-client');
                    if (err.field.classList.contains('custom-dropdown-button') || err.field.id === 'drop-area') {
                        err.field.classList.add('field-error-highlight');
                    }

                    // Add error message below the field
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'client-error';
                    errorMsg.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + err.message;
                    err.field.parentNode.insertBefore(errorMsg, err.field.nextSibling);
                });

                // Scroll to first error
                errors[0].field.scrollIntoView({ behavior: 'smooth', block: 'center' });

                return false;
            }

            // Show loading state
            const btn = document.getElementById('submit-btn');
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
            btn.disabled = true;
        });
        
        // Remove error highlight on input
        document.querySelectorAll('.form-control, .custom-dropdown-button').forEach(el => {
            el.addEventListener('input', function() {
                this.classList.remove('is-invalid-client');
                const next = this.nextElementSibling;
                if (next && next.classList.contains('client-error')) next.remove();
            });
            el.addEventListener('click', function() {
                this.classList.remove('is-invalid-client', 'field-error-highlight');
                const next = this.nextElementSibling;
                if (next && next.classList.contains('client-error')) next.remove();
            });
        });

        // Initialize dropdown selection
        const initialRole = document.getElementById('role-input').value;
        const initialOrgId = document.getElementById('organization-id-input').value;
        
        if (initialRole && initialOrgId) {
            const selectedItem = document.querySelector(`.role-item[data-role="${initialRole}"][data-value="${initialOrgId}"]`);
            if (selectedItem) {
                document.getElementById("selected-option").textContent = selectedItem.getAttribute('data-text');
            }
        }
    });
</script>

@endsection