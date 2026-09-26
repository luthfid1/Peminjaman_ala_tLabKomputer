<?php
$activePage = 'laporan_pengembalian';
$pageTitle = 'Laporan Pengembalian Alat - Ketua Jurusan';
require_once __DIR__ . '/kejur_header.php';
?>

<div class="print-kop">
    <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">LAPORAN PENGEMBALIAN & DENDA ALAT LABORATORIUM KOMPUTER</h2>
    <p style="margin: 4px 0 0 0; font-size: 12px;">SMK NEGERI - JURUSAN REKAYASA PERANGKAT LUNAK</p>
    <span style="font-size: 10px; color: #555;">Dicetak pada: <?= date('d F Y, H:i') ?> WIB | Oleh: <?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Ketua Jurusan') ?></span>
</div>

<div class="admin-header-row no-print">
    <div>
        <span class="admin-date-label">PENGEMBALIAN & PEMULIHAN STOK</span>
        <h1 class="admin-title">Laporan Pengembalian Alat Lab</h1>
        <p class="admin-subtitle">Rekapitulasi berkas transaksi pengembalian perangkat, verifikasi fisik, serta catatan denda.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="window.print()">
        🖨 Cetak Laporan
    </button>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header no-print">
        <div>
            <h3 class="inventory-title">Catatan Pengembalian Selesai</h3>
            <p class="inventory-sub">Total <?= count($daftarPengembalian) ?> transaksi pengembalian berhasil diproses.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="kejur">
            <input type="hidden" name="a" value="laporan_pengembalian">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari siswa, kode, alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kode Pinjam</th>
                <th>Siswa Peminjam</th>
                <th>Kelas / NIS</th>
                <th>Alat Lab Dikembalikan</th>
                <th>Waktu Pinjam</th>
                <th>Waktu Kembali</th>
                <th>Kondisi Fisik</th>
                <th>Denda</th>
                <th>Petugas Penerima</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengembalian)): ?>
                <tr>
                    <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada catatan pengembalian alat yang ditemukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengembalian as $pg): 
                    $hasDenda = ($pg['denda'] > 0);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($pg['kode_peminjaman'] ?? '-') ?></span>
                        </td>
                        <td>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($pg['nama_peminjam'] ?? '-') ?></strong>
                        </td>
                        <td><?= htmlspecialchars($pg['kelas'] ?? '-') ?> <span style="font-size: 11px; color: var(--text-muted);">(<?= htmlspecialchars($pg['nis'] ?? '-') ?>)</span></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($pg['nama_alat'] ?? '-') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= (int)($pg['jumlah'] ?? 1) ?> unit &bull; <?= htmlspecialchars($pg['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td style="white-space: nowrap;"><?= !empty($pg['waktu_pinjam']) ? date('H:i', strtotime($pg['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;">
                            <strong style="color: var(--teal-dark);"><?= !empty($pg['waktu_kembali']) ? date('H:i', strtotime($pg['waktu_kembali'])) . ' WIB' : '-' ?></strong>
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
                        <td style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($pg['nama_petugas'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/kejur_footer.php'; ?>
