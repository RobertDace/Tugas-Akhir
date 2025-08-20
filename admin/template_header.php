<?php
// dan diletakkan SEBELUM kode HTML apapun.
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("location: ../login.php");
    exit();
}

// Logika untuk menampilkan judul halaman secara dinamis
$filename = basename($_SERVER['PHP_SELF']);
$page_title = 'Dashboard'; // Judul default
if (strpos($filename, 'pendaftar') !== false) {
    $page_title = 'Data Pendaftar';
} elseif (strpos($filename, 'guru') !== false) {
    $page_title = 'Manajemen Guru';
} elseif (strpos($filename, 'wali') !== false) {
    $page_title = 'Manajemen Wali Murid';
} elseif (strpos($filename, 'profil') !== false) {
    $page_title = 'Profil Admin';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
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
                <img src="../uploads/profil/<?php echo htmlspecialchars($_SESSION['admin_foto_profil'] ?? 'default.png'); ?>" alt="Foto Profil">
                <h4><?php echo htmlspecialchars($_SESSION['admin_username']); ?></h4>
                <p>Administrator</p>
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
                <li><a href="data_pendaftar.php">📋 Data Pendaftar</a></li>
                <li><a href="manajemen_guru.php">👩‍🏫 Manajemen Guru</a></li>
                <li><a href="manajemen_wali.php">👨‍👩‍👧 Manajemen Wali Murid</a></li>
                <li><a href="manajemen_testimoni.php" class="menu-testimoni">⭐ Manajemen Testimoni</a></li>
            </ul>
            
            <div class="logout-area">
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>

        <div id="notif-dropdown" class="notif-dropdown-content"></div>

        <div class="admin-main-content">