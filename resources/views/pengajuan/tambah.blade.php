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
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #C9DFF2;
    }
    .main-content {
        padding: 80px 20px 20px;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.1);
    }
    .btn i {
        margin-right: 6px;
        font-size: 13px;
        vertical-align: middle;
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
        background-color: #fff;
        min-width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 100;
        border: 1px solid #ddd;
        border-radius: 0.25rem;
        overflow: hidden;
        max-height: 200px;
        overflow-y: auto;
    }
    .custom-dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .custom-dropdown-content a:hover {
        background-color: #f0f2f5;
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
    .ruangan-item.disabled {
        background-color: #f8f9fa;
        color: #6c757d;
        pointer-events: none;
        opacity: 0.6;
        text-decoration: line-through;
    }
    .ruangan-item.recommended {
        background-color: #d1ecf1;
        font-weight: bold;
        border-left: 4px solid #0c5460;
    }
    .ruangan-item.available {
        background-color: #d4edda;
    }
</style>
    <div class="main-content">
        <div class="container d-flex justify-content-center">
            <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 12px;">
                <h4 class="text-left mb-4 fw-bold">{{ $isEdit ? 'Edit Pengajuan' : 'Form Pengajuan Peminjaman Ruangan' }}</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ $isEdit ? route('pengajuan.update', $pengajuan) : route('pengajuan.store') }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="judul_kegiatan" class="form-label">Judul Kegiatan</label>
                        <input type="text" name="judul_kegiatan" id="judul_kegiatan" class="form-control" placeholder="Judul Singkat Kegiatan" value="{{ old('judul_kegiatan', $pengajuan->judul_kegiatan ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="kegiatan" class="form-label">Kegiatan</label>
                        <textarea name="kegiatan" id="kegiatan" class="form-control" rows="2" placeholder="Deskripsi Lengkap Kegiatan" required>{{ old('kegiatan', $pengajuan->kegiatan ?? '') }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="jml_peserta" class="form-label">Jumlah Peserta <span class="text-danger">*Isi Dulu</span></label>
                            <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" placeholder="Masukkan Jumlah Peserta Dulu" value="{{ old('jml_peserta', $pengajuan->jml_peserta ?? '') }}" required min="1" oninput="handlePesertaChange()">
                            <small class="text-muted">Isi jumlah peserta untuk melihat ruangan yang tersedia</small>
                        </div>
                        <div class="col">
                            <label for="ruangan_id" class="form-label">Ruangan</label>
                            <input type="hidden" name="ruangan_id" id="ruangan-id-input" value="{{ old('ruangan_id', $pengajuan->ruangan_id ?? '') }}" required>
                            <div class="custom-dropdown-container">
                                <div class="custom-dropdown-button form-control" onclick="toggleDropdown()" id="dropdown-button">
                                    <span id="selected-ruangan">
                                        {{ $selectedRuanganName }}
                                    </span>
                                    <span>&#9660;</span>
                                </div>
                                <div id="ruangan-dropdown-content" class="custom-dropdown-content">
                                    <input type="text" class="custom-dropdown-input" onkeyup="filterRuangan()" placeholder="Cari Ruangan...">
                                    @foreach($ruangans as $ruangan)
                                        <a href="#" 
                                           data-value="{{ $ruangan->id }}" 
                                           data-nama="{{ $ruangan->nama_ruangan }}"
                                           data-kapasitas="{{ $ruangan->jml_peserta }}"
                                           class="ruangan-item">
                                           {{ $ruangan->nama_ruangan }} (Kapasitas: {{ $ruangan->jml_peserta }})
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <small id="ruangan-hint" class="text-muted"></small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', ($pengajuan->tanggal_mulai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', ($pengajuan->tanggal_selesai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col">
                            <label for="waktu_pinjam" class="form-label">Waktu Pinjam</label>
                            <select name="waktu_pinjam" id="waktu_pinjam" class="form-control" required>
                                <option value="">-- Pilih Waktu --</option>
                                @php
                                    $oldWaktuPinjam = old('waktu_pinjam', ($pengajuan->tanggal_mulai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('H:i') : '');
                                @endphp
                                @for($hour = 6; $hour <= 16; $hour++)
                                    @foreach(['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                            // Skip 16:30 karena minimal 2 jam, jadi maksimal kembali 18:00
                                            if($hour == 16 && $minute == '30') continue;
                                        @endphp
                                        <option value="{{ $time }}" {{ $oldWaktuPinjam == $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endforeach
                                @endfor
                            </select>
                            <small class="text-muted">Maksimal pukul 16:00 (minimal pinjam 2 jam, selesai max 18:00)</small>
                        </div>
                        <div class="col">
                            <label for="waktu_kembali" class="form-label">Waktu Kembali</label>
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
                            <small class="text-muted">Maksimal pukul 18:00 WIB</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-dark px-4">Cancel</a>
                        <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>{{ $isEdit ? 'Update' : 'Submit' }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<script>
    // Menyimpan data ruangan dari PHP ke variabel JavaScript untuk akses mudah
    const ruangansData = @json($ruangans->keyBy('id'));
    
    // Array untuk sorting
    const ruangansArray = @json($ruangans->values());

    function toggleDropdown() {
        const jmlPeserta = parseInt(document.getElementById('jml_peserta').value);
        
        if (!jmlPeserta || jmlPeserta < 1) {
            alert('Silakan isi jumlah peserta terlebih dahulu!');
            document.getElementById('jml_peserta').focus();
            return;
        }
        
        document.getElementById("ruangan-dropdown-content").classList.toggle("show-dropdown");
    }

    function filterRuangan() {
        const input = event.target;
        const filter = input.value.toUpperCase();
        const div = document.getElementById("ruangan-dropdown-content");
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

    // Fungsi utama untuk handle perubahan jumlah peserta
    function handlePesertaChange() {
        const jmlPeserta = parseInt(document.getElementById('jml_peserta').value);
        const dropdownButton = document.getElementById('dropdown-button');
        const ruanganHint = document.getElementById('ruangan-hint');
        
        if (!jmlPeserta || jmlPeserta < 1) {
            // Reset semua ruangan
            resetAllRuangan();
            dropdownButton.style.pointerEvents = 'none';
            dropdownButton.style.opacity = '0.6';
            ruanganHint.textContent = 'Isi jumlah peserta untuk melihat ruangan tersedia';
            ruanganHint.style.color = '#6c757d';
            return;
        }
        
        // Enable dropdown
        dropdownButton.style.pointerEvents = 'auto';
        dropdownButton.style.opacity = '1';
        
        // Filter dan classify ruangan
        const availableRuangan = [];
        const disabledRuangan = [];
        
        ruangansArray.forEach(ruangan => {
            if (ruangan.jml_peserta >= jmlPeserta) {
                availableRuangan.push(ruangan);
            } else {
                disabledRuangan.push(ruangan);
            }
        });
        
        // Update tampilan ruangan di dropdown
        const allRuanganItems = document.querySelectorAll('.ruangan-item');
        allRuanganItems.forEach(item => {
            const kapasitas = parseInt(item.getAttribute('data-kapasitas'));
            
            // Remove all classes first
            item.classList.remove('disabled', 'recommended', 'available');
            
            if (kapasitas < jmlPeserta) {
                // Ruangan terlalu kecil - disabled
                item.classList.add('disabled');
                item.innerHTML = item.getAttribute('data-nama') + ' (Kapasitas: ' + kapasitas + ') - <em>Terlalu Kecil</em>';
            } else {
                // Ruangan tersedia
                item.classList.add('available');
                item.innerHTML = item.getAttribute('data-nama') + ' (Kapasitas: ' + kapasitas + ')';
            }
        });
        
        // Auto-select ruangan dengan kapasitas terdekat
        if (availableRuangan.length > 0) {
            // Sort berdasarkan kapasitas (ascending)
            availableRuangan.sort((a, b) => a.jml_peserta - b.jml_peserta);
            
            const recommendedRuangan = availableRuangan[0]; // Ruangan dengan kapasitas terdekat
            
            // Highlight recommended
            const recommendedItem = document.querySelector(`.ruangan-item[data-value="${recommendedRuangan.id}"]`);
            if (recommendedItem) {
                recommendedItem.classList.remove('available');
                recommendedItem.classList.add('recommended');
                recommendedItem.innerHTML = recommendedItem.getAttribute('data-nama') + ' (Kapasitas: ' + recommendedRuangan.jml_peserta + ') - <strong>⭐ Direkomendasikan</strong>';
            }
            
            // Auto-select recommended ruangan
            document.getElementById("ruangan-id-input").value = recommendedRuangan.id;
            document.getElementById("selected-ruangan").textContent = recommendedRuangan.nama_ruangan + ' (Kapasitas: ' + recommendedRuangan.jml_peserta + ')';
            
            // Update hint
            ruanganHint.textContent = `✓ Ruangan "${recommendedRuangan.nama_ruangan}" dipilih otomatis (paling efisien untuk ${jmlPeserta} peserta)`;
            ruanganHint.style.color = '#0c5460';
            ruanganHint.style.fontWeight = '500';
        } else {
            // Tidak ada ruangan yang cukup
            ruanganHint.textContent = `⚠ Tidak ada ruangan dengan kapasitas ${jmlPeserta} peserta atau lebih!`;
            ruanganHint.style.color = '#dc3545';
            ruanganHint.style.fontWeight = '500';
            
            // Clear selection
            document.getElementById("ruangan-id-input").value = '';
            document.getElementById("selected-ruangan").textContent = 'Tidak Ada Ruangan Tersedia';
        }
    }

    function resetAllRuangan() {
        const allRuanganItems = document.querySelectorAll('.ruangan-item');
        allRuanganItems.forEach(item => {
            item.classList.remove('disabled', 'recommended', 'available');
            const nama = item.getAttribute('data-nama');
            const kapasitas = item.getAttribute('data-kapasitas');
            item.innerHTML = nama + ' (Kapasitas: ' + kapasitas + ')';
        });
        
        // Clear selection
        document.getElementById("ruangan-id-input").value = '';
        document.getElementById("selected-ruangan").textContent = 'Pilih Ruangan';
    }

    document.getElementById("ruangan-dropdown-content").addEventListener('click', function(event) {
        if (event.target.tagName === 'A' && !event.target.classList.contains('disabled')) {
            const selectedValue = event.target.getAttribute('data-value');
            const selectedNama = event.target.getAttribute('data-nama');
            const selectedKapasitas = event.target.getAttribute('data-kapasitas');

            document.getElementById("ruangan-id-input").value = selectedValue;
            document.getElementById("selected-ruangan").textContent = selectedNama + ' (Kapasitas: ' + selectedKapasitas + ')';
            
            document.getElementById("ruangan-dropdown-content").classList.remove("show-dropdown");
            event.preventDefault();
        }
    });

    window.onclick = function(event) {
        if (!event.target.matches('.custom-dropdown-button') && !event.target.matches('.custom-dropdown-input')) {
            const dropdown = document.getElementById("ruangan-dropdown-content");
            if (dropdown.classList.contains('show-dropdown')) {
                dropdown.classList.remove('show-dropdown');
            }
        }
    }

    // Jalankan saat halaman dimuat untuk mengatur kondisi awal
    document.addEventListener('DOMContentLoaded', function() {
        const initialJmlPeserta = document.getElementById('jml_peserta').value;
        const dropdownButton = document.getElementById('dropdown-button');
        
        if (initialJmlPeserta && initialJmlPeserta > 0) {
            // Jika ada nilai peserta (edit mode atau validation error)
            handlePesertaChange();
        } else {
            // Disable dropdown jika belum ada jumlah peserta
            dropdownButton.style.pointerEvents = 'none';
            dropdownButton.style.opacity = '0.6';
            document.getElementById('ruangan-hint').textContent = 'Isi jumlah peserta untuk melihat ruangan tersedia';
            document.getElementById('ruangan-hint').style.color = '#6c757d';
        }
    });
</script>
@endsection

