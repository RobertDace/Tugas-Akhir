<?php
session_start(); 
require_once 'config.php';
$file_css = 'style.css';
include 'header.php'
?>

<body>

    <main>
        <section id="hero">
            <h1>Bermain, Belajar, dan Tumbuh Bersama Kami</h1>
            <p>Membentuk generasi cerdas dan berakhlak mulia sejak usia dini.</p>
        </section>

        <section id="profil">
            <h2>Profil Sekolah</h2>
            <p>TK Cahaya Hati adalah lembaga pendidikan anak usia dini yang berfokus pada pengembangan karakter, kreativitas, dan kemampuan kognitif anak melalui metode pembelajaran yang menyenangkan dan interaktif.</p>
        </section>
        
        <section id="cta-pendaftaran">
            <h2>Bergabunglah dengan Keluarga Besar TK Cahaya Hati!</h2>
            <p>Pendaftaran siswa baru untuk tahun ajaran mendatang telah dibuka. Klik tombol di bawah untuk mengisi formulir pendaftaran.</p>
            <a href="pendaftaran.php" class="btn-cta">Daftar Sekarang</a>
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

        <section id="blog-preview">
            <h2>Pojok Artikel & Berita</h2>
            <div class="artikel-preview-container">
                <div class="artikel-card">
                    <img src="https://via.placeholder.com/400x200.png?text=Anak-anak+Bermain" alt="Anak-anak sedang bermain">
                    <div class="artikel-card-content">
                        <h3>5 Manfaat Bermain Sambil Belajar untuk Perkembangan Anak</h3>
                        <p>Bermain adalah dunia anak-anak. Namun, lebih dari sekadar hiburan, bermain merupakan sarana penting bagi...</p>
                        <a href="artikel_detail.php?id=1" class="btn-baca">Baca Selengkapnya &rarr;</a>
                    </div>
                </div>
                <div class="artikel-card">
                    <img src="https://via.placeholder.com/400x200.png?text=Bekal+Sekolah+Sehat" alt="Bekal sekolah yang sehat">
                     <div class="artikel-card-content">
                        <h3>Tips Memilih Bekal Sehat dan Menarik untuk si Kecil</h3>
                        <p>Menyiapkan bekal sekolah seringkali menjadi tantangan bagi para orang tua. Bekal tidak hanya harus sehat...</p>
                        <a href="artikel_detail.php?id=2" class="btn-baca">Baca Selengkapnya &rarr;</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="kalender">
    <h2 class="text-center mb-4">Kalender Akademik</h2>

    <div id="kalender-wrapper">
        </div>
</section>

        <section id="testimoni">
            <h2>Apa Kata Orang Tua Murid?</h2>
            <div class="testimoni-container">
                <div class="testimoni-card">
                    <p class="testimoni-text">"Anak saya jadi lebih ceria dan percaya diri sejak sekolah di TK Cahaya Hati. Guru-gurunya sangat sabar dan kreatif. Terima kasih!"</p>
                    <h4 class="testimoni-author">- Ibu Anita, Orang Tua Murid</h4>
                </div>
                <div class="testimoni-card">
                    <p class="testimoni-text">"Fasilitasnya bersih dan aman untuk anak-anak. Metode belajarnya juga menyenangkan, anak saya tidak pernah bosan belajar."</p>
                    <h4 class="testimoni-author">- Bapak Budi, Orang Tua Murid</h4>
                </div>
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