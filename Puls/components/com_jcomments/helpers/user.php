<?php
/**
 * JComments - Joomla Comment System
 *
 * Service functions for JComments system plugin
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

/**
 * JComments User Helper
 */
class JCommentsUserHelper
{
	public static function getUserGroups()
	{
		static $groups = array();
		
		if (!count($groups)) {

			if (JCOMMENTS_JVERSION == '1.0') {

				$db = JCommentsFactory::getDbo();
				$db->setQuery('SELECT a.name, a.name AS text, a.group_id as id, COUNT(DISTINCT b.group_id) AS level' 
						. ' FROM #__core_acl_aro_groups AS a'
						. ' LEFT JOIN `#__core_acl_aro_groups` AS b ON a.lft > b.lft AND a.rgt < b.rgt'
						. ' WHERE a.name NOT IN ("ROOT", "USERS", "Public Backend")'
						. ' GROUP BY a.group_id'
						. ' ORDER BY a.lft ASC');
				$groups = $db->loadObjectList();

				// for backward compatibility
				self::updateUserGroups($groups);

			} else if (JCOMMENTS_JVERSION == '1.5') {

				$db = JFactory::getDbo();
				$db->setQuery('SELECT a.name, a.name AS text, a.id, COUNT(DISTINCT b.id) AS level' 
						. ' FROM #__core_acl_aro_groups AS a'
						. ' LEFT JOIN `#__core_acl_aro_groups` AS b ON a.lft > b.lft AND a.rgt < b.rgt'
						. ' WHERE a.name NOT IN ("ROOT", "USERS", "Public Backend")'
						. ' GROUP BY a.id'
						. ' ORDER BY a.lft ASC');
				$groups = $db->loadObjectList();

				// for backward compatibility
				self::updateUserGroups($groups);
		
			} else if (JCOMMENTS_JVERSION == '1.7') {

				$db = JFactory::getDbo();
				$db->setQuery('SELECT CASE WHEN a.id = 1 THEN \'Public\' ELSE a.title END AS name, a.title AS text, a.id, COUNT(DISTINCT b.id) AS level' 
						. ' FROM #__usergroups AS a'
						. ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt'
						. ' GROUP BY a.id'
						. ' ORDER BY a.lft ASC');
				$groups = $db->loadObjectList();
			}
		}

		return $groups;
	}

	protected function updateUserGroups(&$groups)
	{
		if (is_array($groups)) {
			foreach ($groups as &$group) {
				if ($group->text == 'Public Frontend') {
					$group->name = 'Public';
					$group->text = 'Unregistered';
				} else if ($group->text == 'Super Administrator') {
					$group->name = 'Super Users';
				}
				$group->level = $group->level - 2;
			}
		}
	}
}
?>
