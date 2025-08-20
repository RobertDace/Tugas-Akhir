<?php
include 'template_header.php';
require_once '../config.php';

// Ambil ID kelas dari guru yang login
$id_guru = $_SESSION['guru_id'];
$query_kelas = $conn->prepare("SELECT id_kelas FROM guru WHERE id_guru = ?");
$query_kelas->bind_param("i", $id_guru);
$query_kelas->execute();
$result_kelas = $query_kelas->get_result();
$data_kelas = $result_kelas->fetch_assoc();
$id_kelas = $data_kelas['id_kelas'];
$query_kelas->close();
?>

<div class="admin-content-header">
    <h2>Catatan Perkembangan Siswa</h2>
    <p>Pilih siswa untuk melihat atau menambahkan catatan perkembangan.</p>
</div>

<div class="admin-content-body">
    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
    <?php
    $stmt_siswa = $conn->prepare("SELECT id, nama_lengkap FROM calon_siswa WHERE id_kelas = ? ORDER BY nama_lengkap ASC");
    $stmt_siswa->bind_param("i", $id_kelas);
    $stmt_siswa->execute();
    $result_siswa = $stmt_siswa->get_result();
    
    if ($result_siswa->num_rows > 0) {
        $no = 1;
        while($siswa = $result_siswa->fetch_assoc()) {
    ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($siswa['nama_lengkap']); ?></td>
                <td>
                    <a href="detail_perkembangan.php?id=<?php echo $siswa['id']; ?>" class="btn-aksi btn-info">Catatan</a>
                    <a href="edit_siswa.php?id=<?php echo $siswa['id']; ?>" class="btn-aksi btn-detail">Pindah Kelas</a>
                    <a href="hapus_siswa_dari_kelas.php?id=<?php echo $siswa['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin mengeluarkan siswa ini dari kelas?');">Keluarkan</a>
                </td>
            </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='3' style='text-align:center;'>Belum ada siswa di kelas ini.</td></tr>";
    }
    $stmt_siswa->close();
    ?>
</tbody>
        </table>
    </div>
</div>

<?php
include 'template_footer.php';
?>