<?php
/**
 * JComments plugin for DJ Classifieds objects support (http://design-joomla.ru)
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2011 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_djclassifieds extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$db = JFactory::getDBO();
		$db->setQuery('SELECT id, cat_id, user_id, name FROM #__djcf_items WHERE id = '.$id);
		$row = $db->loadObject();

		$info = new JCommentsObjectInfo();

		if (!empty($row)) {
			$Itemid = self::getItemid('com_djclassifieds', 'index.php?option=com_djclassifieds&view=show&cid=0');
			$Itemid = $Itemid > 0 ? '&Itemid='.$Itemid : '';

			$info->title = $row->name;
			$info->userid = $row->user_id;
			$info->link = JRoute::_('index.php?option=com_djcatalog&amp;view=showitem&amp;cid='.$row->cat_id.'&amp;id='.$row->id.$Itemid);
		}

		return $info;
	}
}
?>