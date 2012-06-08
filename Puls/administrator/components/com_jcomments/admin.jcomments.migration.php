<?php
/**
 * JComments - Joomla Comment System
 *
 * Migration wizard (import comments for 3d party extensions)
 *
 * @version 2.3
 * @package JComments
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2012 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

class JCommentsImportedComment extends JCommentsTableComment
{
	/** @var int */
	var $source_id = null;

	function JCommentsImportedComment(&$db)
	{
		parent::JCommentsTableComment($db);
	}

	function store($updateNulls = false)
	{
		if ($this->source != '') {
			if ($this->name == '') {
				$this->name = 'Guest';
			} else {
				$this->name = JCommentsMigrationTool::processName($this->name);
			}

			if ($this->username == '') {
				$this->username = $this->name;
			} else {
				$this->username = JCommentsMigrationTool::processName($this->username);
			}

			$this->email = strip_tags($this->email);
			$this->homepage = strip_tags($this->homepage);
			$this->title = strip_tags($this->title);
			$this->comment = JCommentsMigrationTool::processComment(stripslashes($this->comment));

			if (!isset($this->source_id)) {
				$this->source_id = 0;
			}
		}
		return parent::store($updateNulls);
	}
}

class JOtherCommentSystem
{
	var $code = null;
	var $name = null;
	var $author = null;
	var $license = null;
	var $license_url = null;
	var $homepage = null;
	var $table = null;
	var $found = false;
	var $count = 0;

	function JOtherCommentSystem($code, $name, $author, $license, $license_url, $homepage, $table)
	{
		$this->code = $code;
		$this->name = $name;
		$this->author = $author;
		$this->homepage = $homepage;
		$this->license = $license;
		$this->license_url = $license_url;
		$this->table = $table;
	}

	function UpdateCount()
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery('SELECT COUNT(*) FROM ' . $this->table . (empty($filter) ? '' : ' WHERE ' . $filter));
		$this->count = $db->loadResult();
	}
}

class yvCommentSystem extends JOtherCommentSystem
{
	function UpdateCount()
	{
		$this->count = $this->getCount();
	}

	function getCount()
	{
		$yvHelper = JPATH_SITE . DS . 'components' . DS . 'com_yvcomment' . DS . 'helpers.php';
		if (is_file($yvHelper)) {
			require_once($yvHelper);
			$yvComment = yvCommentHelper::getInstance();

			$where = array();

			if (JCOMMENTS_JVERSION == '1.5') {
				if ($yvComment->UseDesignatedSectionForComments()) {
					$where[] = "(c.sectionid=" . $yvComment->getSectionForComments() . ")";
				} else {
					$where[] = '(c.parentid<>0)';
				}
			} else {
				$where[] = '(c.parentid<>0)';
			}

			$db = JCommentsFactory::getDBO();
			$query = "SELECT count(*)"
					. "\nFROM " . $yvComment->getTableName() . " AS c"
					. "\nLEFT JOIN #__content AS ar ON c.parentid=ar.id"
					. "\nWHERE " . implode(' AND ', $where);
			$db->setQuery($query);
			return $db->loadResult();
		}
		return 0;
	}

	public static function getList($start = 0, $limit = 100)
	{
		$yvHelper = JPATH_SITE . DS . 'components' . DS . 'com_yvcomment' . DS . 'helpers.php';
		if (is_file($yvHelper)) {
			require_once($yvHelper);
			$yvComment = yvCommentHelper::getInstance();

			$where = array();

			if (JCOMMENTS_JVERSION == '1.5') {
				if ($yvComment->UseDesignatedSectionForComments()) {
					$where[] = "(c.sectionid=" . $yvComment->getSectionForComments() . ")";
				} else {
					$where[] = '(c.parentid<>0)';
				}
			} else {
				$where[] = '(c.parentid<>0)';
			}

			$query = "SELECT c.*, u.email as email, u.name, u.username"
					. "\nFROM " . $yvComment->getTableName() . " AS c"
					. "\nLEFT JOIN #__content AS ar ON c.parentid=ar.id"
					. "\nLEFT JOIN #__users AS u ON c.created_by = u.id"
					. "\nWHERE " . implode(' AND ', $where)
					. "\nORDER BY c.`created`";

			$db = JCommentsFactory::getDBO();
			$db->setQuery($query, $start, $limit);
			return $db->loadObjectList();
		}
		return array();
	}
}

class JCommentsMigrationTool
{
	var $db = null;
	var $source = '';
	var $language = '';
	var $start = 0;
	var $limit = 500;
	var $total = 0;
	var $imported = 0;

	function JCommentsMigrationTool($source, $language, $start)
	{
		$this->db = JCommentsFactory::getDBO();
		$this->source = preg_replace('#[^0-9A-Za-z\-\_\.]#is', '', $source);
		$this->language = preg_replace('#[^0-9A-Za-z\-\_\.]#is', '', $language);
		$this->start = (int) $start;

		if (empty($this->language)) {
			if (JCOMMENTS_JVERSION == '1.0') {
			 	$this->language = JCommentsMultilingual::getLanguage();
			} else {
				$params = JComponentHelper::getParams('com_languages');
				$this->language = $params->get("site", 'en-GB');
			}
		}

		$sources = $this->getSources();
		foreach ($sources as $source) {
			if ($source->code == $this->source) {
				$source->UpdateCount();
				$this->total = $source->count;
				break;
			}
		}
		unset($sources);
	}

	function getSource()
	{
		return $this->source;
	}

	function getLanguage()
	{
		return $this->language;
	}

	function getStart()
	{
		return $this->start;
	}

	function getLimit()
	{
		return $this->limit;
	}

	function getTotal()
	{
		return $this->total;
	}
	
	function getImported()
	{
		return $this->imported;
	}

	function import()
	{
		if ($this->source != '' && $this->source != 'com_jcomments') {
			$methodName = 'import' . $this->source;
			if (method_exists($this, $methodName)) {
				if ($this->start == 0) {
					$this->deleteCommentsBySource(strtolower($this->source));
				}

				$this->$methodName(strtolower($this->source), $this->language, $this->start, $this->limit);
				$this->imported = $this->countCommentsBySource(strtolower($this->source));

				if ($this->total == $this->imported) {
					$this->updateParent(strtolower($this->source));
				}
			}
		}
	}


	public static function showImport()
	{
		$sources = JCommentsMigrationTool::getSources();
		HTML_JCommentsMigrationTool::showImport($sources);
	}

	function getSources()
	{
		$CommentSystems = array();
		
		$CommentSystems[] = new JOtherCommentSystem( 
						'AkoComment'
						, 'AkoComment'
						, 'Arthur Konze'
						, 'http://www.konze.de/content/view/8/26/'
						, 'http://www.konze.de/content/view/8/26/'
						, 'http://mamboportal.com'
						, '#__akocomment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'MosCom'
						, 'MosCom'
						, 'Chanh Ong'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://ongetc.com'
						, '#__content_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'ComboMax'
						, 'ComboMax'
						, 'Phil Taylor'
						, 'Commercial (22.50 GPB)'
						, ''
						, 'http://www.phil-taylor.com/Joomla/Components/ComboMAX/'
						, '#__combomax'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'JoomlaComment'
						, 'JoomlaComment'
						, 'Frantisek Hliva'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://cavo.co.nr'
						, '#__comment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'mXcomment'
						, 'mXcomment'
						, 'Bernard Gilly'
						, 'Creative Commons'
						, ''
						, 'http://www.visualclinic.fr'
						, '#__mxc_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'JomComment'
						, 'JomComment'
						, 'Azrul Rahim'
						, 'Commercial/Free'
						, ''
						, 'http://www.azrul.com'
						, '#__jomcomment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'jxtendedcomments'
						, 'JXtended Comments'
						, 'JXtended LLC'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://jxtended.com/products/comments.html'
						, '#__jxcomments_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'chronocomments'
						, 'Chrono Comments'
						, 'Chronoman'
						, 'CC'
						, ''
						, 'http://www.chronoengine.com/'
						, '#__chrono_comments'
						);


		$CommentSystems[] = new JOtherCommentSystem( 
						'jacomment'
						, 'JA Comment'
						, 'JoomlArt'
						, 'Copyrighted Commercial Software'
						, ''
						, 'www.joomlart.com'
						, '#__jacomment_items'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'sliComments'
						, 'sliComments'
						, 'Jonnathan Soares Lima'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-3.0.html'
						, 'https://github.com/jonnsl/sliComments'
						, '#__slicomments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'UdjaComments'
						, 'Udja Comments'
						, 'Andy Sharman'
						, 'GNU/GPL2+'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.udjamaflip.com'
						, '#__udjacomments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'DatsoGallery'
						, 'DatsoGallery comments'
						, 'Andrey Datso'
						, 'Free'
						, ''
						, 'http://www.datso.fr'
						, '#__datsogallery_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'JoomGallery'
						, 'JoomGallery comments'
						, 'M. Andreas Boettcher'
						, 'Free'
						, ''
						, 'http://www.joomgallery.net'
						, '#__joomgallery_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'IceGallery'
						, 'IceGallery comments'
						, 'Markus Donhauser'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://joomlacode.org/gf/project/ice/'
						, '#__ice_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'Remository'
						, 'Remository file reviews'
						, 'Martin Brampton'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.remository.com'
						, '#__downloads_reviews'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'PAXXGallery'
						, 'PAXXGallery comments'
						, 'Tobias Floery'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.paxxgallery.com'
						, '#__paxxcomments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'PhocaGallery'
						, 'PhocaGallery Category Comments'
						, 'Jan Pavelka'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.phoca.cz'
						, '#__phocagallery_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'PhocaGalleryImage'
						, 'PhocaGallery Image Comments'
						, 'Jan Pavelka'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.phoca.cz'
						, '#__phocagallery_img_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'JMovies'
						, 'JMovies comments'
						, 'Luscarpa &amp; Vamba'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.jmovies.eu/'
						, '#__jmovies_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'Cinema'
						, 'Cinema comments'
						, 'Vamba'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.joomlaitalia.com'
						, '#__cinema_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'MosetsTree'
						, 'Mosets Tree reviews'
						, 'Mosets Consulting'
						, 'Commercial'
						, ''
						, 'http://www.mosets.com'
						, '#__mt_reviews'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'LinkDirectory'
						, 'LinkDirectory link comments'
						, 'Soner Ekici'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.sonerekici.com/'
						, '#__ldcomment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'zOOmMediaGallery'
						, 'zOOm Media Gallery comments'
						, 'Mike de Boer'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.zoomfactory.org/'
						, '#__zoom_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'rsgallery2'
						, 'RSGallery2 comments'
						, 'rsgallery2.net'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://rsgallery2.net/'
						, '#__rsgallery2_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'hotornot2'
						, 'Hotornot2 comments'
						, 'Aron Watson'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://joomlacode.org/gf/project/com_hotornot2/frs/'
						, '#__hotornot_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'easycomments'
						, 'EasyComments (www.easy-joomla.org)'
						, 'EasyJoomla'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.easy-joomla.org/'
						, '#__easycomments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'musicbox'
						, 'MusicBox'
						, 'Vamba'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.joomlaitalia.com'
						, '#__musicboxrewiev'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'jreviews'
						, 'JReviews'
						, 'Alejandro Schmeichler'
						, 'Commercial'
						, ''
						, 'http://www.reviewsforjoomla.com'
						, '#__jreviews_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'tutorials'
						, 'Tutorials (comments for items)'
						, 'NSOrg Project'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.nsorg.com'
						, '#__tutorials_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'idoblog'
						, 'IDoBlog'
						, 'Sunshine studio'
						, 'GNU/GPL'
						, ''
						, 'http://idojoomla.com'
						, '#__idoblog_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'sobi2reviews'
						, 'SOBI2 Reviews'
						, 'SOBI2 Developer Team'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.sigsiu.net'
						, '#__sobi2_plugin_reviews'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'jreactions'
						, 'J! Reactions'
						, 'SDeCNet Software'
						, ''
						, ''
						, 'http://jreactions.sdecnet.com'
						, '#__jreactions'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'virtuemart'
						, 'VirtueMart product reviews'
						, 'The VirtueMart Development Team'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.virtuemart.net'
						, '#__vm_product_reviews'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'akobook'
						, 'AkoBook'
						, 'Arthur Konze'
						, ''
						, ''
						, 'http://mamboportal.com'
						, '#__akobook'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'jambook'
						, 'JamBook'
						, 'Olle Johansson'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.jxdevelopment.com/'
						, '#__jx_jambook'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'k2'
						, 'K2 Comments'
						, 'JoomlaWorks'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://k2.joomlaworks.gr/'
						, '#__k2_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'smartblog'
						, 'SmartBlog Comments'
						, 'Aneesh S'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.aarthikaindia.com'
						, '#__blog_comment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'urcomment'
						, 'UrComment'
						, 'Comdev Software Sdn Bhd'
						, 'GPL, Commercial Software'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://joomla.comdevweb.com'
						, '#__urcomment'
						);

		$CommentSystems[] = new yvCommentSystem( 
						'yvcomment'
						, 'yvComment'
						, 'Yuri Volkov'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://yurivolkov.com/Joomla/yvComment/index_en.html'
						, '#__yvcomment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'zimbcomment'
						, 'ZiMB Comment'
						, 'ZiMB LLC'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.zimbllc.com/Software/zimbcomment'
						, '#__zimbcomment_comment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'rdbscomment'
						, 'RDBS Commment'
						, 'Robert Deutz'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.rdbs.de'
						, '#__rdbs_comment_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'lyftenbloggie'
						, 'LyftenBloggie'
						, 'Lyften Designs'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.lyften.com'
						, '#__bloggies_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'webeecomment'
						, 'Webee Comment'
						, 'Onno Groen'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.onnogroen.nl/webee/'
						, '#__webeecomment_comment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'resource'
						, 'MightyExtensions Resource comments'
						, 'MightyExtensions'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://mightyextensions.com/'
						, '#__js_res_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'tpdugg'
						, 'TPDugg'
						, 'TemplatePlazza'
						, ''
						, ''
						, 'http://templateplazza.com/'
						, '#__tpdugg_comments'
						);
						
		$CommentSystems[] = new JOtherCommentSystem( 
						'zoo'
						, 'ZOO Comments'
						, 'YOOtheme'
						, 'GNU/GPLv2'
						, 'http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only'
						, 'http://www.yootheme.com/zoo/'
						, '#__zoo_comment'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'beeheard'
						, 'BeeHeard Comments'
						, 'Kaysten Mazerino'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://www.cmstactics.com'
						, '#__beeheard_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'jmylife'
						, 'JMyLife Comments'
						, 'Jeff Channell'
						, 'GNU/GPL'
						, 'http://www.gnu.org/copyleft/gpl.html'
						, 'http://jeffchannell.com'
						, '#__jmylife_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'musiccollection'
						, 'Music Colllection Comments'
						, 'Germinal Camps'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.joomlamusicsolutions.com'
						, '#__muscol_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'rscomments'
						, 'RSComments'
						, 'RSJoomla'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.rsjoomla.com/joomla-components/joomla-comments.html'
						, '#__rscomments_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'rsicomments'
						, 'rsiComments'
						, 'Rockford Solutions, Inc.'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.rockfordsolutionsinc.com/'
						, '#__rsi_comments'
						);
		$CommentSystems[] = new JOtherCommentSystem( 
						'hekimablog'
						, 'HekimaBlog Comments'
						, 'Irina Popova'
						, 'GNU/GPL'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://hekima.ru'
						, '#__hekima_blog_comments'
						);

		$CommentSystems[] = new JOtherCommentSystem( 
						'easyblog'
						, 'EasyBlog Comments'
						, 'Stack Ideas'
						, 'GPL License'
						, 'http://www.gnu.org/licenses/gpl-2.0.html'
						, 'http://www.stackideas.com'
						, '#__easyblog_comment'
						);

		return $CommentSystems;
	}

	public static function processComment( $str )
	{
		// change \n to <br />	
		$str = preg_replace( array( '/\r/', '/^\n+/', '/\n+$/' ), '', $str);
		$str = preg_replace('/\n/', '<br />', $str);

		// strip BBCode's
		$patterns = array( 
					  '/\[font=(.*?)\](.*?)\[\/font\]/i'
					, '/\[size=(.*?)\](.*?)\[\/size\]/i'
					, '/\[color=(.*?)\](.*?)\[\/color\]/i'
					, '/\[b\](null|)\[\/b\]/i'
					, '/\[i\](null|)\[\/i\]/i'
					, '/\[u\](null|)\[\/u\]/i'
					, '/\[s\](null|)\[\/s\]/i'
					, '/\[url=null\]null\[\/url\]/i'
					, '/\[img\](null|)\[\/img\]/i'
					, '/\[url=(.*?)\](.*?)\[\/url\]/i'
					, '/\[email](.*?)\[\/email\]/i'
					// JA Comment syntax
					, '/\[quote=\"?([^\:\]]+)(\:[0-9]+)?\"?\]/ism'
					, '/\[link=\"?([^\]]+)\"?\]/ism'
					, '/\[\/link\]/ism'
					, '/\[youtube ([^\s]+) youtube\]/ism'
					);

		$replacements = array(
					  '\\2'
					, '\\2'
					, '\\2'
					, ''
					, ''
					, ''
					, ''
					, ''
					, ''
					, '\\2 ([url]\\1[/url])'
					, '\\1'
					, '[quote name="\\1"]'
					, '[url=\\1]'
					, '[/url]'
					, '[youtube]\\1[/youtube]'
					);
		$str = preg_replace( $patterns, $replacements, $str);

		//convert smiles 
		$patterns = array( 
					  '/\:eek/i'
					, '/\:roll/i'
					, '/\:sigh/i'
					, '/\:grin/i'
					, '/\:p/i'
					, '/\:0\s/i'
					, '/\:cry/i'
					, '/\:\'\(/i'
					, '/\:upset/i'
					, '/\>\:\(/i'
					, '/\:\(/i'
					, '/\:\)/i'
					, '/\;\)/i'
					, '/\:x/i'
					, '/\:\?/i'
					, '/\;\?/i'
					, '/\:\-\\\\/i'
					, '/\;D/i'
					, '/\:angry\:/i'
					, '/\:angry-red\:/i'
					, '/\:sleep\:/i'
					);

		$replacements = array( 
					  ':eek:'
					, ':roll:'
					, ':sigh:'
					, ':D'
					, ':P'
					, ':o '
					, ':cry:'
					, ':cry:'
					, ''
					, ':sad:'
					, ':sad:'
					, ':-)'
					, ';-)'
					, ':-x'
					, ':-?'
					, ':-?'
					, ':sigh:'
					, ';-)'
					, ':sad:'
					, ':sad:'
					, ':zzz'
					);

		$str = preg_replace( $patterns, $replacements, $str);

		return $str;
	}

	public static function processName( $str )
	{
		return preg_replace("/[\'\"\>\<\(\)\[\]]?+/i", '', $str );
	}

	function updateParent($source)
	{
		$db = JCommentsFactory::getDBO();
		$query = "UPDATE `#__jcomments` c1, `#__jcomments` c2"
				. " SET c1.parent = c2.id"
				. " WHERE c1.source = c2.source"
				. " AND c1.id <> c2.id"
				. " AND c1.parent <> 0"
				. " AND c1.parent = c2.source_id"
				. " AND c1.source = " . $db->Quote($source);
		$db->setQuery($query);
		$db->query();
	}

	function deleteCommentsBySource($source)
	{
		if (trim($source) != '') {
			$db = JCommentsFactory::getDBO();
			$db->setQuery("DELETE FROM #__jcomments WHERE source = " . $db->Quote($source));
			$db->query();
			$db->setQuery("DELETE FROM #__jcomments_subscriptions WHERE source = " . $db->Quote($source));
			$db->query();
		}
	}

	function countCommentsBySource($source)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT COUNT(*) FROM `#__jcomments` WHERE source = ".$db->Quote($source));
		return $db->loadResult();
	}

	function importMosCom($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.id as userid"
				. "\n, u.email as user_email, u.name as user_name, u.username as user_username "
				. "\nFROM `#__content_comments` AS c"
				. "\nLEFT JOIN #__users AS u ON u.email=c.email AND u.name=c.name"
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);

			$comment->object_id = $row->articleid;
			$comment->object_group = 'com_content';
			$comment->userid = $row->userid;
			$comment->name = $row->name;
			$comment->email = $row->email;
			$comment->homepage = $row->homepage;
			$comment->comment = $row->entry;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", strtotime($row->date . ' ' . $row->time));
			$comment->source = $source;
			$comment->lang = $language;

			$comment->store();
		}
	}

	function importAkoComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__akocomment`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);

			$comment->object_id = $row->contentid;
			$comment->object_group = 'com_content';
			$comment->userid = isset($row->userid) ? $row->userid : (isset($row->iduser) ? $row->iduser : 0);
			$comment->name = $row->name;
			$comment->username = $comment->name;
			$comment->email = $row->email;
			$comment->homepage = $row->web;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;

			$comment->store();
		}
	}

	function importJoomlaComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.email as user_email, u.name as user_name, u.username as user_username "
				. "\nFROM `#__comment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id"
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);

			if (isset($row->component)) {
				if (trim($row->component) == '') {
					$row->component = 'com_content';
				}
			} else {
				$row->component = 'com_content';
			}

			$comment->object_id = $row->contentid;
			$comment->object_group = $row->component;
			$comment->parent = isset($row->parentid) ? $row->parentid : 0;
			$comment->userid = isset($row->userid) && strtolower($row->usertype) != 'unregistered' ? $row->userid : 0;
			$comment->name = $row->user_name ? $row->user_name : $row->name;
			$comment->username = $row->user_username ? $row->user_username : $row->name;
			$comment->email = $row->user_email ? $row->user_email : $row->email;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->homepage = isset($row->website) ? $row->website : '';
			$comment->isgood = isset($row->voting_yes) ? $row->voting_yes : 0;
			$comment->ispoor = isset($row->voting_no) ? $row->voting_no : 0;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;

			$comment->store();
		}

		if ($start == 0) {
			// import subscriptions
			$query = "INSERT INTO #__jcomments_subscriptions (`object_id`, `object_group`, `lang`, `userid`, `name`, `email`, `hash`, `published`, `source`)"
					. "\n SELECT DISTINCT contentid"
					. "\n , CASE WHEN component = '' THEN 'com_content' ELSE component END"
					. "\n , '$language'"
					. "\n , userid"
					. "\n , name"
					. "\n , email"
					. "\n , md5(concat(contentid,CASE WHEN component = '' THEN 'com_content' ELSE component END,userid,email,'$language'))"
					. "\n , 1"
					. "\n , '$source'"
					. "\n FROM #__comment c"
					. "\n WHERE `notify` = 1"
					. "\n AND NOT EXISTS"
					. "\n ( "
					. "\n   SELECT * FROM #__jcomments_subscriptions s"
					. "\n   WHERE s.object_id = c.contentid "
					. "\n   AND s.object_group = CASE WHEN c.component = '' THEN 'com_content' ELSE c.component END"
					. "\n   AND s.userid = c.userid"
					. "\n   AND s.email = c.email"
					. "\n ) ";
			$db->setQuery($query);
			$db->query();
		}
	}

	function importComboMax($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__combomax`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);

			$comment->object_id = $row->contentid;
			$comment->object_group = 'com_content';
			$comment->name = $row->name;
			$comment->username = $comment->name;
			$comment->email = $row->email;
			$comment->homepage = $row->url;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->published = $row->approved;
			$comment->date = $row->date;
			$comment->userid = $row->myid;
			$comment->source = $source;
			$comment->lang = $language;

			$comment->store();
		}
	}

	function importJomComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query	= "SELECT c.*"
			. "\n, u.email as user_email, u.name as user_name, u.username as user_username "
			. "\nFROM `#__jomcomment` AS c"
			. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id"
			. "\nORDER BY c.`date`"
			;
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);

			$comment->object_id = $row->contentid;
			$comment->object_group = (isset($row->option) && $row->option != '') ? $row->option : 'com_content';
			$comment->userid = $row->user_id;
			$comment->name = $row->user_name ? $row->user_name : $row->name;
			$comment->username = $row->user_username ? $row->user_username : $row->name;
			$comment->email = $row->user_email ? $row->user_email : $row->email;
			$comment->homepage = $row->website;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;

			$comment->store();
		}

		if ($start == 0) {
			// import subscriptions
			$query = "INSERT INTO #__jcomments_subscriptions (`object_id`, `object_group`, `lang`, `userid`, `name`, `email`, `hash`, `published`, `source`)"
				. "\n SELECT DISTINCT `contentid`"
				. "\n , `option`"
				. "\n , '$language'"
				. "\n , `userid`"
				. "\n , `name`"
				. "\n , `email`"
				. "\n , md5(concat(`contentid`,`option`,`userid`,`email`,'$language'))"
				. "\n , 1"
				. "\n , '$source'"
				. "\n FROM `#__jomcomment_subs` c"
				. "\n WHERE `status` = 1"
				. "\n AND NOT EXISTS"
				. "\n ( "
				. "\n   SELECT * FROM `#__jcomments_subscriptions` s"
				. "\n   WHERE s.`object_id` = c.`contentid`"
				. "\n   AND s.`object_group` = c.`option`"
				. "\n   AND s.`userid` = c.`userid`"
				. "\n   AND s.`email` = c.`email`"
				. "\n ) "
				;
			$db->setQuery($query);
			$db->query();
		}
	}

	function importmXcomment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.email as user_email, u.name as user_name, u.username as user_username "
				. "\nFROM #__mxc_comments AS c"
				. "\nLEFT JOIN #__users AS u ON c.iduser = u.id"
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->contentid;
			$comment->object_group = (isset($row->component) && $row->component != '') ? $row->component : 'com_content';
			$comment->parent = isset($row->parentid) ? $row->parentid : 0;
			$comment->userid = $row->iduser;
			$comment->name = $row->user_name ? $row->user_name : $row->name;
			$comment->username = $row->user_username ? $row->user_username : $row->name;
			$comment->email = $row->email;
			$comment->homepage = $row->web;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importDatsoGallery($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__datsogallery_comments` ORDER BY cmtdate", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->cmtpic;
			$comment->object_group = 'com_datsogallery';
			$comment->name = $row->cmtname;
			$comment->username = $comment->name;
			$comment->comment = $row->cmttext;
			$comment->ip = $row->cmtip;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", $row->cmtdate);
			$comment->source = $source;
			$comment->lang = $language;

			$comment->store();
		}
	}

	function importJoomGallery($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.id as userid, u.email, u.name, u.username "
				. "\nFROM #__joomgallery_comments AS c"
				. "\nLEFT JOIN #__users AS u ON c.userid = u.id "
				. "\nORDER BY c.`cmtdate`";

		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		$isJG155 = is_file(JPATH_SITE . DS . 'components' . DS . 'com_joomgallery' . DS . 'controller.php');

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->cmtpic;
			$comment->object_group = 'com_joomgallery';
			$comment->userid = $row->userid;
			$comment->name = isset($row->cmtname) ? $row->cmtname : $row->name;
			$comment->username = $comment->name;
			$comment->comment = $row->cmttext;
			$comment->ip = $row->cmtip;
			$comment->published = $row->published && $row->approved;
			$comment->date = $isJG155 ? $row->cmtdate : strftime("%Y-%m-%d %H:%M:00", $row->cmtdate);
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importIceGallery($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT c.* FROM #__ice_comments AS c ORDER BY c.`cmtdate`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->imgid;
			$comment->object_group = 'com_icegallery';
			$comment->name = $row->cmtname;
			$comment->username = $comment->name;
			$comment->comment = $row->cmtcontent;
			$comment->ip = $row->hostaddr;
			$comment->published = $row->published;
			$comment->date = $row->cmtdate;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importRemository($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.id as userid, u.email, u.name, u.username "
				. "\nFROM `#__downloads_reviews` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`date`";

		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->itemid;
			$comment->object_group = 'com_remository';
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->userid = $row->userid;
			$comment->email = $row->email;
			$comment->homepage = $row->userURL;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importPhocaGallery($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.id, u.email, u.name, u.username "
				. "\nFROM `#__phocagallery_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`date`";

		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->catid;
			$comment->object_group = 'com_phocagallery';
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->userid = $row->userid;
			$comment->email = $row->email;
			$comment->title = isset($row->title) ? $row->title : '';
			$comment->comment = $row->comment;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}

		$db = JCommentsFactory::getDBO();
		$db->setQuery("SHOW TABLES LIKE '%phocagallery_img_comments%';");
		$tables = $db->loadResultArray();

		if (count($tables)) {
		}

	}

	function importPhocaGalleryImage($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.id, u.email, u.name, u.username "
				. "\nFROM `#__phocagallery_img_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`date`";

		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->imgid;
			$comment->object_group = 'com_phocagallery_images';
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->userid = $row->userid;
			$comment->email = $row->email;
			$comment->title = isset($row->title) ? $row->title : '';
			$comment->comment = $row->comment;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importCinema($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT c.* FROM `#__cinema_comments` AS c ORDER BY c.`cmtdate`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->cmtpic;
			$comment->object_group = 'com_cinema';
			$comment->name = $row->cmtname;
			$comment->username = $comment->name;
			$comment->comment = $row->cmttext;
			$comment->ip = $row->cmtip;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", $row->cmtdate);
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importJMovies($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT c.* FROM `#__jmovies_comments` AS c ORDER BY c.`cmtdate`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->cmtpic;
			$comment->object_group = 'com_jmovies';
			$comment->name = $row->cmtname;
			$comment->username = $comment->name;
			$comment->comment = $row->cmttext;
			$comment->ip = $row->cmtip;
			$comment->userid = $row->cmtiduser;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", $row->cmtdate);
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importMosetsTree($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.email, u.name, u.username"
				. "\nFROM #__mt_reviews AS c"
				. "\nLEFT JOIN #__users AS u ON c.user_id = u.id "
				. "\nORDER BY c.`rev_date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->link_id;
			$comment->object_group = 'com_mtree';
			$comment->name = $row->user_id ? $row->name : $row->guest_name;
			$comment->username = $row->username ? $row->username : $comment->name;
			$comment->email = $row->email;
			$comment->comment = $row->rev_text;
			$comment->userid = $row->user_id;
			$comment->published = $row->rev_approved;
			$comment->date = $row->rev_date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importLinkDirectory($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.email, u.name, u.username"
				. "\nFROM `#__ldcomment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`rev_date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->link_id;
			$comment->object_group = 'com_linkdirectory';
			$comment->name = $row->user_id ? $row->name : $row->guest_name;
			$comment->username = $row->username ? $row->username : $comment->name;
			$comment->email = $row->email;
			$comment->comment = $row->rev_text;
			$comment->userid = $row->user_id;
			$comment->published = $row->rev_approved;
			$comment->date = $row->rev_date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importZoomMediaGallery($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT c.* FROM `#__zoom_comments` AS c ORDER BY c.`cmtdate`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->imgid;
			$comment->object_group = 'com_zoom';
			$comment->name = $row->cmtname;
			$comment->username = $comment->name;
			$comment->comment = $row->cmtcontent;
			$comment->published = 1;
			$comment->date = $row->cmtdate;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importRSGallery2($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__rsgallery2_comments` ORDER BY id", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = isset($row->picid) ? $row->picid : $row->item_id;
			$comment->object_group = 'com_rsgallery2';
			$comment->parent = isset($row->parent_id) ? $row->parent_id : 0;
			$comment->userid = isset($row->user_id) ? $row->user_id : 0;
			$comment->name = isset($row->name) ? $row->name : $row->user_name;
			$comment->username = $comment->name;
			$comment->comment = $row->comment;
			$comment->ip = isset($row->user_ip) ? $row->user_ip : '';
			$comment->published = 1;
			$comment->date = isset($row->date) ? $row->date : $row->datetime;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importHotOrNot2($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.email as user_email, u.name as user_name, u.username as user_username"
				. "\nFROM `#__hotornot_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.username = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->idx;
			$comment->object_group = 'com_hotornot2';
			$comment->name = $row->user_name != '' ? $row->user_name : 'Guest';
			$comment->username = $row->user_username != '' ? $row->user_username : 'Guest';
			$comment->email = $row->user_email;
			$comment->userid = $row->username;
			$comment->comment = $row->comment;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importEasyComments($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT c.* FROM `#__easycomments` AS c ORDER BY c.`date`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->contentid;
			$comment->object_group = 'com_content';
			$comment->parent = $row->parentid;
			$comment->userid = 0;
			$comment->name = $row->name;
			$comment->username = $comment->name;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->email = $row->email;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", $row->date);
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importMusicBox($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.email, u.name, u.username"
				. "\nFROM `#__musicboxrewiev` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`id`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->albumid;
			$comment->object_group = 'com_musicbox';
			$comment->userid = $row->userid;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->text;
			$comment->published = $row->published;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importJReviews($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SELECT c.* FROM `#__jreviews_comments` AS c ORDER BY c.`created`", $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->pid;
			$comment->object_group = 'com_content';
			$comment->userid = $row->userid;
			$comment->ip = $row->ipaddress;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->title = $row->title;
			$comment->comment = $row->comments;
			$comment->email = $row->email;
			$comment->published = $row->published;
			$comment->date = $row->created;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importTutorials($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.email, u.name, u.username"
				. "\nFROM `#__tutorials_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->tutorialid;
			$comment->object_group = 'com_tutorials';
			$comment->userid = $row->userid;
			$comment->ip = $row->userip;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->title = $row->title;
			$comment->comment = $row->comments;
			$comment->email = $row->email;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importIDoBlog($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name"
				. "\nFROM `#__idoblog_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.created_by = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->idarticle;
			$comment->object_group = 'com_idoblog';
			$comment->parent = $row->parent;
			$comment->userid = $row->created_by;
			$comment->ip = $row->userip;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->title = $row->title;
			$comment->comment = $row->text;
			$comment->email = $row->email;
			$comment->published = $row->publish;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importSobi2Reviews($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name"
				. "\nFROM `#__sobi2_plugin_reviews` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id"
				. "\nWHERE c.review <> ''"
				. "\nORDER BY c.`added`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->itemid;
			$comment->object_group = 'com_sobi2';
			$comment->parent = 0;
			$comment->userid = $row->userid;
			$comment->ip = $row->ip;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->title = $row->title;
			$comment->comment = $row->review;
			$comment->email = $row->email;
			$comment->published = $row->published;
			$comment->date = $row->added;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importJReactions($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.username"
				. "\nFROM `#__jreactions` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->contentid;
			$comment->object_group = 'com_content';
			$comment->parent = 0;
			$comment->userid = $row->userid;
			$comment->ip = $row->ip;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->title = $row->title;
			$comment->comment = $row->comments;
			$comment->email = $row->email;
			$comment->homepage = $row->website;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importVirtueMart($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.username, u.email, u.name"
				. "\nFROM `#__vm_product_reviews` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`time`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->product_id;
			$comment->object_group = 'com_virtuemart';
			$comment->parent = 0;
			$comment->userid = $row->userid;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->comment;
			$comment->email = $row->email;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", $row->time);
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importJXtendedComments($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SHOW TABLES LIKE '%jxcomments_threads%';");
		$tables = $db->loadResultArray();

		if (count($tables) == 0) {
			$query = "SELECT c.*, c.context_id as object_id, c.context as object_group, c.subject as title, u.username"
					. "\nFROM `#__jxcomments_comments` AS c"
					. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
					. "\nORDER BY c.`created_date`";
		} else {
			$query = "SELECT c.*, t.context_id as object_id, t.context as object_group, t.page_title as title, u.username"
					. "\nFROM `#__jxcomments_comments` AS c"
					. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
					. "\nLEFT JOIN `#__jxcomments_threads` AS t on c.thread_id = t.id "
					. "\nORDER BY c.`created_date`";
		}

		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->object_id;
			$comment->object_group = $row->object_group == 'content' ? 'com_' . $row->object_group : $row->object_group;
			$comment->parent = 0;
			$comment->userid = $row->user_id;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->title = $row->subject != '' ? $row->subject : 'RE: ' . $row->title;
			$comment->comment = $row->body;
			$comment->email = $row->email;
			$comment->homepage = $row->url;
			$comment->ip = $row->address;
			$comment->published = $row->published;
			$comment->date = $row->created_date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importChronoComments($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.username"
				. "\nFROM `#__chrono_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`datetime`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->pageid;
			$comment->object_group = $row->component;
			$comment->parent = $row->parentid;
			$comment->userid = $row->userid;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->text;
			$comment->email = $row->email;
			$comment->homepage = $row->url;
			$comment->published = $row->published;
			$comment->date = $row->datetime;
			$comment->isgood = $row->rating > 0 ? $row->rating : 0;
			$comment->ispoor = $row->rating < 0 ? abs($row->rating) : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importAkoBook($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.gbname, c.gbmail, c.gbip, c.gbpage, c.gbdate, c.published, c.gbtext"
				. "\n, u.username, u.id as userid"
				. "\nFROM `#__akobook` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.gbname = u.username and c.gbmail = u.email "
				. "\nORDER BY c.`gbdate`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = 1;
			$comment->object_group = 'com_akobook';
			$comment->parent = 0;
			$comment->userid = isset($row->userid) ? intval($row->userid) : 0;
			$comment->name = $row->gbname;
			$comment->username = $row->gbname == '' ? $row->username : $row->gbname;
			$comment->comment = $row->gbtext;
			$comment->email = $row->gbmail;
			$comment->homepage = $row->gbpage;
			$comment->ip = $row->gbip;
			$comment->published = $row->published;
			$comment->date = strftime("%Y-%m-%d %H:%M:00", $row->gbdate);
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importJamBook($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username, u.name, u.id as userid"
				. "\nFROM `#__jx_jambook` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.created_by = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = 0;
			$comment->object_group = 'com_jambook';
			$comment->parent = 0;
			$comment->userid = isset($row->userid) ? intval($row->userid) : 0;
			$comment->name = isset($row->authoralias) ? $row->authoralias : $row->name;
			$comment->username = isset($row->authoralias) ? $row->authoralias : $row->username;
			$comment->comment = $row->content;
			$comment->email = $row->email;
			$comment->homepage = $row->url;
			$comment->ip = $row->fromip;
			$comment->published = 1;
			$comment->date = $row->created;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importK2($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.name"
				. "\nFROM `#__k2_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`commentDate`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->itemID;
			$comment->object_group = 'com_k2';
			$comment->parent = 0;
			$comment->userid = isset($row->userID) ? intval($row->userID) : 0;
			$comment->name = isset($row->userName) ? $row->userName : $row->name;
			$comment->username = isset($row->userName) ? $row->userName : $row->name;
			$comment->comment = $row->commentText;
			$comment->email = $row->commentEmail;
			$comment->homepage = $row->commentURL;
			$comment->ip = '';
			$comment->published = $row->published;
			$comment->date = $row->commentDate;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importSmartBlog($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username, u.name, u.email, u.id as userid"
				. "\nFROM `#__blog_comment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`comment_date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->post_id;
			$comment->object_group = 'com_blog';
			$comment->parent = 0;
			$comment->userid = $row->user_id;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->comment_desc;
			$comment->title = $row->comment_title;
			$comment->email = $row->email;
			$comment->homepage = '';
			$comment->ip = $row->comment_ip;
			$comment->published = $row->published;
			$comment->date = $row->comment_date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importUrComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.email as user_email, u.name as user_name, u.username as user_username "
				. "\nFROM #__urcomment AS c"
				. "\nLEFT JOIN #__users AS u ON c.userid = u.id"
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->contentid;
			$comment->object_group = (isset($row->component) && $row->component != '') ? $row->component : 'com_content';
			$comment->parent = isset($row->parentid) ? $row->parentid : 0;
			$comment->userid = $row->userid;
			$comment->name = $row->user_name ? $row->user_name : $row->name;
			$comment->username = $row->user_username ? $row->user_username : $row->name;
			$comment->email = $row->email;
			$comment->homepage = $row->website;
			$comment->title = $row->title;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->isgood = $row->rate_good > 0 ? $row->rate_good : 0;
			$comment->ispoor = $row->rate_bad < 0 ? $row->rate_bad : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importYvComment($source, $language, $start = 0, $limit = 100)
	{
		$yvHelper = JPATH_SITE . DS . 'components' . DS . 'com_yvcomment' . DS . 'helpers.php';
		if (is_file($yvHelper)) {
			$db = JCommentsFactory::getDBO();

			require_once($yvHelper);
			$yvComment = yvCommentHelper::getInstance();
			$rows = yvCommentSystem::getList($start, $limit);
			$guestId = $yvComment->getGuestID();

			foreach ($rows as $row) {
				$yvEmail = trim($yvComment->getValueFromIni($row->metadata, 'created_by_email'));

				if ($row->created_by == $guestId) {
					$yvUsername = trim($yvComment->getValueFromIni($row->metadata, 'created_by_username'));
					$yvAlias = trim($yvComment->getValueFromIni($row->metadata, 'created_by_alias'));

					$row->created_by = 0;
					$row->username = $yvUsername ? $yvUsername : ($yvAlias ? $yvAlias : $row->created_by_alias);
					$row->name = $yvUsername ? $yvUsername : ($yvAlias ? $yvAlias : $row->created_by_alias);
					$row->email = $yvEmail;
				}

				$comment = new JCommentsImportedComment($db);
				$comment->object_id = $row->parentid;
				$comment->object_group = 'com_content';
				$comment->parent = 0;
				$comment->userid = $row->created_by;
				$comment->username = $row->created_by_alias ? $row->created_by_alias : $row->username;
				$comment->name = $row->created_by_alias ? $row->created_by_alias : $row->name;
				$comment->email = $yvEmail ? $yvEmail : $row->email;
				$comment->homepage = $yvComment->getValueFromIni($row->metadata, 'created_by_link');
				$comment->title = $row->title;
				$comment->comment = $row->introtext . $row->fulltext;
				$comment->published = ($row->state == 1) ? 1 : 0;
				$comment->date = $row->created;
				$comment->source = $source;
				$comment->lang = $language;
				$comment->store();
			}
		}
	}

	function importZimbComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username, u.name, u.email, u.id as userid"
				. "\nFROM `#__zimbComment_Comment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.iduser = u.id "
				. "\nORDER BY c.`saved`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->articleId;
			$comment->object_group = 'com_content';
			$comment->parent = 0;
			$comment->userid = $row->iduser;
			$comment->username = isset($row->handle) ? $row->handle : $row->username;
			$comment->name = $row->name;
			$comment->email = $row->email;
			$comment->homepage = $row->url;
			$comment->title = '';
			$comment->comment = $row->content;
			$comment->published = $row->published;
			$comment->date = $row->saved;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importRDBSComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.username"
				. "\nFROM `#__rdbs_comment_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.created_by = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->refid;
			$comment->object_group = isset($row->application) ? $row->application : 'com_content';
			$comment->parent = $row->parent;
			$comment->userid = $row->created_by;
			$comment->title = $row->title;
			$comment->name = $row->name;
			$comment->username = $row->created_by_alias;
			$comment->comment = $row->comment;
			$comment->email = $row->email;
			$comment->homepage = $row->web;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->created;
			$comment->isgood = $row->usefull_yes > 0 ? $row->usefull_yes : 0;
			$comment->ispoor = $row->usefull_no < 0 ? $row->usefull_no : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importLyftenBloggie($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.username"
				. "\nFROM `#__bloggies_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->entry_id;
			$comment->object_group = 'com_lyftenbloggie';
			$comment->parent = 0;
			$comment->userid = $row->user_id;
			$comment->name = $row->author;
			$comment->username = isset($row->username) ? $row->username : $row->author;
			$comment->comment = $row->content;
			$comment->email = $row->author_email;
			$comment->homepage = $row->author_url;
			$comment->ip = $row->author_ip;
			$comment->published = ($row->state == 1) ? 1 : 0;
			$comment->date = $row->date;
			$comment->isgood = $row->karma > 0 ? $row->karma : 0;
			$comment->ispoor = $row->karma < 0 ? $row->karma : 0;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importWebeeComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username, u.name, u.email as user_email, u.id as userid"
				. "\nFROM `#__webeeComment_Comment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.isuser = 1 AND c.handle = u.id "
				. "\nORDER BY c.`saved`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->articleId;
			$comment->object_group = 'com_content';
			$comment->parent = 0;
			$comment->userid = $row->isuser ? $row->handle : 0;
			$comment->username = $row->isuser ? $row->username : $row->handle;
			$comment->name = $row->isuser ? $row->name : $row->handle;
			$comment->email = $row->isuser ? $row->user_email : $row->email;
			$comment->homepage = $row->url;
			$comment->title = '';
			$comment->ip = isset($row->ipAddress) ? $row->ipAddress : '';
			$comment->comment = $row->content;
			$comment->published = $row->published;
			$comment->date = $row->saved;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importResource($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username, u.email as user_email"
				. "\nFROM `#__js_res_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`ctime`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->record_id;
			$comment->object_group = 'com_resource';
			$comment->parent = $row->parent;
			$comment->userid = $row->user_id;
			$comment->username = isset($row->username) ? $row->username : $row->name;
			$comment->name = $row->name;
			$comment->email = isset($row->user_email) ? $row->user_email : $row->email;
			$comment->title = $row->subject;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->ctime;
			$comment->isgood = $row->rate > 0 ? $row->rate : 0;
			$comment->ispoor = $row->rate < 0 ? abs($row->rate) : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = isset($row->langs) ? $row->langs : $language;
			$comment->store();
		}
	}

	function importJAComment($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.username"
				. "\nFROM `#__jacomment_items` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->contentid;
			$comment->object_group = $row->option;
			$comment->parent = $row->parentid;
			$comment->userid = $row->userid;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->comment;
			$comment->ip = $row->ip;
			$comment->email = $row->email;
			$comment->homepage = $row->website;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->isgood = $row->voted > 0 ? $row->voted : 0;
			$comment->ispoor = $row->voted < 0 ? abs($row->voted) : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importSliComments($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.email as user_email, u.name as user_name, u.username as user_username"
				. "\nFROM `#__slicomments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->article_id;
			$comment->object_group = 'com_content';
			$comment->parent = 0;
			$comment->userid = $row->user_id;
			$comment->name = $row->user_id ? $row->user_name : $row->name;
			$comment->username = $row->user_id ? $row->user_username : $row->name;;
			$comment->comment = $row->raw;
			$comment->email = $row->user_id ? $row->user_email : $row->email;
			$comment->published = $row->status;
			$comment->date = $row->created;
			$comment->isgood = $row->rating > 0 ? $row->rating : 0;
			$comment->ispoor = $row->rating < 0 ? abs($row->rating) : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importUdjaComments($source, $language, $start = 0, $limit = 100)
	{
		static $cache = array();

		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.id as userid, u.username"
				. "\nFROM `#__udjacomments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON u.name = c.full_name AND u.email = c.email"
				. "\nORDER BY c.`time_added`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {

			if (!isset($cache[$row->comment_url])) {
				$cache[$row->comment_url] = null;

				if (preg_match('#[\/\?\=]#', $row->comment_url)) {
					// TODO Implement SEF URL reversing and etc.
				} else {
					$parts = preg_split('/:/', $row->comment_url);
					if (count($parts) == 2) {
						$object = new StdClass;
						$object->option = $parts[0];
						$object->id = $parts[1];

						$cache[$row->comment_url] = $object;
					}
				}
			}

			if ($cache[$row->comment_url] !== null) {

				$comment = new JCommentsImportedComment($db);
				$comment->object_id = $cache[$row->comment_url]->id;
				$comment->object_group = $cache[$row->comment_url]->option;
				$comment->parent = $row->parent_id;
				$comment->userid = isset($row->userid) ? $row->userid : 0;
				$comment->name = $row->full_name;
				$comment->username = isset($row->username) ? $row->username : $row->full_name;
				$comment->comment = $row->content;
				$comment->ip = $row->ip;
				$comment->email = $row->email;
				$comment->homepage = $row->url;
				$comment->published = $row->is_published;
				$comment->date = $row->time_added;
				$comment->source = $source;
				$comment->source_id = $row->id;
				$comment->lang = $language;
				$comment->store();
			}
		}

		if ($start == 0) {
			// import subscriptions
			$query = "INSERT INTO #__jcomments_subscriptions (`object_id`, `object_group`, `lang`, `userid`, `name`, `email`, `hash`, `published`, `source`)"
				. "\n SELECT DISTINCT c.`object_id`"
				. "\n , c.`object_group`"
				. "\n , '$language'"
				. "\n , c.`userid`"
				. "\n , c.`name`"
				. "\n , c.`email`"
				. "\n , md5(concat(c.`object_id`,c.`object_group`,c.`userid`,c.`email`,'$language'))"
				. "\n , 1"
				. "\n , '$source'"
				. "\n FROM `#__jcomments` AS c"
				. "\n JOIN `#__udjacomments` AS uc ON concat(c.object_group,':',c.object_id) = uc.comment_url"
				. "\n WHERE c.source = '$source'"
				. "\n AND uc.receive_notifications = 1"

				. "\n AND NOT EXISTS"
				. "\n ( "
				. "\n   SELECT * FROM `#__jcomments_subscriptions` s"
				. "\n   WHERE s.`object_id` = c.`object_id`"
				. "\n   AND s.`object_group` = c.`object_group`"
				. "\n   AND s.`userid` = c.`userid`"
				. "\n   AND s.`email` = c.`email`"
				. "\n ) "

				;
			$db->setQuery($query);
			$db->query();
		}
	}

	function importTPDugg($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name, u.username, u.email"
				. "\nFROM `#__tpdugg_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.userid = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->duggid;
			$comment->object_group = 'com_tpdugg';
			$comment->parent = $row->parentid;
			$comment->userid = $row->userid;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->comment;
			$comment->ip = $row->ipaddress;
			$comment->email = $row->email;
			$comment->published = $row->published;
			$comment->date = $row->created;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importZoo($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name, u.username, u.email"
				. "\nFROM `#__zoo_comment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->item_id;
			$comment->object_group = 'com_zoo';
			$comment->parent = $row->parent_id;
			$comment->userid = $row->user_id;
			$comment->name = $row->name;
			$comment->username = $row->author;
			$comment->comment = $row->content;
			$comment->ip = $row->ip;
			$comment->email = $row->email;
			$comment->homepage = $row->url;
			$comment->published = $row->state == 1;
			$comment->date = $row->created;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importBeeHeard($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name, u.username, u.email"
				. "\nFROM `#__beeheard_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`created_at`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->suggestion_id;
			$comment->object_group = 'com_beeheard';
			$comment->parent = 0;
			$comment->userid = $row->user_id;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->comment_text;
			$comment->email = $row->email;
			$comment->published = 1;
			$comment->date = $row->created_at;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importJMyLife($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name, u.username, u.email"
				. "\nFROM `#__jmylife_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.author_id = u.id "
				. "\nORDER BY c.`id`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->sid;
			$comment->object_group = 'com_jmylife';
			$comment->userid = $row->author_id;
			$comment->name = $row->name ? $row->name : $row->author;
			$comment->username = $row->username ? $row->username : $row->author;
			$comment->comment = $row->message;
			$comment->email = $row->email ? $row->email : '';
			$comment->published = $row->published;
			$comment->date = JHTML::_('date', $row->time, '%Y-%m-%d %H:%M:%s');
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importMusicCollection($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name, u.username, u.email"
				. "\nFROM `#__muscol_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->album_id;
			$comment->object_group = 'com_muscol';
			$comment->userid = $row->user_id;
			$comment->name = $row->name;
			$comment->username = $row->username;
			$comment->comment = $row->comment;
			$comment->email = $row->email;
			$comment->published = 1;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importRSComments($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name as user_name, u.username, u.email as user_email"
				. "\nFROM `#__rscomments_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.uid = u.id "
				. "\nORDER BY c.`date`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->id;
			$comment->object_group = $row->option;
			$comment->userid = $row->uid;
			$comment->name = isset($row->user_name) ? $row->user_name : $row->name;
			$comment->username = $row->username;
			$comment->email = isset($row->user_email) ? $row->user_email : $row->email;
			$comment->homepage = $row->website;
			$comment->ip = $row->ip;
			$comment->title = $row->subject;
			$comment->comment = $row->comment;
			$comment->published = $row->published;
			$comment->date = $row->date;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importRsiComments($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*, u.name, u.username, u.email as user_email"
				. "\nFROM `#__rsi_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.user_id = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->article_id;
			$comment->object_group = 'com_content';
			$comment->userid = $row->user_id;
			$comment->name = isset($row->name) ? $row->name : $row->user_name;
			$comment->username = isset($row->username) ? $row->username : $row->user_name;
			$comment->email = isset($row->user_email) ? $row->user_email : $row->email;
			$comment->title = $row->title;
			$comment->comment = $row->descrip;
			$comment->published = $row->status == 1;
			$comment->date = $row->created;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}
	}

	function importHekimaBlog($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username, u.name, u.email"
				. "\nFROM `#__hekima_blog_comments` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.kuid = u.id "
				. "\nORDER BY c.`kdate`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->kmid;
			$comment->object_group = $row->kprofile ? 'com_hekimablog_users' : 'com_hekimablog';
			$comment->parent = $row->kparentid;
			$comment->userid = $row->kuid;
			$comment->name = isset($row->name) ? $row->name : $row->kusername;
			$comment->username = isset($row->username) ? $row->username : $row->kusername;
			$comment->email = isset($row->email) ? $row->email : $row->kemail;;
			$comment->homepage = $row->khomepage;
			$comment->comment = $row->ktext;
			$comment->title = $row->ktitle;
			$comment->homepage = '';
			$comment->ip = $row->kip;
			$comment->published = $row->kpublished;
			$comment->date = $row->kdate;
			$comment->isgood = $row->kplus;
			$comment->ispoor = $row->kminus;
			$comment->source = $source;
			$comment->lang = $language;
			$comment->store();
		}

		if ($start == 0) {
			// import subscriptions
			$query = "INSERT INTO #__jcomments_subscriptions (`object_id`, `object_group`, `lang`, `userid`, `name`, `email`, `hash`, `published`, `source`)"
					. "\n SELECT DISTINCT kmid"
					. "\n , CASE WHEN c.kprofile = 0 THEN 'com_hekimablog' ELSE 'com_hekimablog_users' END"
					. "\n , '$language'"
					. "\n , kuid"
					. "\n , kusername"
					. "\n , kemail"
					. "\n , md5(concat(kmid, CASE WHEN c.kprofile = 0 THEN 'com_hekimablog' ELSE 'com_hekimablog_users' END, kuid, kemail, '$language'))"
					. "\n , 1"
					. "\n , '$source'"
					. "\n FROM #__hekima_blog_comments c"
					. "\n WHERE c.ksubscribe = 1"
					. "\n AND NOT EXISTS"
					. "\n ( "
					. "\n   SELECT * FROM #__jcomments_subscriptions s"
					. "\n   WHERE s.object_id = c.kmid "
					. "\n   AND s.object_group = CASE WHEN c.kprofile = 0 THEN 'com_hekimablog' ELSE 'com_hekimablog_users' END"
					. "\n   AND s.userid = c.kuid"
					. "\n   AND s.email = c.kemail"
					. "\n ) ";
			$db->setQuery($query);
			$db->query();
		}
	}

	function importEasyBlog($source, $language, $start = 0, $limit = 100)
	{
		$db = JCommentsFactory::getDBO();
		$query = "SELECT c.*"
				. "\n, u.username as user_username, u.name as user_name, u.email as user_email"
				. "\nFROM `#__easyblog_comment` AS c"
				. "\nLEFT JOIN `#__users` AS u ON c.created_by = u.id "
				. "\nORDER BY c.`created`";
		$db->setQuery($query, $start, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			$comment = new JCommentsImportedComment($db);
			$comment->object_id = $row->post_id;
			$comment->object_group = 'com_easyblog';
			$comment->parent = $row->parent_id;
			$comment->userid = $row->created_by;
			$comment->name = isset($row->user_name) ? $row->user_name : $row->name;
			$comment->username = isset($row->user_username) ? $row->user_username : $row->name;
			$comment->email = isset($row->user_email) ? $row->user_email : $row->email;;
			$comment->homepage = $row->url;
			$comment->comment = $row->comment;
			$comment->title = $row->title;
			$comment->ip = $row->ip;
			$comment->published = $row->published;
			$comment->date = $row->created;
			$comment->isgood = $row->vote > 0 ? $row->vote : 0;
			$comment->ispoor = $row->vote < 0 ? $row->vote : 0;
			$comment->source = $source;
			$comment->source_id = $row->id;
			$comment->lang = $language;
			$comment->store();
		}
	}
}

class HTML_JCommentsMigrationTool
{
	public static function showImport($CommentSystems = array())
	{
		$app = JCommentsFactory::getApplication('administrator');
		$db = JCommentsFactory::getDBO();
		$db->setQuery("SHOW TABLES");
		$tables = $db->loadResultArray();
	
		foreach ($tables as $tableName) {
			for($i=0,$n=count($CommentSystems); $i < $n; $i++ ) {
				$tableMask = str_replace( '#__', $app->getCfg('dbprefix'), $CommentSystems[$i]->table );
				if (preg_match('/'.$tableMask.'$/i', $tableName)) {
					$CommentSystems[$i]->found = true;
					$CommentSystems[$i]->UpdateCount();
				} 
			}
		}

		$languages = JCommentsMultilingual::getLanguages();

		if (JCOMMENTS_JVERSION == '1.0') {
		 	$lang = JCommentsMultilingual::getLanguage();
		} else {
			$params = JComponentHelper::getParams('com_languages');
			$lang = $params->get("site", 'en-GB');
		}

		$ajaxUrl = JCommentsFactory::getLink('ajax-backend');
?>
<link rel="stylesheet" href="<?php echo $app->getCfg('live_site'); ?>/administrator/components/com_jcomments/assets/style.css" type="text/css" />
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/components/com_jcomments/libraries/joomlatune/ajax.js?v=2"></script>
<script type="text/javascript" src="<?php echo $app->getCfg( 'live_site' );?>/administrator/components/com_jcomments/assets/jcomments-backend-v2.1.js"></script>
<script type="text/javascript">
<!--
function JCommentsImportCommentsAJAX(source, language, start)
{
	try {
		jtajax.setup({url:'<?php echo $ajaxUrl; ?>'});
		return jtajax.call('JCommentsImportCommentsAjax', arguments, 'post');
	} catch (e) {
		return false;
	}
}

function startCommentsImport(source)
{
	var language = '';
	var e=document.getElementById(source.toLowerCase() + '_lang');
	if (e){for (var i=0;i<e.length;i++) {if (e.options[i].selected){language=e.options[i].value;break;}}}
	var b = document.getElementById('btnImport' + source);
	if (b) {b.disabled = true;}
	JCommentsImportCommentsAJAX(source, language, 0);
}

function finishCommentsImport(source) {
	var b=document.getElementById('btnImport'+source);if(b){b.disabled=false;}
}

<?php if (JCOMMENTS_JVERSION == '1.7') { ?>
Joomla.submitbutton = function (task) {
	Joomla.submitform(task, document.getElementById('adminForm'));
};
<?php } else { ?>
function submitbutton(task)
{
	submitform(task);
}
<?php } ?>
//-->
</script>
<script type="text/javascript">
<!--
var jc_comments = new Array(
<?php
		$jsArray = array();
		foreach ($CommentSystems as $CommentSystem) {
			if ($CommentSystem->found) {
				$jsArray[] = $CommentSystem->code;
			}
		}
		echo "'" . implode("', '", $jsArray) . "'";
?>
			);

function importMode( mode ) {
	if(document.getElementById) {
		for(var i=0;i<jc_comments.length;i++) {
			if (mode == jc_comments[i]) {
				document.getElementById('import' + jc_comments[i]).checked = true;
				document.getElementById('import' + jc_comments[i]+'Info').style.display = '';
			} else {
				document.getElementById('import' + jc_comments[i]).checked = false;
				document.getElementById('import' + jc_comments[i]+'Info').style.display = 'none';
			}
		}
	}
}
//-->
</script>

<style type="text/css">
#jcomments-message {padding: 0 0 0 25px;margin: 0; width: auto; float: right; font-size: 14px; font-weight: bold;}
.jcomments-message-error {background: transparent url(components/com_jcomments/assets/error.gif) no-repeat 4px 50%; color: red;}
.jcomments-message-info {background: transparent url(components/com_jcomments/assets/info.gif) no-repeat 4px 50%; color: green;}
.jcomments-message-wait {background: transparent url(components/com_jcomments/assets/wait.gif) no-repeat 4px 50%; color: green;}
.adminform fieldset { border: 1px #999 solid; }
.adminform fieldset input, fieldset select { float: none; }
.adminform span.note { color: #777; }
table.componentinfo td { color: #777; padding: 0; }
</style>

<div>
<form action="<?php echo JCOMMENTS_INDEX; ?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_jcomments" />
<input type="hidden" name="task" value="" />
<?php
		if ( JCOMMENTS_JVERSION == '1.0' ) {
?>
<table class="adminheading">
<tr>
	<th style="background-image: none; padding: 0;"><img src="components/com_jcomments/assets/icon-48-import.png" width="48" height="48" align="middle" alt="<?php echo JText::_('A_IMPORT'); ?>">&nbsp;<?php echo JText::_('A_IMPORT'); ?></th>
</tr>
</table>
<?php
		}
?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td align="right">&nbsp;</td>
		<td width="50%" align="right"><div id="jcomments-message-holder"></div></td>
	</tr>
</table>

<table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminform">
<tr>
	<td>
		<fieldset>
		<legend><?php echo JText::_('A_IMPORT_SELECT_SOURCE'); ?></legend>
		<table cellpadding="1" cellspacing="1" border="0">
<?php
		$foundSources = 0;
		foreach ($CommentSystems as $CommentSystem) {
			if ($CommentSystem->found) {
				$foundSources++;
?>
		<tr valign="top" align="left">
			<td><input type="radio" id="import<?php echo $CommentSystem->code; ?>" name="vars[import]" value="<?php echo $CommentSystem->code; ?>" onclick="importMode('<?php echo $CommentSystem->code; ?>')" <?php echo ($CommentSystem->found ? '' : 'disabled') ?> /></td>
			<td><label for="import<?php echo $CommentSystem->code; ?>"><?php echo $CommentSystem->name; ?></label></td>
			<td><div id="jcomments-message-<?php echo strtolower($CommentSystem->code); ?>"></div></td>
		</tr>
		<tr id="import<?php echo $CommentSystem->code; ?>Info" style="display: none;">
			<td>&nbsp;</td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" class="componentinfo">
				<tr>
					<td width="150px"><?php echo JText::_('A_IMPORT_COMPONENT_AUTHOR'); ?></td>
					<td><?php echo $CommentSystem->author; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('A_IMPORT_COMPONENT_HOMEPAGE'); ?></td>
					<td><a href="<?php echo $CommentSystem->homepage; ?>" target="_blank"><?php echo str_replace('http://', '', $CommentSystem->homepage); ?></a></td>
				</tr>
				<tr>
					<td><?php echo JText::_('A_IMPORT_COMPONENT_LICENSE'); ?></td>
					<td>
<?php
				if ($CommentSystem->license_url != '') {
?>
						<a href="<?php echo $CommentSystem->license_url; ?>" target="_blank"><?php echo $CommentSystem->license; ?></a>
<?php
				} else {
?>					
						<?php echo $CommentSystem->license; ?>
<?php
				}
?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr valign="top" align="left">
					<td>
						<?php echo JText::_('A_IMPORT_COMPONENT_COMMENTS_COUNT'); ?>
					</td>
					<td>
						<label for="import<?php echo $CommentSystem->code; ?>"><?php echo $CommentSystem->count; ?></label>
					</td>
				</tr>

				<tr valign="top" align="left">
					<td>
					</td>
					<td>
<?php
				if (count($languages)) {
					echo JCommentsHTML::selectList( $languages, strtolower($CommentSystem->code) . '_lang', 'class="inputbox" size="1"', 'value', 'name', $lang) . '&nbsp;';
				}
?>
						<input type="button" id="btnImport<?php echo $CommentSystem->code; ?>" name="btnImport<?php echo $CommentSystem->code; ?>" value="<?php echo JText::_('A_IMPORT_BUTTON_IMPORT'); ?>" onclick="startCommentsImport('<?php echo $CommentSystem->code; ?>')" <?php echo ($CommentSystem->count ? '' : 'disabled') ?> />
					</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
<?php
			}
		}

		if ($foundSources == 0) {
?>
		<tr>
			<td><?php echo JText::_('A_IMPORT_NO_SOURCES'); ?></td>
		</tr>
<?php
		}
?>
		</table>
	</fieldset>
	</td>
</tr>
</table>
<?php echo JCommentsSecurity::formToken(); ?>
</form>
</div>
<?php
	}
}
?>