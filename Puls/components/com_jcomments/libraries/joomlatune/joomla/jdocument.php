<?php
/**
 * Joomla! JDocument object
 *
 * @package 	Joomla.Framework
 * @subpackage	Document
 */

if (!class_exists('JDocument')) {
	class JDocument
	{
		public static function getInstance($type = 'html', $attributes = array())
		{
			static $instance;

			if (!is_object($instance)) {
				$instance = new JDocument();
			}

			return $instance;
		}

		public function getType()
		{
			return 'html';
		}

		public function setTitle($title)
		{
			global $mainframe;
			$mainframe->setPageTitle($title);
		}

		public function setDescription($description)
		{
			global $mainframe;
			$mainframe->appendMetaTag('description', $description);
		}

		public function setMetaData($name, $content, $http_equiv = false)
		{
			global $mainframe;
			$mainframe->appendMetaTag($name, $content);
		}

		public function addHeadLink($href, $relation, $relType = 'rel', $attribs = array())
		{
			global $mainframe;

			if (is_array($attribs)) {
				$a = array();
				foreach ($attribs as $k => $v) {
					$a[] = $k . '="' .$v .'"';
				}
				$attribs = implode(" ", $a);
			} else {
				$attribs = "";
			}

			$mainframe->addCustomHeadTag('<link href="'.$href.'" '.$relType.'="'.$relation.'" '.$attribs.' />');
		}

		public function addScript($url, $type="text/javascript")
		{
			global $mainframe;
			$mainframe->addCustomHeadTag('<script src="'.$url.'" type="'.$type.'"></script>');
		}

		public function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array())
		{
			global $mainframe;
			$mainframe->addCustomHeadTag('<link href="'.$url.'" rel="stylesheet" type="'.$type.'" />');
		}
	}
}
