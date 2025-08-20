<?php
session_start(); // Baris ini sangat penting dan harus ada di paling atas
require_once '../config.php';

// Cek sesi login
if (!isset($_SESSION['guru_id'])) {
    header("location: ../login.html");
    exit();
}

$id_pengumuman = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pengumuman > 0) {
    // Pastikan hanya guru yang bersangkutan yang bisa menghapus
    $stmt = $conn->prepare("DELETE FROM pengumuman_kelas WHERE id_pengumuman = ? AND id_guru = ?");
    $stmt->bind_param("ii", $id_pengumuman, $_SESSION['guru_id']);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: pengumuman_kelas.php?status=hapus_sukses");
exit();
?>