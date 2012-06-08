<?php
/**
 * JComments - Joomla Comment System
 *
 * Search plugin
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

global $mosConfig_absolute_path, $_MAMBOTS;
include_once ($mosConfig_absolute_path.'/components/com_jcomments/jcomments.legacy.php');

if (!defined('JCOMMENTS_JVERSION')) {
	return;
}

$_MAMBOTS->registerFunction('onSearch', 'plgSearchJComments');

if (!function_exists('sefreltoabs')) {
	function sefRelToAbs( $s )
	{
		return $s;
	}
}

/**
 * @return array An array of search areas
 */
function &plgSearchJCommentsAreas()
{
	static $areas = array('comments' => 'Comments');
	return $areas;
}

/**
 * Comments Search method
 *
 * The sql must return the following fields that are used in a common display
 * routine: href, title, section, created, text, browsernav
 * @param string $text Target search string
 * @param string $phrase mathcing option, exact|any|all
 * @param string $ordering ordering option, newest|oldest|popular|alpha|category
 * @param mixed $areas An array if the search it to be restricted to areas, null if search all
 * @return array
 */
function plgSearchJComments($text, $phrase = '', $ordering = '', $areas = null)
{
	$text = strtolower(trim($text));
	$result = array();

	if ($text == '') {
		return $result;
	}

	if (is_array($areas)) {
		if (!array_intersect($areas, array_keys(plgSearchJCommentsAreas()))) {
			return $result;
		}
	}
	if (file_exists(JCOMMENTS_BASE.'/jcomments.php')) {

		require_once (JCOMMENTS_BASE.'/jcomments.php');
		require_once (JCOMMENTS_BASE.'/jcomments.class.php');

		$db = JCommentsFactory::getDBO();
		$limit = 30;

		switch ($phrase) {
			case 'exact':
				$text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
				$wheres2[] = "LOWER(c.name) LIKE " . $text;
				$wheres2[] = "LOWER(c.comment) LIKE " . $text;
				$wheres2[] = "LOWER(c.title) LIKE " . $text;
				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;
			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = "LOWER(c.name) LIKE " . $word;
					$wheres2[] = "LOWER(c.comment) LIKE " . $word;
					$wheres2[] = "LOWER(c.title) LIKE " . $word;
					$wheres[] = implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering) {
			case 'oldest':
				$order = 'date ASC';
				break;
			case 'newest':
			default:
				$order = 'date DESC';
				break;
		}

		$acl = JCommentsFactory::getACL();
		$access = $acl->getUserAccess();

		if (is_array($access)) {
			$accessCondition = "AND jo.access IN (" . implode(',', $access) . ")";
		} else {
			$accessCondition = "AND jo.access <= " . (int) $access;
		}

		$query = "SELECT "
				. "  c.comment AS text"
				. ", c.date AS created"
				. ", '2' AS browsernav"
				. ", '" . JText::_('Comments') . "' AS section"
				. ", ''  AS href"
				. ", c.id"
				. ", jo.title AS object_title, jo.link AS object_link"
				. " FROM #__jcomments AS c"
				. " INNER JOIN #__jcomments_objects AS jo ON jo.object_id = c.object_id AND jo.object_group = c.object_group and jo.lang=c.lang"
				. " WHERE c.published=1"
				. " AND c.deleted=0"
				. " AND jo.link <> ''"
				. (JCommentsMultilingual::isEnabled() ? " AND c.lang = '" . JCommentsMultilingual::getLanguage() . "'" : "")
				. " AND ($where) "
				. $accessCondition
				. " ORDER BY c.object_id, $order";

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		$cnt = count($rows);

		if ($cnt > 0) {
			$config = JCommentsFactory::getConfig();
			$enableCensor = $acl->check('enable_autocensor');
			$word_maxlength = $config->getInt('word_maxlength');

			for ($i = 0; $i < $cnt; $i++) {
				$text = JCommentsText::cleanText($rows[$i]->text);

				if ($enableCensor) {
					$text = JCommentsText::censor($text);
				}

				if ($word_maxlength > 0) {
					$text = JCommentsText::fixLongWords($text, $word_maxlength);
				}

				if ($text != '') {
					$rows[$i]->title = $rows[$i]->object_title;
					$rows[$i]->text = $text;
					$rows[$i]->href = $rows[$i]->object_link . '#comment-' . $rows[$i]->id;
					$result[] = $rows[$i];
				}
			}
		}
		unset($rows);
	}
	return $result;
}
	?>