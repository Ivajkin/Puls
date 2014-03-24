<?php
/**
*
* @package acp
* @version $Id: mod_version_check_version.php 51 2007-10-30 04:40:42Z Handyman $
* @copyright (c) 2007 StarTrekGuide
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package mod_version_check
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

class cleantalk_version
{
	function version()
	{
		return array(
			'author'	=> 'Shagimuratov',
			'title'		=> 'Cleantalk. Spam protect',
			'tag'		=> 'phpbb_cleantalk',
			'version'	=> '3.4.7',
			'file'		=> array('cleantalk.ru', 'updatecheck', 'phpbb_cleantalk.php?version=347'),
		);
	}
}

?>
