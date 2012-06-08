<?php
/**
 * JComments plugin for Joomla com_weblinks component
 *
 * @version 1.4, based on the one for com_poll by Sergey M. Litvinov
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru), Tommy Nilsson, tommy@architechtsoftomorrow.com
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru), 2011 Tommy Nilsson www.architechtsoftomorrow.com
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_weblinks extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT title, id FROM #__categories WHERE section = "com_weblinks" and id = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$_Itemid = self::getItemid( 'com_weblinks' );
			$link = sefRelToAbs( 'index.php?option=com_weblinks&amp;view=category&amp;id=' . $id . '&amp;Itemid=' . $_Itemid );
		} else {
			$db = JFactory::getDBO();
			$db->setQuery( 'SELECT alias FROM #__categories WHERE section = "com_weblinks" and id = ' . $id );
			$alias = $db->loadResult();
			
			$link = 'index.php?option=com_weblinks&view=category&id='. $id.':'.$alias;

			require_once(JPATH_SITE.DS.'includes'.DS.'application.php');

			$component = JComponentHelper::getComponent('com_weblinks');
			$menus = JApplication::getMenu('site');
			$items = $menus->getItems('componentid', $component->id);

			if (count($items)) {
				$link .= "&Itemid=" . $items[0]->id;
			}

			$link = JRoute::_($link);
		}
		return $link;
	}
}
?>