<?php
/**
 * JComments CustomBBCodes table
 */
class JCommentsTableCustomBBCode extends JoomlaTuneDBTable
{
	/** @var int Primary key */
	var $id = null;
	/** @var string */
	var $name = null;
	/** @var string */
	var $pattern = null;
	/** @var string */
	var $replacement_html = null;
	/** @var string */
	var $replacement_text = null;
	/** @var string */
	var $simple_pattern = null;
	/** @var string */
	var $simple_replacement_html = null;
	/** @var string */
	var $simple_replacement_text = null;
	/** @var string */
	var $button_acl = null;
	/** @var string */
	var $button_open_tag = null;
	/** @var string */
	var $button_close_tag = null;
	/** @var string */
	var $button_title = null;
	/** @var string */
	var $button_prompt = null;
	/** @var string */
	var $button_image = null;
	/** @var string */
	var $button_css = null;
	/** @var boolean */
	var $button_enabled = null;
	/** @var int */
	var $ordering = null;
	/** @var boolean */
	var $published = null;

	/**
	 * @param JDatabase $db A database connector object
	 * @return void
	 */
	function JCommentsTableCustomBBCode(&$db)
	{
		parent::__construct('#__jcomments_custom_bbcodes', 'id', $db);
	}
}
