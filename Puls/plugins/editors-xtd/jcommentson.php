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

defined('_JEXEC') or die;

include_once(JPATH_ROOT . '/components/com_jcomments/jcomments.legacy.php');

if (!defined('JCOMMENTS_JVERSION')) {
	return;
}

jimport('joomla.event.plugin');

/**
 * Editor JComments Off button
 **/
class plgButtonJCommentsOn extends JPlugin
{
	function plgButtonJCommentsOn(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onDisplay($name)
	{
		$getContent = $this->_subject->getContent($name);
		$js = "
				function insertJCommentsOn(editor) {
					var content = $getContent
					if (content.match(/{jcomments on}/)) {
						return false;
					} else {
						jInsertEditorText('{jcomments on}', editor);
					}
				}
				";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		$button = new JObject();
		$button->set('modal', false);
		$button->set('onclick', 'insertJCommentsOn(\'' . $name . '\');return false;');
		$button->set('text', JText::_('PLG_EDITORS-XTD_JCOMMENTSON_BUTTON_JCOMMENTSON'));
		$button->set('name', 'blank');
		$button->set('link', '#');

		return $button;
	}
}
?>