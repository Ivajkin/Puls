<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: product.product_form.php 2813 2011-03-03 09:21:46Z zanardi $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
mm_showMyFileName( __FILE__ );
global $ps_product, $ps_product_category;
require_once( CLASSPATH.'ps_product_discount.php' );
require_once( CLASSPATH.'ps_manufacturer.php' );

$product_id = vmGet( $_REQUEST, 'product_id');
if( is_array( $product_id )) {
	$product_id = (int)$product_id[0];
}
vmCommonHTML::loadExtjs();
echo vmCommonHTML::scriptTag( $mosConfig_live_site.'/components/'.VM_COMPONENT_NAME.'/js/product_attributes.js');
echo vmCommonHTML::scriptTag( $mosConfig_live_site .'/includes/js/calendar/calendar.js');
if( class_exists( 'JConfig' ) ) {
	// in Joomla 1.5, the name of calendar lang file is changed...
	echo vmCommonHTML::scriptTag( $mosConfig_live_site .'/includes/js/calendar/lang/calendar-en-GB.js');
} else {
	echo vmCommonHTML::scriptTag( $mosConfig_live_site .'/includes/js/calendar/lang/calendar-en.js');
}
echo vmCommonHTML::linkTag( $mosConfig_live_site .'/includes/js/calendar/calendar-mos.css');

$product_parent_id = vmGet( $_REQUEST, 'product_parent_id');
$next_page = vmGet( $_REQUEST, 'next_page', "product.product_display" );
$option = empty($option)?vmGet( $_REQUEST, 'option', 'com_virtuemart'):$option;
$clone_product = vmGet( $_REQUEST, 'clone_product', 0 );
$extra_ids = '';
$display_use_parent="";
$product_list="";
$display_header="";
$product_list_child="";
$product_list_type="";
$display_desc="";
$desc_width="20%";
$attrib_width="10%";
$display_type = "none";
$child_class_sfx ="";
$min_order="";
$max_order="";

$display_use_parent_disabled = false;
if($product_parent_id !=0) {
	$display_use_parent_disabled = true;
}
$list = Array();
if( !empty($_REQUEST['product_categories']) && is_array($_REQUEST['product_categories'])) {
	foreach( $_REQUEST['product_categories'] as $catid ) $my_categories[$catid] = '1';
} else {
	$my_categories = array();
}
$related_products = Array();

if ($product_parent_id > 0) {
	if ($product_id) {
		$action = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_UPDATE_ITEM_LBL');
	}
	else {
		$action = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NEW_ITEM_LBL');
	}
	$info_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ITEM_INFO_LBL');
	$status_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ITEM_STATUS_LBL');
	$dim_weight_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ITEM_DIM_WEIGHT_LBL');
	$images_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ITEM_IMAGES_LBL');
	$delete_message = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DELETE_ITEM_MSG');
}
else {
	$product_parent_id = '';
	if ($product_id = @$vars["product_id"]) {
		if( $clone_product == '1') {
			$action = $VM_LANG->_('PHPSHOP_PRODUCT_CLONE');
		}
		else {
			$action = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_UPDATE_ITEM_LBL');
		}
	}
	else {
		$action = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NEW_PRODUCT_LBL');
	}
	$info_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRODUCT_INFO_LBL');
	$status_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRODUCT_STATUS_LBL');
	$dim_weight_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRODUCT_DIM_WEIGHT_LBL');
	$images_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRODUCT_IMAGES_LBL');
	$delete_message = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DELETE_PRODUCT_MSG');
}
$display_label = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ITEM_DISPLAY_LBL');
if (!empty($product_id)) {
	$price = $ps_product->get_retail_price($product_id);
} else {
	$price['product_price'] = vmGet($_REQUEST,'product_price', '');
}
$quantity_start = 0;
$quantity_end = 0;
$quantity_step = 1;
if (!empty($product_id)) {
	// get the Database object we're filling the product form with
	$db = $ps_product->get($product_id);
	
	//get quantity options
	$quantity_options = ps_product::get_quantity_options($product_id);
	extract( $quantity_options );
	//get list style
	$child_options = ps_product::get_child_options($product_id);
	extract($child_options);
	
	//Get min max order levels
	$order_levels = ps_product::product_order_levels($product_id);
	if($order_levels) {
		$min_order = array_shift($order_levels);
		$max_order = array_shift($order_levels);
	}

	// Get category IDs
	$db2 = new ps_DB;
	$q = "SELECT category_id FROM #__{vm}_product_category_xref WHERE product_id='$product_id'";
	$db2->query($q);
	while ($db2->next_record()) {
		$my_categories[$db2->f("category_id")] = "1";
	}

	// Get the Manufacturer ID
	$db2->query("SELECT manufacturer_id FROM #__{vm}_product_mf_xref WHERE product_id='$product_id'");
	$db2->next_record();
	$manufacturer_id = $db2->f("manufacturer_id");

	// Get the Related Products
	$db2->query("SELECT related_products FROM #__{vm}_product_relations WHERE product_id='$product_id'");
	if($db2->next_record()) {
		$related_products = explode("|", $db2->f("related_products"));
	}

}
// Get some "default" values, which are used when no other values where provided through _REQUEST
$default['attribute'] = ps_product_attribute::formatAttributeX();
$default["product_publish"] = "Y";
$default["product_weight_uom"] = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_WEIGHT_UOM_DEFAULT');
$default["product_lwh_uom"] = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DIMENSION_UOM_DEFAULT');
$default["product_unit"] = $VM_LANG->_('PHPSHOP_PRODUCT_FORM_UNIT_DEFAULT');
if( !empty($vars['product_available_date'])) $vars['product_available_date'] = strtotime($vars['product_available_date']);
else $default["product_available_date"] = time();

// get the default shopper group
$shopper_db = new ps_DB;
$q =  "SELECT shopper_group_id,shopper_group_name FROM #__{vm}_shopper_group WHERE `default`= '1' AND vendor_id='".$db->f("vendor_id")."'";
$shopper_db->query($q);
if ($shopper_db->num_rows()<1) {  // when there is no "default", take the first in the table
	$q =  "SELECT shopper_group_id,shopper_group_name from #__{vm}_shopper_group WHERE vendor_id='$ps_vendor_id'";
	$shopper_db->query($q);
}
$shopper_db->next_record();
$my_shopper_group_id = $shopper_db->f("shopper_group_id");

// For cloning a product, we just need to empty the variable product_id
if( $clone_product == "1" ) {
	$product_id = "";
}

$title = '<img src="'. VM_THEMEURL.'images/administration/dashboard/product_code.png" border="0" align="center" alt="Product Form" />&nbsp;&nbsp;';
$title .= $action;

if( !empty( $product_id )) {
	$title .= " :: " . $db->f("product_name");
	$flypage = $ps_product->get_flypage($product_id);
	?>
	<a href="<?php echo $mosConfig_live_site."/index.php?option=com_virtuemart&page=shop.product_details&flypage=$flypage&product_id=$product_id" ?>" target="_blank">
		  <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_SHOW_FLYPAGE') ?>
	</a>
	<?php
}
elseif(!empty($product_parent_id)) {
	$parent_product_name = $ps_product->get_field($product_parent_id, 'product_name');
	$title .= ' :: <a href="' .$sess->url( $_SERVER['PHP_SELF'].'?page=product.product_form&product_id='.$product_parent_id).'">'.$parent_product_name.'</a>';
}
//First create the object and let it print a form heading
$formObj = new formFactory( $title );
//Then Start the form
$formObj->startForm( 'adminForm', 'enctype="multipart/form-data"');

$tabs = new vmTabPanel(0, 1, "productform");
$tabs->startPane("content-pane");
$tabs->startTab( $info_label, "info-page");
?>
<table class="adminform">
	<tr> 
   		<td valign="top">
			<table width="100%" border="0">
      			<tr> 
       				<td align="left" colspan="2"><?php echo "<h2 >$info_label</h2>"; ?></td>
    			</tr>
    			<tr class="row0"> 
      				<td  width="21%" ><div style="text-align:right;font-weight:bold;">
      				<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PUBLISH') ?>:</div>
      				</td>
      				<td width="79%" > <?php 
      				if ($db->sf("product_publish")=="Y") { 
      					echo "<input type=\"checkbox\" name=\"product_publish\" value=\"Y\" checked=\"checked\" />";
      				}
      				else {
      					echo "<input type=\"checkbox\" name=\"product_publish\" value=\"Y\" />";
      				}
					?></td>
    			</tr>
    			<tr class="row1"> 
      				<td width="21%" >
      					<div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_SKU') ?>:</div>
      				</td>
      				<td width="79%"> 
        				<input type="text" class="inputbox"  name="product_sku" value="<?php $db->sp("product_sku"); ?>" size="32" maxlength="64" />
      				</td>
    			</tr>
    			<tr class="row0"> 
      				<td width="21%"><div style="text-align:right;font-weight:bold;">
      					<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NAME') ?>:</div>
      				</td>
      				<td width="79%"> 
        				<input type="text" class="inputbox"  name="product_name" value="<?php echo shopMakeHtmlSafe( $db->sf("product_name")); ?>" size="32" maxlength="255" />
      				</td>
    			</tr>
    			<tr class="row1"> 
      				<td width="21%"><div style="text-align:right;font-weight:bold;">
        				<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_URL') ?>:</div>
      				</td>
      				<td width="79%"> 
        				<input type="text" class="inputbox"  name="product_url" value="<?php $db->sp("product_url"); ?>" size="32" maxlength="255" />
      				</td>
    			</tr>
    			<tr class="row0"> 
      				<td width="21%"><div style="text-align:right;font-weight:bold;">
        			<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_VENDOR') ?>:</div>
      				</td>
      				<td width="79%" ><?php ps_vendor::list_vendor($db->sf("vendor_id"));  ?></td>
    			</tr>
    			<tr class="row1"> 
      				<td width="21%" ><div style="text-align:right;font-weight:bold;">
        				<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_MANUFACTURER') ?>:</div>
      				</td>
      				<td width="79%" ><?php ps_manufacturer::list_manufacturer(@$manufacturer_id);  ?></td>
    			</tr>
    			<?php
    			if (!$product_parent_id) { 
    				?>
    			<tr class="row0"> 
    			<?php
    			$number_of_categories = ps_product_category::count_categories();
    			if( $number_of_categories > 200 ) {
    			?>
					<td style="vertical-align:top;">
						<?php echo $VM_LANG->_('PHPSHOP_CATEGORIES') ?>:<br/>
						<input type="text" size="40" name="catsearch" id="categorySearch" value="" />
					</td>
					<td>
						<input style="vertical-align: top;" type="button" name="remove_category" onclick="removeSelectedOptions(relatedCatSelection, 'category_ids' )" value="&nbsp; &lt; &nbsp;" />
						<?php			
						foreach( array_keys($my_categories) as $cat_id ) {
							$categoriesArr[$cat_id] = ps_product_category::get_name_by_catid( $cat_id );
						}
						echo ps_html::selectList('relCats', '', $categoriesArr, 10, 'multiple="multiple"', 'id="relatedCatSelection" ondblclick="removeSelectedOptions(relatedCatSelection, \'category_ids\');"');
						?>
						<input type="hidden" name="category_ids" value="<?php echo implode('|', array_keys($my_categories) ) ?>" />
					</td>	
					<?php
    			} else {
		    	?>		    
		      		<td width="29%" valign="top"><div style="text-align:right;font-weight:bold;">
		       			<?php echo $VM_LANG->_('PHPSHOP_CATEGORIES') ?>:<br/><br/>
		       			<?php echo vmToolTip( $VM_LANG->_('PHPSHOP_MULTISELECT') ) ?></div>
		       		</td>
		      		<td width="71%" ><?php 
		        		$ps_product_category->list_all("product_categories[]", "", $my_categories, 10, false, true); 
		        		?>
		        	</td>		    
		    		<?php
    			}
    			?>
    			</tr>
    			<?php
    		}
    		?>
  			</table>
 		</td>
 		<td>
  			<table class="adminform">
    			<tr class="row0"> 
      				<td width="29%" ><div style="text-align:right;font-weight:bold;">
      					<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRICE_NET') ?>:</div>
      				</td>
      				<td width="71%" >
        				<table border="0" cellspacing="0" cellpadding="0">
            				<tr>
            					<td>
                					<input type="text" value="<?php echo @$price["product_price"]; ?>" class="inputbox" name="product_price" onkeyup="updateGross();" size="10" maxlength="10" />
                					<input type="hidden" name="product_price_id" value="<?php echo @$price["product_price_id"] ?>" />                
                					<input type="hidden" name="price_quantity_start" value="<?php echo @intval($price["price_quantity_start"]) ?>" />
                					<input type="hidden" name="price_quantity_end" value="<?php echo @intval($price["price_quantity_end"]) ?>" />
            					</td>
            					<td><?php
           						 if(empty($price["product_currency"])) {
            						$price["product_currency"] = $vendor_currency;
            					}
              					$ps_html->list_currency("product_currency",$price["product_currency"]) ?>
            					</td>
            					<td>&nbsp;<?php
                					echo vmToolTip( $VM_LANG->_('PHPSHOP_PRICE_FORM_GROUP') . ": ".$shopper_db->f("shopper_group_name")); ?>               
                					<input type="hidden" name="shopper_group_id" value="<?php echo $my_shopper_group_id ?>" />
             					</td>
            				</tr>
        				</table>
      				</td>
    			</tr>
    			<tr class="row1"> 
      				<td width="29%" ><div style="text-align:right;font-weight:bold;">
        				<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRICE_GROSS') ?>:</div>
      				</td>
      				<td width="71%" ><input type="text" class="inputbox" onkeyup="updateNet();" name="product_price_incl_tax" size="10" /></td>
    			</tr>
    			<tr class="row0">
      				<td width="29%" ><div style="text-align:right;font-weight:bold;">
        				<?php echo $VM_LANG->_('PHPSHOP_RATE_FORM_VAT_ID') ?>:</div>
        			</td>
      				<td width="71%" >
	        			<?php
	        			require_once(CLASSPATH.'ps_tax.php');
	        			$tax_rates = ps_tax::list_tax_value("product_tax_id",$db->sf("product_tax_id"),"updateGross();") ?>
      				</td>
    			</tr>
    			<tr class="row1"> 
      				<td width="21%" ><div style="text-align:right;font-weight:bold;">
        				<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DISCOUNT_TYPE') ?>:</div>
      				</td>
      				<td width="79%" ><?php
        				echo ps_product_discount::discount_list( $db->sf("product_discount_id") ); ?>
      				</td>
    			</tr>
    			<tr class="row0"> 
      				<td width="21%" ><div style="text-align:right;font-weight:bold;">
        			<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DISCOUNTED_PRICE') ?>:</div>
      				</td>
      				<td width="79%" >
                		<input type="text" size="10" name="discounted_price_override" onchange="try { document.adminForm.product_discount_id[document.adminForm.product_discount_id.length-1].selected=true; } catch( e ) {}" />&nbsp;&nbsp;
                		<?php echo vmToolTip( $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DISCOUNTED_PRICE_TIP') ) ?>
        			</td>
    			</tr>
    			<tr class="row1"><td colspan="2">&nbsp;</td></tr>
    			<tr class="row1"> 
      				<td width="29%" valign="top"><div style="text-align:right;font-weight:bold;">
          				<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_S_DESC') ?>:</div>
      				</td>
      				<td width="71%"  valign="top">
          				<textarea class="inputbox" name="product_s_desc" id="short_desc" cols="35" rows="6" ><?php echo $db->sf("product_s_desc"); ?></textarea> 
      				</td>
    			</tr>
  			</table>
  		</td>
  	</tr>
</table>
<div style="font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DESCRIPTION') ?>:</div>
      
        <?php
        editorArea( 'editor1', htmlspecialchars( $db->sf("product_desc"), ENT_QUOTES ), 'product_desc', '100%', '300', '55', '25' )
	?>
      
<?php
$tabs->endTab();
$tabs->startTab( $display_label, "display-page");
?>
  <table class="adminform">
    <tr> 
      <td align="left" colspan="2"><?php echo "<h2>$display_label</h2>"; ?></td>
    </tr>
    <tr class="row1"> 
      <td width="21%"  style="vertical-align: middle;"><div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('VM_DISPLAY_USE_PARENT_LABEL'); ?></div>
      </td>
      <td width="79%" style="vertical-align: middle;" colspan="2"><input type="checkbox" class="checkbox"  id="display_use_parent" name="display_use_parent" value="Y"
      <?php 
      if (@$display_use_parent == "Y" && !$display_use_parent_disabled) echo "checked=\"checked\"";
        else if($display_use_parent_disabled) {
        	echo ' disabled="disabled" ';
        }   ?> 

      />
      <label for="display_use_parent" ><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_USE_PARENT'); ?></label><br/>
      </td>
    </tr>
    <tr class="row0"> 
      <td width="21%"  style="vertical-align: top;"><div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('VM_DISPLAY_LIST_TYPE'); ?></div>
      </td>
      <td width="20%"  style="vertical-align: top;"> <?php  
      echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"product_list_check\" name=\"product_list\" value=\"Y\" onclick=\"javascript: toggleProductList( this.checked);\" ";

      if (@$product_list =="Y" || @$product_list =="YM" ) {
      	echo "checked=\"checked\" ";
      }

      if($product_parent_id !=0) {
      	echo ' disabled="disabled" ';
      }
      echo '/> <label for="product_list_check">'.$VM_LANG->_('VM_DISPLAY_USE_LIST_BOX').'</label>';
      //Formatting Code
?> <br />
      
       <?php 
       echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"display_desc\" name=\"display_desc\" value=\"Y\" ";
       if ($display_desc == "Y") {
       	echo 'checked="checked" ';
       }
    echo '/> <label for="display_desc">'.$VM_LANG->_('VM_DISPLAY_CHILD_DESCRIPTION').'</label><br />
    		<input type="inputbox" style="vertical-align: middle;" class="inputbox" size="8" id="desc_width" name="desc_width" value="'.$desc_width.'" />';
    echo $VM_LANG->_('VM_DISPLAY_DESC_WIDTH'); ?>
    <br />
    <?php 
    echo "<input type=\"inputbox\" style=\"vertical-align: middle;\" class=\"inputbox\" size=\"8\" id=\"attrib_width\" name=\"attrib_width\" value=\"$attrib_width\"  ";
    echo "/> ".$VM_LANG->_('VM_DISPLAY_ATTRIB_WIDTH'); ?>
    <br />
    <?php 
    echo $VM_LANG->_('VM_DISPLAY_CHILD_SUFFIX')."<br /><input type=\"inputbox\" style=\"vertical-align: middle;\" class=\"inputbox\" size=\"20\" id=\"child_class_sfx\" name=\"child_class_sfx\" value=\"$child_class_sfx\"  ";
    echo "/> "; ?>
    <br />
      </td>
        
        <td width="20%" >
        <fieldset>
            <legend><?php echo $VM_LANG->_('VM_DISPLAY_LIST_STYLE'); ?></legend>
        
        <input type="radio" class="radio" style="vertical-align: middle;" id="list_style0" name="list_style" value="one" 
        <?php if (@$product_list == "Y") echo "checked=\"checked\""; 
        if($product_parent_id !=0 || @$product_list =="" || @$product_list =="N") {
        	echo ' disabled="disabled" ';
        }
        ?> 
        />
        <label for="list_style0" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_ONE'); ?></label><br/>
        <input type="radio" class="radio" style="vertical-align: middle;" id="list_style1" name="list_style" value="many" 
        <?php 
        if (@$product_list == "YM") echo "checked=\"checked\""; 
        if($product_parent_id !=0 || @$product_list =="" || @$product_list =="N") {
        	echo ' disabled="disabled" ';
        }
        ?> 
        />
        <label for="list_style1" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_MANY') ?> </label><br />
        <?php if (@$display_header =="Y" && (@$product_list =="Y" || @$product_list =="YM" )) { 
        	echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"display_headers\" name=\"display_headers\" value=\"Y\" checked=\"checked\" ";
        }
        else {
        	echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"display_headers\" name=\"display_headers\" value=\"Y\" ";
        }
        if (@$product_list =="Y"  || @$product_list =="YM" ) {
            echo " /> "; }
        else {
            echo ' disabled=true /> ';
        }        
        echo $VM_LANG->_('VM_DISPLAY_TABLE_HEADER');
?> <br />

        <?php if (@$product_list_child =="Y" && (@$product_list =="Y"  || @$product_list =="YM" )) { 
        	echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"product_list_child\" name=\"product_list_child\" value=\"Y\" checked=\"checked\" ";
        }
        else {
        	echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"product_list_child\" name=\"product_list_child\" value=\"Y\" ";
        }
        if (@$product_list =="Y"  || @$product_list =="YM" ) {
            echo " /> "; }
        else {
            echo ' disabled=true /> ';
        }    
        
        echo $VM_LANG->_('VM_DISPLAY_LINK_TO_CHILD')."<br />";
?> 

        <?php if (@$product_list_type =="Y" && (@$product_list =="Y"  || @$product_list =="YM" )) { 
        	echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"product_list_type\" name=\"product_list_type\" value=\"Y\" checked=\"checked\" ";
        }
        else {
        	echo "<input type=\"checkbox\" style=\"vertical-align: middle;\" class=\"checkbox\" id=\"product_list_type\" name=\"product_list_type\" value=\"Y\" ";
        }
        if (@$product_list =="Y"  || @$product_list =="YM" ) {
            echo " /> "; }
        else {
            echo " disabled=true /> ";
        }
        echo $VM_LANG->_('VM_DISPLAY_INCLUDE_PRODUCT_TYPE');
?> 

        </fieldset>
        </td>
        <td width="39%">
        </td>
    </tr>
    <tr class="row1"> 
      <td width="21%"  style="vertical-align: top;"><div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('VM_EXTRA_PRODUCT_ID'); ?></div>
      </td>
      <td width="79%" colspan="2"><input type="inputbox" class="inputbox" size="35" id="included_product_id" name="included_product_id" value="<?php echo $db->f("child_option_ids") ?>" />
      <label for="included_product_id" style="vertical-align: middle;"><?php echo $VM_LANG->_('VM_INCLUDED_PRODUCT_ID'); ?></label><br/>
      </td>
    </tr>

    <tr class="row0">
        <td width="21%" style="vertical-align: top;"><div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('VM_DISPLAY_QUANTITY_LABEL'); ?></div>
        </td>
        <td width="20%" style="vertical-align: top;">
        
            <input type="radio" class="radio" style="vertical-align: middle;" id="quantity_box0" name="quantity_box" value="none" <?php 
            	if ($display_type == "none") echo "checked=\"checked\""; ?>  />
            <label for="quantity_box0" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_NORMAL'); ?></label><br/>
            <input type="radio" class="radio" style="vertical-align: middle;" id="quantity_box1" name="quantity_box" value="hide" <?php 
            	if ($display_type == "hide") echo "checked=\"checked\""; ?> />
            <label for="quantity_box1" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_HIDE') ?> </label><br />
            <input type="radio" class="radio" style="vertical-align: middle;" id="quantity_box2" name="quantity_box" value="drop" <?php 
            	if ($display_type == "drop") echo "checked=\"checked\""; ?> />
            <label for="quantity_box2" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_DROPDOWN') ?> </label><br />
            <input type="radio" class="radio" style="vertical-align: middle;" id="quantity_box3" name="quantity_box" value="check" <?php 
            	if ($display_type == "check") echo "checked=\"checked\""; ?> 
            />
            <label for="quantity_box3" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_CHECKBOX') ?> </label><br />
            <input type="radio" class="radio" style="vertical-align: middle;" id="quantity_box4" name="quantity_box" value="radio" <?php 
	            if ($display_type == "radio") echo 'checked="checked"';  
	            if($product_parent_id !=0) echo ' disabled="true"'; ?>  />
            <label for="quantity_box4" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_RADIOBOX') ?> </label><br />
            
        </td>
        <td width="20%" style="vertical-align: top;">
            <fieldset>
                <legend><?php echo $VM_LANG->_('VM_DISPLAY_QUANTITY_DROPDOWN_LABEL') ?></legend>
            <input type="text" class="inputbox" style="vertical-align: middle;" id="quantity_start" name="quantity_start" size="4" value="<?php echo $quantity_start; ?>" />
            <label for="quantity_start" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_START') ?> </label><br />
            <input type="text" class="inputbox" style="vertical-align: middle;" id="quantity_end" name="quantity_end" size="4" value="<?php echo $quantity_end; ?>" />
            <label for="quantity_end" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_END') ?> </label><br />
            <input type="text" class="inputbox" style="vertical-align: middle;" id="quantity_step" name="quantity_step" size="4" value="<?php echo $quantity_step; ?>" />
            <label for="quantity_step" style="vertical-align: middle;"><?php echo $VM_LANG->_('PHPSHOP_DISPLAY_STEP') ?> </label><br />
            </fieldset>
        </td>
        <td width="39%">
        </td>
    </tr>
  </table>
<?php
$tabs->endTab();
$tabs->startTab( $status_label, "status-page");
?>

  <table class="adminform">
    <tr> 
      <td align="left" colspan="2"><?php echo "<h2>$status_label</h2>"; ?></td>
    </tr>
    <tr class="row0"> 
      <td width="21%" height="2" ><div style="text-align:right;font-weight:bold;">
      <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_IN_STOCK') ?>:</div>
      </td>
      <td width="79%" height="2" > 
        <input type="text" class="inputbox"  name="product_in_stock" value="<?php $db->sp("product_in_stock"); ?>" size="10" />
      </td>
    </tr>
    <tr class="row1"> 
      <td width="21%" height="2" ><div style="text-align:right;font-weight:bold;">
      <?php echo $VM_LANG->_('VM_PRODUCT_FORM_MIN_ORDER') ?>:</div>
      </td>
      <td width="79%" height="2" > 
        <input type="text" class="inputbox"  name="min_order_level" value="<?php echo $min_order; ?>" size="10" />
      </td>
    </tr>
    <tr class="row0"> 
      <td width="21%" height="2" ><div style="text-align:right;font-weight:bold;">
      <?php echo $VM_LANG->_('VM_PRODUCT_FORM_MAX_ORDER') ?>:</div>
      </td>
      <td width="79%" height="2" > 
        <input type="text" class="inputbox"  name="max_order_level" value="<?php echo $max_order; ?>" size="10" />
      </td>
    </tr>
    <tr class="row1"> 
      <td width="21%" ><div style="text-align:right;font-weight:bold;">
        <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_AVAILABLE_DATE') ?>:</div>
      </td>
      <td width="79%" >
          <input class="inputbox" type="text" name="product_available_date" id="product_available_date" size="20" maxlength="19" value="<?php echo date('Y-m-d', $db->sf("product_available_date") ); ?>" />
          <input name="reset" type="reset" class="button" onClick="return showCalendar('product_available_date', 'y-mm-dd');" value="..." />
     </td>
    </tr>
    <tr class="row0"><td colspan="2">&nbsp;</td></tr>
    <tr>
      <td valign="top" width="21%" ><div style="text-align:right;font-weight:bold;">
          <?php echo $VM_LANG->_('PHPSHOP_AVAILABILITY') ?>:</div>
      </td>
      <td width="79%" >
        <input type="text" class="inputbox" name="product_availability" value="<?php $db->sp("product_availability"); ?>" />
      <?php
      echo vmToolTip($VM_LANG->_('PHPSHOP_PRODUCT_FORM_AVAILABILITY_TOOLTIP1')); ?>
      <br /><br />
        <select class="inputbox" name="image" onchange="javascript:if (document.adminForm.image.options[selectedIndex].value!='') {document.imagelib.src='<?php echo VM_THEMEURL ?>images/availability/' + document.adminForm.image.options[selectedIndex].value; document.adminForm.product_availability.value=document.adminForm.image.options[selectedIndex].value;} else {document.imagelib.src='<?php echo VM_THEMEURL.'images/'.NO_IMAGE;?>'; document.adminForm.product_availability.value=''}">
          <option value=""><?php echo $VM_LANG->_('VM_PRODUCT_FORM_AVAILABILITY_SELECT_IMAGE'); ?></option><?php
          $path = VM_THEMEPATH."images/availability";
          $files = vmReadDirectory( "$path", ".", true, true);
          foreach ($files as $file) {
          	$file_info = pathinfo($file);
          	$filename = $file_info['basename'];
                if ($filename != "index.html") {?>
                <option <?php echo ($db->f("product_availability")==$filename) ? "selected=\"selected\"" : "" ?> value="<?php echo $filename ?>">
                <?php echo $filename ?>
                </option><?php 
                }
            }  ?>
        </select>&nbsp;
        <?php
		$pathrelative = str_replace($mosConfig_live_site,'',VM_THEMEURL."images/availability/");
		echo vmToolTip(sprintf($VM_LANG->_('PHPSHOP_PRODUCT_FORM_AVAILABILITY_TOOLTIP2'),$pathrelative));
		?>
        &nbsp;&nbsp;&nbsp;
        <img src="<?php echo $db->f("product_availability") ? VM_THEMEURL."images/availability/".$db->sf("product_availability") : VM_THEMEURL.'images/'.NO_IMAGE; ?>" name="imagelib" border="0" alt="Preview" />
      </td>
    </tr>
    <tr class="row1"> 
      <td width="21%" ><div style="text-align:right;font-weight:bold;">
      <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_SPECIAL') ?>:</div>
      </td>
      <td width="79%" ><?php if ($db->sf("product_special")=="Y") { ?>
        <input type="checkbox" name="product_special" value="Y" checked="checked" />
    <?php    } 
       else { ?>
        <input type="checkbox" name="product_special" value="Y" />
    <?php }
    ?> </td>
    </tr>
    <tr class="row0">
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="row0">
        <td align="right" width="21%" valign="top"><div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ATTRIBUTE_LIST') ?>:</div></td> 
		<td width="79%" id="attribute_container">
			<?php 
			// ATTRIBUTE EXTENSION by Tobias (eaxs)
			ps_product_attribute::loadAttributeExtension($db->sf("attribute"));
			?>
    </tr>
    <tr class="row0">
    <td>&nbsp;</td>
        <td><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ATTRIBUTE_LIST_EXAMPLES') ?></td>
    </tr>
    <tr class="row0">
    <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="row1">
        <td align="right" width="21%" valign="top"><div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_CUSTOM_ATTRIBUTE_LIST') ?>:</div></td> 
        <td width="79%" >
        <input class="inputbox" type="text" name="product_custom_attribute" value="<?php $db->sp("custom_attribute"); ?>" size="64" />
    </tr>
    <tr class="row1">
     <td>&nbsp;</td>
     <td><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_CUSTOM_ATTRIBUTE_LIST_EXAMPLES') ?></td>
        </tr>
  </table>

<?php
if( !empty( $product_id )) {
	$db_items = $ps_product->items_sql($product_id);
} else {
	$db_items = new ps_DB();
}
if (!$product_parent_id and $product_id and $db_items->num_rows() > 0) {
?> 
  <table class="adminform">
    <tr class="row0"> 
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr class="row1"> 
      <td colspan="4"><div style="text-align:right;font-weight:bold;">
          <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRODUCT_ITEMS_LBL') ?></div>
      </td>
    </tr>
    <tr class="row0"> 
      <td><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NAME') ?></td>
      <td><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_SKU') ?></td>
      <td><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PRICE_NET') ?></td>
      <?php
      $db_heading = $ps_product->attribute_sql("",$product_id);
      while ($db_heading->next_record()) {
?> 
      <td><?php echo $db_heading->sf("attribute_name"); ?></td>
      <?php
      }
?> </tr>
    <tr class="row1"> 
      <td colspan="<?php echo $db_heading->num_rows() + 3 ?>"> 
        <hr size="1" />
      </td>
    </tr>
    <?php
    while ($db_items->next_record()) {
?> 
    <tr  class="row0"> 
      <td> <?php
      $url = $_SERVER['PHP_SELF'] . "?page=$modulename.product_form&product_id=" . $db_items->f("product_id") . "&product_parent_id=$product_id";
      echo "<a href=\"" . $sess->url($url) . "\">";
      echo $db_items->f("product_name");
                    echo "</a>"; ?>
                </td>
      <td><?php $db_items->sp("product_sku"); ?> </td>
      <td> <?php
      $price = $ps_product->get_price($db_items->f("product_id"));
      $url  = $_SERVER['PHP_SELF'] . "?page=$modulename.product_price_list&product_id=" . $db_items->f("product_id") . "&product_parent_id=$product_parent_id";
      $url .= "&return_args=" . urlencode("page=$page&product_id=$product_id");
      echo "<a href=\"" . $sess->url($url) . "\">";
      if ($price) {
      	if (!empty($price["item"])) {
      		echo $price["product_price"];
      	} else {
      		echo "none";
      	}
      } else {
      	echo "none";
      }
      echo "</a>";
?> </td>
      <?php
      $db_detail = $ps_product->attribute_sql($db_items->f("product_id"),$product_id);
      while ($db_detail->next_record()) {
      	echo '<td>'. $db_detail->f("attribute_value").'</td>';

      }
            ?>
        </tr>
    <?php
    }
?> 
  </table>
  <?php
} elseif ($product_parent_id) {
?> 
  <table class="adminform">
    <tr class="row0"> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="row1"> 
      <td colspan="2"><strong><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ITEM_ATTRIBUTES_LBL') ?></strong></td>
    </tr>
    <?php
    if (!empty($_REQUEST['product_id'])) {
    	$db_attribute = $ps_product->attribute_sql($product_id,$product_parent_id);
    } else {
    	$db_attribute = $ps_product->attribute_sql("",$product_parent_id);
    }
    $num = 0;
    while ($db_attribute->next_record()) {
    $num++; ?> 
    <tr  class="row<?php echo $num%2 ?>"> 
      <td width="21%" height="22" > 
        <div style="text-align:right;font-weight:bold;"><?php
        echo $db_attribute->sf("attribute_name") . ":";
          $field_name = "attribute_$num"; ?></div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="<?php echo $field_name; ?>" size="32" maxlength="255" value="<?php $db_attribute->sp("attribute_value"); ?>" />
      </td>
    </tr>
    <?php
  } ?> 
  </table>
  <?php
}

$tabs->endTab();
$tabs->startTab( $dim_weight_label, "about-page");

?>

<?php
echo "<h2>$dim_weight_label</h2>";
?>      
   <table class="adminform">
    <tr class="row1"> 
      <td width="21%" valign="top" > 
        <div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_LENGTH') ?>:</div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="product_length" value="<?php $db->sp("product_length"); ?>" size="15" maxlength="15" />
      </td>
    </tr>
    <tr class="row0"> 
      <td width="21%" valign="top" > 
        <div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_WIDTH') ?>:</div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="product_width" value="<?php $db->sp("product_width"); ?>" size="15" maxlength="15" />
      </td>
    </tr>
    <tr class="row1"> 
      <td width="21%" valign="top" > 
        <div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_HEIGHT') ?>:</div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="product_height" value="<?php $db->sp("product_height"); ?>" size="15" maxlength="15" />
      </td>
    </tr>
    <tr class="row0"> 
      <td width="21%" valign="top" > 
        <div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_DIMENSION_UOM') ?>:</div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="product_lwh_uom" value="<?php $db->sp("product_lwh_uom"); ?>" size="8" maxlength="32" />
      </td>
    </tr>
    <tr class="row1"> 
      <td width="21%" valign="top" >&nbsp;</td>
      <td width="79%" >&nbsp;</td>
    </tr>
    <tr class="row0"> 
      <td width="21%" valign="top" > 
        <div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_WEIGHT') ?>:</div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="product_weight" size="15" maxlength="15" value="<?php $db->sp("product_weight"); ?>" />
      </td>
    </tr>
    <tr class="row1"> 
      <td width="21%" valign="top" > 
        <div style="text-align:right;font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_WEIGHT_UOM') ?>:</div>
      </td>
      <td width="79%" > 
        <input type="text" class="inputbox"  name="product_weight_uom" value="<?php $db->sp("product_weight_uom"); ?>" size="8" maxlength="32" />
      </td>
    </tr>
    <!-- Changed Packaging - Begin -->
    <tr class="row0"> 
      <td width="21%" valign="top" >&nbsp;</td>
      <td width="21%" >&nbsp;</td>
    </tr>
    <tr class="row1"> 
      <td width="21%" valign="top" > 
        <div align="right"><strong><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_UNIT') ?>:</strong></div>
      </td>
      <td width="21%" > 
        <input type="text" class="inputbox"  name="product_unit" size="15" maxlength="15" value="<?php $db->sp("product_unit"); ?>" />
      </td>
    </tr>
    <tr class="row0">
      <td width="21%" valign="top" > 
        <div align="right"><strong><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_PACKAGING') ?>:</strong></div>
      </td>
      <td width="21%" > 
        <input type="text" class="inputbox"  name="product_packaging" value="<?php echo $db->f("product_packaging") & 0xFFFF; ?>" size="8" maxlength="32" />&nbsp;<?php
        echo vmToolTip($VM_LANG->_('PHPSHOP_PRODUCT_FORM_PACKAGING_DESCRIPTION')); ?>
      </td>
    </tr>
    <tr class="row1">
      <td width="21%" valign="top" > 
        <div align="right"><strong><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_BOX') ?>:</strong></div>
      </td>
      <td width="21%" > 
        <input type="text" class="inputbox"  name="product_box" value="<?php echo ($db->f("product_packaging")>>16)&0xFFFF; ?>" size="8" maxlength="32" />&nbsp;<?php
        echo vmToolTip($VM_LANG->_('PHPSHOP_PRODUCT_FORM_BOX_DESCRIPTION')); ?>
      </td>
    </tr>
    <!-- Changed Packaging - End -->
  
</table>
<?php
$tabs->endTab();

$tabs->startTab( $images_label, "images-page");

$ps_html->writableIndicator( array( IMAGEPATH."product", IMAGEPATH."product/resized") );

 ?>
  <table class="adminform" >
    <tr> 
      <td valign="top" width="50%" style="border-right: 1px solid black;">
        <h2><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_FULL_IMAGE') ?></h2>
        <table class="adminform">
          <tr class="row0"> 
            <td colspan="2" ><?php 
            if ($product_id) {
                echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_IMAGE_UPDATE_LBL') . "<br />"; } ?> 
              <input type="file" class="inputbox" name="product_full_image" onchange="document.adminForm.product_full_image_url.value='';if(this.value!='') { document.adminForm.product_full_image_action[1].checked=true;toggleDisable(document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image, true) }" size="50" maxlength="255" />
            </td>
          </tr>
          <tr class="row1"> 
            <td colspan="2" ><div style="font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_IMAGE_ACTION') ?>:</div><br/>
              <input type="radio" class="inputbox" id="product_full_image_action0" name="product_full_image_action" checked="checked" value="none" onchange="toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image, true );toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image_url, true );"/>
              <label for="product_full_image_action0"><?php echo $VM_LANG->_('PHPSHOP_NONE'); ?></label><br/>
              <?php
              // Check if GD library is available
              if( function_exists('imagecreatefromjpeg')) { ?>
	              <input type="radio" class="inputbox" id="product_full_image_action1" name="product_full_image_action" value="auto_resize" onchange="toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image, true );toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image_url, true );"/>
	              <label for="product_full_image_action1"><?php echo $VM_LANG->_('PHPSHOP_FILES_FORM_AUTO_THUMBNAIL') . "</label><br />"; 
              }
              if ($product_id and $db->f("product_full_image")) { ?>
                <input type="radio" class="inputbox" id="product_full_image_action2" name="product_full_image_action" value="delete" onchange="toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image, true );toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image_url, true );"/>
                <label for="product_full_image_action2"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_IMAGE_DELETE_LBL') . "</label><br />"; 
              } ?> 
            </td>
          </tr>
          <tr class="row0"><td colspan="2">&nbsp;</td></tr>
          <tr class="row0"> 
            <td width="21%" ><?php echo $VM_LANG->_('URL')." (".$VM_LANG->_('CMN_OPTIONAL')."!)&nbsp;"; ?></td>
            <td width="79%" >
              <?php 
              if( stristr($db->f("product_full_image"), "http") )
              $product_full_image_url = $db->f("product_full_image");
              else if(!empty($_REQUEST['product_full_image_url']))
              $product_full_image_url = vmGet($_REQUEST, 'product_full_image_url');
              else
              $product_full_image_url = "";
              ?>
              <input type="text" class="inputbox" size="50" name="product_full_image_url" value="<?php echo $product_full_image_url ?>" onchange="if( this.value.length>0) document.adminForm.product_full_image_action[1].checked=false; else document.adminForm.product_full_image_action[1].checked=true; toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image_url, true );toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image, true );" />
            </td>
          </tr>
          <tr class="row1"><td colspan="2">&nbsp;</td></tr>
          <tr class="row1"> 
            <td colspan="2" >
              <div style="overflow:auto;">
                <?php 
                if( $clone_product != "1" ) {
                	echo $ps_product->image_tag($db->f("product_full_image"), "", 0);
                }
                ?>
              </div>
            </td>
          </tr>
        </table>
      </td>

      <td valign="top" width="50%">
        <h2><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_THUMB_IMAGE') ?></h2>
        <table class="adminform">
          <tr class="row0"> 
            <td colspan="2" ><?php if ($product_id) {
                echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_IMAGE_UPDATE_LBL') . "<br />"; } ?> 
              <input type="file" class="inputbox" name="product_thumb_image" size="50" maxlength="255" onchange="if(document.adminForm.product_thumb_image.value!='') document.adminForm.product_thumb_image_url.value='';" />
            </td>
          </tr>
          <tr class="row1"> 
            <td colspan="2" ><div style="font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_IMAGE_ACTION') ?>:</div><br/>
              <input type="radio" class="inputbox" id="product_thumb_image_action0" name="product_thumb_image_action" checked="checked" value="none" onchange="toggleDisable( document.adminForm.product_thumb_image_action[1], document.adminForm.product_thumb_image, true );toggleDisable( document.adminForm.product_thumb_image_action[1], document.adminForm.product_thumb_image_url, true );"/>
              <label for="product_thumb_image_action0"><?php echo $VM_LANG->_('PHPSHOP_NONE') ?></label><br/>
              <?php 
              if ($product_id and $db->f("product_thumb_image")) { ?>
                <input type="radio" class="inputbox" id="product_thumb_image_action1" name="product_thumb_image_action" value="delete" onchange="toggleDisable( document.adminForm.product_thumb_image_action[1], document.adminForm.product_thumb_image, true );toggleDisable( document.adminForm.product_thumb_image_action[1], document.adminForm.product_thumb_image_url, true );"/>
                <label for="product_thumb_image_action1"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_IMAGE_DELETE_LBL') . "</label><br />"; 
              } ?> 
            </td>
          </tr>
          <tr class="row0"><td colspan="2">&nbsp;</td></tr>
          <tr class="row0"> 
            <td width="21%" ><?php echo $VM_LANG->_('URL')." (".$VM_LANG->_('CMN_OPTIONAL').")&nbsp;"; ?></td>
            <td width="79%" >
              <?php 
              if( stristr($db->f("product_thumb_image"), "http") )
              $product_thumb_image_url = $db->f("product_thumb_image");
              else if(!empty($_REQUEST['product_thumb_image_url']))
              $product_thumb_image_url = vmGet($_REQUEST, 'product_thumb_image_url');
              else
              $product_thumb_image_url = "";
              ?>
              <input type="text" class="inputbox" size="50" name="product_thumb_image_url" value="<?php echo $product_thumb_image_url ?>" />
            </td>
          </tr>
          <tr class="row1"><td colspan="2">&nbsp;</td></tr>
          <tr class="row1">
            <td colspan="2" >
              <div style="overflow:auto;">
                <?php 
                if( $clone_product != "1" ) {
                	echo $ps_product->image_tag($db->f("product_thumb_image"), "", 0);
                }
                ?>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

<?php
$tabs->endTab();

$tabs->startTab( $VM_LANG->_('PHPSHOP_RELATED_PRODUCTS'), "related-page");

?><br />
<h2><?php echo $VM_LANG->_('PHPSHOP_RELATED_PRODUCTS') ?></h2>
<br />
        <table class="adminform">
          <tr class="row1">
			<td style="vertical-align:top;"><br />
			<?php echo $VM_LANG->_('VM_PRODUCT_RELATED_SEARCH'); ?>
			<input type="text" size="40" name="search" id="relatedProductSearch" value="" />
			</td>
			<td><input type="button" name="remove_related" onclick="removeSelectedOptions(relatedSelection, 'related_products');" value="&nbsp; &lt; &nbsp;" /></td>
			<td>
			<?php
			$relProducts = array();
			foreach( $related_products as $relProd ) {
				$relProducts[$relProd] = $ps_product->get_field( $relProd, 'product_name');
			}
			echo ps_html::selectList('relProds', '', $relProducts, 10, 'multiple="multiple"', 'id="relatedSelection" ondblclick="removeSelectedOptions(relatedSelection, \'related_products\');"');
			?>
			<input type="hidden" name="related_products" value="<?php echo implode('|', $related_products ) ?>" />
			</td>			
		</tr>
	</table>
<!-- Changed Product Type - Begin -->
<?php
$tabs->endTab();

// Get Product Types
$dba = new ps_DB;

################################
# Alatis Mod
#
# New Product based on specified Product Type
################################
$product_type_id = vmGet($_REQUEST, 'product_type_id', 0);
if ($product_type_id > 0) {
	$q = "SELECT * FROM #__{vm}_product_type WHERE product_type_id=$product_type_id";
	$dba->query($q);
	echo "<input type=\"hidden\" name=\"product_type_id\" value=\"$product_type_id\" />";
} else {
	// Get Product Types
	$q  = "SELECT * FROM #__{vm}_product_product_type_xref,#__{vm}_product_type WHERE ";
	$q .= "#__{vm}_product_product_type_xref.product_type_id=#__{vm}_product_type.product_type_id ";
	$q .= 'AND product_id='.(int)$product_id;
	/*  if (!$product_parent_id) {
	$q .= "AND product_id='$product_id' ";
	}
	else {
	$q .= "AND product_id='$product_parent_id' ";
	}*/
	$q .= ' ORDER BY product_type_list_order';
	$dba->query($q);
}
#################################
# / Alatis Mod
#################################

$dbpt = new ps_DB;
$dbp = new ps_DB;

while ($dba->next_record()) {

	$product_type_id = $dba->f("product_type_id");

	$tabs->startTab( $dba->f('product_type_name'), "parameter-page-$product_type_id");

	$q  = "SELECT * FROM #__{vm}_product_type_parameter WHERE ";
	$q .= "product_type_id='$product_type_id' ";
	$q .= "ORDER BY parameter_list_order";
	$dbpt->query($q);

	$q  = "SELECT * FROM #__{vm}_product_type_$product_type_id WHERE ";
	$q .= "product_id='$product_id'";
	$dbp->query($q);
?>

  <table class="adminform">
    <tr class="row0"> 
      <td colspan="2"><h2><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_LBL').': '.$dba->f("product_type_name") ?></h2>
      
      <?php
      echo '<h3>'.$VM_LANG->_('E_REMOVE').' =&gt; '.$ps_html->deleteButton( "product_type_id", $product_type_id, "productProductTypeDelete", $keyword, $limitstart, "&product_id=$product_id&product_parent_id=$product_parent_id&next_page=$next_page" ) . '</h3>';
      ?>
      </td>
    </tr>

    <?php 
    $i = 0;
    while ($dbpt->next_record()) {
    	if ($dbpt->f("parameter_type")!="B") {
    		echo "<tr class=\"row".$i++ % 2 . "\">\n  <td width=\"21%\" height=\"2\" valign=\"top\"><div style=\"text-align:right;font-weight:bold;\">";
    		echo $dbpt->f("parameter_label");
    		echo ":</div>\n  </td>\n  <td width=\"79%\" valign=\"top\" >";

    		$parameter_values=$dbpt->f("parameter_values");
    		if (!empty($parameter_values)) { // List of values
    			$fields=explode(";",$parameter_values);
    			echo "<select class=\"inputbox\" name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name");

    			if ($dbpt->f("parameter_type")=="V") { //  Type: Multiple Values
    				$size = min(count($fields),6);
    				echo "[]\" multiple size=\"$size\">\n";
    				$selected_value = array();
    				$get_item_value = $dbp->f($dbpt->f("parameter_name"));
    				$get_item_value = explode(";",$get_item_value);
    				foreach($get_item_value as $value) {
    					$selected_value[$value] = 1;
    				}
    				foreach($fields as $field) {
    					echo "<option value=\"$field\"".(($selected_value[$field]==1) ? " selected>" : ">"). $field."</option>\n";
    				}
    			}
    			else {  // Other Parameter type
    				echo "\">\n";
    				foreach($fields as $field) {
    					echo "<option value=\"$field\" ";
    					if ($dbp->f($dbpt->f("parameter_name")) == $field) echo "selected=\"selected\"";
    					echo " >".$field."</option>\n";
    				}
    			}
    			echo "</select>\n";
    		}
    		else { // Input field
    			switch( $dbpt->f("parameter_type") ) {
    				case "I": // Integer
    				case "F": // Float
    				case "D": // Date & Time
    				case "A": // Date
    				case "M": // Time
    				echo "    <input type=\"text\" class=\"inputbox\"  name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name")."\" value=\"".$dbp->f($dbpt->f("parameter_name"))."\" size=\"20\" />";
    				break;
    				case "T": // Text
    				case "S": // Short Text
    				echo "<textarea class=\"inputbox\" name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name")."\" cols=\"35\" rows=\"6\" >";
    				echo $dbp->sf($dbpt->f("parameter_name"))."</textarea>";
    				break;
    				case "C": // Char
    				echo "    <input type=\"text\" class=\"inputbox\"  name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name")."\" value=\"".$dbp->f($dbpt->f("parameter_name"))."\" size=\"5\" />";
    				break;
    				case "V": // Multiple Values
    				echo "    <input type=\"text\" class=\"inputbox\"  name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name")."\" value=\"".$dbp->f($dbpt->f("parameter_name"))."\" size=\"20\" />";

    				// 						$fields=explode(";",$parameter_values);
    				// 						echo "<select class=\"inputbox\" name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name");
    				// 						if ($db->f("parameter_multiselect")=="Y") {
    				// 							$size = min(count($fields),6);
    				// 							echo "[]\" multiple size=\"$size\">\n";
    				// 							$selected_value = array();
    				// 							$get_item_value = explode(",",$dbp->sf($dbpt->f("parameter_name")));
    				// 							foreach($get_item_value as $value) {
    				// 								$selected_value[$value] = 1;
    				// 							}
    				// 							foreach($fields as $field) {
    				// 								echo "<option value=\"$field\"".(($selected_value[$field]==1) ? " selected>" : ">"). $field."</option>\n";
    				// 							}
    				// 						}
    				// 						else {
    				// 							echo "\">\n";
    				// 							$get_item_value = $dbp->sf($dbpt->f("parameter_name"));
    				// 							foreach($fields as $field) {
    				// 								echo "<option value=\"$field\"".(($get_item_value==$field) ? " selected>" : ">"). $field."</option>\n";
    				// 							}
    				// 						}
    				// 						echo "</select>";
    				break;
    					default: // Default type Short Text
    					echo "    <input type=\"text\" class=\"inputbox\" name=\"product_type_".$product_type_id."_".$dbpt->f("parameter_name")."\" value=\"".$dbp->f($dbpt->f("parameter_name"))."\" size=\"20\" />";
    				}
    		}

    		if ($dbpt->f("parameter_description")) {
    			echo "&nbsp;";
    			echo vmToolTip($dbpt->f("parameter_description"));
    		}
    		echo " ".$dbpt->f("parameter_unit");
    		if ($dbpt->f("parameter_default")) {
    			echo " (".$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_DEFAULT').": ";
    			echo $dbpt->f("parameter_default").")";
    		}
    		echo " [ ".$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE').": ";
    		switch( $dbpt->f("parameter_type") ) {
    			case "I": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_INTEGER'); break;	// Integer
    			case "T": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TEXT'); break; 	// Text
    			case "S": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_SHORTTEXT'); break; // Short Text
    			case "F": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_FLOAT'); break; 	// Float
    			case "C": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_CHAR'); break; 	// Char
    			case "D": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATETIME')." ";	// Date & Time
    			echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATE_FORMAT')." ";
    			echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TIME_FORMAT');
    			break;
    			case "A": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATE')." ";		// Date
    			echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATE_FORMAT');
    			break;
    			case "M": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TIME')." ";		// Time
    			echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TIME_FORMAT');
    			break;
    			case "V": echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_MULTIVALUE'); break; 	// Multiple Value
    		}
    		echo " ] ";
    	}
    	else {
    		echo "<tr>\n  <td colspan=\"2\" height=\"2\" ><hr/>";
    	}
    	echo "  </td>\n</tr>";
    }
?>

  </table>
  
  <?php 

  $tabs->endTab();
  //<!-- Changed Product Type - End -->
}
if( $clone_product == "1" ) {
	
	echo '<input type="hidden" name="clone_product" value="Y" />';
	echo '<input type="hidden" name="old_product_id" value="'.vmGet($_REQUEST, 'product_id').'" />';
	$db_att = new ps_DB;
	$db->query( "SELECT product_id, product_name
                FROM #__{vm}_product
                WHERE product_parent_id='".vmGet($_REQUEST, 'product_id')."' " );
	if( $db->num_rows() > 0 ) {
		$tabs->startTab( $VM_LANG->_('VM_PRODUCT_CLONE_OPTIONS_TAB'), 'clone-page' );
		echo "<h3>" . $VM_LANG->_('VM_PRODUCT_CLONE_OPTIONS_LBL') . ":</h3>";
	
		while( $db->next_record() ) {
			$db_att->query( "SELECT attribute_name, attribute_value FROM #__{vm}_product_attribute
	                      WHERE product_id ='".$db->f("product_id")."'" );
			echo '<input type="checkbox" checked="checked" name="child_items[]" value="'.$db->f("product_id").'" id="child_'.$db->f("product_id").'" />
	    <label for="child_'.$db->f("product_id").'">'.$db->f("product_name").' (';
			while( $db_att->next_record() ) {
				echo $db_att->f("attribute_name").": ".$db_att->f("attribute_value")."; ";
			}
			echo ')</label><br/>';
		}

		$tabs->endTab();
	}
}

if( $product_id ) {
	// SHOW THE WAITING LIST!
	$dbw = new ps_DB;
	$dbw->query( 'SELECT name, username, user_id, notify_email, notified, notify_date FROM `#__{vm}_waiting_list`
					LEFT JOIN `#__users` ON `user_id` = `id`
					WHERE `product_id`=' . $product_id );
	if( $dbw->num_rows() > 0 ) {
		$tabs->startTab( $VM_LANG->_('PRODUCT_WAITING_LIST_TAB'), 'waiting-list-tab' );

		echo '<table class="adminform"><tr><td><h2>' . $VM_LANG->_('PRODUCT_WAITING_LIST_USERLIST') . ':</h2></td></tr>';
		echo '<tr><td><input type="hidden" value="'.$db->f('product_in_stock').'" name="product_in_stock_old" />';
		echo '<input type="checkbox" value="1" checked="checked" id="notify_users" name="notify_users" /> <label for="notify_users">' . $VM_LANG->_('PRODUCT_WAITING_LIST_NOTIFYUSERS') . '</label><br /><br /></td></tr>';
		echo '<tr><td>';
		while( $dbw->next_record() ) {
			if ($dbw->f("notified")==1) {
				$waiting_notified = ' - <strong style="font-weight:bold">' . $VM_LANG->_('PRODUCT_WAITING_LIST_NOTIFIED') . ' ' . $dbw->f("notify_date") . '</strong>';
			} else {
				$waiting_notified = '';
			}
			if ($dbw->f("user_id")==0) {
				$waitinglist[] = '<a href="mailto:' . $dbw->f('notify_email') . '">' . $dbw->f('notify_email') . '</a>' . $waiting_notified;
			} else {
				$waitinglist[] = $dbw->f('name') . ' ('.$dbw->f('username') . ' - ' . '<a href="mailto:' . $dbw->f('notify_email') . '">' . $dbw->f('notify_email') . '</a>' . ')' . $waiting_notified;
			}
		}
		echo vmCommonHTML::getList( $waitinglist );
		echo '</td></tr></table>';
		$tabs->endTab();
	}
}
$tabs->endPane();

// Add necessary hidden fields
$formObj->hiddenField( 'product_id', $product_id );
$formObj->hiddenField( 'product_parent_id', $product_parent_id );
$formObj->hiddenField( 'pshop_mode', 'admin' );

if( !stristr( $db->f("product_thumb_image"), "http") && $clone_product != "1" ) {
	$formObj->hiddenField( 'product_thumb_image_curr', $db->f("product_thumb_image") );
}
if( !stristr( $db->f("product_full_image"), "http") && $clone_product != "1" ) {
	$formObj->hiddenField( 'product_full_image_curr', $db->f("product_full_image") );
}

$funcname = !empty($product_id) ? "productUpdate" : "productAdd";

// finally close the form:
$formObj->finishForm( $funcname, $next_page, $option );

?>

<script type="text/javascript">
//<!--
function toggleDisable( elementOnChecked, elementDisable, disableOnChecked ) {
	try {
		if( !disableOnChecked ) {
			if(elementOnChecked.checked==true) {
				elementDisable.disabled=false;
			}
			else {
				elementDisable.disabled=true;
			}
		}
		else {
			if(elementOnChecked.checked==true) {
				elementDisable.disabled=true;
			}
			else {
				elementDisable.disabled=false;
			}
		}
	}
	catch( e ) {}
}
// borrowed from OSCommerce with small modifications.
// All rights reserved.
var tax_rates = new Array();
<?php
foreach( $tax_rates as $tax_rate_id => $tax_rate ) {
	echo "tax_rates[\"$tax_rate_id\"] = $tax_rate;\n";
}
?>
function doRound(x, places) {
	return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
	var selected_value = document.adminForm.product_tax_id.selectedIndex;
	var parameterVal = document.adminForm.product_tax_id[selected_value].value;

	if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
		return tax_rates[parameterVal];
	} else {
		return 0;
	}
}

function updateGross() {
	if( document.adminForm.product_price.value != '' ) {
		var taxRate = getTaxRate();

		var r = new RegExp("\,", "i");
		document.adminForm.product_price.value = document.adminForm.product_price.value.replace( r, "." );

		var grossValue = document.adminForm.product_price.value;

		if (taxRate > 0) {
			grossValue = grossValue * (taxRate + 1);
		}

		document.adminForm.product_price_incl_tax.value = doRound(grossValue, 5);
	}
}

function updateNet() {
	if( document.adminForm.product_price_incl_tax.value != '' ) {
		var taxRate = getTaxRate();

		var r = new RegExp("\,", "i");
		document.adminForm.product_price_incl_tax.value = document.adminForm.product_price_incl_tax.value.replace( r, "." );

		var netValue = document.adminForm.product_price_incl_tax.value;

		if (taxRate > 0) {
			netValue = netValue / (taxRate + 1);
		}

		document.adminForm.product_price.value = doRound(netValue, 5);
	}
}

function updateDiscountedPrice() {
	if( document.adminForm.product_price.value != '' ) {
		try {
			var selected_discount = document.adminForm.product_discount_id.selectedIndex;
			var discountCalc = document.adminForm.product_discount_id[selected_discount].id;
			<?php if( PAYMENT_DISCOUNT_BEFORE == '1' ) : ?>
			var origPrice = document.adminForm.product_price.value;
			<?php else : ?>
			var origPrice = document.adminForm.product_price_incl_tax.value;
			<?php endif; ?>

			if( discountCalc ) {
				eval( 'var discPrice = ' + origPrice + discountCalc );
				if( discPrice != origPrice ) {
					document.adminForm.discounted_price_override.value = discPrice.toFixed( 2 );
				} else {
					document.adminForm.discounted_price_override.value = '';
				}
			}
		}
		catch( e ) { }
	}
}
function toggleProductList( enable ) {
	if(enable) {		
    	document.getElementById('list_style0').disabled = false;
       document.getElementById('list_style0').checked = true;
    	document.getElementById('list_style1').disabled = false;
       document.getElementById('display_headers').disabled = false;
    	document.getElementById('product_list_child').disabled = false;
       document.getElementById('product_list_type').disabled = false;
	}
    else {
    	document.getElementById('list_style0').disabled = true;
    	document.getElementById('list_style1').disabled = true;
       document.getElementById('display_headers').disabled = true;
    	document.getElementById('product_list_child').disabled = true;
       document.getElementById('product_list_type').disabled = true;
       document.getElementById('display_headers').checked = false;
    	document.getElementById('product_list_child').checked = false;
       document.getElementById('product_list_type').checked = false;
	}
}
updateGross();
updateDiscountedPrice();
<?php
if( @$_REQUEST['no_menu'] != '1') {
	?>
	toggleDisable( document.adminForm.product_full_image_action[1], document.adminForm.product_thumb_image, true );
	<?php
}
?>
var productSearchField = function(){

    var relds = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: 'index2.php?option=com_virtuemart&page=product.ajax_tools&task=getproducts&ajax_request=1&func=&no_menu=1&only_page=1&no_html=1&product_id=<?php echo $product_id ?>',
            method: 'GET' }),
        reader: new Ext.data.JsonReader({
            root: 'products',
            totalProperty: 'totalCount',
            id: 'product_id'
	        }, [
	            {name: 'product'},
	            {name: 'category'},
	            {name: 'product_id'}
	        ])
    });   
    // Custom rendering Template
    var resultTpl = new Ext.XTemplate( '<tpl for="."><div class="x-combo-list-item">{category} / {product}</div></tpl>' );
    relatedSelection = document.getElementById('relatedSelection');
    related_products = document.adminForm.related_products;
    var relProdSearch = new Ext.form.ComboBox({
    	applyTo: 'relatedProductSearch',
        store: relds,
        title: '<?php echo addslashes($VM_LANG->_('VM_PRODUCT_SELECT_ONE_OR_MORE')); ?>',
        displayField:'product',
        typeAhead: false,
        loadingText: '<?php echo addslashes($VM_LANG->_('VM_PRODUCT_SEARCHING')); ?>',
        width: 270,
        minListWidth: 270,
        pageSize:15,
        emptyText: "<?php  echo addslashes($VM_LANG->_('PHPSHOP_SEARCH_TITLE')); ?>",
        tpl: resultTpl,
        onSelect: function(record) {
        	for(var i=0;i<relatedSelection.options.length;i++) {
        		if(relatedSelection.options[i].value==record.id) {
        			return;
        		}
        	}
        	o = new Option( record.data.product, record.id );
        	relatedSelection.options[relatedSelection.options.length] = o;
        	if( related_products.value != '') {
        		related_products.value += '|' + record.id;
        	} else {
        		related_products.value += record.id;
        	}
        }
    });
	
};
var categorySearchField = function(){

    var relds = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: 'index2.php?option=com_virtuemart&page=product.ajax_tools&task=getcategories&ajax_request=1&func=&no_menu=1&only_page=1&no_html=1',
            method: 'GET'            
        }),
        reader: new Ext.data.JsonReader({
            root: 'categories',
            totalProperty: 'totalCount',
            id: 'category_id'
	        }, [
	            {name: 'category'},
	            {name: 'category_id'}
	        ])
    });
   
    // Custom rendering Template
    var resultTpl = new Ext.XTemplate(
    	'<tpl for="."><div class="x-combo-list-item">{category} (ID: {category_id})</div></tpl>'
    );
    relatedCatSelection = document.getElementById('relatedCatSelection');
    category_ids = document.adminForm.category_ids;
    var relProdSearch = new Ext.form.ComboBox({
    	applyTo: "categorySearch",
        store: relds,
        title: '<?php echo addslashes($VM_LANG->_('VM_PRODUCT_SELECT_ONE_OR_MORE')); ?>',
        displayField:'category',
        typeAhead: false,
        loadingText: '<?php echo addslashes($VM_LANG->_('VM_PRODUCT_SEARCHING')); ?>',
        width: 170,
        minListWidth: 170,
        pageSize:15,
        emptyText: "<?php  echo addslashes($VM_LANG->_('PHPSHOP_SEARCH_TITLE')); ?>",
        tpl: resultTpl,
        onSelect: function(record) {
        	for(var i=0;i<relatedCatSelection.options.length;i++) {
        		if(relatedCatSelection.options[i].value==record.id) {
        			return;
        		}
        	}
        	o = new Option( record.data.category, record.id );
        	
        	relatedCatSelection.options[relatedCatSelection.options.length] = o;
        	if( category_ids.value != '') {
        		category_ids.value += '|' + record.id;
        	} else {
        		category_ids.value += record.id;
        	}
        }
    });
	
};
if( Ext.isIE6 || Ext.isIE7 ) {
	Ext.EventManager.addListener( window, 'load', productSearchField );
	if( Ext.get("categorySearch") ) {
		Ext.EventManager.addListener( window, 'load', categorySearchField );
	}
}
else {
	Ext.onReady( productSearchField );
	if( Ext.get("categorySearch") ) {
		Ext.onReady( categorySearchField );
	}
}
function removeSelectedOptions(from, hiddenField ) {
	field = eval( "document.adminForm." + hiddenField );
	// Delete them from original
	var newOptions = [];
	for (var i=(from.options.length-1); i>=0; i--) {
		var o = from.options[i];
		if (o.selected) {
			from.options[i] = null;
		} else {
			newOptions.push(o.value);
		}
	}
	field.value = newOptions.join('|');
	
}
//-->
</script>
