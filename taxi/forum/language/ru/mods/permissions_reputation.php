<?php
/**
*
* groups [English]
*
* @author idiotnesia pungkerz@gmail.com - http://www.phpbbindonesia.com
*
* @package language
* @version 0.3.1
* @copyright (c) 2008, 2009 phpbbindonesia
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

// Adding new category
$lang['permission_cat']['reputation'] = 'Репутация';

// Adding the permissions
$lang = array_merge($lang, array(
	'acl_a_reputation'	=> array('lang' => 'Можете управлять репутацией', 'cat' => 'misc'),
	'acl_m_rp_moderate'	=> array('lang' => 'Можете модерировать репутацию', 'cat' => 'misc'),
	'acl_u_rp_give'		=> array('lang' => 'Можете дать репутацию', 'cat' => 'reputation'),
	'acl_u_rp_disable'	=> array('lang' => 'Можете скрыть\заблокировать репутацию', 'cat' => 'reputation'),
	'acl_u_rp_ignore'	=> array('lang' => 'Может пренебречь ограничением (предел времени и распространение репутации)', 'cat' => 'reputation'),
	'acl_u_rp_give_negative'	=> array('lang' => 'Может давать негативную репутацию', 'cat' => 'reputation'),
	'acl_u_rp_view_comment'		=> array('lang' => 'Может просматривать комментарии к репутации', 'cat' => 'reputation'),
));
?>