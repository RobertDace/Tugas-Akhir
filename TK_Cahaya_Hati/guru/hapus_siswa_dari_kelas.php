<?php
session_start();
require_once '../config.php';
if (!isset($_SESSION['guru_id'])) exit('Akses ditolak');

$id_siswa = (int)$_GET['id'];
$conn->query("UPDATE calon_siswa SET id_kelas = NULL WHERE id = $id_siswa");

header("Location: perkembangan_siswa.php?status=dihapus");
exit();
?>