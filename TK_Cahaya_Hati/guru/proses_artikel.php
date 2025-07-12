<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id']) || !isset($_POST['simpan_artikel'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil semua data dari form
$id_guru = (int)$_POST['id_guru'];
$id_artikel = (int)$_POST['id_artikel'];
$id_kategori = (int)$_POST['id_kategori']; // Ambil id_kategori dari form
$judul_artikel = $_POST['judul_artikel'];
$isi_artikel = $_POST['isi_artikel'];
$status_publikasi = $_POST['status_publikasi'];

// Cek keamanan, pastikan guru hanya bisa mengedit artikelnya sendiri
if ($id_artikel > 0) {
    $stmt_check = $conn->prepare("SELECT id_guru FROM artikel WHERE id_artikel = ?");
    $stmt_check->bind_param("i", $id_artikel);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows == 1) {
        $owner = $result_check->fetch_assoc();
        if ($owner['id_guru'] != $id_guru) {
            header("Location: manajemen_artikel.php");
            exit();
        }
    }
    $stmt_check->close();
}

// Proses Upload Gambar
$nama_file_gambar = null;
if (isset($_FILES['gambar_header']) && $_FILES['gambar_header']['error'] == 0) {
    $target_dir = "../uploads/artikel/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $nama_file_unik = "artikel-" . time() . "-" . basename($_FILES["gambar_header"]["name"]);
    if (move_uploaded_file($_FILES["gambar_header"]["tmp_name"], $target_dir . $nama_file_unik)) {
        $nama_file_gambar = $nama_file_unik;
    }
}

// Logika Simpan ke Database
if ($id_artikel == 0) { // Buat artikel baru
    // Tambahkan id_kategori ke query INSERT
    $stmt = $conn->prepare("INSERT INTO artikel (id_guru, id_kategori, judul_artikel, isi_artikel, gambar_header, status_publikasi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $id_guru, $id_kategori, $judul_artikel, $isi_artikel, $nama_file_gambar, $status_publikasi);
} else { // Edit artikel yang ada
    if ($nama_file_gambar) { // Jika ada gambar baru diupload
        // Tambahkan id_kategori ke query UPDATE
        $stmt = $conn->prepare("UPDATE artikel SET judul_artikel = ?, isi_artikel = ?, id_kategori = ?, gambar_header = ?, status_publikasi = ? WHERE id_artikel = ?");
        $stmt->bind_param("ssissi", $judul_artikel, $isi_artikel, $id_kategori, $nama_file_gambar, $status_publikasi, $id_artikel);
    } else { // Jika tidak ada gambar baru
        // Tambahkan id_kategori ke query UPDATE
        $stmt = $conn->prepare("UPDATE artikel SET judul_artikel = ?, isi_artikel = ?, id_kategori = ?, status_publikasi = ? WHERE id_artikel = ?");
        $stmt->bind_param("ssisi", $judul_artikel, $isi_artikel, $id_kategori, $status_publikasi, $id_artikel);
    }
}

$stmt->execute();
$stmt->close();
$conn->close();

header("Location: manajemen_artikel.php?status=sukses");
exit();
?>