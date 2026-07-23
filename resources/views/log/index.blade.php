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
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">TANGGAL</th>
                            <th style="width: 20%;">USER</th>
                            <th style="width: 15%;">EVENT</th>
                            <th style="width: 20%;">MODEL</th>
                            <th style="width: 15%;">IP</th>
                            <th style="width: 10%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        @forelse($logs as $log)
                        <tr>
                            <td class="fw-bold">{{ $loop->iteration }}</td>
                            <td>{{ $log->created_at->format('d M Y, H.i') }}</td>
                            <td>
                                <div>{{ $log->user->nama ?? 'System' }}</div>
                                <div class="text-muted" style="font-size: 0.85em;">{{ $log->user->email ?? '' }}</div>
                            </td>
                            <td>
                                @php
                                    $event = strtolower($log->event);
                                    $badgeClass = 'log-default';
                                    
                                    if ($event === 'created') {
                                        $badgeClass = 'log-add';
                                    } elseif ($event === 'updated') {
                                        $badgeClass = 'log-edit';
                                    } elseif ($event === 'deleted') {
                                        $badgeClass = 'log-delete';
                                    }
                                @endphp
                                <span class="log-badge {{ $badgeClass }}">
                                    {{ $event }}
                                </span>
                            </td>
                            <td>{{ $log->auditable_type }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>
                                @php
                                    $auditableData = $log->auditable;
                                    if ($auditableData && $log->auditable_type === 'App\\Models\\Pengajuan') {
                                        $auditableData->loadMissing('ruangan');
                                    }
                                @endphp
                                <button class="btn btn-primary btn-sm rounded-1 px-3 py-1 text-white" style="background-color: #3b82f6; border: none; font-weight: 500;" onclick="openLogDetail(this)"
                                        data-log="{{ json_encode($log) }}"
                                        data-auditable="{{ json_encode($auditableData) }}"
                                        title="Lihat Detail">
                                    Detail
                                </button>
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
                <span id="modalTitle">Detail Audit</span>
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
        const logData = button.getAttribute('data-log');
        const auditableData = button.getAttribute('data-auditable');
        
        let log = null;
        let auditable = null;
        
        try {
            if (logData) log = JSON.parse(logData);
        } catch (e) {
            console.error('Error parsing log data:', e);
        }
        
        try {
            if (auditableData) auditable = JSON.parse(auditableData);
        } catch (e) {
            console.error('Error parsing auditable data:', e);
        }
        
        populateModal(log, auditable);
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

    function populateModal(log, auditable) {
        const modalBody = document.getElementById('modalBodyLog');
        const modalTitle = document.getElementById('modalTitle');
        
        const modelType = log.auditable_type || '';
        let modelLabel = modelType.split('\\').pop();
        modalTitle.textContent = 'Detail Audit — ' + modelLabel;

        // Build old/new values comparison table
        let oldValues = log.old_values || {};
        let newValues = log.new_values || {};
        let allKeys = new Set([...Object.keys(oldValues), ...Object.keys(newValues)]);
        
        let tableRows = '';

        // Add extra context rows at the top for Pengajuan
        if (modelLabel === 'Pengajuan' && auditable) {
            const extraFields = [
                { label: 'Nama Ruangan', val: auditable.ruangan ? auditable.ruangan.nama_ruangan : null },
                { label: 'Judul Kegiatan', val: auditable.judul_kegiatan },
                { label: 'Nama Pengaju', val: auditable.nama_pengaju },
            ];
            extraFields.forEach(f => {
                if (f.val) {
                    tableRows += `
                        <tr style="background-color:#eff6ff;">
                            <td style="font-weight:700;color:#1e3a8a;">${f.label}</td>
                            <td colspan="2" style="font-weight:600;color:#1e40af;">${f.val}</td>
                        </tr>`;
                }
            });
            // Separator
            if (tableRows) {
                tableRows += `<tr><td colspan="3" style="padding:0;background:#e2e8f0;height:2px;"></td></tr>`;
            }
        } else if (modelLabel === 'Ruangan' && auditable) {
            if (auditable.nama_ruangan) {
                tableRows += `
                    <tr style="background-color:#eff6ff;">
                        <td style="font-weight:700;color:#1e3a8a;">Nama Ruangan</td>
                        <td colspan="2" style="font-weight:600;color:#1e40af;">${auditable.nama_ruangan}</td>
                    </tr>
                    <tr><td colspan="3" style="padding:0;background:#e2e8f0;height:2px;"></td></tr>`;
            }
        } else if (modelLabel === 'User' && auditable) {
            if (auditable.nama) {
                tableRows += `
                    <tr style="background-color:#eff6ff;">
                        <td style="font-weight:700;color:#1e3a8a;">Nama Pengguna</td>
                        <td colspan="2" style="font-weight:600;color:#1e40af;">${auditable.nama} (${auditable.email || '-'})</td>
                    </tr>
                    <tr><td colspan="3" style="padding:0;background:#e2e8f0;height:2px;"></td></tr>`;
            }
        }

        if (allKeys.size === 0) {
            tableRows += `<tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data perubahan pada event ini</td></tr>`;
        } else {
            allKeys.forEach(key => {
                let oldVal = oldValues[key] !== undefined ? oldValues[key] : null;
                let newVal = newValues[key] !== undefined ? newValues[key] : null;
                
                if (typeof oldVal === 'object' && oldVal !== null) oldVal = JSON.stringify(oldVal);
                if (typeof newVal === 'object' && newVal !== null) newVal = JSON.stringify(newVal);

                const oldDisplay = oldVal !== null ? oldVal : '<span class="text-muted fst-italic">null</span>';
                const newDisplay = newVal !== null ? newVal : '<span class="text-muted fst-italic">null</span>';

                const isChanged = String(oldVal) !== String(newVal);
                const rowStyle = isChanged ? 'background-color:#fffbeb;' : '';
                
                tableRows += `
                    <tr style="${rowStyle}">
                        <td style="font-weight:600;">${key}</td>
                        <td class="text-muted">${oldDisplay}</td>
                        <td style="color:#1d4ed8;font-weight:${isChanged ? '600' : '400'}">${newDisplay}</td>
                    </tr>`;
            });
        }
        
        modalBody.innerHTML = `
            <div class="table-responsive">
                <table class="table table-bordered" style="border-radius:8px;overflow:hidden;">
                    <thead style="background-color:#1e3a8a;color:white;">
                        <tr>
                            <th style="width:30%;">FIELD</th>
                            <th style="width:35%;">OLD VALUE</th>
                            <th style="width:35%;">NEW VALUE</th>
                        </tr>
                    </thead>
                    <tbody>${tableRows}</tbody>
                </table>
            </div>
        `;
    }
</script>
@endsection