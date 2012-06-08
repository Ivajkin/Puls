<?php
/**
 * JComments object
 */
class JCommentsObjectInfo
{
	/** @var int */
	var $id = null;
	/** @var int */
	var $object_id = null;
	/** @var string */
	var $object_group = null;
	/** @var string */
	var $lang = null;
	/** @var string */
	var $title = null;
	/** @var string */
	var $link = null;
	/** @var int */
	var $access = null;
	/** @var int */
	var $userid = null;
	/** @var int */
	var $expired = null;
	/** @var datetime */
	var $modified = null;

	function JCommentsObjectInfo($src = null)
	{
		if ($src !== null && is_object($src)) {
			$vars = get_object_vars($this);
			foreach ($vars as $k => $v) {
				if (isset($src->$k)) {
					$this->$k = $src->$k;
				}
			}
		}
	}
}
