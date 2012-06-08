<?php
/**
 * JComments - Joomla Comment System
 *
 * Configuration loader class
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

class JCommentsCfg
{
	/**
	 * Associative array of configuration variables
	 * @var array
	 */
	var $_params = array();
	/**
	 * Last loaded language name
	 * @var string
	 */
	var $_current = '';

	/**
	 * Returns a reference to a language object.
	 * 
	 * @param string $language The language to use.
	 * @param string $component The component name.
	 * @return JCommentsCfg
	 */
	public static function getInstance($language='', $component = '')
	{
		static $instance = null;
		$app = JCommentsFactory::getApplication();

		if (JCOMMENTS_JVERSION == '1.7') {
			$multilingual_support = $app->isSite() && $app->getLanguageFilter();
		} else {
			$multilingual_support = ($app->getCfg('multilingual_support') == 1);
		}

		if (!is_object( $instance )) {
			$instance = new JCommentsCfg();
			if ($language == '') {
				$language = $multilingual_support ? JCommentsMultilingual::getLanguage() : '';
			}
			$instance->load($language, $component);
		} else {
			if ($language != $instance->_current && $instance->_current == '') {
				if ($language != '') {
					$instance->load($language, $component);
				} else {
					$language = $multilingual_support ? JCommentsMultilingual::getLanguage() : '';
					if ($language != '') {
						$instance->load($language, $component);
					}
				}
			}
		}

		return $instance;
	}

	/**
	 * Returns params names
	 *
	 * @return array
	 */
	public function getKeys()
	{
		return array_keys($this->_params);
	}

	public function get( $name, $default = '' )
	{
		return isset($this->_params[$name]) ? $this->_params[$name] : $default;
	}

	/**
	 * Fetches and returns a given variable as integer.
	 * This is currently only a proxy function for get().
	 *
	 * @param string	$name		Variable name
	 * @param int	$default	Default value if the variable does not exist
	 * @return int	Requested variable
	 */
	public function getInt( $name, $default = 0 )
	{
		return (int) $this->get($name, $default);
	}

	/**
	 * Sets a configuration variable
	 *
	 * @param	string $name The name of the variable
	 * @param	mixed  $value The value of the variable to set
	 * @return	void
	 */
	public function set( $name, $value )
	{
		$this->_params[$name] = $value;
	}

	/**
	 * Checks if value exists in list
	 *
	 * @param	string $name The name of the variable
	 * @param	mixed  $value The value of the variable to set
	 * @return	boolean
	 */
	public function check( $name, $value )
	{
		$v = $this->get($name);
		$va = explode(',', $v);
		if (is_array($va)) {
			return (in_array($value, $va));
		}
		return false;
	}

	/**
	 * Sets a configuration variable
	 *
	 * @param	string $lang The language name to use.
	 * @param	mixed  $component The component name to use
	 * @return	array An array of loaded configuration variables
	 */
	public static function _load( $lang = '', $component = '' )
	{
		$db = JCommentsFactory::getDBO();

		$where = array();

		if ($lang != '') {
			$where[] = "lang = '" . $db->getEscaped($lang) . "'";
		}

		if ($component != '') {
			$where[] = "component = '" . $db->getEscaped($component) . "'";
		}

		$query = "SELECT * FROM #__jcomments_settings"
				. (count($where) ? ("\nWHERE " . implode(' AND ', $where)) : "");

		$db->setQuery($query);
		$data = $db->loadObjectList();

		if (count($data) == 0) {
			$db->setQuery( "SELECT * FROM #__jcomments_settings WHERE lang='' AND component=''" );
			$data = $db->loadObjectList();
		}

		return $data;
	}

	/**
	 * Load configuration from DB and stores it into field _params
	 *
	 * @param string $lang The language to use.
	 * @param string $component The component name.
	 * @return void
	 */
	public function load( $lang = '', $component = '' )
	{
		if (JCOMMENTS_JVERSION == '1.5') {
			$jcfg = JFactory::getConfig();
			// temporary fix (eAccelerator won't reset cache)
			if ($jcfg->getValue('config.cache_handler', 'file') == 'eaccelerator') {
				$params = JCommentsCfg::_load($lang, $component);
			} else {
				$cache = JCommentsFactory::getCache('com_jcomments');
				$params = (array) $cache->get('JCommentsCfg::_load', array($lang, $component));
			}
		} else {
			$cache = JCommentsFactory::getCache('com_jcomments');
			$params = (array) $cache->get('JCommentsCfg::_load', array($lang, $component));
		}

		foreach ($params as $param) {
			if ('smiles' == $param->name) {
				if (!empty($param->value)) {
					$smileValues = explode("\n", $param->value);
					$this->_params['smiles'] = array();
					foreach ($smileValues as $v) {
						list ($code, $image) = explode("\t", $v);
						$this->_params['smiles'][$code] = $image;
					}
				} else {
					$this->_params['smiles'] = array();
				}
			} else if ('badwords' == $param->name) {
				if ('' != trim($param->value)) {
					$this->_params['badwords'] = explode("\n", $param->value);
				}
			} else {
				$this->_params[$param->name] = $param->value;
			}
		}

		if ($this->get('smiles_path') == '') {
			$this->set('smiles_path', '/components/com_jcomments/images/smiles/');
		}

		if ($this->get('enable_notification') == 0 || $this->check('notification_type', 2) == false) {
			$this->set('can_report', '');
		}

		if (!extension_loaded('gd') || !function_exists('imagecreatefrompng')) {
			if ($this->get('captcha_engine', 'kcaptcha') != 'recaptcha') {
				$this->set('enable_captcha', '');
			}
		}

		$this->_current = $lang;

		unset( $params );
	}
}
?>