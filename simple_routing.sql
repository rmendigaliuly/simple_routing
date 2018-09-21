-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/

--
-- Database: `first_procedural_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `modules_with_rank_0`
--

CREATE TABLE `modules_with_rank_0` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(110) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module` (`module`)
);

--
-- Dumping data for table `modules_with_rank_0`
--

INSERT INTO `modules_with_rank_0` (`module`) VALUES
('root'),
('admin'),
('login');

-- --------------------------------------------------------

--
-- Table structure for table `modules_with_rank_1`
--

CREATE TABLE `modules_with_rank_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(110) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module` (`module`),
  KEY `parent_id` (`parent_id`)
);

--
-- Dumping data for table `modules_with_rank_1`
--

INSERT INTO `modules_with_rank_1` (`module`, `parent_id`) VALUES
('manage_content', 2),
('manage_users', 2);

-- --------------------------------------------------------

--
-- Table structure for table `modules_with_rank_2`
--

CREATE TABLE `modules_with_rank_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(110) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module` (`module`),
  KEY `parent_id` (`parent_id`)
);

--
-- Dumping data for table `modules_with_rank_2`
--

INSERT INTO `modules_with_rank_2` (`module`, `parent_id`) VALUES
('add', 2);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(50) NOT NULL,
  `module_id` int(11) NOT NULL,
  `route_owner_id` int(11) NOT NULL,
  `module_rank` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modules_id` (`module_id`),
  KEY `route_owner_id` (`route_owner_id`)
);

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route`, `module_id`, `route_owner_id`, `module_rank`) VALUES
('edit/post', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `registration_date` datetime NOT NULL,
  `email` varchar(100) NOT NULL,
  `blocked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`)
);

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `number` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
);

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`role`, `number`) VALUES
('admin', NULL);