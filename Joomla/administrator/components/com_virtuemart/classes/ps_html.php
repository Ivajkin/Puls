<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* This Class provides some utility functions
* to easily create drop-down lists
*
* @version $Id: ps_html.php 2574 2010-10-10 13:56:28Z zanardi $
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

class vm_ps_html {


	function dropdown_display($name, $value, &$arr, $size=1, $multiple="", $extra="") {
		echo ps_html::selectList($name, $value, $arr, $size, $multiple, $extra );
	}

	/**
	 * Prints an HTML dropdown box named $name using $arr to
	 * load the drop down.  If $value is in $arr, then $value
	 * will be the selected option in the dropdown.
	 * @author gday
	 * @author soeren
	 * 
	 * @param string $name The name of the select element
	 * @param string $value The pre-selected value
	 * @param array $arr The array containting $key and $val
	 * @param int $size The size of the select element
	 * @param string $multiple use "multiple=\"multiple\" to have a multiple choice select list
	 * @param string $extra More attributes when needed
	 * @return string HTML drop-down list
	 */	
	function selectList($name, $value, &$arr, $size=1, $multiple="", $extra="") {
		$html = '';
		if( empty( $arr ) ) {
			$arr = array();
		}
		$html = "<select class=\"inputbox\" name=\"$name\" size=\"$size\" $multiple $extra>\n";

		foreach($arr as $key => $val) {
			$selected = "";
			if( is_array( $value )) {
				if( in_array( $key, $value )) {
					$selected = "selected=\"selected\"";
				}
			}
			else {
				if(strtolower($value) == strtolower($key) ) {
					$selected = "selected=\"selected\"";
				}
			}
			$html .= "<option value=\"$key\" $selected>".shopMakeHtmlSafe($val);
			$html .= "</option>\n";
		}

		$html .= "</select>\n";
		
		return $html;
	}
	function yesNoSelectList( $fieldname, $value, $yesValue=1, $noValue=0 ) {
		global $VM_LANG;
		$values = array($yesValue => $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES'),
								$noValue => $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO'));
		return ps_html::selectList($fieldname, $value, $values );
	}
	/**
	 * Creates a Radio Input List
	 *
	 * @param string $name
	 * @param string $value
	 * @param string $arr
	 * @param string $extra
	 * @return string
	 */
	function radioList($name, $value, &$arr, $extra="") {
		$html = '';
		if( empty( $arr ) ) {
			$arr = array();
		}
		$html = '';
		$i = 0;
		while (list($key, $val) = each($arr)) {
			$checked = '';
			if( is_array( $value )) {
				if( in_array( $key, $value )) {
					$checked = 'checked="checked"';
				}
			}
			else {
				if(strtolower($value) == strtolower($key) ) {
					$checked = 'checked="checked"';
				}
			}
			$html .= '<input type="radio" name="'.$name.'" id="'.$name.$i.'" value="'.htmlspecialchars($key, ENT_QUOTES).'" '.$checked.' '.$extra." />\n";
			$html .= '<label for="'.$name.$i++.'">'.$val."</label>\n";
		}
		
		return $html;
	}

	/**
	 * Lists titles for people
	 *
	 * @param string $t The selected title value
	 * @param string $extra More attributes when needed
	 */
	function list_user_title($t, $extra="") {
		global $VM_LANG;

		$title = array($VM_LANG->_('PHPSHOP_REGISTRATION_FORM_MR'),
						$VM_LANG->_('PHPSHOP_REGISTRATION_FORM_MRS'),
						$VM_LANG->_('PHPSHOP_REGISTRATION_FORM_MISS'),
						$VM_LANG->_('PHPSHOP_REGISTRATION_FORM_MS'),
						$VM_LANG->_('PHPSHOP_REGISTRATION_FORM_DR'),
						$VM_LANG->_('PHPSHOP_REGISTRATION_FORM_PROF'));
		echo "<select class=\"inputbox\" name=\"title\" $extra>\n";
		echo "<option value=\"\">".$VM_LANG->_('PHPSHOP_REGISTRATION_FORM_NONE')."</option>\n";
		for ($i=0;$i<count($title);$i++) {
			if ($title[$i] == "") continue;
			echo "<option value=\"" . $title[$i]."\"";
			if ($title[$i] == $t)
			echo " selected=\"selected\" ";
			echo ">" . $title[$i] . "</option>\n";
		}
		echo "</select>\n";

	}

	/**
	 * Creates an drop-down list with numbers from 1 to 31 or of the selected range
	 *
	 * @param string $list_name The name of the select element
	 * @param string $selected_item The pre-selected value
	 */
	function list_days($list_name,$selected_item='', $start=null, $end=null) {
		if( $selected_item == '') {
			$selected_item = date('d');
		}
		$start = $start ? $start : 1;
		$end = $end ? $end : $start + 30;
		$list = array('Day');
		for ($i=$start; $i<=$end; $i++) {
			$list[$i] = $i;
		}
		ps_html::dropdown_display($list_name, $selected_item, $list);
	}
	/**
	 * Creates a Drop-Down List for the 12 months in a year
	 *
	 * @param string $list_name The name for the select element
	 * @param string $selected_item The pre-selected value
	 * 
	 */
	function list_month($list_name, $selected_item="") {
		global $VM_LANG;
		if( $selected_item == '') {
			$selected_item = date('m');
		}
		$list = array("Month",
		"01" => $VM_LANG->_('JAN'),
		"02" => $VM_LANG->_('FEB'),
		"03" => $VM_LANG->_('MAR'),
		"04" => $VM_LANG->_('APR'),
		"05" => $VM_LANG->_('MAY'),
		"06" => $VM_LANG->_('JUN'),
		"07" => $VM_LANG->_('JUL'),
		"08" => $VM_LANG->_('AUG'),
		"09" => $VM_LANG->_('SEP'),
		"10" => $VM_LANG->_('OCT'),
		"11" => $VM_LANG->_('NOV'),
		"12" => $VM_LANG->_('DEC'));
		ps_html::dropdown_display($list_name, $selected_item, $list);
	}

	/**
	 * Creates an drop-down list with years of the selected range or of the next 7 years
	 *
	 * @param string $list_name The name of the select element
	 * @param string $selected_item The pre-selected value
	 */
	function list_year($list_name,$selected_item='', $start=null, $end=null) {
		$start = $start ? $start : date('Y');
		$end = $end ? $end : $start + 7;
		for ($i=$start; $i<=$end; $i++) {
			$list[$i] = $i;
		}
		ps_html::dropdown_display($list_name, $selected_item, $list);
		
	}


	function list_country($list_name, $value="", $extra="") {
		echo ps_html::getCountryList($list_name, $value, $extra);
	}

	/**
	 * Creates a drop-down list for all countries
	 *
	 * @param string $list_name The name of the select element
	 * @param string $value The value of the pre-selected option
	 * @param string $extra More attributes for the select element when needed
	 * @return string The HTML code for the select list
	 */	
	function getCountryList($list_name, $value="", $extra="") {
		global $VM_LANG;

		$db = new ps_DB;

		$q = "SELECT country_id, country_name, country_3_code from #__{vm}_country ORDER BY country_id ASC";
		$db->query($q);
		while ($db->next_record()) {
			$countries[$db->f("country_3_code")] = $db->f("country_name");
		}
		asort($countries);
		array_unshift($countries, $VM_LANG->_('PHPSHOP_SELECT'));
		
		return ps_html::selectList( $list_name, $value, $countries, 1, '', $extra );
	}
	
	/**
	 * Creates a drop-down list for states [filtered by country_id]
	 *
	 * @param string $list_name The name of the select element
	 * @param string $selected_item The value of the pre-selected option
	 * @param int $country_id The ID of a country to filter states from
	 * @param string $extra More attributes for the select element when needed
	 * @return HTML code with the drop-down list
	 */
	function list_states($list_name,$selected_item="", $country_id="", $extra="") {
		global $VM_LANG;

		$db = new ps_DB;
		$q = 'SELECT country_name, state_name, state_3_code , state_2_code 
				FROM #__{vm}_state s, #__{vm}_country c 
				WHERE s.country_id = c.country_id';
		if( !empty( $country_id )) {
			$q .= ' AND c.country_id='.(int)$country_id;
		}
		$q .= "\nORDER BY country_name, state_name";
		$db->query( $q );
		$list = Array();
		$list["0"] = $VM_LANG->_('PHPSHOP_SELECT');
		$list["NONE"] = "not listed";
		$country = "";

		while( $db->next_record() ) {
			if( $country != $db->f("country_name")) {
				$list[] = "------- ".$db->f("country_name")." -------";
				$country = $db->f("country_name");
			}
			$list[$db->f("state_2_code")] = $db->f("state_name");
		}

		$this->dropdown_display($list_name, $selected_item, $list,"","",$extra);
		return 1;
	}
	/**
	 * Creates a Javascript based dynamic state list, depending of the selected
	 * country of a country drop-down list (specified by $country_list_name)
	 *
	 * @param string $country_list_name The name of the country select list element
	 * @param string $state_list_name The name for this states drop-down list
	 * @param string $selected_country_code The 3-digit country code that is pre-selected
	 * @param string $selected_state_code The state code of a pre-selected state
	 * @return string HTML code containing the dynamic state list
	 */
	function dynamic_state_lists( $country_list_name, $state_list_name, $selected_country_code="", $selected_state_code="" ) {
		global $vendor_country_3_code, $VM_LANG, $vm_mainframe, $mm_action_url, $page;
		$db = new ps_DB;
		if( empty( $selected_country_code )) {
			$selected_country_code = $vendor_country_3_code;
		}

		if( empty( $selected_state_code )) {
			$selected_state_code = "originalPos";
		} else {
			$selected_state_code = "'".$selected_state_code."'";
		}

		$db->query( "SELECT c.country_id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__{vm}_country c
						LEFT JOIN #__{vm}_state s 
						ON c.country_id=s.country_id OR s.country_id IS NULL
						ORDER BY c.country_id, s.state_name" );

		if( $db->num_rows() > 0 ) {
			if( !vmIsAdminMode() ) {
				$vm_mainframe->addScript( $mm_action_url.'includes/js/mambojavascript.js');
				$vm_mainframe->addScript( $mm_action_url.'includes/js/joomla.javascript.js');
			}
			// Build the State lists for each Country
			$script = "<script language=\"javascript\" type=\"text/javascript\">//<![CDATA[\n";
			$script .= "<!--\n";
			$script .= "var originalOrder = '1';\n";
			$script .= "var originalPos = '$selected_country_code';\n";
			$script .= "var states = new Array();	// array in the format [key,value,text]\n";
			$i = 0;
			$prev_country = '';
			while( $db->next_record() ) {
				$country_3_code = $db->f("country_3_code");
				if( $db->f('state_name') ) {
					// Add 'none' to the list of countries that have states:
					if( $prev_country != $country_3_code  && $page == 'tax.tax_form' ) {
						$script .= "states[".$i++."] = new Array( '".$country_3_code."',' - ','".$VM_LANG->_('PHPSHOP_NONE')."' );\n";
					}
					elseif( $prev_country != $country_3_code ) {
						$script .= "states[".$i++."] = new Array( '".$country_3_code."','',' -= ".$VM_LANG->_('PHPSHOP_SELECT')." =-' );\n";
					}
					$prev_country = $country_3_code;

					// array in the format [key,value,text]
					$script .= "states[".$i++."] = new Array( '".$country_3_code."','".$db->f("state_2_code")."','".addslashes($db->f("state_name"))."' );\n";
				}
				else {
					$script .= "states[".$i++."] = new Array( '".$country_3_code."',' - ','".$VM_LANG->_('PHPSHOP_NONE')."' );\n";
				}

			}
			$script .= "
			function changeStateList() { 
			  var selected_country = null;
			  for (var i=0; i<document.adminForm.".$country_list_name.".length; i++)
				 if (document.adminForm.".$country_list_name."[i].selected)
					selected_country = document.adminForm.".$country_list_name."[i].value;
			  changeDynaList('".$state_list_name."',states,selected_country, originalPos, originalOrder);
			  
			}
			writeDynaList( 'class=\"inputbox\" name=\"".$state_list_name."\" size=\"1\" id=\"state\"', states, originalPos, originalPos, $selected_state_code );
			//-->
			//]]></script>";

			return $script;
		}
	}


	/**
	 * Creates a drop-down list for weight units-of-measure
	 *
	 * @param string $list_name The name for the select element
	 * @return string The HTML code for the select list
	 */
	function list_weight_uom($list_name) {
		global $VM_LANG;

		$list = array($VM_LANG->_('PHPSHOP_SELECT'),
		"LBS" => "Pounds",
		"KGS" => "Kilograms",
		"G" => "Grams");
		$this->dropdown_display($list_name, "", $list);
		return 1;
	}

	function list_currency($list_name, $value="") {
		echo ps_html::getCurrencyList($list_name, $value, 'currency_code');
	}
	/**
	 * Creates a drop-down list for currencies. The currency ID is used as option value
	 *
	 * @param string $list_name The name of the select element
	 * @param string $value The value of the pre-selected option
	 * @return HTML code with the drop-down list
	 */
	function list_currency_id($list_name, $value="") {
		echo ps_html::getCurrencyList($list_name, $value, 'currency_id');
	}
	
	/**
	 * Creates a drop-down list for currencies.
	 *
	 * @param string $list_name The name of the select element
	 * @param string $value The value of the pre-selected option
	 * @param string $key The name of the field that will be the array index [curreny_code|currency_id]
	 * @return HTML code with the drop-down list
	 */	
	function getCurrencyList($list_name, $value="", $key='currency_code', $extra='', $size=1, $multiple='') {
		global $VM_LANG;
		$db = new ps_DB;

		$q = "SELECT `currency_id`, `currency_code`, `currency_name` FROM `#__{vm}_currency` ORDER BY `currency_name` ASC";
		$db->query($q);
		
		if( $size == 1 ) {
			$currencies[''] = $VM_LANG->_('PHPSHOP_SELECT');
		}
		while ($db->next_record()) {
			$currencies[$db->f($key)] = $db->f("currency_name");
		}
		
		return ps_html::selectList( $list_name, $value, $currencies, $size, $multiple, $extra );
	}


	/**
	 * This is the equivalent to mosCommonHTML::idBox
	 * 
	 * @param int The row index
	 * @param int The record id
	 * @param string The name of the form element
	 * @param string The name of the checkbox element
	 * @return string
	 */
	function idBox( $rowNum, $recId, $frmName="adminForm", $name='cid' ) {

		return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="ms_isChecked(this.checked, \''.$frmName.'\');" />';

	}
	/**
	 * Creates a multi-select list with all products except the given $product_id
	 *
	 * @param string $list_name The name of the select element
	 * @param array $values Contains the IDs of all products which are pre-selected
	 * @param int $product_id The product id that is excluded from the list
	 * @param boolean $show_items Wether to show child products as well
	 */
	function list_products($list_name, $values=array(), $product_id, $show_items=false ) {

		$db = new ps_DB;

		$q = "SELECT #__{vm}_product.product_id,category_name,product_name
			FROM #__{vm}_product,#__{vm}_product_category_xref,#__{vm}_category ";
		if( !$show_items ) {
			$q .= "WHERE product_parent_id='0'
					AND #__{vm}_product.product_id <> '$product_id' 
					AND #__{vm}_product.product_id=#__{vm}_product_category_xref.product_id
					AND #__{vm}_product_category_xref.category_id=#__{vm}_category.category_id";
		}
		else {
			$q .= "WHERE #__{vm}_product.product_id <> '$product_id' 
					AND  #__{vm}_product.product_id=#__{vm}_product_category_xref.product_id 
					AND #__{vm}_product_category_xref.category_id=#__{vm}_category.category_id";;
		}
		$q .= ' ORDER BY category_name,#__{vm}_category.category_id,product_name';
		// This is necessary, because so much products are difficult to handle!
		$q .= ' LIMIT 0, 2000';
		
		$db->query( $q );
		$products = Array();
		while( $db->next_record() ) {
			$products[$db->f("product_id")] = $db->f("category_name")." =&gt; ".$db->f("product_name");
		}
		$this->dropdown_display($list_name, $values, $products, 20, "multiple=\"multiple\"");
	}

	/**
	 * Creates a drop-down list for Extra fields
	 * @deprecated 
	 * @param string $t The pre-selected value
	 * @param string $extra Additional attributes for the select element
	 */
	function list_extra_field_4($t, $extra="") {
		global $VM_LANG, $vmLogger;

		$vmLogger->debug( 'The function '.__CLASS__.'::'.__FUNCTION__.' is deprecated. Use the userfield manager instead please.' );
		
		$title = array(array('Y',$VM_LANG->_('PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_4_1')),
		array('N',$VM_LANG->_('PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_4_2')));

		echo "<select class=\"inputbox\" name=\"extra_field_4\" $extra>\n";
		for ($i=0;$i<count($title);$i++) {
			echo "<option value=\"" . $title[$i][0]."\"";
			if ($title[$i][0] == $t)
			echo " selected=\"selected\" ";
			echo ">" . $title[$i][1] . "</option>\n";
		}
		echo "</select>\n";
	}
	/**
	 * Creates a drop-down list for Extra fields
	 * @deprecated 
	 * @param string $t The pre-selected value
	 * @param string $extra Additional attributes for the select element
	 */
	function list_extra_field_5($t, $extra="") {
		global $VM_LANG, $vmLogger;
		
		$vmLogger->debug( 'The function '.__CLASS__.'::'.__FUNCTION__.' is deprecated. Use the userfield manager instead please.' );
		
		$title = array(array('A',$VM_LANG->_('PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_5_1')),
		array('B',$VM_LANG->_('PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_5_2')),
		array('C',$VM_LANG->_('PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_5_3')));

		echo "<select class=\"inputbox\" name=\"extra_field_5\" $extra>\n";
		for ($i=0;$i<count($title);$i++) {
			echo "<option value=\"" . $title[$i][0]."\"";
			if ($title[$i][0] == $t)
			echo " selected=\"selected\" ";
			echo ">" . $title[$i][1] . "</option>\n";
		}
		echo "</select>\n";
	}
	/**
	 * Lists all available themes for this VirtueMart installation
	 *
	 * @param string $name
	 * @param string $preselected
	 * @return string
	 */
	function list_themes( $name, $preselected='default' ) {
		global $mosConfig_absolute_path;
		$themes = vmReadDirectory( $mosConfig_absolute_path . "/components/com_virtuemart/themes", "", false, true );
		$array = array();
		foreach ($themes as $theme ) {
			if( file_exists($theme.'/theme.php' ) ) {
				$array[basename($theme)] = basename( $theme );
			}
		}
		return ps_html::selectList( $name, $preselected, $array );
	}
	
	/**
	 * Funtion to create a select list holding all files for a special template section (e.g. order_emails)
	 *
	 * @param string $name
	 * @param string $section
	 * @param string $preselected
	 * @return string
	 */
	function list_template_files( $name, $section='browse', $preselected='' ) {
		
		$files = vmReadDirectory( VM_THEMEPATH . "templates/$section/" );
		$array = array();
        foreach ($files as $file) {
        	if( is_dir( $file ) ) continue;
            $file_info = pathinfo($file);
            $filename = $file_info['basename'];
            if( $filename == 'index.html' ) { continue; }
            $array[basename($filename, '.'.$file_info['extension'] )] = basename($filename, '.'.$file_info['extension'] );
        }
        if( $section == 'browse') {
        	$array = array_merge( array('managed' => 'managed'), $array );
        }
        return ps_html::selectList( $name, $preselected, $array );
	} 

	/**
	* Writes a box containing an information about the write access to a file
	* A green colored "Writable" box when the file is writeable
	* A red colored "Unwritable" box when the file is NOT writeable
	* 
	* @param string A path to a file or directory
	* @return string Prints a div element
	*/
	function writableIndicator( $folder, $style='text-align:left;margin-left:20px;' ) {
                global $VM_LANG;
		if( !is_array( $folder)) {
			$folder = array($folder);
		}
		echo '<div class="vmquote" style="'.$style.'">';
        foreach( $folder as $dir ) {
            echo $dir . ' :: ';
            echo is_writable( $dir )
                 ? '<span style="font-weight:bold;color:green;">'.$VM_LANG->_('VM_WRITABLE').'</span>'
                 : '<span style="font-weight:bold;color:red;">'.$VM_LANG->_('VM_UNWRITABLE').'</span>';
            echo '<br/>';
        }
        echo '</div>';
	}
	/**
	 * This is used by lists to show a "Delete this item" button in each row
	 *
	 * @param string $id_fieldname The name of the identifying field [example: product_id]
	 * @param mixed $id The unique ID identifying the item that is to be deleted
	 * @param string $func The name of the function that is used to delete the item [e.g. productDelete]
	 * @param string $keyword The recent keyword [deprecated]
	 * @param int $limitstart The recent limitstart value [deprecated]
	 * @param string $extra Additional URL parameters to be appended to the link
	 * @return A link with the delete button in it
	 */
	function deleteButton( $id_fieldname, $id, $func, $keyword="", $limitstart=0, $extra="" ) {
		global $page, $sess, $VM_LANG;
		$no_menu = vmRequest::getInt('no_menu');
		$href = $sess->url($_SERVER['PHP_SELF']. "?page=$page&func=$func&$id_fieldname=$id&keyword=". urlencode($keyword)."&limitstart=$limitstart&no_menu=$no_menu" . $extra );
		$code = "<a class=\"toolbar\" href=\"$href\" onclick=\"return confirm('".$VM_LANG->_('PHPSHOP_DELETE_MSG') ."');\" onmouseout=\"MM_swapImgRestore();\"  onmouseover=\"MM_swapImage('delete$id','','". IMAGEURL ."ps_image/delete_f2.gif',1);\">";
		$code .= "<img src=\"". IMAGEURL ."ps_image/delete.gif\" alt=\"Delete this record\" name=\"delete$id\" align=\"middle\" border=\"0\" />";
		$code .= "</a>";

		return $code;
	}
	/**
	 * Used to create the Control Panel links with icons in it
	 *
	 * @param string $image The complete icon URL
	 * @param string $link The URL that is linked to
	 * @param string $text The text / label for the link
	 */
	function writePanelIcon( $image, $link, $text ) {
		echo '<div style="float:left;"><div class="icon">
			<a title="'.$text.'" href="'.$link.'">
					<img src="'.$image.'" alt="'.$text.'" align="middle" name="image" border="0" /><br />
			'.$text.'</a></div></div>
			';

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
	class ps_html extends vm_ps_html {}
}
?>
