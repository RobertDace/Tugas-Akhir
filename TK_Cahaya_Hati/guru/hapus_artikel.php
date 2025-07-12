<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id']) || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit();
}

$id_guru = $_SESSION['guru_id'];
$id_artikel = (int)$_GET['id'];

// Hapus artikel, pastikan hanya pemilik yang bisa menghapus
$stmt = $conn->prepare("DELETE FROM artikel WHERE id_artikel = ? AND id_guru = ?");
$stmt->bind_param("ii", $id_artikel, $id_guru);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: manajemen_artikel.php?status=dihapus");
exit();
?>