@extends('layout.main')
@section('title', 'Log Aktivitas')
@section('content')
<style>
    .main-content {
        padding: 80px 20px 20px;
        min-height: 100vh;
    }
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        color: #ffffff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        margin-bottom: 20px;
    }
    .table-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }
    th, td {
        padding: 0.85rem 1rem;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
    }
    th {
        background-color: #010D26 !important;
        color: #ffffff !important;
        font-weight: 700;
    }
    tr:last-child td {
        border-bottom: none;
    }
    .btn-log {
        border: none;
        padding: 6px 18px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-approve { 
        background: #28a745; 
        color: #fff;
    }
    .btn-approve:hover {
        background: #218838;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
    }
    .btn-deny { 
        background: #dc3545; 
        color: #fff;
    }
    .btn-deny:hover {
        background: #c82333;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
    }
    .btn-edit { 
        background: #ffc107; 
        color: #000;
    }
    .btn-edit:hover {
        background: #e0a800;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4);
    }
    .btn-delete { 
        background: #6c757d; 
        color: #fff;
        cursor: not-allowed;
        opacity: 0.6;
    }
    .btn-add { 
        background: #2196F3; 
        color: #fff;
    }
    .btn-add:hover {
        background: #0b7dda;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.4);
    }
    /* Icon indicators */
    .btn-log i {
        margin-right: 4px;
        font-size: 14px;
    }
    /* Standardized search styles (matching other index pages) */
    .search-container {
        position: relative;
        margin-bottom: 18px;
        max-width: 350px;
    }
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }
    #searchInput {
        padding-left: 40px;
        border-radius: 8px;
        height: 40px;
        width: 100%;
        border: 1px solid #ced4da;
        font-size: 15px;
    }
    /* Modal styles */
    .modal-log {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.3);
        justify-content: center;
        align-items: center;
    }
    .modal-content-log {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        min-width: 350px;
        max-width: 500px;
        position: relative;
    }
    .close-modal-log {
        position: absolute;
        top: 12px;
        right: 18px;
        font-size: 1.5rem;
        cursor: pointer;
        color: #222;
        border: none;
        background: none;
    }
    .compare-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .compare-table th, .compare-table td {
        border: 1px solid #e0e0e0;
        padding: 6px 10px;
        text-align: left;
    }
    .compare-table th {
        background: #f5f5f5;
    }
    
    /* Standardized Modal Styling */
    .modal-dialog {
        max-width: 900px !important;
        width: 900px !important;
        margin: 1.75rem auto;
    }
    .modal-content {
        width: 100% !important;
    }
    .modal-header {
        background: linear-gradient(135deg, #010D26 0%, #1a2b4a 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }
    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .modal-body {
        padding: 2rem;
        max-height: 70vh;
        overflow-y: auto;
    }
    .modal-body h5 {
        color: #010D26;
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #010D26;
    }
    .modal-body .table {
        margin-bottom: 0;
    }
    .modal-body .table th {
        background-color: #f8f9fa !important;
        color: #010D26 !important;
        font-weight: 600;
        width: 30%;
        padding: 0.75rem;
    }
    .modal-body .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .modal-body img {
        max-width: 100%;
        max-height: 200px;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
    .modal-body .no-data {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
        font-style: italic;
    }
    .modal-body pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        overflow-x: auto;
        font-size: 0.875rem;
    }
</style>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Log Aktivitas</h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari: Nama Pengguna, Role, Aktivitas, Waktu, ..." style="width:280px;">
        </div>
    </div>

    <div class="table-container">
        <table id="logTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Role Pengguna</th>
                    <th>Aktivitas</th>
                    <th>Waktu Kejadian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->user->nama ?? 'N/A' }}</td>
                    <td>{{ $log->user->role ?? 'N/A' }}</td>
                    <td>
                        @php
                            $activity = strtolower($log->activity);
                            $btnClass = '';
                            $btnLabel = '';
                            $btnIcon = '';
                            $isDisabled = false;
                            
                            // Approve Pengajuan (Hijau)
                            if(str_contains($activity, 'approve') || 
                               str_contains($activity, 'menyetujui') || 
                               str_contains($activity, 'disetujui')) {
                                $btnClass = 'btn-approve';
                                $btnLabel = 'Approve Pengajuan';
                                $btnIcon = '<i class="bi bi-check-circle"></i>';
                            }
                            // Deny Pengajuan (Merah)
                            elseif(str_contains($activity, 'deny') || 
                                   str_contains($activity, 'menolak') || 
                                   str_contains($activity, 'ditolak')) {
                                $btnClass = 'btn-deny';
                                $btnLabel = 'Deny Pengajuan';
                                $btnIcon = '<i class="bi bi-x-circle"></i>';
                            }
                            // Menghapus (Abu-abu - Disabled)
                            elseif(str_contains($activity, 'delete') || 
                                   str_contains($activity, 'menghapus') || 
                                   str_contains($activity, 'dihapus')) {
                                $btnClass = 'btn-delete';
                                $btnIcon = '<i class="bi bi-trash"></i>';
                                $isDisabled = true;
                                
                                // Tentukan label berdasarkan jenis yang dihapus
                                if(str_contains($activity, 'ruangan')) {
                                    $btnLabel = 'Menghapus Ruangan';
                                } elseif(str_contains($activity, 'user') || str_contains($activity, 'pengguna')) {
                                    $btnLabel = 'Menghapus User';
                                } elseif(str_contains($activity, 'pengajuan') || str_contains($activity, 'permohonan')) {
                                    $btnLabel = 'Menghapus Pengajuan';
                                } else {
                                    $btnLabel = 'Menghapus Data';
                                }
                            }
                            // Edit Ruangan (Kuning) - Harus sebelum Edit Pengajuan
                            elseif(str_contains($activity, 'edit ruangan') || 
                                   str_contains($activity, 'mengedit ruangan') ||
                                   str_contains($activity, 'mengubah ruangan')) {
                                $btnClass = 'btn-edit';
                                $btnLabel = 'Mengedit Ruangan';
                                $btnIcon = '<i class="bi bi-pencil-square"></i>';
                            }
                            // Edit User (Kuning) - Harus sebelum Edit Pengajuan
                            elseif(str_contains($activity, 'edit user') || 
                                   str_contains($activity, 'mengedit user') ||
                                   str_contains($activity, 'mengubah user') ||
                                   str_contains($activity, 'edit pengguna') ||
                                   str_contains($activity, 'mengedit pengguna')) {
                                $btnClass = 'btn-edit';
                                $btnLabel = 'Mengedit User';
                                $btnIcon = '<i class="bi bi-pencil-square"></i>';
                            }
                            // Edit Pengajuan (Kuning)
                            elseif(str_contains($activity, 'edit pengajuan') || 
                                   str_contains($activity, 'mengedit pengajuan') ||
                                   str_contains($activity, 'edit') ||
                                   str_contains($activity, 'mengedit') || 
                                   str_contains($activity, 'mengubah') || 
                                   str_contains($activity, 'mengupdate')) {
                                $btnClass = 'btn-edit';
                                $btnLabel = 'Mengedit Pengajuan';
                                $btnIcon = '<i class="bi bi-pencil-square"></i>';
                            }
                            // Tambah Data (Biru)
                            elseif(str_contains($activity, 'add') || 
                                   str_contains($activity, 'menambah') || 
                                   str_contains($activity, 'menambahkan')) {
                                $btnClass = 'btn-add';
                                $btnLabel = 'Tambah Data';
                                $btnIcon = '<i class="bi bi-plus-circle"></i>';
                            }
                            else {
                                $btnClass = 'btn-log';
                                $btnLabel = $log->activity;
                                $btnIcon = '<i class="bi bi-info-circle"></i>';
                            }
                        @endphp
                        @php
                            $resource = $log->resource();
                            $resourceType = $log->resource_type;
                        @endphp
                        <button 
                            class="btn-log {{ $btnClass }} log-modal-btn" 
                            data-log-id="{{ $log->id }}"
                            data-log="{{ htmlspecialchars(json_encode($log), ENT_QUOTES, 'UTF-8') }}"
                            data-resource="{{ htmlspecialchars(json_encode($resource), ENT_QUOTES, 'UTF-8') }}"
                            data-resource-type="{{ $resourceType }}"
                            @if(!$isDisabled)
                                data-bs-toggle="modal" 
                                data-bs-target="#modalLogBootstrap"
                            @else
                                disabled 
                                title="Log aktivitas penghapusan tidak dapat dibuka"
                                style="pointer-events: none;"
                            @endif
                        >{!! $btnIcon !!} {{ $btnLabel }}</button>
                    </td>
                    <td>{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada aktivitas yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="modalLogBootstrap" tabindex="-1" aria-labelledby="modalLogLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLogLabel">Detail Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyLog">
                <!-- populated by JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Standardized filter function (compatible with other index pages)
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#logTable tbody tr');
        let visible = 0;
        rows.forEach(row => {
            if(row.querySelector('td[colspan]')) return; // skip no-data rows
            const text = row.textContent.toLowerCase();
            if(text.indexOf(filter) > -1) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        const noResults = document.getElementById('noResultsRowLog');
        if(!noResults) {
            const tr = document.createElement('tr');
            tr.id = 'noResultsRowLog';
            tr.innerHTML = '<td colspan="5" class="text-center py-4">Tidak ada aktivitas yang cocok dengan pencarian Anda.</td>';
            document.querySelector('#logTable tbody').appendChild(tr);
        }
        document.getElementById('noResultsRowLog').style.display = visible === 0 ? 'table-row' : 'none';
    }

    // Populate Bootstrap modal when it's shown
    var modalLog = document.getElementById('modalLogBootstrap');
    if (modalLog) {
        modalLog.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            if (!button) return;
            
            // Helper function to decode HTML entities
            function decodeHtml(html) {
                var txt = document.createElement("textarea");
                txt.innerHTML = html;
                return txt.value;
            }
            
            var raw = button.getAttribute('data-log') || button.dataset.log || null;
            var resRaw = button.getAttribute('data-resource') || button.dataset.resource || null;
            var resourceType = button.getAttribute('data-resource-type') || button.dataset.resourceType || null;
            
            var log = null;
            var resource = null;
            
            try { 
                if (raw) {
                    raw = decodeHtml(raw);
                    log = JSON.parse(raw);
                }
            } catch (err) { 
                console.error('Error parsing log:', err);
                log = null; 
            }
            
            try { 
                if (resRaw) {
                    resRaw = decodeHtml(resRaw);
                    resource = JSON.parse(resRaw);
                }
            } catch (err) { 
                console.error('Error parsing resource:', err);
                resource = null; 
            }

            var modalBody = document.getElementById('modalBodyLog');
            var modalTitle = document.getElementById('modalLogLabel');
            var html = '';
            var title = 'Detail Aktivitas';

            // If resource object provided, render resource details depending on type
            if(resource && resourceType) {
                if(resourceType === 'pengajuan') {
                    title = 'Detail Pengajuan';
                    html += '<h5>Informasi Pengajuan</h5>';
                    html += '<table class="table table-bordered"><tbody>';
                    html += '<tr><th>Judul Kegiatan</th><td>' + (resource.judul_kegiatan || '-') + '</td></tr>';
                    html += '<tr><th>Nama Pengaju</th><td>' + (resource.nama_pengaju || (resource.user && resource.user.nama) || '-') + '</td></tr>';
                    html += '<tr><th>Ruangan</th><td>' + (resource.ruangan && resource.ruangan.nama_ruangan ? resource.ruangan.nama_ruangan : '-') + '</td></tr>';
                    html += '<tr><th>Tanggal Mulai</th><td>' + (resource.tanggal_mulai || '-') + '</td></tr>';
                    html += '<tr><th>Tanggal Selesai</th><td>' + (resource.tanggal_selesai || '-') + '</td></tr>';
                    html += '<tr><th>Jumlah Peserta</th><td>' + (resource.jml_peserta || '-') + '</td></tr>';
                    html += '<tr><th>Status</th><td><span class="badge bg-' + (resource.status === 'disetujui' ? 'success' : resource.status === 'ditolak' ? 'danger' : 'warning') + '">' + (resource.status || '-') + '</span></td></tr>';
                    html += '</tbody></table>';
                } else if(resourceType === 'ruangan') {
                    title = 'Detail Ruangan';
                    html += '<h5>Informasi Ruangan</h5>';
                    html += '<table class="table table-bordered"><tbody>';
                    html += '<tr><th>Nama Ruangan</th><td>' + (resource.nama_ruangan || '-') + '</td></tr>';
                    html += '<tr><th>Kapasitas</th><td>' + (resource.jml_peserta || '-') + ' orang</td></tr>';
                    html += '<tr><th>Fasilitas</th><td>' + (resource.fasilitas || '-') + '</td></tr>';
                    if(resource.foto_ruangan) {
                        html += '<tr><th>Foto Ruangan</th><td><img src="/storage/' + resource.foto_ruangan + '" alt="Foto Ruangan"></td></tr>';
                    }
                    html += '</tbody></table>';
                } else if(resourceType === 'user') {
                    title = 'Detail Pengguna';
                    html += '<h5>Informasi Pengguna</h5>';
                    html += '<table class="table table-bordered"><tbody>';
                    html += '<tr><th>Nama</th><td>' + (resource.nama || '-') + '</td></tr>';
                    html += '<tr><th>Email</th><td>' + (resource.email || '-') + '</td></tr>';
                    html += '<tr><th>Username</th><td>' + (resource.username || '-') + '</td></tr>';
                    html += '<tr><th>Role</th><td><span class="badge bg-primary">' + (resource.role || '-') + '</span></td></tr>';
                    html += '<tr><th>No. WhatsApp</th><td>' + (resource.no_wa || '-') + '</td></tr>';
                    if(resource.foto_profil) {
                        html += '<tr><th>Foto Profil</th><td><img src="/storage/' + resource.foto_profil + '" alt="Foto Profil" style="max-width:150px;"></td></tr>';
                    }
                    html += '</tbody></table>';
                }
            } else if(log) {
                // fallback previous behavior: render old/new data or details based on activity
                var activity = (log.activity || '').toLowerCase();
                if(activity.includes('deny') || activity.includes('approve')) {
                    title = activity.includes('approve') ? 'Detail Persetujuan' : 'Detail Penolakan';
                    html += '<h5>Informasi Aktivitas</h5>';
                    if(log.details) {
                        try { 
                            html += '<pre>' + JSON.stringify(log.details, null, 2) + '</pre>'; 
                        } catch(e) { 
                            html += '<div class="no-data">Tidak ada detail pengajuan.</div>'; 
                        }
                    } else {
                        html += '<div class="no-data">Tidak ada detail pengajuan.</div>';
                    }
                } else if(activity.includes('edit')) {
                    title = 'Detail Perubahan Data';
                    html += '<h5>Perbandingan Data Lama & Baru</h5>';
                    if(log.old_data && log.new_data) {
                        html += '<table class="table table-bordered"><thead><tr><th style="width:33%">Field</th><th style="width:33%">Nilai Lama</th><th style="width:34%">Nilai Baru</th></tr></thead><tbody>';
                        for(var key in log.new_data) {
                            var oldVal = log.old_data[key] ?? '-';
                            var newVal = log.new_data[key] ?? '-';
                            var isChanged = oldVal !== newVal;
                            html += '<tr' + (isChanged ? ' class="table-warning"' : '') + '>';
                            html += '<th>' + key + '</th>';
                            html += '<td>' + oldVal + '</td>';
                            html += '<td>' + newVal + (isChanged ? ' <i class="bi bi-arrow-left text-warning"></i>' : '') + '</td>';
                            html += '</tr>';
                        }
                        html += '</tbody></table>';
                    } else {
                        html += '<div class="no-data">Data lama dan baru tidak tersedia.</div>';
                    }
                } else if(activity.includes('add')) {
                    title = 'Detail Data Baru';
                    html += '<h5>Data Baru Ditambahkan</h5>';
                    if(log.new_data) {
                        html += '<table class="table table-bordered"><thead><tr><th style="width:40%">Field</th><th style="width:60%">Nilai</th></tr></thead><tbody>';
                        for(var key in log.new_data) {
                            html += '<tr><th>' + key + '</th><td>' + (log.new_data[key] ?? '-') + '</td></tr>';
                        }
                        html += '</tbody></table>';
                    } else {
                        html += '<div class="no-data">Data baru tidak tersedia.</div>';
                    }
                } else {
                    html += '<h5>Detail Aktivitas</h5>';
                    html += '<div class="no-data">Detail aktivitas tidak tersedia.</div>';
                }
            } else {
                html += '<h5>Detail Aktivitas</h5>';
                html += '<div class="no-data">Data detail tidak tersedia.</div>';
            }

            modalBody.innerHTML = html;
        });
    }

    // Fallback: tambahkan click listener untuk semua button log (tidak disabled)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing log buttons...');
        
        const logButtons = document.querySelectorAll('.log-modal-btn:not([disabled])');
        console.log('Found ' + logButtons.length + ' active log buttons');
        
        logButtons.forEach(function(btn) {
            // Pastikan Bootstrap modal bisa ter-trigger
            btn.addEventListener('click', function(e) {
                console.log('Button clicked:', {
                    logId: this.dataset.logId,
                    hasLog: !!this.dataset.log,
                    hasResource: !!this.dataset.resource,
                    resourceType: this.dataset.resourceType
                });
            });
        });
        
        // Check if Bootstrap is loaded
        if (typeof bootstrap !== 'undefined') {
            console.log('Bootstrap loaded successfully');
        } else {
            console.error('Bootstrap not loaded!');
        }
    });
</script>
@endsection
