<?php
require_once '../config.php';

$id = intval($_GET['id'] ?? 0);
$aksi = $_GET['aksi'] ?? '';

if ($id && ($aksi=='show' || $aksi=='hide')) {
    $status = ($aksi=='show') ? 'ditampilkan' : 'pending';
    $stmt = $conn->prepare("UPDATE testimoni SET status=? WHERE id_testimoni=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: manajemen_testimoni.php");
exit();
?>
