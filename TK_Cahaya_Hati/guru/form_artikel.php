<?php
include 'template_header.php';
require_once '../config.php';

$id_guru = $_SESSION['guru_id'];
$id_artikel = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page_title = 'Tulis Artikel Baru';

// Inisialisasi variabel untuk form
$judul_artikel = '';
$isi_artikel = '';
$status_publikasi = 'draft';
$gambar_sekarang = 'default-artikel.png';

// Jika ini mode edit, ambil data dari database
if ($id_artikel > 0) {
    $page_title = 'Edit Artikel';
    $stmt = $conn->prepare("SELECT judul_artikel, isi_artikel, gambar_header, status_publikasi FROM artikel WHERE id_artikel = ? AND id_guru = ?");
    $stmt->bind_param("ii", $id_artikel, $id_guru);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $artikel = $result->fetch_assoc();
        $judul_artikel = $artikel['judul_artikel'];
        $isi_artikel = $artikel['isi_artikel'];
        $status_publikasi = $artikel['status_publikasi'];
        $gambar_sekarang = $artikel['gambar_header'] ?? 'default-artikel.png';
    } else {
        // Jika artikel tidak ditemukan atau bukan milik guru ini, redirect
        header("Location: manajemen_artikel.php");
        exit();
    }
    $stmt->close();
}
?>

<div class="admin-content-header">
    <h2><?php echo $page_title; ?></h2>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_artikel.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_artikel" value="<?php echo $id_artikel; ?>">
            <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">

            <div class="form-group">
                <label for="judul_artikel">Judul Artikel</label>
                <input type="text" name="judul_artikel" id="judul_artikel" value="<?php echo htmlspecialchars($judul_artikel); ?>" required>
            </div>

            <div class="form-group">
                <label for="id_kategori">Kategori Artikel</label>
                <select name="id_kategori" id="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $result_kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($kategori = $result_kategori->fetch_assoc()){
                        // Cek jika ini mode edit dan cocokkan dengan kategori yang tersimpan
                        $selected = (isset($artikel) && $artikel['id_kategori'] == $kategori['id_kategori']) ? 'selected' : '';
                        echo "<option value='{$kategori['id_kategori']}' {$selected}>" . htmlspecialchars($kategori['nama_kategori']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="isi_artikel">Isi Artikel</label>
                <textarea name="isi_artikel" id="isi_artikel" rows="15" required><?php echo htmlspecialchars($isi_artikel); ?></textarea>
            </div>

            <div class="form-group">
                <label>Gambar Header Saat Ini</label>
                <div class="profile-picture-preview">
                    <img src="../uploads/artikel/<?php echo htmlspecialchars($gambar_sekarang); ?>" alt="Gambar Header" id="preview_img">
                </div>
            </div>

            <div class="form-group">
                <label for="gambar_header">Ganti Gambar Header</label>
                <input type="file" name="gambar_header" id="gambar_header" accept="image/png, image/jpeg">
                <small>Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>

            <div class="form-group">
                <label for="status_publikasi">Status</label>
                <select name="status_publikasi" id="status_publikasi">
                    <option value="draft" <?php if($status_publikasi == 'draft') echo 'selected'; ?>>Simpan sebagai Draft</option>
                    <option value="published" <?php if($status_publikasi == 'published') echo 'selected'; ?>>Publikasikan</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="manajemen_artikel.php" class="btn-aksi btn-hapus">Batal</a>
                <button type="submit" name="simpan_artikel" class="btn-aksi btn-simpan">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>

<script>
// Script untuk preview gambar
document.getElementById('gambar_header').onchange = evt => {
    const [file] = evt.target.files;
    if (file) {
        document.getElementById('preview_img').src = URL.createObjectURL(file);
    }
}
</script>

<?php include 'template_footer.php'; ?>