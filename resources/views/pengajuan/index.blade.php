<?php ?>
@extends('layout.main')
@section('title', 'Pengajuan')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
    .main-content {
        padding: 2rem;
        min-height: 100vh;
        margin-top: 60px;
    }
    .content {
        width: 100%;
        max-width: 2000px;
        margin: 0 auto;
    }
    .ruangan-table-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 2.5rem 2rem;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        margin-top: 1.5rem;
    }
    .dashboard-title {
        margin-top: 0;
        margin-bottom: 1.2rem;
        font-size: 2rem;
        font-weight: 700;
        color: #ffffff !important;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
        background: #fff;
        table-layout: fixed; /* Diperbarui: Mengatur layout tabel */
    }
    th, td {
        padding: 0.85rem 1rem;
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
    }
    th:first-child,
    td:first-child {
        width: 60px;
    }
    /* Diperbarui: Mengatur lebar kolom aksi */
    th:last-child,
    td:last-child {
        width: 160px;
    }
    th {
        background-color: #010D26 !important;
        color: #ffffff !important;
        font-weight: 700;
        border-bottom: 2px solid rgba(255,255,255,0.06);
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
    td .btn-action.btn-sm {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    /* Styles for the new search bar */
    .search-container {
        position: relative;
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    #searchInput {
        padding-left: 40px;
        border-radius: 8px;
        height: 44px;
        width: 320px;
        border: 1px solid #ced4da;
    }
    /* Modal styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
    }
    /* Base z-index for modals */
    #detailModal { z-index: 1000; }
    #confirmStatusModal { z-index: 1001; }
    #deleteConfirmModal { z-index: 1002; }
    
    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        width: 100%;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.3s ease;
        position: relative;
    }
    #detailModal .modal-dialog {
        max-width: 600px;
    }
    #detailModal .modal-content {
        text-align: left;
    }
    #confirmStatusModal .modal-dialog, 
    #deleteConfirmModal .modal-dialog {
        max-width: 400px;
    }
    .modal-header {
        background-color: #010D26;
        color: #ffffff;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
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
    .btn-info { background-color: #17a2b8; color: white; }
    .btn-success { background-color: #28a745; color: white; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    
    /* Custom Add Button with Gradient like Sign In */
    .btn-add-custom {
        background: linear-gradient(135deg, #1D64F2 0%, #010D26 100%);
        color: white !important;
        border: none;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(29, 100, 242, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-add-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 100, 242, 0.4);
        color: white !important;
    }
    .btn-add-custom:active {
        transform: translateY(0);
    }
    
    /* Brand primary for add buttons */
    .btn-primary {
        background-color: #010D26 !important;
        color: #ffffff !important;
        border-color: #010D26 !important;
        box-shadow: none !important;
    }
    .btn-primary:hover {
        background-color: #ffffff !important;
        color: #010D26 !important;
        border-color: transparent !important;
        box-shadow: inset 0 0 0 1px rgba(1,13,38,0.06) !important;
    }
    
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

    /* Custom Close Button */
    .btn-close-custom {
        background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        color: white;
        font-size: 16px;
    }
    .btn-close-custom:hover {
        transform: translateY(-2px) rotate(90deg);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }
    .btn-close-custom:active {
        transform: translateY(0) rotate(90deg);
    }
    .btn-close-custom i {
        margin: 0;
    }

</style>

<div class="main-content">
    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
            <h1 class="dashboard-title">List Data Pengajuan</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari pengaju, kegiatan, ruangan...">
                </div>
                <a href="{{ route('pengajuan.tambah') }}" class="btn btn-add-custom">
                    <i class="bi bi-plus-circle-fill"></i> Buat Pengajuan
                </a>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="ruangan-table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengaju</th>
                        <th>Judul Kegiatan</th>
                        <th>Ruangan</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th>Jumlah Peserta</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengajuanTableBody">
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
                                    <button type="button" class="btn btn-info btn-sm btn-action bi bi-search" onclick="openDetailModal({{ json_encode($pengajuan) }})" title="Lihat Detail"></button>
                                    @if(session('user_role') !== 'OPD')
                                    <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn btn-success btn-sm btn-action bi bi-pencil-fill" title="Edit"></a>
                                    <button type="button" class="btn btn-danger btn-sm btn-action bi bi-trash-fill" onclick="openDeleteModal({{ $pengajuan->id }})" title="Hapus"></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Tidak ada pengajuan yang menunggu persetujuan.</td>
                        </tr>
                    @endforelse
                    {{-- Baris untuk pesan "tidak ditemukan" --}}
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="9" class="text-center py-4">Tidak ada pengajuan yang cocok dengan pencarian Anda.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL 1: Detail Pengajuan --}}
<div id="detailModal" class="modal">
    <div class="modal-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 style="margin-bottom: 0;">Detail Pengajuan</h3>
            <button type="button" class="btn-close-custom" onclick="closeModal('detailModal')" title="Tutup">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <table class="table table-bordered text-start">
            <tr><th width="30%">Nama Pengaju</th><td id="modalNamaPengaju"></td></tr>
            <tr><th>Organisasi/Role</th><td id="modalOrganisasi"></td></tr>
            <tr><th>Judul Kegiatan</th><td id="modalJudul"></td></tr>
            <tr><th>Deskripsi Kegiatan</th><td id="modalDeskripsi"></td></tr>
            <tr><th>Ruangan Dipesan</th><td id="modalRuangan"></td></tr>
            <tr><th>Waktu Pinjam</th><td id="modalMulai"></td></tr>
            <tr><th>Waktu Selesai</th><td id="modalSelesai"></td></tr>
            <tr><th>Jumlah Peserta</th><td id="modalPeserta"></td></tr>
            <tr><th>Status Saat Ini</th><td id="modalStatus"></td></tr>
            <tr><th>Contact Person</th><td><a id="modalContactPersonWa" href="#" target="_blank" class="btn btn-success btn-sm"><i class="bi bi-whatsapp"></i> WhatsApp</a></td></tr>
        </table>
        @if(session('user_role') !== 'OPD')
        <div class="modal-actions">
            <button class="btn btn-danger" onclick="openConfirmModal('ditolak')"><i class="bi bi-x-circle-fill"></i> Deny</button>
            <button class="btn btn-success" onclick="openConfirmModal('disetujui')"><i class="bi bi-check-circle-fill"></i> Approve</button>
        </div>
        @else
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('detailModal')"><i class="bi bi-x-circle"></i> Tutup</button>
        </div>
        @endif
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
        document.getElementById('modalNamaPengaju').innerText = pengajuan.nama_pengaju;
        document.getElementById('modalJudul').innerText = pengajuan.judul_kegiatan;
        document.getElementById('modalDeskripsi').innerText = pengajuan.kegiatan;
        document.getElementById('modalRuangan').innerText = pengajuan.ruangan ? pengajuan.ruangan.nama_ruangan : 'N/A';
        document.getElementById('modalMulai').innerText = tglMulai.replace(/\./g, ':') + ' WIB';
        document.getElementById('modalSelesai').innerText = tglSelesai.replace(/\./g, ':') + ' WIB';
        document.getElementById('modalPeserta').innerText = pengajuan.jml_peserta + ' orang';
        document.getElementById('modalStatus').innerHTML = `<span class="status-badge status-${pengajuan.status.toLowerCase()}">${pengajuan.status.charAt(0).toUpperCase() + pengajuan.status.slice(1)}</span>`;
        
        let roleOrOrgText = 'N/A';
        if (pengajuan.user) {
            if (pengajuan.user.role === 'OPD') {
                if (pengajuan.user.organization && pengajuan.user.organization.organization_name) {
                    roleOrOrgText = `OPD - ${pengajuan.user.organization.organization_name}`;
                } else {
                    roleOrOrgText = 'OPD - (Data Organisasi Tidak Ditemukan)';
                }
            } else {
                roleOrOrgText = pengajuan.user.role;
            }
        }
        document.getElementById('modalOrganisasi').innerText = roleOrOrgText;

        // Set WhatsApp link
        const waButton = document.getElementById('modalContactPersonWa');
        if (pengajuan.user && pengajuan.user.no_wa) {
            // Hapus '0' di depan jika ada dan tambahkan '62'
            const phoneNumber = pengajuan.user.no_wa.startsWith('0') ? pengajuan.user.no_wa.substring(1) : pengajuan.user.no_wa;
            waButton.href = `https://wa.me/62${phoneNumber}`;
            waButton.style.display = 'inline-flex'; // Tampilkan tombol
        } else {
            waButton.href = '#';
            waButton.style.display = 'none'; // Sembunyikan tombol jika no_wa tidak tersedia
        }
        openModal('detailModal');
    }

    function openConfirmModal(status) {
        newStatus = status;
        const actionText = status === 'disetujui' ? 'menyetujui' : 'menolak';
        document.getElementById('confirmMessage').innerText = `Apakah Anda yakin ingin ${actionText} pengajuan ini?`;
        
        closeModal('detailModal');
        openModal('confirmStatusModal');
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
        openModal('deleteConfirmModal');
    }

    function executeDelete() {
        if (pengajuanIdToDelete) {
            const deleteForm = document.getElementById('deleteForm');
            let url = "{{ route('pengajuan.destroy', ':id') }}";
            url = url.replace(':id', pengajuanIdToDelete);
            deleteForm.action = url;
            deleteForm.submit();
        }
    }
    
    // --- Fungsi Helper ---
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
        
        // Re-enable scrolling on body when all modals are closed
        if (!document.querySelector('.modal[style*="display: flex"]')) {
            document.body.style.overflow = '';
        }
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'flex';
        // Disable scrolling on body when modal is open
        document.body.style.overflow = 'hidden';
    }

    // Event listener untuk menutup modal saat klik di luar area konten
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            // Only close the top-most modal when clicking outside
            const openModals = Array.from(document.querySelectorAll('.modal[style*="display: flex"]'));
            if (openModals.length > 0) {
                const topModal = openModals.reduce((a, b) => 
                    (parseInt(getComputedStyle(a).zIndex) > parseInt(getComputedStyle(b).zIndex) ? a : b)
                );
                if (event.target === topModal) {
                    closeModal(topModal.id);
                }
            }
        }
    });
    
    // FUNGSI BARU: untuk memfilter tabel
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const tableBody = document.getElementById("pengajuanTableBody");
        const rows = tableBody.getElementsByTagName("tr");
        let visibleRows = 0;

        for (let i = 0; i < rows.length; i++) {
            if (rows[i].id === 'noResultsRow' || rows[i].querySelector('td[colspan="9"]')) {
                continue;
            }

            const pengajuCell = rows[i].getElementsByTagName("td")[1];
            const kegiatanCell = rows[i].getElementsByTagName("td")[2];
            const ruanganCell = rows[i].getElementsByTagName("td")[3];

            if (pengajuCell && kegiatanCell && ruanganCell) {
                const rowText = (pengajuCell.textContent || pengajuCell.innerText) +
                              (kegiatanCell.textContent || kegiatanCell.innerText) +
                              (ruanganCell.textContent || ruanganCell.innerText);
                
                if (rowText.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                    visibleRows++;
                } else {
                    rows[i].style.display = "none";
                }
            }       
        }

        const noResultsRow = document.getElementById('noResultsRow');
        if (visibleRows === 0 && !tableBody.querySelector('td[colspan="9"]')) {
            noResultsRow.style.display = "table-row";
        } else {
            noResultsRow.style.display = "none";
        }
    }
</script>
@endsection