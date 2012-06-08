<?php
/**
 * JComments - Joomla Comment System
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

/**
 * Joomla plugins helper
 * 
 * @package JComments
 **/
class JCommentsPluginHelper
{
	/**
	 * Gets the parameter object for a plugin
	 *
	 * @param string $pluginName The plugin name
	 * @param string $type The plugin type, relates to the sub-directory in the plugins directory
	 * @return JParameter A JParameter object (mosParameters for J1.0)
	 */
	public static function getParams($pluginName, $type = 'content')
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			static $mambotParams = array();
			$paramKey = $type . '_' . $pluginName;

			if (!isset($mambotParams[$paramKey])) {
				include_once (JCOMMENTS_BASE.DS.'jcomments.class.php');

				$dbo = JCommentsFactory::getDBO();
				$dbo->setQuery("SELECT params FROM #__mambots WHERE element = '$pluginName' AND folder = '$type'");
				$mambotParams[$paramKey] = $dbo->loadResult();
			}

			$data = $mambotParams[$paramKey];
			$pluginParams = new mosParameters($data);
		} elseif (JCOMMENTS_JVERSION == '1.5') {
 			$plugin	= JPluginHelper::getPlugin($type, $pluginName);
 			if (is_object($plugin)) {
		 		$pluginParams = new JParameter($plugin->params);
		 	} else {
		 		$pluginParams = new JParameter('');
		 	}
		} else {
			$plugin	= JPluginHelper::getPlugin($type, $pluginName);
 			if (is_object($plugin)) {
		 		$pluginParams = new JRegistry($plugin->params);
		 	} else {
		 		$pluginParams = new JRegistry('');
		 	}
		}
		return $pluginParams;
	}
}
?>