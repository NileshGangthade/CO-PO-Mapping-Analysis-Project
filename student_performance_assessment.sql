-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 04, 2024 at 04:47 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_performance_assessment`
--

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_classes`
--

CREATE TABLE `enrolled_classes` (
  `ID` bigint(20) NOT NULL,
  `CourseID` bigint(20) NOT NULL,
  `SuballocationID` bigint(20) NOT NULL,
  `SubID` bigint(20) NOT NULL,
  `Year` int(20) NOT NULL,
  `Division` varchar(20) NOT NULL,
  `Sem` varchar(20) NOT NULL,
  `Exam` varchar(20) NOT NULL,
  `TableName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enrolled_classes`
--

INSERT INTO `enrolled_classes` (`ID`, `CourseID`, `SuballocationID`, `SubID`, `Year`, `Division`, `Sem`, `Exam`, `TableName`) VALUES
(42, 2, 9, 2, 1, 'A', 'SEM_I', 'UT1', '42_OS_9'),
(43, 2, 9, 2, 1, 'A', 'SEM_I', 'UT1', '43_OS_9'),
(44, 2, 9, 2, 1, 'A', 'SEM_I', 'UT1', '44_OS_9'),
(45, 2, 9, 2, 4, 'G', 'SEM_II', 'Assign4', '45_OS_9'),
(46, 2, 16, 8, 4, 'A', 'SEM_I', 'Prelim', '46_AI_16'),
(47, 2, 16, 8, 1, 'A', 'SEM_I', 'UT1', '47_AI_16');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(200) DEFAULT NULL,
  `UserName` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'SuperAdmin', 'admin', 5689784592, 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2023-05-25 11:58:35');

-- --------------------------------------------------------

--
-- Table structure for table `tblcourse`
--

CREATE TABLE `tblcourse` (
  `ID` int(10) NOT NULL,
  `BranchName` varchar(200) DEFAULT NULL,
  `CourseName` varchar(200) DEFAULT NULL,
  `Years` int(20) NOT NULL DEFAULT '1',
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcourse`
--

INSERT INTO `tblcourse` (`ID`, `BranchName`, `CourseName`, `Years`, `CreationDate`) VALUES
(2, 'Computer Science & Engineering', 'B.Tech', 4, '2024-02-29 14:10:47'),
(3, 'Electrical Engineering', 'B.Tech', 4, '2024-02-29 14:10:52'),
(5, 'ENTC', 'B.Tech', 4, '2024-02-29 14:10:55'),
(6, 'MECH', 'B.Tech', 4, '2024-02-29 14:10:59'),
(7, 'Civil', 'B.Tech', 4, '2024-02-29 14:11:37'),
(8, 'First Year', 'B.Tech', 4, '2024-02-29 14:11:39'),
(9, '   ', 'None', 4, '2024-02-29 14:11:42'),
(10, 'IT', 'B.Tech', 4, '2024-02-29 14:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `tblsuballocation`
--

CREATE TABLE `tblsuballocation` (
  `ID` int(5) NOT NULL,
  `CourseID` int(5) DEFAULT NULL,
  `Teacherempid` bigint(100) NOT NULL,
  `Subid` int(5) DEFAULT NULL,
  `AllocationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `academic_year` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsuballocation`
--

INSERT INTO `tblsuballocation` (`ID`, `CourseID`, `Teacherempid`, `Subid`, `AllocationDate`, `academic_year`) VALUES
(15, 2, 20, 8, '2024-03-03 17:28:54', '2023-24'),
(16, 2, 18, 8, '2024-03-03 17:31:34', '2023-24'),
(17, 2, 18, 2, '2024-03-03 17:31:51', '2023-24'),
(18, 2, 20, 2, '2024-03-03 17:32:01', '2023-24');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubject`
--

CREATE TABLE `tblsubject` (
  `ID` int(5) NOT NULL,
  `CourseID` int(5) DEFAULT NULL,
  `SubjectFullname` varchar(200) DEFAULT NULL,
  `SubjectShortname` varchar(200) DEFAULT NULL,
  `SubjectCode` varchar(200) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblsubject`
--

INSERT INTO `tblsubject` (`ID`, `CourseID`, `SubjectFullname`, `SubjectShortname`, `SubjectCode`, `CreationDate`) VALUES
(1, 1, 'Mathematics', 'Math', 'Math101', '2023-05-10 04:45:51'),
(2, 2, 'Operating System', 'OS', 'OS101', '2023-05-10 05:27:04'),
(3, 1, 'Digital Electronics', 'DE', 'DE101', '2023-05-10 05:28:05'),
(4, 3, 'Computer Communication Network', 'CCN', 'CCN101', '2023-05-10 05:28:55'),
(5, 3, 'Management Information Systems', 'MIS', 'MIS', '2023-05-10 05:29:25'),
(6, 4, 'Introduction to Microprocessor', 'ITM', 'ITM101', '2023-05-10 05:30:18'),
(7, 3, 'Relational Database Management System', 'RDBMS', 'RDBMS101', '2023-05-10 05:31:44'),
(8, 2, 'Artificial Intelligence', 'AI', 'AI102', '2023-05-10 05:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher`
--

CREATE TABLE `tblteacher` (
  `ID` int(10) NOT NULL,
  `EmpID` varchar(50) DEFAULT NULL,
  `FirstName` varchar(200) DEFAULT NULL,
  `LastName` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Gender` varchar(200) DEFAULT NULL,
  `Dob` varchar(200) DEFAULT NULL,
  `CourseID` int(5) DEFAULT NULL,
  `Religion` varchar(200) DEFAULT NULL,
  `Address` mediumtext,
  `Password` varchar(200) DEFAULT NULL,
  `ProfilePic` varchar(200) DEFAULT NULL,
  `JoiningDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblteacher`
--

INSERT INTO `tblteacher` (`ID`, `EmpID`, `FirstName`, `LastName`, `MobileNumber`, `Email`, `Gender`, `Dob`, `CourseID`, `Religion`, `Address`, `Password`, `ProfilePic`, `JoiningDate`) VALUES
(1, 'Emp101', 'Test', 'Sample', 8956231478, 'kaushal@gmail.com', 'Male', '1984-01-05', 1, 'Hindu', 'J-125, Mohan Road Jakirpur Merrut', '202cb962ac59075b964b07152d234b70', '779b7513263ef185b6d094af290ef5401595083511.png', '2023-05-25 01:04:34'),
(2, 'Emp102', 'Sarita', 'Pandey', 4564877987, 'sar@gmail.com', 'Female', '1990-01-09', 2, 'Hindu', 'K-980', '202cb962ac59075b964b07152d234b70', 'e76de47f621d84adbab3266e3239baee1594385101.png', '2023-05-13 05:22:14'),
(3, 'Emp103', 'Test', 'Sample', 6544654654, 'test@gmail.com', 'Male', '1990-07-05', 3, 'Hindu', 'B-234 Nehru Nagar New Delhi', '202cb962ac59075b964b07152d234b70', '779b7513263ef185b6d094af290ef5401595247971.png', '2023-05-20 12:26:11'),
(4, 'EMP12345', 'Anuj', 'Kumar', 1234567890, 'ak@gmail.com', 'Male', '2019-04-02', 1, 'Indian', 'New Delhi India 110101', 'f925916e2754e5e03f75dd58a5733251', 'ea8f8a4ef2a9dbbb375c6f9adf0d35501684818674.jpg', '2023-05-23 05:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `teachers_data`
--

CREATE TABLE `teachers_data` (
  `ID` bigint(200) NOT NULL,
  `EmpID` varchar(50) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `MobileNumber` int(10) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `Dob` date DEFAULT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `ProfilePic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers_data`
--

INSERT INTO `teachers_data` (`ID`, `EmpID`, `FirstName`, `LastName`, `MobileNumber`, `Email`, `user_role`, `Dob`, `CourseID`, `ProfilePic`) VALUES
(5, 'HOD-CSE-1', 'CSE', 'HOD-1', 1234554321, 'cse.hod.1@gmail.com', 'HOD', '2024-01-01', 2, '16eef79ec21159474b28199dc678dd861709180491.jpg'),
(7, 'MECH-HOD-1', 'MECH', 'HOD', 1919191919, 'mech.hod@gmail.com', 'HOD', '2024-01-01', 6, 'e76de47f621d84adbab3266e3239baee1709101319.png'),
(8, 'CIVIL-HOD-1', 'CIVIL', 'HOD', 1010101013, 'civil.hod@gmail.com', 'HOD', '2024-01-01', 7, 'e76de47f621d84adbab3266e3239baee1709101383.png'),
(9, 'ELE-HOD-1', 'Electronics', 'HOD', 2345353, 'electronics.hod@gmail.com', 'HOD', '2024-01-01', 3, '81355fcbc8b564a8dcf604cf0333267b1709124799.png'),
(10, 'FY-HOD-1', 'FY', 'HOD', 36479123, 'first_year.hod@gmail.com', 'HOD', '2024-01-01', 8, 'e76de47f621d84adbab3266e3239baee1709101559.png'),
(15, 'ENTC-HOD-1', 'ENTC', 'HOD', 3789354, 'entc.hod@gmail.com', 'HOD', '2024-01-01', 5, 'e76de47f621d84adbab3266e3239baee1709134799.png'),
(16, 'admin-01', 'admin', '  ', 0, 'admin@gmail.com', 'Admin', '2024-01-01', 9, 'e76de47f621d84adbab3266e3239baee1709134837.png'),
(17, 'Principal-1', 'SKNSCOE', 'Principal-01', 123456753, 'sknscoe.principal.1@gmail.com', 'Principal', '2024-01-01', 9, 'e76de47f621d84adbab3266e3239baee1709181085.png'),
(18, 'CSE-T01', 'CSE-1', 'Teacher', 23453, 'cse.teacher@gmail.com', 'Professor', '2024-01-01', 2, 'e76de47f621d84adbab3266e3239baee1709135611.png'),
(19, 'ENTC-T01', 'ENTC-T', 'Teacher', 3574566, 'entc.teacher@gmail.com', 'Professor', '2024-01-01', 5, 'e76de47f621d84adbab3266e3239baee1709135668.png'),
(20, 'CSET-01', 'CSETeacher', '1', 456467, 'cse.teacher.1@gmail.con', 'Professor', '2024-01-01', 2, '1d97f48dc2cc419272983eb571033d271709200069.png');

-- --------------------------------------------------------

--
-- Table structure for table `users_login`
--

CREATE TABLE `users_login` (
  `ID` bigint(200) NOT NULL,
  `EmpID` varchar(50) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Course` varchar(255) DEFAULT NULL,
  `user_role` varchar(50) NOT NULL,
  `ProfilePic` varchar(255) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_login`
--

INSERT INTO `users_login` (`ID`, `EmpID`, `FirstName`, `LastName`, `Email`, `Course`, `user_role`, `ProfilePic`, `Password`, `otp`, `otp_expiry`) VALUES
(5, 'HOD-CSE-1', 'CSE', 'HOD-1', 'cse.hod.1@gmail.com', '2', 'HOD', 'e76de47f621d84adbab3266e3239baee1709101144.png', '$2y$10$1oHmSNBnAcLVPPqO5HjjdeGyAL5AfoppMaIIJHAVFVFX/Twmu8ITu', '564121', '2024-02-29 11:03:21'),
(7, 'MECH-HOD-1', 'MECH', 'HOD', 'mech.hod@gmail.com', '6', 'HOD', 'e76de47f621d84adbab3266e3239baee1709101319.png', '$2y$10$uuY9ZkzUS3FUcRD5xH6MAOwRte9.ah0XIzHs859GEGbWY/lDzz7sS', NULL, NULL),
(8, 'CIVIL-HOD-1', 'CIVIL', 'HOD', 'civil.hod@gmail.com', '7', 'HOD', 'e76de47f621d84adbab3266e3239baee1709101383.png', '$2y$10$uLWFA0cC7FewsLadlfyjzOhnYh3HeMoCamzyTKMyJ2yc77.91nP1G', NULL, NULL),
(9, 'ELE-HOD-1', 'Electronics', 'HOD', 'electronics.hod@gmail.com', '3', 'HOD', 'e76de47f621d84adbab3266e3239baee1709101489.png', '$2y$10$LT0fz2SVU/F4FrXQ.Dbtc.CR5GezKySFD5N7JfWWZKF.Uc24oU0h6', NULL, NULL),
(10, 'FY-HOD-1', 'FY', 'HOD', 'first_year.hod@gmail.com', '8', 'HOD', 'e76de47f621d84adbab3266e3239baee1709101559.png', '$2y$10$JRts4SCkq1puXpmDdHc7o.vgMCDt4EeGAFHhEshpssxr3RXgNnSxu', NULL, NULL),
(15, 'ENTC-HOD-1', 'ENTC', 'HOD', 'entc.hod@gmail.com', '5', 'HOD', 'e76de47f621d84adbab3266e3239baee1709134799.png', '$2y$10$K9R9t9ldNzGNXknu4OUB/.zHcJVqETpiR7.vRJj/1RblMnXsgTR/K', NULL, NULL),
(16, 'admin-01', 'admin', '  ', 'admin@gmail.com', '9', 'Admin', 'e76de47f621d84adbab3266e3239baee1709134837.png', '$2y$10$lDcHZPwgf43EMDmBZ14WWOQ4z7yHXNSyJj.AYoaTi.L5I0jHz7b.i', NULL, NULL),
(17, 'Principal-1', 'SKNSCOE', 'Principal-01', 'sknscoe.principal.1@gmail.com', '9', 'Principal', 'e76de47f621d84adbab3266e3239baee1709135059.png', '$2y$10$rwu550CAMxFUsTUSUQmstu7QrZ5lEhDIZ25qg5JcQnPhcghSGANoa', NULL, NULL),
(18, 'CSE-T01', 'CSE-1', 'Teacher', 'cse.teacher@gmail.com', '2', 'Professor', 'e76de47f621d84adbab3266e3239baee1709135611.png', '$2y$10$GS8fDOBVrvwPpClIX/rPYeZJ.YVoVZezo3FQvXHlbqHm27OH/d0HC', NULL, NULL),
(19, 'ENTC-T01', 'ENTC-T', 'Teacher', 'entc.teacher@gmail.com', '5', 'Professor', 'e76de47f621d84adbab3266e3239baee1709135668.png', '$2y$10$urG6irAsThyg7tepdx13OudDD3UDLVMvuGyLPaP0VnckSQxON3ZJG', NULL, NULL),
(20, 'CSET-01', 'CSETeacher', '1', 'cse.teacher.1@gmail.con', '2', 'Professor', '1d97f48dc2cc419272983eb571033d271709200069.png', '$2y$10$y8dhgM2Pb9TiVkKf8cI29.mlxAfO.a0bBwZzbQwvk2dvMumSBBKr2', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrolled_classes`
--
ALTER TABLE `enrolled_classes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcourse`
--
ALTER TABLE `tblcourse`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblsuballocation`
--
ALTER TABLE `tblsuballocation`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblsubject`
--
ALTER TABLE `tblsubject`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `teachers_data`
--
ALTER TABLE `teachers_data`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EmpID` (`EmpID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `fk_course` (`CourseID`);

--
-- Indexes for table `users_login`
--
ALTER TABLE `users_login`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EmpID` (`EmpID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrolled_classes`
--
ALTER TABLE `enrolled_classes`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcourse`
--
ALTER TABLE `tblcourse`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblsuballocation`
--
ALTER TABLE `tblsuballocation`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblsubject`
--
ALTER TABLE `tblsubject`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teachers_data`
--
ALTER TABLE `teachers_data`
  MODIFY `ID` bigint(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users_login`
--
ALTER TABLE `users_login`
  MODIFY `ID` bigint(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teachers_data`
--
ALTER TABLE `teachers_data`
  ADD CONSTRAINT `fk_course` FOREIGN KEY (`CourseID`) REFERENCES `tblcourse` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
