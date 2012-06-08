<?php
/**
 * JComments blacklist table
 */
class JCommentsTableBlacklist extends JoomlaTuneDBTable
{
	/** @var int Primary key */
	var $id = null;
	/** @var string */
	var $ip = null;
	/** @var int */
	var $userid = null;
	/** @var datetime */
	var $created = 0;
	/** @var int */
	var $created_by = null;
	/** @var datetime */
	var $expire = 0;
	/** @var string */
	var $reason = null;
	/** @var string */
	var $notes = null;
	/** @var boolean */
	var $checked_out = 0;
	/** @var datetime */
	var $checked_out_time = 0;
	/** @var string */
	var $editor = '';

	/**
	 * @param JDatabase $db A database connector object
	 * @return void
	 */
	function JCommentsTableBlacklist(&$db)
	{
		parent::__construct('#__jcomments_blacklist', 'id', $db);
	}
}