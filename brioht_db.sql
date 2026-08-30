-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2026 at 02:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brioht_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `student_name`, `class_name`, `attendance_date`, `status`, `created_at`) VALUES
(1, 1, 'ali', 'Basic', '2026-08-02', 'Present', '2026-08-02 05:59:42'),
(2, 2, 'adil', 'Level Five', '2026-08-02', 'Present', '2026-08-02 06:20:04'),
(3, 2, 'adil', 'Level Five', '2026-08-02', 'Present', '2026-08-02 06:24:10'),
(4, 1, 'ali', 'Basic', '2026-08-02', 'Present', '2026-08-02 06:24:30'),
(11, 4, 'Hani', 'Level Four', '2026-08-02', 'Present', '2026-08-02 06:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`) VALUES
(11, 'Advance'),
(1, 'Basic'),
(4, 'Beginner One'),
(5, 'Beginner Two'),
(2, 'Foundation One'),
(3, 'Foundation Two'),
(10, 'Level Five'),
(9, 'Level Four'),
(6, 'Level One'),
(8, 'Level Three'),
(7, 'Level Two');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `fee_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fee_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `student_id`, `student_name`, `class_name`, `fee_amount`, `paid_amount`, `fee_status`, `created_at`) VALUES
(1, 1, 'ali', 'Basic', 800.00, 780.00, 'Paid', '2026-08-02 05:56:31'),
(2, 2, 'adil', 'Level Five', 800.00, 787.00, 'Paid', '2026-08-02 06:05:37'),
(3, 3, 'ahmeda', 'Beginner One', 700.00, 6700.00, 'Paid', '2026-08-02 06:12:21'),
(4, 4, 'Hani', 'Level Four', 700.00, 700.00, 'Paid', '2026-08-02 06:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `total_marks` int(11) NOT NULL DEFAULT 0,
  `obtained_marks` int(11) NOT NULL DEFAULT 0,
  `grade` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `student_name`, `class_name`, `total_marks`, `obtained_marks`, `grade`, `created_at`) VALUES
(1, 1, 'ali', 'Basic', 100, 98, 'A', '2026-08-02 05:56:31'),
(2, 2, 'adil', 'Level Five', 100, 867, 'a', '2026-08-02 06:05:37'),
(3, 3, 'ahmeda', 'Beginner One', 0, 0, 'N/A', '2026-08-02 06:12:21'),
(4, 4, 'Hani', 'Level Four', 0, 0, 'N/A', '2026-08-02 06:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `admission_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_name`, `father_name`, `mobile`, `email`, `class_id`, `admission_date`, `created_at`) VALUES
(1, 'ali', 'faa', '6868788', 'adirafiq7860@gmail.com', 1, '2026-08-02', '2026-08-02 05:56:31'),
(2, 'adil', 'gg', '0331', 'adirafiq7860@gmail.com', 10, '2026-08-02', '2026-08-02 06:05:37'),
(3, 'ahmeda', 'alia', '8700', 'tree@grrr.com', 4, '2026-08-02', '2026-08-02 06:12:21'),
(4, 'Hani', 'Rafique', '03343397898', 'teacher@gmail.com', 9, '2026-08-02', '2026-08-02 06:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('teacher','admin') NOT NULL DEFAULT 'teacher'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`) VALUES
(1, 'Teacher', 'teacher@example.com', '$2y$10$ERGQojvPGNX3WiHiLhx5K.1v1R4sKCIk3ndba2VZrBB3caFh.Lk5O', 'teacher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1993;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
