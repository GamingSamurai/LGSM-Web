-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 17, 2016 at 08:15 PM
-- Server version: 5.7.13-0ubuntu0.16.04.2
-- PHP Version: 7.0.8-0ubuntu0.16.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `git`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `cat_id` int(8) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_description` mediumtext NOT NULL,
  `cat_tooltip` varchar(64) NOT NULL,
  `isgroup` tinyint(1) NOT NULL,
  `groupid` int(11) NOT NULL,
  `area` int(11) NOT NULL COMMENT 'used for checking template in admin',
  `disp_order` int(11) NOT NULL,
  `icon` varchar(120) NOT NULL,
  `tab_display` text NOT NULL COMMENT 'this is comma delimeted display for device'
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `bots`
--

CREATE TABLE `bots` (
  `bot_id` int(11) NOT NULL COMMENT 'index',
  `user_agent` text NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `bot_name` text NOT NULL,
  `visits` int(11) NOT NULL,
  `last_visit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` text NOT NULL,
  `name` text NOT NULL,
  `protocol` text NOT NULL,
  `game_mode` text NOT NULL,
  `file` text NOT NULL,
  `ip` text NOT NULL,
  `port` int(11) NOT NULL,
  `install_path` text NOT NULL,
  `active` int(11) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE `plugins` (
  `pid` int(11) NOT NULL,
  `name` text NOT NULL,
  `enabled` int(11) NOT NULL,
  `plugin` text NOT NULL,
  `area` int(11) NOT NULL,
  `global` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `updated_on` int(10) NOT NULL DEFAULT '0',
  `nid` varchar(50) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL,
  `location` varchar(150) CHARACTER SET ascii NOT NULL,
  `useragent` varchar(150) CHARACTER SET ascii NOT NULL,
  `usertype` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `sid` int(11) NOT NULL,
  `s_order` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `area` varchar(25) NOT NULL,
  `title` varchar(25) NOT NULL,
  `value` varchar(128) NOT NULL,
  `s_desc` text NOT NULL,
  `display` int(11) NOT NULL,
  `setting_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nid` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(65) NOT NULL DEFAULT '',
  `theme` text NOT NULL,
  `level` enum('guest','banned','user','admin','mod','smod') NOT NULL DEFAULT 'user',
  `avatar` varchar(256) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL,
  `dob` bigint(20) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `currentip` varchar(20) NOT NULL,
  `regdate` bigint(30) NOT NULL,
  `lastseen` int(10) NOT NULL,
  `warning` int(11) NOT NULL,
  `topicnum` int(8) NOT NULL,
  `postnum` int(8) NOT NULL,
  `email` varchar(50) NOT NULL,
  `steamid` bigint(30) NOT NULL,
  `skypeid` varchar(128) NOT NULL,
  `sig` varchar(1024) NOT NULL,
  `nick` varchar(128) NOT NULL,
  `b_priv` int(1) NOT NULL,
  `sex` int(1) NOT NULL,
  `tabs` text NOT NULL,
  `loc` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `bio` varchar(500) NOT NULL,
  `posts` int(1) NOT NULL,
  `threads` int(1) NOT NULL,
  `show_ava` text NOT NULL,
  `hosting` enum('0','1','2','3') NOT NULL,
  `ports` int(11) NOT NULL,
  `installs` int(11) NOT NULL,
  `slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `area` (`area`),
  ADD KEY `area_2` (`area`);

--
-- Indexes for table `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`bot_id`),
  ADD UNIQUE KEY `ip` (`ip`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `sid` (`sid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `cat_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `bots`
--
ALTER TABLE `bots`
  MODIFY `bot_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'index', AUTO_INCREMENT=3526;
--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `plugins`
--
ALTER TABLE `plugins`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
DELIMITER $$
--
-- Events
--
CREATE DEFINER=`baron`@`localhost` EVENT `Session Cleanup` ON SCHEDULE EVERY 3 MINUTE STARTS '2015-01-25 23:08:18' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Session Cleanup' DO delete FROM sessions WHERE updated_on < (UNIX_TIMESTAMP() - 900)$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
