<?php
include 'template_header.php';
require_once '../config.php';

$id_siswa = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_siswa == 0) {
    echo "ID Siswa tidak valid."; exit();
}

// Ambil data siswa
$stmt_siswa = $conn->prepare("SELECT nama_lengkap FROM calon_siswa WHERE id = ?");
$stmt_siswa->bind_param("i", $id_siswa);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
if ($result_siswa->num_rows == 0) {
    echo "Data siswa tidak ditemukan."; exit();
}
$siswa = $result_siswa->fetch_assoc();
$stmt_siswa->close();
?>

<div class="admin-content-header">
    <h2>Detail Perkembangan: <?php echo htmlspecialchars($siswa['nama_lengkap']); ?></h2>
    <p>Tambahkan catatan perkembangan baru atau lihat riwayat di bawah.</p>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <h3>Form Tambah Catatan Baru</h3>
        <form action="proses_catatan.php" method="POST">
            <input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">
            <input type="hidden" name="id_guru" value="<?php echo $_SESSION['guru_id']; ?>">
            
            <div class="form-group">
                <label for="aspek_perkembangan">Aspek Perkembangan</label>
                <select name="aspek_perkembangan" id="aspek_perkembangan" required>
                    <option value="Sosial-Emosional">Sosial-Emosional</option>
                    <option value="Kognitif">Kognitif</option>
                    <option value="Motorik Halus">Motorik Halus</option>
                    <option value="Motorik Kasar">Motorik Kasar</option>
                    <option value="Seni">Seni</option>
                    <option value="Bahasa">Bahasa</option>
                </select>
            </div>
            <div class="form-group">
                <label for="catatan">Catatan Observasi</label>
                <textarea name="catatan" id="catatan" rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="simpan_catatan" class="btn-aksi btn-simpan">Simpan Catatan</button>
            </div>
        </form>
    </div>

    <div class="table-container" style="margin-top: 2rem;">
        <h3>Riwayat Catatan Perkembangan</h3>
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 25%;">Aspek Perkembangan</th>
                    <th>Catatan dari Guru</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt_catatan = $conn->prepare("SELECT tanggal_catatan, aspek_perkembangan, catatan FROM catatan_perkembangan WHERE id_siswa = ? ORDER BY tanggal_catatan DESC");
                $stmt_catatan->bind_param("i", $id_siswa);
                $stmt_catatan->execute();
                $result_catatan = $stmt_catatan->get_result();

                if ($result_catatan->num_rows > 0) {
                    while($catatan = $result_catatan->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo date('d F Y', strtotime($catatan['tanggal_catatan'])); ?></td>
                        <td><?php echo htmlspecialchars($catatan['aspek_perkembangan']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($catatan['catatan'])); ?></td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center;'>Belum ada catatan perkembangan untuk siswa ini.</td></tr>";
                }
                $stmt_catatan->close();
                ?>
            </tbody>
        </table>
    </div>
    </div>

<?php include 'template_footer.php'; ?>