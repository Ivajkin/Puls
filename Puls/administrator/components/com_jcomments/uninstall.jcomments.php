<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend uninstall handler
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

// include legacy class
if (defined('JPATH_ROOT')) {
	include_once (JPATH_ROOT.DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php');
	include_once (JPATH_ROOT.DS.'components'.DS.'com_jcomments'.DS.'jcomments.class.php');
	$language = JFactory::getLanguage();
	$language->load('com_jcomments');
} else {
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
	global $mainframe;
	include_once ($mainframe->getCfg('absolute_path').DS.'components'.DS.'com_jcomments'.DS.'jcomments.legacy.php');
	include_once ($mainframe->getCfg('absolute_path').DS.'components'.DS.'com_jcomments'.DS.'jcomments.class.php');
}

include_once (dirname(__FILE__).DS.'install'.DS.'helpers'.DS.'installer.php');

function com_uninstall()
{
	require_once(dirname(__FILE__).DS.'version.php');
	$version = new JCommentsVersion();
?>
<style type="text/css">
div#jc {width: 600px;margin: 0 auto;}
span.copyright {color: #777;display: block;margin-top: 12px;}
div#element-box span.componentname {color: #FF9900;font-family: Arial, Helvetica, sans-serif;font-size: 16px;font-weight: bold;}
div#element-box span.componentdate {color: #FF9900;font-family: Arial, Helvetica, sans-serif;font-size: 16px;font-weight: normal;}
div#element-box span.installheader {color: #FF9900;font-family: Arial, Helvetica, sans-serif;font-size: 16px;font-weight: bold;}
div#jc table td {padding: 0}
</style>

<div id="jc">

<div id="element-box">
<div class="t">
<div class="t">
<div class="t"></div>
</div>
</div>
<div class="m">

<table width="95%" border="0" cellpadding="0" cellspacing="0">
	<tr valign="top" align="left">
		<td width="50px"><img src="http://www.joomlatune.com/images/logo/jcomments.png" width="48" height="48" border="0" alt="" /></td>
		<td><span class="componentname"><?php echo $version->getLongVersion(); ?></span>
		<span class="componentdate">[<?php echo $version->getReleaseDate(); ?>]</span><br />
		<span class="copyright">&copy; 2006-<?php echo date('Y'); ?> smart (<a href="http://www.joomlatune.ru" target="_blank">JoomlaTune.ru</a> | <a href="http://www.joomlatune.com" target="_blank">JoomlaTune.com</a>). <?php echo JText::_('A_ABOUT_COPYRIGHT');?><br /></span></td>
	</tr>
	<tr valign="top" align="left">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>

		<tr valign="top" align="left">
			<td>&nbsp;</td>
			<td><span class="installheader"><?php echo JText::_('A_UNINSTALL_LOG'); ?></span></td>
		</tr>
		<tr valign="top" align="left">
			<td>&nbsp;</td>
			<td>
			<ul style="padding: 0 0 0 20px; margin: 0;">
<?php
	JCommentsInstallerHelper::uninstallPlugin('jcomments', 'content');
?>
			<li><?php echo JText::_('A_UNINSTALL_PLUGIN_CONTENT'); ?>: <span style="color: green">OK</span></li>
<?php
	JCommentsInstallerHelper::uninstallPlugin('jcomments', 'search');
?>
			<li><?php echo JText::_('A_UNINSTALL_PLUGIN_SEARCH'); ?>: <span style="color: green">OK</span></li>
<?php
	JCommentsInstallerHelper::uninstallPlugin('jcomments', 'system');
?>
			<li><?php echo JText::_('A_UNINSTALL_PLUGIN_SYSTEM'); ?>: <span style="color: green">OK</span></li>
<?php
	JCommentsInstallerHelper::uninstallPlugin('jcommentson', 'editors-xtd', array('jcommentson.gif'));
	JCommentsInstallerHelper::uninstallPlugin('jcommentsoff', 'editors-xtd', array('jcommentsoff.gif'));
?>
			<li><?php echo JText::_('A_UNINSTALL_PLUGIN_EDITORS_XTD'); ?>: <span style="color: green">OK</span></li>
<?php
	if (JCOMMENTS_JVERSION == '1.0') {
		global $mainframe;
		$app = $mainframe;
	} else {
		JCommentsInstallerHelper::uninstallPlugin('jcomments', 'user');
		$app = JFactory::getApplication('administrator');
	}

	// Clean all caches for components with comments
	if ($app->getCfg('caching') == 1) {
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT DISTINCT(object_group) AS name FROM #__jcomments");
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$cache = JCommentsFactory::getCache($row->name);
			$cache->clean();
		}
		unset($rows);
?>
			<li><?php echo JText::_('A_UNINSTALL_CLEAN_CACHE'); ?>: <span style="color: green">OK</span></li>
<?php
	}
?>
			<li><span style="color: green"><strong><?php echo JText::_('A_UNINSTALL_COMPLETE'); ?></strong></span></li>
			</ul>
			</td>
		</tr>
	</table>

	</div>
	<div class="b">
	<div class="b">
	<div class="b"></div>
	</div>
	</div>
	</div>

	</div>
<?php
	if (JCOMMENTS_JVERSION == '1.0') {
		global $mainframe;
		$componentPath = $mainframe->getCfg('absolute_path') . DS . 'components' . DS . 'com_jcomments';
		require_once ($componentPath . DS . 'libraries' . DS . 'joomlatune' . DS . 'filesystem.php');

		$files = JoomlaTuneFS::readDirectory($componentPath . DS . 'languages', '\.ini', false, true);
		foreach ($files as $file) {
			if (!preg_match('/[a-z]{2}-[A-Z]{2}\.com_jcomments/', (string) $file)) {
				@unlink((string) $file);
			}
		}
	}
}
?>