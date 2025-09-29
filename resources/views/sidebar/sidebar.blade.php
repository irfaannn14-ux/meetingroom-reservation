<?php ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

    :root {
        --color-bg: #C9DFF2;
        --color-dark: #010D26;
        --color-light: #ffffff;
        --color-hover-bg: #010D26;
        --color-hover-text: #ffffff;

        --sidebar-collapsed: 70px;
        --sidebar-expanded: 310px;
        --logo-left-padding: 0.5rem;
        --content-gap: 12px;
    }
    body {
        background-color: var(--color-bg);
        font-family: 'Montserrat', sans-serif;
        margin: 0; /* reset margin default browser agar tidak ada jarak tepi */
    }
    /* Reset margin heading pada navbar agar tidak mendorong tinggi/ada jarak */
    .navbar { width: 100%; margin: 0; padding: 0; }
    .navbar h1 { margin: 0; }
    /* Sidebar base */
    .sidebar {
        width: var(--sidebar-collapsed);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--color-dark);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow: hidden;
        padding: 1rem 0.5rem;
        transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
        z-index: 1000;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
    }
    /* Perluas area hover agar mudah memicu expand */
    .sidebar::after {
        content: "";
        position: absolute;
        top: 0;
        right: -12px; /* strip hover tipis */
        width: 12px;
        height: 100%;
    }
    .sidebar:hover {
        width: var(--sidebar-expanded);
        padding: 1rem 1rem;
    }
    /* Konten utama menyesuaikan lebar sidebar */
    .main-content {
        margin-left: var(--sidebar-collapsed);
        transition: margin-left 0.3s ease-in-out;
    }
    .sidebar:hover ~ .main-content {
        margin-left: calc(var(--sidebar-expanded) + var(--content-gap));
    }
    /* tetap start di kiri; jangan animasi justify-content */
    .sidebar-logo {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding-left: var(--logo-left-padding);
        padding-bottom: 2rem;
        width: 100%;
    }
    .sidebar-logo img {
        width: 38px;
        height: 40px;
        transition: transform 0.3s ease-in-out; /* animasi halus */
        display: block;
        margin: 0;
        flex-shrink: 0;
        object-fit: contain;
        transform: none;
    }
    /* saat hover, geser ke tengah dengan transform */
    .sidebar:hover .sidebar-logo img {
        transform: translateX(calc((var(--sidebar-expanded) - var(--sidebar-collapsed)) / 2 - var(--logo-left-padding)));
    }
    /* Hapus .sidebar-logo-text dan hover-nya */
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar-item {
        margin-bottom: 0.5rem;
        position: relative;
    }
    .sidebar-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--color-dark);
        padding: 0.75rem 0.5rem;
        border-radius: 8px;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        white-space: nowrap;
        overflow: hidden;
        font-size: 16px;
    }
    .sidebar-link:hover {
        background-color: var(--color-hover-bg);
        color: var(--color-hover-text);
    }
    .sidebar-icon {
        min-width: 40px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sidebar-icon svg {
        transition: stroke 0.2s ease-in-out;
    }
    .sidebar-link:hover .sidebar-icon svg {
        stroke: var(--color-hover-text);
    }
    .sidebar-link-text {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    .sidebar:hover .sidebar-link-text {
        opacity: 1;
    }

    /* Dropdown khusus untuk Manajemen Pengajuan */
    .sidebar-dropdown {
        position: relative;
    }
    /* --- Transisi halus untuk dropdown Manajemen Pengajuan --- */
    .sidebar-dropdown-menu {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding-left: 2.2rem;
        margin-top: 0.2rem;
        transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.35s cubic-bezier(0.4,0,0.2,1);
        /* display: none; dihapus agar transisi bisa berjalan */
    }
    .sidebar-dropdown.open > .sidebar-dropdown-menu,
    .sidebar-dropdown:focus-within > .sidebar-dropdown-menu {
        max-height: 500px; /* cukup besar untuk menampung isi */
        opacity: 1;
        /* display: flex; tidak perlu, sudah flex di default */
    }
    .sidebar-dropdown-menu .sidebar-link {
        font-size: 15px;
        padding: 0.6rem 0.5rem;
        background: transparent;
        color: var(--color-dark);
    }
    .sidebar-dropdown-menu .sidebar-link:hover {
        background-color: var(--color-hover-bg);
        color: var(--color-hover-text);
    }
    button.sidebar-link {
        background: transparent;
        border: none;
        margin: 0;
        font-family: inherit;
        color: inherit;
        text-align: left;
        width: 100%;
        cursor: pointer;
    }
</style>

<div class="sidebar">
    <div class="sidebar-logo">
        <a href="/">
            <img src="{{ asset('images/logoipsum.png') }}" alt="Logo">
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="/" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Dashboard</strong></span>
            </a>
        </li>
        <!-- Mulai custom dropdown Manajemen Pengajuan -->
        <li class="sidebar-item sidebar-dropdown">
            <button type="button" class="sidebar-link" id="manajemen-pengajuan-toggle">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks"><path d="m3 12 2 2 4-4"/><path d="M11 6h9"/><path d="M11 12h9"/><path d="M11 18h9"/><path d="M3 18h.01"/><path d="M3 6h.01"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Manajemen Pengajuan</strong></span>
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                    </svg>
                </span>
            </button>
            <div class="sidebar-dropdown-menu">
                <a href="/ruangan/index" class="sidebar-link">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <path d="M9 21V9h6v12"/>
                            <path d="M9 12h6"/>
                        </svg>
                    </span>
                    <span class="sidebar-link-text">Daftar Ruangan</span>
                </a>
                <a href="/index" class="sidebar-link">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks"><path d="m3 12 2 2 4-4"/><path d="M11 6h9"/><path d="M11 12h9"/><path d="M11 18h9"/><path d="M3 18h.01"/><path d="M3 6h.01"/></svg>
                    </span>
                    <span class="sidebar-link-text">Daftar Pengajuan</span>
                </a>
                <a href="/history" class="sidebar-link">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3"/><path d="M12 7v5l4 2"/></svg>
                    </span>
                    <span class="sidebar-link-text">History Pengajuan</span>
                </a>
            </div>
        </li>
        <!-- Akhir custom dropdown Manajemen Pengajuan -->
        @if(session('user_role') === 'Admin' || session('user_role') === 'Super Admin')
        <li class="sidebar-item">
            <a href="/user" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Manajemen User</strong></span>
            </a>
        </li>
        @endif
        @if(session('user_role') === 'Super Admin')
        <li class="sidebar-item">
            <a href="{{ route('log.index') }}" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Log Aktivitas</strong></span>
            </a>
        </li>
        @endif
    </ul>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownToggle = document.getElementById('manajemen-pengajuan-toggle');
        
        if (dropdownToggle) {
            const dropdownContainer = dropdownToggle.parentElement;

            dropdownToggle.addEventListener('click', function (e) {
                e.preventDefault();
                dropdownContainer.classList.toggle('open');
            });

            document.addEventListener('click', function (e) {
                if (dropdownContainer.classList.contains('open') && !dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.remove('open');
                }
            });
        }
    });
</script>
<?php ?>
