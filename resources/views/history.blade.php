@extends('layout.main')

@section('title', 'Riwayat Pengajuan')
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
        padding-left: 60px;
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
        min-width: 1000px;
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
    
    .no-data-row td {
        text-align: center;
        padding: 40px 0;
        font-style: italic;
        color: #64748b;
        font-size: 1.1rem;
    }
    
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background-color: var(--secondary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background-color: #64748b;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #475569;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(100, 116, 139, 0.3);
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
        max-width: 500px;
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
        padding: 32px;
        text-align: center;
    }
    
    .qr-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        margin: 0 auto;
        max-width: 350px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        position: relative;
    }
    
    .qr-image {
        width: 100%;
        max-width: 280px;
        margin: 0 auto;
        display: block;
    }
    
    .qr-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: var(--primary-color);
        font-weight: 600;
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
        
        .table-container {
            font-size: 0.85rem;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
        }
        
        .modal-content {
            max-width: 95%;
            margin: 10px;
        }
    }
</style>

<div class="page-container py-4">
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-clock-history"></i>
            Riwayat Pengajuan
        </h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" 
                   placeholder="Cari pengaju, kegiatan, ruangan...">
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-list-ul"></i>
                Daftar Riwayat Pengajuan
            </h2>
        </div>
        
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Pengaju</th>
                            <th style="width: 20%;">Kegiatan</th>
                            <th style="width: 15%;">Ruangan</th>
                            <th style="width: 12%;">Waktu Mulai</th>
                            <th style="width: 12%;">Waktu Selesai</th>
                            <th style="width: 8%;">Peserta</th>
                            <th style="width: 8%;">Status</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
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
                                    <div class="d-flex gap-2">
                                        <button class="btn-action btn-primary" 
                                                onclick="showQrCode({{ $pengajuan->id }})">
                                            <i class="bi bi-qr-code"></i> QR
                                        </button>
                                        <a href="{{ route('presensi.show', $pengajuan->id) }}" 
                                           class="btn-action btn-secondary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data-row">
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="bi bi-clock-history"></i>
                                        <h3>Belum Ada Riwayat</h3>
                                        <p>Belum ada data riwayat pengajuan yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="noResultsRow" style="display: none;" class="no-data-row">
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-search"></i>
                                    <h3>Tidak Ada Hasil</h3>
                                    <p>Tidak ada riwayat yang sesuai dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-qr-code"></i>
                QR Code Presensi
            </h3>
            <button class="btn-close" onclick="closeModal('qrModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p class="mb-4 text-muted">Scan QR code ini untuk melakukan presensi pada kegiatan:</p>
            <div class="qr-container">
                <div id="qrCodeContainer" class="d-flex justify-content-center align-items-center" style="height: 280px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat QR Code...</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-blue-50 rounded-lg text-center">
                <p class="mb-2 fw-medium">Petunjuk Penggunaan:</p>
                <p class="mb-0 text-muted small">Buka kamera ponsel Anda, arahkan ke QR code di atas, atau gunakan aplikasi WhatsApp untuk scan QR code.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase().trim();
        const tableBody = document.getElementById("historyTableBody");
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

    let refreshInterval;

    function showQrCode(pengajuanId) {
        openModal('qrModal');
        loadQrCode(pengajuanId);
    }

    function loadQrCode(pengajuanId) {
        const qrContainer = document.getElementById("qrCodeContainer");
        const qrUrl = `${window.location.origin}/presensi/${pengajuanId}`;
        
        // Clear any existing interval
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        // Generate QR code
        QRCode.toCanvas(qrUrl, {
            width: 240,
            margin: 2,
            color: {
                dark: "#1e3a8a",
                light: "#ffffff"
            }
        }, function (error, canvas) {
            if (error) {
                console.error(error);
                qrContainer.innerHTML = `
                    <div class="text-center">
                        <i class="bi bi-x-circle text-danger fs-1"></i>
                        <p class="mt-2">Gagal memuat QR Code</p>
                    </div>
                `;
                return;
            }
            
            qrContainer.innerHTML = '';
            qrContainer.appendChild(canvas);
            
            // Add refresh functionality
            refreshInterval = setInterval(() => {
                QRCode.toCanvas(qrUrl + '?t=' + new Date().getTime(), {
                    width: 240,
                    margin: 2,
                    color: {
                        dark: "#1e3a8a",
                        light: "#ffffff"
                    }
                }, function (err, newCanvas) {
                    if (!err) {
                        qrContainer.innerHTML = '';
                        qrContainer.appendChild(newCanvas);
                    }
                });
            }, 60000); // Refresh every minute
        });
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
        modal.style.display = 'none';
        document.body.style.overflow = '';
        
        // Clear interval when closing modal
        if (modalId === 'qrModal' && refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
        
        // Reset modal animation
        setTimeout(() => {
            const content = modal.querySelector('.modal-content');
            content.classList.remove('show');
        }, 300);
    }

    // Close modal when clicking outside
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Load QR code library
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof QRCode === 'undefined') {
            const script = document.createElement('script');
            script.src = "https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js";
            script.onload = function() {
                console.log('QRCode library loaded');
            };
            script.onerror = function() {
                console.error('Failed to load QRCode library');
            };
            document.head.appendChild(script);
        }
    });
</script>
@endsection