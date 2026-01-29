-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2016 at 12:02 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cvtghhhd_hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_type`
--

CREATE TABLE `attendance_type` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `symbol` varchar(5) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendance_type`
--

INSERT INTO `attendance_type` (`id`, `title`, `symbol`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`) VALUES
(1, 'Hưởng lương (HL).', 'x', NULL, NULL, NULL, NULL),
(2, 'Nghỉ phép', 'p', NULL, NULL, NULL, NULL),
(3, 'Ốm', 'Ô', NULL, NULL, NULL, NULL),
(4, 'Con ốm', 'Cô', NULL, NULL, NULL, NULL),
(5, 'Thai sản', 'Ts', NULL, NULL, NULL, NULL),
(6, 'Tai nạn', 'T', NULL, NULL, NULL, NULL),
(7, 'Hội nghị, học tập', 'H', NULL, NULL, NULL, NULL),
(8, 'Nghỉ bù', 'Nb', NULL, NULL, NULL, NULL),
(9, 'Nghỉ không lương', 'No', NULL, NULL, NULL, NULL),
(10, 'Nghỉ lễ', 'Nl', NULL, NULL, NULL, NULL),
(11, 'Không đi muộn', 'O', NULL, NULL, NULL, NULL),
(12, 'Đi muộn', 'M', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bonus_type`
--

CREATE TABLE `bonus_type` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` enum('ADDITIONAL','MINUS') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coefficients_salary`
--

CREATE TABLE `coefficients_salary` (
  `id` int(11) NOT NULL,
  `he_so` float NOT NULL,
  `quy_doi` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `coefficients_salary`
--

INSERT INTO `coefficients_salary` (`id`, `he_so`, `quy_doi`) VALUES
(1, 4, 2000000);

-- --------------------------------------------------------

--
-- Table structure for table `cookie_user`
--

CREATE TABLE `cookie_user` (
  `id` int(20) NOT NULL,
  `id_personnel` int(20) NOT NULL,
  `cookie` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `time_c` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `manager_id` tinyint(4) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `depth` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `title`, `parent_id`, `manager_id`, `status`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`, `depth`) VALUES
(1, '2401', 0, 1, NULL, '2016-04-25 04:29:03', '2016-04-25 04:29:03', NULL, NULL, NULL),
(2, '2405', 0, 0, NULL, '2016-04-25 08:22:07', '2016-04-25 09:08:04', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_criteria`
--

CREATE TABLE `evaluation_criteria` (
  `id` int(11) NOT NULL,
  `criteria_content` varchar(200) NOT NULL COMMENT 'lưu dưới dạng json',
  `description` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `evaluation_criteria`
--

INSERT INTO `evaluation_criteria` (`id`, `criteria_content`, `description`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`) VALUES
(8, 'Tiêu chí 1', 'a', NULL, NULL, NULL, NULL),
(15, 'Tiêu chí 2', '', '2016-12-28 00:00:00', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_default_criteria`
--

CREATE TABLE `evaluation_default_criteria` (
  `id` int(11) NOT NULL,
  `content` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_stage`
--

CREATE TABLE `evaluation_stage` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `update_by_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `evaluation_stage`
--

INSERT INTO `evaluation_stage` (`id`, `title`, `date_start`, `date_end`, `created_at`, `updated_at`, `created_by_id`, `update_by_id`, `status`) VALUES
(49, 'Bộ tiêu chí 1', '2016-12-01 00:00:00', '2016-12-31 00:00:00', '2016-12-28 00:00:00', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_stage_detail`
--

CREATE TABLE `evaluation_stage_detail` (
  `stage_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `evaluation_stage_detail`
--

INSERT INTO `evaluation_stage_detail` (`stage_id`, `criteria_id`) VALUES
(36, 1),
(36, 2),
(37, 1),
(37, 3),
(38, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 5),
(44, 4),
(45, 5),
(45, 4),
(46, 4),
(47, 8),
(47, 15),
(48, 8),
(49, 8),
(49, 15);

-- --------------------------------------------------------

--
-- Table structure for table `fixed_bonus_setting`
--

CREATE TABLE `fixed_bonus_setting` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `money_value` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_setting`
--

CREATE TABLE `holiday_setting` (
  `id` int(11) NOT NULL,
  `is_salary` enum('0','1') DEFAULT NULL COMMENT 'nghỉ tính lương hay không',
  `is_repeat` enum('0','1') DEFAULT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(2) NOT NULL,
  `reason` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `holiday_setting`
--

INSERT INTO `holiday_setting` (`id`, `is_salary`, `is_repeat`, `day`, `month`, `year`, `reason`) VALUES
(2, '1', '0', 12, 3, 4, 'aaa'),
(3, '1', '1', 12, 3, 44, 'bbbbbbb'),
(4, '1', '1', 11, 11, 11, 'oooo'),
(5, '1', '1', 0, 0, 0, 'ttt'),
(6, '1', '1', 10, 7, 2016, 'Hôm nay 10-06-2016 Cho Nghỉ');

-- --------------------------------------------------------

--
-- Table structure for table `info_company`
--

CREATE TABLE `info_company` (
  `id` int(11) NOT NULL,
  `name` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(225) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `info_company`
--

INSERT INTO `info_company` (`id`, `name`, `site`, `logo`) VALUES
(1, 'Công ty phần mềm tower Hà Nội', 'Hệ thống quản lý nhân sự', '');

-- --------------------------------------------------------

--
-- Table structure for table `job_titles`
--

CREATE TABLE `job_titles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `job_titles`
--

INSERT INTO `job_titles` (`id`, `title`, `status`) VALUES
(1, 'kỹ sư phát triển', 1),
(2, 'Kỹ sư chính\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_100000_create_password_resets_table', 1),
('2016_06_23_033100_create_roles_table', 1),
('2016_06_23_034007_create_privileges_table', 1),
('2016_06_23_034009_create_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parameters`
--

CREATE TABLE `parameters` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `parameter_type` int(2) NOT NULL,
  `multiplication` int(2) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parameters`
--

INSERT INTO `parameters` (`id`, `title`, `value`, `type`, `parameter_type`, `multiplication`, `status`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`) VALUES
(5, 'abcxyz', '100000000', '0', 1, 1, 1, NULL, NULL, NULL, NULL),
(6, 'oooooo', '10000', '0', 1, 1, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `gender` enum('0','1') NOT NULL,
  `birthday` date NOT NULL,
  `avatar` text,
  `job_date_in` date DEFAULT NULL,
  `job_date_out` date DEFAULT NULL,
  `job_title_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `manager_id` tinyint(4) DEFAULT NULL,
  `role_group_id` int(11) DEFAULT NULL,
  `roles` varchar(225) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `reserve` float DEFAULT NULL,
  `reserve_1` float DEFAULT NULL,
  `reserve_2` float DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `identity_number` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `current_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`id`, `fullname`, `first_name`, `last_name`, `email`, `phone_number`, `gender`, `birthday`, `avatar`, `job_date_in`, `job_date_out`, `job_title_id`, `department_id`, `manager_id`, `role_group_id`, `roles`, `status`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`, `reserve`, `reserve_1`, `reserve_2`, `user_id`, `identity_number`, `address`, `current_address`) VALUES
(1, 'Vũ Thị Mỹ Mỹ', 'Vũ Thị', 'Mỹ Mỹ', 'admin@gmail.com', '098767676899', '0', '1983-07-19', '1470386670.IMG_0001.JPG', NULL, NULL, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '01676424684688', 'Hà Nam', 'Hà Nội'),
(2, 'Phan Kiên Trung', 'Phan Kiên', 'Trung', 'trungpk@gmail.com', '435464', '1', '2016-07-10', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, '43353453535353', 'gdfgdf', 'dfgdgdg'),
(1000000, '444 554324', '444', '554324', 'f@gmail.com', '342432', '1', '2016-07-07', NULL, NULL, NULL, 1, 1, 127, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 66, '432432', '432423', '432423'),
(1000003, 'admin1 ***', 'admin1', '***', '', '6554654', '0', '2016-07-05', NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '453453453534', 'nam định', 'Hà Nội'),
(1000005, 'Nguyễn Việt  Dũng', 'Nguyễn Việt ', 'Dũng', '2.v@tryhrt.fgdrfg', '45564564546', '1', '2016-07-05', NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 73, '4333333333335', 'ttttttteggggggggggggggdđ', 'tttttttttttttttttttttt'),
(1000006, 'Nguyễn/Trí hải', 'Nguyễn/Trí', 'hải', 'Haint@gmail.com', '-34242342', '1', '2016-07-03', '1469676577.DangXuat.png', NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 78, '-432424234', 'fhfhf', 'fhfhfghfghfghgf'),
(1000013, 'Hoàng Văn  Linh', 'Hoàng Văn ', 'Linh', 'Linhhn1@gmail.com', '45345353453', '1', '2016-07-02', NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 79, '53535434535', 'hhfhfdhf', 'Hà Nội'),
(1000014, 'test1 ***', 'test1', '***', 'Test1@gmail.com', '555555555333333', '0', '2016-07-12', NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 76, '2543525235235222222', 'ssssss', 'errrrrrrrrrr'),
(1000016, 'Phan Mạnh  Quỳnh', 'Phan Mạnh ', 'Quỳnh', 'Quynhpm@gmail.com', '233333444444444', '1', '2016-07-12', NULL, NULL, NULL, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, '42222222222222222222', 'Thái Bình', 'Hà nội'),
(1000017, 'Nguyễn Xuân Phong', 'Nguyễn Xuân', 'Phong', 'XuanPhong10@gmail.com', '35453435353', '0', '2016-02-01', '1470039322.Hydrangeas.jpg', NULL, NULL, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 62, '333534535', 'Quảng Ninh', 'Hà Nội'),
(1000018, 'Mai Thanh  Sơn', 'Mai Thanh ', 'Sơn', '', '345434534', '1', '2016-08-01', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '235424234', '13rdsádf', 'sdfsdf');

-- --------------------------------------------------------

--
-- Table structure for table `personnel_attendance`
--

CREATE TABLE `personnel_attendance` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `real_time_start` datetime DEFAULT NULL,
  `real_time_end` datetime DEFAULT NULL,
  `personnels_ID` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `attendance_type_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `check_work` tinyint(2) DEFAULT NULL,
  `day_worked` datetime DEFAULT NULL,
  `day_late_work` datetime DEFAULT NULL,
  `day_off_work` datetime DEFAULT NULL,
  `day_work_leave` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnel_attendance`
--

INSERT INTO `personnel_attendance` (`id`, `personnel_id`, `time_start`, `time_end`, `real_time_start`, `real_time_end`, `personnels_ID`, `department_id`, `attendance_type_id`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`, `check_work`, `day_worked`, `day_late_work`, `day_off_work`, `day_work_leave`) VALUES
(361, 1000000, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, NULL, NULL, '2016-12-01 00:00:00', '2016-12-01 00:00:00'),
(362, 1000010, NULL, NULL, NULL, NULL, 0, 0, 2, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(363, 1000015, NULL, NULL, NULL, NULL, 0, 0, 2, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(364, 1000018, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(365, 1000000, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(366, 1000010, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(367, 1000015, NULL, NULL, NULL, NULL, 0, 0, 12, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(368, 1000018, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(369, 1000001, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(370, 1000003, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(371, 1000005, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(372, 1000006, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(373, 1000013, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(374, 1000014, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(375, 1000016, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(376, 1000017, NULL, NULL, NULL, NULL, 0, 0, 10, '2016-12-01 00:00:00', NULL, 1, NULL, 1, '2016-12-01 00:00:00', NULL, NULL, NULL),
(377, 1000001, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, NULL, '2016-12-01 00:00:00', NULL, NULL),
(378, 1000003, NULL, NULL, NULL, NULL, 0, 0, 12, '2016-12-01 00:00:00', NULL, 1, NULL, 2, NULL, '2016-12-01 00:00:00', NULL, NULL),
(379, 1000005, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(380, 1000006, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(381, 1000013, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(382, 1000014, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(383, 1000016, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(384, 1000017, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-01 00:00:00', NULL, 1, NULL, 2, '2016-12-01 00:00:00', NULL, NULL, NULL),
(433, 1000000, NULL, NULL, NULL, NULL, 0, 0, 10, '2016-12-26 00:00:00', NULL, 1, NULL, 1, NULL, NULL, '2016-12-26 00:00:00', '2016-12-26 00:00:00'),
(434, 1000001, NULL, NULL, NULL, NULL, 0, 0, 2, '2016-12-26 00:00:00', NULL, 1, NULL, 1, NULL, NULL, '2016-12-26 00:00:00', '2016-12-26 00:00:00'),
(435, 1000003, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(436, 1000005, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(437, 1000006, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(438, 1000010, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(439, 1000013, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(440, 1000014, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(441, 1000015, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(442, 1000016, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(443, 1000017, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(444, 1000018, NULL, NULL, NULL, NULL, 0, 0, 1, '2016-12-26 00:00:00', NULL, 1, NULL, 1, '2016-12-26 00:00:00', NULL, NULL, NULL),
(445, 1000000, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(446, 1000001, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(447, 1000003, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(448, 1000005, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(449, 1000006, NULL, NULL, NULL, NULL, 0, 0, 12, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(450, 1000010, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(451, 1000013, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(452, 1000014, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(453, 1000015, NULL, NULL, NULL, NULL, 0, 0, 11, '2016-12-26 00:00:00', NULL, 1, NULL, 2, '2016-12-26 00:00:00', NULL, NULL, NULL),
(454, 1000016, NULL, NULL, NULL, NULL, 0, 0, 12, '2016-12-26 00:00:00', NULL, 1, NULL, 2, NULL, '2016-12-26 00:00:00', NULL, NULL),
(455, 1000017, NULL, NULL, NULL, NULL, 0, 0, 12, '2016-12-26 00:00:00', NULL, 1, NULL, 2, NULL, '2016-12-26 00:00:00', NULL, NULL),
(456, 1000018, NULL, NULL, NULL, NULL, 0, 0, 12, '2016-12-26 00:00:00', NULL, 1, NULL, 2, NULL, '2016-12-26 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personnel_bonus`
--

CREATE TABLE `personnel_bonus` (
  `id` int(11) NOT NULL,
  `money` float DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `personnels_ID` int(11) NOT NULL,
  `bonus_type_id` int(11) NOT NULL,
  `status` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_evaluation`
--

CREATE TABLE `personnel_evaluation` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `total_point` float DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1: Tự đánh giá, 2: Quản lý đánh giá',
  `status` enum('0','1','2') DEFAULT NULL COMMENT '0: chưa đánh giá\n1: đang đánh giá\n2: đã đánh giá xong\n',
  `comment` text,
  `date` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_evaluation_details`
--

CREATE TABLE `personnel_evaluation_details` (
  `id` int(11) NOT NULL,
  `type_e_role` tinyint(4) DEFAULT NULL COMMENT '1: Tự đánh giá, 2: Quản lý đánh giá',
  `type_e_time` tinyint(4) NOT NULL COMMENT '1: Đánh giá tháng , 2: Đánh giá năm',
  `criteria_id` int(11) DEFAULT NULL,
  `point` float DEFAULT NULL,
  `personnel_evaluation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnel_evaluation_details`
--

INSERT INTO `personnel_evaluation_details` (`id`, `type_e_role`, `type_e_time`, `criteria_id`, `point`, `personnel_evaluation_id`) VALUES
(0, 1, 1, 5, 3, 1),
(0, 1, 1, 4, 5, 1),
(0, 1, 1, 5, 3, 1),
(0, 1, 1, 4, 5, 1),
(0, 1, 1, 5, 3, 1),
(0, 1, 1, 4, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `personnel_groups`
--

CREATE TABLE `personnel_groups` (
  `int` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `personnel_ids` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_groups_has_personnel_insurance`
--

CREATE TABLE `personnel_groups_has_personnel_insurance` (
  `personnel_groups_int` int(11) NOT NULL,
  `personnel_insurance_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_groups_has_personnel_tax`
--

CREATE TABLE `personnel_groups_has_personnel_tax` (
  `personnel_groups_int` int(11) NOT NULL,
  `personnel_tax_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_has_personnel_groups`
--

CREATE TABLE `personnel_has_personnel_groups` (
  `personnel_ID` int(11) NOT NULL,
  `personnel_groups_int` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_insurance`
--

CREATE TABLE `personnel_insurance` (
  `id` int(11) NOT NULL,
  `formula` text,
  `group_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_job_ratio`
--

CREATE TABLE `personnel_job_ratio` (
  `id` int(11) NOT NULL COMMENT 'hệ số chức danh',
  `ratio` float DEFAULT NULL,
  `apply_from` date DEFAULT NULL,
  `apply_to` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `update_by_id` int(11) DEFAULT NULL,
  `personnel_ID` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnel_job_ratio`
--

INSERT INTO `personnel_job_ratio` (`id`, `ratio`, `apply_from`, `apply_to`, `created_at`, `update_at`, `created_by_id`, `update_by_id`, `personnel_ID`, `status`) VALUES
(1, 12, '2016-07-01', '2016-07-08', NULL, NULL, NULL, NULL, 13, 1),
(2, 13, '2016-07-02', '2016-07-09', NULL, NULL, NULL, NULL, 13, 1),
(5, 20, '2016-07-02', '2016-07-08', NULL, NULL, NULL, NULL, 10, 0),
(6, 21, '2016-07-16', '2016-07-09', NULL, NULL, NULL, NULL, 10, 1),
(7, 12, '2016-07-01', '2016-07-08', NULL, NULL, NULL, NULL, 15, 0),
(9, 30, '2016-07-15', '2016-07-29', NULL, NULL, NULL, NULL, 15, 1),
(10, 23, '2016-07-01', '2016-07-15', NULL, NULL, NULL, NULL, 16, 1),
(11, 34, '2016-07-12', '2016-07-05', NULL, NULL, NULL, NULL, 16, 1),
(12, 12.9, '2016-07-08', '2016-07-29', NULL, NULL, NULL, NULL, 13, 1),
(13, 12.9, '2016-07-01', '2016-07-08', NULL, NULL, NULL, NULL, 17, 1),
(15, -13, '2016-07-12', '2016-07-08', NULL, NULL, NULL, NULL, 1000013, 1),
(16, 323, '2016-08-10', '2016-08-10', NULL, NULL, NULL, NULL, 1000000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `personnel_parameters`
--

CREATE TABLE `personnel_parameters` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `personnel_parameters`
--

INSERT INTO `personnel_parameters` (`id`, `user_id`, `parameter_id`, `value`) VALUES
(13, 2, 7, 2000),
(14, 1, 6, 300),
(15, 2, 6, 400),
(16, 3, 6, 500),
(17, 1, 5, 700),
(18, 2, 5, 100),
(19, 3, 5, 200);

-- --------------------------------------------------------

--
-- Table structure for table `personnel_salary`
--

CREATE TABLE `personnel_salary` (
  `id` int(11) NOT NULL,
  `total_money` float DEFAULT NULL,
  `month` tinyint(4) DEFAULT NULL,
  `year` smallint(6) DEFAULT NULL,
  `personnels_ID` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_tax`
--

CREATE TABLE `personnel_tax` (
  `id` int(11) NOT NULL,
  `formula` text,
  `group_id` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personnel_working_histories`
--

CREATE TABLE `personnel_working_histories` (
  `id` int(11) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `personnels_ID` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnel_working_histories`
--

INSERT INTO `personnel_working_histories` (`id`, `date_start`, `date_end`, `job_id`, `department_id`, `personnels_ID`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`, `status`) VALUES
(1, '2016-07-01', '2016-07-05', 1, 1, 10, NULL, NULL, NULL, NULL, 1),
(2, '2016-06-13', '2016-07-06', 1, 1, 10, NULL, NULL, NULL, NULL, 1),
(3, '2016-07-01', '2016-07-07', 2, 1, 13, NULL, NULL, NULL, NULL, 1),
(4, '2016-05-01', '2016-07-07', 1, 3, 13, NULL, NULL, NULL, NULL, 1),
(8, '2016-07-08', '2016-07-09', 1, 1, 15, NULL, NULL, NULL, NULL, 1),
(9, '2016-07-01', '2016-07-01', 1, 1, 16, NULL, NULL, NULL, NULL, 1),
(11, '2016-07-07', '2016-07-20', 1, 1, 1000001, NULL, NULL, NULL, NULL, 1),
(12, '2016-07-28', '2016-07-21', 2, 3, 1000001, NULL, NULL, NULL, NULL, 1),
(14, '2016-07-05', '2016-07-10', 1, 2, 1000013, NULL, NULL, NULL, NULL, 1),
(15, '2016-07-05', '2016-07-11', 1, 2, 1000013, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `privilegs`
--

CREATE TABLE `privilegs` (
  `id` int(10) UNSIGNED NOT NULL,
  `privilege_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `router` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `privilegs`
--

INSERT INTO `privilegs` (`id`, `privilege_name`, `router`, `parent_id`) VALUES
(1, 'Quản lý phân quyền', 'roles', 0),
(2, 'Quản lý hồ sơ', 'hoso', 0),
(3, 'Quản lý chấm công', 'chamcong', 0),
(4, 'Quản lý lương thưởng', 'luongthuong', 0),
(5, 'Quản lý đánh giá nhân viên', 'danhgia', 0),
(6, 'Đề xuất', 'dexuat', 0),
(7, 'Quản lý người dùng', 'user', 0),
(8, 'Quản trị', 'user', 0),
(9, 'Quản lý quá trình công tác', 'user', 0),
(10, 'Danh sách roles', 'roles-list', 1),
(11, 'Thêm Roles', 'roles-add', 1),
(12, 'Chỉnh sửa Roles', 'roles-edit', 1),
(13, 'Xóa Roles', 'roles-del', 1),
(14, 'Danh sách hồ sơ', 'hoso-list', 2),
(15, 'Thêm hồ sơ', 'hoso-add', 2),
(16, 'Sửa hồ sơ', 'hoso-edit', 2),
(17, 'Xóa hồ sơ', 'hoso-del', 2),
(18, 'Gán tài khoản đăng nhập', 'hoso-assign', 2),
(19, 'Danh sách chấm công', 'chamcong-list', 3),
(20, 'Thêm chấm công', 'chamcong-add', 3),
(21, 'Sửa chấm công', 'chamcong-edit', 3),
(22, 'Xóa chấm công', 'chamcong-del', 3),
(23, 'Chấm công tổng hợp', 'chamcong-tonghop', 3),
(24, 'Chấm công đi làm', 'chamcong-dilam', 3),
(25, 'Chấm công đi muộn', 'chamcong-dimuon', 3),
(26, 'Ngày phép', 'chamcong-ngayphep', 3),
(27, 'Danh sách lương thưởng', 'luongthuong-list', 4),
(28, 'Thêm lương thưởng', 'luongthuong-add', 4),
(29, 'Sửa lương thưởng', 'luongthuong-edit', 4),
(30, 'Xóa lương thưởng', 'luongthuong-del', 4),
(31, 'Danh sách đánh giá nhân viên', 'danhgia-list', 5),
(32, 'Thêm đánh giá nhân viên', 'danhgia-add', 5),
(33, 'Sửa đánh giá nhân viên', 'danhgia-edit', 5),
(34, 'Xóa đánh giá nhân viên', 'danhgia-del', 5),
(35, 'Danh sách đề xuất', 'dexuat-list', 6),
(36, 'Thêm đề xuất', 'dexuat-add', 6),
(37, 'Sửa đề xuất', 'dexuat-edit', 6),
(38, 'Xóa đề xuất', 'dexuat-del', 6),
(39, 'Danh sách người dùng', 'user-list', 7),
(40, 'Thêm người dùng', 'user-add', 7),
(41, 'Sửa người dùng', 'user-edit', 7),
(42, 'Xóa người dùng', 'user-del', 7),
(43, 'Danh sách chức năng', 'quantri-list', 8),
(44, 'Danh sách quá trình công tác', 'quatrinh-list', 9),
(45, 'Chi tiết quá trình công tác', 'quatrinh-detail', 9),
(46, 'Thêm quá trình công tác', 'quatrinh-add', 9),
(47, 'Sửa quá trình công tác', 'quatrinh-edit', 9),
(48, 'Xóa quá trình công tác', 'quatrinh-del', 9),
(49, 'Thêm hệ số chức danh', 'quatrinh-addratio', 9),
(50, 'Sửa hệ số chức danh', 'quatrinh-editratio', 9),
(51, 'Xóa hệ số chức danh', 'quatrinh-delratio', 9),
(52, 'Hướng dẫn các tiêu chí đánh giá', 'danhgia-viethuongdan', 5),
(53, 'Xem hướng dẫn đánh giá', 'danhgia-xemhuongdan', 5),
(54, 'Đánh giá KPI tháng', 'danhgia-thang', 5),
(55, 'Đánh giá KPI năm', 'danhgia-nam', 5),
(56, 'Đánh giá cho từng nhân viên thuộc quản lý ', 'danhgia-nhanvien', 5),
(57, 'Thêm tiêu chí đánh giá', 'danhgia-themtieuchi', 5),
(58, 'Setting các khoảng thời gian đánh giá tiêu chí', 'danhgia-caidat', 5),
(59, 'Danh sách tiêu chí đánh giá', 'danhgia-danhsachtieuchi', 5),
(60, 'Sửa tiêu chí đánh giá', 'danhgia-suatieuchi', 5),
(61, 'Xóa tiêu chí đánh giá', 'danhgia-xoatieuchi', 5),
(62, 'Danh sách bộ tiêu chí', 'danhgia-danhsachbotieuchi', 5);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `roles_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `privileges_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `roles_name`, `privileges_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62', NULL, '2016-07-18 10:19:15'),
(2, 'Nomal', '10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61', NULL, '2016-08-03 08:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `role_group`
--

CREATE TABLE `role_group` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `role_links` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_group`
--

INSERT INTO `role_group` (`id`, `title`, `role_links`, `created_at`, `updated_at`, `created_by_id`, `updated_by_id`, `status`) VALUES
(1, 'admin', '(''11'',''10'',''16'',''13'',''20'',''21'',''17'',''15'',''14'',''18'',''19'',''53'')', NULL, NULL, NULL, NULL, NULL),
(4, 'cccccee', '(''11'',''10'',''16'',''13'',''22'',''20'',''21'',''23'',''17'',''15'',''14'',''18'',''24'',''19'')', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `avatar` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `role_id`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'admin                                    ', 'admin@gmail.com', '$2y$10$vCFcA2iBsFP0bMxcqmWBterIU2yg9OmEd1cA.LOPZefF0T6qgV6ju', 'OFT9KDPX13ilSMYc8QEIGQ7Jd9j7GJZE760I7KEGUmKb8e0JDTk8T6gi3BeT', 1, '1470386755.4.jpg', NULL, '2016-12-28 03:21:29'),
(2, 'admin1', 'admin1@gmail.com', '$2y$10$1w5H23rWL.bgCMyiZ.zVh.vwCfiNIOj8ahwRlPzW4b3xfc9JM/8Qm', 'BHwbcyZnOAYczzSJiSfUZwNJ4QUDyECyu5Df0BmlTCEWORp0fnCv13KF6uaD', 2, '', NULL, '2016-12-28 03:21:45'),
(3, 'admin2', 'admin2@gmail.com', '$2y$10$wX4dkKxchd0pk9dNdUuyB.0gHxcEZWOQ9K62/tx8I8cNOxzcVOmv6', NULL, 2, '', NULL, NULL),
(4, 'admin3', 'admin3@gmail.com', '$2y$10$CZZFo6.KKHaMrPmERK/BG.YTQywhrrFhHD/ZA52mIw.jVdWCS0Pni', 'HvRVdnSSuekZf2BQV8JXkzZQPRuvBjGgBlfz9W9aLCNmN4fh5SHqMTJBWBa2', 2, '', NULL, '2016-07-07 09:36:15'),
(13, 'Phan Mạnh Quỳnh', 'Quynhpm@gmail.com', '$2y$10$Dx5fpaHIZqJXzNbgHcLJaurVbNEC6yBebsTX75DBDCyWrqr2tLY2K', 'GagjvQUkxHsHy0GksOAwSsqlYuGAq6MTH2iwWiXVIMX0XIuQavp6mccyq7th', 2, '', '2016-07-13 03:45:33', '2016-07-28 10:07:17'),
(15, 'Nguyễn Trí Trung ', 'trungnt@mail.com', '$2y$10$bVwYMbmf3Hvy6/4/Tq2nQuNfZDLNt6VvRjQrXQWSHHgHciP6TSp7W', 'kCzeAXlpyIpDY84NshOIo5iWltGy6zL8hOe1behe9rUwqKdhrNaztZKWUfto', 2, '', '2016-07-25 04:22:45', '2016-07-26 08:43:58'),
(17, 'Phạm Kiên Trung', 'trungKP@gmail.com', '$2y$10$hx0ELpQtT9sTQ1sL4asCxejuLftk7dC3kaw4XBcx4gzcpFFC1qbA2', NULL, 2, '', '2016-07-26 07:07:58', '2016-07-26 08:46:49'),
(20, 'Trần Thu Hà', 'hatt@gmail.com', '$2y$10$9AdQoJLYKd.YUNrPDYdul.aoZ1rUtBB4r.XsErJ65xrpyCmxQML9G', NULL, 1, '', '2016-07-26 09:36:42', '2016-07-26 09:36:42'),
(24, 'Test_02', 'test02@gmail.com', '$2y$10$0S8ALv9rdy1Qesk3SZV.4e8kWPvlUDXFiVt2KpXbQT9IzztPvThSW', NULL, 2, '', '2016-07-26 09:41:18', '2016-07-26 09:41:18'),
(25, 'test03', 'test03@gmail.com', '$2y$10$SrTyyUjp9QMuBSMV2dD5T.Uaak2aqxWziiqRDk5DJ7aa6iKT6MJUy', NULL, 2, '', '2016-07-26 09:42:08', '2016-07-26 09:42:08'),
(27, 'Test05', 'Test05@gmail.com', '$2y$10$HZuUpPnfh9IBoWjQkM5exuNHKmfYdsvnSMdwNgBIor8gP.yqBQK.m', NULL, 2, '', '2016-07-26 09:43:18', '2016-07-26 09:43:18'),
(28, 'test06', 'test06@gmail.com', '$2y$10$6PLrDWgWA5XEvdl0Pc5Jz.4PtAAbD4GFV5GPJHSzSCkD/6Hh1YlnG', NULL, 2, '', '2016-07-26 09:44:22', '2016-07-26 09:44:22'),
(31, 'test09', 'test09@gmail.com', '$2y$10$9YT5N2d31IBNkhOkA89X/.LJYnH2nHydaUQEzngY.GsKWvEvvZyYK', NULL, 2, '', '2016-07-26 09:52:02', '2016-07-26 09:52:02'),
(32, 'test10', 'test10@gmail.com', '$2y$10$.a5B8kd0xWOiHa35hrbo7uKNZHUwaoN.5CHKxQIl1jDsotCyckj6G', NULL, 2, '', '2016-07-26 09:52:41', '2016-07-26 09:52:41'),
(34, 'test12', 'test12@gmail.com', '$2y$10$c99e/pD0CKsHlcUGVH9YsuvQuXt/euS8jb2dUmMhxX/qS0canY18W', NULL, 2, '', '2016-07-26 09:55:56', '2016-07-26 09:55:56'),
(35, 'test13', 'test13@gmail.com', '$2y$10$6L0mKFtf.FDu76VT9xwscenk5DqSLsuHK7kH51CVOOcawqZDPWtJS', NULL, 1, '', '2016-07-26 09:56:27', '2016-07-26 09:56:27'),
(36, 'NguyetMinh01', 'NguyetMinh01@gmail.com', '$2y$10$3Yq41A5TUNe6VR79xilFj.qmQc5i68CPYaYbtz.tyZk5fWxF6aTiu', NULL, 2, '', '2016-07-26 10:01:11', '2016-07-26 10:01:11'),
(39, 'NguyetMinh04', 'NguyetMinh04@gmail.com', '$2y$10$5yMAdfTNyLTOeaM3kJfAOuxvBg15YbXAoYv5vWta5u1SpwSvPTDBK', NULL, 2, '', '2016-07-26 10:03:51', '2016-07-26 10:03:51'),
(40, 'NguyetMinh05', 'NguyetMinh05@gmail.com', '$2y$10$TqFLDKm5f0/UNSWvnkstpuMrkx9CdEQ5SS1BRoEeavUjvXmfElV6C', NULL, 2, '', '2016-07-26 10:04:30', '2016-07-26 10:04:30'),
(41, 'NguyetMinh06', 'NguyetMinh06@gmail.com', '$2y$10$HqSgyNbt0Q3DmKxa/NeFOOnxsRyccMKsWvhIi41XLk9hcoAnkjQXS', NULL, 2, '', '2016-07-26 10:05:02', '2016-07-26 10:05:02'),
(42, 'NguyetMinh07', 'NguyetMinh07@gmail.com', '$2y$10$Q/ORaur1/JkVdaTEXuJNzO4jDrNsUQgj6LxCos5C5Sh18u/n9StOG', NULL, 2, '', '2016-07-26 10:11:28', '2016-07-26 10:11:28'),
(44, 'NguyetMinh09', 'NguyetMinh09@gmail.com', '$2y$10$qfX2fEFfeNQjghsqDxyrv.p49/13SU/lgAV9SknHdxAqzdec4wr5q', NULL, 2, '', '2016-07-26 10:13:59', '2016-07-26 10:13:59'),
(48, 'NguyetMinh13', 'NguyetMinh13@gmail.com', '$2y$10$iKgkFoZwtv5SfzSg2TEK.eHHYFGyOJnIeB.bUKlqU/JuCqRSqjsXG', NULL, 2, '', '2016-07-26 10:21:26', '2016-07-26 10:21:26'),
(49, 'NguyetMinh14', 'NguyetMinh14@gmail.com', '$2y$10$nQh59kCm2DMMdPuLe7jSpOwSYaO1ee9tqRdnySi6SR2NYTIVkh7XO', NULL, 2, '', '2016-07-26 10:22:50', '2016-07-26 10:22:50'),
(50, 'NguyetMinh15', 'NguyetMinh15@gmail.com', '$2y$10$uaabXdB1pZ0FGJkv8A5aX.XEU2VGTUXhCRL2CEndzvrnlV3VErOi6', NULL, 2, '', '2016-07-26 10:23:38', '2016-07-26 10:23:38'),
(51, 'NguyetMinh16', 'NguyetMinh16@gmail.com', '$2y$10$FrMajYJyDs7SX0TCM94W..sk1ynKMYcgT/.OQA00tEvQnpbF6kRCS', NULL, 2, '', '2016-07-26 10:24:25', '2016-07-26 10:24:25'),
(53, 'NguyetMinh18', 'NguyetMinh18@gmail.com', '$2y$10$9bL4phZ/1cf9M7bK7azaJOsyvqlWuhxqn5U/IpWHJ25rez2Nd2uWa', NULL, 2, '', '2016-07-26 10:25:54', '2016-07-26 10:25:54'),
(54, 'XuanPhong', 'XuanPhong@gmail.com', '$2y$10$hgcc23/tn5mDHQuG8p6RXOYTBELxQabk.2m1ER8kWs7zlOkjWztry', NULL, 2, '', '2016-07-26 10:35:05', '2016-07-26 10:35:05'),
(56, 'XuanPhong02', 'XuanPhong02@gmail.com', '$2y$10$lNHcPB12.Imcb4GaNwVjl.TadFc.mLwMePTVMx.2AVHddlRL8VRBW', NULL, 2, '', '2016-07-26 10:36:55', '2016-07-26 10:36:55'),
(61, 'XuanPhong09', 'XuanPhong09@gmail.com', '$2y$10$YPhe/vif4UXkzdzg9.2eXesqBsjYO6wL.nnJ.EQXyxBB18X.DFPSe', NULL, 2, '', '2016-07-26 10:40:26', '2016-07-26 10:40:26'),
(62, 'XuanPhong10', 'XuanPhong10@gmail.com', '$2y$10$Wbaz5rdUVyjQOXHIkckDreWUsB9DI1pev6X/f002i7u8M5VttVdnK', 'UVCpLIzN35p2ldmtkB99kad1JNeFBAzHfRpxDqbZV1nYlqq1OZWLIx834cmD', 2, '1470040749.Hydrangeas.jpg', '2016-07-26 10:41:22', '2016-08-01 09:26:22'),
(72, 'Lại Thị Tuyến', 'admin16@gmail.com', '$2y$10$rewbqfIMCFWjHKNOBRXD6e5OqhdbKsBbhYeEoQRvL0hU2nnaMUCeG', NULL, 1, '', '2016-07-27 07:46:13', '2016-07-27 07:46:13'),
(73, 'Nguyễn Việt Dũng', '2.v@tryhrt.fgdrfg', '$2y$10$fIxNWTLwXbXiIylCWrJetup6Teape7WBgmaxJJHc4D4i7V6BASTuq', NULL, 2, '', '2016-07-28 04:26:46', '2016-07-28 04:26:46'),
(74, 'Phan Kiên Trung', 'trungpk@gmail.com', '$2y$10$RKTQ//WqvKuEBCGfXXLvSOE3FHym6mi1NvAVVpQIvFxvmyKWC5Spq', '74vGH8vKRUjgcWdDgKuwE7sBUSibOeF6T6GHdqJjX4rkJyfpQXRMhnRPTIGO', 1, '', '2016-07-28 06:53:00', '2016-08-01 08:47:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_criteria`
--
ALTER TABLE `evaluation_criteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `criteria_content` (`criteria_content`);

--
-- Indexes for table `evaluation_default_criteria`
--
ALTER TABLE `evaluation_default_criteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_stage`
--
ALTER TABLE `evaluation_stage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnel_attendance`
--
ALTER TABLE `personnel_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privilegs`
--
ALTER TABLE `privilegs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_group`
--
ALTER TABLE `role_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `evaluation_criteria`
--
ALTER TABLE `evaluation_criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `evaluation_default_criteria`
--
ALTER TABLE `evaluation_default_criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evaluation_stage`
--
ALTER TABLE `evaluation_stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000019;
--
-- AUTO_INCREMENT for table `personnel_attendance`
--
ALTER TABLE `personnel_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=457;
--
-- AUTO_INCREMENT for table `privilegs`
--
ALTER TABLE `privilegs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `role_group`
--
ALTER TABLE `role_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
