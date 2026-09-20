<?php
$activePage = 'log';
$pageTitle = 'Log Aktivitas Pengguna - SEWANADA';
require_once 'Views/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">AUDIT SISTEM</span>
        <h1 class="admin-title">Log Aktivitas Anggota</h1>
        <p class="admin-subtitle">Catatan rekam jejak aktivitas login, peminjaman, dan pengelolaan data oleh seluruh pengguna.</p>
    </div>
</div>

<!-- =============================================
     SECTION: INFO BARANG SEDANG DIPINJAM
     ============================================= -->
<section class="inventory-section-card" style="margin-bottom: 24px;">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title" style="display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Barang Sedang Dipinjam
                <span style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 22px;
                    height: 22px;
                    padding: 0 6px;
                    border-radius: 999px;
                    background: var(--accent, #f59e0b);
                    color: #fff;
                    font-size: 11px;
                    font-weight: 700;
                    line-height: 1;
                "><?= $totalPeminjamanAktif ?></span>
            </h3>
            <p class="inventory-sub">Daftar peminjam aktif dan barang yang sedang mereka gunakan.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="log">
            <?php if (!empty($_GET['search'])): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
            <?php endif; ?>
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search_peminjaman" placeholder="Cari peminjam atau barang..." value="<?= htmlspecialchars($_GET['search_peminjaman'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang Dipinjam</th>
                <th>Kategori</th>
                <th>Jml</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjamanAktif)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="display:block; margin: 0 auto 8px; opacity:0.4;">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path>
                            <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                        </svg>
                        Tidak ada barang yang sedang dipinjam saat ini.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjamanAktif as $p): ?>
                    <?php
                        // Tentukan warna & label status
                        $statusLabel = ucfirst($p['status']);
                        $statusStyle = '';
                        switch ($p['status']) {
                            case 'menunggu':
                                $statusStyle = 'background: rgba(245,158,11,0.15); color: #d97706; border: 1px solid rgba(245,158,11,0.3);';
                                $statusLabel = 'Menunggu';
                                break;
                            case 'disetujui':
                                $statusStyle = 'background: rgba(59,130,246,0.15); color: #2563eb; border: 1px solid rgba(59,130,246,0.3);';
                                $statusLabel = 'Disetujui';
                                break;
                            case 'dipinjam':
                                $statusStyle = 'background: rgba(16,185,129,0.15); color: #059669; border: 1px solid rgba(16,185,129,0.3);';
                                $statusLabel = 'Dipinjam';
                                break;
                        }

                        // Format tanggal
                        $tglPinjam = !empty($p['tanggal_pinjam']) ? date('d M Y', strtotime($p['tanggal_pinjam'])) : '-';
                        $tglKembali = !empty($p['tanggal_kembali']) ? date('d M Y', strtotime($p['tanggal_kembali'])) : '-';

                        // Cek apakah terlambat (melewati tanggal kembali & masih dipinjam)
                        $terlambat = false;
                        if (!empty($p['tanggal_kembali']) && $p['status'] === 'dipinjam') {
                            $terlambat = strtotime($p['tanggal_kembali']) < strtotime('today');
                        }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_lengkap'] ?? 'Anonim') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                @<?= htmlspecialchars($p['username'] ?? '-') ?>
                            </div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_alat'] ?? '-') ?>
                            <?php if (!empty($p['harga_sewa'])): ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                Rp <?= number_format($p['harga_sewa'], 0, ',', '.') ?>/hari
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['nama_kategori'] ?? '-') ?></td>
                        <td style="text-align: center; font-weight: 600;"><?= (int)($p['jumlah'] ?? 1) ?></td>
                        <td style="white-space: nowrap;"><?= $tglPinjam ?></td>
                        <td style="white-space: nowrap;">
                            <?php if ($terlambat): ?>
                                <span style="color: #ef4444; font-weight: 600;">
                                    <?= $tglKembali ?>
                                    <span style="display:block; font-size:11px; font-weight:400;">⚠ Terlambat</span>
                                </span>
                            <?php else: ?>
                                <?= $tglKembali ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="
                                display: inline-flex;
                                align-items: center;
                                gap: 5px;
                                padding: 4px 10px;
                                border-radius: 999px;
                                font-size: 12px;
                                font-weight: 600;
                                white-space: nowrap;
                                <?= $statusStyle ?>
                            ">
                                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                                <?= $statusLabel ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- =============================================
     SECTION: LOG AKTIVITAS
     ============================================= -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Riwayat Aktivitas</h3>
            <p class="inventory-sub">Total <?= count($daftarLog) ?> rekaman aktivitas sistem.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="log">
            <?php if (!empty($_GET['search_peminjaman'])): ?>
                <input type="hidden" name="search_peminjaman" value="<?= htmlspecialchars($_GET['search_peminjaman']) ?>">
            <?php endif; ?>
            <span class="search-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Cari aktivitas atau nama..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Role</th>
                <th>Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarLog)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada aktivitas yang tercatat.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarLog as $log): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="white-space: nowrap;"><?= htmlspecialchars($log['waktu_format'] ?? $log['waktu']) ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($log['nama_lengkap'] ?? 'Sistem / Anonim') ?>
                            <div style="font-size: 12px; font-weight: 400; color: var(--text-muted);">
                                @<?= htmlspecialchars($log['username'] ?? '-') ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($log['role'])): ?>
                                <span class="badge-role badge-role-<?= htmlspecialchars($log['role']) ?>">
                                    <?= ucfirst(htmlspecialchars($log['role'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge-role badge-role-peminjam">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($log['aktivitas']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once 'Views/admin_footer.php'; ?>
