<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id']) || !isset($_POST['simpan_profil'])) {
    header("Location: ../login.html");
    exit();
}

$id_guru = $_SESSION['guru_id'];

// Cek jika ada file foto yang diunggah
if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
    $target_dir = "../uploads/profil/";
    
    // Ambil nama foto lama untuk dihapus (jika bukan default.png)
    $stmt_old = $conn->prepare("SELECT foto_profil FROM guru WHERE id_guru = ?");
    $stmt_old->bind_param("i", $id_guru);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    $data_old = $result_old->fetch_assoc();
    $foto_lama = $data_old['foto_profil'];
    $stmt_old->close();

    // Proses upload file baru
    $nama_file_asli = basename($_FILES["foto_profil"]["name"]);
    $imageFileType = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
    $nama_file_unik = "guru-" . $id_guru . "-" . time() . "." . $imageFileType;
    $target_file = $target_dir . $nama_file_unik;

    // Validasi sederhana
    $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Error: Format file tidak diizinkan.");
    }

    if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
        // Update database dengan nama file baru
        $stmt_update = $conn->prepare("UPDATE guru SET foto_profil = ? WHERE id_guru = ?");
        $stmt_update->bind_param("si", $nama_file_unik, $id_guru);
        $stmt_update->execute();
        $stmt_update->close();

        // Hapus file foto lama jika bukan default.png
        if ($foto_lama != 'default.png' && file_exists($target_dir . $foto_lama)) {
            unlink($target_dir . $foto_lama);
        }

        // Perbarui session agar foto langsung berubah di sidebar
        $_SESSION['guru_foto_profil'] = $nama_file_unik;
    }
}

header("Location: profil.php?status=sukses");
exit();
?>