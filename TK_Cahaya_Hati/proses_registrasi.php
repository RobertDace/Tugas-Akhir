<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $kode_verifikasi_input = isset($_POST['kode_verifikasi']) ? trim($_POST['kode_verifikasi']) : '';
    $KODE_RAHASIA_GURU = "GURUHEBAT2025";

    // Cek jika username sudah ada
    $stmt_check = $conn->prepare("SELECT username FROM guru WHERE username = ? UNION SELECT username FROM admin WHERE username = ? UNION SELECT username FROM wali_murid WHERE username = ?");
    $stmt_check->bind_param("sss", $username, $username, $username);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        $_SESSION['login_error'] = "Username '{$username}' sudah digunakan. Silakan pilih yang lain.";
        header("location: login.php");
        exit();
    }
    $stmt_check->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if ($role == 'guru') {
        if ($kode_verifikasi_input !== $KODE_RAHASIA_GURU) {
            $_SESSION['login_error'] = "Kode verifikasi guru salah!";
            header("location: login.php");
            exit();
        }
        $stmt_insert = $conn->prepare("INSERT INTO guru (nama_guru, username, password) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $nama_lengkap, $username, $password_hash);

    } elseif ($role == 'wali') {
        // PERUBAHAN DI SINI: Sekarang pendaftaran wali diizinkan
        $stmt_insert = $conn->prepare("INSERT INTO wali_murid (nama_wali, username, password) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $nama_lengkap, $username, $password_hash);
    
    } else {
        // Jika peran tidak valid
        $_SESSION['login_error'] = "Peran yang dipilih tidak valid.";
        header("location: registrasi.php");
        exit();
    }

    // Eksekusi query
    if (isset($stmt_insert) && $stmt_insert->execute()) {
        $_SESSION['login_error'] = "Pendaftaran berhasil! Silakan login dengan akun Anda.";
        header("location: login.php");
    } else {
        $_SESSION['login_error'] = "Terjadi kesalahan saat pendaftaran.";
        header("location: registrasi.php");
    }
    $stmt_insert->close();
    $conn->close();
    exit();
}
?>