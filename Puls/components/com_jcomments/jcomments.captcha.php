<?php
/**
 * JComments - Joomla Comment System
 *
 * CAPTCHA - Automatic test to tell computers and humans apart
 *
 * @version 2.3
 * @package JComments
 * @filename jcomments.captcha.php
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

class JCommentsCaptcha
{
	public static function check($code)
	{
		@session_start();
		return (($code != '') && ($code == $_SESSION['comments-captcha-code']));
	}

	public static function destroy()
	{
		unset($_SESSION['comments-captcha-code']);
	}

	public static function image()
	{
		// small hack to allow captcha display even if any notice or warning occurred
		$length = ob_get_length();
		if ($length !== false || $length > 0) {
			while (@ob_end_clean());
			if (function_exists('ob_clean')) {
				@ob_clean();
			}
		}

		@session_start();

		if (!class_exists('KCAPTCHA')) {
			require_once(JCOMMENTS_LIBRARIES.DS.'kcaptcha'.DS.'kcaptcha.php');
		}

		$captcha = new KCAPTCHA();
		$_SESSION['comments-captcha-code'] = $captcha->getKeyString();
		exit;
	}
}
?>