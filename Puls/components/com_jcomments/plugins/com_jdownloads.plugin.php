<?php
/**
 * JComments plugin for JDownloads objects support
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_jdownloads extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$db = JFactory::getDBO();
		$query = "SELECT file_id as id, file_title as title, submitted_by as owner"
			. " FROM #__jdownloads_files"
			. " WHERE file_id = " . $id;
		$db->setQuery($query);
		$row = $db->loadObject();

		$info = new JCommentsObjectInfo();

		if (!empty($row)) {
			$Itemid = self::getItemid('com_jdownloads');
			$Itemid = $Itemid > 0 ? '&amp;Itemid='.$Itemid : '';

			$info->title = $row->title;
			$info->userid = $row->owner;
			$info->link = JRoute::_('index.php?option=com_jdownloads&amp;task=view.download&amp;cid='.$id.$Itemid);
		}

		return $info;
	}
}
?>