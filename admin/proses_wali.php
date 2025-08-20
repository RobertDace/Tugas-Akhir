<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['simpan'])) {
    header("Location: ../login.html");
    exit();
}

$id_wali = (int)$_POST['id_wali'];
$id_siswa = (int)$_POST['id_siswa'];
$nama_wali = trim($_POST['nama_wali']);
$hubungan = $_POST['hubungan'];
$username = trim($_POST['username']);
$password = $_POST['password'];

if ($id_wali > 0) {
    // Proses UPDATE
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE wali_murid SET id_siswa = ?, nama_wali = ?, hubungan = ?, username = ?, password = ? WHERE id_wali = ?");
        $stmt->bind_param("issssi", $id_siswa, $nama_wali, $hubungan, $username, $password_hash, $id_wali);
    } else {
        $stmt = $conn->prepare("UPDATE wali_murid SET id_siswa = ?, nama_wali = ?, hubungan = ?, username = ? WHERE id_wali = ?");
        $stmt->bind_param("isssi", $id_siswa, $nama_wali, $hubungan, $username, $id_wali);
    }
} else {
    // Proses INSERT
    if (empty($password)) die("Password wajib diisi untuk akun baru.");
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO wali_murid (id_siswa, nama_wali, hubungan, username, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $id_siswa, $nama_wali, $hubungan, $username, $password_hash);
}

if ($stmt->execute()) {
    header("Location: manajemen_wali.php?status=sukses");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>