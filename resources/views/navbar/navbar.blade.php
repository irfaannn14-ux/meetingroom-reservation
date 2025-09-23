<?php ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

    :root {
        --color-bg: #C9DFF2;
        --color-dark: #010D26;
        --color-light: #ffffff;
        --color-hover-bg: #010D26;
        --color-hover-text: #ffffff;

        --sidebar-collapsed: 60px;
        --sidebar-expanded: 210px;
        --logo-left-padding: 0.5rem;
        --content-gap: 12px;
    }
    body {
        background-color: var(--color-bg);
        font-family: 'Montserrat', sans-serif;
        margin: 0;
    }
    .navbar {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 60px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
        transition: padding-left 0.3s ease-in-out;
        z-index: 999;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    .navbar-title {
        font-size: 1rem;
        font-weight: bold;
        color: var(--color-dark);
        white-space: nowrap;
        padding-left: calc(var(--sidebar-collapsed) + 2rem);
        transition: padding-left 0.3s ease-in-out;
    }
    .sidebar:hover ~ .navbar .navbar-title {
        padding-left: calc(var(--sidebar-expanded) + 3rem);
    }
    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-right: 2rem;
    }
    .profile-icon {
        color: var(--color-dark);
    }
    .profile-photo {
        width: 38px;
        height: 38px;
        background-color: var(--color-bg);
        border-radius: 50%;
        border: 2px solid var(--color-dark);
    }
    .profile-info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .profile-name {
        font-size: 1rem;
        font-weight: bold;
        color: var(--color-dark);
    }
    .profile-role {
        font-size: 0.8rem;
        color: var(--color-dark);
    }
    .dropdown-toggle {
        border: none;
        background: none;
        color: var(--color-dark);
        padding: 5px;
    }
    .dropdown-toggle:hover {
        background: rgba(1, 13, 38, 0.1);
        border-radius: 4px;
    }
    .dropdown-menu {
        min-width: 150px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    .notification-btn {
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background-color 0.2s;
        position: relative;
    }
    .notification-btn:hover {
        background: rgba(1, 13, 38, 0.1);
    }
    .notification-indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 8px;
        background-color: #dc3545;
        border-radius: 50%;
        display: none;
    }
    .notification-indicator.active {
        display: block;
    }
    /* Notification styles are now in main.blade.php */
</style>

@include('sidebar.sidebar')

<div class="navbar">
    <div class="navbar-title">Aplikasi Pengajuan Peminjaman Ruangan PemDa Probolinggo</div>
    <div class="navbar-right">
        <div class="notification-btn" data-bs-toggle="modal" data-bs-target="#notificationModal">
            <div class="notification-indicator" id="notificationIndicator"></div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="profile-icon lucide lucide-bell"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        </div>
        
        <div class="dropdown">
            <div class="d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                @if(session('user_foto'))
                    <img src="{{ asset('storage/' . session('user_foto')) }}" alt="Foto Profil" class="profile-photo" style="object-fit: cover;">
                @else
                    <img src="{{ asset('images/dummyperson.jpg') }}" alt="Foto Profil" class="profile-photo" style="object-fit: cover;">
                @endif
                
                <div class="profile-info ms-2">
                    <span class="profile-name">{{ session('user_nama', 'Guest') }}</span>
                    <span class="profile-role">{{ session('user_role', 'Guest') }}</span>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-3"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
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
        notificationList.innerHTML = `
            <div class="notification-item">
                <div class="notification-content">Memuat notifikasi...</div>
            </div>`;
            
        fetch('/api/notifications')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Received notifications:', data);
                
                if (!data || !Array.isArray(data) || data.length === 0) {
                    notificationList.innerHTML = `
                        <div class="notification-item">
                            <div class="notification-text">Tidak ada notifikasi</div>
                        </div>`;
                    return;
                }

                notificationList.innerHTML = data.map(notification => {
                    // Format the time to "x minutes ago"
                    const createdAt = notification.created_at.toLowerCase();
                    return `
                    <div class="notification-item">
                        <div class="notification-text">${notification.message}</div>
                        <div class="notification-time">${createdAt}</div>
                    </div>
                    `;
                }).join('');
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = `
                    <div class="notification-item">
                        <div class="notification-content">
                            <div class="text-danger">Gagal memuat notifikasi</div>
                            <small>${error.message}</small>
                        </div>
                    </div>`;
            });
    }

    // Load notifications when modal is opened
    const notificationModal = document.getElementById('notificationModal');
    
    // Handle modal events
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
});
</script>