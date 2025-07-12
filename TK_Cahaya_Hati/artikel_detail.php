<?php
require_once 'config.php';

$id_artikel = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_artikel == 0) {
    header("Location: blog.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT a.judul_artikel, a.isi_artikel, a.gambar_header, a.tanggal_dibuat, g.nama_guru, k.nama_kategori 
    FROM artikel a 
    JOIN guru g ON a.id_guru = g.id_guru 
    LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
    WHERE a.id_artikel = ? AND a.status_publikasi = 'published'");
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: blog.php");
    exit();
}
$artikel = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artikel['judul_artikel']); ?> - TK Cahaya Hati</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header>
        <nav>
            <div class="logo">
                <a href="index.php">TK Cahaya Hati</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php#profil">Profil</a></li>
                <li><a href="index.php#galeri">Galeri</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="index.php#kalender">Kalender</a></li>
                <li><a href="index.php#testimoni">Testimoni</a></li>
                <li><a href="index.php#faq">FAQ</a></li>
            </ul>
            <div class="dropdown">
                <button class="nav-login-btn">Login</button>
                <div class="dropdown-content">
                    <a href="login.php">Login Admin</a>
                    <a href="login.php">Login Guru</a>
                    <a href="login.php">Login Wali Murid</a>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-4">
        <div class="blog-layout">
            <div class="main-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($artikel['nama_kategori'] ?? 'Artikel'); ?></li>
                    </ol>
                </nav>

                <article class="blog-post">
                    <p class="text-primary fw-bold"><?php echo htmlspecialchars($artikel['nama_kategori'] ?? ''); ?></p>
                    <h1 class="blog-post-title"><?php echo htmlspecialchars($artikel['judul_artikel']); ?></h1>
                    <p class="blog-post-meta">
                        <?php echo date('d F Y', strtotime($artikel['tanggal_dibuat'])); ?> oleh <?php echo htmlspecialchars($artikel['nama_guru']); ?>
                    </p>
                    <img src="uploads/artikel/<?php echo htmlspecialchars($artikel['gambar_header'] ?? 'default-artikel.png'); ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($artikel['judul_artikel']); ?>">
                    <div class="blog-post-content">
                        <?php echo nl2br(htmlspecialchars($artikel['isi_artikel'])); ?>
                    </div>
                </article>
                <a href="blog.php" class="btn btn-secondary mt-4">&larr; Kembali ke Daftar Artikel</a>
            </div>

            <aside class="sidebar">
                <div class="sidebar-widget">
                    <h5>Pengumuman Penting</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Pendaftaran Tahun Ajaran Baru Dibuka!</a></li>
                        <li><a href="#">Jadwal Kegiatan Karyawisata</a></li>
                    </ul>
                </div>
                <div class="sidebar-widget">
                    <h5>Kategori Artikel</h5>
                    <ul class="list-unstyled">
                        <?php
                        $kategori_result = $conn->query("SELECT nama_kategori FROM kategori ORDER BY nama_kategori ASC");
                        while($kat = $kategori_result->fetch_assoc()){
                            echo '<li><a href="#">' . htmlspecialchars($kat['nama_kategori']) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </aside>
        </div>
    </main>

    <footer class="mt-5">
        <p>&copy; 2025 TK Cahaya Hati. Dibuat dengan ❤️ oleh Alfian Robit Nadifi Masyhudi</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>