<?php
/**
 * JComments - Joomla Comment System
 *
 * System plugin for attaching JComments CSS & JavaScript to HEAD tag
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

global $_MAMBOTS;
$_MAMBOTS->registerFunction('onAfterStart', 'plgSystemJComments');

function plgSystemJComments()
{
	global $mosConfig_absolute_path, $mainframe;
	include_once ($mosConfig_absolute_path . '/components/com_jcomments/jcomments.legacy.php');

	if (!defined('JCOMMENTS_JVERSION')) {
		return;
	}

	include_once (JCOMMENTS_BASE . '/jcomments.class.php');
	include_once (JCOMMENTS_BASE . '/jcomments.config.php');
	include_once (JCOMMENTS_HELPERS . '/system.php');

	$document = JCommentsFactory::getDocument();

	if (!defined('JCOMMENTS_CSS')) {
		$document->addStyleSheet(JCommentsSystemPluginHelper::getCSS());
		define('JCOMMENTS_CSS', 1);
	}

	if (!$mainframe->isAdmin()) {
		if (!defined('JCOMMENTS_JS')) {
			$document->addScript(JCommentsSystemPluginHelper::getCoreJS());
			define('JCOMMENTS_JS', 1);
		}
		if (!defined('JOOMLATUNE_AJAX_JS')) {
			$document->addScript(JCommentsSystemPluginHelper::getAjaxJS());
			define('JOOMLATUNE_AJAX_JS', 1);
		}
	}
}
?>