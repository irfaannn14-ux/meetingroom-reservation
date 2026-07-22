<?php
$isEdit = isset($pengajuan) && $pengajuan->id;
$selectedRuanganId = old('ruangan_id', $pengajuan->ruangan_id ?? null);
$selectedRuanganName = 'Pilih Ruangan';
if ($selectedRuanganId) {
    $selectedRuangan = $ruangans->firstWhere('id', $selectedRuanganId);
    if ($selectedRuangan) {
        $selectedRuanganName = $selectedRuangan->nama_ruangan;
    }
}
?>
@extends('layout.main')
@section('title', $isEdit ? 'Edit Pengajuan' : 'Form Pengajuan Peminjaman Ruangan')
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
    
    .form-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
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
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
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
    
    .input-group {
        position: relative;
    }
    
    .input-hint {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 6px;
        display: block;
        line-height: 1.4;
    }
    
    .input-warning {
        color: var(--warning-color);
        font-weight: 500;
    }
    
    .input-success {
        color: var(--success-color);
        font-weight: 500;
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
        background: #ffffff;
        width: 100%;
        box-shadow: 0 12px 40px -8px rgba(0, 0, 0, 0.15), 0 4px 12px -4px rgba(0, 0, 0, 0.08);
        z-index: 1000;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-top: 6px;
        max-height: 380px;
        overflow-y: auto;
        padding: 0;
    }

    .custom-dropdown-content::-webkit-scrollbar {
        width: 6px;
    }
    .custom-dropdown-content::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-dropdown-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .dropdown-open .custom-dropdown-content {
        display: block;
        animation: dropdownSlide 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes dropdownSlide {
        from { opacity: 0; transform: translateY(-8px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    /* === Group Divider === */
    .dropdown-divider-title {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;
        margin: 0;
        user-select: none;
    }
    .dropdown-divider-title.divider-available  { color: #16a34a; background: #f0fdf4; border-top-color: #bbf7d0; }
    .dropdown-divider-title.divider-capacity   { color: #d97706; background: #fffbeb; border-top-color: #fde68a; }
    .dropdown-divider-title.divider-booked     { color: #dc2626; background: #fef2f2; border-top-color: #fecaca; }

    .dropdown-divider-title .divider-count {
        margin-left: auto;
        font-size: 0.65rem;
        font-weight: 600;
        opacity: 0.7;
    }

    /* === Room Card Item === */
    .ruangan-item {
        display: flex;
        align-items: stretch;
        gap: 14px;
        padding: 12px 16px;
        color: var(--dark-color);
        text-decoration: none;
        transition: background 0.15s ease, transform 0.1s ease;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        position: relative;
    }
    .ruangan-item:last-child { border-bottom: none; }

    .ruangan-item:hover:not(.disabled) {
        background-color: #eff6ff;
    }
    .ruangan-item:active:not(.disabled) {
        transform: scale(0.995);
    }

    /* Icon column */
    .ruangan-item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #eff6ff;
        color: var(--primary-color);
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    /* Text column */
    .ruangan-item-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 3px;
    }
    .ruangan-item-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--dark-color);
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ruangan-item-meta {
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .ruangan-item-meta i { font-size: 0.72rem; }

    /* Right side badges */
    .ruangan-item-badges {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    /* Selected state */
    .ruangan-item.selected {
        background-color: #eff6ff;
    }
    .ruangan-item.selected .ruangan-item-icon {
        background: var(--primary-color);
        color: #ffffff;
    }
    .ruangan-item.selected .ruangan-item-name {
        color: var(--primary-color);
    }
    .ruangan-item-check {
        display: none;
        color: var(--primary-color);
        font-size: 1.1rem;
    }
    .ruangan-item.selected .ruangan-item-check {
        display: flex;
        align-items: center;
    }

    /* Disabled state */
    .ruangan-item.disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }
    .ruangan-item.disabled .ruangan-item-icon {
        background: #f1f5f9;
        color: #94a3b8;
    }
    .ruangan-item.disabled:hover {
        background-color: transparent;
    }

    /* Recommended state */
    .ruangan-item.recommended {
        background-color: #f0fdf4;
    }
    .ruangan-item.recommended .ruangan-item-icon {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Badges */
    .ruangan-capacity-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .ruangan-capacity-badge i { font-size: 0.68rem; }
    
    .ruangan-recommended-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #dcfce7;
        color: #15803d;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
        animation: badgePulse 2s ease-in-out infinite;
    }
    @keyframes badgePulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.75; }
    }

    .ruangan-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .ruangan-status-badge.status-booked {
        background: #fef2f2;
        color: #dc2626;
    }
    .ruangan-status-badge.status-capacity {
        background: #fffbeb;
        color: #d97706;
    }

    /* Empty state */
    .dropdown-empty {
        padding: 24px 16px;
        text-align: center;
        color: #94a3b8;
    }
    .dropdown-empty i {
        font-size: 2rem;
        margin-bottom: 8px;
        display: block;
    }
    .dropdown-empty span {
        font-size: 0.85rem;
    }
    
    .date-time-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 12px;
    }
    
    .time-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.9rem;
        margin-top: 12px;
        color: #0c4a6e;
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
        
        .date-time-grid {
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
                <i class="bi bi-building"></i>
                {{ $isEdit ? 'Edit Pengajuan Ruangan' : 'Form Pengajuan Ruangan' }}
            </h1>
            <p class="form-subtitle">Lengkapi data di bawah untuk mengajukan peminjaman ruang rapat</p>
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

            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-x-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ $isEdit ? route('pengajuan.update', $pengajuan->id) : route('pengajuan.store') }}" method="POST" id="pengajuan-form" novalidate>
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-section">
                    <div class="mb-4">
                        <label for="judul_kegiatan" class="form-label">Judul Kegiatan <span class="required-badge">*</span></label>
                        <input type="text" name="judul_kegiatan" id="judul_kegiatan" class="form-control" 
                               placeholder="Contoh: Rapat Koordinasi Bulanan" 
                               value="{{ old('judul_kegiatan', $pengajuan->judul_kegiatan ?? '') }}" 
                               required>
                        <span class="input-hint">Buat judul yang singkat namun deskriptif</span>
                    </div>

                    <div class="mb-4">
                        <label for="kegiatan" class="form-label">Deskripsi Kegiatan <span class="required-badge">*</span></label>
                        <textarea name="kegiatan" id="kegiatan" class="form-control" 
                                  placeholder="Jelaskan secara lengkap tujuan dan agenda kegiatan ini..." 
                                  required>{{ old('kegiatan', $pengajuan->kegiatan ?? '') }}</textarea>
                        <span class="input-hint">Minimal 10 kata untuk memudahkan verifikasi</span>
                    </div>
                </div>

                <div class="form-section">
                    <div class="date-time-grid">
                        <div class="mb-4">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Mulai <span class="required-badge">*</span></label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" 
                                   value="{{ old('tanggal_pinjam', ($pengajuan->tanggal_mulai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('Y-m-d') : '') }}" 
                                   required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="tanggal_kembali" class="form-label">Tanggal Selesai <span class="required-badge">*</span></label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" 
                                   value="{{ old('tanggal_kembali', ($pengajuan->tanggal_selesai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('Y-m-d') : '') }}" 
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="waktu_pinjam" class="form-label">Waktu Mulai <span class="required-badge">*</span></label>
                            <select name="waktu_pinjam" id="waktu_pinjam" class="form-control" required>
                                <option value="">-- Pilih Waktu --</option>
                                @php
                                    $oldWaktuPinjam = old('waktu_pinjam', ($pengajuan->tanggal_mulai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('H:i') : '');
                                @endphp
                                @for($hour = 6; $hour <= 16; $hour++)
                                    @foreach(['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                            if($hour == 16 && $minute == '30') continue;
                                        @endphp
                                        <option value="{{ $time }}" {{ $oldWaktuPinjam == $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="waktu_kembali" class="form-label">Waktu Selesai <span class="required-badge">*</span></label>
                            <select name="waktu_kembali" id="waktu_kembali" class="form-control" required>
                                <option value="">-- Pilih Waktu --</option>
                                @php
                                    $oldWaktuKembali = old('waktu_kembali', ($pengajuan->tanggal_selesai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('H:i') : '');
                                @endphp
                                @for($hour = 6; $hour <= 18; $hour++)
                                    @foreach(['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                            if($hour == 18 && $minute == '30') continue; 
                                        @endphp
                                        <option value="{{ $time }}" {{ $oldWaktuKembali == $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="time-info">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Jam Operasional:</strong> Ruangan dapat dipinjam mulai pukul 06.00 hingga 18.00 WIB. 
                        Minimal durasi peminjaman adalah 2 jam.
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-grid">
                        <div class="mb-4">
                            <label for="jml_peserta" class="form-label">Jumlah Peserta <span class="required-badge">*</span></label>
                            <div class="input-group">
                                <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" 
                                       placeholder="Masukkan jumlah peserta" 
                                       value="{{ old('jml_peserta', $pengajuan->jml_peserta ?? '') }}" 
                                       required min="1" oninput="handlePesertaChange()">
                                <span class="input-hint">Masukkan jumlah peserta untuk rekomendasi ruangan terbaik</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="ruangan_id" class="form-label">Ruangan <span class="required-badge">*</span></label>
                            <input type="hidden" name="ruangan_id" id="ruangan-id-input" value="{{ old('ruangan_id', $pengajuan->ruangan_id ?? '') }}" required>
                            <div class="custom-dropdown-container" id="ruangan-dropdown-container">
                                <div class="custom-dropdown-button" onclick="toggleDropdown()" id="dropdown-button">
                                    <span id="selected-ruangan">
                                        {{ $selectedRuanganName }}
                                    </span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="custom-dropdown-content" id="ruangan-dropdown-content">
                                    <!-- Options will be injected here -->
                                </div>
                            </div>
                            <span id="ruangan-hint" class="input-hint">Pilih ruangan sesuai dengan kapasitas peserta</span>
                        </div>
                    </div>
                </div>



                <div class="btn-group">
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Perbarui Pengajuan' : 'Ajukan Sekarang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // State variables
    const ruangansData = @json($ruangans->keyBy('id'));
    const ruangansArray = @json($ruangans->values());
    let selectedRuanganId = {{ $selectedRuanganId ?: 'null' }};
    let bentrokIds = [];
    let currentJmlPeserta = parseInt(document.getElementById('jml_peserta').value) || 0;

    function renderRuanganDropdown() {
        const dropdownContent = document.getElementById("ruangan-dropdown-content");
        dropdownContent.innerHTML = '';
        
        const available = [];
        const capacityLow = [];
        const booked = [];

        ruangansArray.forEach(ruangan => {
            if (bentrokIds.includes(ruangan.id)) {
                booked.push(ruangan);
            } else if (currentJmlPeserta && ruangan.jml_peserta < currentJmlPeserta) {
                capacityLow.push(ruangan);
            } else {
                available.push(ruangan);
            }
        });

        // Sort available by capacity (ascending)
        available.sort((a, b) => a.jml_peserta - b.jml_peserta);
        let recommendedId = available.length > 0 ? available[0].id : null;

        const renderItem = (ruangan, status) => {
            const isSelected = selectedRuanganId == ruangan.id;
            const isDisabled = status !== 'available';
            const isRecommended = (status === 'available' && ruangan.id === recommendedId);
            
            const a = document.createElement('a');
            a.href = '#';
            a.className = 'ruangan-item' + (isSelected ? ' selected' : '') + (isDisabled ? ' disabled' : '') + (isRecommended ? ' recommended' : '');
            a.setAttribute('data-value', ruangan.id);
            a.setAttribute('data-nama', ruangan.nama_ruangan);
            a.setAttribute('data-kapasitas', ruangan.jml_peserta);

            // Icon berdasarkan status
            let iconClass = 'bi-door-open';
            if (status === 'booked') iconClass = 'bi-door-closed';
            if (status === 'capacity_low') iconClass = 'bi-door-open';

            // Meta info baris kedua
            let metaHtml = `<i class="bi bi-people"></i> Kapasitas ${ruangan.jml_peserta} orang`;

            // Badges di sisi kanan
            let badgesHtml = '';
            if (isRecommended) {
                badgesHtml += `<span class="ruangan-recommended-badge"><i class="bi bi-star-fill"></i> Terbaik</span>`;
            }
            if (status === 'booked') {
                badgesHtml += `<span class="ruangan-status-badge status-booked"><i class="bi bi-lock-fill"></i> Terpakai</span>`;
            } else if (status === 'capacity_low') {
                badgesHtml += `<span class="ruangan-status-badge status-capacity"><i class="bi bi-exclamation-circle"></i> Kurang</span>`;
            }
            badgesHtml += `<span class="ruangan-capacity-badge"><i class="bi bi-people-fill"></i> ${ruangan.jml_peserta}</span>`;

            a.innerHTML = `
                <div class="ruangan-item-icon"><i class="bi ${iconClass}"></i></div>
                <div class="ruangan-item-info">
                    <span class="ruangan-item-name">${ruangan.nama_ruangan}</span>
                    <span class="ruangan-item-meta">${metaHtml}</span>
                </div>
                <div class="ruangan-item-badges">${badgesHtml}</div>
                <span class="ruangan-item-check"><i class="bi bi-check-lg"></i></span>
            `;
            return a;
        };

        const addDivider = (text, icon, cssClass, count) => {
            const div = document.createElement('div');
            div.className = 'dropdown-divider-title ' + cssClass;
            div.innerHTML = `<i class="bi ${icon}"></i> <span>${text}</span> <span class="divider-count">${count} ruangan</span>`;
            dropdownContent.appendChild(div);
        };

        if (available.length > 0) {
            addDivider('Tersedia', 'bi-check-circle-fill', 'divider-available', available.length);
            available.forEach(r => dropdownContent.appendChild(renderItem(r, 'available')));
        }

        if (capacityLow.length > 0) {
            addDivider('Kapasitas Tidak Cukup', 'bi-exclamation-triangle-fill', 'divider-capacity', capacityLow.length);
            capacityLow.forEach(r => dropdownContent.appendChild(renderItem(r, 'capacity_low')));
        }

        if (booked.length > 0) {
            addDivider('Jadwal Bentrok', 'bi-x-circle-fill', 'divider-booked', booked.length);
            booked.forEach(r => dropdownContent.appendChild(renderItem(r, 'booked')));
        }

        // Jika tidak ada ruangan sama sekali setelah filter
        if (available.length === 0 && capacityLow.length === 0 && booked.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'dropdown-empty';
            empty.innerHTML = `<i class="bi bi-x-circle"></i><span>Tidak ada ruangan tersedia</span>`;
            dropdownContent.appendChild(empty);
        }
    }

    function toggleDropdown() {
        const dropdownContainer = document.getElementById('ruangan-dropdown-container');
        
        const tglMulai = document.getElementById('tanggal_pinjam').value;
        const wktMulai = document.getElementById('waktu_pinjam').value;
        const tglSelesai = document.getElementById('tanggal_kembali').value;
        const wktSelesai = document.getElementById('waktu_kembali').value;
        
        if (!tglMulai || !wktMulai || !tglSelesai || !wktSelesai) {
            showToast('Silakan lengkapi Tanggal & Waktu terlebih dahulu!', 'warning');
            document.getElementById('tanggal_pinjam').focus();
            return;
        }

        const jmlPeserta = parseInt(document.getElementById('jml_peserta').value);
        if (!jmlPeserta || jmlPeserta < 1) {
            showToast('Silakan isi jumlah peserta terlebih dahulu!', 'warning');
            document.getElementById('jml_peserta').focus();
            return;
        }
        
        dropdownContainer.classList.toggle('dropdown-open');
    }

    // Old filterRuangan and updateRuanganList are removed, handled by renderRuanganDropdown

    async function checkAvailableRooms() {
        const tglMulai = document.getElementById('tanggal_pinjam').value;
        const wktMulai = document.getElementById('waktu_pinjam').value;
        const tglSelesai = document.getElementById('tanggal_kembali').value;
        const wktSelesai = document.getElementById('waktu_kembali').value;
        const ruanganHint = document.getElementById('ruangan-hint');
        
        if (!tglMulai || !wktMulai || !tglSelesai || !wktSelesai) {
            return; // Belum lengkap
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || document.querySelector('input[name="_token"]').value;

        const pengajuanId = {{ $pengajuan->id ?? 'null' }};

        ruanganHint.innerHTML = `<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span> Mengecek ketersediaan ruangan...`;
        ruanganHint.className = 'input-hint';

        try {
            const response = await fetch('{{ route('pengajuan.checkRooms') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    tanggal_mulai: tglMulai,
                    waktu_mulai: wktMulai,
                    tanggal_selesai: tglSelesai,
                    waktu_selesai: wktSelesai,
                    pengajuan_id: pengajuanId
                })
            });

            const data = await response.json();
            
            if (response.ok) {
                // Update bentrokIds state
                bentrokIds = data.bentrok_ids || [];
                
                // Re-render dropdown
                renderRuanganDropdown();
                
                // Jika ruangan terpilih saat ini masuk daftar bentrok, reset
                if (bentrokIds.includes(selectedRuanganId)) {
                    document.getElementById("ruangan-id-input").value = '';
                    document.getElementById("selected-ruangan").innerHTML = `Pilih Ruangan`;
                    selectedRuanganId = null;
                    showToast('Ruangan yang Anda pilih sebelumnya bentrok dengan jadwal lain. Silakan pilih ruangan baru.', 'warning');
                }
                
                // Update rekomendasi
                updateRekomendasi();
            } else {
                ruanganHint.innerHTML = `<i class="bi bi-exclamation-triangle text-danger me-1"></i> ${data.error || 'Terjadi kesalahan'}`;
                ruanganHint.className = 'input-hint input-danger';
                showToast(data.error || 'Gagal mengecek ruangan', 'danger');
            }
        } catch (error) {
            console.error('Error checking rooms:', error);
            ruanganHint.innerHTML = `<i class="bi bi-exclamation-triangle text-danger me-1"></i> Gagal mengecek ruangan.`;
        }
    }

    function handlePesertaChange() {
        currentJmlPeserta = parseInt(document.getElementById('jml_peserta').value) || 0;
        updateRekomendasi();
    }

    function updateRekomendasi() {
        const jmlPeserta = currentJmlPeserta;
        const ruanganHint = document.getElementById('ruangan-hint');
        const submitBtn = document.getElementById('submit-btn');
        
        const tglMulai = document.getElementById('tanggal_pinjam').value;
        const wktMulai = document.getElementById('waktu_pinjam').value;
        const tglSelesai = document.getElementById('tanggal_kembali').value;
        const wktSelesai = document.getElementById('waktu_kembali').value;
        
        if (!tglMulai || !wktMulai || !tglSelesai || !wktSelesai) {
            ruanganHint.textContent = 'Silakan lengkapi Tanggal & Waktu terlebih dahulu';
            ruanganHint.className = 'input-hint';
            submitBtn.disabled = true;
            renderRuanganDropdown();
            return;
        }

        if (!jmlPeserta || jmlPeserta < 1) {
            ruanganHint.textContent = 'Masukkan jumlah peserta untuk melihat rekomendasi ruangan';
            ruanganHint.className = 'input-hint';
            submitBtn.disabled = true;
            renderRuanganDropdown();
            return;
        }
        
        // Render dropdown updates the UI
        renderRuanganDropdown();
        
        const availableRuangan = ruangansArray.filter(r => r.jml_peserta >= jmlPeserta && !bentrokIds.includes(r.id));
        const unavailableRuangan = ruangansArray.filter(r => r.jml_peserta < jmlPeserta || bentrokIds.includes(r.id));
        
        // Rekomendasi ruangan
        if (availableRuangan.length > 0) {
            availableRuangan.sort((a, b) => a.jml_peserta - b.jml_peserta);
            const recommended = availableRuangan[0];
            
            // Auto-select jika belum ada pilihan atau pilihan lama kapasitasnya kurang/bentrok
            if (!selectedRuanganId || unavailableRuangan.some(r => r.id === selectedRuanganId) || !ruangansArray.some(r => r.id === selectedRuanganId)) {
                document.getElementById("ruangan-id-input").value = recommended.id;
                setSelectedDisplay(recommended.nama_ruangan, recommended.jml_peserta);
                selectedRuanganId = recommended.id;
                
                ruanganHint.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i> Ruangan <strong>${recommended.nama_ruangan}</strong> direkomendasikan untuk ${jmlPeserta} peserta`;
                ruanganHint.className = 'input-hint input-success';
                
                // Re-render again to show the checkmark on the new selected item
                renderRuanganDropdown();
            } else {
                ruanganHint.innerHTML = `<i class="bi bi-info-circle text-primary me-1"></i> ${availableRuangan.length} ruangan tersedia untuk ${jmlPeserta} peserta`;
                ruanganHint.className = 'input-hint';
            }
            
            submitBtn.disabled = false;
        } else {
            if (ruangansArray.length > 0 && ruangansArray.some(r => !bentrokIds.includes(r.id))) {
                ruanganHint.innerHTML = `<i class="bi bi-exclamation-triangle text-warning me-1"></i> Tidak ada ruangan yang dapat menampung ${jmlPeserta} peserta pada waktu tersebut!`;
            } else {
                ruanganHint.innerHTML = `<i class="bi bi-exclamation-triangle text-warning me-1"></i> Tidak ada ruangan yang tersedia sama sekali pada waktu tersebut!`;
            }
            ruanganHint.className = 'input-hint input-warning';
            document.getElementById("ruangan-id-input").value = '';
            document.getElementById("selected-ruangan").textContent = 'Tidak Ada Ruangan Tersedia';
            selectedRuanganId = null;
            submitBtn.disabled = true;
        }
    }

    // Helper: update tampilan teks di tombol dropdown
    function setSelectedDisplay(nama, kapasitas) {
        document.getElementById("selected-ruangan").innerHTML = `
            <span style="display:flex; align-items:center; gap:8px;">
                <i class="bi bi-door-open text-primary"></i>
                <span>${nama}</span>
                <span class="ruangan-capacity-badge" style="margin-left:auto;"><i class="bi bi-people-fill"></i> ${kapasitas}</span>
            </span>
        `;
    }

    document.getElementById("ruangan-dropdown-content").addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (target && !target.classList.contains('disabled')) {
            event.preventDefault();
            
            const selectedValue = target.getAttribute('data-value');
            const selectedNama = target.getAttribute('data-nama');
            const selectedKapasitas = target.getAttribute('data-kapasitas');

            document.getElementById("ruangan-id-input").value = selectedValue;
            setSelectedDisplay(selectedNama, selectedKapasitas);
            
            selectedRuanganId = parseInt(selectedValue);
            
            // Render ulang supaya "check" mark pindah
            renderRuanganDropdown();
            
            document.getElementById('ruangan-dropdown-container').classList.remove('dropdown-open');
            
            // Update hint
            const jmlPeserta = parseInt(document.getElementById('jml_peserta').value);
            const ruanganHint = document.getElementById('ruangan-hint');
            
            if (jmlPeserta && parseInt(selectedKapasitas) >= jmlPeserta) {
                ruanganHint.innerHTML = `<i class="bi bi-check2-circle text-success me-1"></i> Ruangan <strong>${selectedNama}</strong> terpilih`;
                ruanganHint.className = 'input-hint input-success';
            }
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdownContainer = document.getElementById('ruangan-dropdown-container');
        if (!dropdownContainer.contains(event.target) && dropdownContainer.classList.contains('dropdown-open')) {
            dropdownContainer.classList.remove('dropdown-open');
        }
    });

    // Toast notification
    function showToast(message, type = "info") {
        const toast = document.createElement("div");
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed`;
        toast.style = "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
        toast.setAttribute("role", "alert");
        toast.setAttribute("aria-live", "assertive");
        toast.setAttribute("aria-atomic", "true");
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
        toast.addEventListener("hidden.bs.toast", () => {
            document.body.removeChild(toast);
        });
    }
    // Form submission loading state and client validation
    document.getElementById('pengajuan-form').addEventListener('submit', function(e) {
        // Clear previous validation errors
        document.querySelectorAll('.client-error').forEach(el => el.remove());
        document.querySelectorAll('.is-invalid-client').forEach(el => el.classList.remove('is-invalid-client'));
        document.querySelectorAll('.field-error-highlight').forEach(el => el.classList.remove('field-error-highlight'));

        const errors = [];

        // 1. Judul Kegiatan
        const judul = document.getElementById('judul_kegiatan');
        if (!judul.value.trim()) {
            errors.push({ field: judul, message: 'Judul kegiatan wajib diisi.' });
        }

        // 2. Deskripsi Kegiatan
        const kegiatan = document.getElementById('kegiatan');
        if (!kegiatan.value.trim()) {
            errors.push({ field: kegiatan, message: 'Deskripsi kegiatan wajib diisi.' });
        } else if (kegiatan.value.trim().split(/\s+/).length < 10) {
            errors.push({ field: kegiatan, message: 'Deskripsi minimal 10 kata.' });
        }

        // 3. Tanggal Pinjam
        const tglPinjam = document.getElementById('tanggal_pinjam');
        if (!tglPinjam.value) {
            errors.push({ field: tglPinjam, message: 'Tanggal mulai wajib diisi.' });
        }

        // 4. Tanggal Kembali
        const tglKembali = document.getElementById('tanggal_kembali');
        if (!tglKembali.value) {
            errors.push({ field: tglKembali, message: 'Tanggal selesai wajib diisi.' });
        } else if (tglPinjam.value && new Date(tglKembali.value) < new Date(tglPinjam.value)) {
            errors.push({ field: tglKembali, message: 'Tanggal selesai harus sama atau setelah tanggal mulai.' });
        }

        // 5. Waktu Pinjam
        const waktuPinjam = document.getElementById('waktu_pinjam');
        if (!waktuPinjam.value) {
            errors.push({ field: waktuPinjam, message: 'Waktu mulai wajib dipilih.' });
        }

        // 6. Waktu Kembali
        const waktuKembali = document.getElementById('waktu_kembali');
        if (!waktuKembali.value) {
            errors.push({ field: waktuKembali, message: 'Waktu selesai wajib dipilih.' });
        } else if (waktuPinjam.value && tglPinjam.value === tglKembali.value && waktuKembali.value <= waktuPinjam.value) {
            errors.push({ field: waktuKembali, message: 'Waktu selesai harus setelah waktu mulai pada hari yang sama.' });
        }

        // 7. Jumlah Peserta
        const jmlPeserta = document.getElementById('jml_peserta');
        if (!jmlPeserta.value) {
            errors.push({ field: jmlPeserta, message: 'Jumlah peserta wajib diisi.' });
        } else if (parseInt(jmlPeserta.value) < 1) {
            errors.push({ field: jmlPeserta, message: 'Jumlah peserta minimal 1 orang.' });
        }

        // 8. Ruangan
        const ruanganId = document.getElementById('ruangan-id-input');
        const dropdownBtn = document.getElementById('dropdown-button');
        if (!ruanganId.value) {
            errors.push({ field: dropdownBtn, message: 'Ruangan wajib dipilih.' });
        }

        // If errors found, display them
        if (errors.length > 0) {
            e.preventDefault();

            errors.forEach(err => {
                // Add red highlight
                err.field.classList.add('is-invalid-client');
                if (err.field.classList.contains('custom-dropdown-button')) {
                    err.field.classList.add('field-error-highlight');
                }

                // Add error message below the field
                const errorMsg = document.createElement('div');
                errorMsg.className = 'client-error text-danger mt-1 fs-sm';
                errorMsg.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + err.message;
                err.field.parentNode.insertBefore(errorMsg, err.field.nextSibling);
            });

            // Scroll to first error
            errors[0].field.scrollIntoView({ behavior: 'smooth', block: 'center' });

            return false;
        }

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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const initialJmlPeserta = document.getElementById('jml_peserta').value;
        if (initialJmlPeserta && initialJmlPeserta > 0) {
            handlePesertaChange();
        }
        
        // Set min date for return date
        const startDateInput = document.getElementById('tanggal_pinjam');
        const endDateInput = document.getElementById('tanggal_kembali');
        
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (new Date(endDateInput.value) < new Date(this.value)) {
                endDateInput.value = this.value;
            }
        });
        
        // Initialize date inputs
        const today = new Date().toISOString().split('T')[0];
        if (!startDateInput.value) startDateInput.min = today;
        if (!endDateInput.value) endDateInput.min = today;

        // Add event listeners for date/time changes to fetch available rooms
        const timeInputs = [
            'tanggal_pinjam', 
            'tanggal_kembali', 
            'waktu_pinjam', 
            'waktu_kembali'
        ];
        
        timeInputs.forEach(id => {
            document.getElementById(id).addEventListener('change', checkAvailableRooms);
        });

        // initial check if all data is present (e.g. edit mode)
        if (startDateInput.value && endDateInput.value && document.getElementById('waktu_pinjam').value && document.getElementById('waktu_kembali').value) {
            checkAvailableRooms();
        } else {
            // initial state: disable room selection until time is picked
            document.getElementById("selected-ruangan").textContent = 'Pilih Tanggal & Waktu Dulu';
            document.getElementById('ruangan-hint').textContent = 'Silakan lengkapi Tanggal & Waktu terlebih dahulu';
            document.getElementById('submit-btn').disabled = true;
        }
    });
</script>
@endsection