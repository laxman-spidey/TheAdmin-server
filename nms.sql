-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2016 at 10:49 AM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `c9`
--
--
-- Database: `nms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `roaster_id` int(11) NOT NULL,
  `time_in` timestamp NULL DEFAULT NULL,
  `time_out` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`attendance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;



--
-- Table structure for table `leave`
--

CREATE TABLE IF NOT EXISTS `leave` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `leave_status` int(1) NOT NULL,
  `leave_submission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leave_date` date NOT NULL,
  `comment` varchar(200) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  PRIMARY KEY (`leave_id`),
  KEY `staff_id` (`staff_id`),
  KEY `staff_id_2` (`staff_id`),
  KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `leave`
--

INSERT INTO `leave` (`leave_id`, `staff_id`, `leave_status`, `leave_submission_date`, `leave_date`, `comment`, `leave_type_id`) VALUES
(2, 6, 0, '0000-00-00 00:00:00', '0000-00-00', '', 1),
(3, 6, 0, '0000-00-00 00:00:00', '0000-00-00', '', 1),
(4, 6, 0, '0000-00-00 00:00:00', '0000-00-00', '', 1),
(5, 6, 0, '0000-00-00 00:00:00', '0000-00-00', '', 1),
(6, 6, 0, '0000-00-00 00:00:00', '0000-00-00', '', 1),
(7, 6, 0, '2016-08-07 07:33:38', '0000-00-00', '', 1),
(8, 6, 1, '2016-08-07 07:35:04', '2016-08-07', '', 1),
(9, 6, 1, '2016-08-07 07:46:48', '2016-08-08', '', 1),
(10, 6, 1, '2016-08-07 07:47:02', '2016-08-10', '', 1),
(11, 6, 1, '2016-08-07 07:47:13', '2016-08-11', '', 1),
(12, 6, 0, '2016-08-07 07:47:22', '2016-08-12', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `leave_status`
--

CREATE TABLE IF NOT EXISTS `leave_status` (
  `swap_status` int(11) NOT NULL,
  `swap_status_description` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_status`
--

INSERT INTO `leave_status` (`swap_status`, `swap_status_description`) VALUES
(0, 'Submitted'),
(1, 'Accepted'),
(2, 'Rejected'),
(3, 'Cancelled'),
(4, 'Expired');

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE IF NOT EXISTS `leave_type` (
  `leave_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type` varchar(20) NOT NULL,
  `note` varchar(60) NOT NULL,
  PRIMARY KEY (`leave_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`leave_type_id`, `leave_type`, `note`) VALUES
(1, 'earned leave', 'null');

-- --------------------------------------------------------

--
-- Table structure for table `otp_log`
--

CREATE TABLE IF NOT EXISTS `otp_log` (
  `staff_id` int(11) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `otp` varchar(7) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otp_status` varchar(11) NOT NULL DEFAULT 'Generated'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `otp_log`
--

INSERT INTO `otp_log` (`staff_id`, `phone_number`, `otp`, `timestamp`, `otp_status`) VALUES
(6, '8143646268', 'eXNla3h', '2016-08-20 15:04:33', ''),
(7, '9505878984', 'Nzk0MTA', '2016-08-20 17:26:31', ''),
(7, '9505878984', 'NDYwODM', '2016-08-20 19:08:27', ''),
(7, '9505878984', '493680', '2016-08-21 14:25:15', ''),
(7, '9505878984', '139486', '2016-08-21 14:27:45', 'used'),
(7, '9505878984', '690327', '2016-08-22 16:25:34', 'used'),
(7, '9505878984', '756021', '2016-08-22 16:26:31', 'used'),
(0, '9505878984', '359042', '2016-08-22 17:05:26', 'used'),
(7, '9505878984', '205918', '2016-08-22 17:15:58', 'Generated'),
(7, '9505878984', '802671', '2016-08-22 17:16:18', 'Generated'),
(7, '9505878984', '910762', '2016-08-24 17:01:38', 'Generated'),
(7, '9505878984', '051368', '2016-08-24 17:01:46', 'Generated'),
(7, '9505878984', '064137', '2016-08-24 17:06:30', 'Generated'),
(7, '9505878984', '128567', '2016-08-24 17:06:47', 'Generated'),
(7, '9505878984', '214708', '2016-08-24 17:07:50', 'Generated'),
(7, '9505878984', '407692', '2016-08-24 17:08:23', 'Generated'),
(7, '9505878984', '281734', '2016-08-24 17:08:34', 'Generated');

-- --------------------------------------------------------

--
-- Table structure for table `roaster`
--

CREATE TABLE IF NOT EXISTS `roaster` (
  `roaster_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shift_id` int(11) NOT NULL,
  `swap_status` int(1) NOT NULL,
  `original_shift_id` int(11) NOT NULL,
  PRIMARY KEY (`roaster_id`),
  KEY `staff_id` (`staff_id`),
  KEY `shift_id` (`shift_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `roaster`
--

INSERT INTO `roaster` (`roaster_id`, `staff_id`, `date`, `shift_id`, `swap_status`, `original_shift_id`) VALUES
(1, 1, '2016-08-01', 3, 5, 2),
(2, 2, '2016-08-01', 2, 5, 3),
(3, 3, '2016-08-01', 2, 5, 1),
(4, 5, '2016-08-01', 1, 5, 2),
(5, 6, '2016-08-02', 1, 0, 0),
(6, 8, '2016-08-02', 1, 0, 1),
(7, 8, '2016-08-31', 4, 0, 1),
(8, 9, '2016-08-31', 1, 1, 1),
(10, 10, '2016-08-31', 1, 0, 1),
(11, 11, '2016-08-31', 1, 0, 1),
(12, 12, '2016-08-31', 1, 0, 0),
(13, 13, '2016-08-31', 4, 0, 0),
(14, 14, '2016-09-02', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift` varchar(10) NOT NULL,
  `description` varchar(20) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`shift_id`, `shift`, `description`, `time_in`, `time_out`) VALUES
(1, 'Shift-A', 'morning shift', '08:00:00', '14:00:00'),
(2, 'Shift-B', 'Afternoon shift', '14:00:00', '20:00:00'),
(3, 'Shift-C', 'Night shift', '20:00:00', '08:00:00'),
(4, 'Week Off', 'Week Off', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int(11) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `staff_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `phone_number`, `staff_name`) VALUES
(6, '8143646268', 'Mittuwife'),
(7, '9505878984', 'mittu');

-- --------------------------------------------------------

--
-- Table structure for table `swap_request`
--

CREATE TABLE IF NOT EXISTS `swap_request` (
  `swap_id` int(11) NOT NULL AUTO_INCREMENT,
  `roaster_id_requested` int(11) NOT NULL,
  `shift_id_requested` int(11) NOT NULL,
  `request_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `swap_status` int(1) NOT NULL,
  `roaster_id_accepted` int(11) DEFAULT NULL,
  PRIMARY KEY (`swap_id`),
  KEY `roaster_id_requested` (`roaster_id_requested`),
  KEY `shift_id_requested` (`shift_id_requested`),
  KEY `roaster_id_accepted` (`roaster_id_accepted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `swap_request`
--

INSERT INTO `swap_request` (`swap_id`, `roaster_id_requested`, `shift_id_requested`, `request_timestamp`, `swap_status`, `roaster_id_accepted`) VALUES
(1, 1, 1, '2016-08-10 17:35:02', 5, 2),
(2, 1, 1, '2016-08-09 17:07:28', 0, 1),
(3, 1, 1, '2016-08-09 17:07:36', 0, 1),
(4, 1, 1, '2016-08-09 17:07:41', 0, 1),
(5, 1, 1, '2016-08-09 17:10:19', 0, 1),
(6, 1, 1, '2016-08-09 17:12:09', 0, 1),
(7, 1, 1, '2016-08-10 10:10:24', 0, 1),
(8, 1, 1, '2016-08-10 10:11:56', 0, 1),
(9, 1, 1, '2016-08-10 10:12:00', 0, 1),
(10, 1, 1, '2016-08-10 10:12:41', 0, 1),
(11, 1, 1, '2016-08-10 17:43:45', 0, NULL),
(12, 1, 1, '2016-08-10 10:16:01', 0, 1),
(13, 1, 1, '2016-08-10 10:16:22', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `swap_request_sent`
--

CREATE TABLE IF NOT EXISTS `swap_request_sent` (
  `swap_request_sent_id` int(11) NOT NULL AUTO_INCREMENT,
  `swap_id` int(11) NOT NULL,
  `staff_id_sent_to` int(11) NOT NULL,
  PRIMARY KEY (`swap_request_sent_id`),
  KEY `swap_id` (`swap_id`),
  KEY `staff_id_sent_to` (`staff_id_sent_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `swap_request_sent`
--

INSERT INTO `swap_request_sent` (`swap_request_sent_id`, `swap_id`, `staff_id_sent_to`) VALUES
(4, 6, 2),
(5, 7, 2),
(6, 8, 2),
(7, 9, 2),
(8, 10, 2),
(9, 11, 2),
(10, 12, 2),
(11, 13, 2);

-- --------------------------------------------------------

--
-- Table structure for table `swap_rule`
--

CREATE TABLE IF NOT EXISTS `swap_rule` (
  `max_swaps_per_month` int(5) NOT NULL,
  `max_swaps_per_week` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `swap_rule`
--

INSERT INTO `swap_rule` (`max_swaps_per_month`, `max_swaps_per_week`) VALUES
(5, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave`
--
ALTER TABLE `leave`
  ADD CONSTRAINT `leave_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_type` (`leave_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roaster`
--
ALTER TABLE `roaster`
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
  ADD CONSTRAINT `swap_request_sent_ibfk_1` FOREIGN KEY (`swap_id`) REFERENCES `swap_request` (`swap_id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
