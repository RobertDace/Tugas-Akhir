</div> </div> <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Bagian 1: Logika untuk menu mobile (kode lama Anda) ---
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.admin-sidebar');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // --- Bagian 2: Logika untuk Notifikasi (kode baru) ---
        const notifIcon = document.getElementById('notif-icon');
        const notifCount = document.getElementById('notif-count');
        const notifDropdown = document.getElementById('notif-dropdown');

        if (notifIcon) {
            // Fungsi untuk mengambil notifikasi dari server
            function fetchNotifications() {
                fetch('fetch_notif.php')
                    .then(response => response.json())
                    .then(data => {
                        notifDropdown.innerHTML = ''; // Selalu kosongkan dropdown dulu
                        let unreadCount = 0;

                        if (data.length > 0) {
                            data.forEach(notif => {
                                if (notif.status_baca == 0) {
                                    unreadCount++;
                                }
                                const notifItem = document.createElement('a');
                                notifItem.href = notif.link;
                                notifItem.className = notif.status_baca == 1 ? 'notif-read' : '';
                                notifItem.innerHTML = `<strong>${notif.pesan}</strong><small>${notif.waktu}</small>`;
                                notifDropdown.appendChild(notifItem);
                            });
                        } else {
                            notifDropdown.innerHTML = '<span class="no-notif">Tidak ada notifikasi.</span>';
                        }

                        // Atur tampilan dot merah
                        if (unreadCount > 0) {
                            notifCount.textContent = unreadCount;
                            notifCount.style.display = 'flex';
                        } else {
                            notifCount.style.display = 'none';
                        }
                    });
            }

            // Aksi saat ikon lonceng diklik
            notifIcon.addEventListener('click', function(e) {
                e.preventDefault();
                notifDropdown.classList.toggle('show');
                
                if (notifCount.style.display !== 'none') {
                    fetch('mark_notif_read.php'); // Tandai notif sebagai sudah dibaca
                    notifCount.style.display = 'none'; 
                }
            });

            // Panggil fungsi saat halaman dimuat dan setiap 30 detik
            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        }
        
        // Aksi untuk menutup notif saat klik di luar area
        window.addEventListener('click', function(e) {
            if (notifDropdown && notifDropdown.classList.contains('show') && !notifIcon.contains(e.target)) {
                notifDropdown.classList.remove('show');
            }
        });
    });
    </script>
</body>
</html>