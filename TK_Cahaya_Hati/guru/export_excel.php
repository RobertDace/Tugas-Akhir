<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.php");
    exit();
}

$id_kelas = (int)$_POST['id_kelas'];

// Ambil nama kelas
$nama_kelas = $conn->query("SELECT nama_kelas FROM kelas WHERE id_kelas = $id_kelas")->fetch_assoc()['nama_kelas'];

// --- LOGIKA UNTUK EKSPOR HARIAN ---
if (isset($_POST['export_tanggal'])) {
    $tanggal_export = $_POST['tanggal_export'];
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Absensi_Harian_".$tanggal_export.".xls");

    echo '<h3 style="text-align: center;">Laporan Absensi Harian</h3>';
    echo '<p><strong>Kelas:</strong> '.htmlspecialchars($nama_kelas).'</p>';
    echo '<p><strong>Tanggal:</strong> '.date('d F Y', strtotime($tanggal_export)).'</p>';

    echo '<table border="1">';
    echo '<thead>
            <tr>
                <th style="background-color: #34495e; color: white;">No.</th>
                <th style="background-color: #34495e; color: white;">Nama Siswa</th>
                <th style="background-color: #34495e; color: white;">Status Kehadiran</th>
            </tr>
          </thead>';
    echo '<tbody>';

    $stmt = $conn->prepare("SELECT cs.nama_lengkap, a.status_kehadiran FROM calon_siswa cs LEFT JOIN absensi a ON cs.id = a.id_siswa AND a.tanggal_absen = ? WHERE cs.id_kelas = ? ORDER BY cs.nama_lengkap ASC");
    $stmt->bind_param("si", $tanggal_export, $id_kelas);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $no = 1;
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$no++.'</td>';
            echo '<td>'.htmlspecialchars($row['nama_lengkap']).'</td>';
            echo '<td>'.htmlspecialchars($row['status_kehadiran'] ?? 'Belum Diabsen').'</td>';
            echo '</tr>';
        }
    }
    $stmt->close();
    echo '</tbody></table>';
}

// --- LOGIKA UNTUK EKSPOR BULANAN ---
elseif (isset($_POST['export_bulan'])) {
    $bulan = (int)$_POST['bulan_export'];
    $tahun = (int)$_POST['tahun_export'];
    $nama_file = "Absensi_Bulanan_".date('F_Y', mktime(0,0,0,$bulan,1,$tahun)).".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$nama_file");

    echo '<h3 style="text-align: center;">Laporan Absensi Bulanan</h3>';
    echo '<p><strong>Kelas:</strong> '.htmlspecialchars($nama_kelas).'</p>';
    echo '<p><strong>Bulan:</strong> '.date('F Y', mktime(0,0,0,$bulan,1,$tahun)).'</p>';
    
    // Ambil semua siswa di kelas
    $result_siswa = $conn->query("SELECT id, nama_lengkap FROM calon_siswa WHERE id_kelas = $id_kelas ORDER BY nama_lengkap ASC");
    $daftar_siswa = [];
    while($row = $result_siswa->fetch_assoc()){
        $daftar_siswa[$row['id']] = $row['nama_lengkap'];
    }

    // Ambil semua data absensi untuk bulan ini
    $absensi_bulan_ini = [];
    $stmt = $conn->prepare("SELECT id_siswa, DAY(tanggal_absen) as hari, status_kehadiran FROM absensi WHERE id_kelas = ? AND MONTH(tanggal_absen) = ? AND YEAR(tanggal_absen) = ?");
    $stmt->bind_param("iii", $id_kelas, $bulan, $tahun);
    $stmt->execute();
    $result_absen = $stmt->get_result();
    while($row = $result_absen->fetch_assoc()){
        $absensi_bulan_ini[$row['id_siswa']][$row['hari']] = $row['status_kehadiran'];
    }
    $stmt->close();

    $jumlah_hari = date('t', mktime(0,0,0,$bulan,1,$tahun));
    
    echo '<table border="1" style="width:100%;">';
    echo '<thead><tr><th style="background-color: #34495e; color: white;">Nama Siswa</th>';
    for($i = 1; $i <= $jumlah_hari; $i++){
        echo '<th style="background-color: #34495e; color: white;">'.$i.'</th>';
    }
    echo '</tr></thead><tbody>';

    foreach($daftar_siswa as $id_siswa => $nama_siswa){
        echo '<tr><td>'.htmlspecialchars($nama_siswa).'</td>';
        for($i = 1; $i <= $jumlah_hari; $i++){
            $status = isset($absensi_bulan_ini[$id_siswa][$i]) ? $absensi_bulan_ini[$id_siswa][$i] : '-';
            echo '<td>'.$status.'</td>';
        }
        echo '</tr>';
    }
    echo '</tbody></table>';
}

$conn->close();
exit();
?>