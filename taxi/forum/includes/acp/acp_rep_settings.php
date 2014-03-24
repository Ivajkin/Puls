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
* based on acp_board.php by phpBB team
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_rep_settings
{
	var $u_action;
	var $new_config = array();

	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('acp/board');

		$action	= request_var('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		$form_key = 'acp_board';
		add_form_key($form_key);

		/**
		*	Validation types are:
		*		string, int, bool,
		*		script_path (absolute path in url - beginning with / and no trailing slash),
		*		rpath (relative), rwpath (realtive, writable), path (relative path, but able to escape the root), wpath (writable)
		*/
		switch ($mode)
		{

// user reputation points			
			case 'reputation':
				$display_vars = array(
					'title'	=> 'ACP_REPUTATION_SETTINGS',
					'lang' 	=> 'mods/reputation_mod',
					'vars'	=> array(
						'legend1'				=> 'GENERAL_SETTINGS',
						'rp_enable'				=> array('lang' => 'RP_ENABLE',				'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => false),						
						'rp_display'	=> array('lang' => 'RP_DISPLAY',	'validate' => 'int',	'type' => 'select', 'method' => 'rp_display', 'explain' => false),
						'rp_time_limitation'	=> array('lang' => 'RP_TIME_LIMITATION', 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true, 'append' => ' ' . $user->lang['HOURS']),
						'rp_user_spread'		=> array('lang' => 'RP_USER_SPREAD',	 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_recent_points'		=> array('lang' => 'RP_RECENT_POINTS',	 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_block_per_points'		=> array('lang' => 'RP_BLOCK_PER_POINTS',	 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_max_blocks'		=> array('lang' => 'RP_MAX_BLOCK',	 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_minimum_point'			=> array('lang' => 'RP_MINIMUM_POINT',	 		'validate' => 'int',	'type' => 'text:4:5', 'explain' => false),
						'rp_maximum_point'			=> array('lang' => 'RP_MAXIMUM_POINT',	 		'validate' => 'int',	'type' => 'text:4:5', 'explain' => false),
						'rp_forum_exclusions'			=> array('lang' => 'RP_FORUM_EXCLUSIONS',			'validate' => 'string',	'type' => 'text:25:255', 'explain' => true),
						'legend2'				=> 'RP_COMMENT',
						'rp_disable_comment'	=> array('lang' => 'RP_DISABLE_COMMENT', 	'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => false),
						'rp_force_comment'	=> array('lang' => 'RP_FORCE_COMMENT', 	'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => false),
						'rp_comment_max_chars'	=> array('lang' => 'RP_MAX_CHARS',	 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),						
						'legend3'				=> 'RP_POWER',
						'rp_min_posts'			=> array('lang' => 'RP_MIN_POSTS',	 		'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_total_posts'		=> array('lang' => 'RP_TOTAL_POSTS',	 	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_membership_days'	=> array('lang' => 'RP_MEMBERSHIP_DAYS',	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_power_rep_point'	=> array('lang' => 'RP_POWER_REP_POINT',	'validate' => 'int',	'type' => 'text:4:5', 'explain' => true),
						'rp_max_power'			=> array('lang' => 'RP_MAX_POWER',			'validate' => 'int',	'type' => 'text:4:5', 'explain' => true)
					)
				);
			break;
// end user reputation points
			default:
				trigger_error('NO_MODE', E_USER_ERROR);
			break;
		}

		if (isset($display_vars['lang']))
		{
			$user->add_lang($display_vars['lang']);
		}

		$this->new_config = $config;
		$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
		$error = array();

		// We validate the complete config if whished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		if ($submit && !check_form_key($form_key))
		{
			$error[] = $user->lang['FORM_INVALID'];
		}
		// Do not write values if there is an error
		if (sizeof($error))
		{
			$submit = false;
		}

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

		if ($submit)
		{
			add_log('admin', 'LOG_CONFIG_' . strtoupper($mode));

			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		$this->tpl_name = 'acp_board';
		$this->page_title = $display_vars['title'];

		$template->assign_vars(array(
			'L_TITLE'			=> $user->lang[$display_vars['title']],
			'L_TITLE_EXPLAIN'	=> $user->lang[$display_vars['title'] . '_EXPLAIN'],

			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),

			'U_ACTION'			=> $this->u_action)
		);

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
			
			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);
			
			if (empty($content))
			{
				continue;
			}
			
			$template->assign_block_vars('options', array(
				'KEY'			=> $config_key,
				'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> $content,
				)
			);

			unset($display_vars['vars'][$config_key]);
		}


	}



// user reputation point
	/**
	* Select RP Display
	*/
	function rp_display($value, $key = '')
	{
		global $user;

		return '<option value="1"' . (($value == 1) ? ' selected="selected"' : '') . '>' . $user->lang['RP_DISPLAY_TEXT'] . '</option><option value="2"' . (($value == 2) ? ' selected="selected"' : '') . '>' . $user->lang['RP_DISPLAY_BLOCK'] . '</option><option value="3"' . (($value == 3) ? ' selected="selected"' : '') . '>' . $user->lang['RP_DISPLAY_BOTH'] . '</option>';
	}
// end user reputation	


}

?>