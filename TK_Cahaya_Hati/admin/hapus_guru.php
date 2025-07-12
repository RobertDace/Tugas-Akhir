<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.html");
    exit();
}

$id_guru = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_guru > 0) {
    $stmt = $conn->prepare("DELETE FROM guru WHERE id_guru = ?");
    $stmt->bind_param("i", $id_guru);
    $stmt->execute();
    $stmt->close();
}

header("Location: manajemen_guru.php?status=hapus_sukses");
exit();
?>