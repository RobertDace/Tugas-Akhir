<?php
require_once 'config.php'; // Hubungkan ke database

// Pengaturan Bulan dan Tahun dari parameter URL
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Ambil semua event untuk bulan dan tahun yang dipilih
$events = [];
$stmt_events = $conn->prepare("SELECT * FROM kalender_akademik WHERE (MONTH(tanggal_mulai) = ? AND YEAR(tanggal_mulai) = ?) OR (MONTH(tanggal_selesai) = ? AND YEAR(tanggal_selesai) = ?)");
$stmt_events->bind_param("iiii", $month, $year, $month, $year);
$stmt_events->execute();
$result_events = $stmt_events->get_result();
while ($event = $result_events->fetch_assoc()) {
    $current_date = new DateTime($event['tanggal_mulai']);
    
    // PERBAIKAN DI SINI: Gunakan 'clone' untuk membuat salinan objek
    $end_date = $event['tanggal_selesai'] ? new DateTime($event['tanggal_selesai']) : clone $current_date;

    // Loop dari tanggal mulai sampai selesai untuk menandai setiap hari
    while ($current_date <= $end_date) {
        $events[$current_date->format('Y-m-d')][] = $event;
        $current_date->modify('+1 day');
    }
}
$stmt_events->close();
// Logika pembuatan kalender
$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day_of_month);
$start_day_of_week = date('N', $first_day_of_month);
$month_name = date('F', $first_day_of_month);

$prev_month = $month == 1 ? 12 : $month - 1;
$prev_year = $month == 1 ? $year - 1 : $year;
$next_month = $month == 12 ? 1 : $month + 1;
$next_year = $month == 12 ? $year + 1 : $year;
?>
<div class="calendar-header">
    <a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="calendar-nav">&laquo;</a>
    <h3><?php echo $month_name . ' ' . $year; ?></h3>
    <a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="calendar-nav">&raquo;</a>
</div>
<table class="calendar-table">
    <thead><tr><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th><th>Min</th></tr></thead>
    <tbody>
        <tr>
        <?php
        for ($i = 1; $i < $start_day_of_week; $i++) { echo "<td></td>"; }

        $day_counter = $start_day_of_week;
        for ($day = 1; $day <= $days_in_month; $day++, $day_counter++) {
            $current_full_date = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $day_of_week = date('N', strtotime($current_full_date));
            $class = '';
            if ($day_of_week == 6 || $day_of_week == 7) { $class = 'libur-akhir-pekan'; }
            if (isset($events[$current_full_date])) {
                $event_color = $events[$current_full_date][0]['warna_event'];
                if ($event_color == 'merah') { $class = 'libur-merah'; } 
                elseif (empty($class)) { $class = 'event-' . $event_color; }
            }
            echo "<td class='$class'>$day</td>";
            if ($day_counter % 7 == 0 && $day < $days_in_month) { echo "</tr><tr>"; }
        }
        while ($day_counter <= 42) { echo "<td></td>"; $day_counter++; if($day_counter % 7 == 1) break; }
        ?>
        </tr>
    </tbody>
</table>
<div class="calendar-legend">
        <h4>Keterangan Bulan Ini:</h4>
        <ul>
            <?php
            // Ambil kembali data event khusus untuk ditampilkan sebagai daftar
            $stmt_list = $conn->prepare("SELECT * FROM kalender_akademik WHERE MONTH(tanggal_mulai) = ? AND YEAR(tanggal_mulai) = ? ORDER BY tanggal_mulai ASC");
            $stmt_list->bind_param("ii", $month, $year);
            $stmt_list->execute();
            $result_list = $stmt_list->get_result();

            if ($result_list->num_rows > 0) {
                while($event_item = $result_list->fetch_assoc()) {
                    $tanggal_info = date('d M', strtotime($event_item['tanggal_mulai']));
                    if ($event_item['tanggal_selesai']) {
                        $tanggal_info .= " - " . date('d M', strtotime($event_item['tanggal_selesai']));
                    }
                    echo "<li><span class='event-dot event-" . htmlspecialchars($event_item['warna_event']) . "'></span> <strong>" . $tanggal_info . "</strong>: " . htmlspecialchars($event_item['nama_event']) . "</li>";
                }
            } else {
                echo "<li>Tidak ada acara yang dijadwalkan untuk bulan ini.</li>";
            }
            $stmt_list->close();
            ?>
        </ul>
    </div>