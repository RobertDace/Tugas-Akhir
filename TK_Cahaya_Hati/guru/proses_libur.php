<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil semua data yang dikirim dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    // Ambil juga bulan dan tahun untuk redirect
    $month = isset($_POST['month']) ? $_POST['month'] : date('m', strtotime($tanggal));
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y', strtotime($tanggal));

    if (isset($_POST['set_libur'])) {
        $keterangan = "Libur Ditetapkan oleh Guru";
        $stmt = $conn->prepare("INSERT IGNORE INTO hari_libur (tanggal_libur, keterangan) VALUES (?, ?)");
        $stmt->bind_param("ss", $tanggal, $keterangan);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['cancel_libur'])) {
        $stmt = $conn->prepare("DELETE FROM hari_libur WHERE tanggal_libur = ?");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
// Redirect kembali ke halaman absensi dengan parameter tanggal, bulan, dan tahun yang benar
header("Location: absensi_kelas.php?tanggal=" . $tanggal . "&month=" . $month . "&year=" . $year);
exit();
?>