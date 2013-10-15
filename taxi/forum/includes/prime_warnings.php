<?php
/**
*
* @package phpBB3
* @version $Id: prime_warnings.php,v 1.0.3 2008/08/04 02:00:00 primehalo Exp $
* @copyright (c) 2008 Ken Innes IV
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
* The PRIME_WARNING_CAP define can be changed to any positive integer.
* More than this number of warnings will be displayed as a single card
* graphic with the number of warnings the card represents displayed inside.
*/
if (!defined('PRIME_WARNING_CAP'))
{
	define('PRIME_WARNING_CAP', 1);
}

/*
* If set to true, only administrators and moderators will see the number
* of warnings a user has been issued.
*/
if (!defined('PRIME_WARNING_PRIVATE'))
{
	define('PRIME_WARNING_PRIVATE', false);
}

/**
* Check to see if this file has already been included.
*/
if (!defined('INCLUDES_PRIME_WARNINGS'))
{
	define('INCLUDES_PRIME_WARNINGS', true);

	function prime_warnings($user_warnings = 0, $loop_name = 'warnings', $user_id = null)
	{
		global $template, $auth, $user;

		if (PRIME_WARNING_PRIVATE)
		{
			$self	= ($user_id && !empty($user->data) && $user_id == $user->data['user_id']) ? true : false;
			$mod	= ($auth && ($auth->acl_get('m_') || $auth->acl_get('a_'))) ? true : false;
			if (!($self || $mod))
			{
				return;
			}
		}

		if ($user_warnings)
		{
			//$template->assign_var('WARNINGS', $user_warnings);
			if ($user_warnings > PRIME_WARNING_CAP && PRIME_WARNING_CAP > 0)
			{
				$template->assign_block_vars($loop_name, array('COUNT' => $user_warnings));
			}
			else
			{
				for ($warning_count = 0; $warning_count < $user_warnings; $warning_count++)
				{
					$template->assign_block_vars($loop_name, array('COUNT' => ''));
				}
			}
		}
	}
}
?>