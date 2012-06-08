<?php
/**
 * Class to handle dispatching of events.
 *
 * @package 	Joomla.Framework
 * @subpackage	Events
 */

if (!class_exists('JDispatcher')) {
	class JDispatcher
	{
		/**
		 * Returns a reference to the global Event Dispatcher object, only creating it
		 * if it doesn't already exist.
		 *
		 * @return	JDispatcher	The EventDispatcher object.
		 * @since	1.5
		 */
		public static function getInstance()
		{
			static $instance;

			if (!is_object($instance)) {
				$instance = new JDispatcher();
			}

			return $instance;
		}

		/**
		 * Triggers an event by dispatching arguments to all observers that handle
		 * the event and returning their return values.
		 *
		 * @param string $event The event name
		 * @param array	$args An array of arguments
		 * @return array An array of results from each function call
		 */
		public function trigger($event, $args = null)
		{
			global $_MAMBOTS;
			return $_MAMBOTS->trigger($event, $args, false);
		}
	}
}
?>