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
                            <?php elseif ($status === 'dipinjam'): ?>
                                <button type="button" 
                                        class="btn-action-edit" 
                                        style="color: #0f766e; background: #ccfbf1; border-color: #99f6e4; font-weight: 600;" 
                                        onclick="openModalKembalikan(<?= (int)$p['id_peminjaman'] ?>, '<?= htmlspecialchars(addslashes($p['nama_alat'] ?? 'Instrumen')) ?>', '<?= htmlspecialchars($p['tanggal_kembali'] ?? '') ?>')">
                                    Kembalikan
                                </button>
                            <?php elseif ($status === 'dikembalikan'): ?>
                                <a href="index.php?c=peminjam&a=pengembalian" class="btn-action-edit" style="color: var(--teal-dark);">
                                    Lihat Riwayat
                                </a>
                            <?php else: ?>
                                <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL KEMBALIKAN ALAT -->
<div class="modal-overlay" id="modalKembalikanAlat">
    <div class="modal-content" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title">Form Pengembalian Alat Musik</h3>
            <button type="button" class="modal-close" onclick="closeModalKembalikan()">&times;</button>
        </div>
        <form method="POST" action="index.php?c=peminjam&a=kembalikan_alat">
            <input type="hidden" name="id_peminjaman" id="kembalikan_id_peminjaman">

            <div style="padding: 16px; background: #f0fdf4; border-radius: 8px; margin-bottom: 16px; border: 1px solid #bbf7d0;">
                <div style="font-size: 12px; color: #166534;">Anda akan mengembalikan:</div>
                <div style="font-size: 15px; font-weight: 700; color: #14532d;" id="kembalikan_nama_alat">-</div>
                <div style="font-size: 12px; color: #166534; margin-top: 4px;" id="kembalikan_info_tenggat"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Pengembalian <span style="color:#ef4444;">*</span></label>
                <input type="date" name="tanggal_pengembalian" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Kondisi / Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Alat dikembalikan dalam keadaan lengkap dan baik."></textarea>
            </div>

            <div class="modal-footer" style="margin-top: 20px;">
                <button type="button" class="btn-modal-cancel" onclick="closeModalKembalikan()">Batal</button>
                <button type="submit" class="btn-modal-submit">Konfirmasi Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModalKembalikan(idPeminjaman, namaAlat, tglKembali) {
    document.getElementById('kembalikan_id_peminjaman').value = idPeminjaman;
    document.getElementById('kembalikan_nama_alat').textContent = namaAlat;
    document.getElementById('kembalikan_info_tenggat').textContent = 'Tenggat Waktu: ' + (tglKembali || '-');
    document.getElementById('modalKembalikanAlat').classList.add('active');
}

function closeModalKembalikan() {
    document.getElementById('modalKembalikanAlat').classList.remove('active');
}
</script>

<?php require_once 'Views/peminjam_footer.php'; ?>
