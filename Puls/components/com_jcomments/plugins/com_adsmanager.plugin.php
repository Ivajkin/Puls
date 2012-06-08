<?php
/**
 * JComments plugin for AdsManager ads objects support
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_adsmanager extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$info = new JCommentsObjectInfo();
	        $row = null; 

		if (JCOMMENTS_JVERSION == '1.0') {
			$db = JCommentsFactory::getDBO();
			$db->setQuery('SELECT id, ad_headline, userid, category FROM #__adsmanager_ads WHERE id = ' . $id);
			$db->loadObject($row);
		} else {
			$db = JFactory::getDBO();
			$db->setQuery('SELECT id, ad_headline, userid, category FROM #__adsmanager_ads WHERE id = ' . $id);
			$row = $db->loadObject();
		}

		if (!empty($row)) {
			$info->title = $row->ad_headline;
			$info->userid = $row->userid;

			if (JCOMMENTS_JVERSION == '1.0') {
				$Itemid = self::getItemid('com_adsmanager');
				$Itemid = $Itemid > 0 ? '&Itemid=' . $Itemid : '';

				$info->link = sefRelToAbs("index.php?option=com_adsmanager&amp;page=show_ad&amp;adid=" . $id . $Itemid);
			} else {
				$Itemid = self::getItemid('com_adsmanager', 'index.php?option=com_adsmanager&view=front');
				$Itemid = $Itemid > 0 ? '&Itemid=' . $Itemid : '';

				$info->link = JRoute::_("index.php?option=com_adsmanager&view=details&id=" . $row->id . "&catid=" . $row->category . $Itemid);
			}
		}

		return $info;
	}
}
?>