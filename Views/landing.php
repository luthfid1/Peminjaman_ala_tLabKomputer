<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NARA BAND - Sewa Alat Band & Studio Sekolah</title>
    <link rel="stylesheet" href="Assets/css/style.css">
</head>
<body>

    
    <div class="hero-wrapper">
        
        <!-- NAVBAR -->
        <header class="landing-navbar">
            <a href="index.php" class="brand-group">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                </div>
                <span class="brand-title">NARA BAND</span>
            </a>

            <nav>
                <ul class="nav-center-links">
                    <li><a href="index.php" class="active">Beranda</a></li>
                    <li><a href="#keunggulan">Daftar Alat</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                </ul>
            </nav>

            <div class="nav-auth-actions">
                <a href="index.php?c=auth&a=login" class="nav-link-login">Masuk</a>
                <a href="index.php?c=auth&a=register" class="btn-nav-register">Daftar</a>
            </div>
        </header>

        <!-- HERO CONTENT -->
        <section class="hero-container">
            <div class="hero-text-col">
                <div class="hero-tag">SEWA ALAT BAND & STUDIO SEKOLAH</div>
                <h1 class="hero-main-title">
                    Tanpa ribet,<br>
                    <span class="text-teal">langsung siap.</span>
                </h1>
                <p class="hero-lead-text">
                    Platform resmi peminjaman inventaris alat musik untuk siswa, komunitas, dan band sekolah. Mulai dari gitar, drum, keyboard, hingga perlengkapan audio.
                </p>

                <div class="hero-actions">
                    <a href="<?= isset($_SESSION['user']) ? 'index.php?c=peminjaman&a=dashboard' : 'index.php?c=auth&a=login' ?>" class="btn-primary-action">
                        Mulai peminjaman &rarr;
                    </a>
                    <a href="#keunggulan" class="btn-secondary-action">
                        Lihat daftar alat
                    </a>
                </div>

                <div class="hero-check-benefit">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Pengajuan dan status peminjaman tercatat dengan jelas</span>
                </div>
            </div>

            <!-- FLOATING MOCKUP CARD -->
            <div class="hero-mockup-wrapper">
                <div class="mockup-card">
                    <div class="mockup-header-meta">
                        <span class="mockup-breadcrumb">NARA BAND/ DAFTAR ALAT</span>
                        <div class="live-dot"></div>
                    </div>

                    <h3 class="mockup-heading">Alat tersedia</h3>
                    <p class="mockup-subheading">Pilih alat yang kamu butuhkan.</p>

                    <div class="mockup-item-list">
                        <!-- ITEM 1 -->
                        <div class="mockup-item">
                            <div class="mockup-item-left">
                                <div class="item-icon-circle icon-purple">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M9 18V5l12-2v13"></path>
                                        <circle cx="6" cy="18" r="3"></circle>
                                        <circle cx="18" cy="16" r="3"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <div class="item-name">Gitar Yamaha C40</div>
                                    <div class="item-cat">Gitar & Bass</div>
                                </div>
                            </div>
                            <span class="status-tag ready">Siap</span>
                        </div>

                        <!-- ITEM 2 -->
                        <div class="mockup-item">
                            <div class="mockup-item-left">
                                <div class="item-icon-circle icon-teal">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                                        <line x1="12" y1="19" x2="12" y2="23"></line>
                                        <line x1="8" y1="23" x2="16" y2="23"></line>
                                    </svg>
                                </div>
                                <div>
                                    <div class="item-name">Mic Shure SM58</div>
                                    <div class="item-cat">Perlengkapan audio</div>
                                </div>
                            </div>
                            <span class="status-tag ready">Siap</span>
                        </div>

                        <!-- ITEM 3 -->
                        <div class="mockup-item">
                            <div class="mockup-item-left">
                                <div class="item-icon-circle icon-amber">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <ellipse cx="12" cy="8" rx="8" ry="4"></ellipse>
                                        <path d="M4 8v8c0 2.2 3.6 4 8 4s8-1.8 8-4V8"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="item-name">Drum Mapex</div>
                                    <div class="item-cat">Drum</div>
                                </div>
                            </div>
                            <span class="status-tag borrowed">Dipinjam</span>
                        </div>
                    </div>

                    <div class="mockup-footer">
                        <div class="mockup-stat">
                            <span class="mockup-stat-dot"></span>
                            <span>18 alat tersedia hari ini</span>
                        </div>
                        <span class="mockup-btn-badge">Data tercatat rapi</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ==========================================
         SECTION: KEUNGGULAN KAMI
         ========================================== -->
    <section class="section-features" id="keunggulan">
        <div class="features-header">
            <span class="section-tag-center">KEUNGGULAN KAMI</span>
            <h2 class="section-title-serif">
                Semua yang dibutuhkan<br>
                <span class="title-accent">untuk mulai bermusik.</span>
            </h2>
        </div>

        <div class="features-grid">
            <!-- CARD 1 -->
            <div class="feature-card">
                <div class="feature-icon-box icon-box-mint">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>
                </div>
                <h3>Alat lengkap</h3>
                <p>Gitar, bass, drum, keyboard, dan perlengkapan audio dalam satu daftar.</p>
                <a href="<?= isset($_SESSION['user']) ? 'index.php?c=peminjaman&a=dashboard' : 'index.php?c=auth&a=login' ?>" class="feature-link">
                    Mulai sekarang &rarr;
                </a>
            </div>

            <!-- CARD 2 -->
            <div class="feature-card">
                <div class="feature-icon-box icon-box-peach">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
                <h3>Cepat & mudah</h3>
                <p>Ajukan peminjaman dan pantau statusnya tanpa proses yang berbelit.</p>
                <a href="<?= isset($_SESSION['user']) ? 'index.php?c=peminjaman&a=dashboard' : 'index.php?c=auth&a=login' ?>" class="feature-link">
                    Mulai sekarang &rarr;
                </a>
            </div>

            <!-- CARD 3 -->
            <div class="feature-card">
                <div class="feature-icon-box icon-box-purple">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3>Terstruktur</h3>
                <p>Riwayat, persetujuan, pengembalian, dan denda tercatat aman.</p>
                <a href="<?= isset($_SESSION['user']) ? 'index.php?c=peminjaman&a=dashboard' : 'index.php?c=auth&a=login' ?>" class="feature-link">
                    Mulai sekarang &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- ==========================================
         SECTION: TENTANG SEWANADA
         ========================================== -->
    <section class="section-about" id="tentang">
        <div class="about-container">
            <div>
                <span class="about-tag">TENTANG NARA BAND</span>
                <h2 class="about-title">
                    Satu sistem untuk alat yang <span class="highlight">lebih terawat.</span>
                </h2>
            </div>
            <div>
                <p class="about-description">
                    Admin mengelola inventaris dan pengguna. Petugas menyetujui peminjaman serta memantau pengembalian. Peminjam cukup melihat alat, mengajukan, lalu mengembalikannya sesuai jadwal.
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
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                            </svg>
                        </div>
                        <span class="brand-title">Nara Band</span>
                    </a>
                    <p class="footer-desc">Sistem peminjaman alat musik sekolah.</p>
                </div>

                <ul class="footer-nav">
                    <li><a href="#keunggulan">Fitur</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="index.php?c=auth&a=login">Masuk</a></li>
                    <li><a href="index.php?c=auth&a=register">Daftar</a></li>
                </ul>
            </div>

            <hr class="footer-divider">

            <div class="footer-bottom">
                <span>&copy; Nara Band</span>
                <span>Dibuat untuk musik yang lebih teratur</span>
            </div>
        </div>
    </footer>

</body>
</html>
