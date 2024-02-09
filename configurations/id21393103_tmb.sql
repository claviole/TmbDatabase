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
  `environmental_impact` varchar(255) DEFAULT NULL,
  `environmental_impact_explain` mediumblob DEFAULT NULL,
  `prevent_reoccurance` mediumblob DEFAULT NULL,
  `immediate_corrective_action` mediumblob DEFAULT NULL,
  `irp_required` varchar(255) DEFAULT NULL,
  `irp_names` varchar(255) DEFAULT NULL,
  `equip_out_of_service` varchar(255) DEFAULT NULL,
  `equip_out_of_service_explain` mediumblob DEFAULT NULL,
  `location_code` varchar(255) DEFAULT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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



--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `employee_fname` varchar(255) NOT NULL,
  `employee_lname` varchar(255) NOT NULL,
  `date_hired` date NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_day_of_work` date NOT NULL,
  `job_title` int(11) NOT NULL,
  `status` varchar(255) DEFAULT "active"
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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


-- --------------------------------------------------------

--
-- Table structure for table `invoice_files`
--

CREATE TABLE `invoice_files` (
  `invoice_id` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;




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
  `ship_to_location` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



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
  `user_type` varchar(255) DEFAULT NULL,
  `location_code` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT "active"
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `username`, `email`, `password`, `user_type`) VALUES
(1, 'Christian Laviolette', 'claviolette@targetmetalblanking.com', '$2y$10$BFueNcQ2cNQllOvQRB7sqeqxnsgzyBCvMSYxHownE8lBEGGnoIcae', 'super-admin');


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

-- 
-- Create table for maintenance tickets
--
CREATE TABLE `orange_tag`(
  `orange_tag_id` varchar(255) NULL,
  `ticket_type` varchar(255) NULL,
  `originator` varchar(255)  NULL,
  `originator_name` varchar(255) NULL,
  `location` varchar(255) NULL,
  `line_name` varchar(255) NULL,
  `die_number` varchar(255) NULL,
  `priority` int(11) NULL,
  `section` varchar(255) NULL,
  `supervisor` varchar(255) NULL,
  `maintenance_supervisor` varchar(255)  NULL,
  `safety_coordinator` varchar(255) NULL,
  `orange_tag_creation_date` varchar(255) NULL,
  `orange_tag_creation_time` varchar(255) NULL,
  `orange_tag_due_date` varchar(255) NULL,
  `repairs_made` mediumblob NULL,
  `root_cause` mediumblob NULL,
  `equipment_down_time` varchar(255) NULL,
  `total_repair_time` varchar(255) NULL,
  `area_cleaned` varchar(255) NULL,
  `follow_up_necessary` varchar(255) NULL,
  `parts_needed` varchar(255) NULL,
  `reviewed_by_supervisor` varchar(255) NULL,
  `reviewed_by_safety_coordinator` varchar(255) NULL,
  `supervisor_review_date` varchar(255) NULL,
  `safety_coordinator_review_date` varchar(255) NULL,
  `verified` varchar(255) NULL,
  `date_verified` varchar(255) NULL,
  `orange_tag_description` mediumblob NULL,
  `repair_technician` varchar(255) NULL,
  `total_cost` varchar(255) NULL,
  `ticket_status` varchar(255) DEFAULT "Open",
  `work_order_number` varchar(255) NULL,
  `location_code` varchar(255) NULL,
  `date_closed` varchar(255) NULL

 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `repair_parts`(
  `date_used` varchar(255) NULL,
  `orange_tag_id` varchar(255) NULL,
  `part_description` mediumblob NULL,
  `quantity` int(11) NULL,
  `brand_name` varchar(255) NULL,
  `model_number` varchar(255) NULL,
  `serial_number` varchar(255) NULL,
  `dimensions` varchar(255) NULL
  
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `purchase_requests`(
  `expense_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `expense_type` varchar(255) NULL,
  `travel_start_date` varchar(255) NULL,
  `travel_end_date` varchar(255) NULL,
  `customer_name` varchar(255) NULL,
  `customer_location` varchar(255) NULL,
  `additional_comments` mediumblob NULL,
  `month_of_expense` varchar(255) NULL,
  `date_of_visit` varchar(255) NULL,
  `mileage` decimal(12,3) NULL,
  `mileage_expense` decimal(12,3) NULL,
  `meals_expense` decimal(12,3) NULL,
  `entertainment_expense` decimal(12,3) NULL,
  `facility` varchar(255) NULL,
  `employee_name` varchar(255) NULL,
  `vendor_name` varchar(255) NULL,
  `approval_status` varchar(255) DEFAULT "pending"
);

CREATE TABLE `expense_files`(
  `expense_id` int(11) NULL,
  `file_name` varchar(255) NULL,
  `file_path` varchar(255) NULL
);

CREATE TABLE `expense_items`(
  `expense_id` int(11) NULL,
  `item_name` mediumblob NULL,
  `item_quantity` int(11) NULL,
  `price_per_item` decimal(12,3) NULL,
  `total_cost` decimal(12,3) NULL,
  `department` varchar(255) NULL
);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
