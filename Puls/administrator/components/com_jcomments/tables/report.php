<?php
/**
 * JComments report table
 */
class JCommentsTableReport extends JoomlaTuneDBTable
{
	/** @var int Primary key */
	var $id = null;
	/** @var int */
	var $commentid = null;
	/** @var int */
	var $userid = null;
	/** @var string */
	var $name = null;
	/** @var string */
	var $reason = null;
	/** @var string */
	var $ip = null;
	/** @var datetime */
	var $date = null;
	/** @var int */
	var $status = null;

	/**
	 * @param JDatabase $db A database connector object
	 * @return void
	 */
	function JCommentsTableReport(&$db)
	{
		parent::__construct('#__jcomments_reports', 'id', $db);
	}
}