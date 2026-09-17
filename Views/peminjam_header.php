<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Ruang Peminjam - NARA BAND') ?></title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

    <!-- TOP NAVBAR -->
    <header class="navbar">
        <div class="admin-navbar-brand-group">
            <a href="index.php?c=peminjam&a=dashboard" class="nav-brand">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                </div>
                <span class="brand-name">NARA BAND</span>
            </a>
            <span class="admin-nav-separator">|</span>
            <span class="admin-nav-subtext">Ruang Peminjam</span>
        </div>

        <div class="nav-user">
            <div class="user-profile">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? 'Peminjam') ?></span>
                    <span class="user-role">Peminjam</span>
                </div>
                <div class="user-avatar" style="background: var(--teal-primary);">
                    <?php
                        $nama = $_SESSION['user']['nama_lengkap'] ?? 'Peminjam';
                        $parts = explode(' ', trim($nama));
                        $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        echo htmlspecialchars($initials);
                    ?>
                </div>
            </div>
        </div>
    </header>

    <!-- APP CONTAINER -->
    <div class="app-container">
        
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-mode-card" style="background: linear-gradient(135deg, rgba(20, 184, 166, 0.12), rgba(15, 118, 110, 0.05)); border: 1px solid rgba(20, 184, 166, 0.2);">
                <span class="mode-tag" style="background: var(--teal-primary);">MODE AKTIF</span>
                <h2 class="mode-title" style="color: var(--teal-dark);">Peminjam</h2>
                <p class="mode-desc">Jelajahi instrumen & pinjam alat musik untuk kebutuhan Anda.</p>
            </div>

            <ul class="admin-sidebar-menu">
                <li>
                    <a href="index.php?c=peminjam&a=dashboard" class="<?= ($activePage === 'dashboard') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="index.php?c=peminjam&a=katalog" class="<?= ($activePage === 'katalog') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        Katalog Alat
                    </a>
                </li>
                <li>
                    <a href="index.php?c=peminjam&a=peminjaman" class="<?= ($activePage === 'peminjaman') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Peminjaman Saya
                    </a>
                </li>
                <li>
                    <a href="index.php?c=peminjam&a=pengembalian" class="<?= ($activePage === 'pengembalian') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg>
                        Riwayat Pengembalian
                    </a>
                </li>
                <li>
                    <a href="index.php?c=peminjam&a=profil" class="<?= ($activePage === 'profil') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profil Saya
                    </a>
                </li>
                <li>
                    <a href="index.php?c=auth&a=logout">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Keluar
                    </a>
                </li>
            </ul>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="main-content">
