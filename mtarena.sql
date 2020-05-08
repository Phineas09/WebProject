-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2020 at 06:43 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mtarena`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user`, `date`) VALUES
(1, 1, '2020-04-26 18:30:03'),
(2, 1, '2020-04-26 18:30:16'),
(4, 6, '2020-04-26 18:35:15'),
(5, 1, '2020-04-26 18:35:46'),
(6, 1, '2020-04-26 18:40:59'),
(7, 6, '2020-04-26 18:41:11'),
(8, 1, '2020-04-26 18:41:25'),
(9, 1, '2020-04-26 18:42:25'),
(10, 1, '2020-04-26 18:49:20'),
(11, 1, '2020-04-26 18:49:55'),
(12, 1, '2020-04-26 18:51:38'),
(13, 1, '2020-04-26 19:02:10'),
(14, 1, '2020-04-26 19:03:31'),
(15, 1, '2020-04-26 19:03:42'),
(16, 1, '2020-04-26 19:04:15'),
(17, 1, '2020-04-26 19:04:36'),
(18, 1, '2020-04-26 19:05:32'),
(19, 1, '2020-04-26 19:13:14'),
(20, 1, '2020-04-26 19:14:04'),
(21, 1, '2020-04-26 19:16:16'),
(22, 1, '2020-04-26 19:19:58'),
(23, 1, '2020-04-26 19:22:04'),
(24, 6, '2020-04-26 20:34:43'),
(25, 6, '2020-04-26 20:35:29'),
(26, 6, '2020-04-26 20:40:54'),
(27, 6, '2020-04-26 20:45:25'),
(28, 6, '2020-04-26 20:54:15'),
(29, 6, '2020-05-01 10:12:26'),
(30, 1, '2020-05-01 10:15:15'),
(31, 1, '2020-05-01 10:16:44'),
(32, 1, '2020-05-01 10:17:01'),
(33, 1, '2020-05-01 10:21:14'),
(34, 1, '2020-05-01 11:01:57'),
(35, 1, '2020-05-01 11:02:14'),
(36, 1, '2020-05-01 11:06:47'),
(37, 1, '2020-05-01 11:07:05'),
(38, 1, '2020-05-01 11:07:16'),
(39, 1, '2020-05-01 11:08:23'),
(40, 1, '2020-05-01 11:08:41'),
(41, 1, '2020-05-01 11:11:13'),
(42, 1, '2020-05-01 11:11:40'),
(43, 1, '2020-05-01 11:12:01'),
(44, 1, '2020-05-01 11:17:30'),
(45, 1, '2020-05-01 11:17:31'),
(46, 1, '2020-05-01 11:17:47'),
(47, 1, '2020-05-01 11:20:50'),
(48, 1, '2020-05-01 12:16:59'),
(49, 1, '2020-05-01 12:17:21'),
(50, 1, '2020-05-01 12:18:20'),
(51, 1, '2020-05-01 12:26:34'),
(52, 1, '2020-05-01 12:26:58'),
(53, 1, '2020-05-01 12:27:25'),
(54, 1, '2020-05-01 12:28:13'),
(55, 1, '2020-05-01 12:31:27'),
(56, 1, '2020-05-01 12:33:22'),
(57, 1, '2020-05-01 12:34:10'),
(58, 1, '2020-05-01 12:35:47'),
(59, 1, '2020-05-01 12:36:26'),
(60, 1, '2020-05-01 12:57:02'),
(61, 1, '2020-05-01 13:05:06'),
(62, 1, '2020-05-01 13:33:29'),
(63, 1, '2020-05-01 13:34:56'),
(64, 1, '2020-05-01 13:35:54'),
(65, 1, '2020-05-01 13:36:11'),
(66, 1, '2020-05-01 13:49:30'),
(67, 5, '2020-05-01 13:49:51'),
(68, 1, '2020-05-01 13:50:02'),
(69, 1, '2020-05-01 13:55:30'),
(70, 1, '2020-05-01 13:56:32'),
(71, 1, '2020-05-01 13:56:44'),
(72, 1, '2020-05-01 13:57:28'),
(73, 6, '2020-05-01 18:45:08'),
(74, 1, '2020-05-01 18:45:12'),
(75, 1, '2020-05-01 18:45:23'),
(76, 1, '2020-05-01 18:51:45'),
(77, 1, '2020-05-01 18:52:06'),
(78, 1, '2020-05-01 19:34:05'),
(79, 1, '2020-05-01 19:35:55'),
(80, 1, '2020-05-01 19:43:22'),
(81, 1, '2020-05-01 19:44:27'),
(82, 1, '2020-05-01 19:48:32'),
(83, 1, '2020-05-01 23:26:45'),
(84, 1, '2020-05-01 23:27:55'),
(85, 1, '2020-05-01 23:32:52'),
(86, 1, '2020-05-01 23:41:46'),
(87, 1, '2020-05-01 23:42:10'),
(88, 1, '2020-05-01 23:42:25'),
(89, 1, '2020-05-01 23:45:18'),
(90, 1, '2020-05-01 23:46:02'),
(91, 1, '2020-05-01 23:46:23'),
(92, 1, '2020-05-01 23:46:42'),
(93, 1, '2020-05-01 23:47:36'),
(94, 1, '2020-05-01 23:49:23'),
(95, 1, '2020-05-01 23:50:48'),
(96, 1, '2020-05-01 23:52:12'),
(97, 1, '2020-05-01 23:52:37'),
(98, 1, '2020-05-01 23:53:04'),
(99, 1, '2020-05-01 23:53:52'),
(100, 1, '2020-05-01 23:56:07'),
(101, 1, '2020-05-01 23:56:38'),
(102, 1, '2020-05-02 00:04:51'),
(103, 1, '2020-05-02 00:07:05'),
(104, 1, '2020-05-02 00:10:21'),
(105, 1, '2020-05-02 00:11:18'),
(106, 1, '2020-05-02 00:14:41'),
(107, 1, '2020-05-02 00:17:03'),
(108, 1, '2020-05-02 00:19:24'),
(109, 1, '2020-05-02 00:19:41'),
(110, 1, '2020-05-02 00:19:55'),
(111, 1, '2020-05-02 00:23:26'),
(112, 1, '2020-05-02 08:38:28'),
(113, 1, '2020-05-02 08:44:07'),
(114, 1, '2020-05-02 08:44:39'),
(115, 1, '2020-05-02 08:45:07'),
(116, 1, '2020-05-02 08:45:51'),
(117, 1, '2020-05-02 08:46:20'),
(118, 1, '2020-05-02 08:49:21'),
(119, 1, '2020-05-02 08:49:45'),
(120, 1, '2020-05-02 08:51:33'),
(121, 1, '2020-05-02 08:52:05'),
(122, 1, '2020-05-02 08:52:32'),
(123, 1, '2020-05-02 08:53:35'),
(124, 1, '2020-05-02 08:54:19'),
(125, 1, '2020-05-02 09:26:41'),
(126, 1, '2020-05-02 09:27:49'),
(127, 1, '2020-05-02 09:28:18'),
(128, 1, '2020-05-02 11:36:55'),
(129, 1, '2020-05-02 18:55:36'),
(130, 1, '2020-05-02 20:07:17'),
(131, 1, '2020-05-02 20:18:05'),
(132, 1, '2020-05-02 20:23:06'),
(133, 1, '2020-05-02 20:24:55'),
(134, 1, '2020-05-02 20:25:33'),
(135, 1, '2020-05-02 20:26:19'),
(136, 1, '2020-05-02 20:52:09'),
(137, 1, '2020-05-02 20:53:44'),
(138, 1, '2020-05-02 21:04:28'),
(139, 1, '2020-05-02 21:04:49'),
(140, 1, '2020-05-02 21:05:39'),
(141, 1, '2020-05-02 21:06:44'),
(142, 1, '2020-05-02 21:07:01'),
(143, 1, '2020-05-02 21:23:11'),
(144, 1, '2020-05-02 21:26:09'),
(145, 1, '2020-05-02 21:26:19'),
(146, 1, '2020-05-02 21:26:35'),
(147, 1, '2020-05-02 21:26:47'),
(148, 1, '2020-05-02 21:27:43'),
(149, 1, '2020-05-02 21:29:00'),
(150, 1, '2020-05-02 21:29:29'),
(151, 1, '2020-05-02 21:29:42'),
(152, 1, '2020-05-02 21:30:10'),
(153, 1, '2020-05-02 21:30:31'),
(154, 1, '2020-05-02 21:30:57'),
(155, 1, '2020-05-02 21:33:15'),
(156, 1, '2020-05-02 21:33:44'),
(157, 1, '2020-05-02 21:35:51'),
(158, 1, '2020-05-02 21:36:35'),
(159, 1, '2020-05-02 21:37:20'),
(160, 1, '2020-05-02 21:38:10'),
(161, 1, '2020-05-02 21:39:11'),
(162, 1, '2020-05-02 21:39:33'),
(163, 1, '2020-05-02 21:39:45'),
(164, 1, '2020-05-02 21:40:34'),
(165, 1, '2020-05-02 21:40:53'),
(166, 1, '2020-05-02 21:42:36'),
(167, 1, '2020-05-02 21:42:54'),
(168, 1, '2020-05-02 21:43:01'),
(169, 1, '2020-05-02 21:46:29'),
(170, 1, '2020-05-02 21:47:19'),
(171, 1, '2020-05-02 21:48:54'),
(172, 1, '2020-05-02 21:49:28'),
(173, 1, '2020-05-02 21:50:07'),
(174, 1, '2020-05-02 21:51:16'),
(175, 1, '2020-05-02 21:52:05'),
(176, 1, '2020-05-02 21:52:16'),
(177, 1, '2020-05-02 21:52:53'),
(178, 1, '2020-05-02 22:03:19'),
(179, 1, '2020-05-02 22:06:16'),
(180, 1, '2020-05-02 22:08:16'),
(181, 1, '2020-05-02 22:10:32'),
(182, 1, '2020-05-02 22:11:12'),
(183, 1, '2020-05-02 22:11:22'),
(184, 1, '2020-05-02 22:16:25'),
(185, 1, '2020-05-02 22:16:37'),
(186, 1, '2020-05-02 22:17:21'),
(187, 1, '2020-05-02 22:17:49'),
(188, 1, '2020-05-02 22:18:15'),
(189, 1, '2020-05-03 12:32:02'),
(190, 1, '2020-05-03 12:36:08'),
(191, 1, '2020-05-03 12:38:40'),
(192, 1, '2020-05-03 12:40:18'),
(193, 1, '2020-05-03 12:47:48'),
(194, 1, '2020-05-03 12:48:23'),
(195, 1, '2020-05-03 12:52:45'),
(196, 1, '2020-05-03 12:53:38'),
(197, 1, '2020-05-03 12:57:01'),
(198, 1, '2020-05-03 12:57:25'),
(199, 1, '2020-05-03 12:59:05'),
(200, 1, '2020-05-03 13:00:02'),
(201, 1, '2020-05-03 13:01:15'),
(202, 1, '2020-05-03 13:03:00'),
(203, 1, '2020-05-03 14:02:15'),
(204, 1, '2020-05-03 14:06:01'),
(205, 1, '2020-05-03 14:06:36'),
(206, 1, '2020-05-03 14:07:57'),
(207, 1, '2020-05-03 14:08:16'),
(208, 1, '2020-05-03 14:08:34'),
(209, 1, '2020-05-03 14:09:46'),
(210, 1, '2020-05-03 14:09:59'),
(211, 1, '2020-05-03 14:11:38'),
(212, 1, '2020-05-03 14:12:12'),
(213, 1, '2020-05-03 14:12:45'),
(214, 1, '2020-05-03 14:14:44'),
(215, 1, '2020-05-03 14:15:31'),
(216, 1, '2020-05-03 14:19:07'),
(217, 1, '2020-05-03 18:13:30'),
(218, 1, '2020-05-03 18:14:57'),
(219, 1, '2020-05-03 18:15:20'),
(220, 1, '2020-05-03 18:16:27'),
(221, 1, '2020-05-03 18:19:09'),
(222, 1, '2020-05-03 18:19:58'),
(223, 1, '2020-05-03 18:23:56'),
(224, 1, '2020-05-03 18:25:26'),
(225, 1, '2020-05-03 18:25:54'),
(226, 1, '2020-05-03 18:26:11'),
(227, 1, '2020-05-03 18:28:35'),
(228, 1, '2020-05-03 18:44:06'),
(229, 1, '2020-05-03 18:49:57'),
(230, 1, '2020-05-03 18:50:31'),
(231, 1, '2020-05-03 18:50:52'),
(232, 1, '2020-05-03 20:00:55'),
(233, 1, '2020-05-03 20:05:40'),
(234, 1, '2020-05-03 20:26:50'),
(235, 1, '2020-05-03 20:28:11'),
(236, 1, '2020-05-03 20:29:09'),
(237, 1, '2020-05-03 20:33:41'),
(238, 1, '2020-05-03 20:34:14'),
(239, 1, '2020-05-03 20:36:39'),
(240, 1, '2020-05-03 20:37:15'),
(241, 1, '2020-05-03 20:40:51'),
(242, 1, '2020-05-03 20:51:45'),
(243, 1, '2020-05-03 21:10:19'),
(244, 1, '2020-05-03 21:11:27'),
(245, 1, '2020-05-03 21:16:37'),
(246, 1, '2020-05-03 21:17:04'),
(247, 1, '2020-05-03 21:18:37'),
(248, 1, '2020-05-03 21:20:11'),
(249, 1, '2020-05-03 21:22:43'),
(250, 1, '2020-05-03 21:23:05'),
(251, 1, '2020-05-03 21:24:22'),
(252, 1, '2020-05-03 21:24:41'),
(253, 1, '2020-05-03 21:25:33'),
(254, 1, '2020-05-03 21:26:08'),
(255, 1, '2020-05-03 21:27:18'),
(256, 1, '2020-05-03 21:33:28'),
(257, 1, '2020-05-04 19:07:19'),
(258, 1, '2020-05-04 20:52:57'),
(259, 1, '2020-05-04 21:55:39'),
(260, 1, '2020-05-04 22:05:53'),
(261, 1, '2020-05-05 07:17:09'),
(262, 1, '2020-05-05 07:17:11'),
(263, 1, '2020-05-05 07:18:55'),
(264, 1, '2020-05-05 07:41:31'),
(265, 1, '2020-05-05 18:31:08'),
(266, 1, '2020-05-05 19:02:38'),
(267, 1, '2020-05-05 19:02:47'),
(268, 1, '2020-05-05 19:03:09'),
(269, 9, '2020-05-05 19:04:15'),
(270, 9, '2020-05-05 19:06:17'),
(271, 9, '2020-05-05 19:06:58'),
(272, 9, '2020-05-05 19:08:46'),
(273, 9, '2020-05-05 19:45:40'),
(274, 9, '2020-05-05 19:46:02'),
(275, 9, '2020-05-05 19:48:40'),
(276, 1, '2020-05-05 19:52:52'),
(277, 1, '2020-05-05 19:56:20'),
(278, 1, '2020-05-05 20:01:51'),
(279, 1, '2020-05-05 20:02:42'),
(280, 1, '2020-05-05 20:02:59'),
(281, 1, '2020-05-05 20:04:45'),
(282, 1, '2020-05-05 20:05:22'),
(283, 1, '2020-05-05 20:05:56'),
(284, 1, '2020-05-05 20:06:57'),
(285, 1, '2020-05-05 20:07:20'),
(286, 1, '2020-05-05 21:20:37'),
(287, 1, '2020-05-05 21:51:37'),
(288, 1, '2020-05-05 21:52:01'),
(289, 1, '2020-05-05 21:52:19'),
(290, 1, '2020-05-05 21:55:21'),
(291, 1, '2020-05-05 21:56:55'),
(292, 1, '2020-05-05 22:04:24'),
(293, 1, '2020-05-05 22:04:57'),
(294, 1, '2020-05-05 22:17:53'),
(295, 1, '2020-05-05 22:22:42'),
(296, 1, '2020-05-05 22:23:21'),
(297, 1, '2020-05-05 22:34:13'),
(298, 1, '2020-05-05 22:34:57'),
(299, 1, '2020-05-05 22:50:32'),
(300, 1, '2020-05-05 22:53:28'),
(301, 1, '2020-05-05 22:56:37'),
(302, 1, '2020-05-05 22:56:55'),
(303, 1, '2020-05-05 22:57:41'),
(304, 1, '2020-05-05 22:58:02'),
(305, 1, '2020-05-05 22:59:02'),
(306, 1, '2020-05-05 22:59:47'),
(307, 1, '2020-05-05 23:00:46'),
(308, 1, '2020-05-05 23:01:24'),
(309, 1, '2020-05-05 23:02:57'),
(310, 1, '2020-05-05 23:03:37'),
(311, 1, '2020-05-05 23:04:33'),
(312, 1, '2020-05-05 23:06:29'),
(313, 1, '2020-05-05 23:10:26'),
(314, 1, '2020-05-05 23:10:44'),
(315, 1, '2020-05-05 23:14:54'),
(316, 1, '2020-05-05 23:15:16'),
(317, 1, '2020-05-05 23:16:06'),
(318, 1, '2020-05-05 23:16:32'),
(319, 1, '2020-05-05 23:17:59'),
(320, 1, '2020-05-05 23:18:28'),
(321, 1, '2020-05-05 23:19:04'),
(322, 1, '2020-05-05 23:19:37'),
(323, 1, '2020-05-05 23:20:01'),
(324, 1, '2020-05-05 23:20:48'),
(325, 1, '2020-05-05 23:21:35'),
(326, 1, '2020-05-05 23:22:24'),
(327, 1, '2020-05-05 23:22:59'),
(328, 1, '2020-05-05 23:30:27'),
(329, 1, '2020-05-05 23:30:57'),
(330, 1, '2020-05-05 23:32:24'),
(331, 1, '2020-05-05 23:32:36'),
(332, 1, '2020-05-05 23:33:26'),
(333, 1, '2020-05-05 23:37:17'),
(334, 1, '2020-05-05 23:42:05'),
(335, 1, '2020-05-05 23:43:37'),
(336, 1, '2020-05-05 23:44:20'),
(337, 1, '2020-05-05 23:45:25'),
(338, 1, '2020-05-05 23:46:49'),
(339, 1, '2020-05-05 23:53:45'),
(340, 1, '2020-05-05 23:55:31'),
(341, 1, '2020-05-05 23:57:24'),
(342, 1, '2020-05-06 00:15:22'),
(343, 1, '2020-05-06 00:16:07'),
(344, 1, '2020-05-06 11:10:26'),
(345, 1, '2020-05-06 11:12:17'),
(346, 1, '2020-05-06 11:17:52'),
(347, 1, '2020-05-06 11:18:25'),
(348, 1, '2020-05-06 11:19:35'),
(349, 1, '2020-05-06 11:21:19'),
(350, 1, '2020-05-06 12:00:29'),
(351, 1, '2020-05-06 12:03:06'),
(352, 1, '2020-05-06 12:03:42'),
(353, 1, '2020-05-06 12:04:16'),
(354, 1, '2020-05-06 12:04:50'),
(355, 1, '2020-05-06 12:05:11'),
(356, 1, '2020-05-06 12:06:09'),
(357, 1, '2020-05-06 12:06:23'),
(358, 1, '2020-05-06 12:08:25'),
(359, 1, '2020-05-06 12:08:38'),
(360, 1, '2020-05-06 12:09:23'),
(361, 1, '2020-05-06 12:09:43'),
(362, 1, '2020-05-06 12:09:59'),
(363, 1, '2020-05-06 12:10:16'),
(364, 1, '2020-05-06 12:10:47'),
(365, 1, '2020-05-06 12:11:06'),
(366, 1, '2020-05-06 12:15:44'),
(367, 1, '2020-05-06 12:15:54'),
(368, 1, '2020-05-06 12:16:18'),
(369, 1, '2020-05-06 12:17:11'),
(370, 1, '2020-05-06 12:17:22'),
(371, 1, '2020-05-06 12:18:22'),
(372, 1, '2020-05-06 12:18:31'),
(373, 1, '2020-05-06 12:18:41'),
(374, 1, '2020-05-06 12:21:46'),
(375, 1, '2020-05-06 12:23:13'),
(376, 1, '2020-05-06 12:23:30'),
(377, 1, '2020-05-06 12:29:40'),
(378, 1, '2020-05-06 12:29:52'),
(379, 1, '2020-05-06 12:33:25'),
(380, 1, '2020-05-06 12:33:45'),
(381, 1, '2020-05-06 12:34:04'),
(382, 1, '2020-05-06 12:34:18'),
(383, 1, '2020-05-06 12:35:00'),
(384, 1, '2020-05-06 12:35:31'),
(385, 1, '2020-05-06 12:35:45'),
(386, 1, '2020-05-06 12:36:22'),
(387, 1, '2020-05-06 12:36:42'),
(388, 1, '2020-05-06 12:37:18'),
(389, 1, '2020-05-06 12:38:14'),
(390, 1, '2020-05-06 12:39:56'),
(391, 1, '2020-05-06 12:40:16'),
(392, 1, '2020-05-06 12:40:23'),
(393, 1, '2020-05-06 12:40:42'),
(394, 1, '2020-05-06 12:41:26'),
(395, 1, '2020-05-06 12:42:14'),
(396, 1, '2020-05-06 12:44:39'),
(397, 1, '2020-05-06 12:50:10'),
(398, 1, '2020-05-06 12:50:24'),
(399, 1, '2020-05-06 12:50:44'),
(400, 1, '2020-05-06 13:18:37'),
(401, 1, '2020-05-06 13:25:15'),
(402, 1, '2020-05-06 13:25:30'),
(403, 1, '2020-05-06 13:25:43'),
(404, 1, '2020-05-06 13:25:54'),
(405, 1, '2020-05-06 13:26:51'),
(406, 1, '2020-05-06 13:26:59'),
(407, 1, '2020-05-06 13:27:51'),
(408, 1, '2020-05-06 13:40:45'),
(409, 1, '2020-05-06 13:41:21'),
(410, 1, '2020-05-06 13:41:29'),
(411, 1, '2020-05-06 13:43:33'),
(412, 1, '2020-05-06 13:43:51'),
(413, 1, '2020-05-06 13:44:30'),
(414, 1, '2020-05-06 13:44:54'),
(415, 1, '2020-05-06 13:45:16'),
(416, 1, '2020-05-06 13:49:07'),
(417, 1, '2020-05-06 13:49:22'),
(418, 1, '2020-05-06 13:51:02'),
(419, 1, '2020-05-06 13:51:31'),
(420, 1, '2020-05-06 13:51:38'),
(421, 1, '2020-05-06 13:51:41'),
(422, 1, '2020-05-06 13:51:43'),
(423, 1, '2020-05-06 13:52:08'),
(424, 1, '2020-05-06 13:52:42'),
(425, 1, '2020-05-06 13:53:07'),
(426, 1, '2020-05-06 13:53:21'),
(427, 1, '2020-05-06 13:53:47'),
(428, 1, '2020-05-06 13:55:10'),
(429, 1, '2020-05-06 15:52:40'),
(430, 1, '2020-05-06 15:52:57'),
(431, 1, '2020-05-06 16:03:02'),
(432, 1, '2020-05-06 17:37:59'),
(433, 1, '2020-05-06 17:38:13'),
(434, 1, '2020-05-06 17:41:22'),
(435, 1, '2020-05-06 18:20:30'),
(436, 1, '2020-05-06 22:10:46'),
(437, 1, '2020-05-06 23:36:15'),
(438, 1, '2020-05-06 23:36:18'),
(439, 1, '2020-05-06 23:37:37'),
(440, 1, '2020-05-06 23:37:59'),
(441, 1, '2020-05-06 23:47:25'),
(442, 1, '2020-05-06 23:49:46'),
(443, 1, '2020-05-06 23:51:56'),
(444, 1, '2020-05-07 00:03:58'),
(445, 1, '2020-05-07 00:21:43'),
(446, 1, '2020-05-07 00:21:57'),
(447, 1, '2020-05-07 00:22:39'),
(448, 1, '2020-05-07 00:23:10'),
(449, 1, '2020-05-07 00:23:32'),
(450, 1, '2020-05-07 00:23:38'),
(451, 1, '2020-05-07 00:24:14'),
(452, 1, '2020-05-07 00:25:02'),
(453, 1, '2020-05-07 00:25:11'),
(454, 1, '2020-05-07 00:25:47'),
(455, 1, '2020-05-07 00:26:18'),
(456, 1, '2020-05-07 00:26:44'),
(457, 1, '2020-05-07 00:26:57'),
(458, 1, '2020-05-07 00:29:23'),
(459, 1, '2020-05-07 00:30:58'),
(460, 1, '2020-05-07 00:31:01'),
(461, 1, '2020-05-07 00:31:47'),
(462, 1, '2020-05-07 00:32:07'),
(463, 1, '2020-05-07 00:33:27'),
(464, 1, '2020-05-07 01:19:55'),
(465, 1, '2020-05-07 01:47:41'),
(466, 1, '2020-05-07 01:48:21'),
(467, 1, '2020-05-07 01:52:38'),
(468, 1, '2020-05-07 01:53:34'),
(469, 1, '2020-05-07 01:57:42'),
(470, 1, '2020-05-07 02:00:42'),
(471, 1, '2020-05-07 02:01:56'),
(472, 1, '2020-05-07 02:10:12'),
(473, 1, '2020-05-07 03:26:40'),
(474, 1, '2020-05-07 03:34:00'),
(475, 1, '2020-05-07 03:44:05'),
(476, 1, '2020-05-07 03:45:45'),
(477, 1, '2020-05-07 03:46:33'),
(478, 7, '2020-05-07 03:48:14'),
(479, 1, '2020-05-07 03:52:25'),
(480, 1, '2020-05-07 03:52:40'),
(481, 7, '2020-05-07 03:53:48'),
(482, 1, '2020-05-07 03:53:50'),
(483, 1, '2020-05-07 03:54:14'),
(484, 7, '2020-05-07 03:54:19'),
(485, 1, '2020-05-07 03:54:20'),
(486, 1, '2020-05-07 04:33:04'),
(487, 1, '2020-05-07 04:34:38'),
(488, 1, '2020-05-07 04:35:02'),
(489, 1, '2020-05-07 04:36:22'),
(490, 1, '2020-05-07 04:38:37'),
(491, 1, '2020-05-07 04:39:49'),
(492, 1, '2020-05-07 04:41:05'),
(493, 7, '2020-05-07 04:41:25'),
(494, 7, '2020-05-07 04:42:00'),
(495, 7, '2020-05-07 04:50:00'),
(496, 7, '2020-05-07 04:52:16'),
(497, 1, '2020-05-07 04:52:38'),
(498, 1, '2020-05-07 04:53:12'),
(499, 1, '2020-05-07 04:53:20'),
(500, 1, '2020-05-07 04:55:10'),
(501, 7, '2020-05-07 04:55:29'),
(502, 7, '2020-05-07 04:55:31'),
(503, 7, '2020-05-07 04:56:39'),
(504, 7, '2020-05-07 04:56:40'),
(505, 7, '2020-05-07 04:56:51'),
(506, 7, '2020-05-07 04:59:23'),
(507, 7, '2020-05-07 05:00:33'),
(508, 7, '2020-05-07 05:02:29'),
(509, 7, '2020-05-07 05:03:56'),
(510, 7, '2020-05-07 05:04:27'),
(511, 7, '2020-05-07 05:06:11'),
(512, 7, '2020-05-07 05:06:18'),
(513, 7, '2020-05-07 05:07:40'),
(514, 7, '2020-05-07 05:07:49'),
(515, 7, '2020-05-07 05:08:56'),
(516, 7, '2020-05-07 05:09:46'),
(517, 1, '2020-05-07 05:26:40'),
(518, 7, '2020-05-07 05:26:53'),
(519, 1, '2020-05-07 05:28:38'),
(520, 7, '2020-05-07 05:28:46'),
(521, 7, '2020-05-07 05:29:41'),
(522, 1, '2020-05-07 05:30:47'),
(523, 7, '2020-05-07 05:30:51'),
(524, 1, '2020-05-07 05:33:46'),
(525, 7, '2020-05-07 05:33:51'),
(526, 7, '2020-05-07 05:36:37'),
(527, 1, '2020-05-07 05:36:39'),
(528, 1, '2020-05-07 05:58:52'),
(529, 1, '2020-05-07 05:59:12'),
(530, 1, '2020-05-07 06:54:28'),
(531, 6, '2020-05-07 06:55:59'),
(532, 1, '2020-05-07 06:56:14'),
(533, 1, '2020-05-07 06:56:38'),
(534, 6, '2020-05-07 06:58:16'),
(535, 6, '2020-05-07 06:58:18'),
(536, 1, '2020-05-07 06:59:09'),
(537, 6, '2020-05-07 06:59:14'),
(538, 1, '2020-05-07 06:59:20'),
(539, 1, '2020-05-07 07:25:59'),
(540, 1, '2020-05-07 07:29:06'),
(541, 6, '2020-05-07 07:29:19'),
(542, 1, '2020-05-07 07:31:42'),
(543, 1, '2020-05-07 07:32:56'),
(544, 1, '2020-05-07 07:34:16'),
(545, 1, '2020-05-07 07:35:00'),
(546, 1, '2020-05-07 07:35:20'),
(547, 1, '2020-05-07 07:38:42'),
(548, 1, '2020-05-07 07:43:35'),
(549, 5, '2020-05-07 07:58:42'),
(550, 6, '2020-05-07 08:01:04'),
(551, 6, '2020-05-07 08:03:55'),
(552, 1, '2020-05-07 08:04:08'),
(553, 6, '2020-05-07 08:04:09'),
(554, 1, '2020-05-07 08:04:21'),
(555, 6, '2020-05-07 08:04:36'),
(556, 6, '2020-05-07 08:05:32'),
(557, 1, '2020-05-07 08:06:07'),
(558, 1, '2020-05-07 08:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `can_modify` tinyint(1) DEFAULT 0,
  `can_approve` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `user`, `is_admin`, `can_modify`, `can_approve`) VALUES
(1, 1, 1, 0, 1),
(5, 5, 1, 0, 1),
(6, 6, 1, 0, 0),
(7, 7, 0, 0, 0),
(9, 9, 0, 0, 1),
(10, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `id` int(11) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `approved` int(11) DEFAULT 0,
  `name` text NOT NULL,
  `language` text NOT NULL,
  `points` int(11) DEFAULT NULL,
  `difficulty` text DEFAULT 'easy',
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `presentation` text DEFAULT NULL,
  `testCases` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`id`, `author`, `approved`, `name`, `language`, `points`, `difficulty`, `date`, `presentation`, `testCases`) VALUES
(1, 5, 1, 'Sarah Robbins', 'P++', 29, 'easy', '2020-04-26 18:59:04', 'turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in,', 0),
(2, 1, 1, 'Sopoline', 'C++', 74, 'medium', '2020-04-26 18:59:04', 'metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh.', 4),
(3, 3, 1, 'Jenna Jacobson', 'W++', 18, 'hard', '2020-04-26 18:59:04', 'libero. Proin sed turpis nec mauris blandit mattis. Cras eget', 0),
(4, 5, 1, 'Wynne Cannon', 'R++', 67, 'hard', '2020-04-26 18:59:04', 'parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque', 0),
(5, 1, 1, 'Hillary Buckley', 'Z++', 45, 'hard', '2020-04-26 18:59:04', 'nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu.', 0),
(6, 1, 1, 'Miriam Patterson', 'Z++', 145, 'hard', '2020-04-26 18:59:04', 'volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam', 0),
(7, 1, 1, 'Isadora Durham', 'W++', 66, 'easy', '2020-04-26 18:59:04', 'tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus', 0),
(8, 1, 1, 'Geraldine Oneil', 'M++', 53, 'easy', '2020-04-26 18:59:04', 'massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor', 0),
(9, 1, 1, 'Mariko Tillman', 'B++', 97, 'easy', '2020-04-26 18:59:04', 'sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam', 0),
(10, 1, 1, 'Adena Osborne', 'P++', 33, 'easy', '2020-04-26 18:59:04', 'leo. Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse dui.', 0),
(11, 6, 1, 'Cassidy Puckett', 'M++', 24, 'easy', '2020-04-26 18:59:05', 'morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a', 0),
(12, 6, 1, 'Ariana Phelps', 'L++', 138, 'easy', '2020-04-26 18:59:05', 'metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer', 0),
(13, 1, 1, 'Camille Avila', 'D++', 54, 'easy', '2020-04-26 18:59:05', 'purus. Nullam scelerisque neque sed sem egestas blandit. Nam nulla magna, malesuada vel,', 0),
(14, 6, 1, 'Venus Kerr', 'R++', 129, 'easy', '2020-04-26 18:59:05', 'Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam', 0),
(15, 1, 1, 'Hilda Moore', 'B++', 117, 'easy', '2020-04-26 18:59:05', 'magna. Cras convallis convallis dolor. Quisque tincidunt pede ac urna. Ut tincidunt vehicula risus. Nulla', 0),
(16, 1, 1, 'Hollee Good', 'G++', 77, 'easy', '2020-04-26 18:59:05', 'quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius', 0),
(17, 6, 1, 'Hedy Fry', 'N++', 73, 'easy', '2020-04-26 18:59:05', 'nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas', 0),
(18, 1, 1, 'Ciara Mccullough', 'Y++', 97, 'easy', '2020-04-26 18:59:05', 'diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla.', 0),
(19, 3, 1, 'Chloe Kennedy', 'J++', 79, 'easy', '2020-04-26 18:59:05', 'gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris', 0),
(20, 3, 1, 'Shafira Garner', 'W++', 50, 'hard', '2020-04-26 18:59:05', 'auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est.', 0),
(21, 3, 1, 'Ivory Haley', 'P++', 34, 'easy', '2020-04-26 18:59:05', 'molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl', 0),
(22, 5, 1, 'Lesley Reynolds', 'X++', 140, 'easy', '2020-04-26 18:59:05', 'consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non', 0),
(23, 5, 1, 'Vivian Cabrera', 'X++', 103, 'easy', '2020-04-26 18:59:05', 'Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus,', 0),
(24, 1, 1, 'Violet Donaldson', 'T++', 50, 'easy', '2020-04-26 18:59:05', 'Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie', 0),
(25, 6, 1, 'Dana Tran', 'X++', 50, 'easy', '2020-04-26 18:59:05', 'Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu.', 0),
(26, 6, 1, 'Abra Benson', 'Q++', 34, 'easy', '2020-04-26 18:59:05', 'tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget', 0),
(27, 1, 1, 'Cassandra Wilcox', 'R++', 23, 'easy', '2020-04-26 18:59:05', 'vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem', 0),
(28, 5, 1, 'Delilah Hoffman', 'C++', 123, 'easy', '2020-04-26 18:59:05', 'est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac', 0),
(29, 6, 1, 'Clare Aguilar', 'M++', 84, 'easy', '2020-04-26 18:59:05', 'ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec', 0),
(30, 6, 1, 'Portia Tyler', 'G++', 130, 'easy', '2020-04-26 18:59:05', 'amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes,', 0),
(31, 1, 1, 'Riley Gentry', 'V++', 36, 'easy', '2020-04-26 18:59:05', 'eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum', 0),
(32, 1, 1, 'Fredericka Morse', 'M++', 62, 'easy', '2020-04-26 18:59:05', 'molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu', 0),
(33, 1, 1, 'Cathleen Alston', 'X++', 109, 'easy', '2020-04-26 18:59:05', 'adipiscing, enim mi tempor lorem, eget mollis lectus pede et risus. Quisque libero', 0),
(34, 5, 1, 'Doris Hunter', 'Y++', 48, 'easy', '2020-04-26 18:59:05', 'ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent', 0),
(35, 3, 1, 'Cameron Carpenter', 'Z++', 54, 'easy', '2020-04-26 18:59:05', 'mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend', 0),
(36, 1, 1, 'Carolyn Blevins', 'Z++', 150, 'easy', '2020-04-26 18:59:05', 'sed tortor. Integer aliquam adipiscing lacus. Ut nec urna et arcu imperdiet ullamcorper.', 0),
(37, 1, 1, 'Madeson Norris', 'R++', 102, 'easy', '2020-04-26 18:59:05', 'ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui.', 0),
(38, 1, 1, 'Whoopi Vazquez', 'B++', 116, 'easy', '2020-04-26 18:59:05', 'Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci,', 0),
(39, 1, 1, 'Ruby Fields', 'S++', 134, 'easy', '2020-04-26 18:59:05', 'sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus', 0),
(40, 1, 1, 'Miriam Witt', 'N++', 37, 'easy', '2020-04-26 18:59:05', 'mattis velit justo nec ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere vulputate,', 0),
(41, 5, 1, 'Yvette Shaffer', 'Y++', 117, 'easy', '2020-04-26 18:59:05', 'orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur', 0),
(42, 3, 1, 'Ima Church', 'Q++', 60, 'easy', '2020-04-26 18:59:05', 'sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque', 0),
(43, 1, 1, 'Emerald Bowers', 'X++', 139, 'easy', '2020-04-26 18:59:05', 'sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac', 0),
(44, 1, 1, 'Sade Boyer', 'B++', 104, 'easy', '2020-04-26 18:59:05', 'ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris', 0),
(45, 3, 1, 'Bo Crane', 'M++', 7, 'easy', '2020-04-26 18:59:05', 'velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est.', 0),
(46, 5, 1, 'Kylynn Sanders', 'V++', 128, 'easy', '2020-04-26 18:59:05', 'Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim', 0),
(47, 6, 1, 'Yen Garner', 'L++', 4, 'easy', '2020-04-26 18:59:05', 'ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla.', 0),
(48, 3, 1, 'Portia Brady', 'M++', 55, 'easy', '2020-04-26 18:59:05', 'est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras', 0),
(49, 5, 1, 'Cassidy Phelps', 'G++', 2, 'easy', '2020-04-26 18:59:05', 'a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula', 0),
(50, 1, 1, 'Belle Alston', 'C++', 35, 'easy', '2020-04-26 18:59:05', 'porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo', 0),
(51, 6, 1, 'Lavinia Delaney', 'D++', 137, 'easy', '2020-04-26 18:59:05', 'penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse', 0),
(52, 1, 1, 'Ivory Hull', 'D++', 26, 'easy', '2020-04-26 18:59:05', 'eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum', 0),
(53, 6, 1, 'Sarah Livingston', 'X++', 144, 'easy', '2020-04-26 18:59:05', 'erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam', 0),
(54, 1, 1, 'Emi Matthews', 'W++', 66, 'easy', '2020-04-26 18:59:05', 'elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus', 0),
(55, 1, 1, 'Ila Glover', 'K++', 139, 'easy', '2020-04-26 18:59:05', 'sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac', 0),
(56, 3, 1, 'Pearl Pratt', 'C++', 138, 'easy', '2020-04-26 18:59:05', 'Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum', 0),
(57, 1, 1, 'Frances Barron', 'Q++', 14, 'easy', '2020-04-26 18:59:05', 'scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis', 0),
(58, 3, 1, 'Hadley Cantu', 'B++', 3, 'easy', '2020-04-26 18:59:05', 'euismod in, dolor. Fusce feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor,', 0),
(59, 5, 1, 'Quin Hobbs', 'H++', 78, 'easy', '2020-04-26 18:59:05', 'consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci,', 0),
(60, 6, 1, 'Wanda Pittman', 'N++', 52, 'easy', '2020-04-26 18:59:05', 'luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis', 0),
(61, 5, 1, 'Jasmine Dotson', 'R++', 129, 'easy', '2020-04-26 18:59:05', 'pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor', 0),
(62, 6, 1, 'Macey Alford', 'L++', 62, 'easy', '2020-04-26 18:59:05', 'pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in', 0),
(63, 1, 1, 'Flavia Clark', 'D++', 106, 'easy', '2020-04-26 18:59:05', 'Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi', 0),
(64, 3, 1, 'Unity Guerra', 'R++', 114, 'easy', '2020-04-26 18:59:05', 'arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc', 0),
(65, 5, 1, 'Clementine Dennis', 'X++', 135, 'easy', '2020-04-26 18:59:05', 'nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam', 0),
(66, 1, 1, 'Vanna York', 'K++', 59, 'easy', '2020-04-26 18:59:05', 'quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer', 0),
(67, 1, 1, 'Maryam Frazier', 'Y++', 14, 'easy', '2020-04-26 18:59:05', 'fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi', 0),
(68, 1, 1, 'Blythe Jacobs', 'M++', 44, 'easy', '2020-04-26 18:59:05', 'ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo.', 0),
(69, 5, 1, 'Sonya White', 'K++', 13, 'easy', '2020-04-26 18:59:05', 'ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat', 0),
(70, 1, 1, 'Azalia Simmons', 'G++', 11, 'easy', '2020-04-26 18:59:05', 'semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque sed,', 0),
(71, 5, 1, 'Caryn Henderson', 'H++', 42, 'easy', '2020-04-26 18:59:05', 'fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu', 0),
(72, 6, 1, 'Riley Jenkins', 'V++', 115, 'easy', '2020-04-26 18:59:05', 'sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut,', 0),
(73, 1, 1, 'Jennifer Sullivan', 'G++', 130, 'easy', '2020-04-26 18:59:05', 'vestibulum massa rutrum magna. Cras convallis convallis dolor. Quisque tincidunt pede ac urna. Ut tincidunt', 0),
(74, 1, 1, 'Candace Whitley', 'B++', 66, 'easy', '2020-04-26 18:59:05', 'massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at', 0),
(75, 1, 1, 'Aline Frederick', 'Z++', 51, 'easy', '2020-04-26 18:59:05', 'habitant morbi tristique senectus et netus et malesuada fames ac turpis', 0),
(76, 1, 1, 'Kameko Ross', 'T++', 109, 'easy', '2020-04-26 18:59:05', 'cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis', 0),
(77, 5, 1, 'Rebecca Reynolds', 'P++', 61, 'easy', '2020-04-26 18:59:05', 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;', 0),
(78, 6, 1, 'Pearl Luna', 'W++', 14, 'easy', '2020-04-26 18:59:05', 'eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum', 0),
(79, 1, 1, 'Venus Gray', 'Q++', 150, 'easy', '2020-04-26 18:59:05', 'convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem', 0),
(80, 3, 1, 'Shannon David', 'Q++', 147, 'easy', '2020-04-26 18:59:05', 'lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', 0),
(81, 1, 1, 'Britanni Jacobs', 'Y++', 15, 'easy', '2020-04-26 18:59:05', 'tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet,', 0),
(82, 1, 1, 'Gloria Lowery', 'C++', 149, 'easy', '2020-04-26 18:59:05', 'turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper', 0),
(83, 6, 1, 'Joelle Kennedy', 'M++', 121, 'easy', '2020-04-26 18:59:05', 'ullamcorper. Duis at lacus. Quisque purus sapien, gravida non, sollicitudin a, malesuada', 0),
(84, 5, 1, 'Ursa Sanchez', 'D++', 27, 'easy', '2020-04-26 18:59:05', 'ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus', 0),
(85, 1, 1, 'Macy Myers', 'Z++', 19, 'easy', '2020-04-26 18:59:05', 'mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu', 0),
(86, 6, 1, 'Georgia Douglas', 'Z++', 89, 'easy', '2020-04-26 18:59:05', 'ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer', 0),
(87, 6, 1, 'Nell Davidson', 'J++', 57, 'easy', '2020-04-26 18:59:05', 'arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui,', 0),
(88, 5, 1, 'Phyllis Douglas', 'M++', 95, 'easy', '2020-04-26 18:59:05', 'nulla. Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis', 0),
(89, 5, 1, 'Ramona Hopkins', 'L++', 77, 'easy', '2020-04-26 18:59:05', 'Integer aliquam adipiscing lacus. Ut nec urna et arcu imperdiet ullamcorper.', 0),
(90, 1, 1, 'Lee Frederick', 'S++', 73, 'easy', '2020-04-26 18:59:05', 'felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris', 0),
(91, 3, 1, 'Kameko Carr', 'V++', 50, 'easy', '2020-04-26 18:59:05', 'leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida', 0),
(92, 6, 1, 'Mari Carrillo', 'J++', 17, 'easy', '2020-04-26 18:59:05', 'lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis', 0),
(93, 6, 1, 'Stephanie Morris', 'F++', 113, 'easy', '2020-04-26 18:59:05', 'dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at,', 0),
(94, 1, 1, 'Maia David', 'Q++', 142, 'easy', '2020-04-26 18:59:05', 'sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque', 0),
(95, 5, 1, 'Maya Melendez', 'X++', 103, 'easy', '2020-04-26 18:59:05', 'sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna.', 0),
(96, 1, 1, 'Quincy Pearson', 'Y++', 13, 'easy', '2020-04-26 18:59:05', 'tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus.', 0),
(97, 6, 1, 'Jena Howell', 'G++', 142, 'easy', '2020-04-26 18:59:05', 'ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet', 0),
(98, 3, 1, 'Xantha Sampson', 'X++', 105, 'easy', '2020-04-26 18:59:05', 'risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus.', 0),
(99, 5, 1, 'Iris Byers', 'V++', 135, 'easy', '2020-04-26 18:59:05', 'mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta', 0),
(100, 1, 1, 'Reagan Rose', 'Y++', 99, 'easy', '2020-04-26 18:59:05', 'Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna.', 0),
(126, 1, 1, 'jgerwgw', 'C++', 150, 'hard', '2020-05-02 20:07:34', 'This is the best problem that i\'ve ever seen man, this is amazing, so let\'s see if this works!', 4),
(132, 1, 1, 'Test Problem', 'C++', 80, 'easy', '2020-05-05 07:22:12', NULL, 8),
(133, 1, 0, 'test Problem', 'C++', NULL, 'easy', '2020-05-05 23:48:19', NULL, 3),
(134, 1, 0, 'test problem Numero 2', 'C++', NULL, 'easy', '2020-05-05 23:58:26', NULL, 1),
(135, 1, 0, 'wwebkhbjwbnwbwe', 'C++', NULL, 'easy', '2020-05-07 08:00:10', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `problems_approvedby`
--

CREATE TABLE `problems_approvedby` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `problem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `problems_approvedby`
--

INSERT INTO `problems_approvedby` (`id`, `user`, `problem`) VALUES
(3, 1, 126);

-- --------------------------------------------------------

--
-- Table structure for table `problems_solved`
--

CREATE TABLE `problems_solved` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `problem` int(11) NOT NULL,
  `points` float DEFAULT 0,
  `result` text DEFAULT '',
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `submitNumber` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `problems_solved`
--

INSERT INTO `problems_solved` (`id`, `user`, `problem`, `points`, `result`, `date`, `submitNumber`) VALUES
(1, 6, 86, 89, '', '2020-05-05 07:06:36', 0),
(2, 3, 88, 95, '', '2020-05-05 07:06:36', 0),
(7, 1, 58, 3, '', '2020-05-05 07:06:36', 0),
(9, 5, 30, 130, '', '2020-05-05 07:06:36', 0),
(11, 1, 92, 17, '', '2020-05-05 07:06:36', 0),
(13, 1, 24, 50, '', '2020-05-05 07:06:36', 0),
(14, 3, 64, 114, '', '2020-05-05 07:06:36', 0),
(15, 3, 6, 145, '', '2020-05-05 07:06:36', 0),
(16, 5, 24, 50, '', '2020-05-05 07:06:36', 0),
(17, 6, 31, 36, '', '2020-05-05 07:06:36', 0),
(19, 6, 36, 150, '', '2020-05-05 07:06:36', 0),
(20, 3, 49, 2, '', '2020-05-05 07:06:36', 0),
(22, 5, 25, 50, '', '2020-05-05 07:06:36', 0),
(24, 5, 2, 74, '', '2020-05-05 07:06:36', 0),
(26, 1, 56, 138, '', '2020-05-05 07:06:36', 0),
(27, 3, 60, 52, '', '2020-05-05 07:06:36', 0),
(28, 6, 45, 7, '', '2020-05-05 07:06:36', 0),
(32, 6, 10, 33, '', '2020-05-05 07:06:36', 0),
(33, 1, 52, 26, '', '2020-05-05 07:06:36', 0),
(34, 3, 11, 24, '', '2020-05-05 07:06:36', 0),
(37, 3, 54, 66, '', '2020-05-05 07:06:36', 0),
(38, 3, 93, 113, '', '2020-05-05 07:06:36', 0),
(41, 3, 96, 13, '', '2020-05-05 07:06:36', 0),
(44, 6, 51, 137, '', '2020-05-05 07:06:36', 0),
(45, 3, 84, 27, '', '2020-05-05 07:06:36', 0),
(47, 6, 30, 130, '', '2020-05-05 07:06:36', 0),
(52, 1, 61, 129, '', '2020-05-05 07:06:36', 0),
(54, 1, 92, 17, '', '2020-05-05 07:06:36', 0),
(55, 3, 77, 61, '', '2020-05-05 07:06:36', 0),
(56, 6, 37, 102, '', '2020-05-05 07:06:36', 0),
(57, 3, 61, 129, '', '2020-05-05 07:06:36', 0),
(59, 6, 16, 77, '', '2020-05-05 07:06:36', 0),
(61, 3, 34, 48, '', '2020-05-05 07:06:36', 0),
(62, 5, 92, 17, '', '2020-05-05 07:06:36', 0),
(63, 1, 96, 13, '', '2020-05-05 07:06:36', 0),
(64, 5, 99, 135, '', '2020-05-05 07:06:36', 0),
(66, 1, 54, 66, '', '2020-05-05 07:06:36', 0),
(68, 3, 14, 129, '', '2020-05-05 07:06:36', 0),
(71, 1, 69, 13, '', '2020-05-05 07:06:36', 0),
(72, 6, 12, 138, '', '2020-05-05 07:06:36', 0),
(73, 6, 31, 36, '', '2020-05-05 07:06:36', 0),
(74, 6, 4, 67, '', '2020-05-05 07:06:36', 0),
(75, 6, 9, 97, '', '2020-05-05 07:06:36', 0),
(77, 3, 8, 53, '', '2020-05-05 07:06:36', 0),
(78, 1, 62, 62, '', '2020-05-05 07:06:36', 0),
(79, 6, 75, 51, '', '2020-05-05 07:06:36', 0),
(81, 1, 5, 45, '', '2020-05-05 07:06:36', 0),
(83, 1, 6, 145, '', '2020-05-05 07:06:36', 0),
(84, 6, 61, 129, '', '2020-05-05 07:06:36', 0),
(86, 3, 53, 144, '', '2020-05-05 07:06:36', 0),
(87, 1, 13, 54, '', '2020-05-05 07:06:36', 0),
(88, 1, 32, 62, '', '2020-05-05 07:06:36', 0),
(91, 5, 99, 135, '', '2020-05-05 07:06:36', 0),
(94, 1, 91, 50, '', '2020-05-05 07:06:36', 0),
(95, 6, 11, 24, '', '2020-05-05 07:06:36', 0),
(96, 5, 92, 17, '', '2020-05-05 07:06:36', 0),
(97, 1, 10, 33, '', '2020-05-05 07:06:36', 0),
(100, 5, 48, 55, '', '2020-05-05 07:06:36', 0),
(120, 1, 132, 80, '{\"0\" : \"Passed\",\"1\" : \"Passed\",\"2\" : \"Passed\",\"3\" : \"Passed\",\"4\" : \"Passed\",\"5\" : \"Passed\",\"6\" : \"Passed\",\"7\" : \"Passed\",\"score\" : \"80\", \"statusCode\" : \"200\" }', '2020-05-05 07:36:56', 0),
(121, 1, 132, 70, '{\"0\" : \"Passed\",\"1\" : \"Passed\",\"2\" : \"Passed\",\"3\" : \"Passed\",\"4\" : \"Passed\",\"5\" : \"Passed\",\"6\" : \"Passed\",\"7\" : \"Failed\",\"score\" : \"70\", \"statusCode\" : \"200\" }', '2020-05-05 07:37:32', 1),
(122, 1, 2, 74, '{\"0\" : \"Passed\",\"1\" : \"Passed\",\"2\" : \"Passed\",\"3\" : \"Passed\",\"score\" : \"74\", \"statusCode\" : \"200\" }', '2020-05-06 11:10:51', 4),
(123, 1, 2, 74, '{\"0\" : \"Passed\",\"1\" : \"Passed\",\"2\" : \"Passed\",\"3\" : \"Passed\",\"score\" : \"74\", \"statusCode\" : \"200\" }', '2020-05-07 08:07:51', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `oauth` text DEFAULT NULL,
  `oauthId` text DEFAULT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` text DEFAULT NULL,
  `hash` text DEFAULT NULL,
  `online` int(11) DEFAULT NULL,
  `validated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `oauth`, `oauthId`, `name`, `email`, `password`, `hash`, `online`, `validated`) VALUES
(1, 'facebook', '106203721032635', 'Bernice Robertson', 'mtarenaweb@gmail.com', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'ca7eb68bb15ae9982515e28c221de0f6', 0, 1),
(3, 'Defalut', ' ', 'Philip', 'test@philip.eeorfege', '0f33afc21d3d63828bbece86a0affc5b8ba50c818adc04fe3e004fd0368df745', 'berrebebe', 0, 0),
(5, 'google', '112644580159386436416', 'MTArena MTA', 'mtarenaweb@gmail.com', 'efd872cf072e359cbfa4d72f2dcfbe04a15f97a5c889ef0e20c109eaa8914bae', 'a2ddb238960679d3fb14d4d092e2563c', 0, 1),
(6, 'Default', '', 'test', 'test@test.ro', 'efd872cf072e359cbfa4d72f2dcfbe04a15f97a5c889ef0e20c109eaa8914bae', 'ab9830ba7e6b6246eff3ebd5dbc0a362', 0, 0),
(7, 'Default', '', 'TestUser', 'example@mtarena.ro', 'efd872cf072e359cbfa4d72f2dcfbe04a15f97a5c889ef0e20c109eaa8914bae', '50fb7cd1cbc8ccfb3ebcf87a466330b6', 0, 0),
(9, 'Default', '', 'oddpants', 'claudiu.ghenea15@yahoo.ro', '0f33afc21d3d63828bbece86a0affc5b8ba50c818adc04fe3e004fd0368df745', 'f47719502038cef896b618a33b8f427a', 0, 0);

-- --------------------------------------------------------

--
-- Stand-in structure for view `userstatistics`
-- (See below for the actual view)
--
CREATE TABLE `userstatistics` (
`user` bigint(21)
,`date` date
);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `first_name` text DEFAULT 'Unset',
  `last_name` text DEFAULT 'Unset',
  `address` text DEFAULT 'Unset',
  `phone_number` text DEFAULT 'Unset',
  `birth_date` date DEFAULT NULL,
  `profile_picture` text DEFAULT '/Misc/Default/Profile.png',
  `title` text DEFAULT 'Rookie',
  `lastAct` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user`, `first_name`, `last_name`, `address`, `phone_number`, `birth_date`, `profile_picture`, `title`, `lastAct`) VALUES
(1, 1, 'Claudiu', 'Ghenea', 'Unset', '0732101694', NULL, './../Misc/ProfilePictures/ca7eb68bb15ae9982515e28c221de0f6', 'Profu\' De Smecherie', '2020-05-07 08:12:08'),
(5, 5, 'Unset', 'Unset', 'Unset', 'Unset', NULL, '/Misc/Default/Profile.png', 'Rookie', '2020-05-07 08:06:44'),
(6, 6, 'Unset', 'Unset', 'Unset', 'Unset', NULL, '/Misc/Default/Profile.png', 'Rookie', '2020-05-07 08:05:06'),
(7, 7, 'Unset', 'Unset', NULL, 'Unset', NULL, '/Misc/Default/Profile.png', 'Rookie', '2020-05-07 05:37:07'),
(9, 9, 'Unset', 'Unset', 'Unset', 'Unset', NULL, '/Misc/Default/Profile.png', 'Rookie', '2020-05-05 19:48:33'),
(10, 3, 'Unset', 'Unset', 'Unset', 'Unset', NULL, '/Misc/Default/Profile.png', 'Rookie', '2020-05-06 23:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_friends`
--

CREATE TABLE `user_friends` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `friend` int(11) NOT NULL,
  `accepted` int(11) DEFAULT 0,
  `newMessage` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_friends`
--

INSERT INTO `user_friends` (`id`, `user`, `friend`, `accepted`, `newMessage`) VALUES
(1, 1, 3, 1, 0),
(2, 3, 1, 1, 0),
(3, 1, 9, 1, 0),
(4, 9, 1, 1, 0),
(5, 1, 5, 0, 0),
(6, 1, 7, 1, 1),
(7, 7, 1, 1, 1),
(8, 1, 6, 1, 1),
(9, 6, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_messages`
--

CREATE TABLE `user_messages` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `message` text NOT NULL,
  `read` int(11) DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_messages`
--

INSERT INTO `user_messages` (`id`, `user`, `to`, `message`, `read`, `date`) VALUES
(1, 1, 7, 'Acesta este un test bro', 1, '2020-05-07 01:18:57'),
(2, 1, 7, 'Sa vedem cum se comporta smecheria', 1, '2020-05-07 01:18:57'),
(3, 7, 1, 'Testam bro', 1, '2020-05-07 01:19:22'),
(4, 1, 7, 'Cum sa nu testam noi', 1, '2020-05-07 01:19:22'),
(5, 7, 1, 'da', 1, '2020-05-07 01:19:38'),
(6, 7, 1, 'dadadadadada', 1, '2020-05-07 01:19:37'),
(7, 7, 1, 'Test', 1, '2020-05-07 01:41:01'),
(8, 1, 7, 'Hi', 1, '2020-05-07 01:41:11'),
(9, 7, 1, 'dvwbw w bw w', 1, '2020-05-07 01:42:20'),
(10, 1, 7, 'Hello?', 1, '2020-05-07 02:10:27'),
(11, 1, 7, 'Are you here ?', 1, '2020-05-07 02:10:37'),
(12, 7, 1, 'SUp?', 1, '2020-05-07 03:46:15'),
(13, 7, 1, 'Hello', 1, '2020-05-07 03:48:22'),
(14, 1, 7, 'Hi', 1, '2020-05-07 03:48:37'),
(15, 7, 1, 'How are you doing ?', 1, '2020-05-07 03:48:42'),
(16, 1, 7, 'I\'m doing just fine, about you ?', 1, '2020-05-07 03:48:52'),
(17, 7, 1, 'qvq', 1, '2020-05-07 03:48:57'),
(18, 7, 1, 'bq', 1, '2020-05-07 03:48:57'),
(19, 7, 1, 'bq', 1, '2020-05-07 03:48:57'),
(20, 7, 1, 'qb', 1, '2020-05-07 03:48:58'),
(21, 7, 1, 'q', 1, '2020-05-07 03:48:58'),
(22, 1, 7, 'wbwbwbwbw', 1, '2020-05-07 03:49:04'),
(23, 1, 7, 'bw', 1, '2020-05-07 03:49:04'),
(24, 1, 7, 'b', 1, '2020-05-07 03:49:04'),
(25, 1, 7, 'wb', 1, '2020-05-07 03:49:04'),
(26, 1, 7, 'w', 1, '2020-05-07 03:49:04'),
(27, 1, 7, 'Test', 1, '2020-05-07 04:00:02'),
(28, 7, 1, 'Test', 1, '2020-05-07 05:37:03'),
(29, 1, 7, 'Hello', 1, '2020-05-07 05:37:07'),
(30, 7, 1, 'How are you ?', 1, '2020-05-07 05:37:11'),
(31, 1, 7, 'FIne.', 1, '2020-05-07 05:37:14'),
(32, 1, 6, 'Hi', 1, '2020-05-07 06:56:19'),
(33, 6, 1, 'Hello', 1, '2020-05-07 06:56:23'),
(34, 1, 6, 'berbnren', 1, '2020-05-07 08:01:25'),
(35, 6, 1, 'nenen', 1, '2020-05-07 08:01:27'),
(36, 6, 1, 'ne', 1, '2020-05-07 08:01:27'),
(37, 6, 1, 'n', 1, '2020-05-07 08:01:28'),
(38, 6, 1, 'etn', 1, '2020-05-07 08:01:28'),
(39, 6, 1, 'et', 1, '2020-05-07 08:01:28'),
(40, 6, 1, 'ne', 1, '2020-05-07 08:01:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `data` text DEFAULT NULL,
  `new` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user`, `data`, `new`) VALUES
(1, 1, '[{\"text\":\"No notifications yet\",\"name\":\"Test\",\"date\":\"17\",\"read\":true},{\"text\":\"No notifications yet\",\"name\":\"Test\",\"date\":\"19:05\",\"read\":true}]', 0),
(2, 7, NULL, 0),
(3, 9, NULL, 0),
(4, 3, NULL, 0),
(5, 6, NULL, 0),
(6, 5, NULL, 0);

-- --------------------------------------------------------

--
-- Structure for view `userstatistics`
--
DROP TABLE IF EXISTS `userstatistics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `userstatistics`  AS  select if(`s`.`user` is null,0,`s`.`user`) AS `user`,`b`.`Days` AS `date` from ((select `a`.`Days` AS `Days` from (select curdate() - interval (`a`.`a` + 10 * `b`.`a` + 100 * `c`.`a`) day AS `Days` from (((select 0 AS `a` union all select 1 AS `1` union all select 2 AS `2` union all select 3 AS `3` union all select 4 AS `4` union all select 5 AS `5` union all select 6 AS `6` union all select 7 AS `7` union all select 8 AS `8` union all select 9 AS `9`) `a` join (select 0 AS `a` union all select 1 AS `1` union all select 2 AS `2` union all select 3 AS `3` union all select 4 AS `4` union all select 5 AS `5` union all select 6 AS `6` union all select 7 AS `7` union all select 8 AS `8` union all select 9 AS `9`) `b`) join (select 0 AS `a` union all select 1 AS `1` union all select 2 AS `2` union all select 3 AS `3` union all select 4 AS `4` union all select 5 AS `5` union all select 6 AS `6` union all select 7 AS `7` union all select 8 AS `8` union all select 9 AS `9`) `c`)) `a` where `a`.`Days` >= curdate() - interval 13 day) `b` left join (select cast(`logs`.`date` as date) AS `day`,count(distinct `logs`.`user`) AS `user` from `logs` group by cast(`logs`.`date` as date)) `s` on(`s`.`day` = `b`.`Days`)) order by `b`.`Days` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_USER_LOGS_ID` (`user`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_privileges` (`user`);

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_author_id` (`author`);

--
-- Indexes for table `problems_approvedby`
--
ALTER TABLE `problems_approvedby`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_approvedBy_id` (`user`),
  ADD KEY `fk_problemApproved_id` (`problem`);

--
-- Indexes for table `problems_solved`
--
ALTER TABLE `problems_solved`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user`),
  ADD KEY `fk_problem_id` (`problem`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_details` (`user`);

--
-- Indexes for table `user_friends`
--
ALTER TABLE `user_friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_friends` (`user`),
  ADD KEY `fk_user_friends_friend` (`friend`);

--
-- Indexes for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_messages_from` (`user`),
  ADD KEY `fk_user_messages_to` (`to`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_user` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=559;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `problems_approvedby`
--
ALTER TABLE `problems_approvedby`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `problems_solved`
--
ALTER TABLE `problems_solved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_friends`
--
ALTER TABLE `user_friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_messages`
--
ALTER TABLE `user_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `FK_USER_LOGS_ID` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `privileges`
--
ALTER TABLE `privileges`
  ADD CONSTRAINT `fk_user_privileges` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `problems`
--
ALTER TABLE `problems`
  ADD CONSTRAINT `fk_author_id` FOREIGN KEY (`author`) REFERENCES `users` (`id`);

--
-- Constraints for table `problems_approvedby`
--
ALTER TABLE `problems_approvedby`
  ADD CONSTRAINT `fk_approvedBy_id` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_problemApproved_id` FOREIGN KEY (`problem`) REFERENCES `problems` (`id`);

--
-- Constraints for table `problems_solved`
--
ALTER TABLE `problems_solved`
  ADD CONSTRAINT `fk_problem_id` FOREIGN KEY (`problem`) REFERENCES `problems` (`id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `fk_user_details` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_friends`
--
ALTER TABLE `user_friends`
  ADD CONSTRAINT `fk_user_friends` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_friends_friend` FOREIGN KEY (`friend`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD CONSTRAINT `fk_user_messages_from` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_messages_to` FOREIGN KEY (`to`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `usersOnline` ON SCHEDULE EVERY 50 SECOND STARTS '2020-05-07 01:11:40' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE users u
                INNER JOIN user_details ud ON u.id = ud.user
                SET u.online = if(NOW() - ud.lastAct < 50 , 1, 0)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
