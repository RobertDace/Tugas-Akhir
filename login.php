<?php
session_start();

// Cek jika sudah ada sesi login, redirect ke dashboard masing-masing
if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit();
}
if (isset($_SESSION['guru_id'])) {
    header('Location: guru/dashboard.php');
    exit();
}
if (isset($_SESSION['wali_id'])) {
    header('Location: wali/dashboard.php');
    exit();
}

// Logika untuk menampilkan pesan error
$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

// Menentukan file CSS spesifik untuk halaman ini
$file_css = 'login.css';

// Memanggil header
include 'header.php';
?>

<div class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h2>Portal Login</h2>
            <p>TK Cahaya Hati</p>
        </div>
        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <?php if (!empty($error_message)): ?>
                <div id="error-message" style="display: block;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <button type="submit">Login</button>
        </form>
        <div class="back-to-home">
            <a href="index.php">&larr; Kembali ke Halaman Utama</a>
        </div>
        <div class="register-link">
            Tidak punya akun? <a href="registrasi.php">Buat Akun di sini</a>
        </div>
    </div>
</div>
<?php
// Memanggil footer
include 'footer.php';
?>