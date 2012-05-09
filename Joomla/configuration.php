<?php
class JConfig {
/* Site Settings */
var $offline = '0';
var $offline_message = 'В настоящее время сайт закрыт на техническое обслуживание.<br />Пожалуйста, зайдите позже.';
var $sitename = 'Joomla!';
var $editor = 'tinymce';
var $list_limit = '20';
var $legacy = '0';
/* Debug Settings */
var $debug = '0';
var $debug_lang = '0';
/* Database Settings */
var $dbtype = 'mysql';
var $host = 'sql109.byethost5.com';
var $user = 'b5_10620585';
var $password = 'kre8stuf';
var $db = 'b5_10620585_jos';
var $dbprefix = 'joomla_';
/* Server Settings */
var $live_site = '';
var $secret = 'MS4D067Y5UV8171M';
var $gzip = '0';
var $error_reporting = '-1';
var $helpurl = 'http://help.joomla.org';
var $xmlrpc_server = '0';
var $ftp_host = 'ftp.byethost5.com';
var $ftp_port = '21';
var $ftp_user = '';
var $ftp_pass = '';
var $ftp_root = '';
var $ftp_enable = '0';
var $force_ssl = '0';
/* Locale Settings */
var $offset = '0';
var $offset_user = '0';
/* Mail Settings */
var $mailer = 'mail';
var $mailfrom = 'cahbkooo91@mail.ru';
var $fromname = 'Joomla!';
var $sendmail = '/usr/sbin/sendmail';
var $smtpauth = '0';
var $smtpsecure = 'none';
var $smtpport = '25';
var $smtpuser = '';
var $smtppass = '';
var $smtphost = 'localhost';
/* Cache Settings */
var $caching = '0';
var $cachetime = '15';
var $cache_handler = 'file';
/* Meta Settings */
var $MetaDesc = 'Joomla! - the dynamic portal engine and content management system';
var $MetaKeys = 'joomla, Joomla';
var $MetaTitle = '1';
var $MetaAuthor = '1';
/* SEO Settings */
var $sef           = '0';
var $sef_rewrite   = '0';
var $sef_suffix    = '0';
/* Feed Settings */
var $feed_limit   = 10;
var $feed_email   = 'author';
var $log_path = 'C:\\Program Files (x86)\\EasyPHP-5.3.9\\www\\Joomla\\logs';
var $tmp_path = 'C:\\Program Files (x86)\\EasyPHP-5.3.9\\www\\Joomla\\tmp';
/* Session Setting */
var $lifetime = '15';
var $session_handler = 'database';
}
?>