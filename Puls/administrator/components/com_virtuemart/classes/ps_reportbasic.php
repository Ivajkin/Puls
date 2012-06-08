<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_reportbasic.php 1660 2009-02-22 17:05:02Z tkahl $
* @package VirtueMart
* @subpackage classes
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

class vm_nh_report {

	/**
	 * prints month, day, and year popups
	 *
	 * @param string $menu_name string to append to select names
	 * @param int $sel_date Date in YYYYMMDD format
	 * @return string
	 */
	function make_date_popups ($menu_name='', $sel_date = '') {
		// prepare vars for printing select menus
		$yr = date("Y");
		$eta_year = 0;

		$popup = '<select name="'. $menu_name . 'month">';
		// BEGIN print month popup
		for ($i = 1; $i <= 12; $i++) {
			$t_month = sprintf("%02d", $i);
			$popup .= "\n\t".'<option value="'.$t_month.'"';
			if ($t_month == $sel_date["month"]) { $popup .= " selected='selected'"; }
			$popup .= '>'.date("F", mktime(0,0,0,$t_month,01,$eta_year));
		}
		$popup .= "\n";
		// end print month popup

		$popup .= "</select>\n\t".'<select name="'. $menu_name . 'day">';
		for ($i=1;$i<=31;$i++) {
			$t_day = sprintf("%02d", $i);
			$popup .= "\n\t<option value=\"".$t_day.'"';
			if ($t_day == $sel_date["day"]) { $popup .= ' selected="selected"'; }
			$popup .= '>'.$i.'</option>';
		}
		$popup .= "\n";
		$popup .= "</select>\n\t".'<select name="'. $menu_name . 'year">';
		for ($i = -3; $i<=0; $i++) {
			$print_year = ($yr+$i);
			$popup .= "\n\t".'<option value="'.$print_year.'"';
			if ($print_year == $sel_date["year"]) { $popup .= ' selected="selected"'; }
			$popup .= '>'.$print_year.'</option>';
		}
		$popup .= "\n";
		$popup .= '</select><br/>';
		echo $popup;
		return True;
	}
}

// Check if there is an extended class in the Themes and if it is allowed to use them
// If the class is called outside Virtuemart, we have to make sure to load the settings
// Thomas Kahl - Feb. 2009
if (!defined('VM_ALLOW_EXTENDED_CLASSES') && file_exists(dirname(__FILE__).'/../virtuemart.cfg.php')) {
	include_once(dirname(__FILE__).'/../virtuemart.cfg.php');
}
// If settings are loaded, extended Classes are allowed and the class exisits...
if (defined('VM_ALLOW_EXTENDED_CLASSES') && defined('VM_THEMEPATH') && VM_ALLOW_EXTENDED_CLASSES && file_exists(VM_THEMEPATH.'user_class/'.basename(__FILE__))) {
	// Load the theme-user_class as extended
	include_once(VM_THEMEPATH.'user_class/'.basename(__FILE__));
} else {
	// Otherwise we have to use the original classname to extend the core-class
	class nh_report extends vm_nh_report {}
}

?>