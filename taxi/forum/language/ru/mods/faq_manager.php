<?php
/**
*
* @package phpBB3 FAQ Manager
* @copyright (c) 2007 EXreaction, Lithium Studios
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
   exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
   $lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge($lang, array(
	'ACP_FAQ_MANAGER'			=> 'Управление FAQ',

	'BACKUP_LOCATION_NO_WRITE'	=> 'Невозможно создать файл резервной копии.  Пожалуйста, проверьте права на папку store/faq_backup/ и все файлы и папки в ней.',
	'BAD_FAQ_FILE'				=> 'Файл, который вы пытаетесь отредактировать, не является файлом FAQ.',

	'CAT_ALREADY_EXISTS'		=> 'Категория с таким названием уже существует.',
	'CATEGORY_NOT_EXIST'		=> 'Запрашиваемая категория не существует.',
	'CREATE_CATEGORY'			=> 'Создать категорию',
	'CREATE_FIELD'				=> 'Создать запись',

	'DELETE_CAT'				=> 'Удалить категорию',
	'DELETE_CAT_CONFIRM'		=> 'Вы уверены, что хотите удалить эту категорию? Все записи внутри нее также будут удалены!',
	'DELETE_VAR'				=> 'Удалить запись',
	'DELETE_VAR_CONFIRM'		=> 'Вы уверены, что хотите удалить эту запись?',

	'FAQ_CAT_LIST'				=> 'Здесь вы можете посмотреть и отредактировать существующие категории.',
	'FAQ_EDIT_SUCCESS'			=> 'FAQ успешно обновлено.',
	'FAQ_FILE_NOT_EXIST'		=> 'Файл, который вы пытаетесь отредактировать, не существует.',
	'FAQ_FILE_NO_WRITE'			=> 'Невозможно обновить файл.  Пожалуйста, проверьте права на файл, который вы хотите отредактировать.',
	'FAQ_FILE_SELECT'			=> 'Выберите файл, который хотите отредактировать.',

	'LANGUAGE'					=> 'Язык',
	'LOAD_BACKUP'				=> 'Загрузить резервную копию',

	'NAME'						=> 'Название',
	'NOT_ALLOWED_OUT_OF_DIR'	=> 'Вы не можете отредактировать файлы из этой языковой директории.',
	'NO_FAQ_FILES'				=> 'Нет доступных файлов FAQ.',
	'NO_FAQ_VARS'				=> 'Нет переменных в этом файле FAQ.',

	'VAR_ALREADY_EXISTS'		=> 'Запись с заданным названием уже существует.',
	'VAR_NOT_EXIST'				=> 'Запрашиваемые переменные не существуют.',
));

?>