-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 09. Jun 2017 um 14:20
-- Server-Version: 10.1.21-MariaDB
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `bitnami_wordpress`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shop_items`
--

CREATE TABLE `shop_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `shop_items`
--

INSERT INTO `shop_items` (`id`, `title`, `description`, `image`, `position`, `price`) VALUES
(1, 'Schlingel', 'Stubenrein ;)', 'http://localhost/wordpress/wp-content/uploads/2017/05/Schlingel_Formatiert.jpg', 0, 100),
(2, 'Urstein Logo', 'digital', 'http://localhost/wordpress/wp-content/uploads/2017/05/Ursteinburg-bearbeitet.bmp', 1, 0.5),
(16, 'Pfadihemd', 'Ab der Wolfsstufe sollten alle Mitglider ein Pfadihemd haben.\r\nDas Hemd gibt es in den Grössen 128, 144 und 164.\r\nAlle Pfadimitglider der Pfadi Urstein tragen ein braunes Hemd.', 'http://localhost/wordpress/wp-content/uploads/2017/06/8315_hemd-pfadi-langarm.jpg', 2, 50);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `shop_items`
--
ALTER TABLE `shop_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
