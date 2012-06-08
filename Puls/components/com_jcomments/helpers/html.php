<?php
/**
 * JComments - Joomla Comment System
 * 
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

/**
 * JComments Html Helper
 */
class JCommentsHTML
{
	public static function makeOption( $value, $text = '', $value_name = 'value', $text_name = 'text' )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			return mosHTML::makeOption($value, $text, $value_name, $text_name);
		}
		return JHTML::_('select.option', $value, $text, $value_name, $text_name);
	}
	
	public static function selectList( &$arr, $tag_name, $tag_attribs, $key, $text, $selected = NULL, $idtag = false, $flag = false )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			return mosHTML::selectList($arr, $tag_name, $tag_attribs, $key, $text, $selected);
		}
		return JHTML::_('select.genericlist', $arr, $tag_name, $tag_attribs, $key, $text, $selected, $idtag, $flag);
	}
	
	public static function yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes = 'yes', $no = 'no', $id = false )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			return mosHTML::yesnoRadioList($tag_name, $tag_attribs, $selected, $yes, $no);
		}
		return JHTML::_('select.booleanlist', $tag_name, $tag_attribs, $selected, $yes, $no, $id);
	}
	
	public static function yesnoSelectList( $tag_name, $tag_attribs, $selected, $yes = 'yes', $no = 'no' )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$arr = array(JCommentsHTML::makeOption(0, $no), JCommentsHTML::makeOption(1, $yes));
		} else {
			$arr = array(JCommentsHTML::makeOption(0, $no), JCommentsHTML::makeOption(1, $yes));
		}
		return JCommentsHTML::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', (int) $selected);
	}
	
	public static function _($type)
	{
		$type = preg_replace('#[^A-Z0-9_\.]#i', '', $type);
		$args = func_get_args();
		array_shift($args);

		$item = $args[0];
		$i = $args[1];
		$prefix = isset($args[2]) ? $args[2] : null;

		$result = '';

		switch($type) {
			case 'grid.id':
				if (JCOMMENTS_JVERSION == '1.0') {
					$result = mosHTML::idBox( $i, $item->id);
				} elseif (JCOMMENTS_JVERSION == '1.7') {
					$result = JHTML::_('grid.id', $i, $item->id);
				} else {
					$result = JHtml::_('grid.id', $i, $item->id);
				}
				break;
			case 'grid.checkedout':
				if (JCOMMENTS_JVERSION == '1.0') {
					$result = mosCommonHTML::CheckedOutProcessing($item, $i);
				} elseif (JCOMMENTS_JVERSION == '1.7') {
					$result = JHTML::_('grid.checkedout', $item, $i);
				} else {
					$result = JHtml::_('grid.checkedout', $item, $i);
				}
				break;
			case 'grid.published':
				if (JCOMMENTS_JVERSION == '1.0') {
					$result = mosCommonHTML::PublishedProcessing($item, $i);
				} elseif (JCOMMENTS_JVERSION == '1.7') {
					$result = JHTML::_('grid.published', $item, $i, 'tick.png', 'publish_x.png', $prefix);
				} else {
					$result = JHtml::_('grid.published', $item, $i, 'tick.png', 'publish_x.png', $prefix);
				}
				break;
		}
		return $result;
	}
}
?>