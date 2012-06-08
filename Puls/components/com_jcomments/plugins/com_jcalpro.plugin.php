<?php
/**
 * JComments plugin for JCalPRO events support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_jcalpro extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT title, id FROM #__jcalpro2_events WHERE extid = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$_Itemid = self::getItemid( 'com_jcalpro' );
		$link = JoomlaTuneRoute::_('index.php?option=com_jcalpro&amp;extmode=view&amp;extid=' . $id . '&amp;Itemid=' . $_Itemid);
		return $link;
	}
}
?>