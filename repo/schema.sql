-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 15, 2015 at 08:43 PM
-- Server version: 5.6.27
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `alpha_mashdrop`
--

-- --------------------------------------------------------

--
-- Table structure for table `audience`
--

CREATE TABLE `audience` (
  `id` int(11) UNSIGNED NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `gender` enum('all','male','female') NOT NULL DEFAULT 'all',
  `age_min` int(11) UNSIGNED DEFAULT NULL,
  `age_max` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `audience_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `file_id` int(11) UNSIGNED NOT NULL,
  `transaction_id` int(11) UNSIGNED NOT NULL,
  `type` enum('pay-per-click','carousel-space') NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `budget` float(11,2) UNSIGNED DEFAULT NULL,
  `cost_percentage` float(11,2) UNSIGNED DEFAULT NULL,
  `max_clicks` int(11) UNSIGNED DEFAULT NULL,
  `click_cost` float(11,2) UNSIGNED DEFAULT NULL,
  `status` enum('live','done','expired','cancelled') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_post`
--

CREATE TABLE `campaign_post` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `campaign_id` int(11) UNSIGNED NOT NULL,
  `target` varchar(255) DEFAULT NULL,
  `hash` varchar(255) NOT NULL COMMENT 'unique id for campaign and user',
  `ref` varchar(255) DEFAULT NULL COMMENT 'post additional info',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_projection`
--

CREATE TABLE `campaign_projection` (
  `id` int(11) UNSIGNED NOT NULL,
  `campaign_id` int(11) UNSIGNED NOT NULL,
  `photo_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_visit`
--

CREATE TABLE `campaign_visit` (
  `id` int(11) UNSIGNED NOT NULL,
  `campaign_post_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(255) NOT NULL,
  `host` varchar(255) DEFAULT NULL,
  `cookie` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `browser_name` varchar(255) DEFAULT NULL,
  `browser_version` varchar(255) DEFAULT NULL,
  `browser_maker` varchar(255) DEFAULT NULL,
  `browser_comment` varchar(255) DEFAULT NULL,
  `browser_name_pattern` varchar(255) DEFAULT NULL,
  `device_platform` varchar(255) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `device_pointing_method` varchar(255) DEFAULT NULL,
  `is_mobile` tinyint(1) DEFAULT NULL,
  `is_tablet` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Consumer Products', NULL, NULL, NULL),
(2, 'Consumer Services', NULL, NULL, NULL),
(3, 'Charities', NULL, NULL, NULL),
(4, 'Political Campaigns', NULL, NULL, NULL),
(5, 'Artist Promotions', NULL, NULL, NULL),
(6, 'Crowdfunding', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Andorra', NULL, NULL, NULL),
(2, 'United Arab Emirates', NULL, NULL, NULL),
(3, 'Afghanistan', NULL, NULL, NULL),
(4, 'Antigua and Barbuda', NULL, NULL, NULL),
(5, 'Anguilla', NULL, NULL, NULL),
(6, 'Albania', NULL, NULL, NULL),
(7, 'Armenia', NULL, NULL, NULL),
(8, 'Angola', NULL, NULL, NULL),
(9, 'Antarctica', NULL, NULL, NULL),
(10, 'Argentina', NULL, NULL, NULL),
(11, 'American Samoa', NULL, NULL, NULL),
(12, 'Austria', NULL, NULL, NULL),
(13, 'Australia', NULL, NULL, NULL),
(14, 'Aruba', NULL, NULL, NULL),
(15, 'Aland Islands', NULL, NULL, NULL),
(16, 'Azerbaijan', NULL, NULL, NULL),
(17, 'Bosnia and Herzegovina', NULL, NULL, NULL),
(18, 'Barbados', NULL, NULL, NULL),
(19, 'Bangladesh', NULL, NULL, NULL),
(20, 'Belgium', NULL, NULL, NULL),
(21, 'Burkina Faso', NULL, NULL, NULL),
(22, 'Bulgaria', NULL, NULL, NULL),
(23, 'Bahrain', NULL, NULL, NULL),
(24, 'Burundi', NULL, NULL, NULL),
(25, 'Benin', NULL, NULL, NULL),
(26, 'Saint Barthelemy', NULL, NULL, NULL),
(27, 'Bermuda', NULL, NULL, NULL),
(28, 'Brunei Darussalam', NULL, NULL, NULL),
(29, 'Bolivia', NULL, NULL, NULL),
(30, 'Caribbean Netherlands ', NULL, NULL, NULL),
(31, 'Brazil', NULL, NULL, NULL),
(32, 'Bahamas', NULL, NULL, NULL),
(33, 'Bhutan', NULL, NULL, NULL),
(34, 'Bouvet Island', NULL, NULL, NULL),
(35, 'Botswana', NULL, NULL, NULL),
(36, 'Belarus', NULL, NULL, NULL),
(37, 'Belize', NULL, NULL, NULL),
(38, 'Canada', NULL, NULL, NULL),
(39, 'Cocos (Keeling) Islands', NULL, NULL, NULL),
(40, 'Congo, Democratic Republic of', NULL, NULL, NULL),
(41, 'Central African Republic', NULL, NULL, NULL),
(42, 'Congo', NULL, NULL, NULL),
(43, 'Switzerland', NULL, NULL, NULL),
(44, 'Cote d Ivoire', NULL, NULL, NULL),
(45, 'Cook Islands', NULL, NULL, NULL),
(46, 'Chile', NULL, NULL, NULL),
(47, 'Cameroon', NULL, NULL, NULL),
(48, 'China', NULL, NULL, NULL),
(49, 'Colombia', NULL, NULL, NULL),
(50, 'Costa Rica', NULL, NULL, NULL),
(51, 'Cuba', NULL, NULL, NULL),
(52, 'Cape Verde', NULL, NULL, NULL),
(53, 'Curacao', NULL, NULL, NULL),
(54, 'Christmas Island', NULL, NULL, NULL),
(55, 'Cyprus', NULL, NULL, NULL),
(56, 'Czech Republic', NULL, NULL, NULL),
(57, 'Germany', NULL, NULL, NULL),
(58, 'Djibouti', NULL, NULL, NULL),
(59, 'Denmark', NULL, NULL, NULL),
(60, 'Dominica', NULL, NULL, NULL),
(61, 'Dominican Republic', NULL, NULL, NULL),
(62, 'Algeria', NULL, NULL, NULL),
(63, 'Ecuador', NULL, NULL, NULL),
(64, 'Estonia', NULL, NULL, NULL),
(65, 'Egypt', NULL, NULL, NULL),
(66, 'Western Sahara', NULL, NULL, NULL),
(67, 'Eritrea', NULL, NULL, NULL),
(68, 'Spain', NULL, NULL, NULL),
(69, 'Ethiopia', NULL, NULL, NULL),
(70, 'Finland', NULL, NULL, NULL),
(71, 'Fiji', NULL, NULL, NULL),
(72, 'Falkland Islands', NULL, NULL, NULL),
(73, 'Micronesia, Federated States of', NULL, NULL, NULL),
(74, 'Faroe Islands', NULL, NULL, NULL),
(75, 'France', NULL, NULL, NULL),
(76, 'Gabon', NULL, NULL, NULL),
(77, 'United Kingdom', NULL, NULL, NULL),
(78, 'Grenada', NULL, NULL, NULL),
(79, 'Georgia', NULL, NULL, NULL),
(80, 'French Guiana', NULL, NULL, NULL),
(81, 'Guernsey', NULL, NULL, NULL),
(82, 'Ghana', NULL, NULL, NULL),
(83, 'Gibraltar', NULL, NULL, NULL),
(84, 'Greenland', NULL, NULL, NULL),
(85, 'Gambia', NULL, NULL, NULL),
(86, 'Guinea', NULL, NULL, NULL),
(87, 'Guadeloupe', NULL, NULL, NULL),
(88, 'Equatorial Guinea', NULL, NULL, NULL),
(89, 'Greece', NULL, NULL, NULL),
(90, 'South Georgia and the South Sandwich Islands', NULL, NULL, NULL),
(91, 'Guatemala', NULL, NULL, NULL),
(92, 'Guam', NULL, NULL, NULL),
(93, 'Guinea-Bissau', NULL, NULL, NULL),
(94, 'Guyana', NULL, NULL, NULL),
(95, 'Hong Kong', NULL, NULL, NULL),
(96, 'Heard and McDonald Islands', NULL, NULL, NULL),
(97, 'Honduras', NULL, NULL, NULL),
(98, 'Croatia', NULL, NULL, NULL),
(99, 'Haiti', NULL, NULL, NULL),
(100, 'Hungary', NULL, NULL, NULL),
(101, 'Indonesia', NULL, NULL, NULL),
(102, 'Ireland', NULL, NULL, NULL),
(103, 'Israel', NULL, NULL, NULL),
(104, 'Isle of Man', NULL, NULL, NULL),
(105, 'India', NULL, NULL, NULL),
(106, 'British Indian Ocean Territory', NULL, NULL, NULL),
(107, 'Iraq', NULL, NULL, NULL),
(108, 'Iran', NULL, NULL, NULL),
(109, 'Iceland', NULL, NULL, NULL),
(110, 'Italy', NULL, NULL, NULL),
(111, 'Jersey', NULL, NULL, NULL),
(112, 'Jamaica', NULL, NULL, NULL),
(113, 'Jordan', NULL, NULL, NULL),
(114, 'Japan', NULL, NULL, NULL),
(115, 'Kenya', NULL, NULL, NULL),
(116, 'Kyrgyzstan', NULL, NULL, NULL),
(117, 'Cambodia', NULL, NULL, NULL),
(118, 'Kiribati', NULL, NULL, NULL),
(119, 'Comoros', NULL, NULL, NULL),
(120, 'Saint Kitts and Nevis', NULL, NULL, NULL),
(121, 'North Korea', NULL, NULL, NULL),
(122, 'South Korea', NULL, NULL, NULL),
(123, 'Kuwait', NULL, NULL, NULL),
(124, 'Cayman Islands', NULL, NULL, NULL),
(125, 'Kazakhstan', NULL, NULL, NULL),
(126, 'Lao Peoples Democratic Republic', NULL, NULL, NULL),
(127, 'Lebanon', NULL, NULL, NULL),
(128, 'Saint Lucia', NULL, NULL, NULL),
(129, 'Liechtenstein', NULL, NULL, NULL),
(130, 'Sri Lanka', NULL, NULL, NULL),
(131, 'Liberia', NULL, NULL, NULL),
(132, 'Lesotho', NULL, NULL, NULL),
(133, 'Lithuania', NULL, NULL, NULL),
(134, 'Luxembourg', NULL, NULL, NULL),
(135, 'Latvia', NULL, NULL, NULL),
(136, 'Libya', NULL, NULL, NULL),
(137, 'Morocco', NULL, NULL, NULL),
(138, 'Monaco', NULL, NULL, NULL),
(139, 'Moldova', NULL, NULL, NULL),
(140, 'Montenegro', NULL, NULL, NULL),
(141, 'Saint-Martin (France)', NULL, NULL, NULL),
(142, 'Madagascar', NULL, NULL, NULL),
(143, 'Marshall Islands', NULL, NULL, NULL),
(144, 'Macedonia', NULL, NULL, NULL),
(145, 'Mali', NULL, NULL, NULL),
(146, 'Myanmar', NULL, NULL, NULL),
(147, 'Mongolia', NULL, NULL, NULL),
(148, 'Macau', NULL, NULL, NULL),
(149, 'Northern Mariana Islands', NULL, NULL, NULL),
(150, 'Martinique', NULL, NULL, NULL),
(151, 'Mauritania', NULL, NULL, NULL),
(152, 'Montserrat', NULL, NULL, NULL),
(153, 'Malta', NULL, NULL, NULL),
(154, 'Mauritius', NULL, NULL, NULL),
(155, 'Maldives', NULL, NULL, NULL),
(156, 'Malawi', NULL, NULL, NULL),
(157, 'Mexico', NULL, NULL, NULL),
(158, 'Malaysia', NULL, NULL, NULL),
(159, 'Mozambique', NULL, NULL, NULL),
(160, 'Namibia', NULL, NULL, NULL),
(161, 'New Caledonia', NULL, NULL, NULL),
(162, 'Niger', NULL, NULL, NULL),
(163, 'Norfolk Island', NULL, NULL, NULL),
(164, 'Nigeria', NULL, NULL, NULL),
(165, 'Nicaragua', NULL, NULL, NULL),
(166, 'The Netherlands', NULL, NULL, NULL),
(167, 'Norway', NULL, NULL, NULL),
(168, 'Nepal', NULL, NULL, NULL),
(169, 'Nauru', NULL, NULL, NULL),
(170, 'Niue', NULL, NULL, NULL),
(171, 'New Zealand', NULL, NULL, NULL),
(172, 'Oman', NULL, NULL, NULL),
(173, 'Panama', NULL, NULL, NULL),
(174, 'Peru', NULL, NULL, NULL),
(175, 'French Polynesia', NULL, NULL, NULL),
(176, 'Papua New Guinea', NULL, NULL, NULL),
(177, 'Philippines', NULL, NULL, NULL),
(178, 'Pakistan', NULL, NULL, NULL),
(179, 'Poland', NULL, NULL, NULL),
(180, 'St. Pierre and Miquelon', NULL, NULL, NULL),
(181, 'Pitcairn', NULL, NULL, NULL),
(182, 'Puerto Rico', NULL, NULL, NULL),
(183, 'Palestine, State of', NULL, NULL, NULL),
(184, 'Portugal', NULL, NULL, NULL),
(185, 'Palau', NULL, NULL, NULL),
(186, 'Paraguay', NULL, NULL, NULL),
(187, 'Qatar', NULL, NULL, NULL),
(188, 'Reunion', NULL, NULL, NULL),
(189, 'Romania', NULL, NULL, NULL),
(190, 'Serbia', NULL, NULL, NULL),
(191, 'Russian Federation', NULL, NULL, NULL),
(192, 'Rwanda', NULL, NULL, NULL),
(193, 'Saudi Arabia', NULL, NULL, NULL),
(194, 'Solomon Islands', NULL, NULL, NULL),
(195, 'Seychelles', NULL, NULL, NULL),
(196, 'Sudan', NULL, NULL, NULL),
(197, 'Sweden', NULL, NULL, NULL),
(198, 'Singapore', NULL, NULL, NULL),
(199, 'Saint Helena', NULL, NULL, NULL),
(200, 'Slovenia', NULL, NULL, NULL),
(201, 'Svalbard and Jan Mayen Islands', NULL, NULL, NULL),
(202, 'Slovakia', NULL, NULL, NULL),
(203, 'Sierra Leone', NULL, NULL, NULL),
(204, 'San Marino', NULL, NULL, NULL),
(205, 'Senegal', NULL, NULL, NULL),
(206, 'Somalia', NULL, NULL, NULL),
(207, 'Suriname', NULL, NULL, NULL),
(208, 'South Sudan', NULL, NULL, NULL),
(209, 'Sao Tome and Principe', NULL, NULL, NULL),
(210, 'El Salvador', NULL, NULL, NULL),
(211, 'Sint Maarten (Dutch part)', NULL, NULL, NULL),
(212, 'Syria', NULL, NULL, NULL),
(213, 'Swaziland', NULL, NULL, NULL),
(214, 'Turks and Caicos Islands', NULL, NULL, NULL),
(215, 'Chad', NULL, NULL, NULL),
(216, 'French Southern Territories', NULL, NULL, NULL),
(217, 'Togo', NULL, NULL, NULL),
(218, 'Thailand', NULL, NULL, NULL),
(219, 'Tajikistan', NULL, NULL, NULL),
(220, 'Tokelau', NULL, NULL, NULL),
(221, 'Timor-Leste', NULL, NULL, NULL),
(222, 'Turkmenistan', NULL, NULL, NULL),
(223, 'Tunisia', NULL, NULL, NULL),
(224, 'Tonga', NULL, NULL, NULL),
(225, 'Turkey', NULL, NULL, NULL),
(226, 'Trinidad and Tobago', NULL, NULL, NULL),
(227, 'Tuvalu', NULL, NULL, NULL),
(228, 'Taiwan', NULL, NULL, NULL),
(229, 'Tanzania', NULL, NULL, NULL),
(230, 'Ukraine', NULL, NULL, NULL),
(231, 'Uganda', NULL, NULL, NULL),
(232, 'United States Minor Outlying Islands', NULL, NULL, NULL),
(233, 'United States', NULL, NULL, NULL),
(234, 'Uruguay', NULL, NULL, NULL),
(235, 'Uzbekistan', NULL, NULL, NULL),
(236, 'Vatican', NULL, NULL, NULL),
(237, 'Saint Vincent and the Grenadines', NULL, NULL, NULL),
(238, 'Venezuela', NULL, NULL, NULL),
(239, 'Virgin Islands (British)', NULL, NULL, NULL),
(240, 'Virgin Islands (U.S.)', NULL, NULL, NULL),
(241, 'Vietnam', NULL, NULL, NULL),
(242, 'Vanuatu', NULL, NULL, NULL),
(243, 'Wallis and Futuna Islands', NULL, NULL, NULL),
(244, 'Samoa', NULL, NULL, NULL),
(245, 'Yemen', NULL, NULL, NULL),
(246, 'Mayotte', NULL, NULL, NULL),
(247, 'South Africa', NULL, NULL, NULL),
(248, 'Zambia', NULL, NULL, NULL),
(249, 'Zimbabwe', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `size` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL COMMENT 'type of log separated by underscore',
  `name` varchar(255) NOT NULL COMMENT 'action code separated by underscore',
  `description` longtext,
  `raw_request` longtext,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'permission name',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'last updated date',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'deleted date'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'user_view', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 'user_create', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 'user_update', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 'user_remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_id` int(11) UNSIGNED NOT NULL,
  `primary` tinyint(1) NOT NULL DEFAULT '0',
  `caption` varchar(255) DEFAULT NULL,
  `position` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'name of the role',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'last updated date',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'deleted date'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(0, 'Client', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 'Administrator', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
(2, 'Influencer', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);
(3, 'Advertiser', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);


-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL COMMENT 'foreign key of role table',
  `permission_id` int(11) UNSIGNED NOT NULL COMMENT 'foreign key of permission table',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'last updated date',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'deleted date'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `key`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'payout_threshold', '1.00', NULL, '2015-12-15 04:46:27', NULL),
(2, 'min_click_cost', '0.20', NULL, '2015-12-15 04:46:27', NULL),
(3, 'min_clicks', '40', NULL, '2015-12-15 04:46:27', NULL),
(4, 'cost_percentage', '50', NULL, '2015-12-15 04:46:27', NULL),
(5, 'flat_fee', '5.00', NULL, '2015-12-15 04:46:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` enum('charge','refund','payout') NOT NULL,
  `provider` varchar(255) NOT NULL COMMENT 'service provider',
  `ref` varchar(255) NOT NULL COMMENT 'provider reference transaction code',
  `amount` float(11,2) UNSIGNED NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `status` enum('active','inactive','disabled') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role_id`, `email`, `password`, `first_name`, `last_name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1000, 1, 'admin@mashdrop.com', '11f6ad8ec52a2984abaafd7c3b516503785c2072', 'admin', 'super', 'active', NULL, NULL, NULL);

ALTER TABLE `user` ADD `phone_number` VARCHAR( 20 ) NOT NULL AFTER `email` ;

-- --------------------------------------------------------

--
-- Table structure for table `user_facebook`
--

CREATE TABLE `user_facebook` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `uid` varchar(255) NOT NULL COMMENT 'facebook user id',
  `link` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `timezone` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_photo`
--

CREATE TABLE `user_photo` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `photo_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_setting`
--

CREATE TABLE `user_setting` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `setting_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_setting`
--

INSERT INTO `user_setting` (`id`, `user_id`, `setting_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1000, 1, NULL, NULL, NULL),
(2, 1000, 2, NULL, NULL, NULL),
(3, 1000, 3, NULL, NULL, NULL),
(4, 1000, 4, NULL, NULL, NULL),
(5, 1000, 5, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audience`
--
ALTER TABLE `audience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaign_user_id` (`user_id`),
  ADD KEY `idx_campaign_audience_id` (`audience_id`),
  ADD KEY `idx_campaign_category_id` (`category_id`),
  ADD KEY `idx_campaign_file_id` (`file_id`),
  ADD KEY `idx_campaign_transaction_id` (`transaction_id`),
  ADD KEY `idx_campaign_type` (`type`),
  ADD KEY `idx_campaign_title` (`title`),
  ADD KEY `idx_campaign_link` (`link`),
  ADD KEY `idx_campaign_status` (`status`);

--
-- Indexes for table `campaign_post`
--
ALTER TABLE `campaign_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaign_post_user_id` (`user_id`),
  ADD KEY `idx_campaign_post_campaign_id` (`campaign_id`),
  ADD KEY `idx_campaign_post_hash` (`hash`);

--
-- Indexes for table `campaign_projection`
--
ALTER TABLE `campaign_projection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaign_projection_campaign_id` (`campaign_id`),
  ADD KEY `idx_campaign_projection_photo_id` (`photo_id`);

--
-- Indexes for table `campaign_visit`
--
ALTER TABLE `campaign_visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaign_visit_campaign_post_id` (`campaign_post_id`),
  ADD KEY `idx_campaign_visit_ip` (`ip`),
  ADD KEY `idx_campaign_visit_cookie` (`cookie`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_file_uuid` (`uuid`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_user_id` (`user_id`),
  ADD KEY `idx_log_type` (`type`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_photo_file_id` (`file_id`),
  ADD KEY `idx_photo_position` (`position`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_role_permission_role_id` (`role_id`),
  ADD KEY `idx_role_permission_permission_id` (`permission_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction_user_id` (`user_id`),
  ADD KEY `idx_transaction_type` (`type`),
  ADD KEY `idx_transaction_provider` (`provider`),
  ADD KEY `idx_transaction_ref` (`ref`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_role_id` (`role_id`),
  ADD KEY `idx_user_first_name` (`first_name`),
  ADD KEY `idx_user_last_name` (`last_name`),
  ADD KEY `idx_user_status` (`status`);

--
-- Indexes for table `user_facebook`
--
ALTER TABLE `user_facebook`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_facebook_user_id` (`user_id`),
  ADD KEY `idx_user_facebook_uid` (`uid`);

--
-- Indexes for table `user_photo`
--
ALTER TABLE `user_photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_photo_user_id` (`user_id`),
  ADD KEY `idx_user_photo_photo_id` (`photo_id`);

--
-- Indexes for table `user_setting`
--
ALTER TABLE `user_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_setting_user_id` (`user_id`),
  ADD KEY `idx_user_setting_setting_id` (`setting_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audience`
--
ALTER TABLE `audience`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaign_post`
--
ALTER TABLE `campaign_post`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaign_projection`
--
ALTER TABLE `campaign_projection`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaign_visit`
--
ALTER TABLE `campaign_visit`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000003;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000008;
--
-- AUTO_INCREMENT for table `user_facebook`
--
ALTER TABLE `user_facebook`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_photo`
--
ALTER TABLE `user_photo`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_setting`
--
ALTER TABLE `user_setting`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000003;

ALTER TABLE `campaign_visit` ADD `is_free` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'indicates a free click, it will use as a bases if the visit is paid or not. this happens when the campaign has reach the maximum clicks limit' AFTER `is_tablet`;

ALTER TABLE `campaign` CHANGE `status` `status` ENUM('live','done','expired','cancelled','pending') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

INSERT INTO `permission` (`name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('campaign_view', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
('campaign_create', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
('campaign_update', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
('campaign_remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),

('transaction_view', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
('transaction_create', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
('transaction_update', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
('transaction_remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);


INSERT INTO `role_permission` (`role_id`, `permission_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 7, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 8, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),

(1, 9, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 11, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(1, 12, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);


--
-- Table structure for table `influencer_pages_profiles`
--

CREATE TABLE IF NOT EXISTS `influencer_pages_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `link` varchar(200) NOT NULL,
  `link_type` enum('page','profile') NOT NULL,
  `user_base_count` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `followers_location` varchar(255) NOT NULL,
  `social_media_account` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `social_media_category`
--

CREATE TABLE IF NOT EXISTS `social_media_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `social_media_category`
--

INSERT INTO `social_media_category` (`id`, `name`, `created_at`, `deleted_at`) VALUES
(1, 'sports', '2016-07-27 06:36:46', NULL),
(2, 'lifestyle', '2016-07-27 06:36:46', NULL),
(3, 'food', '2016-07-27 06:37:23', NULL),
(4, 'electronics', '2016-07-27 06:37:23', NULL),
(5, 'toys', '2016-07-27 06:37:35', NULL),
(6, 'travel', '2016-07-27 06:37:35', NULL);


--
--  Payment & Rates page tables  
-- 

--
-- Table structure for table `paypal_email`
--

CREATE TABLE IF NOT EXISTS `paypal_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `charities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `donate_to_charity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `charity_id` int(11) NOT NULL,
  `donate_to_charity` tinyint(1) NOT NULL DEFAULT '0',
  `percentage_amount` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `what_can_you_offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `what_will_id_do` text NOT NULL,
  `platform` text NOT NULL,
  `cash` decimal(7,2) NOT NULL,
  `free_sample_of_product` tinyint(1) NOT NULL DEFAULT '0',
  `at_your_ppc_offer` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



