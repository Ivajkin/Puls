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
if (!defined('IN_PHPBB'))
{
    exit;
}

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
	'RP_ADD_POINTS'				=> 'Добавить пункт репутации',
	'RP_COMMENT'				=> 'Комментарий',
	'RP_COMMENTS'				=> 'Комментарии',
	'RP_DISABLED'				=> 'Извините, Админ заблокировал эту возможность.',
	'RP_EMPTY_DATA'				=> 'Пользователь не получил репутации.',
	'RP_FROM'					=> 'От',
	'RP_GROUP_POWER'			=> 'Репутация группы',
	'RP_HIDE'					=> 'Скрыть мою репутацию',
	'RP_NA'						=> 'n/a',
	'RP_NEGATIVE'				=> 'Плохо',
	'RP_NO_COMMENT'				=> 'Вы не можете оставить поле для комментариев пустым',
	'RP_POINTS'					=> 'Пункты',
	'RP_POSITIVE'				=> 'Хорошо',
	'RP_POWER'					=> 'Ранг репутации',
	'RP_SAME_POST'				=> 'Вы уже дали репутацию за этот пост',
	'RP_SELF'					=> 'Вы не можете подымать репутацию самому себе',
	'RP_SENT'					=> 'Ваши пункты репутации успешно посланы',
	'RP_SUBTRACT_POINTS'		=> 'Вычесть пункт репутации',
	'RP_SUCCESS_DELETE'			=> 'Комментарий к репутации успешно удален.',
	'RP_TIMES_LIMIT'			=> 'Вы не можете изменять репутацию, с момента прошлого изменения репутации прошло слишком мало времени.',
	'RP_TITLE'					=> 'Пункты репутации пользователя',
	'RP_TOO_LONG_COMMENT'		=> 'Ваш комментарий содержит %1$d Символов. Максимально возможное число символов %2$d.',
	'RP_TOTAL_POINTS'			=> 'Пункты репутации',
	'RP_USER_DISABLED'			=> 'Вам не разрешено давать репутацию.',
	'RP_USER_SELF_DISABLED'		=> 'Этот пользователь заблокировал репутацию.',
));

// Reputation settings
$lang = array_merge($lang, array(
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Здесь вы можете разместить настроечные параметры пунктов пользовательской репутации.',
	'RP_BLOCK_PER_POINTS'		=> 'Блоков за пункты',
	'RP_BLOCK_PER_POINTS_EXPLAIN'	=> 'Добавить 1 блок при достижении Х пунктов репутации.',
	'RP_DISABLE_COMMENT'		=> 'Отключить комментирование репутации',
	'RP_DISPLAY'				=> 'Показывать репутацию',
	'RP_DISPLAY_BLOCK'			=> 'Блок',
	'RP_DISPLAY_BOTH'			=> 'Оба',
	'RP_DISPLAY_TEXT'			=> 'Текст',
	'RP_ENABLE'					=> 'Включить репутацию пользователей',
	'RP_FORCE_COMMENT'			=> 'Комментарий пользователя обязателен',
	'RP_FORUM_EXCLUSIONS'		=> 'Исключения форума',
	'RP_FORUM_EXCLUSIONS_EXPLAIN'	=> 'Введите ID форума, что бы убрать на нем репутацию, напр. 3,4,6',
	'RP_MAXIMUM_POINT'			=> 'Максимум пунктов',
	'RP_MAX_BLOCK'				=> 'Максимум блоков',
	'RP_MAX_BLOCK_EXPLAIN'		=> 'Максимальное число отображаемых блоков.',
	'RP_MAX_CHARS'				=> 'Максимум символов в комментарии',
	'RP_MAX_CHARS_EXPLAIN'		=> 'Число символов доступное в комментарии, поставте 0 для снятия ограничения.',
	'RP_MAX_POWER'				=> 'Максимальный уровень репутации',
	'RP_MAX_POWER_EXPLAIN'		=> 'Максимальный уровень репутации достигнут.',
	'RP_MEMBERSHIP_DAYS'		=> 'Фактор времени пользователя',
	'RP_MEMBERSHIP_DAYS_EXPLAIN'	=> 'Пользователь получает 1 репутации через каждые Х дней.',
	'RP_MINIMUM_POINT'			=> 'Минимум пунктов',
	'RP_MIN_POSTS'				=> 'Минимум сообщений',
	'RP_MIN_POSTS_EXPLAIN'		=> 'Минимум сообщений до появления репутации.',
	'RP_POWER'					=> 'Ранг репутации',
	'RP_POWER_REP_POINT'		=> 'Факторы пунктов репутации',
	'RP_POWER_REP_POINT_EXPLAIN'	=> 'Пользователю добавляется 1 пункт репутации после каждых Х пунктов репутации.',
	'RP_RECENT_POINTS'			=> 'Шаг изменения пунктов репутации',
	'RP_RECENT_POINTS_EXPLAIN'	=> 'Число пунктов репутации, отображаемое в профиле пользователя.',
	'RP_TIME_LIMITATION'		=> 'Лимит времени',
	'RP_TIME_LIMITATION_EXPLAIN'	=> 'Минимальное время перед тем как пользователю можно дать еще репутацию.',
	'RP_TOTAL_POINTS'			=> 'Пункты репутации',
	'RP_TOTAL_POSTS'			=> 'Фактор сообщений',
	'RP_TOTAL_POSTS_EXPLAIN'	=> 'Пользователь получает 1 репутации через каждые Х постов.',
	'RP_USER_SPREAD'			=> 'Распространение репутации',
	'RP_USER_SPREAD_EXPLAIN'	=> 'Дайте репутацию кому нибудь другому, прежде чем снова дать ее этому пользователю.',
	'RP_USER_SPREAD_FIRST'		=> 'Вы должны сначала дать репутацию кому нибудь другому прежде чем повторно давать ее одному пользователю.',
));

// Rank management
$lang = array_merge($lang, array(
	'ACP_REP_RANKS_EXPLAIN'		=> 'Используя эту форму можно добавлять, редактировать, удалять ранги репутации. ',
	'RP_ADD_RANK'				=> 'Добавить ранг',
	'RP_MUST_SELECT_RANK'		=> 'Вы должны выбрать ранг',
	'RP_NO_RANK_TITLE'			=> 'Вы должны ввести заголовок для ранга',
	'RP_RANK_ADDED'				=> 'Ранг успешно добавлен.',
	'RP_RANK_MINIMUM'			=> 'Минимум пунктов',
	'RP_RANK_TITLE'				=> 'Заглавие',
	'RP_RANK_UPDATED'			=> 'Ранг был успешно обновлен.',
));

// Point management
$lang = array_merge($lang, array(
	'RP_ADD'					=> 'Добавить',
	'RP_ALTER_SUCCESS'			=> 'Пункты репутации пользователя успешно обновлены.',
	'RP_CHANGE'					=> 'Изменить на',
	'RP_SUBTRACT'				=> 'Уменьшить',
	'RP_WRONG_USERNAMES'		=> 'Введено неверное имя пользователя.',
));

// UMIL auto installer
$lang = array_merge($lang, array(
	'INSTALL_REPUTATION_POINT'				=> 'Install user reputation points',
	'INSTALL_REPUTATION_POINT_CONFIRM'		=> 'Are you ready to install user reputation points?',

	'REPUTATION_POINT'						=> 'User reputation points',
	'REPUTATION_POINT_EXPLAIN'				=> 'For support please visit <a href="http://www.phpbbindonesia.com">phpBB Indonesia</a>.',

	'TABLE_SYNC'							=> 'Table successfuly synchronized.',

	'UNINSTALL_REPUTATION_POINT'			=> 'Uninstall User reputation points',
	'UNINSTALL_REPUTATION_POINT_CONFIRM'	=> 'Are you ready to uninstall the User reputation points?  All settings and data saved by this mod will be removed!',
	'UPDATE_REPUTATION_POINT'				=> 'Update Test Mod',
	'UPDATE_REPUTATION_POINT_CONFIRM'		=> 'Are you ready to update the User reputation points?',
));

?>