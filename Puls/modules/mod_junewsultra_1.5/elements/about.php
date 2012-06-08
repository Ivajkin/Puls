<?php

defined( 'JPATH_BASE' ) or die( 'Restricted access' );

class JElementAbout extends JElement {

    var    $_name = 'About';

    function fetchElement($name, $default, &$xmlNode, $control_name='')
    {
        $html = '</tr></table>';
        $html .= '<div style="margin: 10px;">
        <p><b>You can remove the reference to the developer of the module, but we would appreciate if you write a link to our site in the footer.</b></p>
        <p>Our link: <input name="" style="width: 350px!important;" value="&lt;a href=&quot;http://www.joomla-ua.org&quot; target=&quot;_blank&quot;&gt;JUNewsUltra - Joomla! Україна&lt;/a&gt;" /></p>
        <p>Module JUNewsUltra with the subsequent modifications is distributed under a <a target="_blank" href="http://creativecommons.org/licenses/by-nc-nd/3.0/" target="_blank" rel="license" >Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License</a>.</p></div>';

        return $html;
    }
}

?>