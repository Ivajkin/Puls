<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: english.php 1071 2007-12-03 08:42:28Z thepisu $
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @translator soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
global $VM_LANG;
$langvars = array (
	'CHARSET' => 'ISO-8859-1',
	'VM_HELP_YOURVERSION' => 'Your {product} version',
	'VM_HELP_ABOUT' => '<span style="font-weight: bold;">
		VirtueMart</span> is the complete Open Source E-Commerce solution for Mambo and Joomla!. 
		It is an Application, which comes with a Component, more than 8 Modules and Mambots/Plugins.
		It has its roots in a Shopping Cart Script called "phpShop" (Authors: Edikon Corp. & the <a href="http://www.virtuemart.org/" target="_blank">phpShop</a> community).',
	'VM_HELP_LICENSE_DESC' => 'VirtueMart is licensed under the <a href="{licenseurl}" target="_blank">{licensename} License</a>.',
	'VM_HELP_TEAM' => 'There\'s a small team of Developers who help in evolving this Shopping Cart Script.',
	'VM_HELP_PROJECTLEADER' => 'Project Leader',
	'VM_HELP_HOMEPAGE' => 'Homepage',
	'VM_HELP_DONATION_DESC' => 'Please consider a small donation to the VirtueMart Project to help us keep up the work on this Component and create new Features.',
	'VM_HELP_DONATION_BUTTON_ALT' => 'Make payments with PayPal - it\'s fast, free and secure!'
); $VM_LANG->initModule( 'help', $langvars );
?>