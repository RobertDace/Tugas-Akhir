<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di TK Cahaya Hati</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

    <?php if (isset($file_css)): ?>
        <link rel="stylesheet" href="css/<?php echo $file_css; ?>">
    <?php endif; ?>
</head>
<body>

    <header>
        <nav>
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: white;">TK Cahaya Hati</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php#profil">Profil</a></li>
                <li><a href="index.php#galeri">Galeri</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="index.php#kalender">Kalender</a></li>
                <li><a href="index.php#testimoni">Testimoni</a></li>
                <li><a href="index.php#faq">FAQ</a></li>
            </ul>
            <div class="login-display">
    <?php
    // Cek apakah ada sesi login (admin, guru, atau wali)
    if (isset($_SESSION['admin_id']) || isset($_SESSION['guru_id']) || isset($_SESSION['wali_id'])):

        // Tentukan variabel berdasarkan peran yang sedang login
        $dashboard_link = '';
        $user_name = '';

        if (isset($_SESSION['admin_id'])) {
            $dashboard_link = 'admin/dashboard.php';
            $user_name = $_SESSION['admin_username'];
        } elseif (isset($_SESSION['guru_id'])) {
            $dashboard_link = 'guru/dashboard.php';
            $user_name = $_SESSION['guru_nama'];
        } elseif (isset($_SESSION['wali_id'])) {
            $dashboard_link = 'wali/dashboard.php';
            $user_name = $_SESSION['wali_nama'];
        }
    ?>
        <span class="welcome-text">Halo, <?php echo htmlspecialchars($user_name); ?>!</span>
        <a href="<?php echo $dashboard_link; ?>" class="nav-login-btn">Dashboard</a>
        <a href="admin/logout.php" class="nav-logout-btn">Logout</a>

    <?php else: ?>
        <a href="login.php" class="nav-login-btn">Login</a>
    <?php endif; ?>
</div>
            </div>
        </nav>
    </header>

