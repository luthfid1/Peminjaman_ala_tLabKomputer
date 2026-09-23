<?php
$activePage = 'pengembalian';
$pageTitle = 'Pengembalian Saya - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">ARSIP & SIRKULASI</span>
        <h1 class="admin-title">Pengembalian Saya</h1>
        <p class="admin-subtitle">Daftar instrumen musik yang telah Anda kembalikan beserta rincian denda keterlambatan jika ada.</p>
    </div>
</div>

<!-- ALERTS -->
<?php if (!empty($message)): ?>
    <div class="auth-alert auth-alert-success" style="margin-bottom: 20px;">
        <span><?= htmlspecialchars($message) ?></span>
    </div>
<?php endif; ?>

<!-- INFO BOX -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(20, 184, 166, 0.1); color: var(--teal-dark); display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div>
            <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Alat Dikembalikan</span>
            <div style="font-size: 20px; font-weight: 700; color: #0f172a;"><?= count($daftarPengembalian) ?> Transaksi</div>
        </div>
    </div>

    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: <?= $totalDenda > 0 ? '#fee2e2' : '#f1f5f9' ?>; color: <?= $totalDenda > 0 ? '#b91c1c' : '#64748b' ?>; display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div>
            <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Akumulasi Denda</span>
            <div style="font-size: 20px; font-weight: 700; color: <?= $totalDenda > 0 ? '#b91c1c' : '#0f172a' ?>;">
                Rp <?= number_format($totalDenda, 0, ',', '.') ?>
            </div>
        </div>
    </div>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Catatan Pengembalian Alat</h3>
            <p class="inventory-sub">Total <?= count($daftarPengembalian) ?> pengembalian tercatat.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="peminjam">
            <input type="hidden" name="a" value="pengembalian">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama alat atau catatan..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Alat Musik</th>
                <th>Jumlah</th>
                <th>Tgl Mulai Pinjam</th>
                <th>Tgl Dikembalikan</th>
                <th>Denda</th>
                <th>Keterangan / Kondisi Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengembalian)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada riwayat pengembalian alat musik.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengembalian as $pg): 
                    $hasDenda = ($pg['denda'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($pg['nama_alat'] ?? 'Instrumen') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                <?= htmlspecialchars($pg['nama_kategori'] ?? 'Kategori') ?>
                            </div>
                        </td>
                        <td><strong><?= (int)($pg['jumlah'] ?? 1) ?></strong> unit</td>
                        <td><?= !empty($pg['tanggal_pinjam']) ? date('d M Y', strtotime($pg['tanggal_pinjam'])) : '-' ?></td>
                        <td>
                            <strong style="color: var(--teal-dark);"><?= !empty($pg['tanggal_pengembalian']) ? date('d M Y', strtotime($pg['tanggal_pengembalian'])) : '-' ?></strong>
                            <?php if (!empty($pg['tanggal_kembali'])): ?>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                    Tenggat: <?= date('d M Y', strtotime($pg['tanggal_kembali'])) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($hasDenda): ?>
                                <span style="display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700; background: #fee2e2; color: #b91c1c;">
                                    Rp <?= number_format($pg['denda'], 0, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #15803d; font-size: 12px; font-weight: 600; background: #dcfce7; padding: 4px 8px; border-radius: 6px;">
                                    Rp 0 (Tepat Waktu)
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= !empty($pg['keterangan']) ? htmlspecialchars($pg['keterangan']) : '<span style="color: var(--text-muted);">-</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once 'Views/peminjam_footer.php'; ?>
