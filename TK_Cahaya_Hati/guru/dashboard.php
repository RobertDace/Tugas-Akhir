<?php
include 'template_header.php';
require_once '../config.php';

$id_guru = $_SESSION['guru_id'];
$tanggal_sekarang = date('Y-m-d');

// 1. Ambil data kelas & nama kelas
$stmt_kelas = $conn->prepare("SELECT k.id_kelas, k.nama_kelas FROM guru g JOIN kelas k ON g.id_kelas = k.id_kelas WHERE g.id_guru = ?");
$stmt_kelas->bind_param("i", $id_guru);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$kelas_info = $result_kelas->fetch_assoc();
$id_kelas = $kelas_info['id_kelas'];
$nama_kelas = $kelas_info['nama_kelas'];
$stmt_kelas->close();

// 2. Ambil data untuk widget statistik
$total_siswa = $conn->query("SELECT count(*) as total FROM calon_siswa WHERE id_kelas = $id_kelas")->fetch_assoc()['total'];
$hadir_hari_ini = $conn->query("SELECT count(*) as total FROM absensi WHERE id_kelas = $id_kelas AND tanggal_absen = '$tanggal_sekarang' AND status_kehadiran = 'Hadir'")->fetch_assoc()['total'];

// 3. Ambil daftar absensi hari ini
$stmt_absensi = $conn->prepare("SELECT cs.nama_lengkap, a.status_kehadiran FROM calon_siswa cs LEFT JOIN absensi a ON cs.id = a.id_siswa AND a.tanggal_absen = ? WHERE cs.id_kelas = ? ORDER BY cs.nama_lengkap ASC");
$stmt_absensi->bind_param("si", $tanggal_sekarang, $id_kelas);
$stmt_absensi->execute();
$result_absensi = $stmt_absensi->get_result();

// 4. Ambil 3 pengumuman terbaru
$result_pengumuman = $conn->query("SELECT judul_pengumuman, tanggal_posting FROM pengumuman_kelas WHERE id_kelas = $id_kelas ORDER BY tanggal_posting DESC LIMIT 3");

// 5. Ambil 3 catatan perkembangan terbaru
$result_catatan = $conn->query("SELECT cp.catatan, cs.nama_lengkap FROM catatan_perkembangan cp JOIN calon_siswa cs ON cp.id_siswa = cs.id WHERE cp.id_guru = $id_guru ORDER BY cp.id_catatan DESC LIMIT 3");
?>

<div class="admin-content-header">
    <h2>Dashboard Guru</h2>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['guru_nama']); ?></strong>!</p>
</div>

<div class="admin-content-body">
    <h4>Ringkasan Kelas Anda: <?php echo htmlspecialchars($nama_kelas); ?></h4>
    <div class="dashboard-widgets">
        <div class="widget-card">
            <div class="widget-icon">👥</div>
            <div class="widget-data">
                <div class="widget-value"><?php echo $total_siswa; ?></div>
                <div class="widget-title">Total Siswa</div>
            </div>
        </div>
        <div class="widget-card">
            <div class="widget-icon">✅</div>
            <div class="widget-data">
                <div class="widget-value"><?php echo $hadir_hari_ini; ?></div>
                <div class="widget-title">Siswa Hadir Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="dashboard-layout">
        <div class="dashboard-main-column">
            <div class="dashboard-panel">
                <h4>Absensi Hari Ini (<?php echo date('d M Y'); ?>)</h4>
                <div class="dashboard-list">
                    <?php
                    if ($result_absensi->num_rows > 0) {
                        while($absen = $result_absensi->fetch_assoc()){
                            $status = $absen['status_kehadiran'] ?? 'Belum Diabsen';
                            $status_class = 'status-' . strtolower($status);
                            echo '<div class="list-item">
                                    <div class="item-title">' . htmlspecialchars($absen['nama_lengkap']) . '</div>
                                    <span class="status-label ' . $status_class . '">' . htmlspecialchars($status) . '</span>
                                  </div>';
                        }
                    } else { echo '<div class="list-item-empty">Belum ada data absensi untuk hari ini.</div>'; }
                    ?>
                </div>
            </div>
            <div class="dashboard-panel">
                <h4>Pengumuman Terbaru</h4>
                <div class="dashboard-list">
                    <?php
                    if ($result_pengumuman->num_rows > 0) {
                        while($pengumuman = $result_pengumuman->fetch_assoc()){
                           echo '<div class="list-item">
                                    <div class="item-title">' . htmlspecialchars($pengumuman['judul_pengumuman']) . '</div>
                                    <span class="item-date">' . date('d M Y', strtotime($pengumuman['tanggal_posting'])) . '</span>
                                  </div>';
                        }
                    } else { echo '<div class="list-item-empty">Belum ada pengumuman.</div>'; }
                    ?>
                </div>
            </div>
        </div>
        <div class="dashboard-side-column">
            <div class="dashboard-panel">
                <h4>Catatan Perkembangan Terakhir</h4>
                <div class="dashboard-list simple">
                    <?php
                    if ($result_catatan->num_rows > 0) {
                        while($catatan = $result_catatan->fetch_assoc()){
                           echo '<div class="list-item"><strong>' . htmlspecialchars($catatan['nama_lengkap']) . ':</strong> ' . htmlspecialchars(substr($catatan['catatan'], 0, 70)) . '...</div>';
                        }
                    } else { echo '<div class="list-item-empty">Belum ada catatan.</div>'; }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'template_footer.php'; ?>