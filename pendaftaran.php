<?php
session_start();
require_once 'config.php';

// --- BAGIAN PENJAGA HALAMAN ---
// Cek apakah pengguna sudah login sebagai Wali Murid
if (!isset($_SESSION['wali_id'])) {
    // Jika belum, simpan pesan error untuk ditampilkan di halaman login
    $_SESSION['login_error'] = "Anda harus login sebagai Wali Murid untuk mendaftarkan siswa baru.";
    // Alihkan ke halaman login dan hentikan eksekusi skrip
    header("Location: login.php");
    exit();
}

// --- BAGIAN SETUP HALAMAN ---
// Menentukan file CSS (menggunakan style login agar seragam) dan memanggil header
$file_css = 'login.css'; 
include 'header.php';
?>

<div class="login-page"> <div class="login-container" style="max-width: 800px;"> <div class="login-header">
            <h2>Pendaftaran Siswa Baru</h2>
            <p>Silakan lengkapi formulir di bawah ini untuk ananda.</p>
        </div>
        
        <form id="form-pendaftaran">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap Anak</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_panggilan" class="form-label">Nama Panggilan</label>
                    <input type="text" class="form-control" id="nama_panggilan" name="nama_panggilan">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Jenis Kelamin</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki-laki" value="Laki-laki" checked>
                        <label class="form-check-label" for="laki-laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan">
                        <label class="form-check-label" for="perempuan">Perempuan</label>
                    </div>
                </div>
            </div>
            <input type="hidden" id="nama_orang_tua" name="nama_orang_tua" value="<?php echo htmlspecialchars($_SESSION['wali_nama']); ?>">
            
            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon/WA Aktif</label>
                <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Daftarkan Ananda</button>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('form-pendaftaran').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Mengirim...';

        fetch('form_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Pendaftaran Diterima!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Selesai'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            } else {
                Swal.fire('Oops...', data.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error!', 'Tidak dapat terhubung ke server.', 'error');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Daftarkan Ananda';
        });
    });
</script>