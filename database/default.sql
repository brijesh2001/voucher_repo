-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2017 at 10:10 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `default`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_01_03_121135_create_user_role_table', 2),
(4, '2017_01_03_125518_add_new_fields_to_users_table', 3),
(5, '2017_01_12_054946_create_country_table', 4),
(6, '2017_01_12_055537_create_industry_table', 4),
(7, '2017_01_03_122445_create_client_table', 5),
(8, '2017_01_03_124221_create_client_contact_person_table', 5),
(9, '2017_01_11_094231_create_job_positions_table', 6),
(10, '2017_01_19_105652_create_permission_table', 7),
(11, '2017_01_19_114159_create_role_permission_table', 8),
(12, '2017_01_20_094413_add_parent_field_to_users_table', 9),
(13, '2017_01_24_131537_add_softdelete_field_to_users_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_permission`
--

CREATE TABLE `tbl_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Permission Name!',
  `code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Permission short code!',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_permission`
--

INSERT INTO `tbl_permission` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'User Management  :::', 'user_management', '2017-01-19 05:58:17', '2017-02-09 07:00:50'),
(2, 'Role Management', 'role_manage', '2017-01-19 23:48:01', '2017-01-19 23:48:01'),
(3, 'Master Management', 'master_manage', '2017-01-19 23:48:30', '2017-01-19 23:48:30'),
(4, 'Daily Activity Report', 'daily_activity_report', '2017-01-25 02:54:15', '2017-01-25 02:54:15'),
(5, 'Permission Management', 'permission_management', '2017-01-25 02:55:52', '2017-01-25 02:55:52'),
(6, 'Client Management', 'client_management', '2017-01-25 02:56:16', '2017-01-25 02:56:16'),
(7, 'Job Positions Management', 'job_positions_management', '2017-01-25 02:57:13', '2017-01-25 02:57:13'),
(8, 'Threshold For SLAs', 'threshold_for_slas', '2017-01-25 02:58:02', '2017-01-25 02:58:02'),
(9, 'Reports', 'reports', '2017-01-25 02:58:26', '2017-01-25 02:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role_permission`
--

CREATE TABLE `tbl_role_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_role_permission`
--

INSERT INTO `tbl_role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(4, 1, 1, '2017-02-09 06:58:14', '2017-02-09 06:58:14'),
(5, 1, 2, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(6, 1, 3, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(7, 1, 4, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(8, 1, 5, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(9, 1, 6, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(10, 1, 7, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(11, 1, 8, '2017-02-09 06:58:15', '2017-02-09 06:58:15'),
(12, 1, 9, '2017-02-09 06:58:15', '2017-02-09 06:58:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_role`
--

CREATE TABLE `tbl_user_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_type` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User role type!',
  `code` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User role type short code!',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `tbl_user_role`
--

INSERT INTO `tbl_user_role` (`id`, `role_type`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '2017-01-23 03:53:07', '2017-02-09 06:58:14'),
(2, 'Recruitment Consultant', 'RC', '2017-01-23 03:57:41', '2017-01-23 03:57:41'),
(3, 'TeamLead', 'TL', '2017-01-23 03:58:03', '2017-01-23 03:58:03'),
(4, 'Operating Manager', 'OM', '2017-01-23 03:59:23', '2017-01-23 03:59:23'),
(5, 'Assistant Vice President', 'AVP', '2017-01-23 04:01:49', '2017-01-23 04:01:49'),
(6, 'Vice President', 'VP', '2017-01-23 04:02:11', '2017-01-23 04:02:11'),
(7, 'COO', 'COO', '2017-01-23 04:02:28', '2017-01-23 05:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Employee code!',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User first name!',
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User last name!',
  `joining_date` timestamp NULL DEFAULT NULL COMMENT 'User joining date!',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `landline_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User landline number!',
  `mobile_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'User mobile number!',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(10) UNSIGNED DEFAULT NULL COMMENT '0=Inactive,1=Active',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_code`, `name`, `first_name`, `last_name`, `joining_date`, `email`, `role_id`, `parent_id`, `landline_number`, `mobile_number`, `password`, `status`, `created_by`, `updated_by`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '123', 'Admin Ch', 'Admin', 'Ch', '2017-05-27 18:30:00', 'admin@ims.com', 1, 0, '', '8511085150', '$2y$10$N.gv7H1H/ws4xh58ucV9HeJRFYgsmE/OUB8Jpz0GL7NKMGbv6Xk8a', 1, 1, 1, 'YHLsFBmHCxm4hB5oWrAXY2pKFIN9FFgQUaKsYPvEq5nko2gieNOz5X6ehBob', '2017-01-17 01:05:08', '2017-02-09 09:02:01', NULL),
(4, 'Mobio002', 'Mobio admin', 'Mobio', 'admin', '2016-12-11 18:30:00', 'nishit.maheta@mobiosolutions.com', 2, 1, '', '9624491808', '$2y$10$NpvygmHNIT.l9XbS/ntFdOtsGOk52/7UHuOrSC9d9NFCwVvaEaFtO', 1, 1, 1, 'cvvq4Fqe0OirAjlPY4eGZBxsur88SQppAk3x2fduRYNbq45F4cmlszH82SJj', '2017-01-26 03:35:27', '2017-02-09 06:51:21', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `tbl_permission`
--
ALTER TABLE `tbl_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_role_permission`
--
ALTER TABLE `tbl_role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_id` (`role_id`),
  ADD KEY `permissions_id` (`permission_id`);

--
-- Indexes for table `tbl_user_role`
--
ALTER TABLE `tbl_user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_created_by_foreign` (`created_by`),
  ADD KEY `users_updated_by_foreign` (`updated_by`),
  ADD KEY `users_parent_id_foreign` (`parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tbl_permission`
--
ALTER TABLE `tbl_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_role_permission`
--
ALTER TABLE `tbl_role_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tbl_user_role`
--
ALTER TABLE `tbl_user_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
