<?php
session_start();
require_once '../config.php';
if (!isset($_SESSION['guru_id'])) exit('Akses ditolak');

$id_siswa = (int)$_POST['id_siswa'];
$id_kelas = (int)$_POST['id_kelas'];

$stmt = $conn->prepare("UPDATE calon_siswa SET id_kelas = ? WHERE id = ?");
$stmt->bind_param("ii", $id_kelas, $id_siswa);
$stmt->execute();
$stmt->close();

header("Location: perkembangan_siswa.php?status=sukses");
exit();
?>