<?php
$activePage = 'laporan';
$pageTitle = 'Laporan Sirkulasi Lab - Petugas Laboran';
require_once __DIR__ . '/petugas_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">ARSIP & LAPORAN RESMI</span>
        <h1 class="admin-title">Laporan Sirkulasi Alat Lab</h1>
        <p class="admin-subtitle">Rekapitulasi berkas transaksi peminjaman dan pengembalian alat laboratorium komputer untuk dokumentasi sekolah.</p>
    </div>
    <button type="button" class="btn-add-instrument no-print" onclick="window.print()">
        🖨 Cetak Laporan
    </button>
</div>

<!-- PRINT HEADER (HANYA MUNCUL DI CETAK) -->
<div style="display: none;" class="print-header">
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 18px; text-transform: uppercase;">LAPORAN SIRKULASI PEMINJAMAN ALAT LABORATORIUM KOMPUTER</h2>
        <p style="margin: 4px 0 0 0; font-size: 13px;">SMK NEGERI - TAHUN AJARAN 2025/2026</p>
        <span style="font-size: 11px; color: #555;">Dicetak pada: <?= date('d F Y, H:i') ?> WIB oleh <?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Petugas') ?></span>
    </div>
</div>

<!-- TABEL REKAP PEMINJAMAN -->
<section class="inventory-section-card" style="margin-bottom: 30px;">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Rekap Seluruh Peminjaman Alat</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> catatan peminjaman.</p>
        </div>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Siswa Peminjam</th>
                <th>Kelas / NIS</th>
                <th>Alat Lab</th>
                <th>Jml</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada catatan peminjaman.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjaman as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span style="font-family: monospace; font-size: 11px; font-weight: 700;"><?= htmlspecialchars($p['kode_peminjaman'] ?? '-') ?></span></td>
                        <td><strong><?= htmlspecialchars($p['nama_peminjam'] ?? '-') ?></strong></td>
                        <td><?= htmlspecialchars($p['kelas'] ?? '-') ?> (<?= htmlspecialchars($p['nis'] ?? '-') ?>)</td>
                        <td><?= htmlspecialchars($p['nama_alat'] ?? '-') ?></td>
                        <td><?= (int)($p['jumlah'] ?? 1) ?> unit</td>
                        <td><?= !empty($p['waktu_pinjam']) ? date('H:i', strtotime($p['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td><?= !empty($p['waktu_rencana_kembali']) ? date('H:i', strtotime($p['waktu_rencana_kembali'])) . ' WIB' : '-' ?></td>
                        <td><?= ucfirst(htmlspecialchars($p['status'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- TABEL REKAP PENGEMBALIAN -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Rekap Pengembalian Alat Selesai</h3>
            <p class="inventory-sub">Total <?= count($daftarPengembalian) ?> transaksi pengembalian selesai.</p>
        </div>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pinjam</th>
                <th>Siswa</th>
                <th>Alat Lab</th>
                <th>Waktu Kembali</th>
                <th>Kondisi Fisik</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengembalian)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada catatan pengembalian.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPengembalian as $pg): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span style="font-family: monospace; font-size: 11px; font-weight: 700;"><?= htmlspecialchars($pg['kode_peminjaman'] ?? '-') ?></span></td>
                        <td><strong><?= htmlspecialchars($pg['nama_peminjam'] ?? '-') ?></strong></td>
                        <td><?= htmlspecialchars($pg['nama_alat'] ?? '-') ?></td>
                        <td><?= !empty($pg['waktu_kembali']) ? date('H:i', strtotime($pg['waktu_kembali'])) . ' WIB' : '-' ?></td>
                        <td><?= htmlspecialchars($pg['kondisi_kembali'] ?? 'Baik') ?></td>
                        <td>Rp<?= number_format($pg['denda'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/petugas_footer.php'; ?>
