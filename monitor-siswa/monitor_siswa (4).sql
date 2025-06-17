-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jun 2025 pada 15.42
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
-- Database: `monitor_siswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `checkpoint_kegiatan`
--

CREATE TABLE `checkpoint_kegiatan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kegiatan_id` varchar(36) NOT NULL,
  `waktu_checkpoint` datetime NOT NULL,
  `status` enum('Tepat Waktu','Terlambat') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `approval` enum('pending','approved','rejected') DEFAULT 'pending',
  `status_verifikasi` varchar(50) DEFAULT '-',
  `alasan_tolak` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `checkpoint_kegiatan`
--

INSERT INTO `checkpoint_kegiatan` (`id`, `siswa_id`, `kegiatan_id`, `waktu_checkpoint`, `status`, `deskripsi`, `approval`, `status_verifikasi`, `alasan_tolak`) VALUES
(1, 1, '19702002-ebd5-48ed-8be5-0c4d304ac180', '2025-05-12 17:22:48', 'Terlambat', NULL, 'pending', '-', NULL),
(2, 1, '5e2cf11a-beff-45b4-ae2b-150f9f50df49', '2025-05-12 17:23:17', 'Terlambat', NULL, 'pending', '-', NULL),
(13, 1, '459f5336-a47f-4294-95d2-cbc12f88ec65', '2025-05-13 11:05:27', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(14, 4, '48e72863-a1a7-438d-b852-3d37c08d5185', '2025-05-13 13:10:25', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(15, 5, '459f5336-a47f-4294-95d2-cbc12f88ec65', '2025-05-25 10:45:08', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(16, 6, '459f5336-a47f-4294-95d2-cbc12f88ec65', '2025-05-25 11:22:46', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(17, 7, '20205c3a-2180-442c-a438-7ec5c8700bb1', '2025-05-25 14:38:00', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(18, 5, 'b08dc25c-dc9d-4f76-99db-c18d1960477c', '2025-05-25 22:48:37', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(19, 6, 'b08dc25c-dc9d-4f76-99db-c18d1960477c', '2025-05-25 22:48:50', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(20, 5, '459f5336-a47f-4294-95d2-cbc12f88ec65', '2025-05-26 10:26:55', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(21, 5, '8926ce52-e650-40af-927c-4dd2fbd656ae', '2025-05-26 14:15:11', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(22, 8, '8926ce52-e650-40af-927c-4dd2fbd656ae', '2025-05-26 14:17:49', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(23, 5, '459f5336-a47f-4294-95d2-cbc12f88ec65', '2025-05-27 13:08:52', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(24, 5, '43bed69c-c8d3-4798-8fd4-34d84e1d3af0', '2025-05-29 23:55:45', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(25, 5, 'b8110f3d-465b-4e80-afcc-6d37a68cfb16', '2025-05-30 00:00:03', 'Terlambat', NULL, 'pending', '-', NULL),
(26, 5, 'c139a29b-508e-4568-ae21-e765e86768b2', '2025-05-31 16:57:24', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(27, 5, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', '2025-05-31 17:44:45', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(28, 5, 'c139a29b-508e-4568-ae21-e765e86768b2', '2025-06-01 15:45:34', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(29, 2, '19702002', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(30, 2, '459', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(31, 2, '4', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(32, 2, '500', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(33, 6, '19702002', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(34, 6, '459', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(35, 6, '4', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(36, 6, '500', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(37, 5, '19702002', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(38, 5, '459', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(39, 5, '4', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(40, 5, '500', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(41, 7, '19702002', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(42, 7, '459', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(43, 7, '4', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(44, 7, '500', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(45, 1, '19702002', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(46, 1, '459', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(47, 1, '4', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(48, 1, '500', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(49, 8, '19702002', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(50, 8, '459', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(51, 8, '4', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(52, 8, '500', '2025-06-01 15:47:22', '', NULL, 'pending', '-', NULL),
(53, 2, '0', '2025-06-01 15:53:24', '', NULL, 'pending', '-', NULL),
(54, 6, '0', '2025-06-01 15:53:24', '', NULL, 'pending', '-', NULL),
(55, 7, '0', '2025-06-01 15:53:24', '', NULL, 'pending', '-', NULL),
(56, 1, '0', '2025-06-01 15:53:24', '', NULL, 'pending', '-', NULL),
(57, 8, '0', '2025-06-01 15:53:24', '', NULL, 'pending', '-', NULL),
(58, 6, 'c139a29b-508e-4568-ae21-e765e86768b2', '2025-06-01 16:35:18', 'Terlambat', NULL, 'pending', '-', NULL),
(59, 6, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', '2025-06-01 17:17:10', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(60, 6, 'b08dc25c-dc9d-4f76-99db-c18d1960477c', '2025-06-01 20:37:53', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(61, 5, 'c139a29b-508e-4568-ae21-e765e86768b2', '2025-06-07 16:18:21', 'Tepat Waktu', NULL, '', '-', NULL),
(62, 6, 'c139a29b-508e-4568-ae21-e765e86768b2', '2025-06-07 16:19:48', 'Tepat Waktu', NULL, '', '-', NULL),
(63, 6, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', '2025-06-07 18:24:48', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(64, 5, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', '2025-06-07 18:28:03', 'Tepat Waktu', NULL, 'pending', '-', NULL),
(65, 6, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', '2025-06-17 18:35:20', 'Tepat Waktu', NULL, 'pending', 'disetujui', NULL),
(66, 5, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', '2025-06-17 18:40:17', 'Tepat Waktu', NULL, 'pending', 'disetujui', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
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
-- Dumping data untuk tabel `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `nama_kegiatan`, `deskripsi`, `jam_mulai`, `created_at`, `jam_selesai`) VALUES
('19702002-ebd5-48ed-8be5-0c4d304ac180', 'Bangun Pagi', 'Bangun Pagi', '04:30:00', '2025-05-12 13:08:34', '05:30:00'),
('459f5336-a47f-4294-95d2-cbc12f88ec65', 'Gemar Belajar', 'Gemar Belajar', '08:00:00', '2025-05-12 15:28:36', '14:10:00'),
('4f91a614-6399-40e4-9747-1c270caacb0a', 'Makan sehat', 'Makan sehat & bergizi', '07:30:00', '2025-05-12 15:29:55', '07:55:00'),
('5af8c6c2-64fb-4d4a-80a4-f5723da66bbc', 'Beribadah', 'Sholat Jumat', '17:00:00', '2025-05-12 16:13:59', '21:10:00'),
('5e2cf11a-beff-45b4-ae2b-150f9f50df49', 'Berolahraga', 'Berolahraga', '07:00:00', '2025-05-12 13:09:07', '07:30:00'),
('b08dc25c-dc9d-4f76-99db-c18d1960477c', 'Tidur Cepat', 'Tidur Cepat', '20:00:00', '2025-05-12 15:33:38', '22:00:00'),
('c139a29b-508e-4568-ae21-e765e86768b2', 'Bermasyarakat', 'Bermasyarakat', '15:30:00', '2025-05-12 15:30:30', '16:34:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(1, 'Kelas 1', '2025-05-13 13:02:06', '2025-05-13 13:02:06'),
(5, 'Kelas 2', '2025-05-13 14:05:24', '2025-05-13 14:05:24'),
(6, 'Kelas 3 ', '2025-05-24 17:00:32', '2025-05-24 17:00:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapping_guru_kelas`
--

CREATE TABLE `mapping_guru_kelas` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mapping_guru_kelas`
--

INSERT INTO `mapping_guru_kelas` (`id`, `guru_id`, `kelas_id`) VALUES
(6, 4, 1),
(8, 12, 6),
(9, 9, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapping_siswa_kegiatan`
--

CREATE TABLE `mapping_siswa_kegiatan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kegiatan_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mapping_siswa_kegiatan`
--

INSERT INTO `mapping_siswa_kegiatan` (`id`, `siswa_id`, `kegiatan_id`) VALUES
(14, 2, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(15, 2, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(16, 2, '4f91a614-6399-40e4-9747-1c270caacb0a'),
(17, 2, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc'),
(18, 2, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(19, 2, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(20, 2, 'c139a29b-508e-4568-ae21-e765e86768b2'),
(43, 1, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(44, 1, '20205c3a-2180-442c-a438-7ec5c8700bb1'),
(45, 1, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(46, 1, '4f91a614-6399-40e4-9747-1c270caacb0a'),
(47, 1, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc'),
(48, 1, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(49, 1, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(50, 1, 'c139a29b-508e-4568-ae21-e765e86768b2'),
(62, 8, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(63, 8, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(64, 8, '4f91a614-6399-40e4-9747-1c270caacb0a'),
(65, 8, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc'),
(66, 8, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(67, 8, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(68, 8, 'c139a29b-508e-4568-ae21-e765e86768b2'),
(69, 6, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(70, 6, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(71, 6, '4f91a614-6399-40e4-9747-1c270caacb0a'),
(72, 6, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc'),
(73, 6, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(74, 6, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(75, 6, 'c139a29b-508e-4568-ae21-e765e86768b2'),
(76, 5, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(77, 5, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(78, 5, '4f91a614-6399-40e4-9747-1c270caacb0a'),
(79, 5, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc'),
(80, 5, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(81, 5, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(82, 5, 'c139a29b-508e-4568-ae21-e765e86768b2'),
(83, 7, '19702002-ebd5-48ed-8be5-0c4d304ac180'),
(84, 7, '459f5336-a47f-4294-95d2-cbc12f88ec65'),
(85, 7, '4f91a614-6399-40e4-9747-1c270caacb0a'),
(86, 7, '5af8c6c2-64fb-4d4a-80a4-f5723da66bbc'),
(87, 7, '5e2cf11a-beff-45b4-ae2b-150f9f50df49'),
(88, 7, 'b08dc25c-dc9d-4f76-99db-c18d1960477c'),
(89, 7, 'c139a29b-508e-4568-ae21-e765e86768b2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'guru'),
(2, 'siswa'),
(3, 'admin'),
(4, 'kepala sekolah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `nama_siswa`, `kelas`, `user_id`, `kelas_id`) VALUES
(1, 'saya', '1', 5, 1),
(2, 'saya1', '2', 6, 5),
(4, 'coba', '3', 8, 1),
(5, 'Nadira', '', 10, 6),
(6, 'Nadila', '', 11, 6),
(7, 'Tegar', '', 13, 6),
(8, 'elham', '', 14, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `useremail`, `username`, `password`, `token`, `role_user`) VALUES
(4, 'admin@gmail.com', 'admin', '$2y$10$CwVKDdqR.xalIwebQtoB0.EcwCxfYpv0.7ATNZje09ztGe0TR6bN.', 'f47d38c01a0d58d9b7ce9d7bfa1a7da931c3207d0894e93cbcfc03c7d24dbcbaba4480d5ad1d172d05722c6e6b9a5727aac0', 1),
(5, 'saya@gmail.com', 'saya', '$2y$10$hUYrvx.N/y.zpR5LWa.eQu6WdqkfkdRVxCCwgrhkTJI3o31N2qNwK', 'ccc839d82dd3447c346817cb22f9987a', 2),
(6, 'saya1@gmail.com', 'saya1', '$2y$10$P1l3hCuJO/GynAZuwrjJMuWYJ56FpChY/E/2x..9cHVQqwr5kmRny', '64cdea46479e33ee43b1c2167cb226ef', 2),
(8, 'coba@gmail.com', 'coba', '$2y$10$MQ4O1ghE3Rg/3J9LqwlV4O3TXRdIYzx0QuIfI353eXcR5tOf5AIJ2', '6c8b10b53f23d867849cd1c71bb80713caba4d18be94bf415b9163c79109da8e20338131083fc0c014bb639655e4eff90079', 2),
(9, 'guru1@gmail.com', 'guru1', '$2y$10$Yp9hFC8NIUIfZGZtx4/vl.jEtPvY/q1Q4z3I3oyDHS4vNGyaDbfki', 'bd15aa4eb891366847c57dbf12e6fe560617b49c57805a56ac76c81ea5c93ef37fe88391db924bc26fe2862b1500a80e4771', 1),
(10, 'nadira@gmail.com', 'nadira', '$2y$10$iuMZXgwc6bEYwEYp3CE5Y.oO8kDJ5ZcZkdLI/z3Dp67YC56apYC3W', '24355a87c45efc852db44cd4aceec3d283aebea11a3e88d85891ba0aa7ea0afd0c25a5f87ed20e2affdf4e311514d1777417', 2),
(11, 'nadila@gmail.com', 'Nadila', '$2y$10$jpgc4AyApABRMcIece5I/.obrKDs8OQnMySoAfziSZ2mgwBLmS7W.', '19ee76b4fe768f3be0091acfc2296fa26a3d7949a8473e2ca5b24c534b3e4bf361ff7c609e1836c11d3086b3fb68d2c94b1c', 2),
(12, 'ghema@gmail.com', 'pak ghem', '$2y$10$62aL0Xg9.7SAjfBgHoRbLe3DV/34ql6CbebFPkKEtG4aPOLIdFcw.', '1f625208c6b654c1f0fbc5260a13f926249e23f084e58e70ad5721e12244034a76ee87c9dec0930725609c704f3d25f4f257', 1),
(13, 'tegar@gmail.com', 'ramadhan', '$2y$10$wN5.KJcj6cspYbsoK7GoveuOHgwsG6SPCNdiEEf2j54v1F0MmNV/O', '8b48e2b79fd52806f89e6b17d4cd3f2cfc2fc708a867ff656c5ca36e6b7e8018d017799bef014cf2404f77a364dbc2ccc3d9', 2),
(14, 'elham@gmail.com', 'elha,', '$2y$10$fP/hBNeWT8jbVOPeJ/d1b..22fsYNF8CdyyhMCO7pJit/YE1asAwC', 'b27d2220e08e4191e5beff38e858ce9699e23b4a4a68b1ae1c330364e295362937f6ad8e00ded5d33ca5d0abfe6b24b8ffa3', 2),
(15, 'administrator@gmail.com', 'Administrator', '$2y$10$0JpJW749au6JahQBHkfXLOzzUn/pI23HvgYOgL8.O8osrxjb7UAlG', NULL, 3),
(16, 'kepsek@smpn4.com', 'Kepala Sekolah', '$2y$10$WJmKAhxGKh6WUdTIFA8Vzex17I4ThFI0up8HAFwPbBEbBV6L57MZe', NULL, 4);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `checkpoint_kegiatan`
--
ALTER TABLE `checkpoint_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mapping_guru_kelas`
--
ALTER TABLE `mapping_guru_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mapping_siswa_kegiatan`
--
ALTER TABLE `mapping_siswa_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `checkpoint_kegiatan`
--
ALTER TABLE `checkpoint_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `mapping_guru_kelas`
--
ALTER TABLE `mapping_guru_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `mapping_siswa_kegiatan`
--
ALTER TABLE `mapping_siswa_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
