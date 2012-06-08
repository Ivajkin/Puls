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
 
class jc_com_hekimablog_users extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$db = JFactory::getDBO();
		$query = "SELECT i.id, i.name, i.username"
			. " , CONCAT_WS(':', i.id, i.username) as alias"
			. " FROM #__users as i"
			. " WHERE i.id = " . $id;
		$db->setQuery($query);
		$row = $db->loadObject();

		$info = new JCommentsObjectInfo();

		if (!empty($row)) {
			$Itemid = self::getItemid('com_hekimablog');
			$Itemid = $Itemid > 0 ? '&Itemid='.$Itemid : '';

			$info->title = $row->name;
			$info->userid = $row->id;
			$info->link = JRoute::_('index.php?option=com_hekimablog&view=profile&user='.$row->alias.$Itemid);
		}

		return $info;
	}
}
?>