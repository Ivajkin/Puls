<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_product_product_type.php 1698 2009-03-13 20:56:39Z macallf $
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

/**
 * Product Type Management Class
 *
 */
class vm_ps_product_product_type {
  
	/**
	 * Validates the Input Parameters onBeforeProductTypeAdd
	 * @author Zdenek Dvorak
	 * @param array $d
	 * @return boolean
	 */
  function validate_add(&$d) {
  	global $VM_LANG;
    
    if (empty($d["product_type_id"])) {
      $GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ERR_SELECT') );
      return False;
    }
    if (empty($d["product_id"])) {
      $GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ERR_SELECT_PRODUCT') );
      return false;
    }
    $db = new ps_DB;
    $q  = "SELECT product_id,COUNT(*) AS count FROM #__{vm}_product_product_type_xref ";
    if(is_array($d["product_id"])) {
    	$product_ids = implode(",",$d["product_id"]);
    	$q .= "WHERE product_id IN (".$product_ids.") AND product_type_id='".$d["product_type_id"]."' GROUP BY product_id";
    } else {
    	$q .= "WHERE product_id='".$d["product_id"]."' AND product_type_id='".$d["product_type_id"]."'";
    }
    $db->query($q);
    if ($db->f("count") != 0 && sizeof($d["product_id"]) == 1) {
      $GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ERR_ALREADY') );
      return false;
    }
    else {
    	$container = $d["product_id"];
    	while($db->next_record()) {
    		foreach($d["product_id"] as $prod_id) {
    			if($prod_id != $db->f("product_id")) {
    				$temp[] = $prod_id;
    			}
    		}
    		$d["product_id"] = $temp;
    		unset($temp);
    	}
    	if(empty($d["product_id"])) {
    		$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ERR_ALREADY') );
    		$d["product_id"] = $container;
       		return false;
    	}
      	return True;    
    }
  }

  /**
   * Validates the Input Parameters onBeforeProductTypeDelete
   * @author Zdenek Dvorak
   * @param array $d
   * @return boolean
   */
  function validate_delete(&$d) {
  	global $VM_LANG;

    if (empty($d["product_type_id"])) {
      $GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_PRODUCT_TYPE_DELETE_SELECT_PT') );
      return False;
    }
    if (empty($d["product_id"])) {
      $GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_PRODUCT_TYPE_DELETE_SELECT_PR') );
      return false;
    }

    return True;
  }

  /**
   * add a Product into a Product Type
   * @author Zdenek Dvorak
   *
   * @param array $d
   * @return boolean
   */
  function add(&$d) {
  	global $VM_LANG;
  	
    $db = new ps_DB;
    if ($this->validate_add($d)) {
    	if(is_array($d["product_id"])) {
  			$q2 = "VALUES ";
  			$q3 = "VALUES ";  		
   			foreach($d["product_id"] as $product_id) {
				$q2 .= "('".$product_id."','".$d["product_type_id"]."'),";
				$q3 .= "('".$product_id."'),";
			}
			$q2 = substr($q2,0,-1);
			$q3 = substr($q3,0,-1);
    	} else {
      			$q2 .= "VALUES ('".$d["product_id"]."','".$d["product_type_id"]."')";
    		    $q3 .= "VALUES ('".$d["product_id"]."')";
    	} 
      $q  = "INSERT INTO #__{vm}_product_product_type_xref (product_id, product_type_id) ";
      $q .= $q2;
      $db->query($q);
      $q  = "INSERT INTO #__{vm}_product_type_".$d["product_type_id"]." (product_id) ";
      $q .= $q3;
      $db->query($q);
      $GLOBALS['vmLogger']->info( $VM_LANG->_('VM_PRODUCT_PRODUCT_TYPE_ASSIGNED') );
      return true;
    }
    else {
    	return False;
    }

  }

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {
		
		if (!$this->validate_delete($d)) {
		  return False;
		}
		
		$record_id = $d["product_type_id"];
		
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
	* Deletes one Record.
	*/
	function delete_record( $record_id, &$d ) {
		global $db;
	
		$q  = 'DELETE FROM #__{vm}_product_product_type_xref WHERE product_type_id='.(int)$record_id;
		$q .= " AND product_id='".$d["product_id"]."'";
		$db->setQuery($q);   $db->query();
	
		$q  = "DELETE FROM #__{vm}_product_type_".(int)$record_id." WHERE product_id='".$d["product_id"]."'";
		$db->query($q);
		
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
	class ps_product_product_type extends vm_ps_product_product_type {}
}
?>
