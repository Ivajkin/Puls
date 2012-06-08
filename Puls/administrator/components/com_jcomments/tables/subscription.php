<?php
/**
 * JComments subscriptions table
 *
 */
class JCommentsTableSubscription extends JoomlaTuneDBTable
{
	/** @var int Primary key */
	var $id = null;
	/** @var int */
	var $object_id = null;
	/** @var string */
	var $object_group = null;
	/** @var string */
	var $lang = null;
	/** @var int */
	var $userid = null;
	/** @var string */
	var $name = null;
	/** @var string */
	var $email = null;
	/** @var string */
	var $hash = null;
	/** @var boolean */
	var $published = null;

	/**
	 * @param  JDatabase $db A database connector object
	 * @return void
	 */
	function JCommentsTableSubscription(&$db)
	{
		parent::__construct('#__jcomments_subscriptions', 'id', $db);
	}

	function store($updateNulls = false)
	{
		if ($this->userid != 0 && empty($this->email)) {
			$user = JCommentsFactory::getUser($this->userid);
			$this->email = $user->email;
			unset($user);
		}

		if (empty($this->lang)) {
			$this->lang = JCommentsMultilingual::getLanguage();
		}

		$this->hash = $this->getHash();
		return parent::store($updateNulls);
	}

	function getHash()
	{
		return md5($this->object_id . $this->object_group . $this->userid . $this->email . $this->lang);
	}
}