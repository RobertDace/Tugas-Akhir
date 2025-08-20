<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['simpan_event'])) {
    $id_guru = $_POST['id_guru'];
    $nama_event = $_POST['nama_event'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : NULL;
    $warna_event = $_POST['warna_event'];
    $keterangan = $_POST['keterangan'];

    if (isset($_POST['id_event'])) {
        // Proses Edit
        $id_event = $_POST['id_event'];
        $stmt = $conn->prepare("UPDATE kalender_akademik SET nama_event=?, tanggal_mulai=?, tanggal_selesai=?, warna_event=?, keterangan=?, id_guru=? WHERE id_event=?");
        $stmt->bind_param("sssssii", $nama_event, $tanggal_mulai, $tanggal_selesai, $warna_event, $keterangan, $id_guru, $id_event);
    } else {
        // Proses Simpan Baru
        $stmt = $conn->prepare("INSERT INTO kalender_akademik (nama_event, tanggal_mulai, tanggal_selesai, warna_event, keterangan, id_guru) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $nama_event, $tanggal_mulai, $tanggal_selesai, $warna_event, $keterangan, $id_guru);
    }

    if ($stmt->execute()) {
        header("Location: manajemen_kalender.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: manajemen_kalender.php");
}
?>