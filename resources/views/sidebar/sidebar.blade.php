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
    }
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
</style>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logoipsum.png') }}" alt="Logo">
    </div>
    <ul class="sidebar-menu">
        <li class="sidebar-item">
            <a href="/dashboard" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Dashboard</strong></span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/daftar-pengajuan" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks"><path d="m3 12 2 2 4-4"/><path d="M11 6h9"/><path d="M11 12h9"/><path d="M11 18h9"/><path d="M3 18h.01"/><path d="M3 6h.01"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Daftar Pengajuan</strong></span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/manajemen-user" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                <span class="sidebar-link-text"><strong>Manajemen User</strong></span>
            </a>
        </li>
    </ul>
</div>
<?php ?>