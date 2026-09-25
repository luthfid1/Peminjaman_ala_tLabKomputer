<?php
$activePage = 'peminjaman';
$pageTitle = 'Peminjaman Saya - Lab Komputer';
require_once __DIR__ . '/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">TRANSAKSI & PENGAJUAN LAB</span>
        <h1 class="admin-title">Peminjaman Saya</h1>
        <p class="admin-subtitle">Pantau status permohonan pinjam alat laboratorium komputer Anda dari tahap pengajuan hingga pengembalian.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalAjukanPinjam')">
        + Ajukan Peminjaman
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
            <h3 class="inventory-title">Daftar Peminjaman Alat Lab</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> riwayat transaksi peminjaman.</p>
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
            <input type="text" name="search" placeholder="Cari kode atau alat..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Alat Lab</th>
                <th>Keperluan / Jenis</th>
                <th>Jml</th>
                <th>Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada riwayat peminjaman. Klik "+ Ajukan Peminjaman" atau telusuri <a href="index.php?c=peminjam&a=daftar_alat" style="color: var(--teal-dark); font-weight: 600;">Katalog Alat Lab</a>.
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
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($p['nama_alat'] ?? 'Alat Lab') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($p['kode_alat'] ?? '') ?> &bull; <?= htmlspecialchars($p['nama_kategori'] ?? '') ?></div>
                        </td>
                        <td>
                            <strong style="font-size: 12px; color: var(--text-secondary);"><?= htmlspecialchars($p['jenis_peminjaman'] ?? 'Praktek Lab') ?></strong>
                            <div style="font-size: 11px; color: var(--text-muted); max-width: 250px;"><?= htmlspecialchars($p['keperluan'] ?? '-') ?></div>
                        </td>
                        <td><strong><?= (int)($p['jumlah'] ?? 1) ?></strong> unit</td>
                        <td style="white-space: nowrap;"><?= !empty($p['waktu_pinjam']) ? date('H:i', strtotime($p['waktu_pinjam'])) . ' WIB' : '-' ?></td>
                        <td style="white-space: nowrap;">
                            <strong style="color: var(--teal-dark);"><?= !empty($p['waktu_rencana_kembali']) ? date('H:i', strtotime($p['waktu_rencana_kembali'])) . ' WIB' : '-' ?></strong>
                        </td>
                        <td>
                            <?php if ($status === 'menunggu'): ?>
                                <span class="stock-badge stock-badge-low" style="background:#fef3c7; color:#b45309; padding: 4px 10px; font-size: 12px;">
                                    ⏳ Menunggu Petugas
                                </span>
                            <?php elseif ($status === 'disetujui' || $status === 'dipinjam'): ?>
                                <span class="stock-badge stock-badge-available" style="padding: 4px 10px; font-size: 12px;">
                                    💻 Sedang Dipinjam
                                </span>
                            <?php elseif ($status === 'dikembalikan'): ?>
                                <span class="stock-badge" style="background:#e0f2fe; color:#0369a1; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    ✅ Selesai Dikembalikan
                                </span>
                            <?php elseif ($status === 'ditolak'): ?>
                                <span class="stock-badge" style="background:#fee2e2; color:#b91c1c; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    ❌ Ditolak
                                </span>
                            <?php elseif ($status === 'dibatalkan'): ?>
                                <span class="stock-badge" style="background:#f1f5f9; color:#64748b; font-weight:700; padding: 4px 10px; font-size: 12px;">
                                    Dibatalkan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions-cell">
                            <?php if ($status === 'menunggu'): ?>
                                <a href="index.php?c=peminjam&a=batalkan_peminjaman&id=<?= $p['id'] ?>" class="btn-action-delete" onclick="return confirm('Batalkan pengajuan permohonan peminjaman ini?')">
                                    Batalkan
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

<!-- MODAL FORM AJUKAN PEMINJAMAN -->
<div class="modal-overlay" id="modalAjukanPinjam">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajukan Peminjaman Alat Lab</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalAjukanPinjam')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=peminjam&a=ajukan_peminjaman">
            <div class="form-group">
                <label class="form-label">Pilih Alat Lab</label>
                <select name="id_alat" id="select_alat" class="form-control" required onchange="updateMaxStok()">
                    <option value="">-- Pilih Perangkat Lab Komputer --</option>
                    <?php foreach ($daftarAlat as $a): ?>
                        <?php if ($a['jumlah'] > 0): ?>
                            <option value="<?= $a['id'] ?>" data-stok="<?= $a['jumlah'] ?>">
                                [<?= htmlspecialchars($a['kode']) ?>] <?= htmlspecialchars($a['nama_alat']) ?> (Sisa stok: <?= $a['jumlah'] ?> unit)
                            </option>
                        <?php else: ?>
                            <option value="<?= $a['id'] ?>" disabled>
                                [<?= htmlspecialchars($a['kode']) ?>] <?= htmlspecialchars($a['nama_alat']) ?> (Stok Habis)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Waktu Mulai Pinjam (WIB)</label>
                    <input type="time" name="waktu_pinjam" class="form-control" value="<?= date('H:i') ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Rencana Pengembalian (WIB)</label>
                    <input type="time" name="waktu_rencana_kembali" class="form-control" value="<?= date('H:i', strtotime('+2 hours')) ?>" required>
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 16px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Jumlah Unit</label>
                    <input type="number" name="jumlah" id="input_jumlah" class="form-control" min="1" max="1" value="1" required>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label class="form-label">Jenis Peminjaman</label>
                    <select name="jenis_peminjaman" class="form-control" required>
                        <option value="Praktek Lab">Praktek Jam Pelajaran Lab</option>
                        <option value="Tugas Projek">Pengerjaan Tugas / Projek Sekolah</option>
                        <option value="Ujian Praktik">Ujian Praktik Kompetensi (UKK)</option>
                        <option value="Kegiatan Ekskul">Kegiatan Ekstrakurikuler Komputer</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keperluan / Keterangan</label>
                <textarea name="keperluan" class="form-control" rows="3" placeholder="Contoh: Praktikum konfigurasi LAN dan routing di Lab RPL" required></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalAjukanPinjam')">Batal</button>
                <button type="submit" class="btn-modal-submit">Kirim Pengajuan</button>
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
function updateMaxStok() {
    var select = document.getElementById('select_alat');
    var selectedOption = select.options[select.selectedIndex];
    var stok = selectedOption.getAttribute('data-stok');
    var inputJml = document.getElementById('input_jumlah');
    if (stok) {
        inputJml.max = stok;
        if (parseInt(inputJml.value) > parseInt(stok)) {
            inputJml.value = stok;
        }
    }
}
</script>

<?php require_once __DIR__ . '/peminjam_footer.php'; ?>
