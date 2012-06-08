<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}
/**
*
* @version $Id: TODO.php 1055 2007-11-26 18:28:48Z thepisu $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
?>
<pre>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  VirtueMart To-Do List
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

 Know Bugs
##################

See Bugtracker:
http://dev.virtuemart.net/cb/proj/tracker.do?proj_id=1


 FEATURES TO BE IMPLEMENTED
############################

* XML - Product Data Import/Export
* XML - Order Data Import/Export

* make Orders changeable afterwards
* bulk update of order statuses
* send Notification on new reviews
* send Notifications to customer + store owner (config option)

* Shipping Rate per Product
* "Compare Products"
* Tool to Remove all Products
* Add Discount on *all* Products
* Discount for Cart Total (> $100 = 1% discount...)
* Tool to Remove Orphan Images

* Gift- / Wish - List
* allow "Send as Gift" with individual Text on Order

* Modular Checkout
* Changeable Registration Form
  - allow Declaration of new Fields
  - make Fields reorderable
  - make Fields required / not required
  - switch Shopper Group including / excluding tax

 General to-do 
##################

* improve performance
  - Reduce Numbers of SQL Queries
  - Remove doubled function calls
  
* XHTML - Compliance, make it Barrier-free
* clean up checkout
</pre>