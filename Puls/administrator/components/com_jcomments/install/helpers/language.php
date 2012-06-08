<?php
/**
 * JComments - Joomla Comment System
 *
 * Converts language files to Joomla 1.0/Joomla 1.5 format
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2009-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

class JCommentsInstallerLanguageHelper
{
	public static function convertLanguages10()
	{
		global $mainframe;

		$joomlaLanguagesPath = $mainframe->getCfg('absolute_path').'/language';
		$componentPath = $mainframe->getCfg('absolute_path').'/components/com_jcomments';

		require_once ($componentPath . '/libraries/convert/utf8.class.php');
		require_once ($componentPath . '/libraries/joomlatune/filesystem.php');
		require_once ($componentPath . '/libraries/joomlatune/language.tools.php');

		$codeMap = JoomlaTuneLanguageTools::getLanguageCodes();

		$joomlaLanguageFiles = JoomlaTuneFS::readDirectory($joomlaLanguagesPath, '[a-z]+\.xml', false, false);
		$joomlaLanguages = array();
		foreach ($joomlaLanguageFiles as $file) {
			$joomlaLanguages[] = str_replace('.xml', '', $file);
		}

		$path = $componentPath . '/languages';
		$filter = '[a-z]{2}-[A-Z]{2}\.com_jcomments\.ini';
		$files = JoomlaTuneFS::readDirectory($path, $filter, false, true);
		$files2 = JoomlaTuneFS::readDirectory($path . '/administrator', $filter, false, true);

		$files = array_merge($files, $files2);

		foreach ($files as $file) {
			$m = array();
			preg_match('#([a-z]{2}-[A-Z]{2})#', (string) $file, $m);

			$code = $m[0];

			$language = isset($codeMap[$code]) ? $codeMap[$code][0] : '';
			$charset = isset($codeMap[$code]) ? $codeMap[$code][1] : 'iso-8859-1';

			if ($language != '' && in_array($language, $joomlaLanguages)) {
				if (defined('_ISO2')) {
					$charset = strtolower(_ISO2);
					if ($charset == 'utf-8' || $charset == 'utf8') {
						$newFile = str_replace( $code . '.com_jcomments.ini', $language . '.ini', $file);
						@copy((string) $file, $newFile);
					} else {
						JCommentsInstallerLanguageHelper::_convertFile10($file, $code, $charset, $language);
					}
				} else {
					JCommentsInstallerLanguageHelper::_convertFile10($file, $code, $charset, $language);
				}
			}
		}
		unset($codeMap);
	}

	public static function convertLanguages15()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$config = JFactory::getConfig();

		if ($config->getValue('config.legacy')) {
			$dirs = array( JCOMMENTS_BASE.DS.'languages' => JPATH_ROOT.DS.'language'
					, JCOMMENTS_BASE.DS.'languages'.DS.'administrator' => JPATH_ROOT.DS.'administrator'.DS.'language'
					);

			foreach ($dirs as $srcLanguageDir => $dstLanguageDir) {
				if (is_writable($dstLanguageDir)) {
					$languages = JFolder::files($srcLanguageDir, '\.ini', false, false);
					foreach ($languages as $language) {
						$languageCode = substr((string)$language, 0, 5);
						$languageDir = $dstLanguageDir . DS . $languageCode;
						if (is_dir($languageDir)) {
							JFile::move($srcLanguageDir . DS . $language, $languageDir . DS . $language);
						}
					}
					unset($languages);
				}
			}
		}

		// frontend language files
		$files = JFolder::files(JPATH_SITE . DS . 'language', '\.(com_jcomments|plg_search_jcomments)\.ini', true, true);
		foreach ($files as $file) {
			$content = JFile::read($file);
			$content = self::_replaceCommentsAndQuotes($content);

			if (strpos($content, 'DATETIME_FORMAT') !== false) {
				$content = str_replace('Y-m-d', '%Y-%m-%d', $content);
				$content = str_replace('d-m-Y', '%d-%m-%Y', $content);
				$content = str_replace('d/m/Y', '%d/%m/%Y', $content);
				$content = str_replace('d.m.Y', '%d.%m.%Y', $content);
				$content = str_replace('H:i', '%H:%M', $content);
			}
			JFile::write($file, $content);
		}

		// backend language files
		$files = JFolder::files(JPATH_BASE . DS . 'language', '(com_jcomments|plg_content_jcomments|plg_search_jcomments|plg_system_jcomments|plg_user_jcomments|plg_editors-xtd_jcommentsoff|plg_editors-xtd_jcommentson)(\.sys)?\.ini', true, true);
		foreach ($files as $file) {
			if (preg_match('#\.sys\.ini#is', $file)) {
				if (preg_match('#\.com_jcomments\.sys\.ini#is', $file)) {
					$content = JFile::read($file);
					$content = self::_replaceCommentsAndQuotes($content);
					$content = str_replace('COM_JCOMMENTS_', 'COM_JCOMMENTS.', $content);
					$content = str_replace('COM_JCOMMENTS.COMMENTS_VIEW_', 'COM_JCOMMENTS_COMMENTS_VIEW_', $content);
					$content = str_replace('COM_JCOMMENTS.BASIC_', 'COM_JCOMMENTS_BASIC_', $content);
					JFile::write(str_replace('.sys.', '.menu.', $file), $content);
				}
				JFile::delete($file);
			} else {
				$content = JFile::read($file);
				$content = self::_replaceCommentsAndQuotes($content);
				JFile::write($file, $content);
			}
		}
	}

	protected function _replaceCommentsAndQuotes($str)
	{
		$str = preg_replace('#^\;#ismu', '#', $str);
		$str = preg_replace('#\"#ismu', '', $str);
		$str = preg_replace('#_QQ_#ismu', '"', $str);
		return $str;
	}

	protected function _convertFile10($inFile, $code, $charset, $language)
	{
		$oldLines = file($inFile);
		$txt = implode('', $oldLines);
		$txt = str_replace('&lt;', '&amp;lt;', $txt);
		$txt = str_replace('&gt;', '&amp;gt;', $txt);
		$txt = str_replace("’", "'", $txt);

		if (($code == 'de-DE') || ($code == 'fr-FR') || ($code == 'it-IT')) {
			$txt = htmlentities(utf8_decode($txt));
		} else {
			$converter = new JCommentsUtf8($charset);
			$txt = $converter->utf8ToStr($txt);
		}

		$txt = self::_replaceCommentsAndQuotes($txt);

		$txt = str_replace('&lt;', '<', $txt);
		$txt = str_replace('&gt;', '>', $txt);
		$txt = str_replace('&quot;', '"', $txt);
		$txt = str_replace('&amp;quot;', '&quot;', $txt);
		$txt = str_replace('&amp;gt;', '&gt;', $txt);
		$txt = str_replace('&amp;lt;', '&lt;', $txt);
		$txt = str_replace('PLG_SEARCH_JCOMMENTS_COMMENTS=', 'COMMENTS=', $txt);
		$txt = str_replace('Note : All ini files need to be saved as UTF-8 - No BOM', 'Note: this file need to be saved in ' . $charset . ' charset', $txt);

		$inFile = str_replace( $code . '.com_jcomments.ini', $language . '.ini', $inFile);

		$fp = fopen($inFile , "w");
		if ($fp) {
			fputs($fp, $txt);
			fclose($fp);
		}
	}
}
?>