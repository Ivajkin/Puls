<?php
/**
 * JComments plugin for Joomla com_poll component
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_poll extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$query = "SELECT id, title, '' as alias, access FROM #__polls WHERE id = " . $id;
		} else {
			$query = "SELECT id, title, alias, access FROM #__polls WHERE id = " . $id;
		}

		$db = JCommentsFactory::getDBO();
		$db->setQuery($query);
		$row = $db->loadObject();

		$info = new JCommentsObjectInfo();

		if (!empty($row)) {
			$_Itemid = self::getItemid('com_poll');

			$info->title = $row->title;
			$info->access = $row->access;

			if (JCOMMENTS_JVERSION == '1.0') {
				$link = sefRelToAbs( 'index.php?option=com_poll&amp;task=results&amp;id=' . $id . '&amp;Itemid=' . $_Itemid );
			} else {
				$_Itemid = $_Itemid > 0 ? '&Itemid=' . $_Itemid : '';
				$link = JRoute::_('index.php?option=com_poll&id='. $id . ':' . $row->alias . $_Itemid);
			}

			$info->link = $link;
		}

		return $info;
	}
}