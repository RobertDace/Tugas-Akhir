<?php
include 'template_header.php';
require_once '../config.php';

$id_admin = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT username, foto_profil FROM admin WHERE id = ?");
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<div class="admin-content-header">
    <h2>Profil Saya</h2>
</div>
<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_profil.php" method="POST" enctype="multipart/form-data">
            <div class="profile-picture-preview">
                <img src="../uploads/profil/<?php echo htmlspecialchars($admin['foto_profil']); ?>" alt="Foto Profil" id="preview_img">
            </div>
            <div class="form-group">
                <label for="foto_profil">Ganti Foto Profil</label>
                <input type="file" name="foto_profil" id="foto_profil" accept="image/png, image/jpeg">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
            </div>
            <div class="form-actions">
                <button type="submit" name="simpan_profil" class="btn-aksi btn-simpan">Simpan Perubahan</button>
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