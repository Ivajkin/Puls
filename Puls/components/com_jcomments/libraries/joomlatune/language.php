<?php
/**
 * Languages/translation handler class
 *
 * @version 1.0
 * @package JoomlaTune.Framework
 * @author Dmitry M. Litvinov
 * @copyright 2008
 */

// Check for double include
if (!defined('JOOMLATUNE_LANGUAGE')) {
	
	define('JOOMLATUNE_LANGUAGE', 1);
	
	/**
	 * Languages/translation handler class
	 * 
	 * @package JoomlaTune.Framework
	 * @subpackage Language
	 */
	class JoomlaTuneLanguage
	{
		var $currentLanguage = null;
		var $languages = null;
		
		/**
		 * Class constructor, overridden in descendant classes.
		 *
		 */
		function __construct()
		{
			$this->languages = array(array());
		}
		
		/**
		 * Returns a reference to the global JoomlaTuneLanguage object, only creating it
		 * if it doesn't already exist.
		 *
		 * @param	string $language
		 * @return	JoomlaTuneLanguage $instance
		 */
		public static function getInstance( $language = null )
		{
			/**
			 * $instance JoomlaTuneLanguage
			 */
			static $instance = null;
			if (!is_object($instance)) {
				$instance = new JoomlaTuneLanguage();
			}
			
			if (isset($language)) {
				$instance->load($language, '');
				$instance->setLanguage($language);
			}
			return $instance;
		}
		
		/**
		 * Return current language
		 *
		 * @return string
		 */
		function getLanguage()
		{
			return $this->currentLanguage;
		}
		
		/**
		 * Set current language
		 *
		 * @param string $language
		 * @return void
		 */
		function setLanguage( $language )
		{
			$this->currentLanguage = trim($language);
		}
		
		/**
		 * Unload language variables for given language
		 *
		 * @param string $language
		 * @return void
		 */
		function unload( $language )
		{
			$this->languages[$language] = array();
		}
		
	
		/**
		 * Translates a string into the current language
		 *
		 * @param	string $string The string to translate
		 * @param	boolean	$jsSafe		Make the result javascript safe
		 * @return string
		 */
		function _( $string, $jsSafe = false )
		{
			if (isset($this->languages[$this->currentLanguage])) {
				$key = trim($string);
				$key = strtoupper($key);
				
				if (isset($this->languages[$this->currentLanguage][$key])) {
					$string = $this->languages[$this->currentLanguage][$key];
				}
			}
			
			if ($jsSafe) {
				$string = addslashes($string);
			}
			
			return $string;
		}

		/**
		 * Loads a single language file and appends the results to the existing strings
		 *
		 * @param string $extension    The extension for which a language file should be loaded
		 * @param string $basePath The basepath to use
		 * @param string $language The language to load, default null for the current language
		 * @param bool $reload Flag that will force a language to be reloaded if set to true
		 * @param bool $default Flag that force the default language to be loaded if the current does not exist
		 * @return bool
		 */
		function load($extension = '', $basePath = '', $language = null, $reload = false, $default = true)
		{
			$fileName = $language . '.ini';
			$pathFile = $basePath.DS.$fileName;

			if (!is_file($pathFile)) {
				$fileName = 'english.ini';
				$pathFile = $basePath.DS.$fileName;
			}
			
			if (is_file($pathFile)) {
				if (!isset($this->languages[$language])) {
					$this->languages[$language] = array();
					$this->setLanguage($language);
				}
				
				$lines = file($pathFile);
				foreach ($lines as $line) {
					if (!preg_match('/^(#|;)/', $line)) {
						$s = preg_split('/=/', $line, 2);
						if (count($s) >= 2) {
							$this->languages[$language][strtoupper(trim($s[0]))] = trim($s[1]);
						}
					}
				}
				unset($lines);
			}
		}

		/**
		 * Determines is a key exists
		 *
		 * @param string $string The key to check
		 * @return boolean  True, if the key exists
		 */
		function hasKey($string)
		{
			$key = strtoupper($string);
			return (isset($this->languages[$this->currentLanguage][$key]));
		}
	}
}
?>