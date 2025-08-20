<?php
require_once '../config.php';
session_start();

// Keamanan: Pastikan hanya admin yang bisa mengakses dan semua parameter ada
if (!isset($_SESSION['admin_id']) || !isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: dashboard.php");
    exit();
}

$id_siswa = (int)$_GET['id'];
$action = $_GET['action'];

// Jika aksinya adalah 'terima'
if ($action == 'terima') {
    $conn->begin_transaction(); // Mulai transaksi untuk keamanan data
    try {
        // 1. Ambil data siswa untuk dihitung usianya
        $stmt_siswa = $conn->prepare("SELECT nama_lengkap, tanggal_lahir FROM calon_siswa WHERE id = ?");
        $stmt_siswa->bind_param("i", $id_siswa);
        $stmt_siswa->execute();
        $result_siswa = $stmt_siswa->get_result();
        $data_siswa = $result_siswa->fetch_assoc();
        $nama_siswa = $data_siswa['nama_lengkap'];
        $stmt_siswa->close();

        // 2. Hitung usia dalam tahun penuh
        $tgl_lahir = new DateTime($data_siswa['tanggal_lahir']);
        $hari_ini = new DateTime('today');
        $usia = $hari_ini->diff($tgl_lahir)->y;

        // 3. Logika penentuan kelas berdasarkan usia
        // ID Kelas 1 untuk TK A (4 tahun), ID Kelas 2 untuk TK B (5-6 tahun)
        $id_kelas_tujuan = 0;
        if ($usia >= 4 && $usia < 5) {
            $id_kelas_tujuan = 1; // Masuk Kelas A
        } elseif ($usia >= 5 && $usia <= 6) {
            $id_kelas_tujuan = 2; // Masuk Kelas B
        } else {
            // Jika usia tidak sesuai kriteria, batalkan proses
            throw new Exception("Usia tidak sesuai kriteria (4-6 tahun).");
        }

        // 4. Update status dan kelas siswa
        $stmt_update = $conn->prepare("UPDATE calon_siswa SET status_pendaftaran = 'diterima', id_kelas = ? WHERE id = ?");
        $stmt_update->bind_param("ii", $id_kelas_tujuan, $id_siswa);
        $stmt_update->execute();
        $stmt_update->close();

        // 5. Buat notifikasi untuk GURU yang bersangkutan
        $guru_target_stmt = $conn->prepare("SELECT id_guru FROM guru WHERE id_kelas = ?");
        $guru_target_stmt->bind_param("i", $id_kelas_tujuan);
        $guru_target_stmt->execute();
        $guru_target_result = $guru_target_stmt->get_result();
        if ($guru_target_result->num_rows > 0) {
            $id_guru_target = $guru_target_result->fetch_assoc()['id_guru'];
            $pesan_guru = "Siswa baru, " . $nama_siswa . ", telah ditambahkan ke kelas Anda.";
            $link_guru = "absensi_kelas.php";
            $stmt_notif = $conn->prepare("INSERT INTO notifikasi (pesan, link, id_user, role_user) VALUES (?, ?, ?, 'guru')");
            $stmt_notif->bind_param("ssi", $pesan_guru, $link_guru, $id_guru_target);
            $stmt_notif->execute();
            $stmt_notif->close();
        }
        $guru_target_stmt->close();
        
        // 6. Buat notifikasi untuk WALI MURID yang bersangkutan
        $wali_target_stmt = $conn->prepare("SELECT id_wali FROM wali_murid WHERE id_siswa = ?");
        $wali_target_stmt->bind_param("i", $id_siswa);
        $wali_target_stmt->execute();
        $wali_target_result = $wali_target_stmt->get_result();
        if($wali_target_result->num_rows > 0){
            $id_wali_target = $wali_target_result->fetch_assoc()['id_wali'];
            $nama_kelas = $conn->query("SELECT nama_kelas FROM kelas WHERE id_kelas = $id_kelas_tujuan")->fetch_assoc()['nama_kelas'];
            $pesan_wali = "Selamat! Ananda " . $nama_siswa . " diterima di " . $nama_kelas . ".";
            $link_wali = "dashboard.php";
            $stmt_notif = $conn->prepare("INSERT INTO notifikasi (pesan, link, id_user, role_user) VALUES (?, ?, ?, 'wali')");
            $stmt_notif->bind_param("ssi", $pesan_wali, $link_wali, $id_wali_target);
            $stmt_notif->execute();
            $stmt_notif->close();
        }
        $wali_target_stmt->close();
        
        // Jika semua berhasil, simpan perubahan permanen
        $conn->commit();

    } catch (Exception $e) {
        // Jika ada error di tengah jalan, batalkan semua perubahan
        $conn->rollback();
        header("Location: data_pendaftar.php?status=gagal&alasan=" . urlencode($e->getMessage()));
        exit();
    }

} elseif ($action == 'tolak') {
    // Jika aksinya adalah 'tolak'
    $stmt_update = $conn->prepare("UPDATE calon_siswa SET status_pendaftaran = 'ditolak' WHERE id = ?");
    $stmt_update->bind_param("i", $id_siswa);
    $stmt_update->execute();
    $stmt_update->close();
}

$conn->close();
// Kembali ke halaman data pendaftar dengan pesan sukses
header("Location: data_pendaftar.php?status=sukses");
exit();
?>