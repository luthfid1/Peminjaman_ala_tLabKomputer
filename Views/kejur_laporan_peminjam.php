<?php
$activePage = 'laporan_peminjam';
$pageTitle = 'Laporan Data Peminjam - Ketua Jurusan';
require_once __DIR__ . '/kejur_header.php';
?>

<div class="print-kop">
    <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">LAPORAN DATA ANGGOTA / PEMINJAM LABORATORIUM KOMPUTER</h2>
    <p style="margin: 4px 0 0 0; font-size: 12px;">SMK NEGERI - JURUSAN REKAYASA PERANGKAT LUNAK</p>
    <span style="font-size: 10px; color: #555;">Dicetak pada: <?= date('d F Y, H:i') ?> WIB | Oleh: <?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Ketua Jurusan') ?></span>
</div>

<div class="admin-header-row no-print">
    <div>
        <span class="admin-date-label">LAPORAN DATA MASTER</span>
        <h1 class="admin-title">Laporan Data Peminjam Lab</h1>
        <p class="admin-subtitle">Rekapitulasi resmi data siswa yang terdaftar sebagai peminjam alat laboratorium komputer.</p>
    </div>
    <button type="button" class="btn-add-instrument" onclick="window.print()">
        🖨 Cetak Laporan
    </button>
</div>

<!-- INVENTORY CARD & TABLE -->
<section class="inventory-section-card">
    <div class="inventory-header no-print">
        <div>
            <h3 class="inventory-title">Daftar Peminjam Terdaftar</h3>
            <p class="inventory-sub">Total <?= count($daftarPeminjam) ?> siswa dalam database laboratorium.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="kejur">
            <input type="hidden" name="a" value="laporan_peminjam">
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari nama, NIS, atau kelas..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Nama Lengkap Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Nomor Telepon</th>
                <th>Foto Jaminan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjam)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada data peminjam yang sesuai dengan pencarian.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjam as $p): 
                    $fotoName = $p['foto_kartu_pelajar'] ?? $p['foto'] ?? '';
                    $fotoPath = '';
                    if (!empty($fotoName)) {
                        if (file_exists(__DIR__ . '/../Assets/uploads/jaminan/' . $fotoName)) {
                            $fotoPath = 'Assets/uploads/jaminan/' . $fotoName;
                        } elseif (file_exists(__DIR__ . '/../Assets/uploads/kartu/' . $fotoName)) {
                            $fotoPath = 'Assets/uploads/kartu/' . $fotoPath;
                        } else {
                            $fotoPath = 'Assets/uploads/jaminan/' . $fotoName;
                        }
                    }
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong style="color: var(--primary-navy);"><?= htmlspecialchars($p['nama']) ?></strong>
                        </td>
                        <td><span style="font-family: monospace; font-size: 12px; font-weight: 700;"><?= htmlspecialchars($p['nis'] ?? '-') ?></span></td>
                        <td><?= htmlspecialchars($p['kelas'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['jurusan'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['no_telp'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($fotoPath) && !empty($fotoName)): ?>
                                <a href="<?= htmlspecialchars($fotoPath) ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; padding: 4px 10px; border-radius: 6px; background: rgba(0, 180, 160, 0.1); color: var(--primary-teal); font-size: 11px; font-weight: 600;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    Lihat Jaminan
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 12px;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/kejur_footer.php'; ?>
