<?php ?>
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
        background-color: #e6f0ff;
    }
    
    .navbar {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 64px;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.95), rgba(241, 245, 249, 0.95));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(30, 58, 138, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        transition: padding-left 0.3s ease-in-out;
        z-index: 999;
        box-shadow: var(--box-shadow);
    }
    
    .navbar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        white-space: nowrap;
        padding-left: calc(60px + 1.5rem);
        transition: padding-left 0.3s ease-in-out;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .sidebar:hover ~ .navbar .navbar-title {
        padding-left: calc(210px + 1.5rem);
    }
    
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-right: 1.5rem;
    }
    
    .notification-btn {
        position: relative;
        cursor: pointer;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2e8f0;
        transition: var(--transition);
        color: var(--primary-color);
    }
    
    .notification-btn:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }
    
    .notification-indicator {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background-color: var(--danger-color);
        border-radius: 50%;
        display: none;
        border: 2px solid white;
    }
    
    .notification-indicator.active {
        display: block;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    
    .profile-toggle {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: var(--transition);
        padding: 6px 8px;
        border-radius: 10px;
    }
    
    .profile-toggle:hover {
        background: rgba(30, 58, 138, 0.08);
    }
    
    .profile-photo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid var(--primary-color);
        object-fit: cover;
        transition: var(--transition);
    }
    
    .profile-toggle:hover .profile-photo {
        transform: scale(1.05);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.3);
    }
    
    .profile-info {
        display: flex;
        flex-direction: column;
        text-align: right;
    }
    
    .profile-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--dark-color);
        line-height: 1.2;
    }
    
    .profile-role {
        font-size: 0.8rem;
        color: var(--primary-color);
        font-weight: 500;
    }
    
    .dropdown-menu {
        min-width: 200px;
        background: white;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-radius: var(--border-radius);
        padding: 8px 0;
        margin-top: 8px;
        /* Menghapus transform dan opacity default untuk Bootstrap */
        transform: none !important;
        opacity: 1 !important;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        color: var(--dark-color);
        font-weight: 500;
        font-size: 0.95rem;
        transition: var(--transition);
    }
    
    .dropdown-item i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    
    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: var(--primary-color);
        transform: translateX(4px);
    }
    
    .dropdown-divider {
        margin: 6px 0;
        border-color: #e2e8f0;
    }
    
    /* Animasi dropdown */
    .dropdown.show .dropdown-menu {
        animation: dropdownSlide 0.2s ease forwards;
    }
    
    @keyframes dropdownSlide {
        from { 
            opacity: 0; 
            transform: translateY(10px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }
    
    .dropdown.hide .dropdown-menu {
        animation: dropdownSlideOut 0.15s ease forwards;
    }
    
    @keyframes dropdownSlideOut {
        from { 
            opacity: 1; 
            transform: translateY(0);
        }
        to { 
            opacity: 0; 
            transform: translateY(5px);
        }
    }
</style>

@include('sidebar.sidebar')

<div class="navbar">
    <div class="navbar-title">
        <i class="bi bi-door-open"></i>
        Sistem Pengajuan Ruangan Rapat Kabupaten Probolinggo
    </div>
    <div class="navbar-right">
        <div class="notification-btn" data-bs-toggle="modal" data-bs-target="#notificationModal">
            <div class="notification-indicator" id="notificationIndicator"></div>
            <i class="bi bi-bell"></i>
        </div>
        
        <div class="dropdown">
            <div class="profile-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if(session('user_foto'))
                    <img src="{{ asset('storage/' . session('user_foto')) }}" alt="Foto Profil" class="profile-photo">
                @else
                    <img src="{{ asset('images/dummyperson.jpg') }}" alt="Foto Profil" class="profile-photo">
                @endif
                
                <div class="profile-info">
                    <span class="profile-name">{{ session('user_nama', 'Guest') }}</span>
                    <span class="profile-role">{{ session('user_role', 'Guest') }}</span>
                </div>
                <i class="bi bi-chevron-down ms-2" style="font-size: 0.9rem;"></i>
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-gear"></i>
                        Edit Profil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let lastNotificationCount = parseInt(localStorage.getItem('lastNotificationCount') || '0');
    let lastCheckTime = parseInt(localStorage.getItem('lastNotificationCheck') || '0');
    const indicator = document.getElementById('notificationIndicator');
    
    function checkNewNotifications() {
        const now = Date.now();
        fetch('/api/notifications')
            .then(response => response.json())
            .then(data => {
                const currentCount = Array.isArray(data) ? data.length : 0;
                
                // Show indicator if there are new notifications since last check
                if (currentCount > lastNotificationCount) {
                    indicator.classList.add('active');
                }
                // Also show indicator if there are notifications and user hasn't checked in last 6 hours
                else if (currentCount > 0 && (now - lastCheckTime) > (6 * 60 * 60 * 1000)) {
                    indicator.classList.add('active');
                }
                
                lastNotificationCount = currentCount;
                localStorage.setItem('lastNotificationCount', currentCount.toString());
            })
            .catch(error => console.error('Error checking notifications:', error));
    }

    // Check for new notifications periodically
    checkNewNotifications();
    setInterval(checkNewNotifications, 30000); // Check every 30 seconds

    function loadNotifications() {
        const notificationList = document.getElementById('notificationList');
        if (!notificationList) return;
        
        notificationList.innerHTML = `
            <div class="notification-item py-3 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 text-muted">Memuat notifikasi...</div>
            </div>`;
            
        fetch('/api/notifications')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data || !Array.isArray(data) || data.length === 0) {
                    notificationList.innerHTML = `
                        <div class="notification-item py-4 text-center text-muted">
                            <i class="bi bi-bell-slash fs-1 mb-2"></i>
                            <div class="notification-text">Tidak ada notifikasi baru</div>
                        </div>`;
                    return;
                }

                notificationList.innerHTML = data.map(notification => {
                    // Format the time to "x minutes ago"
                    const createdAt = notification.created_at.toLowerCase();
                    return `
                    <div class="notification-item py-3 border-bottom border-light">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-blue-100 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="notification-text fw-medium">${notification.message}</div>
                                <div class="notification-time text-muted small">${createdAt}</div>
                            </div>
                        </div>
                    </div>
                    `;
                }).join('');
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = `
                    <div class="notification-item py-4 text-center">
                        <i class="bi bi-exclamation-triangle text-danger fs-1 mb-2"></i>
                        <div class="text-danger fw-medium">Gagal memuat notifikasi</div>
                        <small class="text-muted">${error.message}</small>
                    </div>`;
            });
    }

    // Load notifications when modal is opened
    const notificationModal = document.getElementById('notificationModal');
    if (notificationModal) {
        notificationModal.addEventListener('show.bs.modal', () => {
            loadNotifications();
            indicator.classList.remove('active'); // Remove indicator when opening modal
            lastCheckTime = Date.now();
            localStorage.setItem('lastNotificationCheck', lastCheckTime.toString());
        });

        notificationModal.addEventListener('hide.bs.modal', () => {
            // Update last count after closing
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    const currentCount = Array.isArray(data) ? data.length : 0;
                    lastNotificationCount = currentCount;
                    localStorage.setItem('lastNotificationCount', currentCount.toString());
                })
                .catch(console.error);
        });

        // Fix modal stacking issues
        notificationModal.addEventListener('hidden.bs.modal', () => {
            document.body.classList.remove('modal-open');
            const modalBackdrops = document.getElementsByClassName('modal-backdrop');
            while (modalBackdrops.length > 0) {
                modalBackdrops[0].parentNode.removeChild(modalBackdrops[0]);
            }
        });
    }
    
    // Dropdown animation using Bootstrap events
    document.querySelectorAll('.dropdown').forEach(function(dropdownElement) {
        dropdownElement.addEventListener('show.bs.dropdown', function() {
            const menu = this.querySelector('.dropdown-menu');
            menu.style.opacity = '0';
            menu.style.transform = 'translateY(5px)';
            setTimeout(() => {
                menu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                menu.style.opacity = '1';
                menu.style.transform = 'translateY(0)';
            }, 10);
        });
        
        dropdownElement.addEventListener('hide.bs.dropdown', function() {
            const menu = this.querySelector('.dropdown-menu');
            menu.style.opacity = '0';
            menu.style.transform = 'translateY(5px)';
        });
    });
    
    // Untuk menutup dropdown ketika klik di luar dropdown area
    document.addEventListener('click', function(event) {
        document.querySelectorAll('.dropdown.show').forEach(function(dropdown) {
            const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            if (!toggle.contains(event.target) && !menu.contains(event.target)) {
                const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }
        });
    });
    
    // Untuk menutup dropdown ketika tekan tombol Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.dropdown.show').forEach(function(dropdown) {
                const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            });
        }
    });
});
</script>