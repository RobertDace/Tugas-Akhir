<?php
include 'template_header.php';
require_once '../config.php';
?>

<div class="admin-content-header">
    <h2>Manajemen Akun Wali Murid</h2>
    <p>Kelola akun login untuk orang tua atau wali siswa.</p>
</div>

<div class="admin-content-body">
    <div class="action-bar">
        <a href="form_wali.php" class="btn-aksi btn-simpan">+ Tambah Akun Wali Murid</a>
    </div>

    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Wali</th>
                    <th>Username</th>
                    <th>Hubungan</th>
                    <th>Wali dari Siswa</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT w.id_wali, w.nama_wali, w.username, w.hubungan, s.nama_lengkap as nama_siswa 
                        FROM wali_murid w
                        JOIN calon_siswa s ON w.id_siswa = s.id
                        ORDER BY w.nama_wali ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_wali']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['hubungan']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                            <td>
                                <a href="form_wali.php?id=<?php echo $row['id_wali']; ?>" class="btn-aksi btn-detail">Edit</a>
                                <a href="hapus_wali.php?id=<?php echo $row['id_wali']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin menghapus akun ini?');">Hapus</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data akun wali murid.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php $conn->close(); include 'template_footer.php'; ?>