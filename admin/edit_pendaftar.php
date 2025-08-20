<?php
include 'template_header.php';
require_once '../config.php';

$id_siswa = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_siswa == 0) {
    die("ID Pendaftar tidak valid.");
}

// Ambil data pendaftar yang akan diedit
$stmt = $conn->prepare("SELECT * FROM calon_siswa WHERE id = ?");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Data pendaftar tidak ditemukan.");
}
$pendaftar = $result->fetch_assoc();
$stmt->close();
?>

<div class="admin-content-header">
    <h2>Edit Data Pendaftar</h2>
    <p>Anda sedang mengedit data untuk ananda <?php echo htmlspecialchars($pendaftar['nama_lengkap']); ?></p>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_edit_pendaftar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $pendaftar['id']; ?>">
            
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($pendaftar['nama_lengkap']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="id_kelas">Tetapkan ke Kelas</label>
                <select name="id_kelas" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php
                    $result_kelas = $conn->query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas");
                    while($kelas = $result_kelas->fetch_assoc()){
                        $selected = ($kelas['id_kelas'] == $pendaftar['id_kelas']) ? 'selected' : '';
                        echo "<option value='{$kelas['id_kelas']}' {$selected}>" . htmlspecialchars($kelas['nama_kelas']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="data_pendaftar.php" class="btn-back">Batal</a>
                <button type="submit" name="simpan" class="btn-aksi btn-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'template_footer.php'; ?>