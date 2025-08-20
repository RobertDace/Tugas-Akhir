<?php
include 'template_header.php';
require_once '../config.php';

$id_guru = $_SESSION['guru_id'];
// Ambil id_kelas guru
$stmt_kelas = $conn->prepare("SELECT id_kelas FROM guru WHERE id_guru = ?");
$stmt_kelas->bind_param("i", $id_guru);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$data_kelas = $result_kelas->fetch_assoc();
$id_kelas = $data_kelas['id_kelas'];
$stmt_kelas->close();
?>

<div class="admin-content-header">
    <h2>Papan Pengumuman Kelas</h2>
    <p>Kelola semua pengumuman untuk kelas Anda.</p>
</div>

<div class="admin-content-body">
    <div class="action-bar">
        <a href="form_pengumuman.php" class="btn-aksi btn-simpan">+ Buat Pengumuman Baru</a>
    </div>

    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Judul Pengumuman</th>
                    <th>Tanggal Posting</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT id_pengumuman, judul_pengumuman, tanggal_posting FROM pengumuman_kelas WHERE id_kelas = ? ORDER BY tanggal_posting DESC");
                $stmt->bind_param("i", $id_kelas);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['judul_pengumuman']); ?></td>
                            <td><?php echo date('d F Y, H:i', strtotime($row['tanggal_posting'])); ?></td>
                            <td>
                                <a href="form_pengumuman.php?id=<?php echo $row['id_pengumuman']; ?>" class="btn-aksi btn-detail">Edit</a>
                                <a href="hapus_pengumuman.php?id=<?php echo $row['id_pengumuman']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin menghapus pengumuman ini?');">Hapus</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center;'>Belum ada pengumuman.</td></tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'template_footer.php'; ?>