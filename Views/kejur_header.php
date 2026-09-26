<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Ketua Jurusan - Lab Komputer') ?></title>
    <link rel="stylesheet" href="Assets/css/style.css">
    <style>
        @media print {
            .navbar, .sidebar, .btn-add-instrument, .search-box, .no-print, button, form {
                display: none !important;
            }
            .app-container {
                display: block !important;
                padding: 0 !important;
            }
            .main-content {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .inventory-section-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .print-kop {
                display: block !important;
            }
        }
        .print-kop {
            display: none;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- TOP NAVBAR -->
    <header class="navbar no-print">
        <div class="admin-navbar-brand-group">
            <a href="index.php?c=kejur&a=dashboard" class="nav-brand">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <span class="brand-name">LAB KOMPUTER</span>
            </a>
            <span class="admin-nav-separator">|</span>
            <span class="admin-nav-subtext">Ketua Jurusan</span>
        </div>

        <div class="nav-user">
            <div class="user-profile">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Ketua Jurusan') ?></span>
                    <span class="user-role">Ketua Jurusan (Kejur)</span>
                </div>
                <div class="user-avatar" style="background: var(--teal-primary);">
                    <?php
                        $nama = $_SESSION['user']['nama'] ?? 'Ketua Jurusan';
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
        <aside class="sidebar no-print">
            <div class="sidebar-mode-card" style="background: linear-gradient(135deg, rgba(20, 184, 166, 0.12), rgba(15, 118, 110, 0.05)); border: 1px solid rgba(20, 184, 166, 0.2);">
                <span class="mode-tag" style="background: var(--teal-primary);">MONITORING</span>
                <h2 class="mode-title" style="color: var(--teal-dark);">Ketua Jurusan</h2>
                <p class="mode-desc">Akses monitoring operasional laboratorium & pelaporan resmi jurusan.</p>
            </div>

            <ul class="admin-sidebar-menu">
                <li>
                    <a href="index.php?c=kejur&a=dashboard" class="<?= ($activePage === 'dashboard') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard Monitoring
                    </a>
                </li>
                <li>
                    <a href="index.php?c=kejur&a=laporan_peminjam" class="<?= ($activePage === 'laporan_peminjam') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Laporan Peminjam
                    </a>
                </li>
                <li>
                    <a href="index.php?c=kejur&a=laporan_alat" class="<?= ($activePage === 'laporan_alat') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        Laporan Alat Lab
                    </a>
                </li>
                <li>
                    <a href="index.php?c=kejur&a=laporan_peminjaman" class="<?= ($activePage === 'laporan_peminjaman') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Laporan Peminjaman
                    </a>
                </li>
                <li>
                    <a href="index.php?c=kejur&a=laporan_pengembalian" class="<?= ($activePage === 'laporan_pengembalian') ? 'active' : '' ?>">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg>
                        Laporan Pengembalian
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
