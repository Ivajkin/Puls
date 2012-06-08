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

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemJComments extends JPlugin
{
	function plgSystemJComments(&$subject, $config)
	{
		parent::__construct($subject, $config);

		if (!isset($this->params)) {
			$this->params = new JParameter('');
		}

		// small hack to allow CAPTCHA display even if any notice or warning occurred
		$option = JRequest::getCmd('option');
		$task = JRequest::getCmd('task');
		if ($option == 'com_jcomments' && $task == 'captcha') {
			@ob_start();
		}

		if (isset($_REQUEST['jtxf'])) {
			if ($this->params->get('disable_error_reporting', 0) == 1) {
				// turn off all error reporting for AJAX call
				@error_reporting(0);
			}
		}
	}

	function onAfterRender()
	{
		if ($this->params->get('clear_rss', 0) == 1) {
			$option = JRequest::getCmd('option');
			if ($option == 'com_content') {
				$document = JFactory::getDocument();
				if ($document->getType() == 'feed') {
					$buffer = JResponse::getBody();
					$buffer = preg_replace('#{jcomments\s+(off|on|lock)}#is', '', $buffer);
					JResponse::setBody($buffer);
				}
			}
		}

		if ((defined('JCOMMENTS_CSS') || defined('JCOMMENTS_JS')) && !defined('JCOMMENTS_SHOW')) {
			$app = JFactory::getApplication();

			if ($app->getName() == 'site') {
				$buffer = JResponse::getBody();

				$regexpJS = '#(\<script(\stype=\"text\/javascript\")? src="[^\"]*\/com_jcomments\/[^\>]*\>\<\/script\>[\s\r\n]*?)#ismU';
				$regexpCSS = '#(\<link rel="stylesheet" href="[^\"]*\/com_jcomments\/[^>]*>[\s\r\n]*?)#ismU';

				$jcommentsTestJS = '#(JCommentsEditor|new JComments)#ismU';
				$jcommentsTestCSS = '#(comment-link|jcomments-links)#ismU';

				$jsFound = preg_match($jcommentsTestJS, $buffer);
				$cssFound = preg_match($jcommentsTestCSS, $buffer);

				if (!$jsFound) {
					// remove JavaScript if JComments isn't loaded
					$buffer = preg_replace($regexpJS, '', $buffer);
				}

				if (!$cssFound && !$jsFound) {
					// remove CSS if JComments isn't loaded
					$buffer = preg_replace($regexpCSS, '', $buffer);
				}

				if ($buffer != '') {
					JResponse::setBody($buffer);
				}
			}
		}
		return true;
	}

	function onAfterRoute()
	{
		$legacyFile = JPATH_ROOT . '/components/com_jcomments/jcomments.legacy.php';

		if (!is_file($legacyFile)) {
			return;
		}

		include_once ($legacyFile);

		$mainframe = JFactory::getApplication('site');
		$mainframe->getRouter();
		$document = JFactory::getDocument();

		if ($document->getType() == 'html') {
			if ($mainframe->isAdmin()) {
				$document->addStyleSheet(JURI::base() . 'components/com_jcomments/assets/icon.css?v=2');

				$option = JAdministratorHelper::findOption();
				$task = JRequest::getCmd('task');
				$type = JRequest::getCmd('type', '', 'post');

				// remove comments if content item deleted from trash
				if ($option == 'com_trash' && $task == 'delete' && $type == 'content') {
					$cid = JRequest::getVar('cid', array(0), 'post', 'array');
					JArrayHelper::toInteger($cid, array(0));
					include_once (JPATH_ROOT . '/components/com_jcomments/jcomments.php');
					JCommentsModel::deleteComments($cid, 'com_content');
				}
			} else {
				$option = JRequest::getCmd('option');

				if ($option == 'com_content' || $option == 'com_alphacontent' || $option == 'com_multicategories') {
					include_once (JCOMMENTS_BASE . '/jcomments.class.php');
					include_once (JCOMMENTS_BASE . '/jcomments.config.php');
					include_once (JCOMMENTS_HELPERS . '/system.php');

					// include JComments CSS
					if ($this->params->get('disable_template_css', 0) == 0) {
						$document->addStyleSheet(JCommentsSystemPluginHelper::getCSS());
						$language = JFactory::getLanguage();
						if ($language->isRTL()) {
							$rtlCSS = JCommentsSystemPluginHelper::getCSS(true);
							if ($rtlCSS != '') {
								$document->addStyleSheet($rtlCSS);
							}
						}
					}

					if (!defined('JCOMMENTS_CSS')) {
						define('JCOMMENTS_CSS', 1);
					}

					// include JComments JavaScript library
					$document->addScript(JCommentsSystemPluginHelper::getCoreJS());
					if (!defined('JOOMLATUNE_AJAX_JS')) {
						$document->addScript(JCommentsSystemPluginHelper::getAjaxJS());
						define('JOOMLATUNE_AJAX_JS', 1);
					}

					if (!defined('JCOMMENTS_JS')) {
						define('JCOMMENTS_JS', 1);
					}
				}
			}
		}
	}


	function onJCommentsShow($object_id, $object_group, $object_title)
	{
		$coreFile = JPATH_ROOT . '/components/com_jcomments/jcomments.php';

		if (is_file($coreFile)) {
			include_once ($coreFile);
			echo JComments::show($object_id, $object_group, $object_title);
		}
	}

	function onJCommentsCount($object_id, $object_group)
	{
		$coreFile = JPATH_ROOT . '/components/com_jcomments/jcomments.php';

		if (is_file($coreFile)) {
			include_once ($coreFile);
			echo JComments::getCommentsCount($object_id, $object_group);
		}
	}
}

?>