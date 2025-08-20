<?php
require_once '../config.php';
session_start();

if (isset($_SESSION['guru_id'])) {
    $id_guru = $_SESSION['guru_id'];
    $conn->query("UPDATE notifikasi SET status_baca = 1 WHERE role_user = 'guru' AND id_user = $id_guru AND status_baca = 0");
}
// Tidak perlu output apa-apa
?>