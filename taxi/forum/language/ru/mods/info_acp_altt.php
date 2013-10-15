<?php
/**
*
* @package - NV Advanced Last Topic Titles
* @version $Id$
* @copyright (c) 2007 nickvergessen nickvergessen@gmx.de http://www.flying-bits.org
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ALTT_ACTIVE'					=> 'Активировать мод NV advanced last topic titles',

	'ALTT_CHAR_LIMIT'				=> 'Количество символов, которые будут отображаться на странице',
	'ALTT_CHAR_LIMIT_EXP'			=> 'Введите 0 или 64 для снятия ограничений',
	'ALTT_CONFIG'					=> 'конфигурация мода NV advanced last topic titles',
	'ALTT_CONFIG_SAVED'				=> 'сохранить изменения',

	'ALTT_LINK_NAME'				=> 'текст ссылки это заголовок',
	'ALTT_LINK_URL'					=> 'ссылка ведёт к',
	'ALTT_FIRST_POST'				=> 'Первое сообщение в последней теме',
	'ALTT_LAST_POST'				=> 'Последнее сообщение в последней теме',
	'ALTT_FIRST_UNREAD_POST'		=> 'Первое непрочитанное сообщение в последней теме',
	'ALTT_FIRST_UNREAD_POST_NOTE'	=> 'Помните: Если нет непрочитанных сообщений, ссылка будет вести к последнему сообщению.',
	'ALTT_POST'						=> 'Сообщение',
	'ALTT_TOPIC'					=> 'Тема',
	'ALTT_LINK_STYLE'				=> 'Стилизация ссылок',
	'ALTT_BOLD'						=> 'Жирный',
	'ALTT_ITALIC'					=> 'Курсив',
	'ALTT_ADV'						=> 'подробнее:',

	'ALTT_IGNORE_PASSWORD'			=> 'Игнорировать пароль',
	'ALTT_IGNORE_PASSWORD_EXP'		=> 'Название будет выводиться даже если это форум под паролем.',
	'ALTT_IGNORE_RIGHTS'			=> 'Игнорировать права',
	'ALTT_IGNORE_RIGHTS_EXP'		=> 'Если Вы игнорируете права, название будет выводиться даже если у пользователя нет прав на просмотр форума и сообщения.',

	'ALTT_PROTECTED'			=> 'Этот форум защищен.',
	'ALTT_TITLE'				=> 'NV advanced last topic titles',

	'NV_ALTT_MOD'					=> 'Мод "NV advanced last topic titles"',
	'INSTALL_NV_ALTT_MOD'			=> 'Установить мод "NV advanced last topic titles"',
	'INSTALL_NV_ALTT_MOD_CONFIRM'	=> 'Вы действительно хотите установить мод "NV advanced last topic titles"?',
	'UPDATE_NV_ALTT_MOD'			=> 'Обновить мод "NV advanced last topic titles"',
	'UPDATE_NV_ALTT_MOD_CONFIRM'	=> 'Вы действительно хотите обновить "NV advanced last topic titles"?',
	'UNINSTALL_NV_ALTT_MOD'			=> 'Удалить мод "NV advanced last topic titles"',
	'UNINSTALL_NV_ALTT_MOD_CONFIRM'	=> 'Вы действительно хотите удалить "NV advanced last topic titles"?',
));

?>