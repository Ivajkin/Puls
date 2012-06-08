<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend install handler
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

function com_install()
{
	if (defined('_JEXEC') && class_exists('JApplication')) {
		$config = JFactory::getConfig();
		$config->setValue('config.live_site', substr_replace(JURI::root(), '', -1, 1));
		$url = JURI::root() . 'administrator/index.php?option=com_jcomments&task=postinstall';

		$version = new JVersion();
		if (version_compare('1.6.0', $version->getShortVersion()) > 0) {
			require_once (dirname(__FILE__).DS.'install'.DS.'helpers'.DS.'language.php');
			JCommentsInstallerLanguageHelper::convertLanguages15();
		}
	} else {
		global $mainframe;

		$componentPath = $mainframe->getCfg('absolute_path').DS.'components'.DS.'com_jcomments';
		require_once ($componentPath.DS.'libraries'.DS.'joomlatune'.DS.'filesystem.php');
		require_once ($componentPath.DS.'jcomments.legacy.php');
		require_once (dirname(__FILE__).DS.'install'.DS.'helpers'.DS.'installer.php');
		JCommentsInstallerHelper::extractJCommentsLibraryConvert();

		if (is_file($componentPath.DS.'libraries'.DS.'convert'.DS.'utf8.class.php')) {
			require_once (dirname(__FILE__).DS.'install'.DS.'helpers'.DS.'language.php');
			JCommentsInstallerLanguageHelper::convertLanguages10();
		}
		$url = $mainframe->getCfg('live_site') . '/administrator/index2.php?option=com_jcomments&task=postinstall';
	}

	if (headers_sent()) {
		echo ('<script type="text/javascript">document.location.href="' . $url . '";</script>');
	} else {
		header('Location: ' . $url);
	}
}
?>