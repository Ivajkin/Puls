<?php 
/** 
*
* @package phpBB3
* @version $Id: sitemap_functions.php 2009-03-16 Joshua2100 $
* @copyright (c) 2007 Joshua www.commexcomputers.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
// clean url topicname...
function clean_for_xml($string) 
{
	$before = array('&', "'", '"', '>', '<');
	$after = array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;');
	$string = str_replace($before, $after, $string);
	return $string;
}

function generate_sitemap($seomod)
{
	global $config, $db, $phpbb_root_path, $phpEx, $user;

	@set_time_limit(300);

	$batch_size = 50000;  // According to sitemaps.org 50000 is the max allowed nr of URLs in one file

	$xml_files = array(); // All generated sitemap files, used to create the index file

	// Generic header for sitemap files
	$xml_header = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml_header .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
	$xml_footer = "</urlset>\n";

	$board_url = generate_board_url() . '/';

	$sql = 'SELECT *
		FROM ' . USERS_TABLE . '
		WHERE user_id = ' . intval($config['sitemap_bot_user']);
	$result = $db->sql_query($sql);
	$bot_userdata = $db->sql_fetchrow($result);
	$bot_auth = new Auth();
	$bot_auth->acl($bot_userdata);

	if ($config['sitemap_gzip'] == '1')
	{
		$sitemap_ext = '.xml.gz';
		$sitemap_open = 'gzopen';
		$sitemap_write = 'gzwrite';
		$sitemap_write_mode = 'w';
		$sitemap_close = 'gzclose';
	}
	else
	{
		$sitemap_ext = '.xml';
		$sitemap_open = 'fopen';
		$sitemap_write = 'fwrite';
		$sitemap_write_mode = 'w';
		$sitemap_close = 'fclose';
	}

	// Create file with forum URLs
	$bot_no_access = array();
	$bot_forum_auth_ary = $bot_auth->acl_getf('!f_list');
	foreach ($bot_forum_auth_ary as $forum_id => $not_allowed)
	{
		if ($not_allowed['f_list'])
		{
			$bot_no_access[] = (int) $forum_id;
		}
	}
	$sql = 'SELECT forum_id, forum_name, forum_last_post_time FROM ' . FORUMS_TABLE . ((sizeof($bot_no_access)) ? ' WHERE  ' . $db->sql_in_set('forum_id', $bot_no_access, true) : '');
	$result = $db->sql_query($sql);

	$filename = $config['sitemap_dir'] . '/sitemap_forums' . $sitemap_ext;
	if(!$fp = @$sitemap_open($phpbb_root_path . $filename, $sitemap_write_mode))
	{
		trigger_error('Unable to open ' . $filename . ' for writing');
	}
	
	$lastmod = 0;
	$sitemap_write($fp, $xml_header);
	$rows = $db->sql_fetchrowset($result);
	foreach($rows as $row)
	{
		$f_xml = '<url>'."\n";
		if ($config['sitemap_seo_mod'] == '1') 
		{
			if ($seomod == 'handymans')
			{
				$forumname = clean_url($row['forum_name']);
				$forumname = clean_for_xml($forumname);
			} 
			elseif ($seomod == 'phpbbseo')
			{
			global $phpbb_seo;
				$forumname = $phpbb_seo->format_url($row['forum_name']); 
			}
			else
			{
				trigger_error($user->lang['SEO_NOT_INT'], E_USER_WARNING);
			}
			$f_xml .= '<loc>' . $board_url . $forumname . '-f' . $row['forum_id'] . '.html</loc>' . "\n";
		} 
		else 
		{
			$f_xml .= '<loc>' . $board_url . "viewforum.$phpEx?f=" . $row['forum_id'] . '</loc>' . "\n";
		}
		$f_xml .= "<lastmod>" . date(DATE_W3C, $row['forum_last_post_time']) . "</lastmod>\n";
		$lastmod = max($row['forum_last_post_time'], $lastmod);
	
		$f_xml .= '</url>'."\n";

		$sitemap_write($fp, $f_xml);
	}

	$xml_files[$filename] = $lastmod;

	$sitemap_write($fp, $xml_footer);
	$sitemap_close($fp);

	// Create files with topic URLs
	$bot_no_access = array();
	$bot_forum_auth_ary = $bot_auth->acl_getf('!f_read');
	foreach ($bot_forum_auth_ary as $forum_id => $not_allowed)
	{
		if ($not_allowed['f_read'])
		{
			$bot_no_access[] = (int) $forum_id;
		}
	}

	$sql = "SELECT MAX(topic_id) AS max_topic_id FROM " . TOPICS_TABLE;
	$result = $db->sql_query($sql);
	$max_topic_id = $db->sql_fetchfield('max_topic_id', false, $result);

	$batch_start = 0;
	while ($batch_start < $max_topic_id)
	{
		$lastmod = 0;

		$filename = $config['sitemap_dir'] . '/sitemap_topics_' . sprintf('%07s', $batch_start) . '-' . sprintf('%07s', ($batch_start+$batch_size)) . $sitemap_ext;
		if(!$fp = @$sitemap_open($phpbb_root_path . $filename, $sitemap_write_mode))
		{
			trigger_error('Unable to open ' . $filename . ' for writing');
		
		}

		$sql = 'SELECT forum_id, topic_id' . ($config['sitemap_seo_mod'] == '1' ? ', topic_title' : '') . ', topic_last_post_time 
				FROM ' . TOPICS_TABLE . '
				WHERE  
					topic_id BETWEEN ' . $batch_start . ' AND ' . ($batch_start + $batch_size) .
					((sizeof($bot_no_access)) ? ' AND ' . $db->sql_in_set('forum_id', $bot_no_access, true) : '');
		$result = $db->sql_query($sql);

		$sitemap_write($fp, $xml_header);
		while ($row = $db->sql_fetchrow($result)) 
		{
			$f_xml = '<url>'."\n";
			if ($config['sitemap_seo_mod'] == '1') 
			{
				if ($seomod == 'handymans')
				{
					$topicname = clean_url($row['topic_title']);
					$topicname = clean_for_xml($topicname);
				} 
				elseif ($seomod == 'phpbbseo')
				{
					$forumname = $phpbb_seo->format_url($row['topic_title']); 
				}
				else
				{
					trigger_error($user->lang['SEO_NOT_INT'] . adm_back_link($this->u_action), E_USER_WARNING);
				}
				$f_xml .= '<loc>' . $board_url . $topicname . '-t' . $row['topic_id'] . '.html</loc>' . "\n";
			} 
			else 
			{
				$f_xml .= '<loc>' . $board_url . "viewtopic.$phpEx?f=" . $row['forum_id'] . '&amp;t=' . $row['topic_id'] . "</loc>\n";
			}
			$lastmod = max($lastmod, $row['topic_last_post_time']);
			$f_xml .= "<lastmod>" . date(DATE_W3C, $row['topic_last_post_time']) . "</lastmod>\n";
			$f_xml .= '</url>'."\n";

			$sitemap_write($fp, $f_xml);
		}

		$sitemap_write($fp, $xml_footer);
		$sitemap_close($fp);
		$xml_files[$filename] = $lastmod;
		$batch_start += $batch_size;
	}

	// Sitemap index
	$filename = $config['sitemap_dir'] . '/sitemap_index' . $sitemap_ext;
	if(!$fp = @$sitemap_open($phpbb_root_path . $filename, $sitemap_write_mode))
	{
		trigger_error('Unable to open ' . $filename . ' for writing');
	
	}
	$sitemap_write($fp, '<?xml version="1.0" encoding="UTF-8"?>
   		<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
	foreach($xml_files as $filename => $lastmod)
	{
		$sitemap_write($fp, "
			<sitemap>
				<loc>$board_url$filename</loc>
				<lastmod>" . date(DATE_W3C, $lastmod) . "</lastmod>
			</sitemap>");
	}
	$sitemap_write($fp, '</sitemapindex>');
	$sitemap_close($fp);
}

function pinggooglesitemaps($url_xml)
// @author     J de Silva  <giddomains@gmail.com>
// @link       http://www.gidnetwork.com/b-54.html  PHP function to ping Google Sitemaps
{
	global $user;
	$status = 0;
	$google = 'www.google.com';
	if( $fp=@fsockopen($google, 80) )
	{
		$req =  'GET /webmasters/tools/ping?sitemap=' .
				urlencode( $url_xml ) . " HTTP/1.1\r\n" .
				"Host: $google\r\n" .
				"User-Agent: Mozilla/5.0 (compatible; " .
				PHP_OS . ") PHP/" . PHP_VERSION . "\r\n" .
				"Connection: Close\r\n\r\n";
		fwrite( $fp, $req );
		while( !feof($fp) )
		{
			if( @preg_match('~^HTTP/\d\.\d (\d+)~i', fgets($fp, 128), $m) )
			{
				$status = intval( $m[1] );
				 break;
			}
		}
		echo $req;
	fclose( $fp );
	}
	if ($status != '200') 
	{
		trigger_error($user->lang['MAP_GOOGLE_PING'] . '<br />' . $status . $user->lang['PING_ERROR'] , E_USER_WARNING);
	}
}
?>