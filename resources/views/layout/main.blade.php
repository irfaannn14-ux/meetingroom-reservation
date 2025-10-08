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

        /* Modal Base Styles */
        .modal-dialog.notification-modal {
            max-width: 400px;
            margin: 1.75rem auto;
        }
        
        .modal-content {
            border: none !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Modal Header */
        .modal-header {
            background-color: #000C2B !important;
            padding: 1rem 1.5rem !important;
            border: none !important;
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }
        
        .modal-title {
            color: #ffffff !important;
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            font-family: 'Montserrat', sans-serif !important;
            margin: 0 !important;
        }
        
        /* Close Button */
        .modal-header .btn-close {
            padding: 0.5rem !important;
            margin: 0 !important;
            background: none !important;
            border: none !important;
            opacity: 1 !important;
            position: relative !important;
        }
        
        .modal-header .btn-close::before {
            content: "×";
            color: white;
            font-size: 28px;
            line-height: 1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Modal Body */
        .modal-body {
            padding: 0 !important;
            max-height: 70vh !important;
            overflow-y: auto !important;
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

        /* Modal Stacking Styles */
        .modal {
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
        }
        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 8px;
            width: 100%;
            animation: fadeIn 0.3s ease;
            position: relative;
        }
        .modal.show {
            display: flex !important;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>


</head>

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

<body>
    @include('sidebar.sidebar')
    @include('navbar.navbar')

    @yield('content')
    
    <!-- Notification Modal -->
    <div class="modal" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
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
    
    <!-- Notification Script -->
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
                    <div class="notification-text">Memuat notifikasi...</div>
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
                            <div class="notification-item">
                                <div class="notification-text">Tidak ada notifikasi</div>
                            </div>`;
                        return;
                    }

                    notificationList.innerHTML = data.map(notification => {
                        return `
                        <div class="notification-item">
                            <div class="notification-text">${notification.message}</div>
                            <div class="notification-time">${notification.created_at}</div>
                        </div>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = `
                        <div class="notification-item">
                            <div class="notification-text text-danger">Gagal memuat notifikasi</div>
                            <small>${error.message}</small>
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
