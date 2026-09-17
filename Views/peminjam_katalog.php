<?php
$activePage = 'katalog';
$pageTitle = 'Katalog Alat Musik - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">INVENTARIS & STOK</span>
        <h1 class="admin-title">Katalog Alat Musik</h1>
        <p class="admin-subtitle">Pilih instrumen yang Anda butuhkan dan ajukan peminjaman secara online.</p>
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

<!-- SEARCH & FILTER BAR -->
<div style="background: white; padding: 16px 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 24px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;">
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
        <span style="font-size: 13px; font-weight: 600; color: #475569; margin-right: 4px;">Kategori:</span>
        <a href="index.php?c=peminjam&a=katalog<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
           style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; <?= empty($_GET['kategori']) ? 'background: var(--teal-dark); color: white;' : 'background: #f1f5f9; color: #475569;' ?>">
            Semua (<?= count($daftarAlat) ?>)
        </a>
        <?php foreach ($daftarKategori as $kat): 
            $isActive = (isset($_GET['kategori']) && $_GET['kategori'] == $kat['id_kategori']);
        ?>
            <a href="index.php?c=peminjam&a=katalog&kategori=<?= $kat['id_kategori'] ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
               style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; <?= $isActive ? 'background: var(--teal-dark); color: white;' : 'background: #f1f5f9; color: #475569;' ?>">
                <?= htmlspecialchars($kat['nama_kategori']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <form method="GET" action="index.php" class="search-box" style="width: 280px; margin: 0;">
        <input type="hidden" name="c" value="peminjam">
        <input type="hidden" name="a" value="katalog">
        <?php if (!empty($_GET['kategori'])): ?>
            <input type="hidden" name="kategori" value="<?= htmlspecialchars($_GET['kategori']) ?>">
        <?php endif; ?>
        <span class="search-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </span>
        <input type="text" name="search" placeholder="Cari alat musik..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>
</div>

<!-- INSTRUMENT CARDS GRID -->
<?php if (empty($daftarAlat)): ?>
    <div style="background: white; border-radius: 12px; padding: 48px; text-align: center; border: 1px solid #e2e8f0;">
        <svg width="48" height="48" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 12px;">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <h4 style="margin: 0 0 6px 0; color: #1e293b;">Alat musik tidak ditemukan</h4>
        <p style="margin: 0; color: var(--text-muted); font-size: 13px;">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <?php foreach ($daftarAlat as $alat): 
            $stok = (int)($alat['jumlah_stok'] ?? 0);
            $tersedia = ($stok > 0);
        ?>
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="height: 160px; background: #f8fafc; display: flex; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid #f1f5f9;">
                    <?php if (!empty($alat['foto']) && file_exists('Assets/uploads/' . $alat['foto'])): ?>
                        <img src="Assets/uploads/<?= htmlspecialchars($alat['foto']) ?>" alt="<?= htmlspecialchars($alat['nama_alat']) ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                            </svg>
                            <span style="font-size: 11px; margin-top: 4px; font-weight: 500;">Instrumen Musik</span>
                        </div>
                    <?php endif; ?>
                    
                    <span style="position: absolute; top: 12px; left: 12px; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px; <?= $tersedia ? 'background: #dcfce7; color: #15803d;' : 'background: #fee2e2; color: #b91c1c;' ?>">
                        <?= $tersedia ? 'Tersedia: ' . $stok . ' Unit' : 'Stok Habis' ?>
                    </span>
                </div>

                <div style="padding: 16px; display: flex; flex-direction: column; flex-grow: 1;">
                    <span style="font-size: 12px; font-weight: 600; color: var(--teal-dark); text-transform: uppercase; letter-spacing: 0.5px;">
                        <?= htmlspecialchars($alat['nama_kategori'] ?? 'Kategori') ?>
                    </span>
                    <h3 style="margin: 4px 0 8px 0; font-size: 16px; font-weight: 700; color: #0f172a;">
                        <?= htmlspecialchars($alat['nama_alat']) ?>
                    </h3>

                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5; flex-grow: 1;">
                        <div>Kondisi: <strong><?= htmlspecialchars($alat['kondisi'] ?? 'Baik') ?></strong></div>
                        <div style="margin-top: 4px;">Tarif Denda Telat: <strong>Rp 10.000 / hari / unit</strong></div>
                    </div>

                    <button type="button" 
                            class="btn-modal-submit" 
                            style="width: 100%; padding: 10px; font-size: 13px; text-align: center; border: none; cursor: <?= $tersedia ? 'pointer' : 'not-allowed' ?>; <?= !$tersedia ? 'background: #cbd5e1; color: #64748b;' : '' ?>"
                            <?= !$tersedia ? 'disabled' : '' ?>
                            onclick='openPinjamModal(<?= json_encode($alat) ?>)'>
                        <?= $tersedia ? '+ Ajukan Peminjaman' : 'Stok Tidak Tersedia' ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- MODAL AJUKAN PEMINJAMAN -->
<div class="modal-overlay" id="modalAjukanPinjam">
    <div class="modal-content" style="max-width: 540px;">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Form Pengajuan Peminjaman</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Pengajuan akan diverifikasi dan disetujui oleh Petugas.</p>
            </div>
            <button type="button" class="modal-close" onclick="closeModal('modalAjukanPinjam')">&times;</button>
        </div>
        <form method="POST" action="index.php?c=peminjam&a=ajukan_peminjaman">
            <input type="hidden" name="id_alat" id="form_id_alat">

            <!-- INFO ALAT YANG DIPILIH -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 11px; font-weight: 700; color: var(--teal-dark); text-transform: uppercase;" id="form_kategori_alat">Kategori</span>
                    <h4 style="margin: 2px 0 0 0; font-size: 15px; color: #0f172a;" id="form_nama_alat">Nama Alat</h4>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 11px; color: var(--text-muted);">Stok Tersedia:</span>
                    <div style="font-size: 14px; font-weight: 700; color: #15803d;" id="form_stok_alat">0 Unit</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Unit yang Ingin Dipinjam <span style="color:#ef4444;">*</span></label>
                <input type="number" name="jumlah" id="form_jumlah" class="form-control" value="1" min="1" required>
                <small style="font-size: 11px; color: var(--text-muted);">Maksimal sesuai sisa stok yang tersedia saat ini.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Pinjam <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Rencana Kembali <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" min="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 6px; padding: 10px 12px; margin-bottom: 16px; font-size: 12px; color: #92400e; display: flex; align-items: flex-start; gap: 8px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0; margin-top: 1px;">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span><strong>Ketentuan Denda:</strong> Denda keterlambatan sebesar <strong>Rp 10.000 / hari / unit</strong> akan dihitung otomatis jika alat dikembalikan melewati tanggal rencana kembali.</span>
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
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
function openPinjamModal(alat) {
    document.getElementById('form_id_alat').value = alat.id_alat;
    document.getElementById('form_nama_alat').innerText = alat.nama_alat;
    document.getElementById('form_kategori_alat').innerText = alat.nama_kategori || 'Kategori';
    document.getElementById('form_stok_alat').innerText = alat.jumlah_stok + ' Unit';
    
    const inputJumlah = document.getElementById('form_jumlah');
    inputJumlah.max = alat.jumlah_stok;
    inputJumlah.value = 1;

    openModal('modalAjukanPinjam');
}
</script>

<?php require_once 'Views/peminjam_footer.php'; ?>
