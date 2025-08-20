<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.html");
    exit();
}

$id_siswa = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_siswa > 0) {
    // Hapus juga semua data terkait (absensi, catatan, dll) agar tidak ada data sampah
    $conn->query("DELETE FROM absensi WHERE id_siswa = $id_siswa");
    $conn->query("DELETE FROM catatan_perkembangan WHERE id_siswa = $id_siswa");
    $conn->query("DELETE FROM wali_murid WHERE id_siswa = $id_siswa");
    
    // Hapus data siswa utama
    $stmt = $conn->prepare("DELETE FROM calon_siswa WHERE id = ?");
    $stmt->bind_param("i", $id_siswa);
    $stmt->execute();
    $stmt->close();
}

header("Location: data_pendaftar.php?status=hapus_sukses");
exit();
?>