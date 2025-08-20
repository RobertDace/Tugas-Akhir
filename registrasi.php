<?php
// Menentukan file CSS spesifik untuk halaman ini
$file_css = 'login.css';
include 'header.php';
?>

<div class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h2>Buat Akun Baru</h2>
            <p>Daftar sebagai Guru atau Wali Murid</p>
        </div>
        <form action="proses_registrasi.php" method="POST">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Daftar Sebagai</label>
                <select id="role" name="role" required style="padding: 0.8rem; border: 2px solid #b3e5fc; border-radius: 8px; font-size: 1rem;">
                    <option value="">-- Pilih Peran --</option>
                    <option value="guru">Guru</option>
                    <option value="wali">Wali Murid</option>
                </select>
            </div>

            <div class="form-group" id="kode-verifikasi-group" style="display: none;">
                <label for="kode_verifikasi">Kode Verifikasi Guru</label>
                <input type="text" id="kode_verifikasi" name="kode_verifikasi">
            </div>
            <button type="submit">Daftar</button>
        </form>
        <div class="back-to-home">
            <a href="login.php">&larr; Sudah punya akun? Login</a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

<script>
    document.getElementById('role').addEventListener('change', function() {
        var kodeVerifikasiGroup = document.getElementById('kode-verifikasi-group');
        if (this.value === 'guru') {
            kodeVerifikasiGroup.style.display = 'block';
            document.getElementById('kode_verifikasi').required = true;
        } else {
            kodeVerifikasiGroup.style.display = 'none';
            document.getElementById('kode_verifikasi').required = false;
        }
    });
</script>