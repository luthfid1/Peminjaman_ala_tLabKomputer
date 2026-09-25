<?php
$activePage = 'pengembalian';
$pageTitle = 'Pengembalian Saya - Lab Komputer';
require_once __DIR__ . '/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">RIWAYAT & ARSIP PENGEMBALIAN</span>
        <h1 class="admin-title">Pengembalian Saya</h1>
        <p class="admin-subtitle">Daftar perangkat laboratorium komputer yang telah dikembalikan, status kondisi fisik alat, serta informasi denda (jika ada).</p>
    </div>
</div>

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
            <div style="font-size: 20px; font-weight: 700; color: #0f172a;"><?= count($daftarPengembalian) ?> Transaksi Selesai</div>
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
                Rp<?= number_format($totalDenda, 0, ',', '.') ?>
            </div>
        </div>
    </div>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Catatan Pengembalian Alat Lab</h3>
            <p class="inventory-sub">Total <?= count($daftarPengembalian) ?> riwayat pengembalian alat.</p>
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
            <input type="text" name="search" placeholder="Cari kode atau alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Alat Lab</th>
                <th>Kategori & Jml</th>
                <th>Waktu Pinjam</th>
                <th>Waktu Dikembalikan</th>
                <th>Kondisi Kembali</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengembalian)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada riwayat pengembalian alat lab.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengembalian as $pg): 
                    $hasDenda = ($pg['denda'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($pg['kode_peminjaman'] ?? '-') ?></div>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($pg['nama_alat'] ?? 'Alat Lab') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($pg['kode_alat'] ?? '') ?></div>
                        </td>
                        <td>
                            <?= htmlspecialchars($pg['nama_kategori'] ?? 'Umum') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= (int)($pg['jumlah'] ?? 1) ?> unit</div>
                        </td>
                        <td style="white-space: nowrap;"><?= !empty($pg['waktu_pinjam']) ? date('H:i', strtotime($pg['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;">
                            <strong style="color: var(--teal-dark);"><?= !empty($pg['waktu_kembali']) ? date('H:i', strtotime($pg['waktu_kembali'])) . ' WIB' : '-' ?></strong>
                            <?php if (!empty($pg['waktu_rencana_kembali'])): ?>
                                <div style="font-size: 11px; color: var(--text-muted);">Rencana: <?= date('H:i', strtotime($pg['waktu_rencana_kembali'])) ?> WIB</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #e0f2fe; color: #0369a1;">
                                <?= htmlspecialchars($pg['kondisi_kembali'] ?? 'Baik') ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($hasDenda): ?>
                                <strong style="color: #dc2626;">Rp<?= number_format($pg['denda'], 0, ',', '.') ?></strong>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 12px;">Rp0 (Nihil)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/peminjam_footer.php'; ?>
