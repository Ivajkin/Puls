<?php
class JElementMultisection extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
      
   var   $_name = 'Multisection';

   function fetchElement($name, $value, &$node, $control_name)
   {
      $db =& JFactory::getDBO();
      $size = ( $node->attributes('size') ? $node->attributes('size') : 5 );
      $query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope = "content" ORDER BY title';
      $db->setQuery($query);
      $options = $db->loadObjectList();

      return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]',  ' multiple="multiple" size="' . $size . 'class="inputbox"', 'id', 'title', $value, $control_name.$name);
   }
}
?>