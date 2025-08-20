<?php
session_start();

if (!isset($_SESSION['guru_id'])) {
    header("location: ../login.php");
    exit();
} // <-- Tanda '}' penutup ini yang mungkin hilang

// Logika untuk menampilkan judul halaman secara dinamis
$filename = basename($_SERVER['PHP_SELF']);
$page_title = 'Dashboard'; // Default
if (strpos($filename, 'absensi') !== false) {
    $page_title = 'Absensi Kelas';
} elseif (strpos($filename, 'perkembangan') !== false) {
    $page_title = 'Perkembangan Siswa';
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
    <title><?php echo $page_title; ?> - Portal Guru</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    
    <div class="admin-mobile-header">
        <button class="menu-toggle">☰</button>
        <span class="mobile-header-title"><?php echo $page_title; ?></span>
    </div>

    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <div class="sidebar-profile">
                <img src="../uploads/profil/<?php echo htmlspecialchars($_SESSION['guru_foto_profil'] ?? 'default.png'); ?>" alt="Foto Profil">
                <h4><?php echo htmlspecialchars($_SESSION['guru_nama']); ?></h4>
                <p>Guru</p>
                <div class="profile-actions">
                    <a href="profil.php" class="btn-profile-action">✏️ Edit</a>
                    <a href="#" id="notif-icon" class="btn-profile-action">
                        🔔
                        <span id="notif-count" class="notif-badge"></span>
                    </a>
                </div>
            </div>

            <ul>
                <li><a href="../index.php">🏠 Beranda Situs</a></li>
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="absensi_kelas.php">✅ Absensi</a></li>
                 <li><a href="manajemen_artikel.php">📰 Artikel & Berita</a></li>
                <li><a href="manajemen_kalender.php">🗓️ Kalender Akademik</a></li>
                <li><a href="perkembangan_siswa.php">📈 Perkembangan Siswa</a></li>
            </ul>
            
            <div class="logout-area">
                <a href="../admin/logout.php" class="btn-logout">Logout</a>
            </div>
        </div>

        <div id="notif-dropdown" class="notif-dropdown-content"></div>

        <div class="admin-main-content">