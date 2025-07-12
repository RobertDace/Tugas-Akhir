<?php
session_start(); // Mulai sesi untuk mendapatkan id_wali
require_once 'config.php';

header('Content-Type: application/json');
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan wali murid yang submit form ini sedang login
    if (!isset($_SESSION['wali_id'])) {
        $response['status'] = 'error';
        $response['message'] = 'Sesi Anda telah berakhir. Silakan login kembali untuk mendaftar.';
        echo json_encode($response);
        exit();
    }

    $id_wali_login = $_SESSION['wali_id'];
    
    // Ambil semua data dari form
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $nama_panggilan = $_POST['nama_panggilan'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $nama_orang_tua = $_POST['nama_orang_tua'] ?? '';
    $nomor_telepon = $_POST['nomor_telepon'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    // Validasi dasar
    if (empty($nama_lengkap) || empty($tanggal_lahir) || empty($nama_orang_tua)) {
        $response['status'] = 'error';
        $response['message'] = 'Mohon lengkapi semua data yang wajib diisi.';
    } else {
        $conn->begin_transaction();
        try {
            // 1. Simpan data siswa baru seperti biasa
            $stmt_siswa = $conn->prepare("INSERT INTO calon_siswa (nama_lengkap, nama_panggilan, tempat_lahir, tanggal_lahir, jenis_kelamin, nama_orang_tua, nomor_telepon, alamat, status_pendaftaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt_siswa->bind_param("ssssssss", $nama_lengkap, $nama_panggilan, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $nama_orang_tua, $nomor_telepon, $alamat);
            $stmt_siswa->execute();
            $id_siswa_baru = $conn->insert_id; // Ambil ID anak yang baru dibuat
            $stmt_siswa->close();

            // 2. TAUTKAN ID ANAK BARU INI KE AKUN WALI YANG SEDANG LOGIN
            $stmt_update_wali = $conn->prepare("UPDATE wali_murid SET id_siswa = ? WHERE id_wali = ?");
            $stmt_update_wali->bind_param("ii", $id_siswa_baru, $id_wali_login);
            $stmt_update_wali->execute();
            $stmt_update_wali->close();
            
            // 3. PERBARUI SESI WALI AGAR LANGSUNG TERHUBUNG
            $_SESSION['id_siswa'] = $id_siswa_baru;
            $_SESSION['siswa_nama'] = $nama_lengkap;

            // 4. Buat notifikasi untuk ADMIN
            $pesan_notif = "Pendaftar baru: " . $nama_lengkap;
            $link_notif = "data_pendaftar.php";
            $stmt_notif = $conn->prepare("INSERT INTO notifikasi (pesan, link, role_user) VALUES (?, ?, 'admin')");
            $stmt_notif->bind_param("ss", $pesan_notif, $link_notif);
            $stmt_notif->execute();
            $stmt_notif->close();

            // Simpan semua perubahan jika berhasil
            $conn->commit();
            $response['status'] = 'success';
            $response['message'] = "Pendaftaran untuk ananda $nama_lengkap telah berhasil kami terima.";

        } catch (mysqli_sql_exception $exception) {
            $conn->rollback(); // Batalkan semua jika ada error
            $response['status'] = 'error';
            $response['message'] = 'Terjadi kesalahan pada database.';
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode pengiriman tidak valid.';
}

$conn->close();
echo json_encode($response);
?>