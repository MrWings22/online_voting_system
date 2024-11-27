--
-- Database: `voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `admin_id` int(11) NOT NULL,
  `ad_username` varchar(100) NOT NULL DEFAULT current_timestamp(),
  `ad_password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `candidate_id` int(11) NOT NULL,
  `admission_no` varchar(20) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `year` int(11) NOT NULL,
  `batch` varchar(10) DEFAULT NULL,
  `department` varchar(50) NOT NULL,
  `backpapers` enum('Yes','No') DEFAULT NULL,
  `no_backpapers` varchar(50) NOT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `signature` enum('Yes','No') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` int(11) NOT NULL,
  `description` text NOT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `votes` varchar(50) DEFAULT NULL,
  `election` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `election_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(8) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `batch` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `year` varchar(4) DEFAULT NULL,
  `voted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `vote_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `candidate_id` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `position` enum('Main Union','Department Representative','Class Representative') DEFAULT NULL,
  `election_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `ad_username` (`ad_username`),
  ADD UNIQUE KEY `ad_password` (`ad_password`);

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`candidate_id`),
  ADD KEY `fk_username` (`username`),
  ADD KEY `fk_vote_id` (`vote_id`),
  ADD KEY `fk_election` (`election`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`election_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `password` (`password`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `election_id` (`election_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `candidate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `election_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `fk_election` FOREIGN KEY (`election`) REFERENCES `elections` (`election_id`),
  ADD CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `fk_vote_id` FOREIGN KEY (`vote_id`) REFERENCES `votes` (`vote_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `election_id` FOREIGN KEY (`election_id`) REFERENCES `elections` (`election_id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
