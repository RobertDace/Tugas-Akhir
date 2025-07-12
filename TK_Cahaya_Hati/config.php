<?php
// --- Konfigurasi Database ---
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "tk_cahaya_hati";

// --- Membuat Koneksi ke Database ---
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>