<?php
$activePage = 'log';
$pageTitle = 'Log Aktivitas Sistem - Pengelola Lab';
require_once __DIR__ . '/admin_header.php';
?>

<div class="admin-header-row">
    <div>
        <span class="admin-date-label">AUDIT SISTEM LABORATORIUM</span>
        <h1 class="admin-title">Log Aktivitas Sistem</h1>
        <p class="admin-subtitle">Catatan rekam jejak aktivitas login, perubahan data inventaris, serta pemantauan alat lab yang sedang aktif dipinjam.</p>
    </div>
</div>

<!-- =============================================
     SECTION: INFO ALAT SEDANG DIPINJAM
     ============================================= -->
<section class="inventory-section-card" style="margin-bottom: 24px;">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title" style="display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Alat Lab Sedang Dipinjam
                <span style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 22px;
                    height: 22px;
                    padding: 0 6px;
                    border-radius: 999px;
                    background: #f59e0b;
                    color: #fff;
                    font-size: 11px;
                    font-weight: 700;
                    line-height: 1;
                "><?= $totalPeminjamanAktif ?></span>
            </h3>
            <p class="inventory-sub">Daftar siswa dan alat komputer lab yang sedang berada di luar ruang inventaris.</p>
        </div>
    </div>

    <table class="inventory-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Siswa</th>
                <th>Alat Lab Dipinjam</th>
                <th>Jml</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPeminjamanAktif)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada alat yang sedang dipinjam saat ini. Semua alat berada di laboratorium.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarPeminjamanAktif as $p): 
                    $tglKembali = !empty($p['tanggal_rencana_kembali']) ? date('d M Y', strtotime($p['tanggal_rencana_kembali'])) : '-';
                    $terlambat = !empty($p['tanggal_rencana_kembali']) && (strtotime($p['tanggal_rencana_kembali']) < strtotime(date('Y-m-d')));
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="col-instrument-name">
                            <span style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--primary-teal);"><?= htmlspecialchars($p['kode_peminjaman'] ?? '-') ?></span><br>
                            <?= htmlspecialchars($p['nama_peminjam'] ?? 'Siswa') ?>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: <?= htmlspecialchars($p['nis'] ?? '-') ?> &bull; <?= htmlspecialchars($p['kelas'] ?? '') ?></div>
                        </td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($p['nama_alat'] ?? '-') ?>
                            <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($p['kode_alat'] ?? '') ?></div>
                        </td>
                        <td><strong><?= (int)($p['jumlah'] ?? 1) ?></strong> unit</td>
                        <td style="white-space: nowrap;"><?= date('d M Y', strtotime($p['tanggal_pinjam'])) ?></td>
                        <td style="white-space: nowrap;">
                            <?php if ($terlambat): ?>
                                <span style="color: #ef4444; font-weight: 700;">
                                    <?= $tglKembali ?>
                                    <span style="display:block; font-size:11px; font-weight:400;">⚠ Terlambat</span>
                                </span>
                            <?php else: ?>
                                <?= $tglKembali ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge badge-ready">
                                <?= ucfirst(htmlspecialchars($p['status'])) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- =============================================
     SECTION: LOG AKTIVITAS SISTEM
     ============================================= -->
<section class="inventory-section-card">
    <div class="inventory-header">
        <div>
            <h3 class="inventory-title">Riwayat Log Aktivitas</h3>
            <p class="inventory-sub">Total <?= count($daftarLog) ?> rekaman aktivitas di sistem inventaris lab.</p>
        </div>
        <form method="GET" action="index.php" class="search-box">
            <input type="hidden" name="c" value="admin">
            <input type="hidden" name="a" value="log">
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
                <th>Keterangan / Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarLog)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Belum ada aktivitas yang tercatat.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($daftarLog as $log): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="white-space: nowrap; font-size: 12px; color: var(--text-muted);"><?= date('d M Y H:i', strtotime($log['waktu'])) ?></td>
                        <td class="col-instrument-name">
                            <?= htmlspecialchars($log['nama_user'] ?? 'Sistem') ?>
                            <div style="font-size: 11px; color: var(--text-muted);">@<?= htmlspecialchars($log['username'] ?? '-') ?></div>
                        </td>
                        <td>
                            <span class="badge-role badge-role-<?= htmlspecialchars($log['role'] ?? 'pengelola') ?>">
                                <?= ucfirst(htmlspecialchars($log['role'] ?? '-')) ?>
                            </span>
                        </td>
                        <td><strong><?= htmlspecialchars($log['aktivitas']) ?></strong></td>
                        <td style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($log['deskripsi'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
