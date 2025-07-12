<?php
require_once '../config.php';
session_start();

if (isset($_SESSION['wali_id'])) {
    $id_wali = $_SESSION['wali_id'];
    $conn->query("UPDATE notifikasi SET status_baca = 1 WHERE role_user = 'wali' AND id_user = $id_wali AND status_baca = 0");
}
?>