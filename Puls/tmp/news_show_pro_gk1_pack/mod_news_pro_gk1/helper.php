<?php

/**
* Gavick News Pro GK1 - helper class
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');
// import com_content route helper
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
// import JString class for UTF-8 problems
jimport('joomla.utilities.string'); 
// Main class
class GK1NewsProHelper
{
	var $config;
	var $content;
	var $images;
	var $more_in_text;
	
	/**
	 * 
	 *	INITIALIZATION
	 * 
	 **/
	
	function init()
	{
		$this->config = array(
			'module_unique_id' => '',
			'width_module' => 0,
			'width_links' => 0,
			'td_padding' => 0,
			'section' => 0,
			'category' => 0,
			'sections' => '',
			'categoriess' => '',
			'IDs' => '',
			'mode' => 0,
			// source settings
			'news_full_pages' => 0,
			'news_short_pages' => 0,
			'news_column' => 0,
			'news_rows' => 0,
			'links_amount' => 0, 
			'news_sort_value' => 0,
			'news_sort_order' => 0,
			'news_frontpage' => 0, 
			'unauthorized' => 0,
			'only_frontpage' => 0,	
			'startposition' => 0,	
			// list settings
			'show_list' => 0,
			'list_position' => 0,
			// content settings
			'news_readmore_text' => 0,
			'news_header_link' => 0,
			'news_image_link' => 0,
			'news_text_link' => 0,
			'news_authorr' => 0,
			'news_cats' => 0,
			'news_datee' => 0,
			'news_more_in' => 0,
			'date_format' => 0,
			'username' => 0, 
			// positions
			'news_content_header_pos' => 0,
			'news_content_image_pos' => 0,
			'news_content_text_pos' => 0,
			'news_content_info_pos' => 0,
			'news_content_readmore_pos' => 0,
			// ordering
			'news_header_order' => 0,
			'news_image_order' => 0,
			'news_text_order' => 0,
			'news_info_order' => 0,
			// links settings
			'head_nofollow' => 0,
			'image_nofollow' => 0,
			'text_nofollow' => 0,
			'info_nofollow' => 0,
			'list_nofollow' => 0,
			'readmore_nofollow' => 0,
			'head_target' => 0,
			'image_target' => 0,
			'text_target' => 0,
			'info_target' => 0,
			'list_target' => 0,
			'readmore_target' => 0,
			// limits
			'news_limit_type' => 0,
			'news_limit' => 0,
			'title_limit' => 0,
			'list_title_limit' => 0,
			'list_text_limit' => 0,
			// other content settings
			'clean_xhtml' => 0,
			// thubmbnails settings
			'plugin_support' => 0,
			'only_plugin' => 0,
			'img_height' => 0,
			'img_width' => 0,
			'img_margin' => 0,	
			// Advanced params
			'parse_plugins' => 0,		
			'useMoo' => 0,
			'useScript' => 0,
			'compress_js' => 0
		);	
	}
	
	/**
	 * 
	 *	VARIABLES VALIDATION
	 * 
	 **/
	
	function validateVariables(&$params)
	{
		$this->config['module_unique_id'] = $params->get('module_unique_id','newspro1'); // unique ID
		$this->config['width_module'] = $params->get('width_module', 0); // main content width
		$this->config['width_links'] = $params->get('width_links', 0); // links width
		$this->config['td_padding'] = $params->get('td_padding', 0); // table cells padding
		//
		// Source settings
		//
		$this->config['section'] = $params->get('section', ''); // ID of section from list
		$this->config['category'] = $params->get('category', ''); // ID of category from list	
		$this->config['sections'] = $params->get('sections', ''); // String contained IDs of sections separated by comma
		$this->config['categoriess'] = $params->get('categories', ''); // String contained IDs of scategories separated by comma
		$this->config['IDs'] = $params->get('ids',''); // String contained IDs of articles separated by comma
		$this->config['mode'] = $params->get('mode', 'standard'); // standard - mode like News Show, category mode - new type of showing articles
		//
		// Settings of source
		//
		$this->config['news_full_pages'] = $params->get('news_full_pages', 3); // max. amount of full articles to load
		$this->config['news_short_pages'] = $params->get('news_short_pages', 3); // max. amount of links to articles to load
		$this->config['news_column'] = $params->get('news_column', 1); // amount of news columns
		$this->config['news_rows'] = $params->get('news_rows', 1); // amount of news rows 
		$this->config['links_amount'] = $params->get('links_amount', 6); // amount of links
		$this->config['news_sort_value'] = $params->get('news_sort_value','created'); // Parameter for SQL Query - value of sort	
		$this->config['news_sort_order'] = $params->get('news_sort_order','DESC'); // Parameter for SQL Query - sort direct
		$this->config['news_frontpage'] = $params->get('news_frontpage',1);
		$this->config['unauthorized'] = $params->get('unauthorized', 0);
		$this->config['only_frontpage'] = $params->get('only_frontpage', 0);
		$this->config['startposition'] = $params->get('startposition', 0);
		//
		// Settings of list
		//	
		$this->config['show_list'] = $params->get('show_list', 1); // boolean value - show list or not
		$this->config['list_position'] = $params->get('list_position', 'bottom'); // position of list with rest news 
		//
		// Content settings
		//
		$this->config['news_readmore_text'] = $params->get('news_readmore_text', 'Readmore'); // text for news readmore button
		$this->config['news_header_link'] = $params->get('news_header_link', 1); // add link to header ? (boolean)
		$this->config['news_image_link'] = $params->get('news_image_link', 1); // add link to image ? (boolean)
		$this->config['news_text_link'] = $params->get('news_text_link', 0); // add link to text ? (boolean)
		$this->config['news_authorr'] = $params->get('news_author', 1);
		$this->config['news_cats'] = $params->get('news_cats', 1);
		$this->config['news_datee'] = $params->get('news_date', 1);
		$this->config['news_more_in'] = $params->get('news_more_in', 1);
		$this->config['date_format'] = $params->get('date_format', 'D, d M Y'); // date format
		$this->config['username'] = $params->get('username', 0);
		//
		// Positions
		//
		$this->config['news_content_header_pos'] = $params->get('news_content_header_pos', 1); // text-align for news header
		$this->config['news_content_image_pos'] = $params->get('news_content_image_pos', 1); // text-align for news image
		$this->config['news_content_text_pos'] = $params->get('news_content_text_pos', 1); // text-align for news text
		$this->config['news_content_info_pos'] = $params->get('news_content_info_pos', 1); // text-align for news info
		$this->config['news_content_readmore_pos'] = $params->get('news_content_readmore_pos', 2); // text-align for news readmore button
		//
		// Ordering
		//
		$this->config['news_header_order'] = $params->get('news_header_order', 1); // order of news header
		$this->config['news_image_order'] = $params->get('news_image_order', 2); // order of news image
		$this->config['news_text_order'] = $params->get('news_text_order', 3); // order of news text
		$this->config['news_info_order'] = $params->get('news_info_order', 4);
		//
		// Links
		//
		$this->config['head_nofollow'] = $params->get('head_nofollow', 1);
		$this->config['image_nofollow'] = $params->get('head_nofollow', 1);
		$this->config['text_nofollow'] = $params->get('text_nofollow', 1);
		$this->config['info_nofollow'] = $params->get('info_nofollow', 1);
		$this->config['list_nofollow'] = $params->get('list_nofollow', 1);
		$this->config['readmore_nofollow'] = $params->get('readmore_nofollow', 1);
		$this->config['head_target'] = $params->get('head_target', 1);
		$this->config['image_target'] = $params->get('image_target', 1);
		$this->config['text_target'] = $params->get('text_target', 1);
		$this->config['info_target'] = $params->get('info_target', 1);
		$this->config['list_target'] = $params->get('list_target', 1);
		$this->config['readmore_target'] = $params->get('readmore_target', 1);
		//
		// Limits
		//
		$this->config['news_limit_type'] = $params->get('news_limit_type', 0); // type of limit fo news text
		$this->config['news_limit'] = $params->get('news_limit', 30); // amount of limit "units"
		$this->config['title_limit'] = $params->get('title_limit', 40); // amount of limit "units"
		$this->config['list_title_limit'] = $params->get('list_title_limit', 20); // amount of chars in list element title
		$this->config['list_text_limit'] = $params->get('list_text_limit', 30); // amount of chars in list element text
		//
		// Other content settings
		//
		$this->config['clean_xhtml'] = $params->get('clean_xhtml', 1); // cleaning XHTML in news
		//
		// Thumbnails settings
		//
		$this->config['plugin_support'] = $params->get('plugin_support', 0); // plugin support
		$this->config['only_plugin'] = $params->get('only_plugin', 0); // use only plugin images
		$this->config['img_height'] = $params->get('img_height', 0); // image height
		$this->config['img_width'] = $params->get('img_width', 0); // image width
		$this->config['img_margin'] = $params->get('img_margin', '3px'); // image margin
		//
		// Advanced settings
		//
		$this->config['parse_plugins'] = (bool) $params->get('parse_plugins', 0);
		$this->config['useMoo'] = $params->get('useMoo', 2); // add mootools script to page (if you use more than one module per page then disable it / or if you have mootools included)
		$this->config['useScript'] = $params->get('useScript', 2); // add script for this module to page (if you use more than one module per page then disable it)
		$this->config['compress_js'] = $params->get('compress_js', 1);
		//
		if(JString::strpos($this->config['img_height'],'px') === false && JString::strpos($this->config['img_height'],'%') === false) $this->config['img_height'] = 0;# Height image
		if(JString::strpos($this->config['img_width'],'px') === false && JString::strpos($this->config['img_width'],'%') === false) $this->config['img_width'] = 0;# Width image
		if(JString::strpos($this->config['img_margin'],'px') === false && JString::strpos($this->config['img_margin'],'%') === false) $this->config['img_margin'] = 0;# Margin image
	}
	
	/**
	 * 
	 *	GETTING DATA
	 * 
	 **/
		
	function getDatas()
	{
		//
		$db =& JFactory::getDBO();
		// getting instance of GK_JoomlaNews
		$newsClass = new GK_JoomlaNews();
		// Getting list of categories
		$categories = $newsClass->getSources($this->config, 0);
		// Standard mode
		if($this->config["mode"] == 'standard')
		{
			// init string for more in text
			$this->more_in_text = '';
			// if in database exist some needs datas
			if($categories)
			{
				$sql_where = '';
				//
				$j = 0;
				// getting categories ItemIDs
				foreach ($categories as $item) 
				{
					$this->more_in_text .= '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($item->ID,$item->SID)).'">'. $item->name .'</a>, ';
					//
					$sql_where .= ($j != 0) ? ' OR content.catid = '.$item->ID : ' content.catid = '.$item->ID;
					//
					$j++;
				}	
				//
				$this->more_in_text = JText::_('MORE_IN').": ".JString::substr($this->more_in_text, 0, JString::strlen($this->more_in_text)-2);
			}
			// getting content
			$this->content = $newsClass->getNewsStandardMode($categories, $sql_where, $this->config, (($this->config['news_column'] * $this->config['news_rows'] * $this->config['news_full_pages']) + ($this->config['links_amount'] * $this->config['news_short_pages'])));
			// getting images
			$this->images = $newsClass->getImages($this->config, 'News Pro GK1');
		}
		else // Column mode
		{
			// init string for more in text
			$this->more_in_text = array();
			// if in database exist some needs datas
			if($categories)
			{
				$sql_where = array();
				//
				$j = 0;
				// getting categories ItemIDs
				foreach ($categories as $item) 
				{
					$this->more_in_text[] = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($item->ID, $item->SID)).'">'. JText::_('SEE_ALL') .'</a>';
					//
					$sql_where[] = $item->ID;
					//
					$j++;
				}	
			}
			// getting content
			$this->content = $newsClass->getNewsCategoryMode($categories, $sql_where, $this->config, (($this->config['news_column'] * $this->config['news_rows']) + ($this->config['links_amount'])));
			// getting images
			$this->images = $newsClass->getImages($this->config, 'News Pro GK1');
		}
	}
	
	/**
	 * 
	 *	RENDERING LAYOUT
	 * 
	 **/
	
	function renderLayout()
	{	
		$renderer = new GK1NewsProLayoutParts();
		// tables which will be used in generated content
		$news_list_tab = array();
		//
		$news_html_tab = array();
		// Generating content 
		$uri =& JURI::getInstance();
			
		if($this->config['mode'] == 'category')
		{	
			//
			for($n = 0; $n < count($this->content["ID"]); $n++)
			{	
				$news_html_tab[$n] = array();
				$news_list_tab[$n] = array();
				//
				$li_counter = 0;
				//
				for($i = 0; $i < count($this->content["ID"][$n]); $i++)
				{	
					//
					if($i < ($this->config['news_column'] * $this->config['news_rows']))
					{
						/*
							GENERATING NEWS CONTENT
						*/
					
						// GENERATING HEADER
						$news_header = $renderer->returnHeader($this->config, $this->content['ID'][$n][$i], $this->content['CID'][$n][$i], $this->content['SID'][$n][$i], $this->content['title'][$n][$i]);
						// GENERATING IMAGE
						$news_image = $renderer->returnImage($this->config, $this->images, $uri, $this->content['ID'][$n][$i], $this->content['IID'][$n][$i], $this->content['CID'][$n][$i], $this->content['SID'][$n][$i], $this->content['text'][$n][$i]);
						// GENERATING READMORE
						$news_readmore = $renderer->returnReadMore($this->config, $this->content['ID'][$n][$i], $this->content['CID'][$n][$i], $this->content['SID'][$n][$i]);
						// GENERATING TEXT
						$news_textt = $renderer->returnText($this->config, $this->content['ID'][$n][$i], $this->content['CID'][$n][$i], $this->content['SID'][$n][$i], $this->content['text'][$n][$i], $news_readmore);	
						// GENERATE NEWS INFO
						$news_infoo = $renderer->returnInfo($this->config, $this->content['catname'][$n][$i], $this->content['CID'][$n][$i], $this->content['SID'][$n][$i], $this->content['author'][$n][$i], $this->content['date'][$n][$i]);
								
						// PARSING PLUGINS
						if($this->config['parse_plugins'] == TRUE)
						{
							//
							$news_textt = $this->ParsePlugins($news_textt);
						}				
						// GENERATE CONTENT FOR TAB
						
						
						$news_generated_content = ''; // initialize variable
						//
						for($j = 1;$j < 5;$j++)
						{
							//
							if($this->config['news_header_order'] == $j) $news_generated_content .= $news_header;
							//
							if($this->config['news_image_order'] == $j) $news_generated_content .= $news_image;
							//
							if($this->config['news_text_order'] == $j) $news_generated_content .= $news_textt;
							//
							if($this->config['news_info_order'] == $j) $news_generated_content .= $news_infoo;
						}			
						//
						if($this->config['news_content_readmore_pos'] != 4) 
						{
							//
							$news_generated_content .= $news_readmore;
						}
						// creating table with news content
						$news_html_tab[$n][] = $news_generated_content;
					}
					else //
					{
						if($li_counter % $this->config['links_amount'] == 0) $li_counter = 0; 
						//
						$news_list_tab[$n][] = $renderer->returnList($this->config, $this->content['ID'][$n][$i], $this->content['CID'][$n][$i], $this->content['SID'][$n][$i], $this->content['title'][$n][$i], $this->content['text'][$n][$i], $li_counter % 2);
						//
						$li_counter++;
					}			
				}	
			}
		}
		else
		{
			//
			$li_counter = 0;
			//
			for($i = 0; $i < count($this->content["ID"]); $i++)
			{	
				//
				if($i < ($this->config['news_column'] * $this->config['news_rows'] * $this->config['news_full_pages']))
				{
					/*
						GENERATING NEWS CONTENT
					*/
					
					// GENERATING HEADER
					$news_header = $renderer->returnHeader($this->config, $this->content['ID'][$i], $this->content['CID'][$i], $this->content['SID'][$i], $this->content['title'][$i]);
					// GENERATING IMAGE
					$news_image = $renderer->returnImage($this->config, $this->images, $uri, $this->content['ID'][$i], $this->content['IID'][$i], $this->content['CID'][$i], $this->content['SID'][$i], $this->content['text'][$i]);
					// GENERATING READMORE
					$news_readmore = $renderer->returnReadMore($this->config, $this->content['ID'][$i], $this->content['CID'][$i], $this->content['SID'][$i]);
					// GENERATING TEXT
					$news_textt = $renderer->returnText($this->config, $this->content['ID'][$i], $this->content['CID'][$i], $this->content['SID'][$i], $this->content['text'][$i], $news_readmore);	
					// GENERATE NEWS INFO
					$news_infoo = $renderer->returnInfo($this->config, $this->content['catname'][$i], $this->content['CID'][$i], $this->content['SID'][$i], $this->content['author'][$i], $this->content['date'][$i]);			
					// PARSING PLUGINS
					if($this->config['parse_plugins'] == TRUE)
					{
						//
						$news_textt = $this->ParsePlugins($news_textt);
					}				
					// GENERATE CONTENT FOR TAB	
					$news_generated_content = ''; // initialize variable
					//
					for($j = 1;$j < 5;$j++)
					{
						//
						if($this->config['news_header_order'] == $j) $news_generated_content .= $news_header;
						//
						if($this->config['news_image_order'] == $j) $news_generated_content .= $news_image;
						//
						if($this->config['news_text_order'] == $j) $news_generated_content .= $news_textt;
						//
						if($this->config['news_info_order'] == $j) $news_generated_content .= $news_infoo;
					}			
					//
					if($this->config['news_content_readmore_pos'] != 4) 
					{
						//
						$news_generated_content .= $news_readmore;
					}
					// creating table with news content
					$news_html_tab[] = $news_generated_content;
				}
				else //
				{
					if($li_counter % $this->config['links_amount'] == 0) $li_counter = 0; 
					//
					$news_list_tab[] = $renderer->returnList($this->config, $this->content['ID'][$i], $this->content['CID'][$i], $this->content['SID'][$i], $this->content['title'][$i], $this->content['text'][$i], $li_counter % 2);
					//
					$li_counter++;
				}			
			}
		}
		
		/**
			GENERATING FINAL XHTML CODE START
		**/

		// create instances of basic Joomla! classes
		$document =& JFactory::getDocument();
		$uri =& JURI::getInstance();
		// add stylesheets to document header
		$document->addStyleSheet( $uri->root().'modules/mod_news_pro_gk1/style/style_'.$this->config['mode'].'.css', 'text/css' );
		// init $headData variable
		$headData = false;
		// add scripts with automatic mode to document header
		if($this->config['useMoo'] == 2)
		{
			// getting module head section datas
			unset($headData);
			$headData = $document->getHeadData();
			// generate keys of script section
			$headData_keys = array_keys($headData["scripts"]);
			// set variable for false
			$mootools_founded = false;
			// searching phrase mootools in scripts paths
			for($i = 0;$i < count($headData_keys); $i++)
			{
				if(preg_match('/mootools/i', $headData_keys[$i]))
				{
					// if founded set variable to true and break loop
					$mootools_founded = true;
					break;
				}
			}
			// if mootools file doesn't exists in document head section
			if(!$mootools_founded)
			{
				// add new script tag connected with mootools from module
				$headData["scripts"][$uri->root().'modules/mod_news_pro_gk1/scripts/mootools.js'] = "text/javascript";
				// if added mootools from module then this operation have sense
				$document->setHeadData($headData);
			}
		}
		
		if($this->config['useScript'] == 2)
		{
			// getting module head section datas
			unset($headData);
			$headData = $document->getHeadData();
			// generate keys of script section
			$headData_keys = array_keys($headData["scripts"]);
			// set variable for false
			$engine_founded = false;
			// searching phrase mootools in scripts paths
			if(array_search($uri->root().'modules/mod_news_pro_gk1/scripts/engine_'.$this->config['mode'].(($this->config['compress_js'] == 1) ? '_compressed' : '').'.js', $headData_keys) > 0)
			{
				// if founded set variable to true
				$engine_founded = true;
			}
			// if mootools file doesn't exists in document head section
			if(!$engine_founded)
			{
				// add new script tag connected with mootools from module
				$headData["scripts"][$uri->root().'modules/mod_news_pro_gk1/scripts/engine_'.$this->config['mode'].(($this->config['compress_js'] == 1) ? '_compressed' : '').'.js'] = "text/javascript";
				// if added mootools from module then this operation have sense
				$document->setHeadData($headData);
			}
		}
		//
		$column_width = floor(100 / $this->config['news_column']) . "%";
		//
		require(JModuleHelper::getLayoutPath('mod_news_pro_gk1', 'view.default.'.$this->config['mode']));
		//
		if($this->config['useScript'] != 2 || $this->config['useMoo'] != 2)
		{
			require(JModuleHelper::getLayoutPath('mod_news_pro_gk1', 'view.script_style'));
		}
	}
	
	/**
	 *
	 * 	Method to parse plugin in article content.
	 *	
	 *	@access public
	 *	@param  string - article content
	 *	@return string - modified article content
	 *
	 **/
	
	function ParsePlugins($text)
	{
		// getting global mainframe
		global $mainframe;
		// getting com_content params
        $params =& $mainframe->getParams('com_content');
        // getting JDispatcher instance
		$dispatcher =& JDispatcher::getInstance();
        // importing plugins
		JPluginHelper::importPlugin('content');
        // creating class
		$data = new stdClass();
		// fill class with content
        $data->text = $text;
        // parsing content
        $dispatcher->trigger('onPrepareContent', array(&$data, & $params, 0 ));
	    // returning parsed content (now with parsed plugin content)
		return $data->text;
	}
}

?>