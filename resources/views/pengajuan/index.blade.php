@extends('layout.main')
@section('title', 'Manajemen Pengajuan')
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
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 30px 0 30px; /* Added top padding and more horizontal spacing */
    }
    
    .page-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        gap: 16px;
    }
    
    .page-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-title i {
        color: var(--secondary-color);
    }
    
    .search-container {
        position: relative;
        flex: 1;
        max-width: 400px;
    }
    
    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1.1rem;
    }
    
    #searchInput {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        transition: var(--transition);
        background-color: white;
    }
    
    #searchInput:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        outline: none;
    }
    
    .card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        margin-bottom: 32px;
    }
    
    .card-header {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        color: white;
        padding: 18px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .card-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--secondary-color);
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-body {
        padding: 0;
    }
    
    .table-container {
        overflow-x: auto;
        min-width: 100%;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }
    
    .table th,
    .table td {
        padding: 16px 20px;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.95rem;
    }
    
    .table thead th {
        background-color: #f1f5f9;
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        transition: var(--transition);
    }
    
    .table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-icon i {
        font-size: 1rem;
    }
    
    .btn-info {
        background-color: #3b82f6;
    }
    
    .btn-info:hover {
        background-color: #2563eb;
    }
    
    .btn-success {
        background-color: #10b981;
    }
    
    .btn-success:hover {
        background-color: #059669;
    }
    
    .btn-danger {
        background-color: #ef4444;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
    }
    
    .action-container {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    
    .btn-add-custom {
        /* Using vibrant gradient: orange to amber for high visibility */
        background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
        color: white !important;
        border: none;
        padding: 14px 28px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        /* Enhanced shadow for elevation and prominence */
        box-shadow: 0 6px 16px rgba(249, 115, 22, 0.35), 
                    0 2px 4px rgba(249, 115, 22, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        letter-spacing: 0.3px;
        /* Adding subtle outline for better definition */
        outline: 2px solid rgba(255, 255, 255, 0.2);
        outline-offset: -2px;
        position: relative;
        overflow: hidden;
    }
    
    /* Add subtle shine effect */
    .btn-add-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.2), 
            transparent);
        transition: left 0.5s;
    }
    
    .btn-add-custom:hover::before {
        left: 100%;
    }
    
    .btn-add-custom:hover {
        /* Lift effect on hover */
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 24px rgba(249, 115, 22, 0.45), 
                    0 4px 8px rgba(249, 115, 22, 0.3);
        /* Slightly brighten on hover */
        background: linear-gradient(135deg, #fb923c 0%, #fbbf24 100%);
    }
    
    .btn-add-custom:active {
        transform: translateY(-1px) scale(1.01);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
    }
    
    .btn-add-custom i {
        font-size: 1.1rem;
    }
    
    .custom-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }
    
    .custom-modal-backdrop .modal-content {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 800px;
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .custom-modal-backdrop .modal-content.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .custom-modal-backdrop .modal-header {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        color: white;
        padding: 20px 24px;
        position: relative;
    }
    
    .custom-modal-backdrop .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--secondary-color);
    }
    
    .custom-modal-backdrop .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .custom-modal-backdrop .btn-close {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.7;
        transition: var(--transition);
    }
    
    .custom-modal-backdrop .btn-close:hover {
        opacity: 1;
    }
    
    .custom-modal-backdrop .modal-body {
        padding: 24px;
    }
    
    .pengajuan-detail {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 16px 20px;
        margin-bottom: 16px;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--primary-color);
        padding-right: 8px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .btn-modal {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        transition: var(--transition);
        cursor: pointer;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-secondary {
        background-color: #64748b;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #475569;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .status-pending {
        background-color: #f59e0b;
        color: white;
    }
    
    .status-disetujui {
        background-color: #10b981;
        color: white;
    }
    
    .status-ditolak {
        background-color: #ef4444;
        color: white;
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
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: var(--dark-color);
    }
    
    .whatsapp-btn {
        background: #25D366;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
    }
    
    .whatsapp-btn:hover {
        background: #1da851;
        transform: translateY(-1px);
    }
    
    .time-badge {
        background-color: #dbeafe;
        color: var(--primary-color);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            max-width: 100%;
        }
        
        .pengajuan-detail {
            grid-template-columns: 1fr;
        }
        
        .table-container {
            font-size: 0.85rem;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
        }
        
        .action-container {
            justify-content: center;
        }
        
        .custom-modal-backdrop .modal-content {
            max-width: 95%;
            margin: 10px;
        }
    }
</style>

<div class="page-container py-4">
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-clipboard-check"></i>
            Manajemen Pengajuan
        </h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari pengaju, kegiatan, ruangan...">
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4"></i>
            <div class="ms-3">
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center">
            <i class="bi bi-x-circle-fill fs-4"></i>
            <div class="ms-3">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-list-ul"></i>
                Daftar Pengajuan
            </h2>
            <a href="{{ route('pengajuan.tambah') }}" class="btn-add-custom">
                <i class="bi bi-plus-circle"></i>
                Buat Pengajuan Baru
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Pengaju</th>
                            <th style="width: 20%;">Judul Kegiatan</th>
                            <th style="width: 15%;">Ruangan</th>
                            <th style="width: 15%;">Waktu Mulai (WIB)</th>
                            <th style="width: 15%;">Waktu Selesai (WIB)</th>
                            <th style="width: 5%;">Peserta</th>
                            <th style="width: 5%;">Status</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pengajuanTableBody">
                        @forelse($pengajuans as $pengajuan)
                        <tr>
                            <td class="fw-bold">{{ $loop->iteration }}</td>
                            <td>{{ $pengajuan->nama_pengaju }}</td>
                            <td class="fw-medium">{{ $pengajuan->judul_kegiatan }}</td>
                            <td>{{ $pengajuan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                            <td>
                                <div class="time-badge">
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m H:i') }}
                                </div>
                            </td>
                            <td>
                                <div class="time-badge">
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m H:i') }}
                                </div>
                            </td>
                            <td>{{ $pengajuan->jml_peserta }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($pengajuan->status) }}">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-container">
                                    <button type="button" class="btn-icon btn-info" onclick="openDetailModal({{ json_encode($pengajuan) }})" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if(session('user_role') !== 'OPD')
                                    <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn-icon btn-success" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-danger" onclick="openDeleteModal({{ $pengajuan->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data-row">
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-clipboard-x"></i>
                                    <h3>Belum Ada Pengajuan</h3>
                                    <p>Belum ada data pengajuan yang tersedia. Silakan buat pengajuan baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr id="noResultsRow" style="display: none;" class="no-data-row">
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-search"></i>
                                    <h3>Tidak Ada Hasil</h3>
                                    <p>Tidak ada pengajuan yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Detail Pengajuan --}}
<div id="detailModal" class="custom-modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-clipboard-data"></i>
                Detail Pengajuan
            </h3>
            <button class="btn-close" onclick="closeModal('detailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="pengajuan-detail">
                <div class="detail-label">Nama Pengaju</div>
                <div id="modalNamaPengaju" class="fw-bold"></div>
                
                <div class="detail-label">Organisasi/Role</div>
                <div id="modalOrganisasi"></div>
                
                <div class="detail-label">Judul Kegiatan</div>
                <div id="modalJudul" class="fw-bold"></div>
                
                <div class="detail-label">Deskripsi Kegiatan</div>
                <div id="modalDeskripsi" class="text-muted"></div>
                
                <div class="detail-label">Ruangan Dipesan</div>
                <div id="modalRuangan" class="fw-medium"></div>
                
                <div class="detail-label">Waktu Mulai</div>
                <div id="modalMulai" class="time-badge"></div>
                
                <div class="detail-label">Waktu Selesai</div>
                <div id="modalSelesai" class="time-badge"></div>
                
                <div class="detail-label">Jumlah Peserta</div>
                <div id="modalPeserta"></div>
                
                <div class="detail-label">Status Saat Ini</div>
                <div id="modalStatus"></div>
                
                <div class="detail-label">Contact Person</div>
                <div>
                    <a id="modalContactPersonWa" href="#" target="_blank" class="whatsapp-btn">
                        <i class="bi bi-whatsapp"></i> WhatsApp Pengaju
                    </a>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if(session('user_role') !== 'OPD')
            <button class="btn-modal btn-danger" onclick="openConfirmModal('ditolak')">
                <i class="bi bi-x-circle"></i> Tolak Pengajuan
            </button>
            <button class="btn-modal btn-success" onclick="openConfirmModal('disetujui')">
                <i class="bi bi-check-circle"></i> Setujui Pengajuan
            </button>
            @else
            <button class="btn-modal btn-secondary" onclick="closeModal('detailModal')">
                <i class="bi bi-x"></i> Tutup
            </button>
            @endif
        </div>
    </div>
</div>

{{-- MODAL: Konfirmasi Ubah Status --}}
<div id="confirmStatusModal" class="custom-modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-exclamation-triangle"></i>
                Konfirmasi Tindakan
            </h3>
            <button class="btn-close" onclick="closeModal('confirmStatusModal')">&times;</button>
        </div>
        <div class="modal-body text-center">
            <i class="bi bi-question-circle fs-1 text-warning mb-3"></i>
            <h4 class="mb-3" id="confirmTitle">Konfirmasi Status</h4>
            <p class="text-muted mb-4" id="confirmMessage">Apakah Anda yakin ingin mengubah status pengajuan ini?</p>
            <div class="fw-bold" id="pengajuanInfo"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-secondary" onclick="closeModal('confirmStatusModal')">
                <i class="bi bi-x"></i> Batal
            </button>
            <button class="btn-modal btn-primary" onclick="executeStatusUpdate()">
                <i class="bi bi-check"></i> Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

{{-- MODAL: Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="custom-modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-trash"></i>
                Konfirmasi Hapus
            </h3>
            <button class="btn-close" onclick="closeModal('deleteConfirmModal')">&times;</button>
        </div>
        <div class="modal-body text-center">
            <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i>
            <h4 class="mb-3">Yakin ingin menghapus pengajuan ini?</h4>
            <p class="text-muted">Tindakan ini tidak dapat dibatalkan. Pengajuan akan dihapus secara permanen.</p>
            <div class="fw-bold text-danger" id="judulPengajuanDelete"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-secondary" onclick="closeModal('deleteConfirmModal')">
                <i class="bi bi-x"></i> Batal
            </button>
            <button class="btn-modal btn-danger" onclick="executeDelete()">
                <i class="bi bi-trash"></i> Hapus Permanen
            </button>
        </div>
    </div>
</div>

{{-- Formulir tersembunyi --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="statusUpdateForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="status" id="newStatusInput">
</form>

<script>
    let pengajuanIdToDelete = null;
    let pengajuanToUpdate = null;
    let newStatus = '';

    function openDetailModal(pengajuan) {
        pengajuanToUpdate = pengajuan;

        // Format tanggal Indonesia
        const mulai = new Date(pengajuan.tanggal_mulai);
        const selesai = new Date(pengajuan.tanggal_selesai);
        
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'Asia/Jakarta'
        };

        document.getElementById('modalNamaPengaju').innerText = pengajuan.nama_pengaju || 'Tidak tersedia';
        document.getElementById('modalJudul').innerText = pengajuan.judul_kegiatan || 'Tidak tersedia';
        document.getElementById('modalDeskripsi').innerText = pengajuan.kegiatan || 'Tidak ada deskripsi';
        document.getElementById('modalRuangan').innerText = pengajuan.ruangan?.nama_ruangan || 'N/A';
        document.getElementById('modalMulai').innerText = mulai.toLocaleDateString('id-ID', options).replace(/\./g, ':') + ' WIB';
        document.getElementById('modalSelesai').innerText = selesai.toLocaleDateString('id-ID', options).replace(/\./g, ':') + ' WIB';
        document.getElementById('modalPeserta').innerText = pengajuan.jml_peserta ? `${pengajuan.jml_peserta} orang` : 'N/A';
        
        // Status badge dengan styling konsisten
        let statusText = pengajuan.status || 'pending';
        statusText = statusText.charAt(0).toUpperCase() + statusText.slice(1);
        const statusClass = `status-${pengajuan.status?.toLowerCase() || 'pending'}`;
        document.getElementById('modalStatus').innerHTML = `<span class="status-badge ${statusClass}">${statusText}</span>`;
        
        // Organisasi/Role
        let roleOrOrgText = 'N/A';
        if (pengajuan.user) {
            if (pengajuan.user.role === 'OPD') {
                if (pengajuan.user.organization?.organization_name) {
                    roleOrOrgText = `OPD - ${pengajuan.user.organization.organization_name}`;
                } else {
                    roleOrOrgText = 'OPD - (Data Organisasi Tidak Ditemukan)';
                }
            } else {
                roleOrOrgText = pengajuan.user.role;
            }
        }
        document.getElementById('modalOrganisasi').innerText = roleOrOrgText;

        // WhatsApp link
        const waButton = document.getElementById('modalContactPersonWa');
        if (pengajuan.user?.no_wa) {
            const phoneNumber = pengajuan.user.no_wa.startsWith('0') ? 
                `62${pengajuan.user.no_wa.substring(1)}` : 
                `62${pengajuan.user.no_wa}`;
            waButton.href = `https://wa.me/${phoneNumber}`;
            waButton.style.display = 'inline-flex';
        } else {
            waButton.style.display = 'none';
        }
        
        openModal('detailModal');
    }

    function openConfirmModal(status) {
        newStatus = status;
        const actionText = status === 'disetujui' ? 'menyetujui' : 'menolak';
        const actionTitle = status === 'disetujui' ? 'Setujui Pengajuan' : 'Tolak Pengajuan';
        const actionClass = status === 'disetujui' ? 'text-success' : 'text-danger';
        
        document.getElementById('confirmTitle').innerHTML = `<i class="bi bi-${status === 'disetujui' ? 'check-circle' : 'x-circle'} ${actionClass}"></i> ${actionTitle}`;
        document.getElementById('confirmMessage').innerText = `Apakah Anda yakin ingin ${actionText} pengajuan "${pengajuanToUpdate.judul_kegiatan}"?`;
        document.getElementById('pengajuanInfo').innerText = `Pengaju: ${pengajuanToUpdate.nama_pengaju}`;
        
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

    function openDeleteModal(id) {
        pengajuanIdToDelete = id;
        // Cari judul pengajuan dari tabel
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            const judulCell = row.querySelector('td:nth-child(3)');
            if (judulCell) {
                document.getElementById('judulPengajuanDelete').innerText = judulCell.textContent;
            }
        }
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
    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
        document.body.style.overflow = '';
        
        // Reset modal animation
        setTimeout(() => {
            const content = modal.querySelector('.modal-content');
            content.classList.remove('show');
        }, 300);
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        setTimeout(() => {
            const content = modal.querySelector('.modal-content');
            content.classList.add('show');
        }, 10);
    }

    document.querySelectorAll('.custom-modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal(this.id);
            }
        });
    });

    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase().trim();
        const tableBody = document.getElementById("pengajuanTableBody");
        const rows = tableBody.getElementsByTagName("tr");
        let visibleRows = 0;
        let hasDataRows = false;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            // Skip special rows (empty state, no results)
            if (row.classList.contains('no-data-row') || row.id === 'noResultsRow') {
                continue;
            }
            
            hasDataRows = true;
            const cells = row.getElementsByTagName("td");
            if (cells.length < 8) continue;

            const pengaju = cells[1].textContent || cells[1].innerText;
            const kegiatan = cells[2].textContent || cells[2].innerText;
            const ruangan = cells[3].textContent || cells[3].innerText;
            const status = cells[7].textContent || cells[7].innerText;
            
            const rowText = (pengaju + kegiatan + ruangan + status).toLowerCase();
            
            if (rowText.includes(filter)) {
                row.style.display = "";
                visibleRows++;
            } else {
                row.style.display = "none";
            }
        }

        const noResultsRow = document.getElementById('noResultsRow');
        const emptyStateRow = tableBody.querySelector('.no-data-row:not(#noResultsRow)');
        
        if (!hasDataRows) {
            // No data at all
            emptyStateRow.style.display = "";
            noResultsRow.style.display = "none";
        } else if (visibleRows === 0) {
            // No matching results
            emptyStateRow.style.display = "none";
            noResultsRow.style.display = "table-row";
        } else {
            // Normal display
            emptyStateRow.style.display = "none";
            noResultsRow.style.display = "none";
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        // Add data-id attribute to rows for easier access
        document.querySelectorAll('#pengajuanTableBody tr:not(.no-data-row)').forEach(row => {
            const actionCell = row.cells[row.cells.length - 1];
            const deleteBtn = actionCell.querySelector('button[onclick*="openDeleteModal"]');
            if (deleteBtn) {
                const idMatch = deleteBtn.getAttribute('onclick').match(/openDeleteModal\((\d+)\)/);
                if (idMatch) {
                    row.setAttribute('data-id', idMatch[1]);
                }
            }
        });
    });
</script>
@endsection