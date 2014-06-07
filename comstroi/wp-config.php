<?php
/**
 * Основные параметры WordPress.
 *
 * Этот файл содержит следующие параметры: настройки MySQL, префикс таблиц,
 * секретные ключи, язык WordPress и ABSPATH. Дополнительную информацию можно найти
 * на странице {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Кодекса. Настройки MySQL можно узнать у хостинг-провайдера.
 *
 * Этот файл используется сценарием создания wp-config.php в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать этот файл
 * с именем "wp-config.php" и заполнить значения.
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'comstroi');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'mintsql');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется снова авторизоваться.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '|(6J4/Hr+)#W_xV.{oN{u!i|G!~{S@JvB@Nn8b|^&- G}dpOHYDFs.{}{|2+_iQr');
define('SECURE_AUTH_KEY',  'Nli*+l1TJM1>GmyjX>KrVUr%]>Ch.rZGVSJVK4T=bB`LhdyZv=JsQa^-6CN!bMr8');
define('LOGGED_IN_KEY',    'iy$rLU=s:OJ{pKV //<Di|Mb7pntG*ckuMnST[q,_^H}=>vcEF{ZDMuwFc[W+?kI');
define('NONCE_KEY',        'l4LoB41|SLv4-U?$4Xj^9+^lnAY7(^4GXy=oM/.x4,25q nt5TJ6Qe/lZvY1tjtI');
define('AUTH_SALT',        'H_PTp7-<<A/7*u^d{HN{.N6Z9-CwfkGORvq6F#;R:Tt-/9LePn1jt|]5B+R)?;hE');
define('SECURE_AUTH_SALT', '&I# x*I<+rLqU_@+h&6vJDLv7*k|V}=@BVC+E5 EFJ|=MT(C!ai%FXz/]3+&)CAS');
define('LOGGED_IN_SALT',   'A4H9xYh4H.!Z}-kZ}OPQn>6A0t|Er[lnP?fBXTf#zQNpC=RdhMu{S+U~,sr(l#2|');
define('NONCE_SALT',       'aN{ZUhLg1ZH;OxwkWg&>b=/n(n4*+5LHAwjQ.[12X_f-Um0& wAkN/_|_.dj@/=$');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько блогов в одну базу данных, если вы будете использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Язык локализации WordPress, по умолчанию английский.
 *
 * Измените этот параметр, чтобы настроить локализацию. Соответствующий MO-файл
 * для выбранного языка должен быть установлен в wp-content/languages. Например,
 * чтобы включить поддержку русского языка, скопируйте ru_RU.mo в wp-content/languages
 * и присвойте WPLANG значение 'ru_RU'.
 */
define('WPLANG', 'ru_RU');
//define('WPLANG', '');

define( 'WP_MEMORY_LIMIT', '96M' );

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Настоятельно рекомендуется, чтобы разработчики плагинов и тем использовали WP_DEBUG
 * в своём рабочем окружении.
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
