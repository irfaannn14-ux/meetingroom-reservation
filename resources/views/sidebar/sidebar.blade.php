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
        background-color: var(--color-light);
        color: var(--color-dark);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow: hidden;
        padding: 1rem 0.5rem;
        transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
        z-index: 1000;
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
    .sidebar-dropdown:hover > .sidebar-dropdown-menu,
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
<<<<<<< HEAD
        <li class="sidebar-item">
            <a href="#masterDataSubmenu" data-bs-toggle="collapse" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-folder">
                        <path d="M3 7h5l2 3h11v9a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z"/>
                    </svg>
                </span>
                <span class="sidebar-link-text"><strong>Master Data</strong></span>
                <span class="sidebar-icon">
                    <span class="sidebar-icon">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                        </svg>
                    </span>
                </span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="masterDataSubmenu">
                <li class="sidebar-item">
                    <a href="{{ route('pengajuan.index') }}" class="sidebar-link">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-pen">
                                <path d="M12.5 22H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v9.5"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M13.378 15.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/>
                            </svg>
                        </span>
                        <span class="sidebar-link-text">Pengajuan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('ruangan.index') }}" class="sidebar-link">
                        <span class="sidebar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house">
                                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            </svg>
                        </span>
                        <span class="sidebar-link-text">Ruangan</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="/daftar-pengajuan" class="sidebar-link">
=======
        <!-- Mulai custom dropdown Manajemen Pengajuan -->
        <li class="sidebar-item sidebar-dropdown">
            <a href="#" class="sidebar-link">
>>>>>>> 7d125fcd27b4c6576153dbf5a6d7f6599ec5a88f
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks"><path d="m3 12 2 2 4-4"/><path d="M11 6h9"/><path d="M11 12h9"/><path d="M11 18h9"/><path d="M3 18h.01"/><path d="M3 6h.01"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Manajemen Pengajuan</strong></span>
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                    </svg>
                </span>
            </a>
            <div class="sidebar-dropdown-menu">
                <a href="/listdata" class="sidebar-link">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks"><path d="m3 12 2 2 4-4"/><path d="M11 6h9"/><path d="M11 12h9"/><path d="M11 18h9"/><path d="M3 18h.01"/><path d="M3 6h.01"/></svg>
                    </span>
                    <span class="sidebar-link-text">Daftar Pengajuan</span>
                </a>
                <a href="/history-pengajuan" class="sidebar-link">
                    <span class="sidebar-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3"/><path d="M12 7v5l4 2"/></svg>
                    </span>
                    <span class="sidebar-link-text">History Pengajuan</span>
                </a>
            </div>
        </li>
        <!-- Akhir custom dropdown Manajemen Pengajuan -->
        <li class="sidebar-item">
            <a href="{{ route('user.index') }}" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Manajemen User</strong></span>
            </a>
        </li>
    </ul>
</div>
<?php ?>
