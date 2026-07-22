@extends('layout.main')
@section('title', 'Manajemen Pengguna')
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
    
    #userSearchInput {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        transition: var(--transition);
        background-color: white;
    }
    
    #userSearchInput:focus {
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
        padding: 24px;
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
    
    .user-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin-top: 16px;
    }
    
    .user-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: var(--transition);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: var(--secondary-color);
    }
    
    .card-image {
        height: 180px;
        overflow: hidden;
        position: relative;
    }
    
    .user-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }
    
    .user-card:hover .user-photo {
        transform: scale(1.05);
    }
    
    .card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex-grow: 1;
    }
    
    .user-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0 0 8px 0;
        line-height: 1.3;
    }
    
    .user-role {
        background: #dbeafe;
        color: var(--primary-color);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 16px;
    }
    
    .user-role.admin {
        background: #dcfce7;
        color: #166534;
    }
    
    .user-role.super-admin {
        background: #f0f9ff;
        color: #075985;
    }
    
    .card-actions {
        display: flex;
        gap: 12px;
        margin-top: auto;
        width: 100%;
    }
    
    .action-button {
        flex: 1;
        padding: 8px 0;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: var(--transition);
        text-decoration: none;
    }
    
    .btn-whatsapp {
        background: #25D366;
        color: white;
        border: none;
    }
    
    .btn-whatsapp:hover {
        background: #1da851;
    }
    
    .btn-detail {
        background: #3b82f6;
        color: white;
        border: none;
    }
    
    .btn-detail:hover {
        background: #2563eb;
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
        padding: 32px;
        display: flex;
        gap: 32px;
    }
    
    .modal-left {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    #modalFotoProfil {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #dbeafe;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .user-status {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .modal-right {
        flex: 2;
    }
    
    .user-detail {
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
        padding: 16px 32px 32px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 16px;
    }
    
    .btn-modal {
        padding: 12px 24px;
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
    
    .btn-danger {
        background-color: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
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
    
    .no-results {
        display: none;
        grid-column: 1 / -1;
    }
    
    @media (max-width: 900px) {
        .modal-body {
            flex-direction: column;
            text-align: center;
        }
        
        .user-detail {
            grid-template-columns: 1fr;
        }
        
        .modal-left {
            margin-bottom: 24px;
        }
    }
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            max-width: 100%;
        }
        
        .user-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
        
        .modal-content {
            max-width: 95%;
            margin: 10px;
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .modal-footer {
            padding: 16px 24px 24px;
            flex-direction: column;
            gap: 12px;
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
            <i class="bi bi-people"></i>
            Manajemen Pengguna
        </h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="userSearchInput" onkeyup="filterUsers()" class="form-control" placeholder="Cari nama pengguna, role...">
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
                <i class="bi bi-person-lines-fill"></i>
                Daftar Pengguna
            </h2>
            <a href="{{ route('user.tambah') }}" class="btn-add-custom">
                <i class="bi bi-person-plus"></i>
                Tambah Pengguna Baru
            </a>
        </div>
        
        <div class="card-body">
            <div class="user-grid" id="userGrid">
                @forelse($all as $user)
                <div class="user-card" onclick="openModal({{ json_encode($user) }})">
                    <div class="card-image">
                        <img src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : '/images/dummyperson.jpg' }}" 
                             alt="Foto Profil {{ $user->nama }}" 
                             class="user-photo">
                    </div>
                    <div class="card-content">
                        <h3 class="user-name">{{ Str::limit($user->nama, 18) }}</h3>
                        <span class="user-role {{ strtolower(str_replace(' ', '-', $user->role)) }}">
                            {{ $user->role }}
                        </span>
                        <div class="card-actions">
                            <a href="https://wa.me/62{{ ltrim($user->no_wa, '0') }}" target="_blank" class="action-button btn-whatsapp" onclick="event.stopPropagation()">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="bi bi-people"></i>
                    <h3>Belum Ada Pengguna</h3>
                    <p>Belum ada data pengguna yang tersedia. Silakan tambahkan pengguna baru.</p>
                </div>
                @endforelse
                <div class="empty-state no-results" id="noResults">
                    <i class="bi bi-search"></i>
                    <h3>Tidak Ada Hasil</h3>
                    <p>Tidak ada pengguna yang sesuai dengan pencarian Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Detail Pengguna --}}
<div id="userDetailModal" class="custom-modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-person-badge"></i>
                Detail Pengguna
            </h3>
            <button class="btn-close" onclick="closeModal('userDetailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-left">
                <img id="modalFotoProfil" src="/images/dummyperson.jpg" alt="Foto Profil">
                <span id="modalStatus" class="user-status"></span>
            </div>
            <div class="modal-right">
                <div class="user-detail">
                    <div class="detail-label">Nama Lengkap</div>
                    <div id="modalNama" class="fw-bold"></div>
                    
                    <div class="detail-label">Email</div>
                    <div id="modalEmail"></div>
                    
                    <div class="detail-label">Username</div>
                    <div id="modalUsername"></div>
                    
                    <div class="detail-label">Role / Organisasi</div>
                    <div id="modalRole"></div>
                    
                    <div class="detail-label">No. WhatsApp</div>
                    <div id="modalNoWa"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-secondary" onclick="closeModal('userDetailModal')">
                <i class="bi bi-x"></i> Tutup
            </button>
            <button class="btn-modal btn-danger" onclick="openDeleteModal()">
                <i class="bi bi-trash"></i> Hapus Pengguna
            </button>
            <button class="btn-modal btn-primary" id="editButton">
                <i class="bi bi-pencil"></i> Edit Pengguna
            </button>
        </div>
    </div>
</div>

{{-- MODAL: Konfirmasi Hapus --}}
<div id="deleteConfirmModal" class="custom-modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-exclamation-triangle"></i>
                Konfirmasi Hapus
            </h3>
            <button class="btn-close" onclick="closeModal('deleteConfirmModal')">&times;</button>
        </div>
        <div class="modal-body text-center">
            <i class="bi bi-person-x fs-1 text-danger mb-3"></i>
            <h4 class="mb-3">Yakin ingin menghapus pengguna ini?</h4>
            <p class="text-muted mb-4">Tindakan ini tidak dapat dibatalkan. Pengguna akan dihapus secara permanen.</p>
            <div class="fw-bold" id="userNameDelete"></div>
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

<script>
    let currentUser = null;
    let userIdToDelete = null;

    function openModal(user) {
        currentUser = user;
        
        // Update foto profil
        const fotoSrc = user.foto_profil ? `/storage/${user.foto_profil}` : '/images/dummyperson.jpg';
        document.getElementById('modalFotoProfil').src = fotoSrc;
        
        // Update data pengguna
        document.getElementById('modalNama').innerText = user.nama || 'Tidak tersedia';
        document.getElementById('modalEmail').innerText = user.email || 'Tidak tersedia';
        document.getElementById('modalUsername').innerText = user.username || 'Tidak tersedia';
        document.getElementById('modalNoWa').innerText = user.no_wa || 'Tidak tersedia';
        
        // Format role/organisasi
        let roleText = 'Tidak tersedia';
        if (user.role) {
            if (user.role === 'OPD' && user.organization && user.organization.organization_name) {
                roleText = `OPD - ${user.organization.organization_name}`;
            } else {
                roleText = user.role;
            }
        }
        document.getElementById('modalRole').innerText = roleText;
        
        // Set status badge
        const statusEl = document.getElementById('modalStatus');
        statusEl.innerText = user.role || 'User';
        statusEl.className = 'user-status';
        
        if (user.role === 'Admin') {
            statusEl.style.backgroundColor = '#dcfce7';
            statusEl.style.color = '#166534';
        } else if (user.role === 'Super Admin') {
            statusEl.style.backgroundColor = '#f0f9ff';
            statusEl.style.color = '#075985';
        } else if (user.role === 'OPD') {
            statusEl.style.backgroundColor = '#dbeafe';
            statusEl.style.color = '#1e40af';
        } else {
            statusEl.style.backgroundColor = '#f3f4f6';
            statusEl.style.color = '#4b5563';
        }
        
        // Set edit button URL
        document.getElementById('editButton').onclick = function() {
            window.location.href = `/user/${user.id}/edit`;
        };
        
        openModalWindow('userDetailModal');
    }
    
    function openDeleteModal() {
        if (currentUser) {
            userIdToDelete = currentUser.id;
            document.getElementById('userNameDelete').innerText = `Pengguna: ${currentUser.nama}`;
            closeModal('userDetailModal');
            openModalWindow('deleteConfirmModal');
        }
    }
    
    function executeDelete() {
        if (userIdToDelete) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/user/${userIdToDelete}`;
            deleteForm.submit();
        }
    }
    
    function openModalWindow(modalId) {
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
        
        // Reset modal animation
        setTimeout(() => {
            const content = modal.querySelector('.modal-content');
            content.classList.remove('show');
        }, 300);
    }
    
    // Close modals when clicking outside
    document.querySelectorAll('.custom-modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal(this.id);
            }
        });
    });
    
    function filterUsers() {
        const input = document.getElementById('userSearchInput');
        const filter = input.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.user-card');
        let visibleCount = 0;
        const noResults = document.getElementById('noResults');
        
        cards.forEach(card => {
            const nameElement = card.querySelector('.user-name');
            const roleElement = card.querySelector('.user-role');
            
            if (nameElement && roleElement) {
                const name = nameElement.textContent.toLowerCase();
                const role = roleElement.textContent.toLowerCase();
                
                if (name.includes(filter) || role.includes(filter)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0 && cards.length > 0) {
            noResults.style.display = 'flex';
        } else {
            noResults.style.display = 'none';
        }
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Make WhatsApp links work without closing modal
        document.querySelectorAll('.btn-whatsapp').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>
@endsection