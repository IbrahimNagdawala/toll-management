-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 13, 2021 at 11:01 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tolltax`
--

-- --------------------------------------------------------

--
-- Table structure for table `booth_sno`
--

CREATE TABLE `booth_sno` (
  `BOOTH` varchar(100) NOT NULL,
  `SNO` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booth_sno`
--

INSERT INTO `booth_sno` (`BOOTH`, `SNO`) VALUES
('L1', 37),
('L2', 41),
('L3', 4),
('L4', 0),
('L5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cash_up`
--

CREATE TABLE `cash_up` (
  `DATE` date NOT NULL,
  `TIME` varchar(10) NOT NULL,
  `ID` varchar(255) NOT NULL,
  `SHIFT` varchar(50) NOT NULL,
  `SYS_COLLECTION` double NOT NULL,
  `CORRECT_TOLL` double NOT NULL,
  `RS1` int(11) NOT NULL,
  `RS2` int(11) NOT NULL,
  `RS5` int(11) NOT NULL,
  `RS10` int(11) NOT NULL,
  `RS20` int(11) NOT NULL,
  `RS50` int(11) NOT NULL,
  `RS100` int(11) NOT NULL,
  `RS200` int(11) NOT NULL,
  `RS500` int(11) NOT NULL,
  `RS2000` int(11) NOT NULL,
  `CASH_COLLECTION` double NOT NULL,
  `RECOVERY_AMOUNT` double NOT NULL,
  `BOOTH` varchar(20) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `MANUAL_COLLECTION` double NOT NULL,
  `CASHUP_BY` text NOT NULL DEFAULT 'NULL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cash_up`
--

INSERT INTO `cash_up` (`DATE`, `TIME`, `ID`, `SHIFT`, `SYS_COLLECTION`, `CORRECT_TOLL`, `RS1`, `RS2`, `RS5`, `RS10`, `RS20`, `RS50`, `RS100`, `RS200`, `RS500`, `RS2000`, `CASH_COLLECTION`, `RECOVERY_AMOUNT`, `BOOTH`, `NAME`, `MANUAL_COLLECTION`, `CASHUP_BY`) VALUES
('2021-01-08', '03:51:01am', '1234', 'shift 1', 425, 645, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, -220, '2', 'shreyash', 0, 'NULL'),
('2021-01-09', '11:15:27pm', '1234', 'shift 1', 770, 800, 10, 0, 0, 0, 0, 10, 2, 0, 1, 0, 1210, 410, '1', 'shreyash', 0, 'Accountant Accountant'),
('2021-01-10', '11:39:45pm', '1234', 'shift 3', 1385, 1385, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2000, 615, '1', 'shreyash', 0, 'Accountant Accountant'),
('2021-01-11', '11:47:22pm', '12345', 'shift 3', 555, 555, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 500, -55, '2', 'Aniket Thani', 0, 'Accountant Accountant'),
('2021-01-11', '11:54:17pm', '1234', 'shift 3', 505, 505, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 500, -5, '2', 'shreyash', 0, 'Accountant Accountant'),
('2021-01-11', '11:55:27pm', '123456', 'shift 3', 495, 495, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 500, 5, '3', 'akhil', 0, 'Accountant Accountant');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_pass`
--

CREATE TABLE `monthly_pass` (
  `PASS_NO` int(11) NOT NULL,
  `TIME` varchar(10) NOT NULL,
  `DATE` date NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `MOBILE_NO` varchar(255) NOT NULL,
  `ADDRESS` varchar(255) NOT NULL,
  `VEHICLE_TYPE` varchar(50) NOT NULL,
  `VEHICLE_NUMBER` varchar(20) NOT NULL,
  `FROM_DATE` varchar(10) NOT NULL,
  `TO_DATE` varchar(10) NOT NULL,
  `AMOUNT` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sp_exemption`
--

CREATE TABLE `sp_exemption` (
  `SNO` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `VEHICLE_TYPE` varchar(100) NOT NULL,
  `VEHICLE_NUMBER` varchar(100) NOT NULL,
  `MOBILE` int(11) NOT NULL,
  `ADDRESS` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` varchar(20) NOT NULL,
  `TYPE` varchar(25) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `DATE` date NOT NULL,
  `TIME` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `TYPE`, `NAME`, `USERNAME`, `PASSWORD`, `DATE`, `TIME`) VALUES
('123123123', 'audit', 'Audit Audit', 'audit', '$2y$10$Zy0WbgR/JrVKTp6X7dOjAeKr9nqSJe5k20kOXRNAi0tgUInc/h5MO', '2020-11-05', '10:11:38am'),
('1234', 'staff', 'shreyash', 'shreyashp55', '$2y$10$4kDWKHGIycleCkCCWYDWRO7EDP9DfoK7a.fpUpe4UzbBG4MKGSttG', '2020-12-07', '09:33:46pm'),
('12341234', 'accountant', 'Accountant Accountant', 'accountant', '$2y$10$QqOamAxVGG8d0N4je72i0.realaA4E57Xo100vhZicRF3fIJFIkym', '2020-11-05', '10:23:26am'),
('12345', 'staff', 'Aniket Thani', 'aniket7', '$2y$10$Biqi6nVAeeXbNBL./ySDmubkyuAMNYdCMlDbVAW1.L4Z9v.xOI7iu', '2021-01-11', '11:43:21pm'),
('123456', 'staff', 'akhil', 'akhil', '$2y$10$x8NUO37B/Owr8UttXAk22OLqgQNyV9n3wYydcEUIQHBplixYStUFm', '2021-01-11', '11:49:19pm'),
('1234567', 'administrator', 'MKTPL', 'mktpl', '$2y$10$zJe7.v1xEmP1LolXK/yAsO1tBhsdHiKQJgTgVwwkU5cYrSxGjX0/G', '0000-00-00', '11:18:22pm'),
('7777', 'pass manager', 'shardul thakur', 'pmanager', '$2y$10$nDZIe0elg2wAILa6WFLZmeQMpEGs/d1TEUvp/tuW0/tMId7btqtAG', '2021-01-12', '07:16:25pm');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_details`
--

CREATE TABLE `vehicle_details` (
  `SNO` int(11) NOT NULL,
  `DATE` date NOT NULL,
  `TIME` varchar(10) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `V_TYPE` varchar(50) DEFAULT NULL,
  `V_NUMBER` varchar(20) DEFAULT NULL,
  `JOURNEY_TYPE` varchar(50) NOT NULL,
  `TOLL` float DEFAULT NULL,
  `IMAGE` varchar(200) NOT NULL,
  `SHIFT` varchar(10) NOT NULL,
  `BOOTH_NO` int(11) NOT NULL,
  `RECEIPT_NO` varchar(255) NOT NULL,
  `CORRECT_TOLL` double NOT NULL,
  `VALIDATED` int(11) NOT NULL,
  `DIRECTION` varchar(50) NOT NULL,
  `VALIDATED_BY` text NOT NULL DEFAULT 'NULL',
  `OLD_V_TYPE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vehicle_details`
--

INSERT INTO `vehicle_details` (`SNO`, `DATE`, `TIME`, `EMPLOYEE_ID`, `NAME`, `V_TYPE`, `V_NUMBER`, `JOURNEY_TYPE`, `TOLL`, `IMAGE`, `SHIFT`, `BOOTH_NO`, `RECEIPT_NO`, `CORRECT_TOLL`, `VALIDATED`, `DIRECTION`, `VALIDATED_BY`, `OLD_V_TYPE`) VALUES
(1, '2020-12-29', '07:34:02pm', 1234, 'shreyash', 'LCV', 'Sggsgsg', 'Single Journey', 55, 'vehicle_images/L2-1.jpeg', 'shift 3', 2, 'L2-1', 55, 1, 'Up', 'Audit Audit', ''),
(2, '2020-12-29', '07:44:06pm', 1234, 'shreyash', 'cancel', 'Fsgsgdhd', 'Single Journey', 275, 'vehicle_images/L2-2.jpeg', 'shift 3', 2, 'L2-2', 0, 1, 'Up', '', ''),
(3, '2020-12-29', '07:44:45pm', 1234, 'shreyash', 'CAR', 'Gsgsgsg', 'Single Journey', 25, 'vehicle_images/L2-3.jpeg', 'shift 3', 2, 'L2-3', 25, 1, 'Up', 'Audit Audit', ''),
(4, '2020-12-29', '07:46:59pm', 1234, 'shreyash', 'CAR', 'Aniket', 'Single Journey', 25, 'vehicle_images/L2-4.jpeg', 'shift 3', 2, 'L2-4', 25, 1, 'Up', 'Audit Audit', ''),
(5, '2020-12-29', '07:57:11pm', 1234, 'shreyash', 'M-EXLE', 'Sggsgsg', 'Single Journey', 25, 'vehicle_images/L2-5.jpeg', 'shift 3', 2, 'L2-5', 275, 1, 'Up', 'Audit Audit', ''),
(6, '2020-12-29', '07:58:24pm', 1234, 'shreyash', 'CAR', 'Gsgsgsgsg', 'Single Journey', 25, 'vehicle_images/L2-6.jpeg', 'shift 3', 2, 'L2-6', 25, 1, 'Up', 'Audit Audit', ''),
(7, '2020-12-29', '08:01:20pm', 1234, 'shreyash', 'CAR', 'Gsgsgsg', 'Single Journey', 25, 'vehicle_images/L2-7.jpeg', 'shift 3', 2, 'L2-7', 25, 1, 'Up', 'Audit Audit', ''),
(8, '2020-12-29', '08:02:09pm', 1234, 'shreyash', 'CAR', 'Aggsgsgs', 'Single Journey', 25, 'vehicle_images/L2-8.jpeg', 'shift 3', 2, 'L2-8', 25, 1, 'Up', 'Audit Audit', ''),
(9, '2020-12-29', '08:04:35pm', 1234, 'shreyash', 'CAR', 'Gshshhdh', 'Single Journey', 25, 'vehicle_images/L2-9.jpeg', 'shift 3', 2, 'L2-9', 25, 1, 'Up', 'Audit Audit', ''),
(10, '2020-12-29', '08:18:15pm', 1234, 'shreyash', 'TRUCK', 'shvhdhvd', 'Single Journey', 55, 'vehicle_images/L2-10.jpeg', 'shift 3', 2, 'L2-10', 140, 1, 'Up', 'Audit Audit', ''),
(11, '2020-12-29', '08:19:30pm', 1234, 'shreyash', 'M-EXLE', 'djhjjf', 'Single Journey', 275, 'vehicle_images/L2-11.jpeg', 'shift 3', 2, 'L2-11', 275, 1, 'Up', 'Audit Audit', ''),
(12, '2020-12-29', '08:21:32pm', 1234, 'shreyash', 'CAR', 'hvh', 'Single Journey', 25, 'vehicle_images/L2-12.jpeg', 'shift 3', 2, 'L2-12', 25, 1, 'Up', 'Audit Audit', ''),
(13, '2020-12-29', '08:27:30pm', 1234, 'shreyash', 'CAR', 'hvhhvhv', 'Exemption[Gov]', 0, 'vehicle_images/L2-13.jpeg', 'shift 3', 2, 'L2-13', 0, 1, 'Up', 'Audit Audit', ''),
(14, '2020-12-29', '08:38:23pm', 1234, 'shreyash', 'M-EXLE', 'ahdvd', 'Single Journey', 25, 'vehicle_images/L2-14.jpeg', 'shift 3', 2, 'L2-14', 275, 1, 'Up', 'Audit Audit', ''),
(15, '2020-12-29', '08:38:39pm', 1234, 'shreyash', 'LCV', 'dhbhfvhfv', 'Exemption[Police]', 0, 'vehicle_images/L2-15.jpeg', 'shift 3', 2, 'L2-15', 0, 1, 'Up', 'Audit Audit', ''),
(16, '2020-12-31', '05:56:56am', 1234, 'shreyash', 'BUS', 'dbhvdhbf', 'Single Journey', 115, 'vehicle_images/L2-16.jpeg', 'shift 1', 2, 'L2-16', 115, 0, 'Up', '', ''),
(17, '2020-12-31', '05:57:47am', 1234, 'shreyash', 'LCV', 'anikeyybbd', 'Single Journey', 55, 'vehicle_images/L2-17.jpeg', 'shift 1', 2, 'L2-17', 55, 0, 'Up', '', ''),
(18, '2020-12-31', '06:21:56am', 1234, 'shreyash', 'LCV', 'shshvh', 'Exemption[VIP]', 0, 'vehicle_images/L2-18.jpeg', 'shift 1', 2, 'L2-18', 0, 0, 'Up', '', ''),
(19, '2020-12-31', '06:51:36am', 1234, 'shreyash', 'CAR', 'shreyashp55sb', 'Single Journey', 25, 'vehicle_images/L2-19.jpeg', 'shift 1', 2, 'L2-19', 25, 0, 'Up', '', ''),
(20, '2020-12-31', '06:52:10am', 1234, 'shreyash', 'NA', 'hvdhvhv', 'Single Journey', 100, 'vehicle_images/L2-20.jpeg', 'shift 1', 2, 'L2-20', 100, 0, 'Up', '', ''),
(21, '2020-12-31', '06:52:40am', 1234, 'shreyash', 'TRUCK', 'najdhshsbshre', 'Exemption[VIP]', 0, 'vehicle_images/L2-21.jpeg', 'shift 1', 2, 'L2-21', 0, 0, 'Up', '', ''),
(22, '2020-12-31', '07:04:23am', 1234, 'shreyash', 'TRUCK', 'bushaiye', 'Exemption[Other]', 0, 'vehicle_images/L2-22.jpeg', 'shift 1', 2, 'L2-22', 0, 0, 'Up', '', ''),
(23, '2021-01-01', '02:16:22am', 1234, 'shreyash', 'TRUCK', 'aniketfecg', 'Single Journey', 140, 'vehicle_images/L2-23.jpeg', 'shift 1', 2, 'L2-23', 140, 1, 'Up', '', ''),
(24, '2021-01-02', '01:38:25am', 1234, 'shreyash', 'BUS', 'MP13DS4236', 'Single Journey', 55, 'vehicle_images/L2-24.jpeg', 'shift 1', 2, 'L2-24', 115, 1, 'Up', 'Audit Audit', ''),
(25, '2021-01-05', '01:28:46am', 1234, 'shreyash', 'M-EXLE', 'abdhbhdv', 'Single Journey', 55, 'vehicle_images/L2-27.jpeg', 'shift 1', 2, 'L2-27', 275, 1, 'Up', 'Audit Audit', ''),
(26, '2021-01-05', '01:29:15am', 1234, 'shreyash', 'BUS', 'aniketybns', 'Single Journey', 115, 'vehicle_images/L2-28.jpeg', 'shift 1', 2, 'L2-28', 115, 1, 'Up', 'Audit Audit', ''),
(27, '2021-01-05', '02:04:15am', 1234, 'shreyash', 'TRUCK', 'snbdbdj', 'Single Journey', 25, 'vehicle_images/L2-29.jpeg', 'shift 1', 2, 'L2-29', 140, 1, 'Up', 'Audit Audit', ''),
(28, '2021-01-08', '12:55:53am', 1234, 'shreyash', 'BUS', 'helloahdvd', 'Single Journey', 115, 'vehicle_images/L2-30.jpeg', 'shift 1', 2, 'L2-30', 115, 1, 'Up', 'Audit Audit', ''),
(29, '2021-01-08', '12:56:03am', 1234, 'shreyash', 'TRUCK', 'truck', 'Single Journey', 140, 'vehicle_images/L2-31.jpeg', 'shift 1', 2, 'L2-31', 140, 1, 'Up', 'Audit Audit', ''),
(30, '2021-01-08', '12:56:18am', 1234, 'shreyash', 'M-EXLE', 'lcvhello', 'Single Journey', 55, 'vehicle_images/L2-32.jpeg', 'shift 1', 2, 'L2-32', 275, 1, 'Up', 'Audit Audit', ''),
(31, '2021-01-08', '12:56:43am', 1234, 'shreyash', 'BUS', 'bushye', 'Single Journey', 115, 'vehicle_images/L2-33.jpeg', 'shift 1', 2, 'L2-33', 115, 1, 'Up', 'Audit Audit', ''),
(32, '2021-01-09', '01:56:10am', 1234, 'shreyash', 'LCV', 'carhyeoldcheck', 'Single Journey', 25, 'vehicle_images/L1-1.jpeg', 'shift 1', 1, 'L1-1', 55, 1, 'Up', 'Audit Audit', 'CAR'),
(33, '2021-01-09', '01:58:31am', 1234, 'shreyash', 'M-EXLE', 'mexeleholdcheck', 'Single Journey', 275, 'vehicle_images/L1-2.jpeg', 'shift 1', 1, 'L1-2', 275, 1, 'Up', 'Audit Audit', 'M-EXLE'),
(34, '2021-01-09', '03:12:13am', 1234, 'shreyash', 'CAR', 'abcdefgh', 'Single Journey', 25, 'vehicle_images/L1-3.jpeg', 'shift 1', 1, 'L1-3', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(35, '2021-01-09', '03:12:28am', 1234, 'shreyash', 'BUS', 'ijklmnop', 'Single Journey', 115, 'vehicle_images/L1-4.jpeg', 'shift 1', 1, 'L1-4', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(36, '2021-01-09', '03:12:44am', 1234, 'shreyash', 'M-EXLE', 'qrstuvwx', 'Single Journey', 275, 'vehicle_images/L1-5.jpeg', 'shift 1', 1, 'L1-5', 275, 1, 'Up', 'Audit Audit', 'M-EXLE'),
(37, '2021-01-09', '03:13:00am', 1234, 'shreyash', 'LCV', 'xyz12345', 'Single Journey', 55, 'vehicle_images/L1-6.jpeg', 'shift 1', 1, 'L1-6', 55, 1, 'Up', 'Audit Audit', 'LCV'),
(43, '2021-01-09', '06:09:12pm', 1234, 'shreyash', 'CAR', '123456', 'Single Journey', 25, 'vehicle_images/L1-12.jpeg', 'shift 3', 1, 'L1-12', 25, 0, 'Up', 'NULL', 'CAR'),
(44, '2021-01-09', '06:13:32pm', 1234, 'shreyash', 'BUS', 'anikethumai', 'Single Journey', 115, 'vehicle_images/L1-13.jpeg', 'shift 3', 1, 'L1-13', 115, 0, 'Up', 'NULL', 'BUS'),
(45, '2021-01-09', '06:14:20pm', 1234, 'shreyash', 'LCV', 'hvhvhdvvdhv', 'Single Journey', 55, 'vehicle_images/L1-14.jpeg', 'shift 3', 1, 'L1-14', 55, 0, 'Up', 'NULL', 'LCV'),
(46, '2021-01-09', '06:15:06pm', 1234, 'shreyash', 'CAR', 'anikethumai', 'Single Journey', 25, 'vehicle_images/L1-15.jpeg', 'shift 3', 1, 'L1-15', 25, 0, 'Up', 'NULL', 'CAR'),
(47, '2021-01-10', '05:43:20pm', 1234, 'shreyash', 'BUS', 'aniket', 'Single Journey', 115, 'vehicle_images/L1-16.jpeg', 'shift 3', 1, 'L1-16', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(48, '2021-01-10', '05:48:42pm', 1234, 'shreyash', 'CAR', 'Fghgghh', 'Single Journey', 25, 'vehicle_images/L1-17.jpeg', 'shift 3', 1, 'L1-17', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(49, '2021-01-10', '05:48:42pm', 1234, 'shreyash', 'CAR', 'Ahgsgshsb', 'Single Journey', 25, 'vehicle_images/L1-18.jpeg', 'shift 3', 1, 'L1-18', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(50, '2021-01-10', '05:55:35pm', 1234, 'shreyash', 'CAR', 'hvhhvvhv', 'Single Journey', 25, 'vehicle_images/L1-19.jpeg', 'shift 3', 1, 'L1-19', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(51, '2021-01-10', '05:57:21pm', 1234, 'shreyash', 'BUS', 'phpoto', 'Single Journey', 115, 'vehicle_images/L1-20.jpeg', 'shift 3', 1, 'L1-20', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(52, '2021-01-10', '05:59:07pm', 1234, 'shreyash', 'CAR', 'bjdbbddfjb', 'Single Journey', 25, 'vehicle_images/L1-21.jpeg', 'shift 3', 1, 'L1-21', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(53, '2021-01-10', '06:00:05pm', 1234, 'shreyash', 'CAR', 'photoaaja', 'Single Journey', 25, 'vehicle_images/L1-22.jpeg', 'shift 3', 1, 'L1-22', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(54, '2021-01-10', '06:01:41pm', 1234, 'shreyash', 'CAR', 'sjbjjbbfj', 'Single Journey', 25, 'vehicle_images/L1-23.jpeg', 'shift 3', 1, 'L1-23', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(55, '2021-01-10', '06:03:10pm', 1234, 'shreyash', 'LCV', 'shvdvhfvhf', 'Single Journey', 55, 'vehicle_images/L1-24.jpeg', 'shift 3', 1, 'L1-24', 55, 1, 'Up', 'Audit Audit', 'LCV'),
(56, '2021-01-10', '06:04:09pm', 1234, 'shreyash', 'CAR', 'fyfyyff', 'Single Journey', 25, 'vehicle_images/L1-25.jpeg', 'shift 3', 1, 'L1-25', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(57, '2021-01-10', '06:07:42pm', 1234, 'shreyash', 'CAR', 'ccgcghbj', 'Single Journey', 25, 'vehicle_images/L1-26.jpeg', 'shift 3', 1, 'L1-26', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(58, '2021-01-10', '06:09:03pm', 1234, 'shreyash', 'CAR', 'gfgchhvhhch', 'Single Journey', 25, 'vehicle_images/L1-27.jpeg', 'shift 3', 1, 'L1-27', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(59, '2021-01-10', '06:10:38pm', 1234, 'shreyash', 'LCV', 'wjbhjdhvvdd', 'Single Journey', 55, 'vehicle_images/L1-28.jpeg', 'shift 3', 1, 'L1-28', 55, 1, 'Up', 'Audit Audit', 'LCV'),
(60, '2021-01-10', '06:36:25pm', 1234, 'shreyash', 'M-EXLE', 'avhvhfhf', 'Single Journey', 275, 'vehicle_images/L1-29.jpeg', 'shift 3', 1, 'L1-29', 275, 1, 'Up', 'Audit Audit', 'M-EXLE'),
(61, '2021-01-10', '06:39:39pm', 1234, 'shreyash', 'CAR', 'Craphitot', 'Single Journey', 25, 'vehicle_images/L1-30.jpeg', 'shift 3', 1, 'L1-30', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(62, '2021-01-10', '06:41:25pm', 1234, 'shreyash', 'CAR', 'Sgvshshsh', 'Single Journey', 25, 'vehicle_images/L1-31.jpeg', 'shift 3', 1, 'L1-31', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(63, '2021-01-10', '06:44:31pm', 1234, 'shreyash', 'CAR', 'Atphiti', 'Single Journey', 25, 'vehicle_images/L1-33.jpeg', 'shift 3', 1, 'L1-33', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(64, '2021-01-10', '06:49:11pm', 1234, 'shreyash', 'LCV', 'Mtphoto', 'Single Journey', 55, 'vehicle_images/L1-34.jpeg', 'shift 3', 1, 'L1-34', 55, 1, 'Up', 'Audit Audit', 'LCV'),
(65, '2021-01-10', '06:49:43pm', 1234, 'shreyash', 'BUS', 'Sggdhdhdh', 'Exemption[VIP]', 0, 'vehicle_images/L1-35.jpeg', 'shift 3', 1, 'L1-35', 0, 1, 'Up', 'Audit Audit', 'BUS'),
(66, '2021-01-10', '06:50:36pm', 1234, 'shreyash', 'M-EXLE', 'Abohtotj', 'Single Journey', 275, 'vehicle_images/L1-36.jpeg', 'shift 3', 1, 'L1-36', 275, 1, 'Up', 'Audit Audit', 'M-EXLE'),
(67, '2021-01-10', '06:50:53pm', 1234, 'shreyash', 'BUS', 'Sggsgshdhs', 'Single Journey', 115, 'vehicle_images/L1-37.jpeg', 'shift 3', 1, 'L1-37', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(68, '2021-01-10', '06:43:28pm', 1234, 'shreyash', 'CAR', 'jtphoto', 'Single Journey', 25, 'vehicle_images/L1-32.jpeg', 'shift 3', 1, 'L1-32', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(69, '2021-01-11', '11:44:47pm', 12345, 'Aniket Thani', 'CAR', 'djbjvfh', 'Single Journey', 25, 'vehicle_images/L2-34.jpeg', 'shift 3', 2, 'L2-34', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(70, '2021-01-11', '11:44:58pm', 12345, 'Aniket Thani', 'BUS', 'djbjfvhvf', 'Single Journey', 115, 'vehicle_images/L2-35.jpeg', 'shift 3', 2, 'L2-35', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(71, '2021-01-11', '11:45:10pm', 12345, 'Aniket Thani', 'TRUCK', 'jvhfvhvf', 'Single Journey', 140, 'vehicle_images/L2-36.jpeg', 'shift 3', 2, 'L2-36', 140, 1, 'Up', 'Audit Audit', 'TRUCK'),
(72, '2021-01-11', '11:45:20pm', 12345, 'Aniket Thani', 'M-EXLE', 'shvhdvhd', 'Single Journey', 275, 'vehicle_images/L2-37.jpeg', 'shift 3', 2, 'L2-37', 275, 1, 'Up', 'Audit Audit', 'M-EXLE'),
(73, '2021-01-11', '11:45:42pm', 1234, 'shreyash', 'BUS', 'shvdc', 'Single Journey', 115, 'vehicle_images/L2-38.jpeg', 'shift 3', 2, 'L2-38', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(74, '2021-01-11', '11:45:49pm', 1234, 'shreyash', 'BUS', 'sdhvdvhdv', 'Single Journey', 115, 'vehicle_images/L2-39.jpeg', 'shift 3', 2, 'L2-39', 115, 1, 'Up', 'Audit Audit', 'BUS'),
(75, '2021-01-11', '11:46:01pm', 1234, 'shreyash', 'CAR', 'shvhdvd', 'Exemption[VIP]', 0, 'vehicle_images/L2-40.jpeg', 'shift 3', 2, 'L2-40', 0, 1, 'Up', 'Audit Audit', 'CAR'),
(76, '2021-01-11', '11:46:11pm', 1234, 'shreyash', 'M-EXLE', 'djjdvvd', 'Single Journey', 275, 'vehicle_images/L2-41.jpeg', 'shift 3', 2, 'L2-41', 275, 1, 'Up', 'Audit Audit', 'M-EXLE'),
(77, '2021-01-11', '11:50:01pm', 123456, 'akhil', 'LCV', 'hvhvhv', 'Single Journey', 55, 'vehicle_images/L3-1.jpeg', 'shift 3', 3, 'L3-1', 55, 1, 'Up', 'Audit Audit', 'LCV'),
(78, '2021-01-11', '11:50:10pm', 123456, 'akhil', 'TRUCK', 'hvhhv', 'Single Journey', 140, 'vehicle_images/L3-2.jpeg', 'shift 3', 3, 'L3-2', 140, 1, 'Up', 'Audit Audit', 'TRUCK'),
(79, '2021-01-11', '11:50:17pm', 123456, 'akhil', 'CAR', 'gggcg', 'Single Journey', 25, 'vehicle_images/L3-3.jpeg', 'shift 3', 3, 'L3-3', 25, 1, 'Up', 'Audit Audit', 'CAR'),
(80, '2021-01-11', '11:50:29pm', 123456, 'akhil', 'M-EXLE', 'aetyuijb', 'Single Journey', 275, 'vehicle_images/L3-4.jpeg', 'shift 3', 3, 'L3-4', 275, 1, 'Up', 'Audit Audit', 'M-EXLE');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type`
--

CREATE TABLE `vehicle_type` (
  `VEHICLE_TYPE` varchar(50) NOT NULL,
  `TOLL_AMOUNT` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vehicle_type`
--

INSERT INTO `vehicle_type` (`VEHICLE_TYPE`, `TOLL_AMOUNT`) VALUES
('CAR', 25),
('LCV', 55),
('BUS', 115),
('TRUCK', 140),
('M-EXLE', 275),
('NA', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booth_sno`
--
ALTER TABLE `booth_sno`
  ADD PRIMARY KEY (`BOOTH`);

--
-- Indexes for table `monthly_pass`
--
ALTER TABLE `monthly_pass`
  ADD PRIMARY KEY (`PASS_NO`);

--
-- Indexes for table `sp_exemption`
--
ALTER TABLE `sp_exemption`
  ADD PRIMARY KEY (`SNO`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  ADD PRIMARY KEY (`SNO`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `monthly_pass`
--
ALTER TABLE `monthly_pass`
  MODIFY `PASS_NO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sp_exemption`
--
ALTER TABLE `sp_exemption`
  MODIFY `SNO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  MODIFY `SNO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
