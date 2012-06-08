<?php
/**
 * JComments plugin for Azrul MyBlog support
 *
 * @version 2.0
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_myblog extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT title, id FROM #__content WHERE id=' . $id);
		return $db->loadResult();
	}
 
	function getObjectLink($id)
	{
	        $app = JCommentsFactory::getApplication();
	        $myBlogFunctions = $app->getCfg('absolute_path').DS.'components'.DS.'com_myblog'.DS.'functions.myblog.php';
		if (is_file($myBlogFunctions)) {
			require_once($myBlogFunctions);
			$_Itemid = myGetItemId();
		} else {
			$_Itemid = self::getItemid('com_myblog');
		}

		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT permalink FROM #__myblog_permalinks WHERE contentid=' . $id);
		$permalink = $db->loadResult();
		$link = JoomlaTuneRoute::_('index.php?option=com_myblog&show=' . $permalink . '&Itemid=' . $_Itemid);
		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT created_by, id FROM #__content WHERE id = ' . $id);
		$userid = $db->loadResult();
		
		return $userid;
	}
}
?>