<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['simpan_profil'])) {
    header("Location: ../login.php");
    exit();
}

$id_admin = $_SESSION['admin_id'];

if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
    $target_dir = "../uploads/profil/";
    $stmt_old = $conn->prepare("SELECT foto_profil FROM admin WHERE id = ?");
    $stmt_old->bind_param("i", $id_admin);
    $stmt_old->execute();
    $foto_lama = $stmt_old->get_result()->fetch_assoc()['foto_profil'];
    $stmt_old->close();

    $nama_file_unik = "admin-" . $id_admin . "-" . time() . "." . strtolower(pathinfo(basename($_FILES["foto_profil"]["name"]), PATHINFO_EXTENSION));
    
    if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_dir . $nama_file_unik)) {
        $stmt_update = $conn->prepare("UPDATE admin SET foto_profil = ? WHERE id = ?");
        $stmt_update->bind_param("si", $nama_file_unik, $id_admin);
        $stmt_update->execute();
        $stmt_update->close();
        if ($foto_lama != 'default.png' && file_exists($target_dir . $foto_lama)) {
            unlink($target_dir . $foto_lama);
        }
        $_SESSION['admin_foto_profil'] = $nama_file_unik;
    }
}
header("Location: profil.php?status=sukses");
exit();
?>