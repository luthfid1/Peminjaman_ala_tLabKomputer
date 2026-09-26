<?php
$activePage = 'laporan_peminjaman';
$pageTitle = 'Laporan Peminjaman Alat - Ketua Jurusan';
require_once __DIR__ . '/kejur_header.php';
?>

<div class="print-kop">
    <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">LAPORAN TRANSAKSI PEMINJAMAN ALAT LABORATORIUM KOMPUTER</h2>
    <p style="margin: 4px 0 0 0; font-size: 12px;">SMK NEGERI - JURUSAN REKAYASA PERANGKAT LUNAK</p>
    <span style="font-size: 10px; color: #555;">Dicetak pada: <?= date('d F Y, H:i') ?> WIB | Oleh: <?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Ketua Jurusan') ?></span>
</div>

<div class="admin-header-row no-print">
    <div>
        <span class="admin-date-label">SIRKULASI & PEMINJAMAN</span>
        <h1 class="admin-title">Laporan Peminjaman Alat Lab</h1>
        <p class="admin-subtitle">Rekapitulasi seluruh riwayat pengajuan dan permohonan pinjam alat lab komputer oleh siswa.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="window.print()">
        🖨 Cetak Laporan
    </button>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header no-print">
        <div>
            <h3 class="inventory-title">Catatan Riwayat Peminjaman</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> transaksi peminjaman tercatat.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form method="GET" action="index.php" style="margin: 0;">
                <input type="hidden" name="c" value="kejur">
                <input type="hidden" name="a" value="laporan_peminjaman">
                <select name="status_filter" class="form-control" style="font-size: 13px; height: 38px; padding: 6px 12px;" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu" <?= (($_GET['status_filter'] ?? '') === 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                    <option value="disetujui" <?= (($_GET['status_filter'] ?? '') === 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                    <option value="dipinjam" <?= (($_GET['status_filter'] ?? '') === 'dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="dikembalikan" <?= (($_GET['status_filter'] ?? '') === 'dikembalikan') ? 'selected' : '' ?>>Dikembalikan</option>
                    <option value="ditolak" <?= (($_GET['status_filter'] ?? '') === 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                </select>
                <?php if (!empty($_GET['search'])): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                <?php endif; ?>
            </form>

            <form method="GET" action="index.php" class="search-box" style="margin: 0;">
                <input type="hidden" name="c" value="kejur">
                <input type="hidden" name="a" value="laporan_peminjaman">
                <?php if (!empty($_GET['status_filter'])): ?>
                    <input type="hidden" name="status_filter" value="<?= htmlspecialchars($_GET['status_filter']) ?>">
                <?php endif; ?>
                <span class="search-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Cari siswa, kode, alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        </div>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kode</th>
                <th>Siswa Peminjam</th>
                <th>Kelas / NIS</th>
                <th>Alat Lab Diminta</th>
                <th>Jml</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada data peminjaman yang ditemukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjaman as $p): 
                    $status = strtolower($p['status']);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($p['kode_peminjaman'] ?? '-') ?></span>
                        </td>
                        <td>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($p['nama_peminjam'] ?? '-') ?></strong>
                        </td>
                        <td><?= htmlspecialchars($p['kelas'] ?? '-') ?> <span style="font-size: 11px; color: var(--text-muted);">(<?= htmlspecialchars($p['nis'] ?? '-') ?>)</span></td>
                        <td>
                            <?= htmlspecialchars($p['nama_alat'] ?? '-') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($p['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td><strong><?= (int)($p['jumlah'] ?? 1) ?></strong> unit</td>
                        <td style="white-space: nowrap;"><?= !empty($p['waktu_pinjam']) ? date('H:i', strtotime($p['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;"><strong style="color: var(--teal-dark);"><?= !empty($p['waktu_rencana_kembali']) ? date('H:i', strtotime($p['waktu_rencana_kembali'])) . ' WIB' : '-' ?></strong></td>
                        <td>
                            <?php if ($status === 'menunggu'): ?>
                                <span class="stock-badge stock-badge-low" style="background:#fef3c7; color:#b45309; padding: 2px 8px; font-size: 11px;">Menunggu</span>
                            <?php elseif ($status === 'disetujui'): ?>
                                <span class="stock-badge" style="background:#dcfce7; color:#15803d; padding: 2px 8px; font-size: 11px; font-weight:700;">Disetujui</span>
                            <?php elseif ($status === 'dipinjam'): ?>
                                <span class="stock-badge stock-badge-available" style="padding: 2px 8px; font-size: 11px;">Dipinjam</span>
                            <?php elseif ($status === 'dikembalikan'): ?>
                                <span class="stock-badge" style="background:#e0f2fe; color:#0369a1; font-weight:700; padding: 2px 8px; font-size: 11px;">Dikembalikan</span>
                            <?php elseif ($status === 'ditolak'): ?>
                                <span class="stock-badge" style="background:#fee2e2; color:#b91c1c; font-weight:700; padding: 2px 8px; font-size: 11px;">Ditolak</span>
                            <?php else: ?>
                                <span class="stock-badge" style="background:#f1f5f9; color:#64748b; font-weight:700; padding: 2px 8px; font-size: 11px;"><?= ucfirst($status) ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($p['nama_petugas'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/kejur_footer.php'; ?>
