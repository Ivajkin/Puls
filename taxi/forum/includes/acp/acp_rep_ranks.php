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
* based on acp_ranks.php by phpBB team
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
class acp_rep_ranks
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('mods/reputation_mod');

		// Set up general vars
		$action = request_var('action', '');
		$action = (isset($_POST['add'])) ? 'add' : $action;
		$action = (isset($_POST['save'])) ? 'save' : $action;
		$rank_id = request_var('id', 0);

		$this->tpl_name = 'acp_rep_ranks';
		$this->page_title = 'ACP_MANAGE_RANKS';

		$form_name = 'acp_rep_ranks';
		add_form_key($form_name);

		switch ($action)
		{
			case 'save':

				if (!check_form_key($form_name))
				{
					trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
				}
				$rank_title = utf8_normalize_nfc(request_var('title', '', true));
				$min_points = request_var('min_points', 0);

				if (!$rank_title)
				{
					trigger_error($user->lang['RP_NO_RANK_TITLE'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql_ary = array(
					'rank_title'		=> $rank_title,
					'rank_points'		=> $min_points
				);
				
				if ($rank_id)
				{
					$sql = 'UPDATE ' . REPUTATIONS_RANKS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . " WHERE rank_id = $rank_id";
					$message = $user->lang['RP_RANK_UPDATED'];

					add_log('admin', 'LOG_RP_RANK_UPDATED', $rank_title);
				}
				else
				{
					$sql = 'INSERT INTO ' . REPUTATIONS_RANKS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
					$message = $user->lang['RP_RANK_ADDED'];

					add_log('admin', 'LOG_RP_RANK_ADDED', $rank_title);
				}
				$db->sql_query($sql);
				
				$cache->destroy('_rep_ranks');

				trigger_error($message . adm_back_link($this->u_action));

			break;

			case 'delete':

				if (!$rank_id)
				{
					trigger_error($user->lang['RP_MUST_SELECT_RANK'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				if (confirm_box(true))
				{
					$sql = 'SELECT rank_title
						FROM ' . REPUTATIONS_RANKS_TABLE . '
						WHERE rank_id = ' . $rank_id;
					$result = $db->sql_query($sql);
					$rank_title = (string) $db->sql_fetchfield('rank_title');
					$db->sql_freeresult($result);

					$sql = 'DELETE FROM ' . REPUTATIONS_RANKS_TABLE . "
						WHERE rank_id = $rank_id";
					$db->sql_query($sql);
					
					$cache->destroy('_rep_ranks');

					add_log('admin', 'LOG_RP_RANK_REMOVED', $rank_title);
				}
				else
				{
					confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
						'i'			=> $id,
						'mode'		=> $mode,
						'rank_id'	=> $rank_id,
						'action'	=> 'delete',
					)));
				}

			break;

			case 'edit':
			case 'add':
			
				$data = $ranks = array();
				
				$sql = 'SELECT *
					FROM ' . REPUTATIONS_RANKS_TABLE . '
					ORDER BY rank_points ASC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					if ($action == 'edit' && $rank_id == $row['rank_id'])
					{
						$ranks = $row;
					}
				}
				$db->sql_freeresult($result);

				$template->assign_vars(array(
					'S_EDIT'			=> true,
					'U_BACK'			=> $this->u_action,
					'U_ACTION'			=> $this->u_action . '&amp;id=' . $rank_id,
					'RANK_TITLE'		=> (isset($ranks['rank_title'])) ? $ranks['rank_title'] : '',
					'MIN_POINTS'		=> (isset($ranks['rank_points'])) ? $ranks['rank_points'] : 0)
				);
				
				return;

			break;
		}
	
		$template->assign_vars(array(
			'U_ACTION'		=> $this->u_action)
		);

		$sql = 'SELECT *
			FROM ' . REPUTATIONS_RANKS_TABLE . '
			ORDER BY rank_points ASC, rank_title ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('ranks', array(

				'RANK_TITLE'		=> $row['rank_title'],
				'MIN_POINTS'		=> $row['rank_points'],
				'U_EDIT'			=> $this->u_action . '&amp;action=edit&amp;id=' . $row['rank_id'],
				'U_DELETE'			=> $this->u_action . '&amp;action=delete&amp;id=' . $row['rank_id'])
			);	
		}
		$db->sql_freeresult($result);

	}
}

?>