-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 09 Jan 2025 pada 15.06
-- Versi Server: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indekos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_messages`
--

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `user_id`, `message`, `is_admin`, `timestamp`, `username`) VALUES
(87, 0, 'hallo', 0, '2024-07-10 02:49:32', 'User'),
(88, 0, 'iya', 1, '2024-07-10 02:50:12', 'Admin'),
(89, 0, 'hello', 0, '2024-07-11 11:49:10', 'User');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_diri`
--

CREATE TABLE IF NOT EXISTS `data_diri` (
  `nama` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `ktp_name` varchar(255) DEFAULT NULL,
  `ktp_content` blob,
  `ktp_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_diri`
--

INSERT INTO `data_diri` (`nama`, `alamat`, `pekerjaan`, `no_hp`, `email`, `ktp_name`, `ktp_content`, `ktp_path`) VALUES
('Fatur', 'Jalan baung lll No 15B. Rt 002/ 004', 'jdbsetjhtykjet', '30957309', 'fatur@gmail.com', '236-File Utama Naskah-719-2-10-20220930 (1).pdf', NULL, 'uploads/66a8d37462c56_236-File Utama Naskah-719-2-10-20220930 (1).pdf'),
('arini', 'Jalan baung lll No 15B. Rt 002/ 004', 'dokter', '085212641792', 'henyrahmansyah72@gmail.com', 'KTM.pdf', NULL, 'uploads/668d129d99675_KTM.pdf'),
('muhammad ibnu', 'Jalan baung lll No 15B. Rt 002/ 004', 'mahasiswa', '02937845688', 'ibnu@gmail.com', 'KTM.pdf', NULL, 'uploads/668df5c6a5983_KTM.pdf'),
('ikaaaaaaaaaaa', 'sdgsegsh', 'safagtae', '34658785324521', 'ika@gmail.com', 'kasur.jpg', NULL, 'uploads/668fc722e9ca6_kasur.jpg'),
('Aura Khalisa Dini Lestari', 'Tanjung barat no. 25', 'pns', '5767457', 'jin@gmail.com', 'OPERASI BINER.pdf', NULL, 'uploads/668e8eae6ed4f_OPERASI BINER.pdf'),
('jungkook', 'Tanjung barat no. 25', 'penyanyi', '70870686', 'jung@gmail.com', 'no 4.jpeg', NULL, 'uploads/66890272dce19_no 4.jpeg'),
('Kim Mingyu', 'dfjhdj', 'sdfasd', '3645769', 'kim@gmail.com', '3.jpg', NULL, 'uploads/668bdee436215_3.jpg'),
('Jeon Wonwoo', 'Tanjung barat no. 25', 'pns', '23617947', 'wonwoo@gmail.com', 'no 4.jpeg', NULL, 'uploads/66878b98b85e9_no 4.jpeg'),
('Min Yoongi', 'jln baung', 'pns', '35728572', 'yoon@gmail.com', 'no1.jpeg', NULL, 'uploads/66890324ab645_no1.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','rejected','verified') NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `customer_email`, `payment_proof`, `status`, `created_at`) VALUES
(1, 'yoon@gmail.com', 'uploads/66890a08b71f99.89389575.jpeg', 'verified', NULL),
(2, 'kim@gmail.com', 'uploads/668bdf41be5821.03832077.jpg', 'verified', NULL),
(3, 'henyrahmansyah72@gmail.com', 'uploads/668d12f729ded6.15162446.pdf', 'verified', NULL),
(4, 'ibnu@gmail.com', 'uploads/668df68190fa07.74787266.pdf', 'verified', NULL),
(5, 'wonwoo@gmail.com', 'uploads/668e8e25cc6128.22234658.pdf', 'rejected', NULL),
(6, 'jung@gmail.com', 'uploads/668f7117cf2644.34372205.pdf', 'verified', NULL),
(7, 'ika@gmail.com', 'uploads/668fc7402bc901.12957200.jpeg', 'verified', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `registrasi`
--

CREATE TABLE IF NOT EXISTS `registrasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `password` varchar(25) DEFAULT NULL,
  `email` varchar(35) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `registrasi`
--

INSERT INTO `registrasi` (`id`, `nama`, `password`, `email`, `phone`, `role`) VALUES
(12, 'heny', 'Hello123.', 'admin@gmail.com', '37966347', 'admin'),
(20, 'Jeon Wonwoo', 'Wonwoo123.', 'wonwoo@gmail.com', '635823758', 'user'),
(21, 'jeon jungkook', 'Jungkook123.', 'jung@gmail.com', '6458436', 'user'),
(22, 'Min Yoongi', 'Yoongi123.', 'yoon@gmail.com', '1624824', 'user'),
(23, 'Kim Mingyu', 'Mingyu123.', 'kim@gmail.com', '23546', 'user'),
(24, 'arini', 'Pac.rj3no', 'henyrahmansyah72@gmail.com', '085212641792', 'user'),
(25, 'ibnu', 'muhammadIbnu123.', 'ibnu@gmail.com', '0890349234', 'user'),
(26, 'kim seokjin', 'Seokjin123.', 'jin@gmail.com', '28964828414', 'user'),
(27, 'Ika', 'Muhammadika123.', 'ika@gmail.com', '0826372470', 'user'),
(28, 'raaaaaaaaa', 'Aurakhalisa123.', 'ra@gmail.com', '365623568257', 'user'),
(29, 'Fatur', 'Mhmmdfatur7.', 'fatur@gmail.com', '092784724', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservations`
--

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(35) NOT NULL,
  `available` tinyint(1) DEFAULT '1',
  `status` enum('reserved','available') NOT NULL DEFAULT 'reserved'
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `reservations`
--

INSERT INTO `reservations` (`id`, `room_id`, `start_date`, `end_date`, `price`, `created_at`, `email`, `available`, `status`) VALUES
(87, 1, '2024-07-11', '2024-08-09', '750000.00', '2024-07-11 05:25:02', 'wonwoo@gmail.com', 1, 'reserved'),
(88, 2, '2024-07-11', '2024-10-23', '3000000.00', '2024-07-11 05:34:53', 'jung@gmail.com', 1, 'reserved'),
(89, 3, '2024-07-11', '2024-08-10', '750000.00', '2024-07-11 11:51:15', 'ika@gmail.com', 1, 'reserved'),
(91, 4, '2024-07-30', '2024-11-07', '3000000.00', '2024-07-30 12:04:09', 'fatur@gmail.com', 1, 'reserved');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservation_queue`
--

CREATE TABLE IF NOT EXISTS `reservation_queue` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL,
  `room_number` int(11) NOT NULL,
  `is_booked` tinyint(1) DEFAULT '0',
  `status` varchar(10) DEFAULT 'available'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `is_booked`, `status`) VALUES
(1, 1, 1, 'available'),
(2, 2, 1, 'available'),
(3, 3, 1, 'available'),
(4, 4, 1, 'available'),
(5, 5, 1, 'available'),
(6, 6, 1, 'available'),
(7, 7, 1, 'available'),
(8, 8, 1, 'available'),
(9, 9, 1, 'available'),
(10, 10, 1, 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_diri`
--
ALTER TABLE `data_diri`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_email` (`customer_email`);

--
-- Indexes for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `reservation_queue`
--
ALTER TABLE `reservation_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=90;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `registrasi`
--
ALTER TABLE `registrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `reservation_queue`
--
ALTER TABLE `reservation_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`customer_email`) REFERENCES `registrasi` (`email`);

--
-- Ketidakleluasaan untuk tabel `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
