<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend toolbar handler
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

if (defined('JPATH_ROOT') && defined('JPATH_LIBRARIES')) {
	include_once (JPATH_ROOT.DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php');
	require_once (JApplicationHelper::getPath('toolbar_html'));
	$task = JRequest::getCmd('task');
} else {
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
	global $mainframe;
	require_once ($mainframe->getCfg('absolute_path').DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php');
	require_once ($mainframe->getPath('toolbar_html'));
	require_once ($mainframe->getPath('toolbar_default'));
	$task = mosGetParam($_REQUEST, 'task');
}

if (!defined('JCOMMENTS_ENCODING')) {
	DEFINE('JCOMMENTS_ENCODING', strtolower(preg_replace('/charset=/', '', _ISO)));
	if (JCOMMENTS_ENCODING == 'utf-8') {
		// pattern strings are treated as UTF-8
		DEFINE('JCOMMENTS_PCRE_UTF8', 'u');
	} else {
		DEFINE('JCOMMENTS_PCRE_UTF8', '');
	}
}

switch ($task) {
	case 'comments':
		JCommentsToolbarHelper::comments();
		break;
	case 'comments.edit':
		JCommentsToolbarHelper::commentsEdit();
		break;
	case 'import':
		JCommentsToolbarHelper::import();
		break;
	case 'postinstall':
		JCommentsToolbarHelper::postInstall();
		break;
	case 'about':
		JCommentsToolbarHelper::about();
		break;
	case 'smiles':
		JCommentsToolbarHelper::smiles();
		break;
	case 'subscriptions':
	case 'subscription.cancel':
		JCommentsToolbarHelper::subscriptions();
		break;
	case 'subscription.new':
		JCommentsToolbarHelper::subscriptionsNew();
		break;
	case 'subscription.edit':
		JCommentsToolbarHelper::subscriptionsEdit();
		break;

	case 'custombbcodes':
		JCommentsToolbarHelper::customBBCode();
		break;
	case 'custombbcodes.new':
		JCommentsToolbarHelper::customBBCodeNew();
		break;
	case 'custombbcodes.edit':
		JCommentsToolbarHelper::customBBCodeEdit();
		break;

	case 'blacklist':
		JCommentsToolbarHelper::blacklist();
		break;
	case 'blacklist.new':
		JCommentsToolbarHelper::blacklistNew();
		break;
	case 'blacklist.edit':
		JCommentsToolbarHelper::blacklistEdit();
		break;

	case 'settings':
	case 'settings.save':
	case 'settings.cancel':
	case 'settings.restore':
		JCommentsToolbarHelper::settings();
		break;
	default:
		JCommentsToolbarHelper::comments();
		break;
}
?>