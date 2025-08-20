<?php
session_start(); 
require_once 'config.php';
$file_css = 'style.css';
include 'header.php'
?>

<body class="index-bg">

    <main>
        <section id="hero" style="background-image: url('uploads/card1.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Selamat Datang di TK Cahaya Hati</h1>
                <p>Kami percaya bermain adalah bagian dari belajar!</p>
            </div>
        </section>


        <section id="hero" style="background-image: url('uploads/card2.jpg');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h2>Profil Sekolah</h2>
                <p>TK Cahaya Hati adalah lembaga pendidikan anak usia dini yang berfokus pada pengembangan karakter, kreativitas, dan kemampuan kognitif anak melalui metode pembelajaran yang menyenangkan dan interaktif.</p>
            </div>
        </section>
        
        <section id="cta-pendaftaran">
            <div class="cta-overlay"></div>
            <div class="cta-content">
                <h2>Bergabunglah dengan Keluarga Besar TK Cahaya Hati!</h2>
                <p>Pendaftaran siswa baru untuk tahun ajaran mendatang telah dibuka. Klik tombol di bawah untuk mengisi formulir pendaftaran.</p>
                <a href="pendaftaran.php" class="btn-cta">Daftar Sekarang</a>
            </div>
        </section>


        <section id="galeri">
            <div class="container">
                <h2 class="text-center mb-4">Galeri Kegiatan</h2>
                <div class="carousel-wrapper">
                    <div id="galeriCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $result_galeri = $conn->query("SELECT nama_file, judul_foto FROM galeri_kelas WHERE is_favorit = 1 ORDER BY tanggal_unggah DESC LIMIT 5");
                            if ($result_galeri->num_rows > 0) {
                                $first = true;
                                while($foto = $result_galeri->fetch_assoc()) {
                                    $active_class = $first ? 'active' : '';
                            ?>
                                    <div class="carousel-item <?php echo $active_class; ?>">
                                        <img src="uploads/<?php echo htmlspecialchars($foto['nama_file']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($foto['judul_foto']); ?>">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5><?php echo htmlspecialchars($foto['judul_foto']); ?></h5>
                                        </div>
                                    </div>
                            <?php
                                    $first = false;
                                }
                            } else {
                                echo '<div class="carousel-item active"><img src="https://via.placeholder.com/1200x500.png?text=Belum+Ada+Foto+Favorit" class="d-block w-100" alt="Galeri Kosong"></div>';
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galeriCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galeriCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                    </div>
                </div>
            </div>
        </section>

        <?php
// Ambil 3 artikel terbaru
$artikel_result = $conn->query("
    SELECT id_artikel, judul_artikel, gambar_header, isi_artikel, tanggal_dibuat 
    FROM artikel 
    WHERE status_publikasi='published'
    ORDER BY tanggal_dibuat DESC 
    LIMIT 3
");
?>

<section id="blog-preview">
  <h2>Artikel & Berita Terbaru</h2>
  <div class="artikel-preview-container">
    <?php while($a = $artikel_result->fetch_assoc()): ?>
      <div class="artikel-card">
        <a href="artikel_detail.php?id=<?= $a['id_artikel'] ?>">
          <img src="uploads/artikel/<?= htmlspecialchars($a['gambar_header']) ?>" alt="<?= htmlspecialchars($a['judul_artikel']) ?>">
        </a>
        <div class="artikel-card-content">
          <h3>
            <a href="artikel_detail.php?id=<?= $a['id_artikel'] ?>" style="color: inherit; text-decoration: none;">
              <?= htmlspecialchars($a['judul_artikel']) ?>
            </a>
          </h3>
          <p><?= htmlspecialchars(mb_substr(strip_tags($a['isi_artikel']),0,120)) ?>...</p>
          <a href="artikel_detail.php?id=<?= $a['id_artikel'] ?>" class="btn-baca">Baca Selengkapnya</a>
        </div>
      </div>
    <?php endwhile ?>
  </div>
  <div style="text-align:center;margin-top:2rem;">
    <a href="blog.php" class="btn-cta">Lihat Semua Berita</a>
  </div>
</section>

        <section id="kalender">
    <h2 class="text-center mb-4">Kalender Akademik</h2>

    <div id="kalender-wrapper">
        </div>
</section>

<?php
$testi_result = $conn->query("
    SELECT t.isi_testimoni, w.nama_wali 
    FROM testimoni t 
    JOIN wali_murid w ON t.id_wali = w.id_wali 
    WHERE t.status='ditampilkan'
    ORDER BY t.tanggal_kirim DESC
    LIMIT 5
");
?>
<section id="testimoni">
  <h2>Apa Kata Orang Tua?</h2>
  <div class="testimoni-container">
    <?php while($t = $testi_result->fetch_assoc()): ?>
      <div class="testimoni-card">
        <div class="testimoni-text"><?= nl2br(htmlspecialchars($t['isi_testimoni'])) ?></div>
        <div class="testimoni-author">— <?= htmlspecialchars($t['nama_wali']) ?></div>
      </div>
    <?php endwhile ?>
  </div>
</section>


        <section id="faq">
            <h2>Pertanyaan Umum (FAQ)</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <h3 class="faq-question">Kapan pendaftaran siswa baru dibuka?</h3>
                    <p class="faq-answer">Pendaftaran untuk tahun ajaran baru biasanya kami buka mulai bulan Maret hingga Juni setiap tahunnya. Pantau terus website ini untuk informasi tanggal pastinya.</p>
                </div>
                <div class="faq-item">
                    <h3 class="faq-question">Berapa biaya pendaftaran dan SPP bulanan?</h3>
                    <p class="faq-answer">Untuk rincian biaya, silakan hubungi administrasi kami melalui nomor telepon yang tertera atau datang langsung ke sekolah pada jam kerja.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>