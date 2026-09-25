<?php
$activePage = 'alat';
$pageTitle = 'Inventaris Alat Lab - Petugas Laboran';
require_once __DIR__ . '/petugas_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">INVENTARIS & STOK PERANGKAT</span>
        <h1 class="admin-title">Inventaris Alat Lab Komputer</h1>
        <p class="admin-subtitle">Monitoring ketersediaan unit, kondisi fisik, dan kategori perangkat di laboratorium komputer.</p>
    </div>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Daftar Alat Laboratorium</h3>
            <p class="inventory-sub">Total <?= count($daftarAlat) ?> jenis perangkat terdaftar.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="petugas">
            <input type="hidden" name="a" value="alat">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama, kode, kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Alat</th>
                <th>Nama Alat Lab</th>
                <th>Kategori</th>
                <th>Sisa Stok</th>
                <th>Kondisi Fisik</th>
                <th>Status Ketersediaan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarAlat)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada data alat lab yang ditemukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarAlat as $alat): 
                    $isAvailable = ($alat['jumlah'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span style="font-family: monospace; font-size: 11px; font-weight: 700; background: #e2e8f0; color: #334155; padding: 2px 6px; border-radius: 4px;">
                                <?= htmlspecialchars($alat['kode'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="col-instrument-name">
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($alat['nama_alat']) ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted); max-width: 320px;"><?= htmlspecialchars($alat['deskripsi'] ?? '-') ?></div>
                        </td>
                        <td><?= htmlspecialchars($alat['nama_kategori'] ?? 'Umum') ?></td>
                        <td><strong><?= (int)$alat['jumlah'] ?></strong> unit</td>
                        <td>
                            <span style="padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #e0f2fe; color: #0369a1;">
                                <?= htmlspecialchars($alat['kondisi'] ?? 'Baik') ?>
                            </span>
                        </td>
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

<?php require_once __DIR__ . '/petugas_footer.php'; ?>
