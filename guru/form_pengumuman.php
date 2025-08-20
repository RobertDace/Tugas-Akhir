<?php
include 'template_header.php';
require_once '../config.php';

// Inisialisasi variabel
$judul = '';
$isi = '';
$id_pengumuman = 0;
$form_action = 'proses_pengumuman.php';

// Cek jika ini mode edit
if (isset($_GET['id'])) {
    $id_pengumuman = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT judul_pengumuman, isi_pengumuman FROM pengumuman_kelas WHERE id_pengumuman = ? AND id_guru = ?");
    $stmt->bind_param("ii", $id_pengumuman, $_SESSION['guru_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $judul = $data['judul_pengumuman'];
        $isi = $data['isi_pengumuman'];
    }
    $stmt->close();
}
?>

<div class="admin-content-header">
    <h2><?php echo ($id_pengumuman > 0) ? 'Edit' : 'Buat'; ?> Pengumuman</h2>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="<?php echo $form_action; ?>" method="POST">
            <input type="hidden" name="id_pengumuman" value="<?php echo $id_pengumuman; ?>">
            <input type="hidden" name="id_guru" value="<?php echo $_SESSION['guru_id']; ?>">
            
            <div class="form-group">
                <label for="judul_pengumuman">Judul Pengumuman</label>
                <input type="text" name="judul_pengumuman" id="judul_pengumuman" value="<?php echo htmlspecialchars($judul); ?>" required>
            </div>
            <div class="form-group">
                <label for="isi_pengumuman">Isi Pengumuman</label>
                <textarea name="isi_pengumuman" id="isi_pengumuman" rows="10" required><?php echo htmlspecialchars($isi); ?></textarea>
            </div>
            <div class="form-actions">
                 <a href="pengumuman_kelas.php" class="btn-back">Batal</a>
                <button type="submit" name="simpan" class="btn-aksi btn-simpan">Simpan Pengumuman</button>
            </div>
        </form>
    </div>
</div>

<?php include 'template_footer.php'; ?>