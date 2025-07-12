<?php 
include 'template_header.php'; 
require_once '../config.php';

// Ambil data statistik
$total_siswa = $conn->query("SELECT count(*) as total FROM calon_siswa")->fetch_assoc()['total'];
$total_guru = $conn->query("SELECT count(*) as total FROM guru")->fetch_assoc()['total'];
$total_kelas = $conn->query("SELECT count(*) as total FROM kelas")->fetch_assoc()['total'];

// Ambil 5 pendaftar terbaru
$result_pendaftar = $conn->query("SELECT nama_lengkap, tanggal_pendaftaran FROM calon_siswa ORDER BY tanggal_pendaftaran DESC LIMIT 5");
?>

<div class="admin-content-header">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>!</p>
</div>

<div class="admin-content-body">
    <div class="dashboard-widgets">
        <div class="widget-card">
            <div class="widget-icon">👤</div>
            <div class="widget-data">
                <div class="widget-value"><?php echo $total_siswa; ?></div>
                <div class="widget-title">Total Siswa</div>
            </div>
        </div>
        <div class="widget-card">
            <div class="widget-icon">👩‍🏫</div>
            <div class="widget-data">
                <div class="widget-value"><?php echo $total_guru; ?></div>
                <div class="widget-title">Total Guru</div>
            </div>
        </div>
        <div class="widget-card">
            <div class="widget-icon">🏫</div>
            <div class="widget-data">
                <div class="widget-value"><?php echo $total_kelas; ?></div>
                <div class="widget-title">Total Kelas</div>
            </div>
        </div>
    </div>

    <div class="dashboard-layout">
        <div class="dashboard-main-column">
            <div class="dashboard-panel">
                <h4>Pendaftar Terbaru</h4>
                <div class="dashboard-list">
                    <?php
                    if ($result_pendaftar->num_rows > 0) {
                        while($pendaftar = $result_pendaftar->fetch_assoc()){
                            echo '<div class="list-item">
                                    <div class="item-title">' . htmlspecialchars($pendaftar['nama_lengkap']) . '</div>
                                    <span class="item-date">' . date('d M Y', strtotime($pendaftar['tanggal_pendaftaran'])) . '</span>
                                  </div>';
                        }
                    } else {
                        echo '<div class="list-item-empty">Belum ada pendaftar baru.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="dashboard-side-column">
            </div>
    </div>
</div>

<?php 
include 'template_footer.php'; 
?>