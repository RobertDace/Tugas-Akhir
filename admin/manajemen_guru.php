<?php
include 'template_header.php';
require_once '../config.php';
?>

<div class="admin-content-header">
    <h2>Manajemen Data Guru</h2>
    <p>Kelola akun dan data guru yang terdaftar di sistem.</p>
</div>

<div class="admin-content-body">
    <div class="action-bar">
        <a href="form_guru.php" class="btn-aksi btn-simpan">+ Tambah Guru Baru</a>
    </div>

    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Username</th>
                    <th>Wali Kelas dari</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT g.id_guru, g.nama_guru, g.username, k.nama_kelas 
                        FROM guru g 
                        LEFT JOIN kelas k ON g.id_kelas = k.id_kelas 
                        ORDER BY g.nama_guru ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_guru']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_kelas'] ?? 'Belum ditentukan'); ?></td>
                            <td>
                                <a href="form_guru.php?id=<?php echo $row['id_guru']; ?>" class="btn-aksi btn-detail">Edit</a>
                                <a href="hapus_guru.php?id=<?php echo $row['id_guru']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin menghapus data guru ini?');">Hapus</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data guru.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'template_footer.php'; ?>