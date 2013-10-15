<?php
/**
*
* @package acp
* @version $Id: acp_sitemap_generator.php 2009-03-16 Joshua2100 $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package acp
*/
class acp_sitemap_generator
{
	var $u_action;
	var $new_config = array();
					
	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		
		$user->add_lang('mods/info_acp_sitemap_generator');
		
		$this->tpl_name = 'acp_sitemap_generator';
		$this->page_title = 'ACP_SITEMAP_GENERATOR';
		
		// Set up general vars
		$submit	= isset($_POST['submit']) ? true : false;	
		require($phpbb_root_path . 'includes/sitemap_functions.' . $phpEx);

		define('FORUM_DOMAIN_ROOT', 'http://'.$config['server_name'].$config['script_path'].'/');
			
		if (($submit) && (isset($_POST['generate'])))
		{
		//seomod configures
		if ($config['sitemap_seo_mod'] == '1') 
		{
			$handymanseo = $phpbb_root_path . 'includes/functions_seo.' . $phpEx;
			$phpbbseomod = $phpbb_root_path . 'phpbb_seo/phpbb_seo_class.'.$phpEx;
			if (file_exists($handymanseo)) 
			{
				require_once($handymanseo);
				$seomod = 'handymans';
			}
			elseif (file_exists($phpbbseomod)) 
			{
				require_once($phpbbseomod);
				$phpbb_seo = new phpbb_seo(); 
				$seomod = 'phpbbseo';
			}
			else
			{
			trigger_error($user->lang['SEO_NOT_INT'] . adm_back_link($this->u_action), E_USER_WARNING);
			}
		}
			// Sitemap Generator
			generate_sitemap($seomod);

			// Pinging Google SiteMaps...
			if ($config['sitemap_ping'] == '1') 
			{
				$smindex = generate_board_url() . '/' . $config['sitemap_dir'] . '/sitemap_index.xml';
				pinggooglesitemaps($smindex);
			}
			
			// SiteMap Creation Complete.
			trigger_error($user->lang['GENERATE_COMPLETE'] . adm_back_link($this->u_action));
		}
		// lets get some pointless stats to display : D
		// TODO: Fix this, using substr_count is extremely inefficient. still needs fixing 
		/*if ($config['sitemap_gzip'] != '1' && $config['sitemap_show_stats'] == '1') 
		{
			$topicmap_rel = $phpbb_root_path . $config['sitemap_dir'] . '/topic_sitemap.xml';
			$forummap_rel = $phpbb_root_path . $config['sitemap_dir'] . '/forum_sitemap.xml';
			clearstatcache();
			$topicline = (count(file($topicmap_rel))-3)/4;
			$forumline = (count(file($forummap_rel))-3)/4;
			$topicsize = filesize($topicmap_rel) . ' bytes';
			$forumsize = filesize($forummap_rel) . ' bytes';
			$template->assign_vars(array(
				'TOPIC_LINKNO'      => $topicline,
				'FORUM_LINKNO'      => $forumline,
				'T_SHOW_TEHSTAT'   	=> true,
				'TOPIC_SIZE'		=> $topicsize,
				'FORUM_SIZE'		=> $forumsize,
				'SM_LAST_RUN'		=> 'N/A',
				'SM_VERSION'		=> '1.1.0',
				)
			);
		}
		*/
		// start settings
		$display_vars = array(
				'title'	=> 'ACP_SITEMAP_GENERATOR_SETTINGS',
				'vars'	=> array(
				'legend'		=> 'SETTINGS',
				'sitemap_ping'		=> array('lang' => 'PINGORNOT', 'type' => 'radio:yes_no', 	'explain' => false),
				'sitemap_seo_mod'	=> array('lang' => 'SEOMOD', 	'type' => 'radio:yes_no', 	'explain' => false),
				//'sitemap_show_stats'=> array('lang' => 'MAPSTATS',	'type' => 'radio:yes_no',	'explain' => false),
				'sitemap_gzip'		=> array('lang' => 'MAPZIP', 	'type' => 'radio:yes_no', 	'explain' => false),
				'sitemap_bot_user'	=> array('lang' => 'BOTUSER',	'type' => 'text:3:4',	 	'explain' => false),
				'sitemap_dir' => array('lang' => 'MAPDIR', 	'type' => 'text:9:10', 		'explain' => false),
				)
			);
			
		if (isset($display_vars['lang']))
		{
			$user->add_lang($display_vars['lang']);
		}

		$this->new_config = $config;
		$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
		$error = array();

		// We validate the complete config if whished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);
		
		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				set_config($config_name, $config_value);
			}
		}

// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'		=> true,
					'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
				);

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
			}
			else if ($vars['explain'])
			{
				$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
			}

			$template->assign_block_vars('options', array(
				'KEY'			=> $config_key,
				'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars),
				)
			);
		
			unset($display_vars['vars'][$config_key]);
		}
		
	}
}
?>