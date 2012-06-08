<?php
/**
 *    @version 2.1.2 Build 21201 Alpha [ Linkwater ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007-2012 Highwood Design
 *    @license Creative Commons Attribution-Non-Commercial-No Derivative Works 3.0 Unported Licence
 *    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
 */

class jc_com_hwdvideoshare_g extends JCommentsPlugin
{
	function getObjectTitle($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery( 'SELECT group_name, id FROM #__hwdvidsgroups WHERE id = ' . $id );
		$title = $db->loadResult();

		return (!empty($title)) ? $title : "Unknown hwdVideoShare Content";
	}

	function getObjectLink($id)
	{
		static $_Itemid = null;

		if (!isset($_Itemid)) {
			if (JCOMMENTS_JVERSION == '1.0') {
				$_Itemid = self::getItemid('com_hwdvideoshare_g');
			} else {
				$needles = array('gid' => (int) $id);
				if ($item = self::_findItem($needles)) {
					$_Itemid = $item->id;
				} else {
					$_Itemid = '';
				}
			}
		}

		$link = JoomlaTuneRoute::_("index.php?option=com_hwdvideoshare&amp;task=viewgroup&amp;group_id=" . $id . "&amp;Itemid=" . $_Itemid);

		return $link;
	}

	function getObjectOwner($id)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT adminid FROM #__hwdvidsgroups WHERE id = ' . $id);
		$userid = $db->loadResult();
		return intval( $userid );
	}

	protected static function _findItem($needles)
	{
		$component = JComponentHelper::getComponent('com_hwdvideoshare');
		$menus	= JApplication::getMenu('site');
		$items	= $menus->getItems('componentid', $component->id);
		$user 	= JFactory::getUser();
		$access = (int)$user->get('aid');

		if (count($items) == 0) { return false; }
		foreach ($needles as $needle => $id) {
			foreach ($items as $item) {
				if ($item->published == 1 && $item->access <= $access) {
					return $item;
				}
			}
		}

		return false;
	}
}
?>