<?php
include 'template_header.php';
require_once '../config.php';

$debug_messages = []; // Array untuk menampung semua pesan debug

// BAGIAN PEMROSESAN FORM SAAT TOMBOL SIMPAN DITEKAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_absen'])) {
    $debug_messages[] = "INFO: Tombol 'Simpan Absensi' ditekan.";

    $id_kelas = (int)$_POST['id_kelas'];
    $tanggal_absen = $_POST['tanggal_absen'];
    $daftar_siswa = isset($_POST['id_siswa']) ? $_POST['id_siswa'] : [];
    $status_kehadiran = isset($_POST['status']) ? $_POST['status'] : [];

    // 1. HAPUS DATA LAMA
    $stmt_delete = $conn->prepare("DELETE FROM absensi WHERE id_kelas = ? AND tanggal_absen = ?");
    if ($stmt_delete === false) {
        $debug_messages[] = "ERROR: Gagal prepare statement DELETE: " . $conn->error;
    } else {
        $stmt_delete->bind_param("is", $id_kelas, $tanggal_absen);
        if ($stmt_delete->execute()) {
            $debug_messages[] = "SUKSES: " . $stmt_delete->affected_rows . " baris data lama untuk tanggal $tanggal_absen berhasil dihapus.";
        } else {
            $debug_messages[] = "ERROR: Gagal eksekusi DELETE: " . $stmt_delete->error;
        }
        $stmt_delete->close();
    }

    // 2. SIMPAN DATA BARU
    if(!empty($daftar_siswa)) {
        $stmt_insert = $conn->prepare("INSERT INTO absensi (id_siswa, id_kelas, tanggal_absen, status_kehadiran) VALUES (?, ?, ?, ?)");
        if ($stmt_insert === false) {
            $debug_messages[] = "ERROR: Gagal prepare statement INSERT: " . $conn->error;
        } else {
            $insert_count = 0;
            foreach ($daftar_siswa as $id_siswa) {
                if (isset($status_kehadiran[$id_siswa])) {
                    $status = $status_kehadiran[$id_siswa];
                    $stmt_insert->bind_param("iiss", $id_siswa, $id_kelas, $tanggal_absen, $status);
                    if($stmt_insert->execute()){
                        $insert_count++;
                    } else {
                        $debug_messages[] = "ERROR: Gagal INSERT untuk siswa ID $id_siswa: " . $stmt_insert->error;
                    }
                }
            }
            $debug_messages[] = "SUKSES: $insert_count data absensi baru berhasil disimpan.";
            $stmt_insert->close();
        }
    } else {
        $debug_messages[] = "INFO: Tidak ada data siswa yang dikirim untuk disimpan.";
    }
}

// --- BAGIAN UNTUK MENAMPILKAN DATA (KALENDER DAN FORM) ---
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$selected_date = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$id_guru = $_SESSION['guru_id'];
$stmt_kelas = $conn->prepare("SELECT id_kelas FROM guru WHERE id_guru = ?");
$stmt_kelas->bind_param("i", $id_guru);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$data_kelas = $result_kelas->fetch_assoc();
$id_kelas = $data_kelas['id_kelas'];
$stmt_kelas->close();
$stmt_total_siswa = $conn->prepare("SELECT count(id) as total FROM calon_siswa WHERE id_kelas = ?");
$stmt_total_siswa->bind_param("i", $id_kelas);
$stmt_total_siswa->execute();
$total_siswa_kelas = $stmt_total_siswa->get_result()->fetch_assoc()['total'];
$stmt_total_siswa->close();
$stmt_data = $conn->prepare("(SELECT DAY(tanggal_absen) as hari, COUNT(DISTINCT id_siswa) as jumlah, 'absen' as tipe FROM absensi WHERE id_kelas = ? AND MONTH(tanggal_absen) = ? AND YEAR(tanggal_absen) = ? GROUP BY tanggal_absen) UNION (SELECT DAY(tanggal_libur) as hari, 0 as jumlah, 'libur' as tipe FROM hari_libur WHERE MONTH(tanggal_libur) = ? AND YEAR(tanggal_libur) = ?)");
$stmt_data->bind_param("iiiii", $id_kelas, $month, $year, $month, $year);
$stmt_data->execute();
$result_data = $stmt_data->get_result();
$status_hari = [];
while ($row = $result_data->fetch_assoc()) {
    if ($row['tipe'] == 'libur') {$status_hari[$row['hari']] = 'holiday';} else { if ($total_siswa_kelas > 0 && $row['jumlah'] >= $total_siswa_kelas) {$status_hari[$row['hari']] = 'completed';} elseif ($row['jumlah'] > 0) {$status_hari[$row['hari']] = 'partial';}}
}
$stmt_data->close();
function buat_kalender($month, $year, $status_hari, $selected_date) {
    $nama_bulan = date('F', mktime(0, 0, 0, $month, 1, $year));
    $daysOfWeek = array('Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab');
    $firstDayOfMonth = date('w', mktime(0, 0, 0, $month, 1, $year));
    $numberDays = date('t', mktime(0, 0, 0, $month, 1, $year));
    $prev_month = $month == 1 ? 12 : $month - 1;
    $prev_year = $month == 1 ? $year - 1 : $year;
    $next_month = $month == 12 ? 1 : $month + 1;
    $next_year = $month == 12 ? $year + 1 : $year;
    $kalender = "<div class='calendar-container'><div class='calendar-header'><a href='?tanggal=$selected_date&month=$prev_month&year=$prev_year' class='nav-btn'>&lt;</a><h2>$nama_bulan $year</h2><a href='?tanggal=$selected_date&month=$next_month&year=$next_year' class='nav-btn'>&gt;</a></div><table class='calendar-table'><tr>";
    foreach ($daysOfWeek as $day) { $kalender .= "<th class='header'>$day</th>"; }
    $kalender .= "</tr><tr>";
    for ($i = 0; $i < $firstDayOfMonth; $i++) { $kalender .= "<td></td>"; }
    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($firstDayOfMonth % 7 == 0 && $currentDay != 1) { $kalender .= "</tr><tr>"; }
        $tanggal_penuh = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($currentDay, 2, '0', STR_PAD_LEFT);
        $class = 'day';
        if (isset($status_hari[$currentDay])) { $class .= ' ' . $status_hari[$currentDay]; } else { $class .= ' pending'; }
        if ($tanggal_penuh == $selected_date) { $class .= ' selected'; }
        $kalender .= "<td class='$class'><a href='?tanggal=$tanggal_penuh&month=$month&year=$year'>$currentDay</a></td>";
        $currentDay++;
        $firstDayOfMonth++;
    }
    while ($firstDayOfMonth % 7 != 0) { $kalender .= "<td></td>"; $firstDayOfMonth++; }
    $kalender .= "</tr></table></div>";
    return $kalender;
}
$day_of_month = (int)date('d', strtotime($selected_date));
$is_holiday = isset($status_hari[$day_of_month]) && $status_hari[$day_of_month] == 'holiday';
?>

<div class="admin-content-header">
    <h2>Absensi Kelas Interaktif</h2>
</div>

<div class="admin-content-body">
    <?php if (!empty($debug_messages)): ?>
        <div class="debug-container">
            <h4>Log Proses Penyimpanan:</h4>
            <?php foreach ($debug_messages as $msg): ?>
                <p><?php echo htmlspecialchars($msg); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php echo buat_kalender($month, $year, $status_hari, $selected_date); ?>

    <div class="form-container" style="margin-top: 1.5rem;">
        <h4>Data untuk tanggal: <strong><?php echo date('d F Y', strtotime($selected_date)); ?></strong></h4>
        
        <?php if ($is_holiday): ?>
            <div class="holiday-notice">
                <p>Tanggal ini ditandai sebagai hari libur.</p>
                <form action="proses_libur.php" method="POST">
                    <input type="hidden" name="tanggal" value="<?php echo $selected_date; ?>">
                    <input type="hidden" name="month" value="<?php echo $month; ?>">
                    <input type="hidden" name="year" value="<?php echo $year; ?>">
                    <button type="submit" name="cancel_libur" class="btn-aksi btn-detail">Batalkan Libur</button>
                </form>
            </div>
        <?php else: ?>
            <form action="proses_absensi.php" method="POST" id="form-absensi">
                <input type="hidden" name="tanggal_absen" value="<?php echo $selected_date; ?>">
                <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                <input type="hidden" name="month" value="<?php echo $month; ?>">
                <input type="hidden" name="year" value="<?php echo $year; ?>">
                
                <table class="table-data">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th style="width: 50%;">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt_siswa = $conn->prepare("SELECT cs.id, cs.nama_lengkap, a.status_kehadiran FROM calon_siswa cs LEFT JOIN absensi a ON cs.id = a.id_siswa AND a.tanggal_absen = ? WHERE cs.id_kelas = ? ORDER BY cs.nama_lengkap ASC");
                        $stmt_siswa->bind_param("si", $selected_date, $id_kelas);
                        $stmt_siswa->execute();
                        $result_siswa = $stmt_siswa->get_result();
                        if ($result_siswa->num_rows > 0) {
                            while($siswa = $result_siswa->fetch_assoc()) {
                                $id_siswa = $siswa['id'];
                                $status_tersimpan = $siswa['status_kehadiran'];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($siswa['nama_lengkap']); ?></td>
                                <td>
                                    <div class="kehadiran-group">
                                        <input type="hidden" name="id_siswa[]" value="<?php echo $id_siswa; ?>">
                                        <input type="radio" id="hadir_<?php echo $id_siswa; ?>" name="status[<?php echo $id_siswa; ?>]" value="Hadir" <?php if($status_tersimpan == 'Hadir') echo 'checked'; ?>>
                                        <label for="hadir_<?php echo $id_siswa; ?>">Hadir</label>
                                        <input type="radio" id="sakit_<?php echo $id_siswa; ?>" name="status[<?php echo $id_siswa; ?>]" value="Sakit" <?php if($status_tersimpan == 'Sakit') echo 'checked'; ?>>
                                        <label for="sakit_<?php echo $id_siswa; ?>">Sakit</label>
                                        <input type="radio" id="izin_<?php echo $id_siswa; ?>" name="status[<?php echo $id_siswa; ?>]" value="Izin" <?php if($status_tersimpan == 'Izin') echo 'checked'; ?>>
                                        <label for="izin_<?php echo $id_siswa; ?>">Izin</label>
                                        <input type="radio" id="alpa_<?php echo $id_siswa; ?>" name="status[<?php echo $id_siswa; ?>]" value="Alpa" <?php if($status_tersimpan == 'Alpa') echo 'checked'; ?>>
                                        <label for="alpa_<?php echo $id_siswa; ?>">Alpa</label>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='2' style='text-align:center;'>Belum ada siswa di kelas ini.</td></tr>";
                        }
                        $stmt_siswa->close();
                        ?>
                    </tbody>
                </table>
            </form>
            <div class="form-actions-absensi">
                <form action="proses_libur.php" method="POST" style="display:inline-block;">
                    <input type="hidden" name="tanggal" value="<?php echo $selected_date; ?>">
                    <input type="hidden" name="month" value="<?php echo $month; ?>">
                    <input type="hidden" name="year" value="<?php echo $year; ?>">
                    <button type="submit" name="set_libur" class="btn-aksi btn-hapus">Liburkan Tanggal Ini</button>
                </form>
                <div style="display: flex; gap: 10px;">
                    <form action="export_excel.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                        <input type="hidden" name="tanggal_export" value="<?php echo $selected_date; ?>">
                        <button type="submit" name="export_tanggal" class="btn-aksi btn-detail">Ekspor Tanggal Ini</button>
                    </form>
                    <form action="export_excel.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                        <input type="hidden" name="bulan_export" value="<?php echo $month; ?>">
                        <input type="hidden" name="tahun_export" value="<?php echo $year; ?>">
                        <button type="submit" name="export_bulan" class="btn-aksi btn-info">Ekspor Bulan Ini</button>
                    </form>
                    <button type="submit" name="simpan_absen" form="form-absensi" class="btn-aksi btn-simpan">Simpan Absensi</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'template_footer.php'; ?>