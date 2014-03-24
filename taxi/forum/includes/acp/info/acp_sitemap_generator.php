<?php
/** 
*
* @package acp
* @version $Id: acp_sitemap_generator.php 2009-03-16 Joshua2100 $
* @copyright (c) 2007 phpBB Group 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @package module_install
*/
class acp_sitemap_generator_info
{
	function module()
	{
	return array(
		'filename'	=> 'acp_sitemap_generator',
		'title'		=> 'ACP_SITEMAP_GENERATOR',
		'version'	=> '1.2.0',
		'modes'		=> array(
			'sitemap_generator' => array('title' => 'ACP_SITEMAP_GENERATOR', 'auth' => 'acl_a_board', 'cat' => array('ACP_GENERAL_TASKS'))
			)
	);
		
	}

	function install()
	{
	}

	function uninstall()
	{
	}

}
?>