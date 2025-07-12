<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.html");
    exit();
}

if (isset($_POST['simpan_catatan'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_guru = $_POST['id_guru'];
    $aspek = $_POST['aspek_perkembangan'];
    $catatan = $_POST['catatan'];
    $tanggal = date('Y-m-d');

    if (empty($id_siswa) || empty($catatan)) {
        // Handle error, data tidak lengkap
        header("Location: detail_perkembangan.php?id=" . $id_siswa . "&error=kosong");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO catatan_perkembangan (id_siswa, id_guru, tanggal_catatan, aspek_perkembangan, catatan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $id_siswa, $id_guru, $tanggal, $aspek, $catatan);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect kembali ke halaman detail siswa dengan pesan sukses
        header("Location: detail_perkembangan.php?id=" . $id_siswa . "&status=sukses");
        exit();
    } else {
        echo "Error: Gagal menyimpan catatan.";
    }
} else {
    header("Location: perkembangan_siswa.php");
    exit();
}
?>