<?php

defined( 'JPATH_BASE' ) or die( 'Restricted access' );

require_once JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'pane.php';

class JElementJUtabs extends JElement {

    var    $_name = 'JUtabs';

    function fetchElement($name, $default, &$xmlNode, $control_name='')
    {
        $text = $xmlNode->_attributes['description'];
        $html  = '';
        $html .= '</td></tr></table>';
        $html .= JPaneSliders::endPanel();
        $html .= JPaneSliders::startPanel( ''.JText::_($text), $text );
        $html .= '<table width="100%" class="paramlist admintable" cellspacing="1">';
        $desc='';
        $html .= '<tr><td class="paramlist_description">'.$desc.'</td>';
        $html .= '<td class="paramlist_value">';

        return $html;
    }
}

?>