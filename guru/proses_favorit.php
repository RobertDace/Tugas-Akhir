<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.html");
    exit();
}

$id_foto = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_guru = $_SESSION['guru_id'];

if ($id_foto > 0 && ($action == 'set' || $action == 'unset')) {
    $new_status = ($action == 'set') ? 1 : 0;

    // Pastikan guru hanya bisa mengubah foto dari kelasnya sendiri
    $stmt = $conn->prepare("UPDATE galeri_kelas gk JOIN guru g ON gk.id_kelas = g.id_kelas SET gk.is_favorit = ? WHERE gk.id_foto = ? AND g.id_guru = ?");
    $stmt->bind_param("iii", $new_status, $id_foto, $id_guru);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: galeri_kelas.php");
exit();
?>