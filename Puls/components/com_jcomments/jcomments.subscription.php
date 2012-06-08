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

class JCommentsSubscriptionManager
{
	/**
	 * An array of errors
	 *
	 * @var	array of error messages
	 */
	var $_errors = null;

	function JCommentsSubscriptionManager()
	{
		$this->_errors = array();
	}

	/**
	 * Returns a reference to a subscription manager object,
	 * only creating it if it doesn't already exist.
	 *
	 * @return JCommentsSubscriptionManager	A JCommentsSubscriptionManager object
	 */
	public static function getInstance()
	{
		static $instance = null;

		if (!is_object($instance)) {
			$instance = new JCommentsSubscriptionManager();
		}
		return $instance;
	}

	/**
	 * Subscribes user for new comments notifications for an object
	 *
	 * @param int $object_id	The object identifier
	 * @param string $object_group	The object group (component name)
	 * @param int $userid	The registered user identifier
	 * @param string $email	The user email (for guests only)
	 * @param string $name The user name (for guests only)
	 * @param string $lang The user language
	 * @return boolean True on success, false otherwise.
	 */
	function subscribe($object_id, $object_group, $userid, $email = '', $name = '', $lang = '')
	{
		$object_id = (int) $object_id;
		$object_group = trim($object_group);
		$userid = (int) $userid;
		$result = false;

		if ($lang == '') {
			$lang = JCommentsMultilingual::getLanguage();
		}

		$db = JCommentsFactory::getDBO();

		if ($userid != 0) {
			$user = JCommentsFactory::getUser($userid);
			$name = $user->name;
			$email = $user->email;
			unset($user);
		}

		$query = "SELECT * "
				. " FROM #__jcomments_subscriptions"
				. " WHERE object_id = " . (int) $object_id
				. " AND object_group = " . $db->Quote($object_group)
				. " AND email = " . $db->Quote($email)
				. (JCommentsMultilingual::isEnabled() ? " AND lang = " . $db->Quote($lang) : "");

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		require_once (JCOMMENTS_TABLES.'/subscription.php');

		if (count($rows) == 0) {
			$subscription = new JCommentsTableSubscription($db);
			$subscription->object_id = $object_id;
			$subscription->object_group = $object_group;
			$subscription->name = $name;
			$subscription->email = $email;
			$subscription->userid = $userid;
			$subscription->lang = $lang;
			$subscription->published = 1;
			$subscription->store();
			$result = true;
		} else {
			// if current user is registered, but already exists subscription
			// on same email by guest - update subscription data
			if ($userid > 0 && $rows[0]->userid == 0) {
				$subscription = new JCommentsTableSubscription($db);
				$subscription->id = $rows[0]->id;
				$subscription->userid = $userid;
				$subscription->lang = $lang;
				$subscription->store();
				$result = true;
			} else {
				$this->_errors[] = JText::_('ERROR_ALREADY_SUBSCRIBED');
			}
		}

		if ($result) {
			$cache = JCommentsFactory::getCache('com_jcomments_subscriptions_'.strtolower($object_group));
			$cache->clean();
		}

		return $result;
	}

	/**
	 * Unsubscribe guest from new comments notifications by subscription hash
	 *
	 * @param string $hash	The secret hash value of subscription
	 * @return boolean True on success, false otherwise.
	 */
	function unsubscribeByHash($hash)
	{
		if (!empty($hash)) {
			$db = JCommentsFactory::getDBO();
			$db->setQuery('SELECT `object_group` FROM `#__jcomments_subscriptions` WHERE `hash` = ' . $db->Quote($hash));
			$object_group = $db->loadResult();

			if (!empty($object_group)) {
				$db->setQuery("DELETE FROM `#__jcomments_subscriptions` WHERE `hash` = " . $db->Quote($hash));
				$db->query();

				$cache = JCommentsFactory::getCache('com_jcomments_subscriptions_'.strtolower($object_group));
				$cache->clean();
	
				return true;
			}
		}
		return false;
	}

	/**
	 * Unsubscribe registered user from new comments notifications for an object
	 *
	 * @param int $object_id	The object identifier
	 * @param string $object_group	The object group (component name)
	 * @param int $userid	The registered user identifier
	 * @return boolean True on success, false otherwise.
	 */
	function unsubscribe($object_id, $object_group, $userid)
	{
		if ($userid != 0) {
			$db = JCommentsFactory::getDBO();

			$query = "DELETE"
					. " FROM #__jcomments_subscriptions"
					. " WHERE object_id = " . (int) $object_id
					. " AND object_group = " . $db->Quote($object_group)
					. " AND userid = " . (int) $userid
					. (JCommentsMultilingual::isEnabled() ? " AND lang = '" . JCommentsMultilingual::getLanguage() . "'" : "");

			$db->setQuery($query);
			$db->query();

			$cache = JCommentsFactory::getCache('com_jcomments_subscriptions_'.strtolower($object_group));
			$cache->clean();

			return true;
		}
		return false;
	}

	/**
	 * Checks if given user is subscribed to new comments notifications for an object
	 *
	 * @param int $object_id	The object identifier
	 * @param string $object_group	The object group (component name)
	 * @param int $userid	The registered user identifier
	 * @param string $email	The user email (for guests only)
	 * @param string $language	The object language
	 * @return int
	 */
	function isSubscribed($object_id, $object_group, $userid, $email = '', $language = '')
	{
		static $data = null;

		$key = $object_id . $object_group . $userid . $email . $language;

		if (!isset($data[$key])) {
			$cache = JCommentsFactory::getCache('com_jcomments_subscriptions_'.strtolower($object_group), 'callback');
			$data[$key] = $cache->get(array($this, '_isSubscribed'), array($object_id, $object_group, $userid, $email, $language));
		}

		return $data[$key];
	}

	/**
	 * Return an array of errors messages
	 *
	 * @return Array The array of error messages
	 */
	function getErrors()
	{
		return $this->_errors;
	}

	function _isSubscribed($object_id, $object_group, $userid, $email = '', $language = '')
	{
		if (empty($language)) {
			$language = JCommentsMultilingual::getLanguage();
		}
	
		$db = JCommentsFactory::getDBO();

		$query = "SELECT COUNT(*) "
				. " FROM #__jcomments_subscriptions"
				. " WHERE object_id = " . (int) $object_id
				. " AND object_group = " . $db->Quote($object_group)
				. " AND userid = " . (int) $userid
				. (($userid == 0) ? " AND email = " . $db->Quote($email) : '')
				. (JCommentsMultilingual::isEnabled() ? " AND lang = " . $db->Quote($language) : "");
		$db->setQuery($query);
		$cnt = $db->loadResult();
		return ($cnt > 0 ? 1 : 0);
	}
}
?>