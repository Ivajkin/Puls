<?php
/**
 * JComments - Joomla Comment System
 *
 * User plugin for updating user info in comments
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

class plgUserJComments extends JPlugin
{
	function plgUserJComments(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onUserAfterSave($user, $isNew, $success, $msg)
	{
		if ($success && !$isNew) {
			$id = (int) $user['id'];

			if ($id > 0 && trim($user['username']) != '' && trim($user['email']) != '') {
				$db = JFactory::getDBO();

				// update name, username and email in comments
				$query = "UPDATE #__jcomments"
					. " SET name = " . $db->Quote($user['name'])
					. " , username = " . $db->Quote($user['username'])
					. " , email = " . $db->Quote($user['email'])
					. " WHERE userid = " . $id
					;

				$db->setQuery($query);
				$db->query();

				// update email in subscriptions
				$query = "UPDATE #__jcomments_subscriptions"
					. " SET email = " . $db->Quote($user['email'])
					. " WHERE userid = " . $id
					;

				$db->setQuery($query);
				$db->query();
			}
		}
	}

	function onUserAfterDelete($user, $success, $msg)
	{
		if ($success) {
			$id = (int) $user['id'];

			if ($id > 0) {
				$db = JFactory::getDBO();

				$db->setQuery('UPDATE #__jcomments SET userid = 0 WHERE userid = ' . $id);
				$db->query();

				$db->setQuery('DELETE FROM #__jcomments_reports WHERE userid = ' . $id);
				$db->query();

				$db->setQuery('DELETE FROM #__jcomments_subscriptions WHERE userid = ' . $id);
				$db->query();

				$db->setQuery('DELETE FROM #__jcomments_votes WHERE userid = ' . $id);
				$db->query();
			}
		}
	}

	function onAfterStoreUser($user, $isNew, $success, $msg)
	{
		$this->onUserAfterSave($user, $isNew, $success, $msg);
	}

	function onAfterDeleteUser($user, $success, $msg)
	{
		$this->onUserAfterDelete($user, $success, $msg);
	}
}