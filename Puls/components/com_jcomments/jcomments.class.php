<?php
/**
 * JComments - Joomla Comment System
 *
 * Core classes
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

ob_start();

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once (JCOMMENTS_BASE.DS.'jcomments.legacy.php');
require_once (JCOMMENTS_HELPERS.DS.'object.php');
require_once (JCOMMENTS_TABLES.'/comment.php');
ob_end_clean();

class JCommentsBaseACL
{
	function check( $str )
	{
		static $group = null;

		if (!empty($str)) {
			$user = JCommentsFactory::getUser();

			$list = explode(',', $str);

			if ($group === null) {
				if (JCOMMENTS_JVERSION == '1.0') {
					if ($user->id) {
						$acl = new gacl_api();
						$aroGroup = $acl->getAroGroup($user->id);
						$group = $aroGroup->group_id ? $aroGroup->group_id : 29;
					} else {
						$group = 29;
					}
				} else if (JCOMMENTS_JVERSION == '1.5') {
					$group = $user->id ? $user->gid : 29;
				} else if (JCOMMENTS_JVERSION == '1.7') {
					if ($user->id) {
						$db = JFactory::getDbo();
						// get highest group
						$query	= $db->getQuery(true)
							->select('a.id')
							->from('#__user_usergroup_map AS map')
							->leftJoin('#__usergroups AS a ON a.id = map.group_id')
							->where('map.user_id = '.(int) $user->id)
							->order('a.lft desc');
						$db->setQuery($query, 0, 1);
						$group = $db->loadResult();
					} else {
						$group = JComponentHelper::getParams('com_users')->get('guest_usergroup', 1);
					}
				}
			}

			if (in_array($group, $list)) {
				return 1;		
			}
		}
		return 0;
	}
}

class JCommentsACL extends JCommentsBaseACL
{
	var $canDelete = 0;
	var $canDeleteOwn = 0;
	var $canDeleteForMyObject = 0;
	var $canEdit = 0;
	var $canEditOwn = 0;
	var $canEditForMyObject = 0;
	var $canPublish = 0;
	var $canPublishForMyObject = 0;
	var $canViewIP = 0;
	var $canViewEmail = 0;
	var $canViewHomepage = 0;
	var $canComment = 0;
	var $canQuote = 0;
	var $canReply = 0;
	var $canVote = 0;
	var $canReport = 0;
	var $canBan = 0;
	var $userID = 0;
	var $userIP = 0;
	var $deleteMode = 0;
	var $userBlocked = 0;

	function JCommentsACL()
	{
		$user = JCommentsFactory::getUser();
		$config = JCommentsFactory::getConfig();

		$this->canDelete		= $this->check('can_delete');
		$this->canDeleteOwn		= $this->check('can_delete_own');
		$this->canDeleteForMyObject	= $this->check('can_delete_for_my_object');
		$this->canEdit			= $this->check('can_edit');
		$this->canEditOwn		= $this->check('can_edit_own');
		$this->canEditForMyObject	= $this->check('can_edit_for_my_object');
		$this->canPublish		= $this->check('can_publish');
		$this->canPublishForMyObject	= $this->check('can_publish_for_my_object');
		$this->canViewIP		= $this->check('can_view_ip');
		$this->canViewEmail		= $this->check('can_view_email');
		$this->canViewHomepage		= $this->check('can_view_homepage');
		$this->canComment		= $this->check('can_comment');
		$this->canVote			= $this->check('can_vote');
		$this->canReport		= intval($this->check('can_report') && $config->getInt('enable_reports'));
		$this->canBan			= 0;
		$this->canQuote			= intval($this->canComment && $this->check('enable_bbcode_quote'));
		$this->canReply			= intval($this->canComment && $this->check('can_reply') && $config->get('template_view') == 'tree');

		$this->userID			= (int) $user->id;
		$this->userIP			= $_SERVER['REMOTE_ADDR'];
		$this->userBlocked		= 0;

		$this->deleteMode		= $config->getInt('delete_mode');

		$this->commentsLocked		= false;

		if ($config->getInt('enable_blacklist', 0) == 1) {
			$options = array();
			$options['ip'] = $this->getUserIP();
			$options['userid'] = $this->getUserID();
			if (!JCommentsSecurity::checkBlacklist($options)) {
				$this->userBlocked = 1;
				$this->canComment = 0;
				$this->canQuote = 0;
				$this->canReply = 0;
				$this->canVote = 0;
				$this->canBan = 0;
			} else {
				$this->canBan = $this->check('can_ban');
			}
		}
	}

	function check( $str )
	{
		$config = JCommentsFactory::getConfig();
		return parent::check($config->get($str));
	}

	function getUserIP()
	{
		return $this->userIP;
	}

	function getUserId()
	{
		return $this->userID;
	}

	function getUserBlocked()
	{
		return $this->userBlocked;
	}

	function getUserAccess()
	{
		static $access = null;

		if (!isset($access)) {
			if (JCOMMENTS_JVERSION == '1.0') {
				$user = JCommentsFactory::getUser();
				$access = $user->gid;
			} else if (JCOMMENTS_JVERSION == '1.5') {
				$user = JFactory::getUser();
				$access = $user->get('aid', 0);
			} else {
				$user = JFactory::getUser();
				$access = array_unique(JAccess::getAuthorisedViewLevels($user->get('id')));
				$access[] = 0; // for backward compability
			}
		}

		return $access;
	}

	function isLocked($obj)
	{
		if (isset($obj) && ($obj != null)) {
			return ($obj->checked_out && $obj->checked_out != $this->userID) ? 1 : 0;
		}
		return 0;
	}

	function isDeleted($obj)
	{
		if (isset($obj) && ($obj != null)) {
			return $obj->deleted ? 1 : 0;
		}
		return 0;
	}

	function isObjectOwner($obj)
	{
		if (is_null($obj)) {
			return false;
		} else {
			$objectOwner = $this->userID ? JCommentsObjectHelper::getOwner($obj->object_id, $obj->object_group) : 0;
			return $this->userID ? ($this->userID == $objectOwner) : false;
		}
	}

	function canDelete($obj)
	{
		return (($this->canDelete || ($this->canDeleteForMyObject && $this->isObjectOwner($obj))
			|| ($this->canDeleteOwn && ($obj->userid == $this->userID)))
			&& (!$this->isLocked($obj)) && (!$this->isDeleted($obj) || $this->deleteMode==0)) ? 1 : 0;
	}

	function canEdit($obj)
	{
		return (($this->canEdit  || ($this->canEditForMyObject && $this->isObjectOwner($obj))
			|| ($this->canEditOwn && ($obj->userid == $this->userID)))
			&& (!$this->isLocked($obj)) && (!$this->isDeleted($obj))) ? 1 : 0;
	}

	function canPublish($obj = null)
	{
		return (($this->canPublish  || ($this->canPublishForMyObject && $this->isObjectOwner($obj)))
			&& (!$this->isLocked($obj)) && (!$this->isDeleted($obj))) ? 1 : 0;
	}

	function canPublishForObject($object_id, $object_group)
	{
		return ($this->userID 
			&& $this->canPublishForMyObject 
			&& $this->userID == JCommentsObjectHelper::getOwner($object_id, $object_group)) ? 1 : 0;
	}

	function canViewIP($obj = null)
	{
		if (is_null($obj)) {
			return ($this->canViewIP) ? 1 : 0;
		} else {
			return ($this->canViewIP&&($obj->ip!='') && (!$this->isDeleted($obj))) ? 1 : 0;
		}
	}

	function canViewEmail($obj = null)
	{
		if (is_null($obj)) {
			return ($this->canViewEmail) ? 1 : 0;
		} else {
			return ($this->canViewEmail&&($obj->email!='')) ? 1 : 0;
		}
	}

	function canViewHomepage($obj = null)
	{
		if (is_null($obj)) {
			return ($this->canViewHomepage) ? 1 : 0;
		} else {
			return ($this->canViewHomepage&&($obj->homepage!='')) ? 1 : 0;
		}
	}

	function canComment()
	{
		return $this->canComment;
	}

	function canQuote($obj = null)
	{
		if (is_null($obj)) {
			return $this->canQuote && !$this->commentsLocked;
		} else {
			return ($this->canQuote && !$this->commentsLocked && (!isset($obj->_disable_quote))&&(!$this->isDeleted($obj))) ? 1 : 0;
		}
	}

	function canReply($obj = null)
	{
		if (is_null($obj)) {
			return $this->canReply && !$this->commentsLocked;
		} else {
			return ($this->canReply && !$this->commentsLocked && (!isset($obj->_disable_reply)) && (!$this->isDeleted($obj))) ? 1 : 0;
		}
	}

	function canVote($obj)
	{
		if ($this->userID) {
			return ($this->canVote && $obj->userid != $this->userID && !isset($obj->voted) && (!$this->isDeleted($obj)));
		} else {
			return ($this->canVote && $obj->ip != $this->userIP && !isset($obj->voted) && (!$this->isDeleted($obj)));
		}

	}

	function canReport($obj = null)
	{
		if (is_null($obj)) {
			return $this->canReport;
		} else {
			return ($this->canReport && (!isset($obj->_disable_report)) && (!$this->isDeleted($obj))) ? 1 : 0;
		}
	}

	function canModerate($obj) {
		return ($this->canEdit($obj) || $this->canDelete($obj)
			|| $this->canPublish($obj) || $this->canViewIP($obj) || $this->canBan($obj)) && (!$this->isDeleted($obj) || $this->deleteMode == 0);
	}

	function canBan($obj = null)
	{
		if (is_null($obj)) {
			return $this->canBan;
		} else {
			return ($this->canBan && (!$this->isDeleted($obj))) ? 1 : 0;
		}
	}

	function setCommentsLocked($value)
	{
		$this->commentsLocked = $value;

		//$this->canComment = $this->canComment && !$this->commentsLocked;
		$this->canQuote = $this->canQuote && !$this->commentsLocked;
		$this->canReply = $this->canReply && !$this->commentsLocked;
	}

	function isCommentsLocked()
	{
		return $this->commentsLocked;
	}
}

function jc_compare($a, $b) {
	if (strlen($a) == strlen($b)) {
		return 0;
	}
	return (strlen($a) > strlen($b)) ? -1 : 1;
}

class JCommentsSmiles
{
	var $_smiles = array();

	function JCommentsSmiles()
	{
		$app = JCommentsFactory::getApplication();

		if (count($this->_smiles) == 0) {
			$config = JCommentsFactory::getConfig();
			$smilesPath = str_replace(DS, '/', $config->get('smiles_path', '/components/com_jcomments/images/smiles/'));
			$smilesPath = $smilesPath[strlen($smilesPath)-1] != '/' ? $smilesPath . '/'  : $smilesPath;
			$smilesPath = $app->getCfg( 'live_site' ) . $smilesPath;

			$list = (array) $config->get('smiles');
			uksort($list, 'jc_compare');
			
			foreach ($list as $sc=>$si) {
				$this->_smiles['code'][] = '#(^|\s|\n|\r|\>)('.preg_quote( $sc, '#' ) . ')(\s|\n|\r|\<|$)#ism' . JCOMMENTS_PCRE_UTF8;
				$this->_smiles['icon'][] = '\\1 \\2 \\3';
				$this->_smiles['code'][] = '#(^|\s|\n|\r|\>)('.preg_quote( $sc, '#' ) . ')(\s|\n|\r|\<|$)#ism' . JCOMMENTS_PCRE_UTF8;
				$this->_smiles['icon'][] = '\\1<img src="' . $smilesPath . $si . '" border="0" alt="'.htmlspecialchars($sc).'" />\\3';
			}
		}
	}

	function get()
	{
		return $this->_smiles;
	}

	function replace($str)
	{
		if (count($this->_smiles) > 0) {
			$str = JCommentsText::br2nl($str);
			$str = preg_replace($this->_smiles['code'], $this->_smiles['icon'], $str);
			$str = JCommentsText::nl2br($str);
		}
		return $str;
	}

	function strip($str)
	{
		if (count($this->_smiles) > 0) {
			$str = JCommentsText::br2nl($str);
			$str = preg_replace($this->_smiles['code'], '\\1\\3', $str);
			$str = JCommentsText::nl2br($str);
		}
		return $str;
	}
}

/**
 * Base class
 * 
 */
class JCommentsPlugin
{
	/**
	 * Return the title of an object by given identifier.
	 *
	 * @abstract
	 * @param int $id A object identifier.
	 * @return string Object title 
	 */
	function getObjectTitle( $id )
	{
		$app = JCommentsFactory::getApplication();
		return $app->getCfg('sitename');
	}

	/**
	 * Return the URI to object by given identifier.
	 *
	 * @abstract 
	 * @param int $id A object identifier.
	 * @return string URI of an object 
	 */
	function getObjectLink( $id )
	{
		$app = JCommentsFactory::getApplication();
		return $app->getCfg('live_site');
	}

	/**
	 * Return identifier of the object owner.
	 *
	 * @abstract 
	 * @param int $id A object identifier.
	 * @return int Identifier of the object owner, otherwise -1 
	 */
	function getObjectOwner( $id )
	{
		return -1;
	}

	public static function getItemid( $object_group, $link = '')
	{
		static $cache = array();
		
		$key = 'jc_' . $object_group . '_itemid';

		if (!isset($cache[$key])) {
			if (JCOMMENTS_JVERSION == '1.0') {
				$db = JCommentsFactory::getDBO();
				$db->setQuery("SELECT id FROM `#__menu` WHERE link LIKE '%" . $db->getEscaped($object_group) . "%' AND published=1");
				$cache[$key] = (int) $db->loadResult();
			} else {
				require_once(JPATH_SITE.'/includes/application.php');
				$menu = JSite::getMenu();
				
				if (empty($link)) {
					$component = JComponentHelper::getComponent($object_group);
					if (isset($component->id)) {
						if (JCOMMENTS_JVERSION == '1.5') {
							$item = $menu->getItems('componentid', $component->id, true);
						} else {
							$item = $menu->getItems('component_id', $component->id, true);
						}
					} else {
						$item = null;
					}
				} else {
					$item = $menu->getItems('link', $link, true);
				}

				$cache[$key] = ($item !== null) ? $item->id : 0;
				unset($items);
			}
		}
		return $cache[$key];
	}
}

class JCommentsText
{
	public static function formatDate( $date = 'now', $format = null, $offset = null )
	{
		if ($format == 'DATETIME_FORMAT') {
			$format = null;
		}

		if (JCOMMENTS_JVERSION != '1.0') {
			if (empty($format)) {
				$format = JText::_('DATE_FORMAT_LC1');
			}

			if (JCOMMENTS_JVERSION == '1.7') {
				return JHTML::_('date', $date, $format);
			} else {
				return JHTML::_('date', $date, $format, $offset);
			}
		} else {
			if (!is_string($date)) {
				$date = strftime($format, (int) $date);
			}
			return mosFormatDate($date, $format, $offset);
		}
	}

	/**
	 * Replaces newlines with HTML line breaks
	 * @param  $text string The input string.
	 * @return string Returns the altered string.
	 */
	public static function nl2br( $text )
	{
		$text = preg_replace(array('/\r/', '/^\n+/', '/\n+$/'), '', $text);
		$text = str_replace("\n", '<br />', $text);
		return $text;
	}

	/**
	 * Replaces HTML line breaks with newlines
	 * @param  $text string The input string.
	 * @return string Returns the altered string. 
	 */
	public static function br2nl( $text )
	{
		return str_replace('<br />', "\n", $text);
	}

	/**
	 * Escapes input string with slashes to use it in JavaScript
	 * @param  $text string The input string.
	 * @return string Returns the altered string.
	 */
	public static function jsEscape( $text )
	{
		return addcslashes($text, "\\\\&\"\n\r<>'");
	}

	/**
	 * @param string $str	The input string.
	 * @param int $width	The column width.
	 * @param string $break	The line is broken using the optional break parameter.
	 * @param bool $cut	If the cut is set to TRUE, the string is always wrapped at the specified width. So if you have a word that is larger than the given width, it is broken apart.
	 * @return string
	 */
	public static function wordwrap($str, $width, $break, $cut = false)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			return wordwrap($str, $width, $break, $cut);
		} else {
			if (!$cut) {
				$regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.',}\b#U';
			} else {
				$regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.'}#';
			}

			$i = 1;
			$j = ceil(JCommentsText::strlen($str) / $width);
			$return = '';

			while ($i < $j) {
				preg_match($regexp, $str, $matches);
				$return .= $matches[0] . $break;
				$str = JString::substr($str, JCommentsText::strlen($matches[0]));
				$i++;
			}
			return $return . $str;
		}
	}

	/**
	 * Inserts a separator in a very long continuous sequences of characters
	 * @param string $text The input string.
	 * @param int $maxLength The maximum length of sequence.
	 * @param string $customBreaker The custom string to be used as breaker.
	 * @return string Returns the altered string.
	 */
	public static function fixLongWords($text, $maxLength, $customBreaker = '')
	{
		$maxLength = (int) min(65535, $maxLength);

		if ($maxLength > 5) {
			ob_start();
			if ($customBreaker == '') {
				if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false) {
					$breaker = '<span style="margin: 0 -0.65ex 0 -1px;padding:0;"> </span>';
				} else {
					$breaker = '<span style="font-size:0;padding:0;margin:0;"> </span>';
				}
			} else {
				$breaker = $customBreaker;
			}

			$plainText = $text;
			$plainText = preg_replace(_JC_REGEXP_EMAIL, '', $plainText);
			$plainText = preg_replace('#<br\s?/?>#is'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<img[^\>]+/>#is'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<a.*?>(.*?)</a>#is'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<span class="quote">(.*?)</span>#is', '', $plainText);
			$plainText = preg_replace('#<span[^\>]*?>(.*?)</span>#is', '\\1', $plainText);
			$plainText = preg_replace('#<pre.*?>(.*?)</pre>#isU'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<blockquote.*?>(.*?)</blockquote>#isU'. JCOMMENTS_PCRE_UTF8, '\\1 ', $plainText);
			$plainText = preg_replace('#<code.*?>(.*?)</code>#isU'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<embed.*?>(.*?)</embed>#is'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<object.*?>(.*?)</object>#is'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#(^|\s|\>|\()((http://|https://|news://|ftp://|www.)\w+[^\s\[\]\<\>\"\'\)]+)#i'. JCOMMENTS_PCRE_UTF8, '', $plainText);
			$plainText = preg_replace('#<(b|strong|i|em|u|s|del|sup|sub|li)>(.*?)</(b|strong|i|em|u|s|del|sup|sub|li)>#is'. JCOMMENTS_PCRE_UTF8, '\\2 ', $plainText);

			$words = explode(' ', $plainText);

			foreach ($words as $word) {
				if (JCommentsText::strlen($word) > $maxLength) {
					$text = str_replace($word, JCommentsText::wordwrap($word, $maxLength, $breaker, true), $text);
				}
			}
			ob_end_clean();

		}
		return $text;
	}

	public static function countLinks($text)
	{
		$matches = array();
		return preg_match_all(_JC_REGEXP_LINK, $text, $matches);
	}

	public static function clearLinks($text)
	{
		return preg_replace(_JC_REGEXP_LINK, '', $text);
	}

	public static function url($s)
	{
		if (isset($s) && preg_match('/^((http|https|ftp):\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}((:[0-9]{1,5})?\/.*)?$/i' , $s)) {
			$url = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $s);
			$url = str_replace(';//', '://', $url);
			if ($url != '') {
				$url = (!strstr($url, '://')) ? 'http://'.$url : $url;
				$url = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $url);
				return $url;
			}
		}
		return '';
	}

	public static function censor( $text )
	{
		ob_start();
		
		$config = JCommentsFactory::getConfig();
		$badWords = $config->get('badwords');
		$replaceWord = $config->get('censor_replace_word', '***');
		
		if (!empty($badWords)) {
			for ($i = 0, $n = count($badWords); $i < $n; $i++) {
				$word = trim($badWords[$i]);
				if ($word != '') {
					$word = str_replace('#', '\#', str_replace('\#', '#', $word));
					$txt = trim(preg_replace('#'. $word.'#ism'. JCOMMENTS_PCRE_UTF8, $replaceWord, $text));
					// make safe from dummy bad words list
					if ($txt != '') {
						$text = $txt;
					}
				}
			}
		}
		ob_end_clean();
		return $text;
	}

	/**
	 * Cleans text of all formatting and scripting code
     *
	 * @param  $text string The input string.
	 * @return string Returns the altered string.
	 */
	public static function cleanText( $text )
	{
		$bbcode = JCommentsFactory::getBBCode();
		$config = JCommentsFactory::getConfig();
		
		$text = $bbcode->filter($text, true);

		if ($config->getInt('enable_custom_bbcode')) {
			$customBBCode = JCommentsFactory::getCustomBBCode();
			$text = $customBBCode->filter($text, true);
		}

		$text = str_replace('<br />', ' ', $text);
		$text = preg_replace('#(\s){2,}#ism' . JCOMMENTS_PCRE_UTF8, '\\1', $text);
		$text = preg_replace('#<script[^>]*>.*?</script>#ism' . JCOMMENTS_PCRE_UTF8, '', $text);
		$text = preg_replace('#<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>#ism' . JCOMMENTS_PCRE_UTF8, '\2 (\1)', $text);
		$text = preg_replace('#<!--.+?-->#ism' . JCOMMENTS_PCRE_UTF8, '', $text);
		$text = preg_replace('#&nbsp;#ism' . JCOMMENTS_PCRE_UTF8, ' ', $text);
		$text = preg_replace('#&amp;#ism' . JCOMMENTS_PCRE_UTF8, ' ', $text);
		$text = preg_replace('#&quot;#ism' . JCOMMENTS_PCRE_UTF8, ' ', $text);
		$text = strip_tags($text);
		$text = htmlspecialchars($text);
		$text = html_entity_decode($text);
		//$text = html_entity_decode($text, ENT_COMPAT, JCOMMENTS_ENCODING);

		return $text;
	}

	public static function strlen( $str )
	{
		if (JCOMMENTS_ENCODING != 'utf-8') {
			return strlen($str);
		} else {
			return strlen(utf8_decode($str));
		}
	}

	public static function substr( $text, $length = 0 )
	{
		if (class_exists('JString')) {
			if ($length && JString::strlen($text) > $length) {
				$text = JString::substr($text, 0, $length) . '...';
			}
		} else {
			if ($length && strlen($text) > $length) {
				$text = substr($text, 0, $length) . '...';
			}
		}
		
		return $text;
	}

	public static function isUTF8( $v )
	{
		for ($i = 0; $i < strlen($v); $i++) {
			if (ord($v[$i]) < 0x80) {
				$n = 0;
			} elseif ((ord($v[$i]) & 0xE0) == 0xC0) {
				$n = 1;
			} elseif ((ord($v[$i]) & 0xF0) == 0xE0) {
				$n = 2;
			} elseif ((ord($v[$i]) & 0xF0) == 0xF0) {
				$n = 3;
			} else {
				return false;
			}
			
			for ($j = 0; $j < $n; $j++) {
				if ((++$i == strlen($v)) || ((ord($v[$i]) & 0xC0) != 0x80))
					return false;
			}
		}
		return true;
	}
}

class JCommentsBBCode
{
	var $_codes = array();

	function JCommentsBBCode()
	{
		ob_start();
		$this->registerCode('b');
		$this->registerCode('i');
		$this->registerCode('u');
		$this->registerCode('s');
		$this->registerCode('url');
		$this->registerCode('img');
		$this->registerCode('list');
		$this->registerCode('hide');
		$this->registerCode('quote');
		$this->registerCode('code');
		ob_end_clean();
	}

	function registerCode($str)
	{
		$acl = JCommentsFactory::getACL();
		$this->_codes[$str] = $acl->check('enable_bbcode_'.$str);
	}

	function getCodes()
	{
		return array_keys( $this->_codes );
	}

	function enabled()
	{
		static $enabled = null;

		if ($enabled == null) {
			foreach ($this->_codes as $code => $_enabled) {
				if ($_enabled == 1 && $code != 'quote') {
					$enabled = $_enabled;
					break;
				}
			}
		}
		return $enabled;
	}

	function canUse($str)
	{
		return $this->_codes[$str] ? 1 : 0;
	}

	function filter($str, $forceStrip = false)
	{
		ob_start();
		$patterns = array();
		$replacements = array();

		// disabled BBCodes
		$patterns[] = '/\[email\](.*?)\[\/email\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = ' \\1';
		$patterns[] = '/\[sup\](.*?)\[\/sup\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = ' \\1';
		$patterns[] = '/\[sub\](.*?)\[\/sub\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = ' \\1';

		//empty tags
		foreach ($this->_codes as $code => $enabled) {
			$patterns[] = '/\['.$code.'\]\[\/'.$code.'\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '';
		}
		// B
		if (($this->canUse('b') == 0) || ($forceStrip)) {
			$patterns[] = '/\[b\](.*?)\[\/b\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\1';
		}

		// I
		if (($this->canUse('i') == 0) || ($forceStrip)) {
			$patterns[] = '/\[i\](.*?)\[\/i\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\1';
		}

		// U
		if (($this->canUse('u') == 0) || ($forceStrip)) {
			$patterns[] = '/\[u\](.*?)\[\/u\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\1';
		}

		// S
		if (($this->canUse('s') == 0) || ($forceStrip)) {
			$patterns[] = '/\[s\](.*?)\[\/s\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\1';
		}

		// URL
		if (($this->canUse('url') == 0) || ($forceStrip)) {
			$patterns[] = '/\[url\](.*?)\[\/url\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\1';
			$patterns[] = '/\[url=([^\s<\"\'\]]*?)\](.*?)\[\/url\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\2: \\1';
		}

		// IMG
		if (($this->canUse('img') == 0) || ($forceStrip)) {
			$patterns[] = '/\[img\](.*?)\[\/img\]/i' . JCOMMENTS_PCRE_UTF8;
			$replacements[] = '\\1';
		}

		// HIDE
		if (($this->canUse('hide') == 0) || ($forceStrip)) {
			$patterns[] = '/\[hide\](.*?)\[\/hide\]/i' . JCOMMENTS_PCRE_UTF8;
			$user = JCommentsFactory::getUser();
			if ($user->id) {
				$replacements[] = '\\1';
			} else {
				$replacements[] = '';
			}
		}

		// CODE
		if ($forceStrip) {
			$codePattern = '#\[code\=?([a-z0-9]*?)\](.*?)\[\/code\]#ism' . JCOMMENTS_PCRE_UTF8;
			$patterns[] = $codePattern;
			$replacements[] = '\\2';
		}

		$str = preg_replace($patterns, $replacements, $str);

		// LIST
		if (($this->canUse('list') == 0) || ($forceStrip)) {
			$matches = array();
			$matchCount = preg_match_all('/\[list\](<br\s?\/?\>)*(.*?)(<br\s?\/?\>)*\[\/list\]/is' . JCOMMENTS_PCRE_UTF8, $str, $matches);
			for ($i = 0; $i < $matchCount; $i++) {
				$textBefore = preg_quote($matches[2][$i]);
				$textAfter = preg_replace('#(<br\s?\/?\>)*\[\*\](<br\s?\/?\>)*#is' . JCOMMENTS_PCRE_UTF8, "<br />", $matches[2][$i]);
				$textAfter = preg_replace('#^<br />#is' . JCOMMENTS_PCRE_UTF8, '', $textAfter);
				$textAfter = preg_replace('#(<br\s?\/?\>)+#is' . JCOMMENTS_PCRE_UTF8, '<br />', $textAfter);
				$str = preg_replace('#\[list\](<br\s?\/?\>)*' . $textBefore . '(<br\s?\/?\>)*\[/list\]#is' . JCOMMENTS_PCRE_UTF8, "\n$textAfter\n", $str);
			}
			$matches = array();
			$matchCount = preg_match_all('#\[list=(a|A|i|I|1)\](<br\s?\/?\>)*(.*?)(<br\s?\/?\>)*\[\/list\]#is' . JCOMMENTS_PCRE_UTF8, $str, $matches);
			for ($i = 0; $i < $matchCount; $i++) {
				$textBefore = preg_quote($matches[3][$i]);
				$textAfter = preg_replace('#(<br\s?\/?\>)*\[\*\](<br\s?\/?\>)*#is' . JCOMMENTS_PCRE_UTF8, '<br />', $matches[3][$i]);
				$textAfter = preg_replace('#^<br />#' . JCOMMENTS_PCRE_UTF8, "", $textAfter);
				$textAfter = preg_replace('#(<br\s?\/?\>)+#' . JCOMMENTS_PCRE_UTF8, '<br />', $textAfter);
				$str = preg_replace('#\[list=(a|A|i|I|1)\](<br\s?\/?\>)*' . $textBefore . '(<br\s?\/?\>)*\[/list\]#is' . JCOMMENTS_PCRE_UTF8, "\n$textAfter\n", $str);
			}
		}

		if ($forceStrip) {
			// QUOTE
			$quotePattern = '#\[quote\s?name=\"([^\"\'\<\>\(\)]+)+\"\](<br\s?\/?\>)*(.*?)(<br\s?\/?\>)*\[\/quote\]#i' . JCOMMENTS_PCRE_UTF8;
			$quoteReplace = ' ';
			while(preg_match($quotePattern, $str)) {
				$str = preg_replace($quotePattern, $quoteReplace, $str);
			}
			$quotePattern = '#\[quote[^\]]*?\](<br\s?\/?\>)*([^\[]+)(<br\s?\/?\>)*\[\/quote\]#i' . JCOMMENTS_PCRE_UTF8;
			$quoteReplace = ' ';
			while(preg_match($quotePattern, $str)) {
				$str = preg_replace($quotePattern, $quoteReplace, $str);
			}

			$str = preg_replace('#\[\/?(b|strong|i|em|u|s|del|sup|sub|url|img|list|quote|code|hide)\]#is' . JCOMMENTS_PCRE_UTF8, '', $str);
		}

		$str = trim(preg_replace('#( ){2,}#i' . JCOMMENTS_PCRE_UTF8, '\\1', $str));

		ob_end_clean();
		return $str;
	}


	function replace($str)
	{
		ob_start();
		
		$config = JCommentsFactory::getConfig();
		$app = JCommentsFactory::getApplication('site');

		$patterns = array();
		$replacements = array();

		// B
		$patterns[] = '/\[b\](.*?)\[\/b\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<strong>\\1</strong>';

		// I
		$patterns[] = '/\[i\](.*?)\[\/i\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<em>\\1</em>';

		// U
		$patterns[] = '/\[u\](.*?)\[\/u\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<u>\\1</u>';

		// S
		$patterns[] = '/\[s\](.*?)\[\/s\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<del>\\1</del>';

		// SUP
		$patterns[] = '/\[sup\](.*?)\[\/sup\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<sup>\\1</sup>';

		// SUB
		$patterns[] = '/\[sub\](.*?)\[\/sub\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<sub>\\1</sub>';

		// URL (local)
		$liveSite = $app->getCfg('live_site');

		$patterns[] = '#\[url\]('.preg_quote($liveSite, '#').'[^\s<\"\']*?)\[\/url\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<a href="\\1" target="_blank">\\1</a>';

		$patterns[] = '#\[url=('.preg_quote($liveSite, '#').'[^\s<\"\'\]]*?)\](.*?)\[\/url\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<a href="\\1" target="_blank">\\2</a>';

		$patterns[] = '/\[url=(\#|\/)([^\s<\"\'\]]*?)\](.*?)\[\/url\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<a href="\\1\\2" target="_blank">\\3</a>';


		// URL (external)
		$patterns[] = '#\[url\](http:\/\/)?([^\s<\"\']*?)\[\/url\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<a href="http://\\2" rel="external nofollow" target="_blank">\\2</a>';

		$patterns[] = '/\[url=([a-z]*\:\/\/)([^\s<\"\'\]]*?)\](.*?)\[\/url\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<a href="\\1\\2" rel="external nofollow" target="_blank">\\3</a>';

		$patterns[] = '/\[url=([^\s<\"\'\]]*?)\](.*?)\[\/url\]/i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<a href="http://\\1" rel="external nofollow" target="_blank">\\2</a>';

		$patterns[] = '#\[url\](.*?)\[\/url\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '\\1';

		// EMAIL
		$patterns[] = '#\[email\]([^\s\<\>\(\)\"\'\[\]]*?)\[\/email\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '\\1';

		// IMG
		$patterns[] = '#\[img\](http:\/\/)?([^\s\<\>\(\)\"\']*?)\[\/img\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '<img class="img" src="http://\\2" alt="" border="0" />';

		$patterns[] = '#\[img\](.*?)\[\/img\]#i' . JCOMMENTS_PCRE_UTF8;
		$replacements[] = '\\1';

		// HIDE
		$patterns[] = '/\[hide\](.*?)\[\/hide\]/i' . JCOMMENTS_PCRE_UTF8;
		$user = JCommentsFactory::getUser();
		if ($user->id) {
			$replacements[] = '\\1';
		} else {
			$replacements[] = '<span class="hidden">'.JText::_('BBCODE_MESSAGE_HIDDEN_TEXT').'</span>';
		}

		// CODE
		$geshiEnabled = $config->getInt('enable_geshi', 0);
		$codePattern = '#\[code\=?([a-z0-9]*?)\](.*?)\[\/code\]#ism' . JCOMMENTS_PCRE_UTF8;
		$geshiLibrary = '';
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe;
			$geshiLibrary = $mainframe->getCfg('absolute_path').'/mambots/content/geshi/geshi.php';
		} else if (JCOMMENTS_JVERSION == '1.5') {
			$geshiLibrary = JPATH_SITE.'/libraries/geshi/geshi.php';
		} else if (JCOMMENTS_JVERSION == '1.7') {
			$geshiLibrary = JPATH_SITE.'/plugins/content/geshi/geshi/geshi.php';
		}

		$geshiEnabled = $geshiEnabled && is_file($geshiLibrary);

		if ($geshiEnabled) {
			require_once($geshiLibrary);

			if (!function_exists('jcommentsProcessGeSHi')) {
				function jcommentsProcessGeSHi($matches) {
					$lang = $matches[1] != '' ? $matches[1] : 'php';
					$text = $matches[2];
					$html_entities_match = array('#\<br \/\>#', "#<#", "#>#", "|&#39;|", '#&quot;#', '#&nbsp;#');
					$html_entities_replace = array("\n", '&lt;', '&gt;', "'", '"', ' ');
					$text = preg_replace($html_entities_match, $html_entities_replace, $text);
					$text = preg_replace('#(\r|\n)*?$#ism', '', $text);
					$text = str_replace('&lt;', '<', $text);
					$text = str_replace('&gt;', '>', $text);
					$geshi = new GeSHi($text, $lang);
					$text = $geshi->parse_code();
					return '[code]'.$text.'[/code]';
				}
			}

			$patterns[] = $codePattern;
			$replacements[] = '<span class="code">'.JText::_('COMMENT_TEXT_CODE').'</span>\\2';
			$str = preg_replace_callback($codePattern, 'jcommentsProcessGeSHi', $str);
		} else {
			$patterns[] = $codePattern;
			$replacements[] = '<span class="code">'.JText::_('COMMENT_TEXT_CODE').'</span><code>\\2</code>';

			if (!function_exists('jcommentsProcessCode')) {
				function jcommentsProcessCode($matches) {
					$text = htmlspecialchars(trim($matches[0]));
					$text = str_replace("\r", '', $text);
					$text = str_replace("\n", '<br />', $text);
					return $text;
				}
			}
			$str = preg_replace_callback($codePattern, 'jcommentsProcessCode', $str);
		}

		$str = preg_replace($patterns, $replacements, $str);

		// QUOTE
		$quotePattern = '#\[quote\s?name=\"([^\"\'\<\>\(\)]*?)\"\](<br\s?\/?\>)*?(.*?)(<br\s?\/?\>)*\[\/quote\](<br\s?\/?\>)*?#ism' . JCOMMENTS_PCRE_UTF8;
		$quoteReplace = '<span class="quote">'.JText::sprintf('COMMENT_TEXT_QUOTE_EXTENDED', '\\1').'</span><blockquote><div>\\3</div></blockquote>';

		while(preg_match($quotePattern, $str)) {
			$str = preg_replace($quotePattern, $quoteReplace, $str);
		}

		$quotePattern = '#\[quote[^\]]*?\](<br\s?\/?\>)*([^\[]+)(<br\s?\/?\>)*\[\/quote\](<br\s?\/?\>)*?#ismU' . JCOMMENTS_PCRE_UTF8;
		$quoteReplace = '<span class="quote">'.JText::_('COMMENT_TEXT_QUOTE').'</span><blockquote><div>\\2</div></blockquote>';
		while(preg_match($quotePattern, $str)) {
			$str = preg_replace($quotePattern, $quoteReplace, $str);
		}

		// LIST
		$matches = array();
		$matchCount = preg_match_all('#\[list\](<br\s?\/?\>)*(.*?)(<br\s?\/?\>)*\[\/list\]#i' . JCOMMENTS_PCRE_UTF8, $str, $matches);
		for ($i = 0; $i < $matchCount; $i++) {
			$textBefore = preg_quote($matches[2][$i]);
			$textAfter = preg_replace('#(<br\s?\/?\>)*\[\*\](<br\s?\/?\>)*#is' . JCOMMENTS_PCRE_UTF8, "</li><li>", $matches[2][$i]);
			$textAfter = preg_replace("#^</?li>#" . JCOMMENTS_PCRE_UTF8, "", $textAfter);
			$textAfter = str_replace("\n</li>", "</li>", $textAfter."</li>");
			$str = preg_replace('#\[list\](<br\s?\/?\>)*' . $textBefore . '(<br\s?\/?\>)*\[/list\]#is' . JCOMMENTS_PCRE_UTF8, "<ul>$textAfter</ul>", $str);
		}
		$matches = array();
		$matchCount = preg_match_all('#\[list=(a|A|i|I|1)\](<br\s?\/?\>)*(.*?)(<br\s?\/?\>)*\[\/list\]#is' . JCOMMENTS_PCRE_UTF8, $str, $matches);
		for ($i = 0; $i < $matchCount; $i++) {
			$textBefore = preg_quote($matches[3][$i]);
			$textAfter = preg_replace('#(<br\s?\/?\>)*\[\*\](<br\s?\/?\>)*#is' . JCOMMENTS_PCRE_UTF8, "</li><li>", $matches[3][$i]);
			$textAfter = preg_replace("#^</?li>#" . JCOMMENTS_PCRE_UTF8, '', $textAfter);
			$textAfter = str_replace("\n</li>", "</li>", $textAfter."</li>");
			$str = preg_replace('#\[list=(a|A|i|I|1)\](<br\s?\/?\>)*' . $textBefore . '(<br\s?\/?\>)*\[/list\]#is' . JCOMMENTS_PCRE_UTF8, "<ol type=\\1>$textAfter</ol>", $str);
		}

		$str = preg_replace('#\[\/?(b|i|u|s|sup|sub|url|img|list|quote|code|hide)\]#i' . JCOMMENTS_PCRE_UTF8, '', $str);
		unset($matches);
		ob_end_clean();
		return $str;
	}

	function removeQuotes( $text )
	{
		$text = preg_replace(array('#\n?\[quote.*?\].+?\[\/quote\]\n?#is' . JCOMMENTS_PCRE_UTF8, '#\[\/quote\]#is'), '', $text);
		$text = preg_replace('#<br />+#is', '', $text);
		return $text;
	}

	function removeHidden( $text )
	{
		$text = preg_replace('#\[hide\](.*?)\[\/hide\]#is' . JCOMMENTS_PCRE_UTF8, '', $text);
		$text = preg_replace('#<br />+#is', '', $text);
		return $text;
	}
}

class JCommentsCustomBBCode
{
	var $codes = array();
	var $patterns = array();
	var $filter_patterns = array();
	var $html_replacements = array();
	var $text_replacement = array();

	function JCommentsCustomBBCode()
	{
		$app = JCommentsFactory::getApplication();

		$this->patterns = array();
		$this->html_replacements = array();
		$this->text_replacements = array();
		$this->codes = array();

		ob_start();
		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT * FROM #__jcomments_custom_bbcodes WHERE published = 1 ORDER BY ordering');
		$codes = $db->loadObjectList();

		if (count($codes)) {
			$acl = new JCommentsBaseACL();
			foreach ($codes as $code) {
			
				if (JCOMMENTS_PCRE_UTF8 == 'u') {
					// fix \w pattern issue for UTF-8 encoding
					// details: http://www.phpwact.org/php/i18n/utf-8#w_w_b_b_meta_characters
					$code->pattern = preg_replace('#(\\\w)#u', '\p{L}', $code->pattern);
				}

				// check button permission
				if ($acl->check($code->button_acl)) {
					if ($code->button_image != '') {
						if (strpos($code->button_image, $app->getCfg('live_site')) === false) {
							$code->button_image = $app->getCfg('live_site') . ($code->button_image[0] == '/' ? '' : '/') . $code->button_image;
						}
					}
					$this->codes[] = $code;
				} else {
					$this->filter_patterns[] = '#' . $code->pattern . '#ism' . JCOMMENTS_PCRE_UTF8;
				}

				$this->patterns[] = '#' . $code->pattern . '#ism' . JCOMMENTS_PCRE_UTF8;
				$this->html_replacements[] = $code->replacement_html;
				$this->text_replacements[] = $code->replacement_text;
			}
		}
		ob_end_clean();
	}

	function enabled()
	{
		return 1;
		//return count($this->codes) > 0;
	}

	function filter($str, $forceStrip = false)
	{
		if (count($this->filter_patterns)) {
			ob_start();
			$filter_replacement = $this->text_replacements;
			$str = preg_replace($this->filter_patterns, $filter_replacement, $str);
			ob_end_clean();
		}
		if ($forceStrip === true)  {
			ob_start();
			$str = preg_replace($this->patterns, $this->text_replacements, $str);
			ob_end_clean();
		}
		return $str;
	}

	function replace($str, $textReplacement = false)
	{
		if (count($this->patterns)) {
			ob_start();
			$str = preg_replace($this->patterns, ($textReplacement ? $this->text_replacements : $this->html_replacements), $str);
			ob_end_clean();
		}
		return $str;
	}
}

class JCommentsSecurity
{
	public static function notAuth()
	{
		header('HTTP/1.0 403 Forbidden');
		if (JCOMMENTS_JVERSION == "1.0") {
			echo _NOT_AUTH;
		} else {
			JError::raiseError(403, JText::_('ALERTNOTAUTH'));
		}
		exit;
	}

	public static function badRequest()
	{
		return (int) (empty($_SERVER['HTTP_USER_AGENT']) || (!$_SERVER['REQUEST_METHOD']=='POST'));
	}

	public static function checkFlood( $ip )
	{
		$app = JCommentsFactory::getApplication();

		$config = JCommentsFactory::getConfig();
		$floodInterval = $config->getInt('flood_time');

		if ($floodInterval > 0) {
			$db = JCommentsFactory::getDBO();
			$now = JCommentsFactory::getDate();
			$query = "SELECT COUNT(*) "
				. "\nFROM #__jcomments "
				. "\nWHERE ip = '$ip' "
				. "\nAND '".$now."' < DATE_ADD(date, INTERVAL " . $floodInterval . " SECOND)"
				. (($app->getCfg('multilingual_support') == 1) ? "\nAND lang = '" . $app->getCfg('lang') . "'" : "")
				;
			$db->setQuery($query);
			return ($db->loadResult() == 0) ? 0 : 1;
		}
		return 0;
	}

	public static function checkIsForbiddenUsername($str)
	{
		$config = JCommentsFactory::getConfig();
		$names = $config->get('forbidden_names');

		if (!empty($names) && !empty($str) ) {
			$str = trim(strtolower($str));
			$forbidden_names = preg_split('/,/', strtolower($names));
			foreach ($forbidden_names as $name) {
				if (trim((string) $name) == $str) {
					return 1;
				}
			}
			unset($forbidden_names);
		}
		return 0;
	}

	public static function checkIsRegisteredUsername($name)
	{
		$config = JCommentsFactory::getConfig();
		
		if ($config->getInt('enable_username_check') == 1) {
			$db = JCommentsFactory::getDBO();
			$name = $db->getEscaped($name);
			$db->setQuery("SELECT COUNT(*) FROM #__users WHERE name = '$name' OR username = '$name'");
			return ($db->loadResult() == 0) ? 0 : 1;
		}
		return 0;
	}

	public static function checkIsRegisteredEmail($email)
	{
		$config = JCommentsFactory::getConfig();
		
		if ($config->getInt('enable_username_check') == 1) {
			$db = JCommentsFactory::getDBO();
			$email = $db->getEscaped($email);
			$db->setQuery("SELECT COUNT(*) FROM #__users WHERE email = '$email'");
			return ($db->loadResult() == 0) ? 0 : 1;
		}
		return 0;
	}

	/**
	 * Check if given paramters are not listed in blacklist
	 *
	 * @param  Array $options Array of options for check
	 * @return boolean True on success, false otherwise
	 */
	public static function checkBlacklist($options = array())
	{
		$ip = @$options['ip'];
		$userid = @$options['userid'];

		$result = true;

		if (count($options)) {
			$db = JCommentsFactory::getDBO();
			$where = array();

			if (!empty($ip)) {
				$where[] = "bl.ip = " . $db->Quote($ip);
			}

			if (!empty($userid)) {
				$where[] = "bl.userid = " . (int) $userid;
			}

			$query = "SELECT COUNT(*)"
				. "\nFROM #__jcomments_blacklist AS bl"
				. (count($where) ? ("\nWHERE " . implode(' AND ', $where)) : "" )
				;
			$db->setQuery($query);
			$cnt = $db->loadResult();
			$result = $cnt > 0 ? false : true;
		}
		return $result;
	}

	public static function fixAJAX($link) 
	{
		// fix to prevent cross-domain ajax call
		if (isset($_SERVER['HTTP_HOST'])) {
			$httpHost = (string) $_SERVER['HTTP_HOST'];
			if (strpos($httpHost, '://www.') !== false && strpos($link, '://www.') === false) {
				$link = str_replace('://', '://www.', $link);
			} else if (strpos($httpHost, '://www.') === false && strpos($link, '://www.') !== false) {
				$link = str_replace('://www.', '://', $link);
			}
		}
		return $link;
	}

	public static function clearObjectGroup($str)
	{
		return trim(preg_replace('#[^0-9A-Za-z\-\_]#is', '', strip_tags($str)));
	}

	public static function getToken()
	{
		if (JCOMMENTS_JVERSION == '1.5') {
			return JUtility::getToken();
		} else if (JCOMMENTS_JVERSION == '1.7') {
			$session = JFactory::getSession();
			return $session->getFormToken();
		} else {
			return josSpoofValue();
		}
	}

	public static function checkToken($method = 'post')
	{
		if (JCOMMENTS_JVERSION == '1.5') {
			JRequest::checkToken($method) or jexit('Invalid Token');
		} else if (JCOMMENTS_JVERSION == '1.7') {
			JRequest::checkToken($method) or jexit(JText::_('JINVALID_TOKEN'));
		} else {
			josSpoofCheck(null, null, $method);
		}
	}

	public static function formToken()
	{
		if (JCOMMENTS_JVERSION == '1.5') {
			return JHTML::_('form.token');
		} else if (JCOMMENTS_JVERSION == '1.7') {
			return JHTML::_('form.token');
		} else {
			return '<input type="hidden" name="'.josSpoofValue().'" value="1" />';
		}
	}
}

/**
 * JComments Factory class
 */
class JCommentsFactory
{
	/**
	 * Get a application object
	 *
	 * Returns a reference to the global {@link JApplication} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param mixed $id A client identifier or name.
	 * @param array $config An optional associative array of configuration settings.
	 * @param string $prefix
	 * @return JApplication
	 */
	public static function getApplication($id = null, $config = array(), $prefix = 'J')
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe;
			return $mainframe;
		} else {
			$application = JFactory::getApplication($id, $config, $prefix);
			return $application;
		}
	}

	/**
	 * Get a document object
	 *
	 * Returns a reference to the global {@link JDocument} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return JDocument
	 */
	public static function getDocument()
	{
		static $instance;

		if (!is_object( $instance )) {
			if (JCOMMENTS_JVERSION == '1.0') {
				$instance = JDocument::getInstance();
			} else {
				$instance = JFactory::getDocument();
			}
		}

		return $instance;
	}

	/**
	 * Returns a reference to the global {@link JCommentsSmiles} object, 
	 * only creating it if it doesn't already exist.
	 * 
	 * @return JCommentsSmiles
	 */
	public static function getSmiles()
	{
		static $instance = null;
		
		if (!is_object($instance)) {
			$instance = new JCommentsSmiles();
		}
		return $instance;
	}

	/**
	 * Returns a reference to the global {@link JUser} object, 
	 * only creating it if it doesn't already exist.
	 * 
	 * @param int $id An user identifier
	 * @return JUser
	 */
	public static function getUser($id = null)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			if (!is_null($id)) {
				global $database;
				$user = new mosUser($database);
				$user->load($id);
			} else {
				global $mainframe;
				$user = $mainframe->getUser();
			}
		} else {
			$user = JFactory::getUser($id);
		}
		return $user;
	}

	/**
	 * Returns a reference to the global {@link JCommentsBBCode} object, 
	 * only creating it if it doesn't already exist.
	 * 
	 * @return JCommentsBBCode
	 */
	public static function getBBCode()
	{
		static $instance = null;
		
		if (!is_object($instance)) {
			$instance = new JCommentsBBCode();
		}
		return $instance;
	}

	/**
	 * Returns a reference to the global {@link JCommentsCustomBBCode} object,
	 * only creating it if it doesn't already exist.
	 *
	 * @return JCommentsCustomBBCode
	 */
	public static function getCustomBBCode()
	{
		static $instance = null;
		
		if (!is_object($instance)) {
			$instance = new JCommentsCustomBBCode();
		}
		return $instance;
	}

	/**
	 * Returns a reference to the global {@link JCommentsCfg} object, 
	 * only creating it if it doesn't already exist.
	 *
	 * @param $language string Language
	 * @return JCommentsCfg
	 */
	public static function getConfig($language='')
	{
		return JCommentsCfg::getInstance($language);
	}

	/**
	 * Returns a reference to the global {@link JoomlaTuneTemplateRender} object,
	 * only creating it if it doesn't already exist.
	 *
	 * @param int $object_id
	 * @param string $object_group
	 * @param bool $needThisUrl
	 * @return JoomlaTuneTemplateRender
	 */
	public static function getTemplate( $object_id = 0, $object_group = 'com_content', $needThisUrl = true )
	{
		global $Itemid;

		ob_start();

		$app = JCommentsFactory::getApplication();
		$config = JCommentsFactory::getConfig();

		$templateName = $config->get('template'); 
		
		if (empty($templateName)) {
			$templateName = 'default';
			$config->set('template', $templateName);
		}

		include_once (JCOMMENTS_LIBRARIES.'/joomlatune/template.php');

		$templateDefaultDirectory = JCOMMENTS_BASE.'/tpl/'.$templateName;
		$templateDirectory = $templateDefaultDirectory;
		$templateUrl = $app->getCfg('live_site') . '/components/com_jcomments/tpl/' . $templateName;

		if (JCOMMENTS_JVERSION != '1.0') {
			$templateOverride = JPATH_SITE . '/templates/' . $app->getTemplate() . '/html/com_jcomments/' . $templateName;
			if (is_dir($templateOverride)) {
				$templateDirectory = $templateOverride;
				$templateUrl = JURI::root() . 'templates/' . $app->getTemplate() . '/html/com_jcomments/'.$templateName;
			}
		}

		$tmpl = JoomlaTuneTemplateRender::getInstance();
		$tmpl->setRoot($templateDirectory);
		$tmpl->setDefaultRoot($templateDefaultDirectory);
		$tmpl->setBaseURI($templateUrl);
		$tmpl->addGlobalVar('siteurl', $app->getCfg('live_site'));
		$tmpl->addGlobalVar('charset', strtolower(preg_replace('/charset=/', '', _ISO)));
		$tmpl->addGlobalVar('ajaxurl', JCommentsFactory::getLink('ajax', $object_id, $object_group));
		$tmpl->addGlobalVar('smilesurl', JCommentsFactory::getLink('smiles', $object_id, $object_group));

		if ($config->getInt('enable_rss') == 1) {
			$tmpl->addGlobalVar('rssurl', JCommentsFactory::getLink('rss', $object_id, $object_group));
		}

		$tmpl->addGlobalVar('template', $templateName);
		$tmpl->addGlobalVar('template_url', $templateUrl);
		$tmpl->addGlobalVar('itemid', $Itemid ? $Itemid : 1);

		if (JCOMMENTS_JVERSION == '1.0') {
			$tmpl->addGlobalVar('direction', 'ltr');
		} else {
			$language = JFactory::getLanguage();
			$tmpl->addGlobalVar('direction', $language->isRTL() ? 'rtl' : 'ltr');
		}

		$lang = $app->getCfg('lang');

		if ($lang == 'russian' || $lang == 'ukrainian' || $lang == 'belorussian' || $lang == 'ru-RU' || $lang == 'uk-UA' || $lang == 'be-BY') {
			$tmpl->addGlobalVar('support', base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy5qb29tbGF0dW5lLnJ1IiB0aXRsZT0iSkNvbW1lbnRzIiB0YXJnZXQ9Il9ibGFuayI+SkNvbW1lbnRzPC9hPg=='));
		} else {
			$tmpl->addGlobalVar('support', base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy5qb29tbGF0dW5lLmNvbSIgdGl0bGU9IkpDb21tZW50cyIgdGFyZ2V0PSJfYmxhbmsiPkpDb21tZW50czwvYT4='));
		}

		$tmpl->addGlobalVar('comment-object_id', $object_id);
		$tmpl->addGlobalVar('comment-object_group', $object_group);

		if ($needThisUrl == true) {
			$tmpl->addGlobalVar('thisurl', JCommentsObjectHelper::getLink($object_id, $object_group));
		}

		ob_end_clean();

		return $tmpl;
	}

	/**
	 * Returns a reference to the global {@link JCommentsACL} object, 
	 * only creating it if it doesn't already exist.
	 *
	 * @return JCommentsACL
	 */
	public static function getACL()
	{
		static $instance = null;

		if (!is_object( $instance )) {
			$instance = new JCommentsACL();
		}
		return $instance;
	}

	/**
	 * Returns a reference to the global {@link JDatabase} object
	 *
	 * @return JDatabase
	 */
	public static function getDBO()
	{
		static $instance = null;
		
		if (!is_object($instance)) {
			if (JCOMMENTS_JVERSION == '1.0') {
				global $database;
				$instance = $database;
			} else {
				$instance = JFactory::getDBO();
			}
		}
		return $instance;
	}

	/**
	 * Get a cache object
	 *
	 * Returns a reference to the global {@link JCache} object
	 *
	 * @param string $group The cache group name
	 * @param string $handler The handler to use
	 * @param string $storage The storage method
	 * @return JCache A function cache object
	 */
	public static function getCache($group = '', $handler = 'callback', $storage = null)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$cache = JCache::getInstance($handler, array('defaultgroup' => $group));
			return $cache;
		} else {
			$cache = JFactory::getCache($group, $handler, $storage);
			return $cache;
		}
	}

	/**
	 * Gets the date as in MySQL datetime format
	 *
	 * @param $time mixed|string The initial time for the JDate object
	 * @param int $tzOffset The timezone offset.
	 * @return string A date in MySQL datetime format
	 */
	public static function getDate($time = 'now', $tzOffset = 0)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe;
			if ($time == 'now' || empty($time)) {
				$time = time() + $mainframe->getCfg('offset') * 60 * 60;
			}
			return date('Y-m-d H:i:s', $time);
		} else {
			$dateNow = JFactory::getDate($time, $tzOffset);
			return $dateNow->toMySQL();
		}
	}

	/**
	 * Get a language object
	 *
	 * Returns the global {@link JLanguage} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return JLanguage object
	 */
	public static function getLanguage()
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			$language = JoomlaTuneLanguage::getInstance();
		} else {
			$language = JFactory::getLanguage();
		}
		return $language;
	}

	/**
	 * Returns a reference to the global {@link JoomlaTuneAjaxResponse} object, 
	 * only creating it if it doesn't already exist.
	 * 
	 * @return JoomlaTuneAjaxResponse
	 */
	public static function getAjaxResponse()
	{
		static $instance = null;
		
		if (!is_object($instance)) {
			$instance = new JoomlaTuneAjaxResponse(JCOMMENTS_ENCODING);
		}
		return $instance;
	}

	public static function getCmdHash($cmd, $id)
	{
		$app = JCommentsFactory::getApplication();
		return md5($cmd . $id . $app->getCfg('absolute_path') . $app->getCfg('secret'));
	}

	public static function getCmdLink($cmd, $id)
	{
		$app = JCommentsFactory::getApplication();
		$hash = JCommentsFactory::getCmdHash($cmd, $id);
		$liveSite = $app->getCfg('live_site');

		if (JCOMMENTS_JVERSION == '1.0') {
			$link = $liveSite . '/index2.php?option=com_jcomments&amp;task=cmd&amp;cmd=' . $cmd . '&amp;id=' . $id . '&amp;hash=' . $hash . '&amp;no_html=1';
		} else {
			$liveSite = str_replace(JURI::root(true), '', $liveSite);
			$link = $liveSite . JRoute::_('index.php?option=com_jcomments&amp;task=cmd&amp;cmd=' . $cmd . '&amp;id=' . $id . '&amp;hash=' . $hash . '&amp;format=raw');
		}
		return $link;
	}

	public static function getUnsubscribeLink($hash)
	{
		$app = JCommentsFactory::getApplication();
		$liveSite = $app->getCfg('live_site');

		if (JCOMMENTS_JVERSION == '1.0') {
			$link = $liveSite . '/index2.php?option=com_jcomments&amp;task=unsubscribe&amp;hash='.$hash.'&amp;no_html=1';
		} else {
			$liveSite = str_replace(JURI::root(true), '', $liveSite);
			$link = $liveSite . JRoute::_('index.php?option=com_jcomments&amp;task=unsubscribe&amp;hash='.$hash.'&amp;format=raw');
		}
		return $link;
	}

	public static function getLink($type = 'ajax', $object_id = 0, $object_group = '', $lang = '')
	{
		global $iso_client_lang;

		$app = JCommentsFactory::getApplication();
		$config = JCommentsFactory::getConfig();

		switch($type)
		{
			case 'rss':
				if (JCOMMENTS_JVERSION == '1.0') {
					return $app->getCfg('live_site') . '/index2.php?option=com_jcomments&amp;task=rss&amp;object_id='.$object_id.'&amp;object_group='.$object_group.'&amp;no_html=1';
				} else {
					$link = 'index.php?option=com_jcomments&amp;task=rss&amp;object_id='.$object_id.'&amp;object_group='.$object_group.'&amp;format=raw';
					if ($app->isAdmin()) {
						$link = JURI::root(true).'/'.$link;
					} else {
						$link = JRoute::_($link);
					}
					return $link;
				}
				break;

			case 'noavatar':
				return $app->getCfg('live_site') . '/components/com_jcomments/images/no_avatar.png';
				break;

			case 'smiles':
				$smilesPath = str_replace(DS, '/', $config->get('smiles_path', '/components/com_jcomments/images/smiles/'));
				$smilesPath = $smilesPath[strlen($smilesPath)-1] == '/' ? substr($smilesPath, 0, strlen($smilesPath)-1) : $smilesPath;
				return $app->getCfg('live_site') . $smilesPath; // '/components/com_jcomments/images/smiles';
				break;

			case 'captcha':
				mt_srand((double)microtime()*1000000);
				$random = mt_rand(10000, 99999);

				if (JCOMMENTS_JVERSION == '1.0') {
					return $app->getCfg('live_site') . '/index2.php?option=com_jcomments&amp;task=captcha&amp;no_html=1&amp;ac=' . $random;
				} else if (JCOMMENTS_JVERSION == '1.7') {
					return JRoute::_('index.php?option=com_jcomments&amp;task=captcha&amp;format=raw&amp;ac='.$random);
				} else {
					return JURI::root(true) . '/index.php?option=com_jcomments&amp;task=captcha&amp;tmpl=component&amp;ac=' . $random;
				}
				break;

			case 'ajax':
				$config = JCommentsFactory::getConfig();

				// support alternate language files
				$lsfx = ($config->get('lsfx') != '') ? ('&amp;lsfx='.$config->get('lsfx')) : '';

				// support additional param for multilingual sites
				if (!empty($lang)) {
					$lang = '&amp;lang='.$lang;
				} else {
					$lang = ($app->getCfg('multilingual_support') == 1) ? ('&amp;lang='.$iso_client_lang) : '';
				}

				if (JCOMMENTS_JVERSION == '1.0') {
					$_Itemid = '&amp;Itemid=' . ((!empty($_REQUEST['Itemid'])) ? $_REQUEST['Itemid'] : 1);
					$link = $app->getCfg('live_site') . '/index2.php?option=com_jcomments&amp;no_html=1' . $lang . $lsfx . $_Itemid;
				} else if (JCOMMENTS_JVERSION == '1.5') {
					$link = JURI::root(true) . '/index.php?option=com_jcomments&amp;tmpl=component'.$lang.$lsfx;
				} else {
					$link = JRoute::_('index.php?option=com_jcomments&amp;tmpl=component'.$lang.$lsfx);
				}
				return JCommentsSecurity::fixAJAX($link);
				break;

			case 'ajax-backend':
				if (JCOMMENTS_JVERSION == '1.0') {
					$link = $app->getCfg('live_site') . '/administrator/index3.php?option=com_jcomments&amp;no_html=1';
				} else {
					$link = $app->getCfg('live_site') . '/administrator/index.php?option=com_jcomments&amp;tmpl=component&amp;'.JCommentsSecurity::getToken().'=1';
				}
				return JCommentsSecurity::fixAJAX($link);
				break;

			default:
				return '';
				break;
		}
	}

	/**
	 * Convert relative link to absolute (add http:// and site url)
	 * 
	 * @param string $link The relative url.
	 * @return string
	 */
	public static function getAbsLink($link)
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			global $mainframe;
			$url = $mainframe->getCfg('live_site') . '/';
		} else {
			$uri = JFactory::getURI();
			$url = $uri->toString(array('scheme' , 'user' , 'pass' , 'host' , 'port'));
		}

		if (strpos($link, $url) === false) {
			$link = $url . $link;
		}

		return $link;
	}
}

class JCommentsInput
{
	/**
	 * Fetches and returns a given variable.
	 *
	 * @param string $name Variable name
	 * @param mixed $default Default value if the variable does not exist
	 * @param string $hash Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @param string $type Return type for the variable, for valid values see {@link JFilterInput::clean()}
	 * @param int $mask Filter mask for the variable
	 * @return mixed Requested variable
	 */
	public static function getVar( $name, $default = null, $hash = 'default', $type = 'none', $mask = 0 )
	{
		if (JCOMMENTS_JVERSION == '1.0') {
			switch ($hash) {
				case 'GET' :
					$input = $_GET;
					break;
				case 'POST' :
					$input = $_POST;
					break;
				case 'FILES' :
					$input = $_FILES;
					break;
				case 'COOKIE' :
					$input = $_COOKIE;
					break;
				case 'ENV'    :
					$input = &$_ENV;
					break;
				case 'SERVER'    :
					$input = &$_SERVER;
					break;
				default:
					$input = $_REQUEST;
					break;
			}
			return mosGetParam($input, $name, $default, $mask);
		} else {
			return JRequest::getVar($name, $default, $hash, $type, $mask);
		}
	}
}

class JCommentsMail
{
    /**
     * Mail function (uses phpMailer)
     *
     * @param string $from From e-mail address
     * @param string $fromName From name
     * @param mixed $recipient Recipient e-mail address(es)
     * @param string $subject E-mail subject
     * @param string $body Message body
     * @param bool|int $mode false = plain text, true = HTML
     * @param mixed $cc CC e-mail address(es)
     * @param mixed $bcc BCC e-mail address(es)
     * @param mixed $attachment Attachment file name(s)
     * @param mixed $replyTo Reply to email address(es)
     * @param mixed $replyToName Reply to name(s)
     * @return boolean True on success
     */
	public static function send($from, $fromName, $recipient, $subject, $body, $mode=0, $cc=NULL, $bcc=NULL, $attachment=NULL, $replyTo=NULL, $replyToName=NULL )
	{
		if (JCOMMENTS_JVERSION == '1.5') {
			return JUTility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyTo, $replyToName );
		} else if (JCOMMENTS_JVERSION == '1.7') {
			$mailer = JFactory::getMailer();
			return $mailer->sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyTo, $replyToName );			
		}
		return mosMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyTo, $replyToName );
	}
}

function JCommentsRedirect( $url, $msg='' )
{
	if (JCOMMENTS_JVERSION == '1.0') {
		mosRedirect($url, $msg);
	} else {
		$app = JCommentsFactory::getApplication();
		if (strpos($url, 'index2.php') === 1) {
			$url = str_replace('index2.php', 'index.php', $url);
		}
		$app->redirect($url, $msg);
	}
}

class JCommentsMultilingual
{
	public static function isEnabled()
	{
		static $enabled = null;

		if (!isset($enabled)) {
			$app = JCommentsFactory::getApplication();
			$enabled = $app->getCfg('multilingual_support') == 1;

			if (JCOMMENTS_JVERSION == '1.7') {
				if ($app->isSite()) {
					$enabled = $app->getLanguageFilter();
				} else {
					$db = JFactory::getDBO();
					$db->setQuery("SELECT COUNT(*) FROM `#__extensions` WHERE `element` = 'languagefilter' AND `enabled` = 1");
					$enabled = $db->loadResult() > 0;
				}
			}

			// check if multilingual_support
			if ($enabled) {
				$config = JCommentsFactory::getConfig();
				$enabled = $config->get('multilingual_support', $enabled);
			}
		}
		return $enabled;
	}

	public static function getLanguage()
	{
		static $language = null;

		if (!isset($language)) {
			if (JCOMMENTS_JVERSION == '1.0') {
				global $mainframe;
				$language = $mainframe->getCfg('lang');
			} else {
				$lang = JFactory::getLanguage();
				$language = $lang->getTag();
			}
		}
		return $language;
	}

	public static function getLanguages()
	{
		$languages = array();

		if (JCOMMENTS_JVERSION == '1.7') {
			$db = JFactory::getDBO();
			$db->setQuery('select `title` as name, `lang_code` as value, `sef` as urlcode from `#__languages` where `published`=1');
			$languages = $db->loadObjectList();
		} else {
			$joomfish = JOOMLATUNE_JPATH_SITE.DS.'components'.DS.'com_joomfish'.DS.'joomfish.php';
			if (is_file($joomfish)) {
				$db = JCommentsFactory::getDBO();
				$db->setQuery("SELECT `name`, `code` as value, `shortcode` as urlcode FROM `#__languages` WHERE `active` = 1");
				$languages = $db->loadObjectList();

				if (!count($languages)) {
					$db->setQuery("SELECT CASE WHEN IFNULL(`title`, '') = '' THEN `title_native` ELSE `title` END as name, `lang_code` as value, `sef` as urlcode FROM `#__jf_languages` WHERE `published` = 1");
					$languages = $db->loadObjectList();
				}

				if (!count($languages)) {
					$db->setQuery("SELECT CASE WHEN IFNULL(`title`, '') = '' THEN `title_native` ELSE `title` END as name, `lang_code` as value, `sef` as urlcode FROM `#__languages` WHERE `published` = 1");
					$languages = $db->loadObjectList();
				}
			}
		}
		return $languages;
	}
}
?>
