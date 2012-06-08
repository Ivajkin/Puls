<?php
/**
 * JComments plugin for EasyCalendar events support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_easycalendar extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT name FROM #__easycalendar WHERE id = ' . $id );
		return $db->loadResult();
	}

	function getObjectLink($id)
	{
		$_Itemid = self::getItemid( 'com_easycalendar' );
		$link = JoomlaTuneRoute::_( 'index.php?option=com_easycalendar&amp;task=view&amp;id=' . $id . '&amp;Itemid=' . $_Itemid );
		return $link;
	}
}
?>