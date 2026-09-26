<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAB KOMPUTER - Sistem Peminjaman Alat Laboratorium Komputer RPL</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

    <div class="hero-wrapper">
        
        <!-- NAVBAR -->
        <header class="landing-navbar">
            <a href="index.php" class="brand-group">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <span class="brand-title">LAB KOMPUTER</span>
            </a>

            <nav>
                <ul class="nav-center-links">
                    <li><a href="index.php" class="active">Beranda</a></li>
                    <li><a href="#keunggulan">Daftar Alat</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                </ul>
            </nav>

            <div class="nav-auth-actions">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php 
                        $userRole = strtolower(trim($_SESSION['user']['role'] ?? 'peminjam'));
                        $dashboardUrl = in_array($userRole, ['pengelola', 'admin']) ? 'index.php?c=admin&a=dashboard' : 'index.php?c=auth&a=login';
                    ?>
                    <a href="<?= $dashboardUrl ?>" class="btn-nav-register" style="padding: 8px 16px;">Ruang Kerja &rarr;</a>
                <?php else: ?>
                    <a href="index.php?c=auth&a=login" class="nav-link-login">Login</a>
                    <a href="index.php?c=auth&a=register" class="btn-nav-register">Daftar</a>
                <?php endif; ?>
            </div>
        </header>

        <!-- HERO CONTENT -->
        <section class="hero-container">
            <div class="hero-text-col">
                <div class="hero-tag">SISTEM PEMINJAMAN ALAT LAB KOMPUTER RPL</div>
                <h1 class="hero-main-title">
                    Praktikum lancar,<br>
                    <span class="text-teal">alat selalu siap.</span>
                </h1>
                <p class="hero-lead-text">
                    Platform resmi peminjaman alat laboratorium komputer RPL untuk siswa dan pengajar. Memudahkan proses peminjaman laptop dan berbagai perangkat untuk kebutuhan pembelajaran.
                </p>

                <div class="hero-actions">
                    <a href="<?= isset($_SESSION['user']) ? 'index.php?c=admin&a=dashboard' : 'index.php?c=auth&a=login' ?>" class="btn-primary-action">
                        Mulai Peminjaman &rarr;
                    </a>
                    <a href="#keunggulan" class="btn-secondary-action">
                        Lihat Fasilitas Alat
                    </a>
                </div>

                <div class="hero-check-benefit">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Pengajuan, persetujuan laboran, dan status pengembalian alat tercatat transparan</span>
                </div>
            </div>

            <!-- FLOATING MOCKUP CARD -->
            <div class="hero-mockup-wrapper">
                <div class="mockup-card">
                    <div class="mockup-header-meta">
                        <span class="mockup-breadcrumb">LAB KOMPUTER / INVENTARIS</span>
                        <div class="live-dot"></div>
                    </div>

                    <h3 class="mockup-heading">Alat Lab Tersedia</h3>
                    <p class="mockup-subheading">Pilih perangkat yang dibutuhkan untuk praktikum.</p>

                    <div class="mockup-item-list">
                        <!-- ITEM 1 -->
                        <div class="mockup-item">
                            <div class="mockup-item-left">
                                <div class="item-icon-circle icon-purple">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                        <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                        <line x1="6" y1="18" x2="6.01" y2="18"></line>
                                    </svg>
                                </div>
                                <div>
                                    <div class="item-name">MikroTik RouterBoard RB750r2</div>
                                    <div class="item-cat">Perangkat Jaringan</div>
                                </div>
                            </div>
                            <span class="status-tag ready">Siap</span>
                        </div>

                        <!-- ITEM 2 -->
                        <div class="mockup-item">
                            <div class="mockup-item-left">
                                <div class="item-icon-circle icon-teal">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="item-name">Crimping Tool & LAN Tester</div>
                                    <div class="item-cat">Tools & Kabel UTP</div>
                                </div>
                            </div>
                            <span class="status-tag ready">Siap</span>
                        </div>

                        <!-- ITEM 3 -->
                        <div class="mockup-item">
                            <div class="mockup-item-left">
                                <div class="item-icon-circle icon-amber">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                    </svg>
                                </div>
                                <div>
                                    <div class="item-name">PC Desktop Intel Core i7</div>
                                    <div class="item-cat">Komputer Workstation</div>
                                </div>
                            </div>
                            <span class="status-tag borrowed">Dipinjam</span>
                        </div>
                    </div>

                    <div class="mockup-footer">
                        <div class="mockup-stat">
                            <span class="mockup-stat-dot"></span>
                            <span>Stok terintegrasi real-time</span>
                        </div>
                        <span class="mockup-btn-badge">Inventaris Terdata</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ==========================================
         SECTION: KEUNGGULAN SISTEM LAB
         ========================================== -->
    <section class="section-features" id="keunggulan">
        <div class="features-header">
            <span class="section-tag-center">FASILITAS & FITUR</span>
            <h2 class="section-title-serif">
                Semua yang dibutuhkan<br>
                <span class="title-accent">untuk kelancaran praktikum lab.</span>
            </h2>
        </div>

        <div class="features-grid">
            <!-- CARD 1 -->
            <div class="feature-card">
                <div class="feature-icon-box icon-box-mint">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <h3>Alat Lab Lengkap</h3>
                <p>PC Workstation, switch Cisco, router MikroTik, crimping tool, dan kit IoT dalam satu katalog inventaris terpadu.</p>
                <a href="index.php?c=auth&a=login" class="feature-link">
                    Ajukan peminjaman &rarr;
                </a>
            </div>

            <!-- CARD 2 -->
            <div class="feature-card">
                <div class="feature-icon-box icon-box-peach">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3>Cepat & Mudah</h3>
                <p>Ajukan permohonan peminjaman alat untuk praktikum atau tugas akhir tanpa prosedur kertas yang berbelit.</p>
                <a href="index.php?c=auth&a=login" class="feature-link">
                    Ajukan peminjaman &rarr;
                </a>
            </div>

            <!-- CARD 3 -->
            <div class="feature-card">
                <div class="feature-icon-box icon-box-purple">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3>Terdata & Terstruktur</h3>
                <p>Persetujuan petugas laboran, pemantauan pengembalian, kondisi fisik alat, dan rekam log aktivitas tercatat aman.</p>
                <a href="index.php?c=auth&a=login" class="feature-link">
                    Ajukan peminjaman &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- ==========================================
         SECTION: TENTANG LAB KOMPUTER
         ========================================== -->
    <section class="section-about" id="tentang">
        <div class="about-container">
            <div>
                <span class="about-tag">TENTANG SISTEM LAB</span>
                <h2 class="about-title">
                    Satu sistem untuk inventaris yang <span class="highlight">lebih terawat & tertata.</span>
                </h2>
            </div>
            <div>
                <p class="about-description">
                    Siswa dapat dengan mudah meminjam perangkat untuk kebutuhan praktikum kejuruan.
                </p>
            </div>
        </div>
    </section>

    <!-- ==========================================
         FOOTER
         ========================================== -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-top">
                <div>
                    <a href="index.php" class="brand-group">
                        <div class="brand-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                        </div>
                        <span class="brand-title">LAB KOMPUTER</span>
                    </a>
                    <p class="footer-desc">Sistem Informasi Inventaris & Peminjaman Alat Laboratorium Komputer RPL.</p>
                </div>

                <ul class="footer-nav">
                    <li><a href="#keunggulan">Fitur</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="index.php?c=auth&a=login">Masuk</a></li>
                    <li><a href="index.php?c=auth&a=login">Pengelola</a></li>
                </ul>
            </div>

            <hr class="footer-divider">

            <div class="footer-bottom">
                <span>&copy; <?= date('Y') ?> Lab Komputer. Rekayasa Perangkat Lunak.</span>
                <span>Inventarisasi dan Peminjaman Alat Terpadu</span>
            </div>
        </div>
    </footer>

</body>
</html>
