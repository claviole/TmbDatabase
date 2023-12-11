-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2023 at 11:41 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

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

-- ------------------------------------------------------------

--
-- Table Structure for 'Users'
--

CREATE TABLE `Users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL
)ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table 'Users'
--
INSERT INTO `Users` (`id`, `username`, `email`, `password`,`user_type`) VALUES
(2,'bjohns','wjohns@targetmetalblanking.com','bjohns23','admin'),
(3,'claviolette','claviolette@targetmetalblanking.com','042217Dv!','admin');


-- ------------------------------------------------------------


-- ------------------------------------------------------------
--
-- Table Structure for Parts 'Part'
--
CREATE TABLE `Part` (
  `part_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `supplier_name` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `Part#` varchar(255) DEFAULT NULL,
  `Part Name` varchar(255) DEFAULT NULL,
  `Mill` varchar(255) DEFAULT NULL,
  `Platform` varchar(255) DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `Surface` varchar(255) DEFAULT NULL,
  `Material Type` varchar(255) DEFAULT NULL,
  `pallet_type` varchar(255) DEFAULT NULL,
`pallet_size` varchar(255) DEFAULT NULL,
`pallet_uses` int DEFAULT NULL,
  `Stacks per Skid` int DEFAULT NULL,
  `Scrap Consumption` float DEFAULT NULL

)
ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Part`
--
INSERT INTO `Part` (`Part#`,`Part Name`,`Mill`,`Platform`,`Type`,`Surface`,`Material Type`,`pallet_type`,`pallet_size`,`Pieces per Lift`,`Stacks per Skid`,`Skids per Truck`,`Scrap Consumption`) VALUES
('18318323 A blank', 'Battery Tray - Top Cover Pickup A blank','N/A','Tesla Cybertruck','configured','U','CR-340-410-LA-S','Wood','31" x 78 "',254,1,5,.74),
('18318323 B blank', 'Battery Tray - Top Cover Pickup B blank','N/A','Tesla Cybertruck','configured','U','CR-340-410-LA-S','Wood','20" x 78 "',254,1,9,1.33),
('18318323 C blank', 'Battery Tray - Top Cover Pickup C blank','N/A','Tesla Cybertruck','configured','U','CR-340-410-LA-S','Wood','60" x 78 "',254,1,4,.74);
-- ------------------------------------------------------------
--
-- Table Structure for 'Customer'
--
CREATE TABLE `Customer`(
  `customer_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Customer Name` varchar(255) DEFAULT NULL,
  `Customer Address` varchar(255) DEFAULT NULL,
  `Customer City` varchar(255) DEFAULT NULL,
  `Customer State` varchar(255) DEFAULT NULL,
  `Customer Zip` varchar(255) DEFAULT NULL,
  `Customer Phone` varchar(255) DEFAULT NULL,
  `Customer Email` varchar(255) DEFAULT NULL,
  `Customer Contact` varchar(255) DEFAULT NULL


)
ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ------------------------------------------------------------
--
-- Table Structure for 'Line_Item'
--
CREATE TABLE `Line_Item` (
  `invoice_id` int(11) NOT NULL,
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
  `Pcs per Lift` int DEFAULT NULL,
  `Stacks per Skid` int DEFAULT NULL,
  `Pcs per Skid` int DEFAULT NULL,
  `Lift Weight+Skid Weight(lb)` float DEFAULT NULL,
  `Stack Height` float DEFAULT NULL,
  `Skids per Truck` int DEFAULT NULL,
  `Pieces per Truck` int DEFAULT NULL,
  `Truck Weight(lb)` float DEFAULT NULL,
  `Annual Truckloads` float DEFAULT NULL,
  `UseSkidPcs` int DEFAULT NULL,
  `Skid cost per piece` float DEFAULT NULL,
  `Line Produced on` varchar(255) DEFAULT NULL,
  `PPH` int DEFAULT NULL,
  `Uptime` float DEFAULT NULL,
  `Blanking per piece cost` float DEFAULT NULL,
  `Packaging Per Piece Cost` float DEFAULT NULL,
  `freight per piece cost` float DEFAULT NULL,
  `Total Cost per Piece` float DEFAULT NULL,
  `wash_and_lube` float  DEFAULT NULL,
  `material_cost` float DEFAULT NULL,
  `material_markup_percent` float DEFAULT NULL,
  `material_cost_markup` float DEFAULT NULL,
  `palletCost` float DEFAULT NULL,
  FOREIGN KEY (`Part#`) REFERENCES `Part` (`Part#`),
  FOREIGN KEY (`Part Name`) REFERENCES `Part` (`Part Name`),
  FOREIGN KEY (`Material Type`) REFERENCES `Part` (`Material Type`),
  FOREIGN KEY (`Pallet Type`) REFERENCES `Part` (`Pallet Type`),
  FOREIGN KEY (`Pallet Size`) REFERENCES `Part` (`Pallet Size`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ------------------------------------------------------------
--  
-- Table Structure for 'Invoice'
--
 CREATE TABLE `invoice`(
  invoice_id int(11) DEFAULT NULL PRIMARY KEY ,
  invoice_number varchar(255) NOT NULL,
  `version` int(11) DEFULT 1,
  invoice_date varchar(255) DEFAULT NULL,
  customer_id int(11) DEFAULT NULL,
  invoice_author varchar(255) NOT NULL,
  approval_status varchar(255) DEFAULT "Awaiting Approval",
  award_total decimal(12,3) DEFAULT NULL,
  award_status varchar(255) DEFAULT "pending",
  approved_by varchar(255) DEFAULT NULL,
  FOREIGN KEY (customer_id) REFERENCES Customer(customer_id),
  `Customer Name` varchar(255) NOT NULL,
  FOREIGN KEY (`Customer Name`) REFERENCES `Customer` (`Customer Name`),
  contingencies MEDIUMBLOB
 ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- ------------------------------------------------------------
--
-- Table Structure for 'invoice_files'
--
CREATE TABLE `invoice_files`(
  `invoice_id` int(11) NOT NULL
  `file_name`  varchar(255) NOT NULL, 
  `file_name` MEDIUMBLOB NOT NULL
  FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ------------------------------------------------------------\
--  
-- Table Structure for 'Lines'
CREATE TABLE `Lines`(
  line_id int(11) NOT NULL  PRIMARY KEY,
  Line_Location varchar(255) NOT NULL,
  Line_Name varchar(255) NOT NULL
)
ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table `Lines`
--  
INSERT INTO `Lines`(`line_id`,`Line_Location`,`Line_Name`) VALUES
(1,'Target Steel-Flat Rock (HQ)','Line 1'),
(2,'Target Steel Riverview MI','Line 1'),
(3,'Target Steel Riverview MI','Line 2'),
(4,'Target Steel Riverview MI','Line 3'),
(5,'Target Steel Riverview MI','Line 4'),
(6,'Target Steel Riverview MI','Line 5'),
(7,'Torch Steel Processing','Line 72'),
(8,'Target Metal Blanking - New Boston','Ace'),
(9,'Target Metal Blanking - New Boston','Deuce'),
(10,'Target Metal Blanking - New Boston','Sinq'),
(11,'Target Metal Blanking - North Vernon','Line 1'),
(12,'Target Metal Blanking - North Vernon','Line 2'),
(13,'Target Metal Blanking - North Vernon','Line 3'),
(14,'Target Metal Blanking - North Vernon','Line 4'),
(15,'Target Metal Blanking - Sauk Village','Line 1'),
(16,'Target Metal Blanking - Sauk Village','Line 3'),
(17,'Target Metal Blanking - Sauk Village','Line 4'),
(18,'Target Metal Blanking - Sauk Village','Line 5'),
(19,'Target Metal Blanking - Sauk Village','Line 6'),
(20,'Target Metal Blanking - Sauk Village','Line 7'),
(21,'Target Metal Blanking - Sauk Village','Laser');



-- Table Structure for 'job_titles'
-- 
CREATE TABLE `job_titles`(
  job_title_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  job_title varchar(255) NOT NULL
);

-- Dumping data for table 'job_titles'
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
(19,'Supervisor')
(20,'HR-Manager')
(21,'HR-Generalist')
(22,'Operations-Manager'),
(23,'Operations-Blanking Supervisor'),
(24,'Operations-Slitting Supervisor'),
(25,'Maintenance-Manager'),
(26,'Maintenance-Coordinator'),
(27,'General Manager'),
(28,'Accounting-Controller'),
(29,'Accounting-Manager'),
(30,'Accounting-AR Analyst'),
(31,'Quality-Manager'),
(32,'Quality Engineer'),
(33,'Quality-Supervisor/Liason'),
(34,'Quality-Technician'),
(35,'Logistics-Manager'),
(36,'Sales-Manager'),
(37,'Sales-Representative'),
(38,'Safety-Coordinator');

-- Table Structure for 'training_paths'
--
CREATE TABLE `training_paths`(
  training_path_id int(11) ,
  training_path_name varchar(255) NOT NULL,
  training_path_file MEDIUMBLOB NOT NULL,
  FOREIGN KEY (`training_path_id`) REFERENCES `job_titles` (`job_title_id`)
);


-- Table Structure for 'employees' 
--
CREATE TABLE `employees`(
  employee_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  employee_fname varchar(255) NOT NULL,
  employee_lname varchar(255) NOT NULL,
  date_hired DATE(255) NULL,
  first_day_of_work DATE(255) NULL,
  job_title int(11) NOT NULL,
  FOREIGN KEY (`job_title`) REFERENCES `job_titles` (`job_title_id`)
);

-- Table Structure for 'employee_training`
--
CREATE TABLE `employee_training`(
  employee_id int(11) NOT NULL,
  training_path_id int(11) NOT NULL,
  completion_status varchar(255) DEFAULT 'incomplete',
  FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  FOREIGN KEY (`training_path_id`) REFERENCES `training_paths` (`training_path_id`)
);

--Table Structure for 'employee_incident
--
CREATE TABLE `accident_report`(
  accident_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  employee_id int(11) NOT NULL,
  non_employee_name varchar(255) NULL,
  foreman_id int(11) NULL,
  accident_type varchar(255)  NULL,
  date_added varchar(255)  NULL,
  accident_date varchar(255)  NULL,
  accident_time varchar(255) NULL,
  shift varchar(255)  NULL,
  time_sent_to_clinic varchar(255) NULL,
  date_sent_to_clinic varchar(255) NULL,
  accident_location varchar(255) NULL,
  time_of_report varchar(255) NULL,
  shift_start_time varchar(255) NULL,
  accident_description MEDIUMBLOB NULL,
  consecutive_days_worked int(11)  NULL,
  proper_ppe_used varchar(255) NULL,
  proper_ppe_used_explain MEDIUMBLOB NULL,
  procedure_followed varchar(255) NULL,
  procedure_followed_explain MEDIUMBLOB NULL,
  potential_severity varchar(255) NULL,
  potential_severity_explain MEDIUMBLOB NULL,
  enverionmental_impact varchar(255)  NULL,
  enverionmental_impact_explain MEDIUMBLOB NULL,
  prevent_reoccurance MEDIUMBLOB NULL,
  immediate_corrective_action MEDIUMBLOB NULL,
  irp_required varchar(255) NULL,
  irp_names varchar(255) NULL,
  equip_out_of_service varchar(255) NULL,
  equip_out_of_service_explain MEDIUMBLOB NULL,

  FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`))


  -- Table Structure for 'accident_files'
  --
  CREATE TABLE `accident_files`(
    accident_id int(11) NOT NULL,
    file_name varchar(255) NOT NULL,
    file_path MEDIUMBLOB NOT NULL
  );


  --Last SQL UPDATE 11/21/23 06:43 PM


  -- Table Structure for 'observations'
  --
  CREATE TABLE `observations`(
    observation_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    observation_score int(11) NOT NULL,
    employee_id int(11) NOT NULL,
    observation_date varchar(255) NOT NULL,
    observation_time varchar(255) NOT NULL,
    observation_description MEDIUMBLOB NOT NULL
  )

);