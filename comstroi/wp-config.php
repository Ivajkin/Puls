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
define('AUTH_KEY',         '^X1|o gBw])`QM98RA!y9;5Ck|gXBS+;7mpv3qtz.>x]6@),qDUf~rBf>y/v]fm=');
define('SECURE_AUTH_KEY',  'Yto%@hExS[|+p$9PvjPQRFJf!ayJuy5y :E+~Cj#tpgjj?[1O1L^ mA|3K*(r[c,');
define('LOGGED_IN_KEY',    ';OT!gIPcS%hTL6[4>eIW&u21<7<ggdC8C/n=<27#Jz2&Z~1Ja$oDoXk(z:AD61/+');
define('NONCE_KEY',        ',k.-!7^Fj3<*+8;&Uy :lowc|W8B}iVDyMm5UvJfq.VRtG.J.IrSG?-eigJx#a5?');
define('AUTH_SALT',        'p(Sl[29J~;B{-U*>1 -Ur)NW//2dz|@A;~eih>.ZKYc,:R1D-n-PHim[h|bD [!N');
define('SECURE_AUTH_SALT', 'IOvtg$^}kE|= L7;6 u;e.T|ep>4d}mD-E*R{PUvT`Kx(@L;HpCS6NDu+8;sX0.v');
define('LOGGED_IN_SALT',   ' B`x]UvDwXuR`lu|}qde0yC=1q=`xOi-b<~.+EUi)qX*Z+}#+%STv|(]+i]Qg2{S');
define('NONCE_SALT',       ')&?V<LIIfiC.=,(nS@EJ+9PK2vS*/]]KtC<m7MeA8u|ogzbkueAJ[9(*rekMp]Gw');

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
