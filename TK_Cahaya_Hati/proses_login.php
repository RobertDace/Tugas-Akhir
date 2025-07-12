<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek di tabel admin (Tidak ada perubahan)
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $admin_user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($admin_user && password_verify($password, $admin_user['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin_user['id'];
        $_SESSION['admin_username'] = $admin_user['username'];
        $_SESSION['admin_foto_profil'] = $admin_user['foto_profil'];
        header("Location: index.php");
        exit();
    }

    // Cek di tabel guru (Tidak ada perubahan)
    $stmt = $conn->prepare("SELECT * FROM guru WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $guru_user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($guru_user && password_verify($password, $guru_user['password'])) {
        session_regenerate_id(true);
        $_SESSION['guru_id'] = $guru_user['id_guru'];
        $_SESSION['guru_nama'] = $guru_user['nama_guru'];
        $_SESSION['guru_foto_profil'] = $guru_user['foto_profil'];
        header("Location: index.php");
        exit();
    }
    
    // --- PERUBAHAN DI SINI ---
    // Cek di tabel wali_murid, gunakan LEFT JOIN
    $stmt = $conn->prepare("
        SELECT w.id_wali, w.nama_wali, w.username, w.password, w.foto_profil, s.id as id_siswa, s.nama_lengkap as nama_siswa 
        FROM wali_murid w 
        LEFT JOIN calon_siswa s ON w.id_siswa = s.id 
        WHERE w.username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $wali_user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($wali_user && password_verify($password, $wali_user['password'])) {
        // Login berhasil untuk wali
        session_regenerate_id(true);
        $_SESSION['wali_id'] = $wali_user['id_wali'];
        $_SESSION['wali_nama'] = $wali_user['nama_wali'];
        $_SESSION['wali_foto_profil'] = $wali_user['foto_profil'];
        $_SESSION['id_siswa'] = $wali_user['id_siswa']; // Ini akan NULL jika belum ada anak
        $_SESSION['siswa_nama'] = $wali_user['nama_siswa']; // Ini akan NULL jika belum ada anak
        header("Location: index.php");
        exit();
    }

    // Jika tidak ada yang cocok
    $_SESSION['login_error'] = "Username atau password salah!";
    header("Location: login.php");
    exit();
}
?>