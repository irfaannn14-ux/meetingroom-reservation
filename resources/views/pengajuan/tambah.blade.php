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
                            <label for="ruangan_id" class="form-label">Ruangan</label>
                            <input type="hidden" name="ruangan_id" id="ruangan-id-input" value="{{ old('ruangan_id', $pengajuan->ruangan_id ?? '') }}" required>
                            <div class="custom-dropdown-container">
                                <div class="custom-dropdown-button form-control" onclick="toggleDropdown()">
                                    <span id="selected-ruangan">
                                        {{ $selectedRuanganName }}
                                    </span>
                                    <span>&#9660;</span>
                                </div>
                                <div id="ruangan-dropdown-content" class="custom-dropdown-content">
                                    <input type="text" class="custom-dropdown-input" onkeyup="filterRuangan()" placeholder="Cari Ruangan...">
                                    @foreach($ruangans as $ruangan)
                                        <a href="#" data-value="{{ $ruangan->id }}" data-nama="{{ $ruangan->nama_ruangan }}">{{ $ruangan->nama_ruangan }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <label for="jml_peserta" class="form-label">Jumlah Peserta</label>
                            <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" placeholder="Jumlah Peserta Kegiatan" value="{{ old('jml_peserta', $pengajuan->jml_peserta ?? '') }}" required>
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
                            <input type="time" name="waktu_pinjam" id="waktu_pinjam" class="form-control" value="{{ old('waktu_pinjam', ($pengajuan->tanggal_mulai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('H:i') : '') }}" required>
                        </div>
                        <div class="col">
                            <label for="waktu_kembali" class="form-label">Waktu Kembali</label>
                            <input type="time" name="waktu_kembali" id="waktu_kembali" class="form-control" value="{{ old('waktu_kembali', ($pengajuan->tanggal_selesai ?? null) ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('H:i') : '') }}" required>
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

    function toggleDropdown() {
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

    // Fungsi baru untuk memperbarui placeholder jumlah peserta
    function updatePesertaPlaceholder(ruanganId) {
        const jmlPesertaInput = document.getElementById('jml_peserta');
        const selectedRuangan = ruangansData[ruanganId];

        if (selectedRuangan) {
            // Jika ruangan ditemukan, set placeholder dengan kapasitasnya
            jmlPesertaInput.placeholder = 'Maksimal: ' + selectedRuangan.jml_peserta + ' orang';
        } else {
            // Jika tidak, kembalikan ke default
            jmlPesertaInput.placeholder = 'Jumlah Peserta Kegiatan';
        }
    }

    document.getElementById("ruangan-dropdown-content").addEventListener('click', function(event) {
        if (event.target.tagName === 'A') {
            const selectedValue = event.target.getAttribute('data-value');
            const selectedNama = event.target.getAttribute('data-nama');

            document.getElementById("ruangan-id-input").value = selectedValue;
            document.getElementById("selected-ruangan").textContent = selectedNama;
            
            // Panggil fungsi untuk memperbarui placeholder setiap kali ruangan dipilih
            updatePesertaPlaceholder(selectedValue);
            
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

    // Jalankan saat halaman dimuat untuk mengatur placeholder awal jika dalam mode edit atau ada error validasi
    document.addEventListener('DOMContentLoaded', function() {
        const initialRuanganId = document.getElementById('ruangan-id-input').value;
        if (initialRuanganId) {
            updatePesertaPlaceholder(initialRuanganId);
        }
    });
</script>
@endsection

