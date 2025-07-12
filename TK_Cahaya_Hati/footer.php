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
                        <li><i class="fas fa-map-marker-alt me-2"></i> TK Cahaya Hati</li>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kalenderWrapper = document.getElementById('kalender-wrapper');

    // Fungsi untuk memuat kalender
    function loadCalendar(url) {
        // Tampilkan loading spinner atau pesan (opsional)
        kalenderWrapper.innerHTML = '<p style="text-align:center;">Memuat kalender...</p>';

        fetch(url)
            .then(response => response.text())
            .then(html => {
                kalenderWrapper.innerHTML = html;
            })
            .catch(error => {
                kalenderWrapper.innerHTML = '<p style="text-align:center; color:red;">Gagal memuat kalender.</p>';
                console.error('Error fetching calendar:', error);
            });
    }

    // Muat kalender untuk pertama kali saat halaman dibuka
    loadCalendar('get_kalender.php');

    // Tambahkan event listener untuk menangani klik pada navigasi
    kalenderWrapper.addEventListener('click', function(event) {
        // Cek apakah yang diklik adalah link navigasi kalender
        if (event.target.matches('a.calendar-nav')) {
            event.preventDefault(); // Mencegah link berpindah halaman
            const url = event.target.getAttribute('href');
            loadCalendar('get_kalender.php' + url); // Panggil fungsi load dengan URL baru
        }
    });
});
</script>
</body>
</html>