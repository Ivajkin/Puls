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
	'ACP_REPUTATION'		=> 'Репутация',
	'ACP_RP_SETTINGS'		=> 'Настройки репутации',
	'ACP_RP_CONFIGURATION'	=> 'Конфигурация',
	'ACP_RP_RANKS'			=> 'Управление рангами',
	'ACP_RP_POINTS'			=> 'Управление пунктами репутации',
	'ACP_RP_LOG'			=> 'Лог репутации',
	'ACP_REPUTATION_SETTINGS'		=> 'Настройки репутации',

	'LOG_CONFIG_REPUTATION'			=> '<strong>Изменены настройки пунктов репутации пользователей</strong>',
	'LOG_RP_RANK_ADDED'				=> '<strong>Добавлен новый ранг репутации</strong><br />» %s',
	'LOG_RP_RANK_REMOVED'			=> '<strong>Удален ранг репутации</strong><br />» %s',
	'LOG_RP_RANK_UPDATED'			=> '<strong>Обновлен ранг репутации</strong><br />» %s',

));

?>
