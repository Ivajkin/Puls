<?php
/**
*
* @package phpBB3
* @version $Id: prime_links.php,v 1.3.0 2012/02/08 13:15:00 primehalo Exp $
* @copyright (c) 2007-2012 Ken F. Innes IV
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Include only once.
*/
if (!class_exists('prime_links'))
{
	global $config;
	$internal_link_domains = 'http://' . $config['server_name'];
	$external_link_prefix = 'http://' . $config['server_name'] . ((in_array($config['script_path'], array('/', "/\\")))? '' : rtrim($config['script_path'])) . '/go.php?';	// Options
	define('PRIME_LINKS_ENABLE', true);			// Enable this MOD?
	define('USE_TARGET_ATTRIBUTE', false);		// The attribute "target" is not valid for STRICT doctypes.
	define('HIDE_LINKS_FROM_GUESTS', false);	// Hide external links from guests? If this is a string, then the text of the link will be replaced with this string.
	define('EXTERNAL_LINK_PREFIX', $external_link_prefix);			// Example: 'http://anonym.to?'
	define('INTERNAL_LINK_DOMAINS', $internal_link_domains);		// List of domains to be considered local, separated by semicolons. Example: 'http://www.alternate-domain.com'
	define('FORBIDDEN_DOMAINS', '');			// List of domains for which links should be removed, separated by semicolons. Example: 'http://www.porn.com'
	define('FORBIDDEN_NEW_URL', '#');			// URL to insert in place of any removed links. Example: 'http://www.google.com' or '#top'

	// Link relationships
	define('INTERNAL_LINK_REL', '');
	define('EXTERNAL_LINK_REL', 'nofollow');

	// Link targets (setting to FALSE will remove the link)
	define('INTERNAL_LINK_TARGET', '');
	define('EXTERNAL_LINK_TARGET', '_blank');

	// Link classes
	define('INTERNAL_LINK_CLASS', 'postlink-local');
	define('EXTERNAL_LINK_CLASS', 'postlink');

	// Link file types (separate file extensions with a vertical bar "|")
	define('PDF_LINK_TYPES', 'pdf');
	define('IMG_LINK_TYPES', 'gif|jpg|jpeg|png|bmp');
	define('ZIP_LINK_TYPES', 'zip|rar|7z');

	// Special cases for specific link types. Separate file extensions with a vertical bar (|).
	define('EXTERNAL_LINK_TYPES', '');			// Example 1: 'pdf|gif|jpg|jpeg|png|bmp|zip|rar|7z'
	define('INTERNAL_LINK_TYPES', '');			// Example 2: PDF_LINK_TYPES . '|' . IMG_LINK_TYPES . '|' . ZIP_LINK_TYPES
	define('SKIP_LINK_TYPES', '');				// Don't process links to these file types
	define('SKIP_PREFIX_TYPES', '');			// Don't add an external link prefix for these file types

	// Link classes for specific file types.
	global $link_type_classes;
	$link_type_classes = array(
		PDF_LINK_TYPES	=> 'pdf-link',
		IMG_LINK_TYPES	=> 'img-link',
		ZIP_LINK_TYPES	=> 'zip-link',
	);

	/**
	*/
	function prime_links($text = null)
	{
		if (PRIME_LINKS_ENABLE)
		{
			if(is_string($text))
			{
				$prime_links = new prime_links();
				$text = $prime_links->modify_links($text);
			}
			else if ((EXTERNAL_LINK_TARGET || EXTERNAL_LINK_REL) && $text === null)
			{
				global $template;
				$external_target = !EXTERNAL_LINK_TARGET ? '' : (USE_TARGET_ATTRIBUTE === true ? (' target="' . EXTERNAL_LINK_TARGET . '"') : (' onclick="this.target=\'' . EXTERNAL_LINK_TARGET . '\';"'));
				$external_rel = !EXTERNAL_LINK_REL ? '' : ' rel="' . EXTERNAL_LINK_REL . '"';
				$template->assign_vars(array(
					'EXTERNAL_LINK_TARGET' => $external_target,
					'EXTERNAL_LINK_REL'	=> $external_rel,
					'EXTERNAL_LINK_ATTRIBUTES' => $external_target . $external_rel,
				));
				if (isset($template->_tpldata['forumrow']))
				{
					$prime_links = new prime_links();
					$key = sizeof($template->_tpldata['forumrow']) - 1;
					if (empty($template->_tpldata['forumrow'][$key]['S_IS_LINK']) || $prime_links->is_link_local($template->_tpldata['forumrow'][$key]['U_VIEWFORUM']))
					{
						$external_target = '';
						$external_rel = '';
					}
					$template->alter_block_array('forumrow', array(
						'EXTERNAL_LINK_TARGET' => $external_target,
						'EXTERNAL_LINK_REL' => $external_rel,
						'EXTERNAL_LINK_ATTRIBUTES' => $external_target . $external_rel,
					), true, 'change');
				}
			}
		}
		return($text);
	}

	/**
	*/
	class prime_links
	{
		var $board_url;
		var $board_host;

		/**
		* Constructor
		*/
		function prime_links()
		{
			$this->board_url = generate_board_url(true);
			$this->board_url = utf8_case_fold_nfc($this->board_url);
			$this->board_host = $this->extract_host($this->board_url);
		}

		/**
		* Decodes all HTML entities. The html_entity_decode() function doesn't decode numerical entities,
		* and the htmlspecialchars_decode() function only decodes the most common form for entities.
		*/
		function decode_entities($text)
		{
			$text = html_entity_decode($text, ENT_QUOTES, 'ISO-8859-1');		 //UTF-8 does not work!
			$text = preg_replace('/&#(\d+);/me', 'chr($1)', $text);			 //decimal notation
			$text = preg_replace('/&#x([a-f0-9]+);/mei', 'chr(0x$1)', $text);	//hex notation
			return($text);
		}

		/**
		* Extract the host portion of a URL (the domain plus any subdomains)
		*/
		function extract_host($url)
		{
			// Remove everything before and including the double slashes
			if (($double_slash_pos = strpos($url, '//')) !== false)
			{
				$url = substr($url, $double_slash_pos + 2);
			}

			// Remove everything after the domain, including the slash
			if (($domain_end_pos = strpos($url, '/')) !== false)
			{
				$url = substr($url, 0, $domain_end_pos);
			}
			return $url;
		}

		/**
		* Determine if the URL contains a domain.
		* $domains	: list of domains (an array or a string separated by semicolons)
		* $remove	: list of subdomains to remove (or TRUE/FALSE to remove all/none)
		*/
		function match_domain($url, $domains)
		{
			$url = $this->extract_host($url);
			$url = utf8_case_fold_nfc($url);
			$url_split = array_reverse(explode('.', $url));

			$domain_list = is_string($domains) ? explode(';', $domains) : $domains;
			foreach ($domain_list as $domain)
			{
				$domain = $this->extract_host($domain);
				$domain = utf8_case_fold_nfc($domain);

				// Ignoring all subdomains, so check if our URL ends with domain
				if (substr($url, -strlen($domain)) == $domain)
				{
					return true;
				}
				$domain_split = array_reverse(explode('.', $domain));
				$match_count = 0;
				$match_list = array();
				foreach ($domain_split as $index => $segment)
				{
					if (isset($url_split[$index]) && strcmp($url_split[$index], $segment) === 0)
					{
						$match_count += 1;
						array_splice($match_list, 0, 0, $segment);
						continue;
					}
					break;
				}
				if ($match_count > 2 || ($match_count == 2 && strlen($match_list[0]) > 2)) // not the best check, but catches domains like 'co.jp'
				{
					return true;
				}
			}
			return false;
		}

		/**
		* Determines if a URL is local or external. If no valid-ish scheme is found,
		* assume a relative (thus internal) link that happens to contain a colon (:).
		*/
		function is_link_local($url)
		{
			$url = strtolower($url);

			// Compare the URLs
			if (!($is_local = $this->match_domain($url, $this->board_url)))
			{
				// If there is no scheme, then it's probably a relative, local link
				$scheme = substr($url, 0, strpos($url, '://'));
				//$is_local = !$scheme || ($scheme && !in_array($scheme, array('http', 'https', 'mailto', 'ftp', 'gopher')));
				$is_local = !$scheme || ($scheme && !preg_match('/^[a-z0-9.]{2,16}$/i', $scheme));
			}

			// Not local, now check forced local domains
			if (!$is_local && INTERNAL_LINK_DOMAINS)
			{
				$is_local = $this->match_domain($url, INTERNAL_LINK_DOMAINS);
			}
			return($is_local);
		}

		/**
		* Removes an attribute from an HTML tag.
		*/
		function remove_attribute($attr_name, $html_tag)
		{
			$html_tag = preg_replace('/\s+' . $attr_name . '="[^"]*"/i', '', $html_tag);
			return $html_tag;
		}

		/**
		* Insert an attribute into an HTML tag.
		*/
		function insert_attribute($attr_name, $new_attr, $html_tag, $overwrite = false)
		{
			$javascript	= (strpos($attr_name, 'on') === 0);	// onclick, onmouseup, onload, etc.
			$old_attr	= preg_replace('/^.*' . $attr_name . '="([^"]*)".*$/i', '$1', $html_tag);
			$is_attr	= !($old_attr == $html_tag);		// Does the attribute already exist?
			$old_attr	= ($is_attr) ? $old_attr : '';

			if ($javascript)
			{
				if ($is_attr && !$overwrite)
				{
					$old_attr = ($old_attr && ($last_char = substr(trim($old_attr), -1)) && $last_char != '}' && $last_char != ';') ? $old_attr . ';' : $old_attr; // Ensure we can add code after any existing code
					$new_attr = $old_attr . $new_attr;
				}
				$overwrite = true;
			}

			if ($overwrite && is_string($overwrite))
			{
				if (strpos(' ' . $overwrite . ' ', ' ' . $old_attr . ' ') !== false)
				{
					// Overwrite the specified value if it exists, otherwise just append the value.
					$new_attr = trim(str_replace(' '  . $overwrite . ' ', ' ' . $new_attr . ' ', ' '  . $old_attr . ' '));
				}
				else
				{
					$overwrite = false;
				}
			}
			if (!$overwrite)
			{
				 // Append the new one if it's not already there.
				$new_attr = strpos(' ' . $old_attr . ' ', ' ' . $new_attr . ' ') === false ? trim($old_attr . ' ' . $new_attr) : $old_attr;
			}

			$html_tag = $is_attr ? str_replace("$attr_name=\"$old_attr\"", "$attr_name=\"$new_attr\"", $html_tag) : str_replace('>', " $attr_name=\"$new_attr\">", $html_tag);
			return($html_tag);
		}

		/**
		* Modify links within a block of text.
		*/
		function modify_links($message = '')
		{
			// A quick check before we start using regular expressions
			if (strpos($message, '<a ') === false)
			{
				return($message);
			}
			global $user, $link_type_classes;

			preg_match_all('#(<a\s[^>]+?>)(.*?</a>)#i', $message, $matches, PREG_SET_ORDER);
			foreach ($matches as $links)
			{
				$link = $new_link = $links[1];
				$href = preg_replace('/^.*href="([^"]*)".*$/i', '$1', $link);
				if ($href == $link) //no link was found
				{
					continue;
				}
				$href	= $this->decode_entities($href);
				$scheme	= substr($href, 0, strpos($href, ':'));
				if ($scheme)
				{
					$scheme = strtolower($scheme);
					if ($scheme != 'http' && $scheme != 'https') // Only classify links for these schemes (or no scheme)
					{
						continue;
					}
				}
				$external_prefix = EXTERNAL_LINK_PREFIX;

				if (SKIP_LINK_TYPES && preg_match('/\.(?:' . SKIP_LINK_TYPES . ')(?:[#?]|$)/', $href))
				{
					continue;
				}

				$is_local = null;
				$is_local = (INTERNAL_LINK_TYPES && preg_match('/\.(?:' . INTERNAL_LINK_TYPES . ')(?:[#?]|$)/', $href)) ? true : $is_local;
				$is_local = (EXTERNAL_LINK_TYPES && preg_match('/\.(?:' . EXTERNAL_LINK_TYPES . ')(?:[#?]|$)/', $href)) ? false : $is_local;
				if ($is_local === null)
				{
					if (FORBIDDEN_DOMAINS && $this->match_domain($href, FORBIDDEN_DOMAINS))
					{
						$searches[]		= $link;
						$replacements[]	= $this->insert_attribute('href', FORBIDDEN_NEW_URL, $new_link, true);
						continue;
					}
					$is_local = $this->is_link_local($href);
				}
				$new_class	= $is_local ? INTERNAL_LINK_CLASS : EXTERNAL_LINK_CLASS;
				$new_target	= $is_local ? INTERNAL_LINK_TARGET : EXTERNAL_LINK_TARGET;
				$new_rel	= $is_local ? INTERNAL_LINK_REL : EXTERNAL_LINK_REL;

				// Check if this link needs a special class based on the type of file to which it points.
				foreach ($link_type_classes as $extensions => $class)
				{
					if ($class && $extensions && preg_match('/\.(?:' . $extensions . ')(?:[#?]|$)/', $href))
					{
						$new_class .= ' ' . $class;
						break;
					}
				}
				if ($new_class)
				{
					$new_link = $this->insert_attribute('class', $new_class, $new_link, 'postlink');
				}
				if ($new_rel)
				{
					$new_link = $this->insert_attribute('rel', $new_rel, $new_link);
				}
				if ($new_target)
				{
					if (USE_TARGET_ATTRIBUTE === true)
					{
						$new_link = $this->insert_attribute('target', $new_target, $new_link, true);
					}
					else
					{
						$new_link = $this->insert_attribute('onclick', "this.target='$new_target';", $new_link);
					}
				}
				// Remove the link?
				if ($new_target === false || (HIDE_LINKS_FROM_GUESTS && !$is_local && !$user->data['is_registered']))
				{
					$new_text = is_string(HIDE_LINKS_FROM_GUESTS) ? HIDE_LINKS_FROM_GUESTS : substr($links[2], 0, -4);
					$new_link = '<span class="link_removed">' . $new_text . '</span>';
					$link = $links[0];
				}
				else if (!$is_local && $external_prefix)
				{
					$external_prefix = (SKIP_PREFIX_TYPES && preg_match('/\.(?:' . SKIP_PREFIX_TYPES . ')(?:[#?]|$)/', $href)) ? '' : $external_prefix;
					$new_link = str_replace('href="', 'href="' . $external_prefix, $new_link);
				}
				$searches[]		= $link;
				$replacements[]	= $new_link;
			}
			if (isset($searches) && isset($replacements))
			{
				$message = str_replace($searches, $replacements, $message);
			}
			return($message);
		}
	}
}
?>