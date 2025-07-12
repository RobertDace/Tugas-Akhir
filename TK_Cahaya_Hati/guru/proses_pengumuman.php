<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id']) || !isset($_POST['simpan'])) {
    header("Location: ../login.html");
    exit();
}

$id_pengumuman = (int)$_POST['id_pengumuman'];
$id_guru = (int)$_POST['id_guru'];
$judul = $_POST['judul_pengumuman'];
$isi = $_POST['isi_pengumuman'];

// Ambil id_kelas guru
$stmt_kelas = $conn->prepare("SELECT id_kelas FROM guru WHERE id_guru = ?");
$stmt_kelas->bind_param("i", $id_guru);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$data_kelas = $result_kelas->fetch_assoc();
$id_kelas = $data_kelas['id_kelas'];
$stmt_kelas->close();

if ($id_pengumuman > 0) {
    // Ini adalah proses UPDATE (Edit)
    $stmt = $conn->prepare("UPDATE pengumuman_kelas SET judul_pengumuman = ?, isi_pengumuman = ? WHERE id_pengumuman = ? AND id_guru = ?");
    $stmt->bind_param("ssii", $judul, $isi, $id_pengumuman, $id_guru);
} else {
    // Ini adalah proses INSERT (Baru)
    $stmt = $conn->prepare("INSERT INTO pengumuman_kelas (id_kelas, id_guru, judul_pengumuman, isi_pengumuman) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_kelas, $id_guru, $judul, $isi);
}

if ($stmt->execute()) {
    header("Location: pengumuman_kelas.php?status=sukses");
} else {
    echo "Error: Gagal menyimpan data.";
}

$stmt->close();
$conn->close();
?>