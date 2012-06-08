<?php
/**
 * Joomla! Cache base object
 *
 * @package 	Joomla.Framework
 * @subpackage	Cache
 */

if (!class_exists('JCache')) {
	class JCache
	{
		/**
		 * Storage Handler
		 * @var		object
		 */
		public $_handler;

		/**
		 * Cache Options
		 * @var		array
		 */
		public $_options;

		/**
		 * Constructor
		 *
		 * @param	array	$options	options
		 */
		public function __construct($options)
		{
			$this->_options =& $options;
		}

		/**
		 * Returns a reference to a cache adapter object, always creating it
		 *
		 * @param	string	$type	The cache object type to instantiate
		 * @param	array	$options	The cache object options
		 * @return	object	A JCache object
		 */
		public static function &getInstance($type = 'output', $options = array())
		{
			$instance = new JCache($options);
			return $instance;
		}

		/**
		 * Clean cache for a group
		 *
		 * @return boolean True on success, false otherwise
		 */
		public function clean()
		{
			mosCache::cleanCache($this->_options['defaultgroup']);
			return true;
		}

		/**
		 * Executes a cacheable callback if not found in cache else returns cached output and result
		 *
		 * @param   mixed   $callback   Callback or string shorthand for a callback
		 * @param   array   $args   Callback arguments
		 * @param   bool  $id
		 * @return  mixed   Result of the callback
		 */
		public function get( $callback, $args, $id = false )
		{
			array_unshift($args, $callback);
			$cache = mosCache::getCache($this->_options['defaultgroup']);
			return call_user_func_array(array($cache, 'call'), $args);
		}
	}
}
?>