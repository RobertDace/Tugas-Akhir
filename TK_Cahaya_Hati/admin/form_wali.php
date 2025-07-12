<?php
include 'template_header.php';
require_once '../config.php';

// Inisialisasi variabel
$nama_wali = '';
$username = '';
$hubungan = '';
$id_siswa_terpilih = '';
$id_wali = 0;
$page_title = 'Tambah Akun Wali Murid';

// Cek jika ini mode edit
if (isset($_GET['id'])) {
    $id_wali = (int)$_GET['id'];
    $page_title = 'Edit Akun Wali Murid';
    $stmt = $conn->prepare("SELECT nama_wali, username, hubungan, id_siswa FROM wali_murid WHERE id_wali = ?");
    $stmt->bind_param("i", $id_wali);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $nama_wali = $data['nama_wali'];
        $username = $data['username'];
        $hubungan = $data['hubungan'];
        $id_siswa_terpilih = $data['id_siswa'];
    }
    $stmt->close();
}
?>

<div class="admin-content-header">
    <h2><?php echo $page_title; ?></h2>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_wali.php" method="POST">
            <input type="hidden" name="id_wali" value="<?php echo $id_wali; ?>">
            
            <div class="form-group">
                <label for="id_siswa">Akun untuk Siswa</label>
                <select name="id_siswa" required>
                    <option value="">-- Pilih Siswa --</option>
                    <?php
                    $result_siswa = $conn->query("SELECT id, nama_lengkap FROM calon_siswa ORDER BY nama_lengkap ASC");
                    while($siswa = $result_siswa->fetch_assoc()){
                        $selected = ($siswa['id'] == $id_siswa_terpilih) ? 'selected' : '';
                        echo "<option value='{$siswa['id']}' {$selected}>" . htmlspecialchars($siswa['nama_lengkap']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nama_wali">Nama Lengkap Wali</label>
                <input type="text" name="nama_wali" value="<?php echo htmlspecialchars($nama_wali); ?>" required>
            </div>
             <div class="form-group">
                <label for="hubungan">Hubungan dengan Siswa</label>
                <select name="hubungan" required>
                    <option value="">-- Pilih Hubungan --</option>
                    <option value="Ayah" <?php if($hubungan == 'Ayah') echo 'selected'; ?>>Ayah</option>
                    <option value="Ibu" <?php if($hubungan == 'Ibu') echo 'selected'; ?>>Ibu</option>
                    <option value="Wali" <?php if($hubungan == 'Wali') echo 'selected'; ?>>Wali</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" <?php if($id_wali == 0) echo 'required'; ?>>
                <?php if($id_wali > 0): ?>
                    <small>Kosongkan jika tidak ingin mengubah password.</small>
                <?php endif; ?>
            </div>
            <div class="form-actions">
                 <a href="manajemen_wali.php" class="btn-back">Batal</a>
                <button type="submit" name="simpan" class="btn-aksi btn-simpan">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php include 'template_footer.php'; ?>