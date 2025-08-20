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
    <h2>Galeri Foto Kegiatan Kelas</h2>
    <p>Dokumentasi kegiatan belajar dan bermain ananda di kelas.</p>
</div>

<div class="admin-content-body">
    <div class="gallery-view-container">
        <div class="photo-gallery">
            <?php
            // Ambil semua foto dari galeri berdasarkan id_kelas
            $stmt_foto = $conn->prepare("SELECT nama_file, judul_foto FROM galeri_kelas WHERE id_kelas = ? ORDER BY tanggal_unggah DESC");
            $stmt_foto->bind_param("i", $id_kelas);
            $stmt_foto->execute();
            $result_foto = $stmt_foto->get_result();

            if ($result_foto->num_rows > 0) {
                while($foto = $result_foto->fetch_assoc()) {
            ?>
                <div class="photo-card">
                    <img src="../uploads/<?php echo htmlspecialchars($foto['nama_file']); ?>" alt="<?php echo htmlspecialchars($foto['judul_foto']); ?>">
                    <div class="photo-title"><?php echo htmlspecialchars($foto['judul_foto']); ?></div>
                </div>
            <?php
                }
            } else {
                echo "<p>Guru kelas belum mengunggah foto kegiatan apa pun.</p>";
            }
            $stmt_foto->close();
            $conn->close();
            ?>
        </div>
    </div>
</div>

<?php include 'template_footer.php'; ?>