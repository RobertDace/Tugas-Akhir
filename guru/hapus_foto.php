<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.html");
    exit();
}

$id_foto = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_foto > 0) {
    // Ambil nama file dari DB untuk dihapus dari folder
    $stmt_get = $conn->prepare("SELECT nama_file FROM galeri_kelas WHERE id_foto = ? AND id_guru = ?");
    $stmt_get->bind_param("ii", $id_foto, $_SESSION['guru_id']);
    $stmt_get->execute();
    $result_get = $stmt_get->get_result();
    
    if($result_get->num_rows == 1){
        $foto = $result_get->fetch_assoc();
        $file_path = "../uploads/" . $foto['nama_file'];

        // Hapus file dari server
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Hapus record dari database
        $stmt_del = $conn->prepare("DELETE FROM galeri_kelas WHERE id_foto = ?");
        $stmt_del->bind_param("i", $id_foto);
        $stmt_del->execute();
        $stmt_del->close();
    }
    $stmt_get->close();
}

header("Location: galeri_kelas.php?status=hapus_sukses");
exit();
?>