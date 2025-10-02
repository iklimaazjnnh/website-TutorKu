-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Okt 2025 pada 10.36
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutorku`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'admin2', '654321');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_room`
--

CREATE TABLE `chat_room` (
  `id` int(11) NOT NULL,
  `id_kursus` int(11) NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `peran_pengirim` enum('members','tutors') NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `peran_penerima` enum('members','tutors') DEFAULT NULL,
  `pesan` text NOT NULL,
  `waktu` datetime DEFAULT current_timestamp(),
  `dibaca` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `chat_room`
--

INSERT INTO `chat_room` (`id`, `id_kursus`, `id_pengirim`, `peran_pengirim`, `id_penerima`, `peran_penerima`, `pesan`, `waktu`, `dibaca`) VALUES
(1, 3, 1, 'members', 0, NULL, 'hai', '2025-05-21 13:23:27', 1),
(2, 3, 1, 'members', 0, NULL, 'hallo', '2025-05-21 13:59:30', 1),
(3, 3, 1, '', 0, NULL, 'hai tutor', '2025-05-22 14:01:16', 1),
(4, 3, 1, '', 0, NULL, 'hai\r\n', '2025-05-22 14:12:20', 1),
(5, 3, 1, '', 0, NULL, 'hai tutor', '2025-05-22 14:19:08', 1),
(6, 3, 1, '', 0, NULL, 'hallo', '2025-05-22 14:22:00', 1),
(7, 3, 1, 'members', 0, NULL, 'hai', '2025-05-22 14:31:17', 1),
(8, 3, 1, 'members', 0, NULL, 'siang tutor', '2025-05-22 14:59:52', 1),
(9, 3, 1, 'members', 0, NULL, 'hallo tutor, saya butuh bantuan', '2025-05-22 17:27:47', 1),
(10, 3, 7, 'tutors', 0, NULL, 'baik, apa yang perlu saya bantu?', '2025-05-22 17:46:18', 1),
(11, 3, 7, 'tutors', 0, NULL, 'baik', '2025-05-22 18:25:15', 1),
(12, 3, 7, 'tutors', 0, NULL, 'hallo', '2025-05-22 18:45:11', 1),
(13, 3, 7, 'tutors', 0, NULL, 'hallo, apakah ada yang mau ditanyakan?', '2025-05-25 16:04:03', 1),
(14, 3, 1, 'members', 0, NULL, 'ada materi yang belum saya mengerti', '2025-05-25 17:39:29', 1),
(15, 3, 7, 'tutors', 0, NULL, 'hallo, ada yang mau ditanyakan?', '2025-05-26 11:42:44', 1),
(16, 3, 1, 'members', 0, NULL, 'siang', '2025-05-26 12:46:08', 1),
(17, 3, 7, 'tutors', 0, NULL, 'siang, mau tanya apa nih?', '2025-05-26 13:32:03', 1),
(18, 3, 1, 'members', 0, NULL, 'ada materi yang belum saya mengerti', '2025-05-26 15:44:40', 1),
(19, 3, 7, 'tutors', 0, NULL, 'bagian mana yang belum mengerti?', '2025-05-26 15:45:25', 1),
(20, 3, 1, 'members', 0, NULL, 'data analysis dalam pengertian singkatnya apa sih?', '2025-05-26 16:17:09', 1),
(21, 3, 7, 'tutors', 0, NULL, 'mengumpulkan, membersihkan, menganalisis, dan menyajikan data ', '2025-05-26 16:18:19', 1),
(22, 3, 7, 'tutors', 0, NULL, 'hai', '2025-05-26 17:35:32', 1),
(23, 3, 7, 'tutors', 0, NULL, 'siang ', '2025-05-27 12:48:48', 1),
(24, 3, 7, 'tutors', 0, NULL, 'hai, sudah kah kamu mempelajari materi selanjutnya?', '2025-05-27 15:58:57', 1),
(25, 3, 1, 'members', 0, NULL, 'sudah dong', '2025-05-27 16:38:42', 1),
(26, 3, 7, 'tutors', 0, NULL, 'good, terus semangat ya', '2025-05-27 16:39:28', 1),
(27, 2, 1, 'members', 0, NULL, 'hai', '2025-06-08 16:15:39', 2),
(28, 4, 1, 'members', 0, NULL, 'hai', '2025-06-08 16:33:13', 1),
(29, 2, 9, 'tutors', 0, NULL, 'hai ada apa?', '2025-06-11 15:47:42', 1),
(30, 3, 1, 'members', 0, NULL, 'hai', '2025-06-11 15:48:55', 1),
(31, 3, 7, 'tutors', 0, NULL, 'hallo', '2025-06-11 15:49:23', 1),
(32, 4, 8, 'tutors', 0, NULL, 'hallo', '2025-06-11 15:51:25', 1),
(33, 4, 1, 'members', 0, NULL, 'saya perlu bantuan', '2025-06-11 16:07:26', 1),
(34, 3, 1, 'members', 0, NULL, 'ayo belajar', '2025-06-13 12:21:01', 1),
(35, 4, 9, 'members', 0, NULL, 'hai', '2025-09-16 12:10:35', 1),
(36, 4, 1, 'members', 8, 'tutors', 'hai', '2025-09-16 14:28:14', 1),
(37, 4, 8, 'tutors', 9, 'members', 'hallo', '2025-09-16 15:23:19', 1),
(38, 4, 8, 'tutors', 1, 'members', 'ada yang bisa dibantu?', '2025-09-16 15:23:40', 1),
(39, 4, 9, 'members', 8, '', 'hai', '2025-09-16 16:26:09', 1),
(40, 4, 9, 'members', 8, '', 'hai tutor', '2025-09-16 17:54:31', 1),
(41, 4, 9, 'members', 8, 'tutors', 'hai', '2025-09-16 18:00:28', 1),
(42, 4, 9, 'members', 8, 'tutors', 'hai', '2025-09-16 18:04:06', 1),
(43, 1, 1, 'members', 2, 'tutors', 'Test pesan', '2025-09-16 18:05:39', 0),
(44, 4, 9, 'members', 8, 'tutors', 'hai', '2025-09-16 18:09:08', 1),
(45, 4, 9, 'members', 8, 'tutors', 'hai', '2025-09-16 18:10:22', 1),
(46, 4, 9, 'members', 8, 'tutors', 'test', '2025-09-16 18:12:32', 1),
(47, 4, 9, 'members', 8, 'tutors', 'hallo', '2025-09-16 18:16:58', 1),
(48, 4, 9, 'members', 8, 'tutors', 'hai', '2025-09-16 18:25:11', 1),
(49, 4, 9, 'members', 8, 'tutors', 'hai', '2025-09-16 18:39:58', 1),
(50, 4, 9, 'members', 8, 'tutors', 'malam', '2025-09-16 19:01:24', 1),
(51, 4, 8, 'tutors', 9, 'members', 'hai', '2025-09-16 19:31:09', 1),
(52, 4, 8, 'tutors', 9, 'members', 'malam jannah, adakah yang bisa saya bantu?', '2025-09-16 19:38:25', 1),
(53, 4, 1, 'members', 8, 'tutors', 'malam, bantu saya', '2025-09-16 19:39:39', 1),
(54, 3, 1, 'members', 7, 'tutors', 'hai tutor', '2025-09-16 19:53:05', 1),
(55, 4, 1, 'members', 8, 'tutors', 'ada materi yang belum saya ngerti', '2025-09-16 20:05:04', 1),
(56, 3, 7, 'tutors', 0, 'members', 'adakah yang bisa saya bantu', '2025-09-16 20:27:46', 1),
(57, 4, 8, 'tutors', 0, 'members', 'apa yang belum kamu menegrti', '2025-09-16 20:29:33', 1),
(58, 4, 1, 'members', 8, 'tutors', 'dibagian part 2', '2025-09-16 20:32:14', 1),
(59, 4, 8, 'tutors', 0, 'members', 'baik', '2025-09-16 20:35:12', 1),
(60, 4, 8, 'tutors', 1, 'members', 'baik saya akan bantu', '2025-09-16 20:39:41', 1),
(61, 4, 1, 'members', 8, 'tutors', 'makasih tutor', '2025-09-16 20:40:43', 1),
(62, 4, 8, 'tutors', 1, 'members', 'sama-sama', '2025-09-16 20:42:54', 1),
(63, 4, 1, 'members', 8, 'tutors', 'tutor', '2025-09-16 20:43:43', 1),
(64, 4, 8, 'tutors', 1, 'members', 'iya, ada yang bisa di bantu?', '2025-09-16 20:57:00', 1),
(65, 4, 8, 'tutors', 1, 'members', 'hallo iklima', '2025-09-16 21:00:05', 1),
(66, 4, 1, 'members', 8, 'tutors', 'hai', '2025-09-16 21:06:49', 1),
(67, 4, 1, 'members', 8, 'tutors', 'hai', '2025-09-17 10:34:45', 1),
(68, 4, 8, 'tutors', 1, 'members', 'pagi iklima', '2025-09-17 10:35:25', 1),
(69, 4, 8, 'tutors', 1, 'members', 'bagaimana iklima?', '2025-09-17 11:12:38', 1),
(70, 4, 1, 'members', 8, 'tutors', 'lancar', '2025-09-17 11:13:33', 1),
(71, 4, 8, 'tutors', 1, 'members', 'hai iklima', '2025-09-17 13:24:22', 1),
(72, 4, 1, 'members', 8, 'tutors', 'hai kak gre', '2025-09-17 13:44:24', 1),
(73, 4, 8, 'tutors', 1, 'members', 'udah belajar apa hari ini?', '2025-09-17 13:45:01', 1),
(74, 4, 8, 'tutors', 0, NULL, 'hai', '2025-09-17 15:02:43', 1),
(75, 4, 8, 'tutors', 0, NULL, 'hai', '2025-09-17 18:15:21', 1),
(76, 4, 1, 'members', 0, NULL, 'hai kak gre', '2025-09-17 18:26:25', 1),
(77, 4, 8, 'tutors', 0, NULL, 'gimana belajarnya?', '2025-09-17 18:27:25', 1),
(78, 4, 1, 'members', 0, NULL, 'seru banget', '2025-09-17 18:28:13', 1),
(79, 4, 8, 'tutors', 0, NULL, 'hai', '2025-09-17 18:33:49', 1),
(80, 4, 9, 'members', 0, NULL, 'hai kak gre', '2025-09-17 18:35:20', 1),
(81, 4, 8, 'tutors', 0, NULL, 'selamat siang', '2025-09-18 13:22:21', 1),
(82, 4, 1, 'members', 0, NULL, 'siang kak gre', '2025-09-18 13:23:25', 1),
(83, 4, 8, 'tutors', 0, NULL, 'siang iklima', '2025-09-18 15:06:18', 1),
(84, 4, 1, 'members', 0, NULL, 'sore', '2025-09-18 15:14:59', 1),
(85, 4, 1, 'members', 0, NULL, 'kak gre', '2025-09-18 15:15:05', 1),
(86, 4, 1, 'members', 0, NULL, 'hai', '2025-09-18 16:10:15', 1),
(87, 4, 1, 'members', 0, NULL, 'aku mau belajar yeay', '2025-09-18 16:44:54', 1),
(88, 4, 1, 'members', 0, NULL, 'malam', '2025-09-18 18:19:30', 1),
(89, 4, 1, 'members', 0, NULL, 'halo halo', '2025-09-18 18:27:58', 1),
(90, 4, 8, 'tutors', 0, NULL, 'apa kabar iklima?', '2025-09-18 18:35:10', 1),
(91, 4, 1, 'members', 0, NULL, 'kabar baik dong kak gre', '2025-09-18 18:35:43', 1),
(92, 4, 8, 'tutors', 0, NULL, 'hai', '2025-09-19 15:39:58', 1),
(93, 4, 1, 'members', 0, NULL, 'hallo', '2025-09-19 15:43:00', 1),
(94, 4, 9, 'members', 0, NULL, 'hai', '2025-09-19 15:45:39', 1),
(95, 3, 7, 'tutors', 0, NULL, 'sore', '2025-09-19 16:19:24', 1),
(96, 3, 7, 'tutors', 0, NULL, 'hai', '2025-09-19 16:20:00', 1),
(97, 3, 1, 'members', 0, NULL, 'hallo', '2025-09-19 16:20:41', 1),
(98, 3, 7, 'tutors', 0, NULL, 'p', '2025-09-19 16:21:35', 1),
(99, 3, 7, 'tutors', 0, NULL, 'hai', '2025-09-19 16:35:00', 1),
(100, 3, 7, 'tutors', 0, NULL, 'soree', '2025-09-19 16:38:22', 1),
(101, 4, 8, 'tutors', 9, 'members', 'hai', '2025-09-19 17:45:56', 1),
(102, 4, 1, 'members', 8, '', 'hai', '2025-09-19 18:27:26', 1),
(103, 4, 9, 'members', 8, '', 'malam', '2025-09-19 18:29:19', 1),
(104, 4, 1, 'members', 8, '', 'malam kak gre', '2025-09-19 18:29:52', 1),
(105, 4, 8, 'tutors', 9, '', 'malam azizah', '2025-09-19 18:30:41', 1),
(106, 4, 8, 'tutors', 9, '', 'malam jannah', '2025-09-19 18:31:15', 1),
(107, 4, 1, 'members', 8, '', 'malammmmmm', '2025-09-19 18:37:19', 1),
(108, 4, 8, 'tutors', 1, '', 'malam juga azizah', '2025-09-19 18:38:28', 1),
(109, 4, 9, 'members', 8, 'tutors', 'malam kak gre', '2025-09-19 20:16:54', 1),
(110, 4, 8, 'tutors', 1, 'members', 'hallo azizah', '2025-09-19 21:10:40', 1),
(111, 4, 1, 'members', 8, 'tutors', 'hallo kak gre, selamat istirahat', '2025-09-19 21:46:16', 1),
(112, 4, 1, 'members', 8, 'tutors', 'hallo kak gre, selamat istirahat', '2025-09-19 21:47:45', 1),
(113, 4, 9, 'members', 8, 'tutors', 'yuk istirahat kak', '2025-09-19 21:48:31', 1),
(114, 4, 1, 'members', 8, 'tutors', 'selamat sore kak gre', '2025-09-20 16:00:21', 1),
(115, 4, 8, 'tutors', 9, 'members', 'hai', '2025-09-20 16:09:23', 1),
(116, 4, 8, 'tutors', 1, 'members', 'sore azizah', '2025-09-20 16:09:34', 1),
(117, 4, 9, 'members', 8, 'tutors', 'hai kak gre', '2025-09-20 16:11:06', 1),
(118, 4, 1, 'members', 8, 'tutors', 'ada yang mau aku tanyain kak', '2025-09-20 16:30:16', 1),
(119, 4, 8, 'tutors', 1, 'members', 'tanya apa?', '2025-09-20 16:31:05', 1),
(120, 4, 1, 'members', 8, 'tutors', 'materi di part 2 itu gmn ya kak', '2025-09-20 16:50:49', 1),
(121, 4, 1, 'members', 8, 'tutors', 'hai', '2025-09-20 17:00:01', 1),
(122, 4, 1, 'members', 8, 'tutors', 'sore kak', '2025-09-20 17:29:05', 1),
(123, 4, 9, 'members', 8, 'tutors', 'sore kak gre', '2025-09-20 17:29:41', 1),
(124, 3, 9, 'members', 7, 'tutors', 'hai kak', '2025-09-20 17:30:17', 1),
(125, 4, 8, 'tutors', 1, 'members', 'sore azizah', '2025-09-20 17:31:03', 1),
(126, 4, 8, 'tutors', 9, 'members', 'sore anna', '2025-09-20 17:31:25', 1),
(127, 3, 7, 'tutors', 9, 'members', 'hallo, ada yang bisa dibantu?', '2025-09-20 17:32:05', 1),
(128, 4, 1, 'members', 8, 'tutors', 'malam kak', '2025-09-20 19:00:58', 1),
(129, 4, 8, 'tutors', 1, 'members', 'malam azizah', '2025-09-20 19:01:32', 1),
(130, 4, 1, 'members', 8, 'tutors', 'malam', '2025-10-01 21:20:36', 1),
(131, 4, 8, 'tutors', 1, 'members', 'malam, ada apa?', '2025-10-01 21:21:19', 1),
(132, 4, 1, 'members', 8, 'tutors', 'hai', '2025-10-02 13:52:34', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tgl_isi` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_tutor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `content`
--

INSERT INTO `content` (`id`, `foto`, `judul`, `isi`, `tgl_isi`, `id_tutor`) VALUES
(1, 'content_1749631262_webdev.jpeg', 'Web Development', '<p>web development</p>', '2025-06-11 15:41:02', 6),
(2, 'content_1746769829_desain.jpeg', 'Graphic Design', '<p>Graphic</p>', '2025-06-11 14:56:00', 9),
(3, 'content_1746770087_analis.jpeg', 'Data Analysis', '<p>Materi Data Analysis untuk Pemula</p>', '2025-05-22 17:37:59', 7),
(4, 'content_1749630936_editing.jpeg', 'Photo Editing', '<p>editing</p>', '2025-06-11 15:35:36', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaman`
--

CREATE TABLE `halaman` (
  `id` int(11) NOT NULL,
  `judul` varchar(225) NOT NULL,
  `kutipan` varchar(225) NOT NULL,
  `isi` text NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `halaman`
--

INSERT INTO `halaman` (`id`, `judul`, `kutipan`, `isi`, `tgl_isi`) VALUES
(6, 'Judul 1', 'Kutipan 1', '<p>Isi 1</p>', '2025-02-15 12:34:45'),
(7, 'Judul 2', 'Kutipan 2', '<p>Isi 2</p>', '2025-02-15 12:34:54'),
(8, 'Judul 3', 'Kutipan 3', '<p>Isi 3</p>', '2025-02-15 12:35:41'),
(9, 'Online Courses ', 'You Will Need This ', '<p><img src=\"../gambar/0e65972dce68dad4d52d063967f0a705.jpg\" style=\"width: 50%; float: left;\" class=\"note-float-left\"></p><p data-start=\"183\" data-end=\"537\"><strong data-start=\"183\" data-end=\"194\">Tutorku</strong> adalah platform kursus online yang dirancang untuk membantu siapa saja belajar keterampilan digital dengan mudah, praktis, dan terarah. Kami menyediakan berbagai kelas berkualitas di bidang <strong data-start=\"385\" data-end=\"404\">Web Development</strong>, <strong data-start=\"406\" data-end=\"423\">Data Analysis</strong>, <strong data-start=\"425\" data-end=\"443\">Graphic Design</strong>, <strong data-start=\"445\" data-end=\"462\">Photo Editing</strong>, dan keterampilan lainnya yang relevan dengan kebutuhan industri saat ini.</p><p data-start=\"539\" data-end=\"834\">Berawal dari semangat untuk memperluas akses pendidikan keterampilan digital, Tutorku menghadirkan tutor-tutor profesional yang berpengalaman di bidangnya dan siap membimbing siswa dari berbagai latar belakang — baik pelajar, mahasiswa, profesional, maupun pemula yang ingin memulai karier baru.</p><p data-start=\"52\" data-end=\"353\">\r\n\r\n</p><p data-start=\"836\" data-end=\"1143\">Kami percaya bahwa pembelajaran terbaik terjadi saat teori dipadukan dengan praktik. Oleh karena itu, setiap kelas di Tutorku dirancang interaktif, berbasis proyek, dan fokus pada aplikasi dunia nyata. Peserta tidak hanya mendapatkan materi, tetapi juga bimbingan dan dukungan langsung dari tutor.</p>', '2025-06-01 07:11:16'),
(10, 'Tingkatkan Kemampuanmu Bersama TutorKu', 'Belajar #dimanasaja bersama TutorKu', '<p style=\"text-align: justify;\"><img src=\"../gambar/2b44928ae11fb9384c4cf38708677c48.jpg\" class=\"\" style=\"background-color: var(--bs-body-bg); color: var(--bs-body-color); font-family: var(--bs-body-font-family); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align); width: 50%;\"></p><p><br></p><h2 data-start=\"100\" data-end=\"134\">Selamat Datang di <strong data-start=\"121\" data-end=\"132\">Tutorku</strong></h2><p data-start=\"135\" data-end=\"189\"><strong data-start=\"135\" data-end=\"189\">Belajar Skill Digital Jadi Lebih Mudah dan Terarah</strong></p><p data-start=\"191\" data-end=\"419\">Ingin mahir di bidang Web Development, Data Analysis, Graphic Design, atau Photo Editing? Di <strong data-start=\"284\" data-end=\"295\">Tutorku</strong>, kami hadir untuk membantumu belajar langsung dari tutor profesional dengan pendekatan praktis dan berbasis proyek nyata.</p><p data-start=\"191\" data-end=\"419\">Mulai dari pemula hingga tingkat lanjut, kamu bisa memilih kelas sesuai kebutuhan dan belajar dengan fleksibel — kapan saja, di mana saja.</p>', '2025-06-01 07:09:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `info`
--

CREATE TABLE `info` (
  `id` int(11) NOT NULL,
  `judul` varchar(225) NOT NULL,
  `isi` text NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `info`
--

INSERT INTO `info` (`id`, `judul`, `isi`, `tgl_isi`) VALUES
(1, 'TutorKu', '<p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, sans-serif; font-size: medium; background-color: rgb(222, 222, 222);\">TutorKu menyediakan layanan bimbingan belajar online dengan metode interaktif, fleksibel, dan berkualitas untuk semua jenjang pendidikan.</span></p>', '2025-02-17 11:10:57'),
(2, 'Contact', '<p>Whatsapp : 087887840110</p><p>Email : iklimaazizahjannah@gmail.com</p>', '2025-02-17 11:12:25'),
(3, 'Social', '<p>Instagram : iklimaazizah</p>', '2025-02-17 11:12:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `langganan`
--

CREATE TABLE `langganan` (
  `id` int(11) NOT NULL,
  `id_members` int(11) NOT NULL,
  `id_kursus` int(11) NOT NULL,
  `tgl_berlangganan` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_open_chat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `langganan`
--

INSERT INTO `langganan` (`id`, `id_members`, `id_kursus`, `tgl_berlangganan`, `last_open_chat`) VALUES
(1, 1, 3, '2025-05-20 09:18:42', '2025-09-20 18:10:03'),
(2, 1, 2, '2025-05-20 11:43:35', '2025-09-20 18:08:16'),
(3, 1, 4, '2025-06-08 09:32:59', '2025-10-02 14:08:53'),
(5, 9, 4, '2025-09-15 08:00:40', '2025-09-20 17:29:32'),
(6, 9, 3, '2025-09-19 08:11:31', '2025-09-20 17:47:07'),
(7, 9, 2, '2025-09-20 10:33:06', '2025-09-20 17:33:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi`
--

CREATE TABLE `materi` (
  `id` int(11) NOT NULL,
  `id_kursus` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `isi` text DEFAULT NULL,
  `tgl_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `materi`
--

INSERT INTO `materi` (`id`, `id_kursus`, `foto`, `judul`, `video`, `isi`, `tgl_upload`) VALUES
(1, 3, 'materi_1747125070_analis.jpeg', 'Introduction to Data Analytics', 'https://www.youtube.com/watch?v=BrOB_CCRWyQ&list=PLEF_HnsQxYYglem2xEQSZyx66VeNDdP8k&index=1', '<p>Mengenal lebih lanjut data analysis</p>', '2025-05-13 15:31:10'),
(2, 3, 'materi_1747134215_analis.jpeg', 'Data Analytics in Business', 'https://www.youtube.com/watch?v=EzUZYrVmOk8&list=PLEF_HnsQxYYglem2xEQSZyx66VeNDdP8k&index=2', '<p>Mempelajari data analysis untuk bisnis</p>', '2025-05-13 18:03:35'),
(4, 3, 'materi_1747541233_analis.jpeg', 'Practical Simulation', 'https://www.youtube.com/watch?v=2RcYrg7f31Q&list=PLEF_HnsQxYYglem2xEQSZyx66VeNDdP8k&index=3', '<p>latihan praktek simulasi</p>', '2025-05-18 11:07:13'),
(5, 3, 'materi_1747542746_analis.jpeg', 'Practical Simulation Part 2', 'https://www.youtube.com/watch?v=lMoY5Irj1bc&list=PLEF_HnsQxYYglem2xEQSZyx66VeNDdP8k&index=4', '<p>Latihan praktek simulasi part 2</p>', '2025-05-18 11:32:26'),
(6, 3, 'materi_1747542923_analis.jpeg', 'Career Session', 'https://www.youtube.com/watch?v=RLUOdZFSknI&list=PLEF_HnsQxYYglem2xEQSZyx66VeNDdP8k&index=5', '<p>Diskusi Karir bersama para alumni Tutorku</p>', '2025-05-18 11:35:23'),
(7, 3, 'materi_1747543123_analis.jpeg', 'Case Study Session', 'https://www.youtube.com/watch?v=fKO0S_VL1JM&list=PLEF_HnsQxYYglem2xEQSZyx66VeNDdP8k&index=8', '<p>Studi Kasus</p>', '2025-05-18 11:38:43'),
(9, 2, 'materi_1747635014_desain.jpeg', 'Belajar Desain Grafis', 'https://www.youtube.com/watch?v=P5FDjO_9_Vo&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB', '<h1 class=\"style-scope ytd-watch-metadata\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 0px; border: 0px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; word-break: break-word; line-height: 2.8rem; overflow: hidden; max-height: 5.6rem; -webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; text-overflow: ellipsis;\"><span style=\"font-size: 12px;\">﻿Tips Belajar Desain Grafis</span><br></h1>', '2025-05-19 13:10:14'),
(10, 2, 'materi_1747635197_desain.jpeg', 'Fundamental Desain Grafis', 'https://www.youtube.com/watch?v=0P96Q1UANn8&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=2', '<p>cara jago desain? belajar fundamental desain grafis</p>', '2025-05-19 13:13:17'),
(11, 2, 'materi_1747635298_desain.jpeg', 'Skema Warna', 'https://www.youtube.com/watch?v=DZLFndRoSL8&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=3', '<p>Tips memilih skema warna yang baik</p>', '2025-05-19 13:14:46'),
(12, 2, 'materi_1747635372_desain.jpeg', 'Psikologi Warna', 'https://www.youtube.com/watch?v=txMOmIiA32E&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=4', '<p>Mempelajari Psikologi Warna</p>', '2025-05-19 13:16:12'),
(13, 2, 'materi_1747635459_desain.jpeg', 'Mode Warna', 'https://www.youtube.com/watch?v=mE-lVwkt9gw&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=5', '<p>Perbedaan CMYK &amp; RGB</p>', '2025-05-19 13:17:39'),
(14, 2, 'materi_1747635555_desain.jpeg', 'Layout dan Komposisi Dalam Desain', 'https://www.youtube.com/watch?v=UB5q7-RE2oc&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=6', '<p>Mempelajari&nbsp;Layout dan Komposisi Dalam Desain</p>', '2025-05-19 13:19:15'),
(15, 2, 'materi_1747635824_desain.jpeg', 'Gambar Bitmap vs Vector ', 'https://www.youtube.com/watch?v=0IC_XRMPAK4&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=7', '<p>Tutorial Siluet Corel Draw</p>', '2025-05-19 13:23:44'),
(16, 2, 'materi_1747635953_desain.jpeg', 'Cara Membuat Desain CV Menggunakan Coreldraw', 'https://www.youtube.com/watch?v=4zojHQ2GCiI&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=8', '<p>Part 1</p>', '2025-05-19 13:25:53'),
(17, 2, 'materi_1747635999_desain.jpeg', 'Cara Membuat Desain CV Menggunakan Coreldraw', 'https://www.youtube.com/watch?v=gG1mp_4oc18&list=PL9E3AWZAtxc7CI15Kkmr9Z4bBRnFippxB&index=9', '<p>Part 2</p>', '2025-05-19 13:26:39'),
(18, 1, 'materi_1747742210_webdev-transformed.jpeg', 'Belajar Membuat Website', 'https://www.youtube.com/watch?v=71a2zeC71gk&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH', '<p>Panduan dasar belajar coding membuat website</p>', '2025-05-20 18:56:50'),
(19, 1, 'materi_1747742404_webdev-transformed.jpeg', 'HTML CSS JS', 'https://www.youtube.com/watch?v=Mb6Np6QURDY&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=2', '<p>Implementasi logika sederhana</p>', '2025-05-20 19:00:04'),
(20, 1, 'materi_1747742485_webdev-transformed.jpeg', 'HTML CSS BOOTSTRAP', 'https://www.youtube.com/watch?v=igwNyjc7Ii8&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=3', '<p>Belajar Boostrap untuk pemula</p>', '2025-05-20 19:01:25'),
(21, 1, 'materi_1747742620_webdev-transformed.jpeg', 'HTML CSS JS (Cetrekan Lampu)', 'https://www.youtube.com/watch?v=vQeJIa-ZYm4&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=4', 'Belajar HTML CSS dan Javascript untuk pemula', '2025-05-20 19:03:40'),
(22, 1, 'materi_1747742740_webdev-transformed.jpeg', 'HTML CSS JS (LocalStorage Browser)', 'https://www.youtube.com/watch?v=QUpyv31Nyso&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=5', 'Belajar HTML CSS dan Javascript untuk pemula (LocalStorage Browser)', '2025-05-20 19:05:18'),
(23, 1, 'materi_1747742857_webdev-transformed.jpeg', 'Membuat Website HTML CSS & JS (Dark Mode)', 'https://www.youtube.com/watch?v=QIpWKTHKcqM&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=6', '<p>Tutorial&nbsp;Membuat Website HTML CSS &amp; JS (Dark Mode)</p>', '2025-05-20 19:07:37'),
(24, 1, 'materi_1747742946_webdev-transformed.jpeg', 'Javascipt Backend (Node JS)', 'https://www.youtube.com/watch?v=kLUI9LISP5k&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=7', '<p>Belajar&nbsp;Javascipt Backend (Node JS) untuk pemula</p>', '2025-05-20 19:09:06'),
(25, 1, 'materi_1747743072_webdev-transformed.jpeg', 'Web Generator Password HTML Bootstrap JS', 'https://www.youtube.com/watch?v=XqJYMlCpYQA&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=8', '<p>Belajar membuat&nbsp;Web Generator Password HTML Bootstrap JS</p>', '2025-05-20 19:11:12'),
(26, 1, 'materi_1747743169_webdev-transformed.jpeg', 'Custom Video Player Sederhana HTML dan Javascript', 'https://www.youtube.com/watch?v=nK8CxPxv8wY&list=PLc6SEcJkQ6DzVY6THm3prtUU6HKMqpZSH&index=10', '<p>Tutorial&nbsp;Custom Video Player Sederhana HTML dan Javascript</p>', '2025-05-20 19:12:49'),
(27, 4, 'materi_1747805833_desain-transformed.jpeg', 'Dasar Photoshop & Fungsi Tools Part 1', 'https://www.youtube.com/watch?v=p-cZHBouACs', '<p>Tutorial Photoshop untuk pemula&nbsp;</p>', '2025-05-21 12:37:13'),
(28, 4, 'materi_1747805911_desain-transformed.jpeg', 'Tools di Photoshop Part 2', 'https://www.youtube.com/watch?v=ADDgUWgUCcI', '<p>Belajar Tools di Photoshop</p>', '2025-05-21 12:38:31'),
(29, 4, 'materi_1747806008_desain-transformed.jpeg', 'Tools di Photoshop Part 3', 'https://www.youtube.com/watch?v=JEcG1-tsJ4M&list=PLPKzEaeyPeMZEtN-0Qs2cCUhoFmQ24Aqx&index=3', '<p>Belajar&nbsp;Tools di Photoshop Part 3</p>', '2025-05-21 12:40:08'),
(30, 4, 'materi_1747806055_desain-transformed.jpeg', 'Tools di Photoshop Part 4', 'https://www.youtube.com/watch?v=GuMlpTEMKro&list=PLPKzEaeyPeMZEtN-0Qs2cCUhoFmQ24Aqx&index=4', '<p>Belajar&nbsp;Tools di Photoshop Part 4</p>', '2025-05-21 12:40:55'),
(31, 4, 'materi_1747806134_desain-transformed.jpeg', 'Layers, Lock, Kind & Styles ', 'https://www.youtube.com/watch?v=u1aMtiQxbVo&list=PLPKzEaeyPeMZEtN-0Qs2cCUhoFmQ24Aqx&index=5', '<p>Tutorial Photoshop untuk pemula part 5</p>', '2025-05-21 12:42:14'),
(32, 4, 'materi_1747806266_desain-transformed.jpeg', 'Blending Mode di Photoshop', 'https://www.youtube.com/watch?v=6NXYS6N2ycE&list=PLPKzEaeyPeMZEtN-0Qs2cCUhoFmQ24Aqx&index=6', '<p>Cara Menggunakan&nbsp;Blending Mode di Photoshop</p>', '2025-05-21 12:44:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `status` text NOT NULL,
  `token_ganti_password` text NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_langganan` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `members`
--

INSERT INTO `members` (`id`, `email`, `nama_lengkap`, `password`, `status`, `token_ganti_password`, `tgl_isi`, `is_langganan`) VALUES
(1, 'iklimaazizahjannah@gmail.com', 'Iklima Azizah ', 'c33367701511b4f6020ec61ded352059', '1', '', '2025-04-14 08:45:08', 1),
(9, 'iklimazizahjannah29@gmail.com', 'Iklima Jannah', 'c33367701511b4f6020ec61ded352059', '1', '', '2025-09-15 07:57:33', 0),
(10, 'annisaeka13.aprilia@gmail.com', 'Annisa Eka', 'c9d2cce909ea37234be8af1a1f958805', 'b534ba68236ba543ae44b22bd110a1d6', '', '2025-09-15 08:12:28', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `foto` varchar(225) NOT NULL,
  `isi` text NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `partners`
--

INSERT INTO `partners` (`id`, `nama`, `foto`, `isi`, `tgl_isi`) VALUES
(1, 'Universitas Gajah Mada ', 'partners_1739770705_ugm.png', '<p>TutorKu berkolaborasi dengan Universitas Gadjah Mada (UGM) dalam menghadirkan pembelajaran daring yang mendukung pengembangan ilmu pengetahuan, kepemimpinan, dan pengabdian masyarakat. Kemitraan ini bertujuan memperluas akses pendidikan berkualitas, memperkuat kompetensi mahasiswa, serta mendorong terciptanya ekosistem pendidikan digital yang inklusif dan berkelanjutan.</p>', '2025-09-20 12:30:16'),
(2, 'Institut Teknologi Bandung ', 'partners_1739770799_itb.png', '<p>TutorKu menjalin kemitraan strategis dengan Institut Teknologi Bandung (ITB) untuk menghadirkan kursus daring yang mendukung penguasaan teknologi, inovasi, dan sains. Kolaborasi ini bertujuan memperkuat kompetensi mahasiswa dan menciptakan ekosistem pembelajaran digital yang mendorong lahirnya solusi kreatif bagi tantangan masa depan.</p>', '2025-09-20 12:29:40'),
(3, 'Universitas Indonesia', 'partners_1739770841_universitas-indonesia.png', '<p>TutorKu berkolaborasi dengan Universitas Indonesia (UI) untuk menghadirkan pembelajaran daring yang inovatif dan inklusif. Kemitraan ini mendukung pengembangan kompetensi akademik dan profesional mahasiswa, sekaligus mendorong terciptanya ekosistem pendidikan digital yang berkualitas dan berkelanjutan.</p>', '2025-09-20 12:28:46'),
(4, 'Institut Pertanian Bogor', 'partners_1739770881_ipb.png', '<p>TutorKu menjalin kemitraan strategis dengan IPB University dalam menyediakan kursus daring yang mendukung pengembangan ilmu pengetahuan, inovasi, dan keterampilan mahasiswa. Kolaborasi ini bertujuan memperkuat ekosistem pembelajaran digital yang mendukung kemajuan pendidikan, penelitian, serta penerapan ilmu di bidang pertanian, teknologi, dan sains.</p>', '2025-09-20 12:27:33'),
(6, 'Universitas Airlangga', 'partners_1739771080_unair.png', '<p>TutorKu menjalin kemitraan strategis dengan Universitas Airlangga (UNAIR) Surabaya dalam menghadirkan pembelajaran daring yang inovatif. Kolaborasi ini bertujuan untuk memperluas akses pendidikan berkualitas, mendukung pengembangan kompetensi mahasiswa, serta mendorong terciptanya ekosistem pembelajaran digital yang inklusif dan berkelanjutan.</p>', '2025-09-20 12:26:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `password` text NOT NULL,
  `foto` varchar(225) NOT NULL,
  `isi` text NOT NULL,
  `tgl_isi` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tutors`
--

INSERT INTO `tutors` (`id`, `nama`, `email`, `password`, `foto`, `isi`, `tgl_isi`, `status`) VALUES
(6, 'Iklima Azizah Jannah', 'tutor@webdev.com', 'e10adc3949ba59abbe56e057f20f883e', 'tutors_1749631218_tutor1.jpeg', 'Iklima Azizah Jannah adalah seorang tutor Web Development yang memiliki keahlian dalam membimbing siswa dari tingkat pemula hingga mahir dalam membangun website modern dan responsif. Berpengalaman dalam menggunakan berbagai teknologi seperti HTML, CSS, JavaScript, PHP, dan framework seperti React, Laravel, serta Node.js, ia mengajarkan tidak hanya teori tetapi juga praktik langsung melalui proyek nyata. Latar belakang pendidikan di bidang Teknik Informatika serta pengalaman dalam pengembangan aplikasi web membuat pendekatan mengajarnya selalu relevan dengan kebutuhan industri saat ini. Dalam setiap sesi, [Nama Tutor] membimbing siswa untuk memahami alur kerja pembuatan website dari awal, mulai dari perancangan tampilan hingga pengelolaan database. Metode pembelajarannya interaktif dan disesuaikan dengan kemampuan masing-masing peserta, dilengkapi dengan studi kasus, latihan proyek, dan konsultasi pribadi. Dengan semangat berbagi dan pendekatan yang ramah, Iklima berkomitmen membantu siswa menjadi web developer yang siap bersaing di dunia profesional.', '2025-06-11 08:40:18', '1'),
(7, 'Shani Indira', 'shaniindira@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'tutors_1747915351_WhatsApp_Image_2024-09-12_at_14.12.57_(1)-transformed.jpeg', '<p>Shani Indira adalah seorang tutor Data Analysis yang berpengalaman dalam membantu siswa memahami cara mengolah, menganalisis, dan memvisualisasikan data secara praktis dan aplikatif. Dengan latar belakang pendidikan di bidang Teknik Informatika dan sertifikasi di berbagai platform internasional seperti Coursera, Udemy, dan DataCamp, ia menguasai berbagai tools analisis data seperti Microsoft Excel, SQL, Python (Pandas, Matplotlib), serta platform visualisasi seperti Power BI dan Tableau. Dalam setiap sesi pembelajaran, ia fokus pada penyampaian materi yang mudah dipahami, interaktif, dan relevan dengan kebutuhan industri saat ini. Selain itu, ia juga membimbing siswa melalui studi kasus nyata agar mereka siap menghadapi tantangan di dunia kerja. Metode mengajarnya mencakup kelas online, latihan mandiri, hingga bimbingan project akhir. Dengan pendekatan yang sistematis dan suportif, Shani Indira berkomitmen untuk membantu siswa dari berbagai latar belakang agar mahir dalam analisis data dan mampu menggunakannya untuk mendukung pengambilan keputusan yang lebih baik.</p>', '2025-06-01 06:17:56', '1'),
(8, 'Greesella Adhalia', 'tutor@photoediting.com', 'e10adc3949ba59abbe56e057f20f883e', 'tutors_1748759306_WhatsApp_Image_2024-09-12_at_14.12.57_(1)-transformed.jpeg', '<p>Greesella Adhalia&nbsp;adalah seorang tutor Photo Editing yang berpengalaman dalam mengajarkan teknik pengolahan foto digital kepada berbagai kalangan, mulai dari pemula hingga profesional. Dengan keahlian dalam software seperti Adobe Photoshop, Lightroom, dan Canva, ia membimbing siswa untuk memahami dasar-dasar editing, retouching, manipulasi gambar, hingga pembuatan konten visual yang menarik untuk media sosial atau kebutuhan profesional lainnya. Latar belakang pendidikan di bidang desain dan pengalaman kerja di dunia kreatif membuat pendekatan mengajarnya sangat praktis dan relevan dengan tren industri saat ini. Setiap sesi dirancang agar mudah dipahami, disertai latihan langsung dan studi kasus nyata, sehingga siswa dapat langsung mempraktikkan keterampilan yang dipelajari. Greesella Adhalia juga memberikan tips dan trik profesional agar hasil edit terlihat lebih menarik dan natural. Dengan gaya mengajar yang sabar, detail, dan komunikatif, Greesella Adhalia&nbsp;berkomitmen membantu setiap siswa mengembangkan kreativitas dan percaya diri dalam mengedit foto.</p>', '2025-06-01 06:28:26', '1'),
(9, 'Oline Manuel', 'tutor@graphicdesign.com', 'e10adc3949ba59abbe56e057f20f883e', 'tutors_1748759511_WhatsApp_Image_2024-09-12_at_14.12.57_(1)-transformed.jpeg', '<p>Oline Manuel adalah seorang tutor Graphic Design yang berpengalaman dalam membimbing siswa memahami prinsip desain visual secara kreatif dan aplikatif. Menguasai berbagai tools desain seperti Adobe Illustrator, Photoshop, CorelDRAW, dan Canva, ia membantu siswa mengembangkan kemampuan dalam membuat desain untuk kebutuhan branding, media sosial, promosi, hingga presentasi profesional. Dengan latar belakang pendidikan di bidang desain dan pengalaman di dunia industri kreatif, Oline Manuel mengajarkan materi secara bertahap mulai dari teori dasar desain, penggunaan warna dan tipografi, hingga praktik pembuatan logo, poster, dan konten visual lainnya. Sesi pembelajaran disusun secara interaktif dan disesuaikan dengan kemampuan serta tujuan masing-masing peserta, baik untuk kebutuhan pribadi, bisnis, maupun profesional. Melalui pendekatan yang komunikatif dan fokus pada hasil nyata, Oline Manuel berkomitmen untuk membantu siswa meningkatkan kreativitas, portofolio, dan kepercayaan diri mereka dalam dunia desain grafis.</p>', '2025-06-08 09:17:27', '1');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chatroom_kursus` (`id_kursus`);

--
-- Indeks untuk tabel `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tutor` (`id_tutor`);

--
-- Indeks untuk tabel `halaman`
--
ALTER TABLE `halaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `langganan`
--
ALTER TABLE `langganan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_members` (`id_members`,`id_kursus`),
  ADD KEY `fk_langganan_kursus` (`id_kursus`);

--
-- Indeks untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kursus` (`id_kursus`);

--
-- Indeks untuk tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT untuk tabel `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `halaman`
--
ALTER TABLE `halaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `info`
--
ALTER TABLE `info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `langganan`
--
ALTER TABLE `langganan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chat_room`
--
ALTER TABLE `chat_room`
  ADD CONSTRAINT `fk_chatroom_kursus` FOREIGN KEY (`id_kursus`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_tutor` FOREIGN KEY (`id_tutor`) REFERENCES `tutors` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `langganan`
--
ALTER TABLE `langganan`
  ADD CONSTRAINT `fk_langganan_kursus` FOREIGN KEY (`id_kursus`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_langganan_members` FOREIGN KEY (`id_members`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_ibfk_1` FOREIGN KEY (`id_kursus`) REFERENCES `content` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
