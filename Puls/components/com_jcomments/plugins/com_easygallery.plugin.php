<?php
/**
 * JComments plugin for EasyGallery photo objects support
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_easygallery extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$info = new JCommentsObjectInfo();

		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT id, name FROM #__easygallery WHERE id = '.$id);
		$row = $db->loadObject();

		if (!empty($row)) {
			$_Itemid = self::getItemid('com_easygallery');

			$info->title = $row->name;
			$info->link = JoomlaTuneRoute::_('index.php?option=com_easygallery&amp;act=photos&amp;cid=' . $id . '&amp;Itemid=' . $_Itemid);
		}

		return $info;
	}
}
?>