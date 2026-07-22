@extends('layout.main')
@section('title', 'Log Aktivitas')
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
        padding: 0 20px;
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
        position: relative;
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
        min-width: 900px;
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
    
    .log-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .log-approve { 
        background-color: #dcfce7;
        color: #166534;
    }
    
    .log-deny { 
        background-color: #fee2e2;
        color: #b91c1c;
    }
    
    .log-delete { 
        background-color: #f3f4f6;
        color: #4b5563;
        opacity: 0.7;
    }
    
    .log-edit { 
        background-color: #ffedd5;
        color: #c2410c;
    }
    
    .log-add { 
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .log-default { 
        background-color: #e0e7ff;
        color: #4338ca;
    }
    
    .no-data-row td {
        text-align: center;
        padding: 40px 0;
        font-style: italic;
        color: #64748b;
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
        max-width: 900px;
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
        padding: 28px;
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .custom-modal-backdrop .modal-body h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
        font-size: 1.25rem;
    }
    
    .custom-modal-backdrop .modal-body .table {
        margin-bottom: 0;
    }
    
    .custom-modal-backdrop .modal-body .table th {
        background-color: #f8fafc !important;
        color: var(--dark-color) !important;
        font-weight: 600;
        width: 30%;
        padding: 12px 16px;
    }
    
    .custom-modal-backdrop .modal-body .table td {
        padding: 12px 16px;
        vertical-align: middle;
    }
    
    .custom-modal-backdrop .modal-body img {
        max-width: 100%;
        max-height: 200px;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }
    
    .custom-modal-backdrop .modal-footer {
        padding: 16px 28px;
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
    
    .time-badge {
        background-color: #dbeafe;
        color: var(--primary-color);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 12px;
    }
    
    .detail-label {
        flex: 1;
        font-weight: 500;
        color: var(--primary-color);
    }
    
    .detail-value {
        flex: 2;
        color: var(--dark-color);
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
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            max-width: 100%;
        }
        
        .table-container {
            font-size: 0.85rem;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
        }
        
        .custom-modal-backdrop .modal-content {
            max-width: 95%;
            margin: 10px;
        }
        
        .custom-modal-backdrop .modal-body {
            padding: 20px;
        }
        
        .custom-modal-backdrop .modal-footer {
            padding: 16px 20px;
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-modal {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-container py-4">
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journal-text"></i>
            Log Aktivitas
        </h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" 
                   placeholder="Cari: Nama Pengguna, Role, Aktivitas...">
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-list-ul"></i>
                Daftar Aktivitas Sistem
            </h2>
        </div>
        
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 18%;">Nama Pengguna</th>
                            <th style="width: 15%;">Role</th>
                            <th style="width: 35%;">Aktivitas</th>
                            <th style="width: 20%;">Waktu</th>
                            <th style="width: 7%;">Detail</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        @forelse($logs as $log)
                        <tr>
                            <td class="fw-bold">{{ $loop->iteration }}</td>
                            <td>{{ $log->user->nama ?? 'N/A' }}</td>
                            <td>
                                <span class="time-badge">{{ $log->user->role ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @php
                                    $activity = strtolower($log->activity);
                                    $badgeClass = 'log-default';
                                    $badgeText = 'Aktivitas Lainnya';
                                    $badgeIcon = 'bi-info-circle';
                                    
                                    // Approve Pengajuan (Hijau)
                                    if(str_contains($activity, 'approve') || 
                                       str_contains($activity, 'menyetujui') || 
                                       str_contains($activity, 'disetujui')) {
                                        $badgeClass = 'log-approve';
                                        $badgeText = 'Approve Pengajuan';
                                        $badgeIcon = 'bi-check-circle';
                                    }
                                    // Deny Pengajuan (Merah)
                                    elseif(str_contains($activity, 'deny') || 
                                           str_contains($activity, 'menolak') || 
                                           str_contains($activity, 'ditolak')) {
                                        $badgeClass = 'log-deny';
                                        $badgeText = 'Deny Pengajuan';
                                        $badgeIcon = 'bi-x-circle';
                                    }
                                    // Menghapus (Abu-abu - Disabled)
                                    elseif(str_contains($activity, 'delete') || 
                                           str_contains($activity, 'menghapus') || 
                                           str_contains($activity, 'dihapus')) {
                                        $badgeClass = 'log-delete';
                                        $badgeIcon = 'bi-trash';
                                        
                                        // Tentukan label berdasarkan jenis yang dihapus
                                        if(str_contains($activity, 'ruangan')) {
                                            $badgeText = 'Menghapus Ruangan';
                                        } elseif(str_contains($activity, 'user') || str_contains($activity, 'pengguna')) {
                                            $badgeText = 'Menghapus User';
                                        } elseif(str_contains($activity, 'pengajuan') || str_contains($activity, 'permohonan')) {
                                            $badgeText = 'Menghapus Pengajuan';
                                        } else {
                                            $badgeText = 'Menghapus Data';
                                        }
                                    }
                                    // Edit Ruangan (Kuning) - Harus sebelum Edit Pengajuan
                                    elseif(str_contains($activity, 'edit ruangan') || 
                                           str_contains($activity, 'mengedit ruangan') ||
                                           str_contains($activity, 'mengubah ruangan')) {
                                        $badgeClass = 'log-edit';
                                        $badgeText = 'Mengedit Ruangan';
                                        $badgeIcon = 'bi-pencil-square';
                                    }
                                    // Edit User (Kuning) - Harus sebelum Edit Pengajuan
                                    elseif(str_contains($activity, 'edit user') || 
                                           str_contains($activity, 'mengedit user') ||
                                           str_contains($activity, 'mengubah user') ||
                                           str_contains($activity, 'edit pengguna') ||
                                           str_contains($activity, 'mengedit pengguna')) {
                                        $badgeClass = 'log-edit';
                                        $badgeText = 'Mengedit User';
                                        $badgeIcon = 'bi-pencil-square';
                                    }
                                    // Edit Pengajuan (Kuning)
                                    elseif(str_contains($activity, 'edit pengajuan') || 
                                           str_contains($activity, 'mengedit pengajuan') ||
                                           str_contains($activity, 'edit') ||
                                           str_contains($activity, 'mengedit') || 
                                           str_contains($activity, 'mengubah') || 
                                           str_contains($activity, 'mengupdate')) {
                                        $badgeClass = 'log-edit';
                                        $badgeText = 'Mengedit Pengajuan';
                                        $badgeIcon = 'bi-pencil-square';
                                    }
                                    // Tambah Data (Biru)
                                    elseif(str_contains($activity, 'add') || 
                                           str_contains($activity, 'menambah') || 
                                           str_contains($activity, 'menambahkan')) {
                                        $badgeClass = 'log-add';
                                        $badgeText = 'Tambah Data';
                                        $badgeIcon = 'bi-plus-circle';
                                    }
                                @endphp
                                <span class="log-badge {{ $badgeClass }}">
                                    <i class="bi {{ $badgeIcon }}"></i>
                                    {{ $badgeText }}
                                </span>
                            </td>
                            <td>
                                <div class="time-badge">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $resource = $log->resource();
                                    $canView = $resource !== null && !str_contains(strtolower($log->activity), 'delete');
                                @endphp
                                @if($canView)
                                <button class="btn-icon btn-info" onclick="openLogDetail(this)"
                                        data-log-id="{{ $log->id }}"
                                        data-log="{{ json_encode($log) }}"
                                        data-resource="{{ json_encode($resource) }}"
                                        data-resource-type="{{ $log->resource_type }}"
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @else
                                <span class="text-muted" title="Tidak tersedia untuk aktivitas penghapusan">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data-row">
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <h3>Belum Ada Log Aktivitas</h3>
                                    <p>Belum ada data aktivitas yang tercatat dalam sistem.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr id="noResultsRow" style="display: none;" class="no-data-row">
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-search"></i>
                                    <h3>Tidak Ada Hasil</h3>
                                    <p>Tidak ada aktivitas yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Aktivitas -->
<div id="modalLogBootstrap" class="custom-modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-journal-text"></i>
                <span id="modalTitle">Detail Aktivitas</span>
            </h3>
            <button class="btn-close" onclick="closeModal('modalLogBootstrap')">&times;</button>
        </div>
        <div class="modal-body" id="modalBodyLog">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat detail aktivitas...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-secondary" onclick="closeModal('modalLogBootstrap')">
                <i class="bi bi-x"></i> Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase().trim();
        const tableBody = document.getElementById("logTableBody");
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
            if (cells.length < 5) continue;

            const pengguna = cells[1].textContent || cells[1].innerText;
            const role = cells[2].textContent || cells[2].innerText;
            const aktivitas = cells[3].textContent || cells[3].innerText;
            
            const rowText = (pengguna + role + aktivitas).toLowerCase();
            
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

    function openLogDetail(button) {
        // Get data attributes
        const logData = button.getAttribute('data-log');
        const resourceData = button.getAttribute('data-resource');
        const resourceType = button.getAttribute('data-resource-type');
        
        // Parse data
        let log = null;
        let resource = null;
        
        try {
            if (logData) log = JSON.parse(logData);
        } catch (e) {
            console.error('Error parsing log data:', e);
        }
        
        try {
            if (resourceData) resource = JSON.parse(resourceData);
        } catch (e) {
            console.error('Error parsing resource data:', e);
        }
        
        // Populate modal
        populateModal(log, resource, resourceType);
        
        // Open modal
        openModal('modalLogBootstrap');
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

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        content.classList.remove('show');
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }

    // Close modal when clicking outside
    document.querySelectorAll('.custom-modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal(this.id);
            }
        });
    });




    function populateModal(log, resource, resourceType) {
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBodyLog');
        
        if (!resource || !resourceType) {
            modalTitle.textContent = 'Detail Aktivitas';
            modalBody.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-info-circle fs-1 text-muted"></i>
                    <h4 class="mt-3">Detail Tidak Tersedia</h4>
                    <p class="text-muted">Detail aktivitas ini tidak dapat ditampilkan.</p>
                </div>
            `;
            return;
        }
        
        let title = 'Detail Aktivitas';
        let content = '';
        
        // Handle different resource types
        if (resourceType === 'pengajuan') {
            title = 'Detail Pengajuan';
            content = generatePengajuanContent(resource, log);
        } else if (resourceType === 'ruangan') {
            title = 'Detail Ruangan';
            content = generateRuanganContent(resource, log);
        } else if (resourceType === 'user') {
            title = 'Detail Pengguna';
            content = generateUserContent(resource, log);
        } else {
            title = 'Detail Aktivitas';
            content = generateDefaultContent(log);
        }
        
        modalTitle.textContent = title;
        modalBody.innerHTML = content;
    }

    function generatePengajuanContent(resource, log) {
        return `
            <h5>Informasi Pengajuan</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label detail-label">Judul Kegiatan</label>
                        <div class="detail-value fw-bold">${resource.judul_kegiatan || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Nama Pengaju</label>
                        <div class="detail-value">${resource.nama_pengaju || (resource.user && resource.user.nama) || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Ruangan</label>
                        <div class="detail-value">${resource.ruangan?.nama_ruangan || '-'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label detail-label">Tanggal Mulai</label>
                        <div class="detail-value time-badge">${resource.tanggal_mulai || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Tanggal Selesai</label>
                        <div class="detail-value time-badge">${resource.tanggal_selesai || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Jumlah Peserta</label>
                        <div class="detail-value">${resource.jml_peserta || '-'} orang</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Status</label>
                        <div class="detail-value">
                            <span class="status-badge status-${resource.status?.toLowerCase() || 'pending'}">
                                ${resource.status || '-'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function generateRuanganContent(resource, log) {
        let fotoHtml = '';
        if (resource.foto_ruangan) {
            fotoHtml = `
                <div class="mb-3">
                    <label class="form-label detail-label">Foto Ruangan</label>
                    <div class="detail-value">
                        <img src="/storage/${resource.foto_ruangan}" alt="Foto Ruangan" class="img-fluid rounded">
                    </div>
                </div>
            `;
        }
        
        return `
            <h5>Informasi Ruangan</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label detail-label">Nama Ruangan</label>
                        <div class="detail-value fw-bold">${resource.nama_ruangan || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Kapasitas</label>
                        <div class="detail-value">${resource.jml_peserta || '-'} orang</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label detail-label">Fasilitas</label>
                        <div class="detail-value">${resource.fasilitas || '-'}</div>
                    </div>
                    ${fotoHtml}
                </div>
            </div>
        `;
    }

    function generateUserContent(resource, log) {
        let fotoHtml = '';
        if (resource.foto_profil) {
            fotoHtml = `
                <div class="mb-3">
                    <label class="form-label detail-label">Foto Profil</label>
                    <div class="detail-value">
                        <img src="/storage/${resource.foto_profil}" alt="Foto Profil" class="img-fluid rounded-circle" style="max-width: 150px;">
                    </div>
                </div>
            `;
        }
        
        return `
            <h5>Informasi Pengguna</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label detail-label">Nama Lengkap</label>
                        <div class="detail-value fw-bold">${resource.nama || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Email</label>
                        <div class="detail-value">${resource.email || '-'}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">Username</label>
                        <div class="detail-value">${resource.username || '-'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label detail-label">Role</label>
                        <div class="detail-value">
                            <span class="time-badge">${resource.role || '-'}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label detail-label">No. WhatsApp</label>
                        <div class="detail-value">
                            ${resource.no_wa || '-'}
                            ${resource.no_wa ? `<a href="https://wa.me/62${resource.no_wa.replace(/^0/, '')}" target="_blank" class="ms-2 btn btn-sm btn-success">
                                <i class="bi bi-whatsapp"></i> Chat
                            </a>` : ''}
                        </div>
                    </div>
                    ${fotoHtml}
                </div>
            </div>
        `;
    }

    function generateDefaultContent(log) {
        let activity = log?.activity?.toLowerCase() || '';
        let content = '';
        
        if (activity.includes('edit')) {
            content = `
                <h5>Perubahan Data</h5>
                <p class="text-muted">Perbandingan data sebelum dan sesudah perubahan:</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Nilai Lama</th>
                                <th>Nilai Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${Object.entries(log.details?.new_data || {}).map(([key, newVal]) => {
                                const oldVal = log.details?.old_data?.[key] || '-';
                                const isChanged = oldVal !== newVal;
                                return `
                                <tr ${isChanged ? 'class="table-warning"' : ''}>
                                    <td><strong>${key}</strong></td>
                                    <td>${oldVal}</td>
                                    <td>${newVal}${isChanged ? ' <span class="text-warning"><i class="bi bi-arrow-right"></i></span>' : ''}</td>
                                </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            content = `
                <div class="detail-row">
                    <div class="detail-label">Aktivitas</div>
                    <div class="detail-value fw-bold">${log.activity || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Waktu</div>
                    <div class="detail-value time-badge">${log.created_at || '-'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Pengguna</div>
                    <div class="detail-value">${log.user?.nama || '-'}</div>
                </div>
            `;
        }
        
        return content;
    }
</script>
@endsection