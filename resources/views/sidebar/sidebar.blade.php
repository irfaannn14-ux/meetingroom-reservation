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
        margin: 0;
    }
    
    .sidebar {
        width: 90px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.95), rgba(241, 245, 249, 0.95));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-right: 1px solid rgba(30, 58, 138, 0.1);
        color: var(--dark-color);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow-x: hidden;
        overflow-y: auto;
        padding: 1.2rem 0.8rem;
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        box-shadow: var(--box-shadow);
    }
    
    .sidebar::after {
        content: "";
        position: absolute;
        top: 0;
        right: -15px;
        width: 15px;
        height: 100%;
    }
    
    .sidebar:hover {
        width: 300px;
        padding: 1.2rem 1.2rem;
    }
    
    .sidebar-logo {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding-bottom: 1.8rem;
        width: 100%;
        transition: var(--transition);
    }
    
    .sidebar-logo img {
        width: 40px;
        height: 40px;
        transition: transform 0.3s ease-in-out;
        display: block;
        margin: 0;
        flex-shrink: 0;
        object-fit: contain;
    }
    
    .sidebar:hover .sidebar-logo img {
        transform: translateX(calc((300px - 90px) / 2 - 0.8rem));
    }
    
    .logo-text {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        margin-left: 12px;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--primary-color);
        letter-spacing: -0.5px;
    }
    
    .sidebar:hover .logo-text {
        opacity: 1;
    }
    
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
        overflow-y: auto;
        height: calc(100% - 80px);
    }
    
    .sidebar-item {
        margin-bottom: 0.6rem;
        position: relative;
    }
    
    .sidebar-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--dark-color);
        padding: 0.9rem 1rem;
        border-radius: var(--border-radius);
        background: white;
        transition: var(--transition);
        white-space: nowrap;
        font-weight: 500;
        font-size: 0.95rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }
    
    .sidebar-link:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateX(4px);
        border-color: var(--secondary-color);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.15);
    }
    
    .sidebar-link.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 8px rgba(30, 58, 138, 0.2);
    }
    
    .sidebar-icon {
        min-width: 28px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 12px;
    }
    
    .sidebar-link:hover .sidebar-icon,
    .sidebar-link.active .sidebar-icon {
        color: white;
    }
    
    .sidebar-link-text {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        flex-grow: 1;
    }
    
    .sidebar:hover .sidebar-link-text {
        opacity: 1;
    }
    
    /* Dropdown styles */
    .sidebar-dropdown {
        position: relative;
    }
    
    .dropdown-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        cursor: pointer;
        border: none;
        background: none;
        outline: none;
    }
    
    /* Dropdown menu - hidden by default */
    .sidebar-dropdown-menu {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        min-width: 250px;
        background: white;
        border-radius: var(--border-radius);
        padding: 0.5rem;
        margin-left: 0.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        flex-direction: column;
        gap: 0.4rem;
    }
    
    /* Show dropdown on hover OR when has .open class */
    .sidebar-dropdown:hover > .sidebar-dropdown-menu,
    .sidebar-dropdown.open > .sidebar-dropdown-menu {
        display: flex;
    }
    
    /* When sidebar is expanded (hovered), show dropdown below instead */
    .sidebar:hover .sidebar-dropdown-menu {
        position: relative;
        left: 0;
        margin-left: 0;
        padding-left: 2.5rem;
        padding-right: 0;
        box-shadow: none;
        background: transparent;
        min-width: auto;
        margin-top: 0.5rem;
    }
    
    .sidebar:hover .sidebar-dropdown:hover > .sidebar-dropdown-menu,
    .sidebar:hover .sidebar-dropdown.open > .sidebar-dropdown-menu {
        display: flex;
    }
    
    .sidebar-dropdown-menu .sidebar-link {
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        color: var(--dark-color);
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        white-space: nowrap;
    }
    
    .sidebar-dropdown-menu .sidebar-link:hover {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        transform: translateX(2px);
    }
    
    .sidebar-dropdown-menu .sidebar-link .sidebar-link-text {
        opacity: 1 !important;
    }
    
    .sidebar-dropdown-menu .sidebar-link.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 2px 4px rgba(30, 58, 138, 0.15);
    }
    
    .arrow-icon {
        transition: transform 0.3s ease;
        font-size: 0.9rem;
        min-width: 20px;
    }
    
    .sidebar-dropdown.open .arrow-icon {
        transform: rotate(90deg);
    }
    
    .sidebar-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 0.8rem 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .sidebar:hover .sidebar-divider {
        opacity: 1;
    }
    
    /* Main content adjustment */
    .main-content {
        margin-left: 90px;
        transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        padding-top: 64px;
    }
    
    .sidebar:hover ~ .main-content {
        margin-left: 300px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .sidebar {
            width: 90px;
        }
        
        .sidebar:hover {
            width: 280px;
        }
        
        .sidebar:hover ~ .main-content {
            margin-left: 280px;
        }
    }
    
    @media (max-width: 768px) {
        .sidebar {
            width: 90px;
        }
        
        .sidebar:hover {
            width: 260px;
        }
        
        .sidebar:hover ~ .main-content {
            margin-left: 260px;
        }
    }
</style>

<div class="sidebar">
    <div class="sidebar-logo">
        <a href="/">
            <img src="{{ asset('images/logoipsum.png') }}" alt="Logo BKD">
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="/" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}">
                <span class="sidebar-icon">
                    <i class="bi bi-speedometer2"></i>
                </span>
                <span class="sidebar-link-text">Dashboard</span>
            </a>
        </li>
        
        <div class="sidebar-divider"></div>
        
        <!-- Mulai custom dropdown Manajemen Pengajuan -->
        <li class="sidebar-item sidebar-dropdown {{ request()->is('ruangan*','pengajuan*','history*') ? 'open' : '' }}">
            <button type="button" class="sidebar-link dropdown-toggle" aria-expanded="{{ request()->is('ruangan*','pengajuan*','history*') ? 'true' : 'false' }}">
                <span class="sidebar-icon">
                    <i class="bi bi-card-list"></i>
                </span>
                <span class="sidebar-link-text">Manajemen Pengajuan</span>
                <span class="arrow-icon">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </button>
            <div class="sidebar-dropdown-menu">
                <a href="/ruangan/index" class="sidebar-link {{ request()->is('ruangan*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <i class="bi bi-building"></i>
                    </span>
                    <span class="sidebar-link-text">Daftar Ruangan</span>
                </a>
                <a href="/index" class="sidebar-link {{ request()->is('pengajuan*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </span>
                    <span class="sidebar-link-text">Daftar Pengajuan</span>
                </a>
                <a href="/history" class="sidebar-link {{ request()->is('history*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    <span class="sidebar-link-text">History Pengajuan</span>
                </a>
            </div>
        </li>
        <!-- Akhir custom dropdown Manajemen Pengajuan -->
        
        @if(session('user_role') === 'Admin' || session('user_role') === 'Super Admin')
        <div class="sidebar-divider"></div>
        
        <li class="sidebar-item">
            <a href="/user" class="sidebar-link {{ request()->is('user*') ? 'active' : '' }}">
                <span class="sidebar-icon">
                    <i class="bi bi-people"></i>
                </span>
                <span class="sidebar-link-text">Manajemen User</span>
            </a>
        </li>
        @endif
        
        @if(session('user_role') === 'Super Admin')
        <div class="sidebar-divider"></div>
        
        <li class="sidebar-item">
            <a href="{{ route('log.index') }}" class="sidebar-link {{ request()->is('log*') ? 'active' : '' }}">
                <span class="sidebar-icon">
                    <i class="bi bi-journal-text"></i>
                </span>
                <span class="sidebar-link-text">Log Aktivitas</span>
            </a>
        </li>
        @endif
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Sidebar script loaded');
        
        // Manajemen Pengajuan dropdown toggle
        const dropdownToggle = document.querySelector('.sidebar-dropdown .dropdown-toggle');
        const dropdownContainer = document.querySelector('.sidebar-dropdown');
        
        console.log('Dropdown toggle:', dropdownToggle);
        console.log('Dropdown container:', dropdownContainer);
        
        if (dropdownToggle && dropdownContainer) {
            // Click to toggle
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Dropdown clicked');
                dropdownContainer.classList.toggle('open');
                console.log('Has open class:', dropdownContainer.classList.contains('open'));
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (dropdownContainer && !dropdownContainer.contains(e.target)) {
                dropdownContainer.classList.remove('open');
            }
        });
        
        // Automatically open dropdown if current page is in dropdown items
        const currentPath = window.location.pathname;
        const dropdownMenuLinks = document.querySelectorAll('.sidebar-dropdown-menu .sidebar-link');
        
        dropdownMenuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (currentPath.includes(href)) {
                if (dropdownContainer) {
                    dropdownContainer.classList.add('open');
                    console.log('Auto-opened dropdown for current page');
                }
            }
        });
    });
</script>
<?php ?>