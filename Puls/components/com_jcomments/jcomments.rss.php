<?php
/**
 * JComments - Joomla Comment System
 *
 * Export comments to RSS
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

class JoomlaTuneFeedItem
{
	var $title = "";
	var $link = "";
	var $description = "";
	var $author = "";
	var $category = "";
	var $pubDate = "";
	var $source = "";
}

class JoomlaTuneFeed
{
	var $encoding = "";
	var $timezone = "+0000";
	var $offset = "";
	var $title = "";
	var $link = "";
	var $syndicationURL = "";
	var $description = "";
	var $lastBuildDate = "";
	var $pubDate = "";
	var $copyright = "";
	var $items = array();

	function __construct()
	{
		$this->items = array();
	}

	function addItem( &$item )
	{
		$item->source = $this->link;
		$this->items[] = $item;
	}

	function htmlspecialchars($str)
	{
		return (strtoupper($this->encoding) == 'UTF-8') ? htmlspecialchars($str, ENT_COMPAT, 'UTF-8') : htmlspecialchars($str);
	}

	function setOffset($offset)
	{
		$h = abs(intval($offset));
		$m = abs(($offset - intval($offset)) * 60);
		$this->offset = $offset;
		$this->timezone = (($offset >= 0) ? '+' : '-') . sprintf("%02d%02d", $h, $m);
	}

	function toRFC822($date = 'now')
	{
		if ($date == 'now' || empty($date)) {
			$date = strtotime(gmdate("M d Y H:i:s", time()));
		} else if (is_string($date)) {
			$date = strtotime($date);
		}

		if ($this->offset != '') {
			$date = $date + $this->offset * 3600;
		}

		return str_replace('UTC', 'UT', date('D, d M Y H:i:s', $date) . ' ' . $this->timezone);
	}

	function render()
	{
		$this->link = str_replace('&', '&amp;', str_replace('&amp;', '&', $this->link));

		$feed  = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
		$feed .= "	<channel>\n";
		$feed .= "		<title>".$this->title."</title>\n";
		$feed .= "		<description>".$this->description."</description>\n";
		$feed .= "		<link>".$this->link."</link>\n";
		$feed .= "		<lastBuildDate>".$this->htmlspecialchars($this->toRFC822())."</lastBuildDate>\n";
		$feed .= "		<generator>JComments</generator>\n";

		if ($this->syndicationURL != '') {
			$feed .= "		<atom:link href=\"".str_replace(' ', '%20', $this->syndicationURL)."\" rel=\"self\" type=\"application/rss+xml\" />\n";
		}

		foreach ($this->items as $item) {
			$item->link = str_replace('&', '&amp;', str_replace('&amp;', '&', $item->link));

			$feed .= "		<item>\n";
			$feed .= "			<title>".$this->htmlspecialchars(strip_tags($item->title))."</title>\n";
			$feed .= "			<link>".$item->link."</link>\n";
			$feed .= "			<description><![CDATA[".$item->description."]]></description>\n";

			if ($item->author != "") {
				$feed .= "			<dc:creator>".$this->htmlspecialchars($item->author)."</dc:creator>\n";
			}

			if ($item->pubDate != "") {
				$feed .= "			<pubDate>".$this->htmlspecialchars($this->toRFC822($item->pubDate))."</pubDate>\n";
			}
			$feed .= "			<guid>" . $item->link . "</guid>\n";
			$feed .= "		</item>\n";
		}
		$feed .= "	</channel>\n";
		$feed .= "</rss>\n";
		return $feed;
	}

	function display()
	{
		if (!headers_sent()) {
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 900) . ' GMT');
			header('Content-Type: application/xml');
		}
		echo $this->render();
	}
}

class JCommentsRSS
{
	public static function showObjectComments()
	{
		$config = JCommentsFactory::getConfig();

		if ($config->get('enable_rss') == '1') {

			$app = JCommentsFactory::getApplication('site');
			$object_id = (int) JCommentsInput::getVar('object_id', 0);
			$object_group = JCommentsSecurity::clearObjectGroup(JCommentsInput::getVar('object_group', 'com_content'));
			$limit = (int) JCommentsInput::getVar('limit', $config->getInt('feed_limit', 100));

			// if no group or id specified - return 404
			if ($object_id == 0 || $object_group == '') {
				self::showNotFound();
				return;
			}

			if (JCOMMENTS_JVERSION == '1.0') {
				$offset = $app->getCfg('offset') + date('O') / 100;
			} else {
				$offset = $app->getCfg('offset');
			}

			$lm = $limit != $config->getInt('feed_limit') ? ('&amp;limit='.$limit) : '';

			if (JCommentsMultilingual::isEnabled()) {
				$language = JCommentsMultilingual::getLanguage();
				$lp = '&amp;lang=' . $language;
			} else {
				$language = null;
				$lp = '';
			}

			if (JCOMMENTS_JVERSION == '1.0') {
				$syndicationURL = $app->getCfg('live_site') . '/index2.php?option=com_jcomments&amp;task=rss&amp;object_id='.$object_id.'&amp;object_group='.$object_group.$lm.$lp.'&amp;no_html=1';
			} else {
				$liveSite = str_replace(JURI::root(true), '', $app->getCfg('live_site'));
				$syndicationURL = $liveSite . JRoute::_('index.php?option=com_jcomments&amp;task=rss&amp;object_id='.$object_id.'&amp;object_group='.$object_group.$lm.$lp.'&amp;format=raw');
			}

			$object_title = JCommentsObjectHelper::getTitle($object_id, $object_group, $language);
			$object_link = JCommentsFactory::getAbsLink(JCommentsObjectHelper::getLink($object_id, $object_group, $language));

			$rss = new JoomlaTuneFeed();
			$rss->setOffset($offset);
			$rss->encoding = JCOMMENTS_ENCODING;
			$rss->title = $object_title;
			$rss->link = $object_link;
			$rss->syndicationURL = $syndicationURL;
			$rss->description = JText::sprintf('OBJECT_FEED_DESCRIPTION', $rss->title);

			$object_link = str_replace('amp;', '', $object_link);

			$options = array();
			$options['object_id'] = $object_id;
			$options['object_group'] = $object_group;
			$options['lang'] = $language;
			$options['published'] = 1;
			$options['filter'] = 'c.deleted = 0';
			$options['orderBy'] = 'c.date DESC';
			$options['limit'] = $limit;
			$options['limitStart'] = 0;
			$options['objectinfo'] = true;

			$rows = JCommentsModel::getCommentsList($options);

			$word_maxlength = $config->getInt('word_maxlength');

			foreach ($rows as $row) {
				$comment = JCommentsText::cleanText($row->comment);
				$title = $row->title;
				$author = JComments::getCommentAuthorName($row);

				if ($comment != '') {
					// apply censor filter
					$title = JCommentsText::censor($title);
					$comment = JCommentsText::censor($comment);

					// fix long words problem
					if ($word_maxlength > 0) {
						$comment = JCommentsText::fixLongWords($comment, $word_maxlength, ' ');
						if ($title != '') {
							$title = JCommentsText::fixLongWords($title, $word_maxlength, ' ');
						}
					}

					$item = new JoomlaTuneFeedItem();
					$item->title = ($title != '') ? $title : JText::sprintf('OBJECT_FEED_ITEM_TITLE', $author);
					$item->link = $object_link . '#comment-' . $row->id;
					$item->description = $comment;
					$item->source = $object_link;

					if (JCOMMENTS_JVERSION == '1.0') {
						$date = strtotime($row->date) - $offset * 3600;
						$item->pubDate = date('Y-m-d H:i:s', $date);
					} else {
						$item->pubDate = $row->date;
					}

					$item->author = $author;
					$rss->addItem($item);
				}
			}

			$rss->display();

			unset($rows, $rss);
			exit();
		}
	}

	public static function showAllComments()
	{
		$config = JCommentsFactory::getConfig();

		if ($config->get('enable_rss') == '1') {

			$app = JCommentsFactory::getApplication('site');
			$acl = JCommentsFactory::getACL();
			$object_group = trim(strip_tags(JCommentsInput::getVar('object_group', '')));
			$object_group = preg_replace('#[^0-9A-Za-z\-\_\,\.]#is', '', $object_group);
			$limit = (int) JCommentsInput::getVar('limit', $config->getInt('feed_limit', 100));

			if (JCOMMENTS_JVERSION == '1.0') {
				$offset = $app->getCfg('offset') + date('O') / 100;
			} else {
				$offset = $app->getCfg('offset');
			}

			$og = $object_group ? ('&amp;object_group='.$object_group) : '';
			$lm = $limit != $config->getInt('feed_limit') ? ('&amp;limit='.$limit) : '';

			if (JCommentsMultilingual::isEnabled()) {
				$language = JCommentsMultilingual::getLanguage();
				$lp = '&amp;lang=' . $language;
			} else {
				$language = null;
				$lp = '';
			}

			if (JCOMMENTS_JVERSION == '1.0') {
				$syndicationURL = $app->getCfg('live_site') . '/index2.php?option=com_jcomments&amp;task=rss_full'.$og.$lm.$lp.'&amp;no_html=1';
			} else {
				$liveSite = str_replace(JURI::root(true), '', $app->getCfg('live_site'));
				$syndicationURL = $liveSite . JRoute::_('index.php?option=com_jcomments&amp;task=rss_full'.$og.$lm.$lp.'&amp;tmpl=raw');
			}

			$rss = new JoomlaTuneFeed();
			$rss->setOffset($offset);
			$rss->encoding = JCOMMENTS_ENCODING;
			$rss->title = JText::sprintf('SITE_FEED_TITLE', $app->getCfg('sitename'));
			$rss->link = $app->getCfg('live_site');
			$rss->syndicationURL = $syndicationURL;
			$rss->description = JText::sprintf('SITE_FEED_DESCRIPTION', $app->getCfg('sitename'));

			if ($object_group != '') {
				$groups = explode(',', $object_group);
			} else {
				$groups = array();
			}

			$options = array();
			$options['object_group'] = $groups;
			$options['lang'] = $language;
			$options['published'] = 1;
			$options['filter'] = 'c.deleted = 0';
			$options['orderBy'] = 'c.date DESC';
			$options['votes'] = false;
			$options['limit'] = $limit;
			$options['limitStart'] = 0;
			$options['objectinfo'] = true;
			$options['access'] = $acl->getUserAccess();

			$rows = JCommentsModel::getCommentsList($options);

			$word_maxlength = $config->getInt('word_maxlength');

			foreach ($rows as $row) {
				$comment = JCommentsText::cleanText($row->comment);

				if ($comment != '') {
					// getting object's information (title and link)					
					$object_title = empty($row->object_title) ? JCommentsObjectHelper::getTitle($row->object_id, $row->object_group, $language) : $row->object_title;
					$object_link = empty($row->object_link) ? JCommentsObjectHelper::getLink($row->object_id, $row->object_group, $language) : $row->object_link;
					$object_link = JCommentsFactory::getAbsLink(str_replace('amp;', '', $object_link));

					// apply censor filter
					$object_title = JCommentsText::censor($object_title);
					$comment = JCommentsText::censor($comment);

					// fix long words problem
					if ($word_maxlength > 0) {
						$comment = JCommentsText::fixLongWords($comment, $word_maxlength, ' ');
						if ($object_title != '') {
							$object_title = JCommentsText::fixLongWords($object_title, $word_maxlength, ' ');
						}
					}

					$author = JComments::getCommentAuthorName($row);

					$item = new JoomlaTuneFeedItem();
					$item->title = $object_title;
					$item->link = $object_link . '#comment-' . $row->id;
					$item->description = JText::sprintf('SITE_FEED_ITEM_DESCRIPTION', $author, $comment);
					$item->source = $object_link;

					if (JCOMMENTS_JVERSION == '1.0') {
						$date = strtotime((string) $row->date) - $offset * 3600;
						$item->pubDate = date('Y-m-d H:i:s', $date);
					} else {
						$item->pubDate = $row->date;
					}

					$item->author = $author;
					$rss->addItem($item);
				}
			}

			$rss->display();

			unset($rows, $rss);
			exit();
		}
	}

	public static function showUserComments()
	{
		$config = JCommentsFactory::getConfig();

		if ($config->get('enable_rss') == '1') {

			$app = JCommentsFactory::getApplication('site');
			$acl = JCommentsFactory::getACL();
			$userid = (int) JCommentsInput::getVar('userid', 0);
			$limit = (int) JCommentsInput::getVar('limit', $config->getInt('feed_limit', 100));

			$user = JCommentsFactory::getUser($userid);
			if (!isset($user->id)) {
				self::showNotFound();
				return;
			}

			if (JCOMMENTS_JVERSION == '1.0') {
				$offset = $app->getCfg('offset') + date('O') / 100;
			} else {
				$offset = $app->getCfg('offset');
			}

			$lm = $limit != $config->getInt('feed_limit') ? ('&amp;limit='.$limit) : '';

			if (JCommentsMultilingual::isEnabled()) {
				$language = JCommentsMultilingual::getLanguage();
				$lp = '&amp;lang=' . $language;
			} else {
				$language = null;
				$lp = '';
			}

			if (JCOMMENTS_JVERSION == '1.0') {
				$syndicationURL = $app->getCfg('live_site') . '/index2.php?option=com_jcomments&amp;task=rss_user&amp;userid='.$userid.$lm.$lp.'&amp;no_html=1';
			} else {
				$liveSite = str_replace(JURI::root(true), '', $app->getCfg('live_site'));
				$syndicationURL = $liveSite . JRoute::_('index.php?option=com_jcomments&amp;task=rss_user&amp;userid='.$userid.$lm.$lp.'&amp;tmpl=raw');
			}

			$user->userid = $user->id;
			$username = JComments::getCommentAuthorName($user);

			$rss = new JoomlaTuneFeed();
			$rss->setOffset($offset);
			$rss->encoding = JCOMMENTS_ENCODING;
			$rss->title = JText::sprintf('USER_FEED_TITLE', $username);
			$rss->link = $app->getCfg('live_site');
			$rss->syndicationURL = $syndicationURL;
			$rss->description = JText::sprintf('USER_FEED_DESCRIPTION', $username);

			$options = array();
			$options['lang'] = $language;
			$options['userid'] = $userid;
			$options['published'] = 1;
			$options['filter'] = 'c.deleted = 0';
			$options['orderBy'] = 'c.date DESC';
			$options['votes'] = false;
			$options['limit'] = $limit;
			$options['limitStart'] = 0;
			$options['objectinfo'] = true;
			$options['access'] = $acl->getUserAccess();

			$rows = JCommentsModel::getCommentsList($options);

			$word_maxlength = $config->getInt('word_maxlength');
			$lang = JCommentsMultilingual::isEnabled() ? JCommentsMultilingual::getLanguage() : null;

			foreach ($rows as $row) {
				$comment = JCommentsText::cleanText($row->comment);

				if ($comment != '') {
					// getting object's information (title and link)
					$object_title = empty($row->object_title) ? JCommentsObjectHelper::getTitle($row->object_id, $row->object_group, $lang) : $row->object_title;
					$object_link = empty($row->object_link) ? JCommentsObjectHelper::getLink($row->object_id, $row->object_group, $lang) : $row->object_link;
					$object_link = JCommentsFactory::getAbsLink(str_replace('amp;', '', $object_link));

					// apply censor filter
					$object_title = JCommentsText::censor($object_title);
					$comment = JCommentsText::censor($comment);

					// fix long words problem
					if ($word_maxlength > 0) {
						$comment = JCommentsText::fixLongWords($comment, $word_maxlength, ' ');
						if ($object_title != '') {
							$object_title = JCommentsText::fixLongWords($object_title, $word_maxlength, ' ');
						}
					}

					$author = JComments::getCommentAuthorName($row);

					$item = new JoomlaTuneFeedItem();
					$item->title = $object_title;
					$item->link = $object_link . '#comment-' . $row->id;
					$item->description = JText::sprintf('USER_FEED_ITEM_DESCRIPTION', $author, $comment);
					$item->source = $object_link;

					if (JCOMMENTS_JVERSION == '1.0') {
						$date = strtotime((string) $row->date) - $offset * 3600;
						$item->pubDate = date('Y-m-d H:i:s', $date);
					} else {
						$item->pubDate = $row->date;
					}

					$item->author = $author;
					$rss->addItem($item);
				}
			}

			$rss->display();

			unset($rows, $rss);
			exit();
		}
	}

	protected static function showNotFound()
	{
		header('HTTP/1.0 404 Not Found');

		if (JCOMMENTS_JVERSION != '1.0') {
			$message = JCOMMENTS_JVERSION == '1.5' ? 'Resource Not Found' : 'JGLOBAL_RESOURCE_NOT_FOUND';
			JError::raiseError(404, $message);
		}
		exit(404);
	}
}
?>