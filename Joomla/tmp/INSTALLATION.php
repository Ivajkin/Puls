<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) )	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* @version $Id: INSTALLATION.php 2575 2010-10-10 14:34:26Z zanardi $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2008 The Virtuemart Project - All rights reserved.
* VirtueMart is free software. This version may have been modified pursuant to the GNU General Public License, and as distributed it includes or is derivative of works licensed under the GNU General Public License or other free or open source software licenses.
* http://virtuemart.net
*/
?><pre>

MANUAL INSTALLATION OF VIRTUEMART
=== README FILE ===

The easy & automatic installation procedure is explained in the file
README.php of the COMPLETE PACKAGE.

You shouldn't use these instructions, when you have downloaded the
COMPLETE PACKAGE.

You should only use this file, when you have 
	* VirtueMart 1.0.x and want to upgrade to VirtueMart 1.1.x
	* safe mode problems, so you can't use the component installer (Safe Mode = On ? Manual Installation is your saviour)
	* problems installing the Component by uploading the contents of the component installer archive and installing from directory
	

=== ABOUT THIS FILE ===

This file is meant to provide hints on the Manual Installation Procedure for VirtueMart.


=== ABOUT VirtueMart ===

VirtueMart is an Open Source Online-Shop plugin for Joomla! and Mambo.


=== MANUAL INSTALLATION PROCEDURE ===
  
1. Unpack the archive file "VirtueMart_Manual-Installation-Package_1.x.tar.gz" (the one which contains this file as "README.txt")
	using WinRAR or a similar Archive Software
	
	You should now see some directories:
	  * /administrator
	  * /components
	  * /modules
	  * /plugins
	
	The directory structure in those directories is the
	same as in your Joomla!/Mambo site.
	
2.  Open up an FTP Connection to your site and upload
	the directories to your Joomla! site.
	
	/components
	to
	  /path-to-site-root/components/

	/administrator
	to
	  /path-to-site-root/administrator/
	  
	/modules
	to
	  /path-to-site-root/modules/

	/plugins
	to
	  /path-to-site-root/plugins/
	  
	You will probably have to confirm overwriting some existing files 
	in these directories.	
	An existing configuration file will not be overwritten.


3.  Login in to the Joomla! Administration (the so-called Backend).

	  http://www.xxxxxx.com/administrator/
	
	* When having logged in, you see this URL in the address bar:
	
	  http://www.xxxxxx.com/administrator/index.php
	
	* Now just add "?option=com_virtuemart" after index.php, so it looks like this
	  in your browser's address bar:
	  
		http://www.xxxxxx.com/administrator/index.php?option=com_virtuemart
	
	  and submit (press Enter).
	
	* You should now see the "Installation was successful..." Screen.
	  There you can click on 
		"GO TO THE SHOP >>" 
	  or on
		"INSTALL SAMPLE DATA" (when you want to have some sample products and categories in your Shop).

	* That's it.	  

=== Modules and Plugins ===

4. The Modules and Plugins in the archive are add-ons, but most important is the VirtueMart Main Module,
	which consists of two files:
	/modules/mod_virtuemart.php
	/modules/mod_virtuemart.xml
	
	You need this module to be able to access and browse your shop
	like a customer.

	For experienced users, just import the appropriate sql file:
	 - Joomla! 1.5.x: virtuemart.installation.addons.joomla1.5.sql
	
	Otherwise, browse to phpMyAdmin and select your database.
	Select "SQL" in the toolbar on the middle top.
	Then run the SQL code below:
	
	For Joomla! 1.5.x (using a jos_ table prefix):
	
####
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Module', '', 99, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_virtuemart', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Login', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_login', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart TopTen Products', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_topten', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Product Scroller', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_productscroller', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Product Categories', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_product_categories', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart All-In-One', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_allinone', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Cart', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_cart', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Featured Products', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_featureprod', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Latest Products', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_latestprod', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Manufacturers', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_manufacturers', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Random Products', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_randomprod', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_modules` (`title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES ( 'VirtueMart Search', '', 99, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_search', 0, 0, 1, '', 0, 0);
INSERT IGNORE INTO `jos_plugins` (`name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES ('VirtueMart Product Snapshot', 'vmproductsnapshots', 'content', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `jos_plugins` (`name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES ('VirtueMart Product Search', 'vmxsearch.plugin', 'search', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '');
####

5.  In the Joomla! backend browse to "Modules" => "Site Modules" and search for VirtueMart modules.
	Select each of them step-by-step and set them to "published".
	Please note that you MUST PUBLISH THE "VirtueMart Module"

</pre>
