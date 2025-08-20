<?php
include 'template_header.php';
require_once '../config.php';

$id_guru = $_SESSION['guru_id'];
$stmt = $conn->prepare("SELECT * FROM guru WHERE id_guru = ?");
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$guru = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<div class="admin-content-header">
    <h2>Profil Saya</h2>
    <p>Informasi data kepegawaian dan akun Anda.</p>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_profil.php" method="POST" enctype="multipart/form-data">
            <div class="profile-picture-preview">
                <img src="../uploads/profil/<?php echo htmlspecialchars($guru['foto_profil']); ?>" alt="Foto Profil" id="preview_img">
            </div>
            <div class="form-group">
                <label for="foto_profil">Ganti Foto Profil</label>
                <input type="file" name="foto_profil" id="foto_profil" accept="image/png, image/jpeg">
            </div>
            <hr>
            <div class="form-group"><label>Nama Lengkap</label><input type="text" value="<?php echo htmlspecialchars($guru['nama_guru']); ?>" disabled></div>
            <div class="form-group"><label>NIP / No. Induk</label><input type="text" value="<?php echo htmlspecialchars($guru['nip']); ?>" disabled></div>
            <div class="form-group"><label>Jabatan</label><input type="text" value="<?php echo htmlspecialchars($guru['jabatan']); ?>" disabled></div>
            <div class="form-group"><label>Status</label><input type="text" value="<?php echo htmlspecialchars($guru['status_kepegawaian']); ?>" disabled></div>
            <div class="form-group"><label>Username</label><input type="text" value="<?php echo htmlspecialchars($guru['username']); ?>" disabled></div>
            <div class="form-actions">
                <button type="submit" name="simpan_profil" class="btn-aksi btn-simpan">Simpan Foto</button>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('foto_profil').onchange = evt => {
    const [file] = evt.target.files;
    if (file) {
        document.getElementById('preview_img').src = URL.createObjectURL(file);
    }
}
</script>
<?php include 'template_footer.php'; ?>