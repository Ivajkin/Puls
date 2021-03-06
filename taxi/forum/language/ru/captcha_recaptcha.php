<?php
/**
*
* recaptcha [Russian]
*
* @package language
* @version $Id$
* @copyright (c) 2009 phpBB Group
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

$lang = array_merge($lang, array(
	'RECAPTCHA_LANG'							=> 'ru',
	'RECAPTCHA_NOT_AVAILABLE'		=> 'Для того, чтобы использовать reCaptcha, Вы должны создать аккаунт на <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a>.',
	'CAPTCHA_RECAPTCHA'					=> 'reCaptcha',
	'RECAPTCHA_INCORRECT'				=> 'Введённый Вами код визуального подтверждения некорректен.',

	'RECAPTCHA_PUBLIC'						=> 'Публичный ключ reCaptcha',
	'RECAPTCHA_PUBLIC_EXPLAIN'		=> 'Ваш публичный ключ reCaptcha. Ключи можно получить на сайте <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a>.',
	'RECAPTCHA_PRIVATE'					=> 'Личный ключ reCaptcha',
	'RECAPTCHA_PRIVATE_EXPLAIN'		=> 'Ваш личный ключ reCaptcha. Ключи можно получить на сайте <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a>.',

	'RECAPTCHA_EXPLAIN'					=> 'В целях предотвращения автоматической отправки форм, введите оба слова из текстовго поля ниже.',
));

?>