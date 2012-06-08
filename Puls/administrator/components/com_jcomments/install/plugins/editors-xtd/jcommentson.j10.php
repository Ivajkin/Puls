<?php
/**
 * JComments - Joomla Comment System
 *
 * Provides button to insert {jcomments on} into content edit box
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
include_once($mosConfig_absolute_path . '/components/com_jcomments/jcomments.legacy.php');

if (!defined('JCOMMENTS_JVERSION')) {
	return;
}

$_MAMBOTS->registerFunction('onCustomEditorButton', 'botJCommentsOnButton');

function botJCommentsOnButton()
{
	global $option;
	switch ($option) {
		case 'com_sections':
		case 'com_categories':
		case 'com_modules':
			$button = array('', '');
			break;
		default:
			$button = array('jcommentson.gif', '{jcomments on}');
			break;
	}
	return $button;
}

?>