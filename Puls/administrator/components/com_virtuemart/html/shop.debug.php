<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: shop.debug.php 1589 2008-12-08 20:27:20Z soeren_nb $
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

global $page, $last_page, $error, $database, $funcParams, $pagePermissionsOK, $_VERSION;
$return_to_page = vmGet( $_REQUEST, 'return_to_page' );
$i = 0;
if( !empty( $database->_log )) {
  foreach( $database->_log as $sql ) {
    if( strstr( $sql, "_{vm}_" ) || strstr( $sql, "'BT'" ) || strstr( $sql, "first_name"))
      $i++;
  }
}
echo '<br style="clear:both;" />';

include_once(ADMINPATH ."version.php");
$tabs = new vmTabPanel(1, 1, "debugpanel");
$tabs->startPane("debug-pane");
$tabs->startTab( "Shop Core Variables", "shop-variables" );
?>
      <table width="100%" border="0" cellspacing="5" cellpadding="2" >
        <tr class="sectiontableheader" nowrap> 
          <th colspan="4" align="center">
          <h3>DEBUG CENTER</h3>
          <?php echo "Version: $myVersion"; ?></th>
        </tr>
       
        <tr class="sectiontableentry1"> 
          <td align="right"><b>RunTime:</b></td>
          <td align="left"><?php echo @$runtime; ?> sec.&nbsp;</td>
          <td align="right" valign="top"><b>Current Page:</b></td>
          <td valign="top"><?php echo $page; ?></td>
        </tr>
       
        <tr class="sectiontableentry2"> 
          <td align="right" valign="top" nowrap><b>Queries executed:</b></td>
          <td valign="top" nowrap><?php 
            echo $database->_ticker 
                . "&nbsp;&nbsp;"
                .mm_ToolTip( "Note: This is only the number of queries related to VirtueMart, 
                              which have been processed so far. Because the component is wrapped 
                              into the Joomla! Framework, we can't get the total number of Queries at THIS point"); 
            ?>
          </td>
          <td align="right" valign="top"><b>Last Page:</b></td>
          <td valign="top"><?php echo empty($_SESSION['last_page']) ? "empty" : $_SESSION['last_page']; ?>&nbsp;</td>
        </tr>
       
        <tr class="sectiontableentry1"> 
          <td align="right" valign="top"><b>UID:</b></td>
          <td valign="top"><?php echo $auth["user_id"]; ?>&nbsp;</td>
          <td align="right" valign="top"><b>Return To Page:</b></td>
          <td valign="top"><?php echo $return_to_page; ?>&nbsp;</td>
        </tr>
       
        <tr class="sectiontableentry2"> 
          <td align="right" valign="top"><b>Username:</b></td>
          <td valign="top"><?php echo $auth["username"]; ?>&nbsp;</td>
          <td align="right" valign="top"><b>Function:</b></td>
          <td valign="top"><?php echo $func;?>&nbsp;</td>
        </tr>
        
        <tr class="sectiontableentry1" > 
          <td align="right" valign="top"><b>Perms:</b></td>
          <td valign="top"><?php echo $auth["perms"]; ?>&nbsp;</td>
          <td align="right" valign="top"><b>Command (Result):</b></td>
          <td valign="top"><?php echo $cmd." (" . ($ok ? "True": "False").') '; ?>&nbsp;</td>
        </tr>
       
        <tr class="sectiontableentry1"> 
          <td align="right" valign="top"><b>$func_perms:</b></td>
          <td valign="top"><?php 
          	if( !empty($funcParams["method"])) {
          		echo '<pre>'.print_r($funcParams, true).'</pre>'; 
          	}
          	else {
          		echo './.';
          	}
          		?>
          </td>
          <td align="right" valign="top"><b>$ps_vendor_id:</b></td>
          <td valign="top"><?php echo $ps_vendor_id; ?> &nbsp;&nbsp;</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr class="sectiontableentry2"> 
          <td align="right" valign="top"><b>$dir_perms:</b></td>
          <td valign="top"><?php echo $pagePermissionsOK ? 'Ok' : 'False';	?>&nbsp;</td>
          <td align="right" valign="top"><b>global Log:</b></td>
          <td valign="top"><?php echo $vmLogger->_ticker.' logged message(s).'; ?> &nbsp;&nbsp;</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr class="sectiontableentry1"> 
          <td align="right" valign="top"><b><?php echo '<strong>'.$_VERSION->PRODUCT.' Session ID:</strong>'; ?></b></td>
          <td colspan="3" valign="top"><?php echo $sess->getSessionId(); ?>&nbsp;</td>
        </tr>
        <tr class="sectiontableentry2"> 
          <td  align="right" valign="top"><b>VirtueMart Session ID:</b></td>
          <td colspan="3" valign="top"><?php echo session_id(); ?> &nbsp;&nbsp;</td>
        </tr>
        <tr class="sectiontableentry1"> 
          <td align="right"><b>$cart:</b></td>
          <td colspan="3"><?php   
          for ($i=0; $i < $_SESSION["cart"]["idx"];$i++) {
            echo "\$cart[$i]:ID[" . $_SESSION["cart"][$i]["product_id"];
            echo "]->Qty:[" . $_SESSION["cart"][$i]["quantity"] . "]<br />";
           } 
           ?></td>
        </tr>
        <tr class="sectiontableentry2" > 
          <td align="right" valign="top"><b>$auth:</b></td>
          <td colspan="3"><?php   print_r( $auth ); ?></td>
        </tr>
    </table>
<?php
$tabs->endTab();
$tabs->startTab( "Global Variables", "global-variables");
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="2" >
    
        <?php 
        if ($_POST) { ?>
        <tr class="sectiontableentry1"> 
          <td align="right" valign="top"><b>$_POST:</b></td>
          <td colspan="3" valign="top"><?php   
        	while (list($key, $val) = each($_POST)) {
              	if( is_array($val)) continue;
              	echo shopMakeHtmlSafe($key).'=&gt;'. shopMakeHtmlSafe($val).'<br/>';
            }
            ?>
          </td>
          <?php 
          }
          if ($_GET) { ?>
        <tr class="sectiontableentry1"> 
          <td align="right" valign="top"><b>$_GET:</b> </td>
          <td colspan="3" valign="top"><?php   
          	while (list($key, $val) = each($_GET)) {
              	if( is_array($val)) continue;
              	echo shopMakeHtmlSafe($key).'=&gt;'. shopMakeHtmlSafe($val).'<br/>';
            }
            ?>
          </td>
    <?php } ?>
        </tr>
        
         
        <?php
        if ($_COOKIE) { ?>
        <tr class="sectiontableentry2"> 
          <td align="right" valign="top"><b>$_COOKIE:</b></td>
          <td colspan="3" valign="top"><?php   
        while (list($key, $val) = each($_COOKIE)) {
              	if( is_array($val)) continue;
              	echo shopMakeHtmlSafe($key).'=&gt;'. shopMakeHtmlSafe($val).'<br/>';
            }  ?>
          </td>
         </tr>
          <?php 
        }
          ?>
          
        <tr class="sectiontableentry1"> 
        <?php
        if ($_SESSION) { ?>
          <td align="right" valign="top"><b>$_SESSION:</b></td>
          <td colspan="3" valign="top"><?php   
        	while (list($key, $val) = each($_SESSION)) {
              	if( is_object($val) ) {
              		echo shopMakeHtmlSafe($key).'=&gt; {Object}<br/>';
              	} elseif( is_array($val)) { 
              		echo shopMakeHtmlSafe($key).'=&gt; {Array}<br/>';
              	} else {
              		echo shopMakeHtmlSafe($key).'=&gt;'. shopMakeHtmlSafe($val).'<br/>';
              	}
            }
              ?>
          </td>
          <?php 
        }
        else {
          echo "<td colspan=\"4\"><strong>Something's wrong with your Session Setup - the Session is empty. VirtueMart cannot run without
          Sessions!</strong></td>";
        }
          ?>
         </tr>
        <tr class="sectiontableentry1"> 
          <td align="right" valign="top">&nbsp;</td>
          <td colspan="3" valign="top">&nbsp;</td>
        </tr>
        <tr class="sectiontableentry2">
          <td align="right" valign="top"><b>$vars:</b></td>
          <td colspan="3"><?php   
            while (list($val,$key) = each($vars)) {
            	if( !is_object($key) && !is_array($key)) {
            		echo "$val =&gt; $key<br/>";
            	}
            }
            ?>
          </td>
        </tr>
      </table>
<?php
$tabs->endTab();
$tabs->endPane();
?>

