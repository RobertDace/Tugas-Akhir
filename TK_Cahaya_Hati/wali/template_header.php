<?php
session_start();

// Cek apakah pengguna (wali murid) sudah login
if (!isset($_SESSION['wali_id'])) {
    header("location: ../login.php");
    exit();
}

// Logika untuk judul halaman dinamis
$filename = basename($_SERVER['PHP_SELF']);
$page_title = 'Dashboard'; // Default
if (strpos($filename, 'absensi') !== false) {
    $page_title = 'Riwayat Absensi';
} elseif (strpos($filename, 'perkembangan') !== false) {
    $page_title = 'Catatan Perkembangan';
} elseif (strpos($filename, 'galeri') !== false) {
    $page_title = 'Galeri Kelas';
} elseif (strpos($filename, 'pengumuman') !== false) {
    $page_title = 'Papan Pengumuman';
} elseif (strpos($filename, 'profil') !== false) {
    $page_title = 'Profil Saya';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Portal Wali Murid</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-mobile-header">
        </div>
    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <div class="sidebar-profile">
                <img src="../uploads/profil/<?php echo htmlspecialchars($_SESSION['wali_foto_profil'] ?? 'default.png'); ?>" alt="Foto Profil">
                <h4><?php echo htmlspecialchars($_SESSION['wali_nama']); ?></h4>
                <p>Wali Murid dari <br><strong><?php echo htmlspecialchars($_SESSION['siswa_nama']);?></strong></p>
                <div class="profile-actions">
                    <a href="profil.php" class="btn-profile-action">✏️ Edit</a>
                    <a href="#" id="notif-icon" class="btn-profile-action">
                        🔔
                        <span id="notif-count" class="notif-badge"></span>
                    </a>
                </div>
            </div>
            <ul>
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="lihat_absensi.php">✅ Riwayat Absensi</a></li>
                <li><a href="lihat_perkembangan.php">📈 Catatan Perkembangan</a></li>
                <li><a href="lihat_galeri.php">🖼️ Galeri Kelas</a></li>
                <li><a href="lihat_pengumuman.php">📢 Papan Pengumuman</a></li>
                <li><a href="kirim_testimoni.php">⭐ Kirim Testimoni</a></li>
                <li><a href="../index.php">🏠 Beranda Situs</a></li>
            </ul>
            <div class="logout-area">
                <a href="../admin/logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        <div id="notif-dropdown" class="notif-dropdown-content"></div>
        <div class="admin-main-content">