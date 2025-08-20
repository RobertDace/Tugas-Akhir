<?php
include 'template_header.php';
require_once '../config.php';

// Ambil ID siswa dari session wali murid yang login
$id_siswa = $_SESSION['id_siswa'] ?? 0;
?>

<div class="admin-content-header">
    <h2>Riwayat Kehadiran Ananda</h2>
    <p>Berikut adalah catatan kehadiran ananda di sekolah.</p>
</div>

<div class="admin-content-body">
    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT tanggal_absen, status_kehadiran FROM absensi WHERE id_siswa = ? ORDER BY tanggal_absen DESC");
                $stmt->bind_param("i", $id_siswa);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Menentukan class CSS berdasarkan status
                        $status_class = 'status-' . strtolower($row['status_kehadiran']);
                ?>
                        <tr>
                            <td><?php echo date('l, d F Y', strtotime($row['tanggal_absen'])); ?></td>
                            <td><span class="status-label <?php echo $status_class; ?>"><?php echo htmlspecialchars($row['status_kehadiran']); ?></span></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='2' style='text-align:center;'>Belum ada data absensi untuk ananda.</td></tr>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'template_footer.php'; ?>