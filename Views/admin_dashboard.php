<?php
$activePage = 'dashboard';
$pageTitle = 'Pusat Kendali - Pengelola Lab Komputer';
require_once __DIR__ . '/admin_header.php';
?>

<!-- HEADER ROW -->
<div class="admin-header-row">
    <div>
        <span class="admin-date-label"><?= htmlspecialchars($tglStr) ?></span>
        <h1 class="admin-title">Pusat Kendali Lab.</h1>
        <p class="admin-subtitle">Pantau kesehatan inventaris alat lab komputer dan aktivitas peminjaman hari ini.</p>
    </div>
    <a href="index.php?c=admin&a=alat" class="btn-add-instrument">
        + Tambah Alat Lab
    </a>
</div>

<!-- 4 METRIC CARDS -->
<div class="admin-metrics-grid">
    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Total Jenis Alat</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $stats['total_alat'] ?></div>
        <div class="metric-sub"><?= $stats['total_kategori'] ?> kategori lab</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Sedang Dipinjam</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $stats['sedang_dipinjam'] ?></div>
        <div class="metric-sub">Peminjaman aktif</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Pengguna Terdaftar</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= $totalPengguna ?></div>
        <div class="metric-sub">Akun dalam sistem</div>
    </div>

    <div class="metric-card">
        <div class="metric-top-row">
            <span class="metric-label">Menunggu Persetujuan</span>
            <div class="metric-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?= sprintf('%02d', $stats['menunggu_persetujuan']) ?></div>
        <div class="metric-sub">Permohonan baru</div>
    </div>
</div>

<!-- INVENTARIS ALAT TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Inventaris Alat Lab Komputer</h3>
            <p class="inventory-sub">Daftar alat dan ketersediaan stok di laboratorium.</p>
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
            <input type="text" name="search" placeholder="Cari nama atau kode alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($inventaris)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada data inventaris alat lab.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($inventaris as $item): 
                    $isAvailable = ($item['jumlah'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span style="font-family: monospace; font-size: 12px; font-weight: 700; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;"><?= htmlspecialchars($item['kode'] ?? '-') ?></span></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($item['nama_alat']) ?></td>
                        <td><?= htmlspecialchars($item['nama_kategori'] ?? 'Umum') ?></td>
                        <td><strong><?= (int)$item['jumlah'] ?></strong> unit</td>
                        <td><span style="padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #e0f2fe; color: #0369a1;"><?= htmlspecialchars($item['kondisi'] ?? 'Baik') ?></span></td>
                        <td>
                            <?php if ($isAvailable): ?>
                                <span class="status-badge badge-ready">Tersedia</span>
                            <?php else: ?>
                                <span class="status-badge badge-busy">Habis Dipinjam</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
