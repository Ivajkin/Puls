<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
* This file lists all shipping modules. It's in a file that's not called shipping_module_list
* because we currently can't add or remove shipping modules automatically!
*
* @version $Id: store.shipping_modules.php 1750 2009-05-01 06:25:34Z rolandd $
* @package VirtueMart
* @subpackage html
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
mm_showMyFileName( __FILE__ );

require_once( CLASSPATH. "ps_shipping_method.php" );
$ps_shipping_method = new ps_shipping_method;

 ?>
 <table width="100%" cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td>
      <br />&nbsp;&nbsp;&nbsp;<img src="<?php echo VM_THEMEURL ?>images/administration/dashboard/ups.gif" border="0" />
      <br /><br />
    </td>
    <td><span class="sectionname"><?php echo $VM_LANG->_('VM_SHIPPING_MODULE_LIST_LBL') ?></span></td>
  </tr>
</table>

<?php
$rows = $ps_shipping_method->method_list();
if ( !$rows ) {
     echo $VM_LANG->_('PHPSHOP_NO_SEARCH_RESULT');
}
else {
?>
  <table width="100%" class="adminlist">
    <tr> 
      <th width="20">#</th>
      <th width="20"><?php echo ucfirst($VM_LANG->_('VM_ENABLED')).'?' ?></th>
      <th class="title"><?php echo $VM_LANG->_('VM_SHIPPING_MODULE_LIST_NAME') ?></th>
      <th class="title"><?php echo $VM_LANG->_('VM_SHIPPING_MODULE_LIST_E_VERSION') ?></th>
      <th class="title"><?php echo $VM_LANG->_('VM_SHIPPING_MODULE_LIST_HEADER_AUTHOR') ?></th>
      <th class="title"><?php echo $VM_LANG->_('URL') ?></th>
      <th class="title"><?php echo $VM_LANG->_('CMN_EMAIL') ?></th>
      <th class="title"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_DESC_TITLE') ?></th>
    </tr>
<?php
    $i = 0;
    global $PSHOP_SHIPPING_MODULES;
    foreach( $rows as $row ) {
      	$i++;
         ?> 
      <tr class="row<?php echo $i%2 ?>"> 
        <td><?php echo( $i ); ?></td>
        <td><?php 
          if( in_array(str_replace('.php', '', $row['filename']), $PSHOP_SHIPPING_MODULES ) )
            echo "<img src=\"$mosConfig_live_site/administrator/images/tick.png\" border=\"0\" alt=\"" . $VM_LANG->_('PHPSHOP_ISSHIP_LIST_PUBLISH_LBL') . "\"  align=\"center\"/>";
        ?></td>
        <td width="19%"><?php
        echo $row["name"];
        echo "<br/>"; 
        
        if( $row['filename'] == "zone_shipping.php" ) {
        	echo "<a href=\"".$sess->url( $_SERVER['PHP_SELF']."?page=zone.zone_list" )."\">";
        }
        elseif( $row['filename'] == "standard_shipping.php" ) {
        	echo "<a href=\"".$sess->url( $_SERVER['PHP_SELF']."?page=shipping.rate_list.php" )."\">";
        }
        elseif( $row['filename'] == "no_shipping.php" ) {
        	//
        }	
        else {
              echo "<a href=\"".$sess->url( $_SERVER['PHP_SELF']."?page=store.shipping_module_form&shipping_module=".$row['filename'] )."\">";
        }
        
        if( $row['filename'] != 'no_shipping.php' ) {
        	echo $VM_LANG->_('PHPSHOP_ISSHIP_FORM_UPDATE_LBL')."</a>";
        }
        
          ?>
        </td>
        <td width="7%"><?php echo $row["version"]; ?></td>
        <td width="24%"><?php echo $row["author"]; ?></td>
        <td width="10%"><?php echo "<a target=\"_blank\" href=\"http://".$row["authorUrl"]."\">".$row["authorUrl"]."</a>"; ?>&nbsp;</td>
        <td width="10%"><?php echo $row["authorEmail"]; ?>&nbsp;</td>
        <td width="50%"><?php echo $row["description"]; ?>&nbsp;</td>
      </tr>
  <?php 
  } 
?> 
</table>
<?php 

}
?>
