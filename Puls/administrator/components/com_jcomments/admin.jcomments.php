<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend event handler
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

ob_start();

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if (defined('JPATH_ROOT')) {
	include_once (JPATH_ROOT.'/components/com_jcomments/jcomments.legacy.php');
} else {
	global $mainframe;
	require_once ($mainframe->getCfg('absolute_path').'/components/com_jcomments/jcomments.legacy.php');
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

if (JCOMMENTS_JVERSION == '1.0') {
	global $acl, $my, $option, $task;
	DEFINE('JCOMMENTS_INDEX', 'index2.php');

	// ensure user has access to this function
	if (!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all') | $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_jcomments'))) {
		require_once (JCOMMENTS_BASE.'/jcomments.class.php');
		JCommentsRedirect('index2.php', _NOT_AUTH);
	}
} else {
	DEFINE('JCOMMENTS_INDEX', 'index.php');
	$acl = JFactory::getACL();
	$option = JRequest::getCmd('option');
	$task = JRequest::getCmd('task');
}

$result = ob_get_contents();
ob_end_clean();

// save PHP error reporting settings
//$_error_reporting = @error_reporting(0);

require_once (JCOMMENTS_BASE.'/jcomments.class.php');
require_once (JCOMMENTS_BASE.'/jcomments.config.php');
require_once (JCOMMENTS_MODELS.'/jcomments.php');
require_once (JCOMMENTS_HELPERS.'/object.php');
require_once (JCOMMENTS_HELPERS.'/event.php');
require_once (JCOMMENTS_HELPERS.'/html.php');
require_once (JCOMMENTS_TABLES.'/comment.php');
require_once (dirname(__FILE__).'/admin.jcomments.html.php');

if ($task != 'postinstall') {
	$config = JCommentsFactory::getConfig();
}

if (!function_exists('sefRelToAbs')){
	if (!defined('_URL_SCHEMES')) {
		$url_schemes = 'data:, file:, ftp:, gopher:, imap:, ldap:, mailto:, news:, nntp:, telnet:, javascript:, irc:, mms:';
		DEFINE( '_URL_SCHEMES', $url_schemes );
	}

	function sefRelToAbs( $string )
	{
		$app = JCommentsFactory::getApplication('administrator');

		if ( (strpos( $string, $app->getCfg( 'live_site' ) ) !== 0) ) {
			if (strncmp($string, '/', 1) == 0) {
				$live_site_parts = array();
				preg_match('/^(https?:[\/]+[^\/]+)(.*$)/i', $app->getCfg( 'live_site' ), $live_site_parts);
				$string = $live_site_parts[1] . $string;
			} else {
				$check = 1;
				$url_schemes 	= explode( ', ', _URL_SCHEMES );
				$url_schemes[] 	= 'http:';
				$url_schemes[] 	= 'https:';

				foreach ( $url_schemes as $url ) {
					if ( strpos( $string, $url ) === 0 ) {
						$check = 0;
					}
				}
				if ( $check ) {
					$string = $app->getCfg( 'live_site' ) .'/'. $string;
				}
			}
		}
		return $string;
	}
}

if ($option == 'com_jcomments') {
	if (isset($_REQUEST['jtxf'])) {
		require_once (JCOMMENTS_BASE.'/jcomments.ajax.php');

		$jtx = new JoomlaTuneAjax();
		$jtx->setCharEncoding(JCOMMENTS_ENCODING);
		$jtx->registerFunction(array('JCommentsSaveSettingsAjax', 'JCommentsAdmin', 'saveSettingsAjax'));
		$jtx->registerFunction(array('JCommentsRestoreSettingsAjax', 'JCommentsAdmin', 'restoreSettingsAjax'));
		$jtx->registerFunction(array('JCommentsImportCommentsAjax', 'JCommentsAdmin', 'importCommentsAjax'));
		$jtx->registerFunction(array('JCommentsRemoveReportAjax', 'JCommentsAdmin', 'removeReportAjax'));
		$jtx->processRequests();

	} else if (isset($_REQUEST['no_html']) && intval($_REQUEST['no_html']) == 1) {
		require_once (JCOMMENTS_BASE.'/jcomments.php');
	} else {

		switch ($task) {
			case "comments":
				JCommentsAdmin::checkPhpVersion();
				JCommentsAdmin::show();
				break;
			case 'comments.edit':
				JCommentsAdmin::edit();
				break;
			case 'comments.apply':
			case 'comments.save':
				JCommentsAdmin::save();
				break;
			case 'comments.cancel':
				JCommentsAdmin::cancel();
				break;
			case 'comments.publish':
				JCommentsAdmin::publish(1);
				break;
			case 'comments.unpublish':
				JCommentsAdmin::publish(0);
				break;
			case 'comments.remove':
				JCommentsAdmin::remove();
				break;

			case "settings":
				JCommentsAdmin::checkPhpVersion();
				JCommentsAdmin::showSettings();
				break;
			case "settings.cancel":
				JCommentsAdmin::cancelSettings();
				break;
			case "settings.save":
				JCommentsAdmin::saveSettingsDefault();
				break;
			case "settings.restore":
				JCommentsAdmin::restoreSettingsDefault();
				break;

			case "smiles":
				JCommentsAdmin::checkPhpVersion();
				JCommentsAdmin::showSmiles();
				break;
			case "smiles.save":
				JCommentsAdmin::saveSmiles();
				break;

			case "about":
				require_once (dirname(__FILE__).DS.'admin.jcomments.installer.php');
				JCommentsAdmin::showAbout();
				break;
			case "import":
				JCommentsAdmin::checkPhpVersion();
				require_once (dirname(__FILE__).DS.'admin.jcomments.migration.php');
				JCommentsMigrationTool::showImport();
				break;
			case "postinstall":
				JCommentsAdmin::checkPhpVersion();
				require_once (dirname(__FILE__).DS.'admin.jcomments.installer.php');
				JCommentsInstaller::postInstall();
				break;

			case 'subscriptions':
				JCommentsAdmin::checkPhpVersion();
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::show();
				break;
			case 'subscription.publish':
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::publish(1);
				break;
			case 'subscription.unpublish':
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::publish(0);
				break;
			case 'subscription.new':
			case 'subscription.edit':
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::edit();
				break;
			case 'subscription.apply':
			case 'subscription.save':
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::save();
				break;
			case 'subscription.remove':
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::remove();
				break;
			case 'subscription.cancel':
				require_once (dirname(__FILE__).DS.'admin.jcomments.subscription.php');
				JCommentsAdminSubscriptionManager::cancel();
				break;

			case 'custombbcodes':
				JCommentsAdmin::checkPhpVersion();
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::show();
				break;
			case 'custombbcodes.publish':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::publish(1);
				break;
			case 'custombbcodes.unpublish':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::publish(0);
				break;
			case 'custombbcodes.enable_button':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::enableButton(1);
				break;
			case 'custombbcodes.disable_button':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::enableButton(0);
				break;
			case 'custombbcodes.new':
			case 'custombbcodes.edit':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::edit();
				break;
			case 'custombbcodes.apply':
			case 'custombbcodes.save':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::save();
				break;
			case 'custombbcodes.remove':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::remove();
				break;
			case 'custombbcodes.copy':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::copy();
				break;
			case 'custombbcodes.orderup':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::order(-1);
				break;
			case 'custombbcodes.orderdown':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::order(1);
				break;
			case 'custombbcodes.cancel':
				require_once (dirname(__FILE__).DS.'admin.jcomments.custombbcodes.php');
				JCommentsACustomBBCodes::cancel();
				break;

			case 'blacklist':
				JCommentsAdmin::checkPhpVersion();
				require_once (dirname(__FILE__).DS.'admin.jcomments.blacklist.php');
				JCommentsAdminBlacklistManager::show();
				break;
			case 'blacklist.new':
			case 'blacklist.edit':
				require_once (dirname(__FILE__).DS.'admin.jcomments.blacklist.php');
				JCommentsAdminBlacklistManager::edit();
				break;
			case 'blacklist.apply':
			case 'blacklist.save':
				require_once (dirname(__FILE__).DS.'admin.jcomments.blacklist.php');
				JCommentsAdminBlacklistManager::save();
				break;
			case 'blacklist.remove':
				require_once (dirname(__FILE__).DS.'admin.jcomments.blacklist.php');
				JCommentsAdminBlacklistManager::remove();
				break;
			case 'blacklist.cancel':
				require_once (dirname(__FILE__).DS.'admin.jcomments.blacklist.php');
				JCommentsAdminBlacklistManager::cancel();
				break;

			case 'refresh.objects':
				JCommentsAdmin::refreshObjects();
				break;

			default:
				JCommentsAdmin::checkPhpVersion();
				JCommentsAdmin::show();
				break;
		}
	}
}

class JCommentsAdmin
{
	public static function show()
	{
		require_once (JCOMMENTS_BASE.'/jcomments.php');

		$app = JCommentsFactory::getApplication('administrator');
		$context = 'com_jcomments.comments.';

		$object_group = trim($app->getUserStateFromRequest($context . "fog", 'fog', ''));
		$object_id = intval($app->getUserStateFromRequest($context . "foid", 'foid', 0));
		$flang = trim($app->getUserStateFromRequest($context . "flang", 'flang', '-1'));
		$fauthor = trim($app->getUserStateFromRequest($context . "fauthor", 'fauthor', ''));
		$fstate = trim($app->getUserStateFromRequest($context . "fstate", 'fstate', ''));
		$limit = intval($app->getUserStateFromRequest($context . "limit", 'limit', $app->getCfg('list_limit')));
		$limitstart = intval($app->getUserStateFromRequest($context . "limitstart", 'limitstart', 0));

		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'c.date');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'desc');
		$search = trim($app->getUserStateFromRequest($context . 'search', 'search', ''));

		if ($filter_order == '') {
			$filter_order = 'c.date';
		}

		if ($filter_order_Dir == '') {
			$filter_order_Dir = 'desc';
		}

		if (JCOMMENTS_JVERSION == '1.0') {
			$search	= strtolower($search);
		} else {
			$search	= JString::strtolower($search);
		}

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['search'] = $search;

		$db = JCommentsFactory::getDBO();

		// load object_groups (components)
		$query = "SELECT DISTINCT(object_group) AS name, object_group AS value "
				. " FROM #__jcomments_objects "
				. " WHERE object_group <> ''"
				. " ORDER BY object_group";
		$db->setQuery($query);
		$objectGroups = $db->loadObjectList('name');

		$cnt = count($objectGroups);

		if ($cnt == 0 || ($cnt > 0 && !in_array($object_group, array_keys($objectGroups)))) {
			$app->setUserState($context . "fog", '');
			$app->setUserState($context . "foid", '');
			$object_group = '';
		}

		$where = array();

		if ($object_group != '') {
			$where[] = 'c.object_group = ' . $db->Quote($object_group);
		} else {
			$object_id = 0;
			$app->setUserState($context . "foid", '');
		}

		if ($object_id != 0) {
			$where[] = 'c.object_id = ' . intval($object_id);
		}

		if ($flang != '-1') {
			$where[] = 'c.lang = ' . $db->Quote($flang);
		}

		if (trim($fauthor) != '') {
			$where[] = 'c.name = ' . $db->Quote($fauthor);
		}

		if ($fstate != '' && $fstate != '-1') {
			if ($fstate == '2') {
				$where[] = 'EXISTS (SELECT * FROM #__jcomments_reports AS r WHERE r.commentid = c.id)';
			} else {
				$where[] = 'c.published = ' . intval($fstate);
			}
		}

		if ($search != '') {
			$where[] = '('
					. 'LOWER(c.comment) like "%' . $db->getEscaped($search, true) . '%" OR '
					. 'LOWER(c.title) like "%' . $db->getEscaped($search, true) . '%" OR '
					. 'LOWER(c.name) like "%' . $db->getEscaped($search, true) . '%" OR '
					. 'LOWER(c.username) like "%' . $db->getEscaped($search, true) . '%" OR '
					. 'LOWER(c.email) like "%' . $db->getEscaped($search, true) . '%"'
					. ')';
		}

		// TODO: add filter to show deleted comments
		$where[] = 'c.deleted = 0';

		$query = "SELECT COUNT(*)"
				. "\nFROM #__jcomments AS c"
				. (count($where) ? ("\nWHERE " . implode(' AND ', $where)) : "");
		$db->setQuery($query);
		$total = $db->loadResult();

		$lists['pageNav'] = JCommentsAdmin::getPagination($total, $limitstart, $limit);

		$query = "SELECT c.*, u.name AS editor, js.id as subscription, (select count(*) from #__jcomments_reports where commentid = c.id) as reports"
				. ", jo.title AS object_title, jo.link AS object_link"
				. "\nFROM #__jcomments AS c"
				. "\n LEFT JOIN #__jcomments_objects AS jo ON jo.object_id = c.object_id AND jo.object_group = c.object_group AND jo.lang = c.lang"
				. "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
				. "\n LEFT JOIN #__jcomments_subscriptions AS js ON js.object_id = c.object_id AND js.object_group = c.object_group AND ((c.userid > 0 AND js.userid = c.userid) OR (js.email <> '' AND c.email <> '' AND js.email = c.email)) AND js.lang = c.lang"
				. (count($where) ? ("\nWHERE " . implode(' AND ', $where)) : "")
				. "\nORDER BY " . $filter_order . ' ' . $filter_order_Dir;
		$db->setQuery( $query, $lists['pageNav']->limitstart, $lists['pageNav']->limit );
		$lists['rows'] = $db->loadObjectList();

		// Filter by object_group (component)
		$cnt = count($objectGroups);

		if (JCOMMENTS_JVERSION == '1.0') {
			$a = array();
			if (is_array($objectGroups)) {
				foreach($objectGroups as $o) {
					$a[] = $o;
				}
			}
			$objectGroups = $a;
		}

		if ($cnt > 1 || ($cnt == 1 && $total == 0)) {
			array_unshift($objectGroups, JCommentsHTML::makeOption('', JText::_('A_FILTER_COMPONENT'), 'name', 'value'));
			$lists['fog'] = JCommentsHTML::selectList($objectGroups, 'fog', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'name', 'value', $object_group);
		} else if ($cnt == 1) {
			/*
 			if ($object_group == '') {
				$aGroups = array_keys($objectGroups);
				$object_group = array_pop($aGroups);
			}
			*/
		}
		unset($objectGroups);

		// Filter by published state
		$stateOptions = array();
		$stateOptions[] = JCommentsHTML::makeOption('-1', JText::_('A_FILTER_STATE'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('', JText::_('A_FILTER_STATE_ALL'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('1', JText::_('A_FILTER_STATE_PUBLISHED'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('0', JText::_('A_FILTER_STATE_UNPUBLISHED'), 'text', 'value');
		$stateOptions[] = JCommentsHTML::makeOption('2', JText::_('A_FILTER_STATE_REPORTED'), 'text', 'value');

		$lists['fstate'] = JCommentsHTML::selectList($stateOptions, 'fstate', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'text', 'value', $fstate);
		unset($stateOptions);

		// Filter by language
		$query = "SELECT DISTINCT(lang) AS text, lang AS value "
				. " FROM #__jcomments_objects "
				. "\nORDER BY lang";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if (count($rows) > 1) {
			array_unshift($rows, JCommentsHTML::makeOption('-1', JText::_('A_FILTER_LANGUAGE'), 'text', 'value'));
			$lists['flang'] = JCommentsHTML::selectList($rows, 'flang', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'text', 'value', $flang);
		}
		unset($rows);

		// Filter by author
		$lists['fauthor'] = '';

		// Do not use user filter if we have more than 5000 comments
		if ($total <= 5000) {
			$db->setQuery("SELECT COUNT(DISTINCT(name)) FROM #__jcomments;");
			$usersCount = $db->loadResult();

			// Don't show filter if we have more than 100 comments' authors
			if ($usersCount > 0 && $usersCount < 100) {
				$query = "SELECT DISTINCT(name) AS author, name AS value "
						. "\nFROM #__jcomments"
						. "\nWHERE name <> ''"
						. "\nORDER BY name";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				if (count($rows) > 1) {
					array_unshift($rows, JCommentsHTML::makeOption('', JText::_('A_FILTER_AUTHOR'), 'author', 'value'));
					$lists['fauthor'] = JCommentsHTML::selectList($rows, 'fauthor', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'author', 'value', $fauthor);
				}
				unset($rows);
			}
		}

		HTML_JComments::show($lists);
	}

	public static function edit()
	{
		$id = JCommentsInput::getVar('cid', 0);
		if (is_array($id)) {
			$id = $id[0];
		}

		$user = JCommentsFactory::getUser();
		$db = JCommentsFactory::getDBO();

		$row = new JCommentsTableComment($db);
		if ($row->load(intval($id))) {
			$row->checkout($user->id);

			$row->comment = JCommentsText::br2nl($row->comment);
			$row->comment = htmlspecialchars($row->comment);
			$row->comment = JCommentsText::nl2br($row->comment);
			$row->comment = strip_tags(str_replace('<br />', "\n", $row->comment));

			$lists['object_title'] = JCommentsBackendObjectHelper::getTitle($row->object_id, $row->object_group, $row->lang);
			$lists['object_link'] = JCommentsBackendObjectHelper::getLink($row->object_id, $row->object_group, $row->lang);

			$lists = array();

			$query = "SELECT * "
					. "\n FROM #__jcomments_reports "
					. "\n WHERE commentid = " . (int) $row->id
					. "\n ORDER BY date";
			$db->setQuery($query);
			$lists['reports'] = $db->loadObjectList();

			HTML_JComments::edit($row, $lists);
		} else {
			JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=comments');
		}
	}

	public static function save()
	{
		JCommentsSecurity::checkToken();

		$task = JCommentsInput::getVar('task');
		$id = (int) JCommentsInput::getVar('id', 0);

		$bbcode = JCommentsFactory::getBBCode();
		$db = JCommentsFactory::getDBO();

		$row = new JCommentsTableComment($db);
		if ($row->load($id)) {
			$prevPublished = $row->published;

			$row->homepage = trim(strip_tags(JCommentsInput::getVar('homepage')));
			$row->email = trim(strip_tags(JCommentsInput::getVar('email')));
			$row->title = trim(strip_tags(JCommentsInput::getVar('title')));
			$row->comment = trim(strip_tags(JCommentsInput::getVar('comment')));
			$row->published = (int) JCommentsInput::getVar('published');

			if ($row->userid == 0) {
				$row->name = strip_tags(JCommentsInput::getVar('name'));
				$row->name = preg_replace("/[\'\"\>\<\(\)\[\]]?+/i", '', $row->name);

				if ($row->username != $row->name) {
					$row->username = $row->name;
				}

				$row->username = preg_replace("/[\'\"\>\<\(\)\[\]]?+/i", '', $row->username);
			} else {
				if ($row->name == '' || $row->username == '' || $row->email == '') {
					$user = JCommentsFactory::getUser($row->userid);
					$row->name = $row->name == '' ? $user->name : $row->name;
					$row->username = $row->username == '' ? $user->username : $row->username;
					$row->email = $row->email == '' ? $user->email : $row->email;
				}
			}

			// handle magic quotes compatibility
			if (get_magic_quotes_gpc() == 1) {
				$row->title = stripslashes($row->title);
				$row->comment = stripslashes($row->comment);
			}

			$row->comment = JCommentsText::nl2br($row->comment);
			$row->comment = $bbcode->filter($row->comment);
			$row->store();
			$row->checkin();

			// send notification to comment subscribers
			if ($row->published && $prevPublished != $row->published) {
				// TODO: add separate message for just published comments
				include_once (JCOMMENTS_BASE.'/jcomments.php');

				$language = JCommentsFactory::getLanguage();
				$language->load('com_jcomments', JOOMLATUNE_JPATH_SITE, $row->lang);

				JComments::sendToSubscribers($row, true);
			}

			$cache = JCommentsFactory::getCache('com_jcomments');
			$cache->clean();

			$cache = JCommentsFactory::getCache($row->object_group);
			$cache->clean();
		}

		switch ($task) {
			case 'comments.apply':
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=comments.edit&hidemainmenu=1&cid[]=' . $row->id);
				break;
			case 'comments.save':
			default:
				JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=comments');
				break;
		}
	}

	public static function publish($value)
	{
		JCommentsSecurity::checkToken();

		$pks = JCommentsInput::getVar('cid', array());

		if (is_array($pks)) {
			$db = JCommentsFactory::getDBO();
			$language = JCommentsFactory::getLanguage();
			$config = JCommentsFactory::getConfig();
			$config->set('enable_mambots', 1);

			require_once (JCOMMENTS_BASE . '/jcomments.php');

			$lastLanguage = '';
			foreach ($pks as $pk) {
				$comment = new JCommentsTableComment($db);
				if ($comment->load($pk)) {
					if ($comment->published != $value) {
						$comment->published = $value;

						$result = JCommentsEvent::trigger('onJCommentsCommentBeforePublish', array(&$comment));
						if (!in_array(false, $result, true)) {
							if ($comment->store()) {
								JCommentsEvent::trigger('onJCommentsCommentAfterPublish', array(&$comment));
								if ($comment->published) {
									if ($lastLanguage != $comment->lang) {
										$lastLanguage = $comment->lang;
										$language->load('com_jcomments', JOOMLATUNE_JPATH_SITE, $comment->lang);
									}

									// TODO: add separate message for just published comments
									JComments::sendToSubscribers($comment, true);
								}
							}
						}
					}
				}
			}
		}

		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=comments');
	}

	public static function cancel()
	{
		JCommentsSecurity::checkToken();

		$db = JCommentsFactory::getDBO();
		$row = new JCommentsTableComment($db);
		$row->bind($_POST);
		$row->checkin();

		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=comments');
	}

	public static function remove()
	{
		JCommentsSecurity::checkToken();

		$cid = JCommentsInput::getVar('cid', array());

		if (is_array($cid)) {
			$db = JCommentsFactory::getDBO();
			$config = JCommentsFactory::getConfig();
			if ($config->getInt('delete_mode') == 0) {
				JCommentsModel::deleteCommentsByIds($cid);
			} else {
				$comment = new JCommentsTableComment($db);
				foreach ($cid as $id) {
					$comment->reset();
					if ($comment->load($id)) {
						$comment->markAsDeleted();
					}
				}
			}

			$cache = JCommentsFactory::getCache('com_jcomments');
			$cache->clean();
		}
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=comments');
	}

	public static function showSettings()
	{
		$db = JCommentsFactory::getDBO();
		$config = JCommentsFactory::getConfig();

		// check current site template for afterDisplayContent event
		if (JCOMMENTS_JVERSION == '1.5') {
			$db->setQuery('SELECT template FROM #__templates_menu WHERE client_id = 0 AND menuid = 0', 0, 1);
			$template = $db->loadResult();

			$articleTemplate = JPATH_SITE.'/templates/'.$template.'/html/com_content/article/default.php';
			if (is_file($articleTemplate)) {
				$tmpl = implode('', file($articleTemplate));

				if (strpos($tmpl, 'afterDisplayContent') === false && !(strpos($tmpl, 'include') !== false || strpos($tmpl, 'require') !== false)) {
					JError::raiseWarning(500, JText::_('A_WARNINGS_TEMPLATE_EVENT'));
				}
			}
		}

		$joomfish = JOOMLATUNE_JPATH_SITE.'/components/com_joomfish/joomfish.php';

		if (is_file($joomfish) || JCommentsMultilingual::isEnabled()) {
			$languages = JCommentsMultilingual::getLanguages();
			if (count($languages)) {
				$lang = trim(JCommentsInput::getVar('lang', ''));

				if ($lang == '') {
					if (JCOMMENTS_JVERSION != '1.0') {
						$params = JComponentHelper::getParams('com_languages');
						$lang = $params->get("site", 'en-GB');
					}

					if ($lang == '') {
					 	$lang = JCommentsMultilingual::getLanguage();
					}
				}

				// reload configuration
				$config = JCommentsFactory::getConfig($lang);

				$lists['languages'] = JCommentsHTML::selectList( $languages, 'lang', 'class="inputbox" size="1" onchange="submitform(\'settings\');"', 'value', 'name', $lang );
			}
		}

		$forbiddenNames = $config->get('forbidden_names');
		$forbiddenNames = preg_replace('#,+#', "\n", $forbiddenNames);
		$config->set('forbidden_names', $forbiddenNames);

		$badWords = $config->get('badwords');
		if ($badWords != '') {
			$config->set('badwords', implode("\n", $badWords));
		}

		require_once (JCOMMENTS_LIBRARIES.'/joomlatune/filesystem.php');

		// path to images directory
		$path = JCOMMENTS_BASE.DS.'tpl'.DS;
		$items = JoomlaTuneFS::readDirectory($path);
		$templates = array();

		foreach ($items as $item) {
			if (is_dir($path . $item)) {
				$tpl = new StdClass;
				$tpl->text = $item;
				$tpl->value = $item;
				$templates[] = $tpl;
			}
		}

		$currentTemplate = $config->get('template');
		$lists['templates'] = JCommentsHTML::selectList($templates, 'cfg_template', 'class="inputbox"', 'value', 'text', $currentTemplate);

		require_once (JCOMMENTS_HELPERS.'/user.php');
		$groups = JCommentsUserHelper::getUserGroups();

		$captchaError = '';
		$captchaExclude = array();
		if (!extension_loaded('gd') || !function_exists('imagecreatefrompng')) {
			if ($config->get('captcha_engine', 'kcaptcha') != 'recaptcha') {
				foreach ($groups as $group) {
					$captchaExclude[] = $group->id;
				}
				$captchaError = JText::_('A_WARNINGS_PHP_GD');
			}
		}

		$reportError = '';
		$reportExclude = array();
		if ($config->getInt('enable_notification') == 0 || $config->check('notification_type', 2) == false) {
			foreach ($groups as $group) {
				$reportExclude[] = $group->id;
			}
			$reportError = JText::_('A_REPORTS_WARNING_NOTIFICATIONS_DISABLED');
		}


		$lists['group_names'] = $groups;

		$permissions = array();

		// Post
		JCommentsAdmin::loadParam( $permissions, 'can_comment', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_CAN_COMMENT')
					, JText::_('AP_CAN_COMMENT_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_reply', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_CAN_REPLY')
					, JText::_('AP_CAN_REPLY_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'autopublish', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_AUTOPUBLISH')
					, JText::_('AP_AUTOPUBLISH_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'show_policy', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_SHOW_POLICY')
					, JText::_('AP_SHOW_POLICY_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_captcha', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_ENABLE_CAPTCHA')
					, JText::_('AP_ENABLE_CAPTCHA_DESC')
					, $captchaExclude
					, $captchaError
					);
		JCommentsAdmin::loadParam( $permissions, 'floodprotection', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_ENABLE_FLOODPROTECTION')
					, JText::_('AP_ENABLE_FLOODPROTECTION_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_comment_length_check', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_ENABLE_COMMENT_LENGTH_CHECK')
					, JText::_('AP_ENABLE_COMMENT_LENGTH_CHECK_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_autocensor', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_ENABLE_AUTOCENSOR')
					, JText::_('AP_ENABLE_AUTOCENSOR_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_subscribe', $groups
					, JText::_('A_RIGHTS_POST')
					, JText::_('AP_ENABLE_SUBSCRIBE')
					, JText::_('AP_ENABLE_SUBSCRIBE_DESC')
					);

		// BBCodes
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_b', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_B')
					, JText::_('AP_ENABLE_BBCODE_B_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_i', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_I')
					, JText::_('AP_ENABLE_BBCODE_I_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_u', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_U')
					, JText::_('AP_ENABLE_BBCODE_U_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_s', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_S')
					, JText::_('AP_ENABLE_BBCODE_S_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_url', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_URL')
					, JText::_('AP_ENABLE_BBCODE_URL_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_img', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_IMG')
					, JText::_('AP_ENABLE_BBCODE_IMG_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_list', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_LIST')
					, JText::_('AP_ENABLE_BBCODE_LIST_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_hide', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_HIDE')
					, JText::_('AP_ENABLE_BBCODE_HIDE_DESC')
					, array('Public')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_bbcode_quote', $groups
					, JText::_('A_RIGHTS_BBCODE')
					, JText::_('AP_ENABLE_BBCODE_QUOTE')
					, JText::_('AP_ENABLE_BBCODE_QUOTE_DESC')
					);

		// View
		JCommentsAdmin::loadParam( $permissions, 'autolinkurls', $groups
					, JText::_('A_RIGHTS_VIEW')
					, JText::_('AP_ENABLE_AUTOLINKURLS')
					, JText::_('AP_ENABLE_AUTOLINKURLS_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'emailprotection', $groups
					, JText::_('A_RIGHTS_VIEW')
					, JText::_('AP_ENABLE_EMAILPROTECTION')
					, JText::_('AP_ENABLE_EMAILPROTECTION_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'enable_gravatar', $groups
					, JText::_('A_RIGHTS_VIEW')
					, JText::_('AP_ENABLE_GRAVATAR')
					, JText::_('AP_ENABLE_GRAVATAR_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_view_email', $groups
					, JText::_('A_RIGHTS_VIEW')
					, JText::_('AP_CAN_VIEW_AUTHOR_EMAIL')
					, JText::_('AP_CAN_VIEW_AUTHOR_EMAIL_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_view_homepage', $groups
					, JText::_('A_RIGHTS_VIEW')
					, JText::_('AP_CAN_VIEW_AUTHOR_HOMEPAGE')
					, JText::_('AP_CAN_VIEW_AUTHOR_HOMEPAGE_DESC')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_view_ip', $groups
					, JText::_('A_RIGHTS_VIEW')
					, JText::_('AP_CAN_VIEW_AUTHOR_IP')
					, JText::_('AP_CAN_VIEW_AUTHOR_IP_DESC')
					, array('Public', 'Registered')
					);


		// Edit
		JCommentsAdmin::loadParam( $permissions, 'can_edit_own', $groups
					, JText::_('A_RIGHTS_EDIT')
					, JText::_('AP_CAN_EDIT_OWN')
					, JText::_('AP_CAN_EDIT_OWN_DESC')
					, array('Public')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_delete_own', $groups
					, JText::_('A_RIGHTS_EDIT')
					, JText::_('AP_CAN_DELETE_OWN')
					, JText::_('AP_CAN_DELETE_OWN_DESC')
					, array('Public')
					);

		// Administration
		JCommentsAdmin::loadParam( $permissions, 'can_edit', $groups
					, JText::_('A_RIGHTS_ADMINISTRATION')
					, JText::_('AP_CAN_EDIT')
					, JText::_('AP_CAN_EDIT_DESC')
					, array('Public', 'Registered')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_edit_for_my_object', $groups
					, JText::_('A_RIGHTS_ADMINISTRATION')
					, JText::_('AP_CAN_EDIT_FOR_MY_OBJECT')
					, JText::_('AP_CAN_EDIT_FOR_MY_OBJECT_DESC')
					, array('Public')
					);

		JCommentsAdmin::loadParam( $permissions, 'can_publish', $groups
					, JText::_('A_RIGHTS_ADMINISTRATION')
					, JText::_('AP_CAN_PUBLISH')
					, JText::_('AP_CAN_PUBLISH_DESC')
					, array('Public', 'Registered')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_publish_for_my_object', $groups
					, JText::_('A_RIGHTS_ADMINISTRATION')
					, JText::_('AP_CAN_PUBLISH_FOR_MY_OBJECT')
					, JText::_('AP_CAN_PUBLISH_FOR_MY_OBJECT_DESC')
					, array('Public')
					);


		JCommentsAdmin::loadParam( $permissions, 'can_delete', $groups
					, JText::_('A_RIGHTS_ADMINISTRATION')
					, JText::_('AP_CAN_DELETE')
					, JText::_('AP_CAN_DELETE_DESC')
					, array('Public', 'Registered')
					);
		JCommentsAdmin::loadParam( $permissions, 'can_delete_for_my_object', $groups
					, JText::_('A_RIGHTS_ADMINISTRATION')
					, JText::_('AP_CAN_DELETE_FOR_MY_OBJECT')
					, JText::_('AP_CAN_DELETE_FOR_MY_OBJECT_DESC')
					, array('Public')
					);

		// Votes
		JCommentsAdmin::loadParam( $permissions, 'can_vote', $groups
					, JText::_('A_RIGHTS_MISC')
					, JText::_('AP_CAN_VOTE')
					, JText::_('AP_CAN_VOTE_DESC')
					);

		JCommentsAdmin::loadParam( $permissions, 'can_report', $groups
					, JText::_('A_RIGHTS_MISC')
					, JText::_('AP_CAN_REPORT')
					, JText::_('AP_CAN_REPORT_DESC')
					, $reportExclude
					, $reportError
					);

		JCommentsAdmin::loadParam( $permissions, 'can_ban', $groups
					, JText::_('A_RIGHTS_MISC')
					, JText::_('AP_CAN_BAN')
					, JText::_('AP_CAN_BAN_DESC')
					, array('Public', 'Registered')
					);

		$lists['groups'] =& $permissions;

		if (JCOMMENTS_JVERSION == '1.0') {
			$lookupQuery = "SELECT c.id AS `value`, CONCAT_WS( ' / ', s.title, c.title) AS `text`"
					. "\n FROM #__sections AS s"
					. "\n INNER JOIN #__categories AS c ON c.section = s.id"
					. "\n WHERE c.id IN ( " . $config->get('enable_categories') . " )"
					. "\n ORDER BY s.title,c.title";
			$categoriesQuery = "SELECT c.id AS `value`, CONCAT_WS( ' / ', s.title, c.title) AS `text`"
					. "\n FROM #__sections AS s"
					. "\n INNER JOIN #__categories AS c ON c.section = s.id"
					. "\n ORDER BY s.title,c.title";
		} elseif (JCOMMENTS_JVERSION == '1.5') {
			$lookupQuery = "SELECT c.id AS `value`, CONCAT_WS( ' / ', s.title, c.title) AS `text`"
					. "\n FROM #__sections AS s"
					. "\n INNER JOIN #__categories AS c ON c.section = s.id"
					. "\n WHERE c.id IN ( " . $config->get('enable_categories') . " )"
					. "\n ORDER BY s.title,c.title";
			$categoriesQuery = "SELECT c.id AS `value`, CONCAT_WS( ' / ', s.title, c.title) AS `text`"
					. "\n FROM #__sections AS s"
					. "\n INNER JOIN #__categories AS c ON c.section = s.id"
					. "\n ORDER BY s.title,c.title";
		} else {
			$lookupQuery = "SELECT c.id AS `value`, c.title AS `text`"
					. "\n FROM #__categories AS c"
					. "\n WHERE c.extension = 'com_content'"
					. "\n AND c.id IN ( " . $config->get('enable_categories') . " )"
					. "\n ORDER BY c.title";
			$categoriesQuery = "SELECT c.id AS `value`, c.title AS `text`, c.level"
					. "\n FROM #__categories AS c"
					. "\n WHERE c.extension = 'com_content'"
					. "\n ORDER BY c.lft, c.title";
		}

		$db->setQuery($categoriesQuery);
		$categories = $db->loadObjectList();

		if (!is_array($categories)) {
			$categories = array();
		} else {
			if (JCOMMENTS_JVERSION == '1.7') {
				for ($i = 0, $n = count($categories); $i < $n; $i++) {
					$repeat = ( $categories[$i]->level - 1 >= 0 ) ? $categories[$i]->level - 1 : 0;
					$categories[$i]->text = str_repeat('- ', $repeat).$categories[$i]->text;
				}
			}
		}

		if ($config->get('enable_categories') != '') {
			$db->setQuery($lookupQuery);
			$lookup = $db->loadObjectList();
		} else {
			$lookup = '';
		}

		$lists['categories'] = JCommentsHTML::selectList($categories, 'cfg_enable_categories[]', 'class="inputbox categories" size="10" multiple="multiple"', 'value', 'text', $lookup);

		$captcha = array();
		$captcha[] = JCommentsHTML::makeOption('kcaptcha', 'KCAPTCHA');

		$config->set('enable_mambots', 1);
		$enginesList = JCommentsEvent::trigger('onJCommentsCaptchaEngines');

		foreach ($enginesList as $engines) {
			foreach ($engines as $code => $text) {
				$captcha[] = JCommentsHTML::makeOption($code, $text);
			}
		}

		$disabledCAPTCHA = count($captcha) == 1 ? ' disabled="disabled"' : '';

		$lists["captcha"] = JCommentsHTML::selectList($captcha, 'cfg_captcha_engine', 'class="inputbox"' . $disabledCAPTCHA, 'value', 'text', $config->get('captcha_engine'));

		HTML_JComments::showSettings($lists);
	}

	public static function loadParam(&$plist, $name, $groups, $pgroup, $label, $note, $exclude = array(), $errorMessage = '')
	{
		$config = JCommentsFactory::getConfig();

		$params = explode(",", $config->get($name));
		$lkeys = array_keys($plist);

		for ($i = 0; $i < count($groups); $i++) {
			$group = $groups[$i]->text;
			$value = 0;

			if (in_array($groups[$i]->id, $params)) {
				$value = 1;
			}

			if (in_array($groups[$i]->name, $exclude)) {
				$value = -1;
			}

			if (!in_array($group, $lkeys)) {
				$plist[$group] = array();
			}

			if (!in_array($pgroup, array_keys($plist[$group]))) {
				$plist[$group][$pgroup] = array();
			}

			$param['name'] = $name;
			$param['label'] = $label;
			$param['note'] = $note;
			$param['value'] = $value;
			$param['group'] = $group;
			$param['groupId'] = $groups[$i]->id;
			$param['error'] = $errorMessage;
			$plist[$group][$pgroup][] = $param;
		}
	}

	public static function getGroupsList( $name, $label, $note, $groups, $params, $exclude = array() ){

		$result['name'] = $name;
		$result['label'] = $label;
		$result['note'] = $note;
		$result['groups'] = array();

		for ( $i=0; $i < count( $groups ); $i++ ) {
			$group = $groups[$i]->value;
			if (in_array( $group, $params ) ) {
				$result['groups'][$group] = 1;
			} else {
				$result['groups'][$group] = 0;
			}
			if (in_array( $group, $exclude ) ) {
				$result['groups'][$group] = -1;
			}
		}
		return $result;
	}

	public static function importCommentsAjax($source, $language, $start = 0)
	{
		$response = JCommentsFactory::getAjaxResponse();

		require_once (dirname(__FILE__) . '/admin.jcomments.migration.php');

		$importer = new JCommentsMigrationTool($source, $language, $start);
		$importer->import();

		if ($importer->getStart() == 0 && $importer->getImported() == 0) {
			// if we couldn't import any items on first step
			$message = JText::_('A_IMPORT_FAILED');
			$response->addScript("jcbackend.showMessage('$message', 'info', 'jcomments-message-".strtolower($source)."',1);");
			$response->addScript("finishCommentsImport('$source');");
		} else {
			if ($importer->getImported() == $importer->getTotal()) {
				$message = JText::sprintf('A_IMPORT_DONE', $importer->getImported());
				$response->addScript("jcbackend.showMessage('$message', 'info', 'jcomments-message-".strtolower($source)."');");
				$response->addScript("finishCommentsImport('$source');");
			} else if ($importer->getImported() < $importer->getTotal()) {
				$imported = $importer->getImported();
				$total = $importer->getTotal();
				$percent = ceil(($imported / $total) * 100);

				$message = JText::sprintf('%s %% (%s from %s)', $percent, $imported, $total);
				$response->addScript("jcbackend.showMessage('$message', 'wait', 'jcomments-message-".strtolower($source)."');");

				$start = $importer->getStart() + $importer->getLimit();
				$response->addScript("JCommentsImportCommentsAJAX('$source', '$language', '$start');");
			}
		}
		return $response;
	}

	public static function removeReportAjax($id)
	{
		$response = JCommentsFactory::getAjaxResponse();
		$db = JCommentsFactory::getDBO();

		$db->setQuery("SELECT commentid FROM #__jcomments_reports WHERE id = " . $id);
		$commentId = $db->loadResult();

		$db->setQuery("DELETE FROM #__jcomments_reports WHERE id = " . $id);
		$db->query();

		$db->setQuery("SELECT COUNT(*) FROM #__jcomments_reports WHERE commentid = " . $commentId);
		$count = $db->loadResult();

		if ($count > 0) {
			$response->addScript("removeReport('".$id."')");
		} else {
			$response->addScript("removeReportList()");
		}

		return $response;
	}

	public static function cancelSettings()
	{
		JCommentsSecurity::checkToken();

		$lang = JCommentsAdmin::loadSettingsByLanguage(JCommentsInput::getVar('lang', ''));
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=settings' . ($lang != '' ? "&lang=$lang" : ''));
	}

	public static function saveSettingsAjax()
	{
		$response = JCommentsFactory::getAjaxResponse();

		$jtx64 = JCommentsInput::getVar('jtx64', '');
		if ($jtx64 != '') {
			$jtx64 = base64_decode(urldecode($jtx64));
			$data = array();
			parse_str($jtx64, $data);

			if (JCOMMENTS_JVERSION == '1.0') {
				require_once (JCOMMENTS_BASE.'/jcomments.ajax.php');
				$data = JCommentsAJAX::convertEncoding($data);
			}

			$_POST = array_merge($_POST, $data);
			$_REQUEST = array_merge($_REQUEST, $data);
		}

		$lang = JCommentsAdmin::loadSettingsByLanguage(JCommentsInput::getVar('lang', ''));
		$message = JCommentsAdmin::saveSettings($lang);
		$response->addScript("jcbackend.showMessage('$message', 'info', 'jcomments-message-holder', 1);");

		return $response;
	}

	public static function saveSettingsDefault()
	{
		$lang = JCommentsAdmin::loadSettingsByLanguage(JCommentsInput::getVar('lang', ''));
		$message = JCommentsAdmin::saveSettings($lang);
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=settings' . ($lang != '' ? "&lang=$lang" : ''), $message);
	}

	public static function loadSettingsByLanguage($language='')
	{
		$lang = '';

		$joomfish = JOOMLATUNE_JPATH_SITE.'/components/com_joomfish/joomfish.php';

		if (is_file($joomfish) || JCommentsMultilingual::isEnabled()) {
			$lang = $language;

			if ($lang == '') {
			 	$lang = JCommentsMultilingual::getLanguage();
			}

			// reload configuration
			JCommentsFactory::getConfig($lang);
		}
		return $lang;
	}

	public static function restoreSettingsAjax()
	{
		$response = JCommentsFactory::getAjaxResponse();
		$message = JCommentsAdmin::restoreSettings();
		$response->addScript("jcbackend.showMessage('$message', 'info', 'jcomments-message-holder', 1);");
		return $response;
	}

	public static function restoreSettingsDefault()
	{
		$lang = JCommentsInput::getVar('lang', '');
		$message = JCommentsAdmin::restoreSettings();
		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=settings' . ($lang != '' ? "&lang=$lang" : ''), $message);
	}

	public static function restoreSettings()
	{
		JCommentsSecurity::checkToken();

		$db = JCommentsFactory::getDBO();
		$db->setQuery("DELETE FROM `#__jcomments_settings`");
		$db->query();

		require_once (dirname(__FILE__).'/admin.jcomments.installer.php');

		$defaultSettings = dirname(__FILE__).'/install/sql/settings.sql';
		JCommentsInstaller::executeSQL($defaultSettings);

		require_once (dirname(__FILE__).'/install/helpers/installer.php');
		JCommentsInstallerHelper::fixUsergroups();

		return JText::_('A_SETTINGS_RESTORED');
	}

	public static function saveSettings($lang)
	{
		JCommentsSecurity::checkToken();

		$app = JCommentsFactory::getApplication('administrator');
		$db = JCommentsFactory::getDBO();
		$config = JCommentsFactory::getConfig();

		require_once (JCOMMENTS_HELPERS.'/user.php');
		$groups = JCommentsUserHelper::getUserGroups();

		$c_params = $config->getKeys();
		$p_params = array_keys($_POST);
		$i_params = array('smiles', 'smiles_path', 'enable_geshi');

		foreach ($c_params as $param) {
			if ((!in_array('cfg_' . $param, $p_params)) && (!in_array($param, $i_params))) {
				$_POST['cfg_' . $param] = '';
			}
		}

		$db->setQuery("SELECT name FROM #__jcomments_settings WHERE component=''" . ($lang != '' ? " AND lang ='$lang'" : ''));
		$dbParams = $db->loadResultArray();

		$query = 'SELECT * FROM #__jcomments_settings WHERE name IN ("' . implode('", "', $i_params) . '")';
		$db->setQuery($query);
		$systemVars = $db->loadObjectList('name');

		foreach ($i_params as $p) {
			if (!in_array($p, $dbParams)) {
				if (isset($systemVars[$p])) {
					$_POST['cfg_' . $p] = $systemVars[$p]->value;
				}
			}
		}

		if (!isset($_POST['cfg_comment_minlength'])) {
			$_POST['cfg_comment_minlength'] = 0;
		}

		if (!isset($_POST['cfg_comment_maxlength'])) {
			$_POST['cfg_comment_maxlength'] = 0;
		}

		if ($_POST['cfg_comment_minlength'] > $_POST['cfg_comment_maxlength']) {
			$_POST['cfg_comment_minlength'] = 0;
		}

		foreach ($_POST as $k=>$v) {
			if (strpos( $k, 'cfg_' ) === 0 ) {
				$paramName = substr($k, 4);
				if (($paramName == 'smile_codes') || ($paramName == 'smile_images')) {
					continue;
				}

				if (is_array($v)) {
					$config->set($paramName, '');

					foreach ($groups as $group) {
						if (strpos($config->get($paramName), $group->id) !== false) {
							$v[] = $group->id;
						}
					}
					$v = implode(',', $v);
				}

				// handle magic quotes compatibility
				if (get_magic_quotes_gpc() == 1) {
					$v = stripslashes($v);
				}

				if ($paramName == 'forbidden_names') {
					$v = preg_replace("#[\n|\r]+#", ',', $v);
					$v = preg_replace("#,+#", ',', $v);
				} else if ($paramName == 'badwords') {
					$v = preg_replace('#[\s|\,]+#i', "\n", $v);
					$v = preg_replace('#[\n|\r]+#i', "\n", $v);
				}

				$v = trim($v);
				$config->set($paramName, $v);

				if (in_array($paramName, $dbParams)) {
					$query = "UPDATE #__jcomments_settings"
						. "\n SET `value` = '" . $db->getEscaped($v) . "'"
						. "\n WHERE `name` = '" . $db->getEscaped($paramName) . "'"
						. ($lang != '' ? " AND `lang` = '$lang'" : '' )
						;
				} else {
					$query = "INSERT INTO #__jcomments_settings"
						. "\n SET `value` = '" . $db->getEscaped($v) . "'"
						. "\n , `name` = '" . $db->getEscaped($paramName) . "'"
						. ($lang != '' ? " , `lang` = '$lang'" : '' )
						;
				}

				$db->setQuery($query);
				$db->query();
			}
		}

		if ($config->get('smiles_path') == '') {
			$smilesPath = '/components/com_jcomments/images/smiles/';
			$config->set('smiles_path', $smilesPath);

			$query = "UPDATE #__jcomments_settings"
				. "\n SET `value` = '" . $db->getEscaped($smilesPath) . "'"
				. "\n WHERE `name` = 'smiles_path'"
				. ($lang != '' ? " AND `lang` = '$lang'" : '' )
				;
			$db->setQuery($query);
			$db->query();
		}

		$message = JText::_('A_SETTINGS_SAVED');

		// clean all caches for components with comments
		if ($app->getCfg('caching') == 1) {
			$db->setQuery("SELECT DISTINCT(object_group) AS name FROM #__jcomments");
			$rows = $db->loadObjectList();

			foreach ($rows as $row) {
				$cache = JCommentsFactory::getCache($row->name);
				$cache->clean();
			}
			unset($rows);
		}

		$cache = JCommentsFactory::getCache('com_jcomments');
		$cache->clean();

		return $message;
	}

	public static function showSmiles()
	{
		$app = JCommentsFactory::getApplication('administrator');
		$config = JCommentsFactory::getConfig();

		require_once (JCOMMENTS_LIBRARIES . '/joomlatune/filesystem.php');
		$smilesPath = $config->get('smiles_path', 'components' . DS . 'com_jcomments' . DS . 'images' . DS . 'smiles' . DS);
		$smilesAbsPath = $app->getCfg('absolute_path') . DS . $smilesPath;
		$smilesAbsPath = str_replace(DS.DS, DS, $smilesAbsPath);

		$imageFiles = JoomlaTuneFS::readDirectory($smilesAbsPath);

		$lists['images'] = array();
		foreach ($imageFiles as $file) {
			if (preg_match("/(gif|jpg|png)/i", (string) $file)) {
				$lists['images'][] = $file;
			}
		}

		$config = JCommentsFactory::getConfig();
		$lists['smiles'] = $config->get('smiles');
		$lists['smiles_path'] = $app->getCfg('live_site') . str_replace('//', '/', str_replace(DS, '/', $smilesPath). '/');

		HTML_JComments::showSmiles($lists);
	}

	public static function saveSmiles()
	{
		JCommentsSecurity::checkToken();

		$app = JCommentsFactory::getApplication('administrator');
		$db = JCommentsFactory::getDBO();

		$smileCodes = JCommentsInput::getVar('cfg_smile_codes', array());
		$smileImages = JCommentsInput::getVar('cfg_smile_images', array());
		$smilesValues = array();

		foreach ($smileCodes as $k => $code) {
			$image = trim($smileImages[$k]);
			$code = trim($code);

			if ($code != '' && $image != '') {
				$smilesValues[] = $code . "\t" . $image;
			}
		}

		$values = count($smilesValues) ? implode("\n", $smilesValues) : '';

		$db->setQuery("SELECT name FROM #__jcomments_settings WHERE component=''");
		$dbParams = $db->loadResultArray();

		if (in_array('smiles', $dbParams)) {
			$query = "UPDATE #__jcomments_settings SET `value` = " . $db->Quote($values) . " WHERE `name` = 'smiles'";
		} else {
			$query = "INSERT INTO #__jcomments_settings SET `value` = " . $db->Quote($values) . ", `name` = 'smiles'";
		}
		$db->setQuery($query);
		$db->query();

		$message = JText::_('A_SETTINGS_SAVED');

		// Clean all caches for components with comments
		if ($app->getCfg('caching') == 1) {
			$db->setQuery("SELECT DISTINCT(object_group) AS name FROM #__jcomments");
			$rows = $db->loadObjectList();

			foreach ($rows as $row) {
				$cache = JCommentsFactory::getCache($row->name);
				$cache->clean();
			}
			unset($rows);
		}
		
		$cache = JCommentsFactory::getCache('com_jcomments');
		$cache->clean();

		JCommentsRedirect(JCOMMENTS_INDEX . '?option=com_jcomments&task=smiles', $message);
	}

	public static function showAbout()
	{
		HTML_JComments::showAbout();
	}

	public static function checkPhpVersion()
	{
		// check PHP version (we will stop supporting PHP4 in nearest future)
		if ((version_compare(phpversion(), '5.1.0') < 0)) {
			if (JCOMMENTS_JVERSION != '1.0') {
				JError::raiseWarning(500, JText::sprintf('A_WARNINGS_PHP_VERSION', phpversion()));
			}
		}
	}

	public static function getPagination($total, $limitstart, $limit)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$app = JCommentsFactory::getApplication();
			require_once ($app->getCfg('absolute_path').'/administrator/includes/pageNavigation.php');
			$pagination = new mosPageNav($total, $limitstart, $limit);
		} else {
			jimport('joomla.html.pagination');
			$pagination = new JPagination( $total, $limitstart, $limit );
		}
		return $pagination;
	}

	public static function refreshObjects()
	{
		HTML_JComments::refreshObjects();
	}
}
?>