<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['guru_id'])) {
    header("Location: ../login.html");
    exit();
}

if (isset($_POST['upload'])) {
    $id_kelas = $_POST['id_kelas'];
    $id_guru = $_POST['id_guru'];
    $judul_foto = $_POST['judul_foto'];

    // Proses file upload
    $target_dir = "../uploads/";
    $nama_file_asli = basename($_FILES["foto_kegiatan"]["name"]);
    $imageFileType = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
    
    // Buat nama file unik
    $nama_file_unik = time() . "_" . uniqid() . "." . $imageFileType;
    $target_file = $target_dir . $nama_file_unik;

    // Validasi file
    $check = getimagesize($_FILES["foto_kegiatan"]["tmp_name"]);
    if($check === false) {
        die("Error: File yang diunggah bukan gambar.");
    }
    if ($_FILES["foto_kegiatan"]["size"] > 2000000) { // Batas 2MB
        die("Error: Ukuran file terlalu besar. Maksimal 2MB.");
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        die("Error: Hanya format JPG, JPEG, PNG & GIF yang diizinkan.");
    }

    // Pindahkan file dan simpan ke database
    if (move_uploaded_file($_FILES["foto_kegiatan"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO galeri_kelas (id_kelas, id_guru, nama_file, judul_foto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_kelas, $id_guru, $nama_file_unik, $judul_foto);
        
        if ($stmt->execute()) {
            header("Location: galeri_kelas.php?status=upload_sukses");
        } else {
            echo "Error: Gagal menyimpan data ke database.";
        }
        $stmt->close();
    } else {
        echo "Error: Terjadi kesalahan saat mengunggah file.";
    }
    $conn->close();
}
?>