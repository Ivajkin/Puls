<?php
/*
 *  This file is part of the Joomla Extension VirtueMart_Multiupload.
 *
 *  VirtueMart_Multiupload is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  VirtueMart_Multiupload is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with VirtueMart_Multiupload.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  @copyright Copyright (C) 2010- Markus Harmsen
 *  @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

define('VIRTUEMART_PATH',   'components/com_virtuemart/html');
define('PLUGIN_PATH',       '../plugins/system/virtuemart_multiupload');
define('PLUGIN_FILE',       'product.file_form_multi.php');

/**
 * Virtemart Multiupload system plugin
 */
class plgSystemVirtuemart_multiupload extends JPlugin {
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemTest( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}

	/**
	 * Insert script at "onAfterInitialise"
	 */
	function onAfterDispatch() {
        if(JRequest::getVar('option') != 'com_virtuemart' || JRequest::getVar('page') != 'product.product_list') return;
        
        //try to copy the extension file if needed
        if(!is_readable(PLUGIN_PATH . '/installed') && is_readable(VIRTUEMART_PATH)) {
            if(!copy(PLUGIN_PATH . '/' . PLUGIN_FILE, VIRTUEMART_PATH . '/' . PLUGIN_FILE)) {
                return;
            } else {
                @file_put_contents(PLUGIN_PATH . '/installed', 'installed');
            }
        }
        
        $document = &JFactory::getDocument();
        $document->addScriptDeclaration( "var mod_virtuemart_muliupload_root = '" . JURI::root() . "';" );
        JHTML::script('plg_virtuemart_multiupload.js', JURI::root() . 'plugins/system/virtuemart_multiupload/', true);
	}
}

?>