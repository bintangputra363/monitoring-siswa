-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 11:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitor_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkpoint_kegiatan`
--

CREATE TABLE `checkpoint_kegiatan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kegiatan_id` varchar(36) NOT NULL,
  `waktu_checkpoint` datetime NOT NULL,
  `status` enum('Tepat Waktu','Terlambat') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkpoint_kegiatan`
--

INSERT INTO `checkpoint_kegiatan` (`id`, `siswa_id`, `kegiatan_id`, `waktu_checkpoint`, `status`) VALUES
(1, 1, '19702002-ebd5-48ed-8be5-0c4d304ac180', '2025-05-12 17:22:48', 'Terlambat'),
(2, 1, '5e2cf11a-beff-45b4-ae2b-150f9f50df49', '2025-05-12 17:23:17', 'Terlambat'),
(13, 1, '459f5336-a47f-4294-95d2-cbc12f88ec65', '2025-05-13 11:05:27', 'Tepat Waktu'),
(14, 4, '48e72863-a1a7-438d-b852-3d37c08d5185', '2025-05-13 13:10:25', 'Tepat Waktu');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` char(36) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `jam_mulai` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `nama_kegiatan`, `deskripsi`, `jam_mulai`, `created_at`, `jam_selesai`) VALUES
('19702002-ebd5-48ed-8be5-0c4d304ac180', 'Bangun Pagi', 'Bangun Pagi', '04:30:00', '2025-05-12 13:08:34', '06:00:00'),
('459f5336-a47f-4294-95d2-cbc12f88ec65', 'Gemar Belajar', 'Gemar Belajar', '08:00:00', '2025-05-12 15:28:36', '14:10:00'),
('4f91a614-6399-40e4-9747-1c270caacb0a', 'Makan sehat', 'Makan sehat & bergizi', '07:30:00', '2025-05-12 15:29:55', '07:55:00'),
('5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', 'Beribadah', 'Beribadah', '12:00:00', '2025-05-12 16:13:59', '12:10:00'),
('5e2cf11a-beff-45b4-ae2b-150f9f50df49', 'Berolahraga', 'Berolahraga', '07:00:00', '2025-05-12 13:09:07', '07:30:00'),
('b08dc25c-dc9d-4f76-99db-c18d1960477c', 'Tidur Cepat', 'Tidur Cepat', '20:00:00', '2025-05-12 15:33:38', '04:30:00'),
('c139a29b-508e-4568-ae21-e765e86768b2', 'Bermasyarakat', 'Bermasyarakat', '15:30:00', '2025-05-12 15:30:30', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(1, 'Kelas 1', '2025-05-13 13:02:06', '2025-05-13 13:02:06'),
(5, 'Kelas 2', '2025-05-13 14:05:24', '2025-05-13 14:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `mapping_guru_kelas`
--

CREATE TABLE `mapping_guru_kelas` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapping_guru_kelas`
--

INSERT INTO `mapping_guru_kelas` (`id`, `guru_id`, `kelas_id`) VALUES
(6, 4, 1),
(7, 9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mapping_siswa_kegiatan`
--

CREATE TABLE `mapping_siswa_kegiatan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kegiatan_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapping_siswa_kegiatan`
--

INSERT INTO `mapping_siswa_kegiatan` (`id`, `siswa_id`, `kegiatan_id`) VALUES
(1, 1, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(2, 1, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(3, 1, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(4, 1, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(5, 1, 'f2f1d46a-e7f8-469c-9031-b9a414664340'),
(6, 4, '48e72863-a1a7-438d-b852-3d37c08d5185'),
(11, 4, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(12, 4, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(13, 4, '4f91a614-6399-40e4-9747-1c270caacb0a');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nama_siswa`, `kelas`, `user_id`, `kelas_id`) VALUES
(1, 'saya', '1', 5, 1),
(2, 'saya1', '2', 6, 5),
(4, 'coba', '3', 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `token` varchar(300) DEFAULT NULL,
  `role_user` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `useremail`, `username`, `password`, `token`, `role_user`) VALUES
(4, 'admin@gmail.com', 'admin', '$2y$10$CwVKDdqR.xalIwebQtoB0.EcwCxfYpv0.7ATNZje09ztGe0TR6bN.', 'f47d38c01a0d58d9b7ce9d7bfa1a7da931c3207d0894e93cbcfc03c7d24dbcbaba4480d5ad1d172d05722c6e6b9a5727aac0', 1),
(5, 'saya@gmail.com', 'saya', '$2y$10$hUYrvx.N/y.zpR5LWa.eQu6WdqkfkdRVxCCwgrhkTJI3o31N2qNwK', 'ccc839d82dd3447c346817cb22f9987a', 2),
(6, 'saya1@gmail.com', 'saya1', '$2y$10$P1l3hCuJO/GynAZuwrjJMuWYJ56FpChY/E/2x..9cHVQqwr5kmRny', '64cdea46479e33ee43b1c2167cb226ef', 2),
(8, 'coba@gmail.com', 'coba', '$2y$10$MQ4O1ghE3Rg/3J9LqwlV4O3TXRdIYzx0QuIfI353eXcR5tOf5AIJ2', '6c8b10b53f23d867849cd1c71bb80713caba4d18be94bf415b9163c79109da8e20338131083fc0c014bb639655e4eff90079', 2),
(9, 'guru1@gmail.com', 'guru1', '$2y$10$Yp9hFC8NIUIfZGZtx4/vl.jEtPvY/q1Q4z3I3oyDHS4vNGyaDbfki', 'bd15aa4eb891366847c57dbf12e6fe560617b49c57805a56ac76c81ea5c93ef37fe88391db924bc26fe2862b1500a80e4771', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkpoint_kegiatan`
--
ALTER TABLE `checkpoint_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapping_guru_kelas`
--
ALTER TABLE `mapping_guru_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mapping_siswa_kegiatan`
--
ALTER TABLE `mapping_siswa_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkpoint_kegiatan`
--
ALTER TABLE `checkpoint_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mapping_guru_kelas`
--
ALTER TABLE `mapping_guru_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mapping_siswa_kegiatan`
--
ALTER TABLE `mapping_siswa_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
