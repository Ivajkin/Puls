<?php
/**
 * JComments plugin for Music Collection support
 *
 * @version 2.1
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_muscol_song extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT name, id FROM #__muscol_songs WHERE id = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$link = 'index.php?option=com_muscol&amp;view=song&amp;id=' . $id;
		$_Itemid = self::getItemid('com_muscol');
		$link .= ($_Itemid > 0) ? ('&Itemid=' . $_Itemid) : '';
		$link = JRoute::_( $link );
		return $link;
	}
}
?>