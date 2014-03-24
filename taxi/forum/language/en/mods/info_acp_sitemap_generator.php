<?php
/**
*
* mcp [English]
*
* @package language
* @version $Id: info_acp_sitemap_generator.php 2007-11-26 Joshua2100 $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_SITEMAP_GENERATOR'	=> 'SiteMap Generator',
	'SITEMAP_GEN_DETAILS'	=> 'Generates an XML sitemap for you forum',
	
	'SM_SETTINGS'			=> 'Sitemap Settings',
	'GENERATE'				=> 'Generate Sitemap',
	
	'RUN_NOW'				=> 'XML Sitemap Generator',
	'RUN_DESC'				=> 'Run the Sitemap Generator and Create Your XML SiteMap',
	'PINGORNOT'				=> 'Google Server Ping',
	'PINGORNOT_DESC'		=> 'Ping the Google Server After Sitemap Creation',
	'SEO_MOD'				=> 'SEOMOD',
	'SEOMOD_DESC'			=> 'If enabled, SEOMOD style URLs will be used in the Sitemaps',
	'NOTE'					=> 'Please Note',
	'THE_NOTE'				=> 'To use Google Sitemaps, first you must register at www.google.com/webmasters/sitemaps/',
	'MAP_RETURN'			=> 'Return to SiteMap Generator Index',
	
	'PING_SUCCESS'			=> '% has successfuly been informed of your updated sitemap',
	'PING_FAIL'				=> 'Error: Server ping failed',
	
	'GENERATE_COMPLETE'		=> 'All Sitemaps have Successfuly been Generated.',
	'GENERATE_NOTSUCCESS'	=> 'Error: An Error has occured during Sitemap Creation',
		
	'GENERATE_COMPLETE'		=> 'SiteMap Creation Complete.',	
	'MAP_CREATE_FAIL'		=> 'Creating Sitemap Failed',
	'MAP_WRITE_FAIL'		=> 'Writting Sitemap to file Failed',
	'MAP_GOOGLE_PING'		=> 'Pinging Google Failed.',
	'PING_ERROR'			=> ' Error Returned',
	
	'SM_STATS'				=> 'SiteMap Stats',
	'SM_TOPIC_LINK'			=> 'Number of Topic Links',
	'SM_FORUM_LINK'			=> 'Number of Forum Links',
	'SM_VERSION'			=> 'SiteMap Generator Version',
	'SM_TOPIC_SIZE'			=> 'TopicMap Size',
	'SM_FORUM_SIZE'			=> 'ForumMap Site',

	'MAPZIP'				=> 'Gzip Sitemaps',
	'SHOWSTAT'				=> 'Show Sitemap Stats',
	
	'SEO_NOT_INT'			=> 'You Have SEOMOD turned on, But SEO MOD is not installed..<br />You need to either install SEO MOD, or turn SEOMOD to off in the settings',
));

?>