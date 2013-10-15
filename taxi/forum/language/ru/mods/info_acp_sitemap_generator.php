<?php
/**
*
* mcp [English]
*
* @package language
* @version $Id: info_acp_sitemap_generator.php 2007-11-26 Joshua2100 $
* @copyright (c) 2005 phpBB Group
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

$lang = array_merge($lang, array(
	'ACP_SITEMAP_GENERATOR'	=> 'SiteMap Generator',
	'SITEMAP_GEN_DETAILS'	=> 'Генерирует карту сайта в формате XML',
	
	'SM_SETTINGS'			=> 'Настройки',
	'GENERATE'				=> 'Сгенерировать карту сайта',
	
	'RUN_NOW'				=> 'XML Sitemap Generator',
	'RUN_DESC'				=> 'Запустить Sitemap Generator и создать карту в формате XML.',
	'PINGORNOT'				=> 'Google Server Ping',
	'PINGORNOT_DESC'		=> 'Отправить запрос на Google Server после создания карты',
	'SEO_MOD'				=> 'SEOMOD',
	'SEOMOD_DESC'			=> 'Если опция включена, при создании карты будут использоваться URL обработанные SEOMOD',
	'NOTE'					=> 'Примечание',
	'THE_NOTE'				=> 'Для использования Google Sitemaps нужно зарегистрироваться на www.google.com/webmasters/sitemaps/',
	'MAP_RETURN'			=> 'Вернуться на главную страницу',
	
	'PING_SUCCESS'			=> '% получил уведомление о генерации карты сайта',
	'PING_FAIL'				=> 'Произошла ошибка: нет связи с сервером',
	
	'GENERATE_COMPLETE'		=> 'Все карты успешно сгенерированы.',
	'GENERATE_NOTSUCCESS'	=> 'Произошла ошибка: создание карты завершилось с ошибками',
		
	'GENERATE_COMPLETE'		=> 'Создание карты завершено.',	
	'MAP_CREATE_FAIL'		=> 'Создание карты закончилось неудачно',
	'MAP_WRITE_FAIL'		=> 'Сохранение карты в файле закончилось неудачно',
	'MAP_GOOGLE_PING'		=> 'Связь с сервером потеряна.',
	'PING_ERROR'			=> ' Error Returned',
	
	'SM_STATS'				=> 'Статистика SiteMap',
	'SM_TOPIC_LINK'			=> 'Количество ссылок на темы',
	'SM_FORUM_LINK'			=> 'Количество ссылок на форумы',
	'SM_VERSION'			=> 'Версия SiteMap Generator',
	'SM_TOPIC_SIZE'			=> 'Размеры TopicMap',
	'SM_FORUM_SIZE'			=> 'Размеры ForumMap',

	'MAPZIP'				=> 'Gzip Sitemaps',
	'SHOWSTAT'				=> 'Показать статистику Sitemap',
	
	'SEO_NOT_INT'			=> 'Опция SEOMOD включена, но SEO MOD не установлен..<br />Прежде чем включать данную опцию необходимо установить SEO MOD.',
));

?>