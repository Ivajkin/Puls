<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_communication.php 1660 2009-02-22 17:05:02Z tkahl $
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
 * The ask class is used to validate email details and send product_ask
 * details to the store admin
 * * methods:
 *       mail_question()
*************************************************************************/

/**
 * This class is used to validate email details and send 
 * product questions and recommendations
 *
 */
class vm_ps_communication {

	function validate( &$d ) {
		global $vmLogger, $VM_LANG;

		if (empty($d['sender_name']) ) {
			$vmLogger->err( $VM_LANG->_('CONTACT_FORM_NC',false) );
			return false;
		}

		if (empty($d['sender_mail']) || empty($d['recipient_mail'])) {
			$vmLogger->err( $VM_LANG->_('CONTACT_FORM_NC',false) );
			return false;
		}

		$validate = vmGet( $_POST, vmCreateHash(), 0 );

		// probably a spoofing attack
		if (!$validate) {
			$vmLogger->err( 'Hash not valid - '.vmCreateHash().$VM_LANG->_('NOT_AUTH',false) );
			return false;
		}

		if (!$_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$vmLogger->err( 'Request must be POSTed - ' . $VM_LANG->_('NOT_AUTH',false) );
			return false;
		}

		// Attempt to defend against header injections:
		$badStrings = array(
		'Content-Type:',
		'MIME-Version:',
		'Content-Transfer-Encoding:',
		'bcc:',
		'cc:'
		);

		// Loop through each POST'ed value and test if it contains
		// one of the $badStrings:
		foreach ($_POST as $k => $v){
			foreach ($badStrings as $v2) {
				if (strpos( $v, $v2 ) !== false) {
					$vmLogger->err( $VM_LANG->_('NOT_AUTH',false) );
					return false;
				}
			}
		}

		// Made it past spammer test, free up some memory
		// and continue rest of script:
		unset($v, $v2, $badStrings);

		$email 		= vmGet( $_POST, 'email', 		'' );
		$text 		= vmGet( $_POST, 'text', 			'' );
		
	    $sender_mail = vmGet( $_REQUEST, 'sender_mail', null);
	    $recipient_mail = vmGet( $_REQUEST, 'recipient_mail', null);
	    $message = vmGet( $_REQUEST, 'recommend_message', null);

		// Get Session Cookie `value`
		$sessioncookie 		= vmGet( $_COOKIE, 'virtuemart', null );

		if ( strlen($sessioncookie) < 16 || $sessioncookie == '-') {
			$vmLogger->err( $VM_LANG->_('VM_COOKIE_MISSING').'. '.$VM_LANG->_('NOT_AUTH',false) );
			return false;
		}

		// test to ensure that only one email address is entered
		$check = explode( '@', $email );
		if ( strpos( $email, ';' ) || strpos( $email, ',' ) || strpos( $email, ' ' ) || count( $check ) > 2 ) {
			$vmLogger->err( $VM_LANG->_('EMAIL_ERR_ONLYONE') );
			return false;
		}

		if ( (!$email&&!$sender_mail) || (!$text&&!$message)  ) {
			$vmLogger->err( $VM_LANG->_('CONTACT_FORM_NC',false) );
			return false;
		}
		if( !empty( $email )) {
			if( ps_communication::is_email( $email ) == false ) {
				$vmLogger->err( $VM_LANG->_('REGWARN_MAIL',false) );
				return false;
			}
		}
		if( !empty($sender_mail)) {
			if( !ps_communication::is_email( $sender_mail ) || !ps_communication::is_email( $recipient_mail ) ) {
				$vmLogger->err( $VM_LANG->_('EMAIL_ERR_NOINFO',false) );
				return false;
			}
		}
		return true;
	}

	/**
	 
 	*/    
	function mail_question(&$d) {
		global $vmLogger,  $Itemid, $_SESSION, $VM_LANG,$mosConfig_live_site,$mosConfig_lang, $sess;

		$db = new ps_DB;
		$product_id = (int)$d["product_id"];
		$q='SELECT * FROM #__{vm}_product WHERE product_id='.$product_id.' AND product_publish=\'Y\'';
		$db->query($q);
		if ( !$db->next_record() ) {
			$vmLogger->err( $VM_LANG->_('NOT_AUTH',false) );
			return false;
		}
		if ($db->f("product_sku") <> @$d["product_sku"] ) {
			$vmLogger->err( $VM_LANG->_('NOT_AUTH',false) );
			return false;
		}
		
		$Itemid = $sess->getShopItemid();
		$flypage = vmGet($_REQUEST, "flypage", null);
		// product url
		$product_url = $mosConfig_live_site."/index.php?option=com_virtuemart&page=shop.product_details&flypage=".urlencode($flypage)."&product_id=$product_id&Itemid=$Itemid";
		
		$dbv = new ps_DB;
		$qt = "SELECT * from #__{vm}_vendor ";
		$qt .= "WHERE vendor_id = '".$_SESSION['ps_vendor_id']."'";
		$dbv->query($qt);
		$dbv->next_record();
		$vendor_email = $dbv->f("contact_email");
		$shopper_email = $d["email"];
		$shopper_name = $d["name"];
		$subject_msg = vmRequest::getVar( 'text', '', 'post' );
		
		$shopper_subject = sprintf( $VM_LANG->_('VM_ENQUIRY_SHOPPER_EMAIL_SUBJECT'), $dbv->f("vendor_name"));
				
		$shopper_msg = str_replace( '{vendor_name}', $dbv->f("vendor_name"), $VM_LANG->_('VM_ENQUIRY_SHOPPER_EMAIL_MESSAGE') );
		$shopper_msg = str_replace( '{product_name}', $db->f("product_name"), $shopper_msg );
		$shopper_msg = str_replace( '{product_sku}', $db->f("product_sku"), $shopper_msg );
		$shopper_msg = str_replace( '{product_url}', $product_url, $shopper_msg );
		
		$shopper_msg = vmHtmlEntityDecode( $shopper_msg );
		
		//
		
		$vendor_subject = sprintf( $VM_LANG->_('VM_ENQUIRY_VENDOR_EMAIL_SUBJECT'), $dbv->f("vendor_name"), $db->f("product_name"));
		
		$vendor_msg = str_replace( '{shopper_name}', $shopper_name, $VM_LANG->_('VM_ENQUIRY_VENDOR_EMAIL_MESSAGE') );
		$vendor_msg = str_replace( '{shopper_message}', $subject_msg, $vendor_msg );
		$vendor_msg = str_replace( '{shopper_email}', $shopper_email, $vendor_msg );
		$vendor_msg = str_replace( '{product_name}', $db->f("product_name"), $vendor_msg );
		$vendor_msg = str_replace( '{product_sku}', $db->f("product_sku"), $vendor_msg );
		$vendor_msg = str_replace( '{product_url}', $product_url, $vendor_msg );
		
		$vendor_msg = vmHtmlEntityDecode( $vendor_msg );
		//END: set up text mail
		/////////////////////////////////////
		// Send text email
		//
		if (ORDER_MAIL_HTML == '0') {
			// Mail receipt to the shopper
			vmMail( $vendor_email, $dbv->f("vendor_name"), $shopper_email, $shopper_subject, $shopper_msg, "" );

			// Mail receipt to the vendor
			vmMail($shopper_email, $shopper_name, $vendor_email, $vendor_subject, $vendor_msg, "" );


		}
		////////////////////////////
		// set up the HTML email
		//
		elseif (ORDER_MAIL_HTML == '1') {
			// Mail receipt to the vendor
			$template = vmTemplate::getInstance();
			
			$template->set_vars( array(
															'vendorname' => $dbv->f("vendor_name"),
															'subject' => nl2br($subject_msg),
															'contact_name' => $shopper_name,
															'contact_email' => $shopper_email,
															'product_name' => $db->f("product_name"),
															'product_s_description' => $db->f("product_s_desc"),
															'product_url' =>$product_url,
															'product_sku' =>$db->f("product_sku")
			));
			
			if ($db->f("product_thumb_image")) {
				$imagefile = pathinfo($db->f("product_thumb_image"));
				$extension = $imagefile['extension'] == "jpg" ? "jpeg" : "jpeg";

				$EmbeddedImages[] = array(	'path' => IMAGEPATH."product/".$db->f("product_thumb_image"),
				'name' => "product_image",
				'filename' => $db->f("product_thumb_image"),
				'encoding' => "base64",
				'mimetype' => "image/".$extension );

				$template->set( 'product_thumb', '<img src="cid:product_image" alt="product_image" border="0" />' );
				$body = $template->fetch('order_emails/enquiry_email.tpl.php');

				$vendor_mail = vmMail( $shopper_email, $shopper_name, $vendor_email, $vendor_subject, $body, $vendor_msg, true, null, null, $EmbeddedImages);
			}
			else {
				$template->set( 'product_thumb', '' );
				$body = $template->fetch('order_emails/enquiry_email.tpl.php');

				$vendor_mail = vmMail( $shopper_email, $shopper_name, $vendor_email, $vendor_subject, $body, $vendor_msg, true, null, null, null);
			}

			//Send sender confirmation email
			$sender_mail = vmMail( $vendor_email, $dbv->f("vendor_name"), $shopper_email, $shopper_subject, $shopper_msg, "" );

			if ( ( !$vendor_mail ) || (!$sender_mail) ) {
				$vmLogger->debug( 'Something went wrong while sending the enquiry email to '.$vendor_email.' and '.$shopper_email );
				return false;
			}
		}

		return true;



	}
  function showRecommendForm( $product_id ) {
    global $VM_LANG, $vendor_store_name, $sess,$my;
    
    $sender_name = shopMakeHtmlSafe(vmGet( $_REQUEST, 'sender_name', null));
    $sender_mail = shopMakeHtmlSafe(vmGet( $_REQUEST, 'sender_mail', null));
    $recipient_mail = shopMakeHtmlSafe(vmGet( $_REQUEST, 'recipient_mail', null));
    $message = shopMakeHtmlSafe( vmGet( $_REQUEST, 'recommend_message'));
    
    echo '
    <form action="index2.php" method="post">
    
    <table border="0" cellspacing="2" cellpadding="1" width="80%">
      <tr>
        <td>'.$VM_LANG->_('EMAIL_FRIEND_ADDR').'</td>
        <td><input type="text" name="recipient_mail" size="50" value="'.(!empty($recipient_mail)?$recipient_mail:'').'" /></td>
      </tr>
      <tr>
        <td>'.$VM_LANG->_('EMAIL_YOUR_NAME').'</td>
        <td><input type="text" name="sender_name" size="50" value="'.(!empty($sender_name)?$sender_name:$my->name).'" /></td>
      </tr>
      <tr>
        <td>'.$VM_LANG->_('EMAIL_YOUR_MAIL').'</td>
        <td><input type="text" name="sender_mail" size="50" value="'.(!empty($sender_mail)?$sender_mail:$my->email).'" /></td>
      </tr>
      <tr>
        <td colspan="2">'.$VM_LANG->_('VM_RECOMMEND_FORM_MESSAGE').'</td>
      </tr>
      <tr>
        <td colspan="2">
          <textarea name="recommend_message" style="width: 100%; height: 200px">';
     
    if (!empty($message)) {
        echo stripslashes(str_replace( array('\r', '\n' ), array("\r", "\n" ), $message ));
    }
    else {
        $msg = sprintf($VM_LANG->_('VM_RECOMMEND_MESSAGE',false), $vendor_store_name, $sess->url( URL.'index.php?page=shop.product_details&product_id='.$product_id, true ));
        echo shopMakeHtmlSafe(stripslashes( str_replace( 'index2.php', 'index.php', $msg )));
    }

    echo '</textarea>
        </td>
      </tr>
    </table>
    
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="page" value="shop.recommend" />
    <input type="hidden" name="product_id" value="'.$product_id.'" />
    <input type="hidden" name="'.vmCreateHash().'" value="1" />
    <input type="hidden" name="Itemid" value="'.$sess->getShopItemid().'" />
    <input type="hidden" name="func" value="recommendProduct" />
    <input class="button" type="submit" name="submit" value="'.$VM_LANG->_('PHPSHOP_SUBMIT').'" />
    <input class="button" type="button" onclick="window.close();" value="'.$VM_LANG->_('CMN_CANCEL').'" />
    </form>
    ';
  }
  
  function sendRecommendation( &$d ) {
    global $vmLogger, $VM_LANG, $vendor_store_name;
    
    if (!$this->validate( $d )) {
        return false;
    }
    $subject = sprintf( $VM_LANG->_('VM_RECOMMEND_SUBJECT',false), $vendor_store_name );
    $msg = vmRequest::getVar( 'recommend_message', '', 'post' );
    $send = vmMail($d['sender_mail'], 
                   $d['sender_name'],
                   $d['recipient_mail'],
                   $subject,
                   $msg, ''
                  );
    
    if ($send) {
        $vmLogger->info( $VM_LANG->_('VM_RECOMMEND_DONE',false) );
    }
    else {
        $vmLogger->warning( $VM_LANG->_('VM_RECOMMEND_FAILED',false) );
        return false;
    }
    
    unset($_REQUEST['sender_name']);
    unset($_REQUEST['sender_mail']);
    unset($_REQUEST['recipient_mail']);
    unset($_REQUEST['recommend_message']);
    
    return true;    
  }
  
	function is_email($email){
		$rBool=false;

		if  ( preg_match( "/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/" , $email ) ){
			$rBool=true;
		}
		return $rBool;
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
	class ps_communication extends vm_ps_communication {}
}
?>
