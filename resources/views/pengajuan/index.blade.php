<?php ?>
@extends('layout.main')
@section('title', 'Pengajuan')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #C9DFF2;
        margin: 0;
        padding: 0;
    }
    .main-content {
        padding: 2rem;
        min-height: 100vh;
        background-color: #C9DFF2;
        margin-top: 60px;
    }
    .content {
        width: 100%;
        max-width: 2000px;
        margin: 0 auto;
    }
    .ruangan-table-container {
        background: #fff;
        padding: 2.5rem 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-top: 1.5rem;
    }
    .dashboard-title {
        margin-top: 0;
        margin-bottom: 1.2rem;
        font-size: 2rem;
        font-weight: 700;
        color: #010D26;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
        background: #fff;
    }
    th, td {
        padding: 0.85rem 1rem;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background-color: #C9DFF2;
        color: #010D26;
        font-weight: 700;
        border-bottom: 2px solid #B0C4DE;
    }
    tr {
        border-bottom: 1px solid #e0e0e0;
    }
    tr:last-child {
        border-bottom: none;
    }
    td {
        color: #222;
    }
    .btn-action {
        margin: 0 2px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    @media (max-width: 900px) {
        .main-content {
            margin-left: 0;
            padding: 1rem;
        }
        .content {
            max-width: 100%;
        }
        .ruangan-table-container {
            padding: 1rem 0.5rem;
            margin-top: 1rem;
        }
        table, th, td {
            font-size: 13px;
        }
        .dashboard-title {
            margin-bottom: 0.8rem;
        }
    }
    /* Modal styles */
    .modal {
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        box-sizing: border-box;
    }
    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        width: 100%;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease;
    }
    #detailModal .modal-content {
        max-width: 600px;
        text-align: left;
    }
    #confirmStatusModal .modal-content, #deleteConfirmModal .modal-content {
        max-width: 400px;
    }
    .modal-content h3 {
        margin-bottom: 15px;
        color: #010D26;
    }
    .modal-content p {
        margin-bottom: 25px;
    }
    .modal-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn i {
        margin-right: 8px;
    }
    .btn-success { background-color: #28a745; color: white; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .status-badge {
        color: #fff;
        padding: 0.35em 1.1em;
        border-radius: 16px;
        font-weight: 600;
        font-size: 0.9em;
        display: inline-block;
    }
    .status-pending { background: #fd7e14; }
    .status-disetujui { background: #28a745; }
    .status-ditolak { background: #dc3545; }

</style>

<div class="main-content">
    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
            <h1 class="dashboard-title">List Data Pengajuan</h1>
            <a href="{{ route('pengajuan.tambah') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Buat Pengajuan
            </a>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="ruangan-table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengaju</th>
                        <th>Kegiatan</th>
                        <th>Ruangan</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th>Jumlah Peserta</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $pengajuan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pengajuan->nama_pengaju }}</td>
                            <td>{{ $pengajuan->judul_kegiatan }}</td>
                            <td>{{ $pengajuan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d-m-Y H:i') }} WIB</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d-m-Y H:i') }} WIB</td>
                            <td>{{ $pengajuan->jml_peserta }}</td>
                            <td><span class="status-badge status-{{ strtolower($pengajuan->status) }}"> {{ ucfirst($pengajuan->status) }} </span></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Ikon pada tombol aksi telah diperbarui --}}
                                    <button type="button" class="btn btn-info btn-sm btn-action bi bi-search" onclick="openDetailModal({{ json_encode($pengajuan) }})" title="Lihat Detail"></button>
                                    <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn btn-success btn-sm btn-action nav-icon bi bi-pencil-fill" title="Edit"></a>
                                    <button type="button" class="btn btn-danger btn-sm btn-action bi bi-trash-fill" onclick="openDeleteModal({{ $pengajuan->id }})" title="Hapus"></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Tidak ada pengajuan yang menunggu persetujuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL 1: Detail Pengajuan --}}
<div id="detailModal" class="modal">
    <div class="modal-content">
        <h3>Detail Pengajuan</h3>
        <table class="table table-bordered text-start">
            <tr><th width="30%">ID Pengajuan</th><td id="modalId"></td></tr>
            <tr><th>Nama Pengaju</th><td id="modalNamaPengaju"></td></tr>
            <tr><th>Judul Kegiatan</th><td id="modalJudul"></td></tr>
            <tr><th>Deskripsi Kegiatan</th><td id="modalDeskripsi"></td></tr>
            <tr><th>Ruangan Dipesan</th><td id="modalRuangan"></td></tr>
            <tr><th>Waktu Pinjam</th><td id="modalMulai"></td></tr>
            <tr><th>Waktu Selesai</th><td id="modalSelesai"></td></tr>
            <tr><th>Jumlah Peserta</th><td id="modalPeserta"></td></tr>
            <tr><th>Status Saat Ini</th><td id="modalStatus"></td></tr>
        </table>
        {{-- Tombol diperbarui: posisi ditukar, ikon ditambahkan, tombol "Tutup" dihapus --}}
        <div class="modal-actions">
            <button class="btn btn-danger" onclick="openConfirmModal('ditolak')"><i class="bi bi-x-circle-fill"></i> Deny</button>
            <button class="btn btn-success" onclick="openConfirmModal('disetujui')"><i class="bi bi-check-circle-fill"></i> Approve</button>
        </div>
    </div>
</div>

{{-- MODAL 2: Konfirmasi Ubah Status --}}
<div id="confirmStatusModal" class="modal">
    <div class="modal-content">
        <h3>Konfirmasi Tindakan</h3>
        <p id="confirmMessage">Apakah Anda yakin?</p>
        <div class="modal-actions">
            <button class="btn btn-primary" onclick="executeStatusUpdate()">Ya, Lanjutkan</button>
            <button class="btn btn-secondary" onclick="closeModal('confirmStatusModal')">Batal</button>
        </div>
    </div>
</div>

{{-- MODAL 3: Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content">
        <h3>Konfirmasi Hapus Pengajuan</h3>
        <p>Apakah Anda yakin ingin menghapus pengajuan ini?</p>
        <div class="modal-actions">
            <button class="btn btn-danger" onclick="executeDelete()">Ya, Hapus</button>
            <button class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Batal</button>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk HAPUS --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Form tersembunyi untuk UPDATE STATUS --}}
<form id="statusUpdateForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="status" id="newStatusInput">
</form>

<script>
    // Variabel global untuk menyimpan konteks
    let pengajuanIdToDelete = null;
    let pengajuanToUpdate = null;
    let newStatus = '';

    // --- Fungsi untuk Modal Detail dan Update Status ---
    function openDetailModal(pengajuan) {
        pengajuanToUpdate = pengajuan; // Simpan seluruh objek pengajuan

        // Format tanggal
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        const tglMulai = new Date(pengajuan.tanggal_mulai).toLocaleDateString('id-ID', options);
        const tglSelesai = new Date(pengajuan.tanggal_selesai).toLocaleDateString('id-ID', options);

        // Isi data ke modal
        document.getElementById('modalId').innerText = pengajuan.id;
        document.getElementById('modalNamaPengaju').innerText = pengajuan.nama_pengaju;
        document.getElementById('modalJudul').innerText = pengajuan.judul_kegiatan;
        document.getElementById('modalDeskripsi').innerText = pengajuan.kegiatan;
        document.getElementById('modalRuangan').innerText = pengajuan.ruangan ? pengajuan.ruangan.nama_ruangan : 'N/A';
        document.getElementById('modalMulai').innerText = tglMulai.replace(/\./g, ':') + ' WIB';
        document.getElementById('modalSelesai').innerText = tglSelesai.replace(/\./g, ':') + ' WIB';
        document.getElementById('modalPeserta').innerText = pengajuan.jml_peserta + ' orang';
        document.getElementById('modalStatus').innerHTML = `<span class="status-badge status-${pengajuan.status.toLowerCase()}">${pengajuan.status.charAt(0).toUpperCase() + pengajuan.status.slice(1)}</span>`;
        
        document.getElementById('detailModal').style.display = 'flex';
    }

    function openConfirmModal(status) {
        newStatus = status;
        const actionText = status === 'disetujui' ? 'menyetujui' : 'menolak';
        document.getElementById('confirmMessage').innerText = `Apakah Anda yakin ingin ${actionText} pengajuan ini?`;
        
        closeModal('detailModal');
        document.getElementById('confirmStatusModal').style.display = 'flex';
    }

    function executeStatusUpdate() {
        if (pengajuanToUpdate && newStatus) {
            const statusForm = document.getElementById('statusUpdateForm');
            document.getElementById('newStatusInput').value = newStatus;
            statusForm.action = `/pengajuan/${pengajuanToUpdate.id}/status`;
            statusForm.submit();
        }
    }

    // --- Fungsi untuk Modal Hapus ---
    function openDeleteModal(id) {
        pengajuanIdToDelete = id;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    function executeDelete() {
        if (pengajuanIdToDelete) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/pengajuan/${pengajuanIdToDelete}`;
            deleteForm.submit();
        }
    }
    
    // --- Fungsi Helper ---
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Event listener untuk menutup modal saat klik di luar area konten
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    });

</script>
@endsection

