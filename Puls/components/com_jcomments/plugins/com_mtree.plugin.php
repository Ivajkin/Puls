<?php
/**
 * JComments plugin for Mosets tree support
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_mtree extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$info = new JCommentsObjectInfo();
		$row = null;

		if (JCOMMENTS_JVERSION == '1.0') {
			$db = JCommentsFactory::getDBO();
			$db->setQuery('SELECT link_id, link_name, user_id FROM #__mt_links WHERE link_id = ' . $id);
			$db->loadObject($row);
		} else {
			$db = JFactory::getDBO();
			$db->setQuery('SELECT link_id, link_name, user_id FROM #__mt_links WHERE link_id = ' . $id);
			$row = $db->loadObject();
		}

		if (!empty($row)) {
			$Itemid = self::getItemid('com_mtree');
			$Itemid = $Itemid > 0 ? '&Itemid=' . $Itemid : '';

			$info->title = $row->link_name;
			$info->userid = $row->user_id;

			if (JCOMMENTS_JVERSION == '1.0') {
				$info->link = sefRelToAbs('index.php?option=com_mtree&amp;task=viewlink&amp;link_id=' . $id . $Itemid);
			} else {
				$info->link = JRoute::_('index.php?option=com_mtree&amp;task=viewlink&amp;link_id=' . $id . $Itemid);
			}
		}

		return $info;
	}
}
?>