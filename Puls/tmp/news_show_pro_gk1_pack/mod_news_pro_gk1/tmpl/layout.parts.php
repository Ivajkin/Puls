<?php

/**
* Gavick News Pro GK1 - layout parts
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

class GK1NewsProLayoutParts
{

	/**
	 *
	 * 
	 * 
	 * 
	 **/

	function returnHeader($config, $news_id, $news_cid, $news_sid, $news_title)
	{
		if($config['news_content_header_pos'] != 0)
		{
			//
			$class = '';
			$attributes = '';
			//
			if($config['head_nofollow'] == 1) $attributes .= ' rel="nofollow" ';
			//
			if($config['head_target'] == 1) $attributes .= ' target="_blank" ';	
			//
			switch($config['news_content_header_pos'])
			{
				case 0: $class = '';break; 
				case 1: $class = 'ta_left';break; 
				case 2: $class = 'ta_right';break; 
				case 3: $class = 'ta_center';break; 
			}
			//
			return ($config['news_header_link'] == 1) ? '<h4 class="gk_npro_header '.$class.'"><a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($news_id, $news_cid, $news_sid)).'" '.$attributes.'>'.JString::substr($news_title, 0, $config['title_limit']).'</a></h4>' : '<h4 class="gk_npro_header '.$class.'">'.JString::substr($news_title, 0, $config['title_limit']).'</h4>';
		}
		else
		{
			return '';
		}		
	}

	/**
	 *
	 * 
	 * 
	 * 
	 **/

	function returnText($config, $news_id, $news_cid, $news_sid, $news_text, $news_readmore)
	{
		//
		if($config['news_content_text_pos'] != 0)
		{
			//
			if($config['clean_xhtml'] == 1) $news_text = strip_tags($news_text);
			//
			if($config['news_limit_type'] == 0)
			{
				$str = $news_text;
				//
				if(JString::strlen($str) > $config['news_limit'])
				{
					$start_strpos = 0;
					//
					for($i = 0; $i < $config['news_limit'] && $start_strpos < JString::strlen($str); $i++)
					{
						//
						if(JString::strpos($str, ' ', $start_strpos) !== FALSE)
						{
							//
							$start_strpos = JString::strpos($str, ' ', $start_strpos) + 1;
						}	
					}
					
					$news_text = JString::trim($str);
					$news_text = JString::substr($news_text, 0, $start_strpos);
					$news_text .= "...";
				}
			}
			else
			{
				$str = $news_text;
				//
				if(JString::strlen($str) > $config['news_limit'])
				{
					//	
					if(JString::strlen($str) >= $config['news_limit'])
					{	
						//
						$news_text = JString::substr($str, 0, $config['news_limit']);
						$news_text .= "...";
					}
				}
			}
			//
			$attributes = '';
			//
			if($config['text_nofollow'] == 1) $attributes .= ' rel="nofollow" ';
			//
			if($config['text_target'] == 1) $attributes .= ' target="_blank" ';
			//
			$news_text = ($config['news_text_link'] == 1) ? '<a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($news_id, $news_cid, $news_sid)).'" '.$attributes.'>'.$news_text.'</a>' : $news_text; 
			//
			$class = '';
			//
			switch($config['news_content_text_pos'])
			{
				case 0: $class = '';break; 
				case 1: $class = 'ta_left';break; 
				case 2: $class = 'ta_right';break; 
				case 3: $class = 'ta_center';break; 
				case 4: $class = 'ta_justify';break; 
			}
			//
			return ($config['news_content_readmore_pos'] == 4) ? '<p class="gk_npro_text '.$class.'">'.$news_text.' '.$news_readmore.'</p>' : $news_text = '<p class="gk_npro_text '.$class.'">'.$news_text.'</p>';
		}
	}
	
	/**
	 *
	 * 
	 * 
	 * 
	 **/
	
	function returnImage($config, $images, $uri, $news_id, $news_iid, $news_cid, $news_sid, $news_text)
	{
		$IMG_SOURCE = '';
		$IMG_LINK = JRoute::_(ContentHelperRoute::getArticleRoute($news_id, $news_cid, $news_sid));
		//
		if($config['plugin_support'] == 0)
		{
			//	
			$imgStartPos = JString::strpos($news_text, 'src="');
			//
			if($imgStartPos)
			{
				$imgEndPos = JString::strpos($news_text, '"', $imgStartPos + 5);
			} 
			//	
			if($imgStartPos > 0) 
			{
				$IMG_SOURCE = JString::substr($news_text, ($imgStartPos + 5), ($imgEndPos - ($imgStartPos + 5)));
			}
			//
		}
		else
		{	
			if($config['plugin_support'] == 1 && 
				(isset($images[$news_iid]) || 
				isset($images[200000000+$news_cid]) || 
				isset($images[300000000+$news_sid]))
			)
			{
				//
				if(isset($images[$news_iid]))
				{
					$IMG_SOURCE = $uri->root().'components/com_gk2_photoslide/images/thumbs/'.$images[$news_iid];
				}
				elseif(isset($images[200000000 + $news_cid])) //
				{
					$IMG_SOURCE = $uri->root().'components/com_gk2_photoslide/images/thumbs/'.$images[200000000+$news_cid];
				}
				elseif(isset($images[300000000 + $news_sid])) //
				{
					$IMG_SOURCE = $uri->root().'components/com_gk2_photoslide/images/thumbs/'.$images[300000000+$news_sid];	
				}
			}
			else //
			{	
				//
				if($config['plugin_support'] == 1 && $config['only_plugin'] == 0)
				{
					//	
					$imgStartPos = JString::strpos($news_text, 'src="');
					//
					if($imgStartPos)
					{
						$imgEndPos = JString::strpos($news_text, '"', $imgStartPos + 5);
					} 
					//	
					if($imgStartPos > 0) 
					{
						$IMG_SOURCE = JString::substr($news_text, ($imgStartPos + 5), ($imgEndPos - ($imgStartPos + 5)));
					}
				}
			}	
		}	
		//
		if($IMG_SOURCE != '' && $config['news_content_image_pos'] != 0)
		{
			//
			$class = ''; 
			$margin = '';
			$attributes = '';
			$size = '';
			//
			if($config['image_nofollow'] == 1) $attributes .= ' rel="nofollow" ';
			//
			if($config['image_target'] == 1) $attributes .= ' target="_blank" ';
			//
			switch($config['news_content_image_pos']){
				case 0: $class .= '';break; 
				case 1: $class .= 'i_left';break; 
				case 2: $class .= 'i_right';break; 
				case 3: $class .= 'i_center';break; 
			}
			//
			if($config['img_margin'] != 0)
			{
				$margin = 'margin: '.$config['img_margin'].';';
			}
			//
			if($config['img_width'] != 0) $size .= 'width:'.$config['img_width'].';';
			if($config['img_height'] != 0) $size .= 'height:'.$config['img_height'].';';
			//
			if($config['news_image_link'] == 1)
			{
				//
				return ($config['news_content_image_pos'] == 3) ? '<div><a href="'.$IMG_LINK.'" '.$attributes.'><img class="gk_npro_image'.((isset($images[$news_iid])) ? '' : '_static').' '.$class.'" src="'.$IMG_SOURCE.'" alt="News image" style="'.((isset($images[$news_iid])) ? '' : $size).$margin.'"  /></a></div>' : '<a href="'.$IMG_LINK.'" '.$attributes.'><img class="gk_npro_image'.((isset($images[$news_iid])) ? '' : '_static').' '.$class.'" src="'.$IMG_SOURCE.'" alt="News image" style="'.((isset($images[$news_iid])) ? '' : $size).$margin.'"  /></a>';
			}
			else //
			{
				//
				return ($config['news_content_image_pos'] == 3) ? '<div><img class="gk_npro_image'.((isset($images[$news_iid])) ? '' : '_static').' '.$class.'" src="'.$IMG_SOURCE.'" alt="News image" '.((isset($images[$news_iid])) ? '' : $size).' '.$margin.' /></div>' : $news_image = '<img class="gk_npro_image'.((isset($images[$news_iid])) ? '' : '_static').' '.$class.'" src="'.$IMG_SOURCE.'" alt="News image" style="'.((isset($images[$news_iid])) ? '' : $size).$margin.'" />';
			}
		}
		else
		{
			return '';
		}
	}

	/**
	 *
	 * 
	 * 
	 * 
	 **/

	function returnReadMore($config, $news_id, $news_cid, $news_sid)
	{
		//
		if($config['news_content_readmore_pos'] != 0)
		{
			//
			$class = '';
			$attributes = '';
			//
			if($config['readmore_nofollow'] == 1) $attributes .= ' rel="nofollow" ';
			//
			if($config['readmore_target'] == 1) $attributes .= ' target="_blank" ';
			//
			switch($config['news_content_readmore_pos'])
			{
				case 0: $class = '';break; 
				case 1: $class .= 'p_left';break; 
				case 2: $class .= 'p_right';break; 
				case 3: $class .= 'p_center';break;
				case 4: $class = '';break; 
			}
			//
			if($config['news_content_readmore_pos'] != 4)
			{
				return '<a class="readon readon_class '.$class.'" href="'.JRoute::_(ContentHelperRoute::getArticleRoute($news_id, $news_cid, $news_sid)).'" '.$attributes.'>'.$config['news_readmore_text'].'</a>';
			}
			else
			{
				return '<a class="gk_npro_readmore_inline" href="'.JRoute::_(ContentHelperRoute::getArticleRoute($news_id, $news_cid, $news_sid)).'" '.$attributes.'>'.$config['news_readmore_text'].'</a>';
			}
		}
		else
		{
			return '';
		}
	}

	/**
	 *
	 * 
	 * 
	 * 
	 **/

	function returnInfo($config, $news_catname, $news_cid, $news_sid, $news_author, $news_date)
	{
		//
		$news_info = '';
		//
		if($config['news_content_info_pos'] != 0)
		{
			//
			if($config['news_datee'] == 1 || $config['news_cats'] == 1 || $config['news_authorr'] == 1)
			{	
				//
				$class = '';
				$attributes = '';
				//
				if($config['text_nofollow'] == 1) $attributes .= ' rel="nofollow" ';
				//
				if($config['text_target'] == 1) $attributes .= ' target="_blank" ';
				//
				if($config['news_content_info_pos'] == 1) $class = 'ta_left';
				elseif($config['news_content_info_pos'] == 2) $class = 'ta_right';
				else $class = 'ta_center';
				//
				$news_info .= '<p class="gk_npro_info '.$class.'">';
				//
				if($config['news_cats'] == 1)
				{
					$news_info .= '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($news_cid, $news_sid)).'" '.$attributes.'>'.$news_catname.'</a>';
				}
				//
				if($config['news_authorr'] == 1)
				{
					if($config['news_cats'] == 1)
					{
						$news_info .= ' | ';
					}
					//
					$news_info .= $news_author;
				}			
				//
				if($config['news_datee'] == 1)
				{
					if(($config['news_cats'] == 1 || $config['news_authorr'] == 1))
					{
						$news_info .= ' | ';
					}
					//
					$GKD = new GK_Date();
					$GKD->init();
					//
					$news_info .= $GKD->news_date($news_date, $config['date_format']); 
				}
				//
				$news_info .= '</p>';
			}	
		}
		//
		return $news_info;		
	}

	/**
	 *
	 * 
	 * 
	 * 
	 **/
	
	function returnList($config, $news_id, $news_cid, $news_sid, $news_title, $news_text, $odd)
	{
		//
		if($config['show_list'] == 1)
		{
			//
			$attributes = '';
			//
			if($config['list_nofollow'] == 1) $attributes .= ' rel="nofollow" ';
			//
			if($config['list_target'] == 1) $attributes .= ' target="_blank" ';
			//
			$text = strip_tags($news_text);
			$text = JString::substr($text, 0, $config['list_text_limit']);
			$title = $news_title;
			$title = JString::substr($title, 0, $config['list_title_limit']);
			// creating rest news list
			return '<li class="'.(($odd == 1) ? 'odd' : 'even').'"><h4><a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($news_id, $news_cid, $news_sid)).'" '.$attributes.'>'.$title.'</a></h4><p>'.$text.'</p></li>';	
		}
	}
}

?>