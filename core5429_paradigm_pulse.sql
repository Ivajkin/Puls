-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 08 2012 г., 03:32
-- Версия сервера: 5.1.63
-- Версия PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `core5429_paradigm_pulse`
--

-- --------------------------------------------------------

--
-- Структура таблицы `jos_banner`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_banner` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(30) NOT NULL DEFAULT 'banner',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `imageurl` varchar(100) NOT NULL DEFAULT '',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `date` datetime DEFAULT NULL,
  `showBanner` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` varchar(50) DEFAULT NULL,
  `custombannercode` text,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tags` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `viewbanner` (`showBanner`),
  KEY `idx_banner_catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_bannerclient`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_bannerclient` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `extrainfo` text NOT NULL,
  `checked_out` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out_time` time DEFAULT NULL,
  `editor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_bannertrack`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_bannertrack` (
  `track_date` date NOT NULL,
  `track_type` int(10) unsigned NOT NULL,
  `banner_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_categories`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `section` varchar(50) NOT NULL DEFAULT '',
  `image_position` varchar(30) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` varchar(50) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `jos_categories`
--

INSERT INTO `jos_categories` (`id`, `parent_id`, `title`, `name`, `alias`, `image`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`, `params`) VALUES
(1, 0, 'TEST', '', 'test', '', 'com_contact_details', 'left', '', 1, 62, '2012-05-22 05:24:10', NULL, 1, 0, 0, ''),
(3, 0, 'Демокатегирия', '', '2012-05-22-05-27-22', 'articles.jpg', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_components`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 06 2012 г., 13:11
--

CREATE TABLE IF NOT EXISTS `jos_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `menuid` int(11) unsigned NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_menu_link` varchar(255) NOT NULL DEFAULT '',
  `admin_menu_alt` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(50) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `admin_menu_img` varchar(255) NOT NULL DEFAULT '',
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_option` (`parent`,`option`(32))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Дамп данных таблицы `jos_components`
--

INSERT INTO `jos_components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
(1, 'Banners', '', 0, 0, '', 'Banner Management', 'com_banners', 0, 'js/ThemeOffice/component.png', 0, 'track_impressions=0\ntrack_clicks=0\ntag_prefix=\n\n', 1),
(2, 'Banners', '', 0, 1, 'option=com_banners', 'Active Banners', 'com_banners', 1, 'js/ThemeOffice/edit.png', 0, '', 1),
(3, 'Clients', '', 0, 1, 'option=com_banners&c=client', 'Manage Clients', 'com_banners', 2, 'js/ThemeOffice/categories.png', 0, '', 1),
(4, 'Web Links', 'option=com_weblinks', 0, 0, '', 'Manage Weblinks', 'com_weblinks', 0, 'js/ThemeOffice/component.png', 0, 'show_comp_description=1\ncomp_description=\nshow_link_hits=1\nshow_link_description=1\nshow_other_cats=1\nshow_headings=1\nshow_page_title=1\nlink_target=0\nlink_icons=\n\n', 1),
(5, 'Links', '', 0, 4, 'option=com_weblinks', 'View existing weblinks', 'com_weblinks', 1, 'js/ThemeOffice/edit.png', 0, '', 1),
(6, 'Categories', '', 0, 4, 'option=com_categories&section=com_weblinks', 'Manage weblink categories', '', 2, 'js/ThemeOffice/categories.png', 0, '', 1),
(7, 'Contacts', 'option=com_contact', 0, 0, '', 'Edit contact details', 'com_contact', 0, 'js/ThemeOffice/component.png', 1, 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1),
(8, 'Contacts', '', 0, 7, 'option=com_contact', 'Edit contact details', 'com_contact', 0, 'js/ThemeOffice/edit.png', 1, '', 1),
(9, 'Categories', '', 0, 7, 'option=com_categories&section=com_contact_details', 'Manage contact categories', '', 2, 'js/ThemeOffice/categories.png', 1, 'contact_icons=0\nicon_address=\nicon_email=\nicon_telephone=\nicon_fax=\nicon_misc=\nshow_headings=1\nshow_position=1\nshow_email=0\nshow_telephone=1\nshow_mobile=1\nshow_fax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsession=1\ncustomReply=0\n\n', 1),
(10, 'Polls', 'option=com_poll', 0, 0, 'option=com_poll', 'Manage Polls', 'com_poll', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(11, 'News Feeds', 'option=com_newsfeeds', 0, 0, '', 'News Feeds Management', 'com_newsfeeds', 0, 'js/ThemeOffice/component.png', 0, '', 1),
(12, 'Feeds', '', 0, 11, 'option=com_newsfeeds', 'Manage News Feeds', 'com_newsfeeds', 1, 'js/ThemeOffice/edit.png', 0, 'show_headings=1\nshow_name=1\nshow_articles=1\nshow_link=1\nshow_cat_description=1\nshow_cat_items=1\nshow_feed_image=1\nshow_feed_description=1\nshow_item_description=1\nfeed_word_count=0\n\n', 1),
(13, 'Categories', '', 0, 11, 'option=com_categories&section=com_newsfeeds', 'Manage Categories', '', 2, 'js/ThemeOffice/categories.png', 0, '', 1),
(14, 'User', 'option=com_user', 0, 0, '', '', 'com_user', 0, '', 1, '', 1),
(15, 'Search', 'option=com_search', 0, 0, 'option=com_search', 'Search Statistics', 'com_search', 0, 'js/ThemeOffice/component.png', 1, 'enabled=0\n\n', 1),
(16, 'Categories', '', 0, 1, 'option=com_categories&section=com_banner', 'Categories', '', 3, '', 1, '', 1),
(17, 'Wrapper', 'option=com_wrapper', 0, 0, '', 'Wrapper', 'com_wrapper', 0, '', 1, '', 1),
(18, 'Mail To', '', 0, 0, '', '', 'com_mailto', 0, '', 1, '', 1),
(19, 'Media Manager', '', 0, 0, 'option=com_media', 'Media Manager', 'com_media', 0, '', 1, 'upload_extensions=bmp,csv,doc,epg,gif,ico,jpg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,EPG,GIF,ICO,JPG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\nupload_maxsize=10000000\nfile_path=images\nimage_path=images/stories\nrestrict_uploads=1\nallowed_media_usergroup=3\ncheck_mime=1\nimage_extensions=bmp,gif,jpg,png\nignore_extensions=\nupload_mime=image/jpeg,image/gif,image/png,image/bmp,application/x-shockwave-flash,application/msword,application/excel,application/pdf,application/powerpoint,text/plain,application/x-zip\nupload_mime_illegal=text/html\nenable_flash=0\n\n', 1),
(20, 'Articles', 'option=com_content', 0, 0, '', '', 'com_content', 0, '', 1, 'show_noauth=0\nshow_title=1\nlink_titles=1\nshow_intro=1\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_author=1\nshow_create_date=1\nshow_modify_date=1\nshow_item_navigation=0\nshow_readmore=0\nshow_vote=0\nshow_icons=1\nshow_pdf_icon=1\nshow_print_icon=1\nshow_email_icon=1\nshow_hits=1\nfeed_summary=0\nfilter_tags=\nfilter_attritbutes=\n\n', 1),
(21, 'Configuration Manager', '', 0, 0, '', 'Configuration', 'com_config', 0, '', 1, '', 1),
(22, 'Installation Manager', '', 0, 0, '', 'Installer', 'com_installer', 0, '', 1, '', 1),
(23, 'Language Manager', '', 0, 0, '', 'Languages', 'com_languages', 0, '', 1, 'site=ru-RU\nadministrator=ru-RU\n\n', 1),
(24, 'Mass mail', '', 0, 0, '', 'Mass Mail', 'com_massmail', 0, '', 1, 'mailSubjectPrefix=\nmailBodySuffix=\n\n', 1),
(25, 'Menu Editor', '', 0, 0, '', 'Menu Editor', 'com_menus', 0, '', 1, '', 1),
(27, 'Messaging', '', 0, 0, '', 'Messages', 'com_messages', 0, '', 1, '', 1),
(28, 'Modules Manager', '', 0, 0, '', 'Modules', 'com_modules', 0, '', 1, '', 1),
(29, 'Plugin Manager', '', 0, 0, '', 'Plugins', 'com_plugins', 0, '', 1, '', 1),
(30, 'Template Manager', '', 0, 0, '', 'Templates', 'com_templates', 0, '', 1, '', 1),
(31, 'User Manager', '', 0, 0, '', 'Users', 'com_users', 0, '', 1, 'allowUserRegistration=1\nnew_usertype=Registered\nuseractivation=1\nfrontend_userparams=1\n\n', 1),
(32, 'Cache Manager', '', 0, 0, '', 'Cache', 'com_cache', 0, '', 1, '', 1),
(33, 'Control Panel', '', 0, 0, '', 'Control Panel', 'com_cpanel', 0, '', 1, '', 1),
(34, 'VirtueMart', 'option=com_virtuemart', 0, 0, 'option=com_virtuemart', 'VirtueMart', 'com_virtuemart', 0, '../components/com_virtuemart/shop_image/ps_image/menu_icon.png', 0, '', 1),
(35, 'virtuemart_version', '', 0, 9999, '', '', '', 0, '', 0, 'RELEASE=1.1.9\nDEV_STATUS=stable', 1),
(36, 'JComments', 'option=com_jcomments', 0, 0, 'option=com_jcomments', 'JComments', 'com_jcomments', 0, 'components/com_jcomments/assets/icon-16-jcomments.png', 0, '', 1),
(37, 'Comments', '', 0, 36, 'option=com_jcomments&task=comments', 'Comments', 'com_jcomments', 0, 'components/com_jcomments/assets/icon-16-comments.png', 0, '', 1),
(38, 'Settings', '', 0, 36, 'option=com_jcomments&task=settings', 'Settings', 'com_jcomments', 1, 'components/com_jcomments/assets/icon-16-settings.png', 0, '', 1),
(39, 'Smiles', '', 0, 36, 'option=com_jcomments&task=smiles', 'Smiles', 'com_jcomments', 2, 'components/com_jcomments/assets/icon-16-smiles.png', 0, '', 1),
(40, 'Subscriptions', '', 0, 36, 'option=com_jcomments&task=subscriptions', 'Subscriptions', 'com_jcomments', 3, 'components/com_jcomments/assets/icon-16-subscriptions.png', 0, '', 1),
(41, 'Custom_BBCode', '', 0, 36, 'option=com_jcomments&task=custombbcodes', 'Custom_BBCode', 'com_jcomments', 4, 'components/com_jcomments/assets/icon-16-custombbcodes.png', 0, '', 1),
(42, 'Blacklist', '', 0, 36, 'option=com_jcomments&task=blacklist', 'Blacklist', 'com_jcomments', 5, 'components/com_jcomments/assets/icon-16-blacklist.png', 0, '', 1),
(43, 'Import', '', 0, 36, 'option=com_jcomments&task=import', 'Import', 'com_jcomments', 6, 'components/com_jcomments/assets/icon-16-import.png', 0, '', 1),
(44, 'About', '', 0, 36, 'option=com_jcomments&task=about', 'About', 'com_jcomments', 7, 'components/com_jcomments/assets/icon-16-jcomments.png', 0, '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_contact_details`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 06 2012 г., 00:57
--

CREATE TABLE IF NOT EXISTS `jos_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `con_position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `imagepos` varchar(20) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `default_con` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_contact_details`
--

INSERT INTO `jos_contact_details` (`id`, `name`, `alias`, `con_position`, `address`, `suburb`, `state`, `country`, `postcode`, `telephone`, `fax`, `misc`, `image`, `imagepos`, `email_to`, `default_con`, `published`, `checked_out`, `checked_out_time`, `ordering`, `params`, `user_id`, `catid`, `access`, `mobile`, `webpage`) VALUES
(1, 'Контакты', 'testcontacts', '', 'ул. Хабаровская, д. 25', 'г.Хабаровск', 'Хабаровский Край', 'Россия', '', '(4212) т/ф   72-87-87,  72-87-88', '', '', 'clock.jpg', NULL, 'info@waterline.ru', 0, 1, 0, '0000-00-00 00:00:00', 1, 'show_name=1\nshow_position=0\nshow_email=0\nshow_street_address=1\nshow_suburb=1\nshow_state=1\nshow_postcode=0\nshow_country=1\nshow_telephone=1\nshow_mobile=0\nshow_fax=0\nshow_webpage=0\nshow_misc=0\nshow_image=0\nallow_vcard=0\ncontact_icons=1\nicon_address=\nicon_email=\nicon_telephone=\nicon_mobile=\nicon_fax=\nicon_misc=\nshow_email_form=1\nemail_description=\nshow_email_copy=1\nbanned_email=\nbanned_subject=\nbanned_text=', 0, 1, 0, '', ''),
(2, 'О компании', 'about', '', '', '', '', '', '', '', '', 'Американская компания «Террафьюджиа» начинает серийное производство легковых авиа-автомобилей. Испытания новинки прошли успешно. Мировая премьера чуда техники состоится в пятницу на «Нью-йоркском автосалоне», сама же летающая машина поступит в продажу в будущем году.\r\nАвто, рассчитанное на двух человек, взлетает с дороги при наборе максимальной скорости после пробега восьмисот метров. Потолок полета – пятьсот метров, что для автомобилистов многих стран исключает необходимость получения разрешений на полеты от властей. Например, в США и государствах-членах ЕС полеты малых летательных аппаратов без уведомления соответствующих официальных инстанций \r\nразрешены именно до этой высоты. Размах крыльев новинки составляет восемь метров. После посадки крылья складываются вдоль кузова. Цена автомобиля составляет 279 тысяч долларов. Более ста человек уже внесли залоговую сумму и в будущем году получат «Транзишн».', 'key.jpg', NULL, '', 0, 1, 0, '0000-00-00 00:00:00', 2, 'show_name=0\nshow_position=0\nshow_email=0\nshow_street_address=0\nshow_suburb=0\nshow_state=0\nshow_postcode=0\nshow_country=0\nshow_telephone=0\nshow_mobile=0\nshow_fax=0\nshow_webpage=0\nshow_misc=1\nshow_image=1\nallow_vcard=0\ncontact_icons=2\nicon_address=\nicon_email=\nicon_telephone=\nicon_mobile=\nicon_fax=\nicon_misc=\nshow_email_form=1\nemail_description=\nshow_email_copy=1\nbanned_email=\nbanned_subject=\nbanned_text=', 0, 1, 0, '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_content`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 06 2012 г., 15:04
--

CREATE TABLE IF NOT EXISTS `jos_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `sectionid` int(11) unsigned NOT NULL DEFAULT '0',
  `mask` int(11) unsigned NOT NULL DEFAULT '0',
  `catid` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` text NOT NULL,
  `version` int(11) unsigned NOT NULL DEFAULT '1',
  `parentid` int(11) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_section` (`sectionid`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `jos_content`
--

INSERT INTO `jos_content` (`id`, `title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`) VALUES
(1, 'Демонстрация новости', '2012-05-22-05-34-21', '', '<p><img src="images/stories/articles.jpg" border="0" width="128" height="96" style="border: 5px solid black; float: left; margin: 5px;" />Американская компания «Террафьюджиа» начинает серийное производство легковых авиа-автомобилей. Испытания новинки прошли успешно. Мировая премьера чуда техники состоится в пятницу на «Нью-йоркском автосалоне», сама же летающая машина поступит в продажу в будущем году.</p>\r\n<p>Авто, рассчитанное на двух человек, взлетает с дороги при наборе максимальной скорости после пробега восьмисот метров. Потолок полета – пятьсот метров, что для автомобилистов многих стран исключает необходимость получения разрешений на полеты от властей. Например, в США и государствах-членах ЕС полеты малых летательных аппаратов без уведомления соответствующих официальных инстанций</p>\r\n<p>разрешены именно до этой высоты. Размах крыльев новинки составляет восемь метров. После посадки крылья складываются вдоль кузова. Цена автомобиля составляет 279 тысяч долларов. Более ста человек уже внесли залоговую сумму и в будущем году получат «Транзишн».</p>\r\n<p> </p>\r\n', '\r\n<p>Американская компания «Террафьюджиа» начинает серийное производство легковых авиа-автомобилей. Испытания новинки прошли успешно. Мировая премьера чуда техники состоится в пятницу на «Нью-йоркском автосалоне», сама же летающая машина поступит в продажу в будущем году.</p>\r\n<p>Авто, рассчитанное на двух человек, взлетает с дороги при наборе максимальной скорости после пробега восьмисот метров. Потолок полета – пятьсот метров, что для автомобилистов многих стран исключает необходимость получения разрешений на полеты от властей. Например, в США и государствах-членах ЕС полеты малых летательных аппаратов без уведомления соответствующих официальных инстанций</p>\r\n<p>разрешены именно до этой высоты. Размах крыльев новинки составляет восемь метров. После посадки крылья складываются вдоль кузова. Цена автомобиля составляет 279 тысяч долларов. Более ста человек уже внесли залоговую сумму и в будущем году получат «Транзишн».</p>\r\n<p> </p>', -2, 0, 0, 0, '2012-05-22 05:29:39', 62, '', '2012-06-01 13:37:20', 62, 0, '0000-00-00 00:00:00', '2012-05-22 05:29:39', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=0\nlink_section=0\nshow_category=0\nlink_category=0\nshow_vote=0\nshow_author=0\nshow_create_date=0\nshow_modify_date=0\nshow_pdf_icon=0\nshow_print_icon=0\nshow_email_icon=0\nlanguage=\nkeyref=\nreadmore=', 4, 0, 1, '', '', 0, 20, 'robots=\nauthor='),
(2, 'Инновации в спинальной артропластике продолжаются', '2012-06-04-08-02-44', '', '<p><img src="images/stories/news/04.06.2012.jpg" border="0" width="320" height="240" style="border: 5px solid black; float: left; margin: 5px;" /> <strong style="color: #000000; font-family: Verdana, Geneva, sans-serif; line-height: 18px;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Поиски новых материалов</span></strong></p>\r\n<p> </p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="color: #000000;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Замещение межпозвонкового диска или </span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"> вот уже более двух десятилетий остается центральным компонентом спинальной артропластики в Европе, и инновации в этой области активно развиваются. В США, как признает д-р Зиглер, развитие метода идет куда более медленными темпами – главным образом из-за недостатка покрытия этой операции страховой медициной. Тем не менее, с расширением базы данных по результатам таких операций и ростом числа научных публикаций, указывающих на их положительный исход, страховое покрытие постепенно растет, особенно для цервикального </span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"> (в области шейных позвонков).</span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"><span style="color: #000000;"> </span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="color: #000000;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Развитие следующего поколения </span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"> побудило производителей медицинских технологий начать поиск новых материалов, таких как титан, высокоплотный биосовместимый полиуретан и керамические композиционные сплавы алюминия и циркония. Эти материалы улучшают пост-операционную МРТ. «Кроме того, имплантаты с меняющимся центром вращения и демпфирующей способностью могут еще больше улучшить и без того хорошие результаты», – говорит д-р Зиглер. В их конструкции применено пористое внешнее покрытие для лучшей интеграции и углеродное внутреннее покрытие для износоустойчивости.</span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="color: #000000;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"> </span></span></p>\r\n', '\r\n<p> </p>\r\n<p> </p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU"> </span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="color: #000000;"><strong><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Конференция </span></strong><strong><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ISASS</span></strong><strong><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">-2012 – демонстрация инноваций</span></strong></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="color: #000000;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Конференция Международной ассоциации спинальной хирургии (</span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ISSAS</span></span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">) в марте 2012 года стала отражением эволюции спинальной артропластики и будущей революции в лечении болезней спины и позвоночника. Как считает д-р Зиглер, на конференции были продемонстрированы усовершенствованные материалы второго и третьего поколения </span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="EN-IE">ADR</span></span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="RU">, которые могут обеспечить распределение осевой нагрузки, максимально приближенное к функциям естественного позвоночника. Он также с воодушевлением говорит о тех перспективах, которые биологическое вмешательство открывает в диагностике и терапевтическом лечении больных.</span></span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU"> </span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"><span style="color: #000000;"> </span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"><span style="color: #000000;">Особый интерес вызвали результаты небольшого исследования по инъекции культивированных ювенильных хрящевых клеток для восстановления студенистого ядра поясничных дисков. Хотя главной задачей этого исследования на примере всего лишь 15 пациентов было выявление возможности применения метода, 60 процентов пациентов показали улучшение МРТ. Ни у одного из них не было выявлено неврологического ухудшения или иммунологической реакции на хондроцит.</span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span class="st1"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"><span style="color: #000000;"> </span></span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">«Я считаю, что биологическое вмешательство произведет революцию в наших методах лечения, – говорит д-р Зиглер. – У нас появятся диагностические инструменты, способные различать естественную дегенерацию дисков и их болезненное функциональное расстройство». По его мнению, более совершенные диагностические инструменты помогут устранить барьеры по покрытию </span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="EN-IE">ADR</span></span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="RU"> страховыми компаниями.</span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="RU"><br /></span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span class="st1"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"><span style="color: #000000;"> </span></span></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span class="st1"><strong><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"><span style="color: #000000;">Многообещающие результаты применения </span></span></strong></span><span class="st1"><strong><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="EN-IE">ADR</span></strong></span><span class="st1"><strong><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="RU"> по незарегистрированным показаниям</span></strong></span><span class="st1"><strong><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"> </span></strong></span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">Первые результаты цервикальных </span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU"> показывали до 30 процентов самопроизвольного слияния, но, как сообщает д-р Зиглер, эти показатели существенно снизились с улучшением техники и пост-оперативного назначения н<span style="background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; background-position: initial initial; background-repeat: initial initial;">естероидных противовоспалительных препаратов </span>(</span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="EN-GB">NSAIDs</span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">). В одном из испытаний, по его словам, уровень г<span style="background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; background-position: initial initial; background-repeat: initial initial;">етеротопической</span></span><span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: black; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB"> </span></span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: black; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="RU">оссификации удалось понизить до однозначных цифр. Он также отмечает: «К счастью, даже те пациенты, у которых в принципе происходит автоматическое слияние, демонстрировали прекрасные результаты, вполне сопоставимые с </span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB">ACDF</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB"> </span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="RU">(</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB">anterior</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB"> </span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB">cervical</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB"> </span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB">discectomy</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB">and</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB"> </span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB">fusion</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="EN-GB"> </span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">– </span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: #333333; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="RU">удаление диска, грыжи диска с последующим спондилодезом различными имплантатами)».</span><span style="font-family: Arial, sans-serif; background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; color: black; font-size: 12pt; background-position: initial initial; background-repeat: initial initial;" lang="RU"> Просматриваются и другие возможности использования. Интересные результаты получают эксперты по спинальной артропластике, изучающие использование </span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="EN-IE">ADR</span></span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="RU"> по незарегистрированным показаниям или в неутвержденных ситуациях. «На уровнях, где ранее происходило слияние с соседними дисками, </span></span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="EN-IE">ADR</span></span><span class="st1"><span style="font-family: Arial, sans-serif; color: #222222; font-size: 12pt;" lang="RU">способно дать лучшие результаты [по сохранению] оставшихся сегментов, - говорит д-р Зиглер. – Точно так же при многоуровневом заболевании дисков шейных позвонков оптимальным решением может оказаться многоуровневое </span></span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU"> или гибридные конструкции».</span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">Один цервикальный диск, проходящий в настоящее время испытания в США, может уже в следующем году получить от <span style="background-image: initial; background-attachment: initial; background-origin: initial; background-clip: initial; background-color: white; background-position: initial initial; background-repeat: initial initial;">Управления по контролю качества пищевых продуктов и лекарственных препаратов (</span></span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="EN-GB">FDA</span><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU">) утверждение на многоуровневое использование.</span></p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="font-family: Arial, sans-serif; color: black; font-size: 12pt;" lang="RU"> </span></p>\r\n<p><em style="color: #000000; font-family: ''Times New Roman''; line-height: normal; font-size: medium;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Интервью у д-ра Зиглера брала Мари Гетинс из информационного ресурса </span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB"><span style="font-family: Arial;">Mediscribe</span></span></em></p>\r\n<p> </p>', 1, 0, 0, 0, '2012-06-04 07:43:15', 62, '', '2012-06-05 14:48:37', 62, 0, '0000-00-00 00:00:00', '2012-06-04 07:43:15', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=ru-RU\nkeyref=\nreadmore=', 3, 0, 6, '', '', 0, 52, 'robots=\nauthor='),
(3, 'tata', 'tata', '', '<p>asdsadas</p>\r\n<p> </p>\r\n', '\r\n<p>asdsadas</p>\r\n<p> </p>', 0, 0, 0, 0, '2012-06-05 14:58:44', 62, '', '2012-06-06 15:02:29', 62, 0, '0000-00-00 00:00:00', '2012-06-05 14:58:44', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=ru-RU\nkeyref=\nreadmore=', 4, 0, 5, '', '', 0, 4, 'robots=\nauthor='),
(4, 'News 3', 'news-3', '', '<p><strong style="color: #000000; font-family: Verdana, Geneva, sans-serif; line-height: 18px;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">оиски новых материалов</span></strong></p>\r\n<p> </p>\r\n<p class="MsoNormal" style="font-family: Verdana, Geneva, sans-serif; color: #505050; line-height: 18px; margin-top: 0cm; margin-right: 0cm; margin-bottom: 0pt; margin-left: 0cm;"><span style="color: #000000;"><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">Замещение межпозвонкового диска или </span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU">вот уже более двух десятилетий остается центральным компонентом спинальной артропластики в Европе, и инновации в этой области активно развиваются. В США, как признает д-р Зиглер, развитие метода идет куда более медленными темпами – главным образом из-за недостатка покрытия этой операции страховой медициной. Тем не менее, с расширением базы данных по результатам таких операций и ростом числа научных публикаций, указывающих на их положительный исход, страховое покрытие постепенно растет, особенно для цервикального </span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="EN-GB">ADR</span><span style="font-family: Arial, sans-serif; font-size: 12pt;" lang="RU"> (в области шейных позвонков).</span></span></p>\r\n<p> </p>\r\n<p> </p>', '', 1, 0, 0, 0, '2012-06-05 15:00:01', 62, '', '2012-06-06 15:04:41', 62, 0, '0000-00-00 00:00:00', '2012-06-05 15:00:01', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=\nkeyref=\nreadmore=', 7, 0, 4, '', '', 0, 1, 'robots=\nauthor='),
(5, 'lasttest', 'lasttest', '', '<p>123</p>\r\n<p> </p>\r\n', '\r\n<p>789</p>\r\n<p> </p>', 0, 0, 0, 0, '2012-06-06 14:19:45', 62, '', '2012-06-06 15:01:41', 62, 0, '0000-00-00 00:00:00', '2012-06-06 14:19:45', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=\nkeyref=\nreadmore=', 3, 0, 3, '', '', 0, 3, 'robots=\nauthor='),
(6, 'tataetd', 'tataetd', '', '<p>tadst</p>', '', 0, 0, 0, 0, '2012-06-06 14:56:38', 62, '', '2012-06-06 15:02:01', 62, 0, '0000-00-00 00:00:00', '2012-06-06 14:56:38', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=\nkeyref=\nreadmore=', 3, 0, 2, '', '', 0, 0, 'robots=\nauthor=');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_content_frontpage`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 06 2012 г., 15:02
--

CREATE TABLE IF NOT EXISTS `jos_content_frontpage` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_content_frontpage`
--

INSERT INTO `jos_content_frontpage` (`content_id`, `ordering`) VALUES
(1, 3),
(2, 2),
(4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_content_rating`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_content_rating` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(11) unsigned NOT NULL DEFAULT '0',
  `rating_count` int(11) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_aro`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_value` varchar(240) NOT NULL DEFAULT '0',
  `value` varchar(240) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `jos_section_value_value_aro` (`section_value`(100),`value`(100)),
  KEY `jos_gacl_hidden_aro` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_core_acl_aro`
--

INSERT INTO `jos_core_acl_aro` (`id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', '62', 0, 'Administrator', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_aro_groups`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `jos_gacl_parent_id_aro_groups` (`parent_id`),
  KEY `jos_gacl_lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Дамп данных таблицы `jos_core_acl_aro_groups`
--

INSERT INTO `jos_core_acl_aro_groups` (`id`, `parent_id`, `name`, `lft`, `rgt`, `value`) VALUES
(17, 0, 'ROOT', 1, 22, 'ROOT'),
(28, 17, 'USERS', 2, 21, 'USERS'),
(29, 28, 'Public Frontend', 3, 12, 'Public Frontend'),
(18, 29, 'Registered', 4, 11, 'Registered'),
(19, 18, 'Author', 5, 10, 'Author'),
(20, 19, 'Editor', 6, 9, 'Editor'),
(21, 20, 'Publisher', 7, 8, 'Publisher'),
(30, 28, 'Public Backend', 13, 20, 'Public Backend'),
(23, 30, 'Manager', 14, 19, 'Manager'),
(24, 23, 'Administrator', 15, 18, 'Administrator'),
(25, 24, 'Super Administrator', 16, 17, 'Super Administrator');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_aro_map`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_map` (
  `acl_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(230) NOT NULL DEFAULT '0',
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`acl_id`,`section_value`,`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_aro_sections`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(230) NOT NULL DEFAULT '',
  `order_value` int(11) NOT NULL DEFAULT '0',
  `name` varchar(230) NOT NULL DEFAULT '',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `jos_gacl_value_aro_sections` (`value`),
  KEY `jos_gacl_hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_core_acl_aro_sections`
--

INSERT INTO `jos_core_acl_aro_sections` (`id`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', 1, 'Users', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_acl_groups_aro_map`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_groups_aro_map` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `section_value` varchar(240) NOT NULL DEFAULT '',
  `aro_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`,`section_value`,`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_core_acl_groups_aro_map`
--

INSERT INTO `jos_core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`) VALUES
(25, '', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_log_items`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_log_items` (
  `time_stamp` date NOT NULL DEFAULT '0000-00-00',
  `item_table` varchar(50) NOT NULL DEFAULT '',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_core_log_searches`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_groups`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_groups` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_groups`
--

INSERT INTO `jos_groups` (`id`, `name`) VALUES
(0, 'Public'),
(1, 'Registered'),
(2, 'Special');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 06 2012 г., 10:20
--

CREATE TABLE IF NOT EXISTS `jos_jcomments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `thread_id` int(11) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `object_id` int(11) unsigned NOT NULL DEFAULT '0',
  `object_group` varchar(255) NOT NULL DEFAULT '',
  `object_params` text NOT NULL,
  `lang` varchar(255) NOT NULL DEFAULT '',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `ip` varchar(39) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isgood` smallint(5) NOT NULL DEFAULT '0',
  `ispoor` smallint(5) NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `source` varchar(255) NOT NULL DEFAULT '',
  `source_id` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`userid`),
  KEY `idx_source` (`source`),
  KEY `idx_email` (`email`),
  KEY `idx_lang` (`lang`),
  KEY `idx_subscribe` (`subscribe`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_object` (`object_id`,`object_group`,`published`,`date`),
  KEY `idx_path` (`path`,`level`),
  KEY `idx_thread` (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `jos_jcomments`
--

INSERT INTO `jos_jcomments` (`id`, `parent`, `thread_id`, `path`, `level`, `object_id`, `object_group`, `object_params`, `lang`, `userid`, `name`, `username`, `email`, `homepage`, `title`, `comment`, `ip`, `date`, `isgood`, `ispoor`, `published`, `deleted`, `subscribe`, `source`, `source_id`, `checked_out`, `checked_out_time`, `editor`) VALUES
(7, 0, 0, '0', 0, 6, 'com_virtuemart', '', 'en-GB', 62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '', '', 'Тестовое стосимвольное сообщение. Тестовое стосимвольное сообщение. Тестовое стосимвольное сообщение. Тестовое стосимвольное сообщение.', '', '2012-06-04 08:04:00', 0, 0, 1, 0, 0, 'virtuemart', 0, 0, '0000-00-00 00:00:00', ''),
(5, 0, 0, '0', 0, 18, 'com_virtuemart', '', 'en-GB', 62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '', '', 'ntjfj', '92.37.190.128', '2012-06-04 16:37:53', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(6, 0, 0, '0', 0, 19, 'com_virtuemart', '', 'en-GB', 62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '', '', 'That so wonderful! That so wonderful! That so wonderful! That so wonderful! That so wonderful! That so wonderful!', '', '2012-06-04 07:01:00', 0, 0, 1, 0, 0, 'virtuemart', 0, 0, '0000-00-00 00:00:00', ''),
(4, 0, 0, '0', 0, 2, 'com_content', '', 'en-GB', 0, 'Федосей', 'Федосей', 'tjfj@mail.ru', '', '', 'Test com', '92.37.190.128', '2012-06-04 15:37:40', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(8, 0, 0, '0', 0, 18, 'com_virtuemart', '', 'en-GB', 62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '', '', '122222222222222222222222222222222222222221212121212121212121212121212121212121212121212121212121221212', '', '2012-06-04 08:30:00', 2, 0, 1, 0, 0, 'virtuemart', 0, 0, '0000-00-00 00:00:00', ''),
(10, 0, 0, '0', 0, 22, 'com_virtuemart', '', 'ru-RU', 62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '', '', 'My comments', '92.37.191.100', '2012-06-06 09:54:49', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(11, 0, 0, '0', 0, 22, 'com_virtuemart', '', 'ru-RU', 62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '', '', '[quote name="Administrator"]My comments[/quote]<br />Mister Salyery sends regargs.', '92.37.191.100', '2012-06-06 09:55:49', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(12, 0, 0, '0', 0, 21, 'com_virtuemart', '', 'ru-RU', 0, 'Федосей', 'Федосей', 'tjfj@mail.ru', '', '', '345', '92.37.191.100', '2012-06-06 09:58:14', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(13, 0, 0, '0', 0, 21, 'com_virtuemart', '', 'ru-RU', 0, 'uio', 'uio', 'tjfj@mail.ru', '', '', 'Long commentLong commentLong commentLong commentLong commentLong commentLong commentLong commentLong commentLong comment', '92.37.191.100', '2012-06-06 10:03:34', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(14, 0, 0, '0', 0, 2, 'com_content', '', 'ru-RU', 0, 'uio', 'uio', 'tjfj@mail.ru', '', '', '234', '92.37.191.100', '2012-06-06 10:16:46', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', ''),
(15, 0, 0, '0', 0, 2, 'com_content', '', 'ru-RU', 0, 'Урал', 'Урал', 'tjfj@mail.ru', '', '', '90-8-', '92.37.191.100', '2012-06-06 10:20:25', 0, 0, 1, 0, 0, '', 0, 0, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_blacklist`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 04 2012 г., 12:59
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(39) NOT NULL DEFAULT '',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reason` tinytext NOT NULL,
  `notes` tinytext NOT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_custom_bbcodes`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 04 2012 г., 12:59
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_custom_bbcodes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `simple_pattern` varchar(255) NOT NULL DEFAULT '',
  `simple_replacement_html` text NOT NULL,
  `simple_replacement_text` text NOT NULL,
  `pattern` varchar(255) NOT NULL DEFAULT '',
  `replacement_html` text NOT NULL,
  `replacement_text` text NOT NULL,
  `button_acl` text NOT NULL,
  `button_open_tag` varchar(16) NOT NULL DEFAULT '',
  `button_close_tag` varchar(16) NOT NULL DEFAULT '',
  `button_title` varchar(255) NOT NULL DEFAULT '',
  `button_prompt` varchar(255) NOT NULL DEFAULT '',
  `button_image` varchar(255) NOT NULL DEFAULT '',
  `button_css` varchar(255) NOT NULL DEFAULT '',
  `button_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `jos_jcomments_custom_bbcodes`
--

INSERT INTO `jos_jcomments_custom_bbcodes` (`id`, `name`, `simple_pattern`, `simple_replacement_html`, `simple_replacement_text`, `pattern`, `replacement_html`, `replacement_text`, `button_acl`, `button_open_tag`, `button_close_tag`, `button_title`, `button_prompt`, `button_image`, `button_css`, `button_enabled`, `ordering`, `published`) VALUES
(1, 'YouTube Video', '[youtube]http://www.youtube.com/watch?v={IDENTIFIER}[/youtube]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/{IDENTIFIER}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/{IDENTIFIER}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.youtube.com/watch?v={IDENTIFIER}', '\\[youtube\\]http\\:\\/\\/www\\.youtube\\.com\\/watch\\?v\\=([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/youtube\\]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/${1}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/${1}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.youtube.com/watch?v=${1}', '29,18,19,20,21,23,24,25', '[youtube]', '[/youtube]', 'YouTube Video', '', '', 'bbcode-youtube', 1, 1, 1),
(2, 'YouTube Video (short syntax)', '[youtube]{IDENTIFIER}[/youtube]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/{IDENTIFIER}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/{IDENTIFIER}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.youtube.com/watch?v={IDENTIFIER}', '\\[youtube\\]([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/youtube\\]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/${1}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/${1}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.youtube.com/watch?v=${1}', '29,18,19,20,21,23,24,25', '', '', '', '', '', '', 0, 2, 1),
(3, 'YouTube Video (full syntax)', '[youtube]http://www.youtube.com/watch?v={IDENTIFIER}{TEXT}[/youtube]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/{IDENTIFIER}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/{IDENTIFIER}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.youtube.com/watch?v={IDENTIFIER}', '\\[youtube\\]http\\:\\/\\/www\\.youtube\\.com\\/watch\\?v\\=([A-Za-z0-9-_]+)([\\w0-9-\\+\\=\\!\\?\\(\\)\\[\\]\\{\\}\\&\\%\\*\\#\\.,_ ]+)\\[\\/youtube\\]', '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/${1}"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/${1}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.youtube.com/watch?v=${1}', '29,18,19,20,21,23,24,25', '[youtube]', '[/youtube]', 'YouTube Video', '', '', '', 0, 3, 1),
(4, 'Google Video', '[google]http://video.google.com/videoplay?docid={IDENTIFIER}[/google]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId={IDENTIFIER}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid={IDENTIFIER}', '\\[google\\]http\\:\\/\\/video\\.google\\.com\\/videoplay\\?docid\\=([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/google\\]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=${1}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid=${1}', '29,18,19,20,21,23,24,25', '[google]', '[/google]', 'Google Video', '', '', 'bbcode-google', 1, 4, 1),
(5, 'Google Video (short syntax)', '[google]{IDENTIFIER}[/google]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId={IDENTIFIER}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid={IDENTIFIER}', '\\[google\\]([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/google\\]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=${1}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid=${1}', '29,18,19,20,21,23,24,25', '', '', '', '', '', '', 0, 5, 1),
(6, 'Google Video (alternate syntax)', '[gv]http://video.google.com/videoplay?docid={IDENTIFIER}[/gv]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId={IDENTIFIER}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid={IDENTIFIER}', '\\[gv\\]http\\:\\/\\/video\\.google\\.com\\/videoplay\\?docid\\=([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/gv\\]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=${1}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid=${1}', '29,18,19,20,21,23,24,25', '', '', '', '', '', '', 0, 6, 1),
(7, 'Google Video (alternate syntax)', '[googlevideo]http://video.google.com/videoplay?docid={IDENTIFIER}[/googlevideo]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId={IDENTIFIER}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid={IDENTIFIER}', '\\[googlevideo\\]http\\:\\/\\/video\\.google\\.com\\/videoplay\\?docid\\=([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/googlevideo\\]', '<embed style="width:425px; height:350px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=${1}" flashvars=""></embed>', 'http://video.google.com/videoplay?docid=${1}', '29,18,19,20,21,23,24,25', '', '', '', '', '', '', 0, 7, 1),
(8, 'Facebook Video', '[fv]http://www.facebook.com/video/video.php?v={IDENTIFIER}[/fv]', '<object width="425" height="350"><param name="movie" value="http://www.facebook.com/v/{IDENTIFIER}"></param><param name="wmode" value="transparent"></param><embed src="http://www.facebook.com/v/{IDENTIFIER}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.facebook.com/video/video.php?v={IDENTIFIER}', '\\[fv\\]http\\:\\/\\/www\\.facebook\\.com\\/video\\/video\\.php\\?v\\=([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/fv\\]', '<object width="425" height="350"><param name="movie" value="http://www.facebook.com/v/${1}"></param><param name="wmode" value="transparent"></param><embed src="http://www.facebook.com/v/${1}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.facebook.com/video/video.php?v=${1}', '29,18,19,20,21,23,24,25', '[fv]', '[/fv]', 'Facebook Video', '', '', 'bbcode-facebook', 1, 8, 1),
(9, 'Facebook Video (short syntax)', '[fv]{IDENTIFIER}[/fv]', '<object width="425" height="350"><param name="movie" value="http://www.facebook.com/v/{IDENTIFIER}"></param><param name="wmode" value="transparent"></param><embed src="http://www.facebook.com/v/{IDENTIFIER}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.facebook.com/video/video.php?v={IDENTIFIER}', '\\[fv\\]([A-Za-z0-9-_]+)([A-Za-z0-9\\%\\&\\=\\#]*?)\\[\\/fv\\]', '<object width="425" height="350"><param name="movie" value="http://www.facebook.com/v/${1}"></param><param name="wmode" value="transparent"></param><embed src="http://www.facebook.com/v/${1}" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', 'http://www.facebook.com/video/video.php?v=${1}', '29,18,19,20,21,23,24,25', '', '', '', '', '', '', 0, 9, 1),
(10, 'Wiki', '[wiki]{SIMPLETEXT}[/wiki]', '<a href="http://www.wikipedia.org/wiki/{SIMPLETEXT}" title="{SIMPLETEXT}" target="_blank">{SIMPLETEXT}</a>', '{SIMPLETEXT}', '\\[wiki\\]([A-Za-z0-9\\-\\+\\.,_ ]+)\\[\\/wiki\\]', '<a href="http://www.wikipedia.org/wiki/${1}" title="${1}" target="_blank">${1}</a>', '${1}', '29,18,19,20,21,23,24,25', '[wiki]', '[/wiki]', 'Wikipedia', '', '', 'bbcode-wiki', 1, 10, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_objects`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 06 2012 г., 14:22
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_objects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL DEFAULT '0',
  `object_group` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `expired` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_object` (`object_id`,`object_group`,`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `jos_jcomments_objects`
--

INSERT INTO `jos_jcomments_objects` (`id`, `object_id`, `object_group`, `lang`, `title`, `link`, `access`, `userid`, `expired`, `modified`) VALUES
(1, 2, 'com_content', 'en-GB', 'Инновации в спинальной артропластике продолжаются', '/paradigm/Puls/index.php?option=com_content&amp;view=article&amp;id=2:2012-06-04-08-02-44', 0, 62, 0, '2012-06-04 15:37:40'),
(2, 18, 'com_virtuemart', 'en-GB', 'Interacoustics AD 226', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=6&amp;product_id=18', 0, 0, 0, '2012-06-04 16:37:53'),
(3, 6, 'com_virtuemart', 'en-GB', 'Hammer', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=1&amp;product_id=6', 0, 0, 0, '2012-06-04 17:48:06'),
(4, 21, 'com_virtuemart', 'en-GB', 'SCHILLER CARDIOVIT AT-1 с сумкой для переноски', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=8&amp;product_id=21', 0, 0, 0, '2012-06-05 06:28:11'),
(5, 22, 'com_virtuemart', 'en-GB', 'Unsigned', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=8&amp;product_id=22', 0, 0, 0, '2012-06-05 08:21:05'),
(6, 2, 'com_content', 'ru-RU', 'Инновации в спинальной артропластике продолжаются', '/paradigm/Puls/index.php?option=com_content&amp;view=article&amp;id=2:2012-06-04-08-02-44', 0, 62, 0, '2012-06-06 10:20:25'),
(7, 21, 'com_virtuemart', 'ru-RU', 'SCHILLER CARDIOVIT AT-1 с сумкой для переноски', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=8&amp;product_id=21', 0, 0, 0, '2012-06-06 10:03:34'),
(8, 18, 'com_virtuemart', 'ru-RU', 'Interacoustics AD 226', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=6&amp;product_id=18', 0, 0, 0, '2012-06-05 22:25:48'),
(9, 3, 'com_content', 'ru-RU', 'tata', '/paradigm/Puls/index.php?option=com_content&amp;view=article&amp;id=3%3Atata&amp;Itemid=1', 0, 62, 0, '2012-06-05 22:29:45'),
(10, 22, 'com_virtuemart', 'ru-RU', 'Unsigned', 'http://coreatrade.com/paradigm/Puls/index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=flypage.tpl&amp;category_id=8&amp;product_id=22', 0, 0, 0, '2012-06-06 09:55:49'),
(11, 4, 'com_content', 'ru-RU', 'News 3', '/paradigm/Puls/index.php?option=com_content&amp;view=article&amp;id=4:news-3', 0, 62, 0, '2012-06-06 14:10:07'),
(12, 5, 'com_content', 'ru-RU', 'lasttest', '/paradigm/Puls/index.php?option=com_content&amp;view=article&amp;id=5%3Alasttest&amp;Itemid=1', 0, 62, 0, '2012-06-06 14:22:08');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_reports`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 04 2012 г., 12:59
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commentid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(39) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reason` tinytext NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_settings`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 06 2012 г., 10:24
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_settings` (
  `component` varchar(50) NOT NULL DEFAULT '',
  `lang` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`component`,`lang`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_jcomments_settings`
--

INSERT INTO `jos_jcomments_settings` (`component`, `lang`, `name`, `value`) VALUES
('', '', 'enable_username_check', '1'),
('', '', 'username_maxlength', '20'),
('', '', 'forbidden_names', 'administrator,moderator'),
('', '', 'author_name', '2'),
('', '', 'author_email', '2'),
('', '', 'author_homepage', '1'),
('', '', 'comment_maxlength', '1000'),
('', '', 'comment_minlength', '0'),
('', '', 'word_maxlength', '15'),
('', '', 'link_maxlength', '30'),
('', '', 'flood_time', '30'),
('', '', 'enable_notification', '0'),
('', '', 'notification_email', ''),
('', '', 'template', 'default'),
('', '', 'enable_smiles', '1'),
('', '', 'comments_per_page', '10'),
('', '', 'comments_page_limit', '15'),
('', '', 'comments_pagination', 'both'),
('', '', 'comments_order', 'DESC'),
('', '', 'show_commentlength', '1'),
('', '', 'enable_nested_quotes', '1'),
('', '', 'enable_rss', '1'),
('', '', 'censor_replace_word', '[censored]'),
('', '', 'can_comment', '29,18,19,20,21,23,24,25'),
('', '', 'can_reply', '29,18,19,20,21,23,24,25'),
('', '', 'show_policy', '29,18'),
('', '', 'enable_captcha', '29'),
('', '', 'floodprotection', '29,18,19,20'),
('', '', 'enable_comment_length_check', '29,18'),
('', '', 'autopublish', '29,18,19,20,21,23,24,25'),
('', '', 'autolinkurls', '18,19,20,21,23,24,25'),
('', '', 'enable_subscribe', '29,18,19,20,21,23,24,25'),
('', '', 'enable_gravatar', ''),
('', '', 'can_view_homepage', '18,19,20,21,23,24,25'),
('', '', 'can_publish', '21,23,24,25'),
('', '', 'can_publish_for_my_object', ''),
('', '', 'can_view_email', '23,24,25'),
('', '', 'can_edit', '23,24,25'),
('', '', 'can_edit_own', '18,19,20,21,23,24,25'),
('', '', 'can_edit_for_my_object', ''),
('', '', 'can_delete', '23,24,25'),
('', '', 'can_delete_own', '23,24,25'),
('', '', 'can_delete_for_my_object', ''),
('', '', 'enable_bbcode_b', '18,19,20,21,23,24,25'),
('', '', 'enable_bbcode_i', '18,19,20,21,23,24,25'),
('', '', 'enable_bbcode_u', '18,19,20,21,23,24,25'),
('', '', 'enable_bbcode_s', '18,19,20,21,23,24,25'),
('', '', 'enable_bbcode_url', '18,19,20,21,23,24,25'),
('', '', 'message_banned', ''),
('', '', 'enable_bbcode_img', '18,19,20,21,23,24,25'),
('', '', 'max_comments_per_object', '0'),
('', '', 'enable_bbcode_list', '18,19,20,21,23,24,25'),
('', '', 'captcha_engine', 'kcaptcha'),
('', '', 'enable_bbcode_hide', '18,19,20,21,23,24,25'),
('', '', 'form_position', '0'),
('', '', 'can_view_ip', '24,25'),
('', '', 'enable_categories', ''),
('', '', 'emailprotection', '29'),
('', '', 'enable_comment_maxlength_check', ''),
('', '', 'enable_autocensor', '29'),
('', '', 'badwords', ''),
('', '', 'smiles', ':D	laugh.gif\n:lol:	lol.gif\n:-)	smile.gif\n;-)	wink.gif\n8)	cool.gif\n:-|	normal.gif\n:-*	whistling.gif\n:oops:	redface.gif\n:sad:	sad.gif\n:cry:	cry.gif\n:o	surprised.gif\n:-?	confused.gif\n:-x	sick.gif\n:eek:	shocked.gif\n:zzz	sleeping.gif\n:P	tongue.gif\n:roll:	rolleyes.gif\n:sigh:	unsure.gif'),
('', '', 'enable_mambots', '1'),
('', '', 'form_show', '1'),
('', '', 'display_author', 'name'),
('', '', 'enable_voting', '1'),
('', '', 'can_vote', '29,18,19,20,21,23,24,25'),
('', '', 'reports_before_unpublish', '0'),
('', '', 'merge_time', '0'),
('', '', 'template_view', 'list'),
('', '', 'message_policy_post', ''),
('', '', 'message_policy_whocancomment', 'You have no rights to post comments'),
('', '', 'message_locked', 'Comments are now closed for this entry'),
('', '', 'comment_title', '0'),
('', '', 'enable_custom_bbcode', '0'),
('', '', 'enable_bbcode_quote', '29,18,19,20,21,23,24,25'),
('', '', 'reports_per_comment', '0'),
('', '', 'enable_bbcode_code', ''),
('', '', 'enable_reports', '0'),
('', '', 'enable_geshi', '0'),
('', '', 'can_report', ''),
('', '', 'load_cached_comments', '0'),
('', '', 'enable_quick_moderation', '0'),
('', '', 'notification_type', '1,2'),
('', '', 'delete_mode', '0'),
('', '', 'enable_blacklist', '0'),
('', '', 'can_ban', '24,25'),
('', '', 'smiles_path', '/components/com_jcomments/images/smiles/'),
('', '', 'feed_limit', '100'),
('', '', 'report_reason_required', '1'),
('', '', 'tree_order', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_subscriptions`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 06 2012 г., 09:56
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_subscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL DEFAULT '0',
  `object_group` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(255) NOT NULL DEFAULT '',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `hash` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `source` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_object` (`object_id`,`object_group`),
  KEY `idx_lang` (`lang`),
  KEY `idx_source` (`source`),
  KEY `idx_hash` (`hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_jcomments_subscriptions`
--

INSERT INTO `jos_jcomments_subscriptions` (`id`, `object_id`, `object_group`, `lang`, `userid`, `name`, `email`, `hash`, `published`, `source`) VALUES
(1, 2, 'com_content', 'en-GB', 0, 'Федосей', 'tjfj@mail.ru', 'fe66657d55928b280922741f609a09b0', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_version`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 04 2012 г., 12:59
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_version` (
  `version` varchar(16) NOT NULL DEFAULT '',
  `previous` varchar(16) NOT NULL DEFAULT '',
  `installed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_jcomments_version`
--

INSERT INTO `jos_jcomments_version` (`version`, `previous`, `installed`, `updated`) VALUES
('2.3.0', '', '2012-06-04 07:59:06', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_jcomments_votes`
--
-- Создание: Июн 04 2012 г., 12:59
-- Последнее обновление: Июн 06 2012 г., 09:56
--

CREATE TABLE IF NOT EXISTS `jos_jcomments_votes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commentid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(39) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `value` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_comment` (`commentid`,`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_jcomments_votes`
--

INSERT INTO `jos_jcomments_votes` (`id`, `commentid`, `userid`, `ip`, `date`, `value`) VALUES
(1, 8, 0, '92.37.190.128', '2012-06-04 12:51:27', 1),
(2, 8, 0, '92.37.191.100', '2012-06-06 04:56:49', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_menu`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(75) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `link` text,
  `type` varchar(50) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `componentid` int(11) unsigned NOT NULL DEFAULT '0',
  `sublevel` int(11) DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pollid` int(11) NOT NULL DEFAULT '0',
  `browserNav` tinyint(4) DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `utaccess` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `lft` int(11) unsigned NOT NULL DEFAULT '0',
  `rgt` int(11) unsigned NOT NULL DEFAULT '0',
  `home` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `componentid` (`componentid`,`menutype`,`published`,`access`),
  KEY `menutype` (`menutype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `jos_menu`
--

INSERT INTO `jos_menu` (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`) VALUES
(1, 'mainmenu', 'Новости', 'home', 'index.php?option=com_content&view=frontpage', 'component', 1, 0, 20, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'num_leading_articles=1\nnum_intro_articles=4\nnum_columns=2\nnum_links=4\norderby_pri=\norderby_sec=front\nmulti_column_order=1\nshow_pagination=2\nshow_pagination_results=1\nshow_feed_link=1\nshow_noauth=\nshow_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_item_navigation=\nshow_readmore=\nshow_vote=\nshow_icons=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nshow_hits=\nfeed_summary=\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0\n\n', 0, 0, 1),
(2, 'mainmenu', 'Контакты', '2012-05-05-07-21-09', 'index.php?option=com_contact&view=contact', 'component', -2, 0, 7, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'show_contact_list=0\nshow_category_crumb=0\ncontact_icons=\nicon_address=\nicon_email=\nicon_telephone=\nicon_mobile=\nicon_fax=\nicon_misc=\nshow_headings=\nshow_position=\nshow_email=\nshow_telephone=\nshow_mobile=\nshow_fax=\nallow_vcard=\nbanned_email=\nbanned_subject=\nbanned_text=\nvalidate_session=\ncustom_reply=\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0\n\n', 0, 0, 0),
(3, 'mainmenu', 'Контакты', 'sd', 'index.php?option=com_contact&view=contact&id=1', 'component', 1, 0, 7, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'show_contact_list=0\nshow_category_crumb=0\ncontact_icons=\nicon_address=\nicon_email=\nicon_telephone=\nicon_mobile=\nicon_fax=\nicon_misc=\nshow_headings=\nshow_position=\nshow_email=\nshow_telephone=\nshow_mobile=\nshow_fax=\nallow_vcard=\nbanned_email=\nbanned_subject=\nbanned_text=\nvalidate_session=\ncustom_reply=\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0\n\n', 0, 0, 0),
(4, 'mainmenu', 'О компании', '2012-05-05-08-01-19', 'index.php?option=com_contact&view=contact&id=2', 'component', 1, 0, 7, 0, 5, 62, '2012-05-21 10:22:35', 0, 0, 0, 0, 'show_contact_list=0\nshow_category_crumb=0\ncontact_icons=\nicon_address=\nicon_email=\nicon_telephone=\nicon_mobile=\nicon_fax=\nicon_misc=\nshow_headings=\nshow_position=\nshow_email=\nshow_telephone=\nshow_mobile=\nshow_fax=\nallow_vcard=\nbanned_email=\nbanned_subject=\nbanned_text=\nvalidate_session=\ncustom_reply=\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0\n\n', 0, 0, 0),
(5, 'mainmenu', 'hgh', 'hgh', 'index.php?option=com_newsfeeds&view=newsfeed&id=1', 'component', -2, 0, 11, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'show_headings=\nshow_name=\nshow_articles=\nshow_link=\nshow_cat_description=\nshow_cat_items=\nshow_feed_image=\nshow_feed_description=\nshow_item_description=\nfeed_word_count=0\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0\n\n', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_menu_types`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(75) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `menutype` (`menutype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_menu_types`
--

INSERT INTO `jos_menu_types` (`id`, `menutype`, `title`, `description`) VALUES
(1, 'mainmenu', 'Main Menu', 'The main menu for the site');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_messages`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
  `folder_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` int(11) NOT NULL DEFAULT '0',
  `priority` int(1) unsigned NOT NULL DEFAULT '0',
  `subject` text NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `useridto_state` (`user_id_to`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_messages_cfg`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_messages_cfg` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfg_name` varchar(100) NOT NULL DEFAULT '',
  `cfg_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_migration_backlinks`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_migration_backlinks` (
  `itemid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` text NOT NULL,
  `sefurl` text NOT NULL,
  `newurl` text NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 06 2012 г., 14:31
--

CREATE TABLE IF NOT EXISTS `jos_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) DEFAULT NULL,
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `numnews` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `iscore` tinyint(4) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `control` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `jos_modules`
--

INSERT INTO `jos_modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`, `control`) VALUES
(1, 'Main Menu', '', 0, 'main_menu', 0, '0000-00-00 00:00:00', 1, 'mod_mainmenu', 0, 0, 1, 'menutype=mainmenu\nmenu_style=horiz_flat\nstartLevel=0\nendLevel=0\nshowAllChildren=0\nwindow_open=\nshow_whitespace=0\ncache=1\ntag_id=\nclass_sfx=\nmoduleclass_sfx=_menu\nmaxdepth=10\nmenu_images=0\nmenu_images_align=0\nmenu_images_link=0\nexpand_menu=0\nactivate_parent=0\nfull_active_id=0\nindent_image=0\nindent_image1=\nindent_image2=\nindent_image3=\nindent_image4=\nindent_image5=\nindent_image6=\nspacer=\nend_spacer=\n\n', 1, 0, ''),
(2, 'Login', '', 1, 'login', 0, '0000-00-00 00:00:00', 1, 'mod_login', 0, 0, 1, '', 1, 1, ''),
(3, 'Popular', '', 3, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_popular', 0, 2, 1, '', 0, 1, ''),
(4, 'Recent added Articles', '', 4, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_latest', 0, 2, 1, 'ordering=c_dsc\nuser_id=0\ncache=0\n\n', 0, 1, ''),
(5, 'Menu Stats', '', 5, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_stats', 0, 2, 1, '', 0, 1, ''),
(6, 'Unread Messages', '', 1, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_unread', 0, 2, 1, '', 1, 1, ''),
(7, 'Online Users', '', 2, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_online', 0, 2, 1, '', 1, 1, ''),
(8, 'Toolbar', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 2, 1, '', 1, 1, ''),
(9, 'Quick Icons', '', 1, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicon', 0, 2, 1, '', 1, 1, ''),
(10, 'Logged in Users', '', 2, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_logged', 0, 2, 1, '', 0, 1, ''),
(11, 'Footer', '', 0, 'footer', 0, '0000-00-00 00:00:00', 1, 'mod_footer', 0, 0, 1, '', 1, 1, ''),
(12, 'Admin Menu', '', 1, 'menu', 0, '0000-00-00 00:00:00', 1, 'mod_menu', 0, 2, 1, '', 0, 1, ''),
(13, 'Admin SubMenu', '', 1, 'submenu', 0, '0000-00-00 00:00:00', 1, 'mod_submenu', 0, 2, 1, '', 0, 1, ''),
(14, 'User Status', '', 1, 'status', 0, '0000-00-00 00:00:00', 1, 'mod_status', 0, 2, 1, '', 0, 1, ''),
(15, 'Title', '', 1, 'title', 0, '0000-00-00 00:00:00', 1, 'mod_title', 0, 2, 1, '', 0, 1, ''),
(16, 'VirtueMart Product Categories', '', 2, 'left', 62, '2012-06-05 14:00:54', 1, 'mod_product_categories', 0, 0, 0, 'menutype=dtree\njscook_type=tree\njscookMenu_style=ThemeOffice\nmenu_orientation=hbr\njscookTree_style=ThemeXP\nroot_label=Категории\ncache=0\nmoduleclass_sfx=\nclass_sfx=\n\n', 0, 0, ''),
(17, 'VirtueMart Product Scroller', '', 3, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_productscroller', 0, 0, 1, 'NumberOfProducts=5\nfeaturedProducts=no\nScrollSortMethod=random\nshow_product_name=yes\nshow_addtocart=yes\nshow_price=yes\nScrollHeight=125\nScrollWidth=150\nScrollBehavior=scroll\nScrollDirection=up\nScrollAmount=scroll\nScrollDelay=80\nScrollAlign=left\nScrollSpaceChar=&nbsp;\nScrollSpaceCharTimes=5\nScrollLineChar=<br />\nScrollLineCharTimes=2\nScrollCSSOverride=no\nScrollTextAlign=left\nScrollTextWeight=normal\nScrollTextSize=10\nScrollTextColor=#000000\nScrollBGColor=transparent\nScrollMargin=2\n', 0, 0, ''),
(18, 'VirtueMart Module', '', 4, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart', 0, 0, 1, 'show_login_form=no\nremember_me_default=1\nshow_categories=yes\nshow_listall=yes\nshow_adminlink=yes\nshow_accountlink=yes\nshow_minicart=yes\nshow_productsearch=yes\nshow_product_parameter_search=no\nmenutype=links\njscook_type=menu\njscookMenu_style=ThemeOffice\nmenu_orientation=hbr\njscookTree_style=ThemeXP\nroot_label=Shop\n', 0, 0, ''),
(19, 'VirtueMart Currency Selector', '', 5, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_currencies', 0, 0, 1, '', 0, 0, ''),
(20, 'VirtueMart Featured Products', '', 6, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_featureprod', 0, 0, 1, 'max_items=2\nshow_price=1\nshow_addtocart=1\ndisplay_style=vertical\nproducts_per_row=4\n', 0, 0, ''),
(21, 'VirtueMart Shopping Cart', '', 2, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_virtuemart_cart', 0, 0, 0, 'moduleclass_sfx=\nvmCartDirection=0\nvmEnableGreyBox=0\n\n', 0, 0, ''),
(22, 'VirtueMart Latest Products', '', 8, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_latestprod', 0, 0, 1, 'max_items=2\nshow_price=1\nshow_addtocart=1\ndisplay_style=vertical\nproducts_per_row=4\n', 0, 0, ''),
(23, 'VirtueMart Login', '', 0, 'login-form', 62, '2012-06-06 14:31:01', 1, 'mod_virtuemart_login', 0, 0, 0, 'moduleclass_sfx=\npretext=\nposttext=\nlogin=samepage\nlogout=samepage\ngreeting=1\nname=0\naccountlink=1\nremember_me_default=0\n\n', 0, 0, ''),
(24, 'VirtueMart Manufacturers', '', 10, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_manufacturers', 0, 0, 1, 'show_linklist=1\nshow_dropdown=1\nauto=1\n', 0, 0, ''),
(25, 'VirtueMart Random Products', '', 11, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_randomprod', 0, 0, 1, 'max_items=2\nshow_price=1\nshow_addtocart=1\ndisplay_style=vertical\nproducts_per_row=4\n', 0, 0, ''),
(26, 'VirtueMart Search', '', 0, 'search-form', 0, '0000-00-00 00:00:00', 1, 'mod_virtuemart_search', 0, 0, 1, 'moduleclass_sfx=\nclass_sfx=\n\n', 0, 0, ''),
(27, 'VirtueMart Top Ten Products', '', 13, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_virtuemart_topten', 0, 0, 1, 'num_topsellers=10\n', 0, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_modules_menu`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_modules_menu`
--

INSERT INTO `jos_modules_menu` (`moduleid`, `menuid`) VALUES
(1, 0),
(16, 0),
(17, 0),
(18, 0),
(19, 0),
(20, 0),
(21, 0),
(22, 0),
(23, 0),
(24, 0),
(25, 0),
(26, 0),
(27, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_newsfeeds`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_newsfeeds` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `link` text NOT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `numarticles` int(11) unsigned NOT NULL DEFAULT '1',
  `cache_time` int(11) unsigned NOT NULL DEFAULT '3600',
  `checked_out` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rtl` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `published` (`published`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_plugins`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 04 2012 г., 12:59
--

CREATE TABLE IF NOT EXISTS `jos_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `element` varchar(100) NOT NULL DEFAULT '',
  `folder` varchar(100) NOT NULL DEFAULT '',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '0',
  `iscore` tinyint(3) NOT NULL DEFAULT '0',
  `client_id` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_folder` (`published`,`client_id`,`access`,`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Дамп данных таблицы `jos_plugins`
--

INSERT INTO `jos_plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'Authentication - Joomla', 'joomla', 'authentication', 0, 1, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(2, 'Authentication - LDAP', 'ldap', 'authentication', 0, 2, 0, 1, 0, 0, '0000-00-00 00:00:00', 'host=\nport=389\nuse_ldapV3=0\nnegotiate_tls=0\nno_referrals=0\nauth_method=bind\nbase_dn=\nsearch_string=\nusers_dn=\nusername=\npassword=\nldap_fullname=fullName\nldap_email=mail\nldap_uid=uid\n\n'),
(3, 'Authentication - GMail', 'gmail', 'authentication', 0, 4, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(4, 'Authentication - OpenID', 'openid', 'authentication', 0, 3, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(5, 'User - Joomla!', 'joomla', 'user', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', 'autoregister=1\n\n'),
(6, 'Search - Content', 'content', 'search', 0, 1, 1, 1, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\nsearch_content=1\nsearch_uncategorised=1\nsearch_archived=1\n\n'),
(7, 'Search - Contacts', 'contacts', 'search', 0, 3, 1, 1, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(8, 'Search - Categories', 'categories', 'search', 0, 4, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(9, 'Search - Sections', 'sections', 'search', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(10, 'Search - Newsfeeds', 'newsfeeds', 'search', 0, 6, 1, 0, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(11, 'Search - Weblinks', 'weblinks', 'search', 0, 2, 1, 1, 0, 0, '0000-00-00 00:00:00', 'search_limit=50\n\n'),
(12, 'Content - Pagebreak', 'pagebreak', 'content', 0, 10000, 1, 1, 0, 0, '0000-00-00 00:00:00', 'enabled=1\ntitle=1\nmultipage_toc=1\nshowall=1\n\n'),
(13, 'Content - Rating', 'vote', 'content', 0, 4, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(14, 'Content - Email Cloaking', 'emailcloak', 'content', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', 'mode=1\n\n'),
(15, 'Content - Code Hightlighter (GeSHi)', 'geshi', 'content', 0, 5, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(16, 'Content - Load Module', 'loadmodule', 'content', 0, 6, 1, 0, 0, 0, '0000-00-00 00:00:00', 'enabled=1\nstyle=0\n\n'),
(17, 'Content - Page Navigation', 'pagenavigation', 'content', 0, 2, 1, 1, 0, 0, '0000-00-00 00:00:00', 'position=1\n\n'),
(18, 'Editor - No Editor', 'none', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(19, 'Editor - TinyMCE', 'tinymce', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', 'mode=advanced\nskin=0\ncompressed=0\ncleanup_startup=0\ncleanup_save=2\nentity_encoding=raw\nlang_mode=0\nlang_code=en\ntext_direction=ltr\ncontent_css=1\ncontent_css_custom=\nrelative_urls=1\nnewlines=0\ninvalid_elements=applet\nextended_elements=\ntoolbar=top\ntoolbar_align=left\nhtml_height=550\nhtml_width=750\nelement_path=1\nfonts=1\npaste=1\nsearchreplace=1\ninsertdate=1\nformat_date=%Y-%m-%d\ninserttime=1\nformat_time=%H:%M:%S\ncolors=1\ntable=1\nsmilies=1\nmedia=1\nhr=1\ndirectionality=1\nfullscreen=1\nstyle=1\nlayer=1\nxhtmlxtras=1\nvisualchars=1\nnonbreaking=1\ntemplate=0\nadvimage=1\nadvlink=1\nautosave=1\ncontextmenu=1\ninlinepopups=1\nsafari=1\ncustom_plugin=\ncustom_button=\n\n'),
(20, 'Editor - XStandard Lite 2.0', 'xstandard', 'editors', 0, 0, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(21, 'Editor Button - Image', 'image', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(22, 'Editor Button - Pagebreak', 'pagebreak', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(23, 'Editor Button - Readmore', 'readmore', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(24, 'XML-RPC - Joomla', 'joomla', 'xmlrpc', 0, 7, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(25, 'XML-RPC - Blogger API', 'blogger', 'xmlrpc', 0, 7, 0, 1, 0, 0, '0000-00-00 00:00:00', 'catid=1\nsectionid=0\n\n'),
(27, 'System - SEF', 'sef', 'system', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(28, 'System - Debug', 'debug', 'system', 0, 2, 1, 0, 0, 0, '0000-00-00 00:00:00', 'queries=1\nmemory=1\nlangauge=1\n\n'),
(29, 'System - Legacy', 'legacy', 'system', 0, 3, 0, 1, 0, 0, '0000-00-00 00:00:00', 'route=0\n\n'),
(30, 'System - Cache', 'cache', 'system', 0, 4, 0, 1, 0, 0, '0000-00-00 00:00:00', 'browsercache=0\ncachetime=15\n\n'),
(31, 'System - Log', 'log', 'system', 0, 5, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(32, 'System - Remember Me', 'remember', 'system', 0, 6, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(33, 'System - Backlink', 'backlink', 'system', 0, 7, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(34, 'System - Mootools Upgrade', 'mtupgrade', 'system', 0, 8, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(35, 'Search - Virtuemart', 'virtuemart.search', 'search', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'density_flag=1\nname_flag=1\nsku_flag=1\ndesc_flag=1\nsdesc_flag=1\nurl_flag=1\nreview_flag=1\nmanufacturer_flag=1\ncategory_flag=1\nparent_filter=both\n'),
(36, 'VirtueMart Product Snapshot', 'vmproductsnapshots', 'content', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'enabled=1\nshowname=n\nshowimage=n\nshowdesc=n\nshowprice=n\nquantity=1\nshowaddtocart=n\ndisplayeach=h\ndisplaylist=v\n'),
(37, 'Virtuemart Extended Search Plugin', 'vmxsearch.plugin', 'search', 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 'density_flag=1\nname_flag=1\nsku_flag=1\ndesc_flag=1\nsdesc_flag=1\nurl_flag=1\nreview_flag=1\nmanufacturer_flag=1\ncategory_flag=1\nparent_filter=both\n'),
(38, 'Content - JComments', 'jcomments', 'content', 0, 10001, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(39, 'Search - JComments', 'jcomments', 'search', 0, 7, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(40, 'System - JComments', 'jcomments', 'system', 0, 9, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(41, 'Editor Button - JComments ON', 'jcommentson', 'editors-xtd', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(42, 'Editor Button - JComments OFF', 'jcommentsoff', 'editors-xtd', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(43, 'User - JComments', 'jcomments', 'user', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_polls`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_polls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `voters` int(9) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '0',
  `lag` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_poll_data`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_poll_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pollid` int(11) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pollid` (`pollid`,`text`(1))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_poll_date`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_poll_date` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vote_id` int(11) NOT NULL DEFAULT '0',
  `poll_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_poll_menu`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_poll_menu` (
  `pollid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pollid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_sections`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `image` text NOT NULL,
  `scope` varchar(50) NOT NULL DEFAULT '',
  `image_position` varchar(30) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_scope` (`scope`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_session`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 07 2012 г., 09:58
--

CREATE TABLE IF NOT EXISTS `jos_session` (
  `username` varchar(150) DEFAULT '',
  `time` varchar(14) DEFAULT '',
  `session_id` varchar(200) NOT NULL DEFAULT '0',
  `guest` tinyint(4) DEFAULT '1',
  `userid` int(11) DEFAULT '0',
  `usertype` varchar(50) DEFAULT '',
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `data` longtext,
  PRIMARY KEY (`session_id`(64)),
  KEY `whosonline` (`guest`,`usertype`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_session`
--

INSERT INTO `jos_session` (`username`, `time`, `session_id`, `guest`, `userid`, `usertype`, `gid`, `client_id`, `data`) VALUES
('', '1339063134', '252a68efc6099224cbf3356fba677df0', 1, 0, '', 0, 0, '__default|a:9:{s:22:"session.client.browser";s:106:"Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5";s:15:"session.counter";i:3;s:8:"registry";O:9:"JRegistry":3:{s:17:"_defaultNameSpace";s:7:"session";s:9:"_registry";a:1:{s:7:"session";a:1:{s:4:"data";O:8:"stdClass":0:{}}}s:7:"_errors";a:0:{}}s:4:"user";O:5:"JUser":19:{s:2:"id";i:0;s:4:"name";N;s:8:"username";N;s:5:"email";N;s:8:"password";N;s:14:"password_clear";s:0:"";s:8:"usertype";s:15:"Public Frontend";s:5:"block";N;s:9:"sendEmail";i:0;s:3:"gid";i:0;s:12:"registerDate";N;s:13:"lastvisitDate";N;s:10:"activation";N;s:6:"params";N;s:3:"aid";i:0;s:5:"guest";i:1;s:7:"_params";O:10:"JParameter":7:{s:4:"_raw";s:0:"";s:4:"_xml";N;s:9:"_elements";a:0:{}s:12:"_elementPath";a:1:{i:0;s:81:"/home2/core5429/public_html/paradigm/Puls/libraries/joomla/html/parameter/element";}s:17:"_defaultNameSpace";s:8:"_default";s:9:"_registry";a:1:{s:8:"_default";a:1:{s:4:"data";O:8:"stdClass":0:{}}}s:7:"_errors";a:0:{}}s:9:"_errorMsg";N;s:7:"_errors";a:0:{}}s:16:"com_mailto.links";a:4:{s:40:"c00c7e8a701f1de21d818a785ad47d8966e6d5e8";O:8:"stdClass":2:{s:4:"link";s:100:"http://coreatrade.com/paradigm/Puls/index.php?option=com_content&view=article&id=4%3Anews-3&Itemid=1";s:6:"expiry";i:1339062420;}s:40:"f49494bf693a76075bc86f5b5e0c5fd339151332";O:8:"stdClass":2:{s:4:"link";s:113:"http://coreatrade.com/paradigm/Puls/index.php?option=com_content&view=article&id=2%3A2012-06-04-08-02-44&Itemid=1";s:6:"expiry";i:1339062420;}s:40:"9442428d31b1e25b05a5af7494f6b587a478dd29";O:8:"stdClass":2:{s:4:"link";s:89:"http://coreatrade.com/paradigm/Puls/index.php?option=com_content&view=article&id=4:news-3";s:6:"expiry";i:1339063134;}s:40:"21f19d72b9ac1a2f609f1fad812d5530608efe15";O:8:"stdClass":2:{s:4:"link";s:102:"http://coreatrade.com/paradigm/Puls/index.php?option=com_content&view=article&id=2:2012-06-04-08-02-44";s:6:"expiry";i:1339063134;}}s:13:"session.token";s:32:"79df02e10ea9f16fc2bb7050ab635871";s:19:"session.timer.start";i:1339063100;s:18:"session.timer.last";i:1339063100;s:17:"session.timer.now";i:1339063134;}auth|a:11:{s:11:"show_prices";i:1;s:7:"user_id";i:0;s:8:"username";s:4:"demo";s:5:"perms";s:0:"";s:10:"first_name";s:5:"guest";s:9:"last_name";s:0:"";s:16:"shopper_group_id";s:1:"5";s:22:"shopper_group_discount";s:4:"0.00";s:24:"show_price_including_tax";s:1:"1";s:21:"default_shopper_group";i:1;s:22:"is_registered_customer";b:0;}cart|a:1:{s:3:"idx";i:0;}recent|a:1:{s:3:"idx";i:0;}ps_vendor_id|i:1;minimum_pov|s:4:"0.00";vendor_currency|s:3:"RUB";vmUseGreyBox|s:1:"0";vmCartDirection|s:1:"0";');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_stats_agents`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_stats_agents` (
  `agent` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_templates_menu`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_templates_menu` (
  `template` varchar(255) NOT NULL DEFAULT '',
  `menuid` int(11) NOT NULL DEFAULT '0',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`menuid`,`client_id`,`template`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `jos_templates_menu`
--

INSERT INTO `jos_templates_menu` (`template`, `menuid`, `client_id`) VALUES
('rhuk_milkyway', 0, 0),
('khepri', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_users`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 06 2012 г., 14:54
--

CREATE TABLE IF NOT EXISTS `jos_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `usertype` varchar(25) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `gid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`),
  KEY `gid_block` (`gid`,`block`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Дамп данных таблицы `jos_users`
--

INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `gid`, `registerDate`, `lastvisitDate`, `activation`, `params`) VALUES
(62, 'Administrator', 'admin', 'cahbkooo91@mail.ru', '489adc58c6a87cd9bab807c6db96912a:HouBr41aNXZWtbGFif9Ypf513POn6tgS', 'Super Administrator', 0, 1, 25, '2012-05-04 03:52:29', '2012-06-06 14:54:31', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_auth_group`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_auth_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(128) DEFAULT NULL,
  `group_level` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds all the user groups' AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `jos_vm_auth_group`
--

INSERT INTO `jos_vm_auth_group` (`group_id`, `group_name`, `group_level`) VALUES
(1, 'admin', 0),
(2, 'storeadmin', 250),
(3, 'shopper', 500),
(4, 'demo', 750);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_auth_user_group`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_auth_user_group` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps the user to user groups';

--
-- Дамп данных таблицы `jos_vm_auth_user_group`
--

INSERT INTO `jos_vm_auth_user_group` (`user_id`, `group_id`) VALUES
(62, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_auth_user_vendor`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_auth_user_vendor` (
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  KEY `idx_auth_user_vendor_user_id` (`user_id`),
  KEY `idx_auth_user_vendor_vendor_id` (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps a user to a vendor';

--
-- Дамп данных таблицы `jos_vm_auth_user_vendor`
--

INSERT INTO `jos_vm_auth_user_vendor` (`user_id`, `vendor_id`) VALUES
(62, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_category`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 05 2012 г., 13:12
--

CREATE TABLE IF NOT EXISTS `jos_vm_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `category_name` varchar(128) NOT NULL DEFAULT '',
  `category_description` text,
  `category_thumb_image` varchar(255) DEFAULT NULL,
  `category_full_image` varchar(255) DEFAULT NULL,
  `category_publish` char(1) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `category_browsepage` varchar(255) NOT NULL DEFAULT 'browse_1',
  `products_per_row` tinyint(2) NOT NULL DEFAULT '1',
  `category_flypage` varchar(255) DEFAULT NULL,
  `list_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `idx_category_vendor_id` (`vendor_id`),
  KEY `idx_category_name` (`category_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Product Categories are stored here' AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `jos_vm_category`
--

INSERT INTO `jos_vm_category` (`category_id`, `vendor_id`, `category_name`, `category_description`, `category_thumb_image`, `category_full_image`, `category_publish`, `cdate`, `mdate`, `category_browsepage`, `products_per_row`, `category_flypage`, `list_order`) VALUES
(6, 1, 'Аудиометры', '', '', '', 'Y', 1338736636, 1338741931, 'browse_3', 2, 'flypage.tpl', 2),
(8, 1, 'Электрокардиографы', '', '', '', 'Y', 1338846178, 1338901953, 'browse_3', 2, 'flypage.tpl', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_category_xref`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 04 2012 г., 21:43
--

CREATE TABLE IF NOT EXISTS `jos_vm_category_xref` (
  `category_parent_id` int(11) NOT NULL DEFAULT '0',
  `category_child_id` int(11) NOT NULL DEFAULT '0',
  `category_list` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_child_id`),
  KEY `category_xref_category_parent_id` (`category_parent_id`),
  KEY `idx_category_xref_category_list` (`category_list`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Category child-parent relation list';

--
-- Дамп данных таблицы `jos_vm_category_xref`
--

INSERT INTO `jos_vm_category_xref` (`category_parent_id`, `category_child_id`, `category_list`) VALUES
(0, 6, NULL),
(0, 8, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_country`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
-- Последняя проверка: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_country` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) NOT NULL DEFAULT '1',
  `country_name` varchar(64) DEFAULT NULL,
  `country_3_code` char(3) DEFAULT NULL,
  `country_2_code` char(2) DEFAULT NULL,
  PRIMARY KEY (`country_id`),
  KEY `idx_country_name` (`country_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Country records' AUTO_INCREMENT=246 ;

--
-- Дамп данных таблицы `jos_vm_country`
--

INSERT INTO `jos_vm_country` (`country_id`, `zone_id`, `country_name`, `country_3_code`, `country_2_code`) VALUES
(1, 1, 'Afghanistan', 'AFG', 'AF'),
(2, 1, 'Albania', 'ALB', 'AL'),
(3, 1, 'Algeria', 'DZA', 'DZ'),
(4, 1, 'American Samoa', 'ASM', 'AS'),
(5, 1, 'Andorra', 'AND', 'AD'),
(6, 1, 'Angola', 'AGO', 'AO'),
(7, 1, 'Anguilla', 'AIA', 'AI'),
(8, 1, 'Antarctica', 'ATA', 'AQ'),
(9, 1, 'Antigua and Barbuda', 'ATG', 'AG'),
(10, 1, 'Argentina', 'ARG', 'AR'),
(11, 1, 'Armenia', 'ARM', 'AM'),
(12, 1, 'Aruba', 'ABW', 'AW'),
(13, 1, 'Australia', 'AUS', 'AU'),
(14, 1, 'Austria', 'AUT', 'AT'),
(15, 1, 'Azerbaijan', 'AZE', 'AZ'),
(16, 1, 'Bahamas', 'BHS', 'BS'),
(17, 1, 'Bahrain', 'BHR', 'BH'),
(18, 1, 'Bangladesh', 'BGD', 'BD'),
(19, 1, 'Barbados', 'BRB', 'BB'),
(20, 1, 'Belarus', 'BLR', 'BY'),
(21, 1, 'Belgium', 'BEL', 'BE'),
(22, 1, 'Belize', 'BLZ', 'BZ'),
(23, 1, 'Benin', 'BEN', 'BJ'),
(24, 1, 'Bermuda', 'BMU', 'BM'),
(25, 1, 'Bhutan', 'BTN', 'BT'),
(26, 1, 'Bolivia', 'BOL', 'BO'),
(27, 1, 'Bosnia and Herzegowina', 'BIH', 'BA'),
(28, 1, 'Botswana', 'BWA', 'BW'),
(29, 1, 'Bouvet Island', 'BVT', 'BV'),
(30, 1, 'Brazil', 'BRA', 'BR'),
(31, 1, 'British Indian Ocean Territory', 'IOT', 'IO'),
(32, 1, 'Brunei Darussalam', 'BRN', 'BN'),
(33, 1, 'Bulgaria', 'BGR', 'BG'),
(34, 1, 'Burkina Faso', 'BFA', 'BF'),
(35, 1, 'Burundi', 'BDI', 'BI'),
(36, 1, 'Cambodia', 'KHM', 'KH'),
(37, 1, 'Cameroon', 'CMR', 'CM'),
(38, 1, 'Canada', 'CAN', 'CA'),
(39, 1, 'Cape Verde', 'CPV', 'CV'),
(40, 1, 'Cayman Islands', 'CYM', 'KY'),
(41, 1, 'Central African Republic', 'CAF', 'CF'),
(42, 1, 'Chad', 'TCD', 'TD'),
(43, 1, 'Chile', 'CHL', 'CL'),
(44, 1, 'China', 'CHN', 'CN'),
(45, 1, 'Christmas Island', 'CXR', 'CX'),
(46, 1, 'Cocos (Keeling) Islands', 'CCK', 'CC'),
(47, 1, 'Colombia', 'COL', 'CO'),
(48, 1, 'Comoros', 'COM', 'KM'),
(49, 1, 'Congo', 'COG', 'CG'),
(50, 1, 'Cook Islands', 'COK', 'CK'),
(51, 1, 'Costa Rica', 'CRI', 'CR'),
(52, 1, 'Cote D''Ivoire', 'CIV', 'CI'),
(53, 1, 'Croatia', 'HRV', 'HR'),
(54, 1, 'Cuba', 'CUB', 'CU'),
(55, 1, 'Cyprus', 'CYP', 'CY'),
(56, 1, 'Czech Republic', 'CZE', 'CZ'),
(57, 1, 'Denmark', 'DNK', 'DK'),
(58, 1, 'Djibouti', 'DJI', 'DJ'),
(59, 1, 'Dominica', 'DMA', 'DM'),
(60, 1, 'Dominican Republic', 'DOM', 'DO'),
(61, 1, 'East Timor', 'TMP', 'TP'),
(62, 1, 'Ecuador', 'ECU', 'EC'),
(63, 1, 'Egypt', 'EGY', 'EG'),
(64, 1, 'El Salvador', 'SLV', 'SV'),
(65, 1, 'Equatorial Guinea', 'GNQ', 'GQ'),
(66, 1, 'Eritrea', 'ERI', 'ER'),
(67, 1, 'Estonia', 'EST', 'EE'),
(68, 1, 'Ethiopia', 'ETH', 'ET'),
(69, 1, 'Falkland Islands (Malvinas)', 'FLK', 'FK'),
(70, 1, 'Faroe Islands', 'FRO', 'FO'),
(71, 1, 'Fiji', 'FJI', 'FJ'),
(72, 1, 'Finland', 'FIN', 'FI'),
(73, 1, 'France', 'FRA', 'FR'),
(74, 1, 'France, Metropolitan', 'FXX', 'FX'),
(75, 1, 'French Guiana', 'GUF', 'GF'),
(76, 1, 'French Polynesia', 'PYF', 'PF'),
(77, 1, 'French Southern Territories', 'ATF', 'TF'),
(78, 1, 'Gabon', 'GAB', 'GA'),
(79, 1, 'Gambia', 'GMB', 'GM'),
(80, 1, 'Georgia', 'GEO', 'GE'),
(81, 1, 'Germany', 'DEU', 'DE'),
(82, 1, 'Ghana', 'GHA', 'GH'),
(83, 1, 'Gibraltar', 'GIB', 'GI'),
(84, 1, 'Greece', 'GRC', 'GR'),
(85, 1, 'Greenland', 'GRL', 'GL'),
(86, 1, 'Grenada', 'GRD', 'GD'),
(87, 1, 'Guadeloupe', 'GLP', 'GP'),
(88, 1, 'Guam', 'GUM', 'GU'),
(89, 1, 'Guatemala', 'GTM', 'GT'),
(90, 1, 'Guinea', 'GIN', 'GN'),
(91, 1, 'Guinea-bissau', 'GNB', 'GW'),
(92, 1, 'Guyana', 'GUY', 'GY'),
(93, 1, 'Haiti', 'HTI', 'HT'),
(94, 1, 'Heard and Mc Donald Islands', 'HMD', 'HM'),
(95, 1, 'Honduras', 'HND', 'HN'),
(96, 1, 'Hong Kong', 'HKG', 'HK'),
(97, 1, 'Hungary', 'HUN', 'HU'),
(98, 1, 'Iceland', 'ISL', 'IS'),
(99, 1, 'India', 'IND', 'IN'),
(100, 1, 'Indonesia', 'IDN', 'ID'),
(101, 1, 'Iran (Islamic Republic of)', 'IRN', 'IR'),
(102, 1, 'Iraq', 'IRQ', 'IQ'),
(103, 1, 'Ireland', 'IRL', 'IE'),
(104, 1, 'Israel', 'ISR', 'IL'),
(105, 1, 'Italy', 'ITA', 'IT'),
(106, 1, 'Jamaica', 'JAM', 'JM'),
(107, 1, 'Japan', 'JPN', 'JP'),
(108, 1, 'Jordan', 'JOR', 'JO'),
(109, 1, 'Kazakhstan', 'KAZ', 'KZ'),
(110, 1, 'Kenya', 'KEN', 'KE'),
(111, 1, 'Kiribati', 'KIR', 'KI'),
(112, 1, 'Korea, Democratic People''s Republic of', 'PRK', 'KP'),
(113, 1, 'Korea, Republic of', 'KOR', 'KR'),
(114, 1, 'Kuwait', 'KWT', 'KW'),
(115, 1, 'Kyrgyzstan', 'KGZ', 'KG'),
(116, 1, 'Lao People''s Democratic Republic', 'LAO', 'LA'),
(117, 1, 'Latvia', 'LVA', 'LV'),
(118, 1, 'Lebanon', 'LBN', 'LB'),
(119, 1, 'Lesotho', 'LSO', 'LS'),
(120, 1, 'Liberia', 'LBR', 'LR'),
(121, 1, 'Libyan Arab Jamahiriya', 'LBY', 'LY'),
(122, 1, 'Liechtenstein', 'LIE', 'LI'),
(123, 1, 'Lithuania', 'LTU', 'LT'),
(124, 1, 'Luxembourg', 'LUX', 'LU'),
(125, 1, 'Macau', 'MAC', 'MO'),
(126, 1, 'Macedonia, The Former Yugoslav Republic of', 'MKD', 'MK'),
(127, 1, 'Madagascar', 'MDG', 'MG'),
(128, 1, 'Malawi', 'MWI', 'MW'),
(129, 1, 'Malaysia', 'MYS', 'MY'),
(130, 1, 'Maldives', 'MDV', 'MV'),
(131, 1, 'Mali', 'MLI', 'ML'),
(132, 1, 'Malta', 'MLT', 'MT'),
(133, 1, 'Marshall Islands', 'MHL', 'MH'),
(134, 1, 'Martinique', 'MTQ', 'MQ'),
(135, 1, 'Mauritania', 'MRT', 'MR'),
(136, 1, 'Mauritius', 'MUS', 'MU'),
(137, 1, 'Mayotte', 'MYT', 'YT'),
(138, 1, 'Mexico', 'MEX', 'MX'),
(139, 1, 'Micronesia, Federated States of', 'FSM', 'FM'),
(140, 1, 'Moldova, Republic of', 'MDA', 'MD'),
(141, 1, 'Monaco', 'MCO', 'MC'),
(142, 1, 'Mongolia', 'MNG', 'MN'),
(143, 1, 'Montserrat', 'MSR', 'MS'),
(144, 1, 'Morocco', 'MAR', 'MA'),
(145, 1, 'Mozambique', 'MOZ', 'MZ'),
(146, 1, 'Myanmar', 'MMR', 'MM'),
(147, 1, 'Namibia', 'NAM', 'NA'),
(148, 1, 'Nauru', 'NRU', 'NR'),
(149, 1, 'Nepal', 'NPL', 'NP'),
(150, 1, 'Netherlands', 'NLD', 'NL'),
(151, 1, 'Netherlands Antilles', 'ANT', 'AN'),
(152, 1, 'New Caledonia', 'NCL', 'NC'),
(153, 1, 'New Zealand', 'NZL', 'NZ'),
(154, 1, 'Nicaragua', 'NIC', 'NI'),
(155, 1, 'Niger', 'NER', 'NE'),
(156, 1, 'Nigeria', 'NGA', 'NG'),
(157, 1, 'Niue', 'NIU', 'NU'),
(158, 1, 'Norfolk Island', 'NFK', 'NF'),
(159, 1, 'Northern Mariana Islands', 'MNP', 'MP'),
(160, 1, 'Norway', 'NOR', 'NO'),
(161, 1, 'Oman', 'OMN', 'OM'),
(162, 1, 'Pakistan', 'PAK', 'PK'),
(163, 1, 'Palau', 'PLW', 'PW'),
(164, 1, 'Panama', 'PAN', 'PA'),
(165, 1, 'Papua New Guinea', 'PNG', 'PG'),
(166, 1, 'Paraguay', 'PRY', 'PY'),
(167, 1, 'Peru', 'PER', 'PE'),
(168, 1, 'Philippines', 'PHL', 'PH'),
(169, 1, 'Pitcairn', 'PCN', 'PN'),
(170, 1, 'Poland', 'POL', 'PL'),
(171, 1, 'Portugal', 'PRT', 'PT'),
(172, 1, 'Puerto Rico', 'PRI', 'PR'),
(173, 1, 'Qatar', 'QAT', 'QA'),
(174, 1, 'Reunion', 'REU', 'RE'),
(175, 1, 'Romania', 'ROM', 'RO'),
(176, 1, 'Russian Federation', 'RUS', 'RU'),
(177, 1, 'Rwanda', 'RWA', 'RW'),
(178, 1, 'Saint Kitts and Nevis', 'KNA', 'KN'),
(179, 1, 'Saint Lucia', 'LCA', 'LC'),
(180, 1, 'Saint Vincent and the Grenadines', 'VCT', 'VC'),
(181, 1, 'Samoa', 'WSM', 'WS'),
(182, 1, 'San Marino', 'SMR', 'SM'),
(183, 1, 'Sao Tome and Principe', 'STP', 'ST'),
(184, 1, 'Saudi Arabia', 'SAU', 'SA'),
(185, 1, 'Senegal', 'SEN', 'SN'),
(186, 1, 'Seychelles', 'SYC', 'SC'),
(187, 1, 'Sierra Leone', 'SLE', 'SL'),
(188, 1, 'Singapore', 'SGP', 'SG'),
(189, 1, 'Slovakia (Slovak Republic)', 'SVK', 'SK'),
(190, 1, 'Slovenia', 'SVN', 'SI'),
(191, 1, 'Solomon Islands', 'SLB', 'SB'),
(192, 1, 'Somalia', 'SOM', 'SO'),
(193, 1, 'South Africa', 'ZAF', 'ZA'),
(194, 1, 'South Georgia and the South Sandwich Islands', 'SGS', 'GS'),
(195, 1, 'Spain', 'ESP', 'ES'),
(196, 1, 'Sri Lanka', 'LKA', 'LK'),
(197, 1, 'St. Helena', 'SHN', 'SH'),
(198, 1, 'St. Pierre and Miquelon', 'SPM', 'PM'),
(199, 1, 'Sudan', 'SDN', 'SD'),
(200, 1, 'Suriname', 'SUR', 'SR'),
(201, 1, 'Svalbard and Jan Mayen Islands', 'SJM', 'SJ'),
(202, 1, 'Swaziland', 'SWZ', 'SZ'),
(203, 1, 'Sweden', 'SWE', 'SE'),
(204, 1, 'Switzerland', 'CHE', 'CH'),
(205, 1, 'Syrian Arab Republic', 'SYR', 'SY'),
(206, 1, 'Taiwan', 'TWN', 'TW'),
(207, 1, 'Tajikistan', 'TJK', 'TJ'),
(208, 1, 'Tanzania, United Republic of', 'TZA', 'TZ'),
(209, 1, 'Thailand', 'THA', 'TH'),
(210, 1, 'Togo', 'TGO', 'TG'),
(211, 1, 'Tokelau', 'TKL', 'TK'),
(212, 1, 'Tonga', 'TON', 'TO'),
(213, 1, 'Trinidad and Tobago', 'TTO', 'TT'),
(214, 1, 'Tunisia', 'TUN', 'TN'),
(215, 1, 'Turkey', 'TUR', 'TR'),
(216, 1, 'Turkmenistan', 'TKM', 'TM'),
(217, 1, 'Turks and Caicos Islands', 'TCA', 'TC'),
(218, 1, 'Tuvalu', 'TUV', 'TV'),
(219, 1, 'Uganda', 'UGA', 'UG'),
(220, 1, 'Ukraine', 'UKR', 'UA'),
(221, 1, 'United Arab Emirates', 'ARE', 'AE'),
(222, 1, 'United Kingdom', 'GBR', 'GB'),
(223, 1, 'United States', 'USA', 'US'),
(224, 1, 'United States Minor Outlying Islands', 'UMI', 'UM'),
(225, 1, 'Uruguay', 'URY', 'UY'),
(226, 1, 'Uzbekistan', 'UZB', 'UZ'),
(227, 1, 'Vanuatu', 'VUT', 'VU'),
(228, 1, 'Vatican City State (Holy See)', 'VAT', 'VA'),
(229, 1, 'Venezuela', 'VEN', 'VE'),
(230, 1, 'Viet Nam', 'VNM', 'VN'),
(231, 1, 'Virgin Islands (British)', 'VGB', 'VG'),
(232, 1, 'Virgin Islands (U.S.)', 'VIR', 'VI'),
(233, 1, 'Wallis and Futuna Islands', 'WLF', 'WF'),
(234, 1, 'Western Sahara', 'ESH', 'EH'),
(235, 1, 'Yemen', 'YEM', 'YE'),
(236, 1, 'Serbia', 'SRB', 'RS'),
(237, 1, 'The Democratic Republic of Congo', 'DRC', 'DC'),
(238, 1, 'Zambia', 'ZMB', 'ZM'),
(239, 1, 'Zimbabwe', 'ZWE', 'ZW'),
(240, 1, 'East Timor', 'XET', 'XE'),
(241, 1, 'Jersey', 'XJE', 'XJ'),
(242, 1, 'St. Barthelemy', 'XSB', 'XB'),
(243, 1, 'St. Eustatius', 'XSE', 'XU'),
(244, 1, 'Canary Islands', 'XCA', 'XC'),
(245, 1, 'Montenegro', 'MNE', 'ME');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_coupons`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_coupons` (
  `coupon_id` int(16) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(32) NOT NULL DEFAULT '',
  `percent_or_total` enum('percent','total') NOT NULL DEFAULT 'percent',
  `coupon_type` enum('gift','permanent') NOT NULL DEFAULT 'gift',
  `coupon_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to store coupon codes' AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `jos_vm_coupons`
--

INSERT INTO `jos_vm_coupons` (`coupon_id`, `coupon_code`, `percent_or_total`, `coupon_type`, `coupon_value`) VALUES
(1, 'test1', 'total', 'gift', 6.00),
(2, 'test2', 'percent', 'permanent', 15.00),
(3, 'test3', 'total', 'permanent', 4.00),
(4, 'test4', 'total', 'gift', 15.00);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_creditcard`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_creditcard` (
  `creditcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `creditcard_name` varchar(70) NOT NULL DEFAULT '',
  `creditcard_code` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`creditcard_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to store credit card types' AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `jos_vm_creditcard`
--

INSERT INTO `jos_vm_creditcard` (`creditcard_id`, `vendor_id`, `creditcard_name`, `creditcard_code`) VALUES
(1, 1, 'Visa', 'VISA'),
(2, 1, 'MasterCard', 'MC'),
(3, 1, 'American Express', 'amex'),
(4, 1, 'Discover Card', 'discover'),
(5, 1, 'Diners Club', 'diners'),
(6, 1, 'JCB', 'jcb'),
(7, 1, 'Australian Bankcard', 'australian_bc');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_csv`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_csv` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(128) NOT NULL DEFAULT '',
  `field_default_value` text,
  `field_ordering` int(3) NOT NULL DEFAULT '0',
  `field_required` char(1) DEFAULT 'N',
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds all fields which are used on CVS Ex-/Import' AUTO_INCREMENT=26 ;

--
-- Дамп данных таблицы `jos_vm_csv`
--

INSERT INTO `jos_vm_csv` (`field_id`, `field_name`, `field_default_value`, `field_ordering`, `field_required`) VALUES
(1, 'product_sku', '', 1, 'Y'),
(2, 'product_s_desc', '', 5, 'N'),
(3, 'product_desc', '', 6, 'N'),
(4, 'product_thumb_image', '', 7, 'N'),
(5, 'product_full_image', '', 8, 'N'),
(6, 'product_weight', '', 9, 'N'),
(7, 'product_weight_uom', 'KG', 10, 'N'),
(8, 'product_length', '', 11, 'N'),
(9, 'product_width', '', 12, 'N'),
(10, 'product_height', '', 13, 'N'),
(11, 'product_lwh_uom', '', 14, 'N'),
(12, 'product_in_stock', '0', 15, 'N'),
(13, 'product_available_date', '', 16, 'N'),
(14, 'product_discount_id', '', 17, 'N'),
(15, 'product_name', '', 2, 'Y'),
(16, 'product_price', '', 4, 'N'),
(17, 'category_path', '', 3, 'Y'),
(18, 'manufacturer_id', '', 18, 'N'),
(19, 'product_tax_id', '', 19, 'N'),
(20, 'product_sales', '', 20, 'N'),
(21, 'product_parent_id', '0', 21, 'N'),
(22, 'attribute', '', 22, 'N'),
(23, 'custom_attribute', '', 23, 'N'),
(24, 'attributes', '', 24, 'N'),
(25, 'attribute_values', '', 25, 'N');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_currency`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
-- Последняя проверка: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(64) DEFAULT NULL,
  `currency_code` char(3) DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  KEY `idx_currency_name` (`currency_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to store currencies' AUTO_INCREMENT=159 ;

--
-- Дамп данных таблицы `jos_vm_currency`
--

INSERT INTO `jos_vm_currency` (`currency_id`, `currency_name`, `currency_code`) VALUES
(1, 'Andorran Peseta', 'ADP'),
(2, 'United Arab Emirates Dirham', 'AED'),
(3, 'Afghanistan Afghani', 'AFA'),
(4, 'Albanian Lek', 'ALL'),
(5, 'Netherlands Antillian Guilder', 'ANG'),
(6, 'Angolan Kwanza', 'AOK'),
(7, 'Argentine Peso', 'ARS'),
(9, 'Australian Dollar', 'AUD'),
(10, 'Aruban Florin', 'AWG'),
(11, 'Barbados Dollar', 'BBD'),
(12, 'Bangladeshi Taka', 'BDT'),
(14, 'Bulgarian Lev', 'BGL'),
(15, 'Bahraini Dinar', 'BHD'),
(16, 'Burundi Franc', 'BIF'),
(17, 'Bermudian Dollar', 'BMD'),
(18, 'Brunei Dollar', 'BND'),
(19, 'Bolivian Boliviano', 'BOB'),
(20, 'Brazilian Real', 'BRL'),
(21, 'Bahamian Dollar', 'BSD'),
(22, 'Bhutan Ngultrum', 'BTN'),
(23, 'Burma Kyat', 'BUK'),
(24, 'Botswanian Pula', 'BWP'),
(25, 'Belize Dollar', 'BZD'),
(26, 'Canadian Dollar', 'CAD'),
(27, 'Swiss Franc', 'CHF'),
(28, 'Chilean Unidades de Fomento', 'CLF'),
(29, 'Chilean Peso', 'CLP'),
(30, 'Yuan (Chinese) Renminbi', 'CNY'),
(31, 'Colombian Peso', 'COP'),
(32, 'Costa Rican Colon', 'CRC'),
(33, 'Czech Koruna', 'CZK'),
(34, 'Cuban Peso', 'CUP'),
(35, 'Cape Verde Escudo', 'CVE'),
(36, 'Cyprus Pound', 'CYP'),
(40, 'Danish Krone', 'DKK'),
(41, 'Dominican Peso', 'DOP'),
(42, 'Algerian Dinar', 'DZD'),
(43, 'Ecuador Sucre', 'ECS'),
(44, 'Egyptian Pound', 'EGP'),
(46, 'Ethiopian Birr', 'ETB'),
(47, 'Euro', 'EUR'),
(49, 'Fiji Dollar', 'FJD'),
(50, 'Falkland Islands Pound', 'FKP'),
(52, 'British Pound', 'GBP'),
(53, 'Ghanaian Cedi', 'GHC'),
(54, 'Gibraltar Pound', 'GIP'),
(55, 'Gambian Dalasi', 'GMD'),
(56, 'Guinea Franc', 'GNF'),
(58, 'Guatemalan Quetzal', 'GTQ'),
(59, 'Guinea-Bissau Peso', 'GWP'),
(60, 'Guyanan Dollar', 'GYD'),
(61, 'Hong Kong Dollar', 'HKD'),
(62, 'Honduran Lempira', 'HNL'),
(63, 'Haitian Gourde', 'HTG'),
(64, 'Hungarian Forint', 'HUF'),
(65, 'Indonesian Rupiah', 'IDR'),
(66, 'Irish Punt', 'IEP'),
(67, 'Israeli Shekel', 'ILS'),
(68, 'Indian Rupee', 'INR'),
(69, 'Iraqi Dinar', 'IQD'),
(70, 'Iranian Rial', 'IRR'),
(73, 'Jamaican Dollar', 'JMD'),
(74, 'Jordanian Dinar', 'JOD'),
(75, 'Japanese Yen', 'JPY'),
(76, 'Kenyan Shilling', 'KES'),
(77, 'Kampuchean (Cambodian) Riel', 'KHR'),
(78, 'Comoros Franc', 'KMF'),
(79, 'North Korean Won', 'KPW'),
(80, '(South) Korean Won', 'KRW'),
(81, 'Kuwaiti Dinar', 'KWD'),
(82, 'Cayman Islands Dollar', 'KYD'),
(83, 'Lao Kip', 'LAK'),
(84, 'Lebanese Pound', 'LBP'),
(85, 'Sri Lanka Rupee', 'LKR'),
(86, 'Liberian Dollar', 'LRD'),
(87, 'Lesotho Loti', 'LSL'),
(89, 'Libyan Dinar', 'LYD'),
(90, 'Moroccan Dirham', 'MAD'),
(91, 'Malagasy Franc', 'MGF'),
(92, 'Mongolian Tugrik', 'MNT'),
(93, 'Macau Pataca', 'MOP'),
(94, 'Mauritanian Ouguiya', 'MRO'),
(95, 'Maltese Lira', 'MTL'),
(96, 'Mauritius Rupee', 'MUR'),
(97, 'Maldive Rufiyaa', 'MVR'),
(98, 'Malawi Kwacha', 'MWK'),
(99, 'Mexican Peso', 'MXP'),
(100, 'Malaysian Ringgit', 'MYR'),
(101, 'Mozambique Metical', 'MZM'),
(102, 'Nigerian Naira', 'NGN'),
(103, 'Nicaraguan Cordoba', 'NIC'),
(105, 'Norwegian Kroner', 'NOK'),
(106, 'Nepalese Rupee', 'NPR'),
(107, 'New Zealand Dollar', 'NZD'),
(108, 'Omani Rial', 'OMR'),
(109, 'Panamanian Balboa', 'PAB'),
(110, 'Peruvian Nuevo Sol', 'PEN'),
(111, 'Papua New Guinea Kina', 'PGK'),
(112, 'Philippine Peso', 'PHP'),
(113, 'Pakistan Rupee', 'PKR'),
(114, 'Polish Złoty', 'PLN'),
(116, 'Paraguay Guarani', 'PYG'),
(117, 'Qatari Rial', 'QAR'),
(118, 'Romanian Leu', 'RON'),
(119, 'Rwanda Franc', 'RWF'),
(120, 'Saudi Arabian Riyal', 'SAR'),
(121, 'Solomon Islands Dollar', 'SBD'),
(122, 'Seychelles Rupee', 'SCR'),
(123, 'Sudanese Pound', 'SDP'),
(124, 'Swedish Krona', 'SEK'),
(125, 'Singapore Dollar', 'SGD'),
(126, 'St. Helena Pound', 'SHP'),
(127, 'Sierra Leone Leone', 'SLL'),
(128, 'Somali Shilling', 'SOS'),
(129, 'Suriname Guilder', 'SRG'),
(130, 'Sao Tome and Principe Dobra', 'STD'),
(131, 'Russian Ruble', 'RUB'),
(132, 'El Salvador Colon', 'SVC'),
(133, 'Syrian Potmd', 'SYP'),
(134, 'Swaziland Lilangeni', 'SZL'),
(135, 'Thai Bath', 'THB'),
(136, 'Tunisian Dinar', 'TND'),
(137, 'Tongan Pa''anga', 'TOP'),
(138, 'East Timor Escudo', 'TPE'),
(139, 'Turkish Lira', 'TRY'),
(140, 'Trinidad and Tobago Dollar', 'TTD'),
(141, 'Taiwan Dollar', 'TWD'),
(142, 'Tanzanian Shilling', 'TZS'),
(143, 'Uganda Shilling', 'UGS'),
(144, 'US Dollar', 'USD'),
(145, 'Uruguayan Peso', 'UYP'),
(146, 'Venezualan Bolivar', 'VEB'),
(147, 'Vietnamese Dong', 'VND'),
(148, 'Vanuatu Vatu', 'VUV'),
(149, 'Samoan Tala', 'WST'),
(150, 'Democratic Yemeni Dinar', 'YDD'),
(151, 'Yemeni Rial', 'YER'),
(152, 'Dinar', 'RSD'),
(153, 'South African Rand', 'ZAR'),
(154, 'Zambian Kwacha', 'ZMK'),
(155, 'Zaire Zaire', 'ZRZ'),
(156, 'Zimbabwe Dollar', 'ZWD'),
(157, 'Slovak Koruna', 'SKK'),
(158, 'Armenian Dram', 'AMD');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_export`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_export` (
  `export_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `export_name` varchar(255) DEFAULT NULL,
  `export_desc` text NOT NULL,
  `export_class` varchar(50) NOT NULL,
  `export_enabled` char(1) NOT NULL DEFAULT 'N',
  `export_config` text NOT NULL,
  `iscore` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`export_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Export Modules' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_function`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
-- Последняя проверка: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_function` (
  `function_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `function_name` varchar(32) DEFAULT NULL,
  `function_class` varchar(32) DEFAULT NULL,
  `function_method` varchar(32) DEFAULT NULL,
  `function_description` text,
  `function_perms` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`function_id`),
  KEY `idx_function_module_id` (`module_id`),
  KEY `idx_function_name` (`function_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to map a function alias to a ''real'' class::function' AUTO_INCREMENT=195 ;

--
-- Дамп данных таблицы `jos_vm_function`
--

INSERT INTO `jos_vm_function` (`function_id`, `module_id`, `function_name`, `function_class`, `function_method`, `function_description`, `function_perms`) VALUES
(1, 1, 'userAdd', 'ps_user', 'add', '', 'admin,storeadmin'),
(2, 1, 'userDelete', 'ps_user', 'delete', '', 'admin,storeadmin'),
(3, 1, 'userUpdate', 'ps_user', 'update', '', 'admin,storeadmin'),
(31, 2, 'productAdd', 'ps_product', 'add', '', 'admin,storeadmin'),
(6, 1, 'functionAdd', 'ps_function', 'add', '', 'admin'),
(7, 1, 'functionUpdate', 'ps_function', 'update', '', 'admin'),
(8, 1, 'functionDelete', 'ps_function', 'delete', '', 'admin'),
(9, 1, 'userLogout', 'ps_user', 'logout', '', 'none'),
(10, 1, 'userAddressAdd', 'ps_user_address', 'add', '', 'admin,storeadmin,shopper,demo'),
(11, 1, 'userAddressUpdate', 'ps_user_address', 'update', '', 'admin,storeadmin,shopper'),
(12, 1, 'userAddressDelete', 'ps_user_address', 'delete', '', 'admin,storeadmin,shopper'),
(13, 1, 'moduleAdd', 'ps_module', 'add', '', 'admin'),
(14, 1, 'moduleUpdate', 'ps_module', 'update', '', 'admin'),
(15, 1, 'moduleDelete', 'ps_module', 'delete', '', 'admin'),
(16, 1, 'userLogin', 'ps_user', 'login', '', 'none'),
(17, 3, 'vendorAdd', 'ps_vendor', 'add', '', 'admin'),
(18, 3, 'vendorUpdate', 'ps_vendor', 'update', '', 'admin,storeadmin'),
(19, 3, 'vendorDelete', 'ps_vendor', 'delete', '', 'admin'),
(20, 3, 'vendorCategoryAdd', 'ps_vendor_category', 'add', '', 'admin'),
(21, 3, 'vendorCategoryUpdate', 'ps_vendor_category', 'update', '', 'admin'),
(22, 3, 'vendorCategoryDelete', 'ps_vendor_category', 'delete', '', 'admin'),
(23, 4, 'shopperAdd', 'ps_shopper', 'add', '', 'none'),
(24, 4, 'shopperDelete', 'ps_shopper', 'delete', '', 'admin,storeadmin'),
(25, 4, 'shopperUpdate', 'ps_shopper', 'update', '', 'admin,storeadmin,shopper'),
(26, 4, 'shopperGroupAdd', 'ps_shopper_group', 'add', '', 'admin,storeadmin'),
(27, 4, 'shopperGroupUpdate', 'ps_shopper_group', 'update', '', 'admin,storeadmin'),
(28, 4, 'shopperGroupDelete', 'ps_shopper_group', 'delete', '', 'admin,storeadmin'),
(30, 5, 'orderStatusSet', 'ps_order', 'order_status_update', '', 'admin,storeadmin'),
(32, 2, 'productDelete', 'ps_product', 'delete', '', 'admin,storeadmin'),
(33, 2, 'productUpdate', 'ps_product', 'update', '', 'admin,storeadmin'),
(34, 2, 'productCategoryAdd', 'ps_product_category', 'add', '', 'admin,storeadmin'),
(35, 2, 'productCategoryUpdate', 'ps_product_category', 'update', '', 'admin,storeadmin'),
(36, 2, 'productCategoryDelete', 'ps_product_category', 'delete', '', 'admin,storeadmin'),
(37, 2, 'productPriceAdd', 'ps_product_price', 'add', '', 'admin,storeadmin'),
(38, 2, 'productPriceUpdate', 'ps_product_price', 'update', '', 'admin,storeadmin'),
(39, 2, 'productPriceDelete', 'ps_product_price', 'delete', '', 'admin,storeadmin'),
(40, 2, 'productAttributeAdd', 'ps_product_attribute', 'add', '', 'admin,storeadmin'),
(41, 2, 'productAttributeUpdate', 'ps_product_attribute', 'update', '', 'admin,storeadmin'),
(42, 2, 'productAttributeDelete', 'ps_product_attribute', 'delete', '', 'admin,storeadmin'),
(43, 7, 'cartAdd', 'ps_cart', 'add', '', 'none'),
(44, 7, 'cartUpdate', 'ps_cart', 'update', '', 'none'),
(45, 7, 'cartDelete', 'ps_cart', 'delete', '', 'none'),
(46, 10, 'checkoutComplete', 'ps_checkout', 'add', '', 'shopper,storeadmin,admin'),
(48, 8, 'paymentMethodUpdate', 'ps_payment_method', 'update', '', 'admin,storeadmin'),
(49, 8, 'paymentMethodAdd', 'ps_payment_method', 'add', '', 'admin,storeadmin'),
(50, 8, 'paymentMethodDelete', 'ps_payment_method', 'delete', '', 'admin,storeadmin'),
(51, 5, 'orderDelete', 'ps_order', 'delete', '', 'admin,storeadmin'),
(52, 11, 'addTaxRate', 'ps_tax', 'add', '', 'admin,storeadmin'),
(53, 11, 'updateTaxRate', 'ps_tax', 'update', '', 'admin,storeadmin'),
(54, 11, 'deleteTaxRate', 'ps_tax', 'delete', '', 'admin,storeadmin'),
(55, 10, 'checkoutValidateST', 'ps_checkout', 'validate_shipto', '', 'none'),
(59, 5, 'orderStatusUpdate', 'ps_order_status', 'update', '', 'admin,storeadmin'),
(60, 5, 'orderStatusAdd', 'ps_order_status', 'add', '', 'storeadmin,admin'),
(61, 5, 'orderStatusDelete', 'ps_order_status', 'delete', '', 'admin,storeadmin'),
(62, 1, 'currencyAdd', 'ps_currency', 'add', 'add a currency', 'storeadmin,admin'),
(63, 1, 'currencyUpdate', 'ps_currency', 'update', '        update a currency', 'storeadmin,admin'),
(64, 1, 'currencyDelete', 'ps_currency', 'delete', 'delete a currency', 'storeadmin,admin'),
(65, 1, 'countryAdd', 'ps_country', 'add', 'Add a country ', 'storeadmin,admin'),
(66, 1, 'countryUpdate', 'ps_country', 'update', 'Update a country record', 'storeadmin,admin'),
(67, 1, 'countryDelete', 'ps_country', 'delete', 'Delete a country record', 'storeadmin,admin'),
(68, 2, 'product_csv', 'ps_csv', 'upload_csv', '', 'admin'),
(110, 7, 'waitingListAdd', 'zw_waiting_list', 'add', '', 'none'),
(111, 13, 'addzone', 'ps_zone', 'add', 'This will add a zone', 'admin,storeadmin'),
(112, 13, 'updatezone', 'ps_zone', 'update', 'This will update a zone', 'admin,storeadmin'),
(113, 13, 'deletezone', 'ps_zone', 'delete', 'This will delete a zone', 'admin,storeadmin'),
(114, 13, 'zoneassign', 'ps_zone', 'assign', 'This will assign a country to a zone', 'admin,storeadmin'),
(115, 1, 'writeConfig', 'ps_config', 'writeconfig', 'This will write the configuration details to virtuemart.cfg.php', 'admin'),
(116, 12839, 'carrierAdd', 'ps_shipping', 'add', '', 'admin,storeadmin'),
(117, 12839, 'carrierDelete', 'ps_shipping', 'delete', '', 'admin,storeadmin'),
(118, 12839, 'carrierUpdate', 'ps_shipping', 'update', '', 'admin,storeadmin'),
(119, 12839, 'rateAdd', 'ps_shipping', 'rate_add', '', 'admin,storeadmin'),
(120, 12839, 'rateUpdate', 'ps_shipping', 'rate_update', '', 'admin,shopadmin'),
(121, 12839, 'rateDelete', 'ps_shipping', 'rate_delete', '', 'admin,storeadmin'),
(122, 10, 'checkoutProcess', 'ps_checkout', 'process', '', 'none'),
(123, 5, 'downloadRequest', 'ps_order', 'download_request', 'This checks if the download request is valid and sends the file to the browser as file download if the request was successful, otherwise echoes an error', 'none'),
(128, 99, 'manufacturerAdd', 'ps_manufacturer', 'add', '', 'admin,storeadmin'),
(129, 99, 'manufacturerUpdate', 'ps_manufacturer', 'update', '', 'admin,storeadmin'),
(130, 99, 'manufacturerDelete', 'ps_manufacturer', 'delete', '', 'admin,storeadmin'),
(131, 99, 'manufacturercategoryAdd', 'ps_manufacturer_category', 'add', '', 'admin,storeadmin'),
(132, 99, 'manufacturercategoryUpdate', 'ps_manufacturer_category', 'update', '', 'admin,storeadmin'),
(133, 99, 'manufacturercategoryDelete', 'ps_manufacturer_category', 'delete', '', 'admin,storeadmin'),
(134, 7, 'addReview', 'ps_reviews', 'process_review', 'This lets the user add a review and rating to a product.', 'admin,storeadmin,shopper,demo'),
(135, 7, 'productReviewDelete', 'ps_reviews', 'delete_review', 'This deletes a review and from a product.', 'admin,storeadmin'),
(136, 8, 'creditcardAdd', 'ps_creditcard', 'add', 'Adds a Credit Card entry.', 'admin,storeadmin'),
(137, 8, 'creditcardUpdate', 'ps_creditcard', 'update', 'Updates a Credit Card entry.', 'admin,storeadmin'),
(138, 8, 'creditcardDelete', 'ps_creditcard', 'delete', 'Deletes a Credit Card entry.', 'admin,storeadmin'),
(139, 2, 'changePublishState', 'vmAbstractObject.class', 'handlePublishState', 'Changes the publish field of an item, so that it can be published or unpublished easily.', 'admin,storeadmin'),
(140, 2, 'export_csv', 'ps_csv', 'export_csv', 'This function exports all relevant product data to CSV.', 'admin,storeadmin'),
(141, 2, 'reorder', 'ps_product_category', 'reorder', 'Changes the list order of a category.', 'admin,storeadmin'),
(142, 2, 'discountAdd', 'ps_product_discount', 'add', 'Adds a discount.', 'admin,storeadmin'),
(143, 2, 'discountUpdate', 'ps_product_discount', 'update', 'Updates a discount.', 'admin,storeadmin'),
(144, 2, 'discountDelete', 'ps_product_discount', 'delete', 'Deletes a discount.', 'admin,storeadmin'),
(145, 8, 'shippingmethodSave', 'ps_shipping_method', 'save', '', 'admin,storeadmin'),
(146, 2, 'uploadProductFile', 'ps_product_files', 'add', 'Uploads and Adds a Product Image/File.', 'admin,storeadmin'),
(147, 2, 'updateProductFile', 'ps_product_files', 'update', 'Updates a Product Image/File.', 'admin,storeadmin'),
(148, 2, 'deleteProductFile', 'ps_product_files', 'delete', 'Deletes a Product Image/File.', 'admin,storeadmin'),
(149, 12843, 'couponAdd', 'ps_coupon', 'add_coupon_code', 'Adds a Coupon.', 'admin,storeadmin'),
(150, 12843, 'couponUpdate', 'ps_coupon', 'update_coupon', 'Updates a Coupon.', 'admin,storeadmin'),
(151, 12843, 'couponDelete', 'ps_coupon', 'remove_coupon_code', 'Deletes a Coupon.', 'admin,storeadmin'),
(152, 12843, 'couponProcess', 'ps_coupon', 'process_coupon_code', 'Processes a Coupon.', 'admin,storeadmin,shopper,demo'),
(153, 2, 'ProductTypeAdd', 'ps_product_type', 'add', 'Function add a Product Type and create new table product_type_<id>.', 'admin'),
(154, 2, 'ProductTypeUpdate', 'ps_product_type', 'update', 'Update a Product Type.', 'admin'),
(155, 2, 'ProductTypeDelete', 'ps_product_type', 'delete', 'Delete a Product Type and drop table product_type_<id>.', 'admin'),
(156, 2, 'ProductTypeReorder', 'ps_product_type', 'reorder', 'Changes the list order of a Product Type.', 'admin'),
(157, 2, 'ProductTypeAddParam', 'ps_product_type_parameter', 'add_parameter', 'Function add a Parameter into a Product Type and create new column in table product_type_<id>.', 'admin'),
(158, 2, 'ProductTypeUpdateParam', 'ps_product_type_parameter', 'update_parameter', 'Function update a Parameter in a Product Type and a column in table product_type_<id>.', 'admin'),
(159, 2, 'ProductTypeDeleteParam', 'ps_product_type_parameter', 'delete_parameter', 'Function delete a Parameter from a Product Type and drop a column in table product_type_<id>.', 'admin'),
(160, 2, 'ProductTypeReorderParam', 'ps_product_type_parameter', 'reorder_parameter', 'Changes the list order of a Parameter.', 'admin'),
(161, 2, 'productProductTypeAdd', 'ps_product_product_type', 'add', 'Add a Product into a Product Type.', 'admin,storeadmin'),
(162, 2, 'productProductTypeDelete', 'ps_product_product_type', 'delete', 'Delete a Product from a Product Type.', 'admin,storeadmin'),
(163, 1, 'stateAdd', 'ps_country', 'addState', 'Add a State ', 'storeadmin,admin'),
(164, 1, 'stateUpdate', 'ps_country', 'updateState', 'Update a state record', 'storeadmin,admin'),
(165, 1, 'stateDelete', 'ps_country', 'deleteState', 'Delete a state record', 'storeadmin,admin'),
(166, 2, 'csvFieldAdd', 'ps_csv', 'add', 'Add a CSV Field ', 'storeadmin,admin'),
(167, 2, 'csvFieldUpdate', 'ps_csv', 'update', 'Update a CSV Field', 'storeadmin,admin'),
(168, 2, 'csvFieldDelete', 'ps_csv', 'delete', 'Delete a CSV Field', 'storeadmin,admin'),
(169, 1, 'userfieldSave', 'ps_userfield', 'savefield', 'add or edit a user field', 'admin'),
(170, 1, 'userfieldDelete', 'ps_userfield', 'deletefield', '', 'admin'),
(171, 1, 'changeordering', 'vmAbstractObject.class', 'handleordering', '', 'admin'),
(172, 2, 'moveProduct', 'ps_product', 'move', 'Move products from one category to another.', 'admin,storeadmin'),
(173, 7, 'productAsk', 'ps_communication', 'mail_question', 'Lets the customer send a question about a specific product.', 'none'),
(174, 7, 'recommendProduct', 'ps_communication', 'sendRecommendation', 'Lets the customer send a recommendation about a specific product to a friend.', 'none'),
(175, 2, 'reviewUpdate', 'ps_reviews', 'update', 'Modify a review about a specific product.', 'admin'),
(176, 8, 'ExportUpdate', 'ps_export', 'update', '', 'admin,storeadmin'),
(177, 8, 'ExportAdd', 'ps_export', 'add', '', 'admin,storeadmin'),
(178, 8, 'ExportDelete', 'ps_export', 'delete', '', 'admin,storeadmin'),
(179, 1, 'writeThemeConfig', 'ps_config', 'writeThemeConfig', 'Writes a theme configuration file.', 'admin'),
(180, 1, 'usergroupAdd', 'usergroup.class', 'add', 'Add a new user group', 'admin'),
(181, 1, 'usergroupUpdate', 'usergroup.class', 'update', 'Update an user group', 'admin'),
(182, 1, 'usergroupDelete', 'usergroup.class', 'delete', 'Delete an user group', 'admin'),
(183, 1, 'setModulePermissions', 'ps_module', 'update_permissions', '', 'admin'),
(184, 1, 'setFunctionPermissions', 'ps_function', 'update_permissions', '', 'admin'),
(185, 2, 'insertDownloadsForProduct', 'ps_order', 'insert_downloads_for_product', '', 'admin'),
(186, 5, 'mailDownloadId', 'ps_order', 'mail_download_id', '', 'storeadmin,admin'),
(187, 7, 'replaceSavedCart', 'ps_cart', 'replaceCart', 'Replace cart with saved cart', 'none'),
(188, 7, 'mergeSavedCart', 'ps_cart', 'mergeSaved', 'Merge saved cart with cart', 'none'),
(189, 7, 'deleteSavedCart', 'ps_cart', 'deleteCart', 'Delete saved cart', 'none'),
(190, 7, 'savedCartDelete', 'ps_cart', 'deleteSaved', 'Delete items from saved cart', 'none'),
(191, 7, 'savedCartUpdate', 'ps_cart', 'updateSaved', 'Update saved cart items', 'none'),
(192, 1, 'getupdatepackage', 'update.class', 'getPatchPackage', 'Retrieves the Patch Package from the virtuemart.net Servers.', 'admin'),
(193, 1, 'applypatchpackage', 'update.class', 'applyPatch', 'Applies the Patch using the instructions from the update.xml file in the downloaded patch.', 'admin'),
(194, 1, 'removePatchPackage', 'update.class', 'removePackageFile', 'Removes  a Patch Package File and its extracted contents.', 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_manufacturer`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_manufacturer` (
  `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT,
  `mf_name` varchar(64) DEFAULT NULL,
  `mf_email` varchar(255) DEFAULT NULL,
  `mf_desc` text,
  `mf_category_id` int(11) DEFAULT NULL,
  `mf_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`manufacturer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Manufacturers are those who create products' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_vm_manufacturer`
--

INSERT INTO `jos_vm_manufacturer` (`manufacturer_id`, `mf_name`, `mf_email`, `mf_desc`, `mf_category_id`, `mf_url`) VALUES
(1, 'Manufacturer', 'info@manufacturer.com', 'An example for a manufacturer', 1, 'http://www.a-url.com');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_manufacturer_category`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_manufacturer_category` (
  `mf_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `mf_category_name` varchar(64) DEFAULT NULL,
  `mf_category_desc` text,
  PRIMARY KEY (`mf_category_id`),
  KEY `idx_manufacturer_category_category_name` (`mf_category_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Manufacturers are assigned to these categories' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_vm_manufacturer_category`
--

INSERT INTO `jos_vm_manufacturer_category` (`mf_category_id`, `mf_category_name`, `mf_category_desc`) VALUES
(1, '-default-', 'This is the default manufacturer category');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_module`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) DEFAULT NULL,
  `module_description` text,
  `module_perms` varchar(255) DEFAULT NULL,
  `module_publish` char(1) DEFAULT NULL,
  `list_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`module_id`),
  KEY `idx_module_name` (`module_name`),
  KEY `idx_module_list_order` (`list_order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='VirtueMart Core Modules, not: Joomla modules' AUTO_INCREMENT=12844 ;

--
-- Дамп данных таблицы `jos_vm_module`
--

INSERT INTO `jos_vm_module` (`module_id`, `module_name`, `module_description`, `module_perms`, `module_publish`, `list_order`) VALUES
(1, 'admin', '<h4>ADMINISTRATIVE USERS ONLY</h4>\r\n\r\n<p>Only used for the following:</p>\r\n<OL>\r\n\r\n<LI>User Maintenance</LI>\r\n<LI>Module Maintenance</LI>\r\n<LI>Function Maintenance</LI>\r\n</OL>\r\n', 'admin', 'Y', 1),
(2, 'product', '<p>Here you can adminster your online catalog of products.  The Product Administrator allows you to create product categories, create new products, edit product attributes, and add product items for each attribute value.</p>', 'storeadmin,admin', 'Y', 4),
(3, 'vendor', '<h4>ADMINISTRATIVE USERS ONLY</h4>\r\n<p>Here you can manage the vendors on the phpShop system.</p>', 'admin', 'Y', 6),
(4, 'shopper', '<p>Manage shoppers in your store.  Allows you to create shopper groups.  Shopper groups can be used when setting the price for a product.  This allows you to create different prices for different types of users.  An example of this would be to have a ''wholesale'' group and a ''retail'' group. </p>', 'admin,storeadmin', 'Y', 4),
(5, 'order', '<p>View Order and Update Order Status.</p>', 'admin,storeadmin', 'Y', 5),
(6, 'msgs', 'This module is unprotected an used for displaying system messages to users.  We need to have an area that does not require authorization when things go wrong.', 'none', 'N', 99),
(7, 'shop', 'This is the Washupito store module.  This is the demo store included with the phpShop distribution.', 'none', 'Y', 99),
(8, 'store', '', 'storeadmin,admin', 'Y', 2),
(9, 'account', 'This module allows shoppers to update their account information and view previously placed orders.', 'shopper,storeadmin,admin,demo', 'N', 99),
(10, 'checkout', '', 'none', 'N', 99),
(11, 'tax', 'The tax module allows you to set tax rates for states or regions within a country.  The rate is set as a decimal figure.  For example, 2 percent tax would be 0.02.', 'admin,storeadmin', 'Y', 8),
(12, 'reportbasic', 'The report basic module allows you to do queries on all orders.', 'admin,storeadmin', 'Y', 7),
(13, 'zone', 'This is the zone-shipping module. Here you can manage your shipping costs according to Zones.', 'admin,storeadmin', 'N', 9),
(12839, 'shipping', '<h4>Shipping</h4><p>Let this module calculate the shipping fees for your customers.<br>Create carriers for shipping areas and weight groups.</p>', 'admin,storeadmin', 'Y', 10),
(99, 'manufacturer', 'Manage the manufacturers of products in your store.', 'storeadmin,admin', 'Y', 12),
(12842, 'help', 'Help Module', 'admin,storeadmin', 'Y', 13),
(12843, 'coupon', 'Coupon Management', 'admin,storeadmin', 'Y', 11);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_orders`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `order_number` varchar(32) DEFAULT NULL,
  `user_info_id` varchar(32) DEFAULT NULL,
  `order_total` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_subtotal` decimal(15,5) DEFAULT NULL,
  `order_tax` decimal(10,2) DEFAULT NULL,
  `order_tax_details` text NOT NULL,
  `order_shipping` decimal(10,2) DEFAULT NULL,
  `order_shipping_tax` decimal(10,2) DEFAULT NULL,
  `coupon_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(32) DEFAULT NULL,
  `order_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_currency` varchar(16) DEFAULT NULL,
  `order_status` char(1) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `ship_method_id` varchar(255) DEFAULT NULL,
  `customer_note` text NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`order_id`),
  KEY `idx_orders_user_id` (`user_id`),
  KEY `idx_orders_vendor_id` (`vendor_id`),
  KEY `idx_orders_order_number` (`order_number`),
  KEY `idx_orders_user_info_id` (`user_info_id`),
  KEY `idx_orders_ship_method_id` (`ship_method_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Used to store all orders' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_order_history`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_order_history` (
  `order_status_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `order_status_code` char(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `customer_notified` int(1) DEFAULT '0',
  `comments` text,
  PRIMARY KEY (`order_status_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores all actions and changes that occur to an order' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_order_item`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_order_item` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `user_info_id` varchar(32) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_item_sku` varchar(64) NOT NULL DEFAULT '',
  `order_item_name` varchar(64) NOT NULL DEFAULT '',
  `product_quantity` int(11) DEFAULT NULL,
  `product_item_price` decimal(15,5) DEFAULT NULL,
  `product_final_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_item_currency` varchar(16) DEFAULT NULL,
  `order_status` char(1) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `product_attribute` text,
  PRIMARY KEY (`order_item_id`),
  KEY `idx_order_item_order_id` (`order_id`),
  KEY `idx_order_item_user_info_id` (`user_info_id`),
  KEY `idx_order_item_vendor_id` (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores all items (products) which are part of an order' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_order_payment`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_order_payment` (
  `order_id` int(11) NOT NULL DEFAULT '0',
  `payment_method_id` int(11) DEFAULT NULL,
  `order_payment_code` varchar(30) NOT NULL DEFAULT '',
  `order_payment_number` blob,
  `order_payment_expire` int(11) DEFAULT NULL,
  `order_payment_name` varchar(255) DEFAULT NULL,
  `order_payment_log` text,
  `order_payment_trans_id` text NOT NULL,
  KEY `idx_order_payment_order_id` (`order_id`),
  KEY `idx_order_payment_method_id` (`payment_method_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='The payment method that was chosen for a specific order';

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_order_status`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_order_status` (
  `order_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_status_code` char(1) NOT NULL DEFAULT '',
  `order_status_name` varchar(64) DEFAULT NULL,
  `order_status_description` text NOT NULL,
  `list_order` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_status_id`),
  KEY `idx_order_status_list_order` (`list_order`),
  KEY `idx_order_status_vendor_id` (`vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='All available order statuses' AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `jos_vm_order_status`
--

INSERT INTO `jos_vm_order_status` (`order_status_id`, `order_status_code`, `order_status_name`, `order_status_description`, `list_order`, `vendor_id`) VALUES
(1, 'P', 'Pending', '', 1, 1),
(2, 'C', 'Confirmed', '', 2, 1),
(3, 'X', 'Cancelled', '', 3, 1),
(4, 'R', 'Refunded', '', 4, 1),
(5, 'S', 'Shipped', '', 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_order_user_info`
--
-- Создание: Июн 04 2012 г., 21:38
-- Последнее обновление: Июн 04 2012 г., 21:38
-- Последняя проверка: Июн 04 2012 г., 21:38
--

CREATE TABLE IF NOT EXISTS `jos_vm_order_user_info` (
  `order_info_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `address_type` char(2) DEFAULT NULL,
  `address_type_name` varchar(32) DEFAULT NULL,
  `company` varchar(64) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `phone_1` varchar(255) DEFAULT NULL,
  `phone_2` varchar(32) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(64) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `extra_field_1` varchar(255) DEFAULT NULL,
  `extra_field_2` varchar(255) DEFAULT NULL,
  `extra_field_3` varchar(255) DEFAULT NULL,
  `extra_field_4` char(1) DEFAULT NULL,
  `extra_field_5` char(1) DEFAULT NULL,
  `bank_account_nr` varchar(32) NOT NULL DEFAULT '',
  `bank_name` varchar(32) NOT NULL DEFAULT '',
  `bank_sort_code` varchar(16) NOT NULL DEFAULT '',
  `bank_iban` varchar(64) NOT NULL DEFAULT '',
  `bank_account_holder` varchar(48) NOT NULL DEFAULT '',
  `bank_account_type` enum('Checking','Business Checking','Savings') NOT NULL DEFAULT 'Checking',
  PRIMARY KEY (`order_info_id`),
  KEY `idx_order_info_order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores the BillTo and ShipTo Information at order time' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_payment_method`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 04 2012 г., 09:47
--

CREATE TABLE IF NOT EXISTS `jos_vm_payment_method` (
  `payment_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `payment_method_name` varchar(255) DEFAULT NULL,
  `payment_class` varchar(50) NOT NULL DEFAULT '',
  `shopper_group_id` int(11) DEFAULT NULL,
  `payment_method_discount` decimal(12,2) DEFAULT NULL,
  `payment_method_discount_is_percent` tinyint(1) NOT NULL,
  `payment_method_discount_max_amount` decimal(10,2) NOT NULL,
  `payment_method_discount_min_amount` decimal(10,2) NOT NULL,
  `list_order` int(11) DEFAULT NULL,
  `payment_method_code` varchar(8) DEFAULT NULL,
  `enable_processor` char(1) DEFAULT NULL,
  `is_creditcard` tinyint(1) NOT NULL DEFAULT '0',
  `payment_enabled` char(1) NOT NULL DEFAULT 'N',
  `accepted_creditcards` varchar(128) NOT NULL DEFAULT '',
  `payment_extrainfo` text NOT NULL,
  `payment_passkey` blob NOT NULL,
  PRIMARY KEY (`payment_method_id`),
  KEY `idx_payment_method_vendor_id` (`vendor_id`),
  KEY `idx_payment_method_name` (`payment_method_name`),
  KEY `idx_payment_method_list_order` (`list_order`),
  KEY `idx_payment_method_shopper_group_id` (`shopper_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='The payment methods of your store' AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `jos_vm_payment_method`
--

INSERT INTO `jos_vm_payment_method` (`payment_method_id`, `vendor_id`, `payment_method_name`, `payment_class`, `shopper_group_id`, `payment_method_discount`, `payment_method_discount_is_percent`, `payment_method_discount_max_amount`, `payment_method_discount_min_amount`, `list_order`, `payment_method_code`, `enable_processor`, `is_creditcard`, `payment_enabled`, `accepted_creditcards`, `payment_extrainfo`, `payment_passkey`) VALUES
(1, 1, 'Purchase Order', '', 6, 0.00, 0, 0.00, 0.00, 4, 'PO', 'N', 0, 'N', '', '', ''),
(2, 1, 'Cash On Delivery', '', 5, -2.00, 0, 0.00, 0.00, 5, 'COD', 'N', 0, 'N', '', '', ''),
(3, 1, 'Credit Card', 'ps_authorize', 5, 0.00, 0, 0.00, 0.00, 0, 'AN', 'Y', 0, 'Y', '1,2,6,7,', '', ''),
(4, 1, 'PayPal (new API)', 'ps_paypal_api', 5, 0.00, 0, 0.00, 0.00, 0, 'PP_API', 'Y', 1, 'Y', '', '', ''),
(5, 1, 'PayMate', 'ps_paymate', 5, 0.00, 0, 0.00, 0.00, 0, 'PM', 'P', 0, 'N', '', '<script type="text/javascript" language="javascript">\n  function openExpress(){\n      var url = ''https://www.paymate.com/PayMate/ExpressPayment?mid=<?php echo PAYMATE_USERNAME.\n          "&amt=".$db->f("order_total").\n   "&currency=".$_SESSION[''vendor_currency''].\n       "&ref=".$db->f("order_id").\n      "&pmt_sender_email=".$user->email.\n         "&pmt_contact_firstname=".$user->first_name.\n       "&pmt_contact_surname=".$user->last_name.\n          "&regindi_address1=".$user->address_1.\n     "&regindi_address2=".$user->address_2.\n     "&regindi_sub=".$user->city.\n       "&regindi_pcode=".$user->zip;?>''\n        var newWin = window.open(url, ''wizard'', ''height=640,width=500,scrollbars=0,toolbar=no'');\n  self.name = ''parent'';\n       newWin.focus();\n  }\n  </script>\n  <div align="center">\n  <p>\n  <a href="javascript:openExpress();">\n  <img src="https://www.paymate.com/homepage/images/butt_PayNow.gif" border="0" alt="Pay with Paymate Express">\n  <br />click here to pay your account</a>\n  </p>\n  </div>', ''),
(6, 1, 'WorldPay', 'ps_worldpay', 5, 0.00, 0, 0.00, 0.00, 0, 'WP', 'P', 0, 'N', '', '<form action="https://select.worldpay.com/wcc/purchase" method="post">\n                                                <input type=hidden name="testMode" value="100"> \n                                                  <input type="hidden" name="instId" value="<?php echo WORLDPAY_INST_ID ?>" />\n                                            <input type="hidden" name="cartId" value="<?php echo $db->f("order_id") ?>" />\n                                               <input type="hidden" name="amount" value="<?php echo $db->f("order_total") ?>" />\n                                            <input type="hidden" name="currency" value="<?php echo $_SESSION[''vendor_currency''] ?>" />\n                                           <input type="hidden" name="desc" value="Products" />\n                                            <input type="hidden" name="email" value="<?php echo $user->email?>" />\n                                                 <input type="hidden" name="address" value="<?php echo $user->address_1?>&#10<?php echo $user->address_2?>&#10<?php echo\n                                                $user->city?>&#10<?php echo $user->state?>" />\n                                             <input type="hidden" name="name" value="<?php echo $user->title?><?php echo $user->first_name?>. <?php echo $user->middle_name?><?php echo $user->last_name?>" />\n                                           <input type="hidden" name="country" value="<?php echo $user->country?>"/>\n                                              <input type="hidden" name="postcode" value="<?php echo $user->zip?>" />\n                                                <input type="hidden" name="tel"  value="<?php echo $user->phone_1?>">\n                                                  <input type="hidden" name="withDelivery"  value="true">\n                                                 <br />\n                                                <input type="submit" value ="PROCEED TO PAYMENT PAGE" />\n                                                  </form>', ''),
(7, 1, '2Checkout', 'ps_twocheckout', 5, 0.00, 0, 0.00, 0.00, 0, '2CO', 'P', 0, 'N', '', '<?php\n      $q  = "SELECT * FROM #__users WHERE user_info_id=''".$db->f("user_info_id")."''"; \n    $dbbt = new ps_DB;\n   $dbbt->setQuery($q);\n        $dbbt->query();\n      $dbbt->next_record(); \n       // Get ship_to information\n    if( $db->f("user_info_id") != $dbbt->f("user_info_id")) {\n         $q2  = "SELECT * FROM #__vm_user_info WHERE user_info_id=''".$db->f("user_info_id")."''"; \n    $dbst = new ps_DB;\n   $dbst->setQuery($q2);\n       $dbst->query();\n      $dbst->next_record();\n      }\n     else  {\n         $dbst = $dbbt;\n    }\n                     \n      //Authnet vars to send\n        $formdata = array (\n   ''x_login'' => TWOCO_LOGIN,\n   ''x_email_merchant'' => ((TWOCO_MERCHANT_EMAIL == ''True'') ? ''TRUE'' : ''FALSE''),\n                  \n      // Customer Name and Billing Address\n  ''x_first_name'' => $dbbt->f("first_name"),\n        ''x_last_name'' => $dbbt->f("last_name"),\n  ''x_company'' => $dbbt->f("company"),\n      ''x_address'' => $dbbt->f("address_1"),\n    ''x_city'' => $dbbt->f("city"),\n    ''x_state'' => $dbbt->f("state"),\n  ''x_zip'' => $dbbt->f("zip"),\n      ''x_country'' => $dbbt->f("country"),\n      ''x_phone'' => $dbbt->f("phone_1"),\n        ''x_fax'' => $dbbt->f("fax"),\n      ''x_email'' => $dbbt->f("email"),\n \n       // Customer Shipping Address\n  ''x_ship_to_first_name'' => $dbst->f("first_name"),\n        ''x_ship_to_last_name'' => $dbst->f("last_name"),\n  ''x_ship_to_company'' => $dbst->f("company"),\n      ''x_ship_to_address'' => $dbst->f("address_1"),\n    ''x_ship_to_city'' => $dbst->f("city"),\n    ''x_ship_to_state'' => $dbst->f("state"),\n  ''x_ship_to_zip'' => $dbst->f("zip"),\n      ''x_ship_to_country'' => $dbst->f("country"),\n     \n       ''x_invoice_num'' => $db->f("order_number"),\n       ''x_receipt_link_url'' => SECUREURL."2checkout_notify.php"\n  );\n    \n     if( TWOCO_TESTMODE == "Y" )\n   $formdata[''demo''] = "Y";\n       \n       $version = "2";\n    $url = "https://www2.2checkout.com/2co/buyer/purchase";\n    $formdata[''x_amount''] = number_format($db->f("order_total"), 2, ''.'', '''');\n \n       //build the post string\n       $poststring = '''';\n  foreach($formdata AS $key => $val){\n          $poststring .= "<input type=''hidden'' name=''$key'' value=''$val'' />\n ";\n    }\n    \n      ?>\n    <form action="<?php echo $url ?>" method="post" target="_blank">\n       <?php echo $poststring ?>\n    <p>Click on the Image below to pay...</p>\n     <input type="image" name="submit" src="https://www.2checkout.com/images/buy_logo.gif" border="0" alt="Make payments with 2Checkout, it''s fast and secure!" title="Pay your Order with 2Checkout, it''s fast and secure!" />\n      </form>', ''),
(8, 1, 'NoChex', 'ps_nochex', 5, 0.00, 0, 0.00, 0.00, 0, 'NOCHEX', 'P', 0, 'N', '', '<form action="https://www.nochex.com/nochex.dll/checkout" method="post" target="_blank"> \n                                                                                     <input type="hidden" name="email" value="<?php echo NOCHEX_EMAIL ?>" />\n                                                                                 <input type="hidden" name="amount" value="<?php printf("%.2f", $db->f("order_total"))?>" />\n                                                                                        <input type="hidden" name="ordernumber" value="<?php $db->p("order_id") ?>" />\n                                                                                       <input type="hidden" name="logo" value="<?php echo $vendor_image_url ?>" />\n                                                                                    <input type="hidden" name="returnurl" value="<?php echo SECUREURL ."index.php?option=com_virtuemart&amp;page=checkout.result&amp;order_id=".$db->f("order_id") ?>" />\n                                                                                      <input type="image" name="submit" src="http://www.nochex.com/web/images/paymeanimated.gif"> \n                                                                                    </form>', ''),
(9, 1, 'Credit Card (PayMeNow)', 'ps_paymenow', 5, 0.00, 0, 0.00, 0.00, 0, 'PN', 'Y', 0, 'N', '1,2,3,', '', ''),
(10, 1, 'eWay', 'ps_eway', 5, 0.00, 0, 0.00, 0.00, 0, 'EWAY', 'Y', 0, 'N', '', '', ''),
(11, 1, 'eCheck.net', 'ps_echeck', 5, 0.00, 0, 0.00, 0.00, 0, 'ECK', 'B', 0, 'N', '', '', ''),
(12, 1, 'Credit Card (eProcessingNetwork)', 'ps_epn', 5, 0.00, 0, 0.00, 0.00, 0, 'EPN', 'Y', 0, 'Y', '1,2,3,', '', ''),
(13, 1, 'iKobo', '', 5, 0.00, 0, 0.00, 0.00, 0, 'IK', 'P', 0, 'N', '', '<form action="https://www.iKobo.com/store/index.php" method="post"> \n  <input type="hidden" name="cmd" value="cart" />Click on the image below to Pay with iKobo\n  <input type="image" src="https://www.ikobo.com/merchant/buttons/ikobo_pay1.gif" name="submit" alt="Pay with iKobo" /> \n  <input type="hidden" name="poid" value="USER_ID" /> \n  <input type="hidden" name="item" value="Order: <?php $db->p("order_id") ?>" /> \n  <input type="hidden" name="price" value="<?php printf("%.2f", $db->f("order_total"))?>" /> \n  <input type="hidden" name="firstname" value="<?php echo $user->first_name?>" /> \n  <input type="hidden" name="lastname" value="<?php echo $user->last_name?>" /> \n  <input type="hidden" name="address" value="<?php echo $user->address_1?>&#10<?php echo $user->address_2?>" /> \n  <input type="hidden" name="city" value="<?php echo $user->city?>" /> \n  <input type="hidden" name="state" value="<?php echo $user->state?>" /> \n  <input type="hidden" name="zip" value="<?php echo $user->zip?>" /> \n  <input type="hidden" name="phone" value="<?php echo $user->phone_1?>" /> \n  <input type="hidden" name="email" value="<?php echo $user->email?>" /> \n  </form> >', ''),
(14, 1, 'iTransact', '', 5, 0.00, 0, 0.00, 0.00, 0, 'ITR', 'P', 0, 'N', '', '<?php\n  //your iTransact account details\n  $vendorID = "XXXXX";\n  global $vendor_name;\n  $mername = $vendor_name;\n  \n  //order details\n  $total = $db->f("order_total");$first_name = $user->first_name;$last_name = $user->last_name;$address = $user->address_1;$city = $user->city;$state = $user->state;$zip = $user->zip;$country = $user->country;$email = $user->email;$phone = $user->phone_1;$home_page = $mosConfig_live_site."/index.php";$ret_addr = $mosConfig_live_site."/index.php";$cc_payment_image = $mosConfig_live_site."/components/com_virtuemart/shop_image/ps_image/cc_payment.jpg";\n  ?>\n  <form action="https://secure.paymentclearing.com/cgi-bin/mas/split.cgi" method="POST"> \n                <input type="hidden" name="vendor_id" value="<?php echo $vendorID; ?>" />\n              <input type="hidden" name="home_page" value="<?php echo $home_page; ?>" />\n             <input type="hidden" name="ret_addr" value="<?php echo $ret_addr; ?>" />\n               <input type="hidden" name="mername" value="<?php echo $mername; ?>" />\n         <!--Enter text in the next value that should appear on the bottom of the order form.-->\n               <INPUT type="hidden" name="mertext" value="" />\n         <!--If you are accepting checks, enter the number 1 in the next value.  Enter the number 0 if you are not accepting checks.-->\n                <INPUT type="hidden" name="acceptchecks" value="0" />\n           <!--Enter the number 1 in the next value if you want to allow pre-registered customers to pay with a check.  Enter the number 0 if not.-->\n            <INPUT type="hidden" name="allowreg" value="0" />\n               <!--If you are set up with Check Guarantee, enter the number 1 in the next value.  Enter the number 0 if not.-->\n              <INPUT type="hidden" name="checkguar" value="0" />\n              <!--Enter the number 1 in the next value if you are accepting credit card payments.  Enter the number zero if not.-->\n         <INPUT type="hidden" name="acceptcards" value="1">\n              <!--Enter the number 1 in the next value if you want to allow a separate mailing address for credit card orders.  Enter the number 0 if not.-->\n               <INPUT type="hidden" name="altaddr" value="0" />\n                <!--Enter the number 1 in the next value if you want the customer to enter the CVV number for card orders.  Enter the number 0 if not.-->\n             <INPUT type="hidden" name="showcvv" value="1" />\n                \n              <input type="hidden" name="1-desc" value="Order Total" />\n               <input type="hidden" name="1-cost" value="<?php echo $total; ?>" />\n            <input type="hidden" name="1-qty" value="1" />\n          <input type="hidden" name="total" value="<?php echo $total; ?>" />\n             <input type="hidden" name="first_name" value="<?php echo $first_name; ?>" />\n           <input type="hidden" name="last_name" value="<?php echo $last_name; ?>" />\n             <input type="hidden" name="address" value="<?php echo $address; ?>" />\n         <input type="hidden" name="city" value="<?php echo $city; ?>" />\n               <input type="hidden" name="state" value="<?php echo $state; ?>" />\n             <input type="hidden" name="zip" value="<?php echo $zip; ?>" />\n         <input type="hidden" name="country" value="<?php echo $country; ?>" />\n         <input type="hidden" name="phone" value="<?php echo $phone; ?>" />\n             <input type="hidden" name="email" value="<?php echo $email; ?>" />\n             <p><input type="image" alt="Process Secure Credit Card Transaction using iTransact" border="0" height="60" width="210" src="<?php echo $cc_payment_image; ?>" /> </p>\n            </form>', ''),
(15, 1, 'Verisign PayFlow Pro', 'payflow_pro', 5, 0.00, 0, 0.00, 0.00, 0, 'PFP', 'Y', 0, 'N', '1,2,6,7,', '', ''),
(16, 1, 'Dankort/PBS via ePay', 'ps_epay', 5, 0.00, 0, 0.00, 0.00, 0, 'EPAY', 'P', 0, 'N', '', '<?php\r\nrequire_once(CLASSPATH ."payment/ps_epay.cfg.php");\r\n$url=basename($mosConfig_live_site);\r\nfunction get_iso_code($code) {\r\nswitch ($code) {\r\ncase "ADP": return "020"; break;\r\ncase "AED": return "784"; break;\r\ncase "AFA": return "004"; break;\r\ncase "ALL": return "008"; break;\r\ncase "AMD": return "051"; break;\r\ncase "ANG": return "532"; break;\r\ncase "AOA": return "973"; break;\r\ncase "ARS": return "032"; break;\r\ncase "AUD": return "036"; break;\r\ncase "AWG": return "533"; break;\r\ncase "AZM": return "031"; break;\r\ncase "BAM": return "977"; break;\r\ncase "BBD": return "052"; break;\r\ncase "BDT": return "050"; break;\r\ncase "BGL": return "100"; break;\r\ncase "BGN": return "975"; break;\r\ncase "BHD": return "048"; break;\r\ncase "BIF": return "108"; break;\r\ncase "BMD": return "060"; break;\r\ncase "BND": return "096"; break;\r\ncase "BOB": return "068"; break;\r\ncase "BOV": return "984"; break;\r\ncase "BRL": return "986"; break;\r\ncase "BSD": return "044"; break;\r\ncase "BTN": return "064"; break;\r\ncase "BWP": return "072"; break;\r\ncase "BYR": return "974"; break;\r\ncase "BZD": return "084"; break;\r\ncase "CAD": return "124"; break;\r\ncase "CDF": return "976"; break;\r\ncase "CHF": return "756"; break;\r\ncase "CLF": return "990"; break;\r\ncase "CLP": return "152"; break;\r\ncase "CNY": return "156"; break;\r\ncase "COP": return "170"; break;\r\ncase "CRC": return "188"; break;\r\ncase "CUP": return "192"; break;\r\ncase "CVE": return "132"; break;\r\ncase "CYP": return "196"; break;\r\ncase "CZK": return "203"; break;\r\ncase "DJF": return "262"; break;\r\ncase "DKK": return "208"; break;\r\ncase "DOP": return "214"; break;\r\ncase "DZD": return "012"; break;\r\ncase "ECS": return "218"; break;\r\ncase "ECV": return "983"; break;\r\ncase "EEK": return "233"; break;\r\ncase "EGP": return "818"; break;\r\ncase "ERN": return "232"; break;\r\ncase "ETB": return "230"; break;\r\ncase "EUR": return "978"; break;\r\ncase "FJD": return "242"; break;\r\ncase "FKP": return "238"; break;\r\ncase "GBP": return "826"; break;\r\ncase "GEL": return "981"; break;\r\ncase "GHC": return "288"; break;\r\ncase "GIP": return "292"; break;\r\ncase "GMD": return "270"; break;\r\ncase "GNF": return "324"; break;\r\ncase "GTQ": return "320"; break;\r\ncase "GWP": return "624"; break;\r\ncase "GYD": return "328"; break;\r\ncase "HKD": return "344"; break;\r\ncase "HNL": return "340"; break;\r\ncase "HRK": return "191"; break;\r\ncase "HTG": return "332"; break;\r\ncase "HUF": return "348"; break;\r\ncase "IDR": return "360"; break;\r\ncase "ILS": return "376"; break;\r\ncase "INR": return "356"; break;\r\ncase "IQD": return "368"; break;\r\ncase "IRR": return "364"; break;\r\ncase "ISK": return "352"; break;\r\ncase "JMD": return "388"; break;\r\ncase "JOD": return "400"; break;\r\ncase "JPY": return "392"; break;\r\ncase "KES": return "404"; break;\r\ncase "KGS": return "417"; break;\r\ncase "KHR": return "116"; break;\r\ncase "KMF": return "174"; break;\r\ncase "KPW": return "408"; break;\r\ncase "KRW": return "410"; break;\r\ncase "KWD": return "414"; break;\r\ncase "KYD": return "136"; break;\r\ncase "KZT": return "398"; break;\r\ncase "LAK": return "418"; break;\r\ncase "LBP": return "422"; break;\r\ncase "LKR": return "144"; break;\r\ncase "LRD": return "430"; break;\r\ncase "LSL": return "426"; break;\r\ncase "LTL": return "440"; break;\r\ncase "LVL": return "428"; break;\r\ncase "LYD": return "434"; break;\r\ncase "MAD": return "504"; break;\r\ncase "MDL": return "498"; break;\r\ncase "MGF": return "450"; break;\r\ncase "MKD": return "807"; break;\r\ncase "MMK": return "104"; break;\r\ncase "MNT": return "496"; break;\r\ncase "MOP": return "446"; break;\r\ncase "MRO": return "478"; break;\r\ncase "MTL": return "470"; break;\r\ncase "MUR": return "480"; break;\r\ncase "MVR": return "462"; break;\r\ncase "MWK": return "454"; break;\r\ncase "MXN": return "484"; break;\r\ncase "MXV": return "979"; break;\r\ncase "MYR": return "458"; break;\r\ncase "MZM": return "508"; break;\r\ncase "NAD": return "516"; break;\r\ncase "NGN": return "566"; break;\r\ncase "NIO": return "558"; break;\r\ncase "NOK": return "578"; break;\r\ncase "NPR": return "524"; break;\r\ncase "NZD": return "554"; break;\r\ncase "OMR": return "512"; break;\r\ncase "PAB": return "590"; break;\r\ncase "PEN": return "604"; break;\r\ncase "PGK": return "598"; break;\r\ncase "PHP": return "608"; break;\r\ncase "PKR": return "586"; break;\r\ncase "PLN": return "985"; break;\r\ncase "PYG": return "600"; break;\r\ncase "QAR": return "634"; break;\r\ncase "ROL": return "642"; break;\r\ncase "RUB": return "643"; break;\r\ncase "RUR": return "810"; break;\r\ncase "RWF": return "646"; break;\r\ncase "SAR": return "682"; break;\r\ncase "SBD": return "090"; break;\r\ncase "SCR": return "690"; break;\r\ncase "SDD": return "736"; break;\r\ncase "SEK": return "752"; break;\r\ncase "SGD": return "702"; break;\r\ncase "SHP": return "654"; break;\r\ncase "SIT": return "705"; break;\r\ncase "SKK": return "703"; break;\r\ncase "SLL": return "694"; break;\r\ncase "SOS": return "706"; break;\r\ncase "SRG": return "740"; break;\r\ncase "STD": return "678"; break;\r\ncase "SVC": return "222"; break;\r\ncase "SYP": return "760"; break;\r\ncase "SZL": return "748"; break;\r\ncase "THB": return "764"; break;\r\ncase "TJS": return "972"; break;\r\ncase "TMM": return "795"; break;\r\ncase "TND": return "788"; break;\r\ncase "TOP": return "776"; break;\r\ncase "TPE": return "626"; break;\r\ncase "TRL": return "792"; break;\r\ncase "TRY": return "949"; break;\r\ncase "TTD": return "780"; break;\r\ncase "TWD": return "901"; break;\r\ncase "TZS": return "834"; break;\r\ncase "UAH": return "980"; break;\r\ncase "UGX": return "800"; break;\r\ncase "USD": return "840"; break;\r\ncase "UYU": return "858"; break;\r\ncase "UZS": return "860"; break;\r\ncase "VEB": return "862"; break;\r\ncase "VND": return "704"; break;\r\ncase "VUV": return "548"; break;\r\ncase "XAF": return "950"; break;\r\ncase "XCD": return "951"; break;\r\ncase "XOF": return "952"; break;\r\ncase "XPF": return "953"; break;\r\ncase "YER": return "886"; break;\r\ncase "YUM": return "891"; break;\r\ncase "ZAR": return "710"; break;\r\ncase "ZMK": return "894"; break;\r\ncase "ZWD": return "716"; break;\r\n}\r\nreturn "XXX"; // return invalid code if the currency is not found \r\n}\r\n\r\nfunction calculateePayCurrency($order_id)\r\n{\r\n$db = new ps_DB;\r\n$currency_code = "208";\r\n$q = "SELECT order_currency FROM #__vm_orders where order_id = " . $order_id;\r\n$db->query($q);\r\nif ($db->next_record()) {\r\n	$currency_code = get_iso_code($db->f("order_currency"));\r\n}\r\nreturn $currency_code;\r\n}\r\n echo $VM_LANG->_(''VM_CHECKOUT_EPAY_PAYMENT_CHECKOUT_HEADER'');\r\n?>\r\n<script type="text/javascript" src="http://www.epay.dk/js/standardwindow.js"></script>\r\n<script type="text/javascript">\r\nfunction printCard(cardId)\r\n{\r\ndocument.write ("<table border=0 cellspacing=10 cellpadding=10>");\r\nswitch (cardId) {\r\ncase 1: document.write ("<input type=hidden name=cardtype value=1>"); break;\r\ncase 2: document.write ("<input type=hidden name=cardtype value=2>"); break;\r\ncase 3: document.write ("<input type=hidden name=cardtype value=3>"); break;\r\ncase 4: document.write ("<input type=hidden name=cardtype value=4>"); break;\r\ncase 5: document.write ("<input type=hidden name=cardtype value=5>"); break;\r\ncase 6: document.write ("<input type=hidden name=cardtype value=6>"); break;\r\ncase 7: document.write ("<input type=hidden name=cardtype value=7>"); break;\r\ncase 8: document.write ("<input type=hidden name=cardtype value=8>"); break;\r\ncase 9: document.write ("<input type=hidden name=cardtype value=9>"); break;\r\ncase 10: document.write ("<input type=hidden name=cardtype value=10>"); break;\r\ncase 12: document.write ("<input type=hidden name=cardtype value=12>"); break;\r\ncase 13: document.write ("<input type=hidden name=cardtype value=13>"); break;\r\ncase 14: document.write ("<input type=hidden name=cardtype value=14>"); break;\r\ncase 15: document.write ("<input type=hidden name=cardtype value=15>"); break;\r\ncase 16: document.write ("<input type=hidden name=cardtype value=16>"); break;\r\ncase 17: document.write ("<input type=hidden name=cardtype value=17>"); break;\r\ncase 18: document.write ("<input type=hidden name=cardtype value=18>"); break;\r\ncase 19: document.write ("<input type=hidden name=cardtype value=19>"); break;\r\ncase 21: document.write ("<input type=hidden name=cardtype value=21>"); break;\r\ncase 22: document.write ("<input type=hidden name=cardtype value=22>"); break;\r\n}\r\ndocument.write ("</table>");\r\n}\r\n</script>\r\n<form action="https://ssl.ditonlinebetalingssystem.dk/popup/default.asp" method="post" name="ePay" target="ePay_window" id="ePay">\r\n<input type="hidden" name="merchantnumber" value="<?php echo EPAY_MERCHANTNUMBER ?>">\r\n<input type="hidden" name="amount" value="<?php echo round($db->f("order_total")*100, 2 ) ?>">\r\n<input type="hidden" name="currency" value="<?php echo calculateePayCurrency($order_id)?>">\r\n<input type="hidden" name="orderid" value="<?php echo $order_id ?>">\r\n<input type="hidden" name="ordretext" value="">\r\n<?php \r\nif (EPAY_CALLBACK == "1")\r\n{\r\n	echo ''<input type="hidden" name="callbackurl" value="'' . $mosConfig_live_site . ''/index.php?page=checkout.epay_result&accept=1&sessionid='' . $sessionid . ''&option=com_virtuemart&Itemid=1">'';\r\n}\r\n?>\r\n<input type="hidden" name="accepturl" value="<?php echo $mosConfig_live_site ?>/index.php?page=checkout.epay_result&accept=1&sessionid=<?php echo $sessionid ?>&option=com_virtuemart&Itemid=1">\r\n<input type="hidden" name="declineurl" value="<?php echo $mosConfig_live_site ?>/index.php?page=checkout.epay_result&accept=0&sessionid=<?php echo $sessionid ?>&option=com_virtuemart&Itemid=1">\r\n<input type="hidden" name="group" value="<?php echo EPAY_GROUP ?>">\r\n<input type="hidden" name="instant" value="<?php echo EPAY_INSTANT_CAPTURE ?>">\r\n<input type="hidden" name="language" value="<?php echo EPAY_LANGUAGE ?>">\r\n<input type="hidden" name="authsms" value="<?php echo EPAY_AUTH_SMS ?>">\r\n<input type="hidden" name="authmail" value="<?php echo EPAY_AUTH_MAIL . (strlen(EPAY_AUTH_MAIL) > 0 && EPAY_AUTHEMAILCUSTOMER == 1 ? ";" : "") . (EPAY_AUTHEMAILCUSTOMER == 1 ? $user->user_email : ""); ?>">\r\n<input type="hidden" name="windowstate" value="<?php echo EPAY_WINDOW_STATE ?>">\r\n<input type="hidden" name="use3D" value="<?php echo EPAY_3DSECURE ?>">\r\n<input type="hidden" name="addfee" value="<?php echo EPAY_ADDFEE ?>">\r\n<input type="hidden" name="subscription" value="<?php echo EPAY_SUBSCRIPTION ?>">\r\n<input type="hidden" name="MD5Key" value="<?php if (EPAY_MD5_TYPE == 2) echo md5( calculateePayCurrency($order_id) . round($db->f("order_total")*100, 2 ) . $order_id  . EPAY_MD5_KEY)?>">\r\n<?php\r\nif (EPAY_CARDTYPES_1 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(1)</script>";\r\nif (EPAY_CARDTYPES_2 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(2)</script>";\r\nif (EPAY_CARDTYPES_3 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(3)</script>";\r\nif (EPAY_CARDTYPES_4 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(4)</script>";\r\nif (EPAY_CARDTYPES_5 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(5)</script>";\r\nif (EPAY_CARDTYPES_6 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(6)</script>";\r\nif (EPAY_CARDTYPES_7 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(7)</script>";\r\nif (EPAY_CARDTYPES_8 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(8)</script>";\r\nif (EPAY_CARDTYPES_9 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(9)</script>";\r\nif (EPAY_CARDTYPES_10 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(10)</script>";\r\nif (EPAY_CARDTYPES_11 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(11)</script>";\r\nif (EPAY_CARDTYPES_12 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(12)</script>";\r\nif (EPAY_CARDTYPES_14 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(14)</script>";\r\nif (EPAY_CARDTYPES_15 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(15)</script>";\r\nif (EPAY_CARDTYPES_16 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(16)</script>";\r\nif (EPAY_CARDTYPES_17 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(17)</script>";\r\nif (EPAY_CARDTYPES_18 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(18)</script>";\r\nif (EPAY_CARDTYPES_19 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(19)</script>";\r\nif (EPAY_CARDTYPES_21 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(21)</script>";\r\nif (EPAY_CARDTYPES_22 == "1" && EPAY_CARDTYPES_0 != "1") echo "<script>printCard(22)</script>";;\r\n?>\r\n</form>\r\n<script>open_ePay_window();</script>\r\n<br>\r\n<table border="0" width="100%"><tr><td><input type="button" onClick="open_ePay_window()" value="<?php echo $VM_LANG->_(''VM_CHECKOUT_EPAY_BUTTON_OPEN_WINDOW'') ?>"></td><td width="100%" id="flashLoader"></td></tr></table><br><br><br>\r\n<?php echo $VM_LANG->_(''VM_CHECKOUT_EPAY_PAYMENT_CHECKOUT_FOOTER'') ?>\r\n<br><br>\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/epay_logo.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/mastercard_securecode.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/pci.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/verisign_secure.gif" border="0">&nbsp;&nbsp;&nbsp;\r\n<img src="components/com_virtuemart/shop_image/ps_image/epay_images/visa_secure.gif" border="0">&nbsp;&nbsp;&nbsp;;', ''),
(17, 1, 'PaySbuy', 'ps_paysbuy', 5, 0.00, 0, 0.00, 0.00, 0, 'PSB', 'P', 0, 'N', '', '', ''),
(18, 1, 'PayPal (Legacy)', 'ps_paypal', 5, 0.00, 0, 0.00, 0.00, 0, 'PP', 'P', 0, 'Y', '', '<?php\r\n$db1 = new ps_DB();\r\n$q = "SELECT country_2_code FROM #__vm_country WHERE country_3_code=''".$user->country."'' ORDER BY country_2_code ASC";\r\n$db1->query($q);\r\n\r\n$url = "https://www.paypal.com/cgi-bin/webscr";\r\n$tax_total = $db->f("order_tax") + $db->f("order_shipping_tax");\r\n$discount_total = $db->f("coupon_discount") + $db->f("order_discount");\r\n$post_variables = Array(\r\n"cmd" => "_ext-enter",\r\n"redirect_cmd" => "_xclick",\r\n"upload" => "1",\r\n"business" => PAYPAL_EMAIL,\r\n"receiver_email" => PAYPAL_EMAIL,\r\n"item_name" => $VM_LANG->_(''PHPSHOP_ORDER_PRINT_PO_NUMBER'').": ". $db->f("order_id"),\r\n"order_id" => $db->f("order_id"),\r\n"invoice" => $db->f("order_number"),\r\n"amount" => round( $db->f("order_total")-$db->f("order_shipping"), 2),\r\n"shipping" => sprintf("%.2f", $db->f("order_shipping")),\r\n"currency_code" => $_SESSION[''vendor_currency''],\r\n\r\n"address_override" => "1",\r\n"first_name" => $dbbt->f(''first_name''),\r\n"last_name" => $dbbt->f(''last_name''),\r\n"address1" => $dbbt->f(''address_1''),\r\n"address2" => $dbbt->f(''address_2''),\r\n"zip" => $dbbt->f(''zip''),\r\n"city" => $dbbt->f(''city''),\r\n"state" => $dbbt->f(''state''),\r\n"country" => $db1->f(''country_2_code''),\r\n"email" => $dbbt->f(''user_email''),\r\n"night_phone_b" => $dbbt->f(''phone_1''),\r\n"cpp_header_image" => $vendor_image_url,\r\n\r\n"return" => SECUREURL ."index.php?option=com_virtuemart&page=checkout.result&order_id=".$db->f("order_id"),\r\n"notify_url" => SECUREURL ."administrator/components/com_virtuemart/notify.php",\r\n"cancel_return" => SECUREURL ."index.php",\r\n"undefined_quantity" => "0",\r\n\r\n"test_ipn" => PAYPAL_DEBUG,\r\n"pal" => "NRUBJXESJTY24",\r\n"no_shipping" => "1",\r\n"no_note" => "1"\r\n);\r\nif( $page == "checkout.thankyou" ) {\r\n$query_string = "?";\r\nforeach( $post_variables as $name => $value ) {\r\n$query_string .= $name. "=" . urlencode($value) ."&";\r\n}\r\nvmRedirect( $url . $query_string );\r\n} else {\r\necho ''<form action="''.$url.''" method="post" target="_blank">'';\r\necho ''<input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/x-click-but6.gif" alt="Click to pay with PayPal - it is fast, free and secure!" />'';\r\n\r\nforeach( $post_variables as $name => $value ) {\r\necho ''<input type="hidden" name="''.$name.''" value="''.htmlspecialchars($value).''" />'';\r\n}\r\necho ''</form>'';\r\n\r\n}\r\n?>', ''),
(19, 1, 'MerchantWarrior', 'ps_merchantwarrior', 5, 0.00, 0, 0.00, 0.00, 1, 'MW', 'Y', 1, 'N', '1,2,3,5,7,', '', ''),
(20, 1, 'Данное оборудование Вы можете приобрести по адресу ул. Хабаровская, д. 25 г.Хабаровск Хабаровский Край', 'ps_payment', 5, 0.00, 0, 0.00, 0.00, 1, '011', '', 0, 'Y', '', 'Данное оборудование Вы можете приобрести по адресу ул. Хабаровская, д. 25 г.Хабаровск Хабаровский Край', '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 05 2012 г., 08:20
--

CREATE TABLE IF NOT EXISTS `jos_vm_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `product_parent_id` int(11) NOT NULL DEFAULT '0',
  `product_sku` varchar(64) NOT NULL DEFAULT '',
  `product_s_desc` varchar(255) DEFAULT NULL,
  `product_desc` text,
  `product_thumb_image` varchar(255) DEFAULT NULL,
  `product_full_image` varchar(255) DEFAULT NULL,
  `product_publish` char(1) DEFAULT NULL,
  `product_weight` decimal(10,4) DEFAULT NULL,
  `product_weight_uom` varchar(32) DEFAULT 'pounds.',
  `product_length` decimal(10,4) DEFAULT NULL,
  `product_width` decimal(10,4) DEFAULT NULL,
  `product_height` decimal(10,4) DEFAULT NULL,
  `product_lwh_uom` varchar(32) DEFAULT 'inches',
  `product_url` varchar(255) DEFAULT NULL,
  `product_in_stock` int(11) NOT NULL DEFAULT '0',
  `product_available_date` int(11) DEFAULT NULL,
  `product_availability` varchar(56) NOT NULL DEFAULT '',
  `product_special` char(1) DEFAULT NULL,
  `product_discount_id` int(11) DEFAULT NULL,
  `ship_code_id` int(11) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `product_name` varchar(64) DEFAULT NULL,
  `product_sales` int(11) NOT NULL DEFAULT '0',
  `attribute` text,
  `custom_attribute` text NOT NULL,
  `product_tax_id` int(11) DEFAULT NULL,
  `product_unit` varchar(32) DEFAULT NULL,
  `product_packaging` int(11) DEFAULT NULL,
  `child_options` varchar(45) DEFAULT NULL,
  `quantity_options` varchar(45) DEFAULT NULL,
  `child_option_ids` varchar(45) DEFAULT NULL,
  `product_order_levels` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `idx_product_vendor_id` (`vendor_id`),
  KEY `idx_product_product_parent_id` (`product_parent_id`),
  KEY `idx_product_sku` (`product_sku`),
  KEY `idx_product_ship_code_id` (`ship_code_id`),
  KEY `idx_product_name` (`product_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='All products are stored here.' AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `jos_vm_product`
--

INSERT INTO `jos_vm_product` (`product_id`, `vendor_id`, `product_parent_id`, `product_sku`, `product_s_desc`, `product_desc`, `product_thumb_image`, `product_full_image`, `product_publish`, `product_weight`, `product_weight_uom`, `product_length`, `product_width`, `product_height`, `product_lwh_uom`, `product_url`, `product_in_stock`, `product_available_date`, `product_availability`, `product_special`, `product_discount_id`, `ship_code_id`, `cdate`, `mdate`, `product_name`, `product_sales`, `attribute`, `custom_attribute`, `product_tax_id`, `product_unit`, `product_packaging`, `child_options`, `quantity_options`, `child_option_ids`, `product_order_levels`) VALUES
(18, 1, 0, 'IAD226', 'Поликлинический аудиометр', '<div style="font-size: 14px;">\r\n<p>Технические характеристики:</p>\r\n<p> </p>\r\n<table border="0" cellspacing="0'' cellpadding=" width="500">\r\n<tbody>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Маскировка </strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>Узкополосная / Широкополосная</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>Максимальная интенсивность Воздух / Кость </strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>120 дБ / 80 дБ</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Частотный диапазон</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>125 Гц – 8 кГц</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>Трель / Импульс</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>Да / Да</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Шаг аттенюатора</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>5 дБ</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>Печать</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>PC/Laser/Ink-Jet/MTP10/MS25/MS40</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Калибровка свободного звукового поля</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>-</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>TDH39 / Внутриушной микрофон Ear Tone 3A</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>Да / Опция</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Тест авт. Определения порога</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>Да / Bekesy</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>Специальные тесты</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>SIS/ABLB/Тон в шуме/Тест Stenger</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Вес</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>2,1 кг</strong></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p> </p>\r\n<p><span class="system-pagebreak">Поликлинический аудиометр AD 226 предназначен для проведения обследований по воздушной и костной проводимости </span></p>\r\n<p><span class="system-pagebreak">Характеристики: </span></p>\r\n<p><span class="system-pagebreak">•    Маскировка узкополосным либо белым шумом </span></p>\r\n<p><span class="system-pagebreak">•    Все данные медицинского обследования сохраняются в памяти </span></p>\r\n<p><span class="system-pagebreak">•    11 тестовых частот от 125 до 8 000 Гц, уровни прослушивания от –10 до 120 дБ </span></p>\r\n<p><span class="system-pagebreak">•    Имеет встроенный микрофон, кнопку ответа пациента, встроенный тест автоматического определения порогов слуха </span></p>\r\n<p><span class="system-pagebreak">•    Тесты ABLB, Stenger и тест Langenbeck (предъявление тона в шуме), тест Бекеши </span></p>\r\n<p><span class="system-pagebreak">В комплект входят: </span></p>\r\n<p><span class="system-pagebreak">•    TDH 39 головные телефоны </span></p>\r\n<p><span class="system-pagebreak">•    А20 телефон костной проводимости </span></p>\r\n<p><span class="system-pagebreak">•    АРS2 кнопка ответа пациента </span></p>\r\n<p><span class="system-pagebreak">•    Бланки аудиограмм – 200 шт. </span></p>\r\n<p><span class="system-pagebreak">•    Интерфейс RS232C для подключения к компьютеру Дополнительные принадлежности: </span></p>\r\n<p><span class="system-pagebreak">(заказываются отдельно) </span></p>\r\n<p><span class="system-pagebreak">•    EAR-Tone 3A аудиометрические внутри-ушные телефоны </span></p>\r\n<p><span class="system-pagebreak">•    ACC26 футляр для переноски </span></p>\r\n<p><span class="system-pagebreak">•    21925 шумозащитные амбушюры </span></p>\r\n<p><span class="system-pagebreak">•    50250 шумозащитные амбушюры Пелтора </span></p>\r\n<p><span class="system-pagebreak">•    программное обеспечение – база данных, инсталляция в NOAH и CONNEX </span></p>\r\n</div>', 'resized/Interacoustics_A_4fcdbf5f734e3_90x90.png', 'Interacoustics_A_4fcdbf5f7a647.png', 'Y', 0.0000, 'кг.', 0.0000, 0.0000, 0.0000, 'см.', '', 0, 1338681600, '', 'N', 0, NULL, 1338736688, 1338883935, 'Interacoustics AD 226', 0, '', '', 0, 'шт.', 0, 'N,N,N,N,N,N,20%,10%,', 'none,0,0,1', '', '0,0'),
(21, 1, 0, 'IAT1', 'Cовременный прибор для регистрации биоэлектрических потенциалов сердца при диагностике состояния сердечно-сосудистой системы человека.', '<div style="font-size: 14px;">\r\n<p align="center"><span> Электрокардиограф CARDIOVIT AT-1 современный прибор для регистрации биоэлектрических потенциалов сердца при диагностике состояния сердечно-сосудистой системы человека.</span></p>\r\n<br />\r\n<p><strong> <span> Характеристики для транспортировки</span></strong></p>\r\n<br /> \r\n<table border="0" cellspacing="0'' cellpadding=" width="500">\r\n<tbody>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong> <span>Габариты в упаковке </span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>0,28х0,37х0,38 </span></strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong> <span>Вес, кг </span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>5</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong><span>Объем, м.куб.</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong><span>0.0394 </span> </strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong> <span>Страна </span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>Швейцария </span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong> <span>Производитель </span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span> <a href="http://www.medcomp.ru/manufacturer/168/"><span style="color:blue"> SCHILLER AG</span></a></span></strong></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<br />\r\n<p><span>Возможно          хранение в памяти данных ЭКГ (12 сек.), с последующей печатью на термореактивной          бумаге шириной 90 мм. В автоматическом режиме одновременно регистрируются 12          стандартных кардиографических отведений через 3 канала оптимизированных для          ширины 80 мм. Изолинии настраиваются автоматически.Питание прибора          осуществляется от сети переменного тока или от встроенной аккумуляторной          батареи. Встроенная батарея аккумуляторов обеспечивает 2 часа работы с функцией          печати. Время зарядки батарей - 15 часов при полностью разряженном аккумуляторе          (3 часа до 60% заряда). Кабель отведений обеспечивает защиту от импульса          дефибриллятора. Звуковая сигнализация сердечного ритма и аварийных ситуаций,          наличие индикаторов контроля состояния аккумуляторной батареи и перегрузки          усилителя биопотенциалов создают дополнительные удобства при работе с прибором.          Наличие индикации показывает: питание от сети или батарей; низкий заряд батарей;          отсутствие бумаги или неправильная заправка; состояние фильтра (вкл/выкл);          нарушение контакта с электродом. Наличие миографического программируемого и          линейного цифрового частотного фильтра обеспечивает высокое качество регистрации          электрокардиограмм. </span></p>\r\n<br />\r\n<p><strong> <span>В комплект          поставки входят: </span></strong></p>\r\n<ul type="disc">\r\n<li> <span>12-проводной кабель пациента; </span> </li>\r\n<li> <span>4 электрода для конечностей, </span> </li>\r\n<li> <span>6 грудных грушевидных электродов, </span> </li>\r\n<li> <span>электродный гель; </span></li>\r\n<li> <span>сетевой кабель; </span></li>\r\n<li> <span>упаковка регистрирующей бумаги; </span></li>\r\n<li> <span>сумка для транспортировки.</span></li>\r\n</ul>\r\n<br />\r\n<p><strong> <span>Технические          характеристики</span></strong></p>\r\n<br /> \r\n<table border="0" cellspacing="0'' cellpadding=" width="500">\r\n<tbody>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong> <span>Диапазон входных напряжений, мВ</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>от 0.03 до 5</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong><span>Чувствительность (ручная и автоматическая установка),      мм/мВ</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>5, 12, 20 (± 5%)</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><span>Скорость подачи, мм/с</span></p>\r\n</td>\r\n<td width="300">\r\n<p><strong style="mso-bidi-font-weight:normal"> <span>5, 25, 50 (Строго)</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>Трель / Импульс</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>Да / Да</strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong> <span>Ток утечки пациента, мкA</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>Менее 5</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong><span>Печать</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong><span>PC/Laser/Ink-Jet/MTP12/MS25/MS40</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong> <span>Питание от сети переменного тока, В / Гц</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>28</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong> <span>Масса электрокардиографа, кг</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>2,9</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong> <span>Габаритные размеры (ДхШхВ), мм</span></strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong> <span>290x212x69</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width="200">\r\n<p><strong>Специальные тесты</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong><span>SIS/ABLB/Тон в шуме/Тест Stenger</span></strong></p>\r\n</td>\r\n</tr>\r\n<tr style="background-color: #cccccc;">\r\n<td width="200">\r\n<p><strong>Вес</strong></p>\r\n</td>\r\n<td width="300">\r\n<p><strong>2,1 кг</strong></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<br />\r\n<p><strong><span style="font-size: 16px;">Цена – 200,00 рублей за штуку.</span></strong></p>\r\n</div>', 'resized/SCHILLER_CARDIOV_4fcdb8bc83321_90x90.png', 'SCHILLER_CARDIOV_4fcdb8bc88889.png', 'Y', 0.0000, 'кг.', 0.0000, 0.0000, 0.0000, 'см.', '', 0, 1338854400, '', 'N', 0, NULL, 1338877675, 1338884434, 'SCHILLER CARDIOVIT AT-1 с сумкой для переноски', 0, '', '', 0, 'шт.', 0, 'N,N,N,N,N,N,20%,10%,', 'none,0,0,1', '', '0,0'),
(22, 1, 0, '12', 'Cовременный прибор для регистрации', '', '', '', 'Y', 0.0000, 'кг.', 0.0000, 0.0000, 0.0000, 'см.', '', 0, 1338854400, '', 'N', 0, NULL, 1338884371, 1338884371, 'Unsigned', 0, '', '', 0, 'шт.', 0, 'N,N,N,N,N,N,20%,10%,', 'none,0,0,1', '', '0,0');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_attribute`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_attribute` (
  `attribute_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `attribute_name` char(255) NOT NULL DEFAULT '',
  `attribute_value` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`attribute_id`),
  KEY `idx_product_attribute_product_id` (`product_id`),
  KEY `idx_product_attribute_name` (`attribute_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Stores attributes + their specific values for Child Products' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_attribute_sku`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_attribute_sku` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `attribute_name` char(255) NOT NULL DEFAULT '',
  `attribute_list` int(11) NOT NULL DEFAULT '0',
  KEY `idx_product_attribute_sku_product_id` (`product_id`),
  KEY `idx_product_attribute_sku_attribute_name` (`attribute_name`),
  KEY `idx_product_attribute_list` (`attribute_list`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Attributes for a Parent Product used by its Child Products';

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_category_xref`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 05 2012 г., 08:19
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_category_xref` (
  `category_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_list` int(11) DEFAULT NULL,
  KEY `idx_product_category_xref_category_id` (`category_id`),
  KEY `idx_product_category_xref_product_id` (`product_id`),
  KEY `idx_product_category_xref_product_list` (`product_list`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

--
-- Дамп данных таблицы `jos_vm_product_category_xref`
--

INSERT INTO `jos_vm_product_category_xref` (`category_id`, `product_id`, `product_list`) VALUES
(8, 22, 1),
(8, 21, 1),
(6, 18, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_discount`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_discount` (
  `discount_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `is_percent` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`discount_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Discounts that can be assigned to products' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_vm_product_discount`
--

INSERT INTO `jos_vm_product_discount` (`discount_id`, `amount`, `is_percent`, `start_date`, `end_date`) VALUES
(1, 20.00, 1, 1097704800, 1194390000),
(2, 2.00, 0, 1098655200, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_download`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_download` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  `download_max` int(11) NOT NULL DEFAULT '0',
  `download_id` varchar(32) NOT NULL DEFAULT '',
  `file_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Active downloads for selling downloadable goods';

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_files`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_files` (
  `file_id` int(19) NOT NULL AUTO_INCREMENT,
  `file_product_id` int(11) NOT NULL DEFAULT '0',
  `file_name` varchar(128) NOT NULL DEFAULT '',
  `file_title` varchar(128) NOT NULL DEFAULT '',
  `file_description` mediumtext NOT NULL,
  `file_extension` varchar(128) NOT NULL DEFAULT '',
  `file_mimetype` varchar(64) NOT NULL DEFAULT '',
  `file_url` varchar(254) NOT NULL DEFAULT '',
  `file_published` tinyint(1) NOT NULL DEFAULT '0',
  `file_is_image` tinyint(1) NOT NULL DEFAULT '0',
  `file_image_height` int(11) NOT NULL DEFAULT '0',
  `file_image_width` int(11) NOT NULL DEFAULT '0',
  `file_image_thumb_height` int(11) NOT NULL DEFAULT '50',
  `file_image_thumb_width` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Additional Images and Files which are assigned to products' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_mf_xref`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 05 2012 г., 08:19
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_mf_xref` (
  `product_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  KEY `idx_product_mf_xref_product_id` (`product_id`),
  KEY `idx_product_mf_xref_manufacturer_id` (`manufacturer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps a product to a manufacturer';

--
-- Дамп данных таблицы `jos_vm_product_mf_xref`
--

INSERT INTO `jos_vm_product_mf_xref` (`product_id`, `manufacturer_id`) VALUES
(21, 1),
(22, 1),
(18, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_price`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 05 2012 г., 08:20
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_price` (
  `product_price_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_price` decimal(12,5) DEFAULT NULL,
  `product_currency` char(16) DEFAULT NULL,
  `product_price_vdate` int(11) DEFAULT NULL,
  `product_price_edate` int(11) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `shopper_group_id` int(11) DEFAULT NULL,
  `price_quantity_start` int(11) unsigned NOT NULL DEFAULT '0',
  `price_quantity_end` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_price_id`),
  KEY `idx_product_price_product_id` (`product_id`),
  KEY `idx_product_price_shopper_group_id` (`shopper_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds price records for a product' AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `jos_vm_product_price`
--

INSERT INTO `jos_vm_product_price` (`product_price_id`, `product_id`, `product_price`, `product_currency`, `product_price_vdate`, `product_price_edate`, `cdate`, `mdate`, `shopper_group_id`, `price_quantity_start`, `price_quantity_end`) VALUES
(21, 21, 58200.00000, 'RUB', 0, 0, 1338877675, 1338884434, 5, 0, 0),
(18, 18, 140000.00000, 'RUB', 0, 0, 1338736688, 1338883935, 5, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_product_type_xref`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_product_type_xref` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_type_id` int(11) NOT NULL DEFAULT '0',
  KEY `idx_product_product_type_xref_product_id` (`product_id`),
  KEY `idx_product_product_type_xref_product_type_id` (`product_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps products to a product type';

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_relations`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_relations` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `related_products` text,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_reviews`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 04 2012 г., 21:43
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `user_rating` tinyint(1) NOT NULL DEFAULT '0',
  `review_ok` int(11) NOT NULL DEFAULT '0',
  `review_votes` int(11) NOT NULL DEFAULT '0',
  `published` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `product_id` (`product_id`,`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `jos_vm_product_reviews`
--

INSERT INTO `jos_vm_product_reviews` (`review_id`, `product_id`, `comment`, `userid`, `time`, `user_rating`, `review_ok`, `review_votes`, `published`) VALUES
(3, 18, '122222222222222222222222222222222222222221212121212121212121212121212121212121212121212121212121221212', 62, 1338816640, 4, 0, 0, 'Y');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_type`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_type` (
  `product_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type_name` varchar(255) NOT NULL DEFAULT '',
  `product_type_description` text,
  `product_type_publish` char(1) DEFAULT NULL,
  `product_type_browsepage` varchar(255) DEFAULT NULL,
  `product_type_flypage` varchar(255) DEFAULT NULL,
  `product_type_list_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_type_parameter`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_type_parameter` (
  `product_type_id` int(11) NOT NULL DEFAULT '0',
  `parameter_name` varchar(255) NOT NULL DEFAULT '',
  `parameter_label` varchar(255) NOT NULL DEFAULT '',
  `parameter_description` text,
  `parameter_list_order` int(11) NOT NULL DEFAULT '0',
  `parameter_type` char(1) NOT NULL DEFAULT 'T',
  `parameter_values` varchar(255) DEFAULT NULL,
  `parameter_multiselect` char(1) DEFAULT NULL,
  `parameter_default` varchar(255) DEFAULT NULL,
  `parameter_unit` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`product_type_id`,`parameter_name`),
  KEY `idx_product_type_parameter_product_type_id` (`product_type_id`),
  KEY `idx_product_type_parameter_parameter_order` (`parameter_list_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Parameters which are part of a product type';

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_product_votes`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 04 2012 г., 21:43
--

CREATE TABLE IF NOT EXISTS `jos_vm_product_votes` (
  `product_id` int(255) NOT NULL DEFAULT '0',
  `votes` text NOT NULL,
  `allvotes` int(11) NOT NULL DEFAULT '0',
  `rating` tinyint(1) NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores all votes for a product';

--
-- Дамп данных таблицы `jos_vm_product_votes`
--

INSERT INTO `jos_vm_product_votes` (`product_id`, `votes`, `allvotes`, `rating`, `lastip`) VALUES
(18, '4', 1, 4, '92.37.190.128');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_shipping_carrier`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_shipping_carrier` (
  `shipping_carrier_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_carrier_name` char(80) NOT NULL DEFAULT '',
  `shipping_carrier_list_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipping_carrier_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Shipping Carriers as used by the Standard Shipping Module' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_vm_shipping_carrier`
--

INSERT INTO `jos_vm_shipping_carrier` (`shipping_carrier_id`, `shipping_carrier_name`, `shipping_carrier_list_order`) VALUES
(1, 'DHL', 0),
(2, 'UPS', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_shipping_label`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_shipping_label` (
  `order_id` int(11) NOT NULL DEFAULT '0',
  `shipper_class` varchar(32) DEFAULT NULL,
  `ship_date` varchar(32) DEFAULT NULL,
  `service_code` varchar(32) DEFAULT NULL,
  `special_service` varchar(32) DEFAULT NULL,
  `package_type` varchar(16) DEFAULT NULL,
  `order_weight` decimal(10,2) DEFAULT NULL,
  `is_international` tinyint(1) DEFAULT NULL,
  `additional_protection_type` varchar(16) DEFAULT NULL,
  `additional_protection_value` decimal(10,2) DEFAULT NULL,
  `duty_value` decimal(10,2) DEFAULT NULL,
  `content_desc` varchar(255) DEFAULT NULL,
  `label_is_generated` tinyint(1) NOT NULL DEFAULT '0',
  `tracking_number` varchar(32) DEFAULT NULL,
  `label_image` blob,
  `have_signature` tinyint(1) NOT NULL DEFAULT '0',
  `signature_image` blob,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores information used in generating shipping labels';

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_shipping_rate`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_shipping_rate` (
  `shipping_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_rate_name` varchar(255) NOT NULL DEFAULT '',
  `shipping_rate_carrier_id` int(11) NOT NULL DEFAULT '0',
  `shipping_rate_country` text NOT NULL,
  `shipping_rate_zip_start` varchar(32) NOT NULL DEFAULT '',
  `shipping_rate_zip_end` varchar(32) NOT NULL DEFAULT '',
  `shipping_rate_weight_start` decimal(10,3) NOT NULL DEFAULT '0.000',
  `shipping_rate_weight_end` decimal(10,3) NOT NULL DEFAULT '0.000',
  `shipping_rate_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_rate_package_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_rate_currency_id` int(11) NOT NULL DEFAULT '0',
  `shipping_rate_vat_id` int(11) NOT NULL DEFAULT '0',
  `shipping_rate_list_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipping_rate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Shipping Rates, used by the Standard Shipping Module' AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `jos_vm_shipping_rate`
--

INSERT INTO `jos_vm_shipping_rate` (`shipping_rate_id`, `shipping_rate_name`, `shipping_rate_carrier_id`, `shipping_rate_country`, `shipping_rate_zip_start`, `shipping_rate_zip_end`, `shipping_rate_weight_start`, `shipping_rate_weight_end`, `shipping_rate_value`, `shipping_rate_package_fee`, `shipping_rate_currency_id`, `shipping_rate_vat_id`, `shipping_rate_list_order`) VALUES
(1, 'Inland > 4kg', 1, 'DEU', '00000', '99999', 0.000, 4.000, 5.62, 2.00, 47, 0, 1),
(2, 'Inland > 8kg', 1, 'DEU', '00000', '99999', 4.000, 8.000, 6.39, 2.00, 47, 0, 2),
(3, 'Inland > 12kg', 1, 'DEU', '00000', '99999', 8.000, 12.000, 7.16, 2.00, 47, 0, 3),
(4, 'Inland > 20kg', 1, 'DEU', '00000', '99999', 12.000, 20.000, 8.69, 2.00, 47, 0, 4),
(5, 'EU+ >  4kg', 1, 'AND;BEL;DNK;FRO;FIN;FRA;GRC;GRL;GBR;IRL;ITA;LIE;LUX;MCO;NLD;AUT;POL;PRT;SMR;SWE;CHE;SVK;ESP;CZE', '00000', '99999', 0.000, 4.000, 14.57, 2.00, 47, 0, 5),
(6, 'EU+ >  8kg', 1, 'AND;BEL;DNK;FRO;FIN;FRA;GRC;GRL;GBR;IRL;ITA;LIE;LUX;MCO;NLD;AUT;POL;PRT;SMR;SWE;CHE;SVK;ESP;CZE', '00000', '99999', 4.000, 8.000, 18.66, 2.00, 47, 0, 6),
(7, 'EU+ > 12kg', 1, 'AND;BEL;DNK;FRO;FIN;FRA;GRC;GRL;GBR;IRL;ITA;LIE;LUX;MCO;NLD;AUT;POL;PRT;SMR;SWE;CHE;SVK;ESP;CZE', '00000', '99999', 8.000, 12.000, 22.57, 2.00, 47, 0, 7),
(8, 'EU+ > 20kg', 1, 'AND;BEL;DNK;FRO;FIN;FRA;GRC;GRL;GBR;IRL;ITA;LIE;LUX;MCO;NLD;AUT;POL;PRT;SMR;SWE;CHE;SVK;ESP;CZE', '00000', '99999', 12.000, 20.000, 30.93, 2.00, 47, 0, 8),
(9, 'Europe > 4kg', 1, 'ALB;ARM;AZE;BLR;BIH;BGR;EST;GEO;GIB;ISL;YUG;KAZ;HRV;LVA;LTU;MLT;MKD;MDA;NOR;ROM;RUS;SVN;TUR;UKR;HUN;BLR;CYP', '00000', '99999', 0.000, 4.000, 23.78, 2.00, 47, 0, 9),
(10, 'Europe >  8kg', 1, 'ALB;ARM;AZE;BLR;BIH;BGR;EST;GEO;GIB;ISL;YUG;KAZ;HRV;LVA;LTU;MLT;MKD;MDA;NOR;ROM;RUS;SVN;TUR;UKR;HUN;BLR;CYP', '00000', '99999', 4.000, 8.000, 29.91, 2.00, 47, 0, 10),
(11, 'Europe > 12kg', 1, 'ALB;ARM;AZE;BLR;BIH;BGR;EST;GEO;GIB;ISL;YUG;KAZ;HRV;LVA;LTU;MLT;MKD;MDA;NOR;ROM;RUS;SVN;TUR;UKR;HUN;BLR;CYP', '00000', '99999', 8.000, 12.000, 36.05, 2.00, 47, 0, 11),
(12, 'Europe > 20kg', 1, 'ALB;ARM;AZE;BLR;BIH;BGR;EST;GEO;GIB;ISL;YUG;KAZ;HRV;LVA;LTU;MLT;MKD;MDA;NOR;ROM;RUS;SVN;TUR;UKR;HUN;BLR;CYP', '00000', '99999', 12.000, 20.000, 48.32, 2.00, 47, 0, 12),
(13, 'World_1 >  4kg', 1, 'EGY;DZA;BHR;IRQ;IRN;ISR;YEM;JOR;CAN;QAT;KWT;LBN;LBY;MAR;OMN;SAU;SYR;TUN;ARE;USA', '00000', '99999', 0.000, 4.000, 26.84, 2.00, 47, 0, 13),
(14, 'World_1 > 8kg', 1, 'EGY;DZA;BHR;IRQ;IRN;ISR;YEM;JOR;CAN;QAT;KWT;LBN;LBY;MAR;OMN;SAU;SYR;TUN;ARE;USA', '00000', '99999', 4.000, 8.000, 35.02, 2.00, 47, 0, 14),
(15, 'World_1 > 12kg', 1, 'EGY;DZA;BHR;IRQ;IRN;ISR;YEM;JOR;CAN;QAT;KWT;LBN;LBY;MAR;OMN;SAU;SYR;TUN;ARE;USA', '00000', '99999', 8.000, 12.000, 43.20, 2.00, 47, 0, 15),
(16, 'World_1 > 20kg', 1, 'EGY;DZA;BHR;IRQ;IRN;ISR;YEM;JOR;CAN;QAT;KWT;LBN;LBY;MAR;OMN;SAU;SYR;TUN;ARE;USA', '00000', '99999', 12.000, 20.000, 59.57, 2.00, 47, 0, 16),
(17, 'World_2 > 4kg', 1, '', '00000', '99999', 0.000, 4.000, 32.98, 2.00, 47, 0, 17),
(18, 'World_2 > 8kg', 1, '', '00000', '99999', 4.000, 8.000, 47.29, 2.00, 47, 0, 18),
(19, 'World_2 > 12kg', 1, '', '00000', '99999', 8.000, 12.000, 61.61, 2.00, 47, 0, 19),
(20, 'World_2 > 20kg', 1, '', '00000', '99999', 12.000, 20.000, 90.24, 2.00, 47, 0, 20),
(21, 'UPS Express', 2, 'AND;BEL;DNK;FRO;FIN;FRA;GRC;GRL;GBR;IRL;ITA;LIE;LUX;MCO;NLD;AUT;POL;PRT;SMR;SWE;CHE;SVK;ESP;CZE', '00000', '99999', 0.000, 20.000, 5.24, 2.00, 47, 0, 21);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_shopper_group`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_shopper_group` (
  `shopper_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `shopper_group_name` varchar(32) DEFAULT NULL,
  `shopper_group_desc` text,
  `shopper_group_discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `show_price_including_tax` tinyint(1) NOT NULL DEFAULT '1',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shopper_group_id`),
  KEY `idx_shopper_group_vendor_id` (`vendor_id`),
  KEY `idx_shopper_group_name` (`shopper_group_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Shopper Groups that users can be assigned to' AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `jos_vm_shopper_group`
--

INSERT INTO `jos_vm_shopper_group` (`shopper_group_id`, `vendor_id`, `shopper_group_name`, `shopper_group_desc`, `shopper_group_discount`, `show_price_including_tax`, `default`) VALUES
(5, 1, '-default-', 'This is the default shopper group.', 0.00, 1, 1),
(6, 1, 'Gold Level', 'Gold Level Shoppers.', 0.00, 1, 0),
(7, 1, 'Wholesale', 'Shoppers that can buy at wholesale.', 0.00, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_shopper_vendor_xref`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_shopper_vendor_xref` (
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `shopper_group_id` int(11) DEFAULT NULL,
  `customer_number` varchar(32) DEFAULT NULL,
  KEY `idx_shopper_vendor_xref_user_id` (`user_id`),
  KEY `idx_shopper_vendor_xref_vendor_id` (`vendor_id`),
  KEY `idx_shopper_vendor_xref_shopper_group_id` (`shopper_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps a user to a Shopper Group of a Vendor';

--
-- Дамп данных таблицы `jos_vm_shopper_vendor_xref`
--

INSERT INTO `jos_vm_shopper_vendor_xref` (`user_id`, `vendor_id`, `shopper_group_id`, `customer_number`) VALUES
(62, 1, 5, '');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_state`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
-- Последняя проверка: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL DEFAULT '1',
  `state_name` varchar(64) DEFAULT NULL,
  `state_3_code` char(3) DEFAULT NULL,
  `state_2_code` char(2) DEFAULT NULL,
  PRIMARY KEY (`state_id`),
  UNIQUE KEY `state_3_code` (`country_id`,`state_3_code`),
  UNIQUE KEY `state_2_code` (`country_id`,`state_2_code`),
  KEY `idx_country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='States that are assigned to a country' AUTO_INCREMENT=452 ;

--
-- Дамп данных таблицы `jos_vm_state`
--

INSERT INTO `jos_vm_state` (`state_id`, `country_id`, `state_name`, `state_3_code`, `state_2_code`) VALUES
(1, 223, 'Alabama', 'ALA', 'AL'),
(2, 223, 'Alaska', 'ALK', 'AK'),
(3, 223, 'Arizona', 'ARZ', 'AZ'),
(4, 223, 'Arkansas', 'ARK', 'AR'),
(5, 223, 'California', 'CAL', 'CA'),
(6, 223, 'Colorado', 'COL', 'CO'),
(7, 223, 'Connecticut', 'CCT', 'CT'),
(8, 223, 'Delaware', 'DEL', 'DE'),
(9, 223, 'District Of Columbia', 'DOC', 'DC'),
(10, 223, 'Florida', 'FLO', 'FL'),
(11, 223, 'Georgia', 'GEA', 'GA'),
(12, 223, 'Hawaii', 'HWI', 'HI'),
(13, 223, 'Idaho', 'IDA', 'ID'),
(14, 223, 'Illinois', 'ILL', 'IL'),
(15, 223, 'Indiana', 'IND', 'IN'),
(16, 223, 'Iowa', 'IOA', 'IA'),
(17, 223, 'Kansas', 'KAS', 'KS'),
(18, 223, 'Kentucky', 'KTY', 'KY'),
(19, 223, 'Louisiana', 'LOA', 'LA'),
(20, 223, 'Maine', 'MAI', 'ME'),
(21, 223, 'Maryland', 'MLD', 'MD'),
(22, 223, 'Massachusetts', 'MSA', 'MA'),
(23, 223, 'Michigan', 'MIC', 'MI'),
(24, 223, 'Minnesota', 'MIN', 'MN'),
(25, 223, 'Mississippi', 'MIS', 'MS'),
(26, 223, 'Missouri', 'MIO', 'MO'),
(27, 223, 'Montana', 'MOT', 'MT'),
(28, 223, 'Nebraska', 'NEB', 'NE'),
(29, 223, 'Nevada', 'NEV', 'NV'),
(30, 223, 'New Hampshire', 'NEH', 'NH'),
(31, 223, 'New Jersey', 'NEJ', 'NJ'),
(32, 223, 'New Mexico', 'NEM', 'NM'),
(33, 223, 'New York', 'NEY', 'NY'),
(34, 223, 'North Carolina', 'NOC', 'NC'),
(35, 223, 'North Dakota', 'NOD', 'ND'),
(36, 223, 'Ohio', 'OHI', 'OH'),
(37, 223, 'Oklahoma', 'OKL', 'OK'),
(38, 223, 'Oregon', 'ORN', 'OR'),
(39, 223, 'Pennsylvania', 'PEA', 'PA'),
(40, 223, 'Rhode Island', 'RHI', 'RI'),
(41, 223, 'South Carolina', 'SOC', 'SC'),
(42, 223, 'South Dakota', 'SOD', 'SD'),
(43, 223, 'Tennessee', 'TEN', 'TN'),
(44, 223, 'Texas', 'TXS', 'TX'),
(45, 223, 'Utah', 'UTA', 'UT'),
(46, 223, 'Vermont', 'VMT', 'VT'),
(47, 223, 'Virginia', 'VIA', 'VA'),
(48, 223, 'Washington', 'WAS', 'WA'),
(49, 223, 'West Virginia', 'WEV', 'WV'),
(50, 223, 'Wisconsin', 'WIS', 'WI'),
(51, 223, 'Wyoming', 'WYO', 'WY'),
(52, 38, 'Alberta', 'ALB', 'AB'),
(53, 38, 'British Columbia', 'BRC', 'BC'),
(54, 38, 'Manitoba', 'MAB', 'MB'),
(55, 38, 'New Brunswick', 'NEB', 'NB'),
(56, 38, 'Newfoundland and Labrador', 'NFL', 'NL'),
(57, 38, 'Northwest Territories', 'NWT', 'NT'),
(58, 38, 'Nova Scotia', 'NOS', 'NS'),
(59, 38, 'Nunavut', 'NUT', 'NU'),
(60, 38, 'Ontario', 'ONT', 'ON'),
(61, 38, 'Prince Edward Island', 'PEI', 'PE'),
(62, 38, 'Quebec', 'QEC', 'QC'),
(63, 38, 'Saskatchewan', 'SAK', 'SK'),
(64, 38, 'Yukon', 'YUT', 'YT'),
(65, 222, 'England', 'ENG', 'EN'),
(66, 222, 'Northern Ireland', 'NOI', 'NI'),
(67, 222, 'Scotland', 'SCO', 'SD'),
(68, 222, 'Wales', 'WLS', 'WS'),
(69, 13, 'Australian Capital Territory', 'ACT', 'AC'),
(70, 13, 'New South Wales', 'NSW', 'NS'),
(71, 13, 'Northern Territory', 'NOT', 'NT'),
(72, 13, 'Queensland', 'QLD', 'QL'),
(73, 13, 'South Australia', 'SOA', 'SA'),
(74, 13, 'Tasmania', 'TAS', 'TS'),
(75, 13, 'Victoria', 'VIC', 'VI'),
(76, 13, 'Western Australia', 'WEA', 'WA'),
(77, 138, 'Aguascalientes', 'AGS', 'AG'),
(78, 138, 'Baja California Norte', 'BCN', 'BN'),
(79, 138, 'Baja California Sur', 'BCS', 'BS'),
(80, 138, 'Campeche', 'CAM', 'CA'),
(81, 138, 'Chiapas', 'CHI', 'CS'),
(82, 138, 'Chihuahua', 'CHA', 'CH'),
(83, 138, 'Coahuila', 'COA', 'CO'),
(84, 138, 'Colima', 'COL', 'CM'),
(85, 138, 'Distrito Federal', 'DFM', 'DF'),
(86, 138, 'Durango', 'DGO', 'DO'),
(87, 138, 'Guanajuato', 'GTO', 'GO'),
(88, 138, 'Guerrero', 'GRO', 'GU'),
(89, 138, 'Hidalgo', 'HGO', 'HI'),
(90, 138, 'Jalisco', 'JAL', 'JA'),
(91, 138, 'México (Estado de)', 'EDM', 'EM'),
(92, 138, 'Michoacán', 'MCN', 'MI'),
(93, 138, 'Morelos', 'MOR', 'MO'),
(94, 138, 'Nayarit', 'NAY', 'NY'),
(95, 138, 'Nuevo León', 'NUL', 'NL'),
(96, 138, 'Oaxaca', 'OAX', 'OA'),
(97, 138, 'Puebla', 'PUE', 'PU'),
(98, 138, 'Querétaro', 'QRO', 'QU'),
(99, 138, 'Quintana Roo', 'QUR', 'QR'),
(100, 138, 'San Luis Potosí', 'SLP', 'SP'),
(101, 138, 'Sinaloa', 'SIN', 'SI'),
(102, 138, 'Sonora', 'SON', 'SO'),
(103, 138, 'Tabasco', 'TAB', 'TA'),
(104, 138, 'Tamaulipas', 'TAM', 'TM'),
(105, 138, 'Tlaxcala', 'TLX', 'TX'),
(106, 138, 'Veracruz', 'VER', 'VZ'),
(107, 138, 'Yucatán', 'YUC', 'YU'),
(108, 138, 'Zacatecas', 'ZAC', 'ZA'),
(109, 30, 'Acre', 'ACR', 'AC'),
(110, 30, 'Alagoas', 'ALG', 'AL'),
(111, 30, 'Amapá', 'AMP', 'AP'),
(112, 30, 'Amazonas', 'AMZ', 'AM'),
(113, 30, 'Bahía', 'BAH', 'BA'),
(114, 30, 'Ceará', 'CEA', 'CE'),
(115, 30, 'Distrito Federal', 'DFB', 'DF'),
(116, 30, 'Espirito Santo', 'ESS', 'ES'),
(117, 30, 'Goiás', 'GOI', 'GO'),
(118, 30, 'Maranhão', 'MAR', 'MA'),
(119, 30, 'Mato Grosso', 'MAT', 'MT'),
(120, 30, 'Mato Grosso do Sul', 'MGS', 'MS'),
(121, 30, 'Minas Geraís', 'MIG', 'MG'),
(122, 30, 'Paraná', 'PAR', 'PR'),
(123, 30, 'Paraíba', 'PRB', 'PB'),
(124, 30, 'Pará', 'PAB', 'PA'),
(125, 30, 'Pernambuco', 'PER', 'PE'),
(126, 30, 'Piauí', 'PIA', 'PI'),
(127, 30, 'Rio Grande do Norte', 'RGN', 'RN'),
(128, 30, 'Rio Grande do Sul', 'RGS', 'RS'),
(129, 30, 'Rio de Janeiro', 'RDJ', 'RJ'),
(130, 30, 'Rondônia', 'RON', 'RO'),
(131, 30, 'Roraima', 'ROR', 'RR'),
(132, 30, 'Santa Catarina', 'SAC', 'SC'),
(133, 30, 'Sergipe', 'SER', 'SE'),
(134, 30, 'São Paulo', 'SAP', 'SP'),
(135, 30, 'Tocantins', 'TOC', 'TO'),
(136, 44, 'Anhui', 'ANH', '34'),
(137, 44, 'Beijing', 'BEI', '11'),
(138, 44, 'Chongqing', 'CHO', '50'),
(139, 44, 'Fujian', 'FUJ', '35'),
(140, 44, 'Gansu', 'GAN', '62'),
(141, 44, 'Guangdong', 'GUA', '44'),
(142, 44, 'Guangxi Zhuang', 'GUZ', '45'),
(143, 44, 'Guizhou', 'GUI', '52'),
(144, 44, 'Hainan', 'HAI', '46'),
(145, 44, 'Hebei', 'HEB', '13'),
(146, 44, 'Heilongjiang', 'HEI', '23'),
(147, 44, 'Henan', 'HEN', '41'),
(148, 44, 'Hubei', 'HUB', '42'),
(149, 44, 'Hunan', 'HUN', '43'),
(150, 44, 'Jiangsu', 'JIA', '32'),
(151, 44, 'Jiangxi', 'JIX', '36'),
(152, 44, 'Jilin', 'JIL', '22'),
(153, 44, 'Liaoning', 'LIA', '21'),
(154, 44, 'Nei Mongol', 'NML', '15'),
(155, 44, 'Ningxia Hui', 'NIH', '64'),
(156, 44, 'Qinghai', 'QIN', '63'),
(157, 44, 'Shandong', 'SNG', '37'),
(158, 44, 'Shanghai', 'SHH', '31'),
(159, 44, 'Shaanxi', 'SHX', '61'),
(160, 44, 'Sichuan', 'SIC', '51'),
(161, 44, 'Tianjin', 'TIA', '12'),
(162, 44, 'Xinjiang Uygur', 'XIU', '65'),
(163, 44, 'Xizang', 'XIZ', '54'),
(164, 44, 'Yunnan', 'YUN', '53'),
(165, 44, 'Zhejiang', 'ZHE', '33'),
(166, 104, 'Israel', 'ISL', 'IL'),
(167, 104, 'Gaza Strip', 'GZS', 'GZ'),
(168, 104, 'West Bank', 'WBK', 'WB'),
(169, 151, 'St. Maarten', 'STM', 'SM'),
(170, 151, 'Bonaire', 'BNR', 'BN'),
(171, 151, 'Curacao', 'CUR', 'CR'),
(172, 175, 'Alba', 'ABA', 'AB'),
(173, 175, 'Arad', 'ARD', 'AR'),
(174, 175, 'Arges', 'ARG', 'AG'),
(175, 175, 'Bacau', 'BAC', 'BC'),
(176, 175, 'Bihor', 'BIH', 'BH'),
(177, 175, 'Bistrita-Nasaud', 'BIS', 'BN'),
(178, 175, 'Botosani', 'BOT', 'BT'),
(179, 175, 'Braila', 'BRL', 'BR'),
(180, 175, 'Brasov', 'BRA', 'BV'),
(181, 175, 'Bucuresti', 'BUC', 'B'),
(182, 175, 'Buzau', 'BUZ', 'BZ'),
(183, 175, 'Calarasi', 'CAL', 'CL'),
(184, 175, 'Caras Severin', 'CRS', 'CS'),
(185, 175, 'Cluj', 'CLJ', 'CJ'),
(186, 175, 'Constanta', 'CST', 'CT'),
(187, 175, 'Covasna', 'COV', 'CV'),
(188, 175, 'Dambovita', 'DAM', 'DB'),
(189, 175, 'Dolj', 'DLJ', 'DJ'),
(190, 175, 'Galati', 'GAL', 'GL'),
(191, 175, 'Giurgiu', 'GIU', 'GR'),
(192, 175, 'Gorj', 'GOR', 'GJ'),
(193, 175, 'Hargita', 'HRG', 'HR'),
(194, 175, 'Hunedoara', 'HUN', 'HD'),
(195, 175, 'Ialomita', 'IAL', 'IL'),
(196, 175, 'Iasi', 'IAS', 'IS'),
(197, 175, 'Ilfov', 'ILF', 'IF'),
(198, 175, 'Maramures', 'MAR', 'MM'),
(199, 175, 'Mehedinti', 'MEH', 'MH'),
(200, 175, 'Mures', 'MUR', 'MS'),
(201, 175, 'Neamt', 'NEM', 'NT'),
(202, 175, 'Olt', 'OLT', 'OT'),
(203, 175, 'Prahova', 'PRA', 'PH'),
(204, 175, 'Salaj', 'SAL', 'SJ'),
(205, 175, 'Satu Mare', 'SAT', 'SM'),
(206, 175, 'Sibiu', 'SIB', 'SB'),
(207, 175, 'Suceava', 'SUC', 'SV'),
(208, 175, 'Teleorman', 'TEL', 'TR'),
(209, 175, 'Timis', 'TIM', 'TM'),
(210, 175, 'Tulcea', 'TUL', 'TL'),
(211, 175, 'Valcea', 'VAL', 'VL'),
(212, 175, 'Vaslui', 'VAS', 'VS'),
(213, 175, 'Vrancea', 'VRA', 'VN'),
(214, 105, 'Agrigento', 'AGR', 'AG'),
(215, 105, 'Alessandria', 'ALE', 'AL'),
(216, 105, 'Ancona', 'ANC', 'AN'),
(217, 105, 'Aosta', 'AOS', 'AO'),
(218, 105, 'Arezzo', 'ARE', 'AR'),
(219, 105, 'Ascoli Piceno', 'API', 'AP'),
(220, 105, 'Asti', 'AST', 'AT'),
(221, 105, 'Avellino', 'AVE', 'AV'),
(222, 105, 'Bari', 'BAR', 'BA'),
(223, 105, 'Barletta Andria Trani', 'BTA', 'BT'),
(224, 105, 'Belluno', 'BEL', 'BL'),
(225, 105, 'Benevento', 'BEN', 'BN'),
(226, 105, 'Bergamo', 'BEG', 'BG'),
(227, 105, 'Biella', 'BIE', 'BI'),
(228, 105, 'Bologna', 'BOL', 'BO'),
(229, 105, 'Bolzano', 'BOZ', 'BZ'),
(230, 105, 'Brescia', 'BRE', 'BS'),
(231, 105, 'Brindisi', 'BRI', 'BR'),
(232, 105, 'Cagliari', 'CAG', 'CA'),
(233, 105, 'Caltanissetta', 'CAL', 'CL'),
(234, 105, 'Campobasso', 'CBO', 'CB'),
(235, 105, 'Carbonia-Iglesias', 'CAR', 'CI'),
(236, 105, 'Caserta', 'CAS', 'CE'),
(237, 105, 'Catania', 'CAT', 'CT'),
(238, 105, 'Catanzaro', 'CTZ', 'CZ'),
(239, 105, 'Chieti', 'CHI', 'CH'),
(240, 105, 'Como', 'COM', 'CO'),
(241, 105, 'Cosenza', 'COS', 'CS'),
(242, 105, 'Cremona', 'CRE', 'CR'),
(243, 105, 'Crotone', 'CRO', 'KR'),
(244, 105, 'Cuneo', 'CUN', 'CN'),
(245, 105, 'Enna', 'ENN', 'EN'),
(246, 105, 'Fermo', 'FMO', 'FM'),
(247, 105, 'Ferrara', 'FER', 'FE'),
(248, 105, 'Firenze', 'FIR', 'FI'),
(249, 105, 'Foggia', 'FOG', 'FG'),
(250, 105, 'Forli-Cesena', 'FOC', 'FC'),
(251, 105, 'Frosinone', 'FRO', 'FR'),
(252, 105, 'Genova', 'GEN', 'GE'),
(253, 105, 'Gorizia', 'GOR', 'GO'),
(254, 105, 'Grosseto', 'GRO', 'GR'),
(255, 105, 'Imperia', 'IMP', 'IM'),
(256, 105, 'Isernia', 'ISE', 'IS'),
(257, 105, 'L''Aquila', 'AQU', 'AQ'),
(258, 105, 'La Spezia', 'LAS', 'SP'),
(259, 105, 'Latina', 'LAT', 'LT'),
(260, 105, 'Lecce', 'LEC', 'LE'),
(261, 105, 'Lecco', 'LCC', 'LC'),
(262, 105, 'Livorno', 'LIV', 'LI'),
(263, 105, 'Lodi', 'LOD', 'LO'),
(264, 105, 'Lucca', 'LUC', 'LU'),
(265, 105, 'Macerata', 'MAC', 'MC'),
(266, 105, 'Mantova', 'MAN', 'MN'),
(267, 105, 'Massa-Carrara', 'MAS', 'MS'),
(268, 105, 'Matera', 'MAA', 'MT'),
(269, 105, 'Medio Campidano', 'MED', 'VS'),
(270, 105, 'Messina', 'MES', 'ME'),
(271, 105, 'Milano', 'MIL', 'MI'),
(272, 105, 'Modena', 'MOD', 'MO'),
(273, 105, 'Monza e della Brianza', 'MBA', 'MB'),
(274, 105, 'Napoli', 'NAP', 'NA'),
(275, 105, 'Novara', 'NOV', 'NO'),
(276, 105, 'Nuoro', 'NUR', 'NU'),
(277, 105, 'Ogliastra', 'OGL', 'OG'),
(278, 105, 'Olbia-Tempio', 'OLB', 'OT'),
(279, 105, 'Oristano', 'ORI', 'OR'),
(280, 105, 'Padova', 'PDA', 'PD'),
(281, 105, 'Palermo', 'PAL', 'PA'),
(282, 105, 'Parma', 'PAA', 'PR'),
(283, 105, 'Pavia', 'PAV', 'PV'),
(284, 105, 'Perugia', 'PER', 'PG'),
(285, 105, 'Pesaro e Urbino', 'PES', 'PU'),
(286, 105, 'Pescara', 'PSC', 'PE'),
(287, 105, 'Piacenza', 'PIA', 'PC'),
(288, 105, 'Pisa', 'PIS', 'PI'),
(289, 105, 'Pistoia', 'PIT', 'PT'),
(290, 105, 'Pordenone', 'POR', 'PN'),
(291, 105, 'Potenza', 'PTZ', 'PZ'),
(292, 105, 'Prato', 'PRA', 'PO'),
(293, 105, 'Ragusa', 'RAG', 'RG'),
(294, 105, 'Ravenna', 'RAV', 'RA'),
(295, 105, 'Reggio Calabria', 'REG', 'RC'),
(296, 105, 'Reggio Emilia', 'REE', 'RE'),
(297, 105, 'Rieti', 'RIE', 'RI'),
(298, 105, 'Rimini', 'RIM', 'RN'),
(299, 105, 'Roma', 'ROM', 'RM'),
(300, 105, 'Rovigo', 'ROV', 'RO'),
(301, 105, 'Salerno', 'SAL', 'SA'),
(302, 105, 'Sassari', 'SAS', 'SS'),
(303, 105, 'Savona', 'SAV', 'SV'),
(304, 105, 'Siena', 'SIE', 'SI'),
(305, 105, 'Siracusa', 'SIR', 'SR'),
(306, 105, 'Sondrio', 'SOO', 'SO'),
(307, 105, 'Taranto', 'TAR', 'TA'),
(308, 105, 'Teramo', 'TER', 'TE'),
(309, 105, 'Terni', 'TRN', 'TR'),
(310, 105, 'Torino', 'TOR', 'TO'),
(311, 105, 'Trapani', 'TRA', 'TP'),
(312, 105, 'Trento', 'TRE', 'TN'),
(313, 105, 'Treviso', 'TRV', 'TV'),
(314, 105, 'Trieste', 'TRI', 'TS'),
(315, 105, 'Udine', 'UDI', 'UD'),
(316, 105, 'Varese', 'VAR', 'VA'),
(317, 105, 'Venezia', 'VEN', 'VE'),
(318, 105, 'Verbano Cusio Ossola', 'VCO', 'VB'),
(319, 105, 'Vercelli', 'VER', 'VC'),
(320, 105, 'Verona', 'VRN', 'VR'),
(321, 105, 'Vibo Valenzia', 'VIV', 'VV'),
(322, 105, 'Vicenza', 'VII', 'VI'),
(323, 105, 'Viterbo', 'VIT', 'VT'),
(324, 195, 'A Coruña', 'ACO', '15'),
(325, 195, 'Alava', 'ALA', '01'),
(326, 195, 'Albacete', 'ALB', '02'),
(327, 195, 'Alicante', 'ALI', '03'),
(328, 195, 'Almeria', 'ALM', '04'),
(329, 195, 'Asturias', 'AST', '33'),
(330, 195, 'Avila', 'AVI', '05'),
(331, 195, 'Badajoz', 'BAD', '06'),
(332, 195, 'Baleares', 'BAL', '07'),
(333, 195, 'Barcelona', 'BAR', '08'),
(334, 195, 'Burgos', 'BUR', '09'),
(335, 195, 'Caceres', 'CAC', '10'),
(336, 195, 'Cadiz', 'CAD', '11'),
(337, 195, 'Cantabria', 'CAN', '39'),
(338, 195, 'Castellon', 'CAS', '12'),
(339, 195, 'Ceuta', 'CEU', '51'),
(340, 195, 'Ciudad Real', 'CIU', '13'),
(341, 195, 'Cordoba', 'COR', '14'),
(342, 195, 'Cuenca', 'CUE', '16'),
(343, 195, 'Girona', 'GIR', '17'),
(344, 195, 'Granada', 'GRA', '18'),
(345, 195, 'Guadalajara', 'GUA', '19'),
(346, 195, 'Guipuzcoa', 'GUI', '20'),
(347, 195, 'Huelva', 'HUL', '21'),
(348, 195, 'Huesca', 'HUS', '22'),
(349, 195, 'Jaen', 'JAE', '23'),
(350, 195, 'La Rioja', 'LRI', '26'),
(351, 195, 'Las Palmas', 'LPA', '35'),
(352, 195, 'Leon', 'LEO', '24'),
(353, 195, 'Lleida', 'LLE', '25'),
(354, 195, 'Lugo', 'LUG', '27'),
(355, 195, 'Madrid', 'MAD', '28'),
(356, 195, 'Malaga', 'MAL', '29'),
(357, 195, 'Melilla', 'MEL', '52'),
(358, 195, 'Murcia', 'MUR', '30'),
(359, 195, 'Navarra', 'NAV', '31'),
(360, 195, 'Ourense', 'OUR', '32'),
(361, 195, 'Palencia', 'PAL', '34'),
(362, 195, 'Pontevedra', 'PON', '36'),
(363, 195, 'Salamanca', 'SAL', '37'),
(364, 195, 'Santa Cruz de Tenerife', 'SCT', '38'),
(365, 195, 'Segovia', 'SEG', '40'),
(366, 195, 'Sevilla', 'SEV', '41'),
(367, 195, 'Soria', 'SOR', '42'),
(368, 195, 'Tarragona', 'TAR', '43'),
(369, 195, 'Teruel', 'TER', '44'),
(370, 195, 'Toledo', 'TOL', '45'),
(371, 195, 'Valencia', 'VAL', '46'),
(372, 195, 'Valladolid', 'VLL', '47'),
(373, 195, 'Vizcaya', 'VIZ', '48'),
(374, 195, 'Zamora', 'ZAM', '49'),
(375, 195, 'Zaragoza', 'ZAR', '50'),
(376, 11, 'Aragatsotn', 'ARG', 'AG'),
(377, 11, 'Ararat', 'ARR', 'AR'),
(378, 11, 'Armavir', 'ARM', 'AV'),
(379, 11, 'Gegharkunik', 'GEG', 'GR'),
(380, 11, 'Kotayk', 'KOT', 'KT'),
(381, 11, 'Lori', 'LOR', 'LO'),
(382, 11, 'Shirak', 'SHI', 'SH'),
(383, 11, 'Syunik', 'SYU', 'SU'),
(384, 11, 'Tavush', 'TAV', 'TV'),
(385, 11, 'Vayots-Dzor', 'VAD', 'VD'),
(386, 11, 'Yerevan', 'YER', 'ER'),
(387, 99, 'Andaman & Nicobar Islands', 'ANI', 'AI'),
(388, 99, 'Andhra Pradesh', 'AND', 'AN'),
(389, 99, 'Arunachal Pradesh', 'ARU', 'AR'),
(390, 99, 'Assam', 'ASS', 'AS'),
(391, 99, 'Bihar', 'BIH', 'BI'),
(392, 99, 'Chandigarh', 'CHA', 'CA'),
(393, 99, 'Chhatisgarh', 'CHH', 'CH'),
(394, 99, 'Dadra & Nagar Haveli', 'DAD', 'DD'),
(395, 99, 'Daman & Diu', 'DAM', 'DA'),
(396, 99, 'Delhi', 'DEL', 'DE'),
(397, 99, 'Goa', 'GOA', 'GO'),
(398, 99, 'Gujarat', 'GUJ', 'GU'),
(399, 99, 'Haryana', 'HAR', 'HA'),
(400, 99, 'Himachal Pradesh', 'HIM', 'HI'),
(401, 99, 'Jammu & Kashmir', 'JAM', 'JA'),
(402, 99, 'Jharkhand', 'JHA', 'JH'),
(403, 99, 'Karnataka', 'KAR', 'KA'),
(404, 99, 'Kerala', 'KER', 'KE'),
(405, 99, 'Lakshadweep', 'LAK', 'LA'),
(406, 99, 'Madhya Pradesh', 'MAD', 'MD'),
(407, 99, 'Maharashtra', 'MAH', 'MH'),
(408, 99, 'Manipur', 'MAN', 'MN'),
(409, 99, 'Meghalaya', 'MEG', 'ME'),
(410, 99, 'Mizoram', 'MIZ', 'MI'),
(411, 99, 'Nagaland', 'NAG', 'NA'),
(412, 99, 'Orissa', 'ORI', 'OR'),
(413, 99, 'Pondicherry', 'PON', 'PO'),
(414, 99, 'Punjab', 'PUN', 'PU'),
(415, 99, 'Rajasthan', 'RAJ', 'RA'),
(416, 99, 'Sikkim', 'SIK', 'SI'),
(417, 99, 'Tamil Nadu', 'TAM', 'TA'),
(418, 99, 'Tripura', 'TRI', 'TR'),
(419, 99, 'Uttaranchal', 'UAR', 'UA'),
(420, 99, 'Uttar Pradesh', 'UTT', 'UT'),
(421, 99, 'West Bengal', 'WES', 'WE'),
(422, 101, 'Ahmadi va Kohkiluyeh', 'BOK', 'BO'),
(423, 101, 'Ardabil', 'ARD', 'AR'),
(424, 101, 'Azarbayjan-e Gharbi', 'AZG', 'AG'),
(425, 101, 'Azarbayjan-e Sharqi', 'AZS', 'AS'),
(426, 101, 'Bushehr', 'BUS', 'BU'),
(427, 101, 'Chaharmahal va Bakhtiari', 'CMB', 'CM'),
(428, 101, 'Esfahan', 'ESF', 'ES'),
(429, 101, 'Fars', 'FAR', 'FA'),
(430, 101, 'Gilan', 'GIL', 'GI'),
(431, 101, 'Gorgan', 'GOR', 'GO'),
(432, 101, 'Hamadan', 'HAM', 'HA'),
(433, 101, 'Hormozgan', 'HOR', 'HO'),
(434, 101, 'Ilam', 'ILA', 'IL'),
(435, 101, 'Kerman', 'KER', 'KE'),
(436, 101, 'Kermanshah', 'BAK', 'BA'),
(437, 101, 'Khorasan-e Junoubi', 'KHJ', 'KJ'),
(438, 101, 'Khorasan-e Razavi', 'KHR', 'KR'),
(439, 101, 'Khorasan-e Shomali', 'KHS', 'KS'),
(440, 101, 'Khuzestan', 'KHU', 'KH'),
(441, 101, 'Kordestan', 'KOR', 'KO'),
(442, 101, 'Lorestan', 'LOR', 'LO'),
(443, 101, 'Markazi', 'MAR', 'MR'),
(444, 101, 'Mazandaran', 'MAZ', 'MZ'),
(445, 101, 'Qazvin', 'QAS', 'QA'),
(446, 101, 'Qom', 'QOM', 'QO'),
(447, 101, 'Semnan', 'SEM', 'SE'),
(448, 101, 'Sistan va Baluchestan', 'SBA', 'SB'),
(449, 101, 'Tehran', 'TEH', 'TE'),
(450, 101, 'Yazd', 'YAZ', 'YA'),
(451, 101, 'Zanjan', 'ZAN', 'ZA');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_tax_rate`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_tax_rate` (
  `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `tax_state` varchar(64) DEFAULT NULL,
  `tax_country` varchar(64) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `tax_rate` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`tax_rate_id`),
  KEY `idx_tax_rate_vendor_id` (`vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='The tax rates for your store' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `jos_vm_tax_rate`
--

INSERT INTO `jos_vm_tax_rate` (`tax_rate_id`, `vendor_id`, `tax_state`, `tax_country`, `mdate`, `tax_rate`) VALUES
(2, 1, 'CA', 'USA', 964565926, 0.09750);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_userfield`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 05 2012 г., 12:11
--

CREATE TABLE IF NOT EXISTS `jos_vm_userfield` (
  `fieldid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `maxlength` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `required` tinyint(4) DEFAULT '0',
  `ordering` int(11) DEFAULT NULL,
  `cols` int(11) DEFAULT NULL,
  `rows` int(11) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `default` int(11) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `registration` tinyint(1) NOT NULL DEFAULT '0',
  `shipping` tinyint(1) NOT NULL DEFAULT '0',
  `account` tinyint(1) NOT NULL DEFAULT '1',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `calculated` tinyint(1) NOT NULL DEFAULT '0',
  `sys` tinyint(4) NOT NULL DEFAULT '0',
  `vendor_id` int(11) DEFAULT NULL,
  `params` mediumtext,
  PRIMARY KEY (`fieldid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds the fields for the user information' AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `jos_vm_userfield`
--

INSERT INTO `jos_vm_userfield` (`fieldid`, `name`, `title`, `description`, `type`, `maxlength`, `size`, `required`, `ordering`, `cols`, `rows`, `value`, `default`, `published`, `registration`, `shipping`, `account`, `readonly`, `calculated`, `sys`, `vendor_id`, `params`) VALUES
(1, 'email', 'REGISTER_EMAIL', '', 'emailaddress', 100, 30, 1, 2, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 0, 0, 1, 1, NULL),
(7, 'title', 'PHPSHOP_SHOPPER_FORM_TITLE', '', 'select', 0, 0, 0, 8, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 1, 1, NULL),
(3, 'password', 'Пароль', '', 'password', 25, 30, 1, 4, 0, 0, '', 0, 1, 1, 0, 1, 0, 0, 1, 1, ''),
(4, 'password2', 'Подтвердите пароль', '', 'password', 25, 30, 1, 5, 0, 0, '', 0, 1, 1, 0, 1, 0, 0, 1, 1, ''),
(6, 'company', 'PHPSHOP_SHOPPER_FORM_COMPANY_NAME', '', 'text', 64, 30, 0, 7, NULL, NULL, NULL, NULL, 0, 1, 1, 1, 0, 0, 1, 1, NULL),
(5, 'vm_delimiter_billto', 'Пользовательская информация', '', 'delimiter', 25, 30, 0, 6, 0, 0, '', 0, 1, 1, 0, 1, 0, 0, 0, 1, ''),
(2, 'username', 'Логин', '', 'text', 25, 30, 1, 3, 0, 0, '', 0, 1, 1, 0, 1, 0, 0, 1, 1, ''),
(35, 'address_type_name', 'PHPSHOP_USER_FORM_ADDRESS_LABEL', '', 'text', 32, 30, 1, 6, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, 0, 1, 1, NULL),
(8, 'first_name', 'Имя', '', 'text', 32, 30, 0, 9, 0, 0, '', 0, 1, 1, 1, 1, 0, 0, 1, 1, ''),
(9, 'last_name', 'Фамилия', '', 'text', 32, 30, 0, 10, 0, 0, '', 0, 1, 1, 1, 1, 0, 0, 1, 1, ''),
(10, 'middle_name', 'Отчество', '', 'text', 32, 30, 0, 11, 0, 0, '', 0, 1, 1, 1, 1, 0, 0, 1, 1, ''),
(11, 'address_1', 'Адрес', '', 'text', 64, 30, 0, 12, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(12, 'address_2', 'PHPSHOP_SHOPPER_FORM_ADDRESS_2', '', 'text', 64, 30, 0, 13, NULL, NULL, NULL, NULL, 0, 1, 1, 1, 0, 0, 1, 1, NULL),
(13, 'city', 'Город', '', 'text', 32, 30, 0, 14, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(14, 'zip', 'Почтовый индекс', '', 'text', 32, 30, 0, 15, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(15, 'country', 'Страна', '', 'select', 0, 0, 0, 16, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(16, 'state', 'PHPSHOP_SHOPPER_FORM_STATE', '', 'select', 0, 0, 0, 17, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(17, 'phone_1', 'Телефон', '', 'text', 32, 30, 0, 19, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(18, 'phone_2', 'PHPSHOP_SHOPPER_FORM_PHONE2', '', 'text', 32, 30, 0, 20, NULL, NULL, NULL, NULL, 0, 1, 1, 1, 0, 0, 1, 1, NULL),
(19, 'fax', 'Факс', '', 'text', 32, 30, 0, 21, 0, 0, '', 0, 0, 1, 1, 1, 0, 0, 1, 1, ''),
(20, 'delimiter_bankaccount', 'PHPSHOP_ACCOUNT_BANK_TITLE', '', 'delimiter', 25, 30, 0, 22, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 0, 1, NULL),
(21, 'bank_account_holder', 'PHPSHOP_ACCOUNT_LBL_BANK_ACCOUNT_HOLDER', '', 'text', 48, 30, 0, 23, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL),
(22, 'bank_account_nr', 'PHPSHOP_ACCOUNT_LBL_BANK_ACCOUNT_NR', '', 'text', 32, 30, 0, 24, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL),
(23, 'bank_sort_code', 'PHPSHOP_ACCOUNT_LBL_BANK_SORT_CODE', '', 'text', 16, 30, 0, 25, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL),
(24, 'bank_name', 'PHPSHOP_ACCOUNT_LBL_BANK_NAME', '', 'text', 32, 30, 0, 26, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL),
(25, 'bank_account_type', 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE', '', 'select', 0, 0, 0, 27, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 1, 0, 1, 1, ''),
(26, 'bank_iban', 'PHPSHOP_ACCOUNT_LBL_BANK_IBAN', '', 'text', 64, 30, 0, 28, NULL, NULL, NULL, NULL, 1, 0, 0, 1, 0, 0, 1, 1, NULL),
(27, 'vm_delimiter_sendregistration', 'Пользовательское соглашение', '', 'delimiter', 25, 30, 0, 29, 0, 0, '', 0, 0, 1, 0, 0, 0, 0, 0, 1, ''),
(28, 'agreed', 'Согласны ли вы с правилами сервиса?', '', 'checkbox', 0, 0, 1, 31, 0, 0, '', 0, 0, 1, 0, 0, 0, 0, 1, 1, ''),
(29, 'vm_delimiter_userinfo', 'Регистрационная информация', '', 'delimiter', 0, 0, 0, 1, 0, 0, '', 0, 1, 1, 0, 1, 0, 0, 0, 1, ''),
(30, 'vm_extra_field_1', 'Регион', '', 'text', 255, 30, 0, 18, 0, 0, '', 0, 0, 1, 0, 1, 0, 0, 0, 1, ''),
(31, 'extra_field_2', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_2', '', 'text', 255, 30, 0, 32, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL),
(32, 'extra_field_3', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_3', '', 'text', 255, 30, 0, 33, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL),
(33, 'extra_field_4', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_4', '', 'select', 1, 1, 0, 34, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL),
(34, 'extra_field_5', 'PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_5', '', 'select', 1, 1, 0, 35, NULL, NULL, NULL, NULL, 0, 1, 0, 1, 0, 0, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_userfield_values`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_userfield_values` (
  `fieldvalueid` int(11) NOT NULL AUTO_INCREMENT,
  `fieldid` int(11) NOT NULL DEFAULT '0',
  `fieldtitle` varchar(255) NOT NULL DEFAULT '',
  `fieldvalue` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `sys` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldvalueid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds the different values for dropdown and radio lists' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `jos_vm_userfield_values`
--

INSERT INTO `jos_vm_userfield_values` (`fieldvalueid`, `fieldid`, `fieldtitle`, `fieldvalue`, `ordering`, `sys`) VALUES
(1, 25, 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE_BUSINESSCHECKING', 'Checking', 1, 1),
(2, 25, 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE_CHECKING', 'Business Checking', 2, 1),
(3, 25, 'PHPSHOP_ACCOUNT_LBL_ACCOUNT_TYPE_SAVINGS', 'Savings', 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_user_info`
--
-- Создание: Июн 04 2012 г., 21:38
-- Последнее обновление: Июн 04 2012 г., 21:38
--

CREATE TABLE IF NOT EXISTS `jos_vm_user_info` (
  `user_info_id` varchar(32) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `address_type` char(2) DEFAULT NULL,
  `address_type_name` varchar(32) DEFAULT NULL,
  `company` varchar(64) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `phone_1` varchar(255) DEFAULT NULL,
  `phone_2` varchar(32) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(64) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `extra_field_1` varchar(255) DEFAULT NULL,
  `extra_field_2` varchar(255) DEFAULT NULL,
  `extra_field_3` varchar(255) DEFAULT NULL,
  `extra_field_4` char(1) DEFAULT NULL,
  `extra_field_5` char(1) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `perms` varchar(40) NOT NULL DEFAULT 'shopper',
  `bank_account_nr` varchar(32) NOT NULL DEFAULT '',
  `bank_name` varchar(32) NOT NULL DEFAULT '',
  `bank_sort_code` varchar(16) NOT NULL DEFAULT '',
  `bank_iban` varchar(64) NOT NULL DEFAULT '',
  `bank_account_holder` varchar(48) NOT NULL DEFAULT '',
  `bank_account_type` enum('Checking','Business Checking','Savings') NOT NULL DEFAULT 'Checking',
  PRIMARY KEY (`user_info_id`),
  KEY `idx_user_info_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Customer Information, BT = BillTo and ST = ShipTo';

--
-- Дамп данных таблицы `jos_vm_user_info`
--

INSERT INTO `jos_vm_user_info` (`user_info_id`, `user_id`, `address_type`, `address_type_name`, `company`, `title`, `last_name`, `first_name`, `middle_name`, `phone_1`, `phone_2`, `fax`, `address_1`, `address_2`, `city`, `state`, `country`, `zip`, `user_email`, `extra_field_1`, `extra_field_2`, `extra_field_3`, `extra_field_4`, `extra_field_5`, `cdate`, `mdate`, `perms`, `bank_account_nr`, `bank_name`, `bank_sort_code`, `bank_iban`, `bank_account_holder`, `bank_account_type`) VALUES
('70a2151839e4d935ccde92411e76e973', 62, 'BT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '', '', 'US', '', 'cahbkooo91@mail.ru', NULL, NULL, NULL, NULL, NULL, 1336063949, 1336140046, 'shopper', '', '', '', '', '', 'Checking');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_vendor`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 04 2012 г., 21:46
--

CREATE TABLE IF NOT EXISTS `jos_vm_vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(64) DEFAULT NULL,
  `contact_last_name` varchar(32) NOT NULL DEFAULT '',
  `contact_first_name` varchar(32) NOT NULL DEFAULT '',
  `contact_middle_name` varchar(32) DEFAULT NULL,
  `contact_title` varchar(32) DEFAULT NULL,
  `contact_phone_1` varchar(32) NOT NULL DEFAULT '',
  `contact_phone_2` varchar(32) DEFAULT NULL,
  `contact_fax` varchar(32) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `vendor_phone` varchar(32) DEFAULT NULL,
  `vendor_address_1` varchar(64) NOT NULL DEFAULT '',
  `vendor_address_2` varchar(64) DEFAULT NULL,
  `vendor_city` varchar(32) NOT NULL DEFAULT '',
  `vendor_state` varchar(32) NOT NULL DEFAULT '',
  `vendor_country` varchar(32) NOT NULL DEFAULT 'US',
  `vendor_zip` varchar(32) NOT NULL DEFAULT '',
  `vendor_store_name` varchar(128) NOT NULL DEFAULT '',
  `vendor_store_desc` text,
  `vendor_category_id` int(11) DEFAULT NULL,
  `vendor_thumb_image` varchar(255) DEFAULT NULL,
  `vendor_full_image` varchar(255) DEFAULT NULL,
  `vendor_currency` varchar(16) DEFAULT NULL,
  `cdate` int(11) DEFAULT NULL,
  `mdate` int(11) DEFAULT NULL,
  `vendor_image_path` varchar(255) DEFAULT NULL,
  `vendor_terms_of_service` text NOT NULL,
  `vendor_url` varchar(255) NOT NULL DEFAULT '',
  `vendor_min_pov` decimal(10,2) DEFAULT NULL,
  `vendor_freeshipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vendor_currency_display_style` varchar(64) NOT NULL DEFAULT '',
  `vendor_accepted_currencies` text NOT NULL,
  `vendor_address_format` text NOT NULL,
  `vendor_date_format` varchar(255) NOT NULL,
  PRIMARY KEY (`vendor_id`),
  KEY `idx_vendor_name` (`vendor_name`),
  KEY `idx_vendor_category_id` (`vendor_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Vendors manage their products in your store' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `jos_vm_vendor`
--

INSERT INTO `jos_vm_vendor` (`vendor_id`, `vendor_name`, `contact_last_name`, `contact_first_name`, `contact_middle_name`, `contact_title`, `contact_phone_1`, `contact_phone_2`, `contact_fax`, `contact_email`, `vendor_phone`, `vendor_address_1`, `vendor_address_2`, `vendor_city`, `vendor_state`, `vendor_country`, `vendor_zip`, `vendor_store_name`, `vendor_store_desc`, `vendor_category_id`, `vendor_thumb_image`, `vendor_full_image`, `vendor_currency`, `cdate`, `mdate`, `vendor_image_path`, `vendor_terms_of_service`, `vendor_url`, `vendor_min_pov`, `vendor_freeshipping`, `vendor_currency_display_style`, `vendor_accepted_currencies`, `vendor_address_format`, `vendor_date_format`) VALUES
(1, 'Washupito\\''s Tiendita', 'Owner', 'Demo', 'Store', 'Mr.', '555-555-1212', '555-555-1212', '555-555-1212', 'cahbkooo91@mail.ru', '555-555-1212', '100 Washupito Avenue, N.W.', '', 'Lake Forest', ' - ', 'RUS', '92630', 'Washupito\\''s Tiendita', '<p>We have the best tools for do-it-yourselfers.  Check us out!</p>\r\n<p>We were established in 1969 in a time when getting good tools was expensive, but the quality was good.  Now that only a select few of those authentic tools survive, we have dedicated this store to bringing the experience alive for collectors and master mechanics everywhere.</p>\r\n<p>You can easily find products selecting the category you would like to browse above.</p>', 0, '', 'c19970d6f2970cb0d1b13bea3af3144a.gif', 'RUB', 950302468, 1338846382, '', '<h5>You haven\\''t configured any terms of service yet. Click <a href=\\"administrator/index2.php?page=store.store_form&amp;option=com_virtuemart\\">here</a> to change this text.</h5>', 'http://127.0.0.1/Joomla', 0.00, 0.00, '1|руб.|2|.| |1|8', 'USD', '{storename}\r\n{address_1}\r\n{address_2}\r\n{city}, {zip}', '%A, %d %B %Y %H:%M');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_vendor_category`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_vendor_category` (
  `vendor_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_category_name` varchar(64) DEFAULT NULL,
  `vendor_category_desc` text,
  PRIMARY KEY (`vendor_category_id`),
  KEY `idx_vendor_category_category_name` (`vendor_category_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='The categories that vendors are assigned to' AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `jos_vm_vendor_category`
--

INSERT INTO `jos_vm_vendor_category` (`vendor_category_id`, `vendor_category_name`, `vendor_category_desc`) VALUES
(6, '-default-', 'Default');

-- --------------------------------------------------------

--
-- Структура таблицы `jos_vm_zone_shipping`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_vm_zone_shipping` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(255) DEFAULT NULL,
  `zone_cost` decimal(10,2) DEFAULT NULL,
  `zone_limit` decimal(10,2) DEFAULT NULL,
  `zone_description` text NOT NULL,
  `zone_tax_rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='The Zones managed by the Zone Shipping Module' AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `jos_vm_zone_shipping`
--

INSERT INTO `jos_vm_zone_shipping` (`zone_id`, `zone_name`, `zone_cost`, `zone_limit`, `zone_description`, `zone_tax_rate`) VALUES
(1, 'Default', 6.00, 35.00, 'This is the default Shipping Zone. This is the zone information that all countries will use until you assign each individual country to a Zone.', 2),
(2, 'Zone 1', 1000.00, 10000.00, 'This is a zone example', 2),
(3, 'Zone 2', 2.00, 22.00, 'This is the second zone. You can use this for notes about this zone', 2),
(4, 'Zone 3', 11.00, 64.00, 'Another usefull thing might be details about this zone or special instructions.', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `jos_weblinks`
--
-- Создание: Июн 03 2012 г., 17:01
-- Последнее обновление: Июн 03 2012 г., 17:01
--

CREATE TABLE IF NOT EXISTS `jos_weblinks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `sid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`,`published`,`archived`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
