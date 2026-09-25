<?php
$activePage = 'pengembalian';
$pageTitle = 'Kelola Pengembalian Alat - Pengelola Lab';
require_once __DIR__ . '/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">PENGEMBALIAN & PEMULIHAN STOK</span>
        <h1 class="admin-title">Kelola Pengembalian Alat Lab</h1>
        <p class="admin-subtitle">Catat pengembalian alat komputer dari siswa, verifikasi kondisi fisik perangkat, dan pulihkan stok lab.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahPengembalian')">
        + Catat Pengembalian
    </button>
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
            <h3 class="inventory-title">Riwayat Pengembalian Alat Lab</h3>
            <p class="inventory-sub">Total <?= count($daftarPengembalian) ?> transaksi pengembalian selesai.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="pengembalian">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari siswa, kode, atau alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab Dikembalikan</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Dikembalikan</th>
                <th>Kondisi Kembali</th>
                <th>Denda</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPengembalian)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada riwayat pengembalian alat. Klik "+ Catat Pengembalian" untuk memproses pengembalian alat aktif.
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
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($pg['nama_peminjam'] ?? 'Siswa') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: <?= htmlspecialchars($pg['nis'] ?? '-') ?> &bull; <?= htmlspecialchars($pg['kelas'] ?? '') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($pg['nama_alat'] ?? 'Alat Lab') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= (int)($pg['jumlah'] ?? 1) ?> unit &bull; <?= htmlspecialchars($pg['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td><?= !empty($pg['tanggal_pinjam']) ? date('d M Y', strtotime($pg['tanggal_pinjam'])) : '-' ?></td>
                        <td>
                            <strong style="color: var(--teal-dark);"><?= !empty($pg['tanggal_kembali']) ? date('d M Y', strtotime($pg['tanggal_kembali'])) : '-' ?></strong>
                            <?php if (!empty($pg['tanggal_rencana_kembali'])): ?>
                                <div style="font-size: 11px; color: var(--text-muted);">Tenggat: <?= date('d M Y', strtotime($pg['tanggal_rencana_kembali'])) ?></div>
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
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editPengembalian(<?= json_encode($pg) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_pengembalian&id=<?= $pg['id'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus catatan pengembalian ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH PENGEMBALIAN -->
<div class="modal-overlay" id="modalTambahPengembalian">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Catat Pengembalian Alat Lab</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahPengembalian')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_pengembalian">
            <div class="form-group">
                <label class="form-label">Pilih Peminjaman Aktif</label>
                <select name="id_peminjaman" class="form-control" required>
                    <option value="">-- Pilih Peminjaman yang Sedang Berjalan --</option>
                    <?php if (empty($peminjamanAktif)): ?>
                        <option value="" disabled>Tidak ada peminjaman aktif saat ini</option>
                    <?php else: ?>
                        <?php foreach ($peminjamanAktif as $pa): ?>
                            <option value="<?= $pa['id'] ?>">
                                [<?= htmlspecialchars($pa['kode_peminjaman']) ?>] <?= htmlspecialchars($pa['nama_peminjam']) ?> - <?= htmlspecialchars($pa['nama_alat']) ?> (<?= $pa['jumlah'] ?> unit)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Kondisi Alat Saat Kembali</label>
                    <select name="kondisi_kembali" class="form-control" required>
                        <option value="Baik & Lengkap">Baik & Lengkap</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Komponen Hilang">Komponen Hilang</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Denda (Jika ada kerusakan / terlambat)</label>
                <input type="number" name="denda" class="form-control" min="0" value="0" placeholder="0">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahPengembalian')">Batal</button>
                <button type="submit" class="btn-modal-submit">Proses Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH PENGEMBALIAN -->
<div class="modal-overlay" id="modalUbahPengembalian">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ubah Catatan Pengembalian</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahPengembalian')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_pengembalian">
            <input type="hidden" name="id" id="edit_pg_id">

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" id="edit_pg_tanggal_kembali" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Kondisi Alat</label>
                    <select name="kondisi_kembali" id="edit_pg_kondisi" class="form-control" required>
                        <option value="Baik & Lengkap">Baik & Lengkap</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Komponen Hilang">Komponen Hilang</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Denda (Rp)</label>
                <input type="number" name="denda" id="edit_pg_denda" class="form-control" min="0" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahPengembalian')">Batal</button>
                <button type="submit" class="btn-modal-submit">Perbarui Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function editPengembalian(data) {
    document.getElementById('edit_pg_id').value = data.id;
    document.getElementById('edit_pg_tanggal_kembali').value = data.tanggal_kembali;
    document.getElementById('edit_pg_kondisi').value = data.kondisi_kembali || 'Baik & Lengkap';
    document.getElementById('edit_pg_denda').value = data.denda || 0;
    openModal('modalUbahPengembalian');
}
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
