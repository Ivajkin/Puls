<?php
/**
 * Text  handling class
 *
 * @package 	Joomla.Framework
 * @subpackage	Language
 */

if (!class_exists('JText')) {
	class JText
	{
		public static function _( $text, $jsSafe = false )
		{
			$lang = JoomlaTuneLanguage::getInstance();
			return $lang->_($text, $jsSafe);
		}

		public static function sprintf( $string )
		{
			$lang = JoomlaTuneLanguage::getInstance();
			$args = func_get_args();
			if (count($args) > 0) {
				$args[0] = $lang->_($args[0]);
				return call_user_func_array('sprintf', $args);
			}
			return '';
		}

		public static function plural($string, $n)
		{
			$lang = JoomlaTuneLanguage::getInstance();
			$args = func_get_args();
			$count = count($args);

			if ($count > 1) {
				$suffix = JoomlaTuneLanguageTools::getPluralSuffix($lang->getLanguage(), $n);
				$key = $string . '_' . $suffix;
				if ($lang->hasKey($key)) {
					$args[0] = $lang->_($key);
				} else {
					$args[0] = $lang->_($string);
				}
			} elseif ($count > 0) {
				$args[0] = $lang->_($string);
			}

			return call_user_func_array('sprintf', $args);
		}
	}
}
?>