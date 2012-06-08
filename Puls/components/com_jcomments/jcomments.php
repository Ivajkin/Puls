<?php
/**
 * JComments - Joomla Comment System
 *
 * Frontend event handler
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

ob_start();

require_once (dirname(__FILE__).DS.'jcomments.legacy.php');

if (!defined('JCOMMENTS_ENCODING')) {
	DEFINE('JCOMMENTS_ENCODING', strtolower(preg_replace('/charset=/', '', _ISO)));
	if (JCOMMENTS_ENCODING == 'utf-8') {
		// pattern strings are treated as UTF-8
		DEFINE('JCOMMENTS_PCRE_UTF8', 'u');
	} else {
		DEFINE('JCOMMENTS_PCRE_UTF8', '');
	}
}

// regular expression for links
DEFINE('_JC_REGEXP_LINK', '#(^|\s|\>|\()((http://|https://|news://|ftp://|www.)\w+[^\s\<\>\"\']+)#i' . JCOMMENTS_PCRE_UTF8);
DEFINE('_JC_REGEXP_EMAIL', '#([\w\.\-]+)@(\w+[\w\.\-]*\.\w{2,4})#i' . JCOMMENTS_PCRE_UTF8);
DEFINE('_JC_REGEXP_EMAIL2', '#^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i' . JCOMMENTS_PCRE_UTF8);

require_once (JCOMMENTS_BASE.'/jcomments.config.php');
require_once (JCOMMENTS_BASE.'/jcomments.class.php');
require_once (JCOMMENTS_MODELS.'/jcomments.php');
require_once (JCOMMENTS_HELPERS.'/object.php');
require_once (JCOMMENTS_HELPERS.'/event.php');
ob_end_clean();

$jc_task = JCommentsInput::getVar('task', '');

switch(trim($jc_task)) {
	case 'captcha':
		$config = JCommentsFactory::getConfig();
		$captchaEngine = $config->get('captcha_engine', 'kcaptcha');
		if ($captchaEngine == 'kcaptcha' || $config->getInt('enable_mambots') == 0) {
			require_once (JCOMMENTS_BASE.'/jcomments.captcha.php');
			JCommentsCaptcha::image();
		} else {
			if ($config->getInt('enable_mambots') == 1) {
				JCommentsEvent::trigger('onJCommentsCaptchaImage');
			}
		}
		break;
	case 'rss':
		require_once (JCOMMENTS_BASE.'/jcomments.rss.php');
		JCommentsRSS::showObjectComments();
		break;
	case 'rss_full':
		require_once (JCOMMENTS_BASE.'/jcomments.rss.php');
		JCommentsRSS::showAllComments();
		break;
	case 'rss_user':
		require_once (JCOMMENTS_BASE.'/jcomments.rss.php');
		JCommentsRSS::showUserComments();
		break;
	case 'unsubscribe':
		JComments::unsubscribe();
		break;
	case 'cmd':
		JComments::executeCmd();
		break;
	case 'go2object':
		JComments::redirectToObject();
		break;
	default:
		$jc_option = JCommentsInput::getVar('option', '');
		$jc_ajax = JCommentsInput::getVar('jtxf', '');
		$app = JCommentsFactory::getApplication('site');

		if ($jc_option == 'com_jcomments' && $jc_ajax == '' && !$app->isAdmin()) {

			$_Itemid = (int) JCommentsInput::getVar('Itemid');
			$_tmpl = JCommentsInput::getVar('tmpl');

			if ($_Itemid !== 0 && $_tmpl !== 'component') {
				if (JCOMMENTS_JVERSION == '1.5') {
					$params = JComponentHelper::getParams('com_jcomments');
				} elseif (JCOMMENTS_JVERSION == '1.7') {
					$params = $app->getParams();
				} else {
					$menu = $app->get('menu');
					if ($menu != null) {
						$params = new mosParameters($menu->params);
					} else {
						$params = new mosParameters('');
					}
				}

				$object_group = $params->get('object_group');
				$object_id = (int) $params->get('object_id', 0);

				if ($object_id != 0 && $object_group != '') {

					if ($params->get('language_suffix') != '') {
						JComments::loadAlternateLanguage($params->get('language_suffix'));
					}

					$keywords = $params->get('keywords');
					$description = $params->get('description');
					$pageTitle = $params->get('page_title');

					$document = JCommentsFactory::getDocument();
					
					if ($pageTitle != '') {
						$document->setTitle($pageTitle);
					}

					if ($keywords) {
						$document->setMetaData('keywords', $keywords);
					}

					if ($description) {
						$document->setDescription($description);
					}

					echo JComments::show($object_id, $object_group);
				} else {
					JCommentsRedirect($app->getCfg('live_site').'/index.php');
				}
			} else {
				JCommentsRedirect($app->getCfg('live_site').'/index.php');
			}
		}
		break;
}

if (isset($_REQUEST['jtxf'])) {
	require_once (JCOMMENTS_BASE.'/jcomments.ajax.php');

	JComments::loadAlternateLanguage();

	$jtx = new JoomlaTuneAjax();
	$jtx->setCharEncoding(JCOMMENTS_ENCODING);
	$jtx->registerFunction(array('JCommentsAddComment', 'JCommentsAJAX', 'addComment'));
	$jtx->registerFunction(array('JCommentsDeleteComment', 'JCommentsAJAX', 'deleteComment'));
	$jtx->registerFunction(array('JCommentsEditComment', 'JCommentsAJAX', 'editComment'));
	$jtx->registerFunction(array('JCommentsCancelComment', 'JCommentsAJAX', 'cancelComment'));
	$jtx->registerFunction(array('JCommentsSaveComment', 'JCommentsAJAX', 'saveComment'));
	$jtx->registerFunction(array('JCommentsPublishComment', 'JCommentsAJAX', 'publishComment'));
	$jtx->registerFunction(array('JCommentsQuoteComment', 'JCommentsAJAX', 'quoteComment'));
	$jtx->registerFunction(array('JCommentsShowPage', 'JCommentsAJAX', 'showPage'));
	$jtx->registerFunction(array('JCommentsShowComment', 'JCommentsAJAX', 'showComment'));
	$jtx->registerFunction(array('JCommentsJump2email', 'JCommentsAJAX', 'jump2email'));
	$jtx->registerFunction(array('JCommentsShowForm', 'JCommentsAJAX', 'showForm'));
	$jtx->registerFunction(array('JCommentsVoteComment', 'JCommentsAJAX', 'voteComment'));
	$jtx->registerFunction(array('JCommentsShowReportForm', 'JCommentsAJAX', 'showReportForm'));
	$jtx->registerFunction(array('JCommentsReportComment', 'JCommentsAJAX', 'reportComment'));
	$jtx->registerFunction(array('JCommentsSubscribe', 'JCommentsAJAX', 'subscribeUser'));
	$jtx->registerFunction(array('JCommentsUnsubscribe', 'JCommentsAJAX', 'unsubscribeUser'));
	$jtx->registerFunction(array('JCommentsBanIP', 'JCommentsAJAX', 'BanIP'));
	$jtx->registerFunction(array('JCommentsRefreshObjects', 'JCommentsAJAX', 'RefreshObjects'));
	$jtx->processRequests();
}

class JComments
{
	/*
	 * The main function that displays comments list & form (if needed)
	 *
	 * @return string
	 */
	public static function show( $object_id, $object_group = 'com_content', $object_title = '' )
	{
		// only one copy of JComments per page is allowed
		if (defined('JCOMMENTS_SHOW')) {
			return '';
		}

		$app = JCommentsFactory::getApplication('site');
		$object_group = JCommentsSecurity::clearObjectGroup($object_group);

		if ($object_group == '' || !isset($object_id) || $object_id == '') {
			return '';
		}

		$object_id = (int) $object_id;
		$object_title = trim($object_title);

		$acl = JCommentsFactory::getACL();
		$config = JCommentsFactory::getConfig();
		$document = JCommentsFactory::getDocument();

		$tmpl = JCommentsFactory::getTemplate($object_id, $object_group);
		$tmpl->load('tpl_index');

		if (!defined('JCOMMENTS_CSS')) {
			include_once (JCOMMENTS_HELPERS.DS.'system.php');
			if ($app->isAdmin()) {
				$tmpl->addVar('tpl_index', 'comments-css', 1);
			} else {
				$document->addStyleSheet(JCommentsSystemPluginHelper::getCSS());
				if (JCOMMENTS_JVERSION != '1.0') {
					$language = JFactory::getLanguage();
					if ($language->isRTL()) {
						$rtlCSS = JCommentsSystemPluginHelper::getCSS(true);
						if ($rtlCSS != '') {
							$document->addStyleSheet($rtlCSS);
						}
					}
				}
			}
		}

		if (!defined('JCOMMENTS_JS')) {
			include_once (JCOMMENTS_HELPERS.DS.'system.php');
			$document->addScript(JCommentsSystemPluginHelper::getCoreJS());
			define('JCOMMENTS_JS', 1);

			if (!defined('JOOMLATUNE_AJAX_JS')) {
				$document->addScript(JCommentsSystemPluginHelper::getAjaxJS());
				define('JOOMLATUNE_AJAX_JS', 1);
			}
		}

		$commentsCount = JComments::getCommentsCount($object_id, $object_group);
		$commentsPerObject = $config->getInt('max_comments_per_object');
		$showForm = ($config->getInt('form_show') == 1) || ($config->getInt('form_show') == 2 && $commentsCount == 0);

		if ($commentsPerObject != 0 && $commentsCount >= $commentsPerObject) {
			$config->set('comments_locked', 1);
		}

		if ($config->getInt('comments_locked', 0) == 1) {
			$config->set('enable_rss', 0);
			$tmpl->addVar('tpl_index', 'comments-form-locked', 1);
			$acl->setCommentsLocked(true);
		}

		$tmpl->addVar('tpl_index', 'comments-form-captcha', $acl->check('enable_captcha'));
		$tmpl->addVar('tpl_index', 'comments-form-link', $showForm ? 0 : 1);

		if ($config->getInt('enable_rss') == 1) {
			if ($document->getType() == 'html') {
				$link = JCommentsFactory::getLink('rss', $object_id, $object_group);
				$title = (JCOMMENTS_JVERSION == '1.0') ? htmlspecialchars($object_title) : htmlspecialchars($object_title, ENT_COMPAT, 'UTF-8');
				$attribs = array('type' => 'application/rss+xml', 'title' => $title);
				$document->addHeadLink($link, 'alternate', 'rel', $attribs);
			}
		}

		$cacheEnabled = intval($app->getCfg('caching')) == 1;

		if ($cacheEnabled == 0) {
			$jrecache = $app->getCfg('absolute_path').DS.'components'.DS.'com_jrecache'.DS.'jrecache.config.php';

			if (is_file($jrecache)) {
				$cfg = new _JRECache_Config();
				$cacheEnabled = $cacheEnabled && $cfg->enable_cache;
			}
		}

		$load_cached_comments = intval($config->getInt('load_cached_comments', 0) && $commentsCount > 0);

		if ($cacheEnabled) {
			$tmpl->addVar('tpl_index', 'comments-anticache', 1);
		}

		if (!$cacheEnabled || $load_cached_comments === 1) {
			if ($config->get('template_view') == 'tree') {
				$tmpl->addVar('tpl_index', 'comments-list', $commentsCount > 0 ? JComments::getCommentsTree($object_id, $object_group) : '');
			} else {
				$tmpl->addVar('tpl_index', 'comments-list', $commentsCount > 0 ? JComments::getCommentsList($object_id, $object_group) : '');
			}
		}

		$needScrollToComment = ($cacheEnabled || ($config->getInt('comments_per_page') > 0)) && $commentsCount > 0;
		$tmpl->addVar('tpl_index', 'comments-gotocomment', (int) $needScrollToComment);
		$tmpl->addVar('tpl_index', 'comments-form', JComments::getCommentsForm($object_id, $object_group, $showForm));
		$tmpl->addVar('tpl_index', 'comments-form-position', $config->getInt('form_position'));

		$result = $tmpl->renderTemplate('tpl_index');
		$tmpl->freeAllTemplates();

		define('JCOMMENTS_SHOW', 1);

		return $result;
	}

	public static function loadAlternateLanguage($languageSuffix = '')
	{
		if ($languageSuffix == '') {
			$languageSuffix = JCommentsInput::getVar('lsfx', '');
		}
		if ($languageSuffix != '') {
			$config = JCommentsFactory::getConfig();
			$config->set('lsfx', $languageSuffix);

			$language = JFactory::getLanguage();
			$language->load('com_jcomments.' . $languageSuffix, JPATH_SITE);
		}
	}

	public static function getCommentsForm( $object_id, $object_group, $showForm = true )
	{
		$object_id = (int) $object_id;
		$object_group = trim($object_group);

		$tmpl = JCommentsFactory::getTemplate($object_id, $object_group);
		$tmpl->load('tpl_form');

		$user = JCommentsFactory::getUser();
		$acl = JCommentsFactory::getACL();
		$config = JCommentsFactory::getConfig();

		if ($acl->canComment()) {
			if ($config->getInt('comments_locked') == 1 ) {
				$message = $config->get('message_locked');

				if ($message != '') {
					$message = stripslashes($message);
					if ($message == strip_tags($message)) {
						$message = nl2br($message);
					}
				} else {
					$message = JText::_('ERROR_CANT_COMMENT');
				}

				$tmpl->addVar('tpl_form', 'comments-form-message', 1);
				$tmpl->addVar('tpl_form', 'comments-form-message-header', JText::_('FORM_HEADER'));
				$tmpl->addVar('tpl_form', 'comments-form-message-text', $message);
				$result = $tmpl->renderTemplate('tpl_form');

				return $result;
			}

			if ($acl->check('enable_captcha') == 1) {
				$captchaEngine = $config->get('captcha_engine', 'kcaptcha');
				if ($captchaEngine != 'kcaptcha') {
					JCommentsEvent::trigger('onJCommentsCaptchaJavaScript');
				}
			}

			if (!$showForm) {
				$tmpl->addVar('tpl_form', 'comments-form-link', 1);
				$result = $tmpl->renderTemplate('tpl_form');

				return $result;
			} else {
				if ($config->getInt('form_show') != 1) {
					$tmpl->addVar('tpl_form', 'comments-form-ajax', 1);
				}
			}

			if ($config->getInt('enable_mambots') == 1) {
				$htmlBeforeForm = JCommentsEvent::trigger('onJCommentsFormBeforeDisplay');
				$htmlAfterForm = JCommentsEvent::trigger('onJCommentsFormAfterDisplay');

				$tmpl->addVar('tpl_form', 'comments-form-html-before', implode("\n", $htmlBeforeForm));
				$tmpl->addVar('tpl_form', 'comments-form-html-after', implode("\n", $htmlAfterForm));
			}

			$policy = $config->get('message_policy_post');
			if (($policy != '') && ($acl->check('show_policy'))) {
				$policy = stripslashes($policy);
				if ($policy == strip_tags($policy)) {
					$policy = nl2br($policy);
				}
				$tmpl->addVar('tpl_form', 'comments-form-policy', 1);
				$tmpl->addVar('tpl_form', 'comments-policy', $policy);
			}

			if ($user->id) {
				$currentUser = JCommentsFactory::getUser($user->id);
				$user->name = $currentUser->name;
				unset($currentUser);
			}

			$tmpl->addObject('tpl_form', 'user', $user);

			if ($config->getInt('enable_smiles') == 1 && is_array($config->get('smiles'))) {
				$tmpl->addVar('tpl_form', 'comment-form-smiles', $config->get('smiles'));
			}

			$bbcode = JCommentsFactory::getBBCode();

			if ($bbcode->enabled()) {
				$tmpl->addVar('tpl_form', 'comments-form-bbcode', 1);
				foreach ($bbcode->getCodes() as $code) {
					$tmpl->addVar('tpl_form', 'comments-form-bbcode-' . $code, $bbcode->canUse($code));
				}
			}

			if ($config->getInt('enable_custom_bbcode')) {
				$customBBCode = JCommentsFactory::getCustomBBCode();
				if ($customBBCode->enabled()) {
					$tmpl->addVar('tpl_form', 'comments-form-custombbcodes', $customBBCode->codes);
				}
			}

			$username_maxlength = $config->getInt('username_maxlength');
			if ( $username_maxlength <= 0 || $username_maxlength > 255 ) {
				$username_maxlength = 255;
			}
			$tmpl->addVar('tpl_form', 'comment-name-maxlength', $username_maxlength);

			if (($config->getInt('show_commentlength') == 1)
			&& ($acl->check('enable_comment_length_check'))) {
				$tmpl->addVar('tpl_form', 'comments-form-showlength-counter', 1);
				$tmpl->addVar('tpl_form', 'comment-maxlength', $config->getInt('comment_maxlength'));
			} else {
				$tmpl->addVar('tpl_form', 'comment-maxlength', 0);
			}

			if ($acl->check('enable_captcha') == 1) {
				$tmpl->addVar('tpl_form', 'comments-form-captcha', 1);

				$captchaEngine = $config->get('captcha_engine', 'kcaptcha');
				if ($captchaEngine == 'kcaptcha') {
					// TODO
				} else {
					if ($config->getInt('enable_mambots') == 1) {
						$captchaHTML = JCommentsEvent::trigger('onJCommentsCaptchaDisplay');
						$tmpl->addVar('tpl_form', 'comments-form-captcha-html', implode("\n", $captchaHTML));
					}
				}
			}

			$canSubscribe = $acl->check('enable_subscribe');

			if ($user->id && $canSubscribe) {
				require_once (JCOMMENTS_BASE.'/jcomments.subscription.php');
				$manager = JCommentsSubscriptionManager::getInstance();
				$canSubscribe = $canSubscribe && (!$manager->isSubscribed($object_id, $object_group, $user->id));
			}

			$tmpl->addVar('tpl_form', 'comments-form-subscribe', (int) $canSubscribe);
			$tmpl->addVar('tpl_form', 'comments-form-email-required', 0);

			switch ($config->getInt('author_name')) {
				case 2:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-user-name-required', 1);
						$tmpl->addVar('tpl_form', 'comments-form-user-name', 1);
					} else {
						$tmpl->addVar('tpl_form', 'comments-form-user-name', 0);
					}
					break;
				case 1:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-user-name', 1);
						$tmpl->addVar('tpl_form', 'comments-form-user-name-required', 0);
					} else {
						$tmpl->addVar('tpl_form', 'comments-form-user-name', 0);
					}
					break;
				case 0:
				default:
					$tmpl->addVar('tpl_form', 'comments-form-user-name', 0);
					break;
			}


			switch ($config->getInt('author_email')) {
				case 2:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-email-required', 1);
						$tmpl->addVar('tpl_form', 'comments-form-user-email', 1);
					} else {
						$tmpl->addVar('tpl_form', 'comments-form-user-email', 0);
					}
					break;
				case 1:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-user-email', 1);
					} else {
						$tmpl->addVar('tpl_form', 'comments-form-user-email', 0);
					}
					break;
				case 0:
				default:
					$tmpl->addVar('tpl_form', 'comments-form-user-email', 0);

					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-subscribe', 0);
					}
					break;
			}

			$tmpl->addVar('tpl_form', 'comments-form-homepage-required', 0);

			switch($config->getInt('author_homepage')) {
				case 5:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-homepage-required', 0);
						$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 1);
					} else {
						$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 0);
					}
					break;
				case 4:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-homepage-required', 1);
						$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 1);
					} else {
						$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 0);
					}
					break;
				case 3:
					$tmpl->addVar('tpl_form', 'comments-form-homepage-required', 1);
					$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 1);
					break;
				case 2:
					if (!$user->id) {
						$tmpl->addVar('tpl_form', 'comments-form-homepage-required', 1);
					}
					$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 1);
					break;
				case 1:
					$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 1);
					break;
				case 0:
				default:
					$tmpl->addVar('tpl_form', 'comments-form-user-homepage', 0);
					break;
			}

			$tmpl->addVar('tpl_form', 'comments-form-title-required', 0);

			switch($config->getInt('comment_title')) {
				case 3:
					$tmpl->addVar('tpl_form', 'comments-form-title-required', 1);
					$tmpl->addVar('tpl_form', 'comments-form-title', 1);
					break;
				case 1:
					$tmpl->addVar('tpl_form', 'comments-form-title', 1);
					break;
				case 0:
				default:
					$tmpl->addVar('tpl_form', 'comments-form-title', 0);
					break;
			}

			$result = $tmpl->renderTemplate('tpl_form');

			// support old-style templates
			$result = str_replace('name="captcha-refid"', 'name="captcha_refid"', $result);

			if ($user->id) {
				$result = str_replace('</form>', '<div><input type="hidden" name="userid" value="'.$user->id.'" /></div></form>', $result);
			}

			return $result;
		} else {
			$message = $acl->getUserBlocked() ? $config->get('message_banned') : $config->get('message_policy_whocancomment');
			if ($message != '') {
				$header = JText::_('FORM_HEADER');
				$message = stripslashes($message);
				if ($message == strip_tags($message)) {
					$message = nl2br($message);
				}
			} else {
				$header = '';
				$message = '';
			}

			$tmpl->addVar('tpl_form', 'comments-form-message', 1);
			$tmpl->addVar('tpl_form', 'comments-form-message-header', $header);
			$tmpl->addVar('tpl_form', 'comments-form-message-text', $message);

			return $tmpl->renderTemplate('tpl_form');
		}
	}

	public static function getCommentsReportForm( $id, $object_id, $object_group )
	{
		$id = (int) $id;

		$user = JCommentsFactory::getUser();
		$tmpl = JCommentsFactory::getTemplate($object_id, $object_group);
		$tmpl->load('tpl_report_form');
		$tmpl->addVar('tpl_report_form', 'comment-id', $id);
		$tmpl->addVar('tpl_report_form', 'isGuest', $user->id ? 0 : 1);
		$result = $tmpl->renderTemplate('tpl_report_form');
		return $result;
	}

	public static function getCommentsList( $object_id, $object_group = 'com_content', $page = 0 )
	{
		$object_id = (int) $object_id;
		$object_group = trim( $object_group );

		$user = JCommentsFactory::getUser();
		$acl = JCommentsFactory::getACL();
		$config = JCommentsFactory::getConfig();

		$comments_per_page = $config->getInt('comments_per_page');

		$limitstart = 0;
		$total = JComments::getCommentsCount($object_id, $object_group);

		if ($acl->canComment() == 0 && $total == 0) {
			return '';
		}

		if ($total > 0) {
			$options = array();
			$options['object_id'] = $object_id;
			$options['object_group'] = $object_group;
			$options['published'] = $acl->canPublish() || $acl->canPublishForObject($object_id, $object_group) ? null : 1;
			$options['votes'] = $config->getInt('enable_voting');

			if ($comments_per_page > 0) {
				$page = (int) $page;

				require_once (JCOMMENTS_HELPERS.DS.'pagination.php');
				$pagination = new JCommentsPagination($object_id, $object_group);
				$pagination->setCurrentPage($page);

				$total_pages = $pagination->getTotalPages();
				$this_page = $pagination->getCurrentPage();
				$limitstart = $pagination->getLimitStart();
				$comments_per_page = $pagination->getCommentsPerPage();

				$options['limit'] = $comments_per_page;
				$options['limitStart'] = $limitstart;
			}

			$rows = JCommentsModel::getCommentsList($options);
		} else {
			$rows = array();
		}

		$tmpl = JCommentsFactory::getTemplate($object_id, $object_group);
		$tmpl->load('tpl_list');
		$tmpl->load('tpl_comment');

		if (count($rows)) {

			$isLocked = ($config->getInt('comments_locked', 0) == 1);

			$tmpl->addVar( 'tpl_list', 'comments-refresh', intval(!$isLocked));
			$tmpl->addVar( 'tpl_list', 'comments-rss', intval($config->getInt('enable_rss') && !$isLocked));
			$tmpl->addVar( 'tpl_list', 'comments-can-subscribe', intval($user->id && $acl->check('enable_subscribe') && !$isLocked));
			$tmpl->addVar( 'tpl_list', 'comments-count', count($rows));

			if ($user->id && $acl->check('enable_subscribe')) {
				require_once (JCOMMENTS_BASE.DS.'jcomments.subscription.php');
				$manager = JCommentsSubscriptionManager::getInstance();
				$isSubscribed = $manager->isSubscribed($object_id, $object_group, $user->id);
				$tmpl->addVar( 'tpl_list', 'comments-user-subscribed', $isSubscribed);
			}

			if ($config->get('comments_order') == 'DESC') {
				if ($comments_per_page > 0) {
					$i = $total - ($comments_per_page * ($page > 0 ? $page - 1 : 0));
				} else {
					$i = count($rows);
				}
			} else {
				$i = $limitstart + 1;
			}

			JCommentsEvent::trigger('onJCommentsCommentsPrepare', array(&$rows));

			if ($acl->check('enable_gravatar')) {
				JCommentsEvent::trigger('onPrepareAvatars', array(&$rows));
			}

			$items = array();

			foreach ($rows as $row) {
				// run autocensor, replace quotes, smiles and other pre-view processing
				JComments::prepareComment($row);

				// setup toolbar
				if (!$acl->canModerate($row)) {
					$tmpl->addVar('tpl_comment', 'comments-panel-visible', 0);
				} else {
					$tmpl->addVar('tpl_comment', 'comments-panel-visible', 1);
					$tmpl->addVar('tpl_comment', 'button-edit', $acl->canEdit($row));
					$tmpl->addVar('tpl_comment', 'button-delete', $acl->canDelete($row));
					$tmpl->addVar('tpl_comment', 'button-publish', $acl->canPublish($row));
					$tmpl->addVar('tpl_comment', 'button-ip', $acl->canViewIP($row));
					$tmpl->addVar('tpl_comment', 'button-ban', $acl->canBan($row));
				}

				$tmpl->addVar('tpl_comment', 'comment-show-vote', $config->getInt('enable_voting'));
				$tmpl->addVar('tpl_comment', 'comment-show-email', $acl->canViewEmail($row));
				$tmpl->addVar('tpl_comment', 'comment-show-homepage', $acl->canViewHomepage($row));
				$tmpl->addVar('tpl_comment', 'comment-show-title', $config->getInt('comment_title'));
				$tmpl->addVar('tpl_comment', 'button-vote', $acl->canVote($row));
				$tmpl->addVar('tpl_comment', 'button-quote', $acl->canQuote($row));
				$tmpl->addVar('tpl_comment', 'button-reply', $acl->canReply($row));
				$tmpl->addVar('tpl_comment', 'button-report', $acl->canReport($row));
				$tmpl->addVar('tpl_comment', 'avatar', $acl->check('enable_gravatar') && !$row->deleted);

				$tmpl->addObject('tpl_comment', 'comment', $row);

				if (isset($row->_number)) {
					$tmpl->addVar('tpl_comment', 'comment-number', $row->_number);
				} else {
					$tmpl->addVar('tpl_comment', 'comment-number', $i);

					if ($config->get('comments_order') == 'DESC') {
						$i--;
					} else {
						$i++;
					}
				}

				$items[$row->id] = $tmpl->renderTemplate('tpl_comment');
			}

			$tmpl->addObject('tpl_list', 'comments-items', $items);

			// build page navigation
			if (($comments_per_page > 0) && ($total_pages > 1)) {
				$tmpl->addVar('tpl_list', 'comments-nav-first', 1);
				$tmpl->addVar('tpl_list', 'comments-nav-total', $total_pages);
				$tmpl->addVar('tpl_list', 'comments-nav-active', $this_page);

				$pagination = $config->get('comments_pagination');

				// show top pagination
				if (($pagination == 'both') || ($pagination == 'top')) {
					$tmpl->addVar('tpl_list', 'comments-nav-top', 1);
				}

				// show bottom pagination
				if (($pagination == 'both') || ($pagination == 'bottom')) {
					$tmpl->addVar('tpl_list', 'comments-nav-bottom', 1);
				}
			}
			unset($rows);
		}
		return $tmpl->renderTemplate('tpl_list');
	}

	public static function getCommentsTree( $object_id, $object_group = 'com_content', $page = 0 )
	{
		$object_id = (int) $object_id;
		$object_group = trim($object_group);

		$user = JCommentsFactory::getUser();
		$acl = JCommentsFactory::getACL();
		$config = JCommentsFactory::getConfig();

		$total = JComments::getCommentsCount($object_id, $object_group);

		if ($acl->canComment() == 0 && $total == 0) {
			return '';
		}

		if ($total > 0) {
			$options = array();
			$options['object_id'] = $object_id;
			$options['object_group'] = $object_group;
			$options['published'] = $acl->canPublish() || $acl->canPublishForObject($object_id, $object_group) ? null : 1;
			$options['votes'] = $config->getInt('enable_voting');

			$rows = JCommentsModel::getCommentsList($options);
		} else {
			$rows = array();
		}

		$tmpl = JCommentsFactory::getTemplate($object_id, $object_group);
		$tmpl->load('tpl_tree');
		$tmpl->load('tpl_comment');

		if (count($rows)) {

			$isLocked = ($config->getInt('comments_locked', 0) == 1);

			$tmpl->addVar( 'tpl_tree', 'comments-refresh', intval(!$isLocked));
			$tmpl->addVar( 'tpl_tree', 'comments-rss', intval($config->getInt('enable_rss') && !$isLocked));
			$tmpl->addVar( 'tpl_tree', 'comments-can-subscribe', intval($user->id && $acl->check('enable_subscribe') && !$isLocked));
			$tmpl->addVar( 'tpl_tree', 'comments-count', count($rows));

			if ($user->id && $acl->check('enable_subscribe')) {
				require_once (JCOMMENTS_BASE.DS.'jcomments.subscription.php');
				$manager = JCommentsSubscriptionManager::getInstance();
				$isSubscribed = $manager->isSubscribed($object_id, $object_group, $user->id);
				$tmpl->addVar('tpl_tree', 'comments-user-subscribed', $isSubscribed);
			}

			$i = 1;

			JCommentsEvent::trigger('onJCommentsCommentsPrepare', array(&$rows));

			if ($acl->check('enable_gravatar')) {
				JCommentsEvent::trigger('onPrepareAvatars', array(&$rows));
			}

			require_once (JCOMMENTS_LIBRARIES.DS.'joomlatune'.DS.'tree.php');

			$tree = new JoomlaTuneTree($rows);
			$items = $tree->get();

			foreach ($rows as $row) {
				// run autocensor, replace quotes, smiles and other pre-view processing
				JComments::prepareComment($row);

				// setup toolbar
				if (!$acl->canModerate($row)) {
					$tmpl->addVar('tpl_comment', 'comments-panel-visible', 0);
				} else {
					$tmpl->addVar('tpl_comment', 'comments-panel-visible', 1);
					$tmpl->addVar('tpl_comment', 'button-edit', $acl->canEdit($row));
					$tmpl->addVar('tpl_comment', 'button-delete', $acl->canDelete($row));
					$tmpl->addVar('tpl_comment', 'button-publish', $acl->canPublish($row));
					$tmpl->addVar('tpl_comment', 'button-ip', $acl->canViewIP($row));
					$tmpl->addVar('tpl_comment', 'button-ban', $acl->canBan($row));
				}

				$tmpl->addVar('tpl_comment', 'comment-show-vote', $config->getInt('enable_voting'));
				$tmpl->addVar('tpl_comment', 'comment-show-email', $acl->canViewEmail($row));
				$tmpl->addVar('tpl_comment', 'comment-show-homepage', $acl->canViewHomepage($row));
				$tmpl->addVar('tpl_comment', 'comment-show-title', $config->getInt('comment_title'));
				$tmpl->addVar('tpl_comment', 'button-vote', $acl->canVote($row));
				$tmpl->addVar('tpl_comment', 'button-quote', $acl->canQuote($row));
				$tmpl->addVar('tpl_comment', 'button-reply', $acl->canReply($row));
				$tmpl->addVar('tpl_comment', 'button-report', $acl->canReport($row));
				$tmpl->addVar('tpl_comment', 'avatar', $acl->check('enable_gravatar') && !$row->deleted);

				if (isset($items[$row->id])) {
					$tmpl->addVar('tpl_comment', 'comment-number', '');
					$tmpl->addObject('tpl_comment', 'comment', $row);
					$items[$row->id]->html = $tmpl->renderTemplate('tpl_comment');
					$i++;
				}
			}

			$tmpl->addObject('tpl_tree', 'comments-items', $items);

			unset($rows);
		}
		return $tmpl->renderTemplate( 'tpl_tree' );
	}

	public static function getCommentItem(&$comment)
	{
		$acl = JCommentsFactory::getACL();
		$config = JCommentsFactory::getConfig();

		if ($acl->check('enable_gravatar')) {
			JCommentsEvent::trigger('onPrepareAvatar', array(&$comment));
		}

		// run autocensor, replace quotes, smiles and other pre-view processing
		JComments::prepareComment($comment);

		$tmpl = JCommentsFactory::getTemplate($comment->object_id, $comment->object_group);
		$tmpl->load('tpl_comment');

		// setup toolbar
		if (!$acl->canModerate($comment)) {
			$tmpl->addVar('tpl_comment', 'comments-panel-visible', 'visibility', 0);
		} else {
			$tmpl->addVar('tpl_comment', 'comments-panel-visible', 1);
			$tmpl->addVar('tpl_comment', 'button-edit', $acl->canEdit($comment));
			$tmpl->addVar('tpl_comment', 'button-delete', $acl->canDelete($comment));
			$tmpl->addVar('tpl_comment', 'button-publish', $acl->canPublish($comment));
			$tmpl->addVar('tpl_comment', 'button-ip', $acl->canViewIP($comment));
			$tmpl->addVar('tpl_comment', 'button-ban', $acl->canBan($comment));
			$tmpl->addVar('tpl_comment', 'comment-show-email', $acl->canViewEmail());
			$tmpl->addVar('tpl_comment', 'comment-show-homepage', $acl->canViewHomepage());
		}

		$tmpl->addVar('tpl_comment', 'comment-show-vote', $config->getInt('enable_voting'));
		$tmpl->addVar('tpl_comment', 'comment-show-email', $acl->canViewEmail($comment));
		$tmpl->addVar('tpl_comment', 'comment-show-homepage', $acl->canViewHomepage($comment));
		$tmpl->addVar('tpl_comment', 'comment-show-title', $config->getInt('comment_title'));
		$tmpl->addVar('tpl_comment', 'button-vote', $acl->canVote($comment));
		$tmpl->addVar('tpl_comment', 'button-quote', $acl->canQuote($comment));
		$tmpl->addVar('tpl_comment', 'button-reply', $acl->canReply($comment));
		$tmpl->addVar('tpl_comment', 'button-report', $acl->canReport($comment));
		$tmpl->addVar('tpl_comment', 'comment-number', '');
		$tmpl->addVar('tpl_comment', 'avatar', $acl->check('enable_gravatar') && !$comment->deleted);

		$tmpl->addObject('tpl_comment', 'comment', $comment);

		return $tmpl->renderTemplate('tpl_comment');
	}

	public static function getCommentListItem( &$comment )
	{
		$total = JComments::getCommentsCount($comment->object_id, $comment->object_group, 'parent = ' . $comment->parent);

		$tmpl = JCommentsFactory::getTemplate($comment->object_id, $comment->object_group);
		$tmpl->load('tpl_list');
		$tmpl->addVar('tpl_list', 'comment-id', $comment->id);
		$tmpl->addVar('tpl_list', 'comment-item', JComments::getCommentItem($comment));
		$tmpl->addVar('tpl_list', 'comment-modulo', $total % 2 ? 1 : 0);

		return $tmpl->renderTemplate('tpl_list');
	}

	/**
	 * @param  $comment JCommentsTableComment
	 * @param boolean $isNew
	 * @return void
	 */
	public static function sendNotification( &$comment, $isNew = true)
	{
		$app = JCommentsFactory::getApplication('site');
		$user = JCommentsFactory::getUser();
		$config = JCommentsFactory::getConfig();

		if ($config->get('notification_email') != '') {

			$objectInfo = JCommentsObjectHelper::getObjectInfo($comment->object_id, $comment->object_group, $comment->lang);

			if ($comment->title != '') {
				$comment->title = JCommentsText::censor($comment->title);
			}

			$commentText = $comment->comment;

			$bbcode = JCommentsFactory::getBBCode();
			$txt = JCommentsText::censor($comment->comment);
			$txt = $bbcode->replace($txt);

			if ($config->getInt('enable_custom_bbcode')) {
				$customBBCode = JCommentsFactory::getCustomBBCode();
				// TODO: add control for replacement mode from CustomBBCode parameters
				$txt = $customBBCode->replace($txt, true);
			}

			$comment->comment = trim(preg_replace('/(\s){2,}/i', '\\1', $txt));
			$comment->author = JComments::getCommentAuthorName($comment);

			$tmpl = JCommentsFactory::getTemplate($comment->object_id, $comment->object_group);
			$tmpl->load('tpl_email_administrator');
			$tmpl->addVar('tpl_email_administrator', 'notification-type', 'admin');
			$tmpl->addVar('tpl_email_administrator', 'comment-isnew', ($isNew) ? 1 : 0);
			$tmpl->addVar('tpl_email_administrator', 'comment-object_title', $objectInfo->title);
			$tmpl->addVar('tpl_email_administrator', 'comment-object_link', JCommentsFactory::getAbsLink($objectInfo->link));
			$tmpl->addVar('tpl_email_administrator', 'quick-moderation', $config->getInt('enable_quick_moderation'));
			$tmpl->addVar('tpl_email_administrator', 'enable-blacklist', $config->getInt('enable_blacklist'));
			$tmpl->addObject('tpl_email_administrator', 'comment', $comment);
			$message = $tmpl->renderTemplate('tpl_email_administrator');
			$tmpl->freeTemplate('tpl_email_administrator');

			if ($isNew) {
				$subject = JText::sprintf('NOTIFICATION_SUBJECT_NEW', $objectInfo->title);
			} else {
				$subject = JText::sprintf('NOTIFICATION_SUBJECT_UPDATED', $objectInfo->title);
			}

			if (isset($subject) && isset($message)) {
				$emails = explode(',', $config->get('notification_email'));
				$mailFrom = $app->getCfg('mailfrom');
				$fromName = $app->getCfg('fromname');

				foreach ($emails as $email) {
					$email = trim($email);

					// don't send notification to message author
					if ($user->email != $email) {
						JCommentsMail::send($mailFrom, $fromName, $email, $subject, $message, true);
					}
				}
			}
			unset($emails, $objectInfo);

			$comment->comment = $commentText;
		}
	}

	public static function sendReport( &$comment, $name, $reason = '')
	{
		$app = JCommentsFactory::getApplication('site');
		$user = JCommentsFactory::getUser();
		$config = JCommentsFactory::getConfig();

		if ($config->get('notification_email') != '') {

			$objectInfo = JCommentsObjectHelper::getObjectInfo($comment->object_id, $comment->object_group, $comment->lang);

			$commentText  = $comment->comment;

			$bbcode = JCommentsFactory::getBBCode();
			$txt = JCommentsText::censor($comment->comment);
			$txt = $bbcode->replace($txt);

			if ($config->getInt('enable_custom_bbcode')) {
				$customBBCode = JCommentsFactory::getCustomBBCode();
				// TODO: add control for replacement mode from CustomBBCode parameters
				$txt = $customBBCode->replace($txt, true);
			}

			$comment->comment = trim(preg_replace('/(\s){2,}/i', '\\1', $txt));
			$comment->author = JComments::getCommentAuthorName($comment);

			$tmpl = JCommentsFactory::getTemplate($comment->object_id, $comment->object_group);
			$tmpl->load('tpl_email_report');
			$tmpl->addVar('tpl_email_report', 'comment-object_title', $objectInfo->title);
			$tmpl->addVar('tpl_email_report', 'comment-object_link', JCommentsFactory::getAbsLink($objectInfo->link));
			$tmpl->addVar('tpl_email_report', 'report-name', $name);
			$tmpl->addVar('tpl_email_report', 'report-reason', $reason);
			$tmpl->addVar('tpl_email_report', 'quick-moderation', $config->getInt('enable_quick_moderation'));
			$tmpl->addVar('tpl_email_report', 'enable-blacklist', $config->getInt('enable_blacklist'));
			$tmpl->addObject('tpl_email_report', 'comment', $comment);

			$message = $tmpl->renderTemplate('tpl_email_report');

			$tmpl->freeTemplate('tpl_email_report');

			$subject = JText::sprintf('REPORT_NOTIFICATION_SUBJECT', $comment->author);

			if (isset($subject) && isset($message)) {
				$emails = explode(',', $config->get('notification_email'));

				$mailFrom = $app->getCfg('mailfrom');
				$fromName = $app->getCfg('fromname');

				foreach ($emails as $email) {
					$email = trim((string) $email);

					// don't send notification to message author
					if ($user->email != $email) {
						JCommentsMail::send($mailFrom, $fromName, $email, $subject, $message, true);
					}
				}
			}
			unset($emails, $objectInfo);

			$comment->comment = $commentText;
		}
	}

	/**
	 * @param  $comment JCommentsTableComment
	 * @param boolean $isNew
	 * @return
	 */
	public static function sendToSubscribers( &$comment, $isNew = true)
	{
		if (!$comment->published) {
			return;
		}

		$app = JCommentsFactory::getApplication('site');
		$dbo = JCommentsFactory::getDBO();
		$config = JCommentsFactory::getConfig();

		$query = "SELECT DISTINCTROW js.`name`, js.`email`, js.`hash` "
			. "\n , jo.title AS object_title, jo.link AS object_link, jo.access AS object_access"
			. "\nFROM #__jcomments_subscriptions AS js"
			. "\nJOIN #__jcomments_objects AS jo ON js.object_id = jo.object_id AND js.object_group = jo.object_group"
			. "\nWHERE js.`object_group` = " . $dbo->Quote($comment->object_group)
			. "\nAND js.`object_id` = " . intval($comment->object_id)
			. "\nAND js.`published` = 1 "
			. (JCommentsMultilingual::isEnabled() ? "\nAND js.`lang` = " . $dbo->Quote($comment->lang) : '')
			. (JCommentsMultilingual::isEnabled() ? "\nAND jo.`lang` = " . $dbo->Quote($comment->lang) : '')
			. "\nAND js.`email` <> '" . $dbo->getEscaped($comment->email) . "'"
			. ($comment->userid ? "\nAND js.`userid` <> " . $comment->userid : '')
			;
		$dbo->setQuery( $query );
		$rows = $dbo->loadObjectList();

		if (count($rows)) {
			// getting object's information (title and link)
			$object_title = empty($rows[0]->object_title) ? JCommentsObjectHelper::getTitle($comment->object_id, $comment->object_group, $comment->lang) : $rows[0]->object_title;
			$object_link = empty($rows[0]->object_link) ? JCommentsObjectHelper::getLink($comment->object_id, $comment->object_group, $comment->lang) : $rows[0]->object_link;
			$object_link = JCommentsFactory::getAbsLink($object_link);

			if ($comment->title != '') {
				$comment->title = JCommentsText::censor($comment->title);
			}

			$commentText = $comment->comment;

			$bbcode = JCommentsFactory::getBBCode();
			$txt = JCommentsText::censor($comment->comment);
			$txt = $bbcode->replace($txt);

			if ($config->getInt('enable_custom_bbcode')) {
				$customBBCode = JCommentsFactory::getCustomBBCode();
				// TODO: add control for replacement mode from CustomBBCode parameters
				$txt = $customBBCode->replace($txt, true);
			}

			$comment->comment = trim(preg_replace('/(\s){2,}/i', '\\1', $txt));
			$comment->author = JComments::getCommentAuthorName($comment);

			$tmpl = JCommentsFactory::getTemplate($comment->object_id, $comment->object_group);
			$tmpl->load('tpl_email');
			$tmpl->addVar('tpl_email', 'notification-type', 'subscription');
			$tmpl->addVar('tpl_email', 'comment-isnew', ($isNew) ? 1 : 0);
			$tmpl->addVar('tpl_email', 'comment-object_title', $object_title);
			$tmpl->addVar('tpl_email', 'comment-object_link', $object_link);
			$tmpl->addObject('tpl_email', 'comment', $comment);

			if ($isNew) {
				$subject = JText::sprintf('NOTIFICATION_SUBJECT_NEW', $object_title);
			} else {
				$subject = JText::sprintf('NOTIFICATION_SUBJECT_UPDATED', $object_title);
			}

			if (isset($subject)) {
				$mailFrom = $app->getCfg('mailfrom');
				$fromName = $app->getCfg('fromname');

				foreach ($rows as $row) {
					$tmpl->addVar('tpl_email', 'hash', $row->hash);
					$message = $tmpl->renderTemplate('tpl_email');

					JCommentsMail::send($mailFrom, $fromName, $row->email, $subject, $message, true);
				}
			}

			$tmpl->freeTemplate('tpl_email');

			unset($rows);

			$comment->comment = $commentText;
		}
	}

	public static function prepareComment( &$comment )
	{
		if (isset($comment->_skip_prepare) && $comment->_skip_prepare == 1) {
			return;
		}

		JCommentsEvent::trigger('onJCommentsCommentBeforePrepare', array(&$comment));

		$config = JCommentsFactory::getConfig();
		$bbcode = JCommentsFactory::getBBCode();
		$acl = JCommentsFactory::getACL();

		// run autocensor
		if ($acl->check('enable_autocensor')) {
			$comment->comment = JCommentsText::censor($comment->comment);

			if ($comment->title != '') {
				$comment->title = JCommentsText::censor($comment->title);
			}
		}

		// replace deleted comment text with predefined message
		if ($comment->deleted == 1) {
			$comment->comment = JText::_('COMMENT_TEXT_COMMENT_HAS_BEEN_DELETED');
			$comment->username = '';
			$comment->name = '';
			$comment->email = '';
			$comment->homepage = '';
			$comment->userid = 0;
			$comment->isgood = 0;
			$comment->ispoor = 0;
		}

		// replace BBCode tags
		$comment->comment = $bbcode->replace($comment->comment);

		if ($config->getInt('enable_custom_bbcode')) {
			$customBBCode = JCommentsFactory::getCustomBBCode();
			$comment->comment = $customBBCode->replace($comment->comment);
		}

		// fix long words problem
		$word_maxlength = $config->getInt('word_maxlength');
		if ($word_maxlength > 0) {
			$comment->comment = JCommentsText::fixLongWords($comment->comment, $word_maxlength);
			if ($comment->title != '') {
				$comment->title = JCommentsText::fixLongWords($comment->title, $word_maxlength);
			}
		}

		if ($acl->check('emailprotection')) {
			$comment->comment = JComments::maskEmail($comment->id, $comment->comment);
		}

		// autolink urls
		if ($acl->check('autolinkurls')) {
			$comment->comment = preg_replace_callback(_JC_REGEXP_LINK, array('JComments', 'urlProcessor'), $comment->comment);

			if ($acl->check('emailprotection') != 1) {
				$comment->comment = preg_replace(_JC_REGEXP_EMAIL, '<a href="mailto:\\1@\\2">\\1@\\2</a>', $comment->comment);
			}
		}

		// replace smile codes with images
		if ($config->get('enable_smiles') == '1') {
			$smiles = JCommentsFactory::getSmiles();
			$comment->comment = $smiles->replace($comment->comment);
		}

		$comment->author = JComments::getCommentAuthorName($comment);

		// Gravatar support
		$comment->gravatar = md5(strtolower($comment->email));

		if (empty($comment->avatar)) {
			$comment->avatar = '<img src="http://www.gravatar.com/avatar.php?gravatar_id='. $comment->gravatar .'&amp;default=' . urlencode(JCommentsFactory::getLink('noavatar')) . '" alt="'.htmlspecialchars($comment->author).'" />';
		}

		JCommentsEvent::trigger('onJCommentsCommentAfterPrepare', array(&$comment));
	}

	public static function maskEmail( $id, $text )
	{
		$id = (int) $id;

		if ($id) {
			$GLOBALS['JCOMMENTS_COMMENTID'] = $id;
			$text = preg_replace_callback(_JC_REGEXP_EMAIL, array('JComments', 'maskEmailReplacer'), $text);
		}
		return $text;
	}

	public static function maskEmailReplacer( &$matches )
	{
		$app = JCommentsFactory::getApplication('site');
		return "<span onclick=\"jcomments.jump2email(" . $GLOBALS['JCOMMENTS_COMMENTID'] . ", '" . md5($matches[0]) . "');\" class=\"email\" onmouseover=\"this.className='emailactive';\" onmouseout=\"this.className='email';\">" . $matches[1] . "<img src=\"" . $app->getCfg('live_site') . "/components/com_jcomments/images/email.png\" border=\"0\" alt=\"@\" />" . $matches[2] . "</span>";
	}

	public static function urlProcessor( &$matches )
	{
		$app = JCommentsFactory::getApplication('site');

		$link = $matches[2];
		$link_suffix = '';

		while (preg_match('#[\,\.]+#', $link[strlen($link) - 1])) {
			$sl = strlen($link)-1;
			$link_suffix .= $link[$sl];
			$link = substr($link, 0, $sl);
		}

		$link_text = preg_replace('#(http|https|news|ftp)\:\/\/#i', '', $link);

		$config = JCommentsFactory::getConfig();
		$link_maxlength = $config->getInt('link_maxlength');

		if (($link_maxlength > 0) && (strlen($link_text) > $link_maxlength)) {
			$linkParts = preg_split('#\/#i', preg_replace('#/$#i', '', $link_text));
			$cnt = count($linkParts);

			if ($cnt >= 2) {
				$linkSite = $linkParts[0];
				$linkDocument = $linkParts[$cnt - 1];
				$shortLink = $linkSite . '/.../' . $linkDocument;

				if ($cnt == 2) {
					$shortLink = $linkSite . '/.../';
				} else if (strlen($shortLink) > $link_maxlength) {
					$linkSite = str_replace('www.', '', $linkSite);
					$linkSiteLength = strlen($linkSite);
					$shortLink = $linkSite . '/.../' . $linkDocument;

					if (strlen($shortLink) > $link_maxlength) {
						if ($linkSiteLength < $link_maxlength) {
							$shortLink = $linkSite . '/.../...';
						} else if ($linkDocument < $link_maxlength) {
							$shortLink = '.../' . $linkDocument;
						} else {
							$link_protocol = preg_replace('#([^a-z])#i', '', $matches[3]);

							if ($link_protocol == 'www') {
								$link_protocol = 'http';
							}

							if ($link_protocol != '') {
								$shortLink = $link_protocol;
							} else {
								$shortLink = '/.../';
							}
						}
					}
				}
				$link_text = wordwrap($shortLink, $link_maxlength, ' ', true);
			} else {
				$link_text = wordwrap($link_text, $link_maxlength, ' ', true);
			}
		}

		if (strpos($link, $app->getCfg('live_site')) === false) {
			return $matches[1]."<a href=\"".((substr($link, 0, 3)=='www') ? "http://" : "").$link."\" target=\"_blank\" rel=\"external nofollow\">$link_text</a>" . $link_suffix;
		} else {
			return $matches[1]."<a href=\"$link\" target=\"_blank\">$link_text</a>" . $link_suffix;
		}
	}

	public static function getCommentPage($object_id, $object_group, $comment_id)
	{
		$config = JCommentsFactory::getConfig();
		if ($config->getInt('comments_per_page') > 0) {
			require_once (JCOMMENTS_HELPERS.DS.'pagination.php');
			$pagination = new JCommentsPagination($object_id, $object_group);
			$this_page = $pagination->getCommentPage($object_id, $object_group, $comment_id);
		} else {
			$this_page = 0;
		}
		return $this_page;
	}

	public static function getCommentAuthorName( $comment )
	{
		$name = '';

		if ($comment != null) {
			$config = JCommentsFactory::getConfig();
			if ($comment->userid && $config->get('display_author') == 'username' && $comment->username != '') {
				$name = $comment->username;
			} else {
				$name = $comment->name ? $comment->name : 'Guest'; // JText::_('Guest');
			}
		}
		return $name;
	}

	public static function unsubscribe()
	{
		$app = JCommentsFactory::getApplication('site');
		$hash = JCommentsInput::getVar('hash','');
		$hash = preg_replace('#[^A-Z0-9]#i', '', $hash);

		if ($hash) {
			require_once (JCOMMENTS_BASE.DS.'jcomments.subscription.php');
			$manager = JCommentsSubscriptionManager::getInstance();
			$result = $manager->unsubscribeByHash($hash);
			if ($result) {
				JCommentsRedirect($app->getCfg('live_site') . '/index.php', JText::_('SUCCESSFULLY_UNSUBSCRIBED'));
			}
		}

		header('HTTP/1.0 404 Not Found');
		if (JCOMMENTS_JVERSION != '1.0') {
			$message = JCOMMENTS_JVERSION == '1.5' ? 'Resource Not Found' : 'JGLOBAL_RESOURCE_NOT_FOUND';
			JError::raiseError(404, $message);
		}
		exit(404);
	}

	public static function executeCmd()
	{
		$app = JCommentsFactory::getApplication('site');
		$cmd = strtolower(JCommentsInput::getVar('cmd', ''));
		$hash = JCommentsInput::getVar('hash', '');
		$id = (int) JCommentsInput::getVar('id', 0);

		$message = '';
		$link = $app->getCfg('live_site') . '/index.php';

		$checkHash = JCommentsFactory::getCmdHash($cmd, $id);
		if ($hash == $checkHash) {
			$config = JCommentsFactory::getConfig();
			if ($config->getInt('enable_quick_moderation') == 1) {
				$db = JCommentsFactory::getDBO();
				$comment = new JCommentsTableComment($db);
				if ($comment->load($id)) {
					$link = JCommentsObjectHelper::getLink($comment->object_id, $comment->object_group, $comment->lang);
					$link = str_replace('&amp;', '&', $link);
					switch($cmd) {
						case 'publish':
							$comment->published = 1;
							$comment->store();

							// send notification to comment subscribers
							JComments::sendToSubscribers($comment, true);

							$link .= '#comment-' . $comment->id;
							break;

						case 'unpublish':
							$comment->published = 0;
							$comment->store();

							$acl = JCommentsFactory::getACL();
							if ($acl->canPublish()) {
								$link .= '#comment-' . $comment->id;
							} else {
								$link .= '#comments';
							}
							break;

						case 'delete':
							if ($config->getInt('delete_mode') == 0) {
								$comment->delete();
								$link .= '#comments';
							} else {
								$comment->markAsDeleted();
								$link .= '#comment-' . $comment->id;
							}
							break;

						case 'ban':
							if ($config->getInt('enable_blacklist') == 1) {
								$acl = JCommentsFactory::getACL();
								// we will not ban own IP ;)
								if ($comment->ip != $acl->getUserIP()) {
									$options = array();
									$options['ip'] = $comment->ip;

									// check if this IP already banned
									if (JCommentsSecurity::checkBlacklist($options)) {
										require_once(JCOMMENTS_TABLES.'/blacklist.php');
										
										$blacklist = new JCommentsTableBlacklist($db);
										$blacklist->ip = $comment->ip;
										$blacklist->created = JCommentsFactory::getDate();
										$blacklist->created_by = $acl->getUserId();
										$blacklist->store();
										$message = JText::_('SUCCESSFULLY_BANNED');
									} else {
										$message = JText::_('ERROR_IP_ALREADY_BANNED');
									}
								} else {
									$message = JText::_('ERROR_YOU_CAN_NOT_BAN_YOUR_IP');
								}
							}
							break;
					}
				} else {
					$message = JText::_('ERROR_NOT_FOUND');
				}
			} else {
				$message = JText::_('ERROR_QUICK_MODERATION_DISABLED');
			}
		} else {
			$message = JText::_('ERROR_QUICK_MODERATION_INCORRECT_HASH');
		}	
		JCommentsRedirect($link, $message);
	}

	public static function redirectToObject()
	{
		$app = JCommentsFactory::getApplication('site');
		$object_id = (int) JCommentsInput::getVar('object_id', 0);
		$object_group = trim(strip_tags(JCommentsInput::getVar('object_group', 'com_content')));
		$lang = trim(strip_tags(JCommentsInput::getVar('lang')));

		if ($object_id != 0 && $object_group != '') {
			$link = JCommentsObjectHelper::getLink($object_id, $object_group, $lang);
			$link = str_replace('amp;', '', $link);
			if ($link == '') {
				$link = $app->getCfg('live_site');
			}
		} else {
			$link = $app->getCfg('live_site');
		}
		JCommentsRedirect($link);
	}


	public static function getCommentsCount( $object_id, $object_group = 'com_content', $filter = '' )
	{
		$acl = JCommentsFactory::getACL();

		$options = array();
		$options['object_id'] = (int) $object_id;
		$options['object_group'] = trim($object_group);
		$options['published'] = $acl->canPublish() || $acl->canPublishForObject($object_id, $object_group) ? null : 1;
		$options['filter'] = $filter;

		return JCommentsModel::getCommentsCount($options);
	}

	/*
	 * @see JComments::show()
	 * @deprecated As of version 2.0.0
	 */
	public static function showComments( $object_id, $object_group = 'com_content', $object_title = '' )
	{
		return JComments::show($object_id, $object_group, $object_title);
	}
}
?>