-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2024 at 12:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Id`, `Name`, `email`, `password`) VALUES
(1, 'talha', 'talhafast0005@gmail.com', 'fast123');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `rollno` varchar(10) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `rollno`, `date`, `status`) VALUES
(1, '2023001', '2024-06-28', 'Present'),
(3, '2023003', '2024-06-28', 'Absent'),
(4, '2023004', '2024-06-28', 'Present'),
(14, '2023003', '2024-06-27', 'Absent'),
(15, '2023004', '2024-06-27', 'Present'),
(29, '2023001', '2024-06-30', 'Late');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `request_id` int(11) NOT NULL,
  `rollno` varchar(20) NOT NULL,
  `request_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`request_id`, `rollno`, `request_date`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, '2023002', '2024-06-27', 'Going down memory lane, English has always been a favourite subject of many of us. Those insightful chapters and catchy poems were total a treat and bliss to read. It is also considered one of the highest-scoring subjects in school. Generally, the question paper in English consists of sections like Literature, Grammar, and Reading Comprehension. Out of these 4, the writing section is counted amongst the trickiest to solve. Furthermore, apart from Notice Writing, Article Writing, Note Making, Leave Application, etc. are some of the topics which can be difficult to score. So, to make things easier, here is a blog that will help you understand the important details about writing a leave application for school.', 'approved', '2024-06-27 17:32:31', '2024-06-29 19:28:06'),
(2, '2023001', '2024-06-30', 'cksnkccansnkjcnanckjnsnckas', 'pending', '2024-06-30 09:55:16', '2024-06-30 09:55:16'),
(3, '2023001', '2024-06-30', 'this is 3rd leave request.', 'approved', '2024-06-30 10:09:41', '2024-06-30 10:15:35');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `rollno` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `phoneno` varchar(15) DEFAULT NULL,
  `homeaddress` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`rollno`, `name`, `department`, `phoneno`, `homeaddress`, `email`, `gender`, `age`, `password`) VALUES
('2023001', 'John Doe', 'Computer Science', '1234567890', '123 Main St, City', 'john.doe@example.com', 'Male', 25, 'password1'),
('2023002', 'Jane Smith', 'Electrical Engineering', '2147483647', '456 Oak Ave, Town', 'jane.smith@example.com', 'Female', 23, 'password2'),
('2023003', 'Michael Johnson', 'Mechanical Engineering', '2147483647', '789 Elm St, Village', 'michael.johnson@example.com', 'Male', 27, 'password3'),
('2023004', 'Emily Davis', 'Civil Engineering', '2147483647', '321 Pine Rd, Suburb', 'emily.davis@example.com', 'Female', 24, 'password4'),
('2023005', 'David Brown', 'Chemical Engineering', '2147483647', '654 Cedar Ln, County', 'david.brown@example.com', 'Male', 26, 'password5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rollno` (`rollno`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `rollno` (`rollno`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`rollno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`rollno`) REFERENCES `students` (`rollno`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`rollno`) REFERENCES `students` (`rollno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
