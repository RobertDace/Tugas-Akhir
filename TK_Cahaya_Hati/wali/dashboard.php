<?php
include 'template_header.php';
require_once '../config.php';

$id_wali = $_SESSION['wali_id'];
$id_siswa = isset($_SESSION['id_siswa']) ? $_SESSION['id_siswa'] : null;
?>

<div class="admin-content-header">
    <h2>Dashboard Wali Murid</h2>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['wali_nama']); ?></strong>!</p>
</div>

<div class="admin-content-body">
    <?php if (empty($id_siswa)): ?>
        <div class="prompt-pendaftaran">
            <h3>Anda Belum Menautkan Data Ananda</h3>
            <p>Untuk melihat laporan absensi, perkembangan, dan galeri kegiatan, silakan daftarkan data ananda terlebih dahulu.</p>
            <a href="../pendaftaran.php" class="btn-aksi btn-simpan" style="font-size: 1.2rem; padding: 15px 30px; text-decoration: none;">Daftarkan Anak Sekarang</a>
        </div>
    <?php else: ?>
        <?php
        // Ambil data yang sudah ada (kelas, absensi)
        $stmt_kelas = $conn->prepare("SELECT id_kelas FROM calon_siswa WHERE id = ?");
        $stmt_kelas->bind_param("i", $id_siswa);
        $stmt_kelas->execute();
        $data_kelas = $stmt_kelas->get_result()->fetch_assoc();
        $id_kelas = $data_kelas ? $data_kelas['id_kelas'] : 0;
        $stmt_kelas->close();

        $tanggal_sekarang = date('Y-m-d');
        $stmt_absen = $conn->prepare("SELECT status_kehadiran FROM absensi WHERE id_siswa = ? AND tanggal_absen = ?");
        $stmt_absen->bind_param("is", $id_siswa, $tanggal_sekarang);
        $stmt_absen->execute();
        $absen_hari_ini = ($stmt_absen->get_result()->num_rows > 0) ? $stmt_absen->get_result()->fetch_assoc()['status_kehadiran'] : 'Belum Diabsen';
        $stmt_absen->close();

        // KODE BARU: Ambil 3 catatan perkembangan terakhir
        $stmt_perkembangan = $conn->prepare("SELECT tanggal_catatan, aspek_perkembangan, catatan FROM catatan_perkembangan WHERE id_siswa = ? ORDER BY tanggal_catatan DESC LIMIT 3");
        $stmt_perkembangan->bind_param("i", $id_siswa);
        $stmt_perkembangan->execute();
        $result_perkembangan = $stmt_perkembangan->get_result();
        ?>
        <h4>Ringkasan Cepat Ananda</h4>
        <div class="dashboard-widgets">
            <div class="widget-card">
                <div class="widget-icon">✅</div>
                <div class="widget-data">
                    <div class="widget-value"><?php echo htmlspecialchars($absen_hari_ini); ?></div>
                    <div class="widget-title">Absensi Hari Ini</div>
                </div>
            </div>
        </div>

        <div class="dashboard-layout">
            <div class="dashboard-main-column">
                <div class="dashboard-panel">
                    <h4>Catatan Perkembangan Terbaru</h4>
                    <div class="dashboard-list simple">
                        <?php
                        if ($result_perkembangan->num_rows > 0) {
                            while($catatan = $result_perkembangan->fetch_assoc()){
                               echo '<div class="list-item">
                                        <div class="item-title">' . htmlspecialchars($catatan['aspek_perkembangan']) . ' - <small>(' . date('d M Y', strtotime($catatan['tanggal_catatan'])) . ')</small></div>
                                        <p style="margin: 0; color: #555;">' . htmlspecialchars(substr($catatan['catatan'], 0, 100)) . '...</p>
                                      </div>';
                            }
                        } else {
                            echo '<div class="list-item-empty">Belum ada catatan perkembangan.</div>';
                        }
                        ?>
                        <a href="lihat_perkembangan.php" style="display: block; text-align: right; margin-top: 1rem; font-weight: bold;">Lihat Semua Catatan...</a>
                    </div>
                </div>

                <div class="dashboard-panel">
                    <h4>Pengumuman Kelas Terbaru</h4>
                    <div class="dashboard-list">
                         <?php
                        $result_pengumuman = $conn->query("SELECT judul_pengumuman, tanggal_posting FROM pengumuman_kelas WHERE id_kelas = $id_kelas ORDER BY tanggal_posting DESC LIMIT 3");
                        if ($result_pengumuman && $result_pengumuman->num_rows > 0) {
                            while($pengumuman = $result_pengumuman->fetch_assoc()){
                               echo '<div class="list-item">
                                        <div class="item-title">' . htmlspecialchars($pengumuman['judul_pengumuman']) . '</div>
                                        <span class="item-date">' . date('d M Y', strtotime($pengumuman['tanggal_posting'])) . '</span>
                                      </div>';
                            }
                        } else {
                            echo '<div class="list-item-empty">Belum ada pengumuman.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="dashboard-side-column">
                <div class="dashboard-panel">
                    <h4>Galeri Foto Terbaru</h4>
                    <div class="dashboard-gallery-preview">
                        <?php
                        $result_galeri = $conn->query("SELECT nama_file, judul_foto FROM galeri_kelas WHERE id_kelas = $id_kelas ORDER BY tanggal_unggah DESC LIMIT 4");
                        if ($result_galeri && $result_galeri->num_rows > 0) {
                            while($foto = $result_galeri->fetch_assoc()) {
                                echo '<img src="../uploads/' . htmlspecialchars($foto['nama_file']) . '" alt="' . htmlspecialchars($foto['judul_foto']) . '">';
                            }
                        } else {
                            echo '<div class="list-item-empty">Belum ada foto.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'template_footer.php'; ?>