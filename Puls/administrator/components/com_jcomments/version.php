<?php
class JCommentsVersion
{
	/** @var string Product */
	var $PRODUCT = 'JComments';
	/** @var int Main Release Level */
	var $RELEASE = '2.3';
	/** @var int Sub Release Level */
	var $DEV_LEVEL = '0';
	/** @var string Development Status */
	var $DEV_STATUS = '';
	/** @var int Build Number */
	var $BUILD = '';
	/** @var string Date */
	var $RELDATE = '20/02/2012';
	/** @var string Time */
	var $RELTIME = '23:10';
	/** @var string Timezone */
	var $RELTZ = 'GMT+2';

	/**
	 * @return string Long format version
	 */
	function getLongVersion()
	{
		return trim($this->PRODUCT . ' ' . $this->RELEASE . '.' . $this->DEV_LEVEL . ($this->BUILD ? '.' . $this->BUILD : '') . ' ' . $this->DEV_STATUS);
	}

	/**
	 * @return string Short version format
	 */
	function getShortVersion()
	{
		return $this->RELEASE . '.' . $this->DEV_LEVEL;
	}

	/**
	 * @return string Version
	 */
	function getVersion()
	{
		return trim($this->RELEASE . '.' . $this->DEV_LEVEL . ($this->BUILD ? '.' . $this->BUILD : '') . ' ' . $this->DEV_STATUS);
	}

	/**
	 * @return string Release date
	 */
	function getReleaseDate()
	{
		return $this->RELDATE;
	}
}
?>