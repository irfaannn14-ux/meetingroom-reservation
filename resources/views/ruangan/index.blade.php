@extends('layout.main')
@section('title', 'Manajemen Ruangan')
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
        padding: 20px 20px 0 30px; /* Added top padding and more horizontal spacing */
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
        min-width: 800px;
    }
    
    .table th,
    .table td {
        padding: 16px 20px;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table thead th {
        background-color: #f1f5f9;
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
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
    
    .no-data-row td {
        text-align: center;
        padding: 40px 0;
        font-style: italic;
        color: #64748b;
        font-size: 1.1rem;
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
    
    .modal-backdrop {
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
    
    .modal-content {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .modal-content.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .modal-header {
        background: linear-gradient(90deg, var(--primary-color) 0%, #2563eb 100%);
        color: white;
        padding: 20px 24px;
        position: relative;
    }
    
    .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--secondary-color);
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-close {
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
    
    .btn-close:hover {
        opacity: 1;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .room-photo {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }
    
    .room-detail {
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
    }
    
    .btn-secondary {
        background-color: #64748b;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #475569;
    }
    
    .capacity-badge {
        display: inline-block;
        background: #dbeafe;
        color: var(--primary-color);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
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
    
    .alert-success i {
        color: #10b981;
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
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            max-width: 100%;
        }
        
        .room-detail {
            grid-template-columns: 1fr;
        }
        
        .table-container {
            font-size: 0.9rem;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
        }
        
        .action-container {
            justify-content: center;
        }
    }
</style>

<div class="page-container py-4">
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-door-open"></i>
            Manajemen Ruangan
        </h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari ruangan...">
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

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-list-ul"></i>
                Daftar Ruangan
            </h2>
            @if(session('user_role') !== 'OPD')
            <a href="{{ route('ruangan.tambah') }}" class="btn-add-custom">
                <i class="bi bi-plus-circle"></i>
                Tambah Ruangan Baru
            </a>
            @endif
        </div>
        
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 25%;">Nama Ruangan</th>
                            <th style="width: 15%;">Kapasitas</th>
                            <th style="width: 35%;">Fasilitas</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ruanganTableBody">
                        @forelse($ruangans as $ruangan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $ruangan->nama_ruangan }}</td>
                            <td><span class="capacity-badge">{{ $ruangan->jml_peserta }} orang</span></td>
                            <td>{{ Str::limit($ruangan->fasilitas, 30) }}</td>
                            <td>
                                <div class="action-container">
                                    <button type="button" class="btn-icon btn-info" onclick="openDetailModal({{ json_encode($ruangan) }})" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if(session('user_role') !== 'OPD')
                                    <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn-icon btn-success" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-danger" onclick="openDeleteModal({{ $ruangan->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data-row">
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-building"></i>
                                    <h3>Belum Ada Ruangan</h3>
                                    <p>Belum ada data ruangan yang tersedia. Silakan tambahkan ruangan baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr id="noResultsRow" style="display: none;" class="no-data-row">
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-search"></i>
                                    <h3>Tidak Ada Hasil</h3>
                                    <p>Tidak ada ruangan yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Detail Ruangan --}}
<div id="detailModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-door-open"></i>
                Detail Ruangan
            </h3>
            <button class="btn-close" onclick="closeModal('detailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <img id="modalFotoRuangan" class="room-photo" src="" alt="Foto Ruangan">
            <div class="room-detail">
                <div class="detail-label">Nama Ruangan</div>
                <div id="modalNamaRuangan" class="fw-bold"></div>
                
                <div class="detail-label">Kapasitas Maksimal</div>
                <div id="modalKapasitas" class="fw-bold"></div>
                
                <div class="detail-label">Fasilitas</div>
                <div id="modalFasilitas"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-secondary" onclick="closeModal('detailModal')">Tutup</button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-trash"></i>
                Konfirmasi Hapus
            </h3>
            <button class="btn-close" onclick="closeModal('deleteConfirmModal')">&times;</button>
        </div>
        <div class="modal-body text-center">
            <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
            <h4 class="mb-3">Yakin ingin menghapus ruangan ini?</h4>
            <p class="text-muted">Tindakan ini tidak dapat dibatalkan. Semua data terkait ruangan ini akan dihapus secara permanen.</p>
            <div class="fw-bold text-danger" id="namaRuanganDelete"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-secondary" onclick="closeModal('deleteConfirmModal')">Batal</button>
            <button class="btn-modal btn-danger" onclick="executeDelete()">Hapus Permanen</button>
        </div>
    </div>
</div>

{{-- Formulir tersembunyi untuk mengirim permintaan DELETE --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    let ruanganIdToDelete = null;
    let ruanganNamaToDelete = '';

    function openDetailModal(ruangan) {
        document.getElementById('modalFotoRuangan').src = ruangan.foto_ruangan ? `/storage/${ruangan.foto_ruangan}` : 'https://via.placeholder.com/600x400?text=No+Image';
        document.getElementById('modalNamaRuangan').innerText = ruangan.nama_ruangan;
        document.getElementById('modalKapasitas').innerText = `${ruangan.jml_peserta} orang`;
        document.getElementById('modalFasilitas').innerText = ruangan.fasilitas || 'Tidak ada fasilitas yang tercatat';
        openModal('detailModal');
    }

    function openDeleteModal(id, nama) {
        ruanganIdToDelete = id;
        ruanganNamaToDelete = nama;
        document.getElementById('namaRuanganDelete').innerText = `Ruangan: ${nama}`;
        openModal('deleteConfirmModal');
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

    function executeDelete() {
        if (ruanganIdToDelete) {
            const deleteForm = document.getElementById('deleteForm');
            let url = "{{ route('ruangan.destroy', ':id') }}";
            url = url.replace(':id', ruanganIdToDelete);
            deleteForm.action = url;
            deleteForm.submit();
        }
    }

    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const tableBody = document.getElementById("ruanganTableBody");
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
            if (cells.length < 4) continue;

            const namaRuangan = cells[1].textContent || cells[1].innerText;
            const kapasitas = cells[2].textContent || cells[2].innerText;
            const fasilitas = cells[3].textContent || cells[3].innerText;
            
            const rowText = (namaRuangan + kapasitas + fasilitas).toLowerCase();
            
            if (rowText.indexOf(filter) > -1) {
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

    // Close modals when clicking outside
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        // Add data-nama attribute to delete buttons
        document.querySelectorAll('[onclick^="openDeleteModal"]').forEach(button => {
            const id = button.getAttribute('onclick').match(/\d+/)[0];
            const row = button.closest('tr');
            if (row) {
                const namaCell = row.cells[1];
                if (namaCell) {
                    button.setAttribute('data-nama', namaCell.textContent.trim());
                    button.setAttribute('onclick', `openDeleteModal(${id}, '${namaCell.textContent.trim()}')`);
                }
            }
        });
    });
</script>
@endsection