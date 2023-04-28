-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Apr 2023 um 02:16
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `appointment_finder`
--
CREATE DATABASE IF NOT EXISTS `appointment_finder` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `appointment_finder`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `appointment_id` int(255) NOT NULL,
  `title` mediumtext NOT NULL,
  `location` mediumtext NOT NULL,
  `duration` int(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `expiring_date` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `title`, `location`, `duration`, `description`, `expiring_date`) VALUES
(9, 'Test 1', 'test 1', 12, 'test 1', '2023-04-25'),
(11, 'test2', 'test2', 124, 'test2', '2023-05-30'),
(13, 'test3', 'test3', 23, 'test3', '2023-09-05'),
(14, 'test4', 'test4', 7, 'test4', '2023-09-30'),
(15, 'test 5', 'test5', 3, 'test5', '2023-01-01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timeslot`
--

DROP TABLE IF EXISTS `timeslot`;
CREATE TABLE `timeslot` (
  `timeslot_id` int(255) NOT NULL,
  `date_time` mediumtext NOT NULL,
  `Fk_appointment_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `timeslot`
--

INSERT INTO `timeslot` (`timeslot_id`, `date_time`, `Fk_appointment_id`) VALUES
(16, '2023-09-14 , 12:02', 9),
(17, '2023-05-07 , 12:03', 9),
(18, '2023-05-13 , 13:09', 10),
(19, '2023-05-16 , 06:04', 10),
(20, '2023-05-13 , 13:09', 11),
(21, '2023-05-16 , 06:04', 11),
(22, '2023-06-07 , 08:04', 11),
(23, '2023-03-06 , 07:03', 11),
(26, '2023-03-31 , 04:07', 13),
(27, '2023-04-07 , 07:04', 14),
(28, '2023-02-13 , 07:04', 15),
(29, '2023-04-07 , 08:04', 15);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(255) NOT NULL,
  `user` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `user`) VALUES
(4, 'Suhail'),
(5, 'Adrian');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE `vote` (
  `vote_id` int(255) NOT NULL,
  `Fk_timeslot_id` int(255) NOT NULL,
  `Fk_user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `vote`
--

INSERT INTO `vote` (`vote_id`, `Fk_timeslot_id`, `Fk_user_id`) VALUES
(51, 22, 4),
(52, 23, 4),
(55, 20, 5),
(56, 22, 5),
(57, 29, 4),
(58, 28, 5),
(59, 29, 5);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indizes für die Tabelle `timeslot`
--
ALTER TABLE `timeslot`
  ADD PRIMARY KEY (`timeslot_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indizes für die Tabelle `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`vote_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `timeslot`
--
ALTER TABLE `timeslot`
  MODIFY `timeslot_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `vote`
--
ALTER TABLE `vote`
  MODIFY `vote_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
