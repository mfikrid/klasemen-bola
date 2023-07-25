-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jul 2023 pada 11.15
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klasemen_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `clubs`
--

CREATE TABLE `clubs` (
  `id` int(11) NOT NULL,
  `club_name` varchar(100) NOT NULL,
  `club_city` varchar(100) NOT NULL,
  `played` int(11) NOT NULL DEFAULT 0,
  `win` int(11) NOT NULL DEFAULT 0,
  `lose` int(11) NOT NULL DEFAULT 0,
  `draw` int(11) NOT NULL DEFAULT 0,
  `goals_for` int(11) NOT NULL DEFAULT 0,
  `goals_against` int(11) NOT NULL DEFAULT 0,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `clubs`
--

INSERT INTO `clubs` (`id`, `club_name`, `club_city`, `played`, `win`, `lose`, `draw`, `goals_for`, `goals_against`, `points`) VALUES
(35, 'Kalteng Putra', 'Palangkaraya', 1, 0, 0, 1, 1, 1, 1),
(36, 'Sriwijaya FC', 'Palembang', 1, 0, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `club1_id` int(11) NOT NULL,
  `club2_id` int(11) NOT NULL,
  `score1` int(11) NOT NULL,
  `score2` int(11) NOT NULL,
  `match_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `scores`
--

INSERT INTO `scores` (`id`, `club1_id`, `club2_id`, `score1`, `score2`, `match_date`) VALUES
(77, 35, 36, 1, 1, '2023-07-25 11:14:45');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `club1_id` (`club1_id`),
  ADD KEY `club2_id` (`club2_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `clubs`
--
ALTER TABLE `clubs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`club1_id`) REFERENCES `clubs` (`id`),
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`club2_id`) REFERENCES `clubs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
