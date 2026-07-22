<?php?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    @yield('styles')
    
    <!-- Common Modal Styles -->
    <style>
        /* Global Background */
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #010D26 0%, #1a2b4a 30%, #4a6fa5 70%, #C9DFF2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Notification Modal Base Styles - scoped to notification modal only */
        .modal-dialog.notification-modal {
            max-width: 400px;
            margin: 1.75rem auto;
        }
        
        #notificationModal .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Notification Modal Header */
        #notificationModal .modal-header {
            background-color: #000C2B;
            padding: 1rem 1.5rem;
            border: none;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        
        #notificationModal .modal-title {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
        
        /* Close Button */
        #notificationModal .modal-header .btn-close {
            padding: 0.5rem;
            margin: 0;
            background: none;
            border: none;
            opacity: 1;
            position: relative;
        }
        
        #notificationModal .modal-header .btn-close::before {
            content: "×";
            color: white;
            font-size: 28px;
            line-height: 1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Notification Modal Body */
        #notificationModal .modal-body {
            padding: 0;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        /* Notification Items */
        .notification-item {
            padding: 1rem 1.5rem !important;
            border-bottom: 1px solid #eee !important;
            background: white !important;
        }
        
        .notification-item:last-child {
            border-bottom: none !important;
        }
        
        .notification-text {
            color: #000C2B !important;
            font-size: 0.95rem !important;
            line-height: 1.4 !important;
            margin-bottom: 0.25rem !important;
            font-family: 'Montserrat', sans-serif !important;
        }
        
        .notification-time {
            color: #666 !important;
            font-size: 0.85rem !important;
            font-family: 'Montserrat', sans-serif !important;
        }
        
        /* Override Bootstrap Modal Backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        .modal.fade .modal-dialog {
            transform: translate(0, -50px) !important;
            transition: transform 0.3s ease-out !important;
        }
        
        .modal.show .modal-dialog {
            transform: none !important;
        }

        /* Custom Modal Stacking Styles (for non-Bootstrap modals like log detail) */
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
            box-sizing: border-box;
            z-index: 1055;
        }
        .custom-modal-backdrop.show {
            display: flex !important;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>


</head>

<body>
    {{-- Toast container (pojok kanan atas) --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
      <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body d-flex align-items-center gap-2">
            {{-- icon check --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 1 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.08-.02l3.99-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <span id="successToastMsg">Absensi berhasil.</span>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    @include('navbar.navbar')

    @yield('content')
    
    <!-- Notification Modal (Bootstrap) -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog notification-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationList">
                    <!-- Notifications will be loaded here via JavaScript -->
                    <div class="notification-item">
                        <div class="notification-text">Loading notifications...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (wajib sebelum DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Script (must be after Bootstrap JS) -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      @if(session('success'))
        // set pesan dari flash (jika ada)
        const msg = @json(session('success'));
        const el = document.getElementById('successToast');
        const msgEl = document.getElementById('successToastMsg');
        if (msg && msgEl) msgEl.textContent = msg;

        // tampilkan toast
        const toast = new bootstrap.Toast(el, { delay: 2800, autohide: true });
        toast.show();
      @endif
    });
    </script>

    <!-- Notification Script (must be after Bootstrap JS and after modal HTML) -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let lastNotificationCount = parseInt(localStorage.getItem('lastNotificationCount') || '0');
        let lastCheckTime = parseInt(localStorage.getItem('lastNotificationCheck') || '0');
        const indicator = document.getElementById('notificationIndicator');
        
        if (!indicator) return; // Guard if not logged in
        
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
                indicator.classList.remove('active');
                lastCheckTime = Date.now();
                localStorage.setItem('lastNotificationCheck', lastCheckTime.toString());
            });

            notificationModal.addEventListener('hide.bs.modal', () => {
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

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            if ($('#myTable').length) {
                $('#myTable').DataTable({
                    responsive: true,
                    pageLength: 5
                });
            }
        });
    </script>
    @yield('javascript')
</body>
</html>
