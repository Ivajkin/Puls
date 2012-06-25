<?php
/**
* @package Joomla! 1.5.x
* @author 2008 (c)  Denys Nosov (aka Dutch)
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

        //READMORE
        $read_more          = $params->get('read_more', '1');
        $rmtext             = $params->def('rmtext', 'Read more...');

        //AUTHOR
        $avtor = $params->def('avtor', '1') ;

        //DATA
        $showDate           = $params->get('showDate', 1);
        $data_format        = $params->def( 'data_format', 'd.m.Y' );
        $df_d      = $params->def( 'df_d', 'd' );
        $df_m      = $params->def( 'df_m', 'm' );
        $df_y      = $params->def( 'df_y', 'Y' );

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
        $imglink            = $params->def('imglink', '1');
        $moreparam          = $params->def('moreparam', '');

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
		$query = 'SELECT a.*, u.name, a.created_by_alias, cc.title AS cattitle,  ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
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
                $lists[$i]->catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($row->catslug, $row->sectionid));
				$lists[$i]->readmore = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
            } else {
            	$lists[$i]->link = JRoute::_('index.php?option=com_user&view=login');
            	$lists[$i]->catlink = JRoute::_('index.php?option=com_user&view=login');
            	$lists[$i]->readmore = JRoute::_('index.php?option=com_user&view=login');
            }

            //TITLE
            $lists[$i]->text = htmlspecialchars( $row->title );
            $lists[$i]->cattitle = htmlspecialchars( $row->cattitle );

            //READMORE
            $lists[$i]->rmtext = $rmtext;

            //TIPS
            if ($dgmtips == 1) {
                $dgm_title = ' class="Tips2" title="'. htmlspecialchars( $row->title ) .'" ';
            } else {
                $dgm_title = '';
            }

            //IMAGE
            if ($pik==1) {

                $iW     = $imageWidth;
                $iH     = $imageHeight;

                if ($imglink == 1) {
                    $imlink = '<a href="'. $lists[$i]->link .'"'. $dgm_title .'>';
                    $imlink2 = '</a>';
                } else {
                    $imlink = '';
                    $imlink2 = '';
                }

                $imgPrefix = JURI::base() .'modules/mod_junewsultra/img/img.php?src=../../../';
                if ($thumb_width==1){
                    if ($Zoom_Crop==1){
                        $zc = '&amp;zc=1';
                    } else {
                        $zc = '';
                    }
                    if ($thumb_filter==1){
                        if ($thumb_filter_color==1){
                            $imgfiltr = '&amp;fltr[]=sep';
                        } else {
                            $imgfiltr = '&amp;fltr[]=gray';
                        }
                        $img_filtr = $imgfiltr;
                    } else {
                        $img_filtr = '';
                    }
                    //$imgthr = JURI::base() .'modules/mod_junewsultra/img/img.php?src=';
                    $imgthr = JURI::base() .'modules/mod_junewsultra/img/';
                    $imgthr2 = '&amp;w='. $iW .'&amp;h='. $iH . $zc . $img_filtr . $moreparam .'&amp;q=100';
                } else { }

                    $junuimgresmatche = $row->introtext . $row->fulltext;
                    preg_match('#src="(.*?)"#s',$junuimgresmatche,$junuimgsource);
                    $junuimgsource  = $junuimgsource[1];
                    $junuimgsource  = str_replace(JURI::base(), '', $junuimgsource);

                $srcimgsource   = $junuimgsource;

                if( $junuimgsource  ){
                    $lists[$i]->srcimage = $srcimgsource;
                } else {
                    $lists[$i]->srcimage = $jununoimg;
                }                    

                    $junuimg  = base64_encode(  '../../../' . $junuimgsource . $imgthr2 );

                    if( $junuimgsource  ){
                        $lists[$i]->image = $imlink .'<img src="'. $imgthr . $junuimg .'_junus.jpg" alt="" />'. $imlink2;
                    } else {
                        $junuimg  = base64_encode(  '../../../media/mod_junewsultra/none.jpg' . $junuimgsource . $imgthr2 );
                        $lists[$i]->image = $imlink .'<img src="'.$imgthr. $junuimg.'_junus.jpg"  alt="" />'. $imlink2;
                    }

            } else {
                $iW     = '';
                $iH     = '';
            }

            //INTROTEX
            if ($clear_tag == 1){
                $row->introtext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $row->introtext);
                $row->introtext = str_replace( '&nbsp;', ' ', $row->introtext );
                $row->introtext = htmlspecialchars( strip_tags( $row->introtext ) );
            } else {
                $row->introtext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $row->introtext);
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
            } else {
                $lists[$i]->author = '';
            }

            //DATA
            if ($showDate) {
                $lists[$i]->created     = date($data_format, strtotime( $row->created ) );
                $lists[$i]->df_d   = date($df_d, strtotime( $row->created ) );
                $lists[$i]->df_m   = date($df_m, strtotime( $row->created ) );
                $lists[$i]->df_y   = date($df_y, strtotime( $row->created ) );
            } else {
                $lists[$i]->created     = '';
            }

			$i++;

		}

		return $lists;

	}

}

?>