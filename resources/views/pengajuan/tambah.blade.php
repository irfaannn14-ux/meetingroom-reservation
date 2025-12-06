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
        background: white;
        width: 100%;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 4px;
        max-height: 300px;
        overflow-y: auto;
        padding: 8px 0;
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
    
    .ruangan-item {
        padding: 12px 16px;
        display: block;
        color: var(--dark-color);
        text-decoration: none;
        transition: var(--transition);
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }
    
    .ruangan-item:last-child {
        border-bottom: none;
    }
    
    .ruangan-item:hover {
        background-color: #f0f7ff;
        color: var(--primary-color);
    }
    
    .ruangan-item.selected {
        background-color: #dbeafe;
        position: relative;
    }
    
    .ruangan-item.selected::after {
        content: '✓';
        position: absolute;
        right: 16px;
        color: var(--success-color);
        font-weight: bold;
    }
    
    .ruangan-item.disabled {
        background-color: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
        text-decoration: line-through;
    }
    
    .ruangan-item.disabled:hover {
        background-color: #f8fafc;
    }
    
    .ruangan-item.recommended {
        background-color: #f0fdf4;
        border-left: 3px solid var(--success-color);
        font-weight: 500;
    }
    
    .ruangan-item.recommended::after {
        content: '⭐';
        position: absolute;
        right: 16px;
        color: #f59e0b;
    }
    
    .ruangan-capacity-badge {
        display: inline-block;
        background: #dbeafe;
        color: var(--primary-color);
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        margin-left: 8px;
        font-weight: 500;
    }
    
    .ruangan-recommended-badge {
        display: inline-block;
        background: #dcfce7;
        color: #15803d;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        margin-left: 8px;
        font-weight: 600;
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

            <form action="{{ $isEdit ? route('pengajuan.update', $pengajuan) : route('pengajuan.store') }}" method="POST" id="pengajuan-form">
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
                                <div id="ruangan-dropdown-content" class="custom-dropdown-content">
                                    <input type="text" class="custom-dropdown-input" onkeyup="filterRuangan()" placeholder="Cari ruangan...">
                                    @foreach($ruangans as $ruangan)
                                        <a href="#" 
                                           data-value="{{ $ruangan->id }}" 
                                           data-nama="{{ $ruangan->nama_ruangan }}"
                                           data-kapasitas="{{ $ruangan->jml_peserta }}"
                                           class="ruangan-item{{ $selectedRuanganId == $ruangan->id ? ' selected' : '' }}">
                                           {{ $ruangan->nama_ruangan }}
                                           <span class="ruangan-capacity-badge">{{ $ruangan->jml_peserta }} orang</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <span id="ruangan-hint" class="input-hint">Pilih ruangan sesuai dengan kapasitas peserta</span>
                        </div>
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
    // Menyimpan data ruangan dari PHP ke variabel JavaScript untuk akses mudah
    const ruangansData = @json($ruangans->keyBy('id'));
    const ruangansArray = @json($ruangans->values());
    let selectedRuanganId = {{ $selectedRuanganId ?: 'null' }};

    function toggleDropdown() {
        const dropdownContainer = document.getElementById('ruangan-dropdown-container');
        const jmlPeserta = parseInt(document.getElementById('jml_peserta').value);
        
        if (!jmlPeserta || jmlPeserta < 1) {
            showToast('Silakan isi jumlah peserta terlebih dahulu!', 'warning');
            document.getElementById('jml_peserta').focus();
            return;
        }
        
        dropdownContainer.classList.toggle('dropdown-open');
    }

    function filterRuangan() {
        const filterText = event.target.value.toLowerCase();
        const items = document.querySelectorAll('.ruangan-item');
        
        items.forEach(item => {
            const ruanganName = item.getAttribute('data-nama').toLowerCase();
            item.style.display = ruanganName.includes(filterText) ? 'block' : 'none';
        });
    }

    function handlePesertaChange() {
        const jmlPeserta = parseInt(document.getElementById('jml_peserta').value);
        const ruanganHint = document.getElementById('ruangan-hint');
        const submitBtn = document.getElementById('submit-btn');
        
        if (!jmlPeserta || jmlPeserta < 1) {
            ruanganHint.textContent = 'Masukkan jumlah peserta untuk melihat rekomendasi ruangan';
            ruanganHint.className = 'input-hint';
            resetAllRuangan();
            submitBtn.disabled = true;
            return;
        }
        
        const availableRuangan = ruangansArray.filter(r => r.jml_peserta >= jmlPeserta);
        const unavailableRuangan = ruangansArray.filter(r => r.jml_peserta < jmlPeserta);
        
        // Reset semua item
        document.querySelectorAll('.ruangan-item').forEach(item => {
            item.classList.remove('disabled', 'recommended');
            const kapasitas = parseInt(item.getAttribute('data-kapasitas'));
            item.querySelector('.ruangan-capacity-badge').textContent = `${kapasitas} orang`;
        });
        
        // Tandai ruangan yang tidak tersedia
        unavailableRuangan.forEach(ruangan => {
            const item = document.querySelector(`.ruangan-item[data-value="${ruangan.id}"]`);
            if (item) {
                item.classList.add('disabled');
            }
        });
        
        // Rekomendasi ruangan
        if (availableRuangan.length > 0) {
            availableRuangan.sort((a, b) => a.jml_peserta - b.jml_peserta);
            const recommended = availableRuangan[0];
            
            const recommendedItem = document.querySelector(`.ruangan-item[data-value="${recommended.id}"]`);
            if (recommendedItem) {
                recommendedItem.classList.add('recommended');
                
                // Tambahkan badge rekomendasi
                if (!recommendedItem.querySelector('.ruangan-recommended-badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'ruangan-recommended-badge';
                    badge.textContent = 'Direkomendasikan';
                    recommendedItem.appendChild(badge);
                }
            }
            
            // Auto-select jika belum ada pilihan
            if (!selectedRuanganId || unavailableRuangan.some(r => r.id === selectedRuanganId)) {
                document.getElementById("ruangan-id-input").value = recommended.id;
                document.getElementById("selected-ruangan").innerHTML = `${recommended.nama_ruangan} <span class="ruangan-capacity-badge">${recommended.jml_peserta} orang</span>`;
                selectedRuanganId = recommended.id;
                
                ruanganHint.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i> Ruangan <strong>${recommended.nama_ruangan}</strong> direkomendasikan untuk ${jmlPeserta} peserta`;
                ruanganHint.className = 'input-hint input-success';
            } else {
                ruanganHint.innerHTML = `<i class="bi bi-info-circle text-primary me-1"></i> ${availableRuangan.length} ruangan tersedia untuk ${jmlPeserta} peserta`;
                ruanganHint.className = 'input-hint';
            }
            
            submitBtn.disabled = false;
        } else {
            ruanganHint.innerHTML = `<i class="bi bi-exclamation-triangle text-warning me-1"></i> Tidak ada ruangan yang dapat menampung ${jmlPeserta} peserta!`;
            ruanganHint.className = 'input-hint input-warning';
            document.getElementById("ruangan-id-input").value = '';
            document.getElementById("selected-ruangan").textContent = 'Tidak Ada Ruangan Tersedia';
            submitBtn.disabled = true;
        }
    }

    function resetAllRuangan() {
        document.querySelectorAll('.ruangan-item').forEach(item => {
            item.classList.remove('disabled', 'recommended');
            const kapasitas = parseInt(item.getAttribute('data-kapasitas'));
            item.querySelector('.ruangan-capacity-badge').textContent = `${kapasitas} orang`;
            const badge = item.querySelector('.ruangan-recommended-badge');
            if (badge) badge.remove();
        });
    }

    document.getElementById("ruangan-dropdown-content").addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (target && !target.classList.contains('disabled')) {
            event.preventDefault();
            
            const selectedValue = target.getAttribute('data-value');
            const selectedNama = target.getAttribute('data-nama');
            const selectedKapasitas = target.getAttribute('data-kapasitas');

            document.getElementById("ruangan-id-input").value = selectedValue;
            document.getElementById("selected-ruangan").innerHTML = `${selectedNama} <span class="ruangan-capacity-badge">${selectedKapasitas} orang</span>`;
            
            selectedRuanganId = parseInt(selectedValue);
            
            // Update visual selection
            document.querySelectorAll('.ruangan-item').forEach(item => {
                item.classList.remove('selected');
            });
            target.classList.add('selected');
            
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

    // Form submission loading state
    document.getElementById('pengajuan-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
        btn.disabled = true;
    });

    // Toast notification
    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed`;
        toast.style = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Initialize and show toast
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
        
        // Remove from DOM after hiding
        toast.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(toast);
        });
    }

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
    });
</script>
@endsection