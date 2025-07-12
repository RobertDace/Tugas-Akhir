<?php
require_once '../config.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode([]);
    exit();
}

// Ambil notifikasi untuk admin yang belum dibaca
$stmt = $conn->prepare("SELECT * FROM notifikasi WHERE role_user = 'admin' AND status_baca = 0 ORDER BY waktu DESC");
$stmt->execute();
$result = $stmt->get_result();

$notifikasi = [];
while($row = $result->fetch_assoc()) {
    $notifikasi[] = $row;
}
$stmt->close();

// Tandai notifikasi admin yang sudah diambil sebagai "telah dibaca"
if (!empty($notifikasi)) {
    $conn->query("UPDATE notifikasi SET status_baca = 1 WHERE role_user = 'admin' AND status_baca = 0");
}

echo json_encode($notifikasi);
$conn->close();
?>