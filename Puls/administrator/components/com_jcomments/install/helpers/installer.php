<?php
/**
 * JComments - Joomla Comment System
 *
 * Service functions for JComments Installer
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2008 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

class JCommentsInstallerHelper
{
	public static function deleteFile( $file )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			@unlink($file);
		} else {
			jimport('joomla.filesystem.file');
			return JFile::delete($file);
		}
		return true;
	}

	public static function copyFile( $src, $dst )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			return @copy($src, $dst);
		} else {
			jimport('joomla.filesystem.file');
			return JFile::copy($src, $dst);
		}
	}

	public static function createFolder($path = '', $mode = 0755)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			@mkdir($path);
			@chmod($path, 0755);
		} else {
			jimport('joomla.filesystem.folder');
			return JFolder::create($path, $mode);
		}
		return true;
	}

	public static function deleteFolder( $folder )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			@rmdir($folder);
		} else {
			jimport('joomla.filesystem.folder');
			return JFolder::delete($folder);
		}
		return true;
	}

	public static function installPlugin($name, $element, $folder, $files = array())
	{
		static $ordering, $plugins;

		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe, $database;
			$db = $database;
			$pluginsTable = '#__mambots';
			$pluginsSrcPath = $mainframe->getCfg('absolute_path').'/administrator/components/com_jcomments/install/plugins/'.$folder;
			$pluginsDstPath = $mainframe->getCfg('absolute_path').'/mambots/'.$folder;
			$pluginsQuery = "INSERT INTO `#__mambots` (`name`, `element`, `folder`, `access`, `ordering`, `published` ) VALUES ('%s', '%s', '%s', 0, %s, 1);";
			$pluginsFileExt = '.j10.php';
			$pluginsManifestExt = '.x10';
		} else if (JCOMMENTS_JVERSION == '1.5') {
			$db = JFactory::getDBO();
			$pluginsTable = '#__plugins';
			$pluginsSrcPath = JPATH_ROOT.'/administrator/components/com_jcomments/install/plugins/'.$folder;
			$pluginsDstPath = JPATH_ROOT.'/plugins/'.$folder;
			$pluginsQuery = "INSERT INTO `#__plugins` (`name`, `element`, `folder`, `access`, `ordering`, `published` ) VALUES ('%s', '%s', '%s', 0, %s, 1);";
			$pluginsFileExt = '.php';
			$pluginsManifestExt = '.x15';
		} else if (JCOMMENTS_JVERSION == '1.7') {
			$db = JFactory::getDBO();
			$pluginsTable = '#__extensions';
			$pluginsSrcPath = JPATH_ROOT.'/administrator/components/com_jcomments/install/plugins/'.$folder;
			$pluginsDstPath = JPATH_ROOT.'/plugins/'.$folder.'/'.$element;
			$pluginsQuery = "INSERT INTO `#__extensions` (`type`, `name`, `element`, `folder`, `access`, `ordering`, `enabled`) VALUES ('plugin', '%s', '%s', '%s', 1, %s, 1);";
			$pluginsFileExt = '.php';
			$pluginsManifestExt = '.x17';

			$name = 'plg_' . $folder . '_' . $element;
		} else {
			return false;
		}

		if (empty($ordering)) {
			$db->setQuery("SELECT folder, MAX(ordering) as maxid FROM `$pluginsTable` GROUP BY `folder`;");
			$ordering = @$db->loadObjectList('folder');
		}

		if (empty($plugins)) {
			$db->setQuery("SELECT CONCAT(folder, '.', element) as plugin FROM `$pluginsTable` WHERE `folder` <> '' order by `folder`;");
			$plugins = $db->loadResultArray();
		}

		if (!is_dir($pluginsDstPath . DS)) {
			self::createFolder($pluginsDstPath . DS);
		} elseif (!is_writable($pluginsDstPath . DS)) {
			return false;
		}

		$result = true;

		$files[] = $element . $pluginsFileExt;
		$files[] = $element . $pluginsManifestExt;

		foreach ($files as $file) {
			$dstFileName = $pluginsDstPath . DS . $file;
			$srcFileName = $pluginsSrcPath . DS . $file;

			$dstFileName = str_replace($pluginsManifestExt, '.xml', $dstFileName);
			$dstFileName = str_replace($pluginsFileExt, '.php', $dstFileName);

			if (is_file($dstFileName)) {
				self::deleteFile($dstFileName);
			}

			$result = $result && self::copyFile($srcFileName, $dstFileName);
		}

		if ($result && !in_array($folder . '.' . $element, $plugins)) {
			$maxId = isset($ordering[$folder]) ? intval($ordering[$folder]->maxid) + 1 : 0;
			$db->setQuery(sprintf($pluginsQuery, $name, $element, $folder, $maxId));
			$db->query();
			$ordering++;
		}

		return $result;
	}

	public static function uninstallPlugin($element, $folder, $files = array())
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe, $database;
			$db = $database;
			$pluginsTable = '#__mambots';
			$pluginsPath = $mainframe->getCfg('absolute_path').'/mambots/'.$folder;
			$pluginsRemoveFolder = false;
		} else if (JCOMMENTS_JVERSION == '1.5') {
			$db = JFactory::getDBO();
			$pluginsTable = '#__plugins';
			$pluginsPath = JPATH_ROOT.'/plugins/'.$folder;
			$pluginsRemoveFolder = false;
		} else if (JCOMMENTS_JVERSION == '1.7') {
			$db = JFactory::getDBO();
			$pluginsTable = '#__extensions';
			$pluginsPath = JPATH_ROOT.'/plugins/'.$folder.'/'.$element;
			$pluginsRemoveFolder = true;
		}

		$files[] = $element . '.php';
		$files[] = $element . '.xml';

		foreach ($files as $file) {
			$dstFileName = $pluginsPath . DS . $file;
			if (is_file($dstFileName)) {
				self::deleteFile($dstFileName);
			}
		}

		if ($pluginsRemoveFolder && is_dir($pluginsPath)) {
			self::deleteFolder($pluginsPath);
		}

		$db->setQuery("DELETE FROM `$pluginsTable` WHERE `element` = " . $db->Quote($element) . " and `folder` = " . $db->Quote($folder));
		$db->query();
	}

	public static function extractArchive( $source, $destination )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe;
			require_once($mainframe->getCfg('absolute_path').'/administrator/includes/pcl/pclzip.lib.php');
			require_once($mainframe->getCfg('absolute_path').'/administrator/includes/pcl/pclerror.lib.php');
			$zipFile = new PclZip($source);
			define('OS_WINDOWS', intval(substr(PHP_OS, 0, 3) == 'WIN'));
			return $zipFile->extract(PCLZIP_OPT_PATH, $destination);
		} else {
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.archive');
			jimport('joomla.filesystem.path');

			$destination = JPath::clean($destination);
			$source = JPath::clean($source);
			return JArchive::extract($source, $destination);
		}
	}

	public static function extractJCommentsLibraryConvert()
	{
		$source = JCOMMENTS_BASE . '/libraries/convert/convert.zip';
		$destination = JCOMMENTS_BASE . '/libraries/convert/';
		return self::extractArchive($source, $destination);
	}

	public static function extractJCommentsPlugins()
	{
		$source = JCOMMENTS_BASE . '/plugins/plugins.zip';
		$destination = JCOMMENTS_BASE . '/plugins/';
		return self::extractArchive($source, $destination);
	}

	public static function fixComponentName()
	{
		$db = JFactory::getDBO();
		$db->setQuery("UPDATE `#__extensions` SET `name` = 'com_jcomments' WHERE LOWER(`name`) = 'jcomments' AND `element` = 'com_jcomments';");
		$db->query();
	}

	public static function fixUsergroups()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $database;
			$db = $database;
		} else {
			$db = JFactory::getDBO();
		}

		require_once (JCOMMENTS_HELPERS.'/user.php');
		$groups = JCommentsUserHelper::getUserGroups();

		$where = array();
		foreach ($groups as $group) {
			$where[] = "`value` LIKE '%" . $group->name . "%'";
		}

		if (count($where)) {
			$db->setQuery("SELECT * FROM #__jcomments_settings WHERE `name` NOT IN ('forbidden_names', 'smiles_path') AND (" . implode(' OR ', $where) . ")");
			$rows = $db->loadObjectList();

			foreach ($rows as $row) {
				$values = explode(',', $row->value);

				foreach ($groups as $group) {
					for ($i = 0, $n = count($values); $i < $n; $i++) {
						if ($values[$i] == $group->name) {
							$values[$i] = $group->id;
						}
					}
				}

				$row->value = implode(',', $values);
				
				$query = "UPDATE #__jcomments_settings"
					. " SET `value` = " . $db->Quote($row->value)
					. " WHERE `component` = " . $db->Quote($row->component)
					. " AND `lang` = " . $db->Quote($row->lang)
					. " AND `name` = " . $db->Quote($row->name)
					;
				$db->setQuery($query);
				$db->query();
			}
		}
	}

	public static function fixCustomBBCodeACL()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $database;
			$db = $database;
		} else {
			$db = JFactory::getDBO();
		}

		require_once (JCOMMENTS_HELPERS.'/user.php');
		$groups = JCommentsUserHelper::getUserGroups();

		$where = array();
		foreach ($groups as $group) {
			$where[] = "`button_acl` LIKE '%" . $group->name . "%'";
		}

		if (count($where)) {
			$db->setQuery("SELECT * FROM `#__jcomments_custom_bbcodes` WHERE " . implode(' OR ', $where));
			$rows = $db->loadObjectList();

			foreach ($rows as $row) {
				$values = explode(',', $row->button_acl);

				foreach ($groups as $group) {
					for ($i = 0, $n = count($values); $i < $n; $i++) {
						if ($values[$i] == $group->name) {
							$values[$i] = $group->id;
						}
					}
				}

				$row->button_acl = implode(',', $values);
				
				$query = "UPDATE `#__jcomments_custom_bbcodes`"
					. " SET `button_acl` = " . $db->Quote($row->button_acl)
					. " WHERE `name` = " . $db->Quote($row->name)
					;
				$db->setQuery($query);
				$db->query();
			}
		}
	}
}
?>