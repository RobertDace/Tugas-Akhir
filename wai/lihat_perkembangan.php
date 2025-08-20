<?php
include 'template_header.php';
require_once '../config.php';

// Ambil ID siswa dari session wali murid yang login
$id_siswa = $_SESSION['id_siswa'];
?>

<div class="admin-content-header">
    <h2>Catatan Perkembangan Ananda</h2>
    <p>Ini adalah kumpulan catatan observasi dari guru kelas mengenai perkembangan ananda.</p>
</div>

<div class="admin-content-body">
    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">Aspek Perkembangan</th>
                    <th>Catatan dari Guru</th>
                    <th style="width: 20%;">Dicatat oleh</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mengambil catatan perkembangan dan nama guru yang membuat
                $stmt = $conn->prepare("
                    SELECT cp.tanggal_catatan, cp.aspek_perkembangan, cp.catatan, g.nama_guru 
                    FROM catatan_perkembangan cp
                    JOIN guru g ON cp.id_guru = g.id_guru
                    WHERE cp.id_siswa = ? 
                    ORDER BY cp.tanggal_catatan DESC
                ");
                $stmt->bind_param("i", $id_siswa);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($catatan = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo date('d F Y', strtotime($catatan['tanggal_catatan'])); ?></td>
                        <td><?php echo htmlspecialchars($catatan['aspek_perkembangan']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($catatan['catatan'])); ?></td>
                        <td><?php echo htmlspecialchars($catatan['nama_guru']); ?></td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Belum ada catatan perkembangan untuk ananda.</td></tr>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    </div>

<?php include 'template_footer.php'; ?>