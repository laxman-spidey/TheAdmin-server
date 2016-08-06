-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2016 at 08:47 AM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE `nms`;
USE `nms`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shift_id` int(11) NOT NULL,
  `time_in` timestamp,
  `time_out` timestamp,
  PRIMARY KEY (`attendance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


-- --------------------------------------------------------

--
-- Table structure for table `leave`
--

CREATE TABLE IF NOT EXISTS `leave` (
  `leave_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_status` int(1) NOT NULL,
  `leave_submission_date` datetime NOT NULL,
  `leave_date` date NOT NULL,
  `comment` varchar(200) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  PRIMARY KEY (`leave_id`),
  KEY `staff_id` (`staff_id`),
  KEY `staff_id_2` (`staff_id`),
  KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE IF NOT EXISTS `leave_type` (
  `leave_type_id` int(11) NOT NULL,
  `leave_type` varchar(20) NOT NULL,
  `note` varchar(60) NOT NULL,
  PRIMARY KEY (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roaster`
--

CREATE TABLE IF NOT EXISTS `roaster` (
  `roaster_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shift_id` int(11) NOT NULL,
  `swap_status` int(1) NOT NULL,
  `original_shift_id` int(11) NOT NULL,
  PRIMARY KEY (`roaster_id`),
  KEY `staff_id` (`staff_id`),
  KEY `shift_id` (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int(11) NOT NULL,
  `shift` varchar(10) NOT NULL,
  `description` varchar(20) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`shift_id`, `shift`, `description`, `time_in`, `time_out`) VALUES
(1, 'Shift-A', 'morning shift', '08:00:00', '14:00:00'),
(2, 'Shift-B', 'Afternoon shift', '14:00:00', '20:00:00'),
(3, 'Shift-C', 'Night shift', '20:00:00', '08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `swap_request`
--

CREATE TABLE IF NOT EXISTS `swap_request` (
  `swap_id` int(11) NOT NULL,
  `roaster_id_requested` int(11) NOT NULL,
  `shift_id_requested` int(11) NOT NULL,
  `request_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `swap_status` int(1) NOT NULL,
  `roaster_id_accepted` int(11) NOT NULL,
  PRIMARY KEY (`swap_id`),
  KEY `roaster_id_requested` (`roaster_id_requested`),
  KEY `shift_id_requested` (`shift_id_requested`),
  KEY `roaster_id_accepted` (`roaster_id_accepted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `swap_request_sent`
--

CREATE TABLE IF NOT EXISTS `swap_request_sent` (
  `swap_request_sent_id` int(11) NOT NULL,
  `swap_id` int(11) NOT NULL,
  `staff_id_sent_to` int(11) NOT NULL,
  PRIMARY KEY (`swap_request_sent_id`),
  KEY `swap_id` (`swap_id`),
  KEY `staff_id_sent_to` (`staff_id_sent_to`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `swap_rule`
--

CREATE TABLE IF NOT EXISTS `swap_rule` (
  `max_swaps_per_month` int(5) NOT NULL,
  `max_swaps_per_week` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

-- Constraints for table `leave`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`shift_id`) REFERENCES `shift` (`shift_id`) ON DELETE CASCADE ON UPDATE CASCADE;
-- Constraints for table `leave`
--
ALTER TABLE `leave`
  ADD CONSTRAINT `leave_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `attendance` (`attendance_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leave_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_type` (`leave_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roaster`
--
ALTER TABLE `roaster`
  ADD CONSTRAINT `roaster_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `attendance` (`attendance_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roaster_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shift` (`shift_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `swap_request`
--
ALTER TABLE `swap_request`
  ADD CONSTRAINT `swap_request_ibfk_1` FOREIGN KEY (`roaster_id_requested`) REFERENCES `roaster` (`roaster_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `swap_request_ibfk_2` FOREIGN KEY (`shift_id_requested`) REFERENCES `shift` (`shift_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `swap_request_ibfk_3` FOREIGN KEY (`roaster_id_accepted`) REFERENCES `roaster` (`roaster_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `swap_request_sent`
--
ALTER TABLE `swap_request_sent`
  ADD CONSTRAINT `swap_request_sent_ibfk_1` FOREIGN KEY (`swap_id`) REFERENCES `swap_request` (`swap_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `swap_request_sent_ibfk_2` FOREIGN KEY (`staff_id_sent_to`) REFERENCES `attendance` (`attendance_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `staff_id`, `shift_id`, `date`, `time_in`, `time_out`) VALUES
(1, 1, 1, '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 4, 1, '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 4, 1, '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 5, 1, '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 6, 1, '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');