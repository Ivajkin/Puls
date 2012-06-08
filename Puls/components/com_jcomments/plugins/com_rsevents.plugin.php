<?php
/**
 * JComments plugin for RSEvents! objects support
 *
 * @version 1.0
 * @package JComments
 * @author Oregon
 * @copyright (C) 2011 by Oregon
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_rsevents extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT title, id FROM #__rsevents WHERE id='$id'");
		return $db->loadResult();
	}
 
	function getObjectLink($id)
	{
		$_Itemid = self::getItemid('com_rsevents');
		$link = JRoute::_('index.php?option=com_rsevents&view=events&layout=show&cid='. $id .'&Itemid='. $_Itemid);
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT created_by FROM #__rsevents WHERE id='$id'");
		return $db->loadResult();
	}
}
?>