<?php
$activePage = 'peminjaman';
$pageTitle = 'Kelola Peminjaman Alat - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">TRANSAKSI & SIRKULASI</span>
        <h1 class="admin-title">Kelola Peminjaman</h1>
        <p class="admin-subtitle">Catat peminjaman offline (langsung di studio), kelola data transaksi, dan pantau masa pinjam.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="openModal('modalTambahPeminjaman')">
        + Catat Peminjaman (Offline)
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
            <h3 class="inventory-title">Daftar Seluruh Peminjaman</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjaman) ?> transaksi peminjaman tercatat.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="peminjaman">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama peminjam, alat, atau status..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam (Nama & Data)</th>
                <th>Alat Musik</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjaman)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada data transaksi peminjaman. Klik "+ Catat Peminjaman (Offline)" untuk menambahkan peminjaman baru secara manual.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjaman as $p): 
                    $status = strtolower($p['status']);
                    $statusBadgeClass = 'badge-busy';
                    $statusLabel = ucfirst($status);

                    if ($status === 'menunggu') {
                        $statusBadgeClass = 'badge-busy';
                        $statusLabel = 'Menunggu';
                    } elseif ($status === 'disetujui') {
                        $statusBadgeClass = 'badge-ready';
                        $statusLabel = 'Disetujui';
                    } elseif ($status === 'dipinjam') {
                        $statusBadgeClass = 'badge-ready';
                        $statusLabel = 'Dipinjam';
                    } elseif ($status === 'dikembalikan') {
                        $statusBadgeClass = 'badge-ready';
                        $statusLabel = 'Selesai';
                    } elseif ($status === 'ditolak') {
                        $statusBadgeClass = 'badge-busy';
                        $statusLabel = 'Ditolak';
                    }
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_lengkap'] ?? 'User #' . $p['id_user']) ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                @<?= htmlspecialchars($p['username'] ?? '-') ?>
                            </div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_alat'] ?? 'Alat #' . $p['id_alat']) ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                <?= htmlspecialchars($p['nama_kategori'] ?? 'Kategori') ?> &bull; Rp<?= number_format($p['harga_sewa'] ?? 0, 0, ',', '.') ?>/hari
                            </div>
                        </td>
                        <td style="font-weight: 600; text-align: center;"><?= (int)$p['jumlah'] ?> unit</td>
                        <td><?= !empty($p['tanggal_pinjam']) ? date('d M Y', strtotime($p['tanggal_pinjam'])) : '-' ?></td>
                        <td><?= !empty($p['tanggal_kembali']) ? date('d M Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                        <td>
                            <span class="status-badge <?= $statusBadgeClass ?>" style="<?php
                                if ($status === 'menunggu') echo 'background: rgba(245,158,11,0.15); color: #d97706; border: 1px solid rgba(245,158,11,0.3);';
                                elseif ($status === 'disetujui') echo 'background: rgba(59,130,246,0.15); color: #2563eb; border: 1px solid rgba(59,130,246,0.3);';
                                elseif ($status === 'dipinjam') echo 'background: rgba(16,185,129,0.15); color: #059669; border: 1px solid rgba(16,185,129,0.3);';
                                elseif ($status === 'dikembalikan') echo 'background: rgba(100,116,139,0.15); color: #475569; border: 1px solid rgba(100,116,139,0.3);';
                                elseif ($status === 'ditolak') echo 'background: rgba(239,68,68,0.15); color: #dc2626; border: 1px solid rgba(239,68,68,0.3);';
                            ?>">
                                <?= $statusLabel ?>
                            </span>
                        </td>
                        <td class="table-actions-cell">
                            <button type="button" class="btn-action-edit" onclick='editPeminjaman(<?= json_encode($p) ?>)'>
                                Ubah
                            </button>
                            <a href="index.php?c=admin&a=hapus_peminjaman&id=<?= $p['id_peminjaman'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus data transaksi peminjaman ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- MODAL TAMBAH PEMINJAMAN (PENCATATAN OFFLINE DENGAN INPUT DATA DIRI MANUAL) -->
<div class="modal-overlay" id="modalTambahPeminjaman">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Catat Peminjaman Offline</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Input manual data diri peminjam langsung di tempat tanpa perlu login akun.</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('modalTambahPeminjaman')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=tambah_peminjaman">
            <!-- DATA PEMINJAM (MANUAL INPUT) -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; font-weight: 800; color: var(--primary-navy); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Data Diri Peminjam (Offline)
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap Peminjam <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Username / Identitas <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: budi_off / NIS / No.HP" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Contoh: Jl. Merdeka No. 10">
                    </div>
                </div>
            </div>

            <!-- DETAIL ALAT & PINJAM -->
            <div class="form-group">
                <label class="form-label">Pilih Alat Musik <span style="color:#ef4444;">*</span></label>
                <select name="id_alat" class="form-control" required>
                    <option value="">-- Pilih Alat yang Dipinjam --</option>
                    <?php foreach ($daftarAlat as $a): ?>
                        <option value="<?= $a['id_alat'] ?>">
                            <?= htmlspecialchars($a['nama_alat']) ?> (Tersedia: <?= (int)$a['jumlah_stok'] ?> unit | Rp<?= number_format($a['harga_sewa'], 0, ',', '.') ?>/hari)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Unit <span style="color:#ef4444;">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Pinjam <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Rencana Kembali <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Peminjaman</label>
                <select name="status" class="form-control" required>
                    <option value="dipinjam" selected>Dipinjam (Instrumen langsung dibawa peminjam)</option>
                    <option value="disetujui">Disetujui (Menunggu diambil)</option>
                    <option value="menunggu">Menunggu</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambahPeminjaman')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Peminjaman Offline</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UBAH PEMINJAMAN -->
<div class="modal-overlay" id="modalUbahPeminjaman">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Ubah Data Peminjaman</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Perbarui data diri peminjam atau transaksi peminjaman.</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('modalUbahPeminjaman')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=admin&a=ubah_peminjaman">
            <input type="hidden" name="id_peminjaman" id="edit_id_peminjaman">
            <input type="hidden" name="id_user" id="edit_id_user">

            <!-- DATA PEMINJAM -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; font-weight: 800; color: var(--primary-navy); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Data Diri Peminjam
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap Peminjam <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nama_lengkap" id="edit_nama_lengkap" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username / Identitas <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="username" id="edit_username" class="form-control" required>
                </div>
            </div>

            <!-- DETAIL TRANSAKSI -->
            <div class="form-group">
                <label class="form-label">Alat Musik <span style="color:#ef4444;">*</span></label>
                <select name="id_alat" id="edit_id_alat" class="form-control" required>
                    <option value="">-- Pilih Alat --</option>
                    <?php foreach ($daftarAlat as $a): ?>
                        <option value="<?= $a['id_alat'] ?>">
                            <?= htmlspecialchars($a['nama_alat']) ?> (Stok: <?= (int)$a['jumlah_stok'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Unit <span style="color:#ef4444;">*</span></label>
                <input type="number" name="jumlah" id="edit_jumlah" class="form-control" min="1" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Pinjam <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_pinjam" id="edit_tanggal_pinjam" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Rencana Kembali <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_kembali" id="edit_tanggal_kembali" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Peminjaman</label>
                <select name="status" id="edit_status" class="form-control" required>
                    <option value="menunggu">Menunggu</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalUbahPeminjaman')">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
function editPeminjaman(data) {
    document.getElementById('edit_id_peminjaman').value = data.id_peminjaman;
    document.getElementById('edit_id_user').value = data.id_user;
    document.getElementById('edit_nama_lengkap').value = data.nama_lengkap || '';
    document.getElementById('edit_username').value = data.username || '';
    document.getElementById('edit_id_alat').value = data.id_alat;
    document.getElementById('edit_jumlah').value = data.jumlah;
    document.getElementById('edit_tanggal_pinjam').value = data.tanggal_pinjam;
    document.getElementById('edit_tanggal_kembali').value = data.tanggal_kembali;
    document.getElementById('edit_status').value = data.status;
    openModal('modalUbahPeminjaman');
}
</script>

<?php require_once 'Views/admin_footer.php'; ?>
