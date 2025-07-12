<?php
include 'template_header.php';
require_once '../config.php';

$id_siswa = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM calon_siswa WHERE id = ?");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$siswa = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<div class="admin-content-header">
    <h2>Edit Data Siswa</h2>
</div>
<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_edit_siswa.php" method="POST">
            <input type="hidden" name="id_siswa" value="<?php echo $siswa['id']; ?>">
            <div class="form-group">
                <label>Nama Siswa</label>
                <input type="text" value="<?php echo htmlspecialchars($siswa['nama_lengkap']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="id_kelas">Pindahkan ke Kelas</label>
                <select name="id_kelas" required>
                    <?php
                    $result_kelas = $conn->query("SELECT * FROM kelas");
                    while($kelas = $result_kelas->fetch_assoc()){
                        $selected = ($kelas['id_kelas'] == $siswa['id_kelas']) ? 'selected' : '';
                        echo "<option value='{$kelas['id_kelas']}' {$selected}>" . htmlspecialchars($kelas['nama_kelas']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" name="simpan" class="btn-aksi btn-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php include 'template_footer.php'; ?>