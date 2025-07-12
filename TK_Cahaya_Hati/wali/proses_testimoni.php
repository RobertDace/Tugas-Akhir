<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['wali_id']) || !isset($_POST['simpan_testimoni'])) {
    header("Location: ../login.php");
    exit();
}

$id_wali = $_SESSION['wali_id'];
$isi_testimoni = trim($_POST['isi_testimoni']);

// Cek apakah testimoni dari wali ini sudah ada
$stmt_check = $conn->prepare("SELECT id_testimoni FROM testimoni WHERE id_wali = ?");
$stmt_check->bind_param("i", $id_wali);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Jika sudah ada, UPDATE testimoni yang lama
    $id_testimoni = $result_check->fetch_assoc()['id_testimoni'];
    $stmt = $conn->prepare("UPDATE testimoni SET isi_testimoni = ?, status = 'pending' WHERE id_testimoni = ?");
    $stmt->bind_param("si", $isi_testimoni, $id_testimoni);
} else {
    // Jika belum ada, INSERT testimoni baru
    $stmt = $conn->prepare("INSERT INTO testimoni (id_wali, isi_testimoni, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("is", $id_wali, $isi_testimoni);
}

$stmt->execute();
$stmt->close();
$conn->close();

header("Location: kirim_testimoni.php?status=sukses");
exit();
?>