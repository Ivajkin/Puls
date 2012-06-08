<?php
/**
 * Plugin helper class
 *
 * @package 	Joomla.Framework
 * @subpackage	Plugins
 */

if (!class_exists('JPluginHelper')) {
	class JPluginHelper
	{
		/**
		 * Loads all the plugin files for a particular type if no specific plugin is specified
		 * otherwise only the specific pugin is loaded.
		 *
		 * @param string $type The plugin type, relates to the sub-directory in the plugins directory
		 * @return boolean True if success
		 */
		public static function importPlugin($type = 'jcomments')
		{
			global $_MAMBOTS;
			$_MAMBOTS->loadBotGroup($type);
		}
	}
}
?>