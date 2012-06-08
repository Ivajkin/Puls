<?php
/**
 * JComments plugin for PonyGallery objects support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_ponygallery extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT imgtitle, id FROM #__ponygallery WHERE id = ' . $id);
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$_Itemid = self::getItemid('com_ponygallery');
		$link = sefRelToAbs( 'index.php?option=com_ponygallery&amp;func=detail&amp;id=' . $id . '&amp;Itemid=' . $_Itemid );
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT u.id "
			. "\n FROM #__users AS u"
			. "\n INNER JOIN #__ponygallery AS pg ON pd.owner = u.username"
			. "\n WHERE pg.id = " . $id
			;
			
		$db->setQuery( $query );
		$userid = $db->loadResult();
		
		return intval( $userid );
	}
}
?>