<?php
/**
 * JComments - Joomla Comment System
 *
 * Plugin for attaching comments list and form to content item
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

global $mainframe;
include_once ($mainframe->getCfg('absolute_path') . '/components/com_jcomments/jcomments.legacy.php');

if (!defined('JCOMMENTS_JVERSION')) {
	return;
}

global $_MAMBOTS;
$_MAMBOTS->registerFunction('onAfterDisplayContent', 'plgContentJCommentsViewJ10');
$_MAMBOTS->registerFunction('onPrepareContent', 'plgContentJCommentsLinksJ10');

function plgContentJCommentsViewJ10(&$row, &$params, $page = 0)
{
	global $task, $option;

	if (!isset($params)) {
		$params = new mosParameters('');
	}

	$pvars = array_keys(get_object_vars($params->_params));

	if ($params->get('popup') || in_array('moduleclass_sfx', $pvars)) {
		return '';
	}

	if (isset($GLOBALS['jcomments_params_readmore'])
			&& isset($GLOBALS['jcomments_row_readmore'])) {
		$params->set('readmore', $GLOBALS['jcomments_params_readmore']);
		$row->readmore = $GLOBALS['jcomments_row_readmore'];
	}

	require_once (JCOMMENTS_BASE . '/jcomments.php');
	require_once (JCOMMENTS_HELPERS . '/content.php');

	JCommentsContentPluginHelper::processForeignTags($row, false, false);

	if (JCommentsContentPluginHelper::isDisabled($row)) {
		return '';
	}

	if (($task == 'view')
			&& (JCommentsContentPluginHelper::checkCategory($row->catid)
					|| JCommentsContentPluginHelper::isEnabled($row))) {

		if (JCommentsContentPluginHelper::isLocked($row)) {
			$config = JCommentsFactory::getConfig();
			$config->set('comments_locked', 1);
		}
		return JComments::show($row->id, 'com_content', $row->title);
	} else if (($option == 'com_events') && ($task == 'view_detail')) {
		return JComments::show($row->id, 'com_events', $row->title);
	}
	return '';
}

function plgContentJCommentsLinksJ10($published, &$row, &$params, $page = 0)
{
	global $task, $option, $my;

	// disable comments link in 3rd party components (except Events and AlphaContent)
	if ($option != 'com_content' && $option != 'com_frontpage'
			&& $option != 'com_alphacontent' && $option != 'com_events') {
		return;
	}

	require_once (JCOMMENTS_HELPERS . '/content.php');
	require_once (JCOMMENTS_LIBRARIES . '/joomlatune/language.tools.php');

	if (!isset($params) || $params == null) {
		$params = new mosParameters('');
	}

	$pvars = array_keys(get_object_vars($params->_params));
	if (!$published || $params->get('popup') || in_array('moduleclass_sfx', $pvars)) {
		JCommentsContentPluginHelper::processForeignTags($row, true);
		JCommentsContentPluginHelper::clear($row, true);
		return;
	}

	/*
	if ($option == 'com_frontpage') {
		$pluginParams = JCommentsPluginHelper::getParams('jcomments', 'content');
		if ((int) $pluginParams->get('show_frontpage', 1) == 0) {
			return;
		}
	}
	*/

	require_once (JCOMMENTS_BASE . '/jcomments.config.php');
	require_once (JCOMMENTS_BASE . '/jcomments.class.php');

	if ($task != 'view') {
		// replace other comment systems tags to JComments equivalents like {jcomments on}
		JCommentsContentPluginHelper::processForeignTags($row, false);

		// show link to comments only
		if ($row->access <= $my->gid) {
			$readmore_link = JCommentsObjectHelper::getLink($row->id, 'com_content');
			$readmore_register = 0;
		} else {
			$readmore_link = sefRelToAbs('index.php?option=com_registration&amp;task=register');
			$readmore_register = 1;
		}

		$tmpl = JCommentsFactory::getTemplate($row->id, 'com_content', false);
		$tmpl->load('tpl_links');

		$tmpl->addVar('tpl_links', 'comments_link_style', ($readmore_register ? -1 : 1));
		$tmpl->addVar('tpl_links', 'link-readmore', $readmore_link);
		$tmpl->addVar('tpl_links', 'content-item', $row);

		if (($params->get('readmore') == 0) || (@$row->readmore == 0)) {
			$tmpl->addVar('tpl_links', 'readmore_link_hidden', 1);
		} else if (@$row->readmore > 0) {
			$tmpl->addVar('tpl_links', 'readmore_link_hidden', 0);
		}

		$config = JCommentsFactory::getConfig();

		$commentsDisabled = false;

		if (!JCommentsContentPluginHelper::checkCategory($row->catid)) {
			$commentsDisabled = true;
		}
		if ($config->getInt('comments_off', 0) == 1) {
			$commentsDisabled = true;
		} else if ($config->getInt('comments_on', 0) == 1) {
			$commentsDisabled = false;
		}

		$tmpl->addVar('tpl_links', 'comments_link_hidden', intval($commentsDisabled));

		$count = 0;
		// do not query comments count if comments disabled and link hidden
		if (!$commentsDisabled) {
			require_once (JCOMMENTS_MODELS . '/jcomments.php');
			require_once (JCOMMENTS_LIBRARIES . '/joomlatune/language.tools.php');

			$acl = JCommentsFactory::getACL();

			$options = array();
			$options['object_id'] = (int) $row->id;
			$options['object_group'] = 'com_content';
			$options['published'] = $acl->canPublish() || $acl->canPublishForObject($row->id, 'com_content') ? null : 1;

			$count = JCommentsModel::getCommentsCount($options);
			$anchor = $count == 0 ? '#addcomments' : '#comments';
			$link_text = $count == 0 ? JText::_('LINK_ADD_COMMENT') : JText::plural('LINK_READ_COMMENTS', $count);

			$tmpl->addVar('tpl_links', 'link-comment', $readmore_link . $anchor);
			$tmpl->addVar('tpl_links', 'link-comment-text', $link_text);
			$tmpl->addVar('tpl_links', 'link-comments-class', 'comments-link');
			$tmpl->addVar('tpl_links', 'comments-count', $count);
		}

		if ($readmore_register == 1 && $count == 0) {
			$tmpl->addVar('tpl_links', 'comments_link_hidden', 1);
		}

		if ($readmore_register == 1) {
			$readmore_text = JText::_('LINK_REGISTER_TO_READ_MORE');
		} else {
			$readmore_text = JText::_('LINK_READ_MORE');
		}

		$tmpl->addVar('tpl_links', 'link-readmore-text', $readmore_text);
		$tmpl->addVar('tpl_links', 'link-readmore-title', $row->title);
		$tmpl->addVar('tpl_links', 'link-readmore-class', 'readmore-link');

		JCommentsContentPluginHelper::clear($row, true);

		$row->text .= $tmpl->renderTemplate('tpl_links');

		$GLOBALS['jcomments_params_readmore'] = $params->get('readmore');
		$GLOBALS['jcomments_row_readmore'] = $row->readmore;

		$params->set('readmore', 0);
		$row->readmore = 0;
	} else {
		JCommentsContentPluginHelper::processForeignTags($row, true);
		JCommentsContentPluginHelper::clear($row, true);
	}
	return;
}
?>