<?php
/**
* @package Joomla! 1.5.x
* @author 2008-2010 (c)  Denys Nosov (aka Dutch)
* @author web-site: www.joomla-ua.org
* @copyright This module is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License.
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

error_reporting(0);

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modJUNewsUltraHelper {

	function getList(&$params) {

		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');

		$count		= (int) $params->get('count', 5);

        $selekt_secid_catid = $params->def('selekt_secid_catid', '1') ;
        $catid_select       = trim( $params->get( 'catid_select' ) );
        $secid_select       = trim( $params->get( 'secid_select' ) );
        $catid_text         = trim( $params->get( 'catid_text' ) );
        $secid_text         = trim( $params->get( 'secid_text' ) );


        $show_frontpage	        = $params->get('show_frontpage', 1);
		$aid		        = $user->get('aid', 0);

        //INTRO
        $show_intro         = $params->get( 'show_intro');
        $introtext_limit    = intval( $params->get( 'introtext_limit', '10') );
        $li                 = $params->def('li', '1');
        $lmttext            = $params->def('lmttext', '1');
        $clear_tag          = $params->def('clear_tag', '1');

        // CATEGORY AND SECTION
        $showsec          = $params->def('showsec', '1');
        $showseclink      = $params->def('showseclink', '1');
        $showcat          = $params->def('showcat', '1');
        $showcatlink      = $params->def('showcatlink', '1');

        //READMORE
        $read_more          = $params->get('read_more', '1');
        $rmtext             = $params->def('rmtext', 'Read more...');

        //AUTHOR
        $avtor = $params->def('avtor', '1');

        //DATA
        $showDate           = $params->get('showDate', 1);
        $data_format        = $params->get('data_format', '%d.%m.%Y');
        $df_d               = $params->get('df_d', '%d');
        $df_m               = $params->get('df_m', '%m');
        $df_y               = $params->get('df_y', '%Y');

        //TIPS
        $dgmtips            = $params->get('dgmtips', '1');

        //IMAGE
        $imageWidth         = intval($params->get('imageWidth', '50'));
        $imageHeight        = intval($params->get('imageHeight', ''));
        $thumb_width        = intval($params->get('thumb_width', 1));
        $thumb_filter       = intval($params->get('thumb_filter', 1));
        $Zoom_Crop          = intval($params->get('Zoom_Crop', 0));
        $thumb_filter_color = intval($params->get('thumb_filter_color', 1));
        $pik                = $params->def('pik', '1');
        $noimage            = $params->def('noimage', '1');
        $imglink            = $params->def('imglink', '1');
        $moreparam          = $params->def('moreparam', '');

        //HITS
        $showHits           = $params->get('showHits', '1');

        //COMMENTS
        $JC = $params->def('JC', '0');

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('show_noauth');

        if ($selekt_secid_catid == 1){
            $catid  = $catid_select;
            $secid  = $secid_select;
        } else{
            $catid  = $catid_text;
            $secid  = $secid_text;
        }        

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;

		// User Filter
		switch ($params->get( 'user_id' )) {
			case 'by_me':
				$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
				break;
		}

		// Ordering
		switch ($params->get( 'ordering' )) {
            case 'title':
                $orderBy = 'a.title ASC';
            break;
            case 'title_desc':
                $orderBy = 'a.title DESC';
            break;
            case 'id':
                $orderBy = 'a.id';
            break;
            case 'id_desc':
                $orderBy = 'a.id DESC';
            break;
            case 'hits':
                $orderBy = 'hits DESC';
            break;
            case 'created':
                $orderBy = 'a.created';
            break;
            case 'modified_desc':
                $orderBy = 'a.modified DESC';
            break;
            case 'ordering':
                $orderBy = 'a.ordering ASC';
            break;
            case 'ordering_desc':
                $orderBy = 'a.ordering DESC';
            break;
            case 'rand':
                $orderBy = 'rand()';
            break;
            case 'created_desc':
            default:
                $orderBy = 'a.created DESC';
            break;
		}

		if ($catid) {
			$ids = explode( ',', $catid );
			JArrayHelper::toInteger( $ids );
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
        }

        if ($secid) {
			$ids = explode( ',', $secid );
			JArrayHelper::toInteger( $ids );
			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
        }

        if ($show_frontpage == 1) {
            $joinfront = "\n INNER JOIN #__content_frontpage AS f ON f.content_id = a.id";
        } else {
            $joinfront = ($show_frontpage == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '');
        }

		// Content Items only
		$query = 'SELECT a.*, u.name, a.created_by_alias, cc.title AS cattitle, s.title AS sectitle, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug,'.
			' CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(":", s.id, s.alias) ELSE s.id END as sectionslug'.
			' FROM #__content AS a' .
            $joinfront .
            ' LEFT JOIN #__users AS u ON u.id = a.created_by'   .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			($catid ? $catCondition : '').
			($secid ? $secCondition : '').
            ($show_frontpage == '0' ? ' AND f.content_id IS NULL' : '').
			' AND s.published = 1' .
			' AND cc.published = 1' .
			' ORDER BY '. $orderBy;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();

        foreach ( $rows as $row ) {
			if($row->access <= $aid) {
				$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
                $catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($row->catslug, $row->sectionid));
                $seclink = JRoute::_(ContentHelperRoute::getSectionRoute($row->sectionid));
				$lists[$i]->readmore = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
            } else {
            	$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
                $catlink = JRoute::_('index.php?option=com_user&view=login');
                $seclink = JRoute::_('index.php?option=com_user&view=login');
            	$lists[$i]->readmore = JRoute::_('index.php?option=com_user&view=login');
            }

            //TITLE
            /*article title*/
            $lists[$i]->text = htmlspecialchars( $row->title );
            /*section title*/
            $sectitle = htmlspecialchars( $row->sectitle );
            /*category title*/
            $cattitle = htmlspecialchars( $row->cattitle );

            if($showsec == 1){
                if($showseclink == 1){
                    $lists[$i]->sectitle = '<a href="'. $seclink .'" title="'. $sectitle .'">'.$sectitle .'</a>';
                } else {
                    $lists[$i]->sectitle = $sectitle;
                }
            }

            if($showcat == 1){
                if($showcatlink == 1){
                    $lists[$i]->cattitle = '<a href="'. $catlink .'" title="'. $cattitle .'">'.$cattitle .'</a>';
                } else {
                    $lists[$i]->cattitle = $cattitle;
                }
            }

            //READMORE
            $lists[$i]->rmtext = $rmtext;

            //TIPS
            if ($dgmtips == 1) {
                $dgm_title = ' title="'. htmlspecialchars( $row->title ) .'" ';
            }

            //IMAGE
            if ($pik==1) {
                $iW     = $imageWidth;
                $iH     = $imageHeight;

                if ($imglink == 1) {
                    $imlink = '<a href="'. $lists[$i]->link .'"'. $dgm_title .'>';
                    $imlink2 = '</a>';
                }

                $imgPrefix = JURI::base() .'modules/mod_junewsultra/img/img.php?src=../../../';
                if ($thumb_width==1){
                    if ($Zoom_Crop==1){
                        $zc = '&amp;zc=1';
                    } else {
                        $zc = '&amp;zc=0';
                    }
                    if ($thumb_filter==1){
                        if ($thumb_filter_color==1){
                            $imgfiltr = '&amp;fltr[]=sep';
                        } else {
                            $imgfiltr = '&amp;fltr[]=gray';
                        }
                        $img_filtr = $imgfiltr;
                    }
                    //$imgthr = JURI::base() .'modules/mod_junewsultra/img/img.php?src=';
                    $imgthr = JURI::base() .'modules/mod_junewsultra/img/';
                    $imgthr2 = '&amp;w='. $iW .'&amp;h='. $iH .'&amp;q=100'. $zc . $img_filtr . $moreparam;
                }

                $junuimgresmatche = $row->introtext . $row->fulltext;
                preg_match('#src="(.*?)"#s',$junuimgresmatche,$junuimgsource);
                $junuimgsource  = $junuimgsource[1];
                $junuimgsource  = str_replace(JURI::base(), '', $junuimgsource);

                if ($thumb_width==1) {
                    $junuimg        = base64_encode(  '../../../' . $junuimgsource . $imgthr2 );
                    $jununoimg      = base64_encode(  '../../../media/mod_junewsultra/' . $noimage . $imgthr2 );

                    if( $junuimgsource  ){
                        $lists[$i]->image = $imlink .'<img src="'. $imgthr . $junuimg .'_junus.jpg" />'. $imlink2;
                    } else {
                        $lists[$i]->image = $imlink .'<img src="'. $imgthr . $jununoimg .'_junus.jpg" />'. $imlink2;
                    }
                } else {
                    $junuimg        = $junuimgsource;
                    $jununoimg      = JURI::base().'/media/mod_junewsultra/' . $noimage;

                    if( $junuimgsource  ){
                        $lists[$i]->image = $imlink .'<img src="'. $imgthr . $junuimg .'" />'. $imlink2;
                    } else {
                        $lists[$i]->image = $imlink .'<img src="'. $imgthr . $jununoimg .'" />'. $imlink2;
                    }
                }
            }

            //INTROTEX
            if ($clear_tag == 1){
                $row->introtext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $row->introtext);
                $row->introtext = str_replace( '&nbsp;', ' ', $row->introtext );
                $row->introtext = htmlspecialchars( strip_tags( $row->introtext ) );
            } else {
                $row->introtext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $row->introtext);
                $row->introtext = preg_replace('/<img(.*?)>/i', '', $row->introtext);
            }

            if ($li==1){
                if ($lmttext==1){
                    $lists[$i]->introtext =  implode(" ", array_slice(explode(" ", $row->introtext), 0, $introtext_limit)) .'...';
                } else {
                    $lists[$i]->introtext = substr($row->introtext, 0, $introtext_limit).'...';
                }
            } else {
                $lists[$i]->introtext = $row->introtext;
            }

            //AUTHOR
            if ($avtor==1) {
                if ( $row->created_by_alias ) {
				    $lists[$i]->author = $row->created_by_alias;
			    } else {
				    $lists[$i]->author = htmlspecialchars( $row->name, ENT_QUOTES, 'UTF-8' );
			    }
            }

            //DATA
            if ($showDate){
                $lists[$i]->created     = JHTML::_('date', $row->created, $data_format );
                $lists[$i]->df_d   = JHTML::_('date', $row->created, $df_d );
                $lists[$i]->df_m   = JHTML::_('date', $row->created, $df_m );
                $lists[$i]->df_y   = JHTML::_('date', $row->created, $df_y );
            }
            //SHOW HITS
            if ($showHits){
                $lists[$i]->hits   = $row->hits;
            }

            //JComments
            if ($JC) {
                $comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
            	if (file_exists($comments)) {
            		require_once($comments);
            		$count = JComments::getCommentsCount($row->id, 'com_content');
                    $lists[$i]->comments = $count ? ('<a class="comment-link" href="'. $lists[$i]->link .'#comments">'. JText::_('JCOMMENTS_COUNT') .' ('. $count . ')</a>') : '<a class="comment-link" href="'. $lists[$i]->link .'#addcomments">'. JText::_('JCOMMENTS_WRITE') .'</a>';
            	}
            } else {
                $lists[$i]->comments     = '';
            }

			$i++;

		}

		return $lists;

	}

}

?>