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

$lang = array_merge($lang, array(
	'ACP_REPUTATION'		=> 'Reputation',
	'ACP_RP_SETTINGS'		=> 'Reputation settings',
	'ACP_RP_CONFIGURATION'	=> 'Configuration',
	'ACP_RP_RANKS'			=> 'Rank management',
	'ACP_RP_POINTS'			=> 'Point management',
	'ACP_RP_LOG'			=> 'Reputation log',
	'ACP_REPUTATION_SETTINGS'		=> 'Reputation settings',

	'LOG_CONFIG_REPUTATION'			=> '<strong>Altered user reputation points settings</strong>',
	'LOG_RP_RANK_ADDED'				=> '<strong>Added new reputation rank</strong><br />» %s',
	'LOG_RP_RANK_REMOVED'			=> '<strong>Removed reputation rank</strong><br />» %s',
	'LOG_RP_RANK_UPDATED'			=> '<strong>Updated reputation rank</strong><br />» %s',

));

?>
