<?php

/*
 * $Id: $
 * VirtueMart Extended Search Bot (based on work from Soeren Eberhardt & Pietro Gallo & Alejandro Kurczyn)
 * @version 1.1 
 * 
 * @copyright (C) Copyright 2010 by Tomasz Wylandowski
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

/** ensure this file is being included by a parent file */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ) ;

/** Register search function inside Joomla's API */

$mainframe->registerEvent( 'onSearch', 'plgSearchVirtueMart' ) ;
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchVirtuemartAreas' );

JPlugin::loadLanguage( 'plg_search_virtuemart' );

/**
 * @return array An array of search areas
 */
function &plgSearchVirtuemartAreas()
{
	static $areas = array(
		'virtuemart.search' => 'Catalogue'
	);
	return $areas;
}

/**
 * Search method
 *
 * The sql must return the following fields that are used in a common display
 * routine: href, title, section, created, text, browsernav
 */

function plgSearchVirtueMart( $text, $phrase='', $ordering='', $areas=null )
{
  $database =& JFactory::getDBO();
  
  require_once(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart_parser.php');
   
  //If the array is not correct, return it:
  if (is_array( $areas )) {
     if (!array_intersect( $areas, array_keys( plgSearchVirtuemartAreas() ) )) {
        return array();
     }
  }
 
  //It is time to define the parameters! First get the right plugin; 'search' (the group), 'nameofplugin'. 
  $plugin =& JPluginHelper::getPlugin('search', 'virtuemart.search');
 
  //Then load the parameters of the plugin..
  $pluginParams = new JParameter( $plugin->params );
  
  //Use the function trim to delete spaces in front of or at the back of the searching terms
  $text = trim( $text ) ;

  //Return Array when nothing was filled in
	if( $text == '' ) {
		return array( ) ;
	}

	$fields = array( ) ;
	
	$thumbnail_flag = $pluginParams->def( 'thumbnail_flag', 1 ) ;
	$thumbnailx = $pluginParams->def( 'thumbnailx', 40 ) ;
	$thumbnaily = $pluginParams->def( 'thumbnaily', 40 ) ;
	
	if( $pluginParams->def( 'name_flag', 1 ) == 1 )
		$fields[] = 'p.product_name' ;

	if( $pluginParams->def( 'sku_flag', 1 ) == 1 )
		$fields[] = 'p.product_sku' ;

	if( $pluginParams->def( 'desc_flag', 1 ) == 1 )
		$fields[] = 'p.product_desc' ;

	if( $pluginParams->def( 'sdesc_flag', 1 ) == 1 )
		$fields[] = 'p.product_s_desc' ;

	if( $pluginParams->def( 'url_flag', 1 ) == 1 )
		$fields[] = 'p.product_url' ;

	if( $pluginParams->def( 'review_flag', 1 ) == 1 )
		$fields[] = 'r.comment' ;

	if( $pluginParams->def( 'manufacturer_flag', 1 ) == 1 )
		$fields[] = 'm.mf_name' ;

	if( $pluginParams->def( 'category_flag', 1 ) == 1 )
		$fields[] = 'c.category_name' ;

	$oos_where = (PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != 1) ? 'and product_in_stock > 0' : '' ;

	switch( $pluginParams->def( 'parent_filter', 'both' )) {
		case 'parent' :
			$parent_where = "AND (p.product_parent_id='' OR p.product_parent_id='0')" ;
		break ;
		case 'child' :
			$parent_where = "AND (p.product_parent_id > '0')" ;
		break ;
		case 'both' :
			$parent_where = '' ;
		break ;
	}

	// Build search logic
	$wheres = array( ) ;
	switch( $phrase) {
		case 'exact' :
			$wheres2 = array( ) ;
			foreach( $fields as $field ) {
				$wheres2[] = "$field LIKE '%".$database->getEscaped($text)."%'" ;
			}
			$where = '(' . implode( ') OR (', $wheres2 ) . ')' ;
		break ;
		case 'all' :
		case 'any' :
		default :
			$words = explode( ' ', $text ) ;
			$wheres = array( ) ;
			foreach( $words as $word ) {
				$wheres2 = array( ) ;
				foreach( $fields as $field ) {
					$wheres2[] = "$field LIKE '%".$database->getEscaped($word)."%'" ;
				}
				$wheres[] = implode( ' OR ', $wheres2 ) ;
			}
			$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')' ;
		break ;
	}

	switch( $ordering) {
		case 'newest' :
		default :
			$order = 'p.cdate DESC' ;
		break ;
		case 'oldest' :
			$order = 'p.cdate ASC' ;
		break ;
		case 'popular' :
			$order = 'p.product_name ASC' ;
		break ;
		case 'alpha' :
			$order = 'p.product_name ASC' ;
		break ;
		case 'category' :
			$order = 'p.category_name ASC' ;
		break ;
	}

	if( $pluginParams->def( 'density_flag', 1 ) == 1 ) {
		$whole_text = ", CONCAT_WS(' ',LOWER(" . implode( '), LOWER(', $fields ) . ')) AS whole_text ' ;
	} else {
		$whole_text = '' ;
	}

	//Get Virtuemart module ID
	$database->setQuery( " SELECT id, name FROM  `#__menu` WHERE link LIKE '%com_virtuemart%' AND published=1 AND access=0" ) ;
	$Item = $database->loadObject() ;
	$ItemName = ! empty( $Item->name ) ? $Item->name : "Shop" ;
	$Itemid = ! empty( $Item->id ) ? $Item->id : "1" ;

	$query = "SELECT DISTINCT p.product_id, p.product_name as title,
					FROM_UNIXTIME( p.cdate, '%Y-%m-%d %H:%i:%s'  ) AS created,
					p.product_s_desc AS text,
					CONCAT('$ItemName/',c.category_name) as section,
					CONCAT('index.php?option=com_virtuemart&page=shop.product_details&flypage=',IFNULL(c.category_flypage,'" . FLYPAGE . "'),'&category_id=',IFNULL(c.category_id,''),'&product_id=',p.product_id,'&Itemid='," . $Itemid . ") as href,
					'2' as browsernav 
					$whole_text 
					FROM #__vm_product p
					LEFT JOIN #__vm_product_reviews r ON (r.product_id = p.product_id) 
					LEFT JOIN #__vm_product_mf_xref mx ON (mx.product_id = p.product_id) 
					LEFT JOIN #__vm_manufacturer m ON (m.manufacturer_id = mx.manufacturer_id),
						#__vm_product_category_xref cx, #__vm_category c
						WHERE ($where)" . "\n AND cx.product_id = p.product_id
							AND cx.category_id = c.category_id $parent_where
							AND c.category_publish='Y'
							AND p.product_publish='Y'
					$oos_where
					ORDER BY $order" ;

	//echo "\n-QUERY:\n$query\n";
	$database->setQuery( $query ) ;

	$row = $database->loadObjectList() ;

	if( ! empty( $row ) && $pluginParams->def( 'density_flag', 1 ) == 1 ) {
		$txt = strtolower( $text ) ;
		if( $phrase != 'exact' )
			$txt_array = explode( ' ', $txt ) ; else
			$txt_array[0] = $txt ;
		$i = 0 ;
		foreach( $row as $result ) {
			$count = 0 ;
			foreach( $txt_array as $txt ) {
				$count = substr_count( $result->whole_text, $txt ) + $count ;
			}
			$row[$i]->count = $count ;
			$i ++ ;
		}
		pg_keydenseSort( $row, 'count', 'DESC' ) ;
	}
	return $row ;
}

function pg_keydenseSort( &$data, $key, $order )
{
	for( $i = count( $data ) - 1 ; $i >= 0 ; $i -- ) {
		$swapped = false ;
		for( $j = 0 ; $j < $i ; $j ++ ) {
			if( $order == 'ASC' ) {
				if( $data[$j]->$key > $data[$j + 1]->$key ) {
					$tmp = $data[$j] ;
					$data[$j] = $data[$j + 1] ;
					$data[$j + 1] = $tmp ;
					$swapped = true ;
				}
			} else {
				if( $data[$j]->$key < $data[$j + 1]->$key ) {
					$tmp = $data[$j] ;
					$data[$j] = $data[$j + 1] ;
					$data[$j + 1] = $tmp ;
					$swapped = true ;
				}
			}
		}
		if( ! $swapped )
			return ;
	}
}
?>