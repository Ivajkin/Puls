<?php
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

$lang = array_merge($lang, array(
    'ACP_CLEANTALK'                     => 'CLEANTALK',
    'CT_ENABLE'                         => 'Включить модуль',
  	'CT_SERVER_URL'                     => 'URL сервера автомодерации Клинтолк',
    'CT_SERVER_URL_EXPLAIN'             => 'Пример: http://moderate.cleantalk.ru',
    'CT_AUTH_KEY'                       => 'Ключ доступа',
    'CT_AUTH_KEY_EXPLAIN'               => 'Ключ доступа к сервису модерации и защиты от спама. Чтобы получить новый ключ зарегистрируйтесь на сайте <a href="http://cleantalk.ru/install/phpbb3?step=2" target="_blank">cleantalk.ru</a>',
	'ENABLE_STOPLIST_CHECK'             => 'Включить проверку запрещенных слов',
	'ENABLE_STOPLIST_CHECK_EXPLAIN'     => 'Сообщения содежащие оскорбления и не нормативную лексику будут отправлены на премодерацию.',
	'CT_ALLOW_LINKS'                    => 'Разрешить ссылки',
	'CT_ALLOW_LINKS_EXPLAIN'            => 'Разшрешить офтоп сообщения содержащие HTML ссылки, адреса электронной почты, телефонные и ICQ номера.',
	'CT_SETTINGS'                       => 'Настройки',
	'CT_SETTINGS_FORUM'					=> 'Настройки Клинтолк',
    'CT_TITLE'                          => 'Клинтолк. Защита от спама',
    'CT_TITLE_EXPLAIN'                  => 'Тут вы можете управлять основными настройками мода "Клинтолк. Защита от спама".',
	'CT_RESPONSE_LANGUAGE'              => 'Язык сервисных сообщений',
	'CT_MODERATE_GUESTS'                => 'Модерировать Гостей',
	'CT_MODERATE_NEWLY_REGISTERED'      => 'Модерировать Новых пользователей',
	'CT_MODERATE_REGISTERED'            => 'Модерировать Зарегистрированных пользователей',
	'CT_NEWUSER'                        => 'Проверять регистрацию',
	'CT_NEWUSER_EXPLAIN'                => 'Непрошедшим проверку будет выдан отказ в регистрации с объяснением причин.',
	'CT_SUCCESSFULLY_INSTALLED' 		=> 'Клинтолк успешно установлен!',
));

/**
* A copy of Handyman` s MOD version check
*/
$lang = array_merge($lang, array(
    'ANNOUNCEMENT_TOPIC'    => 'объявление о выпуске',
    'CURRENT_VERSION'       => 'Текущая версия',
    'DOWNLOAD_LATEST'       => 'Скачать последнюю версию',
    'LATEST_VERSION'        => 'Последняя версия',
    'NO_INFO'                   => 'Нет связи с сервером',
    'NOT_UP_TO_DATE'            => 'Версия %s не самая последняя.',
    'RELEASE_ANNOUNCEMENT'  => 'объявление о выпуске',
    'UP_TO_DATE'            => 'Версия %s самая последняя',
    'VERSION_CHECK'         => 'Проверка версии',
    'HAS_NEW_VERSION'       => 'Доступна новая версия модуля "%1$s". <a href="%2$s" target="_blank">Посмотреть изменения.</a>',
));
?>
