-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 12:23 PM
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
-- Database: `college_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `faculty_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `title`, `description`, `subject`, `due_date`, `created_at`, `faculty_id`) VALUES
(10, 'Hindi', 'भारत के नए राष्ट्रपति का नाम क्या है?', 'Hindi', '2025-12-20', '2025-12-04 20:42:31', 1),
(11, 'hindi', 'भारत के नए राष्ट्रपति का नाम क्या है?', 'Hindi', '2026-02-28', '2025-12-16 16:55:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `date`, `status`) VALUES
(14, 4, '2025-12-06', 'Present'),
(15, 6, '2025-12-06', 'Present'),
(16, 4, '2025-12-17', 'Present'),
(17, 6, '2025-12-17', 'Present'),
(18, 8, '2025-12-17', 'Present'),
(19, 10, '2025-12-17', 'Present'),
(20, 11, '2025-12-17', 'Present'),
(21, 12, '2025-12-17', 'Present'),
(22, 4, '2025-12-19', 'Present'),
(23, 6, '2025-12-19', 'Absent'),
(24, 8, '2025-12-19', 'Present'),
(25, 10, '2025-12-19', 'Present'),
(26, 11, '2025-12-19', 'Present'),
(27, 12, '2025-12-19', 'Present'),
(28, 4, '2025-12-20', 'Present'),
(29, 6, '2025-12-20', 'Present'),
(30, 8, '2025-12-20', 'Present'),
(31, 10, '2025-12-20', 'Present'),
(32, 11, '2025-12-20', 'Present'),
(33, 12, '2025-12-20', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `available` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `quantity`, `available`, `created_at`) VALUES
(2, 'EM - 3', 'Dr.ajay ', 'math', 100, 107, '2025-12-04 11:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `dept_id`, `course_code`) VALUES
(1, 'B.Tech C.S.E', 1, '401'),
(2, 'B.Tech Ag', 4, '402');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `dept_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `dept_name`, `dept_code`) VALUES
(1, 'CSE', '501'),
(2, 'BBA', '401'),
(3, 'MBA', '601'),
(4, 'Ag', '2');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `faculty_name` varchar(100) NOT NULL,
  `faculty_id` varchar(50) NOT NULL,
  `faculty_email` varchar(100) NOT NULL,
  `faculty_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `faculty_name`, `faculty_id`, `faculty_email`, `faculty_password`, `created_at`, `status`) VALUES
(1, 'Prnav mishra', '101', 'prnav123@gmail.com', '$2y$10$jAYVY3hZisDpPFefBzR7Qu9U4GaCb3sXiRhMG6eui27EeUuUJXCoi', '2025-09-18 09:37:05', 1),
(2, 'Ajit Kumar', '102', 'ajitkumar@gmail.com', '$2y$10$48TMXiPyYrlCC54PMky6TuBeqb8GBlDNqKOeBQ6I4HiqME9U7cw02', '2025-09-18 16:53:39', 1),
(3, 'Ajeet Kumar', '103', 'ajeetkumar@gmail.com', '$2y$10$EkokUJR4KZKDENmkQVAEJuz3IoIT7oGKom80NWyrNVkNYIKViRO1u', '2025-09-19 04:24:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hostel_attendance`
--

CREATE TABLE `hostel_attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent') DEFAULT 'Absent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel_attendance`
--

INSERT INTO `hostel_attendance` (`id`, `student_id`, `attendance_date`, `status`) VALUES
(1, 4, '2025-12-21', 'Present'),
(2, 8, '2025-12-21', 'Present'),
(3, 10, '2025-12-21', 'Present'),
(4, 12, '2025-12-21', 'Present'),
(5, 6, '2025-12-21', 'Present'),
(6, 11, '2025-12-21', 'Present'),
(7, 12, '2025-12-22', 'Present'),
(8, 11, '2025-12-22', 'Present'),
(9, 10, '2025-12-22', 'Present'),
(10, 8, '2025-12-22', 'Present'),
(11, 6, '2025-12-22', 'Present'),
(12, 4, '2025-12-22', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `issue_books`
--

CREATE TABLE `issue_books` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `fine` int(11) DEFAULT 0,
  `return_status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_books`
--

INSERT INTO `issue_books` (`id`, `book_id`, `student_id`, `issue_date`, `return_date`, `fine`, `return_status`) VALUES
(1, 2, 4, '2025-12-04', '2025-12-04', 0, 1),
(2, 2, 4, '2025-12-04', '2025-12-04', 0, 1),
(3, 2, 6, '2025-12-04', '2025-12-04', 0, 1),
(4, 2, 6, '2025-12-04', '2025-12-18', 70, 1),
(6, 2, 8, '2025-12-18', NULL, 0, 0),
(7, 2, 4, '2025-12-18', '2025-12-18', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `library_staff`
--

CREATE TABLE `library_staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library_staff`
--

INSERT INTO `library_staff` (`id`, `name`, `username`, `password`, `created_at`) VALUES
(1, 'AJIT KUMAR', 'Ajit@123', '$2y$10$S4eeBnJPWHn8ZeR7o7MwEe2LvlrWBDmjhoJ/hFcSFaNay1Bi9bFLG', '2025-12-18 21:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT 'Admin',
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `message`, `created_at`, `created_by`, `content`) VALUES
(3, 'Holiday 2', 'enjoy semester break', '2025-12-02 12:00:07', 'Admin', '2 week semester break');

-- --------------------------------------------------------

--
-- Table structure for table `no_dues_requests`
--

CREATE TABLE `no_dues_requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `account_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `account_remark` varchar(255) DEFAULT '',
  `hod_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `hod_remark` varchar(255) DEFAULT '',
  `transport_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `transport_remark` varchar(255) DEFAULT '',
  `hostel_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `hostel_remark` varchar(255) DEFAULT '',
  `library_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `library_remark` varchar(255) DEFAULT '',
  `registrar_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `registrar_remark` varchar(255) DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `no_dues_requests`
--

INSERT INTO `no_dues_requests` (`id`, `student_id`, `account_status`, `account_remark`, `hod_status`, `hod_remark`, `transport_status`, `transport_remark`, `hostel_status`, `hostel_remark`, `library_status`, `library_remark`, `registrar_status`, `registrar_remark`, `created_at`) VALUES
(1, 4, 'Rejected', '1500000 Due', 'Approved', 'OK', 'Pending', '', 'Approved', 'OK', 'Approved', 'OK', 'Rejected', '1500000 Due', '2025-12-17 00:40:25'),
(5, 8, 'Approved', 'OK', 'Approved', 'ok', 'Pending', '', 'Approved', 'OK', 'Approved', 'OK', 'Approved', 'Ok', '2025-12-18 20:25:13'),
(8, 11, 'Pending', '', 'Pending', '', 'Pending', '', 'Rejected', '1000 fine', 'Pending', '', 'Approved', 'ok', '2025-12-21 03:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `status` enum('Pass','Fail') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `subject`, `marks`, `status`) VALUES
(1, 4, 'Hindi', 98, 'Pass');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `student_email` varchar(100) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `student_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_name`, `student_id`, `student_email`, `course`, `department`, `student_password`, `created_at`, `profile_pic`, `status`) VALUES
(4, 'Aditya Raj', '53', 'aditya123@gmail.com', 'B.Tech Ag', 'Ag', '$2y$10$puL8om7rKdoyymFpbvmGR.UYm7lsF0A/a.HQesKwxDfI/S7i2RrUC', '2025-09-19 05:26:39', '1765902788_adityapic.jpg', 1),
(6, 'Prem Kumar', '54', 'premchandra@gmail.com', 'computer science', 'CSE', '$2y$10$dPE1RC6rQKstLj9.hqFAP.7Z/Y2bb7F7PGt2nAF5GZdBNcAGvkT4e', '2025-12-04 11:52:21', NULL, 0),
(8, 'Ajit Kumar', '41', 'ajitkumar09112005@gmail.com', 'computer science', 'CSE', '$2y$10$rHLl8pez9QHZ3RXrtHEcKu0ZsX7sXDWSq1XGdzFJOxwkiFZNZ2yha', '2025-12-16 14:58:03', '1765902712_Ak.jpg', 1),
(10, 'jay jha', '102', 'jayjha123@gmail.com', 'computer science', 'CSE', '$2y$10$JrDMY1.7akqwvTbhtd6GzuFt843f5wKnYaNs2sLE11FnzHyz8SYtq', '2025-12-16 16:19:00', NULL, 1),
(11, 'Premchandra', '11', 'pk123@gmail.com', 'computer science', 'CSE', '$2y$10$ILDwfpaQlWzIzUyZSHEUwOyGPe1.Dy2XI6VePDGulWfb.DksY5Nwy', '2025-12-16 18:01:58', NULL, 1),
(12, 'Kumar', '42', 'kumar123@gmail.com', 'B.Tech Ag', 'Ag', '$2y$10$O2GvekDwNiEp.qYogP6aNu59sc/.aMAcUSPardK6mYi4KLD906pE.', '2025-12-16 18:58:13', '1765911493_ajit_pic.jpg', 0),
(13, 'aj', '107', 'aj@gmail.com', 'B.Tech C.S.E', 'CSE', '$2y$10$D04gfeFkd3QQkHG3Ko6LguYWcM1RqN05/W.ospRe0wKWqwpDFdunG', '2026-02-04 11:11:08', '1770203596_WIN_20250322_14_26_56_Pro.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_profile_pics`
--

CREATE TABLE `student_profile_pics` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submitted_assignments`
--

CREATE TABLE `submitted_assignments` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submitted_assignments`
--

INSERT INTO `submitted_assignments` (`id`, `assignment_id`, `student_id`, `file_path`, `submitted_at`) VALUES
(4, 10, 6, 'uploads/1764881019_Experiment-3.pdf', '2025-12-04 20:43:39'),
(5, 10, 4, '1765894805_FLAT MICRO.pdf', '2025-12-16 14:20:05'),
(6, 10, 4, '1765904184_Ajit Kumar aadhar card_.pdf', '2025-12-16 16:56:24'),
(7, 10, 4, '1765962173_aaditya_incom_rec.pdf', '2025-12-17 09:02:53'),
(8, 10, 4, '1765962348_aaditya_incom_rec.pdf', '2025-12-17 09:05:48'),
(9, 10, 8, '1766087186_Ajit Kumar aadhar card_.pdf', '2025-12-18 19:46:26');

-- --------------------------------------------------------



CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','accounts','hostel','transport') NOT NULL DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'Ajit@123', 'ajitkumar09112005@gmail.com', '$2y$10$yGNLtaicrzZ5qPnAOJ5AAu4zj.95/M6EvUaWQ43hgklUjzE8a7zQC', 'admin', 'active'),
(2, 'PREMCHANDRA KUMAR', 'prem123@gmail.com', '$2y$10$8YDPgZiOSABF4BGBo8U4AOp7NaLNNPJ9YAbFMc4dT4BCtE76iO.hK', 'hostel', 'active'),
(4, 'Rahul', 'rahul123@gmail.com', '$2y$10$lM0ij82NDzGGnca3SGXbTOPZfC2l3seXAmqMr/1he5i30LdGhkVO.', 'accounts', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dept_name` (`dept_name`),
  ADD UNIQUE KEY `dept_code` (`dept_code`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculty_id` (`faculty_id`),
  ADD UNIQUE KEY `faculty_email` (`faculty_email`);

--
-- Indexes for table `hostel_attendance`
--
ALTER TABLE `hostel_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `issue_books`
--
ALTER TABLE `issue_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `library_staff`
--
ALTER TABLE `library_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no_dues_requests`
--
ALTER TABLE `no_dues_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `student_email` (`student_email`);

--
-- Indexes for table `student_profile_pics`
--
ALTER TABLE `student_profile_pics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_pic` (`student_id`);

--
-- Indexes for table `submitted_assignments`
--
ALTER TABLE `submitted_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hostel_attendance`
--
ALTER TABLE `hostel_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `issue_books`
--
ALTER TABLE `issue_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `library_staff`
--
ALTER TABLE `library_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `no_dues_requests`
--
ALTER TABLE `no_dues_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `student_profile_pics`
--
ALTER TABLE `student_profile_pics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submitted_assignments`
--
ALTER TABLE `submitted_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hostel_attendance`
--
ALTER TABLE `hostel_attendance`
  ADD CONSTRAINT `hostel_attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `issue_books`
--
ALTER TABLE `issue_books`
  ADD CONSTRAINT `issue_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `issue_books_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_profile_pics`
--
ALTER TABLE `student_profile_pics`
  ADD CONSTRAINT `fk_student_pic` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submitted_assignments`
--
ALTER TABLE `submitted_assignments`
  ADD CONSTRAINT `submitted_assignments_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submitted_assignments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
