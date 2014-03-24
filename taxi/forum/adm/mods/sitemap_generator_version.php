<?php
/**
*
* @package acp
* @version $Id: sitemap_generator_version.php 2009-03-16 Joshua2100 $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package mod_version_check
*/
class sitemap_generator_version
{
	function version()
	{
		return array(
			'author'	=> 'Joshua2100',
			'title'		=> 'SiteMap Generator',
			'tag'		=> 'sitemap_generator',
			'version'	=> '1.2.0',
			'file'		=> array('www.commexcomputers.com', 'updatecheck', 'mods.xml'),
		);
	}
}

?>