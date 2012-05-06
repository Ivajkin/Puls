<?php
/**
* This file contains the lanuages handler class
*
* @version $Id: language.class.php 1475 2008-07-16 17:35:35Z soeren_nb $
* @package VirtueMart
* @copyright Copyright (C) 2007 soeren, thepisu - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

function utf8_to_cp1251($s)	{
	for ($c=0;$c<strlen($s);$c++)
	{
	   $i=ord($s[$c]);
	   if ($i<=127) $out.=$s[$c];
		   if ($byte2){
			   $new_c2=($c1&3)*64+($i&63);
			   $new_c1=($c1>>2)&5;
			   $new_i=$new_c1*256+$new_c2;
		   if ($new_i==1025){
			   $out_i=168;
		   } else {
			   if ($new_i==1105){
				   $out_i=184;
			   } else {
				   $out_i=$new_i-848;
			   }
		   }
		   $out.=chr($out_i);
		   $byte2=false;
		   }
	   if (($i>>5)==6) {
		   $c1=$i;
		   $byte2=true;
	   }
	}
	return $out;
}

/**
* Abstract lanuages/translation handler class
*/
class vmAbstractLanguage {
/** @var boolean If true, highlights string not found */
	var $_debug = false;
	var $modules = array();
	var $key_varname = '';
	
	function vmAbstractLanguage() {
		$this->setDebug();
	}

	/**
	* Translator function
	* @param string Name of the Class Variable
	* @param boolean Encode String to HTML entities?
	* @return string The value of $var (as an HTML Entitiy-encoded string if $htmlentities)
	*/
	function _( $var, $htmlentities=false ) {
		global $modulename;
		$module = $modulename;
	    $key = strtoupper( $var );

		// if language module not yet loaded, load now
		if (!isset($this->modules[$module])) {
			$this->load($module);
		}
		$text = false;
		
	    $module = $this->exists($key);
		if( $module === false && $key[0] == '_' ) {
			$key = substr( $key, 1 );
		    $module = $this->exists($key);
		}
		
		if ($module!==false) {
			$text = $this->modules[$module][$key];
			if( $htmlentities ) {
				$text = htmlentities( $text, ENT_QUOTES, $this->getCharset($module));
				// some symbols are not converted correctly... doing manually
				$text = str_replace(chr(128),'&euro;',$text);
				// enable the use of HTML tags in language file... is this really good?
				$text = str_replace('&lt;','<',$text);
				$text = str_replace('&gt;','>',$text);
				return $text;
			} else {
				$text = $this->convert($text,$module);
				return stripslashes( $text );
			}
		} elseif( $this->_debug ) {
			$GLOBALS['vmLogger']->debug( "$var is missing in language file.");
		} 
		return '';
		
	} 
	/**
	* Merges the class vars of another class --> TO BE REMOVED... ?
	* @param string The name of the class to merge
	* @return boolean True if successful, false is failed
	*/
	function merge( $classname ) {
	    if (class_exists( $classname )) {
	        foreach (get_class_vars( $classname ) as $k=>$v) {
	            if (is_string( $v )) {
	                if ($k[0] != '_') {
	                    $this->$k = $v;
					}
				}
			}
		} else {
		    return false;
		}
	}
	/**
	* Set the debug mode
	*/
	function setDebug() {
		if( function_exists('vmshoulddebug')) {
			$this->_debug = vmShouldDebug() || $GLOBALS['mosConfig_debug'] == '1';
		} else {
			$this->_debug = DEBUG || $GLOBALS['mosConfig_debug'] == '1';
		}
	}
	/**
	* Set the charset of a language module (normally specified in language file)
	* @param string The name of the module
	* @param string Forced charset (optional)
	* @return none
	*/
	function setCharset($module,$charset='') {
		if( !empty( $charset )) {
			$this->modules[$module]['CHARSET'] = $charset;
		} else {
			$this->modules[$module]['CHARSET'] = vmGetCharset();
		}
	}
	/**
	* Get the charset of a languge module 
	* @param string The name of the module
	* @return string The charset code
	*/
	function getCharset($module='common') {
		return $this->modules[$module]['CHARSET'];
	}
	/**
	* Convert a string, using the convert function set for this module
	* @param string The string to be converted
	* @param string The name of the module
	* @return string The converted string
	*/
	function convert($string,$module='common') {
		$func = $this->modules[$module]['CONVERT_FUNC'];
		if( !function_exists( $func )) {
			$func = 'strval';
		}
		return $func($string);
	}
	/**
	 * This safely converts an iso-8859 string into an utf-8 encoded
	 * string. It does not convert when the string is already utf-8 encoded
	 *
	 * @param string $text iso-8859 encoded text
	 * @param string $charset This is a k.o.-Argument. If it is NOT equal to 'utf-8', no conversion will take place
	 * @return string
	 */
	function safe_utf8_encode( $text, $charset ) {
		if( strtolower($charset) == 'utf-8' &&  !vmAbstractLanguage::seems_utf8( $text )) {
			// safely decode and reencode the string
			$text = utf8_encode($text);
		}
		// This converts the currency symbol from HTML entity to the utf-8 symbol
		// example:  &euro; => €
		$text = vmHtmlEntityDecode( $text, null, vmGetCharset() );
		
		return $text;
	}
	/**
	 * a simple function that can help, if you want to know 
	 * if a string could be UTF-8 or not
	 * @author bmorel at ssi dot fr
	 * @param unknown_type $Str
	 * @return boolean
	 */
	function seems_utf8($Str) {
		for ($i=0; $i<strlen($Str); $i++) {
			if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
			elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
			elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
			elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
			elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
			elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
			else return false; # Does not match any model
			for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
				if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80)) {
					return false;
				}
			}
		}
		return true;
	}
	/**
	* Check if a language variable exists in current language file
	* @param string Name of the Class Variable
	* @return mixed the name of the module if exists, false is not exists
	*/
	function exists($var,$module=false) {
		global $modulename;
		if (!$module) $module=$modulename;
	    $key = strtoupper( $var );
	    if (isset($this->modules[$module][$key])) {
			return $module;
		} elseif (isset($this->modules['common'][$key])) {
			return 'common';
		} else {
			foreach ( $this->modules as $lang_module ) {
				if( isset( $lang_module[$key])) {
					return $lang_module[$this->key_varname];
				}
			}
			return false;
		}
	}
	
	/**
	* Load the language file of a specified module
	* @param string The language module to load
	* @return boolean True if file exists, false is non exists
	*/
	function load($module) {
		global $mosConfig_lang;
		if( empty( $module )) return false;
		$module = basename( $module );
		if (file_exists( ADMINPATH. 'languages/'.$module.'/'.strtolower($mosConfig_lang).'.php' )) {
			require_once( ADMINPATH. 'languages/'.$module.'/'.strtolower($mosConfig_lang).'.php' );
			return true;
		} else if (file_exists( ADMINPATH. 'languages/'.$module.'/english.php' )) {
			require_once( ADMINPATH. 'languages/'.$module.'/english.php' );
			return true;
		} else {
			// setting the module to false, to know that has already tried to load this
			$this->modules[$module] = false;
			return false;
		}
	}
	
	/**
	* Initialize the strings array of a language module
	* @param string The language module to init
	* @param array The array of language strings
	* @return none
	*/
	function initModule($module,&$vars) {
		$this->modules[$module] =& $vars;
		$this->modules[$module][$this->key_varname] = $module;
		$this->modules[$module]['CONVERT_FUNC'] = 'strval';
		if( empty( $this->modules[$module]['CHARSET'] )) $this->setCharset($module);
		// get global charset setting
		$iso = explode( '=', @constant('_ISO') );
		// If $iso[1] is NOT empty, it is Mambo or Joomla! 1.0.x - otherwise Joomla! >= 1.5
		$charset = !empty( $iso[1] ) ? $iso[1] : 'utf-8';
		// Prepare the convert function if necessary
		if( strtolower($charset)=='utf-8' && stristr($this->modules[$module]['CHARSET'], 'iso-8859-1' ) ) {
			$this->modules[$module]['CONVERT_FUNC'] = 'utf8_encode';
		} elseif( stristr($charset, 'iso-8859-1') && strtolower($this->modules[$module]['CHARSET'])=='utf-8' ) {
			$this->modules[$module]['CONVERT_FUNC'] = 'utf8_decode';
		} elseif( strpos($charset, '1251') && strtolower($this->modules[$module]['CHARSET'])=='utf-8' ) {
			$this->modules[$module]['CONVERT_FUNC'] = 'utf8_to_cp1251';
		}
	}
}
class mosAbstractLanguage extends vmAbstractLanguage { }
class vmLanguage extends vmAbstractLanguage { }
class phpShopLanguage extends vmLanguage { }

?>
