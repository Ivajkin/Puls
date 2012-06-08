<?php
/**
 * JComments plugin for HekimaBlog objects support
 *
 * @version 2.3
 * @package JComments
 * @author Irina Popova (irina@psytronica.ru)
 * @copyright (C) 2011 by Irina Popova (http://psytronica.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
 
class jc_com_hekimablog extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$db = JFactory::getDBO();
		$query = "SELECT i.id, i.title, i.created_by, i.access"
			. " , CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(':', i.id, i.alias) ELSE i.id END as alias"
			. " FROM #__content as i"
			. " WHERE i.id = " . $id;
		$db->setQuery($query);
		$row = $db->loadObject();

		$info = new JCommentsObjectInfo();

		if (!empty($row)) {
			$Itemid = self::getItemid('com_hekimablog');
			$Itemid = $Itemid > 0 ? '&Itemid='.$Itemid : '';

			$info->title = $row->title;
			$info->access = $row->access;
			$info->userid = $row->created_by;
			$info->link = JRoute::_('index.php?option=com_hekimablog&article='.$row->alias.$Itemid);
		}

		return $info;
	}
}
?>