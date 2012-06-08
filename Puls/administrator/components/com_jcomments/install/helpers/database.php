<?php
/**
 * JComments - Joomla Comment System
 *
 * Service functions for JComments Installer
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2009-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

class JCommentsInstallerDatabaseHelper
{
        protected static function getTableFieldNames($tableName)
        {
		$fields = array();

		$db = JCommentsFactory::getDBO();
		$db->setQuery("SHOW FIELDS FROM `$tableName`;");
		$rows = $db->loadObjectList();

		if (is_array($rows)) {
			foreach ($rows as $row) {
				$fields[] = strtolower($row->Field);
			}
			unset($rows);
		}

		return $fields;
        }

	public static function setupCollation()
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT COUNT(*) FROM `#__jcomments`;");
		$cnt = $db->loadResult();
		
		// only if where are no comments
		if ($cnt == 0) {
			$collation = '';
			
			$db->setQuery("SHOW FULL COLUMNS FROM `#__content` LIKE 'title';");
			$rows = $db->loadObjectList();
			
			if (count($rows)) {
				$collation = $rows[0]->Collation;
			}
			
			if ($collation == '') {
				$db->setQuery("SHOW VARIABLES LIKE 'collation_database';");
				$rows = $db->loadObjectList();
				$collation = count($rows) ? $rows[0]->Value : '';
			}
			
			// if collation not determined - skip correction
			if ($collation != '') {
				$tables = array('#__jcomments'
						, '#__jcomments_settings'
						, '#__jcomments_subscriptions'
						, '#__jcomments_objects'
						, '#__jcomments_reports');

				foreach ($tables as $table) {
					$db->setQuery("SHOW FULL COLUMNS FROM `$table`;");
					$columns = $db->loadObjectList();
				
					if (is_array($columns)) {
						$text = array();
						foreach ($columns as $column) {
							if (strpos($column->Type, 'text') !== false || strpos($column->Type, 'char') !== false) {
								$text[] = "CHANGE `" . $column->Field . "` `" . $column->Field . "` " . $column->Type . " COLLATE " . $collation . " NOT NULL DEFAULT ''";
							}
						}
						$db->setQuery("ALTER TABLE `$table` " . implode(', ', $text) . ";");
						@$db->query();
					}
					$db->setQuery("ALTER TABLE `$table` COLLATE $collation;");
					@$db->query();
				}
			}
		}
	}

	public static function upgradeStructure()
	{
		$db = JCommentsFactory::getDBO();
		
		$fields = self::getTableFieldNames('#__jcomments');

		if (count($fields)) {
			// 2.2.0.0
			if (!in_array('level', $fields)) {
				$db->setQuery("ALTER TABLE `#__jcomments` ADD `level` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `parent`;");
				@$db->query();
				$db->setQuery("ALTER TABLE `#__jcomments` ADD INDEX `idx_level`(`level`);");
				@$db->query();
			}

			if (!in_array('path', $fields)) {
				$db->setQuery("ALTER TABLE `#__jcomments` ADD `path` VARCHAR(255) NOT NULL DEFAULT '' AFTER `parent`;");
				@$db->query();
				$db->setQuery("ALTER TABLE `#__jcomments` ADD INDEX `idx_path`(`path`,`level`);");
				@$db->query();
			}

			if (!in_array('source_id', $fields)) {
				$db->setQuery("ALTER TABLE `#__jcomments` ADD `source_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `source`;");
				@$db->query();
			}

			// 2.3
			if (!in_array('deleted', $fields)) {
				$db->setQuery("ALTER TABLE `#__jcomments` ADD `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `published`;");
				@$db->query();
			}

			if (!in_array('thread_id', $fields)) {
				$db->setQuery("ALTER TABLE `#__jcomments` ADD `thread_id` INT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `parent`;");
				@$db->query();
				$db->setQuery("ALTER TABLE `#__jcomments` ADD INDEX `idx_thread`(`thread_id`);");
				@$db->query();
			}

			unset($fields);
		}

		$db->setQuery("ALTER IGNORE TABLE `#__jcomments` CHANGE `isgood` `isgood` SMALLINT(5) NOT NULL DEFAULT '0';");
		@$db->query();
		$db->setQuery("ALTER IGNORE TABLE `#__jcomments` CHANGE `ispoor` `ispoor` SMALLINT(5) NOT NULL DEFAULT '0';");
		@$db->query();
		$db->setQuery("ALTER IGNORE TABLE `#__jcomments` CHANGE `ip` `ip` VARCHAR(39) NOT NULL DEFAULT '';");
		@$db->query();
		$db->setQuery("ALTER IGNORE TABLE `#__jcomments_votes` CHANGE `ip` `ip` VARCHAR(39) NOT NULL DEFAULT '';");
		@$db->query();
		$db->setQuery("ALTER IGNORE TABLE `#__jcomments_reports` CHANGE `ip` `ip` VARCHAR(39) NOT NULL DEFAULT '';");
		@$db->query();
		$db->setQuery("ALTER IGNORE TABLE `#__jcomments_blacklist` CHANGE `ip` `ip` VARCHAR(39) NOT NULL DEFAULT '';");
		@$db->query();

		$fields = self::getTableFieldNames('#__jcomments_subscriptions');

		if (count($fields)) {

			if (!in_array('source', $fields)) {
				$db->setQuery("ALTER IGNORE TABLE `#__jcomments_subscriptions` ADD `source` VARCHAR(255) NOT NULL DEFAULT '';");
				@$db->query();
				$db->setQuery("ALTER IGNORE TABLE `#__jcomments_subscriptions` ADD INDEX `idx_source`(`source`);");
				@$db->query();
			}

			unset($fields);
		}

		return true;
	}

	public static function updateJoomGallery()
	{
		if (JCOMMENTS_JVERSION != '1.0') {
			$db = JFactory::getDBO();

			$query = "SELECT COUNT(*) FROM `#__jcomments` WHERE object_id > 900000000 AND object_group = 'com_joomgallery'";
			$db->setQuery($query);
			$countComments = $db->loadResult();

			if ($countComments > 0) {
				$query = "UPDATE `#__jcomments`"
						. " SET object_id = 1, object_group = 'com_joomgallery_gallery'"
						. " WHERE object_id = 999999999 AND object_group = 'com_joomgallery';";
				$db->setQuery($query);
				$db->query();

				$query = "UPDATE `#__jcomments`"
						. " SET object_id = object_id - 900000000, object_group = 'com_joomgallery_category'"
						. " WHERE object_id >  900000000 AND object_group = 'com_joomgallery';";
				$db->setQuery($query);
				$db->query();
			}
		}
	}
}
?>