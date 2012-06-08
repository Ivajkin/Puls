<?php
/**
 * JComments plugin for GroupJive (http://www.groupjive.org/) support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_groupjive extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT subject FROM #__gj_bul WHERE id = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$_Itemid = self::getItemid( 'com_groupjive' );
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT group_id FROM #__gj_bul WHERE id = ' . $id );
		$gid = $db->loadResult();
		$link = JoomlaTuneRoute::_('index.php?option=com_groupjive&amp;task=showfullmessage&amp;idm=' . $id . '&amp;groupid=' . $gid . '&amp;Itemid=' . $_Itemid);
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT author_id FROM #__gj_bul WHERE id = ' . $id );
		$userid = $db->loadResult();
		
		return $userid;
	}
}
?>