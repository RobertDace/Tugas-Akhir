</div> </div> <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Logika untuk menu mobile ---
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.admin-sidebar');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // --- Logika untuk Notifikasi ---
        const notifIcon = document.getElementById('notif-icon');
        const notifCount = document.getElementById('notif-count');
        const notifDropdown = document.getElementById('notif-dropdown');

        if (notifIcon) {
            // Fungsi untuk mengambil notifikasi dari server
            function fetchNotifications() {
                fetch('fetch_notif.php')
                    .then(response => response.json())
                    .then(data => {
                        // Selalu kosongkan dropdown terlebih dahulu
                        notifDropdown.innerHTML = ''; 

                        if (data.length > 0) {
                            // Jika ada notifikasi, tampilkan jumlahnya di badge
                            notifCount.textContent = data.length;
                            notifCount.style.display = 'flex';
                            
                            // Masukkan setiap notifikasi ke dalam dropdown
                            data.forEach(notif => {
                                const notifItem = document.createElement('a');
                                notifItem.href = notif.link;
                                notifItem.innerHTML = `<strong>${notif.pesan}</strong><small>${notif.waktu}</small>`;
                                notifDropdown.appendChild(notifItem);
                            });
                        } else {
                            // JIKA TIDAK ADA NOTIFIKASI, TAMPILKAN PESAN INI
                            notifDropdown.innerHTML = '<span class="no-notif">Tidak ada notifikasi baru.</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching notifications:', error);
                        notifDropdown.innerHTML = '<span class="no-notif">Gagal memuat notifikasi.</span>';
                    });
            }

            // Tampilkan/sembunyikan dropdown saat ikon lonceng diklik
            notifIcon.addEventListener('click', function(e) {
                e.preventDefault();
                notifDropdown.classList.toggle('show');
                // Saat dropdown dibuka, sembunyikan badge angka
                notifCount.style.display = 'none';
            });

            // Panggil fungsi pertama kali saat halaman dimuat
            fetchNotifications();
            // Atur untuk memeriksa notifikasi baru setiap 30 detik
            setInterval(fetchNotifications, 30000); 
        }

        // Tutup dropdown jika klik di luar area notifikasi
        window.addEventListener('click', function(e) {
            if (notifDropdown.classList.contains('show') && !notifIcon.contains(e.target)) {
                notifDropdown.classList.remove('show');
            }
        });
    });
    </script>
</body>
</html>