<?php
/**
*
* groups [English]
*
* @author idiotnesia pungkerz@gmail.com - http://www.phpbbindonesia.com
*
* @package language
* @version 0.3.1
* @copyright (c) 2008, 2009 phpbbindonesia
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'RP_ADD_POINTS'				=> 'Give good reputation point to',
	'RP_COMMENT'				=> 'Comment',
	'RP_COMMENTS'				=> 'Comments',
	'RP_DISABLED'				=> 'Sorry, but the board administrator has disabled this feature.',
	'RP_EMPTY_DATA'				=> 'This user has not received any reputation.',
	'RP_FROM'					=> 'From',
	'RP_GROUP_POWER'			=> 'Group reputation power',
	'RP_HIDE'					=> 'Hide my reputation point',
	'RP_NA'						=> 'n/a',
	'RP_NEGATIVE'				=> 'Negative',
	'RP_NO_COMMENT'				=> 'You cannot leave the comment field blank',
	'RP_POINTS'					=> 'Points',
	'RP_POSITIVE'				=> 'Positive',
	'RP_POWER'					=> 'Reputation power',
	'RP_SAME_POST'				=> 'You have already given reputation in this post',
	'RP_SELF'					=> 'You cannot give reputation to yourself',
	'RP_SENT'					=> 'Your reputation point has been sent successfully',
	'RP_SUBTRACT_POINTS'		=> 'Give bad reputation point to',
	'RP_SUCCESS_DELETE'			=> 'The reputation comment has been successfully deleted.',
	'RP_TIMES_LIMIT'			=> 'You cannot give reputation so soon after your last.',
	'RP_TITLE'					=> 'User Reputation Point',
	'RP_TOO_LONG_COMMENT'		=> 'Your comment contains %1$d characters. The maximum number of allowed characters is %2$d.',
	'RP_TOTAL_POINTS'			=> 'Reputation points',
	'RP_USER_DISABLED'			=> 'You are not allowed to give reputation point.',
	'RP_USER_SELF_DISABLED'		=> 'This user has disabled reputation feature.',
));

// Reputation settings
$lang = array_merge($lang, array(
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Here you can configure user reputation points settings.',
	'RP_BLOCK_PER_POINTS'		=> 'Block per points',
	'RP_BLOCK_PER_POINTS_EXPLAIN'	=> 'Add 1 block for every x reputation points.',
	'RP_DISABLE_COMMENT'		=> 'Disable reputation comment',
	'RP_DISPLAY'				=> 'Reputation points display',
	'RP_DISPLAY_BLOCK'			=> 'Block',
	'RP_DISPLAY_BOTH'			=> 'Both',
	'RP_DISPLAY_TEXT'			=> 'Text',
	'RP_ENABLE'					=> 'Enable user reputation points',
	'RP_FORCE_COMMENT'			=> 'Force user to enter comment',
	'RP_FORUM_EXCLUSIONS'		=> 'Forum Exclusions',
	'RP_FORUM_EXCLUSIONS_EXPLAIN'	=> 'Enter the forum ID to exclude separated with comma, eg. 3,4,6',
	'RP_MAXIMUM_POINT'			=> 'Maximum point',
	'RP_MAX_BLOCK'				=> 'Maximum block',
	'RP_MAX_BLOCK_EXPLAIN'		=> 'The maximum number of block displayed.',
	'RP_MAX_CHARS'				=> 'Maximum characters in comment',
	'RP_MAX_CHARS_EXPLAIN'		=> 'The number of characters allowed within a comment. Set to 0 for unlimited characters.',
	'RP_MAX_POWER'				=> 'Maximum reputation power',
	'RP_MAX_POWER_EXPLAIN'		=> 'Maximum reputation power allowed.',
	'RP_MEMBERSHIP_DAYS'		=> 'Membership days factor',
	'RP_MEMBERSHIP_DAYS_EXPLAIN'	=> 'User will gain 1 reputation power for every x number of days.',
	'RP_MINIMUM_POINT'			=> 'Minimum point',
	'RP_MIN_POSTS'				=> 'Minimum posts',
	'RP_MIN_POSTS_EXPLAIN'		=> 'Minimum posts required before having reputation power.',
	'RP_POWER'					=> 'Reputation power',
	'RP_POWER_REP_POINT'		=> 'Reputation points factor',
	'RP_POWER_REP_POINT_EXPLAIN'	=> 'User will gain 1 reputation power for every x number of reputation points.',
	'RP_RECENT_POINTS'			=> 'Recent reputation points',
	'RP_RECENT_POINTS_EXPLAIN'	=> 'The number of reputation points displayed in the user control panel.',
	'RP_TIME_LIMITATION'		=> 'Time limit',
	'RP_TIME_LIMITATION_EXPLAIN'	=> 'The minimum time passed before users are allowed to give another reputation point.',
	'RP_TOTAL_POINTS'			=> 'Reputation point',
	'RP_TOTAL_POSTS'			=> 'Posts factor',
	'RP_TOTAL_POSTS_EXPLAIN'	=> 'User will gain 1 reputation power for every x number of posts.',
	'RP_USER_SPREAD'			=> 'Reputation spread',
	'RP_USER_SPREAD_EXPLAIN'	=> 'Spread reputation to others before giving reputation to the same user.',
	'RP_USER_SPREAD_FIRST'		=> 'You must spread your reputation point to other users before giving to the same user.',
));

// Rank management
$lang = array_merge($lang, array(
	'ACP_REP_RANKS_EXPLAIN'		=> 'Using this form you can add, edit, view and delete ranks based on reputation points. ',
	'RP_ADD_RANK'				=> 'Add rank',
	'RP_MUST_SELECT_RANK'		=> 'You must select a rank',
	'RP_NO_RANK_TITLE'			=> 'You must specify a title for the rank',
	'RP_RANK_ADDED'				=> 'The rank was successfully added.',
	'RP_RANK_MINIMUM'			=> 'Minimum points',
	'RP_RANK_TITLE'				=> 'Title',
	'RP_RANK_UPDATED'			=> 'The rank was successfully updated.',
));

// Point management
$lang = array_merge($lang, array(
	'RP_ADD'					=> 'Add',
	'RP_ALTER_SUCCESS'			=> 'The user point was successfully updated.',
	'RP_CHANGE'					=> 'Change to',
	'RP_SUBTRACT'				=> 'Subtract',
	'RP_WRONG_USERNAMES'		=> 'Wrong usernames entered.',
));

// UMIL auto installer
$lang = array_merge($lang, array(
	'INSTALL_REPUTATION_POINT'				=> 'Install user reputation points',
	'INSTALL_REPUTATION_POINT_CONFIRM'		=> 'Are you ready to install user reputation points?',

	'REPUTATION_POINT'						=> 'User reputation points',
	'REPUTATION_POINT_EXPLAIN'				=> 'For support please visit <a href="http://www.phpbbindonesia.com">phpBB Indonesia</a>.',

	'TABLE_SYNC'							=> 'Table successfuly synchronized.',

	'UNINSTALL_REPUTATION_POINT'			=> 'Uninstall User reputation points',
	'UNINSTALL_REPUTATION_POINT_CONFIRM'	=> 'Are you ready to uninstall the User reputation points?  All settings and data saved by this mod will be removed!',
	'UPDATE_REPUTATION_POINT'				=> 'Update Test Mod',
	'UPDATE_REPUTATION_POINT_CONFIRM'		=> 'Are you ready to update the User reputation points?',
));

?>