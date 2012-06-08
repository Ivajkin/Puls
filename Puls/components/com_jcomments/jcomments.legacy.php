<?php
/**
 * JComments - Joomla Comment System
 *
 * Compatibility Tools (for Joomla 1.5 support)
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

if (!defined( 'DS' )) {
	define('DS', DIRECTORY_SEPARATOR);
}

define('JCOMMENTS_BASE', dirname(__FILE__));
define('JCOMMENTS_LIBRARIES', JCOMMENTS_BASE.'/libraries');
define('JCOMMENTS_MODELS', JCOMMENTS_BASE.'/models');
define('JCOMMENTS_HELPERS', JCOMMENTS_BASE.'/helpers');

require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jversion.php');
require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jroute.php');

if (JOOMLATUNE_JVERSION == '1.0') {
	global $mosConfig_absolute_path, $mosConfig_lang, $mainframe;
	define('JCOMMENTS_JVERSION', '1.0');
	define('JCOMMENTS_BACKEND', $mosConfig_absolute_path.'/administrator/components/com_jcomments');

	require_once (JCOMMENTS_LIBRARIES.'/joomlatune/language.php');
	require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jcache.php');
	require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jdocument.php');
	require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jdispatcher.php');
	require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jpluginhelper.php');

	$lang = $mosConfig_lang;

	if (!is_file(JCOMMENTS_BASE.'/languages/'.$lang.'.ini')) {
		$lang = 'english';
	}

	$languageRoot = JCOMMENTS_BASE.'/languages';
	if (isset($mainframe) && $mainframe->isAdmin()) {
		$languageRoot .= '/administrator';
	}

	$language = JoomlaTuneLanguage::getInstance();
	$language->load('com_jcomments', $languageRoot, $lang);

	$joomfish = $mosConfig_absolute_path.'/components/com_joomfish/joomfish.php';

	if (!class_exists('JText')) {
		$joomfish_class = $mosConfig_absolute_path.'/administrator/components/com_joomfish/joomfish.class.php';
		$joomfish_language = $mosConfig_absolute_path.'/administrator/components/com_joomfish/libraries/joomla/language.php';

		// small hack for JoomFish 1.8.2+ on Joomla 1.0.x
		if (is_file($joomfish) && is_file($joomfish_language)) {
			include_once ($joomfish_class);
			include_once ($joomfish_language);
			if (class_exists('JLanguageHelper')) {
				if (isset($mainframe) && $mainframe->isAdmin()) {
					$jfm = new JoomFishManager($mosConfig_absolute_path.'/administrator/components/com_joomfish');
					$adminLang = strtolower($jfm->getCfg('componentAdminLang'));
					$lng = JLanguageHelper::getLanguage($adminLang);
				} else {
					$lng = JLanguageHelper::getLanguage();
				}
				if (is_array($lng->_strings) && is_array($language->languages[$lang])) {
					$lng->_strings = array_merge($lng->_strings, $language->languages[$lang]);
				}
			}
		} else {
			require_once (JCOMMENTS_LIBRARIES.'/joomlatune/joomla/jtext.php');
		}
	} else {
		if (class_exists('JLanguageHelper')) {
			// small hack for JoomFish 1.8.2+ on Joomla 1.0.x
			$lng = JLanguageHelper::getLanguage();
			if (is_array($lng->_strings) && is_array($language->languages[$lang])) {
				$lng->_strings = array_merge($lng->_strings, $language->languages[$lang]);
			}
		}
	}
} else {
	$version = new JVersion();
	if (version_compare('1.6.0', $version->getShortVersion()) <= 0) {
		define('JCOMMENTS_JVERSION', '1.7');
	} else {
		define('JCOMMENTS_JVERSION', '1.5');
	}
	define('JCOMMENTS_BACKEND', JPATH_ROOT.'/administrator/components/com_jcomments');

	$option = JRequest::getCmd('option');
	$app = JFactory::getApplication();

	if ($option != 'com_jcomments') {
		$language = JFactory::getLanguage();
		$language->load('com_jcomments', JPATH_SITE);
	}
}

define('JCOMMENTS_CLASSES', JCOMMENTS_BACKEND.'/classes');
define('JCOMMENTS_TABLES', JCOMMENTS_BACKEND.'/tables');
?>