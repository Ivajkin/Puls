<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_product_type.php 1905 2009-09-26 14:53:29Z soeren_nb $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2009 soeren - All rights reserved.
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
 * Product Type Handling Class
 *
 */
class vm_ps_product_type {

	/**
	 * Validates the Input Parameters onBeforeProductTypeAdd
	 * @author Zdenek Dvorak
	 * @param array $d
	 * @return boolean
	 */
	function validate_add(&$d) {
		global $VM_LANG;
		
		if (empty($d["product_type_name"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ERR_NAME') );
			return False;
		}
		else {
			return True;
		}
	}

	/**
	 * Validates the Input Parameters onBeforeProductTypeDelete
	 * @author Zdenek Dvorak
	 * @param int $product_type_id
	 * @param array $d
	 * @return boolean
	 */
	function validate_delete( $product_type_id, &$d) {
		global $VM_LANG;
		
		$db = new ps_DB;

		if (empty( $product_type_id)) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_DELETE_SELECT') );
			return False;
		}

		return True;
	}

	/**
	 * Validates the Input Parameters onBeforeProductTypeUpdate
	 * @author Zdenek Dvorak
	 * @param array $d
	 * @return boolean
	 */
	function validate_update(&$d) {
		global $VM_LANG;
		
		if (!$d["product_type_name"]) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ERR_NAME') );
			return False;
		}
		else {
			return True;
		}
	}

	/**
	 * creates a new Product Type record
	 * @author Zdenek Dvorak
	 *
	 * @param array $d
	 * @return boolean
	 */
	function add(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;

		if ($this->validate_add($d)) {

			// find product_type_id
			$q  = "SELECT MAX(product_type_id) AS product_type_id FROM #__{vm}_product_type";
			$db->query( $q );
			$db->next_record();
			$product_type_id = intval($db->f("product_type_id")) + 1;

			// Let's find out the last Product Type
			$q = "SELECT MAX(product_type_list_order) AS list_order FROM #__{vm}_product_type";
			$db->query( $q );
			$db->next_record();
			$list_order = intval($db->f("list_order"))+1;
			if ($d["product_type_publish"] != "Y") {
				$d["product_type_publish"] = "N";
			}
			
			$fields = array( 'product_type_id' => $product_type_id,
										'product_type_name' => vmGet($d, 'product_type_name' ),
										'product_type_description' => vmGet($d, 'product_type_description'),
										'product_type_publish' => vmGet($d, 'product_type_publish'),
										'product_type_browsepage' => vmGet($d, 'product_type_browsepage'),
										'product_type_flypage' => vmGet($d, 'product_type_flypage'),
										'product_type_list_order' => $list_order
										);			
			$db->buildQuery('INSERT', '#__{vm}_product_type', $fields );
			$db->query();
			
			$_REQUEST['product_type_id'] = $product_type_id;
			
			// Make new table product_type_<id>
			$q = "CREATE TABLE `#__{vm}_product_type_";
			$q .= $product_type_id . "` (";
			$q .= "`product_id` int(11) NOT NULL,";
			$q .= "PRIMARY KEY (`product_id`)";
			$q .= ") TYPE=MyISAM;";
			$db->setQuery($q);
			
			if( $db->query() === false ) {
				$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_TYPE_ADD_FAILED') );
				return false;
			} else {
				$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_PRODUCT_TYPE_ADDED') );
				return true;
			}
			
		}
		else {
			return False;
		}

	}

	/**
	 * updates Product Type information
	 * @author Zdenek Dvorak
	 * @param array $d
	 * @return boolean
	 */
	function update(&$d) {
		$db = new ps_DB;

		if ($this->validate_update($d)) {
			if (empty($d["product_type_publish"])) {
				$d["product_type_publish"] = "N";
			}
			$fields = array( 
										'product_type_name' => vmGet($d, 'product_type_name' ),
										'product_type_description' => vmGet($d, 'product_type_description'),
										'product_type_publish' => vmGet($d, 'product_type_publish'),
										'product_type_browsepage' => vmGet($d, 'product_type_browsepage'),
										'product_type_flypage' => vmGet($d, 'product_type_flypage'),
										'product_type_list_order' => vmRequest::getInt('list_order')
										);			
			$db->buildQuery('UPDATE', '#__{vm}_product_type', $fields, 'WHERE product_type_id=' .(int)$d["product_type_id"] );
			$db->query();

			// Re-Order the Product Type table IF the list_order has been changed
			if( intval($d['list_order']) != intval($d['currentpos'])) {
				$dbu = new ps_DB;

				/* Moved UP in the list order */
				if( intval($d['list_order']) < intval($d['currentpos']) ) {

					$q  = "SELECT product_type_id FROM #__{vm}_product_type WHERE ";
					$q .= "product_type_id <> '" . $d["product_type_id"] . "' ";
					$q .= "AND product_type_list_order >= '" . intval($d["list_order"]) . "'";
					$db->query( $q );

					while( $db->next_record() ) {
						$dbu->query("UPDATE #__{vm}_product_type SET product_type_list_order=product_type_list_order+1 WHERE product_type_id='".$db->f("product_type_id")."'");
					}
				}
				// Moved DOWN in the list order
				else {

					$q = "SELECT product_type_id FROM #__{vm}_product_type WHERE ";
					$q .= "product_type_id <> '" . $d["product_type_id"] . "' ";
					$q .= "AND product_type_list_order > '" . intval($d["currentpos"]) . "'";
					$q .= "AND product_type_list_order <= '" . intval($d["list_order"]) . "'";
					$db->query( $q );

					while( $db->next_record() ) {
						$dbu->query("UPDATE #__{vm}_product_type SET product_type_list_order=product_type_list_order-1 WHERE product_type_id='".$db->f("product_type_id")."'");
					}

				}
			} // END Re-Ordering

			return True;
		}
		else {
			return False;
		}
	}

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {

		$record_id = (int)$d["product_type_id"];
		require_once( CLASSPATH.'ps_product_type_parameter.php');
		
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
	 * Should delete a Product Type and drop table product_type_<id>
	 *
	 * @param int $record_id
	 * @param array $d
	 * @return boolean True on success
	 */
	function delete_record( $record_id, &$d ) {
		global $db;

		if (!$this->validate_delete( $record_id, $d)) {
			return False;
		}
		// Delete all product parameters from this product type
		$q = 'SELECT `parameter_name` FROM `#__{vm}_product_type_parameter` WHERE `product_type_id`='.$record_id;
		$db->query($q);
		while( $db->next_record() ) {
			if( !isset($ps_product_type_parameter)) { $ps_product_type_parameter = new ps_product_type_parameter(); }
			$arr['product_type_id'] = $record_id;
			$arr['parameter_name'] = $db->f('parameter_name');
			$ps_product_type_parameter->delete_parameter( $arr );
		}
		
		$q = "DELETE FROM #__{vm}_product_type WHERE product_type_id='$record_id'";
		$db->setQuery($q);   $db->query();

		$q  = "DELETE FROM #__{vm}_product_product_type_xref WHERE product_type_id='$record_id'";
		$db->setQuery($q);   $db->query();

		$q  = "DROP TABLE IF EXISTS `#__{vm}_product_type_".$record_id."`";
		$db->setQuery($q);   $db->query();
		return True;
	}

	/**
	 * Calculates and returns number of products assigned to this Product Type
	 *
	 * @param int $product_type_id
	 * @return int
	 */
	function product_count($product_type_id) {
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		$db = new ps_DB;

		$count  = "SELECT count(*) as num_rows from #__{vm}_product,#__{vm}_product_product_type_xref WHERE ";
		$q  = "#__{vm}_product.vendor_id = '$ps_vendor_id' ";
		$q .= "AND #__{vm}_product_product_type_xref.product_type_id='$product_type_id' ";
		$q .= "AND #__{vm}_product.product_id=#__{vm}_product_product_type_xref.product_id ";
		$q .= "AND #__{vm}_product.product_parent_id='' ";
		//$q .= "ORDER BY product_publish DESC,product_name ";
		$count .= $q;
		$db->query($count);
		$db->next_record();
		return $db->f("num_rows");
	}

	/**
	 * Calculates and returns number of parameters in given Product Type
	 *
	 * @param int $product_type_id
	 * @return int
	 */
	function parameter_count($product_type_id) {
		$db = new ps_DB;

		$count  = "SELECT count(*) as num_rows from #__{vm}_product_type_parameter WHERE ";
		$q = "product_type_id='$product_type_id' ";
		$count .= $q;
		$db->query($count);
		$db->next_record();
		return $db->f("num_rows");
	}

	/**
	 * Returns the Product Type name.
	 *
	 * @param unknown_type $product_type_id
	 * @return unknown
	 */
	function get_name($product_type_id) {
		$db = new ps_DB;

		$q = "SELECT product_type_name FROM #__{vm}_product_type WHERE product_type_id='$product_type_id' ";
		
		$db->query($q);
		$db->next_record();

		return $db->f("product_type_name");
	}

	/**
	 * Returns the Product Type Description
	 *
	 * @param int $product_type_id
	 * @return string
	 */
	function get_description($product_type_id) {
		$db = new ps_DB;

		$q = "SELECT product_type_description FROM #__{vm}_product_type ";
		$q .= "WHERE product_type_id='$product_type_id' ";
		
		$db->query($q);
		$db->next_record();

		return $db->f("product_type_description");
	}

	/**
	 * lists all Product Types
	 *
	 * @param int $product_type_id
	 * @param int $list_order
	 * @return string
	 */
	function list_order( $product_type_id='0', $list_order=0 ) {

		$db = new ps_DB;
		if (!$product_type_id) {
			return $GLOBALS['VM_LANG']->_( 'CMN_NEW_ITEM_LAST' );
		}
		else {

			$q  = "SELECT product_type_list_order,product_type_name FROM #__{vm}_product_type ";
			if( $product_type_id ) {
				$q .= 'WHERE product_type_id='.$product_type_id;
			}
			$q .= " ORDER BY product_type_list_order ASC";
			$db->query( $q );

			$array = array();
			while( $db->next_record() ) {
				$array[$db->f("product_type_list_order")] = $db->f("product_type_list_order").". ".$db->f("product_type_name");
			}
			
			return ps_html::selectList('list_order', $list_order, $array );
		}
	}

	/**
	 * Changes the Product Type List Order
	 *
	 * @param array $d
	 */
	function reorder( &$d ) {
		$cb = vmGet( $_POST, 'product_type_id', array(0) );

		$db = new ps_DB;
		switch( $d["task"] ) {
			case "orderup":
				$q = "SELECT product_type_list_order FROM #__{vm}_product_type ";
				$q .= "WHERE product_type_id='".(int)$cb[0]."' ";
				$db->query($q);
				$db->next_record();
				$currentpos = $db->f("product_type_list_order");
				//$category_parent_id = $db->f("category_parent_id");

				// Get the (former) predecessor and update it
				$q  = "SELECT product_type_list_order,product_type_id FROM #__{vm}_product_type WHERE ";
				$q .= "product_type_list_order<'". $currentpos . "' ";
				$q .= "ORDER BY product_type_list_order DESC";
				$db->query($q);
				$db->next_record();
				$pred = $db->f("product_type_id");
				$pred_pos = $db->f("product_type_list_order");

				// Update the Product Type and decrease the list_order
				$q = "UPDATE #__{vm}_product_type ";
				$q .= "SET product_type_list_order='".$pred_pos."' ";
				$q .= "WHERE product_type_id='".(int)$cb[0]."'";
				$db->query($q);

				$q = "UPDATE #__{vm}_product_type ";
				$q .= "SET product_type_list_order='".intval($pred_pos + 1)."' ";
				$q .= "WHERE product_type_id='$pred'";
				$db->query($q);

				break;

			case "orderdown":
				$q = "SELECT product_type_list_order FROM #__{vm}_product_type ";
				$q .= "WHERE product_type_id='".(int)$cb[0]."' ";
				$db->query($q);
				$db->next_record();
				$currentpos = $db->f("product_type_list_order");

				// Get the (former) successor and update it
				$q  = "SELECT product_type_list_order,product_type_id FROM #__{vm}_product_type WHERE ";
				$q .= "product_type_list_order>'". $currentpos . "' ";
				$q .= "ORDER BY product_type_list_order";
				$db->query($q);
				$db->next_record();
				$succ = $db->f("product_type_id");
				$succ_pos = $db->f("product_type_list_order");

				$q = "UPDATE #__{vm}_product_type ";
				$q .= "SET product_type_list_order='".$succ_pos."' ";
				$q .= "WHERE product_type_id='".(int)$cb[0]."' ";
				$db->query($q);

				$q = "UPDATE #__{vm}_product_type ";
				$q .= "SET product_type_list_order='".intval($succ_pos - 1)."' ";
				$q .= "WHERE product_type_id='$succ'";
				$db->query($q);

				break;
		}

	}

	/**
	 * Returns the parameter list for form (hiden items)
	 * @author Zdenek Dvorak
	 *
	 * @param int $product_type_id
	 * @return string
	 */
	function get_parameter_form($product_type_id='0') {
		$db = new ps_DB;
		$q  = "SELECT * FROM #__{vm}_product_type_parameter ";
		$q .= "WHERE product_type_id='$product_type_id'";
		$db->query($q);

		$html = "";
		while ($db->next_record()) {
			if ($db->f("parameter_type")!="B") { // not Break line
				$item_name = "product_type_$product_type_id"."_".$db->f("parameter_name");
				if ($db->f("parameter_multiselect")=="Y" && $db->f("parameter_values")) { // Multiple section List of values
					$get_item_value = vmGet($_REQUEST, $item_name, array());
					foreach($get_item_value as $value) {
						$html .= "<input type=\"hidden\" id=\"$value\" name=\"".$item_name."[]\"  value=\"".$value."\" />\n";
					}
					$html .= "<input type=\"hidden\" name=\"".$item_name."_comp\"  value=\"";
					$html .= vmGet($_REQUEST, $item_name."_comp", "")."\" />\n";
				}
				else {
					$html .= "<input type=\"hidden\" name=\"".$item_name."\"  value=\"";
					$html .= vmGet($_REQUEST, $item_name, "");
					$html .= "\" />\n";
					// comparison
					$html .= "<input type=\"hidden\" name=\"".$item_name."_comp\"  value=\"";
					$html .= vmGet($_REQUEST, $item_name."_comp", "");
					$html .= "\" />\n";
				}
			}
		}
		// item for price search
		$html .= "<input type=\"hidden\" name=\"price\" value=\"".vmGet($_REQUEST,"price", "")."\" />\n";
		$html .= "<input type=\"hidden\" name=\"price_comp\" value=\"".vmGet($_REQUEST,"price_comp", "")."\" />\n";

		return $html;
	}

	/**
	 * Returns html code for show parameters in select ORDER BY
	 * @author Zdenek Dvorak
	 *
	 * @param int $product_type_id
	 * @param string $orderby
	 */
	function get_parameter_order_list($product_type_id,$orderby="") {
		$db = new ps_DB;
		$q  = "SELECT * FROM #__{vm}_product_type_parameter ";
		$q .= "WHERE product_type_id=$product_type_id ";
		$q .= "AND parameter_type<>'B' "; // NO Break Line
		$q .= "ORDER BY parameter_list_order";
		$db->query($q);
		while ($db->next_record()) {
			$value = "pshop_product_type_".$product_type_id.".".$db->f("parameter_name");
			echo "<option value=\"$value\" ";
			if ($orderby == $value) echo "selected ";
			echo ">".$db->f("parameter_label")."</option>\n";
		}
	}

	/**
	 * Returns true if the product is in a Product Type
	 * @author Zdenek Dvorak
	 *
	 * @param int $product_id
	 * @return boolean
	 */
	function product_in_product_type($product_id) {
		$db = new ps_DB;
		$q = "SELECT * FROM #__{vm}_product_product_type_xref WHERE product_id='$product_id'";
		$db->query($q);
		return $db->num_rows() > 0;
	}

	/**
	 * Returns html code for show parameters
	 * @author Zdenek Dvorak
	 *
	 * @param int $product_id
	 * @return string
	 */
	function list_product_type($product_id) {
		global $VM_LANG;
		$tpl = vmTemplate::getInstance();
		if (!$this->product_in_product_type($product_id)) {
			return "";
		}
		// $dbag = product_types;
		$dbag = new ps_DB;
		// $dba = Attributes of product_type param, holds product_id and values assign to each param;
		$dba = new ps_DB;
		// $dbp = Parameters of product_type, holds definitions of each parameter, but not value ;
		$dbp = new ps_DB;
		$html ="";
		$q  = "SELECT * FROM #__{vm}_product_product_type_xref ";
		$q .= "LEFT JOIN #__{vm}_product_type USING (product_type_id) ";
		$q .= "WHERE product_id='$product_id' AND product_type_publish='Y' ";
		$q .= "ORDER BY product_type_list_order";
		$dbag->query( $q );
		$q  = "SELECT * FROM #__{vm}_product_type_parameter ";
		$q .= "WHERE product_type_id=";
		$pt = 0; //product_type counter;
		while ($dbag->next_record()) { // Show all Product Type
			if ($dbag->f("product_type_flypage")) {
				$flypage_file = VM_THEMEPATH."templates/".$dbag->f("product_type_flypage").".php";
				if (file_exists($flypage_file)) {
					$html .= include($flypage_file);
					continue;
				}
			}
			$product_types[$pt]["product_type_name"] = $dbag->f("product_type_name");
			// SELECT parameter value of product
			$q2  = "SELECT * FROM #__{vm}_product_type_".$dbag->f("product_type_id");
			$q2 .= " WHERE product_id='$product_id'";
			$dbp->query($q2);
			// SELECT parameter of Product Type
			$dba->query($q.$dbag->f("product_type_id")." ORDER BY parameter_list_order");
			$i=0; // parameter counter;
			while ($dba->next_record()) {
				$product_type_param[$i]["parameter_label"] = $dba->f("parameter_label");
				$parameter_description = $dba->f("parameter_description");
				$product_type_param[$i]["parameter_description"] = $parameter_description;
				if (!empty($parameter_description)) {
					$product_type_param[$i]["tooltip"] = vmToolTip($parameter_description, $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_DESCRIPTION'));
				}
				$product_type_param[$i]["parameter_value"] = $dbp->f($dba->f("parameter_name"));
				$product_type_param[$i]["parameter_unit"] = $dba->f("parameter_unit");
				$i++;
			}
			$product_types[$pt]["product_type_count_params"] = $i;
			$product_types[$pt]["parameters"] = $product_type_param;
			$pt++;
		}
		
		$tpl->set( 'product_types', $product_types );
		$html .= $tpl->fetch( 'common/product_type.tpl.php' ) ;
			return $html;
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
	class ps_product_type extends vm_ps_product_type {}
}
?>
