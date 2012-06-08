<?php
/**
 * JComments - Joomla Comment System
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

/**
 * JComments Event Handler
 * 
 **/
class JCommentsEvent
{
	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the event and returning their return values.
	 *
	 * @param string $event The event name
	 * @param array $args An array of arguments
	 * @return array An array of results from each function call
	 */
	public static function trigger($event, $args = null)
	{
		static $pluginsLoaded = false;

		$result = array();
		$config = JCommentsFactory::getConfig();
		if ($config->getInt('enable_mambots') == 1) {
			if (!$pluginsLoaded) {
				JPluginHelper::importPlugin('jcomments');
				$pluginsLoaded = true;
			}

			$dispatcher = JDispatcher::getInstance();
			$result = $dispatcher->trigger($event, $args);
		}
		return $result;
	}
}
?>