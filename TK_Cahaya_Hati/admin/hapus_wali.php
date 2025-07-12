<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.html");
    exit();
}

$id_wali = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_wali > 0) {
    $stmt = $conn->prepare("DELETE FROM wali_murid WHERE id_wali = ?");
    $stmt->bind_param("i", $id_wali);
    $stmt->execute();
    $stmt->close();
}

header("Location: manajemen_wali.php?status=hapus_sukses");
exit();
?>