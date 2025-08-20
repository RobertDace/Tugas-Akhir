<?php
require_once '../config.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['guru_id'])) {
    echo json_encode([]);
    exit();
}

$id_guru = $_SESSION['guru_id'];

// Ambil SEMUA notifikasi untuk guru ini, urutkan berdasarkan yang baru dulu
$stmt = $conn->prepare("SELECT * FROM notifikasi WHERE role_user = 'guru' AND id_user = ? ORDER BY status_baca ASC, waktu DESC");
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();

$notifikasi = [];
while($row = $result->fetch_assoc()) {
    $notifikasi[] = $row;
}
$stmt->close();

echo json_encode($notifikasi);
$conn->close();
?>