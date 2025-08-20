<?php
require_once '../config.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['wali_id'])) {
    echo json_encode([]);
    exit();
}

$id_wali = $_SESSION['wali_id'];

$stmt = $conn->prepare("SELECT * FROM notifikasi WHERE role_user = 'wali' AND id_user = ? ORDER BY status_baca ASC, waktu DESC");
$stmt->bind_param("i", $id_wali);
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