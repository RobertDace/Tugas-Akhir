<?php
include 'template_header.php';
require_once '../config.php';

// Inisialisasi variabel
$nama_guru = ''; $username = ''; $id_kelas_terpilih = ''; $nip = ''; $jabatan = ''; $status_kepegawaian = 'Honorer';
$id_guru = 0; $page_title = 'Tambah Guru Baru';

// Cek jika ini mode edit
if (isset($_GET['id'])) {
    $id_guru = (int)$_GET['id'];
    $page_title = 'Edit Data Guru';
    $stmt = $conn->prepare("SELECT * FROM guru WHERE id_guru = ?");
    $stmt->bind_param("i", $id_guru);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $nama_guru = $data['nama_guru'];
        $username = $data['username'];
        $id_kelas_terpilih = $data['id_kelas'];
        $nip = $data['nip'];
        $jabatan = $data['jabatan'];
        $status_kepegawaian = $data['status_kepegawaian'];
    }
    $stmt->close();
}
?>

<div class="admin-content-header">
    <h2><?php echo $page_title; ?></h2>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_guru.php" method="POST">
            <input type="hidden" name="id_guru" value="<?php echo $id_guru; ?>">
            
            <div class="form-group"><label for="nama_guru">Nama Lengkap Guru</label><input type="text" name="nama_guru" id="nama_guru" value="<?php echo htmlspecialchars($nama_guru); ?>" required></div>
            <div class="form-group"><label for="nip">NIP / No. Induk</label><input type="text" name="nip" id="nip" value="<?php echo htmlspecialchars($nip); ?>"></div>
            <div class="form-group"><label for="jabatan">Jabatan</label><input type="text" name="jabatan" id="jabatan" value="<?php echo htmlspecialchars($jabatan); ?>"></div>
            <div class="form-group">
                <label for="status_kepegawaian">Status Kepegawaian</label>
                <select name="status_kepegawaian" id="status_kepegawaian" required>
                    <option value="Honorer" <?php if($status_kepegawaian == 'Honorer') echo 'selected'; ?>>Honorer</option>
                    <option value="Tetap" <?php if($status_kepegawaian == 'Tetap') echo 'selected'; ?>>Tetap</option>
                    <option value="PNS" <?php if($status_kepegawaian == 'PNS') echo 'selected'; ?>>PNS</option>
                </select>
            </div>
            <hr>
            <div class="form-group"><label for="username">Username</label><input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required></div>
            <div class="form-group"><label for="password">Password</label><input type="password" name="password" id="password" <?php if($id_guru == 0) echo 'required'; ?>><small>Kosongkan jika tidak ingin mengubah password.</small></div>
            <div class="form-group">
                <label for="id_kelas">Wali Kelas dari</label>
                <select name="id_kelas" id="id_kelas"><option value="">-- Tidak menjadi wali kelas --</option>
                    <?php
                    $result_kelas = $conn->query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas ASC");
                    while($kelas = $result_kelas->fetch_assoc()){
                        $selected = ($kelas['id_kelas'] == $id_kelas_terpilih) ? 'selected' : '';
                        echo "<option value='{$kelas['id_kelas']}' {$selected}>" . htmlspecialchars($kelas['nama_kelas']) . "</option>";
                    } ?>
                </select>
            </div>
            <div class="form-actions">
                <a href="manajemen_guru.php" class="btn-aksi btn-hapus">Batal</a>
                <button type="submit" name="simpan" class="btn-aksi btn-simpan">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
<?php include 'template_footer.php'; ?>