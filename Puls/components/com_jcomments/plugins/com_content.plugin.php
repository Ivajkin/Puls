<?php
/**
 * JComments plugin for standart content objects support
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

class jc_com_content extends JCommentsPlugin
{
	function getObjectInfo($id, $language = null)
	{
		$db = JCommentsFactory::getDBO();
		$article = null;
		$link = null;

		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe, $Itemid;
			$query = 'SELECT a.id, a.title, a.created_by, a.access' .
					' FROM #__content AS a' .
					' LEFT JOIN #__categories AS cc ON cc.id = a.catid' .
					' WHERE a.id = ' . intval($id);
			$db->setQuery( $query );
			$db->loadObject($article);

			$compatibilityMode = $mainframe->getCfg('itemid_compat');

			if ($compatibilityMode == null) {
				// Joomla 1.0.12 or below
				if ( $Itemid && $Itemid != 99999999 ) {
					$_Itemid = $Itemid;
				} else {
					$_Itemid = $mainframe->getItemid( $id );
				}
			} else if ( (int) $compatibilityMode > 0 && (int) $compatibilityMode <= 11) {
				// Joomla 1.0.13 or higher and Joomla 1.0.11 compatibility
				$_Itemid = $mainframe->getItemid( $id, 0, 0  );
			} else {
				// Joomla 1.0.13 or higher and new Itemid algorithm
				$_Itemid = $Itemid;
			}

			$link = JoomlaTuneRoute::_('index.php?option=com_content&amp;task=view&amp;id='. $id .'&amp;Itemid='. $_Itemid);
		} else {
			require_once(JPATH_ROOT.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

			$query = 'SELECT a.id, a.title, a.created_by, a.sectionid, a.access,' .
					' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
					' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
					' FROM #__content AS a' .
					' LEFT JOIN #__categories AS cc ON cc.id = a.catid' .
					' WHERE a.id = ' . intval($id);
			$db->setQuery( $query );
			$article = $db->loadObject();

			if (!empty($article)) {
				$user = JFactory::getUser();

				if (JCOMMENTS_JVERSION == '1.5') {
					$checkAccess = $article->access <= $user->get('aid', 0);
				
					if ($checkAccess) {
						$link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid));
					} else {
						$link = JRoute::_("index.php?option=com_user&task=register");
					}
				} else {
					$authorised = JAccess::getAuthorisedViewLevels($user->get('id'));
					$checkAccess = in_array($article->access, $authorised);

					if ($checkAccess) {
						$link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug));
					} else {
						$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));

						$menu = JFactory::getApplication()->getMenu();
						$active = $menu->getActive();
						$ItemId = $active->id;
						$link = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $ItemId);
						$uri = new JURI($link);
						$uri->setVar('return', base64_encode($returnURL));
						$link = $uri->toString();
					}
				}
			}
		}

		$info = new JCommentsObjectInfo();

		if (!empty($article)) {
			$info->title = $article->title;
			$info->access = $article->access;
			$info->userid = $article->created_by;
			$info->link = $link;
		}

		return $info;
	}
}
?>