<?php
/**
 * JComments - Joomla Comment System
 *
 * Backend Installer
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

require_once (dirname(__FILE__).DS.'install'.DS.'helpers'.DS.'installer.php');
require_once (dirname(__FILE__).DS.'install'.DS.'helpers'.DS.'database.php');

class JCommentsInstaller
{
	public static function postInstall()
	{
		$app = JCommentsFactory::getApplication('administrator');
		$db = JCommentsFactory::getDBO();

		$installer = new HTML_JCommentsInstaller();

		JCommentsInstallerHelper::extractJCommentsPlugins();

		// create database tables
		if (JCOMMENTS_JVERSION == '1.0') {
			$sql = dirname(__FILE__).DS.'install'.DS.'sql'.DS.'install.mysql.nonutf8.sql';
			JCommentsInstaller::executeSQL($sql);

			// collation synchronization (for MySQL 4.1.2 or higher)
			if (version_compare(preg_replace('~\-.+?$~', '', $db->getVersion()), '4.1.2') >= 0) {
				JCommentsInstallerDatabaseHelper::setupCollation();
			}

		} else {
			$config = JFactory::getConfig();
			$app = JFactory::getApplication();

			@ob_start();

			if ($config->getValue('config.legacy')) {
				$installSQL = dirname(__FILE__).DS.'install'.DS.'sql'.DS.'install.mysql.utf8.sql';
				JCommentsInstaller::executeSQL($installSQL);
			}

			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');

			$jomSocialRuleSrc = dirname(__FILE__).DS.'install'.DS.'xml'.DS.'jomsocial_rule.xm';
			$jomSocialRuleDst = JCOMMENTS_BASE.DS.'jomsocial_rule.xml';
			if (!is_file($jomSocialRuleDst)) {
				JFile::copy($jomSocialRuleSrc, $jomSocialRuleDst);
			}

			if (is_dir(JCOMMENTS_BASE.DS.'languages')) {
				JFolder::delete(JCOMMENTS_BASE.DS.'languages');
			}
			
			if (is_file(JCOMMENTS_BASE.DS.'plugins'.DS.'plugins.zip')) {
				JFile::delete(JCOMMENTS_BASE.DS.'plugins'.DS.'plugins.zip');
			}

			@ob_end_clean();

			$app->setUserState('com_installer.message', '');
			$app->setUserState('com_installer.extension_message', '');
			$app->setUserState('com_installer.redirect_url', '');
		}

		$jxml10 = dirname(__FILE__).DS.'jcomments10.xml';
		$jxml15 = dirname(__FILE__).DS.'jcomments15.xml';
		$jxml = dirname(__FILE__).DS.'jcomments.xml';

		if (is_file($jxml10)) {
			@rename($jxml10, $jxml);
		} else if (is_file($jxml15)) {
			@rename($jxml15, $jxml);
			
			// fix version in xml manifest file
			if (JCOMMENTS_JVERSION == '1.7') {
				$contents = str_replace('1.5.0', '1.7.0', implode('', file($jxml)));
				$fh = fopen($jxml, 'w');
				if ($fh) {
					fputs($fh, $contents);
					fclose($fh);
				}
			}
		}
		unset($jxml10, $jxml15, $jxml);

		if (JCOMMENTS_JVERSION != '1.7') {
			// remove files from previous version
			$files = array(
					 dirname(__FILE__).DS.'admin.jcomments.subcription.php'
					 , dirname(__FILE__).DS.'table'.DS.'custombbcodes.php'
					 , JCOMMENTS_BASE.DS.'model'.DS.'index.html'
					 , JCOMMENTS_BASE.DS.'model'.DS.'jcomments.php'
					 , JCOMMENTS_BASE.DS.'model'
					 );
			foreach ($files as $file) {
				if (is_file($file)) {
					@unlink($file);
				} else if (is_dir($file)) {
					@rmdir($file);
				}
			}
		}

		// small stuff for future update system
		$db->setQuery('SELECT `version` FROM `#__jcomments_version`');
		$version = $db->loadResult();

		require_once(dirname(__FILE__).DS.'version.php');
		$jcommentsVersion = new JCommentsVersion();
		$currentVersion = $jcommentsVersion->getVersion();
		$currentDate = date('Y-m-d H:i:s');

		if (empty($version)) {
			$db->setQuery("INSERT IGNORE INTO `#__jcomments_version` (`version`,`installed`) VALUES ('$currentVersion', '$currentDate')");
			@$db->query();
			// if version isn't specified - we think that it was 2.2.0.0 or earlier...
			$version = '2.2.0.0';
		} else {
			$db->setQuery("UPDATE `#__jcomments_version` SET `version` = '$currentVersion', `updated` = '$currentDate';");
			@$db->query();
		}

		// install content plugin
		$result = JCommentsInstallerHelper::installPlugin(
			'Content - JComments',
			'jcomments',
			'content'
		);
		$installer->addMessage(JText::_('A_INSTALL_PLUGIN_CONTENT'), $result);

		// install search plugin
		$result = JCommentsInstallerHelper::installPlugin(
			'Search - JComments',
			'jcomments',
			'search'
		);
		$installer->addMessage(JText::_('A_INSTALL_PLUGIN_SEARCH'), $result);

		// install system plugin
		$result = JCommentsInstallerHelper::installPlugin(
			'System - JComments',
			'jcomments',
			'system'
		);
		$installer->addMessage(JText::_('A_INSTALL_PLUGIN_SYSTEM'), $result);

		// install editor buttons
		$result = JCommentsInstallerHelper::installPlugin(
			'Editor Button - JComments ON',
			'jcommentson',
			'editors-xtd',
			array('jcommentson.gif')
		);

		$result = $result && JCommentsInstallerHelper::installPlugin(
			'Editor Button - JComments OFF',
			'jcommentsoff',
			'editors-xtd',
			array('jcommentsoff.gif')
		);
		$installer->addMessage(JText::_('A_INSTALL_PLUGIN_EDITORS_XTD'), $result);

		if (JCOMMENTS_JVERSION != '1.0') {
			// install user plugin
			$result = JCommentsInstallerHelper::installPlugin(
				'User - JComments',
				'jcomments',
				'user'
			);
			$installer->addMessage(JText::_('A_INSTALL_PLUGIN_USER'), $result);
		}

		// Fix component menu icon
		if (JCOMMENTS_JVERSION == '1.0') {
			$menuiconpath = $app->getCfg('absolute_path').DS.'includes'.DS.'js'.DS.'ThemeOffice';
			$adminIconsPath = '../administrator/components/com_jcomments/assets';

			if (is_writable($menuiconpath)) {
				$currentIconsPath = dirname(__FILE__).DS.'images';

				ob_start();
				$res1 = @copy($currentIconsPath.DS.'icon-16-jcomments.png', $menuiconpath.DS.'icon-16-jcomments.png');
				$res2 = @copy($currentIconsPath.DS.'icon-16-import.png', $menuiconpath.DS.'icon-16-import.png');
				$res3 = @copy($currentIconsPath.DS.'icon-16-settings.png', $menuiconpath.DS.'icon-16-settings.png');
				$res4 = @copy($currentIconsPath.DS.'icon-16-smiles.png', $menuiconpath.DS.'icon-16-smiles.png');
				$res5 = @copy($currentIconsPath.DS.'icon-16-comments.png', $menuiconpath.DS.'icon-16-comments.png');
				$res6 = @copy($currentIconsPath.DS.'icon-16-subscriptions.png', $menuiconpath.DS.'icon-16-subscriptions.png');
				$res7 = @copy($currentIconsPath.DS.'icon-16-custombbcodes.png', $menuiconpath.DS.'icon-16-custombbcodes.png');
				$res8 = @copy($currentIconsPath.DS.'icon-16-blacklist.png', $menuiconpath.DS.'icon-16-blacklist.png');
				ob_end_clean();

				$result = $res1 && $res2 && $res3 && $res4 && $res5 && $res6 && $res7 && $res8;

				if ($result && is_file($menuiconpath.DS.'jcomments16x16.png')) {
					$adminIconsPath = 'js/ThemeOffice';
				}
			}

			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-jcomments.png' " . "\n WHERE admin_menu_link='option=com_jcomments'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-comments.png', name='" . JText::_('A_SUBMENU_COMMENTS') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=comments'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-settings.png', name='" . JText::_('A_SUBMENU_SETTINGS') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=settings'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-smiles.png', name='" . JText::_('A_SUBMENU_SMILES') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=smiles'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-subscriptions.png', name='" . JText::_('A_SUBMENU_SUBSCRIPTIONS') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=subscriptions'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-custombbcodes.png', name='" . JText::_('A_SUBMENU_CUSTOM_BBCODE') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=custombbcodes'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-blacklist.png', name='" . JText::_('A_SUBMENU_BLACKLIST') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=blacklist'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-import.png', name='" . JText::_('A_SUBMENU_IMPORT') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=import'");
			@$db->query();
			$db->setQuery("UPDATE #__components SET admin_menu_img='$adminIconsPath/icon-16-jcomments.png', name='" . JText::_('A_SUBMENU_ABOUT') . "'" . "\n WHERE admin_menu_link='option=com_jcomments&task=about'");
			@$db->query();
		}


		if (version_compare($version, '2.3.0', 'le')) {
			// update db tables
			if (JCommentsInstallerDatabaseHelper::upgradeStructure()) {
				$installer->addMessage(JText::_('A_INSTALL_UPGRADE_TABLES'), true);
			}
			JCommentsInstallerDatabaseHelper::updateJoomGallery();

			if (JCOMMENTS_JVERSION != '1.7') {
				$db->setQuery("UPDATE `#__jcomments_settings` SET `value` = REPLACE(`value`, 'Unregistered', 'Public') ");
				$db->query();
				$db->setQuery("UPDATE `#__jcomments_settings` SET `value` = REPLACE(`value`, 'Super Administrator', 'Super Users') ");
				$db->query();
				$db->setQuery("UPDATE `#__jcomments_custom_bbcodes` SET `button_acl` = REPLACE(`button_acl`, 'Unregistered', 'Public') ");
				$db->query();
				$db->setQuery("UPDATE `#__jcomments_custom_bbcodes` SET `button_acl` = REPLACE(`button_acl`, 'Super Administrator', 'Super Users') ");
				$db->query();
			}
		}

		if (isset($_COOKIE['jcommentsadmin_group'])) {
			if (!headers_sent()) {
				setcookie('jcommentsadmin_group', '', time() - 3600, '/');
			}
			unset($_COOKIE['jcommentsadmin_group']);
		}

		$db->setQuery("SELECT `name`, `value` FROM `#__jcomments_settings`");
		$paramsList = $db->loadObjectList('name');

		if (count($paramsList) == 0) {
			$defaultSettings = dirname(__FILE__).DS.'install'.DS.'sql'.DS.'settings.sql';
			JCommentsInstaller::executeSQL($defaultSettings);
		} else {
			JCommentsInstaller::checkParam($paramsList, 'delete_mode', '0');
			JCommentsInstaller::checkParam($paramsList, 'can_publish_for_my_object', '');
			JCommentsInstaller::checkParam($paramsList, 'can_edit_for_my_object', '');
			JCommentsInstaller::checkParam($paramsList, 'can_delete_for_my_object', '');
			JCommentsInstaller::checkParam($paramsList, 'enable_blacklist', '0');
			JCommentsInstaller::checkParam($paramsList, 'can_ban', 'Administrator,Super Administrator');
			JCommentsInstaller::checkParam($paramsList, 'feed_limit', '100');
			JCommentsInstaller::checkParam($paramsList, 'report_reason_required', '1');

			if ((version_compare($version, '2.2.0.0') <= 0)) {
				$smilesPath = DS . 'components' . DS . 'com_jcomments' . DS . 'images' . DS . 'smiles' . DS;
				JCommentsInstaller::checkParam($paramsList, 'smiles_path', $smilesPath, true);
			}
		}
		unset($paramsList);

		if (JCOMMENTS_JVERSION == '1.7') {
			// TODO remove this hack
			JCommentsInstallerHelper::fixComponentName();
		}

		JCommentsInstallerHelper::fixUsergroups();

		$joomfish = $app->getCfg('absolute_path').DS.'components'.DS.'com_joomfish'.DS.'joomfish.php';
		if (is_file($joomfish) || JCommentsMultilingual::isEnabled()) {
			JCommentsInstaller::upgradeLanguages();
		}

		$db->setQuery("SELECT COUNT(*) FROM `#__jcomments_custom_bbcodes`;");
		$cnt = $db->loadResult();
		if ($cnt == 0) {
			$sql = dirname(__FILE__).DS.'install'.DS.'sql'.DS.'custom_bbcodes.sql';
			JCommentsInstaller::executeSQL($sql);
		}
		JCommentsInstallerHelper::fixCustomBBCodeACL();

		$installer->showInstallLog();

		$cache = JCommentsFactory::getCache('com_jcomments');
		$cache->clean();
	}

	public static function checkParam( $list, $param, $value, $required = false )
	{
		$db = JCommentsFactory::getDBO();

		if (!isset($list[$param])) {
			$db->setQuery("INSERT INTO `#__jcomments_settings` VALUES ('', '', " . $db->Quote($param). ", ". $db->Quote($value) . ");");
			@$db->query();
		} else if ($required && $list[$param]->value == '') {
			$db->setQuery("UPDATE `#__jcomments_settings` SET `value` = " . $db->Quote($value) . " WHERE name = " . $db->Quote($param) . ";");
			@$db->query();
		}
	}

	public static function upgradeLanguages()
	{
		if (JCOMMENTS_JVERSION == '1.5') {
			$languages = JLanguage::getKnownLanguages(JPATH_SITE);
			$db = JFactory::getDBO();
			
			foreach ($languages as $language) {
				$backward = $language['backwardlang'];
				$tag = $language['tag'];
				
				if ($backward != '' && $tag != '') {
					$db->setQuery("UPDATE #__jcomments SET lang = '$tag' WHERE lang = '$backward'");
					$db->query();
				}
			}
		}
	}
	
	public static function executeSQL( $filename = '' )
	{
		if (is_file($filename)) {
			$buffer = file_get_contents($filename);
			
			if ($buffer === false) {
				return false;
			}
			
			$db = JCommentsFactory::getDBO();
			
			$queries = JCommentsInstaller::splitSql($buffer);
			foreach ($queries as $query) {
				$query = trim((string) $query);
				if ($query != '') {
					$db->setQuery($query);
					@$db->query();
				}
			}
		}
		return true;
	}

	/**
	 * Splits a string of queries into an array of individual queries
	 *
	 * @param string $queries The queries to split
	 * @return array queries
	 */
	public static function splitSql( $queries )
	{
		$start = 0;
		$open = false;
		$open_char = '';
		$end = strlen($queries);
		$query_split = array();
		for ($i = 0; $i < $end; $i++) {
			$current = substr($queries, $i, 1);
			if (($current == '"' || $current == '\'')) {
				$n = 2;
				while (substr($queries, $i - $n + 1, 1) == '\\' && $n < $i) {
					$n++;
				}
				if ($n % 2 == 0) {
					if ($open) {
						if ($current == $open_char) {
							$open = false;
							$open_char = '';
						}
					} else {
						$open = true;
						$open_char = $current;
					}
				}
			}
			if (($current == ';' && !$open) || $i == $end - 1) {
				$query_split[] = substr($queries, $start, ($i - $start + 1));
				$start = $i + 1;
			}
		}
		
		return $query_split;
	}
}

class HTML_JCommentsInstaller
{
	var $messages = array();

	function HTML_JCommentsInstaller()
	{
	}

	function addMessage( $message, $status = true )
	{
		$msg['text'] = $message;
		$msg['status'] = $status;
		$this->messages[] = $msg;
	}

	function showInstallLog()
	{
		$app = JCommentsFactory::getApplication('administrator');
		require_once(dirname(__FILE__).DS.'version.php');
		$version = new JCommentsVersion();

		if ((version_compare(phpversion(), '5.1.0') >= 0)) {
			date_default_timezone_set('UTC');
		}
	
		if (JCOMMENTS_JVERSION == '1.7') {
			JHtml::_('behavior.framework');
		}
?>
<script type="text/javascript">
<!--
<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function (task) {
	Joomla.submitform(task, document.getElementById('adminForm'));
};
<?php } else { ?>
function submitbutton(task)
{
	submitform(task);
}
<?php } ?>
//-->
</script>
<link rel="stylesheet" href="<?php echo $app->getCfg( 'live_site' ); ?>/administrator/components/com_jcomments/assets/style.css?v=<?php echo $version->getVersion(); ?>" type="text/css" />

<div id="jc">

<div class="jcomments-box">
<div class="m">

<table width="95%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50px"><img src="<?php echo $app->getCfg( 'live_site' ); ?>/administrator/components/com_jcomments/assets/icon-48-jcomments.png" border="0" alt="" /></td>
		<td><span class="componentname"><?php echo $version->getLongVersion(); ?></span>
		<span class="componentdate">[<?php echo $version->getReleaseDate(); ?>]</span><br />
		<span class="copyright">&copy; 2006-<?php echo date('Y'); ?> smart (<a href="http://www.joomlatune.ru" target="_blank">JoomlaTune.ru</a> | <a href="http://www.joomlatune.com" target="_blank">JoomlaTune.com</a>). <?php echo JText::_('A_ABOUT_COPYRIGHT');?><br /></span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
		if (count($this->messages)) {
?>
	<tr>
		<td>&nbsp;</td>
		<td><span class="installheader"><?php echo JText::_('A_INSTALL_LOG'); ?></span>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<ul>
<?php
				foreach ($this->messages as $message) {
					$class = $message['status'] ? 'status-ok' : 'status-error';
					$text  = $message['status'] ? JText::_('A_INSTALL_STATE_OK') : JText::_('A_INSTALL_STATE_ERROR');
?>
				<li><?php echo $message['text']; ?>: <span class="<?php echo $class; ?>"><?php echo $text; ?></span></li>
<?php
				}
?>
				<li><span class="status-ok"><strong><?php echo JText::_('A_INSTALL_COMPLETE'); ?></strong></span></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="center" style="text-align: right;">
			<div class="button-left"><div class="next"><a href="<?php echo $app->getCfg( 'live_site' ); ?>/administrator/<?php echo JCOMMENTS_INDEX; ?>?option=com_jcomments&task=settings"><?php echo JText::_('A_INSTALL_BUTTON_NEXT'); ?></a></div></div>
		</td>
	</tr>
<?php
		}
?>

</table>

</div>

</div>

<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_jcomments" />
<input type="hidden" name="task" value="" />
</form>
<?php
	}
}
?>