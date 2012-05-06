<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: account.order_details.tpl.php 2691 2011-02-06 18:07:26Z zanardi $
* @package VirtueMart
* @subpackage html
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
mm_showMyFileName( __FILE__ );

if( $db->f('order_number')) {
?>	



	<div class="pathway"><?php echo $vmPathway; ?></div>
	<div class="buttons_heading">
	<?php echo vmCommonHTML::PrintIcon('order_id='.$vars["order_id"]); ?>
	
	<br /><br />
	</div>
		 
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td valign="top">
	     <h2><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_LBL') ?></h2>
	     <p><?php echo ps_vendor::formatted_store_address(true) ?></p>
	    </td>
	    <td valign="top" width="10%" align="right"><?php echo $vendor_image; ?></td>
	  </tr>
	</table>
	<?php
	if ( $db->f("order_status") == "P" ) {
		// Copy the db object to prevent it gets altered
		$db_temp = ps_DB::_clone( $db );
	 /** Start printing out HTML Form code (Payment Extra Info) **/ ?>
	<table width="100%">
	  <tr>
	    <td width="100%" align="center">
	    <?php 
	    @include( CLASSPATH. "payment/".$dbpm->f("payment_class").".cfg.php" );
	
	    echo DEBUG ? vmCommonHTML::getInfoField('Beginning to parse the payment extra info code...' ) : '';
	
	    // Here's the place where the Payment Extra Form Code is included
	    // Thanks to Steve for this solution (why make it complicated...?)
	    if( eval('?>' . $dbpm->f("payment_extrainfo") . '<?php ') === false ) {
	    	echo vmCommonHTML::getErrorField( "Error: The code of the payment method ".$dbpm->f( 'payment_method_name').' ('.$dbpm->f('payment_method_code').') '
	    	.'contains a Parse Error!<br />Please correct that first' );
	    }
	      ?>
	    </td>
	  </tr>
	</table>
	<?php
		$db = $db_temp;
	}
	// END printing out HTML Form code (Payment Extra Info)
	?>
	<table border="0" cellspacing="0" cellpadding="2" width="100%">
	  <!-- begin customer information --> 
	  <tr class="sectiontableheader"> 
	    <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ACC_ORDER_INFO') ?></th>
	  </tr>
	  <tr> 
	    <td><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_NUMBER')?>:</td>
	    <td><?php printf("%08d", $db->f("order_id")); ?></td>
	  </tr>
	
	  <tr> 
		<td><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_DATE') ?>:</td>
	    <td><?php echo vmFormatDate($db->f("cdate")+$time_offset); ?></td>
	  </tr>
	  <tr> 
	    <td><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_STATUS') ?>:</td>
	    <td><?php echo ps_order_status::getOrderStatusName( $db->f("order_status") ); ?></td>
	  </tr>
	  <!-- End Customer Information --> 
	  <!-- Begin 2 column bill-ship to --> 
	  <tr class="sectiontableheader"> 
	    <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_CUST_INFO_LBL') ?></th>
	  </tr>
	  <tr valign="top"> 
	    <td width="50%"> <!-- Begin BillTo -->
	      <table width="100%" cellspacing="0" cellpadding="2" border="0">
	        <tr> 
	          <td colspan="2"><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_BILL_TO_LBL') ?></strong></td>
	        </tr>
	        <?php 
		foreach( $registrationfields as $field ) {
			if( $field->type == 'captcha') continue;
			if( $field->name == 'email') $field->name = 'user_email';
			?>
		  <tr> 
			<td align="right"><?php echo $VM_LANG->_($field->title) ? $VM_LANG->_($field->title) : $field->title ?>:</td>
			<td><?php
				switch($field->name) {
		          	case 'country':
		          		require_once(CLASSPATH.'ps_country.php');
		          		$country = new ps_country();
		          		$dbc = $country->get_country_by_code($dbbt->f($field->name));
	          			if( $dbc !== false ) echo $dbc->f('country_name');
		          		break;
		          	default: 
		          		echo $dbbt->f($field->name);
		          		break;
		          }
		          ?>
			</td>
		  </tr>
		  <?php
			}
		   ?>
	      </table>
	      <!-- End BillTo --> </td>
	    <td width="50%"> <!-- Begin ShipTo --> <?php
	    // Get ship_to information
	    $dbbt->next_record();
	    $dbst =& $dbbt;
	
	  ?> 
	 <table width="100%" cellspacing="0" cellpadding="2" border="0">
	        <tr> 
	          <td colspan="2"><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIP_TO_LBL') ?></strong></td>
	        </tr>
	        <?php 
		foreach( $shippingfields as $field ) {
			if( $field->name == 'email') $field->name = 'user_email';
			?>
		  <tr> 
			<td width="35%" align="right">&nbsp;<?php echo $VM_LANG->_($field->title) ? $VM_LANG->_($field->title) : $field->title ?>:</td>
			<td width="65%"><?php
				switch($field->name) {
		          	case 'country':
		          		require_once(CLASSPATH.'ps_country.php');
		          		$country = new ps_country();
		          		$dbc = $country->get_country_by_code($dbst->f($field->name));
		          		if( $dbc !== false ) echo $dbc->f('country_name');
		          		break;
		          	default: 
		          		echo $dbst->f($field->name);
		          		break;
		          }
		          ?>
			</td>
		  </tr>
		  <?php
			}
		   ?>
	      </table>
	      <!-- End ShipTo --> 
	      <!-- End Customer Information --> 
	    </td>
	  </tr>
	  <tr> 
	    <td colspan="2">&nbsp;</td>
	  </tr>
	  <?php if ( $PSHOP_SHIPPING_MODULES[0] != "no_shipping" && $db->f("ship_method_id")) { ?> 
	  <tr> 
	    <td colspan="2"> 
	      <table width="100%" border="0" cellspacing="0" cellpadding="1">
	        
	        <tr class="sectiontableheader"> 
	          <th align="left"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_CUST_SHIPPING_LBL') ?></th>
	        </tr>
	        <tr> 
	          <td> 
	            <table width="100%" border="0" cellspacing="0" cellpadding="0">
	              <tr> 
	                <td><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_CARRIER_LBL') ?></strong></td>
	                <td><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_MODE_LBL') ?></strong></td>
	                <td><strong><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PRICE') ?>&nbsp;</strong></td>
	              </tr>
	              <tr> 
	                <td><?php 
	                $details = explode( "|", $db->f("ship_method_id"));
	                echo $details[1];
	                    ?>&nbsp;
	                </td>
	                <td><?php 
	                echo $details[2];
	                    ?>
	                </td>
	                <td><?php 
	                     echo $CURRENCY_DISPLAY->getFullValue($details[3], '', $db->f('order_currency'));
	                    ?>
	                </td>
	              </tr>
	            </table>
	          </td>
	        </tr>
	        
	      </table>
	    </td>
	  </tr><?php
	  }
	
	  ?> 
	  <tr>
	    <td colspan="2">&nbsp;</td>
	  </tr>
	  <!-- Begin Order Items Information --> 
	  <tr class="sectiontableheader"> 
	    <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ORDER_ITEM') ?></th>
	  </tr>
	  <tr>
	    <td colspan="4">
	<?php
	$dbdl = new ps_DB;
	/* Check if the order has been paid for */
	if ($db->f("order_status") == ENABLE_DOWNLOAD_STATUS && ENABLE_DOWNLOADS) {
	
		$q = "SELECT `download_id` FROM #__{vm}_product_download WHERE";
		$q .= " order_id =" .(int)$vars["order_id"];
		$dbdl->query($q);
	
		// $q = "SELECT * FROM #__{vm}_product_download WHERE order_id ='" . $db->f("order_id")
		// $dbbt->query($q);
	
	
		// check if download records exist for this purchase order
		if ($dbdl->next_record()) {
			echo "<b>" . $VM_LANG->_('PHPSHOP_DOWNLOADS_CLICK') . "</b><br /><br />";
	
			echo($VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_3').DOWNLOAD_MAX.". <br />");
	
			$expire = ((DOWNLOAD_EXPIRE / 60) / 60) / 24;
			echo(str_replace("{expire}", $expire, $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_4')));
			
			echo "<br /><br />";
		}
		//else {
			//echo "<b>" . $VM_LANG->_('PHPSHOP_DOWNLOADS_EXPIRED') . "</b><br /><br />";
		//}
	}
	?>
	    </td>
	  </tr>
	  <!-- END HACK EUGENE -->
	  <tr> 
	    <td colspan="2"> 
	      <table width="100%" cellspacing="0" cellpadding="2" border="0">
	        <tr align="left">
	          <th><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_QTY') ?></th>
	          <th><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_NAME') ?></th>
	          <th><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SKU') ?></th>
	          <th><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PRICE') ?></th>
	          <th align="right"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL') ?>&nbsp;&nbsp;&nbsp;</th>
	        </tr>
	        <?php
	        $dbcart = new ps_DB;
	        $q  = "SELECT * FROM #__{vm}_order_item ";
	        $q .= "WHERE #__{vm}_order_item.order_id='$order_id' ";
	        $dbcart->query($q);
	        $subtotal = 0;
	        $dbi = new ps_DB;
			$dbdel = new ps_DB;

	        while ($dbcart->next_record()) {
	
	        	if ($db->f("order_status") == ENABLE_DOWNLOAD_STATUS && ENABLE_DOWNLOADS) {
	        		/* search for download record that corresponds to this order item */
	        		$q = "SELECT `download_id`, `file_name`, `download_max`, `end_date` FROM #__{vm}_product_download WHERE";
	        		$q .= " `order_id`=" . intval($vars["order_id"]);
	        		$q .= " AND `product_id`=". intval($dbcart->f("product_id"));
	        		$dbdl->query($q);
	
	        	}
	        	/* END HACK EUGENE */

	        	$product_id = null;

// ***** Add product_publish to SELECT statement.

	        	$dbi->query( "SELECT product_id, product_publish FROM #__{vm}_product WHERE product_sku='".$dbcart->f("order_item_sku")."'");
	        	$dbi->next_record();
	        	$product_id = $dbi->f("product_id" );

// ***** Set new flag to guard against the output of the product link by checking existance of product and its published status.

				$link_to_product = (!empty( $product_id ) && ($dbi->f('product_publish') == 'Y'));
	?>
	        <tr align="left">
	          <td valign="top"><?php $dbcart->p("product_quantity"); ?></td>
	          <td valign="top"><?php
	              if ($dbdl->next_record()) {

					// First output a link to the product or just the product name if the product has been removed.

					if( $link_to_product) {
						echo '<a href="'.$sess->url( $mm_action_url."index.php?page=shop.product_details&product_id=$product_id") .'" title="'.$dbcart->f("order_item_name").'">';
					}

				  	echo $dbcart->f("order_item_name");

					if( $link_to_product) {
						echo "</a>";
					}

// ***** This is all new code to output multiple links and download expiration details.

					// Now loop through each download and output links to each filename.

					do {

						$download_id = $dbdl->f('download_id');
						$download_max = (int)$dbdl->f('download_max');
						$end_date = (int)$dbdl->f('end_date');
						$time = time();
					
						// If the download has maxed out or expired then delete it from the database and don't display it.
					
						if (($download_max < 1) || (($end_date != 0) && ($time > $end_date))) {
							$q ="DELETE FROM #__{vm}_product_download";
							$q .=" WHERE download_id = '" . $download_id . "'";
							$dbdel->query($q);
							$dbdel->next_record();
						} else {
					
							// Hyperlink the downloadable order item direct to the downloadFunction.

// ***** NOTE: URL changed to directly access download file.
// ***** NOTE: May wish to consider adding classname to <p> for easier control of layout and maybe change <p> to <div>.
						
							$url = $sess->url( $mm_action_url."/index.php?option=com_virtuemart&page=shop.downloads&func=downloadRequest");
							echo '<p><a href="'."$url&download_id=".$download_id.'" title="'.$VM_LANG->_('PHPSHOP_DOWNLOADS_LINK').'">'
									. '<img src="'.VM_THEMEURL.'images/download.png" alt="'.$VM_LANG->_('PHPSHOP_DOWNLOADS_LINK').'" align="left" border="0" />'
									. $dbdl->f('file_name')
									. '</a><br/>(';
						
							// Output downloads remaining and expiration date.						

// ***** NOTE: May wish to set this up as a configuration option or leave it to the developer as it is in a theme template.
						
							if ($download_max > 1)
								echo str_replace("{count}", $download_max, $VM_LANG->_('PHPSHOP_DOWNLOADS_REMAINING'));
							else
								echo str_replace("{count}", $download_max, $VM_LANG->_('PHPSHOP_DOWNLOAD_REMAINING'));
						
							if ($end_date > 0)
								echo str_replace("{date}", date('d/m/y', $end_date), $VM_LANG->_('PHPSHOP_DOWNLOAD_VALID_UNTIL'));

						
							echo ')<p>';
						}
				  	} while ($dbdl->next_record());
				  }
	        		else {

// ***** Change guard to use new flag $link_to_product instead of !empty( $product_id )

			        	if( $link_to_product) {
			          		echo '<a href="'.$sess->url( $mm_action_url."index.php?page=shop.product_details&product_id=$product_id") .'" title="'.$dbcart->f("order_item_name").'">';
			          	}
			          	$dbcart->p("order_item_name");
			          	echo " <div style=\"font-size:smaller;\">" . $dbcart->f("product_attribute") . "</div>";

// ***** Change guard to use new flag $link_to_product instead of !empty( $product_id )

			          	if( $link_to_product) {
			          		echo "</a>";
			          	}
	        		}
			?>
	          </td>
	          <td valign="top"><?php $dbcart->p("order_item_sku"); ?></td>
	          <td valign="top"><?php /*
			$price = $ps_product->get_price($dbcart->f("product_id"));
			$item_price = $price["product_price"]; */
			if( $auth["show_price_including_tax"] ){
				$item_price = $dbcart->f("product_final_price");
			}
			else {
				$item_price = $dbcart->f("product_item_price");
			}
			echo $CURRENCY_DISPLAY->getFullValue($item_price, '', $db->f('order_currency'));
	
	           ?></td>
	          <td valign="top" align="right"><?php $total = $dbcart->f("product_quantity") * $item_price; 
	          $subtotal += $total;
	          echo $CURRENCY_DISPLAY->getFullValue($total, '', $db->f('order_currency'));
	           ?>&nbsp;&nbsp;&nbsp;</td>
	        </tr><?php
	        }
	?> 
	        <tr> 
	          <td colspan="4" align="right">&nbsp;&nbsp;</td>
	          <td>&nbsp;</td>
	        </tr>
	        <tr> 
	          <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SUBTOTAL') ?> :</td>
	          <td align="right"><?php echo $CURRENCY_DISPLAY->getFullValue($subtotal, '', $db->f('order_currency')) ?>&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	<?php 
	/* COUPON DISCOUNT */
	$coupon_discount = $db->f("coupon_discount");
	$order_discount = $db->f("order_discount");
	
	if( PAYMENT_DISCOUNT_BEFORE == '1') {
		if (($db->f("order_discount") != 0)) {
	?>
	          <tr>
	              <td colspan="4" align="right"><?php 
	              if( $db->f("order_discount") > 0)
	              echo $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT');
	              else
	              echo $VM_LANG->_('PHPSHOP_FEE');
	                ?>:
	              </td> 
	              <td align="right"><?php
	              if ($db->f("order_discount") > 0 ) {
	              	echo "- ".$CURRENCY_DISPLAY->getFullValue(abs($db->f("order_discount")), '', $db->f('order_currency'));
	              }
	              elseif ($db->f("order_discount") < 0 )  {
	              	echo "+ ".$CURRENCY_DISPLAY->getFullValue(abs($db->f("order_discount")), '', $db->f('order_currency'));
	              } 
	              ?>
	              &nbsp;&nbsp;&nbsp;</td>
	          </tr>
	        
	        <?php 
		}
		if( $coupon_discount > 0 ) {
	?>
	        <tr>
	          <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_DISCOUNT') ?>:
	          </td> 
	          <td align="right"><?php
	            echo "- ".$CURRENCY_DISPLAY->getFullValue( $coupon_discount, '', $db->f('order_currency') ); ?>&nbsp;&nbsp;&nbsp;
	          </td>
	        </tr>
	<?php
		}
	}
	?>        
	        <tr> 
	          <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING') ?> :</td>
	          <td align="right"><?php 
	          $shipping_total = $db->f("order_shipping");
	          if ($auth["show_price_including_tax"] == 1)
	          $shipping_total += $db->f("order_shipping_tax");
	          echo $CURRENCY_DISPLAY->getFullValue($shipping_total, '', $db->f('order_currency'));
	
	            ?>&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	  <?php
	  $tax_total = $db->f("order_tax") + $db->f("order_shipping_tax");
	  if ($auth["show_price_including_tax"] == 0) {
	  ?>
	        <tr> 
	          <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL_TAX') ?> :</td>
	          <td align="right"><?php 
	
	          echo $CURRENCY_DISPLAY->getFullValue($tax_total, '', $db->f('order_currency'));
	            ?>&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	<?php
	  }
	  if( PAYMENT_DISCOUNT_BEFORE != '1') {
	  	if (($db->f("order_discount") != 0)) {
	?>
	          <tr>
	              <td colspan="4" align="right"><?php 
	              if( $db->f("order_discount") > 0)
	              echo $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT');
	              else
	              echo $VM_LANG->_('PHPSHOP_FEE');
	                ?>:
	              </td> 
	              <td align="right"><?php
	              if ($db->f("order_discount") > 0 )
	              echo "- ".$CURRENCY_DISPLAY->getFullValue(abs($db->f("order_discount")), '', $db->f('order_currency'));
	              elseif ($db->f("order_discount") < 0 )
	                 echo "+ ".$CURRENCY_DISPLAY->getFullValue(abs($db->f("order_discount")), '', $db->f('order_currency')); ?>
	              &nbsp;&nbsp;&nbsp;</td>
	          </tr>
	        
	        <?php 
	  	}
	  	if( $coupon_discount > 0 ) {
	?>
	        <tr>
	          <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_DISCOUNT') ?>:
	          </td> 
	          <td align="right"><?php
	            echo "- ".$CURRENCY_DISPLAY->getFullValue( $coupon_discount, '', $db->f('order_currency') ); ?>&nbsp;&nbsp;&nbsp;
	          </td>
	        </tr>
	<?php
	  	}
	  }
	?>      <tr> 
	          <td colspan="3" align="right">&nbsp;</td>
	          <td colspan="2" align="right"><hr/></td>
	        </tr>
	        <tr> 
	          <td colspan="4" align="right">
	          <strong><?php echo $VM_LANG->_('PHPSHOP_CART_TOTAL') .": "; ?></strong>
	          </td>
	          
	          <td align="right"><strong><?php  
	          $total = $db->f("order_total");
	          echo $CURRENCY_DISPLAY->getFullValue($total, '', $db->f('order_currency'));
	          ?></strong>&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	  <?php
	  if ($auth["show_price_including_tax"] == 1) {
	  ?>
	        
	        <tr> 
	          <td colspan="3" align="right">&nbsp;</td>
	          <td colspan="2" align="right"><hr/></td>
	        </tr>
	        <tr> 
	          <td colspan="4" align="right"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL_TAX') ?> :</td>
	          <td align="right"><?php 
	
	          echo $CURRENCY_DISPLAY->getFullValue($tax_total, '', $db->f('order_currency'));
			  
	            ?>&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	<?php
	  }
	  ?>    <tr> 
	          <td colspan="3" align="right">&nbsp;</td>
	          <td colspan="2" align="right"><hr/></td>
	        </tr>
	        <tr> 
	          <td colspan="3" align="right">&nbsp;</td>
	          <td colspan="2" align="right"><?php 
					echo ps_checkout::show_tax_details( $db->f('order_tax_details'), $db->f('order_currency') );
	            ?>&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	 </table>
	  <!-- End Order Items Information --> 
	
	<br />
	
	  <!-- Begin Payment Information --> 
	
	      <table width="100%">
	      <tr class="sectiontableheader"> 
	        <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PAYINFO_LBL') ?></th>
	      </tr>
	      <tr> 
	        <td width="20%"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_PAYMENT_LBL') ?> :</td>
	        <td><?php $dbpm->p("payment_method_name"); ?> </td>
	      </tr>
		  <?php
		  require_once(CLASSPATH.'ps_payment_method.php');
		  $ps_payment_method = new ps_payment_method;
		  $payment = $dbpm->f("payment_method_id");
	
		  if ($ps_payment_method->is_creditcard($payment)) {
	
		  	// DECODE Account Number
		  	$dbaccount = new ps_DB;
		  	$q = 'SELECT '.VM_DECRYPT_FUNCTION.'(order_payment_number,\''.ENCODE_KEY.'\') as account_number 
		  				FROM #__{vm}_order_payment WHERE order_id=\''.$order_id.'\'';
		  	$dbaccount->query($q);
	        $dbaccount->next_record();
	        if( $dbaccount->f("order_payment_name")) {
		        ?>	        
		      <tr> 
		        <td width="10%"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_ACCOUNT_NAME') ?> :</td>
		        <td><?php $dbpm->p("order_payment_name"); ?> </td>
		      </tr>
		      <?php 
		  }
	      if( $dbaccount->f("account_number")) {?>
		      <tr> 
		        <td><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_ACCOUNT_NUMBER') ?> :</td>
		        <td> <?php echo ps_checkout::asterisk_pad($dbaccount->f("account_number"),4);
		    ?> </td>
		      </tr>
		      <?php 
	      }
	      if( $dbpm->f("order_payment_expire") ) {
		      ?>
		      <tr> 
		        <td><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_EXPIRE_DATE') ?> :</td>
		        <td><?php echo $dbpm->f("order_payment_expire") ? strftime("%m - %Y", $dbpm->f("order_payment_expire")) : ''; ?> </td>
		      </tr>
			  <?php
	      } 
	   } ?>
	      <!-- end payment information --> 
	      </table>
	
	<?php // }
	
	    /** Print out the customer note **/
	    if ( $db->f("customer_note") ) {
	    ?>
	    <table width="100%">
	      <tr>
	        <td colspan="2">&nbsp;</td>
	      </tr>
	      <tr class="sectiontableheader">
	        <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_CUSTOMER_NOTE') ?></th>
	      </tr>
	      <tr>
	        <td colspan="2">
	         <?php echo nl2br($db->f("customer_note"))."<br />"; ?>
	       </td>
	      </tr>
	    </table>
	    <?php
	    }
}
else {
	echo '<h4>'._LOGIN_TEXT .'</h4><br/>';
	include(PAGEPATH.'checkout.login_form.php');
	echo '<br/><br/>';
}