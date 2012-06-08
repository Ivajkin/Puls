<?php
defined('JPATH_BASE') or die();

class JButtonJCommentsPopup extends JButton
{
	var $_name = 'JCommentsPopup';

	function fetchButton($type = 'JCommentsPopup', $name = '', $text = '', $url = '', $onClose = '')
	{
		$text = JText::_($text);
		$class = $this->fetchIconClass($name);

		$html = "<a class=\"toolbar\" href=\"#\" onclick=\"jcbackend.popup('$url', '$name', function(){" . $onClose . "});\" >\n";
		$html .= "<span class=\"$class\">\n";
		$html .= "</span>\n";
		$html .= "$text\n";
		$html .= "</a>\n";

		return $html;
	}

	function fetchId($type = 'JCommentsPopup', $name)
	{
		if (defined('JPATH_PLATFORM')) {
			return $this->_parent->getName() . '-' . $name;
		} else {
			return $this->_parent->_name . '-' . $name;
		}
	}
}