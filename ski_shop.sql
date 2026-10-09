-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2026 at 09:43 AM
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
-- Database: `ski_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `narudzbine`
--

CREATE TABLE `narudzbine` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `oprema_id` int(11) NOT NULL,
  `kolicina` int(11) NOT NULL,
  `ukupno` decimal(10,2) NOT NULL,
  `status` enum('U obradi','Isporučeno','Otkazano') DEFAULT 'U obradi',
  `datum` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `narudzbine`
--

INSERT INTO `narudzbine` (`id`, `user_id`, `oprema_id`, `kolicina`, `ukupno`, `status`, `datum`) VALUES
(1, 2, 2, 1, 28500.00, 'U obradi', '2026-07-24 07:38:06'),
(2, 2, 3, 2, 26400.00, 'Isporučeno', '2026-07-24 07:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `oprema`
--

CREATE TABLE `oprema` (
  `id` int(11) NOT NULL,
  `naziv` varchar(150) NOT NULL,
  `kategorija` varchar(50) NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `kolicina` int(11) NOT NULL DEFAULT 0,
  `opis` text DEFAULT NULL,
  `slika` text DEFAULT NULL,
  `kreiran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oprema`
--

INSERT INTO `oprema` (`id`, `naziv`, `kategorija`, `cena`, `kolicina`, `opis`, `slika`, `kreiran`) VALUES
(1, 'Atomic Redster G9 Skije', 'Skije', 54000.00, 8, 'Profesionalne skije za veleslalom sa Servotec tehnologijom za maksimalnu stabilnost na stazi.', 'slike/skije.jpg', '2026-07-24 07:38:06'),
(2, 'Salomon X-Pro 100 Pancerice', 'Pancerice', 28500.00, 12, 'Ski pancerice sa Custom Shell tehnologijom i fleksibilnošću flex 100.', 'slike/pancerice.jpg', '2026-07-24 07:38:06'),
(3, 'Oakley MOD3 Kaciga', 'Zaštitna oprema', 13200.00, 15, 'Zaštitna ski kaciga sa integrisanom ventilacijom i MIPS zaštitom.', 'slike/kaciga1.jpg', '2026-07-24 07:38:06'),
(4, 'Head Worldcup Rebels Skije', 'Skije', 62000.00, 5, 'Vrhunske trkačke skije namenjene naprednim skijašima i ljubiteljima brzine.', 'slike/skije1.jpg', '2026-07-24 07:38:06'),
(5, 'Anon M4 Ski Naočare', 'Zaštitna oprema', 16800.00, 10, 'Profesionalne ski naočare sa magnetnom izmenom stakala i anti-fog zaštitom.', 'slike/naocare.jpg', '2026-07-24 07:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `uloga` enum('admin','korisnik') NOT NULL DEFAULT 'korisnik',
  `kreiran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ime`, `email`, `lozinka`, `uloga`, `kreiran`) VALUES
(1, 'Admin Marko', 'admin@skishop.com', '$2y$10$4yS/9b109h20cWwWbYJtveZ90R3Yv3R0L5jE8gM7V5H/Z3K1J2R3m', 'admin', '2026-07-24 07:12:23'),
(2, 'Korisnik Nikola', 'user@skishop.com', '$2y$10$4yS/9b109h20cWwWbYJtveZ90R3Yv3R0L5jE8gM7V5H/Z3K1J2R3m', 'korisnik', '2026-07-24 07:12:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `narudzbine`
--
ALTER TABLE `narudzbine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `oprema_id` (`oprema_id`);

--
-- Indexes for table `oprema`
--
ALTER TABLE `oprema`
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
-- AUTO_INCREMENT for table `narudzbine`
--
ALTER TABLE `narudzbine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oprema`
--
ALTER TABLE `oprema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `narudzbine`
--
ALTER TABLE `narudzbine`
  ADD CONSTRAINT `narudzbine_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `narudzbine_ibfk_2` FOREIGN KEY (`oprema_id`) REFERENCES `oprema` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
