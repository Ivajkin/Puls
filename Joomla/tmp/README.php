<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}
/**
* @version $Id: README.php 2575 2010-10-10 14:34:26Z zanardi $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2009 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*
* http://virtuemart.net
*/
?>
<pre>
****************
VirtueMart 1.1
****************
Complete Package for Joomla! Joomla! 1.5.x

Copyright (C) 2004-2010 soeren - All rights reserved.
License: http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
VirtueMart is free software. This version may have been modified pursuant
to the GNU General Public License, and as distributed it includes or
is derivative of works licensed under the GNU General Public License or
other free or open source software licenses.

Community Home: http://virtuemart.net

****************

##########################
Package Contents:
##########################

 * 1 component (com_virtuemart_1.1.x.j15.zip for Joomla! 1.5)
   INSTALLATION REQUIRED!
   
 * 1 main module (mod_virtuemart_1.1.x.j15.zip for Joomla! 1.5)
   INSTALLATION REQUIRED!
   
 * 12 additional modules
 
 * 2 plugins for Jooma! 1.5.x
   - 1 search plugin for integration into the site search (vmxsearch.plugin), 
   - 1 content mambot for displaying product details in content items (vmproductproductsnapshots)
   

##########################
   ABOUT
##########################
VirtueMart is an Open Source Online-Shop / Shopping Cart Web-Application.
It's an extension for the Content Management System "Joomla!" and cannot be used without it. It installs fairly easy using the automatic installers. 
It's intended for use in small / mid-sized Online businesses / Online-Shops. So every user who wants to build up a Online Store can use this component for selling something to customers.

This package is for new installations. 
You just need a working Joomla Installation. 
You can get your copy of Joomla from http://joomla.org

This package contains some code from the original 0.8.0 Edikon Corp. phpShop distribution available at www.phpshop.org

This package was successfully tested on 
- Joomla 1.5.20

 -- IMPORTANT --
Please note that module and component SHOULD be used together! 
The thing is that you can only access all areas of the component via the VirtueMart Main Module links.

You can surely create a new Menu Item linking to VirtueMart, but you must also publish the VirtueMart module.

##########################
   INSTALLATION
##########################
The installation is really easy thanks to the automatic installer!
You don't need to unpack any of the archives in this complete package!

1. If you have unpacked this archive (VirtueMart_x.x_COMPLETE_PACKAGE.zip), 
	you'll see a lot of other archives.
	- com_virtuemart_x.xxx.zip, 
	- some files beginning with mod_*.zip 
	- other packages (these are the plugins).
    
2. Login to the Administration Backend (/administrator on your site).
        
	Go to "Extensions" => "Install / Uninstall"
        
	You can see an upload form now. Select the file 
	- com_virtuemart_x.xx.zip 
	and click 'Upload and install'
        
	If everything is ok, you should see a "Welcome ..." screen.
	Choose your way of installation to finish the component installation.
	
	Please note that the package is slightly bigger than 2MB, that is the default upload limit for some PHP setup. If you cannot install the component, check if your server does not support more than 2MB uploads.
        
3. Now we have to install the main module, which will help you to browse your categories and products.
		
	Go to "Extensions" => "Install / Uninstall" 	
        
	Select the file 
	- mod_virtuemart_x.xx.zip
	and click 'Upload and install'.
    
4. The module is installed, but it still is not published! To publish that module on your site, you must go to the list of 
	your all modules.
	
	Go to "Extensions" => "Module manager"
	
	You should somewhere see a module entry for "Virtuemart Module" with "mod_virtuemart" at the end of that row.
	If necessary, browse to the next page of the module list.
	If you've found the module, please select it's checkbox and click 	on "Edit" in the toolbar. 
	Make your settings and don't forget to select "Published? - Yes".
       
	Now Save - and: done.   
    
	Note: As long as the VirtueMart main module is NOT published, VirtueMart can't be used properly.

	IF successful, the installer will have created the following directory structure:
        
	/components/com_virtuemart
	- contains code for non-administrative surfing and ordering
         
	/components/com_virtuemart/js
	- contains JavaScript Files
	
    /components/com_virtuemart/shop_image/product
    - contains the product images
         
	/components/com_virtuemart/shop_image/ps_image
	- contains general administration/shop images

	/components/com_virtuemart/shop_image/vendor
	- contains the vendor images

	/components/com_virtuemart/shop_image/category
	- contains the category images
		 		 
	/components/com_virtuemart/themes
	- contains HTML/CSS themes for your shop

	/administrator/components/com_virtuemart
	- contains config files and the main virtuemart-parser
         
	/administrator/components/com_virtuemart/classes
	- contains all the class files
         
	/administrator/components/com_virtuemart/html
	- contains all pages accessible for the shop
			
	/administrator/components/com_virtuemart/languages
	- contains the language files
         
   
##########################
   UNINSTALL
##########################

1. Go to "Extensions" => "Install / Uninstall" 

2. Choose "Components"

3. Select 'VirtueMart' and click on 'Delete'.

4. Choose "Modules"
        
2. Select 'mod_virtuemart' and click on 'Delete'.

If you installed other modules or plugins, just repeat the same procedure for each one of them.

#
############################
#

For updates / changes / hints please read the ChangeLog!
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


Developers, Documentation Writers, Helpers and Coders are welcome to help us. 
Please contact me: soeren|at|virtuemart.net

The VirtueMart Software alway needs helpers to evolve.
</pre>
