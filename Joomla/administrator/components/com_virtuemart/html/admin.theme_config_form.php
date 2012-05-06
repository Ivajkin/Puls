<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: admin.theme_config_form.php 1961 2009-10-12 20:18:00Z Aravot $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2006-2007 soeren - All rights reserved.
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

$theme = vmGet( $_REQUEST, 'theme', 'default' );
$themepath = $mosConfig_absolute_path.'/components/com_virtuemart/themes/'.basename( $theme );

if( !file_exists( $themepath )) {
	echo '<script type="text/javascript">alert(\''.str_replace('{theme}',basename( shopMakeHtmlSafe($theme) ),$VM_LANG->_('VM_ADMIN_THEME_NOT_EXISTS')).'\');history.back();</script>';
	exit;
}

if( !file_exists( $themepath . '/theme.config.php' )) {
	if( !fopen($themepath . '/theme.config.php', 'w')) {
		echo vmCommonHTML::getErrorField( $VM_LANG->_('VM_ADMIN_THEME_CFG_NOT_EXISTS') );
		return;
	}
}

$current_config = file_get_contents( $themepath . '/theme.config.php' );
$parameter_xml_file = $themepath.'/theme.xml';

// get params definitions
$params = new vmParameters( $current_config, $parameter_xml_file, 'theme' );

$title = '&nbsp;&nbsp;<img src="'. VM_THEMEURL .'images/administration/header/icon-48-config.png" align="middle" border="0" alt="'.$VM_LANG->_('VM_ADMIN_CFG_THEME_SETTINGS').'" />&nbsp;';
$title .= $VM_LANG->_('VM_ADMIN_CFG_THEME_SETTINGS');

//First create the object and let it print a form heading
$formObj = new formFactory( $title );
//Then Start the form
$formObj->startForm();

$ps_html->writableIndicator( $themepath . '/theme.config.php', 'text-align:left;width:78%;' );

vmCommonHTML::loadExtjs();

?>

	<fieldset style="width: 80%">
		<legend><?php echo $VM_LANG->_('VM_ADMIN_CFG_THEME_PARAMETERS') ?></legend>
		<table class="adminform">
		<tr>
			<td>
			<?php
			echo $params->render();
			?>
			</td>
		</tr>
		</table>
	</fieldset>
	
<?php
// Add necessary hidden fields
$formObj->hiddenField( 'ajax_request', $only_page );

// Close the form 
$formObj->finishForm( 'writeThemeConfig', 'store.index', $option );
?>
