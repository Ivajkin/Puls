<?php
/**
 * JComments - Joomla Comment System
 *
 * Service functions for JComments system plugin
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

/**
 * JComments System Plugin Helper
 * 
 * @package JComments
 */
class JCommentsSystemPluginHelper
{
	public static function getBaseUrl()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mosConfig_live_site;
			$url = $mosConfig_live_site;
		} else {
			$url = JURI::root(true);
		}
		return $url;
	}

	public static function getCoreJS()
	{
		return JCommentsSystemPluginHelper::getBaseUrl() . '/components/com_jcomments/js/jcomments-v2.3.js?v=8';
	}

	public static function getAjaxJS()
	{
		return JCommentsSystemPluginHelper::getBaseUrl().'/components/com_jcomments/libraries/joomlatune/ajax.js?v=4';
	}

	public static function getCSS($isRTL = false, $template = '')
	{
		if (empty($template)) {
			$config = JCommentsCfg::getInstance();
			$template = $config->get('template');
		}

		$cssName = $isRTL ? 'style_rtl.css' : 'style.css';
		$cssFile = $cssName . '?v=21';

		if (JCOMMENTS_JVERSION == '1.0') {
			$cssUrl = JCommentsSystemPluginHelper::getBaseUrl().'/components/com_jcomments/tpl/'.$template.'/'.$cssFile;
		} else {
			$app = JCommentsFactory::getApplication('site');
			$cssPath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/com_jcomments/'.$template.'/'.$cssName;
			$cssUrl = JURI::root(true).'/templates/'.$app->getTemplate().'/html/com_jcomments/'.$template.'/'.$cssFile;

			if (!is_file($cssPath)) {
				$cssPath = JPATH_SITE . '/components/com_jcomments/tpl/'.$template.'/'.$cssName;
				$cssUrl = JURI::root(true) . '/components/com_jcomments/tpl/'.$template.'/'.$cssFile;
				if ($isRTL && !is_file($cssPath)) {
					$cssUrl = '';
				}
			}
		}

		return $cssUrl;
	}
}
?>