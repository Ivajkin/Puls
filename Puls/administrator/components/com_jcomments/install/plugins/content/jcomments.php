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

defined('_JEXEC') or die;

include_once (JPATH_ROOT . '/components/com_jcomments/jcomments.legacy.php');

if (!defined('JCOMMENTS_JVERSION')) {
	return;
}

jimport('joomla.plugin.plugin');

class plgContentJComments extends JPlugin
{
	function plgContentJComments(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onPrepareContent(&$article, &$params, $limitstart = 0)
	{
		require_once (JCOMMENTS_HELPERS . '/content.php');

		// check whether plugin has been unpublished
		if (!JPluginHelper::isEnabled('content', 'jcomments')) {
			JCommentsContentPluginHelper::clear($article, true);
			return '';
		}

		$app = JFactory::getApplication('site');
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');

		if (!isset($article->id) || ($option != 'com_content' && $option != 'com_alphacontent' && $option != 'com_multicategories')) {
			return '';
		}

		if (!isset($params) || $params == null) {
			jimport('joomla.html.parameter');
			$params = new JParameter('');
		} else if (isset($params->_raw) && strpos($params->_raw, 'moduleclass_sfx') !== false) {
			return '';
		}

		if ($view == 'frontpage' || $view == 'featured') {
			if ($this->params->get('show_frontpage', 1) == 0) {
				return '';
			}
		}

		require_once (JCOMMENTS_BASE . '/jcomments.config.php');
		require_once (JCOMMENTS_BASE . '/jcomments.class.php');

		JCommentsContentPluginHelper::processForeignTags($article);

		$config = JCommentsFactory::getConfig();

		$categoryEnabled = JCommentsContentPluginHelper::checkCategory($article->catid);
		$commentsEnabled = JCommentsContentPluginHelper::isEnabled($article) || $categoryEnabled;
		$commentsDisabled = JCommentsContentPluginHelper::isDisabled($article) || !$commentsEnabled;
		$commentsLocked = JCommentsContentPluginHelper::isLocked($article);

		if ($article->state == -1 && $this->params->get('enable_for_archived', 0) == 0) {
			$commentsLocked = true;
		}

		$config->set('comments_on', intval($commentsEnabled));
		$config->set('comments_off', intval($commentsDisabled));
		$config->set('comments_locked', intval($commentsLocked));

		if ($view != 'article') {
			$user = JFactory::getUser();

			if (JCOMMENTS_JVERSION == '1.5') {
				$checkAccess = $article->access <= $user->get('aid', 0);
			} else {
				$authorised = JAccess::getAuthorisedViewLevels($user->get('id'));
				$checkAccess = in_array($article->access, $authorised);
			}

			if ($checkAccess) {
				require_once(JPATH_ROOT . '/components/com_content/helpers/route.php');
				if (JCOMMENTS_JVERSION == '1.5') {
					$readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
				} else {
					$readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
				}
				$readmore_register = 0;
			} else {
				if (JCOMMENTS_JVERSION == '1.5') {
					$readmore_link = JRoute::_('index.php?option=com_user&task=register');
				} else {
					$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));

					$menu = JFactory::getApplication()->getMenu();
					$active = $menu->getActive();
					$itemId = $active->id;
					$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
					$link = new JURI($link1);
					$link->setVar('return', base64_encode($returnURL));
					$readmore_link = $link;
				}
				$readmore_register = 1;
			}

			// load template for comments & readmore links
			$tmpl = JCommentsFactory::getTemplate($article->id, 'com_content', false);
			$tmpl->load('tpl_links');

			$tmpl->addVar('tpl_links', 'comments_link_style', ($readmore_register ? -1 : 1));
			$tmpl->addVar('tpl_links', 'content-item', $article);
			$tmpl->addVar('tpl_links', 'show_hits', intval($this->params->get('show_hits', 0) && $params->get('show_hits', 0)));

			$readmoreDisabled = false;

			if (($params->get('show_readmore') == 0) || (@$article->readmore == 0)) {
				$readmoreDisabled = true;
			} else if (@$article->readmore > 0) {
				$readmoreDisabled = false;
			}

			if ($this->params->get('readmore_link', 1) == 0) {
				$readmoreDisabled = true;
			}

			$tmpl->addVar('tpl_links', 'readmore_link_hidden', intval($readmoreDisabled));

			// don't fill any readmore variable if it disabled
			if (!$readmoreDisabled) {
				if ($readmore_register == 1) {
					$readmore_text = JText::_('LINK_REGISTER_TO_READ_MORE');
				} else if (isset($params) && $readmore = $params->get('readmore')) {
					$readmore_text = $readmore;
				} else {
					$readmore_text = JText::_('LINK_READ_MORE');
				}
				$tmpl->addVar('tpl_links', 'link-readmore', $readmore_link);
				$tmpl->addVar('tpl_links', 'link-readmore-text', $readmore_text);
				$tmpl->addVar('tpl_links', 'link-readmore-title', $article->title);
				$tmpl->addVar('tpl_links', 'link-readmore-class', $this->params->get('readmore_css_class', 'readmore-link'));
			}

			$commentsDisabled = false;

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

				$anchor = "";

				if ($this->params->get('comments_count', 1) != 0) {

					$acl = JCommentsFactory::getACL();

					$options = array();
					$options['object_id'] = (int) $article->id;
					$options['object_group'] = 'com_content';
					$options['published'] = $acl->canPublish() || $acl->canPublishForObject($article->id, 'com_content') ? null : 1;

					$count = JCommentsModel::getCommentsCount($options);

					$tmpl->addVar('tpl_links', 'comments-count', $count);
					$anchor = $count == 0 ? '#addcomment' : '#comments';

					if ($count == 0) {
						$link_text = JText::_('LINK_ADD_COMMENT');
					} else {
						if (JCOMMENTS_JVERSION == '1.5') {
							require_once (JCOMMENTS_LIBRARIES.'/joomlatune/language.tools.php');
							$lang = JFactory::getLanguage();
							$suffix = JoomlaTuneLanguageTools::getPluralSuffix($lang->getTag(), $count);
							$string = 'LINK_READ_COMMENTS';
							$key = $string . '_' . $suffix;
							if (!$lang->hasKey($key)) {
								$key = $string;
							}
							$link_text = JText::sprintf($key, $count);

						} else {
							$link_text = JText::plural('LINK_READ_COMMENTS', $count);
						}
					}
				} else {
					$link_text = JText::_('LINK_ADD_COMMENT');
				}

				$tmpl->addVar('tpl_links', 'link-comment', $readmore_link . $anchor);
				$tmpl->addVar('tpl_links', 'link-comment-text', $link_text);
				$tmpl->addVar('tpl_links', 'link-comments-class', $this->params->get('comments_css_class', 'comments-link'));
			}

			JCommentsContentPluginHelper::clear($article, true);

			// hide comments link if comments enabled but link disabled in plugin params
			if ((($this->params->get('comments_count', 1) == 0)
					|| ($count == 0 && $this->params->get('add_comments', 1) == 0)
					|| ($count == 0 && $readmore_register == 1))
					&& !$commentsDisabled) {
				$tmpl->addVar('tpl_links', 'comments_link_hidden', 1);
			}

			//links_position
			if ($this->params->get('links_position', 1) == 1) {
				$article->text .= $tmpl->renderTemplate('tpl_links');
			} else {
				$article->text = $tmpl->renderTemplate('tpl_links') . $article->text;
			}

			$tmpl->freeTemplate('tpl_links');

			if ($this->params->get('readmore_link', 1) == 1) {
				$article->readmore = 0;
				$article->readmore_link = '';
				$article->readmore_register = false;
			}
		} else {
			if ($this->params->get('show_comments_event') == 'onPrepareContent') {
				$isEnabled = ($config->getInt('comments_on', 0) == 1) && ($config->getInt('comments_off', 0) == 0);
				if ($isEnabled && $view == 'article') {
					require_once (JCOMMENTS_BASE . '/jcomments.php');

					$comments = JComments::show($article->id, 'com_content', $article->title);

					if (strpos($article->text, '{jcomments}') !== false) {
						$article->text = str_replace('{jcomments}', $comments, $article->text);
					} else {
						$article->text .= $comments;
					}
				}
			}
			JCommentsContentPluginHelper::clear($article, true);
		}
		return '';
	}

	function onAfterDisplayContent(&$article, &$params, $limitstart = 0)
	{
		if ($this->params->get('show_comments_event', 'onAfterDisplayContent') == 'onAfterDisplayContent') {
			require_once (JCOMMENTS_HELPERS . '/content.php');
			$view = JRequest::getCmd('view');

			// check whether plugin has been unpublished
			if (!JPluginHelper::isEnabled('content', 'jcomments')
					|| ($view != 'article')
					|| $params->get('intro_only')
					|| $params->get('popup')
					|| JRequest::getBool('fullview')
					|| JRequest::getVar('print')) {
				JCommentsContentPluginHelper::clear($article, true);
				return '';
			}

			require_once (JCOMMENTS_BASE . '/jcomments.php');

			$config = JCommentsFactory::getConfig();
			$isEnabled = ($config->getInt('comments_on', 0) == 1) && ($config->getInt('comments_off', 0) == 0);

			if ($isEnabled && $view == 'article') {
				JCommentsContentPluginHelper::clear($article, true);
				return JComments::show($article->id, 'com_content', $article->title);
			}
		}
		return '';
	}

	function onContentBeforeDisplay($context, &$article, &$params, $page = 0)
	{
		if ($context == 'com_content.article') {
			$view = JRequest::getCmd('view');
			if ($view == 'featured') {
				if ($this->params->get('show_frontpage', 1) == 0) {
					return;
				}
			}
			
			// do not display comments in modules
			$data = $params->toArray();
			if (isset($data['moduleclass_sfx'])) {
				return;
			}

			$originalText = isset($article->text) ? $article->text : '';
			$article->text = '';
			$this->onPrepareContent($article, $params, $page);

			if (isset($article->text)) {
				if (($view == 'article') && strpos($originalText, '{jcomments}') !== false) {
					$originalText = str_replace('{jcomments}', $article->text, $originalText);
				} else {
					$article->introtext = str_replace('{jcomments}', '', $article->introtext);

					if ($this->params->get('links_position', 1) == 1) {
						$article->introtext = $article->introtext . $article->text;
					} else {
						$article->introtext = $article->text . $article->introtext;
					}
				}
			}
			
			$article->text = $originalText;
			JCommentsContentPluginHelper::clear($article);
		}
	}

	function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0)
	{
		if ($context == 'com_content.article') {
			// do not display comments in modules
			$data = $params->toArray();
			if (isset($data['moduleclass_sfx'])) {
				return '';
			}

			return $this->onAfterDisplayContent($article, $params, $limitstart);
		}
		return '';
	}

	function onContentAfterDelete($context, $data)
	{
		// TODO: delete content item's comments if content item has been deleted
	}

	function onContentAfterSave($context, &$article, $isNew)
	{
		// Check we are handling the frontend edit form.
		if ($context == 'com_content.form') {
			require_once(JCOMMENTS_HELPERS . '/content.php');
			if (JCommentsContentPluginHelper::checkCategory($article->catid)) {
				require_once(JCOMMENTS_HELPERS . '/object.php');
				JCommentsObjectHelper::storeObjectInfo($article->id, 'com_content');
			}
		}
	}
}
?>