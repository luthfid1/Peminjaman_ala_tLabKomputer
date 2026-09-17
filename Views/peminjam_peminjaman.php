<?php
$activePage = 'peminjaman';
$pageTitle = 'Peminjaman Saya - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">STATUS & TRANSAKSI</span>
        <h1 class="admin-title">Peminjaman Saya</h1>
        <p class="admin-subtitle">Pantau seluruh status permohonan pinjam alat musik Anda mulai dari pengajuan hingga persetujuan.</p>
    </div>
    <a href="index.php?c=peminjam&a=katalog" class="btn-add-instrument">
        + Ajukan Peminjaman Baru
    </a>
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
            <h3 class="inventory-title">Daftar Pengajuan & Peminjaman</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> transaksi peminjaman tercatat.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="peminjam">
            <input type="hidden" name="a" value="peminjaman">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama alat musik atau status..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Alat Musik</th>
                <th>Jumlah</th>
                <th>Tgl Mulai Pinjam</th>
                <th>Tgl Rencana Kembali</th>
                <th>Status Pengajuan</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada riwayat peminjaman. Silakan ajukan peminjaman alat musik melalui menu <a href="index.php?c=peminjam&a=katalog" style="color: var(--teal-dark); font-weight: 600;">Katalog Alat</a>.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjaman as $p): 
                    $status = strtolower($p['status']);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_alat'] ?? 'Instrumen') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                <?= htmlspecialchars($p['nama_kategori'] ?? 'Kategori') ?> &bull; Kondisi: <?= htmlspecialchars($p['kondisi'] ?? 'Baik') ?>
                            </div>
                        </td>
                        <td><strong><?= (int)($p['jumlah'] ?? 1) ?></strong> unit</td>
                        <td><?= !empty($p['tanggal_pinjam']) ? date('d M Y', strtotime($p['tanggal_pinjam'])) : '-' ?></td>
                        <td>
                            <strong style="color: #0f172a;"><?= !empty($p['tanggal_kembali']) ? date('d M Y', strtotime($p['tanggal_kembali'])) : '-' ?></strong>
                        </td>
                        <td>
                            <?php if ($status === 'menunggu'): ?>
                                <span class="stock-badge stock-badge-low" style="background:#fef3c7; color:#b45309; padding: 4px 10px; font-size: 12px;">
                                    ⏳ Menunggu Persetujuan
                                </span>
                            <?php elseif ($status === 'disetujui' || $status === 'dipinjam'): ?>
                                <span class="stock-badge stock-badge-available" style="padding: 4px 10px; font-size: 12px;">
                                    🎸 Sedang Dipinjam
                                </span>
                            <?php elseif ($status === 'dikembalikan'): ?>
                                <span class="stock-badge" style="background:#e0e7ff; color:#3730a3; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    ✅ Selesai Dikembalikan
                                </span>
                            <?php elseif ($status === 'ditolak'): ?>
                                <span class="stock-badge" style="background:#fee2e2; color:#b91c1c; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    ❌ Pengajuan Ditolak
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions-cell">
                            <?php if ($status === 'menunggu'): ?>
                                <a href="index.php?c=peminjam&a=batalkan_peminjaman&id=<?= $p['id_peminjaman'] ?>" 
                                   class="btn-action-delete" 
                                   onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan peminjaman alat ini?')">
                                    Batalkan
                                </a>
                            <?php else: ?>
                                <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Terkunci</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once 'Views/peminjam_footer.php'; ?>
