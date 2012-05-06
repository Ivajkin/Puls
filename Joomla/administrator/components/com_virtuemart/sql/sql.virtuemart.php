<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id:sql.virtuemart.php 2011-05-20 18:22:00Z zanardi $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2011 The VirtueMart Team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
@ini_set( "max_execution_time", "120" );

# 08.11.2006 Allowing new user groups
$db->query( "CREATE TABLE `#__{vm}_auth_group` (
	  `group_id` int(11) NOT NULL auto_increment,
	  `group_name` varchar(128) default NULL,
	  `group_level` int(11) default NULL,
	  PRIMARY KEY  (`group_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=5 COMMENT='Holds all the user groups' ;");

# these are the default user groups
$db->query( "INSERT INTO `#__{vm}_auth_group` (`group_id`, `group_name`, `group_level`) VALUES (1, 'admin', 0),(2, 'storeadmin', 250),(3, 'shopper', 500),(4, 'demo', 750);" );
		
$db->query( "CREATE TABLE `#__{vm}_auth_user_group` (
	  `user_id` int(11) NOT NULL default '0',
	  `group_id` int(11) default NULL,
	  PRIMARY KEY  (`user_id`)
	) ENGINE=MyISAM COMMENT='Maps the user to user groups';");

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_auth_user_vendor`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_auth_user_vendor` (
  `user_id` int(11) default NULL,
  `vendor_id` int(11) default NULL,
  KEY `idx_auth_user_vendor_user_id` (`user_id`),
  KEY `idx_auth_user_vendor_vendor_id` (`vendor_id`)
) ENGINE=MyISAM COMMENT='Maps a user to a vendor'; ");

## 
## Dumping data for table `#__{vm}_auth_user_vendor`
## 


## --------------------------------------------------------
## 12.04.2007: Cart Storage for registered users
$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_cart` (
`user_id` INT( 11 ) NOT NULL ,
`cart_content` TEXT NOT NULL ,
`last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY ( `user_id` )
) TYPE = MYISAM COMMENT = 'Stores the cart contents of a user'" );
	
## 
## Table structure for table `#__{vm}_category`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) NOT NULL default '0',
  `category_name` varchar(128) NOT NULL default '',
  `category_description` text,
  `category_thumb_image` varchar(255) default NULL,
  `category_full_image` varchar(255) default NULL,
  `category_publish` char(1) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `category_browsepage` varchar(255) NOT NULL default 'browse_1',
  `products_per_row` tinyint(2) NOT NULL default '1',
  `category_flypage` varchar(255) default NULL,
  `list_order` int(11) default NULL,
  PRIMARY KEY  (`category_id`),
  KEY `idx_category_vendor_id` (`vendor_id`),
  KEY `idx_category_name` (`category_name`)
) ENGINE=MyISAM COMMENT='Product Categories are stored here'; ");

## 
## Dumping data for table `#__{vm}_category`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_category_xref`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_category_xref` (
  `category_parent_id` int(11) NOT NULL default '0',
  `category_child_id` int(11) NOT NULL default '0',
  `category_list` int(11) default NULL,
  PRIMARY KEY (`category_child_id`),
  KEY `category_xref_category_parent_id` (`category_parent_id`),
  KEY `idx_category_xref_category_list` (`category_list`)
) ENGINE=MyISAM COMMENT='Category child-parent relation list'; ");

## 
## Dumping data for table `#__{vm}_category_xref`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_country`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_country` (
  `country_id` int(11) NOT NULL auto_increment,
  `zone_id` int(11) NOT NULL default '1',
  `country_name` varchar(64) default NULL,
  `country_3_code` char(3) default NULL,
  `country_2_code` char(2) default NULL,
  PRIMARY KEY  (`country_id`),
  KEY `idx_country_name` (`country_name`)
) ENGINE=MyISAM COMMENT='Country records'; ");

## 
## Dumping data for table `#__{vm}_country`
## 

$db->query( "INSERT INTO `#__{vm}_country` VALUES (1, 1, 'Afghanistan', 'AFG', 'AF'),
(2, 1, 'Albania', 'ALB', 'AL'),
(3, 1, 'Algeria', 'DZA', 'DZ'),
(4, 1, 'American Samoa', 'ASM', 'AS'),
(5, 1, 'Andorra', 'AND', 'AD'),
(6, 1, 'Angola', 'AGO', 'AO'),
(7, 1, 'Anguilla', 'AIA', 'AI'),
(8, 1, 'Antarctica', 'ATA', 'AQ'),
(9, 1, 'Antigua and Barbuda', 'ATG', 'AG'),
(10, 1, 'Argentina', 'ARG', 'AR'),
(11, 1, 'Armenia', 'ARM', 'AM'),
(12, 1, 'Aruba', 'ABW', 'AW'),
(13, 1, 'Australia', 'AUS', 'AU'),
(14, 1, 'Austria', 'AUT', 'AT'),
(15, 1, 'Azerbaijan', 'AZE', 'AZ'),
(16, 1, 'Bahamas', 'BHS', 'BS'),
(17, 1, 'Bahrain', 'BHR', 'BH'),
(18, 1, 'Bangladesh', 'BGD', 'BD'),
(19, 1, 'Barbados', 'BRB', 'BB'),
(20, 1, 'Belarus', 'BLR', 'BY'),
(21, 1, 'Belgium', 'BEL', 'BE'),
(22, 1, 'Belize', 'BLZ', 'BZ'),
(23, 1, 'Benin', 'BEN', 'BJ'),
(24, 1, 'Bermuda', 'BMU', 'BM'),
(25, 1, 'Bhutan', 'BTN', 'BT'),
(26, 1, 'Bolivia', 'BOL', 'BO'),
(27, 1, 'Bosnia and Herzegowina', 'BIH', 'BA'),
(28, 1, 'Botswana', 'BWA', 'BW'),
(29, 1, 'Bouvet Island', 'BVT', 'BV'),
(30, 1, 'Brazil', 'BRA', 'BR'),
(31, 1, 'British Indian Ocean Territory', 'IOT', 'IO'),
(32, 1, 'Brunei Darussalam', 'BRN', 'BN'),
(33, 1, 'Bulgaria', 'BGR', 'BG'),
(34, 1, 'Burkina Faso', 'BFA', 'BF'),
(35, 1, 'Burundi', 'BDI', 'BI'),
(36, 1, 'Cambodia', 'KHM', 'KH'),
(37, 1, 'Cameroon', 'CMR', 'CM'),
(38, 1, 'Canada', 'CAN', 'CA'),
(39, 1, 'Cape Verde', 'CPV', 'CV'),
(40, 1, 'Cayman Islands', 'CYM', 'KY'),
(41, 1, 'Central African Republic', 'CAF', 'CF'),
(42, 1, 'Chad', 'TCD', 'TD'),
(43, 1, 'Chile', 'CHL', 'CL'),
(44, 1, 'China', 'CHN', 'CN'),
(45, 1, 'Christmas Island', 'CXR', 'CX'),
(46, 1, 'Cocos (Keeling) Islands', 'CCK', 'CC'),
(47, 1, 'Colombia', 'COL', 'CO'),
(48, 1, 'Comoros', 'COM', 'KM'),
(49, 1, 'Congo', 'COG', 'CG'),
(50, 1, 'Cook Islands', 'COK', 'CK'),
(51, 1, 'Costa Rica', 'CRI', 'CR'),
(52, 1, 'Cote D''Ivoire', 'CIV', 'CI'),
(53, 1, 'Croatia', 'HRV', 'HR'),
(54, 1, 'Cuba', 'CUB', 'CU'),
(55, 1, 'Cyprus', 'CYP', 'CY'),
(56, 1, 'Czech Republic', 'CZE', 'CZ'),
(57, 1, 'Denmark', 'DNK', 'DK'),
(58, 1, 'Djibouti', 'DJI', 'DJ'),
(59, 1, 'Dominica', 'DMA', 'DM'),
(60, 1, 'Dominican Republic', 'DOM', 'DO'),
(61, 1, 'East Timor', 'TMP', 'TP'),
(62, 1, 'Ecuador', 'ECU', 'EC'),
(63, 1, 'Egypt', 'EGY', 'EG'),
(64, 1, 'El Salvador', 'SLV', 'SV'),
(65, 1, 'Equatorial Guinea', 'GNQ', 'GQ'),
(66, 1, 'Eritrea', 'ERI', 'ER'),
(67, 1, 'Estonia', 'EST', 'EE'),
(68, 1, 'Ethiopia', 'ETH', 'ET'),
(69, 1, 'Falkland Islands (Malvinas)', 'FLK', 'FK'),
(70, 1, 'Faroe Islands', 'FRO', 'FO'),
(71, 1, 'Fiji', 'FJI', 'FJ'),
(72, 1, 'Finland', 'FIN', 'FI'),
(73, 1, 'France', 'FRA', 'FR'),
(74, 1, 'France, Metropolitan', 'FXX', 'FX'),
(75, 1, 'French Guiana', 'GUF', 'GF'),
(76, 1, 'French Polynesia', 'PYF', 'PF'),
(77, 1, 'French Southern Territories', 'ATF', 'TF'),
(78, 1, 'Gabon', 'GAB', 'GA'),
(79, 1, 'Gambia', 'GMB', 'GM'),
(80, 1, 'Georgia', 'GEO', 'GE'),
(81, 1, 'Germany', 'DEU', 'DE'),
(82, 1, 'Ghana', 'GHA', 'GH'),
(83, 1, 'Gibraltar', 'GIB', 'GI'),
(84, 1, 'Greece', 'GRC', 'GR'),
(85, 1, 'Greenland', 'GRL', 'GL'),
(86, 1, 'Grenada', 'GRD', 'GD'),
(87, 1, 'Guadeloupe', 'GLP', 'GP'),
(88, 1, 'Guam', 'GUM', 'GU'),
(89, 1, 'Guatemala', 'GTM', 'GT'),
(90, 1, 'Guinea', 'GIN', 'GN'),
(91, 1, 'Guinea-bissau', 'GNB', 'GW'),
(92, 1, 'Guyana', 'GUY', 'GY'),
(93, 1, 'Haiti', 'HTI', 'HT'),
(94, 1, 'Heard and Mc Donald Islands', 'HMD', 'HM'),
(95, 1, 'Honduras', 'HND', 'HN'),
(96, 1, 'Hong Kong', 'HKG', 'HK'),
(97, 1, 'Hungary', 'HUN', 'HU'),
(98, 1, 'Iceland', 'ISL', 'IS'),
(99, 1, 'India', 'IND', 'IN'),
(100, 1, 'Indonesia', 'IDN', 'ID'),
(101, 1, 'Iran (Islamic Republic of)', 'IRN', 'IR'),
(102, 1, 'Iraq', 'IRQ', 'IQ'),
(103, 1, 'Ireland', 'IRL', 'IE'),
(104, 1, 'Israel', 'ISR', 'IL'),
(105, 1, 'Italy', 'ITA', 'IT'),
(106, 1, 'Jamaica', 'JAM', 'JM'),
(107, 1, 'Japan', 'JPN', 'JP'),
(108, 1, 'Jordan', 'JOR', 'JO'),
(109, 1, 'Kazakhstan', 'KAZ', 'KZ'),
(110, 1, 'Kenya', 'KEN', 'KE'),
(111, 1, 'Kiribati', 'KIR', 'KI'),
(112, 1, 'Korea, Democratic People''s Republic of', 'PRK', 'KP'),
(113, 1, 'Korea, Republic of', 'KOR', 'KR'),
(114, 1, 'Kuwait', 'KWT', 'KW'),
(115, 1, 'Kyrgyzstan', 'KGZ', 'KG'),
(116, 1, 'Lao People''s Democratic Republic', 'LAO', 'LA'),
(117, 1, 'Latvia', 'LVA', 'LV'),
(118, 1, 'Lebanon', 'LBN', 'LB'),
(119, 1, 'Lesotho', 'LSO', 'LS'),
(120, 1, 'Liberia', 'LBR', 'LR'),
(121, 1, 'Libyan Arab Jamahiriya', 'LBY', 'LY'),
(122, 1, 'Liechtenstein', 'LIE', 'LI'),
(123, 1, 'Lithuania', 'LTU', 'LT'),
(124, 1, 'Luxembourg', 'LUX', 'LU'),
(125, 1, 'Macau', 'MAC', 'MO'),
(126, 1, 'Macedonia, The Former Yugoslav Republic of', 'MKD', 'MK'),
(127, 1, 'Madagascar', 'MDG', 'MG'),
(128, 1, 'Malawi', 'MWI', 'MW'),
(129, 1, 'Malaysia', 'MYS', 'MY'),
(130, 1, 'Maldives', 'MDV', 'MV'),
(131, 1, 'Mali', 'MLI', 'ML'),
(132, 1, 'Malta', 'MLT', 'MT'),
(133, 1, 'Marshall Islands', 'MHL', 'MH'),
(134, 1, 'Martinique', 'MTQ', 'MQ'),
(135, 1, 'Mauritania', 'MRT', 'MR'),
(136, 1, 'Mauritius', 'MUS', 'MU'),
(137, 1, 'Mayotte', 'MYT', 'YT'),
(138, 1, 'Mexico', 'MEX', 'MX'),
(139, 1, 'Micronesia, Federated States of', 'FSM', 'FM'),
(140, 1, 'Moldova, Republic of', 'MDA', 'MD'),
(141, 1, 'Monaco', 'MCO', 'MC'),
(142, 1, 'Mongolia', 'MNG', 'MN'),
(143, 1, 'Montserrat', 'MSR', 'MS'),
(144, 1, 'Morocco', 'MAR', 'MA'),
(145, 1, 'Mozambique', 'MOZ', 'MZ'),
(146, 1, 'Myanmar', 'MMR', 'MM'),
(147, 1, 'Namibia', 'NAM', 'NA'),
(148, 1, 'Nauru', 'NRU', 'NR'),
(149, 1, 'Nepal', 'NPL', 'NP'),
(150, 1, 'Netherlands', 'NLD', 'NL'),
(151, 1, 'Netherlands Antilles', 'ANT', 'AN'),
(152, 1, 'New Caledonia', 'NCL', 'NC'),
(153, 1, 'New Zealand', 'NZL', 'NZ'),
(154, 1, 'Nicaragua', 'NIC', 'NI'),
(155, 1, 'Niger', 'NER', 'NE'),
(156, 1, 'Nigeria', 'NGA', 'NG'),
(157, 1, 'Niue', 'NIU', 'NU'),
(158, 1, 'Norfolk Island', 'NFK', 'NF'),
(159, 1, 'Northern Mariana Islands', 'MNP', 'MP'),
(160, 1, 'Norway', 'NOR', 'NO'),
(161, 1, 'Oman', 'OMN', 'OM'),
(162, 1, 'Pakistan', 'PAK', 'PK'),
(163, 1, 'Palau', 'PLW', 'PW'),
(164, 1, 'Panama', 'PAN', 'PA'),
(165, 1, 'Papua New Guinea', 'PNG', 'PG'),
(166, 1, 'Paraguay', 'PRY', 'PY'),
(167, 1, 'Peru', 'PER', 'PE'),
(168, 1, 'Philippines', 'PHL', 'PH'),
(169, 1, 'Pitcairn', 'PCN', 'PN'),
(170, 1, 'Poland', 'POL', 'PL'),
(171, 1, 'Portugal', 'PRT', 'PT'),
(172, 1, 'Puerto Rico', 'PRI', 'PR'),
(173, 1, 'Qatar', 'QAT', 'QA'),
(174, 1, 'Reunion', 'REU', 'RE'),
(175, 1, 'Romania', 'ROM', 'RO'),
(176, 1, 'Russian Federation', 'RUS', 'RU'),
(177, 1, 'Rwanda', 'RWA', 'RW'),
(178, 1, 'Saint Kitts and Nevis', 'KNA', 'KN'),
(179, 1, 'Saint Lucia', 'LCA', 'LC'),
(180, 1, 'Saint Vincent and the Grenadines', 'VCT', 'VC'),
(181, 1, 'Samoa', 'WSM', 'WS'),
(182, 1, 'San Marino', 'SMR', 'SM'),
(183, 1, 'Sao Tome and Principe', 'STP', 'ST'),
(184, 1, 'Saudi Arabia', 'SAU', 'SA'),
(185, 1, 'Senegal', 'SEN', 'SN'),
(186, 1, 'Seychelles', 'SYC', 'SC'),
(187, 1, 'Sierra Leone', 'SLE', 'SL'),
(188, 1, 'Singapore', 'SGP', 'SG'),
(189, 1, 'Slovakia (Slovak Republic)', 'SVK', 'SK'),
(190, 1, 'Slovenia', 'SVN', 'SI'),
(191, 1, 'Solomon Islands', 'SLB', 'SB'),
(192, 1, 'Somalia', 'SOM', 'SO'),
(193, 1, 'South Africa', 'ZAF', 'ZA'),
(194, 1, 'South Georgia and the South Sandwich Islands', 'SGS', 'GS'),
(195, 1, 'Spain', 'ESP', 'ES'),
(196, 1, 'Sri Lanka', 'LKA', 'LK'),
(197, 1, 'St. Helena', 'SHN', 'SH'),
(198, 1, 'St. Pierre and Miquelon', 'SPM', 'PM'),
(199, 1, 'Sudan', 'SDN', 'SD'),
(200, 1, 'Suriname', 'SUR', 'SR'),
(201, 1, 'Svalbard and Jan Mayen Islands', 'SJM', 'SJ'),
(202, 1, 'Swaziland', 'SWZ', 'SZ'),
(203, 1, 'Sweden', 'SWE', 'SE'),
(204, 1, 'Switzerland', 'CHE', 'CH'),
(205, 1, 'Syrian Arab Republic', 'SYR', 'SY'),
(206, 1, 'Taiwan', 'TWN', 'TW'),
(207, 1, 'Tajikistan', 'TJK', 'TJ'),
(208, 1, 'Tanzania, United Republic of', 'TZA', 'TZ'),
(209, 1, 'Thailand', 'THA', 'TH'),
(210, 1, 'Togo', 'TGO', 'TG'),
(211, 1, 'Tokelau', 'TKL', 'TK'),
(212, 1, 'Tonga', 'TON', 'TO'),
(213, 1, 'Trinidad and Tobago', 'TTO', 'TT'),
(214, 1, 'Tunisia', 'TUN', 'TN'),
(215, 1, 'Turkey', 'TUR', 'TR'),
(216, 1, 'Turkmenistan', 'TKM', 'TM'),
(217, 1, 'Turks and Caicos Islands', 'TCA', 'TC'),
(218, 1, 'Tuvalu', 'TUV', 'TV'),
(219, 1, 'Uganda', 'UGA', 'UG'),
(220, 1, 'Ukraine', 'UKR', 'UA'),
(221, 1, 'United Arab Emirates', 'ARE', 'AE'),
(222, 1, 'United Kingdom', 'GBR', 'GB'),
(223, 1, 'United States', 'USA', 'US'),
(224, 1, 'United States Minor Outlying Islands', 'UMI', 'UM'),
(225, 1, 'Uruguay', 'URY', 'UY'),
(226, 1, 'Uzbekistan', 'UZB', 'UZ'),
(227, 1, 'Vanuatu', 'VUT', 'VU'),
(228, 1, 'Vatican City State (Holy See)', 'VAT', 'VA'),
(229, 1, 'Venezuela', 'VEN', 'VE'),
(230, 1, 'Viet Nam', 'VNM', 'VN'),
(231, 1, 'Virgin Islands (British)', 'VGB', 'VG'),
(232, 1, 'Virgin Islands (U.S.)', 'VIR', 'VI'),
(233, 1, 'Wallis and Futuna Islands', 'WLF', 'WF'),
(234, 1, 'Western Sahara', 'ESH', 'EH'),
(235, 1, 'Yemen', 'YEM', 'YE'),
(236, 1, 'Serbia', 'SRB', 'RS'),
(237, 1, 'The Democratic Republic of Congo', 'DRC', 'DC'),
(238, 1, 'Zambia', 'ZMB', 'ZM'),
(239, 1, 'Zimbabwe', 'ZWE', 'ZW'),
(240, 1, 'East Timor', 'XET', 'XE'),
(241, 1, 'Jersey', 'XJE', 'XJ'),
(242, 1, 'St. Barthelemy', 'XSB', 'XB'),
(243, 1, 'St. Eustatius', 'XSE', 'XU'),
(244, 1, 'Canary Islands', 'XCA', 'XC'),
(245, 1, 'Montenegro', 'MNE', 'ME'); " );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_coupons`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_coupons` (
  `coupon_id` int(16) NOT NULL auto_increment,
  `coupon_code` varchar(32) NOT NULL default '',
  `percent_or_total` enum('percent','total') NOT NULL default 'percent',
  `coupon_type` enum('gift','permanent') NOT NULL default 'gift',
  `coupon_value` decimal(12,2) NOT NULL default '0.00',
  PRIMARY KEY  (`coupon_id`)
) ENGINE=MyISAM COMMENT='Used to store coupon codes'; ");

## 
## Dumping data for table `#__{vm}_coupons`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_creditcard`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_creditcard` (
  `creditcard_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) NOT NULL default '0',
  `creditcard_name` varchar(70) NOT NULL default '',
  `creditcard_code` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`creditcard_id`)
) ENGINE=MyISAM COMMENT='Used to store credit card types'; ");

## 
## Dumping data for table `#__{vm}_creditcard`
## 

$db->query( "INSERT INTO `#__{vm}_creditcard` VALUES (1, 1, 'Visa', 'VISA'),
(2, 1, 'MasterCard', 'MC'),
(3, 1, 'American Express', 'amex'),
(4, 1, 'Discover Card', 'discover'),
(5, 1, 'Diners Club', 'diners'),
(6, 1, 'JCB', 'jcb'),
(7, 1, 'Australian Bankcard', 'australian_bc'); " );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_csv`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_csv` (
  `field_id` int(11) NOT NULL auto_increment,
  `field_name` varchar(128) NOT NULL default '',
  `field_default_value` text,
  `field_ordering` int(3) NOT NULL default '0',
  `field_required` char(1) default 'N',
  PRIMARY KEY  (`field_id`)
) ENGINE=MyISAM COMMENT='Holds all fields which are used on CVS Ex-/Import'; ");

## 
## Dumping data for table `#__{vm}_csv`
## 

$db->query( "INSERT INTO `#__{vm}_csv` VALUES (1, 'product_sku', '', 1, 'Y'),
(2, 'product_s_desc', '', 5, 'N'),
(3, 'product_desc', '', 6, 'N'),
(4, 'product_thumb_image', '', 7, 'N'),
(5, 'product_full_image', '', 8, 'N'),
(6, 'product_weight', '', 9, 'N'),
(7, 'product_weight_uom', 'KG', 10, 'N'),
(8, 'product_length', '', 11, 'N'),
(9, 'product_width', '', 12, 'N'),
(10, 'product_height', '', 13, 'N'),
(11, 'product_lwh_uom', '', 14, 'N'),
(12, 'product_in_stock', '0', 15, 'N'),
(13, 'product_available_date', '', 16, 'N'),
(14, 'product_discount_id', '', 17, 'N'),
(15, 'product_name', '', 2, 'Y'),
(16, 'product_price', '', 4, 'N'),
(17, 'category_path', '', 3, 'Y'),
(18, 'manufacturer_id', '', 18, 'N'),
(19, 'product_tax_id', '', 19, 'N'),
(20, 'product_sales', '', 20, 'N'),
(21, 'product_parent_id', '0', 21, 'N'),
(22, 'attribute', '', 22, 'N'),
(23, 'custom_attribute', '', 23, 'N'),
(24, 'attributes', '', 24, 'N'),
(25, 'attribute_values', '', 25, 'N'); " );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_currency`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_currency` (
  `currency_id` int(11) NOT NULL auto_increment,
  `currency_name` varchar(64) default NULL,
  `currency_code` char(3) default NULL,
  PRIMARY KEY  (`currency_id`),
  KEY `idx_currency_name` (`currency_name`)
) ENGINE=MyISAM COMMENT='Used to store currencies'; ");

## 
## Dumping data for table `#__{vm}_currency`
## 

$db->query( "INSERT INTO `#__{vm}_currency` VALUES (1, 'Andorran Peseta', 'ADP'),
(2, 'United Arab Emirates Dirham', 'AED'),
(3, 'Afghanistan Afghani', 'AFA'),
(4, 'Albanian Lek', 'ALL'),
(5, 'Netherlands Antillian Guilder', 'ANG'),
(6, 'Angolan Kwanza', 'AOK'),
(7, 'Argentine Peso', 'ARS'),
(9, 'Australian Dollar', 'AUD'),
(10, 'Aruban Florin', 'AWG'),
(11, 'Barbados Dollar', 'BBD'),
(12, 'Bangladeshi Taka', 'BDT'),
(14, 'Bulgarian Lev', 'BGL'),
(15, 'Bahraini Dinar', 'BHD'),
(16, 'Burundi Franc', 'BIF'),
(17, 'Bermudian Dollar', 'BMD'),
(18, 'Brunei Dollar', 'BND'),
(19, 'Bolivian Boliviano', 'BOB'),
(20, 'Brazilian Real', 'BRL'),
(21, 'Bahamian Dollar', 'BSD'),
(22, 'Bhutan Ngultrum', 'BTN'),
(23, 'Burma Kyat', 'BUK'),
(24, 'Botswanian Pula', 'BWP'),
(25, 'Belize Dollar', 'BZD'),
(26, 'Canadian Dollar', 'CAD'),
(27, 'Swiss Franc', 'CHF'),
(28, 'Chilean Unidades de Fomento', 'CLF'),
(29, 'Chilean Peso', 'CLP'),
(30, 'Yuan (Chinese) Renminbi', 'CNY'),
(31, 'Colombian Peso', 'COP'),
(32, 'Costa Rican Colon', 'CRC'),
(33, 'Czech Koruna', 'CZK'),
(34, 'Cuban Peso', 'CUP'),
(35, 'Cape Verde Escudo', 'CVE'),
(36, 'Cyprus Pound', 'CYP'),
(40, 'Danish Krone', 'DKK'),
(41, 'Dominican Peso', 'DOP'),
(42, 'Algerian Dinar', 'DZD'),
(43, 'Ecuador Sucre', 'ECS'),
(44, 'Egyptian Pound', 'EGP'),
(46, 'Ethiopian Birr', 'ETB'),
(47, 'Euro', 'EUR'),
(49, 'Fiji Dollar', 'FJD'),
(50, 'Falkland Islands Pound', 'FKP'),
(52, 'British Pound', 'GBP'),
(53, 'Ghanaian Cedi', 'GHC'),
(54, 'Gibraltar Pound', 'GIP'),
(55, 'Gambian Dalasi', 'GMD'),
(56, 'Guinea Franc', 'GNF'),
(58, 'Guatemalan Quetzal', 'GTQ'),
(59, 'Guinea-Bissau Peso', 'GWP'),
(60, 'Guyanan Dollar', 'GYD'),
(61, 'Hong Kong Dollar', 'HKD'),
(62, 'Honduran Lempira', 'HNL'),
(63, 'Haitian Gourde', 'HTG'),
(64, 'Hungarian Forint', 'HUF'),
(65, 'Indonesian Rupiah', 'IDR'),
(66, 'Irish Punt', 'IEP'),
(67, 'Israeli Shekel', 'ILS'),
(68, 'Indian Rupee', 'INR'),
(69, 'Iraqi Dinar', 'IQD'),
(70, 'Iranian Rial', 'IRR'),
(73, 'Jamaican Dollar', 'JMD'),
(74, 'Jordanian Dinar', 'JOD'),
(75, 'Japanese Yen', 'JPY'),
(76, 'Kenyan Shilling', 'KES'),
(77, 'Kampuchean (Cambodian) Riel', 'KHR'),
(78, 'Comoros Franc', 'KMF'),
(79, 'North Korean Won', 'KPW'),
(80, '(South) Korean Won', 'KRW'),
(81, 'Kuwaiti Dinar', 'KWD'),
(82, 'Cayman Islands Dollar', 'KYD'),
(83, 'Lao Kip', 'LAK'),
(84, 'Lebanese Pound', 'LBP'),
(85, 'Sri Lanka Rupee', 'LKR'),
(86, 'Liberian Dollar', 'LRD'),
(87, 'Lesotho Loti', 'LSL'),
(89, 'Libyan Dinar', 'LYD'),
(90, 'Moroccan Dirham', 'MAD'),
(91, 'Malagasy Franc', 'MGF'),
(92, 'Mongolian Tugrik', 'MNT'),
(93, 'Macau Pataca', 'MOP'),
(94, 'Mauritanian Ouguiya', 'MRO'),
(95, 'Maltese Lira', 'MTL'),
(96, 'Mauritius Rupee', 'MUR'),
(97, 'Maldive Rufiyaa', 'MVR'),
(98, 'Malawi Kwacha', 'MWK'),
(99, 'Mexican Peso', 'MXP'),
(100, 'Malaysian Ringgit', 'MYR'),
(101, 'Mozambique Metical', 'MZM'),
(102, 'Nigerian Naira', 'NGN'),
(103, 'Nicaraguan Cordoba', 'NIC'),
(105, 'Norwegian Kroner', 'NOK'),
(106, 'Nepalese Rupee', 'NPR'),
(107, 'New Zealand Dollar', 'NZD'),
(108, 'Omani Rial', 'OMR'),
(109, 'Panamanian Balboa', 'PAB'),
(110, 'Peruvian Nuevo Sol', 'PEN'),
(111, 'Papua New Guinea Kina', 'PGK'),
(112, 'Philippine Peso', 'PHP'),
(113, 'Pakistan Rupee', 'PKR'),
(114, 'Polish Złoty', 'PLN'),
(116, 'Paraguay Guarani', 'PYG'),
(117, 'Qatari Rial', 'QAR'),
(118, 'Romanian Leu', 'RON'),
(119, 'Rwanda Franc', 'RWF'),
(120, 'Saudi Arabian Riyal', 'SAR'),
(121, 'Solomon Islands Dollar', 'SBD'),
(122, 'Seychelles Rupee', 'SCR'),
(123, 'Sudanese Pound', 'SDP'),
(124, 'Swedish Krona', 'SEK'),
(125, 'Singapore Dollar', 'SGD'),
(126, 'St. Helena Pound', 'SHP'),
(127, 'Sierra Leone Leone', 'SLL'),
(128, 'Somali Shilling', 'SOS'),
(129, 'Suriname Guilder', 'SRG'),
(130, 'Sao Tome and Principe Dobra', 'STD'),
(131, 'Russian Ruble', 'RUB'),
(132, 'El Salvador Colon', 'SVC'),
(133, 'Syrian Potmd', 'SYP'),
(134, 'Swaziland Lilangeni', 'SZL'),
(135, 'Thai Bath', 'THB'),
(136, 'Tunisian Dinar', 'TND'),
(137, 'Tongan Pa''anga', 'TOP'),
(138, 'East Timor Escudo', 'TPE'),
(139, 'Turkish Lira', 'TRY'),
(140, 'Trinidad and Tobago Dollar', 'TTD'),
(141, 'Taiwan Dollar', 'TWD'),
(142, 'Tanzanian Shilling', 'TZS'),
(143, 'Uganda Shilling', 'UGS'),
(144, 'US Dollar', 'USD'),
(145, 'Uruguayan Peso', 'UYP'),
(146, 'Venezualan Bolivar', 'VEB'),
(147, 'Vietnamese Dong', 'VND'),
(148, 'Vanuatu Vatu', 'VUV'),
(149, 'Samoan Tala', 'WST'),
(150, 'Democratic Yemeni Dinar', 'YDD'),
(151, 'Yemeni Rial', 'YER'),
(152, 'Dinar', 'RSD'),
(153, 'South African Rand', 'ZAR'),
(154, 'Zambian Kwacha', 'ZMK'),
(155, 'Zaire Zaire', 'ZRZ'),
(156, 'Zimbabwe Dollar', 'ZWD'),
(157, 'Slovak Koruna', 'SKK'),
(NULL, 'Armenian Dram', 'AMD'); " );

## --------------------------------------------------------

## 
## Tabellenstruktur für Tabelle `#__{vm}_export`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_export` (
  `export_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) default NULL,
  `export_name` varchar(255) default NULL,
  `export_desc` text NOT NULL,
  `export_class` varchar(50) NOT NULL,
  `export_enabled` char(1) NOT NULL default 'N',
  `export_config` text NOT NULL,
  `iscore` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`export_id`)
) ENGINE=MyISAM COMMENT='Export Modules';");

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_function`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_function` (
  `function_id` int(11) NOT NULL auto_increment,
  `module_id` int(11) default NULL,
  `function_name` varchar(32) default NULL,
  `function_class` varchar(32) default NULL,
  `function_method` varchar(32) default NULL,
  `function_description` text,
  `function_perms` varchar(255) default NULL,
  PRIMARY KEY  (`function_id`),
  KEY `idx_function_module_id` (`module_id`),
  KEY `idx_function_name` (`function_name`)
) ENGINE=MyISAM COMMENT='Used to map a function alias to a ''real'' class::function'; ");

## 
## Dumping data for table `#__{vm}_function`
## 

$db->query( "INSERT INTO `#__{vm}_function` VALUES (1, 1, 'userAdd', 'ps_user', 'add', '', 'admin,storeadmin'),
(2, 1, 'userDelete', 'ps_user', 'delete', '', 'admin,storeadmin'),
(3, 1, 'userUpdate', 'ps_user', 'update', '', 'admin,storeadmin'),
(31, 2, 'productAdd', 'ps_product', 'add', '', 'admin,storeadmin'),
(6, 1, 'functionAdd', 'ps_function', 'add', '', 'admin'),
(7, 1, 'functionUpdate', 'ps_function', 'update', '', 'admin'),
(8, 1, 'functionDelete', 'ps_function', 'delete', '', 'admin'),
(9, 1, 'userLogout', 'ps_user', 'logout', '', 'none'),
(10, 1, 'userAddressAdd', 'ps_user_address', 'add', '', 'admin,storeadmin,shopper,demo'),
(11, 1, 'userAddressUpdate', 'ps_user_address', 'update', '', 'admin,storeadmin,shopper'),
(12, 1, 'userAddressDelete', 'ps_user_address', 'delete', '', 'admin,storeadmin,shopper'),
(13, 1, 'moduleAdd', 'ps_module', 'add', '', 'admin'),
(14, 1, 'moduleUpdate', 'ps_module', 'update', '', 'admin'),
(15, 1, 'moduleDelete', 'ps_module', 'delete', '', 'admin'),
(16, 1, 'userLogin', 'ps_user', 'login', '', 'none'),
(17, 3, 'vendorAdd', 'ps_vendor', 'add', '', 'admin'),
(18, 3, 'vendorUpdate', 'ps_vendor', 'update', '', 'admin,storeadmin'),
(19, 3, 'vendorDelete', 'ps_vendor', 'delete', '', 'admin'),
(20, 3, 'vendorCategoryAdd', 'ps_vendor_category', 'add', '', 'admin'),
(21, 3, 'vendorCategoryUpdate', 'ps_vendor_category', 'update', '', 'admin'),
(22, 3, 'vendorCategoryDelete', 'ps_vendor_category', 'delete', '', 'admin'),
(23, 4, 'shopperAdd', 'ps_shopper', 'add', '', 'none'),
(24, 4, 'shopperDelete', 'ps_shopper', 'delete', '', 'admin,storeadmin'),
(25, 4, 'shopperUpdate', 'ps_shopper', 'update', '', 'admin,storeadmin,shopper'),
(26, 4, 'shopperGroupAdd', 'ps_shopper_group', 'add', '', 'admin,storeadmin'),
(27, 4, 'shopperGroupUpdate', 'ps_shopper_group', 'update', '', 'admin,storeadmin'),
(28, 4, 'shopperGroupDelete', 'ps_shopper_group', 'delete', '', 'admin,storeadmin'),
(30, 5, 'orderStatusSet', 'ps_order', 'order_status_update', '', 'admin,storeadmin'),
(32, 2, 'productDelete', 'ps_product', 'delete', '', 'admin,storeadmin'),
(33, 2, 'productUpdate', 'ps_product', 'update', '', 'admin,storeadmin'),
(34, 2, 'productCategoryAdd', 'ps_product_category', 'add', '', 'admin,storeadmin'),
(35, 2, 'productCategoryUpdate', 'ps_product_category', 'update', '', 'admin,storeadmin'),
(36, 2, 'productCategoryDelete', 'ps_product_category', 'delete', '', 'admin,storeadmin'),
(37, 2, 'productPriceAdd', 'ps_product_price', 'add', '', 'admin,storeadmin'),
(38, 2, 'productPriceUpdate', 'ps_product_price', 'update', '', 'admin,storeadmin'),
(39, 2, 'productPriceDelete', 'ps_product_price', 'delete', '', 'admin,storeadmin'),
(40, 2, 'productAttributeAdd', 'ps_product_attribute', 'add', '', 'admin,storeadmin'),
(41, 2, 'productAttributeUpdate', 'ps_product_attribute', 'update', '', 'admin,storeadmin'),
(42, 2, 'productAttributeDelete', 'ps_product_attribute', 'delete', '', 'admin,storeadmin'),
(43, 7, 'cartAdd', 'ps_cart', 'add', '', 'none'),
(44, 7, 'cartUpdate', 'ps_cart', 'update', '', 'none'),
(45, 7, 'cartDelete', 'ps_cart', 'delete', '', 'none'),
(46, 10, 'checkoutComplete', 'ps_checkout', 'add', '', 'shopper,storeadmin,admin'),
(48, 8, 'paymentMethodUpdate', 'ps_payment_method', 'update', '', 'admin,storeadmin'),
(49, 8, 'paymentMethodAdd', 'ps_payment_method', 'add', '', 'admin,storeadmin'),
(50, 8, 'paymentMethodDelete', 'ps_payment_method', 'delete', '', 'admin,storeadmin'),
(51, 5, 'orderDelete', 'ps_order', 'delete', '', 'admin,storeadmin'),
(52, 11, 'addTaxRate', 'ps_tax', 'add', '', 'admin,storeadmin'),
(53, 11, 'updateTaxRate', 'ps_tax', 'update', '', 'admin,storeadmin'),
(54, 11, 'deleteTaxRate', 'ps_tax', 'delete', '', 'admin,storeadmin'),
(55, 10, 'checkoutValidateST', 'ps_checkout', 'validate_shipto', '', 'none'),
(59, 5, 'orderStatusUpdate', 'ps_order_status', 'update', '', 'admin,storeadmin'),
(60, 5, 'orderStatusAdd', 'ps_order_status', 'add', '', 'storeadmin,admin'),
(61, 5, 'orderStatusDelete', 'ps_order_status', 'delete', '', 'admin,storeadmin'),
(62, 1, 'currencyAdd', 'ps_currency', 'add', 'add a currency', 'storeadmin,admin'),
(63, 1, 'currencyUpdate', 'ps_currency', 'update', '        update a currency', 'storeadmin,admin'),
(64, 1, 'currencyDelete', 'ps_currency', 'delete', 'delete a currency', 'storeadmin,admin'),
(65, 1, 'countryAdd', 'ps_country', 'add', 'Add a country ', 'storeadmin,admin'),
(66, 1, 'countryUpdate', 'ps_country', 'update', 'Update a country record', 'storeadmin,admin'),
(67, 1, 'countryDelete', 'ps_country', 'delete', 'Delete a country record', 'storeadmin,admin'),
(68, 2, 'product_csv', 'ps_csv', 'upload_csv', '', 'admin'),
(110, 7, 'waitingListAdd', 'zw_waiting_list', 'add', '', 'none'),
(111, 13, 'addzone', 'ps_zone', 'add', 'This will add a zone', 'admin,storeadmin'),
(112, 13, 'updatezone', 'ps_zone', 'update', 'This will update a zone', 'admin,storeadmin'),
(113, 13, 'deletezone', 'ps_zone', 'delete', 'This will delete a zone', 'admin,storeadmin'),
(114, 13, 'zoneassign', 'ps_zone', 'assign', 'This will assign a country to a zone', 'admin,storeadmin'),
(115, 1, 'writeConfig', 'ps_config', 'writeconfig', 'This will write the configuration details to virtuemart.cfg.php', 'admin'),
(116, 12839, 'carrierAdd', 'ps_shipping', 'add', '', 'admin,storeadmin'),
(117, 12839, 'carrierDelete', 'ps_shipping', 'delete', '', 'admin,storeadmin'),
(118, 12839, 'carrierUpdate', 'ps_shipping', 'update', '', 'admin,storeadmin'),
(119, 12839, 'rateAdd', 'ps_shipping', 'rate_add', '', 'admin,storeadmin'),
(120, 12839, 'rateUpdate', 'ps_shipping', 'rate_update', '', 'admin,shopadmin'),
(121, 12839, 'rateDelete', 'ps_shipping', 'rate_delete', '', 'admin,storeadmin'),
(122, 10, 'checkoutProcess', 'ps_checkout', 'process', '', 'none'),
(123, 5, 'downloadRequest', 'ps_order', 'download_request', 'This checks if the download request is valid and sends the file to the browser as file download if the request was successful, otherwise echoes an error', 'none'),
(128, 99, 'manufacturerAdd', 'ps_manufacturer', 'add', '', 'admin,storeadmin'),
(129, 99, 'manufacturerUpdate', 'ps_manufacturer', 'update', '', 'admin,storeadmin'),
(130, 99, 'manufacturerDelete', 'ps_manufacturer', 'delete', '', 'admin,storeadmin'),
(131, 99, 'manufacturercategoryAdd', 'ps_manufacturer_category', 'add', '', 'admin,storeadmin'),
(132, 99, 'manufacturercategoryUpdate', 'ps_manufacturer_category', 'update', '', 'admin,storeadmin'),
(133, 99, 'manufacturercategoryDelete', 'ps_manufacturer_category', 'delete', '', 'admin,storeadmin'),
(134, 7, 'addReview', 'ps_reviews', 'process_review', 'This lets the user add a review and rating to a product.', 'admin,storeadmin,shopper,demo'),
(135, 7, 'productReviewDelete', 'ps_reviews', 'delete_review', 'This deletes a review and from a product.', 'admin,storeadmin'),
(136, 8, 'creditcardAdd', 'ps_creditcard', 'add', 'Adds a Credit Card entry.', 'admin,storeadmin'),
(137, 8, 'creditcardUpdate', 'ps_creditcard', 'update', 'Updates a Credit Card entry.', 'admin,storeadmin'),
(138, 8, 'creditcardDelete', 'ps_creditcard', 'delete', 'Deletes a Credit Card entry.', 'admin,storeadmin'),
(139, 2, 'changePublishState', 'vmAbstractObject.class', 'handlePublishState', 'Changes the publish field of an item, so that it can be published or unpublished easily.', 'admin,storeadmin'),
(140, 2, 'export_csv', 'ps_csv', 'export_csv', 'This function exports all relevant product data to CSV.', 'admin,storeadmin'),
(141, 2, 'reorder', 'ps_product_category', 'reorder', 'Changes the list order of a category.', 'admin,storeadmin'),
(142, 2, 'discountAdd', 'ps_product_discount', 'add', 'Adds a discount.', 'admin,storeadmin'),
(143, 2, 'discountUpdate', 'ps_product_discount', 'update', 'Updates a discount.', 'admin,storeadmin'),
(144, 2, 'discountDelete', 'ps_product_discount', 'delete', 'Deletes a discount.', 'admin,storeadmin'),
(145, 8, 'shippingmethodSave', 'ps_shipping_method', 'save', '', 'admin,storeadmin'),
(146, 2, 'uploadProductFile', 'ps_product_files', 'add', 'Uploads and Adds a Product Image/File.', 'admin,storeadmin'),
(147, 2, 'updateProductFile', 'ps_product_files', 'update', 'Updates a Product Image/File.', 'admin,storeadmin'),
(148, 2, 'deleteProductFile', 'ps_product_files', 'delete', 'Deletes a Product Image/File.', 'admin,storeadmin'),
(149, 12843, 'couponAdd', 'ps_coupon', 'add_coupon_code', 'Adds a Coupon.', 'admin,storeadmin'),
(150, 12843, 'couponUpdate', 'ps_coupon', 'update_coupon', 'Updates a Coupon.', 'admin,storeadmin'),
(151, 12843, 'couponDelete', 'ps_coupon', 'remove_coupon_code', 'Deletes a Coupon.', 'admin,storeadmin'),
(152, 12843, 'couponProcess', 'ps_coupon', 'process_coupon_code', 'Processes a Coupon.', 'admin,storeadmin,shopper,demo'),
(153, 2, 'ProductTypeAdd', 'ps_product_type', 'add', 'Function add a Product Type and create new table product_type_<id>.', 'admin'),
(154, 2, 'ProductTypeUpdate', 'ps_product_type', 'update', 'Update a Product Type.', 'admin'),
(155, 2, 'ProductTypeDelete', 'ps_product_type', 'delete', 'Delete a Product Type and drop table product_type_<id>.', 'admin'),
(156, 2, 'ProductTypeReorder', 'ps_product_type', 'reorder', 'Changes the list order of a Product Type.', 'admin'),
(157, 2, 'ProductTypeAddParam', 'ps_product_type_parameter', 'add_parameter', 'Function add a Parameter into a Product Type and create new column in table product_type_<id>.', 'admin'),
(158, 2, 'ProductTypeUpdateParam', 'ps_product_type_parameter', 'update_parameter', 'Function update a Parameter in a Product Type and a column in table product_type_<id>.', 'admin'),
(159, 2, 'ProductTypeDeleteParam', 'ps_product_type_parameter', 'delete_parameter', 'Function delete a Parameter from a Product Type and drop a column in table product_type_<id>.', 'admin'),
(160, 2, 'ProductTypeReorderParam', 'ps_product_type_parameter', 'reorder_parameter', 'Changes the list order of a Parameter.', 'admin'),
(161, 2, 'productProductTypeAdd', 'ps_product_product_type', 'add', 'Add a Product into a Product Type.', 'admin,storeadmin'),
(162, 2, 'productProductTypeDelete', 'ps_product_product_type', 'delete', 'Delete a Product from a Product Type.', 'admin,storeadmin'),
(163, 1, 'stateAdd', 'ps_country', 'addState', 'Add a State ', 'storeadmin,admin'),
(164, 1, 'stateUpdate', 'ps_country', 'updateState', 'Update a state record', 'storeadmin,admin'),
(165, 1, 'stateDelete', 'ps_country', 'deleteState', 'Delete a state record', 'storeadmin,admin'),
(166, 2, 'csvFieldAdd', 'ps_csv', 'add', 'Add a CSV Field ', 'storeadmin,admin'),
(167, 2, 'csvFieldUpdate', 'ps_csv', 'update', 'Update a CSV Field', 'storeadmin,admin'),
(168, 2, 'csvFieldDelete', 'ps_csv', 'delete', 'Delete a CSV Field', 'storeadmin,admin'),
(169, 1, 'userfieldSave', 'ps_userfield', 'savefield', 'add or edit a user field', 'admin'),
(170, 1, 'userfieldDelete', 'ps_userfield', 'deletefield', '', 'admin'),
(171, 1, 'changeordering', 'vmAbstractObject.class', 'handleordering', '', 'admin'),
(172, 2, 'moveProduct', 'ps_product', 'move', 'Move products from one category to another.', 'admin,storeadmin'),
(173, 7, 'productAsk', 'ps_communication', 'mail_question', 'Lets the customer send a question about a specific product.', 'none'),
(174, 7, 'recommendProduct', 'ps_communication', 'sendRecommendation', 'Lets the customer send a recommendation about a specific product to a friend.', 'none'),
(175, 2, 'reviewUpdate', 'ps_reviews', 'update', 'Modify a review about a specific product.', 'admin'),
(176, 8, 'ExportUpdate', 'ps_export', 'update', '', 'admin,storeadmin'),
(177, 8, 'ExportAdd', 'ps_export', 'add', '', 'admin,storeadmin'),
(178, 8, 'ExportDelete', 'ps_export', 'delete', '', 'admin,storeadmin'),
(179, 1, 'writeThemeConfig', 'ps_config', 'writeThemeConfig', 'Writes a theme configuration file.', 'admin'),
(180, 1, 'usergroupAdd', 'usergroup.class', 'add', 'Add a new user group', 'admin'),
(181, 1, 'usergroupUpdate', 'usergroup.class', 'update', 'Update an user group', 'admin'),
(182, 1, 'usergroupDelete', 'usergroup.class', 'delete', 'Delete an user group', 'admin'),
(183, 1, 'setModulePermissions', 'ps_module', 'update_permissions', '', 'admin'),
(184, 1, 'setFunctionPermissions', 'ps_function', 'update_permissions', '', 'admin'),
(185, 2, 'insertDownloadsForProduct', 'ps_order', 'insert_downloads_for_product', '', 'admin'),
(186, 5, 'mailDownloadId', 'ps_order', 'mail_download_id', '', 'storeadmin,admin'), 
(187, 7, 'replaceSavedCart', 'ps_cart', 'replaceCart', 'Replace cart with saved cart', 'none'),
(188, 7, 'mergeSavedCart', 'ps_cart', 'mergeSaved', 'Merge saved cart with cart', 'none'),
(189, 7, 'deleteSavedCart', 'ps_cart', 'deleteCart', 'Delete saved cart', 'none'),
(190, 7, 'savedCartDelete', 'ps_cart', 'deleteSaved', 'Delete items from saved cart', 'none'),
(191, 7, 'savedCartUpdate', 'ps_cart', 'updateSaved', 'Update saved cart items', 'none'),
( NULL , '1', 'getupdatepackage', 'update.class', 'getPatchPackage', 'Retrieves the Patch Package from the virtuemart.net Servers.', 'admin'), 
(NULL , '1', 'applypatchpackage', 'update.class', 'applyPatch', 'Applies the Patch using the instructions from the update.xml file in the downloaded patch.', 'admin'),
(NULL, 1, 'removePatchPackage', 'update.class', 'removePackageFile', 'Removes  a Patch Package File and its extracted contents.', 'admin')" );
## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_manufacturer`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_manufacturer` (
  `manufacturer_id` int(11) NOT NULL auto_increment,
  `mf_name` varchar(64) default NULL,
  `mf_email` varchar(255) default NULL,
  `mf_desc` text,
  `mf_category_id` int(11) default NULL,
  `mf_url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`manufacturer_id`)
) ENGINE=MyISAM COMMENT='Manufacturers are those who create products'; ");

## 
## Dumping data for table `#__{vm}_manufacturer`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_manufacturer_category`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_manufacturer_category` (
  `mf_category_id` int(11) NOT NULL auto_increment,
  `mf_category_name` varchar(64) default NULL,
  `mf_category_desc` text,
  PRIMARY KEY  (`mf_category_id`),
  KEY `idx_manufacturer_category_category_name` (`mf_category_name`)
) ENGINE=MyISAM COMMENT='Manufacturers are assigned to these categories'");

## 
## Dumping data for table `#__{vm}_manufacturer_category`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_module`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_module` (
  `module_id` int(11) NOT NULL auto_increment,
  `module_name` varchar(255) default NULL,
  `module_description` text,
  `module_perms` varchar(255) default NULL,
  `module_publish` char(1) default NULL,
  `list_order` int(11) default NULL,
  PRIMARY KEY  (`module_id`),
  KEY `idx_module_name` (`module_name`),
  KEY `idx_module_list_order` (`list_order`)
) ENGINE=MyISAM COMMENT='VirtueMart Core Modules, not: Joomla modules'; ");

## 
## Dumping data for table `#__{vm}_module`
## 

$db->query( "INSERT INTO `#__{vm}_module` VALUES (1, 'admin', '<h4>ADMINISTRATIVE USERS ONLY</h4>\r\n\r\n<p>Only used for the following:</p>\r\n<OL>\r\n\r\n<LI>User Maintenance</LI>\r\n<LI>Module Maintenance</LI>\r\n<LI>Function Maintenance</LI>\r\n</OL>\r\n', 'admin', 'Y', 1),
(2, 'product', '<p>Here you can adminster your online catalog of products.  The Product Administrator allows you to create product categories, create new products, edit product attributes, and add product items for each attribute value.</p>', 'storeadmin,admin', 'Y', 4),
(3, 'vendor', '<h4>ADMINISTRATIVE USERS ONLY</h4>\r\n<p>Here you can manage the vendors on the phpShop system.</p>', 'admin', 'Y', 6),
(4, 'shopper', '<p>Manage shoppers in your store.  Allows you to create shopper groups.  Shopper groups can be used when setting the price for a product.  This allows you to create different prices for different types of users.  An example of this would be to have a ''wholesale'' group and a ''retail'' group. </p>', 'admin,storeadmin', 'Y', 4),
(5, 'order', '<p>View Order and Update Order Status.</p>', 'admin,storeadmin', 'Y', 5),
(6, 'msgs', 'This module is unprotected an used for displaying system messages to users.  We need to have an area that does not require authorization when things go wrong.', 'none', 'N', 99),
(7, 'shop', 'This is the Washupito store module.  This is the demo store included with the phpShop distribution.', 'none', 'Y', 99),
(8, 'store', '', 'storeadmin,admin', 'Y', 2),
(9, 'account', 'This module allows shoppers to update their account information and view previously placed orders.', 'shopper,storeadmin,admin,demo', 'N', 99),
(10, 'checkout', '', 'none', 'N', 99),
(11, 'tax', 'The tax module allows you to set tax rates for states or regions within a country.  The rate is set as a decimal figure.  For example, 2 percent tax would be 0.02.', 'admin,storeadmin', 'Y', 8),
(12, 'reportbasic', 'The report basic module allows you to do queries on all orders.', 'admin,storeadmin', 'Y', 7),
(13, 'zone', 'This is the zone-shipping module. Here you can manage your shipping costs according to Zones.', 'admin,storeadmin', 'N', 9),
(12839, 'shipping', '<h4>Shipping</h4><p>Let this module calculate the shipping fees for your customers.<br>Create carriers for shipping areas and weight groups.</p>', 'admin,storeadmin', 'Y', 10),
(99, 'manufacturer', 'Manage the manufacturers of products in your store.', 'storeadmin,admin', 'Y', 12),
(12842, 'help', 'Help Module', 'admin,storeadmin', 'Y', 13),
(12843, 'coupon', 'Coupon Management', 'admin,storeadmin', 'Y', 11);");

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_order_history`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_order_history` (
  `order_status_history_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_status_code` char(1) NOT NULL default '0',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `customer_notified` int(1) default '0',
  `comments` text,
  PRIMARY KEY  (`order_status_history_id`)
) ENGINE=MyISAM COMMENT='Stores all actions and changes that occur to an order'; ");

## 
## Dumping data for table `#__{vm}_order_history`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_order_item`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_order_item` (
  `order_item_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) default NULL,
  `user_info_id` varchar(32) default NULL,
  `vendor_id` int(11) default NULL,
  `product_id` int(11) default NULL,
  `order_item_sku` varchar(64) NOT NULL default '',
  `order_item_name` varchar(64) NOT NULL default '',
  `product_quantity` int(11) default NULL,
  `product_item_price` decimal(15,5) default NULL,
  `product_final_price` decimal(12,2) NOT NULL default '0.00',
  `order_item_currency` varchar(16) default NULL,
  `order_status` char(1) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `product_attribute` text,
  PRIMARY KEY  (`order_item_id`),
  KEY `idx_order_item_order_id` (`order_id`),
  KEY `idx_order_item_user_info_id` (`user_info_id`),
  KEY `idx_order_item_vendor_id` (`vendor_id`)
) ENGINE=MyISAM COMMENT='Stores all items (products) which are part of an order'; ");

## 
## Dumping data for table `#__{vm}_order_item`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_order_payment`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_order_payment` (
  `order_id` int(11) NOT NULL default '0',
  `payment_method_id` int(11) default NULL,
  `order_payment_code` varchar(30) NOT NULL default '',
  `order_payment_number` blob,
  `order_payment_expire` int(11) default NULL,
  `order_payment_name` varchar(255) default NULL,
  `order_payment_log` text,
  `order_payment_trans_id` text NOT NULL,
  KEY `idx_order_payment_order_id` (`order_id`),
  KEY `idx_order_payment_method_id` (`payment_method_id`)
) ENGINE=MyISAM COMMENT='The payment method that was chosen for a specific order'; ");

## 
## Dumping data for table `#__{vm}_order_payment`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_order_status`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_order_status` (
  `order_status_id` int(11) NOT NULL auto_increment,
  `order_status_code` char(1) NOT NULL default '',
  `order_status_name` varchar(64) default NULL,
   `order_status_description` TEXT NOT NULL,
  `list_order` int(11) default NULL,
  `vendor_id` int(11) default NULL,
  PRIMARY KEY  (`order_status_id`),
  KEY `idx_order_status_list_order` (`list_order`),
  KEY `idx_order_status_vendor_id` (`vendor_id`)
) ENGINE=MyISAM COMMENT='All available order statuses'; ");

## 
## Dumping data for table `#__{vm}_order_status`
## 

$db->query( "INSERT INTO `#__{vm}_order_status` VALUES (1, 'P', 'Pending', '', 1, 1),
(2, 'C', 'Confirmed', '', 2, 1),
(3, 'X', 'Cancelled', '', 3, 1),
(4, 'R', 'Refunded', '', 4, 1),
(5, 'S', 'Shipped', '', 5, 1);");

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_order_user_info`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_order_user_info` (
  `order_info_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `address_type` char(2) default NULL,
  `address_type_name` varchar(32) default NULL,
  `company` varchar(64) default NULL,
  `title` varchar(32) default NULL,
  `last_name` varchar(32) default NULL,
  `first_name` varchar(32) default NULL,
  `middle_name` varchar(32) default NULL,
  `phone_1` varchar(32) default NULL,
  `phone_2` varchar(32) default NULL,
  `fax` varchar(32) default NULL,
  `address_1` varchar(64) NOT NULL default '',
  `address_2` varchar(64) default NULL,
  `city` varchar(32) NOT NULL default '',
  `state` varchar(32) NOT NULL default '',
  `country` varchar(32) NOT NULL default 'US',
  `zip` varchar(32) NOT NULL default '',
  `user_email` varchar(255) default NULL,
  `extra_field_1` varchar(255) default NULL,
  `extra_field_2` varchar(255) default NULL,
  `extra_field_3` varchar(255) default NULL,
  `extra_field_4` char(1) default NULL,
  `extra_field_5` char(1) default NULL,
  `bank_account_nr` varchar(32) NOT NULL default '',
  `bank_name` varchar(32) NOT NULL default '',
  `bank_sort_code` varchar(16) NOT NULL default '',
  `bank_iban` varchar(64) NOT NULL default '',
  `bank_account_holder` varchar(48) NOT NULL default '',
  `bank_account_type` enum('Checking','Business Checking','Savings') NOT NULL default 'Checking',
  PRIMARY KEY  (`order_info_id`),
  KEY `idx_order_info_order_id` (`order_id`)
) ENGINE=MyISAM COMMENT='Stores the BillTo and ShipTo Information at order time'; ");

## 
## Dumping data for table `#__{vm}_order_user_info`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_orders`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `vendor_id` int(11) NOT NULL default '0',
  `order_number` varchar(32) default NULL,
  `user_info_id` varchar(32) default NULL,
  `order_total` decimal(15,5) NOT NULL default '0.00',
  `order_subtotal` decimal(15,5) default NULL,
  `order_tax` decimal(10,2) default NULL,
  `order_tax_details` TEXT NOT NULL,
  `order_shipping` decimal(10,2) default NULL,
  `order_shipping_tax` decimal(10,2) default NULL,
  `coupon_discount` decimal(12,2) NOT NULL default '0.00',
  `coupon_code` VARCHAR( 32 ) NULL,
  `order_discount` decimal(12,2) NOT NULL default '0.00',
  `order_currency` varchar(16) default NULL,
  `order_status` char(1) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `ship_method_id` varchar(255) default NULL,
  `customer_note` text NOT NULL,
  `ip_address` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`order_id`),
  KEY `idx_orders_user_id` (`user_id`),
  KEY `idx_orders_vendor_id` (`vendor_id`),
  KEY `idx_orders_order_number` (`order_number`),
  KEY `idx_orders_user_info_id` (`user_info_id`),
  KEY `idx_orders_ship_method_id` (`ship_method_id`)
) ENGINE=MyISAM COMMENT='Used to store all orders'; ");

## 
## Dumping data for table `#__{vm}_orders`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_payment_method`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_payment_method` (
  `payment_method_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) default NULL,
  `payment_method_name` varchar(255) default NULL,
  `payment_class` varchar(50) NOT NULL default '',
  `shopper_group_id` int(11) default NULL,
  `payment_method_discount` decimal(12,2) default NULL,
  `payment_method_discount_is_percent` tinyint(1) NOT NULL,
  `payment_method_discount_max_amount` decimal(10,2) NOT NULL,
  `payment_method_discount_min_amount` decimal(10,2) NOT NULL,
  `list_order` int(11) default NULL,
  `payment_method_code` varchar(8) default NULL,
  `enable_processor` char(1) default NULL,
  `is_creditcard` tinyint(1) NOT NULL default '0',
  `payment_enabled` char(1) NOT NULL default 'N',
  `accepted_creditcards` varchar(128) NOT NULL default '',
  `payment_extrainfo` text NOT NULL,
  `payment_passkey` blob NOT NULL,
  PRIMARY KEY  (`payment_method_id`),
  KEY `idx_payment_method_vendor_id` (`vendor_id`),
  KEY `idx_payment_method_name` (`payment_method_name`),
  KEY `idx_payment_method_list_order` (`list_order`),
  KEY `idx_payment_method_shopper_group_id` (`shopper_group_id`)
) ENGINE=MyISAM COMMENT='The payment methods of your store';");

## 
## Data for table `#__{vm}_payment_method`
## 

$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (1, 1, 'Purchase Order', '', 6, 0.00, 0, 0.00, 0.00, 4, 'PO', 'N', 0, 'Y', '', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (2, 1, 'Cash On Delivery', '', 5, -2.00, 0, 0.00, 0.00, 5, 'COD', 'N', 0, 'Y', '', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (3, 1, 'Credit Card', 'ps_authorize', 5, 0.00, 0, 0.00, 0.00, 0, 'AN', 'Y', 0, 'Y', '1,2,6,7,', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES( 4, 1, 'PayPal (new API)', 'ps_paypal_api', 5, '0.00', 0, '0.00', '0.00', 0, 'PP_API', 'Y', 1, 'Y', '', '', '')");
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (5, 1, 'PayMate', 'ps_paymate', 5, 0.00, 0, 0.00, 0.00, 0, 'PM', 'P', 0, 'N', '', '<script type=\"text/javascript\" language=\"javascript\">\n  function openExpress(){\n      var url = ''https://www.paymate.com/PayMate/ExpressPayment?mid=<?php echo PAYMATE_USERNAME.\n          \"&amt=\".\$db->f(\"order_total\").\n   \"&currency=\".\$_SESSION[''vendor_currency''].\n       \"&ref=\".\$db->f(\"order_id\").\n      \"&pmt_sender_email=\".\$user->email.\n         \"&pmt_contact_firstname=\".\$user->first_name.\n       \"&pmt_contact_surname=\".\$user->last_name.\n          \"&regindi_address1=\".\$user->address_1.\n     \"&regindi_address2=\".\$user->address_2.\n     \"&regindi_sub=\".\$user->city.\n       \"&regindi_pcode=\".\$user->zip;?>''\n        var newWin = window.open(url, ''wizard'', ''height=640,width=500,scrollbars=0,toolbar=no'');\n  self.name = ''parent'';\n       newWin.focus();\n  }\n  </script>\n  <div align=\"center\">\n  <p>\n  <a href=\"javascript:openExpress();\">\n  <img src=\"https://www.paymate.com/homepage/images/butt_PayNow.gif\" border=\"0\" alt=\"Pay with Paymate Express\">\n  <br />click here to pay your account</a>\n  </p>\n  </div>', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (6, 1, 'WorldPay', 'ps_worldpay', 5, 0.00, 0, 0.00, 0.00, 0, 'WP', 'P', 0, 'N', '', '<form action=\"https://select.worldpay.com/wcc/purchase\" method=\"post\">\n                                                <input type=hidden name=\"testMode\" value=\"100\"> \n                                                  <input type=\"hidden\" name=\"instId\" value=\"<?php echo WORLDPAY_INST_ID ?>\" />\n                                            <input type=\"hidden\" name=\"cartId\" value=\"<?php echo \$db->f(\"order_id\") ?>\" />\n                                               <input type=\"hidden\" name=\"amount\" value=\"<?php echo \$db->f(\"order_total\") ?>\" />\n                                            <input type=\"hidden\" name=\"currency\" value=\"<?php echo \$_SESSION[''vendor_currency''] ?>\" />\n                                           <input type=\"hidden\" name=\"desc\" value=\"Products\" />\n                                            <input type=\"hidden\" name=\"email\" value=\"<?php echo \$user->email?>\" />\n                                                 <input type=\"hidden\" name=\"address\" value=\"<?php echo \$user->address_1?>&#10<?php echo \$user->address_2?>&#10<?php echo\n                                                \$user->city?>&#10<?php echo \$user->state?>\" />\n                                             <input type=\"hidden\" name=\"name\" value=\"<?php echo \$user->title?><?php echo \$user->first_name?>. <?php echo \$user->middle_name?><?php echo \$user->last_name?>\" />\n                                           <input type=\"hidden\" name=\"country\" value=\"<?php echo \$user->country?>\"/>\n                                              <input type=\"hidden\" name=\"postcode\" value=\"<?php echo \$user->zip?>\" />\n                                                <input type=\"hidden\" name=\"tel\"  value=\"<?php echo \$user->phone_1?>\">\n                                                  <input type=\"hidden\" name=\"withDelivery\"  value=\"true\">\n                                                 <br />\n                                                <input type=\"submit\" value =\"PROCEED TO PAYMENT PAGE\" />\n                                                  </form>', '');" );
$db->query( 'INSERT INTO `#__{vm}_payment_method` VALUES (7, 1, \'2Checkout\', \'ps_twocheckout\', 5, \'0.00\', 0, \'0.00\', \'0.00\', 0, \'2CO\', \'P\', 0, \'N\', \'\', \'<?php\n      $q  = "SELECT * FROM #__users WHERE user_info_id=\'\'".$db->f("user_info_id")."\'\'"; \n    $dbbt = new ps_DB;\n   $dbbt->setQuery($q);\n        $dbbt->query();\n      $dbbt->next_record(); \n       // Get ship_to information\n    if( $db->f("user_info_id") != $dbbt->f("user_info_id")) {\n         $q2  = "SELECT * FROM #__vm_user_info WHERE user_info_id=\'\'".$db->f("user_info_id")."\'\'"; \n    $dbst = new ps_DB;\n   $dbst->setQuery($q2);\n       $dbst->query();\n      $dbst->next_record();\n      }\n     else  {\n         $dbst = $dbbt;\n    }\n                     \n      //Authnet vars to send\n        $formdata = array (\n   \'\'x_login\'\' => TWOCO_LOGIN,\n   \'\'x_email_merchant\'\' => ((TWOCO_MERCHANT_EMAIL == \'\'True\'\') ? \'\'TRUE\'\' : \'\'FALSE\'\'),\n                  \n      // Customer Name and Billing Address\n  \'\'x_first_name\'\' => $dbbt->f("first_name"),\n        \'\'x_last_name\'\' => $dbbt->f("last_name"),\n  \'\'x_company\'\' => $dbbt->f("company"),\n      \'\'x_address\'\' => $dbbt->f("address_1"),\n    \'\'x_city\'\' => $dbbt->f("city"),\n    \'\'x_state\'\' => $dbbt->f("state"),\n  \'\'x_zip\'\' => $dbbt->f("zip"),\n      \'\'x_country\'\' => $dbbt->f("country"),\n      \'\'x_phone\'\' => $dbbt->f("phone_1"),\n        \'\'x_fax\'\' => $dbbt->f("fax"),\n      \'\'x_email\'\' => $dbbt->f("email"),\n \n       // Customer Shipping Address\n  \'\'x_ship_to_first_name\'\' => $dbst->f("first_name"),\n        \'\'x_ship_to_last_name\'\' => $dbst->f("last_name"),\n  \'\'x_ship_to_company\'\' => $dbst->f("company"),\n      \'\'x_ship_to_address\'\' => $dbst->f("address_1"),\n    \'\'x_ship_to_city\'\' => $dbst->f("city"),\n    \'\'x_ship_to_state\'\' => $dbst->f("state"),\n  \'\'x_ship_to_zip\'\' => $dbst->f("zip"),\n      \'\'x_ship_to_country\'\' => $dbst->f("country"),\n     \n       \'\'x_invoice_num\'\' => $db->f("order_number"),\n       \'\'x_receipt_link_url\'\' => SECUREURL."2checkout_notify.php"\n  );\n    \n     if( TWOCO_TESTMODE == "Y" )\n   $formdata[\'\'demo\'\'] = "Y";\n       \n       $version = "2";\n    $url = "https://www2.2checkout.com/2co/buyer/purchase";\n    $formdata[\'\'x_amount\'\'] = number_format($db->f("order_total"), 2, \'\'.\'\', \'\'\'\');\n \n       //build the post string\n       $poststring = \'\'\'\';\n  foreach($formdata AS $key => $val){\n          $poststring .= "<input type=\'\'hidden\'\' name=\'\'$key\'\' value=\'\'$val\'\' />\n ";\n    }\n    \n      ?>\n    <form action="<?php echo $url ?>" method="post" target="_blank">\n       <?php echo $poststring ?>\n    <p>Click on the Image below to pay...</p>\n     <input type="image" name="submit" src="https://www.2checkout.com/images/buy_logo.gif" border="0" alt="Make payments with 2Checkout, it\'\'s fast and secure!" title="Pay your Order with 2Checkout, it\'\'s fast and secure!" />\n      </form>\', \'\')' );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (8, 1, 'NoChex', 'ps_nochex', 5, 0.00, 0, 0.00, 0.00, 0, 'NOCHEX', 'P', 0, 'N', '', '<form action=\"https://www.nochex.com/nochex.dll/checkout\" method=\"post\" target=\"_blank\"> \n                                                                                     <input type=\"hidden\" name=\"email\" value=\"<?php echo NOCHEX_EMAIL ?>\" />\n                                                                                 <input type=\"hidden\" name=\"amount\" value=\"<?php printf(\"%.2f\", \$db->f(\"order_total\"))?>\" />\n                                                                                        <input type=\"hidden\" name=\"ordernumber\" value=\"<?php \$db->p(\"order_id\") ?>\" />\n                                                                                       <input type=\"hidden\" name=\"logo\" value=\"<?php echo \$vendor_image_url ?>\" />\n                                                                                    <input type=\"hidden\" name=\"returnurl\" value=\"<?php echo SECUREURL .\"index.php?option=com_virtuemart&amp;page=checkout.result&amp;order_id=\".\$db->f(\"order_id\") ?>\" />\n                                                                                      <input type=\"image\" name=\"submit\" src=\"http://www.nochex.com/web/images/paymeanimated.gif\"> \n                                                                                    </form>', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (9, 1, 'Credit Card (PayMeNow)', 'ps_paymenow', 5, 0.00, 0, 0.00, 0.00, 0, 'PN', 'Y', 0, 'N', '1,2,3,', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (10, 1, 'eWay', 'ps_eway', 5, 0.00, 0, 0.00, 0.00, 0, 'EWAY', 'Y', 0, 'N', '', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (11, 1, 'eCheck.net', 'ps_echeck', 5, 0.00, 0, 0.00, 0.00, 0, 'ECK', 'B', 0, 'N', '', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (12, 1, 'Credit Card (eProcessingNetwork)', 'ps_epn', 5, 0.00, 0, 0.00, 0.00, 0, 'EPN', 'Y', 0, 'N', '1,2,3,', '', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (13, 1, 'iKobo', '', 5, 0.00, 0, 0.00, 0.00, 0, 'IK', 'P', 0, 'N', '', '<form action=\"https://www.iKobo.com/store/index.php\" method=\"post\"> \n  <input type=\"hidden\" name=\"cmd\" value=\"cart\" />Click on the image below to Pay with iKobo\n  <input type=\"image\" src=\"https://www.ikobo.com/merchant/buttons/ikobo_pay1.gif\" name=\"submit\" alt=\"Pay with iKobo\" /> \n  <input type=\"hidden\" name=\"poid\" value=\"USER_ID\" /> \n  <input type=\"hidden\" name=\"item\" value=\"Order: <?php \$db->p(\"order_id\") ?>\" /> \n  <input type=\"hidden\" name=\"price\" value=\"<?php printf(\"%.2f\", \$db->f(\"order_total\"))?>\" /> \n  <input type=\"hidden\" name=\"firstname\" value=\"<?php echo \$user->first_name?>\" /> \n  <input type=\"hidden\" name=\"lastname\" value=\"<?php echo \$user->last_name?>\" /> \n  <input type=\"hidden\" name=\"address\" value=\"<?php echo \$user->address_1?>&#10<?php echo \$user->address_2?>\" /> \n  <input type=\"hidden\" name=\"city\" value=\"<?php echo \$user->city?>\" /> \n  <input type=\"hidden\" name=\"state\" value=\"<?php echo \$user->state?>\" /> \n  <input type=\"hidden\" name=\"zip\" value=\"<?php echo \$user->zip?>\" /> \n  <input type=\"hidden\" name=\"phone\" value=\"<?php echo \$user->phone_1?>\" /> \n  <input type=\"hidden\" name=\"email\" value=\"<?php echo \$user->email?>\" /> \n  </form> >', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (14, 1, 'iTransact', '', 5, 0.00, 0, 0.00, 0.00, 0, 'ITR', 'P', 0, 'N', '', '<?php\n  //your iTransact account details\n  \$vendorID = \"XXXXX\";\n  global \$vendor_name;\n  \$mername = \$vendor_name;\n  \n  //order details\n  \$total = \$db->f(\"order_total\");\$first_name = \$user->first_name;\$last_name = \$user->last_name;\$address = \$user->address_1;\$city = \$user->city;\$state = \$user->state;\$zip = \$user->zip;\$country = \$user->country;\$email = \$user->email;\$phone = \$user->phone_1;\$home_page = \$mosConfig_live_site.\"/index.php\";\$ret_addr = \$mosConfig_live_site.\"/index.php\";\$cc_payment_image = \$mosConfig_live_site.\"/components/com_virtuemart/shop_image/ps_image/cc_payment.jpg\";\n  ?>\n  <form action=\"https://secure.paymentclearing.com/cgi-bin/mas/split.cgi\" method=\"POST\"> \n                <input type=\"hidden\" name=\"vendor_id\" value=\"<?php echo \$vendorID; ?>\" />\n              <input type=\"hidden\" name=\"home_page\" value=\"<?php echo \$home_page; ?>\" />\n             <input type=\"hidden\" name=\"ret_addr\" value=\"<?php echo \$ret_addr; ?>\" />\n               <input type=\"hidden\" name=\"mername\" value=\"<?php echo \$mername; ?>\" />\n         <!--Enter text in the next value that should appear on the bottom of the order form.-->\n               <INPUT type=\"hidden\" name=\"mertext\" value=\"\" />\n         <!--If you are accepting checks, enter the number 1 in the next value.  Enter the number 0 if you are not accepting checks.-->\n                <INPUT type=\"hidden\" name=\"acceptchecks\" value=\"0\" />\n           <!--Enter the number 1 in the next value if you want to allow pre-registered customers to pay with a check.  Enter the number 0 if not.-->\n            <INPUT type=\"hidden\" name=\"allowreg\" value=\"0\" />\n               <!--If you are set up with Check Guarantee, enter the number 1 in the next value.  Enter the number 0 if not.-->\n              <INPUT type=\"hidden\" name=\"checkguar\" value=\"0\" />\n              <!--Enter the number 1 in the next value if you are accepting credit card payments.  Enter the number zero if not.-->\n         <INPUT type=\"hidden\" name=\"acceptcards\" value=\"1\">\n              <!--Enter the number 1 in the next value if you want to allow a separate mailing address for credit card orders.  Enter the number 0 if not.-->\n               <INPUT type=\"hidden\" name=\"altaddr\" value=\"0\" />\n                <!--Enter the number 1 in the next value if you want the customer to enter the CVV number for card orders.  Enter the number 0 if not.-->\n             <INPUT type=\"hidden\" name=\"showcvv\" value=\"1\" />\n                \n              <input type=\"hidden\" name=\"1-desc\" value=\"Order Total\" />\n               <input type=\"hidden\" name=\"1-cost\" value=\"<?php echo \$total; ?>\" />\n            <input type=\"hidden\" name=\"1-qty\" value=\"1\" />\n          <input type=\"hidden\" name=\"total\" value=\"<?php echo \$total; ?>\" />\n             <input type=\"hidden\" name=\"first_name\" value=\"<?php echo \$first_name; ?>\" />\n           <input type=\"hidden\" name=\"last_name\" value=\"<?php echo \$last_name; ?>\" />\n             <input type=\"hidden\" name=\"address\" value=\"<?php echo \$address; ?>\" />\n         <input type=\"hidden\" name=\"city\" value=\"<?php echo \$city; ?>\" />\n               <input type=\"hidden\" name=\"state\" value=\"<?php echo \$state; ?>\" />\n             <input type=\"hidden\" name=\"zip\" value=\"<?php echo \$zip; ?>\" />\n         <input type=\"hidden\" name=\"country\" value=\"<?php echo \$country; ?>\" />\n         <input type=\"hidden\" name=\"phone\" value=\"<?php echo \$phone; ?>\" />\n             <input type=\"hidden\" name=\"email\" value=\"<?php echo \$email; ?>\" />\n             <p><input type=\"image\" alt=\"Process Secure Credit Card Transaction using iTransact\" border=\"0\" height=\"60\" width=\"210\" src=\"<?php echo \$cc_payment_image; ?>\" /> </p>\n            </form>', '');" );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (15, 1, 'Verisign PayFlow Pro', 'payflow_pro', 5, 0.00, 0, 0.00, 0.00, 0, 'PFP', 'Y', 0, 'Y', '1,2,6,7,', '', '');" );
$db->query( 'INSERT INTO `#__{vm}_payment_method` VALUES(16, 1, \'Dankort/PBS via ePay\', \'ps_epay\', 5, 0.00, 0, 0.00, 0.00, 0, \'EPAY\', \'P\', 0, \'Y\', \'\', \'<?php\r\nrequire_once(CLASSPATH ."payment/ps_epay.cfg.php");\r\n$url=basename($mosConfig_live_site);\r\nfunction get_iso_code($code) {\r\nswitch ($code) {\r\ncase "ADP": return "020"; break;\r\ncase "AED": return "784"; break;\r\ncase "AFA": return "004"; break;\r\ncase "ALL": return "008"; break;\r\ncase "AMD": return "051"; break;\r\ncase "ANG": return "532"; break;\r\ncase "AOA": return "973"; break;\r\ncase "ARS": return "032"; break;\r\ncase "AUD": return "036"; break;\r\ncase "AWG": return "533"; break;\r\ncase "AZM": return "031"; break;\r\ncase "BAM": return "977"; break;\r\ncase "BBD": return "052"; break;\r\ncase "BDT": return "050"; break;\r\ncase "BGL": return "100"; break;\r\ncase "BGN": return "975"; break;\r\ncase "BHD": return "048"; break;\r\ncase "BIF": return "108"; break;\r\ncase "BMD": return "060"; break;\r\ncase "BND": return "096"; break;\r\ncase "BOB": return "068"; break;\r\ncase "BOV": return "984"; break;\r\ncase "BRL": return "986"; break;\r\ncase "BSD": return "044"; break;\r\ncase "BTN": return "064"; break;\r\ncase "BWP": return "072"; break;\r\ncase "BYR": return "974"; break;\r\ncase "BZD": return "084"; break;\r\ncase "CAD": return "124"; break;\r\ncase "CDF": return "976"; break;\r\ncase "CHF": return "756"; break;\r\ncase "CLF": return "990"; break;\r\ncase "CLP": return "152"; break;\r\ncase "CNY": return "156"; break;\r\ncase "COP": return "170"; break;\r\ncase "CRC": return "188"; break;\r\ncase "CUP": return "192"; break;\r\ncase "CVE": return "132"; break;\r\ncase "CYP": return "196"; break;\r\ncase "CZK": return "203"; break;\r\ncase "DJF": return "262"; break;\r\ncase "DKK": return "208"; break;\r\ncase "DOP": return "214"; break;\r\ncase "DZD": return "012"; break;\r\ncase "ECS": return "218"; break;\r\ncase "ECV": return "983"; break;\r\ncase "EEK": return "233"; break;\r\ncase "EGP": return "818"; break;\r\ncase "ERN": return "232"; break;\r\ncase "ETB": return "230"; break;\r\ncase "EUR": return "978"; break;\r\ncase "FJD": return "242"; break;\r\ncase "FKP": return "238"; break;\r\ncase "GBP": return "826"; break;\r\ncase "GEL": return "981"; break;\r\ncase "GHC": return "288"; break;\r\ncase "GIP": return "292"; break;\r\ncase "GMD": return "270"; break;\r\ncase "GNF": return "324"; break;\r\ncase "GTQ": return "320"; break;\r\ncase "GWP": return "624"; break;\r\ncase "GYD": return "328"; break;\r\ncase "HKD": return "344"; break;\r\ncase "HNL": return "340"; break;\r\ncase "HRK": return "191"; break;\r\ncase "HTG": return "332"; break;\r\ncase "HUF": return "348"; break;\r\ncase "IDR": return "360"; break;\r\ncase "ILS": return "376"; break;\r\ncase "INR": return "356"; break;\r\ncase "IQD": return "368"; break;\r\ncase "IRR": return "364"; break;\r\ncase "ISK": return "352"; break;\r\ncase "JMD": return "388"; break;\r\ncase "JOD": return "400"; break;\r\ncase "JPY": return "392"; break;\r\ncase "KES": return "404"; break;\r\ncase "KGS": return "417"; break;\r\ncase "KHR": return "116"; break;\r\ncase "KMF": return "174"; break;\r\ncase "KPW": return "408"; break;\r\ncase "KRW": return "410"; break;\r\ncase "KWD": return "414"; break;\r\ncase "KYD": return "136"; break;\r\ncase "KZT": return "398"; break;\r\ncase "LAK": return "418"; break;\r\ncase "LBP": return "422"; break;\r\ncase "LKR": return "144"; break;\r\ncase "LRD": return "430"; break;\r\ncase "LSL": return "426"; break;\r\ncase "LTL": return "440"; break;\r\ncase "LVL": return "428"; break;\r\ncase "LYD": return "434"; break;\r\ncase "MAD": return "504"; break;\r\ncase "MDL": return "498"; break;\r\ncase "MGF": return "450"; break;\r\ncase "MKD": return "807"; break;\r\ncase "MMK": return "104"; break;\r\ncase "MNT": return "496"; break;\r\ncase "MOP": return "446"; break;\r\ncase "MRO": return "478"; break;\r\ncase "MTL": return "470"; break;\r\ncase "MUR": return "480"; break;\r\ncase "MVR": return "462"; break;\r\ncase "MWK": return "454"; break;\r\ncase "MXN": return "484"; break;\r\ncase "MXV": return "979"; break;\r\ncase "MYR": return "458"; break;\r\ncase "MZM": return "508"; break;\r\ncase "NAD": return "516"; break;\r\ncase "NGN": return "566"; break;\r\ncase "NIO": return "558"; break;\r\ncase "NOK": return "578"; break;\r\ncase "NPR": return "524"; break;\r\ncase "NZD": return "554"; break;\r\ncase "OMR": return "512"; break;\r\ncase "PAB": return "590"; break;\r\ncase "PEN": return "604"; break;\r\ncase "PGK": return "598"; break;\r\ncase "PHP": return "608"; break;\r\ncase "PKR": return "586"; break;\r\ncase "PLN": return "985"; break;\r\ncase "PYG": return "600"; break;\r\ncase "QAR": return "634"; break;\r\ncase "ROL": return "642"; break;\r\ncase "RUB": return "643"; break;\r\ncase "RUR": return "810"; break;\r\ncase "RWF": return "646"; break;\r\ncase "SAR": return "682"; break;\r\ncase "SBD": return "090"; break;\r\ncase "SCR": return "690"; break;\r\ncase "SDD": return "736"; break;\r\ncase "SEK": return "752"; break;\r\ncase "SGD": return "702"; break;\r\ncase "SHP": return "654"; break;\r\ncase "SIT": return "705"; break;\r\ncase "SKK": return "703"; break;\r\ncase "SLL": return "694"; break;\r\ncase "SOS": return "706"; break;\r\ncase "SRG": return "740"; break;\r\ncase "STD": return "678"; break;\r\ncase "SVC": return "222"; break;\r\ncase "SYP": return "760"; break;\r\ncase "SZL": return "748"; break;\r\ncase "THB": return "764"; break;\r\ncase "TJS": return "972"; break;\r\ncase "TMM": return "795"; break;\r\ncase "TND": return "788"; break;\r\ncase "TOP": return "776"; break;\r\ncase "TPE": return "626"; break;\r\ncase "TRL": return "792"; break;\r\ncase "TRY": return "949"; break;\r\ncase "TTD": return "780"; break;\r\ncase "TWD": return "901"; break;\r\ncase "TZS": return "834"; break;\r\ncase "UAH": return "980"; break;\r\ncase "UGX": return "800"; break;\r\ncase "USD": return "840"; break;\r\ncase "UYU": return "858"; break;\r\ncase "UZS": return "860"; break;\r\ncase "VEB": return "862"; break;\r\ncase "VND": return "704"; break;\r\ncase "VUV": return "548"; break;\r\ncase "XAF": return "950"; break;\r\ncase "XCD": return "951"; break;\r\ncase "XOF": return "952"; break;\r\ncase "XPF": return "953"; break;\r\ncase "YER": return "886"; break;\r\ncase "YUM": return "891"; break;\r\ncase "ZAR": return "710"; break;\r\ncase "ZMK": return "894"; break;\r\ncase "ZWD": return "716"; break;\r\n}\r\nreturn "XXX"; // return invalid code if the currency is not found \r\n}\r\n\r\nfunction calculateePayCurrency($order_id)\r\n{\r\n$db = new ps_DB;\r\n$currency_code = "208";\r\n$q = "SELECT order_currency FROM #__vm_orders where order_id = " . $order_id;\r\n$db->query($q);\r\nif ($db->next_record()) {\r\n	$currency_code = get_iso_code($db->f("order_currency"));\r\n}\r\nreturn $currency_code;\r\n}\r\n echo $VM_LANG->_(\'\'VM_CHECKOUT_EPAY_PAYMENT_CHECKOUT_HEADER\'\');\r\n?>\r\n<script type="text/javascript" src="http://www.epay.dk/js/standardwindow.js"></script>\r\n<script type="text/javascript">\r\nfunction printCard(cardId)\r\n{\r\ndocument.write ("<table border=0 cellspacing=10 cellpadding=10>");\r\nswitch (cardId) {\r\ncase 1: document.write ("<input type=hidden name=cardtype value=1>"); break;\r\ncase 2: document.write ("<input type=hidden name=cardtype value=2>"); break;\r\ncase 3: document.write ("<input type=hidden name=cardtype value=3>"); break;\r\ncase 4: document.write ("<input type=hidden name=cardtype value=4>"); break;\r\ncase 5: document.write ("<input type=hidden name=cardtype value=5>"); break;\r\ncase 6: document.write ("<input type=hidden name=cardtype value=6>"); break;\r\ncase 7: document.write ("<input type=hidden name=cardtype value=7>"); break;\r\ncase 8: document.write ("<input type=hidden name=cardtype value=8>"); break;\r\ncase 9: document.write ("<input type=hidden name=cardtype value=9>"); break;\r\ncase 10: document.write ("<input type=hidden name=cardtype value=10>"); break;\r\ncase 12: document.write ("<input type=hidden name=cardtype value=12>"); break;\r\ncase 13: document.write ("<input type=hidden name=cardtype value=13>"); break;\r\ncase 14: document.write ("<input type=hidden name=cardtype value=14>"); break;\r\ncase 15: document.write ("<input type=hidden name=cardtype value=15>"); break;\r\ncase 16: document.write ("<input type=hidden name=cardtype value=16>"); break;\r\ncase 17: document.write ("<input type=hidden name=cardtype value=17>"); break;\r\ncase 18: document.write ("<input type=hidden name=cardtype value=18>"); break;\r\ncase 19: document.write ("<input type=hidden name=cardtype value=19>"); break;\r\ncase 21: document.write ("<input type=hidden name=cardtype value=21>"); break;\r\ncase 22: document.write ("<input type=hidden name=cardtype value=22>"); break;\r\n}\r\ndocument.write ("</table>");\r\n}\r\n</script>\r\n<form action="https://ssl.ditonlinebetalingssystem.dk/popup/default.asp" method="post" name="ePay" target="ePay_window" id="ePay">\r\n<input type="hidden" name="merchantnumber" value="<?php echo EPAY_MERCHANTNUMBER ?>">\r\n<input type="hidden" name="amount" value="<?php echo round($db->f("order_total")*100, 2 ) ?>">\r\n<input type="hidden" name="currency" value="<?php echo calculateePayCurrency($order_id)?>">\r\n<input type="hidden" name="orderid" value="<?php echo $order_id ?>">\r\n<input type="hidden" name="ordretext" value="">\r\n<?php \r\nif (EPAY_CALLBACK == "1")\r\n{\r\n	echo \'\'<input type="hidden" name="callbackurl" value="\'\' . $mosConfig_live_site . \'\'/index.php?page=checkout.epay_result&accept=1&sessionid=\'\' . $sessionid . \'\'&option=com_virtuemart&Itemid=1">\'\';\r\n}\r\n?>\r\n<input type="hidden" name="accepturl" value="<?php echo $mosConfig_live_site ?>/index.php?page=checkout.epay_result&accept=1&sessionid=<?php echo $sessionid ?>&option=com_virtuemart&Itemid=1">\r\n<input type="hidden" name="declineurl" value="<?php echo $mosConfig_live_site ?>/index.php?page=checkout.epay_result&accept=0&sessionid=<?php echo $sessionid ?>&option=com_virtuemart&Itemid=1">\r\n<input type="hidden" name="group" value="<?php echo EPAY_GROUP ?>">\r\n<input type="hidden" name="instant" value="<?php echo EPAY_INSTANT_CAPTURE ?>">\r\n<input type="hidden" name="language" value="<?php echo EPAY_LANGUAGE ?>">\r\n<input type="hidden" name="authsms" value="<?php echo EPAY_AUTH_SMS ?>">\r\n<input type="hidden" name="authmail" value="<?php echo EPAY_AUTH_MAIL . (strlen(EPAY_AUTH_MAIL) > 0 && EPAY_AUTHEMAILCUSTOMER == 1 ? ";" : "") . (EPAY_AUTHEMAILCUSTOMER == 1 ? $user->user_email : ""); ?>">\r\n<input type="hidden" name="windowstate" value="<?php echo EPAY_WINDOW_STATE ?>">\r\n<input type="hidden" name="use3D" value="<?php echo EPAY_3DSECURE ?>">\r\n<input type="hidden" name="addfee" value="<?php echo EPAY_ADDFEE ?>">\r\n<input type="hidden" name="subscription" value="<?php echo EPAY_SUBSCRIPTION ?>">\r\n<input type="hidden" name="MD5Key" value="<?php if (EPAY_MD5_TYPE == 2) echo md5( calculateePayCurrency($order_id) . round($db->f("order_total")*100, 2 ) . $order_id  . EPAY_MD5_KEY)?>">\r\n<?php\r\nif (EPAY_CARDTYPES_1 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(1)</script>";\r\nif (EPAY_CARDTYPES_2 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(2)</script>";\r\nif (EPAY_CARDTYPES_3 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(3)</script>";\r\nif (EPAY_CARDTYPES_4 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(4)</script>";\r\nif (EPAY_CARDTYPES_5 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(5)</script>";\r\nif (EPAY_CARDTYPES_6 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(6)</script>";\r\nif (EPAY_CARDTYPES_7 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(7)</script>";\r\nif (EPAY_CARDTYPES_8 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(8)</script>";\r\nif (EPAY_CARDTYPES_9 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(9)</script>";\r\nif (EPAY_CARDTYPES_10 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(10)</script>";\r\nif (EPAY_CARDTYPES_11 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(11)</script>";\r\nif (EPAY_CARDTYPES_12 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(12)</script>";\r\nif (EPAY_CARDTYPES_14 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(14)</script>";\r\nif (EPAY_CARDTYPES_15 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(15)</script>";\r\nif (EPAY_CARDTYPES_16 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(16)</script>";\r\nif (EPAY_CARDTYPES_17 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(17)</script>";\r\nif (EPAY_CARDTYPES_18 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(18)</script>";\r\nif (EPAY_CARDTYPES_19 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(19)</script>";\r\nif (EPAY_CARDTYPES_21 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(21)</script>";\r\nif (EPAY_CARDTYPES_22 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(22)</script>";;\r\n?>\r\n</form>\r\n<script>open_ePay_window();</script>\r\n<br>\r\n<table border="0" width="100%"><tr><td><input type="button" onClick="open_ePay_window()" value="<?php echo $VM_LANG->_(\'\'VM_CHECKOUT_EPAY_BUTTON_OPEN_WINDOW\'\') ?>"></td><td width="100%" id="flashLoader"></td></tr></table><br><br><br>\r\n<?php echo $VM_LANG->_(\'\'VM_CHECKOUT_EPAY_PAYMENT_CHECKOUT_FOOTER\'\') ?>\r\n<br><br>\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/epay_logo.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/mastercard_securecode.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/pci.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/verisign_secure.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/visa_secure.gif" border="0">&nbsp;&nbsp;&nbsp;;\', \'\')' );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (17, 1, 'PaySbuy', 'ps_paysbuy', 5, 0.00, 0, 0.00, 0.00, 0, 'PSB', 'P', 0, 'N', '', '', '');" );
$db->query( 'INSERT INTO `#__{vm}_payment_method` VALUES (18, 1, \'PayPal (Legacy)\', \'ps_paypal\', 5, 0.00, 0, 0.00, 0.00, 0, \'PP\', \'P\', 0, \'Y\', \'\', \'<?php\r\n$db1 = new ps_DB();\r\n$q = "SELECT country_2_code FROM #__vm_country WHERE country_3_code=\'\'".$user->country."\'\' ORDER BY country_2_code ASC";\r\n$db1->query($q);\r\n\r\n$url = "https://www.paypal.com/cgi-bin/webscr";\r\n$tax_total = $db->f("order_tax") + $db->f("order_shipping_tax");\r\n$discount_total = $db->f("coupon_discount") + $db->f("order_discount");\r\n$post_variables = Array(\r\n"cmd" => "_ext-enter",\r\n"redirect_cmd" => "_xclick",\r\n"upload" => "1",\r\n"business" => PAYPAL_EMAIL,\r\n"receiver_email" => PAYPAL_EMAIL,\r\n"item_name" => $VM_LANG->_(\'\'PHPSHOP_ORDER_PRINT_PO_NUMBER\'\').": ". $db->f("order_id"),\r\n"order_id" => $db->f("order_id"),\r\n"invoice" => $db->f("order_number"),\r\n"amount" => round( $db->f("order_total")-$db->f("order_shipping"), 2),\r\n"shipping" => sprintf("%.2f", $db->f("order_shipping")),\r\n"currency_code" => $_SESSION[\'\'vendor_currency\'\'],\r\n\r\n"address_override" => "1",\r\n"first_name" => $dbbt->f(\'\'first_name\'\'),\r\n"last_name" => $dbbt->f(\'\'last_name\'\'),\r\n"address1" => $dbbt->f(\'\'address_1\'\'),\r\n"address2" => $dbbt->f(\'\'address_2\'\'),\r\n"zip" => $dbbt->f(\'\'zip\'\'),\r\n"city" => $dbbt->f(\'\'city\'\'),\r\n"state" => $dbbt->f(\'\'state\'\'),\r\n"country" => $db1->f(\'\'country_2_code\'\'),\r\n"email" => $dbbt->f(\'\'user_email\'\'),\r\n"night_phone_b" => $dbbt->f(\'\'phone_1\'\'),\r\n"cpp_header_image" => $vendor_image_url,\r\n\r\n"return" => SECUREURL ."index.php?option=com_virtuemart&page=checkout.result&order_id=".$db->f("order_id"),\r\n"notify_url" => SECUREURL ."administrator/components/com_virtuemart/notify.php",\r\n"cancel_return" => SECUREURL ."index.php",\r\n"undefined_quantity" => "0",\r\n\r\n"test_ipn" => PAYPAL_DEBUG,\r\n"pal" => "NRUBJXESJTY24",\r\n"no_shipping" => "1",\r\n"no_note" => "1"\r\n);\r\nif( $page == "checkout.thankyou" ) {\r\n$query_string = "?";\r\nforeach( $post_variables as $name => $value ) {\r\n$query_string .= $name. "=" . urlencode($value) ."&";\r\n}\r\nvmRedirect( $url . $query_string );\r\n} else {\r\necho \'\'<form action="\'\'.$url.\'\'" method="post" target="_blank">\'\';\r\necho \'\'<input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/x-click-but6.gif" alt="Click to pay with PayPal - it is fast, free and secure!" />\'\';\r\n\r\nforeach( $post_variables as $name => $value ) {\r\necho \'\'<input type="hidden" name="\'\'.$name.\'\'" value="\'\'.htmlspecialchars($value).\'\'" />\'\';\r\n}\r\necho \'\'</form>\'\';\r\n\r\n}\r\n?>\', \'\');' );
$db->query( "INSERT INTO `#__{vm}_payment_method` VALUES (19, 1, 'MerchantWarrior', 'ps_merchantwarrior', 5, '0.00', 0, '0.00', '0.00', 1, 'MW', 'Y', 1, 'Y', '1,2,3,5,7,', '', '');" );
## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product` (
  `product_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) NOT NULL default '0',
  `product_parent_id` int(11) NOT NULL default '0',
  `product_sku` varchar(64) NOT NULL default '',
  `product_s_desc` varchar(255) default NULL,
  `product_desc` text,
  `product_thumb_image` varchar(255) default NULL,
  `product_full_image` varchar(255) default NULL,
  `product_publish` char(1) default NULL,
  `product_weight` decimal(10,4) default NULL,
  `product_weight_uom` varchar(32) default 'pounds.',
  `product_length` decimal(10,4) default NULL,
  `product_width` decimal(10,4) default NULL,
  `product_height` decimal(10,4) default NULL,
  `product_lwh_uom` varchar(32) default 'inches',
  `product_url` varchar(255) default NULL,
  `product_in_stock` int(11) NOT NULL default '0',
  `product_available_date` int(11) default NULL,
  `product_availability` varchar(56) NOT NULL default '',
  `product_special` char(1) default NULL,
  `product_discount_id` int(11) default NULL,
  `ship_code_id` int(11) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `product_name` varchar(64) default NULL,
  `product_sales` int(11) NOT NULL default '0',
  `attribute` text,
  `custom_attribute` text NOT NULL,
  `product_tax_id` int(11) default NULL,
  `product_unit` varchar(32) default NULL,
  `product_packaging` int(11) default NULL,
  `child_options` varchar(45) default NULL,
  `quantity_options` varchar(45) default NULL,
  `child_option_ids` varchar(45) default NULL,
  `product_order_levels` varchar(45) default NULL,
  PRIMARY KEY  (`product_id`),
  KEY `idx_product_vendor_id` (`vendor_id`),
  KEY `idx_product_product_parent_id` (`product_parent_id`),
  KEY `idx_product_sku` (`product_sku`),
  KEY `idx_product_ship_code_id` (`ship_code_id`),
  KEY `idx_product_name` (`product_name`)
) ENGINE=MyISAM COMMENT='All products are stored here.'; ");

## 
## Dumping data for table `#__{vm}_product`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_attribute`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_attribute` (
  `attribute_id` INT NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `attribute_name` char(255) NOT NULL default '',
  `attribute_value` char(255) NOT NULL default '',
  PRIMARY KEY  (`attribute_id`),
  KEY `idx_product_attribute_product_id` (`product_id`),
  KEY `idx_product_attribute_name` (`attribute_name`)
) ENGINE=MyISAM COMMENT='Stores attributes + their specific values for Child Products'; ");

## 
## Dumping data for table `#__{vm}_product_attribute`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_attribute_sku`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_attribute_sku` (
  `product_id` int(11) NOT NULL default '0',
  `attribute_name` char(255) NOT NULL default '',
  `attribute_list` int(11) NOT NULL default '0',
  KEY `idx_product_attribute_sku_product_id` (`product_id`),
  KEY `idx_product_attribute_sku_attribute_name` (`attribute_name`),
  KEY `idx_product_attribute_list` (`attribute_list`)
) ENGINE=MyISAM COMMENT='Attributes for a Parent Product used by its Child Products'; ");

## 
## Dumping data for table `#__{vm}_product_attribute_sku`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_category_xref`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_category_xref` (
  `category_id` int(11) NOT NULL default '0',
  `product_id` int(11) NOT NULL default '0',
  `product_list` int(11) default NULL,
  KEY `idx_product_category_xref_category_id` (`category_id`),
  KEY `idx_product_category_xref_product_id` (`product_id`),
  KEY `idx_product_category_xref_product_list` (`product_list`)
) ENGINE=MyISAM COMMENT='Maps Products to Categories'; ");

## 
## Dumping data for table `#__{vm}_product_category_xref`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_discount`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_discount` (
  `discount_id` int(11) NOT NULL auto_increment,
  `amount` decimal(12,2) NOT NULL default '0.00',
  `is_percent` tinyint(1) NOT NULL default '0',
  `start_date` int(11) NOT NULL default '0',
  `end_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`discount_id`)
) ENGINE=MyISAM COMMENT='Discounts that can be assigned to products'; ");

## 
## Dumping data for table `#__{vm}_product_discount`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_download`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_download` (
  `product_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `order_id` int(11) NOT NULL default '0',
  `end_date` int(11) NOT NULL default '0',
  `download_max` int(11) NOT NULL default '0',
  `download_id` varchar(32) NOT NULL default '',
  `file_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`download_id`)
) ENGINE=MyISAM COMMENT='Active downloads for selling downloadable goods'; ");

## 
## Dumping data for table `#__{vm}_product_download`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_files`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_files` (
  `file_id` int(19) NOT NULL auto_increment,
  `file_product_id` int(11) NOT NULL default '0',
  `file_name` varchar(128) NOT NULL default '',
  `file_title` varchar(128) NOT NULL default '',
  `file_description` mediumtext NOT NULL,
  `file_extension` varchar(128) NOT NULL default '',
  `file_mimetype` varchar(64) NOT NULL default '',
  `file_url` varchar(254) NOT NULL default '',
  `file_published` tinyint(1) NOT NULL default '0',
  `file_is_image` tinyint(1) NOT NULL default '0',
  `file_image_height` int(11) NOT NULL default '0',
  `file_image_width` int(11) NOT NULL default '0',
  `file_image_thumb_height` int(11) NOT NULL default '50',
  `file_image_thumb_width` int(11) NOT NULL default '0',
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM COMMENT='Additional Images and Files which are assigned to products'; ");

## 
## Dumping data for table `#__{vm}_product_files`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_mf_xref`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_mf_xref` (
  `product_id` int(11) default NULL,
  `manufacturer_id` int(11) default NULL,
  KEY `idx_product_mf_xref_product_id` (`product_id`),
  KEY `idx_product_mf_xref_manufacturer_id` (`manufacturer_id`)
) ENGINE=MyISAM COMMENT='Maps a product to a manufacturer'; ");

## 
## Dumping data for table `#__{vm}_product_mf_xref`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_price`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_price` (
  `product_price_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `product_price` decimal(12,5) default NULL,
  `product_currency` char(16) default NULL,
  `product_price_vdate` int(11) default NULL,
  `product_price_edate` int(11) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `shopper_group_id` int(11) default NULL,
  `price_quantity_start` int(11) unsigned NOT NULL default '0',
  `price_quantity_end` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`product_price_id`),
  KEY `idx_product_price_product_id` (`product_id`),
  KEY `idx_product_price_shopper_group_id` (`shopper_group_id`)
) ENGINE=MyISAM COMMENT='Holds price records for a product'; ");

## 
## Dumping data for table `#__{vm}_product_price`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_product_type_xref`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_product_type_xref` (
  `product_id` int(11) NOT NULL default '0',
  `product_type_id` int(11) NOT NULL default '0',
  KEY `idx_product_product_type_xref_product_id` (`product_id`),
  KEY `idx_product_product_type_xref_product_type_id` (`product_type_id`)
) ENGINE=MyISAM COMMENT='Maps products to a product type'; ");

## 
## Dumping data for table `#__{vm}_product_product_type_xref`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_relations`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_relations` (
  `product_id` int(11) NOT NULL default '0',
  `related_products` text,
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM;");

## 
## Dumping data for table `#__{vm}_product_relations`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_reviews`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_reviews` (
  `review_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `comment` text NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `user_rating` tinyint(1) NOT NULL default '0',
  `review_ok` int(11) NOT NULL default '0',
  `review_votes` int(11) NOT NULL default '0',
  `published` char(1) NOT NULL default 'Y',
  PRIMARY KEY  (`review_id`),
  UNIQUE KEY `product_id` (`product_id`,`userid`)
) ENGINE=MyISAM ;");

## 
## Dumping data for table `#__{vm}_product_reviews`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_type`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_type` (
  `product_type_id` int(11) NOT NULL auto_increment,
  `product_type_name` varchar(255) NOT NULL default '',
  `product_type_description` text,
  `product_type_publish` char(1) default NULL,
  `product_type_browsepage` varchar(255) default NULL,
  `product_type_flypage` varchar(255) default NULL,
  `product_type_list_order` int(11) default NULL,
  PRIMARY KEY  (`product_type_id`)
) ENGINE=MyISAM ;");

## 
## Dumping data for table `#__{vm}_product_type`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_type_parameter`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_type_parameter` (
  `product_type_id` int(11) NOT NULL default '0',
  `parameter_name` varchar(255) NOT NULL default '',
  `parameter_label` varchar(255) NOT NULL default '',
  `parameter_description` text,
  `parameter_list_order` int(11) NOT NULL default '0',
  `parameter_type` char(1) NOT NULL default 'T',
  `parameter_values` varchar(255) default NULL,
  `parameter_multiselect` char(1) default NULL,
  `parameter_default` varchar(255) default NULL,
  `parameter_unit` varchar(32) default NULL,
  PRIMARY KEY  (`product_type_id`,`parameter_name`),
  KEY `idx_product_type_parameter_product_type_id` (`product_type_id`),
  KEY `idx_product_type_parameter_parameter_order` (`parameter_list_order`)
) ENGINE=MyISAM COMMENT='Parameters which are part of a product type'; ");

## 
## Dumping data for table `#__{vm}_product_type_parameter`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_product_votes`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_product_votes` (
  `product_id` int(255) NOT NULL default '0',
  `votes` text NOT NULL,
  `allvotes` int(11) NOT NULL default '0',
  `rating` tinyint(1) NOT NULL default '0',
  `lastip` varchar(50) NOT NULL default '0',
  PRIMARY KEY ( `product_id` )
) ENGINE=MyISAM COMMENT='Stores all votes for a product'; ");

## 
## Dumping data for table `#__{vm}_product_votes`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_shipping_carrier`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_shipping_carrier` (
  `shipping_carrier_id` int(11) NOT NULL auto_increment,
  `shipping_carrier_name` char(80) NOT NULL default '',
  `shipping_carrier_list_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shipping_carrier_id`)
) ENGINE=MyISAM COMMENT='Shipping Carriers as used by the Standard Shipping Module'; ");

## 
## Dumping data for table `#__{vm}_shipping_carrier`
## 

# DHL integration
$db->query( "CREATE TABLE IF NOT EXISTS `#__vm_shipping_label` (
	`order_id` int(11) NOT NULL default '0',
	`shipper_class` varchar(32) default NULL,
	`ship_date` varchar(32) default NULL,
	`service_code` varchar(32) default NULL,
	`special_service` varchar(32) default NULL,
	`package_type` varchar(16) default NULL,
	`order_weight` decimal(10,2) default NULL,
	`is_international` tinyint(1) default NULL,
	`additional_protection_type` varchar(16) default NULL,
	`additional_protection_value` decimal(10,2) default NULL,
	`duty_value` decimal(10,2) default NULL,
	`content_desc` varchar(255) default NULL,
	`label_is_generated` tinyint(1) NOT NULL default '0',
	`tracking_number` varchar(32) default NULL,
	`label_image` blob default NULL,
	`have_signature` tinyint(1) NOT NULL default '0',
	`signature_image` blob default NULL,
	PRIMARY KEY (`order_id`)
) ENGINE=MyISAM COMMENT='Stores information used in generating shipping labels'; ");


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_shipping_rate`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_shipping_rate` (
  `shipping_rate_id` int(11) NOT NULL auto_increment,
  `shipping_rate_name` varchar(255) NOT NULL default '',
  `shipping_rate_carrier_id` int(11) NOT NULL default '0',
  `shipping_rate_country` text NOT NULL,
  `shipping_rate_zip_start` varchar(32) NOT NULL default '',
  `shipping_rate_zip_end` varchar(32) NOT NULL default '',
  `shipping_rate_weight_start` decimal(10,3) NOT NULL default '0.000',
  `shipping_rate_weight_end` decimal(10,3) NOT NULL default '0.000',
  `shipping_rate_value` decimal(10,2) NOT NULL default '0.00',
  `shipping_rate_package_fee` decimal(10,2) NOT NULL default '0.00',
  `shipping_rate_currency_id` int(11) NOT NULL default '0',
  `shipping_rate_vat_id` int(11) NOT NULL default '0',
  `shipping_rate_list_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`shipping_rate_id`)
) ENGINE=MyISAM COMMENT='Shipping Rates, used by the Standard Shipping Module'; ");

## 
## Dumping data for table `#__{vm}_shipping_rate`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_shopper_group`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_shopper_group` (
  `shopper_group_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) default NULL,
  `shopper_group_name` varchar(32) default NULL,
  `shopper_group_desc` text,
  `shopper_group_discount` decimal(5,2) NOT NULL default '0.00',
  `show_price_including_tax` tinyint(1) NOT NULL default '1',
  `default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`shopper_group_id`),
  KEY `idx_shopper_group_vendor_id` (`vendor_id`),
  KEY `idx_shopper_group_name` (`shopper_group_name`)
) ENGINE=MyISAM COMMENT='Shopper Groups that users can be assigned to'; ");

## 
## Dumping data for table `#__{vm}_shopper_group`
## 

$db->query( "INSERT INTO `#__{vm}_shopper_group` VALUES (5, 1, '-default-', 'This is the default shopper group.', 0.00, 1, 1),
(6, 1, 'Gold Level', 'Gold Level Shoppers.', 0.00, 1, 0),
(7, 1, 'Wholesale', 'Shoppers that can buy at wholesale.', 0.00, 0, 0);" );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_shopper_vendor_xref`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_shopper_vendor_xref` (
  `user_id` int(11) default NULL,
  `vendor_id` int(11) default NULL,
  `shopper_group_id` int(11) default NULL,
  `customer_number` varchar(32) default NULL,
  KEY `idx_shopper_vendor_xref_user_id` (`user_id`),
  KEY `idx_shopper_vendor_xref_vendor_id` (`vendor_id`),
  KEY `idx_shopper_vendor_xref_shopper_group_id` (`shopper_group_id`)
) ENGINE=MyISAM COMMENT='Maps a user to a Shopper Group of a Vendor'; ");

## 
## Dumping data for table `#__{vm}_shopper_vendor_xref`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_state`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_state` (
  `state_id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '1',
  `state_name` varchar(64) default NULL,
  `state_3_code` char(3) default NULL,
  `state_2_code` char(2) default NULL,
  PRIMARY KEY  (`state_id`),
  UNIQUE KEY `state_3_code` (`country_id`,`state_3_code`),
  UNIQUE KEY `state_2_code` (`country_id`,`state_2_code`),
  KEY `idx_country_id` (`country_id`)
) ENGINE=MyISAM COMMENT='States that are assigned to a country'; ");

## 
## Dumping data for table `#__{vm}_state`
## 

$db->query( "INSERT INTO `#__{vm}_state` VALUES (1, 223, 'Alabama', 'ALA', 'AL'),
(2, 223, 'Alaska', 'ALK', 'AK'),
(3, 223, 'Arizona', 'ARZ', 'AZ'),
(4, 223, 'Arkansas', 'ARK', 'AR'),
(5, 223, 'California', 'CAL', 'CA'),
(6, 223, 'Colorado', 'COL', 'CO'),
(7, 223, 'Connecticut', 'CCT', 'CT'),
(8, 223, 'Delaware', 'DEL', 'DE'),
(9, 223, 'District Of Columbia', 'DOC', 'DC'),
(10, 223, 'Florida', 'FLO', 'FL'),
(11, 223, 'Georgia', 'GEA', 'GA'),
(12, 223, 'Hawaii', 'HWI', 'HI'),
(13, 223, 'Idaho', 'IDA', 'ID'),
(14, 223, 'Illinois', 'ILL', 'IL'),
(15, 223, 'Indiana', 'IND', 'IN'),
(16, 223, 'Iowa', 'IOA', 'IA'),
(17, 223, 'Kansas', 'KAS', 'KS'),
(18, 223, 'Kentucky', 'KTY', 'KY'),
(19, 223, 'Louisiana', 'LOA', 'LA'),
(20, 223, 'Maine', 'MAI', 'ME'),
(21, 223, 'Maryland', 'MLD', 'MD'),
(22, 223, 'Massachusetts', 'MSA', 'MA'),
(23, 223, 'Michigan', 'MIC', 'MI'),
(24, 223, 'Minnesota', 'MIN', 'MN'),
(25, 223, 'Mississippi', 'MIS', 'MS'),
(26, 223, 'Missouri', 'MIO', 'MO'),
(27, 223, 'Montana', 'MOT', 'MT'),
(28, 223, 'Nebraska', 'NEB', 'NE'),
(29, 223, 'Nevada', 'NEV', 'NV'),
(30, 223, 'New Hampshire', 'NEH', 'NH'),
(31, 223, 'New Jersey', 'NEJ', 'NJ'),
(32, 223, 'New Mexico', 'NEM', 'NM'),
(33, 223, 'New York', 'NEY', 'NY'),
(34, 223, 'North Carolina', 'NOC', 'NC'),
(35, 223, 'North Dakota', 'NOD', 'ND'),
(36, 223, 'Ohio', 'OHI', 'OH'),
(37, 223, 'Oklahoma', 'OKL', 'OK'),
(38, 223, 'Oregon', 'ORN', 'OR'),
(39, 223, 'Pennsylvania', 'PEA', 'PA'),
(40, 223, 'Rhode Island', 'RHI', 'RI'),
(41, 223, 'South Carolina', 'SOC', 'SC'),
(42, 223, 'South Dakota', 'SOD', 'SD'),
(43, 223, 'Tennessee', 'TEN', 'TN'),
(44, 223, 'Texas', 'TXS', 'TX'),
(45, 223, 'Utah', 'UTA', 'UT'),
(46, 223, 'Vermont', 'VMT', 'VT'),
(47, 223, 'Virginia', 'VIA', 'VA'),
(48, 223, 'Washington', 'WAS', 'WA'),
(49, 223, 'West Virginia', 'WEV', 'WV'),
(50, 223, 'Wisconsin', 'WIS', 'WI'),
(51, 223, 'Wyoming', 'WYO', 'WY'),
(52, 38, 'Alberta', 'ALB', 'AB'),
(53, 38, 'British Columbia', 'BRC', 'BC'),
(54, 38, 'Manitoba', 'MAB', 'MB'),
(55, 38, 'New Brunswick', 'NEB', 'NB'),
(56, 38, 'Newfoundland and Labrador', 'NFL', 'NL'),
(57, 38, 'Northwest Territories', 'NWT', 'NT'),
(58, 38, 'Nova Scotia', 'NOS', 'NS'),
(59, 38, 'Nunavut', 'NUT', 'NU'),
(60, 38, 'Ontario', 'ONT', 'ON'),
(61, 38, 'Prince Edward Island', 'PEI', 'PE'),
(62, 38, 'Quebec', 'QEC', 'QC'),
(63, 38, 'Saskatchewan', 'SAK', 'SK'),
(64, 38, 'Yukon', 'YUT', 'YT'),
(65, 222, 'England', 'ENG', 'EN'),
(66, 222, 'Northern Ireland', 'NOI', 'NI'),
(67, 222, 'Scotland', 'SCO', 'SD'),
(68, 222, 'Wales', 'WLS', 'WS'),
(69, 13, 'Australian Capital Territory', 'ACT', 'AC'),
(70, 13, 'New South Wales', 'NSW', 'NS'),
(71, 13, 'Northern Territory', 'NOT', 'NT'),
(72, 13, 'Queensland', 'QLD', 'QL'),
(73, 13, 'South Australia', 'SOA', 'SA'),
(74, 13, 'Tasmania', 'TAS', 'TS'),
(75, 13, 'Victoria', 'VIC', 'VI'),
(76, 13, 'Western Australia', 'WEA', 'WA'),
(77, 138, 'Aguascalientes', 'AGS', 'AG'),
(78, 138, 'Baja California Norte', 'BCN', 'BN'),
(79, 138, 'Baja California Sur', 'BCS', 'BS'),
(80, 138, 'Campeche', 'CAM', 'CA'),
(81, 138, 'Chiapas', 'CHI', 'CS'),
(82, 138, 'Chihuahua', 'CHA', 'CH'),
(83, 138, 'Coahuila', 'COA', 'CO'),
(84, 138, 'Colima', 'COL', 'CM'),
(85, 138, 'Distrito Federal', 'DFM', 'DF'),
(86, 138, 'Durango', 'DGO', 'DO'),
(87, 138, 'Guanajuato', 'GTO', 'GO'),
(88, 138, 'Guerrero', 'GRO', 'GU'),
(89, 138, 'Hidalgo', 'HGO', 'HI'),
(90, 138, 'Jalisco', 'JAL', 'JA'),
(91, 138, 'México (Estado de)', 'EDM', 'EM'),
(92, 138, 'Michoacán', 'MCN', 'MI'),
(93, 138, 'Morelos', 'MOR', 'MO'),
(94, 138, 'Nayarit', 'NAY', 'NY'),
(95, 138, 'Nuevo León', 'NUL', 'NL'),
(96, 138, 'Oaxaca', 'OAX', 'OA'),
(97, 138, 'Puebla', 'PUE', 'PU'),
(98, 138, 'Querétaro', 'QRO', 'QU'),
(99, 138, 'Quintana Roo', 'QUR', 'QR'),
(100, 138, 'San Luis Potosí', 'SLP', 'SP'),
(101, 138, 'Sinaloa', 'SIN', 'SI'),
(102, 138, 'Sonora', 'SON', 'SO'),
(103, 138, 'Tabasco', 'TAB', 'TA'),
(104, 138, 'Tamaulipas', 'TAM', 'TM'),
(105, 138, 'Tlaxcala', 'TLX', 'TX'),
(106, 138, 'Veracruz', 'VER', 'VZ'),
(107, 138, 'Yucatán', 'YUC', 'YU'),
(108, 138, 'Zacatecas', 'ZAC', 'ZA'),
(109, 30, 'Acre', 'ACR', 'AC'),
(110, 30, 'Alagoas', 'ALG', 'AL'),
(111, 30, 'Amapá', 'AMP', 'AP'),
(112, 30, 'Amazonas', 'AMZ', 'AM'),
(113, 30, 'Bahía', 'BAH', 'BA'),
(114, 30, 'Ceará', 'CEA', 'CE'),
(115, 30, 'Distrito Federal', 'DFB', 'DF'),
(116, 30, 'Espirito Santo', 'ESS', 'ES'),
(117, 30, 'Goiás', 'GOI', 'GO'),
(118, 30, 'Maranhão', 'MAR', 'MA'),
(119, 30, 'Mato Grosso', 'MAT', 'MT'),
(120, 30, 'Mato Grosso do Sul', 'MGS', 'MS'),
(121, 30, 'Minas Geraís', 'MIG', 'MG'),
(122, 30, 'Paraná', 'PAR', 'PR'),
(123, 30, 'Paraíba', 'PRB', 'PB'),
(124, 30, 'Pará', 'PAB', 'PA'),
(125, 30, 'Pernambuco', 'PER', 'PE'),
(126, 30, 'Piauí', 'PIA', 'PI'),
(127, 30, 'Rio Grande do Norte', 'RGN', 'RN'),
(128, 30, 'Rio Grande do Sul', 'RGS', 'RS'),
(129, 30, 'Rio de Janeiro', 'RDJ', 'RJ'),
(130, 30, 'Rondônia', 'RON', 'RO'),
(131, 30, 'Roraima', 'ROR', 'RR'),
(132, 30, 'Santa Catarina', 'SAC', 'SC'),
(133, 30, 'Sergipe', 'SER', 'SE'),
(134, 30, 'São Paulo', 'SAP', 'SP'),
(135, 30, 'Tocantins', 'TOC', 'TO'),
(NULL, 44, 'Anhui', 'ANH', '34'),
(NULL, 44, 'Beijing', 'BEI', '11'),
(NULL, 44, 'Chongqing', 'CHO', '50'),
(NULL, 44, 'Fujian', 'FUJ', '35'),
(NULL, 44, 'Gansu', 'GAN', '62'),
(NULL, 44, 'Guangdong', 'GUA', '44'),
(NULL, 44, 'Guangxi Zhuang', 'GUZ', '45'),
(NULL, 44, 'Guizhou', 'GUI', '52'),
(NULL, 44, 'Hainan', 'HAI', '46'),
(NULL, 44, 'Hebei', 'HEB', '13'),
(NULL, 44, 'Heilongjiang', 'HEI', '23'),
(NULL, 44, 'Henan', 'HEN', '41'),
(NULL, 44, 'Hubei', 'HUB', '42'),
(NULL, 44, 'Hunan', 'HUN', '43'),
(NULL, 44, 'Jiangsu', 'JIA', '32'),
(NULL, 44, 'Jiangxi', 'JIX', '36'),
(NULL, 44, 'Jilin', 'JIL', '22'),
(NULL, 44, 'Liaoning', 'LIA', '21'),
(NULL, 44, 'Nei Mongol', 'NML', '15'),
(NULL, 44, 'Ningxia Hui', 'NIH', '64'),
(NULL, 44, 'Qinghai', 'QIN', '63'),
(NULL, 44, 'Shandong', 'SNG', '37'),
(NULL, 44, 'Shanghai', 'SHH', '31'),
(NULL, 44, 'Shaanxi', 'SHX', '61'),
(NULL, 44, 'Sichuan', 'SIC', '51'),
(NULL, 44, 'Tianjin', 'TIA', '12'),
(NULL, 44, 'Xinjiang Uygur', 'XIU', '65'),
(NULL, 44, 'Xizang', 'XIZ', '54'),
(NULL, 44, 'Yunnan', 'YUN', '53'),
(NULL, 44, 'Zhejiang', 'ZHE', '33'),
(NULL, 104, 'Israel', 'ISL', 'IL'),
(NULL, 104, 'Gaza Strip', 'GZS', 'GZ'),
(NULL, 104, 'West Bank', 'WBK', 'WB'),
(NULL, 151, 'St. Maarten', 'STM', 'SM'),
(NULL, 151, 'Bonaire', 'BNR', 'BN'),
(NULL, 151, 'Curacao', 'CUR', 'CR'),
(NULL, 175, 'Alba', 'ABA', 'AB'),
(NULL, 175, 'Arad', 'ARD', 'AR'),
(NULL, 175, 'Arges', 'ARG', 'AG'),
(NULL, 175, 'Bacau', 'BAC', 'BC'),
(NULL, 175, 'Bihor', 'BIH', 'BH'),
(NULL, 175, 'Bistrita-Nasaud', 'BIS', 'BN'),
(NULL, 175, 'Botosani', 'BOT', 'BT'),
(NULL, 175, 'Braila', 'BRL', 'BR'),
(NULL, 175, 'Brasov', 'BRA', 'BV'),
(NULL, 175, 'Bucuresti', 'BUC', 'B'),
(NULL, 175, 'Buzau', 'BUZ', 'BZ'),
(NULL, 175, 'Calarasi', 'CAL', 'CL'),
(NULL, 175, 'Caras Severin', 'CRS', 'CS'),
(NULL, 175, 'Cluj', 'CLJ', 'CJ'),
(NULL, 175, 'Constanta', 'CST', 'CT'),
(NULL, 175, 'Covasna', 'COV', 'CV'),
(NULL, 175, 'Dambovita', 'DAM', 'DB'),
(NULL, 175, 'Dolj', 'DLJ', 'DJ'),
(NULL, 175, 'Galati', 'GAL', 'GL'),
(NULL, 175, 'Giurgiu', 'GIU', 'GR'),
(NULL, 175, 'Gorj', 'GOR', 'GJ'),
(NULL, 175, 'Hargita', 'HRG', 'HR'),
(NULL, 175, 'Hunedoara', 'HUN', 'HD'),
(NULL, 175, 'Ialomita', 'IAL', 'IL'),
(NULL, 175, 'Iasi', 'IAS', 'IS'),
(NULL, 175, 'Ilfov', 'ILF', 'IF'),
(NULL, 175, 'Maramures', 'MAR', 'MM'),
(NULL, 175, 'Mehedinti', 'MEH', 'MH'),
(NULL, 175, 'Mures', 'MUR', 'MS'),
(NULL, 175, 'Neamt', 'NEM', 'NT'),
(NULL, 175, 'Olt', 'OLT', 'OT'),
(NULL, 175, 'Prahova', 'PRA', 'PH'),
(NULL, 175, 'Salaj', 'SAL', 'SJ'),
(NULL, 175, 'Satu Mare', 'SAT', 'SM'),
(NULL, 175, 'Sibiu', 'SIB', 'SB'),
(NULL, 175, 'Suceava', 'SUC', 'SV'),
(NULL, 175, 'Teleorman', 'TEL', 'TR'),
(NULL, 175, 'Timis', 'TIM', 'TM'),
(NULL, 175, 'Tulcea', 'TUL', 'TL'),
(NULL, 175, 'Valcea', 'VAL', 'VL'),
(NULL, 175, 'Vaslui', 'VAS', 'VS'),
(NULL, 175, 'Vrancea', 'VRA', 'VN'),
(NULL, 105, 'Agrigento', 'AGR', 'AG'),
(NULL, 105, 'Alessandria', 'ALE', 'AL'),
(NULL, 105, 'Ancona', 'ANC', 'AN'), 
(NULL, 105, 'Aosta', 'AOS', 'AO'),
(NULL, 105, 'Arezzo', 'ARE', 'AR'),
(NULL, 105, 'Ascoli Piceno', 'API', 'AP'),
(NULL, 105, 'Asti', 'AST', 'AT'),
(NULL, 105, 'Avellino', 'AVE', 'AV'),
(NULL, 105, 'Bari', 'BAR', 'BA'),
(NULL, 105, 'Barletta Andria Trani', 'BTA', 'BT'),
(NULL, 105, 'Belluno', 'BEL', 'BL'),
(NULL, 105, 'Benevento', 'BEN', 'BN'),
(NULL, 105, 'Bergamo', 'BEG', 'BG'),
(NULL, 105, 'Biella', 'BIE', 'BI'),
(NULL, 105, 'Bologna', 'BOL', 'BO'),
(NULL, 105, 'Bolzano', 'BOZ', 'BZ'),
(NULL, 105, 'Brescia', 'BRE', 'BS'),
(NULL, 105, 'Brindisi', 'BRI', 'BR'),
(NULL, 105, 'Cagliari', 'CAG', 'CA'),
(NULL, 105, 'Caltanissetta', 'CAL', 'CL'),
(NULL, 105, 'Campobasso', 'CBO', 'CB'),
(NULL, 105, 'Carbonia-Iglesias', 'CAR', 'CI'),
(NULL, 105, 'Caserta', 'CAS', 'CE'),
(NULL, 105, 'Catania', 'CAT', 'CT'),
(NULL, 105, 'Catanzaro', 'CTZ', 'CZ'),
(NULL, 105, 'Chieti', 'CHI', 'CH'),
(NULL, 105, 'Como', 'COM', 'CO'),
(NULL, 105, 'Cosenza', 'COS', 'CS'),
(NULL, 105, 'Cremona', 'CRE', 'CR'),
(NULL, 105, 'Crotone', 'CRO', 'KR'),
(NULL, 105, 'Cuneo', 'CUN', 'CN'),
(NULL, 105, 'Enna', 'ENN', 'EN'),
(NULL, 105, 'Fermo', 'FMO', 'FM'),
(NULL, 105, 'Ferrara', 'FER', 'FE'),
(NULL, 105, 'Firenze', 'FIR', 'FI'),
(NULL, 105, 'Foggia', 'FOG', 'FG'),
(NULL, 105, 'Forli-Cesena', 'FOC', 'FC'),
(NULL, 105, 'Frosinone', 'FRO', 'FR'),
(NULL, 105, 'Genova', 'GEN', 'GE'),
(NULL, 105, 'Gorizia', 'GOR', 'GO'),
(NULL, 105, 'Grosseto', 'GRO', 'GR'),
(NULL, 105, 'Imperia', 'IMP', 'IM'),
(NULL, 105, 'Isernia', 'ISE', 'IS'),
(NULL, 105, 'L\'Aquila', 'AQU', 'AQ'),
(NULL, 105, 'La Spezia', 'LAS', 'SP'),
(NULL, 105, 'Latina', 'LAT', 'LT'),
(NULL, 105, 'Lecce', 'LEC', 'LE'),
(NULL, 105, 'Lecco', 'LCC', 'LC'),
(NULL, 105, 'Livorno', 'LIV', 'LI'),
(NULL, 105, 'Lodi', 'LOD', 'LO'),
(NULL, 105, 'Lucca', 'LUC', 'LU'),
(NULL, 105, 'Macerata', 'MAC', 'MC'),
(NULL, 105, 'Mantova', 'MAN', 'MN'),
(NULL, 105, 'Massa-Carrara', 'MAS', 'MS'),
(NULL, 105, 'Matera', 'MAA', 'MT'),
(NULL, 105, 'Medio Campidano', 'MED','VS'),
(NULL, 105, 'Messina', 'MES', 'ME'),
(NULL, 105, 'Milano', 'MIL', 'MI'),
(NULL, 105, 'Modena', 'MOD', 'MO'),
(NULL, 105, 'Monza e della Brianza', 'MBA', 'MB'),
(NULL, 105, 'Napoli', 'NAP', 'NA'),
(NULL, 105, 'Novara', 'NOV', 'NO'),
(NULL, 105, 'Nuoro', 'NUR', 'NU'),
(NULL, 105, 'Ogliastra', 'OGL', 'OG'),
(NULL, 105, 'Olbia-Tempio', 'OLB', 'OT'),
(NULL, 105, 'Oristano', 'ORI', 'OR'),
(NULL, 105, 'Padova', 'PDA', 'PD'),
(NULL, 105, 'Palermo', 'PAL', 'PA'),
(NULL, 105, 'Parma', 'PAA', 'PR'),
(NULL, 105, 'Pavia', 'PAV', 'PV'),
(NULL, 105, 'Perugia', 'PER', 'PG'),
(NULL, 105, 'Pesaro e Urbino', 'PES', 'PU'),
(NULL, 105, 'Pescara', 'PSC', 'PE'),
(NULL, 105, 'Piacenza', 'PIA', 'PC'),
(NULL, 105, 'Pisa', 'PIS', 'PI'),
(NULL, 105, 'Pistoia', 'PIT', 'PT'),
(NULL, 105, 'Pordenone', 'POR', 'PN'),
(NULL, 105, 'Potenza', 'PTZ', 'PZ'),
(NULL, 105, 'Prato', 'PRA', 'PO'),
(NULL, 105, 'Ragusa', 'RAG', 'RG'),
(NULL, 105, 'Ravenna', 'RAV', 'RA'),
(NULL, 105, 'Reggio Calabria', 'REG', 'RC'),
(NULL, 105, 'Reggio Emilia', 'REE', 'RE'),
(NULL, 105, 'Rieti', 'RIE', 'RI'),
(NULL, 105, 'Rimini', 'RIM','RN'),
(NULL, 105, 'Roma', 'ROM', 'RM'),
(NULL, 105, 'Rovigo', 'ROV', 'RO'),
(NULL, 105, 'Salerno', 'SAL', 'SA'),
(NULL, 105, 'Sassari', 'SAS', 'SS'),
(NULL, 105, 'Savona', 'SAV', 'SV'),
(NULL, 105, 'Siena', 'SIE', 'SI'),
(NULL, 105, 'Siracusa', 'SIR', 'SR'),
(NULL, 105, 'Sondrio', 'SOO', 'SO'),
(NULL, 105, 'Taranto', 'TAR', 'TA'),
(NULL, 105, 'Teramo', 'TER', 'TE'),
(NULL, 105, 'Terni', 'TRN', 'TR'),
(NULL, 105, 'Torino', 'TOR', 'TO'),
(NULL, 105, 'Trapani', 'TRA', 'TP'),
(NULL, 105, 'Trento', 'TRE', 'TN'),
(NULL, 105, 'Treviso', 'TRV', 'TV'),
(NULL, 105, 'Trieste', 'TRI', 'TS'),
(NULL, 105, 'Udine', 'UDI', 'UD'),
(NULL, 105, 'Varese', 'VAR', 'VA'),
(NULL, 105, 'Venezia', 'VEN', 'VE'),
(NULL, 105, 'Verbano Cusio Ossola', 'VCO', 'VB'),
(NULL, 105, 'Vercelli', 'VER', 'VC'),
(NULL, 105, 'Verona', 'VRN', 'VR'),
(NULL, 105, 'Vibo Valenzia', 'VIV', 'VV'),
(NULL, 105, 'Vicenza', 'VII', 'VI'),
(NULL, 105, 'Viterbo', 'VIT', 'VT'),
(NULL, 195, 'A Coruña', 'ACOR', '15'),
(NULL, 195, 'Alava', 'ALA', '01'),
(NULL, 195, 'Albacete', 'ALB', '02'),
(NULL, 195, 'Alicante', 'ALI', '03'),
(NULL, 195, 'Almeria', 'ALM', '04'),
(NULL, 195, 'Asturias', 'AST', '33'),
(NULL, 195, 'Avila', 'AVI', '05'),
(NULL, 195, 'Badajoz', 'BAD', '06'),
(NULL, 195, 'Baleares', 'BAL', '07'),
(NULL, 195, 'Barcelona', 'BAR', '08'),
(NULL, 195, 'Burgos', 'BUR', '09'),
(NULL, 195, 'Caceres', 'CAC', '10'),
(NULL, 195, 'Cadiz', 'CAD', '11'),
(NULL, 195, 'Cantabria', 'CAN', '39'),
(NULL, 195, 'Castellon', 'CAS', '12'),
(NULL, 195, 'Ceuta', 'CEU', '51'),
(NULL, 195, 'Ciudad Real', 'CIU', '13'),
(NULL, 195, 'Cordoba', 'COR', '14'),
(NULL, 195, 'Cuenca', 'CUE', '16'),
(NULL, 195, 'Girona', 'GIR', '17'),
(NULL, 195, 'Granada', 'GRA', '18'),
(NULL, 195, 'Guadalajara', 'GUA', '19'),
(NULL, 195, 'Guipuzcoa', 'GUI', '20'),
(NULL, 195, 'Huelva', 'HUL', '21'),
(NULL, 195, 'Huesca', 'HUS', '22'),
(NULL, 195, 'Jaen', 'JAE', '23'),
(NULL, 195, 'La Rioja', 'LRI', '26'),
(NULL, 195, 'Las Palmas', 'LPA', '35'),
(NULL, 195, 'Leon', 'LEO', '24'),
(NULL, 195, 'Lleida', 'LLE', '25'),
(NULL, 195, 'Lugo', 'LUG', '27'),
(NULL, 195, 'Madrid', 'MAD', '28'),
(NULL, 195, 'Malaga', 'MAL', '29'),
(NULL, 195, 'Melilla', 'MEL', '52'),
(NULL, 195, 'Murcia', 'MUR', '30'),
(NULL, 195, 'Navarra', 'NAV', '31'),
(NULL, 195, 'Ourense', 'OUR', '32'),
(NULL, 195, 'Palencia', 'PAL', '34'),
(NULL, 195, 'Pontevedra', 'PON', '36'),
(NULL, 195, 'Salamanca', 'SAL', '37'),
(NULL, 195, 'Santa Cruz de Tenerife', 'SCT', '38'),
(NULL, 195, 'Segovia', 'SEG', '40'),
(NULL, 195, 'Sevilla', 'SEV', '41'),
(NULL, 195, 'Soria', 'SOR', '42'),
(NULL, 195, 'Tarragona', 'TAR', '43'),
(NULL, 195, 'Teruel', 'TER', '44'),
(NULL, 195, 'Toledo', 'TOL', '45'),
(NULL, 195, 'Valencia', 'VAL', '46'),
(NULL, 195, 'Valladolid', 'VLL', '47'),
(NULL, 195, 'Vizcaya', 'VIZ', '48'),
(NULL, 195, 'Zamora', 'ZAM', '49'),
(NULL, 195, 'Zaragoza', 'ZAR', '50'),
(NULL, 11, 'Aragatsotn', 'ARG', 'AG'),
(NULL, 11, 'Ararat', 'ARR', 'AR'),
(NULL, 11, 'Armavir', 'ARM', 'AV'),
(NULL, 11, 'Gegharkunik', 'GEG', 'GR'),
(NULL, 11, 'Kotayk', 'KOT', 'KT'),
(NULL, 11, 'Lori', 'LOR', 'LO'),
(NULL, 11, 'Shirak', 'SHI', 'SH'),
(NULL, 11, 'Syunik', 'SYU', 'SU'),
(NULL, 11, 'Tavush', 'TAV', 'TV'),
(NULL, 11, 'Vayots-Dzor', 'VAD', 'VD'),
(NULL, 11, 'Yerevan', 'YER', 'ER'),
(NULL, 99, 'Andaman & Nicobar Islands', 'ANI', 'AI'),
(NULL, 99, 'Andhra Pradesh', 'AND', 'AN'),
(NULL, 99, 'Arunachal Pradesh', 'ARU', 'AR'),
(NULL, 99, 'Assam', 'ASS', 'AS'),
(NULL, 99, 'Bihar', 'BIH', 'BI'),
(NULL, 99, 'Chandigarh', 'CHA', 'CA'),
(NULL, 99, 'Chhatisgarh', 'CHH', 'CH'),
(NULL, 99, 'Dadra & Nagar Haveli', 'DAD', 'DD'),
(NULL, 99, 'Daman & Diu', 'DAM', 'DA'),
(NULL, 99, 'Delhi', 'DEL', 'DE'),
(NULL, 99, 'Goa', 'GOA', 'GO'),
(NULL, 99, 'Gujarat', 'GUJ', 'GU'),
(NULL, 99, 'Haryana', 'HAR', 'HA'),
(NULL, 99, 'Himachal Pradesh', 'HIM', 'HI'),
(NULL, 99, 'Jammu & Kashmir', 'JAM', 'JA'),
(NULL, 99, 'Jharkhand', 'JHA', 'JH'),
(NULL, 99, 'Karnataka', 'KAR', 'KA'),
(NULL, 99, 'Kerala', 'KER', 'KE'),
(NULL, 99, 'Lakshadweep', 'LAK', 'LA'),
(NULL, 99, 'Madhya Pradesh', 'MAD', 'MD'),
(NULL, 99, 'Maharashtra', 'MAH', 'MH'),
(NULL, 99, 'Manipur', 'MAN', 'MN'),
(NULL, 99, 'Meghalaya', 'MEG', 'ME'),
(NULL, 99, 'Mizoram', 'MIZ', 'MI'),
(NULL, 99, 'Nagaland', 'NAG', 'NA'),
(NULL, 99, 'Orissa', 'ORI', 'OR'),
(NULL, 99, 'Pondicherry', 'PON', 'PO'),
(NULL, 99, 'Punjab', 'PUN', 'PU'),
(NULL, 99, 'Rajasthan', 'RAJ', 'RA'),
(NULL, 99, 'Sikkim', 'SIK', 'SI'),
(NULL, 99, 'Tamil Nadu', 'TAM', 'TA'),
(NULL, 99, 'Tripura', 'TRI', 'TR'),
(NULL, 99, 'Uttaranchal', 'UAR', 'UA'),
(NULL, 99, 'Uttar Pradesh', 'UTT', 'UT'),
(NULL, 99, 'West Bengal', 'WES', 'WE'),
(NULL, 101, 'Ahmadi va Kohkiluyeh', 'BOK', 'BO'),
(NULL, 101, 'Ardabil', 'ARD', 'AR'),
(NULL, 101, 'Azarbayjan-e Gharbi', 'AZG', 'AG'),
(NULL, 101, 'Azarbayjan-e Sharqi', 'AZS', 'AS'),
(NULL, 101, 'Bushehr', 'BUS', 'BU'),
(NULL, 101, 'Chaharmahal va Bakhtiari', 'CMB', 'CM'),
(NULL, 101, 'Esfahan', 'ESF', 'ES'),
(NULL, 101, 'Fars', 'FAR', 'FA'),
(NULL, 101, 'Gilan', 'GIL', 'GI'),
(NULL, 101, 'Gorgan', 'GOR', 'GO'),
(NULL, 101, 'Hamadan', 'HAM', 'HA'),
(NULL, 101, 'Hormozgan', 'HOR', 'HO'),
(NULL, 101, 'Ilam', 'ILA', 'IL'),
(NULL, 101, 'Kerman', 'KER', 'KE'),
(NULL, 101, 'Kermanshah', 'BAK', 'BA'),
(NULL, 101, 'Khorasan-e Junoubi', 'KHJ', 'KJ'),
(NULL, 101, 'Khorasan-e Razavi', 'KHR', 'KR'),
(NULL, 101, 'Khorasan-e Shomali', 'KHS', 'KS'),
(NULL, 101, 'Khuzestan', 'KHU', 'KH'),
(NULL, 101, 'Kordestan', 'KOR', 'KO'),
(NULL, 101, 'Lorestan', 'LOR', 'LO'),
(NULL, 101, 'Markazi', 'MAR', 'MR'),
(NULL, 101, 'Mazandaran', 'MAZ', 'MZ'),
(NULL, 101, 'Qazvin', 'QAS', 'QA'),
(NULL, 101, 'Qom', 'QOM', 'QO'),
(NULL, 101, 'Semnan', 'SEM', 'SE'),
(NULL, 101, 'Sistan va Baluchestan', 'SBA', 'SB'),
(NULL, 101, 'Tehran', 'TEH', 'TE'),
(NULL, 101, 'Yazd', 'YAZ', 'YA'),
(NULL, 101, 'Zanjan', 'ZAN', 'ZA');" );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_tax_rate`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_tax_rate` (
  `tax_rate_id` int(11) NOT NULL auto_increment,
  `vendor_id` int(11) default NULL,
  `tax_state` varchar(64) default NULL,
  `tax_country` varchar(64) default NULL,
  `mdate` int(11) default NULL,
  `tax_rate` decimal(10,5) default NULL,
  PRIMARY KEY  (`tax_rate_id`),
  KEY `idx_tax_rate_vendor_id` (`vendor_id`)
) ENGINE=MyISAM COMMENT='The tax rates for your store'; ");

## 
## Dumping data for table `#__{vm}_tax_rate`
## 

$db->query( "INSERT INTO `#__{vm}_tax_rate` VALUES (2, 1, 'CA', 'USA', 964565926, 0.0975);");

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_user_info`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_user_info` (
  `user_info_id` varchar(32) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `address_type` char(2) default NULL,
  `address_type_name` varchar(32) default NULL,
  `company` varchar(64) default NULL,
  `title` varchar(32) default NULL,
  `last_name` varchar(32) default NULL,
  `first_name` varchar(32) default NULL,
  `middle_name` varchar(32) default NULL,
  `phone_1` varchar(32) default NULL,
  `phone_2` varchar(32) default NULL,
  `fax` varchar(32) default NULL,
  `address_1` varchar(64) NOT NULL default '',
  `address_2` varchar(64) default NULL,
  `city` varchar(32) NOT NULL default '',
  `state` varchar(32) NOT NULL default '',
  `country` varchar(32) NOT NULL default 'US',
  `zip` varchar(32) NOT NULL default '',
  `user_email` varchar(255) default NULL,
  `extra_field_1` varchar(255) default NULL,
  `extra_field_2` varchar(255) default NULL,
  `extra_field_3` varchar(255) default NULL,
  `extra_field_4` char(1) default NULL,
  `extra_field_5` char(1) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `perms` varchar(40) NOT NULL default 'shopper',
  `bank_account_nr` varchar(32) NOT NULL default '',
  `bank_name` varchar(32) NOT NULL default '',
  `bank_sort_code` varchar(16) NOT NULL default '',
  `bank_iban` varchar(64) NOT NULL default '',
  `bank_account_holder` varchar(48) NOT NULL default '',
  `bank_account_type` enum('Checking','Business Checking','Savings') NOT NULL default 'Checking',
  PRIMARY KEY  (`user_info_id`),
  KEY `idx_user_info_user_id` (`user_id`)
) ENGINE=MyISAM COMMENT='Customer Information, BT = BillTo and ST = ShipTo'; ");


$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_userfield` (
  `fieldid` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `type` varchar(50) NOT NULL default '',
  `maxlength` int(11) default NULL,
  `size` int(11) default NULL,
  `required` tinyint(4) default '0',
  `ordering` int(11) default NULL,
  `cols` int(11) default NULL,
  `rows` int(11) default NULL,
  `value` varchar(50) default NULL,
  `default` int(11) default NULL,
  `published` tinyint(1) NOT NULL default '1',
  `registration` tinyint(1) NOT NULL default '0',
  `shipping` tinyint(1) NOT NULL default '0',
  `account` tinyint(1) NOT NULL default '1',
  `readonly` tinyint(1) NOT NULL default '0',
  `calculated` tinyint(1) NOT NULL default '0',
  `sys` tinyint(4) NOT NULL default '0',
  `vendor_id` int(11) default NULL,
  `params` mediumtext,
  PRIMARY KEY  (`fieldid`)
) ENGINE=MyISAM AUTO_INCREMENT=30 COMMENT='Holds the fields for the user information';" );

## 
## Dumping data for table `#__{vm}_userfield`
## 

$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (1, 'email', 'REGISTER_EMAIL', '', 'emailaddress', 100, 30, 1, 2, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (7, 'title', 'PHPSHOP_SHOPPER_FORM_TITLE', '', 'select', 0, 0, 0, 8, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (3, 'password', 'PHPSHOP_SHOPPER_FORM_PASSWORD_1', '', 'password', 25, 30, 1, 4, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (4, 'password2', 'PHPSHOP_SHOPPER_FORM_PASSWORD_2', '', 'password', 25, 30, 1, 5, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (6, 'company', 'PHPSHOP_SHOPPER_FORM_COMPANY_NAME', '', 'text', 64, 30, 0, 7, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (5, 'delimiter_billto', 'PHPSHOP_USER_FORM_BILLTO_LBL', '', 'delimiter', 25, 30, 0, 6, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (2, 'username', 'REGISTER_UNAME', '', 'text', 25, 30, 1, 3, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 1, 1, '');" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (35, 'address_type_name', 'PHPSHOP_USER_FORM_ADDRESS_LABEL', '', 'text', 32, 30, 1, 6, NULL, NULL, NULL, NULL, 1, 0, 1, 0, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (8, 'first_name', 'PHPSHOP_SHOPPER_FORM_FIRST_NAME', '', 'text', 32, 30, 1, 9, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (9, 'last_name', 'PHPSHOP_SHOPPER_FORM_LAST_NAME', '', 'text', 32, 30, 1, 10, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (10, 'middle_name', 'PHPSHOP_SHOPPER_FORM_MIDDLE_NAME', '', 'text', 32, 30, 0, 11, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (11, 'address_1', 'PHPSHOP_SHOPPER_FORM_ADDRESS_1', '', 'text', 64, 30, 1, 12, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (12, 'address_2', 'PHPSHOP_SHOPPER_FORM_ADDRESS_2', '', 'text', 64, 30, 0, 13, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (13, 'city', 'PHPSHOP_SHOPPER_FORM_CITY', '', 'text', 32, 30, 1, 14, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (14, 'zip', 'PHPSHOP_SHOPPER_FORM_ZIP', '', 'text', 32, 30, 1, 15, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (15, 'country', 'PHPSHOP_SHOPPER_FORM_COUNTRY', '', 'select', 0, 0, 1, 16, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (16, 'state', 'PHPSHOP_SHOPPER_FORM_STATE', '', 'select', 0, 0, 1, 17, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (17, 'phone_1', 'PHPSHOP_SHOPPER_FORM_PHONE', '', 'text', 32, 30, 1, 18, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (18, 'phone_2', 'PHPSHOP_SHOPPER_FORM_PHONE2', '', 'text', 32, 30, 0, 19, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (19, 'fax', 'PHPSHOP_SHOPPER_FORM_FAX', '', 'text', 32, 30, 0, 20, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (20, 'delimiter_bankaccount', 'PHPSHOP_ACCOUNT_BANK_TITLE', '', 'delimiter', 25, 30, 0, 21, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (21, 'bank_account_holder', 'PHPSHOP_ACCOUNT_LBL_BANK_ACCOUNT_HOLDER', '', 'text', 48, 30, 0, 22, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (22, 'bank_account_nr', 'PHPSHOP_ACCOUNT_LBL_BANK_ACCOUNT_NR', '', 'text', 32, 30, 0, 23, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (23, 'bank_sort_code', 'PHPSHOP_ACCOUNT_LBL_BANK_SORT_CODE', '', 'text', 16, 30, 0, 24, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (24, 'bank_name', 'PHPSHOP_ACCOUNT_LBL_BANK_NAME', '', 'text', 32, 30, 0, 25, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (25, 'bank_account_type', 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE', '', 'select', 0, 0, 0, 26, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 0, 1, 1, '');" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (26, 'bank_iban', 'PHPSHOP_ACCOUNT_LBL_BANK_IBAN', '', 'text', 64, 30, 0, 27, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (27, 'delimiter_sendregistration', 'BUTTON_SEND_REG', '', 'delimiter', 25, 30, 0, 28, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (28, 'agreed', 'PHPSHOP_I_AGREE_TO_TOS', '', 'checkbox', NULL, NULL, 1, 29, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 0, 1, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (29, 'delimiter_userinfo', 'PHPSHOP_ORDER_PRINT_CUST_INFO_LBL', '', 'delimiter', NULL, NULL, 0, 1, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (30, 'extra_field_1', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_1', '', 'text', 255, 30, 0, 31, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (31, 'extra_field_2', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_2', '', 'text', 255, 30, 0, 32, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (32, 'extra_field_3', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_3', '', 'text', 255, 30, 0, 33, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (33, 'extra_field_4', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_4', '', 'select', 1, 1, 0, 34, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL);" );
$db->query( "INSERT INTO `#__{vm}_userfield` VALUES (34, 'extra_field_5', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_5', '', 'select', 1, 1, 0, 35, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL);" );

## --------------------------------------------------------


$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_userfield_values` (
  `fieldvalueid` int(11) NOT NULL auto_increment,
  `fieldid` int(11) NOT NULL default '0',
  `fieldtitle` varchar(255) NOT NULL default '',
  `fieldvalue` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `sys` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`fieldvalueid`)
) ENGINE=MyISAM COMMENT='Holds the different values for dropdown and radio lists';" );

$db->query( "INSERT INTO `#__{vm}_userfield_values` VALUES (1, 25, 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE_BUSINESSCHECKING','Checking', 1, 1);" );
$db->query( "INSERT INTO `#__{vm}_userfield_values` VALUES (2, 25, 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE_CHECKING', 'Business Checking', 2, 1);" );
$db->query( "INSERT INTO `#__{vm}_userfield_values` VALUES (3, 25, 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE_SAVINGS', 'Savings', 3, 1);" );


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_vendor`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_vendor` (
  `vendor_id` int(11) NOT NULL auto_increment,
  `vendor_name` varchar(64) default NULL,
  `contact_last_name` varchar(32) NOT NULL default '',
  `contact_first_name` varchar(32) NOT NULL default '',
  `contact_middle_name` varchar(32) default NULL,
  `contact_title` varchar(32) default NULL,
  `contact_phone_1` varchar(32) NOT NULL default '',
  `contact_phone_2` varchar(32) default NULL,
  `contact_fax` varchar(32) default NULL,
  `contact_email` varchar(255) default NULL,
  `vendor_phone` varchar(32) default NULL,
  `vendor_address_1` varchar(64) NOT NULL default '',
  `vendor_address_2` varchar(64) default NULL,
  `vendor_city` varchar(32) NOT NULL default '',
  `vendor_state` varchar(32) NOT NULL default '',
  `vendor_country` varchar(32) NOT NULL default 'US',
  `vendor_zip` varchar(32) NOT NULL default '',
  `vendor_store_name` varchar(128) NOT NULL default '',
  `vendor_store_desc` text,
  `vendor_category_id` int(11) default NULL,
  `vendor_thumb_image` varchar(255) default NULL,
  `vendor_full_image` varchar(255) default NULL,
  `vendor_currency` varchar(16) default NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `vendor_image_path` varchar(255) default NULL,
  `vendor_terms_of_service` text NOT NULL,
  `vendor_url` varchar(255) NOT NULL default '',
  `vendor_min_pov` decimal(10,2) default NULL,
  `vendor_freeshipping` decimal(10,2) NOT NULL default '0.00',
  `vendor_currency_display_style` varchar(64) NOT NULL default '',
  `vendor_accepted_currencies` text NOT NULL,
  `vendor_address_format` text NOT NULL,
  `vendor_date_format` varchar(255) NOT NULL,
  PRIMARY KEY  (`vendor_id`),
  KEY `idx_vendor_name` (`vendor_name`),
  KEY `idx_vendor_category_id` (`vendor_category_id`)
) ENGINE=MyISAM COMMENT='Vendors manage their products in your store'; ");

## 
## Dumping data for table `#__{vm}_vendor`
## 

$db->query( "INSERT INTO `#__{vm}_vendor` VALUES (1, 'Washupito''s Tiendita', 'Owner', 'Demo', 'Store', 'Mr.', '555-555-1212', '555-555-1212', '555-555-1212', '$mosConfig_mailfrom', '555-555-1212', '100 Washupito Avenue, N.W.', '', 'Lake Forest', 'CA', 'USA', '92630', 'Washupito''s Tiendita', '<p>We have the best tools for do-it-yourselfers.  Check us out! </p>\r\n		<p>We were established in 1969 in a time when getting good tools was expensive, but the quality was good.  Now that only a select few of those authentic tools survive, we have dedicated this store to bringing the experience alive for collectors and master mechanics everywhere.</p>\r\n		<p>You can easily find products selecting the category you would like to browse above.</p>', 0, '', 'c19970d6f2970cb0d1b13bea3af3144a.gif', 'USD', 950302468, 968309845, 'shop_image/', '<h5>You haven''t configured any terms of service yet. Click <a href=administrator/index2.php?page=store.store_form&option=com_virtuemart>here</a> to change this text.</h5>', '$mosConfig_live_site', 0.00, 0.00, '1|$|2|.| |2|1', 'USD', '{storename}\n{address_1}\n{address_2}\n{city}, {zip}', '%A, %d %B %Y %H:%M'); " );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_vendor_category`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_vendor_category` (
  `vendor_category_id` int(11) NOT NULL auto_increment,
  `vendor_category_name` varchar(64) default NULL,
  `vendor_category_desc` text,
  PRIMARY KEY  (`vendor_category_id`),
  KEY `idx_vendor_category_category_name` (`vendor_category_name`)
) ENGINE=MyISAM COMMENT='The categories that vendors are assigned to'; ");

## 
## Dumping data for table `#__{vm}_vendor_category`
## 

$db->query( "INSERT INTO `#__{vm}_vendor_category` VALUES (6, '-default-', 'Default'); " );

## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_waiting_list`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_waiting_list` (
  `waiting_list_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `notify_email` varchar(150) NOT NULL default '',
  `notified` enum('0','1') default '0',
  `notify_date` timestamp(14) NOT NULL,
  PRIMARY KEY  (`waiting_list_id`),
  KEY `product_id` (`product_id`),
  KEY `notify_email` (`notify_email`)
) ENGINE=MyISAM COMMENT='Stores notifications, users waiting f. products out of stock'; ");

## 
## Dumping data for table `#__{vm}_waiting_list`
## 


## --------------------------------------------------------

## 
## Table structure for table `#__{vm}_zone_shipping`
## 

$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_zone_shipping` (
  `zone_id` int(11) NOT NULL auto_increment,
  `zone_name` varchar(255) default NULL,
  `zone_cost` decimal(10,2) default NULL,
  `zone_limit` decimal(10,2) default NULL,
  `zone_description` text NOT NULL,
  `zone_tax_rate` int(11) NOT NULL default '0',
  PRIMARY KEY  (`zone_id`)
) ENGINE=MyISAM COMMENT='The Zones managed by the Zone Shipping Module'; ");

## 
## Dumping data for table `#__{vm}_zone_shipping`
## 

$db->query( "INSERT INTO `#__{vm}_zone_shipping` VALUES (1, 'Default', 6.00, 35.00, 'This is the default Shipping Zone. This is the zone information that all countries will use until you assign each individual country to a Zone.', 2),
(2, 'Zone 1', 1000.00, 10000.00, 'This is a zone example', 2),
(3, 'Zone 2', 2.00, 22.00, 'This is the second zone. You can use this for notes about this zone', 2),
(4, 'Zone 3', 11.00, 64.00, 'Another usefull thing might be details about this zone or special instructions.', 2);");

/**
 * You wonder why users are visible in VirtueMart after installation?
 * Well, because we add them here.
 */
$db->query( "SELECT `id`, `email`, `registerDate`, `lastvisitDate` FROM `#__users`"); 
$row = $db->loadObjectList();
foreach( $row as $user) {
	$db->query( "INSERT INTO `#__{vm}_auth_user_vendor` VALUES ('".$user->id."', '1');" );
	$db->query( "INSERT INTO `#__{vm}_shopper_vendor_xref` VALUES ('".$user->id."', '1', '5', '');" );
	$db->query( "INSERT INTO `#__{vm}_user_info` (`user_info_id`,`user_id`, `address_type`,`cdate`,`mdate`,`user_email` )
					VALUES( '".md5(uniqid('virtuemart'))."','".$user->id."','BT', UNIX_TIMESTAMP('".$user->registerDate."'),UNIX_TIMESTAMP('".$user->lastvisitDate."'),'".$user->email."');" );
}

# insert the user <=> group relationship
$db->query( "INSERT INTO `#__{vm}_auth_user_group` 
				SELECT user_id, 
					CASE `perms` 
					    WHEN 'admin' THEN 0
					    WHEN 'storeadmin' THEN 1
					    WHEN 'shopper' THEN 2
					    WHEN 'demo' THEN 3
					    ELSE 2 
					END
				FROM #__{vm}_user_info
				WHERE address_type='BT';");

$db->query( "INSERT INTO `#__{vm}_manufacturer` VALUES ('1', 'Manufacturer', 'info@manufacturer.com', 'An example for a manufacturer', '1', 'http://www.a-url.com');" );
$db->query( "INSERT INTO `#__{vm}_manufacturer_category` VALUES ('1', '-default-', 'This is the default manufacturer category');" );

?>
