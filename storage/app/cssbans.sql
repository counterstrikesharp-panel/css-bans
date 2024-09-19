-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 14, 2024 at 11:32 PM
-- Server version: 5.7.43-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cssbans`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`, `description`, `created_at`, `updated_at`) VALUES
(1, '@css/reservation', 'Reserved slot access.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(2, '@css/generic', 'Generic admin.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(3, '@css/kick', 'Kick other players.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(4, '@css/ban', 'Ban other players.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(5, '@css/unban', 'Remove bans.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(6, '@css/vip', 'General VIP status.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(7, '@css/slay', 'Slay/harm other players.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(8, '@css/changemap', 'Change the map or major gameplay features.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(9, '@css/cvar', 'Change most cvars.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(10, '@css/config', 'Execute config files.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(11, '@css/chat', 'Special chat privileges.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(12, '@css/vote', 'Start or create votes.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(13, '@css/password', 'Set a password on the server.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(14, '@css/rcon', 'Use RCON commands.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(15, '@css/cheats', 'Change sv_cheats or use cheating commands.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(16, '@css/root', 'Magically enables all flags and ignores immunity values.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@css/permban', 'Ban other players for permanently.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@css/showip', 'Show players IP in css_who and css_players commands.', '2024-04-14 15:27:44', '2024-04-14 15:27:44');

INSERT INTO `permissions` (`id`, `permission`, `description`, `created_at`, `updated_at`) VALUES
(NULL, '@web/admin.create', 'Web-only: Permission to create an admin.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/admin.edit', 'Web-only: Permission to edit an admin.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/admin.delete', 'Web-only: Permission to delete an admin.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/ban.add', 'Web-only: Permission to create a ban.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/ban.edit', 'Web-only: Permission to edit a ban.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/ban.unban', 'Web-only: Permission to unban a user.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/group.create', 'Web-only: Permission to create a group.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/group.edit', 'Web-only: Permission to edit a group.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/group.delete', 'Web-only: Permission to delete a group.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/mute.add', 'Web-only: Permission to create a mute.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/mute.edit', 'Web-only: Permission to edit a mute.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/mute.unmute', 'Web-only: Permission to unmute a user.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/group.create', 'Web-only: Permission to create group.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/group.edit', 'Web-only: Permission to edit group.', '2024-04-14 15:27:44', '2024-04-14 15:27:44'),
(NULL, '@web/group.delete', 'Web-only: Permission to delete group.', '2024-04-14 15:27:44', '2024-04-14 15:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `steam_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS rcons;
CREATE TABLE `rcons` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `server_id` int NOT NULL,
 `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS server_visibility_settings;
CREATE TABLE `server_visibility_settings` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`server_id` bigint unsigned NOT NULL,
`is_visible` tinyint(1) NOT NULL DEFAULT '1',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS appeals;
CREATE TABLE `appeals` (
`id` int NOT NULL AUTO_INCREMENT,
`ban_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
`steamid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
`ip` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
`name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
`reason` text COLLATE utf8mb4_general_ci NOT NULL,
`email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
`status` enum('PENDING','APPROVED','REJECTED') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PENDING',
`created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS reports;
CREATE TABLE `reports` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`ban_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`steamid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`nickname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`comments` text COLLATE utf8mb4_unicode_ci NOT NULL,
`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`server_id` bigint unsigned NOT NULL,
`media_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS server_player_stats;
CREATE TABLE `server_player_stats` (
`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
`server_id` bigint(20) unsigned NOT NULL,
`player_count` int(11) NOT NULL,
`map` varchar(50) NOT NULL,
`recorded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS module_server_settings;
CREATE TABLE `module_server_settings` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`module_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`db_host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`db_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`db_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`port` int NOT NULL DEFAULT '3306',
`db_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`active` tinyint(1) DEFAULT '1',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `avatar`, `steam_id`, `created_at`, `updated_at`) VALUES
(1, 'Piko', 'https://avatars.steamstatic.com/483938176623f9bcfc948180a980076667058e24_medium.jpg', 76561199028888055, '2024-04-14 15:25:18', '2024-04-14 15:25:18');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
