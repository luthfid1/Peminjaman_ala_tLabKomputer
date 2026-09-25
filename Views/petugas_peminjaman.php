<?php
$activePage = 'peminjaman';
$pageTitle = 'Persetujuan Peminjaman - Petugas Laboran';
require_once __DIR__ . '/petugas_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">VERIFIKASI & PERSETUJUAN ALAT</span>
        <h1 class="admin-title">Persetujuan Peminjaman</h1>
        <p class="admin-subtitle">Verifikasi permohonan pinjam siswa, berikan persetujuan, atau serahkan fisik perangkat lab komputer.</p>
    </div>
</div>

<!-- ALERTS -->
<?php if (!empty($message)): ?>
    <div class="auth-alert auth-alert-success">
        <span><?= htmlspecialchars($message) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="auth-alert auth-alert-error">
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Daftar Pengajuan Peminjaman</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> data permohonan peminjaman tercatat.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <!-- STATUS FILTER -->
            <form method="GET" action="index.php" style="margin: 0; display: flex; gap: 8px;">
                <input type="hidden" name="c" value="petugas">
                <input type="hidden" name="a" value="peminjaman">
                <select name="status_filter" class="form-control" style="font-size: 13px; padding: 6px 12px; height: 38px;" onchange="this.form.submit()">
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

            <!-- SEARCH BOX -->
            <form method="GET" action="index.php" class="search-box" style="margin: 0;">
                <input type="hidden" name="c" value="petugas">
                <input type="hidden" name="a" value="peminjaman">
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
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab Diminta</th>
                <th>Keperluan / Jenis</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada data peminjaman yang sesuai dengan filter atau pencarian.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjaman as $p): 
                    $status = strtolower($p['status']);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($p['kode_peminjaman'] ?? '-') ?></div>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($p['nama_peminjam'] ?? 'Siswa') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: <?= htmlspecialchars($p['nis'] ?? '-') ?> &bull; <?= htmlspecialchars($p['kelas'] ?? '') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_alat'] ?? 'Alat Lab') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= (int)($p['jumlah'] ?? 1) ?> unit &bull; <?= htmlspecialchars($p['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td>
                            <strong style="font-size: 12px; color: var(--text-secondary);"><?= htmlspecialchars($p['jenis_peminjaman'] ?? 'Praktek Lab') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted); max-width: 200px;"><?= htmlspecialchars($p['keperluan'] ?? '-') ?></div>
                        </td>
                        <td style="white-space: nowrap;"><?= !empty($p['waktu_pinjam']) ? date('H:i', strtotime($p['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;">
                            <strong style="color: var(--teal-dark);"><?= !empty($p['waktu_rencana_kembali']) ? date('H:i', strtotime($p['waktu_rencana_kembali'])) . ' WIB' : '-' ?></strong>
                        </td>
                        <td>
                            <?php if ($status === 'menunggu'): ?>
                                <span class="stock-badge stock-badge-low" style="background:#fef3c7; color:#b45309; padding: 4px 10px; font-size: 12px;">
                                    ⏳ Menunggu
                                </span>
                            <?php elseif ($status === 'disetujui'): ?>
                                <span class="stock-badge" style="background:#dcfce7; color:#15803d; padding: 4px 10px; font-size: 12px; font-weight:700;">
                                    ✓ Disetujui
                                </span>
                            <?php elseif ($status === 'dipinjam'): ?>
                                <span class="stock-badge stock-badge-available" style="padding: 4px 10px; font-size: 12px;">
                                    💻 Sedang Dipinjam
                                </span>
                            <?php elseif ($status === 'dikembalikan'): ?>
                                <span class="stock-badge" style="background:#e0f2fe; color:#0369a1; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    Dikembalikan
                                </span>
                            <?php elseif ($status === 'ditolak'): ?>
                                <span class="stock-badge" style="background:#fee2e2; color:#b91c1c; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    Ditolak
                                </span>
                            <?php elseif ($status === 'dibatalkan'): ?>
                                <span class="stock-badge" style="background:#f1f5f9; color:#64748b; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    Dibatalkan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions-cell">
                            <?php if ($status === 'menunggu'): ?>
                                <a href="index.php?c=petugas&a=setujui_peminjaman&id=<?= $p['id'] ?>" class="btn-action-edit" style="background: #10b981; color: white;" onclick="return confirm('Setujui permohonan pinjam ini?')">
                                    Setujui
                                </a>
                                <a href="index.php?c=petugas&a=tolak_peminjaman&id=<?= $p['id'] ?>" class="btn-action-delete" onclick="return confirm('Tolak permohonan pinjam ini?')">
                                    Tolak
                                </a>
                            <?php elseif ($status === 'disetujui'): ?>
                                <a href="index.php?c=petugas&a=serahkan_alat&id=<?= $p['id'] ?>" class="btn-action-edit" style="background: #0284c7; color: white;" onclick="return confirm('Konfirmasi penyerahan fisik alat kepada siswa?')">
                                    Serahkan Fisik
                                </a>
                            <?php else: ?>
                                <span style="font-size: 12px; color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/petugas_footer.php'; ?>
