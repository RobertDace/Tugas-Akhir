<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['simpan'])) {
    header("Location: ../login.php");
    exit();
}

$id_guru = (int)$_POST['id_guru'];
$nama_guru = $_POST['nama_guru'];
$nip = $_POST['nip'];
$jabatan = $_POST['jabatan'];
$status_kepegawaian = $_POST['status_kepegawaian'];
$username = $_POST['username'];
$password = $_POST['password'];
$id_kelas = !empty($_POST['id_kelas']) ? (int)$_POST['id_kelas'] : null;

if ($id_guru > 0) {
    // Proses UPDATE (Edit)
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE guru SET nama_guru=?, nip=?, jabatan=?, status_kepegawaian=?, username=?, password=?, id_kelas=? WHERE id_guru=?");
        $stmt->bind_param("ssssssii", $nama_guru, $nip, $jabatan, $status_kepegawaian, $username, $password_hash, $id_kelas, $id_guru);
    } else {
        $stmt = $conn->prepare("UPDATE guru SET nama_guru=?, nip=?, jabatan=?, status_kepegawaian=?, username=?, id_kelas=? WHERE id_guru=?");
        $stmt->bind_param("sssssii", $nama_guru, $nip, $jabatan, $status_kepegawaian, $username, $id_kelas, $id_guru);
    }
} else {
    // Proses INSERT (Baru)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO guru (nama_guru, nip, jabatan, status_kepegawaian, username, password, id_kelas) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $nama_guru, $nip, $jabatan, $status_kepegawaian, $username, $password_hash, $id_kelas);
}

if ($stmt->execute()) {
    header("Location: manajemen_guru.php?status=sukses");
} else {
    echo "Error: Gagal menyimpan data guru. " . $stmt->error;
}
$stmt->close();
$conn->close();
?>