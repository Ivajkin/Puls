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
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_rep_change_point
{
	var $u_action;

	function main($id, $mode)
	{
		global $user, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx;
		global $reputation;

		$user->add_lang('mods/reputation_mod');

		$submit		= (isset($_POST['submit'])) ? true : false;
		$usernames	= array_map('trim', explode("\n", utf8_normalize_nfc(request_var('usernames', '', true))));
		$action 	= request_var('action', '');
		$point		= request_var('point', 0);

		$form_name = 'acp_rep_change_point';
		add_form_key($form_name);

		// to do; Add log,

		if ($submit)
		{
			if (!check_form_key($form_name))
			{
				trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
			}

			include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
			$user_id_ary = array();
			user_get_id_name($user_id_ary, $usernames);

			if (!sizeof($user_id_ary))
			{
				trigger_error($user->lang['RP_WRONG_USERNAMES']. adm_back_link($this->u_action));
			}

			if ($action == 'add')
			{
				$reputation->add_point($user_id_ary, $point);
			}

			else if ($action == 'subtract')
			{
				$reputation->subtract_point($user_id_ary, $point);
			}

			else
			{
				$reputation->alter_point($user_id_ary, $point);
			}
			
			trigger_error($user->lang['RP_ALTER_SUCCESS']. adm_back_link($this->u_action));
		}

		$this->tpl_name = 'acp_rep_change_point';
		$this->page_title = 'ACP_MANAGE_RANKS';

		$form_name = 'acp_rep_change_point';
		add_form_key($form_name);

		$template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'U_FIND_USERNAME'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=acp_change_point&amp;field=usernames')
			)
		);
	}
}

?>