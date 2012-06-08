<?php
/**
 * JComments plugin for Bookmarks component objects support
 *
 * @version 1.4
 * @package JComments
 * @author tumtum (tumtum@mail.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_bookmarks extends JCommentsPlugin
{
	function getObjectTitle( $id )
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( "SELECT title FROM #__bookmarks WHERE id='$id'");
		return $db->loadResult();
	}
 
	function getObjectLink( $id )
	{
		$_Itemid = self::getItemid( 'com_bookmarks' );
		$link = JoomlaTuneRoute::_('index.php?option=com_bookmarks&Itemid='. $_Itemid.'&task=detail&navstart=0&mode=0&id='. $id .'&search=*');
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT created_by FROM #__bookmarks WHERE id = ' . $id );
		$userid = $db->loadResult();
		return $userid;
	}
}
?>