-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 01:44 PM
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
-- Database: `crm_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_authtokens`
--

CREATE TABLE `xx_crm_authtokens` (
  `ID` int(11) NOT NULL COMMENT 'Primary key, unique token identifier',
  `USER_ID` int(11) NOT NULL COMMENT 'Reference to the user who owns this token',
  `TOKEN` varchar(500) NOT NULL COMMENT 'The authentication token',
  `TOKEN_TYPE` varchar(50) NOT NULL COMMENT 'Type of token (e.g., access, refresh and auth)',
  `EXPIRY` varchar(100) NOT NULL COMMENT 'Timestamp indicating when the token expires',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Record creation timestamp',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last record update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_authtokens`
--

INSERT INTO `xx_crm_authtokens` (`ID`, `USER_ID`, `TOKEN`, `TOKEN_TYPE`, `EXPIRY`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDEwNDYzNH0=.7324b94923f53cabe2d91f1aafe9fea709c50476bce49e7bb9d4b34da00ae592', 'auth', '1730115434', '2024-10-28 04:07:14', '2024-10-28 08:37:14'),
(2, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDExNTUxMX0=.5be0f8d614a83f447e1392cea90a61e28d90128e6678bea5ca0d6d75e2c1a579', 'auth', '1730126311', '2024-10-28 07:08:31', '2024-10-28 11:38:31'),
(3, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDE4MjgwNH0=.14008dadd52d2783110ce4573a1a43dcd10eaafdc00e24b3d4299785b8fa1824', 'auth', '1730193604', '2024-10-29 01:50:04', '2024-10-29 06:20:05'),
(4, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDE4Nzk5OH0=.53b867a20c4cc08d80b343af579e619145272b2ac1d25f1374b4bca82cd886a5', 'auth', '1730198798', '2024-10-29 03:16:38', '2024-10-29 07:46:38'),
(5, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDE4ODA2MX0=.4890b4c489f4bf4c46dace5be99769b1d0e636cecf5e1d06db22611274061165', 'auth', '1730198861', '2024-10-29 03:17:41', '2024-10-29 07:47:41'),
(6, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDIwMTE5N30=.7745ef33844d63ec5f92238bdec51cb8f0df87e97568d65efe2c09dbc692306b', 'auth', '1730211997', '2024-10-29 06:56:37', '2024-10-29 11:26:37'),
(7, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDI2NTk2Nn0=.9271f8d29c215bced834b9834c177bf55086b905d84801f601b9241c7a73b130', 'auth', '1730276766', '2024-10-30 00:56:06', '2024-10-30 05:26:06'),
(8, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDM1NzU0NX0=.6b4064c950914f4f804d36334c25305f61704abb19584dd351144a33a0629ca7', 'auth', '1730368345', '2024-10-31 02:22:25', '2024-10-31 06:52:25'),
(9, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDM3MDE1MH0=.c432d0ea9ee48778e7a7b876a9a31f659261c6a230d54ea89bccab7894059614', 'auth', '1730380950', '2024-10-31 05:52:30', '2024-10-31 10:22:30'),
(11, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDQ0MTY5MX0=.d9a1001154b0bb6fac4b14198458bd60ee8fa01a863a612dee114f8712d094e6', 'auth', '1730452491', '2024-11-01 01:44:51', '2024-11-01 06:14:51'),
(14, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDUyNTUxOH0=.408a6fe493cfb9f6eeeaa8c28e44d128adee3f85bfe0fb8cc94e1fc2feb7a1c9', 'auth', '1730536318', '2024-11-02 01:01:58', '2024-11-02 05:31:58'),
(15, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDUzODEzOX0=.6b191d10ebf21ccb0d7fe0b89f79c21b348143f5b81cb95071b852c6a3dcaf26', 'auth', '1730548939', '2024-11-02 04:32:19', '2024-11-02 09:02:19'),
(16, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDY5NjAwMX0=.285bab74c9df478af29dc2a79ebc725852bbba8ee9ea9291588334257ade0c18', 'auth', '1730706801', '2024-11-04 00:23:21', '2024-11-04 04:53:21'),
(18, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDcwNzA3NX0=.f91b53d6715bda4704e8b00b0a6180b571659f0171ec2f1bac2252265ea0138f', 'auth', '1730717875', '2024-11-04 03:27:55', '2024-11-04 07:57:55'),
(19, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDc4NTM5Nn0=.4ae1f34b9b0f14fff46302a86bda35f91c5958b11b370fa3d14e1300c7c0fe36', 'auth', '1730796196', '2024-11-05 01:13:16', '2024-11-05 05:43:16'),
(20, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDc5OTA4Nn0=.f214d57f62574b754257cc185b28d73d32ec9d708e3de4b80116ffeb2dd48809', 'auth', '1730809886', '2024-11-05 05:01:26', '2024-11-05 09:31:26'),
(21, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDgwOTg5Mn0=.0c0647fd7c82751385fe7f5b35f84410e7449dbe46d7b8905d0414583a924296', 'auth', '1730820692', '2024-11-05 08:01:32', '2024-11-05 12:31:32'),
(22, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDg2ODg5NH0=.0d82c4bdf1ae14e54e234077d335513429b4ce00824934078f216ab841b6f903', 'auth', '1730879694', '2024-11-06 00:24:54', '2024-11-06 04:54:54'),
(23, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDg3OTgzOX0=.7e457bd9ec1c64152e859eca0b0be3c546fdc97442dc59747a7830956c37dd90', 'auth', '1730890639', '2024-11-06 03:27:19', '2024-11-06 07:57:19'),
(24, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDg5MDY5NH0=.f50127266465e81d2a38a8c646fd7c5852627bd48049c5433dfe0c5ef379aedc', 'auth', '1730901494', '2024-11-06 06:28:14', '2024-11-06 10:58:14'),
(26, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDk1NzMxOH0=.890be0920afee61a47f15b3a0b5649795b5dccfea435cd9102b08769c448c5e2', 'auth', '1730968118', '2024-11-07 00:58:38', '2024-11-07 05:28:38'),
(27, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDk2OTQ5M30=.7a1eee3264393c3e440e77e21165ff9d754c2e034f64c3d7779bd31f614825d8', 'auth', '1730980293', '2024-11-07 04:21:33', '2024-11-07 08:51:33'),
(28, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMDk4MjMxOH0=.d3e3a308bfd854a02e84936fd9e48a616e4fc714d94ace3c4b32309b8470e528', 'auth', '1730993118', '2024-11-07 07:55:18', '2024-11-07 12:25:18'),
(29, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTEzODkzMH0=.4131924509f2d607fb1bdac963656fec1ad0347ec9228cecc4466f3560454686', 'auth', '1731149730', '2024-11-09 03:25:30', '2024-11-09 07:55:30'),
(30, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTE1MTM2M30=.d2cf6400f1c27bdcf5daa5d3a726afbe6a68ad58856e50ef00e16c730f042c4b', 'auth', '1731162163', '2024-11-09 06:52:43', '2024-11-09 11:22:43'),
(31, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTMxOTY0M30=.0ef016f3d64d83eee9114d7f4ea7fc748c1b8d9c7a560a55e40d119a6c11d711', 'auth', '1731330443', '2024-11-11 05:37:23', '2024-11-11 10:07:23'),
(32, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTMzMTMzM30=.4ed580c656581fbeb7d3febca90ca806cb42264aa87e3383a50189865bea3c84', 'auth', '1731342133', '2024-11-11 08:52:13', '2024-11-11 13:22:13'),
(33, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTM5MDgxMX0=.399fa203ba57f86f228ebda9e0e12f6b5ef12cd51aa4ae03ba23aaaf61ede864', 'auth', '1731401611', '2024-11-12 01:23:31', '2024-11-12 05:53:31'),
(34, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTQwMjU2N30=.5066553cfd20935853f2ddb753bc6702e0dd777906546ae672b36cbca4306226', 'auth', '1731413367', '2024-11-12 04:39:27', '2024-11-12 09:09:27'),
(35, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTQxMzg2NH0=.9f2681b351475af01bf899541cacaf029043a563b51efe1e0f70f4b29150ce34', 'auth', '1731424664', '2024-11-12 07:47:44', '2024-11-12 12:17:44'),
(36, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTQ3NzYzNH0=.a9f839daf184af7144b7f88d845a6e308a2c5f5f9e25184a7f34fee7c8439dd3', 'auth', '1731488434', '2024-11-13 01:30:34', '2024-11-13 06:00:34'),
(37, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTQ4ODYwNn0=.21d49ed210864b673f49764dc71de812b71d3e00d49ffdba6d08a6362e3fb3fb', 'auth', '1731499406', '2024-11-13 04:33:26', '2024-11-13 09:03:26'),
(38, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTUwMTc3OH0=.657a15777d8389385c60fe51f9fd031d972f9b27a1209303454e6687a2426139', 'auth', '1731512578', '2024-11-13 08:12:58', '2024-11-13 12:42:58'),
(39, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTU2MjIzMn0=.55584cf8619834fb1dfb56c6410c0d494bebd40f8334b791004f5a8ddc09d441', 'auth', '1731573032', '2024-11-14 01:00:32', '2024-11-14 05:30:32'),
(40, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTU3MzA1MX0=.51472c51c706aad71c95b50ff6d96b63b0d4c9f35c7ba534c652fea3b9ed7383', 'auth', '1731583851', '2024-11-14 04:00:51', '2024-11-14 08:30:51'),
(41, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTU4NDgxOH0=.29ed6d1ba498abeb091d262c114de67b85b1c2f9e85403d239c42daaff277d7d', 'auth', '1731595618', '2024-11-14 07:16:58', '2024-11-14 11:46:58'),
(42, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTY1MTIxMH0=.72485dc0f08a31bf64a744309c04504ddfc3a8d9b69445c8feb3b8484940120a', 'auth', '1731662010', '2024-11-15 01:43:30', '2024-11-15 06:13:30'),
(43, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTY2NDk0Mn0=.c2c5904a8111be205b31c1eb8bc873191385c30162dce36417da4618e2f067b9', 'auth', '1731675742', '2024-11-15 05:32:22', '2024-11-15 10:02:22'),
(44, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTczNzUwMX0=.bf9dc4f2818885a6be6b895e015cafd6c2e8057206218bf966005b5bac743a1e', 'auth', '1731748301', '2024-11-16 01:41:41', '2024-11-16 06:11:41'),
(45, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMTkyNDQyNH0=.acfe7987044b5509394556876e9653394380dbc702e38f04867950f413feec38', 'auth', '1731935224', '2024-11-18 05:37:04', '2024-11-18 10:07:04'),
(47, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjE4ODA4Mn0=.dba313568672052bfdf4a52c886a66e39b476bf76dbdbe575637b8df5135726a', 'auth', '1732198882', '2024-11-21 06:51:22', '2024-11-21 11:21:22'),
(48, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjE4ODI1Nn0=.315b768cb71b6225ad9908656dbf87b17d28e5e10b91e365256a3f7b10173a61', 'auth', '1732199056', '2024-11-21 06:54:16', '2024-11-21 11:24:16'),
(49, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjE4ODQzOX0=.0785b1bc726cef4269ebc1a70aa113bb6ab8870a5b5c10a5d631076aed32d4d7', 'auth', '1732199239', '2024-11-21 06:57:19', '2024-11-21 11:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_client_address`
--

CREATE TABLE `xx_crm_client_address` (
  `ID` int(11) NOT NULL,
  `CLIENT_ID` int(11) NOT NULL,
  `ADDRESS_LINE_1` varchar(255) NOT NULL,
  `ADDRESS_LINE_2` varchar(255) DEFAULT NULL,
  `BILLING_ADDRESS` varchar(255) NOT NULL,
  `SHIPPING_ADDRESS` varchar(255) NOT NULL,
  `CITY` varchar(100) NOT NULL,
  `STATE` varchar(100) NOT NULL,
  `COUNTRY` varchar(100) NOT NULL,
  `ZIP_CODE` varchar(20) NOT NULL,
  `IS_DEFAULT_BILLING` tinyint(1) DEFAULT 0,
  `IS_DEFAULT_SHIPPING` tinyint(1) DEFAULT 0,
  `CREATED_AT` datetime DEFAULT current_timestamp(),
  `CREATED_BY` varchar(100) NOT NULL,
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UPDATED_BY` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xx_crm_client_address`
--

INSERT INTO `xx_crm_client_address` (`ID`, `CLIENT_ID`, `ADDRESS_LINE_1`, `ADDRESS_LINE_2`, `BILLING_ADDRESS`, `SHIPPING_ADDRESS`, `CITY`, `STATE`, `COUNTRY`, `ZIP_CODE`, `IS_DEFAULT_BILLING`, `IS_DEFAULT_SHIPPING`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(1, 4, 'Address Line 1, Address Street', 'Address Line 2, Dammam', 'Billing Address, Line 1', 'Shipping Address, Line 2', 'Dammam', 'East', 'Kingdom of SA', '45215', 0, 0, '2024-11-06 12:45:08', '', '2024-11-06 13:43:54', ''),
(3, 5, 'Riyadh Address Line1', 'Riyadh Address Line 2', 'Jeddah Billing Address', 'Riyadh Shipping Address', 'Riyadh', 'East', 'KSA', '78422', 0, 0, '2024-11-15 12:39:03', '', '2024-11-15 12:39:03', ''),
(4, 6, 'Pune LIne ', ' ', 'Billing Address, Line 1', 'Shipping Address, Line 2', 'Dhule', 'Maharashtra', 'India', '424001', 0, 0, '2024-11-15 15:34:03', '', '2024-11-15 15:34:03', ''),
(5, 7, 'Address Line 1, Address Street', '100 Feet Road', 'Address Line 1, Address Street', 'Hxjxjxj', 'Dhule', 'Maharashtra', 'India', '424001', 0, 0, '2024-11-15 15:38:43', '', '2024-11-15 15:38:43', '');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_client_detail`
--

CREATE TABLE `xx_crm_client_detail` (
  `ID` int(11) NOT NULL,
  `CLIENT_ID` varchar(50) NOT NULL,
  `COMPANY_NAME` varchar(255) NOT NULL,
  `SITE_NAME` varchar(255) NOT NULL,
  `PAYMENT_TERM` varchar(100) NOT NULL,
  `CREDIT_LIMIT` decimal(10,2) NOT NULL,
  `TAXES` decimal(10,2) NOT NULL,
  `CURRENCY` varchar(10) NOT NULL,
  `ORDER_LIMIT` decimal(10,2) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `CREATED_AT` datetime DEFAULT current_timestamp(),
  `CREATED_BY` varchar(100) NOT NULL,
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UPDATED_BY` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_client_detail`
--

INSERT INTO `xx_crm_client_detail` (`ID`, `CLIENT_ID`, `COMPANY_NAME`, `SITE_NAME`, `PAYMENT_TERM`, `CREDIT_LIMIT`, `TAXES`, `CURRENCY`, `ORDER_LIMIT`, `USER_ID`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(2, 'CL-000004', 'Kismatji Info Pvt Ltd', 'Kissu Main Site', 'CASH', 100000.00, 15.00, 'SAR', 45000.00, 4, '2024-11-06 12:45:08', '', '2024-11-06 13:43:54', ''),
(4, 'CL-000005', 'Test Company Pvt Ltd', 'Jeddah', 'Credit', 451200.00, 15.00, 'SAR', 2000.00, 5, '2024-11-15 12:39:02', '', '2024-11-15 12:39:02', ''),
(5, 'CL-000006', 'Olivesofts', 'Pune Branch', 'CASH', 100000.00, 15.00, 'SAR', 45000.00, 6, '2024-11-15 15:34:03', '', '2024-11-15 15:34:03', ''),
(6, 'CL-000007', 'Kismatji Beauty World', 'Madinah Main Site', 'CASH', 100000.00, 15.00, 'SAR', 45000.00, 7, '2024-11-15 15:38:43', '', '2024-11-15 15:38:43', '');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_products`
--

CREATE TABLE `xx_crm_products` (
  `PRODUCT_ID` int(11) NOT NULL COMMENT 'Auto incremenet id for products',
  `UUID` varchar(100) NOT NULL COMMENT 'Unique code for the product for reference in UUIDv4',
  `PRODUCT_CODE` varchar(50) NOT NULL COMMENT 'Unique code for the product',
  `DIVISION` varchar(100) NOT NULL COMMENT 'Division under which the product is categorized',
  `CATEGORY_ID` int(11) NOT NULL COMMENT 'Foreign key referencing the product category',
  `STATUS` enum('active','inactive','discontinued') NOT NULL COMMENT 'Status of the product',
  `PRODUCT_NAME` varchar(255) NOT NULL COMMENT 'Name of the product',
  `DESCRIPTION` text DEFAULT NULL COMMENT 'Detailed description of the product',
  `BASE_PRICE` decimal(10,2) NOT NULL COMMENT 'Base price of the product',
  `CURRENCY` varchar(10) NOT NULL COMMENT 'Currency code for the price, e.g., USD, EUR',
  `DISCOUNT_TYPE` enum('fixed','percentage','no discount') DEFAULT NULL COMMENT 'Type of discount applicable to the product',
  `DISCOUNT_PERCENTAGE` decimal(5,2) DEFAULT NULL COMMENT 'Percentage discount on the product price',
  `TAXABLE` enum('yes','no') DEFAULT NULL COMMENT 'Indicates if the product is taxable',
  `TAX_PERCENTAGE` decimal(5,2) DEFAULT NULL COMMENT 'Tax percentage applicable to the product',
  `PRODUCT_IMAGES` text DEFAULT NULL COMMENT 'Comma-separated URLs or paths of product images',
  `WEIGHT` decimal(10,2) DEFAULT NULL COMMENT 'Weight of the product',
  `HEIGHT` decimal(10,2) DEFAULT NULL COMMENT 'Height of the product',
  `LENGTH` decimal(10,2) DEFAULT NULL COMMENT 'Length of the product',
  `WIDTH` varchar(100) DEFAULT NULL,
  `CREATED_BY` int(11) NOT NULL COMMENT 'User ID of the creator of the product',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp for when the product was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp for when the product was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table to store product details, including pricing, descriptions, and categorization.';

--
-- Dumping data for table `xx_crm_products`
--

INSERT INTO `xx_crm_products` (`PRODUCT_ID`, `UUID`, `PRODUCT_CODE`, `DIVISION`, `CATEGORY_ID`, `STATUS`, `PRODUCT_NAME`, `DESCRIPTION`, `BASE_PRICE`, `CURRENCY`, `DISCOUNT_TYPE`, `DISCOUNT_PERCENTAGE`, `TAXABLE`, `TAX_PERCENTAGE`, `PRODUCT_IMAGES`, `WEIGHT`, `HEIGHT`, `LENGTH`, `WIDTH`, `CREATED_BY`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, '2ab61a2d-5896-4a0b-9720-15d33bde9df3', '281024000001', '242', 1, 'active', 'FG Product - Lead Semi Conductor', '<h1>Description:</h1><p>This is the description of the product, with some <strong>bold</strong> and some <em>italic</em> formatting.</p>', 12.00, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"survey2.png\"]', 3.50, 0.00, 12.00, '12', 1, '2024-10-28 13:18:06', '2024-11-05 08:03:15'),
(2, '3b1af1bc-bfbf-49d5-876a-ed9640df8bbc', '291024000002', '242', 2, 'inactive', 'SF Semi Finished', '<p>Semi Finished good with IBM</p>', 123.00, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"avatar2.png\",\"avatar-user2.png\",\"bg-patterns2.png\"]', 0.10, 0.00, 30.00, '12', 1, '2024-10-29 08:52:15', '2024-11-05 08:04:09'),
(3, '93c2b033-0dd5-42d4-aa07-a0fc0653d540', '291024000003', '242', 1, 'active', 'SF Semi Finished', 'null', 123.00, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"default-image1.png\",\"favicon1.png\"]', 0.00, NULL, 0.00, '', 1, '2024-10-29 09:35:29', '2024-10-29 09:35:30'),
(4, '6700119b-456d-4dd1-85b7-ac147e5e76c8', '291024000004', '242', 1, 'active', 'Black Lead', 'null', 123.00, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"avatar1.png\",\"avatar-user1.png\",\"bg-patterns1.png\",\"default-image2.png\",\"favicon3.png\",\"graph1.png\",\"login-side.png\",\"survey.png\"]', 0.00, 0.00, 0.00, '', 1, '2024-10-29 09:52:54', '2024-11-04 08:52:33'),
(5, '994237a0-4992-4443-80a3-d7e7353238c9', '021124000005', '242', 2, 'active', 'Test Product Name - under semi  finished goods', '<h3>Product Description:</h3><p>This product false under category <strong>SF</strong> which is <em>Semi Finished</em> good.</p><p><br></p><p>And this is the main base product to order from the company which is in <u>low</u> price.</p>', 1.30, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"survey1.png\"]', 0.30, 0.00, 12.00, '120', 1, '2024-11-02 09:08:50', '2024-11-04 08:41:43'),
(7, 'd287478f-4b36-44ac-8330-d4b1c12be53a', '051124000007', '444', 2, 'active', 'Kismat Ji Product', '<h1>Kismat Ji</h1><p><strong>Lorem ipsum dolor sit amet,</strong> consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum. Nulla facilisi. Sed sit amet accumsan arcu. In non felis justo. Mauris varius tortor vel mi elementum, nec aliquam arcu lacinia. Duis dapibus, purus vel convallis auctor, <u>nisi </u>massa volutpat mi, vitae gravida libero eros in justo.</p><p><br></p><p><em>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</em></p>', 123.00, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"avatar3.png\",\"default-image3.png\",\"zpil-logo.png\"]', 0.30, 120.00, 120.00, '120', 1, '2024-11-05 08:06:22', '2024-11-05 08:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_product_categories`
--

CREATE TABLE `xx_crm_product_categories` (
  `ID` int(11) NOT NULL COMMENT 'Unique identifier for each product category',
  `CATEGORY_CODE` varchar(50) NOT NULL,
  `CATEGORY_NAME` varchar(255) NOT NULL COMMENT 'Name of the product category, e.g., Electronics, Apparel, etc.',
  `DESCRIPTION` text DEFAULT NULL COMMENT 'Optional description providing more details about the product category',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp for when the product category was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp for when the product category was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table to store product categories for the CRM application. Used to classify different products into categories like Electronics, Furniture, etc.';

--
-- Dumping data for table `xx_crm_product_categories`
--

INSERT INTO `xx_crm_product_categories` (`ID`, `CATEGORY_CODE`, `CATEGORY_NAME`, `DESCRIPTION`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 'FG', 'Finished Goods', 'Finished Goods For Food Deaprtment', '2024-10-26 10:19:26', '2024-10-26 10:19:26'),
(2, 'SF', 'SemiFinished Goods', NULL, '2024-10-26 10:19:49', '2024-10-26 10:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_product_inventory`
--

CREATE TABLE `xx_crm_product_inventory` (
  `INVENTORY_ID` int(11) NOT NULL COMMENT 'Unique identifier for each product inventory record',
  `PRODUCT_ID` int(11) NOT NULL COMMENT 'Unique identifier of product',
  `SKU` varchar(50) NOT NULL COMMENT 'Stock Keeping Unit, unique identifier for inventory tracking',
  `MIN_QTY` int(11) NOT NULL COMMENT 'Minimum quantity threshold for inventory',
  `MAX_QTY` int(11) NOT NULL COMMENT 'Maximum quantity limit for inventory',
  `AVL_QTY` int(11) NOT NULL COMMENT 'Available quantity of the product in inventory',
  `WAREHOUSE` varchar(255) DEFAULT NULL COMMENT 'Warehouse location of the product inventory',
  `BARCODE` varchar(50) DEFAULT NULL COMMENT 'Barcode for the product',
  `ALLOW_BACKORDERS` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indicates if backorders are allowed for this product',
  `CREATED_BY` int(11) NOT NULL COMMENT 'User ID of the creator of the inventory record',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp for when the inventory record was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp for when the inventory record was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table to store inventory details for each product.';

--
-- Dumping data for table `xx_crm_product_inventory`
--

INSERT INTO `xx_crm_product_inventory` (`INVENTORY_ID`, `PRODUCT_ID`, `SKU`, `MIN_QTY`, `MAX_QTY`, `AVL_QTY`, `WAREHOUSE`, `BARCODE`, `ALLOW_BACKORDERS`, `CREATED_BY`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 1, '', 12, 12, 12, NULL, '', 0, 0, '2024-10-28 13:18:06', '2024-10-28 13:18:06'),
(2, 2, '', 10, 10000, 10000, NULL, '', 0, 0, '2024-10-29 08:52:15', '2024-11-05 08:04:09'),
(3, 3, '', 0, 0, 0, NULL, '', 0, 0, '2024-10-29 09:35:30', '2024-10-29 09:35:30'),
(4, 4, '', 12, 12, 12, NULL, '', 0, 0, '2024-10-29 09:52:54', '2024-10-29 09:52:54'),
(5, 5, '', 10, 100, 10300, NULL, '', 0, 0, '2024-11-02 09:08:50', '2024-11-04 08:41:43'),
(7, 7, '', 12, 100, 120, NULL, '', 0, 0, '2024-11-05 08:06:22', '2024-11-05 08:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_product_variants`
--

CREATE TABLE `xx_crm_product_variants` (
  `VARIANT_ID` int(11) NOT NULL COMMENT 'unique identifier for the product variant',
  `PRODUCT_ID` int(11) NOT NULL COMMENT 'Foreign key referencing the main product',
  `COLOR` varchar(50) DEFAULT NULL COMMENT 'Color variant of the product',
  `SIZE` varchar(50) DEFAULT NULL COMMENT 'Size variant of the product',
  `MATERIAL` varchar(50) DEFAULT NULL COMMENT 'Material variant of the product',
  `CREATED_BY` int(11) NOT NULL COMMENT 'User ID of the creator of the variant record',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp for when the variant was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp for when the variant was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table to store product variants, such as different colors, sizes, and materials for each product.';

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_req_header`
--

CREATE TABLE `xx_crm_req_header` (
  `ID` int(11) NOT NULL COMMENT 'Auto-incremented primary key for each request header',
  `REQUEST_NUMBER` varchar(50) NOT NULL COMMENT 'Unique identifier for the request, combination of numbers and text',
  `UUID` varchar(100) NOT NULL COMMENT 'Unique identifier UUID v4',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'ID of the client',
  `REQUEST_TITLE` varchar(255) NOT NULL COMMENT 'Title of the request',
  `COMPANY_ADDRESS` text DEFAULT NULL COMMENT 'Address of the company',
  `BILLING_ADDRESS` text DEFAULT NULL COMMENT 'Billing address for the request',
  `SHIPPING_ADDRESS` text DEFAULT NULL COMMENT 'Shipping address for the request',
  `CONTACT_NUMBER` varchar(20) DEFAULT NULL COMMENT 'Mobile contact number',
  `EMAIL_ADDRESS` varchar(100) DEFAULT NULL COMMENT 'Email contact address',
  `REQUEST_DETAILS` text DEFAULT NULL COMMENT 'Detailed description of the request',
  `INTERNAL_NOTES` text DEFAULT NULL COMMENT 'Internal notes for the request, not visible to client',
  `ATTACHMENTS` varchar(500) DEFAULT NULL COMMENT 'Attachments in JSON format string',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the request was created',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User ID of the creator of the request',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last update',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User ID of the person who last updated the request',
  `STATUS` varchar(20) DEFAULT NULL COMMENT 'Current status of the request',
  `ACTION_BY` varchar(20) DEFAULT NULL COMMENT 'User type responsible for taking action on the request',
  `VERSION` varchar(20) DEFAULT NULL COMMENT 'Version of the request record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_req_header`
--

INSERT INTO `xx_crm_req_header` (`ID`, `REQUEST_NUMBER`, `UUID`, `CLIENT_ID`, `REQUEST_TITLE`, `COMPANY_ADDRESS`, `BILLING_ADDRESS`, `SHIPPING_ADDRESS`, `CONTACT_NUMBER`, `EMAIL_ADDRESS`, `REQUEST_DETAILS`, `INTERNAL_NOTES`, `ATTACHMENTS`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`, `STATUS`, `ACTION_BY`, `VERSION`) VALUES
(5, 'REQ-131124000005', '2829e87c-7466-40a4-8755-421b75e313f4', 4, 'Request For Following Products in Kismatji', 'Kismatji Info Pvt Ltd', 'Address Line 1, Address Street', 'Shipping Address, Line 2 Address', '8805629207', 'user1@crm.live', 'Request comments - lorem ipsum doller sit amet, that can be illustrated and shown in the lorem text ipsum millets this is request', 'this is the internal notes for the request ', '[\"OEE_OLD_MODEL_SCRIPT.txt\",\"Semester_1_Papers.xlsx\",\"user-profile-icon-avatar.jpg\",\"avatar.png\",\"avatar-user.png\",\"avatar-user-placeholder.PNG\"]', '2024-11-13 07:01:33', 1, '2024-11-14 07:57:25', NULL, 'draft', 'admin', '1'),
(7, 'REQ-151124000007', '2f89c632-b7fe-44eb-b23d-8849c42f6a85', 7, 'Kismatji Beauty World Product Quotes', 'Kismatji Beauty World', 'Address Line 1, Address Street', 'Hxjxjxj', '7854215487', 'kis.beauty@kisworld.com', 'This request is made from kismatji world beauty products.', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat nulla porro voluptate eius, sit harum ad provident aliquid veniam officiis cum odit culpa esse deserunt rerum, nisi maiores. Consectetur voluptate nesciunt deserunt aliquam sequi at et! Temporibus quasi, quos vero repellendus deserunt quis totam quibusdam quia, ipsum perspiciatis dolores nam earum tempore natus aliquid incidunt fugit fugiat assumenda quo eos tenetur. Vitae nesciunt dolorum a obcaecati dicta, deserunt iste saepe? Hic delectus dolore at rem, voluptates itaque sunt magnam. Optio eveniet deserunt illo ut iste commodi eius est accusamus quam in officiis aliquam, consectetur ipsa, sequi nesciunt modi voluptas voluptatibus.', '[\"avatar1.png\",\"avatar-user1.png\",\"avatar-user-placeholder1.PNG\",\"user-profile-icon-avatar1.jpg\"]', '2024-11-15 10:09:19', 1, '2024-11-15 10:11:00', NULL, 'draft', 'admin', '1');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_req_lines`
--

CREATE TABLE `xx_crm_req_lines` (
  `LINE_ID` int(11) NOT NULL COMMENT 'Auto-incremented primary key for each request line',
  `REQ_ID` int(11) NOT NULL COMMENT 'Foreign key linking to XX_CRM_REQ_HEADER.ID',
  `PRODUCT_ID` varchar(255) NOT NULL COMMENT 'ID of the product',
  `PRODUCT_DESC` varchar(500) DEFAULT NULL COMMENT 'Description of the product',
  `QUANTITY` int(11) NOT NULL COMMENT 'Quantity requested',
  `REQUIRED_DATE` date DEFAULT NULL COMMENT 'Date by which the product is required',
  `COLOR` varchar(50) DEFAULT NULL COMMENT 'Preferred color of the product',
  `TRANSPORTATION` varchar(100) DEFAULT NULL COMMENT 'Preferred transportation mode',
  `COMMENTS` text DEFAULT NULL COMMENT 'Additional comments or instructions'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_req_lines`
--

INSERT INTO `xx_crm_req_lines` (`LINE_ID`, `REQ_ID`, `PRODUCT_ID`, `PRODUCT_DESC`, `QUANTITY`, `REQUIRED_DATE`, `COLOR`, `TRANSPORTATION`, `COMMENTS`) VALUES
(13, 5, '7', 'Kismat JiLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum. Nulla facilisi. Sed sit amet accumsan arcu. In non felis justo. Mauris varius tortor vel mi elementum, nec aliquam arcu lacinia. Duis dapibus, purus vel convallis auctor, nisi massa volutpat mi, vitae gravida libero eros in justo.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et q', 130, '2024-11-15', 'Black', 'Ship', ''),
(14, 5, '2', 'Semi Finished good with IBM', 100, '2024-11-15', 'White', 'Ship', ''),
(15, 5, '5', 'Product Description:This product false under category SF which is Semi Finished good.And this is the main base product to order from the company which is in low price.', 100, '2024-11-16', 'Black', 'Shipping', ''),
(18, 7, '7', 'Kismat JiLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum. Nulla facilisi. Sed sit amet accumsan arcu. In non felis justo. Mauris varius tortor vel mi elementum, nec aliquam arcu lacinia. Duis dapibus, purus vel convallis auctor, nisi massa volutpat mi, vitae gravida libero eros in justo.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et q', 1000, '2024-11-23', 'All', 'Shipping', '');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_unit_of_measurement`
--

CREATE TABLE `xx_crm_unit_of_measurement` (
  `UOM_ID` int(11) NOT NULL COMMENT 'Unique identifier for the unit of measurement',
  `UOM_CODE` varchar(10) NOT NULL COMMENT 'Short code for the unit of measurement (e.g., KG, L)',
  `UOM_DESCRIPTION` varchar(100) NOT NULL COMMENT 'Detailed description of the unit of measurement',
  `UOM_TYPE` enum('Weight','Volume','Length','Area','Count','Time') NOT NULL COMMENT 'Type of measurement',
  `CONVERSION_FACTOR` decimal(10,4) DEFAULT 1.0000 COMMENT 'Factor to convert this unit to the base unit',
  `BASE_UOM_ID` int(11) DEFAULT NULL COMMENT 'Reference to the base unit of measurement (nullable for base units)',
  `IS_ACTIVE` tinyint(1) DEFAULT 1 COMMENT 'Status of the unit (1 = active, 0 = inactive)',
  `CREATED_AT` datetime DEFAULT current_timestamp() COMMENT 'Timestamp for when the record was created',
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp for when the record was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_users`
--

CREATE TABLE `xx_crm_users` (
  `ID` int(11) NOT NULL COMMENT 'Primary key, unique user identifier',
  `USER_ID` varchar(30) DEFAULT NULL COMMENT 'User-specific ID, can be used for referencing or custom identifiers',
  `UUID` char(50) NOT NULL COMMENT 'Unique identifier for the user',
  `USER_TYPE` enum('admin','client','employee','vendor','subadmin','co-admin') NOT NULL COMMENT 'Type of user based on role in the CRM system',
  `FIRST_NAME` varchar(100) NOT NULL COMMENT 'User’s first name',
  `LAST_NAME` varchar(100) NOT NULL COMMENT 'User’s last name',
  `EMAIL` varchar(150) NOT NULL COMMENT 'User’s email address, must be unique',
  `PASSWORD` varchar(500) NOT NULL COMMENT 'Encrypted user password',
  `PHONE_NUMBER` varchar(15) DEFAULT NULL COMMENT 'User’s phone number (optional)',
  `STATUS` enum('active','inactive','suspended','locked') DEFAULT 'active' COMMENT 'Current status of the user',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Record creation timestamp',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last record update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_users`
--

INSERT INTO `xx_crm_users` (`ID`, `USER_ID`, `UUID`, `USER_TYPE`, `FIRST_NAME`, `LAST_NAME`, `EMAIL`, `PASSWORD`, `PHONE_NUMBER`, `STATUS`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 'U-10001', 'c06a7626-5dc0-425e-8e63-582137615e14', 'admin', 'Sys', 'Admin', 'admin@crm.live', '$argon2id$v=19$m=65536,t=4,p=1$VkplbmY1OWo2WlAvZ29sMA$tcRAXH8dgNFWGMiV35P91vLyAM9j0ynsJjIrKvBCiwQ', '8805629207', 'active', '2024-10-23 09:41:44', '2024-11-05 13:04:42'),
(4, 'CL-000004', '3d3b23ae-e436-4c82-a089-4de58f63a9e9', 'client', 'Ab Azim', 'Shaikh', 'user1@crm.live', '$argon2id$v=19$m=65536,t=4,p=1$OTkvbzJZRGJDUVlEVEVaSQ$Ak/GeLdeDR5Ck4mP3A3nDpkI54k7xK945vgeheqYiag', '8805629207', 'active', '2024-11-06 07:15:08', '2024-11-06 08:21:57'),
(5, 'CL-000005', '27b01186-66ff-413c-b066-ab0cec5f43d3', 'client', 'Client', 'Name', 'test.main@cm.com', '$argon2id$v=19$m=65536,t=4,p=1$L0tpSURwRUV5Mm9uVFNyZQ$Vn3IVFPzaHc573UTOS5cvb8fIWLS+eu7mf9ooCxi7pU', '966 56214584', 'active', '2024-11-15 07:09:02', '2024-11-15 07:09:02'),
(6, 'CL-000006', '6663eff5-15cc-4f4f-a897-dd46d85c47f4', 'client', 'Noman', 'Mirza', 'nam@ols.com', '$argon2id$v=19$m=65536,t=4,p=1$VFFKUHMyc3lWWUpHaDJ2dw$YiUWKZWdNf9PSggnepebE4Pf0qjXhMV6RE5Ef8WgqCI', '7845215487', 'active', '2024-11-15 10:04:02', '2024-11-15 10:04:03'),
(7, 'CL-000007', '9b921713-8362-427c-81dd-427008e84c12', 'client', 'Ab Azim', 'Shaikh', 'kis.beauty@kisworld.com', '$argon2id$v=19$m=65536,t=4,p=1$QTUwTEhzTHFtUXh6TE9Gaw$Ts7BiCdkAALJ7hOOfyhCndPb23WTB8DSMj8m7OmYbx4', '7854215487', 'active', '2024-11-15 10:08:43', '2024-11-15 10:08:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `xx_crm_authtokens`
--
ALTER TABLE `xx_crm_authtokens`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `xx_crm_client_address`
--
ALTER TABLE `xx_crm_client_address`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CLIENT_ID` (`CLIENT_ID`);

--
-- Indexes for table `xx_crm_client_detail`
--
ALTER TABLE `xx_crm_client_detail`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `xx_crm_products`
--
ALTER TABLE `xx_crm_products`
  ADD PRIMARY KEY (`PRODUCT_ID`);

--
-- Indexes for table `xx_crm_product_categories`
--
ALTER TABLE `xx_crm_product_categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `xx_crm_product_inventory`
--
ALTER TABLE `xx_crm_product_inventory`
  ADD PRIMARY KEY (`INVENTORY_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `xx_crm_product_variants`
--
ALTER TABLE `xx_crm_product_variants`
  ADD PRIMARY KEY (`VARIANT_ID`),
  ADD KEY `PRODUCT_ID` (`PRODUCT_ID`);

--
-- Indexes for table `xx_crm_req_header`
--
ALTER TABLE `xx_crm_req_header`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `xx_crm_req_lines`
--
ALTER TABLE `xx_crm_req_lines`
  ADD PRIMARY KEY (`LINE_ID`),
  ADD KEY `FK_REQ_HEADER` (`REQ_ID`);

--
-- Indexes for table `xx_crm_unit_of_measurement`
--
ALTER TABLE `xx_crm_unit_of_measurement`
  ADD PRIMARY KEY (`UOM_ID`),
  ADD KEY `BASE_UOM_ID` (`BASE_UOM_ID`);

--
-- Indexes for table `xx_crm_users`
--
ALTER TABLE `xx_crm_users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `xx_crm_authtokens`
--
ALTER TABLE `xx_crm_authtokens`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, unique token identifier', AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `xx_crm_client_address`
--
ALTER TABLE `xx_crm_client_address`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `xx_crm_client_detail`
--
ALTER TABLE `xx_crm_client_detail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `xx_crm_products`
--
ALTER TABLE `xx_crm_products`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto incremenet id for products', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `xx_crm_product_categories`
--
ALTER TABLE `xx_crm_product_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each product category', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `xx_crm_product_inventory`
--
ALTER TABLE `xx_crm_product_inventory`
  MODIFY `INVENTORY_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each product inventory record', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `xx_crm_product_variants`
--
ALTER TABLE `xx_crm_product_variants`
  MODIFY `VARIANT_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for the product variant';

--
-- AUTO_INCREMENT for table `xx_crm_req_header`
--
ALTER TABLE `xx_crm_req_header`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incremented primary key for each request header', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `xx_crm_req_lines`
--
ALTER TABLE `xx_crm_req_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incremented primary key for each request line', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `xx_crm_unit_of_measurement`
--
ALTER TABLE `xx_crm_unit_of_measurement`
  MODIFY `UOM_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the unit of measurement';

--
-- AUTO_INCREMENT for table `xx_crm_users`
--
ALTER TABLE `xx_crm_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, unique user identifier', AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `xx_crm_authtokens`
--
ALTER TABLE `xx_crm_authtokens`
  ADD CONSTRAINT `xx_crm_authtokens_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `xx_crm_users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `xx_crm_client_address`
--
ALTER TABLE `xx_crm_client_address`
  ADD CONSTRAINT `xx_crm_client_address_ibfk_1` FOREIGN KEY (`CLIENT_ID`) REFERENCES `xx_crm_client_detail` (`USER_ID`);

--
-- Constraints for table `xx_crm_client_detail`
--
ALTER TABLE `xx_crm_client_detail`
  ADD CONSTRAINT `xx_crm_client_detail_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `xx_crm_users` (`ID`);

--
-- Constraints for table `xx_crm_product_inventory`
--
ALTER TABLE `xx_crm_product_inventory`
  ADD CONSTRAINT `xx_crm_product_inventory_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `xx_crm_products` (`PRODUCT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `xx_crm_product_variants`
--
ALTER TABLE `xx_crm_product_variants`
  ADD CONSTRAINT `xx_crm_product_variants_ibfk_1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `xx_crm_products` (`PRODUCT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `xx_crm_req_lines`
--
ALTER TABLE `xx_crm_req_lines`
  ADD CONSTRAINT `FK_REQ_HEADER` FOREIGN KEY (`REQ_ID`) REFERENCES `xx_crm_req_header` (`ID`);

--
-- Constraints for table `xx_crm_unit_of_measurement`
--
ALTER TABLE `xx_crm_unit_of_measurement`
  ADD CONSTRAINT `xx_crm_unit_of_measurement_ibfk_1` FOREIGN KEY (`BASE_UOM_ID`) REFERENCES `xx_crm_unit_of_measurement` (`UOM_ID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
