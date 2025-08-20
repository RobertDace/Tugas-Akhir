<?php
require_once 'config.php'; // Panggil koneksi database
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - TK Cahaya Hati</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/blog_style.css">
</head>

<body class="blog-page">

    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di TK Cahaya Hati</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/blog_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

    <?php if (isset($file_css)): ?>
        <link rel="stylesheet" href="css/<?php echo $file_css; ?>">
    <?php endif; ?>
</head>
<body class="blog-page">

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
            <a href="login.php" class="nav-login-btn">Login</a>
            </div>
        </nav>
    </header>
    <main>
        <div class="page-header">
            <h1>Pojok Artikel & Berita</h1>
            <p>Kumpulan cerita, tips, dan informasi seputar pendidikan anak usia dini dari TK Cahaya Hati.</p>
        </div>

        <div class="container mt-5">
            <div class="row">
                <?php
                // Ambil semua artikel yang statusnya 'published'
                $sql = "SELECT a.id_artikel, a.judul_artikel, a.isi_artikel, a.gambar_header, a.tanggal_dibuat, g.nama_guru 
                        FROM artikel a
                        JOIN guru g ON a.id_guru = g.id_guru
                        WHERE a.status_publikasi = 'published'
                        ORDER BY a.tanggal_dibuat DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="artikel-card">
  <img src="uploads/artikel/<?= htmlspecialchars($row['gambar_header']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['judul_artikel']) ?>">
  <div class="artikel-card-content">
    <h3 class="card-title"><?= htmlspecialchars($row['judul_artikel']) ?></h3>
    <p class="card-text"><?= htmlspecialchars(mb_substr(strip_tags($row['isi_artikel']),0,120)) ?>...</p>
    <a href="artikel_detail.php?id=<?= $row['id_artikel'] ?>" class="btn-baca">Baca Selengkapnya</a>
  </div>
</div>

                    </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center'>Belum ada artikel yang dipublikasikan.</p>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </main>

    <footer class="footer-baru">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
                    <h5 class="footer-title">TK Cahaya Hati</h5>
                    <p>Mewujudkan potensi anak bangsa melalui pendidikan usia dini yang inovatif dan berkarakter untuk kemajuan bersama.</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Layanan</h5>
                    <ul class="list-unstyled">
                        <li><a href="pendaftaran.php">Pendaftaran Online</a></li>
                        <li><a href="#">Laporan Kegiatan</a></li>
                        <li><a href="#">Notifikasi</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Dusun Nyato, RW.07, RW.02, Desa Gelagah, Kecamatan Pakuniran, Kabupaten Probolinggo, Jawa Timur</li>
                        <li><i class="fas fa-phone me-2"></i> +62 822-3281-3197</li>
                        <li><i class="fas fa-envelope me-2"></i> tkcahayahatiglagah2007@gmail.com</li>
                    </ul>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TK Cahaya Hati. Dibuat dengan ❤️ oleh Alfian Robit Nadifi Masyhudi</p>
                <div>
                    <a href="#">Privacy Policy</a>
                    <span class="mx-2">|</span>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>