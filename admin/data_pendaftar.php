<?php
include 'template_header.php';
require_once '../config.php';
?>

<div class="admin-content-header">
    <h2>Persetujuan Pendaftar Baru</h2>
    <p>Proses pendaftar yang masuk untuk ditetapkan ke kelas yang sesuai berdasarkan usia.</p>
</div>

<div class="admin-content-body">
    <div class="table-container">
        <table class="table-data">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Tanggal Lahir</th>
                    <th>Usia (Perkiraan)</th>
                    <th>Nama Orang Tua</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hanya tampilkan siswa dengan status 'pending'
                $sql = "SELECT id, nama_lengkap, tanggal_lahir, nama_orang_tua FROM calon_siswa WHERE status_pendaftaran = 'pending' ORDER BY tanggal_pendaftaran ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Hitung usia
                        $tgl_lahir = new DateTime($row['tanggal_lahir']);
                        $hari_ini = new DateTime('today');
                        $usia = $hari_ini->diff($tgl_lahir)->y;
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['tanggal_lahir'])); ?></td>
                            <td><?php echo $usia; ?> tahun</td>
                            <td><?php echo htmlspecialchars($row['nama_orang_tua']); ?></td>
                            <td>
                                <a href="proses_persetujuan.php?id=<?php echo $row['id']; ?>&action=terima" class="btn-aksi btn-simpan" onclick="return confirm('Anda yakin ingin MENERIMA siswa ini?');">Terima</a>
                                <a href="proses_persetujuan.php?id=<?php echo $row['id']; ?>&action=tolak" class="btn-aksi btn-hapus" onclick="return confirm('Anda yakin ingin MENOLAK siswa ini?');">Tolak</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada pendaftar baru yang perlu diproses.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$conn->close();
include 'template_footer.php'; 
?>