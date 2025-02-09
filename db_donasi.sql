-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2025 at 06:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_donasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `donasi`
--

CREATE TABLE `donasi` (
  `id` int(11) NOT NULL,
  `donatur_id` int(11) DEFAULT NULL,
  `lembaga_id` int(11) DEFAULT NULL,
  `kategori` enum('pendidikan','kesehatan','bencana','lainnya') NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `status` enum('pending','terima','batal') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bank_tujuan` varchar(50) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi`
--

INSERT INTO `donasi` (`id`, `donatur_id`, `lembaga_id`, `kategori`, `nominal`, `status`, `created_at`, `bank_tujuan`, `no_rekening`, `bukti_transfer`) VALUES
(32, 1, 1, 'pendidikan', 333.00, '', '2025-02-05 13:55:48', 'Mandiri', '987-654-3210', 'uploads/Screenshot 2023-09-08 195650.png'),
(33, 1, 1, 'pendidikan', 123.00, '', '2025-02-05 14:00:16', 'Mandiri', '987-654-3210', 'uploads/Screenshot 2023-11-10 224854.png'),
(34, 1, 1, 'pendidikan', 222.00, '', '2025-02-05 14:01:39', 'BCA', '123-456-7890', 'uploads/Screenshot 2023-12-06 202743.png'),
(35, 1, 1, 'pendidikan', 222.00, '', '2025-02-05 14:04:15', 'BCA', '123-456-7890', 'uploads/Screenshot 2023-09-14 204540.png'),
(36, 2, 1, 'pendidikan', 321.00, '', '2025-02-05 14:06:23', 'Mandiri', '987-654-3210', 'uploads/Screenshot 2023-09-14 204540.png'),
(37, 1, 1, 'pendidikan', 222.00, 'terima', '2025-02-05 14:11:29', 'BRI', '555-666-777', 'uploads/Screenshot 2023-09-08 192601.png'),
(38, 1, 4, 'bencana', 333.00, 'terima', '2025-02-05 14:26:34', 'BCA', '123-456-7890', 'uploads/Screenshot 2023-09-08 192829.png'),
(39, 1, 1, 'pendidikan', 333.00, 'pending', '2025-02-05 15:09:10', 'BRI', '555-666-777', 'uploads/Screenshot 2024-01-26 220243.png'),
(40, 1, 1, 'pendidikan', 22.00, 'terima', '2025-02-05 15:39:04', 'Mandiri', '987-654-3210', 'uploads/2023-11-20_126619970.JPG'),
(41, 1, 1, 'pendidikan', 50000.00, 'pending', '2025-02-05 16:35:25', 'BCA', '123-456-7890', 'uploads/245-2452743_023-307-kb-grand-blue-anime-faces-hd.png'),
(42, 1, 1, 'pendidikan', 332.00, 'pending', '2025-02-05 16:39:25', 'Mandiri', '987-654-3210', 'uploads/t59779.png'),
(43, 1, 1, 'pendidikan', 23.00, 'pending', '2025-02-05 16:40:55', 'BRI', '555-666-777', NULL),
(45, 1, 1, 'pendidikan', 333.00, 'pending', '2025-02-05 17:37:44', 'BCA', '123-456-7890', 'uploads/R.png'),
(46, 10, 1, 'pendidikan', 5000000.00, 'terima', '2025-02-06 04:30:50', 'BCA', '123-456-7890', 'uploads/t59779.png'),
(47, 11, 2, 'kesehatan', 2000000.00, 'terima', '2025-02-06 04:47:02', 'BRI', '555-666-777', 'uploads/farp,small,wall_texture,product,750x1000.jpg'),
(48, 11, 2, 'kesehatan', 2000000.00, 'terima', '2025-02-06 04:59:07', 'BRI', '555-666-777', 'uploads/farp,small,wall_texture,product,750x1000.jpg'),
(49, 15, 3, 'pendidikan', 2000000.00, 'terima', '2025-02-06 16:19:06', 'BRI', '555-666-777', 'uploads/st,small,507x507-pad,600x600,f8f8f8.u2.jpg'),
(50, 2, 4, 'bencana', 250000.00, 'pending', '2025-02-06 17:01:41', 'BCA', '123-456-7890', 'uploads/t59779.png'),
(51, 10, 2, 'kesehatan', 150000.00, 'pending', '2025-02-06 17:02:38', 'BCA', '', 'uploads/t59779.png');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `lembaga_id` int(11) DEFAULT NULL,
  `nama_lembaga` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `nominal` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `lembaga_id`, `nama_lembaga`, `deskripsi`, `nominal`, `created_at`) VALUES
(15, 3, 'Yayasan Pendidikan Cerdas', 'penggunaan dana untuk membeli buku ', 50000.00, '2025-02-05 14:38:56'),
(16, 4, 'Luka mereka luka kita semua', 'membantu korban banjir', 20000.00, '2025-02-05 14:40:15'),
(17, 3, 'Yayasan Pendidikan Cerdas', 'we outsmart em', 24.00, '2025-02-05 16:42:45'),
(18, 3, 'Yayasan Pendidikan Cerdas', 'penggunaan dana untuk pembelian buku sekolah anak sd', 2000000.00, '2025-02-06 04:33:59'),
(19, 2, 'Lembaga Kesehatan Indonesia', 'dana digunakan untuk membeli vaksin', 1000000.00, '2025-02-06 04:49:46'),
(20, 3, 'Yayasan Pendidikan Cerdas', 'dana digunakan untuk membeli buku ', 1000000.00, '2025-02-06 16:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `lembaga_sosial`
--

CREATE TABLE `lembaga_sosial` (
  `id` int(11) NOT NULL,
  `nama_lembaga` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lembaga_sosial`
--

INSERT INTO `lembaga_sosial` (`id`, `nama_lembaga`, `email`) VALUES
(1, 'Yayasan Peduli Anak', 'peduli@anak.com'),
(2, 'Lembaga Kesehatan Indonesia', 'sehat@indonesia.id'),
(3, 'Yayasan Pendidikan Cerdas', 'outsmart@pendidikan.net'),
(4, 'Luka mereka luka kita semua', 'luka@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('donatur','lembaga','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(1, 'gabe newell', 'valve@corp.com', '$2y$10$FS9DAZU8D4pjPoGgacKOVemRhEjXpdOmMkVW6JgE8KchfNpp49j0a', 'donatur'),
(2, 'mas elon', 'spacex@mars.moon', '$2y$10$vzc/zWOFibknTeGpBuvtKO94USgaHZA64eK6yv4MyinezouikzG3a', 'donatur'),
(3, 'Yayasan Peduli Anak', 'peduli@anak.com', '$2y$10$MUPy3/EsRUroWyNVsNkPl.KoH0UlMf1OQdsvQZ7lSiUHp4JN/9e3i', 'lembaga'),
(4, 'admin', 'admin@hood.com', '$2y$10$Bc9ttNsG2DdNzvG4rakKNO67AXnLnLPky0rCcrjVr7yWkKvCNu.ym', 'admin'),
(5, 'sugun', 'sugunkroco666@gmail.com', '$2y$10$AToPCvt1zwo7GbOI5ToLT.qBLMxNJAoME09ZOsGJhTbmkm2ryUSnS', 'donatur'),
(6, 'Lembaga Kesehatan Indonesia', 'sehat@indonesia.id', '$2y$10$WJcyb7ZBjZ3PraNN/dNVAOMLOC1FRZla4JiowntihXtXc63w/sUAu', 'lembaga'),
(7, 'Yayasan Pendidikan Cerdas', 'outsmart@pendidikan.net', '$2y$10$raVlPYFoCxh5naZKAtmFHu/6j8Z8iTtfjz686YMyFyKooFlthm6Ty', 'lembaga'),
(8, 'Luka mereka luka kita semua', 'luka@mail.com', '$2y$10$Mh4hpf2XjcugMVi/LaSqs.qJbu6mzEUbejGmXuEudEz0Ev7RgPHNq', 'lembaga'),
(9, 'Ujang', 'jang@ujang.com', '$2y$10$sCp9mn9v49r8QWKUeie4MOE/yL61VZolTJ96bKjoZCn1iyI7txfCS', 'donatur'),
(10, 'mirana', 'memid@throw.com', '$2y$10$3q.GuJKejrta0N9rDdIYsemeO4xJ5gVkDQK94j5xAkFfuO3T55ih6', 'donatur'),
(11, 'Anurdazle', 'anur@gmail.com', '$2y$10$1SBJQPlLvi.4cqucqxFtk.yldf0uZ0rjeUMq9h8qQiWuqvvTAVdB6', 'donatur'),
(13, 'lmao', 'rofl@king.com', '', 'lembaga'),
(14, 'Stop scrolling', 'getsomehelp@gmail.com', '$2y$10$QrHVHjDQ2SJ9EvValjzjoO8CkuRBcYCpOHNwMWuyL..RPJ..qcGuO', 'donatur'),
(15, 'Kilua', 'kilua@gmail.com', '$2y$10$NR/BG1BNhDxflkgTyiH8jOGGChE0jHY2vhi50mVjTliKPdKLEPDbK', 'donatur');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donatur_id` (`donatur_id`),
  ADD KEY `lembaga_id` (`lembaga_id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lembaga_id` (`lembaga_id`);

--
-- Indexes for table `lembaga_sosial`
--
ALTER TABLE `lembaga_sosial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `lembaga_sosial`
--
ALTER TABLE `lembaga_sosial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`donatur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga_sosial` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`lembaga_id`) REFERENCES `lembaga_sosial` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
