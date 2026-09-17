<?php
$activePage = 'dashboard';
$pageTitle = 'Dashboard Peminjam - NARA BAND';
require_once 'Views/peminjam_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label"><?= strtoupper(date('l, d F Y')) ?></span>
        <h1 class="admin-title">Halo, <?= htmlspecialchars($_SESSION['user']['nama_lengkap'] ?? 'Peminjam') ?>! 👋</h1>
        <p class="admin-subtitle">Selamat datang di Ruang Peminjam NARA BAND. Ajukan peminjaman alat musik dan pantau status transaksi Anda.</p>
    </div>
    <a href="index.php?c=peminjam&a=katalog" class="btn-add-instrument">
        + Pinjam Alat Musik
    </a>
</div>

<!-- STATS GRID -->
<div class="admin-stats-grid">
    <div class="stat-card">
        <div class="stat-icon-wrapper icon-orange">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-label">Menunggu Verifikasi</span>
            <div class="stat-number-row">
                <span class="stat-number"><?= $totalPengajuan ?></span>
                <span class="stat-badge badge-warning">Pengajuan</span>
            </div>
            <span class="stat-subtext">Menunggu persetujuan petugas</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper icon-teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-label">Sedang Dipinjam</span>
            <div class="stat-number-row">
                <span class="stat-number"><?= $totalDipinjam ?></span>
                <span class="stat-badge badge-teal">Aktif</span>
            </div>
            <span class="stat-subtext">Alat yang sedang Anda bawa</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper icon-teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-label">Selesai Dikembalikan</span>
            <div class="stat-number-row">
                <span class="stat-number"><?= $totalKembali ?></span>
                <span class="stat-badge badge-teal">Selesai</span>
            </div>
            <span class="stat-subtext">Total transaksi terselesaikan</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrapper" style="background: <?= $totalDenda > 0 ? '#fee2e2' : '#f1f5f9' ?>; color: <?= $totalDenda > 0 ? '#b91c1c' : '#64748b' ?>;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total Denda Tercatat</span>
            <div class="stat-number-row">
                <span class="stat-number" style="font-size: 20px; color: <?= $totalDenda > 0 ? '#b91c1c' : 'inherit' ?>;">
                    Rp <?= number_format($totalDenda, 0, ',', '.') ?>
                </span>
            </div>
            <span class="stat-subtext"><?= $totalDenda > 0 ? 'Denda keterlambatan/kerusakan' : 'Tidak ada denda tertunggak' ?></span>
        </div>
    </div>
</div>

<!-- CTA BANNER -->
<div style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); border-radius: 12px; padding: 24px 28px; color: white; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(20, 184, 166, 0.2);">
    <div>
        <h3 style="margin: 0 0 6px 0; font-size: 18px; font-weight: 700;">Butuh Alat Musik untuk Latihan atau Penampilan?</h3>
        <p style="margin: 0; font-size: 13px; opacity: 0.9; max-width: 550px;">Lihat stok alat musik terkini mulai dari gitar, bass, drum, hingga keyboard dan ajukan peminjaman sekarang dengan cepat.</p>
    </div>
    <a href="index.php?c=peminjam&a=katalog" style="background: white; color: #0f766e; padding: 10px 18px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 13px; white-space: nowrap; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        Jelajahi Katalog &rarr;
    </a>
</div>

<!-- RIWAYAT TERBARU -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Peminjaman Terbaru Anda</h3>
            <p class="inventory-sub">Daftar transaksi peminjaman terbaru yang Anda lakukan.</p>
        </div>
        <a href="index.php?c=peminjam&a=peminjaman" style="font-size: 13px; font-weight: 600; color: var(--teal-dark); text-decoration: none;">
            Lihat Semua &rarr;
        </a>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Alat Musik</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPinjamTerbaru)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Anda belum memiliki riwayat peminjaman alat musik. Klik "+ Pinjam Alat Musik" untuk mulai mengajukan.
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                $no = 1; 
                $top5 = array_slice($daftarPinjamTerbaru, 0, 5);
                foreach ($top5 as $p): 
                    $status = strtolower($p['status']);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name"><?= htmlspecialchars($p['nama_alat'] ?? 'Instrumen') ?></td>
                        <td><?= htmlspecialchars($p['nama_kategori'] ?? '-') ?></td>
                        <td><strong><?= (int)($p['jumlah'] ?? 1) ?></strong> unit</td>
                        <td><?= !empty($p['tanggal_pinjam']) ? date('d M Y', strtotime($p['tanggal_pinjam'])) : '-' ?></td>
                        <td><?= !empty($p['tanggal_kembali']) ? date('d M Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                        <td>
                            <?php if ($status === 'menunggu'): ?>
                                <span class="stock-badge stock-badge-low" style="background:#fef3c7; color:#b45309;">Menunggu Verifikasi</span>
                            <?php elseif ($status === 'disetujui' || $status === 'dipinjam'): ?>
                                <span class="stock-badge stock-badge-available">Sedang Dipinjam</span>
                            <?php elseif ($status === 'dikembalikan'): ?>
                                <span class="stock-badge" style="background:#e0e7ff; color:#3730a3; font-weight:700;">Dikembalikan</span>
                            <?php elseif ($status === 'ditolak'): ?>
                                <span class="stock-badge" style="background:#fee2e2; color:#b91c1c; font-weight:700;">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions-cell">
                            <?php if ($status === 'menunggu'): ?>
                                <a href="index.php?c=peminjam&a=batalkan_peminjaman&id=<?= $p['id_peminjaman'] ?>" class="btn-action-delete" onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan peminjaman ini?')">
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

<?php require_once 'Views/peminjam_footer.php'; ?>
