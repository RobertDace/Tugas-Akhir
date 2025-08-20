<?php
include 'template_header.php';
require_once '../config.php';

$id_guru = $_SESSION['guru_id'];
?>

<div class="admin-content-header">
    <h2>Manajemen Artikel (Blog)</h2>
    <p>Tulis, edit, atau hapus artikel untuk ditampilkan di halaman blog publik.</p>
</div>

<div class="admin-content-body">
    <div class="action-bar">
        <a href="form_artikel.php" class="btn-aksi btn-simpan">+ Tulis Artikel Baru</a>
    </div>

    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Judul Artikel</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT id_artikel, judul_artikel, status_publikasi, tanggal_dibuat FROM artikel WHERE id_guru = ? ORDER BY tanggal_dibuat DESC");
                $stmt->bind_param("i", $id_guru);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status_class = $row['status_publikasi'] == 'published' ? 'status-hadir' : 'status-sakit';
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['judul_artikel']); ?></td>
                            <td><span class="status-label <?php echo $status_class; ?>"><?php echo ucfirst($row['status_publikasi']); ?></span></td>
                            <td><?php echo date('d M Y', strtotime($row['tanggal_dibuat'])); ?></td>
                            <td>
                                <a href="form_artikel.php?id=<?php echo $row['id_artikel']; ?>" class="btn-aksi btn-detail">Edit</a>
                                <a href="hapus_artikel.php?id=<?php echo $row['id_artikel']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin menghapus artikel ini?');">Hapus</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Anda belum menulis artikel apapun.</td></tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'template_footer.php'; ?>