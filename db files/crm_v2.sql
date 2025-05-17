-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2025 at 06:20 AM
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
-- Table structure for table `xx_crm_access_roles`
--

CREATE TABLE `xx_crm_access_roles` (
  `ID` int(11) NOT NULL COMMENT 'Unique identifier for each role',
  `ROLE_NAME` varchar(255) NOT NULL COMMENT 'Name of the role (e.g., Admin, Editor)',
  `DESCRIPTION` text DEFAULT NULL COMMENT 'Brief description of the role purpose or scope',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the role was created',
  `UPDATED_AT` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Timestamp when the role was last updated',
  `IS_ACTIVE` tinyint(1) DEFAULT 1 COMMENT 'Indicates whether the role is active or inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_act_call`
--

CREATE TABLE `xx_crm_act_call` (
  `CALL_ID` int(11) NOT NULL,
  `ACTIVITY_ID` int(11) NOT NULL,
  `CALL_DURATION` varchar(50) DEFAULT NULL,
  `CALL_PURPOSE` varchar(255) DEFAULT NULL,
  `FOLLOW_UP_DATE` date DEFAULT NULL,
  `SOURCE_TYPE` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_act_call`
--

INSERT INTO `xx_crm_act_call` (`CALL_ID`, `ACTIVITY_ID`, `CALL_DURATION`, `CALL_PURPOSE`, `FOLLOW_UP_DATE`, `SOURCE_TYPE`) VALUES
(15, 28, '25 Minutes', 'Client Introduction', '2024-12-19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_act_email`
--

CREATE TABLE `xx_crm_act_email` (
  `EMAIL_ID` int(11) NOT NULL,
  `ACTIVITY_ID` int(11) NOT NULL,
  `SUBJECT` varchar(255) DEFAULT NULL,
  `BODY` text DEFAULT NULL,
  `ATTACHMENTS` text DEFAULT NULL,
  `READ_STATUS` enum('READ','UNREAD') DEFAULT 'UNREAD',
  `SOURCE_TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_act_event`
--

CREATE TABLE `xx_crm_act_event` (
  `EVENT_ID` int(11) NOT NULL,
  `ACTIVITY_ID` int(11) NOT NULL,
  `EVENT_NAME` varchar(255) DEFAULT NULL,
  `EVENT_TYPE` varchar(255) DEFAULT NULL,
  `FEEDBACK` text DEFAULT NULL,
  `SOURCE_TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_act_meeting`
--

CREATE TABLE `xx_crm_act_meeting` (
  `MEETING_ID` int(11) NOT NULL,
  `ACTIVITY_ID` int(11) NOT NULL,
  `LOCATION` varchar(255) DEFAULT NULL,
  `AGENDA` text DEFAULT NULL,
  `ATTENDEES` varchar(255) DEFAULT NULL,
  `OUTCOME` text DEFAULT NULL,
  `SOURCE_TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_act_meeting`
--

INSERT INTO `xx_crm_act_meeting` (`MEETING_ID`, `ACTIVITY_ID`, `LOCATION`, `AGENDA`, `ATTENDEES`, `OUTCOME`, `SOURCE_TYPE`) VALUES
(7, 30, 'London', 'Safer', 'Shaikh,azimuddin', '<h2>Saw</h2><p>ilan e nubuwat</p>', NULL),
(8, 34, 'Office', 'Deal Meetings', 'Shaikh,Ab Azim,Kissu', '<h2>Test</h2><p>This is test and deals </p>', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_act_note`
--

CREATE TABLE `xx_crm_act_note` (
  `NOTE_ID` int(11) NOT NULL,
  `ACTIVITY_ID` int(11) NOT NULL,
  `NOTE_CONTENT` text DEFAULT NULL,
  `SOURCE_TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_act_note`
--

INSERT INTO `xx_crm_act_note` (`NOTE_ID`, `ACTIVITY_ID`, `NOTE_CONTENT`, `SOURCE_TYPE`) VALUES
(6, 29, '<h2>Notes</h2><p>This is the notes here</p>', NULL),
(7, 31, '<p>this is the notes</p><p>tijarat</p>', NULL),
(8, 32, '<p>sab kuch chhod diya</p><p><br></p><p>saye ki tarah</p>', NULL),
(9, 33, '<p>apne jamane me puchaa tha</p>', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_act_task`
--

CREATE TABLE `xx_crm_act_task` (
  `TASK_ID` int(11) NOT NULL,
  `ACTIVITY_ID` int(11) NOT NULL,
  `TASK_DESCRIPTION` text DEFAULT NULL,
  `DUE_DATE` date DEFAULT NULL,
  `PRIORITY` enum('HIGH','MEDIUM','LOW') DEFAULT 'MEDIUM',
  `SOURCE_TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_act_task`
--

INSERT INTO `xx_crm_act_task` (`TASK_ID`, `ACTIVITY_ID`, `TASK_DESCRIPTION`, `DUE_DATE`, `PRIORITY`, `SOURCE_TYPE`) VALUES
(3, 37, '<h2>Task </h2><p>Task 1</p><p>Task 2</p>', '2024-12-11', 'MEDIUM', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_app_resources`
--

CREATE TABLE `xx_crm_app_resources` (
  `ID` int(11) NOT NULL COMMENT 'Auto-incremented unique identifier for the resource',
  `UUID` varchar(36) NOT NULL COMMENT 'Universally unique identifier for the resource',
  `RESOURCE_NAME` varchar(255) NOT NULL COMMENT 'Human-readable name of the resource',
  `RESOURCE_TYPE` enum('PAGE','API','FORM','REPORT','WIDGET','ACTION','OTHER') NOT NULL COMMENT 'Type of the resource',
  `RESOURCE_DESCRIPTION` text DEFAULT NULL COMMENT 'Detailed description of the resource',
  `RESOURCE_PATH` varchar(1024) NOT NULL COMMENT 'URL or path of the resource',
  `MODULE_CATEGORY` varchar(255) NOT NULL COMMENT 'Module or category associated with the resource',
  `PARENT_RESOURCE_ID` int(11) DEFAULT NULL COMMENT 'Parent resource ID for hierarchical relationships',
  `ASSOCIATED_ROLES` varchar(255) DEFAULT NULL COMMENT 'Roles associated with the resource in JSON format',
  `ICON_LABEL` varchar(255) DEFAULT NULL COMMENT 'Icon or label used for UI display',
  `STATUS` enum('ACTIVE','INACTIVE','DEPRECATED') NOT NULL DEFAULT 'ACTIVE' COMMENT 'Current status of the resource',
  `TAGS` varchar(255) DEFAULT NULL COMMENT 'Tags associated with the resource in JSON format',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'ID of the user who created the record',
  `CREATED_DATE` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the resource was created',
  `LAST_UPDATED_BY` int(11) DEFAULT NULL COMMENT 'ID of the user who last updated the record',
  `LAST_UPDATED_DATE` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Timestamp of the last update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_asn_header`
--

CREATE TABLE `xx_crm_asn_header` (
  `ASN_ID` int(11) NOT NULL COMMENT 'Auto-incrementing unique identifier for each ASN.',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier for the ASN.',
  `ASN_NUMBER` varchar(20) NOT NULL COMMENT 'Unique number assigned to the ASN.',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'Client identifier associated with the ASN.',
  `ASN_STATUS` varchar(50) DEFAULT NULL COMMENT 'Current status of the ASN (e.g., Draft, Confirmed, Shipped).',
  `DELIVERY_NUMBER` varchar(255) DEFAULT NULL COMMENT 'Delivery reference number linked to the shipment.',
  `CARRIER_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the carrier handling the shipment.',
  `TRUCK_NUMBER` varchar(100) DEFAULT NULL COMMENT 'Truck or vehicle number for the shipment.',
  `CUSTOMER_SUPPLIER` varchar(255) DEFAULT NULL COMMENT 'Customer or supplier involved in the ASN.',
  `SHIPPING_ADDRESS` text DEFAULT NULL COMMENT 'Full shipping address for the delivery.',
  `TRANSPORT` varchar(255) DEFAULT NULL COMMENT 'Mode of transport (e.g., Air, Sea, Road).',
  `LEG_DR` varchar(255) DEFAULT NULL COMMENT 'Leg or delivery route information.',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the record was created.',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User or system that created the record.',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the record was last updated.',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User or system that last updated the record.',
  `VERSION` int(11) DEFAULT 1 COMMENT 'Version number for the ASN record.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_asn_lines`
--

CREATE TABLE `xx_crm_asn_lines` (
  `LINE_ID` int(11) NOT NULL COMMENT 'Auto-incrementing unique identifier for each line item.',
  `ASN_ID` int(11) NOT NULL COMMENT 'Foreign key linking to XX_CRM_ASN_HEADER.',
  `PO_NUM` varchar(20) DEFAULT NULL COMMENT 'Purchase Order number associated with the line item.',
  `SOC_NUM` varchar(20) DEFAULT NULL COMMENT 'Sales Order Confirmation number for the line item.',
  `PRODUCT_ID` varchar(20) DEFAULT NULL COMMENT 'Unique identifier for the product.',
  `PRODUCT_DESC` varchar(500) DEFAULT NULL COMMENT 'Description of the product.',
  `COLOR` varchar(100) DEFAULT NULL COMMENT 'Color of the product, if applicable.',
  `TRANSPORT` varchar(255) DEFAULT NULL COMMENT 'Mode of transport for the specific line item.',
  `UOM` varchar(50) DEFAULT NULL COMMENT 'Unit of Measure for the product (e.g., KG, PCS).',
  `QTY` decimal(18,2) DEFAULT NULL COMMENT 'Quantity of the product in the specified unit of measure.',
  `PACKING` varchar(255) DEFAULT NULL COMMENT 'Details about the packing (e.g., Box, Pallet).',
  `INVOICE_NUM` varchar(50) DEFAULT NULL COMMENT 'Invoice number associated with the line item.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(49, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjE4ODQzOX0=.0785b1bc726cef4269ebc1a70aa113bb6ab8870a5b5c10a5d631076aed32d4d7', 'auth', '1732199239', '2024-11-21 06:57:19', '2024-11-21 11:27:19'),
(50, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjI1NDAzMX0=.89408720d6442782795cf0f12eb688a698b423d68b5902f55a00d35302e254ef', 'auth', '1732264831', '2024-11-22 01:10:31', '2024-11-22 05:40:31'),
(51, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjI2ODIwM30=.0dc9883c2e616e40ac8af37d256d3a6858d7146ab87222fe80972c44ff540824', 'auth', '1732279003', '2024-11-22 05:06:43', '2024-11-22 09:36:43'),
(52, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjMzOTY3MH0=.9847e0ea7a2c4a50c12a338bd6a48a91c71dfb9d6d8a8fcfb72b14e63eb44843', 'auth', '1732350470', '2024-11-23 00:57:50', '2024-11-23 05:27:50'),
(53, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjM1NTY1OH0=.0ca23d5f02aec82e3ee991a481080c44940b041ac7503d48dff575b9f4df68c9', 'auth', '1732366458', '2024-11-23 05:24:18', '2024-11-23 09:54:18'),
(54, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjUxMjQ5OX0=.571edf38d0fd8aa17c81e8e22be22cee06da28b68b121af83713367c3c75fa70', 'auth', '1732523299', '2024-11-25 00:58:19', '2024-11-25 05:28:19'),
(55, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjUyMzUxNH0=.b8546438a5b881c7163a90bd8fb3b0a2ef55643349c732b3c5386a0ab1c264a2', 'auth', '1732534314', '2024-11-25 04:01:54', '2024-11-25 08:31:54'),
(56, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjUzNDM4Nn0=.7734404a109bdbcbc912d1bbea81233c7a5abdcd92e3bfa8f10c3d03ae3a59e7', 'auth', '1732545186', '2024-11-25 07:03:06', '2024-11-25 11:33:06'),
(57, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjYwNDEwMH0=.c9e3dfec4e23a08e125497fa7b72807c85eb320d6b0a812d5ebf410b30eaf9f3', 'auth', '1732614900', '2024-11-26 02:25:00', '2024-11-26 06:55:00'),
(58, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjYxNTI0N30=.33efc1a64247e9032d2eaf18cb84e11472b2ffb22dbe1390d66d26e82838cbf2', 'auth', '1732626047', '2024-11-26 05:30:47', '2024-11-26 10:00:47'),
(59, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjYyNzg3MX0=.81b1197532a91edc571728c42a8ba0cba93aaad76453a1aef61f86417c6defb2', 'auth', '1732638671', '2024-11-26 09:01:11', '2024-11-26 13:31:11'),
(60, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjY4NTYwMn0=.97f0ae84184ca73f6f2caf34a5505c03fa138e27a94a2762fbae2fb0ef944e8b', 'auth', '1732696402', '2024-11-27 01:03:22', '2024-11-27 05:33:22'),
(61, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjY5NzAzMn0=.14faffaa26d3f1754a7f4bf2c148401d0ff91b2c3c04afa9655f833013953047', 'auth', '1732707832', '2024-11-27 04:13:52', '2024-11-27 08:43:52'),
(62, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjcwOTU4OX0=.35b6ffea18568229f7557350be247c77779df8272b131d9ba6cf6779847d7d42', 'auth', '1732720389', '2024-11-27 07:43:09', '2024-11-27 12:13:09'),
(63, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjc2OTY0MX0=.a666fa256d7b137533a47c66535f0a2ed21ab0d819e1d904e6ee86e8ab6a5929', 'auth', '1732780441', '2024-11-28 00:24:01', '2024-11-28 04:54:01'),
(64, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjc4MDU5OH0=.2d6c717788af45be8f78d6728f1cd7f2b70088645af46fca600ba159d43ecdd9', 'auth', '1732791398', '2024-11-28 03:26:38', '2024-11-28 07:56:38'),
(65, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjc5MTgxOX0=.83dba10eb9bcba31d3345e31b7af2a085afce5570504b22f0b040ebada9630ef', 'auth', '1732802619', '2024-11-28 06:33:39', '2024-11-28 11:03:39'),
(66, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjg1NTEyN30=.aa413037d4ec520673ea6ecfbab20985f8b19d5ac93da349423ec6db9735a945', 'auth', '1732865927', '2024-11-29 00:08:47', '2024-11-29 04:38:47'),
(67, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjg3MjM4Mn0=.5fcc0a37260032db758ac6a650fd8a568a6356a9a2e83a36e67f0f6afbe373f9', 'auth', '1732883182', '2024-11-29 04:56:22', '2024-11-29 09:26:22'),
(68, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMjk0MzUzMX0=.34bb29ad8060b3abaa5bb98c4a9c59ede4c75839a2605552e16a7bd6eecae4c6', 'auth', '1732954331', '2024-11-30 00:42:11', '2024-11-30 05:12:11'),
(69, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzExNjg1Nn0=.0a334b9be57833884c5c176fe14fa775c93ae2a198262f7e9d426f4bee970472', 'auth', '1733127656', '2024-12-02 00:50:56', '2024-12-02 05:20:56'),
(71, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzE0MDI0OX0=.7e13221d13e295e2cf1d4c2c8a88602a96240f5abc7479fe1a393092eb9a4fa3', 'auth', '1733151049', '2024-12-02 07:20:49', '2024-12-02 11:50:49'),
(74, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzIwMDAwOH0=.c406ef52a70f3544faf046cea9dd834eec4161a09fa90cc47cbf7e11d781814b', 'auth', '1733210808', '2024-12-02 23:56:48', '2024-12-03 04:26:48'),
(75, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzIxMzU5OX0=.13fd7df869949e4c1726bb0d1781b1428f40f99673fbe159dfc318615a709969', 'auth', '1733224399', '2024-12-03 03:43:19', '2024-12-03 08:13:19'),
(76, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzIyNjkxNH0=.1e793123b716192346704ae787e49d4a00966f5ba03d43d5efa5f0e95104a97b', 'auth', '1733237714', '2024-12-03 07:25:14', '2024-12-03 11:55:14'),
(77, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzI4NjE4MX0=.b02381c253f65aa9d4f02da4e11960027335bbc6c368ddd313171f52dd9abf2a', 'auth', '1733296981', '2024-12-03 23:53:01', '2024-12-04 04:23:01'),
(78, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzMxMDUzN30=.1a0c6413fd7f53b49305e21547803cba86ee255e0cd3535536d8f04b564984da', 'auth', '1733321337', '2024-12-04 06:38:57', '2024-12-04 11:08:57'),
(79, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzM4MTAwM30=.2c440875e9c48669ee68b6e3dd08530dbeea4252bfc0b72d98d301bf187529a1', 'auth', '1733391803', '2024-12-05 02:13:23', '2024-12-05 06:43:23'),
(80, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzM5Njg3N30=.085210a7b6aa79f199214dd1a7f9df76bd2d9a057458ba596ec2100794dda166', 'auth', '1733407677', '2024-12-05 06:37:57', '2024-12-05 11:07:57'),
(81, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzQ1ODkwMX0=.819182e4ed262fa1a899d96a284e92119cc19835941bda38442d70ccecc90481', 'auth', '1733469701', '2024-12-05 23:51:41', '2024-12-06 04:21:41'),
(82, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzQ3NjgxMH0=.3ed9f96fb2b488044f375a01eefc05788376c87bc187a8316fb69533b89a4b9e', 'auth', '1733487610', '2024-12-06 04:50:10', '2024-12-06 09:20:10'),
(83, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzQ4Nzk5N30=.1d299af97625911130ae84a7e39d0133f5643f401cb9e97a967cf8295fad2944', 'auth', '1733498797', '2024-12-06 07:56:37', '2024-12-06 12:26:37'),
(84, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzU0NTUyN30=.edba553571d474fed94e17ecff14ea2d957aa1c5624ddab0ad0fb3b7e50a0aa6', 'auth', '1733556327', '2024-12-06 23:55:27', '2024-12-07 04:25:27'),
(85, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzU1NzE3OH0=.4cf677076ec418babaa54fcbb35df5e6849353b30d3cbe3913a187a065dc8399', 'auth', '1733567978', '2024-12-07 03:09:38', '2024-12-07 07:39:38'),
(86, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzU2ODQ5OX0=.d978ff29a4e46267ebb1f6d8989de6de58c3080ae5b81a20c0422b5b39927dfd', 'auth', '1733579299', '2024-12-07 06:18:19', '2024-12-07 10:48:19'),
(87, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzcyMDA3MH0=.d1b4cf4feb0cffac424e06b6c2de0707f9b501a798e7d954ab083f90f777aaea', 'auth', '1733730870', '2024-12-09 00:24:30', '2024-12-09 04:54:30'),
(88, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzczMTY1MX0=.4f34b976780bc3a83d3773820fc609facac704885e962c55ce5f0898265915f7', 'auth', '1733742451', '2024-12-09 03:37:31', '2024-12-09 08:07:31'),
(89, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzc0NTY4NH0=.65517af6a0e4c7953a6084b3b4c6af6a07affa63fe046fc6c1abfa0fe808147d', 'auth', '1733756484', '2024-12-09 07:31:24', '2024-12-09 12:01:24'),
(90, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzgwODE2M30=.33d4684b8231485ce0beca52c688467ee402665ba3d7760287667e5d2dee48a3', 'auth', '1733818963', '2024-12-10 00:52:43', '2024-12-10 05:22:43'),
(91, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzgyNjY3OX0=.a8635aa51060d2dd2eac0ecaa8c1b3f408fb6a38676114ff5b164bbdd668f113', 'auth', '1733837479', '2024-12-10 06:01:19', '2024-12-10 10:31:19'),
(92, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzg5NjQxNn0=.1c5b6de65ce9d3f97e594b45e6fd1b22b9cafc55cbd9418447302672677f5dbd', 'auth', '1733907216', '2024-12-11 01:23:36', '2024-12-11 05:53:36'),
(93, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzkxNDI4OX0=.cf0bb1e2dd5e680bf7fa8c7d053c338209c9a6d4ff24b6ba5f22661758a086b0', 'auth', '1733925089', '2024-12-11 06:21:29', '2024-12-11 10:51:29'),
(94, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzk4MTc4N30=.21835eeea2dc5058587b95c5484a1d5cfdc4b99eae488d1383423570136427b1', 'auth', '1733992587', '2024-12-12 01:06:27', '2024-12-12 05:36:27'),
(95, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczMzk5MzAwM30=.0c0439e5b181d3a96269c69d254f0bf6e805531d8edbf105976f53aeb82c07fe', 'auth', '1734003803', '2024-12-12 04:13:23', '2024-12-12 08:43:23'),
(97, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDA2ODIwOH0=.df400db8dcfbe8154a0386e2fec889ae91d0e20847dcf6f406d62d732680c950', 'auth', '1734079008', '2024-12-13 01:06:48', '2024-12-13 05:36:48'),
(98, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDA4MTQ0N30=.c6c5be12b00f9a381f423176497be9a408e5ede726fce225fb10640dee27c6cc', 'auth', '1734092247', '2024-12-13 04:47:27', '2024-12-13 09:17:27'),
(100, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDE1ODg4MH0=.98e670036fc17b7f6e5c71c68517f37ec0c8407f18ecbff57189ce1eb5a7111f', 'auth', '1734169680', '2024-12-14 02:18:00', '2024-12-14 06:48:00'),
(101, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDMyNDY4N30=.779a6f3485a024a2dec2624679ac4e0cf3909437b35d576e95341ccb29531b5e', 'auth', '1734335487', '2024-12-16 00:21:27', '2024-12-16 04:51:27'),
(112, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDM1Mjg5N30=.f3d4bb13a93ebcf219847d39e168dc586633997598851a096f18c2f1cbc861c0', 'auth', '1734363697', '2024-12-16 08:11:37', '2024-12-16 12:41:37'),
(113, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDQxMjA3MX0=.d06bde581795909f50cbcb58f3cbbfff438d8bf4e7e5d4ccae3e3fbef26142fb', 'auth', '1734422871', '2024-12-17 00:37:51', '2024-12-17 05:07:51'),
(115, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDQzNTQ0NX0=.94e414b4b29a71df74f58768d848f52f7873682dd58f4f9f8887f87d25e29ede', 'auth', '1734446245', '2024-12-17 07:07:25', '2024-12-17 11:37:25'),
(116, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDQ5ODc1N30=.54cbfa24270c7d6d8886bb93324ede20b780c04f4d8cb9ceba0b7d2700d7dd4d', 'auth', '1734509557', '2024-12-18 00:42:37', '2024-12-18 05:12:37'),
(118, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDUxNzUxOH0=.695ae61ca6a8663e8d058b85f613c283ac1ecb793f9968943a10b0bd8b463f68', 'auth', '1734528318', '2024-12-18 05:55:18', '2024-12-18 10:25:18'),
(119, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDU4NTM3N30=.f4e83a258cbdf19c1d1219772b745ad211ebb3f449d39861fd78352df5d247fb', 'auth', '1734596177', '2024-12-19 00:46:17', '2024-12-19 05:16:17'),
(120, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDYwNzY0Mn0=.3a0b30f29fa900ebef033f1e50d302cc2f7fb2b33ea7f85da0a3831bda80977c', 'auth', '1734618442', '2024-12-19 06:57:22', '2024-12-19 11:27:22'),
(121, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDY3MTE0MH0=.44a503349036810e6cbc57293ae24442859700f34061f2bd03e57fdc0a4afc9f', 'auth', '1734681940', '2024-12-20 00:35:40', '2024-12-20 05:05:40'),
(122, 4, 'eyJ1c2VyaWQiOiI0IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6InVzZXIxQGNybS5saXZlIiwidXNlcm5hbWUiOiJBYiBBemltIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNDY3MzQxMH0=.ecb8937ab161bb3835bad062c49dde8711a5ca0c13b1d7c283d00a2a9eb4d179', 'auth', '1734684210', '2024-12-20 01:13:30', '2024-12-20 05:43:30'),
(123, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDY4NzA2Mn0=.d9ff126f3f1c4f382afa46d2e4bf845e8d562407d8be2cbb68ef6479fab4c771', 'auth', '1734697862', '2024-12-20 05:01:02', '2024-12-20 09:31:02'),
(124, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDc1ODY1N30=.aee61a0fb71f8f7a91cf464848b10743230c78a44a24967060d0bca84edd7953', 'auth', '1734769457', '2024-12-21 00:54:17', '2024-12-21 05:24:17'),
(125, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDc2OTQ1OX0=.111e9cf40f5d5183e6899d1facb28d7cb18e1c49ea407506df617b4e9b504edd', 'auth', '1734780259', '2024-12-21 03:54:19', '2024-12-21 08:24:19'),
(126, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDc2OTYzN30=.5fa0191863be8c461c1036ece98278cb04218b2a0b4ef7a6743301c5afc2dd12', 'auth', '1734780437', '2024-12-21 03:57:17', '2024-12-21 08:27:17'),
(127, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDc4MTEyMn0=.b90dbe2fa52e0e7c2d1f0c526ed9f6a3a6dd683bbe8ef34f8e6f65f6e12a95f0', 'auth', '1734791922', '2024-12-21 07:08:42', '2024-12-21 11:38:42'),
(128, 8, 'eyJ1c2VyaWQiOiI4IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6Inpuc0B1c2VyLmxpdmUiLCJ1c2VybmFtZSI6IlpvaGViIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNDc4MTMyOX0=.05fa4fd45a7c9b843bccc587169905e0ef1e505ffba02a6cafaa3ddf1dab23e9', 'auth', '1734792129', '2024-12-21 07:12:09', '2024-12-21 11:42:09'),
(129, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDkzMjc3N30=.3816414b50a6eb3b877d1ebfc1d30cdddf3e23b2b5e7f8618ec7443c84427d6f', 'auth', '1734943577', '2024-12-23 01:16:17', '2024-12-23 05:46:17'),
(130, 4, 'eyJ1c2VyaWQiOiI0IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6InVzZXIxQGNybS5saXZlIiwidXNlcm5hbWUiOiJBYiBBemltIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNDkzNzIyMn0=.90bc25befa23f95fb8f887b300fae23a494e996725148696460deb1ba793df49', 'auth', '1734948022', '2024-12-23 02:30:22', '2024-12-23 07:00:22'),
(131, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDk0NDQ1Nn0=.e34b9b128b09f8c1ada6bcb838dcd2e8d15fb2dbfd0eb62bc36edb1b20a42e70', 'auth', '1734955256', '2024-12-23 04:30:56', '2024-12-23 09:00:56'),
(132, 4, 'eyJ1c2VyaWQiOiI0IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6InVzZXIxQGNybS5saXZlIiwidXNlcm5hbWUiOiJBYiBBemltIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNDk0OTE4OX0=.00c79bfac57e9727ad1a88319c235ab3e6c66cb3c2ea9fc11d795191c4021175', 'auth', '1734959989', '2024-12-23 05:49:49', '2024-12-23 10:19:49'),
(133, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNDk1NjE4NX0=.2be88c5bffc5208de944a5d3a654258b639d1a413a71fd782437c46be08c85a3', 'auth', '1734966985', '2024-12-23 07:46:25', '2024-12-23 12:16:25'),
(134, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTAxNzg5OH0=.c606d8a00f9aa3f99d75578fba0b0e340e4ef72a0388bdda3060c9142b8d371d', 'auth', '1735028698', '2024-12-24 00:54:58', '2024-12-24 05:24:58'),
(135, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTAyNDYyMn0=.52fc5f64a422e65ab30cefbfb435fe8c56a32fe89accc8f67b710ec2704b1e89', 'auth', '1735035422', '2024-12-24 02:47:02', '2024-12-24 07:17:02'),
(136, 4, 'eyJ1c2VyaWQiOiI0IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6InVzZXIxQGNybS5saXZlIiwidXNlcm5hbWUiOiJBYiBBemltIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNTAyNDYzOH0=.091f90b5631659d66711fc26978a9386424aefab2ec1730df0423dd75160a18b', 'auth', '1735035438', '2024-12-24 02:47:18', '2024-12-24 07:17:18'),
(137, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTAzNDgwOH0=.563c9968432d5cb50ae5efe522126794d275c3497572d5b18ada5dcf77d28e9d', 'auth', '1735045608', '2024-12-24 05:36:48', '2024-12-24 10:06:48'),
(138, 4, 'eyJ1c2VyaWQiOiI0IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6InVzZXIxQGNybS5saXZlIiwidXNlcm5hbWUiOiJBYiBBemltIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNTAzNTcwM30=.ca652a64b576ffee354efeafd1950124e802e308ff928f343ea083ab63a2e58d', 'auth', '1735046503', '2024-12-24 05:51:43', '2024-12-24 10:21:43'),
(140, 4, 'eyJ1c2VyaWQiOiI0IiwidXNlcnR5cGUiOiJjbGllbnQiLCJlbWFpbCI6InVzZXIxQGNybS5saXZlIiwidXNlcm5hbWUiOiJBYiBBemltIFNoYWlraCIsInRpbWVzdGFtcCI6MTczNTEwMzEwOH0=.728409db01f75db2004f3f1feb8054dd2fbca906664e1ba69b8508811031b937', 'auth', '1735113908', '2024-12-25 00:35:08', '2024-12-25 05:05:08'),
(141, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTEwNTQ1N30=.c2095e4063c436f9b9ce107a7aff82467807f4c1645120543503ec15e0cc15d5', 'auth', '1735116257', '2024-12-25 01:14:17', '2024-12-25 05:44:17'),
(142, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTExNjM2M30=.c7c2bbc55261f761fb177a41936460a9f286b4b2c8d2f82d6a4c5debd1f62110', 'auth', '1735127163', '2024-12-25 04:16:03', '2024-12-25 08:46:03'),
(143, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTExNjc2NX0=.b217934d5bb7a68620d1b0bd6c3fc2a7a14f6994547a023829cb078b32befa13', 'auth', '1735127565', '2024-12-25 04:22:45', '2024-12-25 08:52:45'),
(144, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTEzMTQ3Mn0=.f2bf33af4052728727793b7a908f6332b9e8e2e1c3275b2fe8b45e4e42bf6f53', 'auth', '1735142272', '2024-12-25 08:27:52', '2024-12-25 12:57:52'),
(145, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTE4NzYyM30=.5484c7c146d5929408e4714cdeea5ce00242fc967c9ba961be6b9f7f5a6f5b05', 'auth', '1735198423', '2024-12-26 00:03:43', '2024-12-26 04:33:43'),
(146, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTE5ODQ0OX0=.48884eee902afbcbe299932e669088481868e52dd7901a173fec6bdbc0d24a0f', 'auth', '1735209249', '2024-12-26 03:04:09', '2024-12-26 07:34:09'),
(147, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTIwOTI3MX0=.923d8a97ebe2ace2add84e441fbd9e6065301645762c81aa05f29f58cf2c3de5', 'auth', '1735220071', '2024-12-26 06:04:31', '2024-12-26 10:34:31'),
(148, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTI3MzkxOH0=.fc576daeeb7ec998fdac15cb7683568d9fca902877b8e8c1775d7c93dac6e2e6', 'auth', '1735284718', '2024-12-27 00:01:58', '2024-12-27 04:31:58'),
(149, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTI5MzU5OH0=.9dd8892439071811f97ead4bd28e4b966a0713c07acaf08ad2e93418854eb185', 'auth', '1735304398', '2024-12-27 05:29:58', '2024-12-27 09:59:58'),
(150, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTM3MTg2OX0=.359d2117b13c217066979956af77aa1b7bbe53c800d6bb6f980cbd38593a11c7', 'auth', '1735382669', '2024-12-28 03:14:29', '2024-12-28 07:44:29'),
(151, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTQ3MTU4OX0=.0f8329b31e4cca2d7161aa8d13feb5dd0ebc9031c891aa91a6ebe739a37f9216', 'auth', '1735482389', '2024-12-29 06:56:29', '2024-12-29 11:26:29'),
(152, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTUzNTk2Nn0=.4a7ed39b03cfe41ad24eb4513301d97bd7a7a4115cde692b0e87cd32a8d8b6b2', 'auth', '1735546766', '2024-12-30 00:49:26', '2024-12-30 05:19:26'),
(153, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTYyNDM4MH0=.a5d761457a3fa355831ea17cd06c949850cf18422561662a2caa97695e441648', 'auth', '1735635180', '2024-12-31 01:23:00', '2024-12-31 05:53:00'),
(154, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTcwNjY4NX0=.d3fc97e589f43bb577ef90dcd7efc0254438ee0667fe96203adcb1d35bc7740c', 'auth', '1735717485', '2025-01-01 00:14:45', '2025-01-01 04:44:45'),
(155, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTgwMzQ5Nn0=.d53542da4784aa520a41f499579902aff3d09296d9b3279b9a468979905ebf85', 'auth', '1735814296', '2025-01-02 03:08:16', '2025-01-02 07:38:16'),
(156, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTgxNTAzNX0=.22d05f57749983b952f5414d3f0dde20f7b51289993381485d664c42c0a8f93b', 'auth', '1735825835', '2025-01-02 06:20:35', '2025-01-02 10:50:35'),
(157, 1, 'eyJ1c2VyaWQiOiIxIiwidXNlcnR5cGUiOiJhZG1pbiIsImVtYWlsIjoiYWRtaW5AY3JtLmxpdmUiLCJ1c2VybmFtZSI6IlN5cyBBZG1pbiIsInRpbWVzdGFtcCI6MTczNTg4MTQwMn0=.051142ba321f1a305e446f987fbbc2f3701d7e4317ecae46f2190e95aae35bd5', 'auth', '1735892202', '2025-01-03 00:46:42', '2025-01-03 05:16:42');

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
(4, 6, 'Pune LIne ', ' ', 'Billing Address, Line 1', 'Shipping Address, Line 2', 'Dhule', 'Maharashtra', 'India', '424001', 0, 0, '2024-11-15 15:34:03', '', '2024-11-15 15:34:03', '');

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
(5, 'CL-000006', 'Olivesofts', 'Pune Branch', 'CASH', 100000.00, 15.00, 'SAR', 45000.00, 6, '2024-11-15 15:34:03', '', '2024-11-15 15:34:03', '');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_compl_header`
--

CREATE TABLE `xx_crm_compl_header` (
  `COMPLAINT_ID` int(11) NOT NULL COMMENT 'Auto-incrementing unique identifier for each complaint.',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier for the complaint record.',
  `COMPLAINT_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Unique number assigned to the complaint.',
  `COMPLAINT_DATE` date NOT NULL COMMENT 'Date when the complaint was raised.',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'Identifier of the client associated with the complaint.',
  `CUSTOMER_NAME` varchar(255) NOT NULL COMMENT 'Name of the customer raising the complaint.',
  `EMAIL` varchar(255) NOT NULL COMMENT 'Email address of the customer.',
  `COMPLAINT_RAISED_BY` varchar(255) DEFAULT NULL COMMENT 'Person who raised the complaint.',
  `MOBILE_NUMBER` varchar(20) DEFAULT NULL COMMENT 'Mobile number of the person raising the complaint.',
  `STATUS` varchar(20) DEFAULT 'OPEN' COMMENT 'Current status of the complaint (e.g., OPEN, RESOLVED).',
  `COMPLAINT` text DEFAULT NULL COMMENT 'Details of the complaint.',
  `ATTACHMENTS` varchar(500) DEFAULT NULL COMMENT 'File paths or references to attachments.',
  `IS_RESOLVED` tinyint(1) DEFAULT 0 COMMENT 'Indicates whether the complaint is resolved (TRUE/FALSE).',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the complaint was created.',
  `CREATED_BY` varchar(100) DEFAULT NULL COMMENT 'User or system that created the complaint record.',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the record was last updated.',
  `UPDATED_BY` varchar(100) DEFAULT NULL COMMENT 'User or system that last updated the record.',
  `VERSION` int(11) DEFAULT 1 COMMENT 'Version number of the complaint record.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table to store complaint header details.';

--
-- Dumping data for table `xx_crm_compl_header`
--

INSERT INTO `xx_crm_compl_header` (`COMPLAINT_ID`, `UUID`, `COMPLAINT_NUMBER`, `COMPLAINT_DATE`, `CLIENT_ID`, `CUSTOMER_NAME`, `EMAIL`, `COMPLAINT_RAISED_BY`, `MOBILE_NUMBER`, `STATUS`, `COMPLAINT`, `ATTACHMENTS`, `IS_RESOLVED`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`, `VERSION`) VALUES
(1, '32fb0a8e-5e8a-4fe7-b5aa-b8fc0f0bc2f1', 'COMP-091224000001', '2024-12-09', 4, 'Ab Azim Shaikh', 'user1@crm.live', 'Ab Azim Shaikh', '8805629207', 'Closed', 'Complaint Details', '[\"free-shipping_(1)2.png\"]', 1, '2024-12-08 18:30:00', '4', '2024-12-09 05:24:31', NULL, 1),
(2, '2f41ee51-3459-468a-99c3-05b4bf20c24e', 'COMP-091224000002', '2024-12-09', 4, 'Ab Azim Shaikh', 'user1@crm.live', 'Ab Azim Shaikh', '8805629207', 'Closed', 'Complaint Details New Attached Files Uploaded Files\r\nComplaint Details New Attached Files Uploaded Files\r\n\r\n', '[\"herbs1.png\"]', 1, '2024-12-08 18:30:00', '4', '2024-12-10 05:13:31', NULL, 1),
(3, '3cdc3adc-3aaf-4535-90db-2a9c7c435fe2', 'COMP-091224000003', '2024-12-09', 6, 'Noman Mirza', 'nam@ols.com', 'Noman Mirza', '7845215487', 'Closed', 'Complaint Details & Complaint Details and Attachments & Complaint Product Details', '[\"herbs2.png\",\"apple6.png\"]', 1, '2024-12-08 18:30:00', '6', '2024-12-10 05:26:40', NULL, 1),
(4, '74f0060a-0795-4ff9-bfd5-ecfb9e418792', 'COMP-101224000004', '2024-12-10', 6, 'Noman Mirza', 'nam@ols.com', 'Noman Mirza', '7845215487', 'Active', 'Raised a complaint', '[\"to-do-list.png\"]', 0, '2024-12-09 18:30:00', '6', '2024-12-10 06:35:10', NULL, 1),
(5, '7f8fccf6-5a02-4b67-87a5-6b9377c24ce5', 'COMP-101224000005', '2024-12-10', 6, 'Noman Mirza', 'nam@ols.com', 'Noman Mirza', '7845215487', 'Closed', 'this is test', '[]', 1, '2024-12-09 18:30:00', '6', '2024-12-10 06:56:22', NULL, 1),
(14, '3eeded67-9740-4547-91b3-38c5b395e16d', 'COMP-101224000014', '2024-12-10', 4, 'Ab Azim Shaikh', 'user1@crm.live', 'Ab Azim Shaikh', '8805629207', 'Closed', 'Complaint Details AAAAA', '[\"check1.png\",\"customer-feedback4.png\"]', 1, '2024-12-09 18:30:00', '4', '2024-12-10 08:16:06', NULL, 1),
(15, '77a358b9-02d1-4727-b031-8e7f43a6d517', 'COMP-101224000015', '2024-12-10', 4, 'Ab Azim Shaikh', 'user1@crm.live', 'Ab Azim Shaikh', '8805629207', 'Active', 'Complaint Details and Attachments\r\n\r\n', '[\"to-do-list1.png\",\"check2.png\"]', 0, '2024-12-09 18:30:00', '4', '2024-12-10 08:19:03', NULL, 1),
(16, 'c8e3b641-b3b9-4abe-bf8c-34074f5c1e48', 'COMP-201224000016', '2024-12-20', 4, 'Ab Azim Shaikh', 'user1@crm.live', 'Ab Azim Shaikh', '8805629207', 'Closed', 'fdfsd sdf sdf sdfd', '[]', 1, '2024-12-19 18:30:00', '4', '2024-12-20 05:52:08', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_compl_lines`
--

CREATE TABLE `xx_crm_compl_lines` (
  `LINE_ID` int(11) NOT NULL COMMENT 'Auto-incrementing unique identifier for each complaint line.',
  `COMPLAINT_ID` int(11) NOT NULL COMMENT 'Foreign key linking to XX_CRM_COMPL_HEADER.',
  `PO_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Purchase Order number associated with the complaint line.',
  `DELIVERY_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Delivery number associated with the complaint line.',
  `PRODUCT_CODE` varchar(50) NOT NULL COMMENT 'Code of the product being complained about.',
  `PRODUCT_DESC` varchar(255) DEFAULT NULL COMMENT 'Description of the product.',
  `DELIVERY_DATE` date DEFAULT NULL COMMENT 'Delivery date of the product.',
  `QTY` decimal(18,2) DEFAULT NULL COMMENT 'Quantity of the product in the complaint.',
  `ISSUE` text DEFAULT NULL COMMENT 'Details of the issue reported in the complaint line.',
  `REMARK` text DEFAULT NULL COMMENT 'Remarks or additional comments.',
  `RETURN_DATE` date DEFAULT NULL COMMENT 'Date when the product is to be returned or was returned.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table to store complaint line details.';

--
-- Dumping data for table `xx_crm_compl_lines`
--

INSERT INTO `xx_crm_compl_lines` (`LINE_ID`, `COMPLAINT_ID`, `PO_NUMBER`, `DELIVERY_NUMBER`, `PRODUCT_CODE`, `PRODUCT_DESC`, `DELIVERY_DATE`, `QTY`, `ISSUE`, `REMARK`, `RETURN_DATE`) VALUES
(1, 1, '1', '1', '2', 'TEST', '2024-12-09', 10.00, 'TEST ISSUE', 'TEST REMARK', NULL),
(2, 1, '2', 'D3', 'D33', 'TEST33', '2024-12-10', 30.00, 'TEST ISSUE 2', 'Remarks 3', NULL),
(3, 2, '', '', '', '', '0000-00-00', 0.00, '', '', NULL),
(4, 3, '4', 'D3', 'D33', '44444', '2024-12-18', 10.00, 'ISSUUE 3', 'Remarks 34', NULL),
(5, 4, '1', '1', '1', 'TEST', '2024-12-12', 10.00, 'TEST ISSUE', '1', NULL),
(6, 5, '1', 'sd', 'D33', 'TEST33', '2024-12-11', 10.00, 'TEST ISSUE 2', '', NULL),
(18, 14, '1', 'D3', '1', '44444', '2024-12-24', 30.00, '1', 'Remarks 34', NULL),
(19, 14, '2', 'D3', 'D33', 'TEST33', '2024-12-25', 10.00, 'TEST ISSUE', 'TEST REMARK', NULL),
(20, 15, '1', 'D3', '1', 'TEST', '2024-12-11', 30.00, '1', 'Remarks 34', NULL),
(21, 16, '1', '12', '12', '12', '2024-12-20', 12.00, '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_compl_resolution`
--

CREATE TABLE `xx_crm_compl_resolution` (
  `RESOLUTION_ID` int(11) NOT NULL COMMENT 'Auto-incrementing unique identifier for each resolution.',
  `COMPLAINT_ID` int(11) NOT NULL COMMENT 'Foreign key linking to XX_CRM_COMPL_HEADER.',
  `UUID` varchar(255) NOT NULL,
  `RESOLUTION_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Unique number assigned to the resolution.',
  `COMPLAINT_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Complaint number associated with this resolution.',
  `RECEIVED_BY` varchar(255) DEFAULT NULL COMMENT 'Person who received the complaint resolution.',
  `RECEIVED_DATE` date DEFAULT NULL COMMENT 'Date when the resolution was received.',
  `ESCALATION_NEEDED` tinyint(1) DEFAULT 0 COMMENT 'Indicates if escalation is required (TRUE/FALSE).',
  `ACTIONS` text DEFAULT NULL COMMENT 'Actions taken to resolve the complaint.',
  `ROOT_CAUSE` text DEFAULT NULL COMMENT 'Root cause analysis of the complaint.',
  `OUTCOME` text DEFAULT NULL COMMENT 'Outcome of the resolution process.',
  `ATTACHMENTS` varchar(500) DEFAULT NULL COMMENT 'File paths or references to attachments.',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the resolution was created.',
  `CREATED_BY` varchar(100) DEFAULT NULL COMMENT 'User or system that created the resolution record.',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the record was last updated.',
  `UPDATED_BY` varchar(100) DEFAULT NULL COMMENT 'User or system that last updated the record.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table to store complaint resolution details.';

--
-- Dumping data for table `xx_crm_compl_resolution`
--

INSERT INTO `xx_crm_compl_resolution` (`RESOLUTION_ID`, `COMPLAINT_ID`, `UUID`, `RESOLUTION_NUMBER`, `COMPLAINT_NUMBER`, `RECEIVED_BY`, `RECEIVED_DATE`, `ESCALATION_NEEDED`, `ACTIONS`, `ROOT_CAUSE`, `OUTCOME`, `ATTACHMENTS`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(3, 1, '1', 'RES-101224000003', '1', '1', '0000-00-00', 0, '1', '1', '1', '[]', '2024-12-09 18:30:00', '1', '2024-12-10 04:58:19', NULL),
(4, 2, 'fe2a6206-d876-49fc-9ade-1c72002ed8bd', 'RES-101224000004', 'COMP-091224000002', 'Sys Admin', '2024-12-09', 1, 'Specific Action was taken to resolve the foloowing request.', 'Root Cause of the issue was not been noticed by our side.', 'Process Measure Outcome was that please dont raise a compaint again.', '[\"apple7.png\"]', '2024-12-09 18:30:00', '1', '2024-12-10 05:13:31', NULL),
(5, 3, 'd365582b-a38a-486d-b464-afed52bb610f', 'RES-101224000005', 'COMP-091224000003', 'Sys Admin', '2024-12-09', 1, 'Specific ActionRoot Cause Process Measure Outcome', 'Root CauseSpecific Action', 'ActionRoot Cause Process Measure Outcome', '[\"herbs4.png\"]', '2024-12-09 18:30:00', '1', '2024-12-10 05:26:40', NULL),
(6, 5, 'a8e45b12-d030-4be6-9c90-6effceda9b87', 'RES-101224000006', 'COMP-101224000005', 'Sys Admin', '2024-12-10', 1, 'jkkjjkjn', 'ghvbm', 'klklnjbkbknnm', '[]', '2024-12-09 18:30:00', '1', '2024-12-10 06:56:22', NULL),
(7, 14, 'bcfed840-f9cf-49d6-a4a7-b09e7ce9276b', 'RES-101224000007', 'COMP-101224000014', 'Sys Admin', '2024-12-10', 1, 'An apple ', 'A day keep', 'Doctor away', '[\"apple8.png\"]', '2024-12-09 18:30:00', '1', '2024-12-10 08:16:06', NULL),
(8, 16, '159f6d59-51f2-4f00-8563-2d0a1add212e', 'RES-201224000008', 'COMP-201224000016', 'Sys Admin', '2024-12-20', 1, 'sd fd', 'df dsf', ' dfds ', '[]', '2024-12-19 18:30:00', '1', '2024-12-20 05:52:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_contacts`
--

CREATE TABLE `xx_crm_contacts` (
  `CONTACT_ID` int(10) NOT NULL COMMENT 'Unique identifier for the contact (Primary Key)',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier for the contact',
  `FIRST_NAME` varchar(100) NOT NULL COMMENT 'Contact''s first name',
  `LAST_NAME` varchar(100) NOT NULL COMMENT 'Contact''s last name',
  `EMAIL` varchar(255) DEFAULT NULL COMMENT 'Contact''s email address',
  `PHONE` varchar(20) DEFAULT NULL COMMENT 'Contact''s phone number',
  `MOBILE` varchar(20) DEFAULT NULL COMMENT 'Contact''s mobile phone number',
  `COMPANY_NAME` varchar(255) DEFAULT NULL COMMENT 'Company associated with the contact',
  `JOB_TITLE` varchar(100) DEFAULT NULL COMMENT 'Job title of the contact',
  `DEPARTMENT` varchar(100) DEFAULT NULL COMMENT 'Department of the contact (e.g., Sales, IT)',
  `CONTACT_SOURCE` varchar(50) DEFAULT NULL COMMENT 'Origin/source of the contact (e.g., Web, Referral, Event)',
  `LEAD_SOURCE` varchar(50) DEFAULT NULL COMMENT 'Lead source, if tracked separately (e.g., Marketing, Referral)',
  `STATUS` varchar(20) DEFAULT NULL COMMENT 'Status of the contact (e.g., Active, Inactive)',
  `ASSIGNED_TO` varchar(100) DEFAULT NULL COMMENT 'User or team managing the contact',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp of when the contact was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last update to the contact',
  `LAST_CONTACTED` date DEFAULT NULL COMMENT 'Last time the contact was engaged or contacted',
  `NOTES` text DEFAULT NULL COMMENT 'Additional notes about the contact',
  `PREFERRED_CONTACT_METHOD` varchar(50) DEFAULT NULL COMMENT 'Preferred method of contact (e.g., Email, Phone)',
  `ADDRESS` text DEFAULT NULL COMMENT 'Contact address'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_contacts`
--

INSERT INTO `xx_crm_contacts` (`CONTACT_ID`, `UUID`, `FIRST_NAME`, `LAST_NAME`, `EMAIL`, `PHONE`, `MOBILE`, `COMPANY_NAME`, `JOB_TITLE`, `DEPARTMENT`, `CONTACT_SOURCE`, `LEAD_SOURCE`, `STATUS`, `ASSIGNED_TO`, `CREATED_AT`, `UPDATED_AT`, `LAST_CONTACTED`, `NOTES`, `PREFERRED_CONTACT_METHOD`, `ADDRESS`) VALUES
(1, '750113a4-25bd-438d-8802-0906cac729b2', 'Shaikh', 'Ab Azim', 'azz@gmail.com', '8754215487', NULL, 'Kismatji Info Pvt Ltd', 'Purchasing Officer', 'IT', 'Lead', NULL, 'engaged', 'Shaikh Aasifd fsdfds ', '2024-12-05 07:43:48', '2024-12-05 09:37:49', NULL, 'note content', 'email', 'Hxjxjxj sds dsdsdf sdfsd f'),
(3, '750113a4-25bd-438d-8802-0906cac729b2', 'Kamran', 'Ansari', 'kmos@gmail.com', '8754216598', NULL, 'OLIVE SOFT', 'Purchasing Officer', 'IT', 'Lead', NULL, 'new', 'Shaikh Aasif', '2024-12-05 07:48:23', '2024-12-05 07:48:23', NULL, 'j jkdfhud fbjkldf isdbjkf sdbjkfd f', 'email', 'Olivesoft'),
(4, '30df7c7f-c9a7-4d73-a9e7-469bf16848da', 'Zoheb', 'Shaikh', 'zoheb@kisworld.com', '5487542154', NULL, 'Kissu Group Pvt Ltd', 'Chairman', 'Research', 'Lead', '4', 'new', 'Kaif Sayyed', '2024-12-06 05:22:06', '2024-12-06 05:23:33', NULL, 'Contact from lead ', 'email', 'Kissu Group Pvt Ltd');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_crapp_contact_info`
--

CREATE TABLE `xx_crm_crapp_contact_info` (
  `HEADER_ID` int(11) NOT NULL COMMENT 'Primary key for the contact info record',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier',
  `APP_HEADER_ID` int(11) NOT NULL COMMENT 'Reference to the application header ID',
  `COMPANY_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the company',
  `CONTACT_PERSON` varchar(255) DEFAULT NULL COMMENT 'Name of the primary contact person',
  `CONTACT_PERSON_TITLE` varchar(255) DEFAULT NULL COMMENT 'Title of the contact person',
  `CONTACT_EMAIL` varchar(255) DEFAULT NULL COMMENT 'Email address of the contact person',
  `PHONE` varchar(30) DEFAULT NULL COMMENT 'Phone number of the contact person',
  `FAX` varchar(30) DEFAULT NULL COMMENT 'Fax number of the company',
  `COMPANY_EMAIL` varchar(255) DEFAULT NULL COMMENT 'Email address of the company',
  `CITY` varchar(100) DEFAULT NULL COMMENT 'City where the company is located',
  `STATE` varchar(50) DEFAULT NULL COMMENT 'State where the company is located',
  `COUNTRY` varchar(100) DEFAULT NULL COMMENT 'Country where the company is located',
  `ZIP_CODE` varchar(20) DEFAULT NULL COMMENT 'ZIP/Postal code of the company address',
  `ADDRESS_SPAN` varchar(20) DEFAULT NULL COMMENT 'Address span or region',
  `BUSINESS_START_DATE` date DEFAULT NULL COMMENT 'Date when the business started',
  `BUSINESS_TYPE` varchar(50) DEFAULT NULL COMMENT 'Type of business activity',
  `BANK_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the bank associated with the company',
  `BANK_LOCATION` varchar(255) DEFAULT NULL COMMENT 'Location of the bank branch',
  `ACCOUNT_NUMBER` varchar(100) DEFAULT NULL COMMENT 'Bank account number',
  `IBAN_NUMBER` varchar(50) DEFAULT NULL COMMENT 'International Bank Account Number',
  `SWIFT_CODE` varchar(50) DEFAULT NULL COMMENT 'SWIFT code for international transactions',
  `CREATED_AT` date DEFAULT NULL COMMENT 'Record creation date',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User who created the record',
  `UPDATED_AT` date DEFAULT NULL COMMENT 'Last update date of the record',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User who last updated the record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_crapp_contact_info`
--

INSERT INTO `xx_crm_crapp_contact_info` (`HEADER_ID`, `UUID`, `APP_HEADER_ID`, `COMPANY_NAME`, `CONTACT_PERSON`, `CONTACT_PERSON_TITLE`, `CONTACT_EMAIL`, `PHONE`, `FAX`, `COMPANY_EMAIL`, `CITY`, `STATE`, `COUNTRY`, `ZIP_CODE`, `ADDRESS_SPAN`, `BUSINESS_START_DATE`, `BUSINESS_TYPE`, `BANK_NAME`, `BANK_LOCATION`, `ACCOUNT_NUMBER`, `IBAN_NUMBER`, `SWIFT_CODE`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(1, '', 1, 'Olivesoft Software Pvt Ltd', 'Ajim Shaikh', 'software developer', 'abc@gmail.com', '+919373350308', 'number', 'company@gmail.com', 'Dhule', 'Maharashtra', 'India', '424001', 'Less than 5 Years', '2024-12-19', 'Partnership', 'abc', 'abc', '978915614565', 'fs54df', 'sdf564564', '2024-12-19', 1, '2024-12-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_crapp_credit_info`
--

CREATE TABLE `xx_crm_crapp_credit_info` (
  `HEADER_ID` int(11) NOT NULL COMMENT 'Primary key for the record',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier',
  `APPLICATION_NUMBER` varchar(20) DEFAULT NULL COMMENT 'Unique application number for the credit info',
  `APPLICATION_DATE` date DEFAULT NULL COMMENT 'Date of the application',
  `CUSTOMER_ID` int(11) DEFAULT NULL COMMENT 'Reference to the customer',
  `CREDIT_VALUE` varchar(20) DEFAULT NULL COMMENT 'Value of the credit in numbers',
  `CREDIT_IN_WORDS` varchar(255) DEFAULT NULL COMMENT 'Value of the credit in words',
  `WITHIN_DAYS` varchar(10) DEFAULT NULL COMMENT 'Number of days within which the credit is applicable',
  `APPLICANT_COMMENT` text DEFAULT NULL COMMENT 'Comments provided by the applicant',
  `CREATED_AT` date DEFAULT NULL COMMENT 'Record creation date',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User who created the record',
  `UPDATED_AT` date DEFAULT NULL COMMENT 'Last update date of the record',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User who last updated the record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_crapp_credit_info`
--

INSERT INTO `xx_crm_crapp_credit_info` (`HEADER_ID`, `UUID`, `APPLICATION_NUMBER`, `APPLICATION_DATE`, `CUSTOMER_ID`, `CREDIT_VALUE`, `CREDIT_IN_WORDS`, `WITHIN_DAYS`, `APPLICANT_COMMENT`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(1, 'fe204a08-e5ef-462c-9ef5-e2593bb49a1e', 'CRED-191224000001', '2024-12-19', 1, '500', 'FIVE HUNDRED RIYALS', '30', 'this is comment', '2024-12-19', 1, '2024-12-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_crapp_formal_info`
--

CREATE TABLE `xx_crm_crapp_formal_info` (
  `HEADER_ID` int(11) NOT NULL COMMENT 'Primary key for the formal info record',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier',
  `APP_HEADER_ID` int(11) DEFAULT NULL COMMENT 'Reference to the application header ID',
  `CRN_NUMBER` varchar(30) DEFAULT NULL COMMENT 'Company registration number',
  `DATE_OF_ISSUANCE` date DEFAULT NULL COMMENT 'Date when the registration was issued',
  `DATE_OF_EXPIRY` date DEFAULT NULL COMMENT 'Date when the registration expires',
  `COMPANY_LOCATION` varchar(255) DEFAULT NULL COMMENT 'Location of the company',
  `PAID_UP_CAPITAL` varchar(20) DEFAULT NULL COMMENT 'Paid-up capital of the company',
  `COMPANY_OWNER` varchar(255) DEFAULT NULL COMMENT 'Owner of the company',
  `PERCENTAGE_OWNER` varchar(100) DEFAULT NULL COMMENT 'Ownership percentage of the owner(s)',
  `TOP_MANAGER` varchar(255) DEFAULT NULL COMMENT 'Name of the top-level manager',
  `SIGN_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the signatory',
  `SIGN_POSITION` varchar(100) DEFAULT NULL COMMENT 'Position of the signatory',
  `SIGN_SPECIMEN` varchar(255) DEFAULT NULL COMMENT 'Specimen signature of the signatory',
  `BUS_ACTIVITIES` varchar(255) DEFAULT NULL COMMENT 'Business activities carried out by the company',
  `GM_NAME` varchar(255) DEFAULT NULL COMMENT 'General Manager''s name',
  `PUR_MGR_NAME` varchar(255) DEFAULT NULL COMMENT 'Purchase Manager''s name',
  `FIN_MGR_NAME` varchar(255) DEFAULT NULL COMMENT 'Finance Manager''s name',
  `CREATED_AT` date DEFAULT NULL COMMENT 'Record creation date',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User who created the record',
  `UPDATED_AT` date DEFAULT NULL COMMENT 'Last update date of the record',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User who last updated the record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_crapp_formal_info`
--

INSERT INTO `xx_crm_crapp_formal_info` (`HEADER_ID`, `UUID`, `APP_HEADER_ID`, `CRN_NUMBER`, `DATE_OF_ISSUANCE`, `DATE_OF_EXPIRY`, `COMPANY_LOCATION`, `PAID_UP_CAPITAL`, `COMPANY_OWNER`, `PERCENTAGE_OWNER`, `TOP_MANAGER`, `SIGN_NAME`, `SIGN_POSITION`, `SIGN_SPECIMEN`, `BUS_ACTIVITIES`, `GM_NAME`, `PUR_MGR_NAME`, `FIN_MGR_NAME`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(1, '', 1, 'CRN5489478478', '2024-12-11', '2024-12-24', 'abc', '10000', 'abc', '12', 'abc', 'abc', 'abc', 'abc', 'abc', 'abc', 'abc', 'abc', '2024-12-19', 1, '2024-12-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_crapp_sign_info`
--

CREATE TABLE `xx_crm_crapp_sign_info` (
  `HEADER_ID` int(11) NOT NULL COMMENT 'Primary key for the sign info record',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier',
  `APP_HEADER_ID` int(11) DEFAULT NULL COMMENT 'Reference to the application header ID',
  `ZPIL_SIGN` varchar(255) DEFAULT NULL COMMENT 'ZPIL sign related information',
  `ZPIL_SIGNATORY_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the ZPIL signatory',
  `ZPIL_SIGN_POSN` varchar(255) DEFAULT NULL COMMENT 'Position of the ZPIL signatory',
  `ZPIL_DATE` date DEFAULT NULL COMMENT 'Date of the ZPIL signatory',
  `CLIENT_SIGN` varchar(255) DEFAULT NULL COMMENT 'Client signature information',
  `CLIENT_STAMP` varchar(255) DEFAULT NULL COMMENT 'Client stamp information',
  `CLIENT_SIGN_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the client signer',
  `CLIENT_SIGN_DATE` date DEFAULT NULL COMMENT 'Date of the client signature',
  `CHAMBER_OF_COMMERCE` varchar(255) DEFAULT NULL COMMENT 'Chamber of Commerce details',
  `CREATED_AT` date DEFAULT NULL COMMENT 'Record creation date',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User who created the record',
  `UPDATED_AT` date DEFAULT NULL COMMENT 'Last update date of the record',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User who last updated the record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_crapp_sign_info`
--

INSERT INTO `xx_crm_crapp_sign_info` (`HEADER_ID`, `UUID`, `APP_HEADER_ID`, `ZPIL_SIGN`, `ZPIL_SIGNATORY_NAME`, `ZPIL_SIGN_POSN`, `ZPIL_DATE`, `CLIENT_SIGN`, `CLIENT_STAMP`, `CLIENT_SIGN_NAME`, `CLIENT_SIGN_DATE`, `CHAMBER_OF_COMMERCE`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(1, '', 1, 'abc', 'abc', 'abc', '2024-12-01', 'abc', 'abc', 'abc', '2024-12-18', 'abc', '2024-12-19', 1, '2024-12-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_crapp_zpil_info`
--

CREATE TABLE `xx_crm_crapp_zpil_info` (
  `HEADER_ID` int(11) NOT NULL COMMENT 'Primary key for the ZPIL info record',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier',
  `APP_HEADER_ID` int(11) DEFAULT NULL COMMENT 'Reference to the application header ID',
  `DIR_SALES_COMMENTS` varchar(500) DEFAULT NULL COMMENT 'Director of Sales comments',
  `SALES_MGR_COMMENTS` varchar(500) DEFAULT NULL COMMENT 'Sales Manager comments',
  `GM_COMMENTS` varchar(500) DEFAULT NULL COMMENT 'General Manager comments',
  `CREDIT_DIV_COMMENTS` varchar(500) DEFAULT NULL COMMENT 'Credit Division comments',
  `FIN_MGR_COMMENTS` varchar(500) DEFAULT NULL COMMENT 'Finance Manager comments',
  `MGMT_COMMENTS` varchar(500) DEFAULT NULL COMMENT 'Management comments',
  `REC_CREDIT_LIMIT` varchar(50) DEFAULT NULL COMMENT 'Recommended credit limit',
  `REC_CREDIT_PERIOD` varchar(50) DEFAULT NULL COMMENT 'Recommended credit period',
  `APPROVED_FINANCE` varchar(10) DEFAULT NULL COMMENT 'Finance approval status',
  `APPROVED_MANAGEMENT` varchar(10) DEFAULT NULL COMMENT 'Management approval status',
  `CRN_ATTACHMENT` varchar(500) DEFAULT NULL COMMENT 'Attachment related to CRN',
  `BANK_CERTIFICATE` varchar(500) DEFAULT NULL COMMENT 'Bank certificate attachment',
  `OWNER_ID` varchar(500) DEFAULT NULL COMMENT 'Owner identification',
  `CREATED_AT` date DEFAULT NULL COMMENT 'Record creation date',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'User who created the record',
  `UPDATED_AT` date DEFAULT NULL COMMENT 'Last update date of the record',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User who last updated the record'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_crapp_zpil_info`
--

INSERT INTO `xx_crm_crapp_zpil_info` (`HEADER_ID`, `UUID`, `APP_HEADER_ID`, `DIR_SALES_COMMENTS`, `SALES_MGR_COMMENTS`, `GM_COMMENTS`, `CREDIT_DIV_COMMENTS`, `FIN_MGR_COMMENTS`, `MGMT_COMMENTS`, `REC_CREDIT_LIMIT`, `REC_CREDIT_PERIOD`, `APPROVED_FINANCE`, `APPROVED_MANAGEMENT`, `CRN_ATTACHMENT`, `BANK_CERTIFICATE`, `OWNER_ID`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(1, '', 1, 'this is comment', 'this is comment', 'this is comment', 'this is comment', 'this is comment', 'this is comment', '25000', '42', 'no', 'yes', '1734609182_logoNewDev_old.png', '1734609182_oliveschoollogo.jpg', '1734609182_Olive_School_and_College.png', '2024-12-19', 1, '2024-12-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_deals`
--

CREATE TABLE `xx_crm_deals` (
  `DEAL_ID` int(10) NOT NULL COMMENT 'Unique identifier for the deal (Primary Key)',
  `UUID` char(36) NOT NULL COMMENT 'Universally Unique Identifier for the deal',
  `DEAL_NAME` varchar(255) NOT NULL COMMENT 'Name or description of the deal',
  `ASSOCIATED_CONTACT_ID` int(10) DEFAULT NULL COMMENT 'Foreign key linking the deal to a contact',
  `DEAL_STAGE` varchar(50) DEFAULT NULL COMMENT 'Current stage of the deal (e.g., Prospecting, Negotiation, Closed-Won)',
  `DEAL_TYPE` varchar(50) DEFAULT NULL COMMENT 'Type of the deal (e.g., New, Renewal, Upsell)',
  `DEAL_VALUE` decimal(15,2) DEFAULT NULL COMMENT 'Estimated revenue value of the deal',
  `DEAL_PRIORITY` varchar(20) DEFAULT NULL COMMENT 'Priority level of the deal (e.g., High, Medium, Low)',
  `EXPECTED_CLOSE_DATE` date DEFAULT NULL COMMENT 'Expected date for deal closure',
  `ACTUAL_CLOSE_DATE` date DEFAULT NULL COMMENT 'Actual date of deal closure',
  `PROBABILITY` int(3) DEFAULT NULL COMMENT 'Probability of winning the deal (in percentage)',
  `ASSIGNED_TO` varchar(100) DEFAULT NULL COMMENT 'User or team managing the deal',
  `DEAL_SOURCE` varchar(50) DEFAULT NULL COMMENT 'Origin/source of the deal (e.g., Marketing Campaign, Referral)',
  `DEAL_STATUS` varchar(20) DEFAULT NULL COMMENT 'Current status of the deal (e.g., Open, Closed-Won, Closed-Lost)',
  `DEAL_DESCRIPTION` text DEFAULT NULL COMMENT 'Detailed description of the deal',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp of when the deal was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last update to the deal',
  `LAST_ACTIVITY_DATE` date DEFAULT NULL COMMENT 'Date of the most recent activity related to the deal',
  `NOTES` text DEFAULT NULL COMMENT 'Additional notes or comments about the deal',
  `CONTRACT_TERMS` text DEFAULT NULL COMMENT 'Terms and conditions of the deal or contract',
  `CLOSE_REASON` varchar(50) DEFAULT NULL COMMENT 'Reason why the deal was closed (e.g., Won, Lost, Stalled)',
  `DEAL_NUMBER` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `CONTACT_NUMBER` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_deals`
--

INSERT INTO `xx_crm_deals` (`DEAL_ID`, `UUID`, `DEAL_NAME`, `ASSOCIATED_CONTACT_ID`, `DEAL_STAGE`, `DEAL_TYPE`, `DEAL_VALUE`, `DEAL_PRIORITY`, `EXPECTED_CLOSE_DATE`, `ACTUAL_CLOSE_DATE`, `PROBABILITY`, `ASSIGNED_TO`, `DEAL_SOURCE`, `DEAL_STATUS`, `DEAL_DESCRIPTION`, `CREATED_AT`, `UPDATED_AT`, `LAST_ACTIVITY_DATE`, `NOTES`, `CONTRACT_TERMS`, `CLOSE_REASON`, `DEAL_NUMBER`, `EMAIL`, `CONTACT_NUMBER`) VALUES
(4, '94cc1aaf-2c37-4ffe-9dea-9e0c2fe11527', 'Mirza Nauman Aasif', 0, 'qualification', 'new-business', 10000.00, 'medium', '2024-12-17', '2024-12-18', 89, 'Kaif Sayyed', 'Marketing', 'new', 'Test Description', '2024-12-09 04:55:32', '2024-12-09 04:55:32', NULL, NULL, NULL, '', 'D091224000004', 'namirza333@gmail.com', '09373350308');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_email_config`
--

CREATE TABLE `xx_crm_email_config` (
  `ID` int(11) NOT NULL COMMENT 'Unique identifier for each email configuration',
  `EMAIL_PROVIDER` varchar(100) NOT NULL COMMENT 'Name of the email service provider (e.g., Gmail, Outlook)',
  `SMTP_SERVER` varchar(255) NOT NULL COMMENT 'SMTP server address (e.g., smtp.gmail.com)',
  `SMTP_PORT` int(11) NOT NULL COMMENT 'Port number for SMTP (e.g., 587 for TLS, 465 for SSL)',
  `USERNAME` varchar(255) NOT NULL COMMENT 'Username for the email account',
  `PASSWORD` varchar(500) NOT NULL COMMENT 'Encrypted password for the email account',
  `FROM_EMAIL` varchar(255) NOT NULL COMMENT 'Default "from" email address',
  `USE_SSL` tinyint(1) DEFAULT 0 COMMENT 'Indicates whether to use SSL (true/false)',
  `USE_TLS` tinyint(1) DEFAULT 0 COMMENT 'Indicates whether to use TLS (true/false)',
  `IS_ACTIVE` tinyint(1) DEFAULT 1 COMMENT 'Indicates whether the configuration is active (true/false)',
  `DESCRIPTION` text DEFAULT NULL COMMENT 'Additional information or notes about the configuration',
  `CREATED_AT` datetime DEFAULT current_timestamp() COMMENT 'Timestamp when the configuration was created',
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last update to the configuration'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_email_config`
--

INSERT INTO `xx_crm_email_config` (`ID`, `EMAIL_PROVIDER`, `SMTP_SERVER`, `SMTP_PORT`, `USERNAME`, `PASSWORD`, `FROM_EMAIL`, `USE_SSL`, `USE_TLS`, `IS_ACTIVE`, `DESCRIPTION`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 'Outlook', 'smtp.office365.com', 587, 'Zamil Emailer', 'QWJjJDEyMzQ=', 'workflowmailer@zamilplastic.com', 0, 1, 1, 'Stores email configuration settings for the Zamil Workflow Email', '2024-12-25 11:06:51', '2024-12-25 11:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_leads`
--

CREATE TABLE `xx_crm_leads` (
  `LEAD_ID` int(11) NOT NULL COMMENT 'Primary key for the lead record',
  `UUID` varchar(50) NOT NULL COMMENT 'Unique Identifier UUID',
  `LEAD_NUMBER` varchar(20) DEFAULT NULL COMMENT 'Lead number for tracking of lead and lead activities',
  `FIRST_NAME` varchar(100) NOT NULL COMMENT 'First name of the lead',
  `LAST_NAME` varchar(100) NOT NULL COMMENT 'Last name of the lead',
  `EMAIL` varchar(255) NOT NULL COMMENT 'Email address of the lead',
  `PHONE` varchar(20) DEFAULT NULL COMMENT 'Phone number of the lead',
  `COMPANY_NAME` varchar(255) DEFAULT NULL COMMENT 'Company name associated with the lead',
  `JOB_TITLE` varchar(255) DEFAULT NULL COMMENT 'Job Title of the lead if lead associated with an employee',
  `LEAD_SOURCE` varchar(100) DEFAULT NULL COMMENT 'Source of the lead (e.g., Website, Referral)',
  `STATUS` varchar(20) DEFAULT 'new' COMMENT 'Current status of the lead',
  `ASSIGNED_TO` int(11) DEFAULT NULL COMMENT 'User ID of the assigned salesperson',
  `LEAD_SCORE` int(11) DEFAULT NULL COMMENT 'Score indicating lead quality',
  `CREATED_AT` datetime DEFAULT current_timestamp() COMMENT 'Timestamp when the lead was created',
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the lead was last updated',
  `NOTES` text DEFAULT NULL COMMENT 'Additional notes about the lead'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_leads`
--

INSERT INTO `xx_crm_leads` (`LEAD_ID`, `UUID`, `LEAD_NUMBER`, `FIRST_NAME`, `LAST_NAME`, `EMAIL`, `PHONE`, `COMPANY_NAME`, `JOB_TITLE`, `LEAD_SOURCE`, `STATUS`, `ASSIGNED_TO`, `LEAD_SCORE`, `CREATED_AT`, `UPDATED_AT`, `NOTES`) VALUES
(4, 'bbea2ade-d1df-4991-81de-08bd843438ff', 'L261124000004', 'Zoheb', 'Shaikh', 'zoheb@kisworld.com', '5487542154', 'Kissu Group Pvt Ltd', 'Chairman', 'Marketing', 'qualified', 0, NULL, '2024-11-26 00:00:00', '2024-12-06 00:00:00', NULL),
(5, 'a1c2232d-dfb9-49cf-8103-314d171c3ea7', 'L261124000005', 'Ab Azim', 'Shaikh R', 'azz.kis@kisworld.com', '8754214587', 'Kissu Group Pvt Ltd', 'Chief Technology Officer', 'Reference, Marketing', 'new', 0, NULL, '2024-11-26 00:00:00', '2024-11-27 00:00:00', NULL),
(6, '1f6e23b8-d09b-4133-b751-6959695816f4', 'L261124000006', 'Sayyed', 'Zaid', 'zaid@con.com', '89764523', 'Sayyed Consultancy', 'CEO', 'Marketing', 'engaged', 0, NULL, '2024-11-26 00:00:00', '2024-12-06 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_lead_activities`
--

CREATE TABLE `xx_crm_lead_activities` (
  `ACTIVITY_ID` int(11) NOT NULL,
  `UUID` varchar(50) DEFAULT NULL,
  `LEAD_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `ACTIVITY_TYPE` varchar(30) NOT NULL,
  `ACTIVITY_DATE` date NOT NULL,
  `STATUS` enum('active','inactive') NOT NULL,
  `NOTES` text DEFAULT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp(),
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ACTIVITY_SOURCE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_lead_activities`
--

INSERT INTO `xx_crm_lead_activities` (`ACTIVITY_ID`, `UUID`, `LEAD_ID`, `USER_ID`, `ACTIVITY_TYPE`, `ACTIVITY_DATE`, `STATUS`, `NOTES`, `CREATED_AT`, `UPDATED_AT`, `ACTIVITY_SOURCE`) VALUES
(28, '54f5659b-15d3-402e-bfe8-19b6538dff38', 6, 1, 'Call', '2024-12-07', 'active', '<h2>Test of the client</h2>', '2024-12-06 18:30:00', '2024-12-07 07:02:57', NULL),
(29, 'c2d0135a-780e-4107-8159-27f0b4cc4ad0', 6, 1, 'Notes', '2024-12-26', 'active', '<h2>Notes</h2><p>This is the notes here</p>', '2024-12-06 18:30:00', '2024-12-26 10:57:20', NULL),
(30, '0f375bfc-ead7-464f-aa74-0482831d6a67', 5, 1, 'Meeting', '2024-12-07', 'active', '<h2>Saw</h2><p>ilan e nubuwat</p>', '2024-12-06 18:30:00', '2024-12-07 07:03:58', NULL),
(31, '791d1b60-1827-4dc9-ae7b-acab1d75e144', 2, 1, 'Notes', '2024-12-07', 'active', '<p>this is the notes</p><p>tijarat</p>', '2024-12-06 18:30:00', '2024-12-07 07:08:06', 'deals'),
(32, 'cc88958e-9983-4856-a12c-13ca3dde6937', 4, 1, 'Notes', '2024-12-07', 'active', '<p>sab kuch chhod diya</p><p><br></p><p>saye ki tarah</p>', '2024-12-06 18:30:00', '2024-12-07 07:08:33', NULL),
(33, '5f57869d-52d6-4efe-be1a-813ac827a2b1', 5, 1, 'Notes', '2024-12-07', 'active', '<p>apne jamane me puchaa tha</p>', '2024-12-06 18:30:00', '2024-12-07 07:08:51', NULL),
(34, 'cc6a8857-86d3-48e6-805f-4f4010b9dde8', 2, 1, 'Meeting', '2024-12-07', 'active', '<h2>Test</h2><p>This is test and deals </p>', '2024-12-06 18:30:00', '2024-12-07 07:10:12', 'deals'),
(37, '7825e667-567c-4e77-84fb-5ab54a5129ea', 4, 1, 'task', '2024-12-09', 'active', '<h2>Task </h2><p>Task 1</p><p>Task 2</p>', '2024-12-08 18:30:00', '2024-12-09 04:56:06', 'deals');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_minutes_of_meeting`
--

CREATE TABLE `xx_crm_minutes_of_meeting` (
  `MOM_ID` int(11) NOT NULL COMMENT 'Unique identifier for each Minutes of Meeting record.',
  `UUID` varchar(50) NOT NULL COMMENT 'Unique Identifier For getting AUTO ID',
  `MEETING_TITLE` varchar(255) NOT NULL COMMENT 'Title or subject of the meeting.',
  `MEETING_DATE` date NOT NULL COMMENT 'Date and time of the meeting.',
  `DURATION` varchar(50) DEFAULT NULL COMMENT 'Total duration of the meeting.',
  `LOCATION_PLATFORM` varchar(255) DEFAULT NULL COMMENT 'Location or virtual platform used for the meeting.',
  `ORGANIZER` varchar(255) NOT NULL COMMENT 'User ID of the meeting organizer.',
  `ATTENDEES` text DEFAULT NULL COMMENT 'Comma-separated list of attendee IDs.',
  `AGENDA` text DEFAULT NULL COMMENT 'Brief overview of the meeting agenda.',
  `MEETING_TYPE` enum('Project Review','Sales Call','Strategy Planning','Other') DEFAULT 'Other' COMMENT 'Type of meeting.',
  `DISCUSSION_TOPICS` varchar(500) DEFAULT NULL COMMENT 'JSON array to store discussion topics and details.',
  `DECISIONS` varchar(500) DEFAULT NULL COMMENT 'JSON array to store decisions made and responsible parties.',
  `COMPANY_NAME` varchar(500) DEFAULT NULL COMMENT 'JSON array to store action items, assignees, priorities, and deadlines.',
  `GENERAL_NOTES` text DEFAULT NULL COMMENT 'Additional remarks or observations from the meeting.',
  `FOLLOW_UP_REQUIRED` tinyint(1) DEFAULT 0 COMMENT 'Indicates if a follow-up meeting is required.',
  `ATTACHMENTS` text DEFAULT NULL COMMENT 'Paths or references to uploaded files.',
  `ASSOCIATED_PROJECT_ID` int(11) DEFAULT NULL COMMENT 'ID of the project associated with the MoM.',
  `TAGS` text DEFAULT NULL COMMENT 'Comma-separated list of keywords for categorization.',
  `CREATED_BY` int(11) NOT NULL COMMENT 'User ID of the person who created the MoM.',
  `LAST_UPDATED_BY` int(11) DEFAULT NULL COMMENT 'User ID of the person who last modified the MoM.',
  `VERSION` int(11) DEFAULT 1 COMMENT 'Version number for MoM tracking.',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the MoM was created.',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the MoM was last updated.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_minutes_of_meeting`
--

INSERT INTO `xx_crm_minutes_of_meeting` (`MOM_ID`, `UUID`, `MEETING_TITLE`, `MEETING_DATE`, `DURATION`, `LOCATION_PLATFORM`, `ORGANIZER`, `ATTENDEES`, `AGENDA`, `MEETING_TYPE`, `DISCUSSION_TOPICS`, `DECISIONS`, `COMPANY_NAME`, `GENERAL_NOTES`, `FOLLOW_UP_REQUIRED`, `ATTACHMENTS`, `ASSOCIATED_PROJECT_ID`, `TAGS`, `CREATED_BY`, `LAST_UPDATED_BY`, `VERSION`, `CREATED_AT`, `UPDATED_AT`) VALUES
(7, 'd16c99d1-586f-4124-bc38-7847aa86f43b', 'Meeting to show CRM Demo', '2024-12-18', '10 Mins', 'Microsoft Teams', 'Zaid Sayyed', '[{\"name\":\"Zaid Sayyed\",\"email\":\"zaid.sayyed@zamilplastic.com\"},{\"name\":\"Shaikh Azim\",\"email\":\"shaikh.azim@olivesofts.com\"}]', 'CRM Demo', 'Project Review', 'CRM demo', '', 'OLIVE SOFT', NULL, 1, NULL, NULL, NULL, 1, NULL, 1, '2024-12-17 11:34:01', '2024-12-17 11:34:01');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_po_header`
--

CREATE TABLE `xx_crm_po_header` (
  `PO_ID` int(11) NOT NULL COMMENT 'Unique Identifier for the Purchase Order',
  `PO_NUMBER` varchar(50) NOT NULL COMMENT 'Sequential or Generated Number for the Purchase Order',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier for the Purchase Order',
  `CLIENT_ID` int(11) DEFAULT NULL,
  `QUOTE_ID` int(11) DEFAULT NULL COMMENT 'Reference to the Quotation ID',
  `REQUEST_ID` int(11) DEFAULT NULL COMMENT 'Reference to the Associated Request ID',
  `COMPANY_NAME` varchar(255) NOT NULL COMMENT 'Name of the Company',
  `EMAIL_ADDRESS` varchar(255) DEFAULT NULL COMMENT 'Email Address of the Company Contact',
  `COMPANY_ADDRESS` varchar(255) DEFAULT NULL COMMENT 'Address of the Company',
  `CONTACT_NUMBER` varchar(20) DEFAULT NULL COMMENT 'Contact Number of the Company',
  `CURRENCY` varchar(10) DEFAULT NULL COMMENT 'Currency for the Purchase Order (e.g., USD, EUR)',
  `PAYMENT_TERM` varchar(255) DEFAULT NULL COMMENT 'Payment Terms for the Purchase Order',
  `PO_STATUS` varchar(50) NOT NULL COMMENT 'Status of the Purchase Order (e.g., Draft, Approved, Rejected)',
  `SUBTOTAL` decimal(15,2) DEFAULT NULL COMMENT 'Subtotal Amount Before Discounts and Taxes',
  `DISCOUNT_PERCENTAGE` decimal(5,2) DEFAULT NULL COMMENT 'Percentage Discount Applied to the Subtotal',
  `TAX_PERCENTAGE` decimal(5,2) DEFAULT NULL COMMENT 'Tax Percentage Applied After Discounts',
  `TOTAL_AMOUNT` decimal(15,2) DEFAULT NULL COMMENT 'Total Amount After Discounts and Taxes',
  `COMMENTS` text DEFAULT NULL COMMENT 'Additional Comments for the Purchase Order',
  `ATTACHMENTS` text DEFAULT NULL COMMENT 'Links or References to Attached Files',
  `ACTION_BY` varchar(50) DEFAULT NULL COMMENT 'User type of the User Who Performed the Last Action',
  `VERSION` varchar(10) DEFAULT '1' COMMENT 'Version Number of the Purchase Order',
  `IS_CONVERTED` tinyint(1) DEFAULT 0 COMMENT 'Indicates if the Purchase Order is Converted from Another Document',
  `CONVERTED_FROM` int(11) DEFAULT NULL COMMENT 'Reference to the Original PO ID if Converted',
  `CREATED_AT` datetime DEFAULT current_timestamp() COMMENT 'Timestamp When the Purchase Order was Created',
  `CREATED_BY` int(11) NOT NULL COMMENT 'ID of the User Who Created the Purchase Order',
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp When the Purchase Order was Last Updated',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'ID of the User Who Last Updated the Purchase Order'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_po_lines`
--

CREATE TABLE `xx_crm_po_lines` (
  `LINE_ID` int(11) NOT NULL COMMENT 'Unique Identifier for the Purchase Order Line',
  `PO_ID` int(11) NOT NULL COMMENT 'Reference to the Purchase Order Header ID',
  `PRODUCT_ID` int(11) NOT NULL COMMENT 'Reference to the Product ID',
  `PRODUCT_DESC` text DEFAULT NULL COMMENT 'Description of the Product or Item',
  `QTY` int(11) NOT NULL COMMENT 'Quantity Ordered',
  `UNIT_PRICE` decimal(15,2) NOT NULL COMMENT 'Price per Unit of the Product or Item',
  `TOTAL` decimal(15,2) DEFAULT NULL COMMENT 'Total Price for the Line Item',
  `COLOR` varchar(50) DEFAULT NULL COMMENT 'Color or Variant of the Product',
  `TRANSPORT` varchar(255) DEFAULT NULL COMMENT 'Transport Details for the Line Item',
  `SOC` varchar(50) DEFAULT NULL COMMENT 'Shipping Order Code (SOC) or Similar Reference',
  `REC_QTY` varchar(50) DEFAULT '0' COMMENT 'Quantity Received',
  `BAL_QTY` varchar(50) DEFAULT NULL COMMENT 'Balance Quantity Remaining to be Received'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(7, 'd287478f-4b36-44ac-8330-d4b1c12be53a', '051124000007', '444', 2, 'active', 'Test Zamil Product 1', '<h1>Description</h1><p><strong>Lorem ipsum dolor sit amet,</strong> consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum. Nulla facilisi. Sed sit amet accumsan arcu. In non felis justo. Mauris varius tortor vel mi elementum, nec aliquam arcu lacinia. Duis dapibus, purus vel convallis auctor, <u>nisi </u>massa volutpat mi, vitae gravida libero eros in justo.</p><p><br></p><p><em>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</em></p>', 123.00, 'SAR', 'no discount', NULL, 'yes', 15.00, '[\"avatar3.png\",\"default-image3.png\",\"zpil-logo.png\"]', 0.33, 120.00, 120.00, '120', 1, '2024-11-05 08:06:22', '2024-12-26 11:17:18');

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
-- Table structure for table `xx_crm_projects`
--

CREATE TABLE `xx_crm_projects` (
  `PROJECT_ID` int(11) NOT NULL COMMENT 'Unique identifier for the project',
  `UUID` varchar(50) NOT NULL COMMENT 'Unique Identifier to read AUTO ID',
  `PROJECT_CODE` varchar(50) NOT NULL COMMENT 'Unique project code/ID',
  `PROJECT_NAME` varchar(255) NOT NULL COMMENT 'Name of the project',
  `DESCRIPTION` text DEFAULT NULL COMMENT 'Description of the project',
  `STATUS` enum('NOT_STARTED','IN_PROGRESS','COMPLETED','ON_HOLD') DEFAULT 'NOT_STARTED' COMMENT 'Current status of the project',
  `START_DATE` date DEFAULT NULL COMMENT 'Start date of the project',
  `END_DATE` date DEFAULT NULL COMMENT 'End date of the project',
  `PRIORITY` enum('LOW','MEDIUM','HIGH') DEFAULT 'MEDIUM' COMMENT 'Priority of the project',
  `PROJECT_TYPE` enum('INTERNAL','CLIENT','RESEARCH','OTHER') DEFAULT 'INTERNAL' COMMENT 'Type of project',
  `PROJECT_MANAGER_ID` int(11) DEFAULT NULL COMMENT 'ID of the project manager (can reference a user table)',
  `PROJECT_MANAGER` varchar(255) DEFAULT NULL,
  `CLIENT_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the client (optional)',
  `CLIENT_CONTACT` varchar(255) DEFAULT NULL COMMENT 'Contact information for the client (optional)',
  `CLIENT_EMAIL` varchar(255) DEFAULT NULL COMMENT 'Client''s email address (optional)',
  `TOTAL_BUDGET` decimal(10,2) DEFAULT NULL COMMENT 'Total budget for the project (optional)',
  `CURRENT_SPEND` decimal(10,2) DEFAULT NULL COMMENT 'Current spend on the project (optional)',
  `PROGRESS` decimal(5,2) DEFAULT 0.00 COMMENT 'Progress of the project as a percentage (0 to 100%)',
  `ATTACHMENTS` text DEFAULT NULL COMMENT 'JSON or comma-separated list of file URLs/paths for project documents',
  `NOTES` text DEFAULT NULL COMMENT 'Internal notes or comments about the project',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date and time when the project was created',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Date and time when the project was last updated',
  `VISIBLE` enum('PRIVATE','PUBLIC','RESTRICTED') DEFAULT 'PRIVATE' COMMENT 'Visibility of the project (private, public, or restricted)',
  `CREATED_BY` int(11) DEFAULT NULL COMMENT 'ID of the user who created the project (optional)',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'ID of the user who last updated the project (optional)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_projects`
--

INSERT INTO `xx_crm_projects` (`PROJECT_ID`, `UUID`, `PROJECT_CODE`, `PROJECT_NAME`, `DESCRIPTION`, `STATUS`, `START_DATE`, `END_DATE`, `PRIORITY`, `PROJECT_TYPE`, `PROJECT_MANAGER_ID`, `PROJECT_MANAGER`, `CLIENT_NAME`, `CLIENT_CONTACT`, `CLIENT_EMAIL`, `TOTAL_BUDGET`, `CURRENT_SPEND`, `PROGRESS`, `ATTACHMENTS`, `NOTES`, `CREATED_AT`, `UPDATED_AT`, `VISIBLE`, `CREATED_BY`, `UPDATED_BY`) VALUES
(2, 'd9ea21f7-19d0-4f11-950d-e086e7c50c4f', 'P-121224000002', 'Zamil CRM Application', 'CRM Application for the Zamil and CLient', 'COMPLETED', '2024-12-01', '0000-00-00', 'MEDIUM', 'INTERNAL', NULL, 'Zaid Sayyed', 'Ajim Shaikh', '09373350308', 'aashaikh@olivesofts.com', 100.00, 10.00, 25.00, '[]', '', '2024-12-12 11:46:50', '2024-12-12 11:48:35', 'PRIVATE', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_quotations`
--

CREATE TABLE `xx_crm_quotations` (
  `QUOTE_ID` int(11) NOT NULL COMMENT 'Unique Identifier for the Quotation Header',
  `UUID` varchar(50) NOT NULL COMMENT 'Unique UUID for the quotation',
  `QUOTE_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Random or Sequential Number for the Quotation',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'Reference to the Client ID',
  `REQUEST_ID` int(11) NOT NULL COMMENT 'Reference to the Associated Request ID',
  `COMPANY_ADDRESS` varchar(500) DEFAULT NULL COMMENT 'Address of the Client Company',
  `JOB_TITLE` varchar(255) DEFAULT NULL COMMENT 'Title of the Job or Project',
  `EMPLOYEE_NAME` varchar(255) DEFAULT NULL COMMENT 'Name of the Employee Creating the Quotation',
  `EMAIL_ADDRESS` varchar(255) DEFAULT NULL COMMENT 'Address of the Employee',
  `MOBILE_NUMBER` varchar(30) DEFAULT NULL COMMENT 'Mobile Number of the Employee or Client',
  `SALES_PERSON` varchar(255) DEFAULT NULL COMMENT 'Salesperson Assigned to the Quotation',
  `CURRENCY` varchar(10) DEFAULT NULL COMMENT 'Currency for the Quotation (e.g., USD, EUR)',
  `PAYMENT_TERM` varchar(255) DEFAULT NULL COMMENT 'Payment Terms for the Quotation',
  `SUB_TOTAL` decimal(15,2) DEFAULT NULL COMMENT 'Subtotal Amount Before Discounts and Taxes',
  `DISCOUNT_PERCENTAGE` decimal(5,2) DEFAULT NULL COMMENT 'Percentage Discount Applied to the Subtotal',
  `TAX_PERCENTAGE` decimal(5,2) DEFAULT NULL COMMENT 'Tax Percentage Applied After Discounts',
  `TOTAL_AMOUNT` decimal(15,2) DEFAULT NULL COMMENT 'Total Amount After Discounts and Taxes',
  `COMMENTS` text DEFAULT NULL COMMENT 'Additional Comments for the Quotation',
  `INTERNAL_NOTES` text DEFAULT NULL COMMENT 'Internal Notes Not Visible to the Client',
  `ATTACHMENTS` text DEFAULT NULL COMMENT 'Links or References to Attached Files',
  `QUOTE_STATUS` varchar(50) NOT NULL COMMENT 'Status of the Quotation (e.g., Draft, Approved, Rejected)',
  `VERSION` varchar(10) DEFAULT '1' COMMENT 'Version Number of the Quotation',
  `IS_CONVERTED` tinyint(1) DEFAULT 0 COMMENT 'Indicates if the Quotation is Converted from old quotation',
  `CONVERTED_FROM` int(11) DEFAULT NULL COMMENT 'Reference to the Original Quotation if Converted',
  `ACTION_BY` int(11) DEFAULT NULL COMMENT 'User type of the User Who Performed the Last Action',
  `CREATED_AT` datetime DEFAULT current_timestamp() COMMENT 'Timestamp When the Quotation was Created',
  `CREATED_BY` int(11) NOT NULL COMMENT 'ID of the User Who Created the Quotation',
  `UPDATED_AT` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp When the Quotation was Last Updated',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'ID of the User Who Last Updated the Quotation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_quotations`
--

INSERT INTO `xx_crm_quotations` (`QUOTE_ID`, `UUID`, `QUOTE_NUMBER`, `CLIENT_ID`, `REQUEST_ID`, `COMPANY_ADDRESS`, `JOB_TITLE`, `EMPLOYEE_NAME`, `EMAIL_ADDRESS`, `MOBILE_NUMBER`, `SALES_PERSON`, `CURRENCY`, `PAYMENT_TERM`, `SUB_TOTAL`, `DISCOUNT_PERCENTAGE`, `TAX_PERCENTAGE`, `TOTAL_AMOUNT`, `COMMENTS`, `INTERNAL_NOTES`, `ATTACHMENTS`, `QUOTE_STATUS`, `VERSION`, `IS_CONVERTED`, `CONVERTED_FROM`, `ACTION_BY`, `CREATED_AT`, `CREATED_BY`, `UPDATED_AT`, `UPDATED_BY`) VALUES
(4, '9da0858a-a8fb-497b-9587-7e0b6e907b3e', 'QUO-171224000004', 7, 7, 'Kismatji Beauty World', 'Purchasing Officer', 'Purchase Officer 1', 'kis.beauty@kisworld.com', '7854215487', 'Sales Person3', 'SAR', 'CASH', 123000.00, 0.00, 0.00, 123000.00, '', '', '[]', 'Pending', '1', 0, NULL, 0, '2024-12-17 16:00:26', 1, '2024-12-17 16:00:26', NULL),
(5, 'ccbd267b-9271-4a42-b65e-97854bab6035', 'QUO-171224000005', 7, 7, NULL, 'Purchasing Officer', 'Purchase Officer 1', 'kis.beauty@kisworld.com', '7854215487', 'Sales Person3', 'SAR', 'CASH', 123000.00, 0.00, 0.00, 123000.00, '', '', '[]', 'draft', '1', 0, NULL, NULL, '2024-12-17 16:00:33', 0, '2024-12-17 16:00:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_quotation_lines`
--

CREATE TABLE `xx_crm_quotation_lines` (
  `LINE_ID` int(11) NOT NULL COMMENT 'Unique Identifier for the Quotation Line',
  `QUOTE_ID` int(11) NOT NULL COMMENT 'Reference to the Quotation Header ID',
  `PRODUCT_ID` int(11) NOT NULL COMMENT 'Reference to the Product ID',
  `PRODUCT` varchar(255) DEFAULT NULL COMMENT 'Name of the Product or Item',
  `DESCRIPTION` text DEFAULT NULL COMMENT 'Description of the Product or Item',
  `QTY` int(11) NOT NULL COMMENT 'Quantity of the Product or Item',
  `UNIT_PRICE` decimal(15,2) NOT NULL COMMENT 'Price per Unit of the Product or Item',
  `TOTAL` decimal(15,2) DEFAULT NULL COMMENT 'Total Price for the Line Item',
  `COLOR` varchar(50) DEFAULT NULL COMMENT 'Color or Variant of the Product',
  `TRANSPORTATION` varchar(255) DEFAULT NULL COMMENT 'transportation type Related to Transportation or Delivery',
  `LINE_COMMENTS` varchar(255) DEFAULT NULL COMMENT 'Comments Related to current line'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_quotation_lines`
--

INSERT INTO `xx_crm_quotation_lines` (`LINE_ID`, `QUOTE_ID`, `PRODUCT_ID`, `PRODUCT`, `DESCRIPTION`, `QTY`, `UNIT_PRICE`, `TOTAL`, `COLOR`, `TRANSPORTATION`, `LINE_COMMENTS`) VALUES
(13, 4, 7, NULL, NULL, 1000, 123.00, 123000.00, 'All', 'Shipping', ''),
(14, 5, 7, NULL, NULL, 1000, 123.00, 123000.00, 'All', 'Shipping', '');

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
(7, 'REQ-151124000007', '2f89c632-b7fe-44eb-b23d-8849c42f6a85', 7, 'Kismatji Beauty World Product Quotes', 'Kismatji Beauty World', 'Address Line 1, Address Street', 'Hxjxjxj', '7854215487', 'kis.beauty@kisworld.com', 'This request is made from kismatji world beauty products.', 'Lorem ipsum dolor sit', '[\"avatar1.png\",\"avatar-user1.png\",\"avatar-user-placeholder1.PNG\",\"user-profile-icon-avatar1.jpg\"]', '2024-11-15 10:09:19', 1, '2024-11-22 06:12:58', NULL, 'draft', 'admin', '1'),
(8, 'REQ-171224000008', 'a0ca9424-e04f-410a-a4fc-c41839667c4b', 5, 'Test Request After Quote Integrate', 'Test Company Pvt Ltd', 'Riyadh Address Line1', 'Riyadh Shipping Address', '966 56214584', 'test.main@cm.com', 'Test Request Details', 'Internal Notes', '[\"light-off.png\",\"light-on.png\"]', '2024-12-17 06:35:54', 1, '2024-12-17 06:35:54', NULL, 'draft', 'admin', '1');

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
(19, 7, '7', 'Kismat JiLorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum. Nulla facilisi. Sed sit amet accumsan arcu. In non felis justo. Mauris varius tortor vel mi elementum, nec aliquam arcu lacinia. Duis dapibus, purus vel convallis auctor, nisi massa volutpat mi, vitae gravida libero eros in justo.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et q', 1000, '2024-11-23', 'All', 'Shipping', ''),
(20, 8, '2', 'Semi Finished good with IBM', 10, '2024-12-18', 'All', 'Shipping', '-');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_survey`
--

CREATE TABLE `xx_crm_survey` (
  `SURVEY_ID` int(11) NOT NULL COMMENT 'Unique identifier for the survey record',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally unique identifier for the survey',
  `SURVEY_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Unique survey number for tracking',
  `SURVEY_NAME` varchar(100) NOT NULL COMMENT 'Name of the survey',
  `SURVEY_DESC` text NOT NULL COMMENT 'Detailed description of the survey',
  `START_DATE` date NOT NULL COMMENT 'Survey start date',
  `END_DATE` date NOT NULL COMMENT 'Survey end date',
  `CONDUCTED_BY` varchar(100) NOT NULL COMMENT 'Name of the person or entity conducting the survey',
  `STATUS` varchar(30) NOT NULL COMMENT 'Current status of the survey (e.g., Active, Inactive, Completed)',
  `SURVEY_YEAR` varchar(10) DEFAULT NULL COMMENT 'Year associated with the survey',
  `VERSION` int(11) NOT NULL DEFAULT 1 COMMENT 'Version number of the survey record',
  `CREATED_BY` varchar(100) NOT NULL COMMENT 'User who created the record',
  `CREATED_AT` datetime NOT NULL COMMENT 'Timestamp when the record was created',
  `UPDATED_BY` varchar(100) NOT NULL COMMENT 'User who last updated the record',
  `UPDATED_AT` datetime NOT NULL COMMENT 'Timestamp when the record was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_survey`
--

INSERT INTO `xx_crm_survey` (`SURVEY_ID`, `UUID`, `SURVEY_NUMBER`, `SURVEY_NAME`, `SURVEY_DESC`, `START_DATE`, `END_DATE`, `CONDUCTED_BY`, `STATUS`, `SURVEY_YEAR`, `VERSION`, `CREATED_BY`, `CREATED_AT`, `UPDATED_BY`, `UPDATED_AT`) VALUES
(1, '9e105d10-48cb-4e92-b049-5e43443900e3', 'SN122324000001', 'Zamil - Quality of the Product', 'This survey is use to check quality of our products', '2024-12-22', '2024-12-24', 'Zamil Plastic Industries', 'draft', '2024', 1, '', '2024-12-23 00:00:00', '', '0000-00-00 00:00:00'),
(2, '9e105d10-48cb-4e92-b049-5e43443900e3', NULL, 'Production Survey', 'Production survey', '2024-12-24', '2024-12-26', 'Zamil Production Teams', 'draft', '2024', 1, '', '2024-12-23 00:00:00', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_survey_header`
--

CREATE TABLE `xx_crm_survey_header` (
  `HEADER_ID` int(11) NOT NULL COMMENT 'Unique identifier for the survey response header',
  `SURVEY_ID` int(11) DEFAULT NULL COMMENT 'Foreign key linking to the survey in XX_CRM_SURVEY',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally unique identifier for the survey response',
  `FILLED_DATE` date NOT NULL COMMENT 'Date when the survey was filled out',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'Unique identifier for the client responding to the survey',
  `RECOMMENDATION` varchar(255) DEFAULT NULL COMMENT 'Recommendations based on the survey',
  `COMMENTS` text DEFAULT NULL COMMENT 'Additional comments provided in the survey',
  `SURVEY_YEAR` varchar(4) DEFAULT NULL COMMENT 'Year associated with the survey response',
  `CREATED_BY` varchar(100) NOT NULL COMMENT 'User who created the record',
  `CREATED_AT` datetime NOT NULL COMMENT 'Timestamp when the record was created',
  `UPDATED_BY` varchar(100) NOT NULL COMMENT 'User who last updated the record',
  `UPDATED_AT` datetime NOT NULL COMMENT 'Timestamp when the record was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_survey_header`
--

INSERT INTO `xx_crm_survey_header` (`HEADER_ID`, `SURVEY_ID`, `UUID`, `FILLED_DATE`, `CLIENT_ID`, `RECOMMENDATION`, `COMMENTS`, `SURVEY_YEAR`, `CREATED_BY`, `CREATED_AT`, `UPDATED_BY`, `UPDATED_AT`) VALUES
(1, 2, '5dcef661-ab7f-40e3-be34-2600d3e6c0a2', '2024-12-23', 4, 'yes', 'this comment is for the survey we are filling', '2024', '4', '2024-12-23 00:00:00', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_survey_lines`
--

CREATE TABLE `xx_crm_survey_lines` (
  `LINE_ID` int(11) NOT NULL COMMENT 'Unique identifier for the survey line item',
  `SURVEY_HEADER_ID` int(11) NOT NULL COMMENT 'Foreign key linking to the survey header in XX_CRM_SURVEY_HEADER',
  `OPTIONS` longtext DEFAULT NULL COMMENT 'Categories associated with this survey line item'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_survey_lines`
--

INSERT INTO `xx_crm_survey_lines` (`LINE_ID`, `SURVEY_HEADER_ID`, `OPTIONS`) VALUES
(1, 1, '[{\"seq\":1,\"rating\":\"strongly-disagree\"},{\"seq\":2,\"rating\":\"disagree\"},{\"seq\":3,\"rating\":\"neutral\"},{\"seq\":4,\"rating\":\"agree\"},{\"seq\":5,\"rating\":\"strongly-disagree\"},{\"seq\":6,\"rating\":\"disagree\"},{\"seq\":7,\"rating\":\"neutral\"},{\"seq\":8,\"rating\":\"agree\"},{\"seq\":9,\"rating\":\"strongly-agree\"},{\"seq\":10,\"rating\":\"strongly-disagree\"},{\"seq\":11,\"rating\":\"strongly-disagree\"},{\"seq\":12,\"rating\":\"disagree\"},{\"seq\":13,\"rating\":\"neutral\"},{\"seq\":14,\"rating\":\"agree\"},{\"seq\":15,\"rating\":\"strongly-agree\"},{\"seq\":16,\"rating\":\"strongly-disagree\"},{\"seq\":17,\"rating\":\"disagree\"},{\"seq\":18,\"rating\":\"neutral\"},{\"seq\":19,\"rating\":\"strongly-disagree\"},{\"seq\":20,\"rating\":\"disagree\"},{\"seq\":21,\"rating\":\"neutral\"},{\"seq\":22,\"rating\":\"agree\"}]');

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
-- Table structure for table `xx_crm_uom`
--

CREATE TABLE `xx_crm_uom` (
  `UOM_ID` int(11) NOT NULL COMMENT 'Auto-incrementing unique identifier for each UOM.',
  `UUID` varchar(50) NOT NULL COMMENT 'Universally Unique Identifier for the UOM record.',
  `UOM_CODE` varchar(50) NOT NULL COMMENT 'Code representing the unit of measurement (e.g., KG, PCS).',
  `UOM_NAME` varchar(100) NOT NULL COMMENT 'Name of the unit of measurement.',
  `UOM_DESCRIPTION` varchar(255) DEFAULT NULL COMMENT 'Description of the unit of measurement.',
  `STATUS` varchar(20) DEFAULT 'ACTIVE' COMMENT 'Indicates whether the UOM is active or inactive (e.g., ACTIVE, INACTIVE).',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the record was created.',
  `CREATED_BY` varchar(100) DEFAULT NULL COMMENT 'User or system that created the record.',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the record was last updated.',
  `UPDATED_BY` varchar(100) DEFAULT NULL COMMENT 'User or system that last updated the record.'
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
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of the last record update',
  `IS_2FA_ENABLED` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_users`
--

INSERT INTO `xx_crm_users` (`ID`, `USER_ID`, `UUID`, `USER_TYPE`, `FIRST_NAME`, `LAST_NAME`, `EMAIL`, `PASSWORD`, `PHONE_NUMBER`, `STATUS`, `CREATED_AT`, `UPDATED_AT`, `IS_2FA_ENABLED`) VALUES
(1, 'U-10001', 'c06a7626-5dc0-425e-8e63-582137615e14', 'admin', 'Sys', 'Admin', 'admin@crm.live', '$argon2id$v=19$m=65536,t=4,p=1$U0dBMzVHb3ZhbkVRc0F6SQ$Ai3f8Fo4PIearUmseB2EBQWAjn55wIaTsQS4gn0whAc', '8805629207', 'active', '2024-10-23 09:41:44', '2024-12-21 12:51:18', 0),
(4, 'CL-000004', '3d3b23ae-e436-4c82-a089-4de58f63a9e9', 'client', 'Ab Azim', 'Shaikh', 'user1@crm.live', '$argon2id$v=19$m=65536,t=4,p=1$U0dBMzVHb3ZhbkVRc0F6SQ$Ai3f8Fo4PIearUmseB2EBQWAjn55wIaTsQS4gn0whAc', '8805629207', 'active', '2024-11-06 07:15:08', '2024-12-20 05:43:25', NULL),
(6, 'CL-000006', '6663eff5-15cc-4f4f-a897-dd46d85c47f4', 'client', 'Noman', 'Mirza', 'nam@ols.com', '$argon2id$v=19$m=65536,t=4,p=1$VFFKUHMyc3lWWUpHaDJ2dw$YiUWKZWdNf9PSggnepebE4Pf0qjXhMV6RE5Ef8WgqCI', '7845215487', 'active', '2024-11-15 10:04:02', '2024-11-15 10:04:03', NULL),
(8, 'U-000008', '9941bafb-8492-4ebb-9018-b43d3a794c0b', 'client', 'Zoheb', 'Shaikh', 'zns@user.live', '$argon2id$v=19$m=65536,t=4,p=1$NU5QMWRzTDJaZzNkUVViTw$5B4X/HhX7bb9Yw7fMfyOgUX1btmK2YgIHDqghKHtMjQ', '2154', 'active', '2024-12-21 07:11:38', '2024-12-21 11:41:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `xx_crm_user_2fa_details`
--

CREATE TABLE `xx_crm_user_2fa_details` (
  `ID` int(11) NOT NULL COMMENT 'Unique identifier for the 2FA detail record.',
  `USER_ID` int(11) NOT NULL COMMENT 'References the user from the USERS table.',
  `TOTP_SECRET` varchar(500) NOT NULL COMMENT 'Encrypted shared secret for TOTP generation.',
  `BACKUP_CODES` text DEFAULT NULL COMMENT 'Encrypted backup codes for recovery, stored as JSON or comma-separated.',
  `IS_ACTIVE` tinyint(1) DEFAULT 1 COMMENT 'Indicates if the 2FA setup is currently active.',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp when the 2FA was created.',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp when the 2FA record was last updated.',
  `QR_DATA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `xx_crm_user_2fa_details`
--

INSERT INTO `xx_crm_user_2fa_details` (`ID`, `USER_ID`, `TOTP_SECRET`, `BACKUP_CODES`, `IS_ACTIVE`, `CREATED_AT`, `UPDATED_AT`, `QR_DATA`) VALUES
(1, 1, 'PNDHLKBVNNFRE45S', '1', 1, '2024-12-16 12:05:34', '2024-12-16 12:05:34', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADIAQMAAACXljzdAAAABlBMVEX///8AAABVwtN+AAAACXBIWXMAAA7EAAAOxAGVKw4bAAACgElEQVRYheWYPe7jIBDFx6Kg5AbhIlZ8rRSRQHLha9nyRfANKCmQZ9+Mvav995hi11K++EXKeD4ejxD9axczn1TMfhKFLZGdt6RLrUkmejnmFU/zSVy+b13qSUJ9Ec/7wfjgs5056dITxHGU30QO3BLvL/clZHnnbOPA+UNPEc11nJIrYSVXzJZ+VOF5Is3lLKqQUWjvlp+d2I7oVUIdMlc0lFT77yHqQJirx8MkR/T2+WNWBLin9qRMeDH4XeTak0TAxfQkFPbTle90cAlYXCrqHq8kNCV4GbJFIvKlVWhuXkS5upEssdlqmFUoSzhRiukBQoiAzPpCrqGJC3Ltyu/JakgoQHaJJmb6Dlg0GNY4UkeC9/JZhLEOUGZzemwG6QEyIdcRwl++5FkiILoj6ENQWshUEFVmnR+pts5PWwLl1wigSNK4mKR0K0UvwtEgNoP3BQXIAZLMT5BsMT/SWqJI8r0TieCeRJoLo2QYAYp3oTfGa301JygtREIwlAL3D7nItlJHwnEc2NYRwlgH3LpIM2tsbYnWl74yxjCCyIY0WleiFzSEpORe1B8+8er4psShjxyKjYRXKMUiabb3/tOH0AdVQGyY3nmV7lthi1dqTuBbRP3R1wSXJiVn6PMDBGIkigubjzvFrgdFYr3TXgRe27ONo2cyG8KKb+T6nqy2ZLwjsCzWGs38J4JeRO4cLvS2LQiQb7fRlMgFMVoFb0wgSZW4H9EzRpHTC8OKqh/VI1Vrogck7OhH1lyHWSPoSuR8akWHEaS/fG8ZHyLXBMMeXXJx7+g9CfMu+5w64lUM1QNEzsFw+Ifuc/mSya5E+w1Phyx6+lz/rlBz8r9cvwC10iH1rut84QAAAABJRU5ErkJggg==');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `xx_crm_access_roles`
--
ALTER TABLE `xx_crm_access_roles`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ROLE_NAME` (`ROLE_NAME`);

--
-- Indexes for table `xx_crm_act_call`
--
ALTER TABLE `xx_crm_act_call`
  ADD PRIMARY KEY (`CALL_ID`),
  ADD KEY `ACTIVITY_ID` (`ACTIVITY_ID`);

--
-- Indexes for table `xx_crm_act_email`
--
ALTER TABLE `xx_crm_act_email`
  ADD PRIMARY KEY (`EMAIL_ID`),
  ADD KEY `ACTIVITY_ID` (`ACTIVITY_ID`);

--
-- Indexes for table `xx_crm_act_event`
--
ALTER TABLE `xx_crm_act_event`
  ADD PRIMARY KEY (`EVENT_ID`),
  ADD KEY `ACTIVITY_ID` (`ACTIVITY_ID`);

--
-- Indexes for table `xx_crm_act_meeting`
--
ALTER TABLE `xx_crm_act_meeting`
  ADD PRIMARY KEY (`MEETING_ID`),
  ADD KEY `ACTIVITY_ID` (`ACTIVITY_ID`);

--
-- Indexes for table `xx_crm_act_note`
--
ALTER TABLE `xx_crm_act_note`
  ADD PRIMARY KEY (`NOTE_ID`),
  ADD KEY `ACTIVITY_ID` (`ACTIVITY_ID`);

--
-- Indexes for table `xx_crm_act_task`
--
ALTER TABLE `xx_crm_act_task`
  ADD PRIMARY KEY (`TASK_ID`),
  ADD KEY `ACTIVITY_ID` (`ACTIVITY_ID`);

--
-- Indexes for table `xx_crm_app_resources`
--
ALTER TABLE `xx_crm_app_resources`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UUID` (`UUID`);

--
-- Indexes for table `xx_crm_asn_header`
--
ALTER TABLE `xx_crm_asn_header`
  ADD PRIMARY KEY (`ASN_ID`);

--
-- Indexes for table `xx_crm_asn_lines`
--
ALTER TABLE `xx_crm_asn_lines`
  ADD PRIMARY KEY (`LINE_ID`),
  ADD KEY `ASN_ID` (`ASN_ID`);

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
-- Indexes for table `xx_crm_compl_header`
--
ALTER TABLE `xx_crm_compl_header`
  ADD PRIMARY KEY (`COMPLAINT_ID`);

--
-- Indexes for table `xx_crm_compl_lines`
--
ALTER TABLE `xx_crm_compl_lines`
  ADD PRIMARY KEY (`LINE_ID`),
  ADD KEY `COMPLAINT_ID` (`COMPLAINT_ID`);

--
-- Indexes for table `xx_crm_compl_resolution`
--
ALTER TABLE `xx_crm_compl_resolution`
  ADD PRIMARY KEY (`RESOLUTION_ID`),
  ADD KEY `COMPLAINT_ID` (`COMPLAINT_ID`);

--
-- Indexes for table `xx_crm_contacts`
--
ALTER TABLE `xx_crm_contacts`
  ADD PRIMARY KEY (`CONTACT_ID`);

--
-- Indexes for table `xx_crm_crapp_contact_info`
--
ALTER TABLE `xx_crm_crapp_contact_info`
  ADD PRIMARY KEY (`HEADER_ID`),
  ADD UNIQUE KEY `UK_UUID` (`UUID`);

--
-- Indexes for table `xx_crm_crapp_credit_info`
--
ALTER TABLE `xx_crm_crapp_credit_info`
  ADD PRIMARY KEY (`HEADER_ID`),
  ADD UNIQUE KEY `UK_UUID` (`UUID`);

--
-- Indexes for table `xx_crm_crapp_formal_info`
--
ALTER TABLE `xx_crm_crapp_formal_info`
  ADD PRIMARY KEY (`HEADER_ID`),
  ADD UNIQUE KEY `UK_UUID` (`UUID`);

--
-- Indexes for table `xx_crm_crapp_sign_info`
--
ALTER TABLE `xx_crm_crapp_sign_info`
  ADD PRIMARY KEY (`HEADER_ID`),
  ADD UNIQUE KEY `UK_UUID` (`UUID`);

--
-- Indexes for table `xx_crm_crapp_zpil_info`
--
ALTER TABLE `xx_crm_crapp_zpil_info`
  ADD PRIMARY KEY (`HEADER_ID`),
  ADD UNIQUE KEY `UK_UUID` (`UUID`);

--
-- Indexes for table `xx_crm_deals`
--
ALTER TABLE `xx_crm_deals`
  ADD PRIMARY KEY (`DEAL_ID`),
  ADD KEY `ASSOCIATED_CONTACT_ID` (`ASSOCIATED_CONTACT_ID`);

--
-- Indexes for table `xx_crm_email_config`
--
ALTER TABLE `xx_crm_email_config`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `xx_crm_leads`
--
ALTER TABLE `xx_crm_leads`
  ADD PRIMARY KEY (`LEAD_ID`);

--
-- Indexes for table `xx_crm_lead_activities`
--
ALTER TABLE `xx_crm_lead_activities`
  ADD PRIMARY KEY (`ACTIVITY_ID`),
  ADD KEY `LEAD_ID` (`LEAD_ID`);

--
-- Indexes for table `xx_crm_minutes_of_meeting`
--
ALTER TABLE `xx_crm_minutes_of_meeting`
  ADD PRIMARY KEY (`MOM_ID`);

--
-- Indexes for table `xx_crm_po_header`
--
ALTER TABLE `xx_crm_po_header`
  ADD PRIMARY KEY (`PO_ID`);

--
-- Indexes for table `xx_crm_po_lines`
--
ALTER TABLE `xx_crm_po_lines`
  ADD PRIMARY KEY (`LINE_ID`);

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
-- Indexes for table `xx_crm_projects`
--
ALTER TABLE `xx_crm_projects`
  ADD PRIMARY KEY (`PROJECT_ID`);

--
-- Indexes for table `xx_crm_quotations`
--
ALTER TABLE `xx_crm_quotations`
  ADD PRIMARY KEY (`QUOTE_ID`);

--
-- Indexes for table `xx_crm_quotation_lines`
--
ALTER TABLE `xx_crm_quotation_lines`
  ADD PRIMARY KEY (`LINE_ID`);

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
-- Indexes for table `xx_crm_survey`
--
ALTER TABLE `xx_crm_survey`
  ADD PRIMARY KEY (`SURVEY_ID`);

--
-- Indexes for table `xx_crm_survey_header`
--
ALTER TABLE `xx_crm_survey_header`
  ADD PRIMARY KEY (`HEADER_ID`);

--
-- Indexes for table `xx_crm_survey_lines`
--
ALTER TABLE `xx_crm_survey_lines`
  ADD PRIMARY KEY (`LINE_ID`);

--
-- Indexes for table `xx_crm_unit_of_measurement`
--
ALTER TABLE `xx_crm_unit_of_measurement`
  ADD PRIMARY KEY (`UOM_ID`),
  ADD KEY `BASE_UOM_ID` (`BASE_UOM_ID`);

--
-- Indexes for table `xx_crm_uom`
--
ALTER TABLE `xx_crm_uom`
  ADD PRIMARY KEY (`UOM_ID`);

--
-- Indexes for table `xx_crm_users`
--
ALTER TABLE `xx_crm_users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `xx_crm_user_2fa_details`
--
ALTER TABLE `xx_crm_user_2fa_details`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `xx_crm_access_roles`
--
ALTER TABLE `xx_crm_access_roles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each role';

--
-- AUTO_INCREMENT for table `xx_crm_act_call`
--
ALTER TABLE `xx_crm_act_call`
  MODIFY `CALL_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `xx_crm_act_email`
--
ALTER TABLE `xx_crm_act_email`
  MODIFY `EMAIL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xx_crm_act_event`
--
ALTER TABLE `xx_crm_act_event`
  MODIFY `EVENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `xx_crm_act_meeting`
--
ALTER TABLE `xx_crm_act_meeting`
  MODIFY `MEETING_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `xx_crm_act_note`
--
ALTER TABLE `xx_crm_act_note`
  MODIFY `NOTE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `xx_crm_act_task`
--
ALTER TABLE `xx_crm_act_task`
  MODIFY `TASK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `xx_crm_app_resources`
--
ALTER TABLE `xx_crm_app_resources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incremented unique identifier for the resource';

--
-- AUTO_INCREMENT for table `xx_crm_asn_header`
--
ALTER TABLE `xx_crm_asn_header`
  MODIFY `ASN_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing unique identifier for each ASN.';

--
-- AUTO_INCREMENT for table `xx_crm_asn_lines`
--
ALTER TABLE `xx_crm_asn_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing unique identifier for each line item.';

--
-- AUTO_INCREMENT for table `xx_crm_authtokens`
--
ALTER TABLE `xx_crm_authtokens`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, unique token identifier', AUTO_INCREMENT=158;

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
-- AUTO_INCREMENT for table `xx_crm_compl_header`
--
ALTER TABLE `xx_crm_compl_header`
  MODIFY `COMPLAINT_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing unique identifier for each complaint.', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `xx_crm_compl_lines`
--
ALTER TABLE `xx_crm_compl_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing unique identifier for each complaint line.', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `xx_crm_compl_resolution`
--
ALTER TABLE `xx_crm_compl_resolution`
  MODIFY `RESOLUTION_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing unique identifier for each resolution.', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `xx_crm_contacts`
--
ALTER TABLE `xx_crm_contacts`
  MODIFY `CONTACT_ID` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the contact (Primary Key)', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `xx_crm_crapp_contact_info`
--
ALTER TABLE `xx_crm_crapp_contact_info`
  MODIFY `HEADER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the contact info record', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_crapp_credit_info`
--
ALTER TABLE `xx_crm_crapp_credit_info`
  MODIFY `HEADER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the record', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_crapp_formal_info`
--
ALTER TABLE `xx_crm_crapp_formal_info`
  MODIFY `HEADER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the formal info record', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_crapp_sign_info`
--
ALTER TABLE `xx_crm_crapp_sign_info`
  MODIFY `HEADER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the sign info record', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_crapp_zpil_info`
--
ALTER TABLE `xx_crm_crapp_zpil_info`
  MODIFY `HEADER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the ZPIL info record', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_deals`
--
ALTER TABLE `xx_crm_deals`
  MODIFY `DEAL_ID` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the deal (Primary Key)', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `xx_crm_email_config`
--
ALTER TABLE `xx_crm_email_config`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each email configuration', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_leads`
--
ALTER TABLE `xx_crm_leads`
  MODIFY `LEAD_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the lead record', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `xx_crm_lead_activities`
--
ALTER TABLE `xx_crm_lead_activities`
  MODIFY `ACTIVITY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `xx_crm_minutes_of_meeting`
--
ALTER TABLE `xx_crm_minutes_of_meeting`
  MODIFY `MOM_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for each Minutes of Meeting record.', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `xx_crm_po_header`
--
ALTER TABLE `xx_crm_po_header`
  MODIFY `PO_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for the Purchase Order', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `xx_crm_po_lines`
--
ALTER TABLE `xx_crm_po_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for the Purchase Order Line', AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT for table `xx_crm_projects`
--
ALTER TABLE `xx_crm_projects`
  MODIFY `PROJECT_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the project', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `xx_crm_quotations`
--
ALTER TABLE `xx_crm_quotations`
  MODIFY `QUOTE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for the Quotation Header', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `xx_crm_quotation_lines`
--
ALTER TABLE `xx_crm_quotation_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for the Quotation Line', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `xx_crm_req_header`
--
ALTER TABLE `xx_crm_req_header`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incremented primary key for each request header', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `xx_crm_req_lines`
--
ALTER TABLE `xx_crm_req_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incremented primary key for each request line', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `xx_crm_survey`
--
ALTER TABLE `xx_crm_survey`
  MODIFY `SURVEY_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the survey record', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `xx_crm_survey_header`
--
ALTER TABLE `xx_crm_survey_header`
  MODIFY `HEADER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the survey response header', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_survey_lines`
--
ALTER TABLE `xx_crm_survey_lines`
  MODIFY `LINE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the survey line item', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `xx_crm_unit_of_measurement`
--
ALTER TABLE `xx_crm_unit_of_measurement`
  MODIFY `UOM_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the unit of measurement';

--
-- AUTO_INCREMENT for table `xx_crm_uom`
--
ALTER TABLE `xx_crm_uom`
  MODIFY `UOM_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-incrementing unique identifier for each UOM.';

--
-- AUTO_INCREMENT for table `xx_crm_users`
--
ALTER TABLE `xx_crm_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key, unique user identifier', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `xx_crm_user_2fa_details`
--
ALTER TABLE `xx_crm_user_2fa_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the 2FA detail record.', AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `xx_crm_act_call`
--
ALTER TABLE `xx_crm_act_call`
  ADD CONSTRAINT `xx_crm_act_call_ibfk_1` FOREIGN KEY (`ACTIVITY_ID`) REFERENCES `xx_crm_lead_activities` (`ACTIVITY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `xx_crm_act_email`
--
ALTER TABLE `xx_crm_act_email`
  ADD CONSTRAINT `xx_crm_act_email_ibfk_1` FOREIGN KEY (`ACTIVITY_ID`) REFERENCES `xx_crm_lead_activities` (`ACTIVITY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `xx_crm_act_event`
--
ALTER TABLE `xx_crm_act_event`
  ADD CONSTRAINT `xx_crm_act_event_ibfk_1` FOREIGN KEY (`ACTIVITY_ID`) REFERENCES `xx_crm_lead_activities` (`ACTIVITY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `xx_crm_act_meeting`
--
ALTER TABLE `xx_crm_act_meeting`
  ADD CONSTRAINT `xx_crm_act_meeting_ibfk_1` FOREIGN KEY (`ACTIVITY_ID`) REFERENCES `xx_crm_lead_activities` (`ACTIVITY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `xx_crm_act_note`
--
ALTER TABLE `xx_crm_act_note`
  ADD CONSTRAINT `xx_crm_act_note_ibfk_1` FOREIGN KEY (`ACTIVITY_ID`) REFERENCES `xx_crm_lead_activities` (`ACTIVITY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `xx_crm_act_task`
--
ALTER TABLE `xx_crm_act_task`
  ADD CONSTRAINT `xx_crm_act_task_ibfk_1` FOREIGN KEY (`ACTIVITY_ID`) REFERENCES `xx_crm_lead_activities` (`ACTIVITY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `xx_crm_asn_lines`
--
ALTER TABLE `xx_crm_asn_lines`
  ADD CONSTRAINT `xx_crm_asn_lines_ibfk_1` FOREIGN KEY (`ASN_ID`) REFERENCES `xx_crm_asn_header` (`ASN_ID`) ON DELETE CASCADE;

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
-- Constraints for table `xx_crm_compl_lines`
--
ALTER TABLE `xx_crm_compl_lines`
  ADD CONSTRAINT `xx_crm_compl_lines_ibfk_1` FOREIGN KEY (`COMPLAINT_ID`) REFERENCES `xx_crm_compl_header` (`COMPLAINT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `xx_crm_compl_resolution`
--
ALTER TABLE `xx_crm_compl_resolution`
  ADD CONSTRAINT `xx_crm_compl_resolution_ibfk_1` FOREIGN KEY (`COMPLAINT_ID`) REFERENCES `xx_crm_compl_header` (`COMPLAINT_ID`) ON DELETE CASCADE;

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
