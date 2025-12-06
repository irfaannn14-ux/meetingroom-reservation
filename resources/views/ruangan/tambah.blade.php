<?php
$isEdit = isset($ruangan);
?>
@extends('layout.main')
@section('title', $isEdit ? 'Edit Ruangan' : 'Tambah Ruangan')
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
        padding-top: 20px;
        min-height: 100vh;
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
        border-bottom: 1px dashed #cbd5e1;
    }
    
    .form-section:last-child {
        border-bottom: none;
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
        max-width: 100%;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .photo-label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: var(--dark-color);
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
    
    @media (max-width: 576px) {
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
</style>

<div class="container py-4">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="bi bi-door-open"></i>
                {{ $isEdit ? 'Edit Data Ruangan' : 'Tambah Ruangan Baru' }}
            </h1>
            <p class="form-subtitle">Lengkapi data ruangan untuk manajemen peminjaman ruang rapat</p>
        </div>
        
        <div class="form-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong>
                    <ul class="mt-2 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $isEdit ? route('ruangan.update', $ruangan) : route('ruangan.store') }}" method="POST" enctype="multipart/form-data" id="ruangan-form">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-section">
                    <div class="form-grid">
                        <div class="mb-4">
                            <label for="nama_ruangan" class="form-label">Nama Ruangan <span class="required-badge">*</span></label>
                            <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" 
                                   placeholder="Contoh: Ruang Rapat Serbaguna" 
                                   value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}" 
                                   required>
                            <span class="input-hint">Nama yang mudah dikenali untuk keperluan peminjaman</span>
                        </div>
                        
                        <div class="mb-4">
                            <label for="jml_peserta" class="form-label">Kapasitas Maksimal <span class="required-badge">*</span></label>
                            <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" 
                                   placeholder="Jumlah peserta maksimal" 
                                   value="{{ old('jml_peserta', $ruangan->jml_peserta ?? '') }}" 
                                   required min="1">
                            <span class="input-hint">Pastikan kapasitas sesuai dengan kondisi ruangan</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <label for="fasilitas" class="form-label">Fasilitas <span class="required-badge">*</span></label>
                    <textarea name="fasilitas" id="fasilitas" class="form-control" 
                              placeholder="Sebutkan semua fasilitas yang tersedia di ruangan ini..." 
                              required>{{ old('fasilitas', $ruangan->fasilitas ?? '') }}</textarea>
                    <span class="input-hint">Contoh: Proyektor, AC, Meja Rapat, Kursi, Whiteboard, Sound System</span>
                </div>

                <div class="form-section">
                    <label class="form-label">Foto Ruangan <span class="required-badge">{{ $isEdit ? '' : '*' }}</span></label>
                    <div class="file-upload-container" id="drop-area">
                        <div class="file-upload-icon">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <div class="file-upload-text">Tarik & Lepaskan Foto atau Klik di Sini</div>
                        <div class="file-upload-subtext">Format: JPG, PNG, GIF (Maks. 5MB)</div>
                        <input type="file" name="foto_ruangan" id="file-input" class="file-upload-input" accept="image/*">
                    </div>
                    
                    @if($isEdit && $ruangan->foto_ruangan)
                        <div class="current-photo">
                            <span class="photo-label">Foto Saat Ini:</span>
                            <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}" alt="Foto Ruangan" class="img-fluid">
                        </div>
                    @endif
                    
                    <span class="input-hint mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ $isEdit ? 'Biarkan kosong untuk mempertahankan foto saat ini' : 'Foto ruangan wajib diisi untuk ruangan baru' }}
                    </span>
                </div>

                <div class="btn-group">
                    <a href="{{ route('ruangan.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Ruangan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        
        // Handle file selection via click
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });
        
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
                    <img src="${e.target.result}" alt="Preview Foto" class="img-fluid rounded">
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
        
        // Form submission loading state
        document.getElementById('ruangan-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
            btn.disabled = true;
        });
    });
</script>
@endsection