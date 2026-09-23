<?php
$activePage = 'kategori';
$pageTitle = 'Daftar Kategori Alat Musik - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">KLASIFIKASI INSTRUMEN</span>
        <h1 class="admin-title">Daftar Kategori</h1>
        <p class="admin-subtitle">Pilih kategori alat musik untuk menjelajahi instrumen yang tersedia dan siap dipinjam.</p>
    </div>
</div>

<!-- SECTION KATEGORI CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Kategori Alat Musik</h3>
            <p class="inventory-sub">Total <?= count($daftarKategori) ?> kategori instrumen musik tersedia.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="peminjam">
            <input type="hidden" name="a" value="kategori">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <?php if (empty($daftarKategori)): ?>
        <div style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px auto; opacity: 0.4;">
                <path d="M4 6h16M4 12h16M4 18h7"></path>
            </svg>
            <div style="font-size: 15px; font-weight: 600; color: #334155; margin-bottom: 4px;">Kategori tidak ditemukan</div>
            <p style="margin: 0; font-size: 13px;">Tidak ada kategori yang cocok dengan pencarian Anda.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 8px;">
            <?php foreach ($daftarKategori as $kat): ?>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(20, 184, 166, 0.12); color: var(--teal-dark); display: flex; align-items: center; justify-content: center;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                </svg>
                            </div>
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                                <?= (int)($kat['total_alat'] ?? 0) ?> Jenis Alat
                            </span>
                        </div>

                        <h4 style="margin: 0 0 6px 0; font-size: 17px; font-weight: 700; color: #0f172a;">
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </h4>
                        <p style="margin: 0 0 18px 0; font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                            Tersedia total <strong><?= (int)($kat['total_stok'] ?? 0) ?> unit</strong> instrumen fisik siap disewa pada kategori ini.
                        </p>
                    </div>

                    <a href="index.php?c=peminjam&a=katalog&kategori=<?= $kat['id_kategori'] ?>" 
                       style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; color: #0f766e; background: #ccfbf1; border: 1px solid #99f6e4; transition: all 0.15s ease;">
                        <span>Lihat Alat Musik</span>
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'Views/peminjam_footer.php'; ?>
