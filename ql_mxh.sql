-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2019 at 02:11 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ql_mxh`
--

-- --------------------------------------------------------

--
-- Table structure for table `accouts`
--

DROP TABLE IF EXISTS `accouts`;
CREATE TABLE IF NOT EXISTS `accouts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Displayname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0',
  `Code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Birthday` year(4) NOT NULL,
  `Phonenumber` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`,`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accouts`
--

INSERT INTO `accouts` (`ID`, `Displayname`, `Email`, `Password`, `Status`, `Code`, `Birthday`, `Phonenumber`) VALUES
(15, 'Trần Đỗ Thanh Hải', 'thanhhieu24092002@gmail.com', '$2y$10$oaj9Qo9zQA2StV48LZ7D4.R36W7h8yixnJP5DGzUe2xU1gmR4znn.', 0, '292677', 2000, '0969452985'),
(14, 'Ái Duyên', 'ltweb1.2019@gmail.com', '$2y$10$dkjw3uSBWgnW7b9MvPSr2O0dYaD8UZKOZBmPeAOTMTfcJDXUsMJfO', 1, '', 2000, '0969452985');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `UserID` int(11) NOT NULL,
  `Createday` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `Content`, `UserID`, `Createday`) VALUES
(132, 'fdsfds', 14, '2019-11-23 02:39:02'),
(136, 'gfdgfdg', 14, '2019-11-25 06:00:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
