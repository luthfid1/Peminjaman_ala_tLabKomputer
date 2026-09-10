<?php
$activePage = 'dashboard';
$pageTitle = 'Pusat Kendali - Ruang Admin SEWANADA';
require_once 'Views/admin_header.php';
?>

<!-- HEADER ROW -->
<div class="admin-header-row">
    <div>
        <span class="admin-date-label"><?= htmlspecialchars($tglStr) ?></span>
        <h1 class="admin-title">Pusat kendali.</h1>
        <p class="admin-subtitle">Pantau kesehatan inventaris dan aktivitas peminjaman hari ini.</p>
    </div>
    <a href="index.php?c=admin&a=alat" class="btn-add-instrument">
        + Tambah alat
    </a>
</div>

<!-- 4 METRIC CARDS -->
<div class="admin-metrics-grid">
    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Total alat</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $stats['total_alat'] ?></div>
        <div class="metric-sub"><?= $stats['total_kategori'] ?> kategori</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Sedang dipinjam</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $stats['sedang_dipinjam'] ?></div>
        <div class="metric-sub">Aktif dipinjam</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Pengguna aktif</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalPengguna ?></div>
        <div class="metric-sub">Terdaftar di sistem</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Menunggu persetujuan</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= sprintf('%02d', $stats['menunggu_persetujuan']) ?></div>
        <div class="metric-sub">Perlu ditinjau</div>
    </div>
</div>

<!-- INVENTARIS ALAT TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Inventaris alat</h3>
            <p class="inventory-sub">Daftar alat terakhir diperbarui hari ini.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="dashboard">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Terakhir Dicek</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventaris as $item): 
                $isReady = ($item['jumlah_stok'] > 0);
                $tglCek = $item['tgl_cek'] ?? date('d M Y');
            ?>
                <tr>
                    <td class="col-instrument-name"><?= htmlspecialchars($item['nama_alat']) ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori'] ?? 'Instrumen') ?></td>
                    <td><?= htmlspecialchars($tglCek) ?></td>
                    <td>
                        <?php if ($isReady): ?>
                            <span class="status-badge badge-ready">Siap</span>
                        <?php else: ?>
                            <span class="status-badge badge-busy">Dipinjam</span>
                        <?php endif; ?>
                    </td>
                    <td class="col-detail-link">
                        <a href="index.php?c=admin&a=alat" class="btn-table-detail">Kelola</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once 'Views/admin_footer.php'; ?>
