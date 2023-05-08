-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2023 at 04:07 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatbot_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `date_added`, `date_updated`) VALUES
(1, 'admin ', 'issat', 'admin_ISSAT', '21361a6d47d024d6ec5362bd568b7954', 'uploads/1682990460_Untitled-1.png', NULL, '2021-01-20 14:02:37', '2023-05-02 02:21:42');

-- --------------------------------------------------------

--
-- Table structure for table `disponibilite_prof2`
--

CREATE TABLE `disponibilite_prof2` (
  `id` int(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `disponibilite` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disponibilite_prof2`
--

INSERT INTO `disponibilite_prof2` (`id`, `name`, `disponibilite`) VALUES
(24, 'yosri selmi', 'prof anglais dispon'),
(28, 'SADSA', 'ASSA'),
(30, 'SADSAyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy', 'ASSAyyyyyyyyyyyyyyyyyyyyyyy');

-- --------------------------------------------------------

--
-- Table structure for table `frequent_asks`
--

CREATE TABLE `frequent_asks` (
  `id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `frequent_asks`
--

INSERT INTO `frequent_asks` (`id`, `question_id`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 4),
(12, 1),
(13, 6),
(14, 7),
(15, 7),
(16, 1),
(17, 7),
(18, 8),
(19, 7),
(20, 7),
(21, 7),
(22, 6),
(23, 7),
(24, 7),
(25, 7),
(26, 7),
(27, 7),
(28, 7),
(29, 9),
(30, 1),
(31, 8),
(32, 2),
(33, 7),
(34, 14),
(35, 9),
(36, 9),
(37, 9),
(38, 1),
(39, 4),
(40, 6),
(41, 7),
(42, 9);

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `id` int(11) NOT NULL,
  `niv_sp` varchar(500) NOT NULL,
  `fullname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `niv_sp`, `fullname`) VALUES
(1, 'isi', 'med yosri selmii selmi'),
(2, 'isi 1ere', 'moez');

-- --------------------------------------------------------

--
-- Table structure for table `name_prof`
--

CREATE TABLE `name_prof` (
  `id` int(30) NOT NULL,
  `prof_name` text DEFAULT NULL,
  `name_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `name_prof`
--

INSERT INTO `name_prof` (`id`, `prof_name`, `name_id`) VALUES
(2, 'yosri', 2),
(5, 'sasa', 7);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `eventn` varchar(50) NOT NULL,
  `descr` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `eventn`, `descr`) VALUES
(4, 'formation online public speaking', 'google meet 21h , 21 avril 2023'),
(6, 'مهرجان الطلابي-التونسي الجزائري', 'الدورة الأولى تحت شعار «ISSAT تجمعنا»\r\n27-26-25-24 أفريل 2023');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Simple ChatBot Application'),
(4, 'intro', 'Hi! I\'m John, a ChatBot of this application. How can I help you?'),
(6, 'short_name', 'ChatBot'),
(10, 'no_result', 'I am sorry. I can\'t understand your question. Please rephrase your question and make sure it is related to this site. Thank you :)'),
(11, 'logo', 'uploads/1620181980_bot2.jpg'),
(12, 'bot_avatar', 'uploads/bot_avatar.jpg'),
(13, 'user_avatar', 'uploads/user_avatar.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `unanswered`
--

CREATE TABLE `unanswered` (
  `id` int(30) NOT NULL,
  `question` text DEFAULT NULL,
  `no_asks` int(30) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unanswered`
--

INSERT INTO `unanswered` (`id`, `question`, `no_asks`) VALUES
(1, 'Que se passe-t-il à l\'université cette semaine?', 5),
(2, 'saasas', 1),
(3, 'mercii', 1),
(5, 'Quelles sont les dernières nouvelles de l\'université?', 19),
(7, 'disponibilite yosri selmi', 9),
(8, 'ssalut', 1),
(9, 'disponibilte yosri selmi', 1),
(13, 'salut', 5),
(14, 'hi', 1),
(15, 'au revoir', 1),
(16, 'Quels sont les noms des clubs ?', 2),
(17, 'Quels sont les nom des clubs ?', 1),
(18, 'dasdasdas', 1),
(19, 'rgawsgjhaerugiwr', 1),
(20, 'eopwfjkopweqkfjopwekfew', 1),
(21, 'opkdqopkqwopdkqwopkdqw', 1),
(22, 'ssaluuut', 1),
(23, 'diqwjidfjqwofjoeiqwfew', 1),
(24, 'opetijberiojhgiouerdhfnbgiujkdew', 1),
(25, 'opdfkqw0kdfopqiejkf9uhwe', 1),
(26, 'flkiofjwejfojwguioergonjibn   jfds', 1),
(27, 'saaaaluuuuttt', 1),
(28, 'qwedqwdqwdqw', 1),
(29, 'oui', 12),
(31, 'oui je peux', 2),
(32, 'hjgyu', 1),
(33, 'assaa', 1),
(34, 'uiyog6y7u8ig', 1),
(35, 'jhkvbghj', 1),
(36, 'saliut', 1),
(37, 'saltu', 1),
(38, 'sdcsa', 1),
(39, 'salit', 1),
(40, 'slaut', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `userID` text DEFAULT NULL,
  `n_login` int(30) NOT NULL DEFAULT 0,
  `student_name` varchar(255) DEFAULT NULL,
  `classe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userID`, `n_login`, `student_name`, `classe`) VALUES
(13, '192.168.1.21679752000776', 4, NULL, NULL),
(14, '192.168.1.21679755259210', 2, NULL, NULL),
(15, '192.168.1.21679927071524', 0, NULL, NULL),
(16, '192.168.1.21679927278715', 0, NULL, NULL),
(23, '02:34:CE:A0:C0:11_2c404958ba4858bf_1679927642759', 0, NULL, NULL),
(24, '192.168.1.21679928479091', 0, NULL, NULL),
(25, '02:34:CE:A0:C0:11_2c404958ba4858bf_1679928865178', 0, NULL, NULL),
(26, '192.168.1.21679929532891', 0, NULL, NULL),
(27, '02:00:00:00:01:00_73e4140fe271441a_1679534448704', 0, NULL, NULL),
(28, '192.168.137.1801680518182031', 0, NULL, NULL),
(29, '192.168.1.31681389057083', 0, NULL, NULL),
(30, '192.168.1.31681390591590', 0, NULL, NULL),
(31, '127.0.0.11681469383026', 0, NULL, NULL),
(33, '192.168.137.11681817245021', 0, NULL, NULL),
(34, '192.168.100.1411682168138584', 0, NULL, NULL),
(36, '192.168.137.71683113370864', 0, NULL, NULL),
(37, '172.16.1.11683283914697', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vacation`
--

CREATE TABLE `vacation` (
  `id` int(11) NOT NULL,
  `vacation_name` varchar(50) NOT NULL,
  `vacation_date` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disponibilite_prof2`
--
ALTER TABLE `disponibilite_prof2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frequent_asks`
--
ALTER TABLE `frequent_asks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `name_prof`
--
ALTER TABLE `name_prof`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unanswered`
--
ALTER TABLE `unanswered`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vacation`
--
ALTER TABLE `vacation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `disponibilite_prof2`
--
ALTER TABLE `disponibilite_prof2`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `frequent_asks`
--
ALTER TABLE `frequent_asks`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `name_prof`
--
ALTER TABLE `name_prof`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `unanswered`
--
ALTER TABLE `unanswered`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `vacation`
--
ALTER TABLE `vacation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
