-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2014 at 09:15 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bookings`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `address1` varchar(20) NOT NULL,
  `address2` varchar(20) DEFAULT NULL,
  `town` varchar(20) DEFAULT NULL,
  `postcode` varchar(8) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `memcat` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`ID`, `username`, `fname`, `lname`, `address1`, `address2`, `town`, `postcode`, `phone`, `email`, `password`, `memcat`) VALUES
(1, 'HoraceBroon', 'Horace', 'Broon', '12 High Street', 'Partick', 'Glasgow', 'GG7 9YU', '123456', 'horace@localhost', 'horace', 'Ordinary'),
(2, 'MaggieBroon', 'Maggie', 'Broon', '12 Princes Street', 'City Centre', 'Edinburgh', 'EH1 1MB', '123456', 'maggie@localhost', 'maggie', 'Friend'),
(3, 'Ayetwin', 'Ayetwin', 'Broon', '12 Glebe Street', 'Sinderins', 'Dundee', 'DD1 3DD', '123456', 'ayetwin@localhost', 'ayetwin', 'Premier'),
(4, 'Ithertwin', 'Ithertwin', 'Broon', '12 Glebe Street', 'Sinderins', 'Dundee', 'DD1 3DD', '1234562', 'ithertwin@localhost', 'ithertwin', 'Friend'),
(5, 'HenBroon', 'Hen', 'Broon', '12 High Street', 'Partick', 'Glasgow', 'GG5 6UI', '123456', 'hen@localhost', 'hen', 'Ordinary'),
(6, 'DaphneBroon', 'Daphne', 'Broon', '12 Glebe Street', 'Sinderins', 'Dundee', 'DD1 3DD', '123456', 'daphne@localhost', 'daphne', 'Ordinary'),
(7, 'MawBroon', 'Maw', 'Broon', '12 Glebe Street', 'Sinderins', 'Dundee', 'DD1 3DD', '123456', 'maw@localhost', 'maw', 'Friend'),
(8, 'PawBroon', 'Paw', 'Broon', '12 Glebe Street', 'Sinderins', 'Dundee', 'DD1 3DD', '123456', 'paw@localhost', 'paw', 'Premier'),
(9, 'JoeBroon', 'Joe', 'Broon', '12 Glebe Street', 'Sinderins', 'Dundee', 'DD1 3DD', '1234562', 'joe@localhost', 'joe', 'Ordinart'),
(10, 'GranpawBroon', 'Granpaw', 'Broon', '14 Hill Street', 'Govan', 'Glasgow', 'GG6 7YU', '54545454', 'granpaw@localhost', 'granpaw', 'Friend');

-- --------------------------------------------------------

--
-- Table structure for table `purchased`
--

DROP TABLE IF EXISTS `purchased`;
CREATE TABLE IF NOT EXISTS `purchased` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `memberID` int(4) NOT NULL,
  `seatID` int(3) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Bridge Table' AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
CREATE TABLE IF NOT EXISTS `seats` (
  `ID` int(3) NOT NULL AUTO_INCREMENT,
  `seatnum` varchar(4) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`ID`, `seatnum`, `status`) VALUES
(1, 'A1', 1),
(2, 'A2', 1),
(3, 'A3', 1),
(4, 'A4', 1),
(5, 'A5', 1),
(6, 'A6', 1),
(7, 'B1', 1),
(8, 'B2', 1),
(9, 'B3', 1),
(10, 'B4', 1),
(11, 'B5', 1),
(12, 'B6', 1),
(13, 'C1', 1),
(14, 'C2', 1),
(15, 'C3', 1),
(16, 'C4', 1),
(17, 'C5', 1),
(18, 'C6', 1),
(19, 'D1', 1),
(20, 'D2', 1),
(21, 'D3', 1),
(22, 'D4', 1),
(23, 'D5', 1),
(24, 'D6', 1),
(25, 'D7', 1),
(26, 'D8', 1),
(27, 'D9', 1),
(28, 'D10', 1),
(29, 'D11', 1),
(30, 'D12', 1),
(31, 'D13', 1),
(32, 'D14', 1),
(33, 'D15', 1),
(34, 'D16', 1),
(35, 'D17', 1),
(36, 'D18', 1),
(37, 'E1', 1),
(38, 'E2', 1),
(39, 'E3', 1),
(40, 'E4', 1),
(41, 'E5', 1),
(42, 'E6', 1),
(43, 'E7', 1),
(44, 'E8', 1),
(45, 'E9', 1),
(46, 'E10', 1),
(47, 'E11', 1),
(48, 'E12', 1),
(49, 'E13', 1),
(50, 'E14', 1),
(51, 'E15', 1),
(52, 'E16', 1),
(53, 'E17', 1),
(54, 'E18', 1),
(55, 'F1', 1),
(56, 'F2', 1),
(57, 'F3', 1),
(58, 'F4', 1),
(59, 'F5', 1),
(60, 'F6', 1),
(61, 'F7', 1),
(62, 'F8', 1),
(63, 'F9', 1),
(64, 'F10', 1),
(65, 'F11', 1),
(66, 'F12', 1),
(67, 'F13', 1),
(68, 'F14', 1),
(69, 'F15', 1),
(70, 'F16', 1),
(71, 'F17', 1),
(72, 'F18', 1),
(73, 'G1', 1),
(74, 'G2', 1),
(75, 'G3', 1),
(76, 'G4', 1),
(77, 'G5', 1),
(78, 'G6', 1),
(79, 'G7', 1),
(80, 'G8', 1),
(81, 'G9', 1),
(82, 'G10', 1),
(83, 'G11', 1),
(84, 'G12', 1),
(85, 'G13', 1),
(86, 'G14', 1),
(87, 'G15', 1),
(88, 'G16', 1),
(89, 'G17', 1),
(90, 'G18', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
