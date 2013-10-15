<?php
/**
*
* @author idiotnesia pungkerz@gmail.com - http://www.phpbbindonesia.com
*
* @package phpBB3
* @version 0.3.1
* @copyright (c) 2008, 2009 phpbbindonesia
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('mods/reputation_mod');

// Define initial vars
$id			= request_var('id', 0);
$rep_id		= request_var('rep_id', 0);
$action		= request_var('action', '');
$start		= request_var('start', 0);

$cancel		= (isset($_POST['cancel'])) ? true : false;

if ($cancel)
{
	$redirect = append_sid("{$phpbb_root_path}viewreputation.$phpEx", 'id=' . $id);
	redirect($redirect);
}

if (!$user->data['is_registered'])
{
	login_box();
}

if (!$auth->acl_get('u_rp_view_comment'))
{
	trigger_error('NO_AUTH');
}

$reputation->display_comment($id, 'viewreputation', $start, false, true);

if ($action == 'delete') 
{
	if (!$auth->acl_get('m_rp_moderate'))
	{
		trigger_error('NO_ADMIN');
	}
	
	$sql = 'SELECT rep_to, rep_point
		FROM ' . REPUTATIONS_TABLE . "
		WHERE rep_id = $rep_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if (!$row)
	{
		die();
	}
	if (confirm_box(true))
	{
		$sql = 'UPDATE ' . USERS_TABLE . '
			SET user_reputation = user_reputation - ' . $row['rep_point'] . '
			WHERE user_id = ' . $row['rep_to'];
		$db->sql_query($sql);
		
		$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . '
			WHERE rep_id = ' . $rep_id;
		$db->sql_query($sql);
		
		$redirect = append_sid("{$phpbb_root_path}viewreputation.$phpEx", 'id=' . $row['rep_to']);
		meta_refresh(3, $redirect);
		trigger_error('RP_SUCCESS_DELETE');
	}
	else
	{
		confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
			'id'			=> $row['rep_to'],
			'rep_id'		=> $rep_id))
		);
	}
}

$sql = 'SELECT username, user_reputation
	FROM ' . USERS_TABLE . '
	WHERE user_id = ' . $id;
$result = $db->sql_query($sql);
$reputation = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$template->assign_vars(array(
	'USERNAME'		=> $reputation['username'],
	'U_USER'		=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $id),
	'TOTAL_POINTS'	=> $reputation['user_reputation'],
	'S_MODERATE'	=> ($auth->acl_get('m_rp_moderate')) ? true : false,
	)
);

page_header($user->lang['RP_TITLE']); 

$template->set_filenames(array(
	'body' => 'viewreputation_body.html')
);

page_footer();

?>