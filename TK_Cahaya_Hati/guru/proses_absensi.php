<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['simpan_absen'])) {
    // Ambil semua data yang diperlukan dari form
    $id_kelas = (int)$_POST['id_kelas'];
    $tanggal_absen = $_POST['tanggal_absen'];
    $daftar_siswa = $_POST['id_siswa'];
    $status_kehadiran = $_POST['status'];
    
    // Ambil bulan dan tahun dari tanggal yang diproses untuk redirect kembali
    $month = date('m', strtotime($tanggal_absen));
    $year = date('Y', strtotime($tanggal_absen));

    // LANGKAH 1: HAPUS SEMUA DATA ABSENSI LAMA UNTUK TANGGAL INI
    $stmt_delete = $conn->prepare("DELETE FROM absensi WHERE id_kelas = ? AND tanggal_absen = ?");
    $stmt_delete->bind_param("is", $id_kelas, $tanggal_absen);
    $stmt_delete->execute();
    $stmt_delete->close();

    // LANGKAH 2: SIMPAN DATA BARU
    $stmt_insert = $conn->prepare("INSERT INTO absensi (id_siswa, id_kelas, tanggal_absen, status_kehadiran) VALUES (?, ?, ?, ?)");

    // Loop untuk setiap siswa dan simpan datanya satu per satu
    foreach ($daftar_siswa as $id_siswa) {
        if (isset($status_kehadiran[$id_siswa])) {
            $status = $status_kehadiran[$id_siswa];
            $stmt_insert->bind_param("iiss", $id_siswa, $id_kelas, $tanggal_absen, $status);
            $stmt_insert->execute();
        }
    }

    $stmt_insert->close();
    $conn->close();

    // Redirect kembali ke halaman absensi dengan parameter yang benar
    header("Location: absensi_kelas.php?tanggal=" . $tanggal_absen . "&month=" . $month . "&year=" . $year . "&status=sukses");
    exit();
} else {
    // Jika diakses tanpa menekan tombol simpan, kembalikan ke halaman absensi default
    header("Location: absensi_kelas.php");
    exit();
}
?>