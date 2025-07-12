-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jul 2025 pada 12.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tk_cahaya_hati`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `tanggal_absen` date NOT NULL,
  `status_kehadiran` enum('Hadir','Sakit','Izin','Alpa') NOT NULL,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_siswa`, `id_kelas`, `tanggal_absen`, `status_kehadiran`, `catatan`) VALUES
(1, 1, 1, '2025-07-01', 'Hadir', NULL),
(2, 2, 1, '2025-07-01', 'Sakit', NULL),
(3, 3, 1, '2025-07-01', 'Hadir', NULL),
(5, 4, 2, '2025-07-01', 'Hadir', NULL),
(6, 5, 2, '2025-07-01', 'Hadir', NULL),
(7, 6, 2, '2025-07-01', 'Hadir', NULL),
(8, 2, 1, '2025-07-02', 'Hadir', NULL),
(9, 3, 1, '2025-07-02', 'Hadir', NULL),
(12, 2, 1, '2025-07-03', 'Izin', NULL),
(14, 3, 1, '2025-07-03', 'Hadir', NULL),
(15, 2, 1, '2025-07-24', 'Hadir', NULL),
(16, 3, 1, '2025-07-24', 'Hadir', NULL),
(17, 2, 1, '2025-07-16', 'Hadir', NULL),
(18, 3, 1, '2025-07-16', 'Hadir', NULL),
(19, 2, 1, '2025-07-10', 'Hadir', NULL),
(20, 3, 1, '2025-07-10', 'Hadir', NULL),
(21, 2, 1, '2025-07-30', 'Hadir', NULL),
(22, 3, 1, '2025-07-30', 'Hadir', NULL),
(23, 2, 1, '2025-07-31', 'Hadir', NULL),
(24, 3, 1, '2025-07-31', 'Hadir', NULL),
(25, 2, 1, '2025-07-17', 'Hadir', NULL),
(26, 3, 1, '2025-07-17', 'Hadir', NULL),
(27, 2, 1, '2025-07-09', 'Hadir', NULL),
(28, 3, 1, '2025-07-09', 'Hadir', NULL),
(29, 2, 1, '2025-07-08', 'Hadir', NULL),
(30, 3, 1, '2025-07-08', 'Hadir', NULL),
(31, 2, 1, '2025-07-07', 'Hadir', NULL),
(32, 3, 1, '2025-07-07', 'Hadir', NULL),
(33, 2, 1, '2025-07-11', 'Hadir', NULL),
(34, 3, 1, '2025-07-11', 'Hadir', NULL),
(36, 2, 1, '2025-07-25', 'Hadir', NULL),
(37, 3, 1, '2025-07-25', 'Hadir', NULL),
(40, 2, 1, '2025-06-02', 'Hadir', NULL),
(41, 3, 1, '2025-06-02', 'Hadir', NULL),
(43, 2, 1, '2025-06-03', 'Hadir', NULL),
(44, 3, 1, '2025-06-03', 'Hadir', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `foto_profil`) VALUES
(1, 'admin', '$2y$10$/83qyUG5rgdxWDWF6dSzqe0WeRQpHiz9b5g889eAkvbZVBeiH7jXq', 'default.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `artikel`
--

CREATE TABLE `artikel` (
  `id_artikel` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `judul_artikel` varchar(255) NOT NULL,
  `isi_artikel` longtext NOT NULL,
  `gambar_header` varchar(255) DEFAULT NULL,
  `status_publikasi` enum('published','draft') NOT NULL DEFAULT 'draft',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `artikel`
--

INSERT INTO `artikel` (`id_artikel`, `id_guru`, `id_kategori`, `judul_artikel`, `isi_artikel`, `gambar_header`, `status_publikasi`, `tanggal_dibuat`, `tanggal_diubah`) VALUES
(1, 1, 1, 'Selamat Datang di Website Baru TK Cahaya Hati!', 'Ini adalah tulisan pertama kami di website baru. Melalui halaman blog ini, kami akan berbagi berbagai informasi menarik, tips parenting, serta dokumentasi kegiatan anak-anak di sekolah. Nantikan terus update dari kami ya, Ayah dan Bunda!', 'artikel-1752216162-website-ai.jpg', 'published', '2025-07-11 06:12:55', '2025-07-11 06:42:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `calon_siswa`
--

CREATE TABLE `calon_siswa` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_panggilan` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `nama_orang_tua` varchar(100) NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `status_pendaftaran` enum('pending','diterima','ditolak') NOT NULL DEFAULT 'pending',
  `id_kelas` int(11) DEFAULT NULL,
  `tanggal_pendaftaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `calon_siswa`
--

INSERT INTO `calon_siswa` (`id`, `nama_lengkap`, `nama_panggilan`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `nama_orang_tua`, `nomor_telepon`, `alamat`, `status_pendaftaran`, `id_kelas`, `tanggal_pendaftaran`) VALUES
(1, 'Ahmad Budi Prasetyo', 'Budi', 'Samarinda', '2020-03-15', 'Laki-laki', 'Bapak Joko', '081234567890', 'Jl. Cendana No. 1, Samarinda', 'diterima', 2, '2025-07-01 10:07:11'),
(2, 'Citra Ayu Lestari', 'Citra', 'Balikpapan', '2020-07-22', 'Perempuan', 'Ibu Wati', '081234567891', 'Jl. Mawar No. 5, Samarinda', 'diterima', 1, '2025-07-01 10:07:11'),
(3, 'Dimas Anggara Putra', 'Dimas', 'Tenggarong', '2020-11-01', 'Laki-laki', 'Bapak Eko', '081234567892', 'Jl. Anggrek No. 12, Samarinda', 'diterima', 1, '2025-07-01 10:07:11'),
(4, 'Fiona Anindita Putri', 'Fiona', 'Samarinda', '2019-05-10', 'Perempuan', 'Ibu Rina', '081234567893', 'Jl. Flamboyan No. 8, Samarinda', 'diterima', 2, '2025-07-01 10:07:11'),
(5, 'Gilang Ramadhan', 'Gilang', 'Bontang', '2019-09-18', 'Laki-laki', 'Bapak Heru', '081234567894', 'Jl. Meranti No. 20, Samarinda', 'diterima', 2, '2025-07-01 10:07:11'),
(6, 'Hana Dahlia', 'Hana', 'Samarinda', '2019-12-30', 'Perempuan', 'Ibu Retno', '081234567895', 'Jl. Gajah Mada No. 3, Samarinda', 'diterima', 2, '2025-07-01 10:07:11'),
(20, 'Arka Wijaya', 'Arka', 'Samarinda', '2019-03-15', 'Laki-laki', 'Bapak Hendra', '081211112222', 'Jl. Pahlawan No. 10, Samarinda', 'diterima', 2, '2025-07-11 09:46:20'),
(21, 'Saskia Putri', 'Kia', 'Samarinda', '2021-05-10', 'Perempuan', 'Ibu Dewi', '085211112222', 'Jl. Siradj Salman No. 45, Samarinda', 'diterima', 1, '2025-07-11 10:03:12'),
(22, 'Bintang Putra Wicaksono', 'Bintang', 'Jakarta', '2019-01-22', 'Laki-laki', 'Bapak Agung', '085233334444', 'Jl. PM Noor No. 12, Samarinda', 'diterima', 2, '2025-07-11 10:04:03'),
(23, 'lucas', 'lucas', 'samarinda', '2021-03-23', 'Laki-laki', 'Alfian Robit Nadifi Masyhudi', '082232813197', 'Samarinda', 'diterima', 1, '2025-07-12 09:02:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_perkembangan`
--

CREATE TABLE `catatan_perkembangan` (
  `id_catatan` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `tanggal_catatan` date NOT NULL,
  `aspek_perkembangan` enum('Sosial-Emosional','Kognitif','Motorik Halus','Motorik Kasar','Seni','Bahasa') NOT NULL,
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `catatan_perkembangan`
--

INSERT INTO `catatan_perkembangan` (`id_catatan`, `id_siswa`, `id_guru`, `tanggal_catatan`, `aspek_perkembangan`, `catatan`) VALUES
(1, 23, 1, '2025-07-12', 'Kognitif', 'Sangat Fast Respond pak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri_kelas`
--

CREATE TABLE `galeri_kelas` (
  `id_foto` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `judul_foto` varchar(100) DEFAULT NULL,
  `is_favorit` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=tidak, 1=favorit',
  `tanggal_unggah` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `galeri_kelas`
--

INSERT INTO `galeri_kelas` (`id_foto`, `id_kelas`, `id_guru`, `nama_file`, `judul_foto`, `is_favorit`, `tanggal_unggah`) VALUES
(2, 1, 1, '1752087209_686ebaa9b3283.jpg', 'batikk', 1, '2025-07-09 18:53:29'),
(3, 1, 1, '1752087257_686ebad973160.jpg', 'batik 2', 1, '2025-07-09 18:54:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `status_kepegawaian` enum('PNS','Honorer','Tetap') NOT NULL DEFAULT 'Honorer',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) NOT NULL DEFAULT 'default.png',
  `id_kelas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `nip`, `jabatan`, `status_kepegawaian`, `username`, `password`, `foto_profil`, `id_kelas`) VALUES
(1, 'Indri Faulina', NULL, NULL, 'Honorer', 'guruA', '$2y$10$gSM24RhmG/ZuYYph2wK7vO4a6XBuw/m0/WbccDfCseHnPqOvbqRNO', 'default.png', 1),
(2, 'Bapak Budi Santoso', NULL, NULL, 'Honorer', 'guruB', '$2y$10$sRiCK3Zn8KuyjIabnXc4fedY5F8lEvUUVpr33GGEgRgijbSiqIAcO', 'default.png', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hari_libur`
--

CREATE TABLE `hari_libur` (
  `id_libur` int(11) NOT NULL,
  `tanggal_libur` date NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hari_libur`
--

INSERT INTO `hari_libur` (`id_libur`, `tanggal_libur`, `keterangan`) VALUES
(6, '2025-07-06', 'Libur Ditetapkan oleh Guru'),
(7, '2025-07-13', 'Libur Ditetapkan oleh Guru'),
(8, '2025-07-20', 'Libur Ditetapkan oleh Guru'),
(9, '2025-07-27', 'Libur Ditetapkan oleh Guru'),
(10, '2025-07-05', 'Libur Ditetapkan oleh Guru'),
(12, '2025-07-19', 'Libur Ditetapkan oleh Guru'),
(13, '2025-07-26', 'Libur Ditetapkan oleh Guru'),
(16, '2025-07-12', 'Libur Ditetapkan oleh Guru'),
(17, '2025-06-01', 'Libur Ditetapkan oleh Guru');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kalender_akademik`
--

CREATE TABLE `kalender_akademik` (
  `id_event` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `nama_event` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `warna_event` varchar(20) NOT NULL DEFAULT 'biru',
  `id_guru` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kalender_akademik`
--

INSERT INTO `kalender_akademik` (`id_event`, `tanggal_mulai`, `tanggal_selesai`, `nama_event`, `keterangan`, `warna_event`, `id_guru`) VALUES
(1, '2025-07-14', NULL, 'Hari Pertama Tahun Ajaran Baru', 'Siswa masuk sekolah untuk pertama kalinya di tahun ajaran ini.', 'hijau', 1),
(2, '2025-07-21', '2025-07-22', 'Lomba Mewarnai Antar Kelas', 'Diadakan di aula sekolah.', 'biru', 1),
(3, '2025-08-17', NULL, 'Libur Hari Kemerdekaan RI', 'Sekolah libur untuk memperingati HUT RI.', 'merah', 1),
(4, '2025-08-29', NULL, 'Pembagian Laporan Tengah Semester', 'Wali murid diharapkan hadir untuk mengambil laporan.', 'biru', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Info Sekolah'),
(2, 'Tips Parenting'),
(3, 'Kegiatan Siswa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`) VALUES
(1, 'Kelas A (Kelompok Bermain)'),
(2, 'Kelas B (Kelompok TK)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notif` int(11) NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_baca` tinyint(1) NOT NULL DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `role_user` enum('admin','guru','wali') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `pesan`, `waktu`, `status_baca`, `link`, `id_user`, `role_user`) VALUES
(1, 'Pendaftar baru: Park Hyung Seok', '2025-07-11 07:58:21', 1, 'admin/data_pendaftar.php', NULL, NULL),
(2, 'Pendaftar baru: Umi Dera', '2025-07-11 08:40:33', 1, 'admin/data_pendaftar.php', NULL, NULL),
(3, 'Pendaftar baru: test', '2025-07-11 09:04:01', 1, 'admin/data_pendaftar.php', NULL, NULL),
(4, 'Pendaftar baru: Arka Wijaya', '2025-07-11 09:40:56', 0, 'admin/data_pendaftar.php', NULL, NULL),
(5, 'Pendaftar baru: Bunga Citra Melati', '2025-07-11 09:41:56', 0, 'admin/data_pendaftar.php', NULL, NULL),
(6, 'Pendaftar baru: Arka Wijaya', '2025-07-11 09:46:20', 1, 'data_pendaftar.php', NULL, 'admin'),
(7, 'Siswa baru, Arka Wijaya, telah ditambahkan ke kelas Anda.', '2025-07-11 09:46:51', 1, 'absensi_kelas.php', 2, 'guru'),
(8, 'Siswa baru, Hana Dahlia, telah ditambahkan ke kelas Anda.', '2025-07-11 09:48:25', 1, 'absensi_kelas.php', 2, 'guru'),
(9, 'Siswa baru, Fiona Anindita Putri, telah ditambahkan ke kelas Anda.', '2025-07-11 09:49:22', 1, 'absensi_kelas.php', 2, 'guru'),
(10, 'Pendaftar baru: Saskia Putri', '2025-07-11 10:03:12', 1, 'data_pendaftar.php', NULL, 'admin'),
(11, 'Pendaftar baru: Bintang Putra Wicaksono', '2025-07-11 10:04:03', 1, 'data_pendaftar.php', NULL, 'admin'),
(12, 'Siswa baru, Bintang Putra Wicaksono, telah ditambahkan ke kelas Anda.', '2025-07-11 10:04:26', 1, 'absensi_kelas.php', 2, 'guru'),
(13, 'Siswa baru, Saskia Putri, telah ditambahkan ke kelas Anda.', '2025-07-11 10:04:28', 1, 'absensi_kelas.php', 1, 'guru'),
(14, 'Siswa baru, Ahmad Budi Prasetyo, telah ditambahkan ke kelas Anda.', '2025-07-12 08:32:50', 1, 'absensi_kelas.php', 2, 'guru'),
(15, 'Selamat! Ananda Ahmad Budi Prasetyo diterima di Kelas B (Kelompok TK).', '2025-07-12 08:32:50', 0, 'dashboard.php', 1, 'wali'),
(16, 'Siswa baru, Citra Ayu Lestari, telah ditambahkan ke kelas Anda.', '2025-07-12 08:32:52', 1, 'absensi_kelas.php', 1, 'guru'),
(17, 'Selamat! Ananda Citra Ayu Lestari diterima di Kelas A (Kelompok Bermain).', '2025-07-12 08:32:52', 0, 'dashboard.php', 2, 'wali'),
(18, 'Siswa baru, Dimas Anggara Putra, telah ditambahkan ke kelas Anda.', '2025-07-12 08:32:54', 1, 'absensi_kelas.php', 1, 'guru'),
(19, 'Siswa baru, Gilang Ramadhan, telah ditambahkan ke kelas Anda.', '2025-07-12 08:32:55', 1, 'absensi_kelas.php', 2, 'guru'),
(20, 'Pendaftar baru: lucas', '2025-07-12 09:02:19', 1, 'data_pendaftar.php', NULL, 'admin'),
(21, 'Siswa baru, lucas, telah ditambahkan ke kelas Anda.', '2025-07-12 09:02:46', 1, 'absensi_kelas.php', 1, 'guru'),
(22, 'Selamat! Ananda lucas diterima di Kelas A (Kelompok Bermain).', '2025-07-12 09:02:46', 1, 'dashboard.php', 5, 'wali');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman_kelas`
--

CREATE TABLE `pengumuman_kelas` (
  `id_pengumuman` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `judul_pengumuman` varchar(255) NOT NULL,
  `isi_pengumuman` text NOT NULL,
  `tanggal_posting` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimoni`
--

CREATE TABLE `testimoni` (
  `id_testimoni` int(11) NOT NULL,
  `id_wali` int(11) NOT NULL,
  `isi_testimoni` text NOT NULL,
  `tanggal_kirim` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','ditampilkan') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `testimoni`
--

INSERT INTO `testimoni` (`id_testimoni`, `id_wali`, `isi_testimoni`, `tanggal_kirim`, `status`) VALUES
(1, 5, 'anak saya jadi lebih berbakti, dan memiliki penalaran serta motorik yang bagus, dan rajin solat 5 waktu', '2025-07-12 10:00:02', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wali_murid`
--

CREATE TABLE `wali_murid` (
  `id_wali` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nama_wali` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) NOT NULL DEFAULT 'default.png',
  `hubungan` enum('Ayah','Ibu','Wali') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wali_murid`
--

INSERT INTO `wali_murid` (`id_wali`, `id_siswa`, `nama_wali`, `username`, `password`, `foto_profil`, `hubungan`) VALUES
(1, 1, 'Bapak Joko', 'ortubudi', '$2y$10$m8MV82OVI9SuXPvSz8cvU.UKVtitWS18iUKm7SnYbHz3pZtbN0sAy', 'default.png', 'Ayah'),
(2, 2, 'Wati WIrano', 'ortucitra', '$2y$10$N6Wyt1mB74kRt5RZ0hBjluGpFR7ypgJMua9TfckHINsMK1IW7g8I6', 'default.png', 'Ibu'),
(5, 23, 'Alfian Robit Nadifi Masyhudi', 'ortulucas', '$2y$10$sv02yMoXP3Xa.kwNWF5nyOpN4YRufH2sqdRcFTnTLHQiL/ifIMexS', 'default.png', 'Ayah'),
(6, NULL, 'test', 'test', '$2y$10$sv02yMoXP3Xa.kwNWF5nyOpN4YRufH2sqdRcFTnTLHQiL/ifIMexS', 'default.png', 'Ayah');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD UNIQUE KEY `siswa_per_tanggal` (`id_siswa`,`tanggal_absen`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id_artikel`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indeks untuk tabel `calon_siswa`
--
ALTER TABLE `calon_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `catatan_perkembangan`
--
ALTER TABLE `catatan_perkembangan`
  ADD PRIMARY KEY (`id_catatan`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indeks untuk tabel `galeri_kelas`
--
ALTER TABLE `galeri_kelas`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `hari_libur`
--
ALTER TABLE `hari_libur`
  ADD PRIMARY KEY (`id_libur`),
  ADD UNIQUE KEY `tanggal_libur` (`tanggal_libur`);

--
-- Indeks untuk tabel `kalender_akademik`
--
ALTER TABLE `kalender_akademik`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notif`);

--
-- Indeks untuk tabel `pengumuman_kelas`
--
ALTER TABLE `pengumuman_kelas`
  ADD PRIMARY KEY (`id_pengumuman`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id_testimoni`),
  ADD KEY `id_wali` (`id_wali`);

--
-- Indeks untuk tabel `wali_murid`
--
ALTER TABLE `wali_murid`
  ADD PRIMARY KEY (`id_wali`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id_artikel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `calon_siswa`
--
ALTER TABLE `calon_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `catatan_perkembangan`
--
ALTER TABLE `catatan_perkembangan`
  MODIFY `id_catatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `galeri_kelas`
--
ALTER TABLE `galeri_kelas`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `hari_libur`
--
ALTER TABLE `hari_libur`
  MODIFY `id_libur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `kalender_akademik`
--
ALTER TABLE `kalender_akademik`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `pengumuman_kelas`
--
ALTER TABLE `pengumuman_kelas`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id_testimoni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `wali_murid`
--
ALTER TABLE `wali_murid`
  MODIFY `id_wali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
