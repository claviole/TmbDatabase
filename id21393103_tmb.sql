-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 21, 2023 at 12:16 PM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id21393103_tmb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accident_files`
--

CREATE TABLE `accident_files` (
  `accident_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accident_report`
--

CREATE TABLE `accident_report` (
  `accident_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `non_employee_name` varchar(255) DEFAULT NULL,
  `foreman_id` int(11) DEFAULT NULL,
  `accident_type` varchar(255) DEFAULT NULL,
  `date_added` varchar(255) DEFAULT NULL,
  `accident_date` varchar(255) DEFAULT NULL,
  `accident_time` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `time_sent_to_clinic` varchar(255) DEFAULT NULL,
  `date_sent_to_clinic` varchar(255) DEFAULT NULL,
  `accident_location` varchar(255) DEFAULT NULL,
  `time_of_report` varchar(255) DEFAULT NULL,
  `shift_start_time` varchar(255) DEFAULT NULL,
  `accident_description` mediumblob DEFAULT NULL,
  `consecutive_days_worked` int(11) DEFAULT NULL,
  `proper_ppe_used` varchar(255) DEFAULT NULL,
  `proper_ppe_used_explain` mediumblob DEFAULT NULL,
  `procedure_followed` varchar(255) DEFAULT NULL,
  `procedure_followed_explain` mediumblob DEFAULT NULL,
  `potential_severity` varchar(255) DEFAULT NULL,
  `potential_severity_explain` mediumblob DEFAULT NULL,
  `enverionmental_impact` varchar(255) DEFAULT NULL,
  `enverionmental_impact_explain` mediumblob DEFAULT NULL,
  `prevent_reoccurance` mediumblob DEFAULT NULL,
  `immediate_corrective_action` mediumblob DEFAULT NULL,
  `irp_required` varchar(255) DEFAULT NULL,
  `irp_names` varchar(255) DEFAULT NULL,
  `equip_out_of_service` varchar(255) DEFAULT NULL,
  `equip_out_of_service_explain` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accident_report`
--

INSERT INTO `accident_report` (`accident_id`, `employee_id`, `non_employee_name`, `foreman_id`, `accident_type`, `date_added`, `accident_date`, `accident_time`, `shift`, `time_sent_to_clinic`, `date_sent_to_clinic`, `accident_location`, `time_of_report`, `shift_start_time`, `accident_description`, `consecutive_days_worked`, `proper_ppe_used`, `proper_ppe_used_explain`, `procedure_followed`, `procedure_followed_explain`, `potential_severity`, `potential_severity_explain`, `enverionmental_impact`, `enverionmental_impact_explain`, `prevent_reoccurance`, `immediate_corrective_action`, `irp_required`, `irp_names`, `equip_out_of_service`, `equip_out_of_service_explain`) VALUES
(1, 1, NULL, NULL, 'Near Miss', '2023-12-04', '2023-12-05', '07:48', '1', '19:49', '2023-12-04', '3E7M', '07:48', '07:48', 0x74657374696e672074657874207375626d697373696f6e, 2, 'yes', '', 'yes', '', 'Low', '', 'yes', 0x6161, 0x6161, 0x6161, 'yes', 'aa', 'yes', 0x6161),
(2, 1, NULL, NULL, 'Near Miss', '2023-12-04', '2023-12-04', '07:53', '1', '07:53', '2023-12-04', '3E7M', '07:53', '07:53', 0x496d6167652b54657874207375626d69742074657374, 3, 'no', 0x496d6167652b54657874207375626d69742074657374, 'no', 0x496d6167652b54657874207375626d69742074657374, 'Low', 0x496d6167652b54657874207375626d69742074657374, 'yes', 0x496d6167652b54657874207375626d69742074657374, 0x496d6167652b54657874207375626d69742074657374, 0x496d6167652b54657874207375626d69742074657374, 'yes', 'Image+Text submit test', 'yes', 0x496d6167652b54657874207375626d69742074657374),
(3, 1, NULL, NULL, 'Near Miss', '2023-12-04', '', '', '1', '', '', '', '', '', '', 0, 'no', '', 'no', '', 'Low', '', 'no', '', '', '', 'no', '', 'no', ''),
(4, 31, 'trucker', NULL, 'Near Miss', '2023-12-11', '2023-12-11', '11:22', '1', '11:22', '2023-12-11', '3E7M', '11:22', '11:22', 0x74657374, 1, 'yes', '', 'yes', '', 'Low', '', 'no', '', 0x74657374, 0x74657374, 'no', '', 'no', '');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aemail` varchar(255) NOT NULL,
  `apassword` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aemail`, `apassword`) VALUES
('claviolette@targetmetalblanking.com', '042217Dv!');

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `customer_id` int(11) NOT NULL,
  `Customer Name` varchar(255) NOT NULL,
  `Customer Address` varchar(255) DEFAULT NULL,
  `Customer City` varchar(255) DEFAULT NULL,
  `Customer State` varchar(255) DEFAULT NULL,
  `Customer Zip` varchar(255) DEFAULT NULL,
  `Customer Phone` varchar(255) DEFAULT NULL,
  `Customer Email` varchar(255) DEFAULT NULL,
  `Customer Contact` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`customer_id`, `Customer Name`, `Customer Address`, `Customer City`, `Customer State`, `Customer Zip`, `Customer Phone`, `Customer Email`, `Customer Contact`) VALUES
(5, 'Ford Motor Company', '123 Ford ave', 'Chicago', 'IL', '60411', '123-456-7895', 'ford@ford.com', 'Henry Ford'),
(6, 'Rivian', '123 Rivian Way', 'Chicago', 'IL', '60411', '321-654-9874', 'rivian@rivian.com', 'Mr.Rivian'),
(7, 'Thai Summit', '123 Thai Summit Ave', 'chicago', 'IL', '60411', '564-879-2315', 'ThaiSummit@thaisummit.com', 'Mr.Summit');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `employee_fname` varchar(255) NOT NULL,
  `employee_lname` varchar(255) NOT NULL,
  `date_hired` date NOT NULL,
  `first_day_of_work` date NOT NULL,
  `job_title` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_fname`, `employee_lname`, `date_hired`, `first_day_of_work`, `job_title`) VALUES
(1, 'tested', 'Smith', '2023-12-04', '2023-12-04', 20),
(2, 'Adriana', 'Marez', '2022-06-06', '2022-06-06', 11),
(3, 'Albert ', 'Mason', '2023-10-09', '2023-10-09', 14),
(4, 'Allen', 'Aldridge', '2022-11-21', '2022-11-21', 25),
(5, 'Andrew ', 'Keister', '2023-07-17', '2023-07-17', 31),
(6, 'Angel ', 'Perez', '2022-08-21', '0002-08-21', 11),
(7, 'Anna ', 'Contreras', '2022-04-25', '2022-04-25', 37),
(8, 'April', 'Williams', '2015-12-07', '2015-12-07', 13),
(9, 'Armando', 'Trevino', '2016-12-25', '2016-12-25', 4),
(10, 'Brendan', 'Demantes', '2021-10-11', '2021-10-11', 38),
(11, 'Brian', 'Conway', '2022-08-29', '2022-08-29', 13),
(12, 'Charles', 'Roseborough', '2016-05-13', '2016-05-13', 17),
(13, 'Charles ', 'Hart', '2023-06-28', '2023-06-28', 32),
(14, 'Christian', 'Laviolette', '2023-07-10', '2023-07-10', 12),
(15, 'Christopher', 'Sewell', '2022-06-15', '2022-06-15', 4),
(16, 'Christos', 'Koutsogeorgopoulos', '2002-09-30', '2002-09-30', 1),
(17, 'Clarence', 'Lewis', '2011-01-18', '2023-11-08', 11),
(18, 'Clarence', 'Lewis', '2011-01-18', '2011-01-18', 11),
(19, 'Corey', 'Diggs', '2010-10-11', '2010-10-11', 12),
(20, 'Craig', 'Mosley', '2022-01-31', '2022-01-31', 7),
(21, 'Dale', 'Biella', '2010-12-14', '2010-12-14', 14),
(22, 'Daniel', 'Stoffregen', '2012-10-25', '2012-10-25', 10),
(23, 'Dariusz', 'Broniek', '2016-10-20', '2016-10-20', 26),
(24, 'David', 'Meeker', '1997-12-08', '1997-12-08', 12),
(25, 'David', 'Del Toro', '2009-08-24', '2009-08-24', 12),
(26, 'David', 'Parks', '2010-07-30', '2010-07-30', 26),
(27, 'David', 'Haynes', '2015-03-30', '2015-03-30', 5),
(28, 'David', 'Gomez', '2022-07-11', '2022-07-11', 17),
(29, 'DeAngelius', 'Barnes', '2022-08-01', '2022-08-01', 9),
(30, 'Demetrius', 'Williams', '2021-09-21', '2021-09-21', 17),
(31, 'non', 'employee', '2023-12-11', '2023-12-11', 39),
(32, 'Dennis', 'Willis', '2012-09-04', '2012-09-04', 14),
(33, 'Derrick', 'Murry', '2013-05-22', '2013-05-22', 13),
(34, 'Devin', 'Menconi', '2023-01-09', '2023-01-09', 17),
(35, 'Donald', 'Nailon', '2012-06-01', '2012-06-01', 11),
(36, 'Donald', 'Poole', '2014-07-29', '2014-07-29', 11),
(37, 'Donald', 'Rettig', '2022-06-06', '2022-06-06', 19),
(38, 'Dontae', 'Wells', '2017-12-12', '2017-12-12', 7),
(39, 'Ebenezer', 'Hester', '2022-02-16', '2022-02-16', 12),
(40, 'Ebony', 'Green', '2022-08-01', '2022-08-01', 15),
(41, 'Edgar', 'Ramirez JR', '2022-06-19', '2022-06-19', 11),
(42, 'Edward', 'Alvarado', '2023-02-06', '2023-02-06', 11),
(43, 'Edwin', 'Ramirez', '2022-06-19', '2022-06-19', 11),
(44, 'Elizabeth', 'Kocel', '2011-05-02', '2011-05-02', 29),
(45, 'Elizabeth', 'Gonzalez', '2011-10-25', '2011-10-25', 11),
(46, 'Enrique', 'Torres', '2011-10-25', '2011-10-25', 5),
(47, 'Eric', 'Dean', '2002-02-25', '2002-02-25', 26),
(48, 'Eugene', 'Perry', '2009-08-04', '2009-08-04', 8),
(49, 'Francisco', 'Mireles', '2022-08-19', '2022-08-19', 22),
(50, 'Francisco', 'Delgado', '2023-05-08', '2023-05-08', 11);

-- --------------------------------------------------------

--
-- Table structure for table `employee_training`
--

CREATE TABLE `employee_training` (
  `employee_id` int(11) NOT NULL,
  `training_path_id` int(11) NOT NULL,
  `completion_status` varchar(255) DEFAULT 'incomplete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` varchar(255) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `invoice_date` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `die_reviewer` varchar(255) DEFAULT NULL,
  `invoice_author` varchar(255) DEFAULT NULL,
  `approval_status` varchar(255) DEFAULT 'Awaiting Approval',
  `approved_by` varchar(255) DEFAULT NULL,
  `award_status` varchar(255) NOT NULL DEFAULT 'pending',
  `award_total` decimal(12,3) DEFAULT NULL,
  `Customer Name` varchar(255) DEFAULT NULL,
  `contingencies` mediumblob NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `version`, `invoice_date`, `customer_id`, `die_reviewer`, `invoice_author`, `approval_status`, `approved_by`, `award_status`, `award_total`, `Customer Name`, `contingencies`) VALUES
('TWB_CL_1', 1, '2023-12-15T19:03:33.599Z', 1, '', 'CL', 'Awaiting Approval', NULL, 'pending', 511200.000, 'Christian Joshua Laviolette', ''),
('TWB_CL_2', 1, '2023-12-15T20:41:55.549Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 401600.000, 'Thai Summit', ''),
('TWB_CL_3', 1, '2023-12-15T20:53:32.169Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 668000.000, 'Thai Summit', ''),
('TWB_CL_4', 1, '2023-12-15T20:57:53.940Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 402000.000, 'Thai Summit', ''),
('TWB_CL_5', 1, '2023-12-15T21:03:19.965Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 393400.000, 'Thai Summit', ''),
('TWB_CL_6', 1, '2023-12-15T21:05:55.088Z', 1, '', 'CL', 'Awaiting Approval', NULL, 'pending', 393800.000, 'Christian Joshua Laviolette', ''),
('TWB_CL_7', 1, '2023-12-15T21:10:15.202Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 142600.000, 'Thai Summit', ''),
('TWB_CL_8', 1, '2023-12-15T21:15:53.121Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 393400.000, 'Thai Summit', ''),
('TWB_CL_9', 1, '2023-12-15T21:24:41.582Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 401600.000, 'Thai Summit', ''),
('TWB_CL_10', 1, '2023-12-15T21:26:01.155Z', 7, '', 'CL', 'Awaiting Approval', NULL, 'pending', 393800.000, 'Thai Summit', ''),
('TWB_CL_11', 1, '2023-12-15T21:31:39.013Z', 7, '', 'CL', 'Approved', 'Christian Laviolette', 'Awarded', 379000.000, 'Thai Summit', ''),
('TWB_CL_12', 1, '2023-12-15T21:57:32.840Z', 7, '', 'CL', 'Denied', 'Christian Laviolette', 'pending', 668000.000, 'Thai Summit', ''),
('TWB_CL_12', 1, '2023-12-15T21:59:20.799Z', 7, '', 'CL', 'Denied', 'Christian Laviolette', 'pending', 668000.000, '', ''),
('TWB_CL_14', 1, '2023-12-15T22:01:11.113Z', 6, '', 'CL', 'Awaiting Approval', NULL, 'pending', 419025600.000, 'Rivian', ''),
('TWB_CL_15', 1, '2023-12-15T22:05:05.411Z', 6, 'Johnny', 'CL', 'Denied', 'Christian Laviolette', 'pending', 401600.000, 'Rivian', ''),
('TWB_CL_16', 1, '2023-12-19T16:00:36.451Z', 5, '', 'CL', 'Awaiting Approval', NULL, 'pending', 701400.000, 'Ford Motor Company', '');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_files`
--

CREATE TABLE `invoice_files` (
  `invoice_id` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_files`
--

INSERT INTO `invoice_files` (`invoice_id`, `file_name`, `file_path`) VALUES
('TWB_CL_2', 'charizard.lxds', '/storage/ssd5/103/21393103/public_html/charizard.lxds'),
('TWB_CL_4', 'TWB_CL_4.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_4.xlsx'),
('TWB_CL_5', 'TWB_CL_5.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_5.xlsx'),
('TWB_CL_6', 'TWB_CL_6.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_6.xlsx'),
('TWB_CL_7', 'TWB_CL_7.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_7.xlsx'),
('TWB_CL_8', 'TWB_CL_8.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_8.xlsx'),
('TWB_CL_9', 'TWB_CL_9.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_9.xlsx'),
('TWB_CL_10', 'TWB_CL_10.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_10.xlsx'),
('TWB_CL_11', 'TWB_CL_11.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_11.xlsx'),
('TWB_CL_12', 'TWB_CL_12.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_12.xlsx'),
('TWB_BJ_16', 'TWB_BJ_16.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_BJ_16.xlsx'),
('TWB_BJ_16', 'TWB_BJ_16.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_BJ_16.xlsx'),
('TWB_BJ_16', 'TWB_BJ_16.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_BJ_16.xlsx'),
('TWB_CL_16', 'TWB_CL_16.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_16.xlsx'),
('TWB_CL_16', 'TWB_CL_16.xlsx', '/storage/ssd5/103/21393103/public_html/invoice_files/TWB_CL_16.xlsx');

-- --------------------------------------------------------

--
-- Table structure for table `job_titles`
--

CREATE TABLE `job_titles` (
  `job_title_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `job_titles`
--

INSERT INTO `job_titles` (`job_title_id`, `job_title`) VALUES
(1, 'Line 1 Operator'),
(2, 'Line 3 Operator'),
(3, 'Line 4 Operator'),
(4, 'Line 5 Operator'),
(5, 'Line 6 Operator'),
(6, 'Line 6 QC Operator'),
(7, 'Line 7 Operator'),
(8, 'Line 7 QC Operator'),
(9, 'Laser Line Operator'),
(10, 'Master Operator'),
(11, 'Material Handler'),
(12, 'Warehouse'),
(13, 'Quality Auditor'),
(14, 'Quality Supervisor'),
(15, 'Clerk'),
(16, 'Die Repair Tech'),
(17, 'Die Setter'),
(18, 'Master Maintenance'),
(19, 'Supervisor'),
(20, 'HR-Manager'),
(21, 'HR-Generalist'),
(22, 'Operations-Manager'),
(23, 'Operations-Blanking Supervisor'),
(24, 'Operations-Slitting Supervisor'),
(25, 'Maintenance-Manager'),
(26, 'Maintenance-Coordinator'),
(27, 'General Manager'),
(28, 'Accounting-Controller'),
(29, 'Accounting-Manager'),
(30, 'Accounting-AR Analyst'),
(31, 'Quality-Manager'),
(32, 'Quality Engineer'),
(33, 'Quality-Supervisor/Liason'),
(34, 'Quality-Technician'),
(35, 'Logistics-Manager'),
(36, 'Sales-Manager'),
(37, 'Sales-Representative'),
(38, 'Safety-Coordinator'),
(39, 'non-employee');

-- --------------------------------------------------------

--
-- Table structure for table `Lines`
--

CREATE TABLE `Lines` (
  `line_id` int(11) NOT NULL,
  `Line_Location` varchar(255) NOT NULL,
  `Line_Name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Lines`
--

INSERT INTO `Lines` (`line_id`, `Line_Location`, `Line_Name`) VALUES
(1, 'Target Steel-Flat Rock (HQ)', 'Line 1'),
(2, 'Target Steel Riverview MI', 'Line 1'),
(3, 'Target Steel Riverview MI', 'Line 2'),
(4, 'Target Steel Riverview MI', 'Line 3'),
(5, 'Target Steel Riverview MI', 'Line 4'),
(6, 'Target Steel Riverview MI', 'Line 5'),
(7, 'Torch Steel Processing', 'Line 72'),
(8, 'Target Metal Blanking - New Boston', 'Ace'),
(9, 'Target Metal Blanking - New Boston', 'Deuce'),
(10, 'Target Metal Blanking - New Boston', 'Sinq'),
(11, 'Target Metal Blanking - North Vernon', 'Line 1'),
(12, 'Target Metal Blanking - North Vernon', 'Line 2'),
(13, 'Target Metal Blanking - North Vernon', 'Line 3'),
(14, 'Target Metal Blanking - North Vernon', 'Line 4'),
(15, 'Target Metal Blanking - Sauk Village', 'Line 1'),
(16, 'Target Metal Blanking - Sauk Village', 'Line 3'),
(17, 'Target Metal Blanking - Sauk Village', 'Line 4'),
(18, 'Target Metal Blanking - Sauk Village', 'Line 5'),
(19, 'Target Metal Blanking - Sauk Village', 'Line 6'),
(20, 'Target Metal Blanking - Sauk Village', 'Line 7'),
(21, 'Target Metal Blanking - Sauk Village', 'Laser');

-- --------------------------------------------------------

--
-- Table structure for table `Line_Item`
--

CREATE TABLE `Line_Item` (
  `invoice_id` varchar(255) DEFAULT NULL,
  `Part#` varchar(255) DEFAULT NULL,
  `Part Name` varchar(255) DEFAULT NULL,
  `model_year` int(11) DEFAULT NULL,
  `Material Type` varchar(255) DEFAULT NULL,
  `blank_die?` varchar(255) DEFAULT NULL,
  `# Outputs` int(11) DEFAULT NULL,
  `Volume` float DEFAULT NULL,
  `Width(mm)` float DEFAULT NULL,
  `width(in)` float DEFAULT NULL,
  `Pitch(mm)` float DEFAULT NULL,
  `nom?` varchar(255) DEFAULT NULL,
  `trap` varchar(255) DEFAULT NULL,
  `Pitch(in)` float DEFAULT NULL,
  `Gauge(mm)` float DEFAULT NULL,
  `Gauge(in)` float DEFAULT NULL,
  `Density` float DEFAULT NULL,
  `Blank Weight(kg)` float DEFAULT NULL,
  `Blank Weight(lb)` float DEFAULT NULL,
  `Scrap Consumption` float DEFAULT NULL,
  `Pcs Weight(kg)` float DEFAULT NULL,
  `Pcs Weight(lb)` float DEFAULT NULL,
  `Scrap Weight(kg)` float DEFAULT NULL,
  `Scrap Weight(lb)` float DEFAULT NULL,
  `Pallet Type` varchar(255) DEFAULT NULL,
  `Pallet Size` varchar(255) DEFAULT NULL,
  `Pallet Weight(lb)` float DEFAULT NULL,
  `Pcs per Lift` int(11) DEFAULT NULL,
  `Stacks per Skid` int(11) DEFAULT NULL,
  `Pcs per Skid` int(11) DEFAULT NULL,
  `Lift Weight+Skid Weight(lb)` float DEFAULT NULL,
  `Stack Height` float DEFAULT NULL,
  `Skids per Truck` int(11) DEFAULT NULL,
  `Pieces per Truck` int(11) DEFAULT NULL,
  `Truck Weight(lb)` float DEFAULT NULL,
  `Annual Truckloads` float DEFAULT NULL,
  `UseSkidPcs` int(11) DEFAULT NULL,
  `Skid cost per piece` float DEFAULT NULL,
  `Line Produced on` varchar(255) DEFAULT NULL,
  `PPH` int(11) DEFAULT NULL,
  `Uptime` float DEFAULT NULL,
  `Blanking per piece cost` float DEFAULT NULL,
  `Packaging Per Piece Cost` float DEFAULT NULL,
  `freight per piece cost` float DEFAULT NULL,
  `Total Cost per Piece` float DEFAULT NULL,
  `wash_and_lube` float DEFAULT NULL,
  `material_cost` float DEFAULT NULL,
  `material_markup_percent` float DEFAULT NULL,
  `material_cost_markup` float DEFAULT NULL,
  `palletCost` float DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `Mill` varchar(255) DEFAULT NULL,
  `Platform` varchar(255) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Surface` varchar(255) DEFAULT NULL,
  `pallet_uses` int(11) DEFAULT NULL,
  `parts_per_blank` int(11) DEFAULT NULL,
  `blanks_per_ton` int(11) NOT NULL,
  `blanks_per_mt` int(11) DEFAULT NULL,
  `total_steel_cost(kg)` float DEFAULT NULL,
  `total_steel_cost(lb)` float DEFAULT NULL,
  `cost_per_kg` float DEFAULT NULL,
  `cost_per_lb` float DEFAULT NULL,
  `ship_to_location` varchar(255) DEFAULT NULL,
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Line_Item`
--

INSERT INTO `Line_Item` (`invoice_id`, `Part#`, `Part Name`, `model_year`, `Material Type`, `blank_die?`, `# Outputs`, `Volume`, `Width(mm)`, `width(in)`, `Pitch(mm)`, `nom?`, `trap`, `Pitch(in)`, `Gauge(mm)`, `Gauge(in)`, `Density`, `Blank Weight(kg)`, `Blank Weight(lb)`, `Scrap Consumption`, `Pcs Weight(kg)`, `Pcs Weight(lb)`, `Scrap Weight(kg)`, `Scrap Weight(lb)`, `Pallet Type`, `Pallet Size`, `Pallet Weight(lb)`, `Pcs per Lift`, `Stacks per Skid`, `Pcs per Skid`, `Lift Weight+Skid Weight(lb)`, `Stack Height`, `Skids per Truck`, `Pieces per Truck`, `Truck Weight(lb)`, `Annual Truckloads`, `UseSkidPcs`, `Skid cost per piece`, `Line Produced on`, `PPH`, `Uptime`, `Blanking per piece cost`, `Packaging Per Piece Cost`, `freight per piece cost`, `Total Cost per Piece`, `wash_and_lube`, `material_cost`, `material_markup_percent`, `material_cost_markup`, `palletCost`, `supplier_name`, `customer_id`, `Mill`, `Platform`, `Type`, `Surface`, `pallet_uses`, `parts_per_blank`, `blanks_per_ton`, `blanks_per_mt`, `total_steel_cost(kg)`, `total_steel_cost(lb)`, `cost_per_kg`, `cost_per_lb`) VALUES
('CL_Testinyiyiy', 'Testinyiyiy', 'Testinyiyiy', 2323, 'Testinyiyiy', 'YES', 1, 60000, 1738, 68.4252, 800, 'NOM', 'N/A', 31.4961, 3.5, 0.137795, 0.098, 13.201, 29.103, 5, 12.5409, 27.6479, 0.660046, 1.45515, 'Testinyiyiy', '0', 34, 255, 1, 255, 7050.2, 35.1378, 5, 1275, 35251, 47.0588, 1275, 0.027451, '11', 0, 1, 15000, 0.235, 2.5, 15148.2, 0, 145.515, 0, 145.515, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('CL_TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 2024, 'TestingTotalAward', 'YES', 1, 150000, 1650, 64.9606, 950, 'NOM', '2', 37.4016, 3.5, 0.137795, 0.098, 14.882, 32.81, 5, 14.1383, 31.1695, 0.744119, 1.6405, 'TestingTotalAward', '0', 250, 250, 1, 250, 7792.38, 34.4488, 5, 1250, 38961.9, 120, 1250, 0.2, '11', 0, 1, 5, 1.1, 2, 8.1, 0, 164.05, 0.05, 8.203, 250, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('CL_anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 2024, 'anotherTotalCost', 'YES', 1, 130500, 1250, 49.2126, 750, 'NOM', 'N/A', 29.5276, 3.5, 0.137795, 0.098, 8.901, 19.623, 3, 8.63383, 19.0343, 0.267026, 0.58869, 'anotherTotalCost', 'anotherTotalCost', 50, 255, 1, 255, 4853.75, 35.1378, 5, 1275, 24268.7, 102.353, 1275, 0.196078, '11', 650, 1, 1.385, 1.078, 1.4902, 6.896, 0, 98.115, 0.03, 2.943, 250, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('CL_testingkjkjk', 'testingkjkjk', 'testingkjkjk', 2023, 'testingkjkjk', 'NO', 1, 250000, 1708, 67.2441, 50, 'NOM', 'N/A', 1.9685, 3.5, 0.137795, 0.0938, 0.776, 1.711, 2, 0.760576, 1.67678, 0.0155219, 0.03422, 'testingkjkjk', 'testingkjkjk', 70, 255, 1, 255, 427.579, 35.1378, 5, 1275, 2137.9, 196.078, 1275, 0.0196078, '11', 650, 1, 1.385, 0.196, 1.4902, 3.071, 0, 0, 0.02, 0, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('CL_LetsDoATest', 'LetsDoATest', 'LetsDoATest', 2024, 'LetsDoATest', 'YES', 1, 200000, 1500, 59.0551, 500, 'NOM', 'n/a', 19.685, 2.5, 0.0984252, 0.0983, 5.102, 11.247, 0, 5.10156, 11.247, 0, 0, 'LetsDoATest', 'LetsDoATest', 20, 255, 1, 255, 2867.99, 25.0984, 10, 2550, 28679.9, 78.4314, 1275, 0.0392157, '11', 650, 1, 1.385, 0.294, 0.745098, 2.424, 0, 0, 0.02, 0, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('CL_novemerTest', 'novemerTest', 'novemerTest', 2024, 'novemerTest', 'YES', 1, 200000, 1500, 59.0551, 500, 'NOM', 'N/a', 19.685, 2.5, 0.0984252, 0.0983, 5.102, 11.247, 0, 5.10156, 11.247, 0, 0, 'novemerTest', 'novemerTest', 50, 255, 1, 255, 2867.99, 25.0984, 5, 1275, 14339.9, 156.863, 1275, 0.0588235, '11', 650, 1, 1.385, 0.392, 1.4902, 3.267, 0, 0, 0.02, 0, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('CL_testingNewChanges', 'testingNewChanges', 'testingNewChanges', 2023, 'testingNewChanges', 'NO', 1, 300000, 1200, 47.2441, 200, 'NOM', 'N/A', 7.87402, 1.5, 0.0590551, 0.098, 0.977, 2.153, 2, 0.957054, 2.10994, 0.0195317, 0.04306, 'testingNewChanges', '0', 50, 220, 2, 440, 928.374, 12.9921, 43, 18920, 39920.1, 15.8562, 2200, 0.0227273, '11', 65000, 100, 0.014, 0.17, 0.1, 0.37, 0, 4.306, 0.02, 0.086, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
('TWB_CL_1', 'testingNewFormat', 'testingNewFormat', 2222, 'testingNewFormat', 'YES', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Medium', 50, 220, 1, 220, 1298.5, 12.9921, 30, 6600, 38954.9, 30.303, 220, 0.568182, '11', 55000, 100, 0.016, 0.682, 0.287879, 2.556, 0, 31.395, 0.05, 1.57, 125, 'testingNewFormat', 1, 'testingNewFormat', 'testingNewFormat', 'Configured', 'Exposed', 1, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_2', 'NewUpdate', 'NewUpdate', 2323, 'NewUpdate', 'YES', 1, 200000, 1000, 39.3701, 700, 'MAX', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Medium', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0227273, '11', 65000, 100, 0.014, 0.136, 0.287879, 2.008, 0, 31.395, 0.05, 1.57, 125, 'NewUpdate', 7, 'NewUpdate', 'NewUpdate', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_3', 'NewUpdate2', 'NewUpdate2', 2323, 'NewUpdate2', 'YES', 1, 200000, 1000, 39.3701, 700, 'NOM', 'N/A', 27.5591, 2.5, 0.0984252, 0.098, 4.747, 10.466, 6, 4.46247, 9.83804, 0.284838, 0.62796, 'Wood', 'Medium', 5, 132, 5, 660, 6493.11, 12.9921, 6, 3960, 38958.6, 50.5051, 3300, 0.0378788, '11', 55000, 100, 0.016, 0.227, 0.479798, 3.34, 0, 52.33, 0.05, 2.617, 125, 'NewUpdate2', 7, 'NewUpdate2', 'NewUpdate2', 'Configured', 'Exposed', 5, 1, 0, 210, 10.766, 52.33, 2.26796, 5),
('TWB_CL_4', 'TestNumero3', 'TestNumero3', 2323, 'TestNumero3', 'YES', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Medium', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0227273, '11', 55000, 100, 0.016, 0.136, 0.287879, 2.01, 0, 31.395, 0.05, 1.57, 125, 'TestNumero3', 7, 'TestNumero3', 'TestNumero3', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_5', 'auto download', 'auto download', 2323, 'auto download', 'YES', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Small', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0145455, '11', 65000, 100, 0.014, 0.095, 0.287879, 1.967, 0, 31.395, 0.05, 1.57, 80, 'auto download', 7, 'auto download', 'auto download', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_6', 'autodownload2', 'autodownload2', 2323, 'autodownload2', 'YES', 1, 200000, 1000, 39.3701, 700, 'NOM', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Small', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0145455, '11', 55000, 100, 0.016, 0.095, 0.287879, 1.969, 0, 31.395, 0.05, 1.57, 80, 'autodownload2', 1, 'autodownload2', 'autodownload2', 'Cut To Length', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_7', 'testing333', 'testing333', 2222, 'testing333', 'YES', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Small', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0145455, '11', 55000, 100, 0.016, 0.095, 0.287879, 0.713, 0, 31.395, 0.01, 0.314, 80, 'testing333', 7, 'testing333', 'testing333', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_8', 'testing66', 'testing66', 2323, 'testing66', 'YES', 1, 200000, 1000, 39.3701, 700, 'NOM', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Small', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0145455, '11', 65000, 100, 0.014, 0.095, 0.287879, 1.967, 0, 31.395, 0.05, 1.57, 80, 'testing66', 7, 'testing66', 'testing66', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_9', 'testingUploads', 'testingUploads', 2323, 'testingUploads', 'NO', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Medium', 50, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0227273, '11', 65000, 100, 0.014, 0.136, 0.287879, 2.008, 0, 31.395, 0.05, 1.57, 125, 'testingUploads', 7, 'testingUploads', 'testingUploads', 'Cut To Length', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_10', 'failtest', 'failtest', 2323, 'failtest', 'YES', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Small', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0145455, '11', 55000, 100, 0.016, 0.095, 0.287879, 1.969, 0, 31.395, 0.05, 1.57, 80, 'failtest', 7, 'failtest', 'failtest', 'Configured', 'Unexposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_11', 'testingonceagain', 'testingonceagain', 2023, 'testingonceagain', 'YES', 1, 200000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Metal', 'Small', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0, '11', 65000, 100, 0.014, 0.023, 0.287879, 1.895, 0, 31.395, 0.05, 1.57, 0, 'testingonceagain', 7, 'testingonceagain', 'testingonceagain', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_12', 'testingReviewTab', 'testingReviewTab', 2323, 'testingReviewTab', 'YES', 1, 200000, 1000, 39.3701, 700, 'NOM', 'N/A', 27.5591, 2.5, 0.0984252, 0.098, 4.747, 10.466, 6, 4.46247, 9.83804, 0.284838, 0.62796, 'Wood', 'Medium', 5, 132, 5, 660, 6493.11, 12.9921, 6, 3960, 38958.6, 50.5051, 3300, 0.0378788, '11', 55000, 100, 0.016, 0.227, 0.479798, 3.34, 0, 52.33, 0.05, 2.617, 125, 'testingReviewTab', 7, 'testingReviewTab', 'testingReviewTab', 'Configured', 'Exposed', 5, 1, 0, 210, 10.766, 52.33, 2.26796, 5),
('TWB_CL_12', 'testingReviewTab', 'testingReviewTab', 2323, 'testingReviewTab', 'YES', 1, 200000, 1000, 39.3701, 700, 'NOM', 'N/A', 27.5591, 2.5, 0.0984252, 0.098, 4.747, 10.466, 6, 4.46247, 9.83804, 0.284838, 0.62796, 'Wood', 'Medium', 5, 132, 5, 660, 6493.11, 12.9921, 6, 3960, 38958.6, 50.5051, 3300, 0.0378788, '11', 55000, 100, 0.016, 0.227, 0.479798, 3.34, 0, 52.33, 0.05, 2.617, 125, 'testingReviewTab', 7, 'testingReviewTab', 'testingReviewTab', 'Configured', 'Exposed', 5, 1, 0, 210, 10.766, 52.33, 2.26796, 5),
('TWB_CL_14', 'testingWidths', 'testingWidths', 2323, 'testingWidths', 'YES', 1, 600000, 1000, 39.3701, 700, 'MIN', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Small', 1, 220, 1, 220, 1298.5, 12.9921, 30, 6600, 38954.9, 90.9091, 220, 0.363636, '11', 65000, 100, 0.014, 0.477, 0.287879, 698.376, 0, 31.395, 22.22, 697.597, 80, 'testingWidths', 6, 'testingWidths', 'testingWidths', 'Configured', 'Exposed', 1, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_15', 'UncheckBoxes', 'UncheckBoxes', 2323, 'UncheckBoxes', 'YES', 1, 200000, 1000, 39.3701, 700, 'NOM', 'N/A', 27.5591, 1.5, 0.0590551, 0.098, 2.848, 6.279, 6, 2.67722, 5.90226, 0.170887, 0.37674, 'Wood', 'Medium', 5, 220, 5, 1100, 6492.49, 12.9921, 6, 6600, 38954.9, 30.303, 5500, 0.0227273, '11', 65000, 100, 0.014, 0.136, 0.287879, 2.008, 0, 31.395, 0.05, 1.57, 125, 'UncheckBoxes', 6, 'UncheckBoxes', 'UncheckBoxes', 'Configured', 'Exposed', 5, 1, 0, 351, 6.459, 31.395, 2.26796, 5),
('TWB_CL_16', 'testingz', 'testingz', 2024, 'testingz', 'YES', 1, 200000, 1100, 43.3071, 700, 'NOM', '5', 27.5591, 2.5, 0.0984252, 0.098, 5.222, 11.512, 5, 4.96067, 10.9364, 0.261088, 0.5756, 'Metal', 'Small', 50, 132, 5, 660, 7218.02, 12.9921, 5, 3300, 36090.1, 60.6061, 3300, 0, '11', 65000, 100, 0.014, 0.038, 0.575758, 3.507, 0, 57.56, 0.05, 2.879, 0, 'testingz', 5, 'testingz', 'testingz', 'Configured', 'Exposed', 5, 1, 173, 191, 11.843, 57.56, 2.26796, 5);

-- --------------------------------------------------------

--
-- Table structure for table `observations`
--

CREATE TABLE `observations` (
  `observation_id` int(11) NOT NULL,
  `observation_score` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `observation_date` varchar(255) NOT NULL,
  `observation_time` varchar(255) NOT NULL,
  `observation_description` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `observations`
--

INSERT INTO `observations` (`observation_id`, `observation_score`, `employee_id`, `observation_date`, `observation_time`, `observation_description`) VALUES
(1, 10, 1, '2023-12-04', '12:12', 0x456d706c6f7965652077656172696e6720616c6c20505045);

-- --------------------------------------------------------

--
-- Table structure for table `Part`
--

CREATE TABLE `Part` (
  `part_id` int(11) NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `Part#` varchar(255) NOT NULL,
  `Part Name` varchar(255) DEFAULT NULL,
  `Mill` varchar(255) DEFAULT NULL,
  `Platform` varchar(255) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Surface` varchar(255) DEFAULT NULL,
  `Material Type` varchar(255) DEFAULT NULL,
  `pallet_type` varchar(255) DEFAULT NULL,
  `pallet_size` varchar(255) DEFAULT NULL,
  `pallet_uses` int(11) DEFAULT NULL,
  `Pieces per Lift` int(11) DEFAULT NULL,
  `Stacks per Skid` int(11) DEFAULT NULL,
  `Skids per Truck` int(11) DEFAULT NULL,
  `Scrap Consumption` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Part`
--

INSERT INTO `Part` (`part_id`, `supplier_name`, `customer_id`, `Part#`, `Part Name`, `Mill`, `Platform`, `Type`, `Surface`, `Material Type`, `pallet_type`, `pallet_size`, `pallet_uses`, `Pieces per Lift`, `Stacks per Skid`, `Skids per Truck`, `Scrap Consumption`) VALUES
(51, 'Target Metal Blanking', 1, 'RL1B-S10552-AA', 'PNL RR FLR RR SL LWR', 'N/A', 'Ford U71X	', 'CTL/Config', 'Unexposed', 'WSS-M2A175-A2 6HS2', 'Wood', 'Large', 5, 100, 1, 10, 3),
(52, 'RunningATest', 1, 'RunningATest', 'RunningATest', 'RunningATest', 'RunningATest', 'RunningATest', 'RunningATest', 'RunningATest', 'RunningATest', 'RunningATest', 5, 255, 1, 5, 5),
(53, 'anothertest', 0, 'anothertest', 'anothertest', 'anothertest', 'anothertest', 'anothertest', 'anothertest', 'anothertest', 'anothertest', 'anothertest', 5, 255, 1, 5, 5),
(54, 'TestingTotalAward', 1, 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 'TestingTotalAward', 5, 250, 1, 5, 5),
(55, 'TestingTotalAwardsAgain', 1, 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 'TestingTotalAwardsAgain', 5, 255, 1, 5, 3),
(56, 'anotherTotalCost', 1, 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 'anotherTotalCost', 5, 255, 1, 5, 3),
(57, 'FinalTotalCostTest', 1, 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 'FinalTotalCostTest', 5, 255, 1, 5, 3),
(58, 'Testinyiyiy', 0, 'Testinyiyiy', 'Testinyiyiy', 'Testinyiyiy', 'Testinyiyiy', 'TestinyiyiyTestinyiyiy', 'Testinyiyiy', 'Testinyiyiy', 'Testinyiyiy', 'Testinyiyiy', 5, 255, 1, 5, 5),
(59, 'testingkjkjk', 1, 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 'testingkjkjk', 5, 255, 1, 5, 2),
(60, 'TMB', 1, 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 'LetsDoATest', 5, 255, 1, 10, 0),
(61, 'novemerTest', 1, 'novemerTest', 'novemerTest', 'novemerTest', 'novemerTest', 'novemerTest', 'novemerTest', 'novemerTest', 'novemerTest', 'novemerTest', 5, 255, 1, 5, 0),
(62, 'testingNewChanges', 1, 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 'testingNewChanges', 5, NULL, 2, NULL, 2),
(63, 'testingNewFormat', 1, 'testingNewFormat', 'testingNewFormat', 'testingNewFormat', 'testingNewFormat', 'Configured', 'Exposed', 'testingNewFormat', 'Wood', 'Medium', 1, NULL, 1, NULL, 6),
(64, 'NewUpdate', 7, 'NewUpdate', 'NewUpdate', 'NewUpdate', 'NewUpdate', 'Configured', 'Exposed', 'NewUpdate', 'Wood', 'Medium', 5, NULL, 5, NULL, 6),
(65, 'NewUpdate2', 7, 'NewUpdate2', 'NewUpdate2', 'NewUpdate2', 'NewUpdate2', 'Configured', 'Exposed', 'NewUpdate2', 'Wood', 'Medium', 5, NULL, 5, NULL, 6),
(66, 'TestNumero3', 7, 'TestNumero3', 'TestNumero3', 'TestNumero3', 'TestNumero3', 'Configured', 'Exposed', 'TestNumero3', 'Wood', 'Medium', 5, NULL, 5, NULL, 6),
(67, 'auto download', 7, 'auto download', 'auto download', 'auto download', 'auto download', 'Configured', 'Exposed', 'auto download', 'Wood', 'Small', 5, NULL, 5, NULL, 6),
(68, 'autodownload2', 1, 'autodownload2', 'autodownload2', 'autodownload2', 'autodownload2', 'Cut To Length', 'Exposed', 'autodownload2', 'Wood', 'Small', 5, NULL, 5, NULL, 6),
(69, 'testing333', 7, 'testing333', 'testing333', 'testing333', 'testing333', 'Configured', 'Exposed', 'testing333', 'Wood', 'Small', 5, NULL, 5, NULL, 6),
(70, 'testing66', 7, 'testing66', 'testing66', 'testing66', 'testing66', 'Configured', 'Exposed', 'testing66', 'Wood', 'Small', 5, NULL, 5, NULL, 6),
(71, 'testingUploads', 7, 'testingUploads', 'testingUploads', 'testingUploads', 'testingUploads', 'Cut To Length', 'Exposed', 'testingUploads', 'Wood', 'Medium', 5, NULL, 5, NULL, 6),
(72, 'failtest', 7, 'failtest', 'failtest', 'failtest', 'failtest', 'Configured', 'Unexposed', 'failtest', 'Wood', 'Small', 5, NULL, 5, NULL, 6),
(73, 'testingonceagain', 7, 'testingonceagain', 'testingonceagain', 'testingonceagain', 'testingonceagain', 'Configured', 'Exposed', 'testingonceagain', 'Metal', 'Small', 5, NULL, 5, NULL, 6),
(74, 'testingReviewTab', 7, 'testingReviewTab', 'testingReviewTab', 'testingReviewTab', 'testingReviewTab', 'Configured', 'Exposed', 'testingReviewTab', 'Wood', 'Medium', 5, NULL, 5, NULL, 6),
(75, 'testingWidths', 6, 'testingWidths', 'testingWidths', 'testingWidths', 'testingWidths', 'Configured', 'Exposed', 'testingWidths', 'Wood', 'Small', 1, NULL, 1, NULL, 6),
(76, 'UncheckBoxes', 6, 'UncheckBoxes', 'UncheckBoxes', 'UncheckBoxes', 'UncheckBoxes', 'Configured', 'Exposed', 'UncheckBoxes', 'Wood', 'Medium', 5, NULL, 5, NULL, 6),
(77, 'TMB ', 5, 'FT4B-R40414', 'Lift Gate Inner ', 'Cliffs ', '', 'Cut To Length', 'Unexposed', 'WSS-M1A365-A14 50G50G GI (CR4', 'Wood', 'Medium', 0, NULL, 1, NULL, 2),
(78, 'testingAgain', 5, 'testingAgain', 'testingAgain', 'testingAgain', 'testingAgain', 'Configured', 'Exposed', 'testingAgain', 'Wood', 'Medium', 5, NULL, 5, NULL, 5),
(79, 'testingz', 5, 'testingz', 'testingz', 'testingz', 'testingz', 'Configured', 'Exposed', 'testingz', 'Metal', 'Small', 5, NULL, 5, NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `training_paths`
--

CREATE TABLE `training_paths` (
  `training_path_id` int(11) DEFAULT NULL,
  `training_path_name` varchar(255) NOT NULL,
  `training_path_file` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `username`, `email`, `password`, `user_type`) VALUES
(2, 'Bill Johns', 'wjohns@targetmetalblanking.com', 'password', 'super-admin'),
(3, 'Christian Laviolette', 'claviolette@targetmetalblanking.com', '042217Dv!', 'super-admin'),
(4, 'Mike Bruff', 'mbruff@targetmetalblanking.com', 'password', 'super-admin'),
(12, 'Brendan Demantes', 'bdemantes@targetmetalblanking.com', 'safetyrocks1', 'Human Resources'),
(13, 'jim laviolette', 'jlaviolette@targetmetalblanking.com', 'O710At', 'super-admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accident_report`
--
ALTER TABLE `accident_report`
  ADD PRIMARY KEY (`accident_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `job_title` (`job_title`);

--
-- Indexes for table `employee_training`
--
ALTER TABLE `employee_training`
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `training_path_id` (`training_path_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `Customer Name` (`Customer Name`),
  ADD KEY `invoice_id` (`invoice_id`) USING BTREE;

--
-- Indexes for table `invoice_files`
--
ALTER TABLE `invoice_files`
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `job_titles`
--
ALTER TABLE `job_titles`
  ADD PRIMARY KEY (`job_title_id`);

--
-- Indexes for table `Lines`
--
ALTER TABLE `Lines`
  ADD PRIMARY KEY (`line_id`);

--
-- Indexes for table `Line_Item`
--
ALTER TABLE `Line_Item`
  ADD KEY `Part#` (`Part#`),
  ADD KEY `Part Name` (`Part Name`),
  ADD KEY `Material Type` (`Material Type`),
  ADD KEY `Pallet Type` (`Pallet Type`),
  ADD KEY `Pallet Size` (`Pallet Size`);

--
-- Indexes for table `observations`
--
ALTER TABLE `observations`
  ADD PRIMARY KEY (`observation_id`);

--
-- Indexes for table `Part`
--
ALTER TABLE `Part`
  ADD PRIMARY KEY (`part_id`);

--
-- Indexes for table `training_paths`
--
ALTER TABLE `training_paths`
  ADD KEY `training_path_id` (`training_path_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accident_report`
--
ALTER TABLE `accident_report`
  MODIFY `accident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Customer`
--
ALTER TABLE `Customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `job_titles`
--
ALTER TABLE `job_titles`
  MODIFY `job_title_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `observations`
--
ALTER TABLE `observations`
  MODIFY `observation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Part`
--
ALTER TABLE `Part`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accident_report`
--
ALTER TABLE `accident_report`
  ADD CONSTRAINT `accident_report_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`job_title`) REFERENCES `job_titles` (`job_title_id`);

--
-- Constraints for table `employee_training`
--
ALTER TABLE `employee_training`
  ADD CONSTRAINT `employee_training_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `employee_training_ibfk_2` FOREIGN KEY (`training_path_id`) REFERENCES `training_paths` (`training_path_id`);

--
-- Constraints for table `training_paths`
--
ALTER TABLE `training_paths`
  ADD CONSTRAINT `training_paths_ibfk_1` FOREIGN KEY (`training_path_id`) REFERENCES `job_titles` (`job_title_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
