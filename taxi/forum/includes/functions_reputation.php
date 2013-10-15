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
if (!defined('IN_PHPBB'))
{
	exit;
}

define('REPUTATIONS_TABLE',			$table_prefix . 'reputations');
define('REPUTATIONS_RANKS_TABLE',	$table_prefix . 'reputations_ranks');

$reputation = new reputation();

class reputation
{

	function get_post_info($post_id)
	{
		global $db, $user;

		$sql = 'SELECT p.forum_id, u.user_id, u.user_hide_reputation
			FROM ' . POSTS_TABLE . ' p
			LEFT JOIN ' . USERS_TABLE . ' u ON (u.user_id = p.poster_id)
			WHERE p.post_id = ' . $post_id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!$row)
		{
			$user->add_lang('posting');
			trigger_error('NO_POST');;
		}

		return $row;
	}
	function get_user_reputation($user_id)
	{
		global $db;

		if (!is_array($user_id))
		{
			$user_id = array($user_id);
		}

		if (!sizeof($user_id))
		{
			return array();
		}

		$sql = 'SELECT user_id, user_reputation, user_hide_reputation
			FROM ' . USERS_TABLE . '
			WHERE ' . $db->sql_in_set('user_id', $user_id);
		$result = $db->sql_query($sql);

		$rep_data = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$rep_data[$row['user_id']] = $row;
		}

		return $rep_data;
	}

	function get_images($user_id)
	{
		global $config;

		$info = $this->grab_info($user_id);

		$points = $info['user_reputation'];
		$ranks = $this->get_rank($points, $info['username']);

		$images = 'neutral';

		if ($points > 0)
		{
			$images = 'pos';
		}

		if ($points < 0)
		{
			$images = 'neg';
		}

		$points = ($points < 0) ? ($points * -1) : $points;

		$repeat = $config['rp_block_per_points'] ? (floor($points / $config['rp_block_per_points']) + 1) : 0;
		if ($repeat > $config['rp_max_blocks'])
		{
			$repeat = ($config['rp_max_blocks']);
		}

		$block_img = '<img src="images/reputation/' . $images . '.gif" title="' . $ranks . '" alt="' . $ranks . '" />';
		$img = ($repeat) ? str_repeat($block_img, ($repeat)): $block_img;

		return $img;
	}

	function grab_info($user_id)
	{
		global $config, $db, $user;

		$sql = 'SELECT username, user_regdate, user_posts, user_reputation, user_hide_reputation, group_id
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . $user_id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$row['user_power'] = $this->get_rep_power($row['user_posts'], $row['user_regdate'], $row['user_reputation'], $row['group_id']);

		return $row;
	}

	function get_rep_power($user_posts, $user_regdate, $user_reputation, $user_group_id)
	{
		global $config, $db;
		$now = time();

		$user_power = 0;

		if ($config['rp_min_posts'] && ($user_posts >= $config['rp_min_posts']))
		{
			$user_power = 1;
			if ($config['rp_total_posts'])
			{
				$user_power += intval($user_posts / $config['rp_total_posts']);
			}

			if ($config['rp_membership_days'])
			{
				$user_power += intval(intval(($now - $user_regdate) / 86400) / $config['rp_membership_days']);
			}

			if ($config['rp_power_rep_point'])
			{
				$user_power += intval($user_reputation / $config['rp_power_rep_point']);
			}

			if ($config['rp_max_power'] && ($user_power > $config['rp_max_power']))
			{
				$user_power = $config['rp_max_power'];
			}
		}

		$sql = 'SELECT group_reputation_power
			FROM ' . GROUPS_TABLE . '
			WHERE group_id = ' . $user_group_id;
		$result = $db->sql_query($sql);
		$group_power = (int) $db->sql_fetchfield('group_reputation_power');
		$db->sql_freeresult($result);

		if ($group_power)
		{
			$user_power = $group_power;
		}

		return $user_power;
	}

	function get_rank($points, $username)
	{
		$rank = $this->rep_rank_cache();
		$user_rank = '';
		foreach ($rank as $ranks)
		{
			if ($points >= $ranks['rank_points'])
			{
				$user_rank = $ranks['rank_title'];
				break;
			}
		}

		return str_replace('{USERNAME}', $username, $user_rank);
	}

	function rep_rank_cache()
	{
		global $db, $cache;
		if (($rank = $cache->get('_rep_ranks')) === false)
		{
			$rank = array();
			$sql = 'SELECT rank_title, rank_points
				FROM ' . REPUTATIONS_RANKS_TABLE . '
				ORDER BY rank_points DESC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$rank[] = $row;
			}
			$db->sql_freeresult($result);

			$cache->put('_rep_ranks', $rank);
		}

		return $rank;
	}

	function viewtopic($forum_id)
	{
		global $template, $config;

		$forum_exc = array();
		if ($config['rp_forum_exclusions'])
		{
			$forum_exc = explode(',', $config['rp_forum_exclusions']);
		}

		if ($config['rp_display'] == 1)
		{
			$reputation_display = 'text';
		}

		else if ($config['rp_display'] == 2)
		{
			$reputation_display = 'block';
		}

		else
		{
			$reputation_display = 'both';
		}

		$template->assign_vars(array(
			'S_REPUTATION'		=> (!in_array($forum_id, $forum_exc) && $config['rp_enable']) ? true : false,
			'S_REP_DISPLAY'		=> $reputation_display,
		));
	}

	function reputation_row($poster_id, $post_id, $reputation_cache)
	{
		global $auth, $phpbb_root_path, $phpEx, $user;

		if ($poster_id == ANONYMOUS)
		{
			$reputation_row = array();
		}

		else
		{
			$reputation_row = array(
				'S_USER_REPUTATION'	=> (!$reputation_cache[$poster_id]['user_hide_reputation']) ? true : false,
				'S_GIVE_REPUTATION'	=> ($auth->acl_get('u_rp_give') && $poster_id != $user->data['user_id']) ? true : false,
				'S_GIVE_NEGATIVE'	=> ($auth->acl_get('u_rp_give_negative')) ? true : false,
				'REPUTATION_TEXT'	=> $reputation_cache[$poster_id]['user_reputation'],
				'REPUTATION_BLOCK'	=> $this->get_images($poster_id),
				'U_VIEW_REP' 		=> ($auth->acl_get('u_rp_view_comment') || ($auth->acl_get('m_rp_moderate')) || $poster_id == $user->data['user_id']) ? append_sid("{$phpbb_root_path}viewreputation.$phpEx", 'id=' . $poster_id) : '',
				'U_ADD_POS' 		=> append_sid("{$phpbb_root_path}reputation.$phpEx", 'p=' . $post_id),
				'U_ADD_NEG' 		=> append_sid("{$phpbb_root_path}reputation.$phpEx", 'p=' . $post_id . '&amp;mode=negative'),
			);
		}

		return $reputation_row;
	}

	function add_point($user_id, $point)
	{
		global $db;

		if (!is_array($user_id))
		{
			$user_id = array($user_id);
		}

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET user_reputation = user_reputation + ' . $point . '
			WHERE ' . $db->sql_in_set('user_id', $user_id);
		$db->sql_query($sql);

		$this->check_point($user_id, $mode='maximum');

		return true;

	}

	function subtract_point($user_id, $point)
	{
		global $db;

		if (!is_array($user_id))
		{
			$user_id = array($user_id);
		}

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET user_reputation = user_reputation - ' . $point . '
			WHERE ' . $db->sql_in_set('user_id', $user_id);
		$db->sql_query($sql);

		$this->check_point($user_id, $mode='minimum');

		return true;
	}

	function alter_point($user_id, $point)
	{
		global $db;

		if (!is_array($user_id))
		{
			$user_id = array($user_id);
		}

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET user_reputation = ' . $point . '
			WHERE ' . $db->sql_in_set('user_id', $user_id);
		$db->sql_query($sql);

		return true;
	}

// check for minimum and maximum point
	function check_point($user_id, $mode)
	{
		global $db, $config;

		$sql_where = ($mode == 'maximum' ? 'user_reputation >' . $config['rp_maximum_point'] : 'user_reputation <' . $config['rp_minimum_point']);

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET user_reputation = ' . ($mode == 'maximum' ? $config['rp_maximum_point'] : $config['rp_minimum_point']) . '
			WHERE ' . $sql_where . '
				AND ' . $db->sql_in_set('user_id', $user_id);
		$db->sql_query($sql);

		return true;
	}

	function give_point($from, $username, $to, $point, $mode, &$data)
	{
		global $db;

		$sql_data = array(
		'rep_from'			=> $from,
		'username'			=> $username,
		'rep_to'			=> $to,
		'rep_post_id'		=> $data['post_id'],
		'rep_point'			=> ($mode == 'negative') ? $point * -1 : $point,
		'rep_time'			=> $data['time'],
		'bbcode_bitfield'	=> $data['bitfield'],
		'bbcode_uid'		=> $data['uid'],
		'enable_bbcode'     => $data['bbcode'],
		'enable_urls'       => $data['urls'],
		'enable_smilies'    => $data['smilies'],
		'rep_comment'		=> $data['comment'],
		'rep_ip_address'	=> $data['ip_address']
		);

		$db->sql_query('INSERT INTO ' . REPUTATIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_data));

		if ($mode == 'negative')
		{
			$this->subtract_point($to, $point);
		}

		else
		{
			$this->add_point($to, $point);
		}

		return true;
	}

	function view_profile($user_posts, $user_regdate, $user_reputation, $user_group_id, $user_hide_reputation)
	{
		global $template, $config, $phpbb_root_path, $phpEx;

		$template->assign_vars(array(
			'REPUTATION'	=> $user_reputation,
			'S_REPUTATION'	=> ($config['rp_enable'] && !$user_hide_reputation) ? true : false,
			'REP_POWER'		=> $this->get_rep_power($user_posts, $user_regdate, $user_reputation, $user_group_id),
		));
	}

	function delete_user($user_id)
	{
		global $db;
		$db->sql_query('DELETE FROM ' . REPUTATIONS_TABLE . ' WHERE rep_to = ' . $user_id);

		$db->sql_query('UPDATE ' . REPUTATIONS_TABLE . ' SET rep_from = 1 WHERE rep_from = ' . $user_id);
		return true;
	}

	function display_comment($user_id, $mode, $start, $limit = false, $pagination = false)
	{
		global $db, $user, $template, $auth, $config, $phpbb_root_path, $phpEx;
		$user->add_lang('mods/reputation_mod');

		$limit = (!$limit) ? $config['topics_per_page'] : $limit;
		$forum_ary = array_unique(array_keys($auth->acl_getf('!f_read', true)));

		$sql_array = array(
			'SELECT'	=> 'p.post_id, p.post_subject, p.forum_id, u.username, u.user_id, u.user_colour, r.*',

			'FROM'		=> array(
				REPUTATIONS_TABLE	=> 'r',
				USERS_TABLE			=> 'u'
				),

			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'r.rep_post_id = p.post_id'
				)
			),

			'WHERE'		=> 'r.rep_to =' . $user_id . '
				AND u.user_id = r.rep_from',

			'ORDER_BY'	=> 'r.rep_id DESC'
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $limit, $start);
		while ($row = $db->sql_fetchrow($result))
		{
			$row['bbcode_options'] = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
			(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) +
			(($row['enable_urls']) ? OPTION_FLAG_LINKS : 0);

			$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/neutral.gif" alt="" title="' . $row['rep_point'] . '" />';

			if ($row['rep_point'] < 0)
			{
				$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/neg.gif" alt="" title="' . $row['rep_point'] . '" />';
			}

			if ($row['rep_point'] > 0)
			{
				$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/pos.gif" alt="" title="' . $row['rep_point'] . '" />';
			}

			$template->assign_block_vars('reputation_row', array(
				'FROM'			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour'], $row['username']),
				'POINT_IMG'		=> $point_img,
				'TIME'			=> $user->format_date($row['rep_time']),
				'POST_SUBJECT'	=> ($row['post_subject'] && (!in_array($row['forum_id'], $forum_ary))) ? censor_text($row['post_subject']) : false,
				'U_POST'		=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;p=' . $row['post_id']) . '#p' . $row['post_id'],
				'U_DELETE'		=> append_sid("{$phpbb_root_path}viewreputation.$phpEx", 'action=delete&amp;rep_id=' . $row['rep_id']),
				'COMMENT'		=> generate_text_for_display($row['rep_comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']))
			);
		}
		$db->sql_freeresult($result);

		if ($pagination)
		{
			$sql = 'SELECT COUNT(rep_id) AS total
				FROM ' . REPUTATIONS_TABLE . '
				WHERE rep_to = ' . $user_id;
			$result = $db->sql_query($sql);
			$total = (int) $db->sql_fetchfield('total');
			$db->sql_freeresult($result);

			$pagination = generate_pagination(append_sid("{$phpbb_root_path}viewreputation.$phpEx", "id=$user_id"), $total, $limit, $start, true);

			$template->assign_vars(array(
				'S_ON_PAGE'		=> on_page($total, $limit, $start),
				'PAGINATION'	=> $pagination,
				'TOTAL'			=> $total,
			));
		}
	}
// Here's for add ons

}
?>