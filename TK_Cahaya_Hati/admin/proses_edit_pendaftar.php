<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['simpan'])) {
    header("Location: ../login.html");
    exit();
}

$id_siswa = (int)$_POST['id'];
$nama_lengkap = trim($_POST['nama_lengkap']);
$id_kelas = (int)$_POST['id_kelas'];

if ($id_siswa > 0 && !empty($nama_lengkap) && $id_kelas > 0) {
    $stmt = $conn->prepare("UPDATE calon_siswa SET nama_lengkap = ?, id_kelas = ? WHERE id = ?");
    $stmt->bind_param("sii", $nama_lengkap, $id_kelas, $id_siswa);
    
    if ($stmt->execute()) {
        header("Location: data_pendaftar.php?status=edit_sukses");
    } else {
        echo "Error: Gagal memperbarui data.";
    }
    $stmt->close();
} else {
    echo "Data tidak lengkap.";
}

$conn->close();
?>