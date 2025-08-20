<?php
include 'template_header.php';
require_once '../config.php';

// Ambil ID siswa dari session
$id_siswa = $_SESSION['id_siswa'] ?? 0;

// Cari tahu ID kelas dari siswa tersebut
$stmt_kelas = $conn->prepare("SELECT id_kelas FROM calon_siswa WHERE id = ?");
$stmt_kelas->bind_param("i", $id_siswa);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$data_kelas = $result_kelas->fetch_assoc();
$id_kelas = $data_kelas['id_kelas'];
$stmt_kelas->close();
?>

<div class="admin-content-header">
    <h2>Papan Pengumuman Kelas</h2>
    <p>Informasi dan pengumuman penting dari guru kelas.</p>
</div>

<div class="admin-content-body">
    <div class="announcement-container">
        <?php
        // Ambil semua pengumuman berdasarkan id_kelas
        $stmt_pengumuman = $conn->prepare("SELECT judul_pengumuman, isi_pengumuman, tanggal_posting FROM pengumuman_kelas WHERE id_kelas = ? ORDER BY tanggal_posting DESC");
        $stmt_pengumuman->bind_param("i", $id_kelas);
        $stmt_pengumuman->execute();
        $result_pengumuman = $stmt_pengumuman->get_result();

        if ($result_pengumuman->num_rows > 0) {
            while($pengumuman = $result_pengumuman->fetch_assoc()) {
        ?>
            <div class="announcement-item">
                <h3><?php echo htmlspecialchars($pengumuman['judul_pengumuman']); ?></h3>
                <span class="announcement-date"><?php echo date('l, d F Y', strtotime($pengumuman['tanggal_posting'])); ?></span>
                <div class="announcement-content">
                    <?php echo nl2br(htmlspecialchars($pengumuman['isi_pengumuman'])); ?>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p>Belum ada pengumuman untuk kelas ini.</p>";
        }
        $stmt_pengumuman->close();
        $conn->close();
        ?>
    </div>
</div>

<?php include 'template_footer.php'; ?>