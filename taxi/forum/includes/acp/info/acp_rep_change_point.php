<?php
/**
*
* @author idiotnesia pungkerz@gmail.com - http://www.phpbbindonesia.com
*
* @package acp
* @version 0.3.1
* @copyright (c) 2008, 2009 phpbbindonesia
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* 
*/

/**
* @package module_install
*/
class acp_rep_change_point_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_rep_change_point',
			'title'		=> 'ACP_RP_POINTS',
			'version'	=> '0.3.0',
			'modes'		=> array(
				'reputation'		=> array('title' => 'ACP_RP_POINTS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_RP_SETTINGS')),
			),
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