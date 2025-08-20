<?php
include 'template_header.php';
require_once '../config.php';
?>
<div class="admin-content-header">
    <h2>Manajemen Kalender Akademik</h2>
    <p>Kelola acara dan hari libur yang akan ditampilkan di halaman utama.</p>
</div>
<div class="admin-content-body">
    <div class="action-bar">
        <a href="form_kalender.php" class="btn-aksi btn-simpan">+ Tambah Acara Baru</a>
    </div>
    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Acara/Kegiatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Warna</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM kalender_akademik ORDER BY tanggal_mulai DESC");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama_event']) . "</td>";
                        echo "<td>" . date('d M Y', strtotime($row['tanggal_mulai'])) . "</td>";
                        echo "<td>" . ($row['tanggal_selesai'] ? date('d M Y', strtotime($row['tanggal_selesai'])) : '-') . "</td>";
                        echo "<td><span style='color:" . htmlspecialchars($row['warna_event']) . "; font-weight:bold;'>" . ucfirst($row['warna_event']) . "</span></td>";
                        echo "<td>
                                <a href='form_kalender.php?id=" . $row['id_event'] . "' class='btn-aksi btn-detail'>Edit</a>
                                <a href='hapus_kalender.php?id=" . $row['id_event'] . "' class='btn-aksi btn-hapus' onclick='return confirm(\"Yakin ingin menghapus acara ini?\");'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Belum ada acara yang ditambahkan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'template_footer.php'; ?>