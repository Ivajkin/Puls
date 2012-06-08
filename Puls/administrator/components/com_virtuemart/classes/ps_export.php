<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_export.php 1660 2009-02-22 17:05:02Z tkahl $
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


class vm_ps_export {

	/**
	* validate order export module add
	* @param array
	* @return bool
	* @author Manfred Dennerlein Rodelo <manni@zapto.de>
	*/
	function validate_add(&$d) {
		global $vmLogger, $VM_LANG;
		$db = new ps_DB;

		if (!$d['export_name']) {
			$vmLogger->err( $VM_LANG->_('VM_EXPORT_ERR_MODULE_NAME') );
			return False;
		}
		if (empty($d['export_enabled'])) {
			$d['export_enabled'] = 'N';
		}
		if(empty($d['export_class'])) {
			$d['export_class'] = 'ps_xmlexport';
		}
		if(!file_exists( CLASSPATH.'export/'.$d['export_class'].'.php' ) ) {
			$vmLogger->err( $VM_LANG->_('VM_EXPORT_ERR_CLASS_NOT_EXIST') );
			return false;
		}
		$d['export_config'] = vmGet( $_POST, 'export_config', '', VMREQUEST_ALLOWHTML );
		$d['export_config'] = addslashes( $d['export_config'] );
		
		return True;
	}
	/**
	* validate order export module deletion
	* @param array
	* @return bool
	* @author Manfred Dennerlein Rodelo <manni@zapto.de>
	*/
	function validate_delete($d) {
		global $vmLogger, $VM_LANG;
		if (!$d['export_id']) {
			$vmLogger->err( $VM_LANG->_('VM_EXPORT_ERR_DELETE_SELECT') );
			return False;
		}
		else {
			return True;
		}
	}

	/**
	* validate order export module update
	* @param array
	* @return bool
	* @author Manfred Dennerlein Rodelo <manni@zapto.de>
	*/
	function validate_update(&$d) {
		global $vmLogger, $VM_LANG;
		$db = new ps_DB;

		if (!$d['export_id']) {
			$vmLogger->err( $VM_LANG->_('VM_EXPORT_ERR_UPDATE_SELECT') );
			return False;
		}
		if (!$d['export_name']) {
			$vmLogger->err( $VM_LANG->_('VM_EXPORT_ERR_MODULE_NAME') );
			return False;
		}
		if(!file_exists( CLASSPATH.'export/'.$d['export_class'].'.php' ) ) {
			$vmLogger->err( $VM_LANG->_('VM_EXPORT_ERR_CLASS_NOT_EXIST') );
			return false;
		}
		$d['export_config'] = vmGet( $_POST, 'export_config', '', VMREQUEST_ALLOWHTML );
		$d['export_config'] = addslashes( $d['export_config'] );
		
		return True;
	}


	/**
	* Add an export module
	* @param array
	* @return bool
	* @author Manfred Dennerlein Rodelo <manni@zapto.de>
	*/
	function add(&$d) {
		global $vmLogger, $VM_LANG,  $mosConfig_absolute_path;
		$db = new ps_DB;
		$ps_vendor_id = $_SESSION['ps_vendor_id'];
		$timestamp = time();
		$export_class = basename($d['export_class']);
		if ( !empty($d['export_class']) ) {
			// Here we have a custom export class
			if( file_exists( CLASSPATH.'export/'.$export_class.'.php' ) ) {
				// Include the class code and create an instance of this class
				include_once( CLASSPATH.'export/'.$export_class.'.php' );
				$_EXPORT = new $export_class();
			}
		} else {
			// ps_xmlexport is the default export method handler
			include_once( CLASSPATH."export/ps_xmlexport.php" );
			$_EXPORT = new ps_xmlexport();
		}
		
		if(method_exists($_EXPORT, 'process_installation')) {
			$d = $_EXPORT->process_installation($d);
		}
		
		if (!$this->validate_add($d)) {
			return False;
		}
		
		if( $_EXPORT->configfile_writeable() ) {
			$_EXPORT->write_configuration( $d );
		}
		
		$fields = array( 'vendor_id' => $ps_vendor_id,
								'export_name' => $d['export_name'],
								'export_desc' => $d['export_desc'], 
								'export_class' => $d['export_class'], 
								'export_enabled' => $d['export_enabled'], 
								'export_config' => $d['export_config'],
								'iscore' => 0 );
		$db->buildQuery( 'INSERT', '#__{vm}_export', $fields );
		
		return $db->query() !== false;

	}

	/**
	* update export module
	* @param array
	* @return bool
	* @author Manfred Dennerlein
	*/
	function update(&$d) {
		global $vmLogger, $VM_LANG;
		$db = new ps_DB;
		$ps_vendor_id = $_SESSION['ps_vendor_id'];
		$timestamp = time();

		if (!$this->validate_update($d)) {
			return False;
		}
		
		if ( !empty($d['export_class']) ) {
			$export_class = basename($d['export_class']);
			if (include_once( CLASSPATH.'export/'.$export_class.'.php' )) {
				$_EXPORT = new $export_class();
			}
		}
		else {
			include_once( CLASSPATH.'export/ps_xmlexport.php' );
			$_EXPORT = new ps_xmlexport();
		}
		if( $_EXPORT->configfile_writeable() ) {
			$_EXPORT->write_configuration( $d );
			$vmLogger->info( $VM_LANG->_('VM_CONFIGURATION_CHANGE_SUCCESS',false) );
		}
		else {
			$vmLogger->err( sprintf($VM_LANG->_('VM_CONFIGURATION_CHANGE_FAILURE',false) , CLASSPATH."export/".$_EXPORT->classname.".cfg.php" ) );
			return false;
		}

		$fields = array( 	'export_enabled' => $d['export_enabled'], 
								'export_config' => $d['export_config'] );
		
		if(!$d['iscore']) {
			$fields['export_name'] = $d['export_name'];
			$fields['export_desc'] = $d['export_desc'];
			$fields['export_class'] = $d['export_class'];
		}
		$db->buildQuery( 'INSERT', '#__{vm}_export', $fields, 'WHERE export_id=' .(int)$d['export_id']." AND vendor_id='$ps_vendor_id'" );
		
		return $db->query() !== false;
		
	}


	/**
	* Controller for Deleting Records.
	* @param array
	* @return bool
	* @author Manfred Dennerlein
	*/
	function delete(&$d) {

		if (!$this->validate_delete($d)) {
			return False;
		}
		$record_id = $d['export_id'];

		if( is_array( $record_id)) {
			foreach( $record_id as $record) {
				if( !$this->delete_record( $record, $d ))
				return false;
			}
			return true;
		}
		else {
			return $this->delete_record( $record_id, $d );
		}
	}

	/**
	* delete order export module update
	* @param array
	* @return bool
	* @author Manfred Dennerlein Rodelo <manni@zapto.de>
	*/
	function delete_record( $record_id, &$d ) {
		global $db;
		$ps_vendor_id = $_SESSION['ps_vendor_id'];

		$q = "DELETE from #__{vm}_export WHERE export_id='$record_id' LIMIT 1";
		$q .= " AND vendor_id='$ps_vendor_id'";
		$db->query($q);
		return True;
	}

/**
 * Enter description here...
 *
 * @param unknown_type $name
 * @param unknown_type $preselected
 * @return unknown
 */
	function list_available_classes( $name, $preselected='ps_xmlexport' ) {

		$files = vmReadDirectory( CLASSPATH."export/", ".php", true, true);
		$array = array();
		foreach ($files as $file) {
			$file_info = pathinfo($file);
			$filename = $file_info['basename'];
			if( stristr($filename, '.cfg')) { continue; }
			$array[basename($filename, '.php' )] = basename($filename, '.php' );
		}
		return ps_html::selectList( $name, $preselected, $array );
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
	class ps_export extends vm_ps_export {}
}
?>
