<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}
/**
* This file is responsible for
* - unpacking the archives inside of the component directories
* - running SQL updates
* - finishing the installation
*
* @version $Id: install.php 1388 2008-04-23 17:58:17Z soeren_nb $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

function installvirtuemart( $install_type, $install_sample_data=false ){
	global $database, $mosConfig_absolute_path, $mosConfig_mailfrom, $VM_LANG, $mosConfig_dirperms, $mosConfig_live_site;
	@ini_set( 'memory_limit', '32M' );
	if( empty($mosConfig_mailfrom)) $mosConfig_mailfrom = "demo_order@virtuemart.net";
	
	$messages = array();
	
	$frontend_dir = $mosConfig_absolute_path."/components/com_virtuemart";
	$frontend_file = $frontend_dir."/frontend_files.tar.gz";
	$admin_dir = $mosConfig_absolute_path."/administrator/components/com_virtuemart";
	$admin_file = $admin_dir."/admin_files.tar.gz";
	
	// Check if the Archives are there
	if( file_exists( $frontend_file ) && file_exists( $admin_file ) ) {
		
		echo '<div align="left" style="max-height: 200px;overflow:auto;"><h2>Update Log</h2>';
		
		/** UNPACK THE ARCHIVES **/
		require_once( $mosConfig_absolute_path."/administrator/components/com_virtuemart/Tar.php" );
		
		// Workaround for Window$
		if(strstr($mosConfig_absolute_path , ":" )) {
		  $path_begin = substr( $mosConfig_absolute_path, strpos( $mosConfig_absolute_path , ":" )+1, strlen($mosConfig_absolute_path));
		  $mosConfig_absolute_path = str_replace( "//", "/", $path_begin );
		}
		// Now let's re-declare the paths for Window$
		$frontend_dir = $mosConfig_absolute_path."/components/com_virtuemart";
		$frontend_file = $frontend_dir."/frontend_files.tar.gz";
		$admin_dir = $mosConfig_absolute_path."/administrator/components/com_virtuemart";
		$admin_file = $admin_dir."/admin_files.tar.gz";
		
		$frontend = $backend = false;
		
		$frontend_archive = new Archive_Tar( $frontend_file, "gz" );
		$admin_archive = new Archive_Tar( $admin_file, "gz" );

/* Don't show errors for either version of Joomla right now
 * The install displays errors even on a successful installation
 
		// Don't show errors on Joomla! 1.5 (errors appear even on a successful extraction)
//		if( !class_exists( 'JConfig' ) ) {
//			$frontend_archive->setErrorHandling(PEAR_ERROR_PRINT);
//			$admin_archive->setErrorHandling(PEAR_ERROR_PRINT);
//		}
 * 
 */
		// Extract the files
		$frontend_result = $frontend_archive->extract( $frontend_dir.'/' );
		$backend_result = $admin_archive->extract( $admin_dir .'/');

	 	// Just say everything went well
		$frontend_result = $backend_result = true;
		
		// Assume the extraction was successfull for Joomla! 1.5
		if( class_exists( 'JConfig' ) ) {
			$frontend_result = $backend_result = true;
		}
	
		if( $frontend_result ) {
			$frontend = true;
			$messages[] = "Frontend files successfully extracted.";
			if( @unlink( $frontend_file ) ) {
				$messages[] = "Frontend archive file successfully deleted.";
			}
		}

		if( $backend_result ) {
			$backend = true;
			$messages[] = "Backend files successfully extracted.";
			if( @unlink( $admin_file ) ) {
					$messages[] = "Backend archive file successfully deleted.";
			}
		}
		if( !$frontend || !$backend ) {
			echo "<span class=\"message\">Something went wrong with unpacking the archive files</span>";
		}
	/** END UNPACKING ARCHIVES */
	}
	// Check if the directories are there
	elseif( !is_dir( $frontend_dir."/js" ) || !is_dir( $admin_dir."/classes" ) ) {	
		die( "<span class=\"message\"><strong>ERROR!<br/>
		a)</strong> No Archive Files and <br/>
		<strong>b)</strong> no directory structure for VirtueMart.<br/><br/>
		What's wrong? Either YOU unpack all the files and upload them or I do that (I can do that when Safe Mode is OFF).
		</span>" );
	}
	if( !file_exists( $admin_dir .'/classes/ps_database.php' ) || !file_exists( $admin_dir.'/sql/sql.virtuemart.php' ) ) {
		echo '<h2>Critical Error:</h2>
		<p class="message">Some Core Files for VirtueMart are missing, but they are required for installing and running VirtueMart. If you see this message
		it\'s recommended to attempt the <a href="http://virtuemart.net/documentation/User_Manual/Manual_Installation.html" target="_blank">Manual Installation</a> of VirtueMart!</p>
		<p><a href="index2.php">Click here to return to Joomla!</a></p>';
		exit;
	}
	require( $admin_dir .'/classes/ps_database.php' );
	$db = new ps_DB;
	defined( 'VM_TABLEPREFIX' ) or define( 'VM_TABLEPREFIX', 'vm' );
	
	/**
	* Query SECTION 
	*
	*/
	// UPDATE FROM mambo-phpShop 1.1
	if ($install_type=='update11') {
                include_once( $admin_dir."/sql/sql.update.from.mambo-phpshop-1.1.php" );
                include_once( $admin_dir."/sql/sql.update.from.mambo-phpshop-1.2-RC2.to.1.2-stable-pl3.php" );
                include_once( $admin_dir."/sql/sql.update.mambo-phpshop-1.2-stable-pl3.to.virtuemart.php" );
                include_once( $admin_dir."/sql/sql.update.virtuemart-1.0.x.to.virtuemart-1.0.3.php" );
                include_once( $admin_dir."/sql/sql.update.VM-1.0.x_to_VM-1.1.0.php" );
        }
        //UDATE FROM mambo-phpShop 1..2 RC2
        elseif ($install_type=='update12') {  
  
                include_once( $admin_dir."/sql/sql.update.from.mambo-phpshop-1.2-RC2.to.1.2-stable-pl3.php" );
                include_once( $admin_dir."/sql/sql.update.mambo-phpshop-1.2-stable-pl3.to.virtuemart.php" );
                include_once( $admin_dir."/sql/sql.update.virtuemart-1.0.x.to.virtuemart-1.0.3.php" );
                include_once( $admin_dir."/sql/sql.update.VM-1.0.x_to_VM-1.1.0.php" );
        }
        //UDATE FROM mambo-phpShop 1..2 stable-pl3
        elseif ($install_type=='update12pl3') {  
  
                include_once( $admin_dir."/sql/sql.update.mambo-phpshop-1.2-stable-pl3.to.virtuemart.php" );
                include_once( $admin_dir."/sql/sql.update.virtuemart-1.0.x.to.virtuemart-1.0.3.php" );
                include_once( $admin_dir."/sql/sql.update.VM-1.0.x_to_VM-1.1.0.php" );
        }
        elseif( $install_type == 'updatevm10x' ) {
                include_once( $admin_dir."/sql/sql.update.virtuemart-1.0.x.to.virtuemart-1.0.3.php" );
                include_once( $admin_dir."/sql/sql.update.virtuemart-1.0.3.to.virtuemart-1.0.5.php" );
                include_once( $admin_dir."/sql/sql.update.VM-1.0.x_to_VM-1.1.0.php" );
        }

        // New Installation : Create all tables
	elseif ($install_type=='newinstall') {	
		/* Rename the cfg-dist file to cfg */
		$dist_file = dirname( __FILE__ ) . "/virtuemart.cfg-dist.php";
		$cfg_file = dirname( __FILE__ ) . "/virtuemart.cfg.php";
	  
		if (!@rename( $dist_file, $cfg_file )) {
			$messages[] = "Fatal Error: Something went wrong while RENAMING the VirtueMart CONFIGURATION FILE!";
			$messages[] =  "Please rename $dist_file to $cfg_file manually!!";
		}
		require_once( $admin_dir.'/sql/sql.virtuemart.php' );
	}
	
	// SAMPLE DATA! 
	if ($install_sample_data) {
		include_once( $admin_dir.'/sql/sql.sampledata.php' );
	}
	elseif ($install_type=='newinstall') {
		/*** Delete the Sample Product - Images ***/
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/1aa8846d3cfe3504b2ccaf7c23bb748f.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/1b0c96d67abdbea648cd0ea96fd6abcb.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/1ff5f2527907ca86103288e1b7cc3446.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/7a36a05526e93964a086f2ddf17fc609.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/8cb8d644ef299639b7eab25829d13dbc.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/8d886c5855770cc01a3b8a2db57f6600.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/9a4448bb13e2f7699613b2cfd7cd51ad.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/520efefd6d7977f91b16fac1149c7438.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/8147a3a9666aec0296525dbd81f9705e.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/8716aefc3b0dce8870360604e6eb8744.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/480655b410d98a5cc3bef3927e786866.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/578563851019e01264a9b40dcf1c4ab6.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/a04395a8aefacd9c1659ebca4dbfd4ba.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/b4a748303d0d996b29d5a1e1d1112537.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/c3a5bf074da14f30c849d13a2dd87d2c.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/c70a3f47baf9a4020aeeee919eb3fda4.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/cca3cd5db813ee6badf6a3598832f2fc.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/dccb8223891a17d752bfc1477d320da9.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/e614ba08c3ee0c2adc62fd9e5b9440eb.jpg" );
		@unlink( $mosConfig_absolute_path."/components/com_virtuemart/shop_image/product/ffd5d5ace2840232c8c32de59553cd8d.jpg" );
	}
	
	/**
	* mambo-phpShop => VirtueMart
	*
	* Section to copy your images,
	* and convert important entries
	* and place redirection files to keep your links
	* like &option=com_phpshop&product_id=1&Itemid=45 alive.
	*/
	if( $install_type != 'newinstall' && is_dir( $mosConfig_absolute_path.'/components/com_phpshop' ) ) {
	
		require_once( $mosConfig_absolute_path.'/administrator/components/com_virtuemart/install.copy.php' );
		
		// COPY all Images from /componentes/com_phpshop/shop_image/*
		// TO /components/com_virtuemart/shop_image/*
		$fromDir = $mosConfig_absolute_path.'/components/com_phpshop/shop_image';
		$toDir = $mosConfig_absolute_path.'/components/com_virtuemart/shop_image';
		$perms = 0777;
		umask( 0022 );
		
		copydirr( $fromDir, $toDir, $perms, true );
		
		// COPY templates from /administrator/components/com_phpshop/templates
		// TO /administrator/components/com_virtuemart/templates
		$fromDir = $mosConfig_absolute_path.'/administrator/components/com_phpshop/html/templates';
		$toDir = $mosConfig_absolute_path.'/administrator/components/com_virtuemart/html/templates';
		copydirr( $fromDir, $toDir, $perms, true );
		
		// COPY&RENAME the configuration file phpshop.cfg.php 
		// TO virtuemart.cfg.php AND replace 'com_phpshop' by 'com_virtuemart'
		$fromDir = $mosConfig_absolute_path.'/administrator/components/com_phpshop';
		$toDir = $mosConfig_absolute_path.'/administrator/components/com_virtuemart';
		
		$config_contents = str_replace( 'com_phpshop', 'com_virtuemart', file_get_contents( $fromDir.'/phpshop.cfg.php' ) );
		$config_contents .= "<?php
@define('VM_TABLEPREFIX', 'vm' );
define('VM_PRICE_SHOW_PACKAGING_PRICELABEL', '1' );
define('VM_PRICE_SHOW_INCLUDINGTAX', '1' );
define('VM_PRICE_ACCESS_LEVEL', 'Public Frontend' );
define('VM_SILENT_REGISTRATION', '1');
?>";

		file_put_contents( $toDir.'/virtuemart.cfg.php', $config_contents );			
		
		// BACKUP 
		// phpshop.php TO phpshop~.php.
		// AND phpshop_parser.php TO phpshop_parser~.php.
		$fromDir = $mosConfig_absolute_path.'/components/com_phpshop';
		if( !is_writable( $fromDir.'/phpshop.php' ))
			@chmod( $fromDir.'/phpshop.php',  '0777' );
		if( !is_writable( $fromDir.'/phpshop_parser.php' ))
			@chmod( $fromDir.'/phpshop_parser.php',  '0777' );
		
		if( !rename( $fromDir.'/phpshop.php', $fromDir.'/phpshop~.php' )) {
			;
		}		
		// CREATE A NEW FILE 'phpshop.php', to handle permanent Redirect to 
		// FROM index.php?option=com_phpshop&...
		// TO index.php?option=com_virtuemart&...
		$contents = "<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}

global \$mosConfig_absolute_path;

\$newURL = str_replace( 'com_phpshop', 'com_virtuemart', \$_SERVER['QUERY_STRING'] );

header( 'HTTP/1.1 301 Moved Permanently' );
header( 'Location: '.\$mosConfig_live_site.\"/\".basename( \$_SERVER['PHP_SELF'] ).'?'.\$newURL );
exit();

?>
";
		if( file_put_contents( $fromDir.'/phpshop.php', $contents ) )
			$messages[] = "Established redirection from old phpshop links to new virtuemart links";
		else
			$messages[] = "Notice: Couldn't established redirection from old phpshop links to new virtuemart links.";
		
		
		rename( $fromDir.'/phpshop_parser.php', $fromDir.'/phpshop_parser~.php' );
		$contents = "<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}
global \$mosConfig_absolute_path;

include( \$mosConfig_absolute_path.'/components/com_virtuemart/virtuemart_parser.php' );

?>
";
		if( file_put_contents( $fromDir.'/phpshop_parser.php', $contents ) )
			$messages[] = "Established connection between old phpshop_parser and new virtuemart_parser";
		else
			$messages[] = "Notice: Couldn't establish connection between old phpshop_parser and new virtuemart_parser";
		
		// DELETE the Admin-Link to "mambo-phpShop" in the Backend.
		// Note: You can delete the directories 
		// /componentes/com_phpshop
		// AND /administrator/componentes/com_phpshop
		// with the mamboXplorer when no longer needed!
		$database->setQuery( 'DELETE FROM #__components WHERE link LIKE \'%option=com_phpshop%\'' );
		$database->query();
	
	}
	//Check if the VirtueMart component has an Entry in the Administration => Components Menu
	$database->setQuery( "SELECT id FROM `#__components` WHERE `link` LIKE '%option=com_virtuemart%'" );
	$id = $database->loadResult();
	if( empty( $id )) {
		$database->setQuery( "INSERT INTO `#__components` ( `id` , `name` , `link` , `menuid` , `parent` , `admin_menu_link` , `admin_menu_alt` , `option` , `ordering` , `admin_menu_img` , `iscore` )
							VALUES ('', 'VirtueMart', 'option=com_virtuemart', '0', '0', 'option=com_virtuemart', 'VirtueMart', 'com_virtuemart', '0', 'js/ThemeOffice/component.png', '0');" ); 
		$database->query();
	}
	// Finally insert the version number into the database
	include_once( $mosConfig_absolute_path.'/administrator/components/com_virtuemart/version.php' );
	global $VMVERSION;
	$VMVERSION= new vmVersion();
	$database->setQuery( 'SELECT id FROM `#__components` WHERE name = \'virtuemart_version\'' );
	$old_version =  $database->loadResult();
	if( $old_version ) {
		$database->setQuery( 'UPDATE `#__components` SET params = \'RELEASE='.$VMVERSION->RELEASE.'
DEV_STATUS='.$VMVERSION->DEV_STATUS.'\' WHERE name = \'virtuemart_version\'' );
		$database->query();
	}
	else {
		$database->setQuery( 'INSERT INTO `#__components` (name, parent, params ) VALUES ( \'virtuemart_version\', 9999, \'RELEASE='.$VMVERSION->RELEASE.'
DEV_STATUS='.$VMVERSION->DEV_STATUS.'\')' );
		$database->query();
	}
	if( !empty( $messages )) {
		echo '<ul style="list-style-type: disc;list-style-position: inside;">';
		foreach( $messages as $message ) {
			echo "<li>$message</li>";
		}
		echo '</ul>';
	}
	echo '</div>';
}
?>