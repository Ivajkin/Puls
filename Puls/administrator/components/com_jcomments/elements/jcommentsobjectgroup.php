<?php

jimport('joomla.filesystem.folder');
jimport('joomla.html.parameter.element');

$language = JFactory::getLanguage();
$language->load('com_jcomments.menu');

class JElementJCommentsObjectGroup extends JElement
{
	var $_name = 'JCommentsObjectGroup';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array();
		$ctrl = $control_name . '[' . $name . ']';
		$attribs = '';

		if ($v = $node->attributes('size')) {
			$attribs .= ' size="'.$v.'"';
		}

		if ($v = $node->attributes('class')) {
			$attribs .= ' class="'.$v.'"';
		} else {
			$attribs .= ' class="inputbox"';
		}

		if ($node->attributes('multiple') != null) {
			$attribs .= ' multiple="multiple"';
			$ctrl .= '[]';
		}

		$db = JFactory::getDBO();
		$db->setQuery('SELECT DISTINCT `option` FROM `#__components` WHERE `option` <> "" ORDER BY `option`;');
		$components = $db->loadResultArray();

		$plugins = JFolder::files(JPATH_SITE . '/components/com_jcomments/plugins/', '\.plugin\.php', true, false);

		if (is_array($plugins)) {
			foreach ($plugins as $plugin) {
				$pluginName = str_replace('.plugin.php', '', $plugin);
				foreach ($components as $component) {
					if ($pluginName == $component || strpos($pluginName, $component . '_') !== false) {
						$o = new StdClass;
						$o->value = $pluginName;
						$o->text = $pluginName;
						$options[] = $o;
					}
				}
			}
		} else {
			$o = new StdClass;
			$o->value = 'com_jcomments';
			$o->text = 'com_jcomments';
			$options[] = $o;
		}

		return JHTML::_('select.genericlist', $options, $ctrl, trim($attribs), 'value', 'text', $value, $control_name . $name);
	}
}
