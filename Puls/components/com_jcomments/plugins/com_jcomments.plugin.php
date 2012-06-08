<?php
/**
 * JComments plugin for JComments ;)
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_jcomments extends JCommentsPlugin
{
	function getObjectInfo($id, $language)
	{
		$info = new JCommentsObjectInfo();

	        $menu = self::getMenuItem($id);

	        if ($menu != '') {
	        	if (JCOMMENTS_JVERSION == '1.0') {
				$params = new mosParameters($menu->params);
	        	} elseif (JCOMMENTS_JVERSION == '1.5') {
				$params = new JParameter($menu->params);
			} else {
				$params = new JRegistry($menu->params);
			}

			$info->title = $params->get('page_title') ? $params->get('page_title') : $menu->title;
			$info->access = $menu->access;
			$info->link = JoomlaTuneRoute::_('index.php?option=com_jcomments&amp;Itemid='.$menu->id);
			$info->userid = 0;
	        }

		return $info;
	}

	protected static function getMenuItem($id)
	{
		$db = JCommentsFactory::getDBO();

		if (JCOMMENTS_JVERSION == '1.7') {
			$query = "SELECT m.*"
				. " FROM `#__menu` AS m"
				. " JOIN `#__extensions` AS e ON m.component_id = e.extension_id"
				. " WHERE m.type = 'component'"
				. " AND e.element = 'com_jcomments'"
				. " AND m.published = 1"
				. " AND m.parent_id > 0"
				. " AND m.client_id = 0"
				. " AND m.params LIKE '%\"object_id\":\"" . $id . "\"%'"
				;			
		} else {
			$query = "SELECT m.*"
				. " FROM `#__menu` AS m"
				. " JOIN `#__components` AS c ON c.id = m.componentid"
				. " WHERE m.type = 'component'"
				. " AND m.published = 1"
				. " AND c.option = 'com_jcomments'"
				. " AND c.parent = 0"
				. " AND m.params LIKE '%object_id=" . $id . "%'"
				;			
		}

		$db->setQuery($query, 0, 1);
		$menus = $db->loadObjectList();

		return count($menus) ? $menus[0] : null;
	}
}
?>