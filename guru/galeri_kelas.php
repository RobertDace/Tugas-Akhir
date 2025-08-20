<?php
include 'template_header.php';
require_once '../config.php';

// Ambil ID kelas dari guru yang login
$id_guru = $_SESSION['guru_id'];
$stmt_kelas = $conn->prepare("SELECT id_kelas FROM guru WHERE id_guru = ?");
$stmt_kelas->bind_param("i", $id_guru);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$data_kelas = $result_kelas->fetch_assoc();
$id_kelas = $data_kelas['id_kelas'];
$stmt_kelas->close();
?>

<div class="admin-content-header">
    <h2>Galeri Foto Kelas</h2>
    <p>Unggah foto kegiatan baru atau kelola foto yang sudah ada.</p>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <h3>Unggah Foto Baru</h3>
        <form action="proses_upload.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
            <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">
            <div class="form-group">
                <label for="judul_foto">Judul Foto</label>
                <input type="text" name="judul_foto" id="judul_foto" required>
            </div>
            <div class="form-group">
                <label for="foto_kegiatan">Pilih File Gambar (JPG, PNG, GIF)</label>
                <input type="file" name="foto_kegiatan" id="foto_kegiatan" accept="image/png, image/jpeg, image/gif" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="upload" class="btn-aksi btn-simpan">Unggah Foto</button>
            </div>
        </form>
    </div>

    <div class="gallery-view-container">
        <h3>Foto yang Sudah Diunggah</h3>
        <div class="photo-gallery">
            <?php
            $stmt_foto = $conn->prepare("SELECT id_foto, nama_file, judul_foto, is_favorit FROM galeri_kelas WHERE id_kelas = ? ORDER BY tanggal_unggah DESC");
            $stmt_foto->bind_param("i", $id_kelas);
            $stmt_foto->execute();
            $result_foto = $stmt_foto->get_result();

            if ($result_foto->num_rows > 0) {
                while($foto = $result_foto->fetch_assoc()) {
            ?>
                <div class="photo-card">
                    <?php if($foto['is_favorit'] == 1): ?>
                        <div class="favorite-star" title="Foto Favorit">★</div>
                    <?php endif; ?>

                    <img src="../uploads/<?php echo htmlspecialchars($foto['nama_file']); ?>" alt="<?php echo htmlspecialchars($foto['judul_foto']); ?>">
                    <div class="photo-title"><?php echo htmlspecialchars($foto['judul_foto']); ?></div>
                    <div class="photo-actions">
                        <?php if($foto['is_favorit'] == 0): ?>
                            <a href="proses_favorit.php?id=<?php echo $foto['id_foto']; ?>&action=set" class="btn-aksi btn-info">Jadikan Favorit</a>
                        <?php else: ?>
                            <a href="proses_favorit.php?id=<?php echo $foto['id_foto']; ?>&action=unset" class="btn-aksi btn-detail">Hapus Favorit</a>
                        <?php endif; ?>
                        <a href="hapus_foto.php?id=<?php echo $foto['id_foto']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin menghapus foto ini?');">Hapus</a>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p>Belum ada foto yang diunggah untuk kelas ini.</p>";
            }
            $stmt_foto->close();
            ?>
        </div>
    </div>
</div>

<?php include 'template_footer.php'; ?>