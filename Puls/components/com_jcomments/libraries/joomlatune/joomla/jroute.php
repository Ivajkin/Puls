<?php
/**
 * Route helper
 *
 * @version 1.0
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 */

if (!defined('JOOMLATUNE_ROUTE')) {
	define('JOOMLATUNE_ROUTE', 1);

	class JoomlaTuneRoute
	{
		/**
		 * @param string $value Absolute or Relative URI to Joomla resource
		 * @return The translated humanly readable URL
		 */
		public static function _($value)
		{
			if (JOOMLATUNE_JVERSION === '1.5') {
				$url = str_replace('&amp;', '&', $value);
				$url = str_replace('&no_html=1', '&tmpl=component', $url);
				if (substr(strtolower($url), 0, 9) != "index.php") {
					return $url;
				}
				$uri = JURI::getInstance();
				$prefix = $uri->toString(array('scheme', 'host', 'port'));
				return $prefix . JRoute::_($url);
			} else {
				return sefRelToAbs($value);
			}
		}
	}
}
?>