<?php
require_once '../config.php';
include 'template_header.php'; // pastikan sesuai header role admin/guru

// Ambil semua testimoni beserta nama wali
$result = $conn->query("
    SELECT t.id_testimoni, t.isi_testimoni, t.status, t.tanggal_kirim, w.nama_wali 
    FROM testimoni t
    JOIN wali_murid w ON t.id_wali = w.id_wali
    ORDER BY t.tanggal_kirim DESC
");
?>
<h2>Manajemen Testimoni Wali Murid</h2>
<table class="table-data">
    <thead>
        <tr>
            <th>Nama Wali</th>
            <th>Testimoni</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_wali']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['isi_testimoni'])) ?></td>
            <td>
                <?= $row['status']=='ditampilkan' ? '<span style="color:green">Ditampilkan</span>' : '<span style="color:gray">Pending</span>' ?>
            </td>
            <td>
                <?php if($row['status']=='pending'): ?>
                    <a href="proses_testimoni_admin.php?id=<?= $row['id_testimoni'] ?>&aksi=show" class="btn-aksi btn-simpan">Tampilkan</a>
                <?php else: ?>
                    <a href="proses_testimoni_admin.php?id=<?= $row['id_testimoni'] ?>&aksi=hide" class="btn-aksi btn-hapus">Sembunyikan</a>
                <?php endif ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'template_footer.php'; ?>
