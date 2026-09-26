<?php
$activePage = 'laporan_alat';
$pageTitle = 'Laporan Alat Lab - Ketua Jurusan';
require_once __DIR__ . '/kejur_header.php';
?>

<div class="print-kop">
    <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">LAPORAN INVENTARIS ALAT LABORATORIUM KOMPUTER</h2>
    <p style="margin: 4px 0 0 0; font-size: 12px;">SMK NEGERI - JURUSAN REKAYASA PERANGKAT LUNAK</p>
    <span style="font-size: 10px; color: #555;">Dicetak pada: <?= date('d F Y, H:i') ?> WIB | Oleh: <?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Ketua Jurusan') ?></span>
</div>

<div class="admin-header-row no-print">
    <div>
        <span class="admin-date-label">INVENTARIS & ASSET LAB</span>
        <h1 class="admin-title">Laporan Alat Laboratorium</h1>
        <p class="admin-subtitle">Rekapitulasi stok fisik, kondisi kelayakan, dan persebaran kategori perangkat lab komputer.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="window.print()">
        🖨 Cetak Laporan
    </button>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header no-print">
        <div>
            <h3 class="inventory-title">Inventaris Perangkat Laboratorium</h3>
            <p class="inventory-sub">Total <?= count($daftarAlat) ?> item alat terdata.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form method="GET" action="index.php" style="margin: 0;">
                <input type="hidden" name="c" value="kejur">
                <input type="hidden" name="a" value="laporan_alat">
                <select name="kategori" class="form-control" style="font-size: 13px; height: 38px; padding: 6px 12px;" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($daftarKategori as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($_GET['kategori']) && (int)$_GET['kategori'] === (int)$k['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($_GET['search'])): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                <?php endif; ?>
            </form>

            <form method="GET" action="index.php" class="search-box" style="margin: 0;">
                <input type="hidden" name="c" value="kejur">
                <input type="hidden" name="a" value="laporan_alat">
                <?php if (!empty($_GET['kategori'])): ?>
                    <input type="hidden" name="kategori" value="<?= (int)$_GET['kategori'] ?>">
                <?php endif; ?>
                <span class="search-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Cari nama, kode..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        </div>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Kode</th>
                <th>Nama Alat Lab</th>
                <th>Kategori</th>
                <th>Sisa Stok</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Deskripsi Singkat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarAlat)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
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
                                <span class="status-badge badge-busy">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted); max-width: 250px;">
                            <?= htmlspecialchars($alat['deskripsi'] ?? '-') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/kejur_footer.php'; ?>
