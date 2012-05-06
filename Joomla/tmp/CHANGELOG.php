<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: CHANGELOG.php 3240 2011-05-14 09:03:03Z zanardi $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2011 VirtueMart Development Team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
?>
<pre>
This is a non-exhaustive (but still near complete) changelog for
VirtueMart, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and code fixes.

Legend:

#	Bug Fix
+	Addition
^	Change
-	Removed
!	Note

--------------------------------------------------------------------------------------------------------------

VirtueMart 1.1.x
*************************************

--- VirtueMart 1.1.8 released (Rev. 3520 2011-06-19) ---

18.06.2011 zanardi
# Bug #322 Miscalculation in order e-mail receipt
# Bug #320 Bug in ps_checkout._tax_based_on_vendor_address

07.06.2011 zanardi
# Bug #327 Error Found in mootools-release-1.1.1.js
# Bug #321 Coupon problem - multiple currencies
^ User-pages

28.05.2011 zanardi
# Bug #367 Use of "mysql_real_escape_string" breaks with mysqli
# Bug #364 Disallow quotes in attributes properties
# Bug #360 Ajax calls recorded as last page
# Bug #310 bug in shop.browse.php empty category

14.05.2011 zanardi + poy
# Bug #366: Security issue in checkout.2Checkout_result.php
# Bug #364 Disallow quotes in attributes properties
# Bug #363 Membergroup to show prices to results to Undefined variable

30.04.2011 zanardi
# Bug #318 HTML error in file : product_type.tpl.php
# Bug #315 Zone Shipping Fatal Error

03.04.2011 aravot
# Bug #326 No download email is send for free download-able product

02.04.2011 zanardi + poy
# Bug #350 Security issue in notify.php
# Bug #346 Version is 1.1.8 stable but extension manager is reported 1.1.7
# Bug #328 CVV still listed on PayPal order list
# Bug #323 Customer numbers not created in administration, when customer is created by site administrator
# Bug #312 No search results in user list when not searching from page one
# Bug #309 PayPal valid IP list is empty

--- VirtueMart 1.1.8 released (Rev. 2861 2011-03-19) ---

15.03.2011 zanardi
# Bug #307 Security issue in notify.php
# Bug #281 Quotes in attributes

12.03.2011 zanardi
# Bug #239 "Deprecated" and "Notice" errors in PayPal API
# Bug #233 Customer Reviews breaks on 150 character and has no line break
# Bug #232 Display Child Description ALWAYS checked

10.03.2011 soeren
# fixed a problem with the advanced search not returning results

01.03.2011 bob
# Task #237 Review '_More' missing language string
^ Task #227 Replace mosHTML with vmCommonHTML

18.02.2011 zanardi
# Task #199 Checkout shipping radio button selected is not the correct one

13.02.2011 zanardi
# Task #189 Duplicate resized image (thanks to Gruz)
# Task #188 Shipping value is not changed according to currency
# Task #180 Bug in discount price display for multi-priced products 
# Task #110 Can't choose vmsearch plugin in search results (thanks to Tomasz)

--- patch VirtueMart 1.1.7a released (2011-02-18) ---

# Task #229 SQL Injection fix in ps_module.php

--- VirtueMart 1.1.7 released (Rev. 2688 2011-02-02) ---

04.02.2011
# Task #212 (#202): product snapshot button always "Add to cart".

01.02.2011
# Task #213 Blind SQL injection in parameter "search_category"

30.01.2011 zanardi
# Task #210 Paypal API rerouting to http://xxxx:443
# Task #209 shop.index.php paypal logo HTML error
# Task #206 SQL update broken
# Task #200 PayPal API wrong payment method type

--- VirtueMart 1.1.6 released (Rev. 2660 2010-11-21) ---

16.11.2010 zanardi
# Task #134 When creating text or textarea field type in Manage User Fields, default value is 0
# Task #142 (again) Fixed other notices in backend order detail
# Task #154 Bug with some SEF router
# Task #198 "Deprecated" warning in PHP 5.3 when trying to add additional images

30.10.2010 zanardi
# Task #184 Bug in get_price for child products
# Task #185 Notify script not installed for ipayment and paysbuy
# Task #186 In Product Type - Parameter Unit value is not shown on frontpage. 
# Task #187 Bugs in the image_tag() function

25.10.2010 soeren
# Task #158 PayPal API cart empty if shipping address and method are not enabled
# fixed deprecated function message regarding usage of "ereg_replace" in ps_checkout.php

22.10.2010 zanardi
^ Task #155 Account maintenance not redirected to https in mod_virtuemart_login
# Task #160 Breadcrumb issues with direct menu links to product or category (again)
# Task #165 Coupon value is not changed according to currency

12.10.2010 zanardi
^ Task #160 Breadcrumb issues with direct menu links to product or category

10.10.2010 zanardi
# Task #111 Old Price in PDF not correctly styled
# Task #132 Image creation error when thumb height or width is Zero or empty in configuration -> site (tab)
^ Task #134 default values for some fields was "0" instead of null
# Task #156 Virtuemart registration form country and state list translation issue
^ Task #161 updated README and INSTALLATION
# Task #163 removed ordering buttons for child products list
# Task #164 bug in ps_montrada
^ Task #176 import / export menu items removed

01.10.2010 zanardi
# Task #167 VM1.1.5: Orders equal to 0.00 but customers still get sent to PayPal

26.09.2010 zanardi
^ Task #115 Error Process for Missing Required Ship To Fields Loses User Entered Data
# Task #117 Illegal variable_files ... error after clicking Add to Cart when there are empty attributes
# Task #129: Some characters in attribute name cause issues
^ Task #134: When creating text or textarea field type in Manage User Fields, default value is 0
+ Task #135: Zone shipping module doesn’t have ‘The shipping rate has been updated’ message when ‘Apply‘
# Task #136 "Apply" button in shipping module form acts wrong
^ Task #138 Admin Customer Reviews page should have no "New" button
^ Task #151 Check for child items before displaying "call for price"

11.09.2010 zanardi
# Small XHTML validation fixes

27.07.2010 zanardi
# Task 114 After changing order status goes back to all orders
# Task 116 Bug in checking stock (doesn't check for negative values)

--- VirtueMart 1.1.5 released (Rev. 2499 2010-07-25) ---

20.06.2010 zanardi
# Fix incorrect link in waiting list e-mail
# Fix notices in backend order detail
+ added configuration for SMTP server with SSL and/or listening on a port different than 25 (retrieves Joomla configuration)
^ modified uninstall function, now tables are preserved for future installations

15.06.2010 tkahl
^ Performace improvement by checking if parent product has to be included in the query

11.06.2010 milbo
^ added Merchant Warrior payment method

20.05.2010 soeren
+ added PayPal Express/API integration code

29.03.2010 soeren
^ FRQ-2889 - improve eway payment module test mode settings 

02.02.2010 soeren
# Task #2750 - custom attributes error in 1.1.4
# Task #2713 - You can't delete from order status listpage with the toolbar button 

01.02.2010 soeren 
# Task #2763 - Product old price not rendered correctly in PDF 
# Task #2754 - "Failed to parse the XML Update File" during the update 1.1.3 -> 1.1.4 
# Task #2755 - Additional images double quotations and backslashes not stripped 
# Task #2758 - No message when add 0 stock product to cart VM 1.1.4
# Task #2760 - If single 0 vote remains when a review is deleted, that vote is wrongly deleted 
# Task #2766 - Tax Rates not calculated in standard shipping module 
# Task #2769 - Method url in class vm_ps_session overwrites native Joomla Itemid 
# Task #2809 - Missing space between html attributes 
# Task #2816 - Value input doesn't work with up/down buttons if child list with radio buttons is used 
# Task #2841 - Login problem during checkout - Joomla 1.0.x only 
# Task #2853 - Ensure Contrasting Text in Shop Debug Messages 
# Task #2859 - Product pagination in product details does not loaduser defined queries 
^ added some modifications to prepare integration of a PayPal WPP/Express module
^ some adjustments to the simple admin interface

28.01.2010 tkahl
! Fixed SQL Injection problem (alert: http://www.securityfocus.com/bid/37963/exploit)

11.01.2010 soeren
# Task #2847 - Joomfish can't translate the category name in navigation list

16.12.2009 soeren
# Task #2748 - Useless table in the query of get_cid
# Task #1094 - Omitting http:// from Product Info URL field in Backend causes invalid Image link on Product flypage 

11.11.2009 soeren
# Task #2764 - Can't delete old (pre 1.1.4) orders in VM 1.1.4


--- VirtueMart 1.1.4 released (Rev. 1967 2009-10-16) ---

14.10.2009 aravot
# Fix for review form.

08.10.2009 soeren
# Task #2719 - Deprecated error message when using php 5.3 
# Admin Folder Blocked Via Htacess | Update Account "Save" Image comes from Admin [topic=61258]
# Task #2722 - 3 Bugs in product.folders.php 

30.09.2009 soeren
# Task #2719 - Deprecated error message when using php 5.3 

26.09.2009 soeren
# Task #2692 - Rounding issue in step 4 of checkout 
# Task #2702 - Count of Parameters of Product Type at front page 
# small fix to resized image display [http://forum.virtuemart.net/index.php?topic=59990.0]

24.09.2009 soeren
# Task #2711 - Undefined variable when review is modified by store owner.
# Task #2651 - EU VAT ID tax handling with dynamic EU VAT ID field name
# Task #2703 - convertECB error in localhost 

23.09.2009 mauri
# Fixed EU VAT ID in ps_product.php and ps_checkout.php
# Fixed undefined index in ps_order_chenge.php

21.09.2009 soeren
# fixed voting on edit vote (http://forum.virtuemart.net/index.php?topic=60287.0)

21.09.2009 aravot
# Switched width and height in ps_product.php
# Fixed case in ps_product_files.php

20.09.2009 thepisu
^ updated Persian (farsi) language (thanks to Mohoammad Hosien Fazeli)
^ updated Bulgarian language (thanks to Todor Iliev)

18.09.2009 soeren
- removed prototype.js
- removed unnecessary nusoap class files
^ using file_put_contents to write the configuration file in ps_config.php (instead of fputs)
- removed old "all-one-module"


14.09.2009 soeren
# small fix to make VirtueMart compatible to PHP 5.3

11.09.2009 soeren
# Task #2683 - Attribute List price modification not honored in add to cart  
# added the fix for order tax calculation when coupon discounts are used (http://www.nandebayo.org/blog/?p=80)

01.09.2009 soeren
# Task #2555 - Searching for child products is not possible 

28.08.2009 thepisu
^ updated Persian (farsi) language (thanks to Mohoammad Hosien Fazeli)
^ updated Hungarian language (thanks to pedrohsi)
^ updated Swedish language (thanks to sgagner)

25.08.2009 soeren

^ added new "init" function to the currency converter module, to check wether currency conversion can be initialized
# when currency conversion was not possible, the currency was still changed, but no conversion was calculated!

22.08.2009 thepisu
^ updated Persian (farsi) language (thanks to Mohoammad Hosien Fazeli)

19.08.2009 soeren
# Task #2690 - impossible to delete a credit card

17.08.2009 soeren
# Task #2687 - mod_virtuemart vm_JSCook.php error 
# Task #2689 - shop.feed.php SEF URL Bug Fix
 
09.08.2009 mauri
# Task #2688 - Page Title shows wrong in checkout, if uncheck some steps.

31.07.2009 aravot
# Fixed - SQL error caused by function get_name_by_catid($catid) (http://forum.virtuemart.net/index.php?topic=58641.0)

29.07.2009 soeren
# Task #2686 - Problem with ps_authorize.php on checkout if more than one ps_authorize payment method exists

23.07.2009 soeren
# Task #2679 - Authorize.net current configuration will not connect to the testing server.

22.07.2009 soeren
# Task #2563 - Wrong credit card expiration date in user Account Maintenance.
# Task #2310 - Stock levels not maintained correctly when selling downloadable products.
  
21.07.2009 aravot
# Task #2677 - Tax rate should not be rounded. 
! Modified tax.tax_list.php file to use 5 decimals for the tax rate 

20.07.2009 soeren
# Task #2677 - Tax rate should not be rounded. 
! Table Structure Change: Table "jos_vm_tax_rate", using 5 decimals for the tax rate now
! ALTER TABLE `jos_vm_tax_rate` CHANGE `tax_rate` `tax_rate` DECIMAL( 10, 5 ) NULL DEFAULT NULL 

# Task #2676 - Notice: Undefined index: order_status in ps_order.php on line 296 when Resend Download ID is clicked.
 
15.07.2009 aravot
+ Task #2663 - One Title option in billing form (Ms)

15.07.2009 mauri
# Task #2672 - Notice: Undefined index: unit in product_type.tpl.php on line 44

14.07.2009 soeren
# Task #2668 - Mail contents is broken on OSX server. 
# Task #1972 - Tax added even when product has no tax assign to it 
# Task #2584 - Image upload displays errors and thumbnail creation is not possible when open_basedir does not include PHP's upload_tmp_dir directory 

12.07.2009 mauri
# Task #2549 - When 'Virtual Tax' is unchecked, 'Show "(including XX% tax)" when applicable?' it not shown for all products. 

11.07.2009 mauri
# Fixed Order Dates incorrect ps_order_change, reverted changes.

10.07.2009 aravot
# Fixed Warning: Call-time pass-by-reference has been deprecated in payment/ps_eway.php on line 511

02.07.2009 thepisu
^ updated Simplified Chinese language, now utf-8 (thanks to joomladz)
+ added Estonian language (thanks to Eraser)

30.06.2009 soeren
# Task #2548 - When Dynamic Thumbnail Resizing is off (unchecked) additional images show big image instead of thumbnail
# Task #2507 - In category removing a thumbnail doesn't check if the file is used by other categories

26.06.2009 aravot
#BUG #2498 - Order Dates incorrect, reverted changes.
^ Update CA tax rate

29.06.2009 soeren
# Task #2658 - The page title of product details pages gets truncated when use national product names
# Task #2647 - Product could not be unpublished from the product list page if this product has different vendor other then the default one.
# Task #2659 - Super Adminisrtaor has right to publish/unpublish only default vendor products (should have permission to all) 
# Task #2665 - Price converted twice when attributes with prices are set up 
# Task #2662 - Only variable references should be returned in ps_country
# Task #2655 - CC month in Admin panel differs from Email Invoice 

28.06.2009 soeren
# Task #2660 - little enhancement for addtocart_advanced_attribute.tpl.php
# Task #2661 - Notice: Undefined variable: resultmm in ps_authorize 

27.06.2009 mauri
# Clean unnecessary comments in ps_product
# Fixed Standard_Shipping_module, Currency convert in shipping rates.
# Fixed Divided by zero in ps_order_change, when all orders are deleted.
# BUG #2657 - Undefined variable: order_id when viewing order.
# Fixed Undefined variables in ps_order_change.
# Fixed Order Dates incorrect in ps_order_change.
# Fixed Undefined variable rate in ps_product.
# BUG #2502 - Wrong tax, when update price in backend order.

26.06.2009 aravot
#BUG #2498 - Order Dates incorrect (thank you Scott)

24.06.2009 sobers_2002
# Fixed the shipping tax calculation in the standard shipping module
# Fixed the sh404sef issues in transmenu with submenus

24.06.2009 aravot
# Corrected PHP short tags.

24.06.2009 soeren
^ TSK-2620 -  Do not save login cookies by default 
# Task #2624 - Credit card type now showing 
# Task #1489 - PDF Output won't add product image
 
23.06.2009 soeren
# Task #2630 - Undefined index message in Store Edit 
# Task #2631 - prep4SQL renames field names
# Task #2652 - Wrapper Url traced wrrong 
# Task #2653 - arguments for str_replace () are swapped 
# Task #2654 - Problem with email registration 

23.06.2009 aravot
# Fixed landscape/portrait issue http://forum.virtuemart.net/index.php?topic=55201.0 Thank you Phil
# Fixed Bug in template.class.php http://forum.virtuemart.net/index.php?topic=54394.0 Thank you Phil

22.06.2009 soeren
# Task #2015 - The Value of the Coupon is greater than the current Order Total, coupon value displayed with no tax 
# Fixed VAT based on Shipping Address (http://forum.virtuemart.net/index.php?topic=56983.msg186554)
^ added Slovak Language Files (utf-8)
# Task #2053 - Resend Download ID, lower order status update buttons do not send emails 
# when updating a downloadable product file any references to ordered downloadable files are updated as well when necessary
# Task #2643 - Title setting is done by different function, integrity of code requires virtumart defined function 

 
17.06.2009 aravot
# BUG #2649 - Username & Password required when updating address with "No Account Creation"
# BUG #2648 - Product name in Call for Pricing message promt is wrong, if product name have special characters.

12.06.2009 soeren
# Task #2373 - Customers comments sripts slashes and I m getting rn in the enter of users. 
# Task #2646 - New usergroup with admin rights can't see administration in in frontend
^ adapted rewritten ps_session::url function from here: http://forum.virtuemart.net/index.php?topic=56664.0, thanks!

02.06.2009 soeren
# Task #2637 - Credit card type now showing for No Account creation method 
# Task #2638 - flypage_images.tpl.php has function protoPop() which is outdated
# Task #2495 - Order Print View, Inc Tax & Coupon Discount
 
29.05.2009 soeren
# partly fixed linkpoint class

28.05.2009 soeren
# Task #2547 - "&amp;#039;" instead of "apostrophe" in breadcrumb (mainframe.class.php) 

27.05.2009 soeren
# Task #2634 - Can not register user 
# corrected Serbian/Yugoslavia currency

24.05.2009 thepisu
# updated Croatian-Hrvatski language (thanks to dac3d)
# updated Spanish language (thanks to Blogapeuta)
# updated Bulgarian language (thanks to Imago)
# updated Swedish language (thanks to mauri)
# updated Traditional Chinese language (thanks to SimonSimon)
+ added Lithuanian translation (thanks to no0n3)

15.05.2009 macallf
# BUG #1345/#1598 - Out of stock children displayed in drop down

15.05.2009 aravot
# BUG #1348 - Functions not showing in admin

13.05.2009 aravot
# Fixed Notice Undefined variable total in shop.basket_short.php

11.05.2009 soeren
# Task #2590 - Attributes are not calculated 
# Task #2595 - Frontend edit icon should not have a hard-coded dimension
# Task #2566 - Notify button not working 
# Task #2599 - Product attributes with a '0' value are blank in orders [PATCH] 
# Task #2602 - Multiple price table (tier pricing) conversion bug

10.05.2009 aravot
# BUG #2611 - Changed _PSHOP_ADMIN to _VM_IS_BACKEND
# Fixed The Vendor Category and Vendoer Image Path fields can't be updated (Thank you Phil)
# Fixed 'Error: A value for the field "vendor_category_name" is missing' (Thank you Phil)

06.05.2009 aravot
# Fixed TransMenu Call to a member function setQuery() on a non-object error message
# Fixed TigraTree Call to a member function query() on a non-object error message
# BUG #2592 - Fix for Authorize.net status code 4 (good trans but produces error in VM)

03.05.2009 aravot
^ Changed Authorize.net Login ID to Authorize.net API ID

02.05.2009 rolandd
# BUG #2016 &euro; is displayed in info message The Value of the Coupon is greater than the current Order Total,
# suppres getimagesize warning if file does not exist

01.05.2009
# BUG #2583 - Fixed to show shipping rate tax for all tax modes

01.05.2009 rolandd
# Unknown column 'file_is_image=0'
# PSHOP_SHIPPING_MODULES not defined
# Product type not adding for a single product

27.04.2009 aravot
# Fixed typo in product.product_product_type_list.php

26.04.2009 rolandd
^ FRQ #2582  shop.browse - optimize to lower nubmer of DB queries.

23.04.2009 rolandd
# BUG #2356 Billing information will be overwritten if sento information is saved
# BUG #2573 ps_cashondel.php contains a couple of short tags 

22.04.2009 aravot
# Fixed recently viewed items when a product or category is unpublished it is still shown (thank you Phil)
# Fixed number of recently viewed products to display (thank you Phil)

21.04.2009 aravot
# BUG #2550 - Child product price is shown wrong for users other than default group

16.04.2009 aravot
# Task #2536 - Incomplete php tag with a duplicated table closure'
# Task #2537 - Default currency plugin not set!
# Fixed delete child product from cart when child product has apostrophe in product name. 

15.04.2009 aravot
# Fixed Kenya, Somali and Tanzania currency
 
07.04.2009 macallf
# Bug - Fix problems with slimbox not init after AJAX pageload & addtocart confirmation box only working once.

05.04.2009 aravot
^ Update CA tax rate

05.04.2009 macallf
# BUG - 0 quantity deletes product from cart on product page and browse page. Should only happen on update or single add to cart button child list

31.03.2009 rolandd
# BUG #2410 Making a File a "downloadable product file" resets existing downloadable product files 

30.03.2009 aravot
^ Changed PayPal test account link in notify.php
+ Added Continue Shopping link when cart is empty
 
23.03.2009 soeren
# Task #2536 - Incomplete php tag with a duplicated table closure' 
# Task #2537 - Default curency plugin not set!

20.03.2009 macallf
# Bug #2543

19.03.2009 aravot
# Review message fixed. 

15.03.2009 rolandd
# Bug #2539 missing in conditional expression in mod_virtuemart TigraTree
# CSV Improved not correctly detected
# Bug #2526 Open Account Maintenance in a GreyBox does not work.
# Bug #2538 Ext is undefined
# Open Checkout in Greybox (revisited)
^ Updated DTD for modules for J1.5

13.03.2009 macallf
# Task #2532 Fixed adding multiple products to product_type

12.03.2009 aravot
# Fixed - Fatal error: Class 'ps_order_change' not found in ps_order_change.php

08.03.2009 macallf
^ Changed product_types to template control. Template file product_types.tpl.php added to /common

08.03.2009 aravot
^ Changed Parameters of Category to Parameters of Product

04.03.2009 aravot
# Fixed - Open Checkout in Greybox

04.03.2009 soeren
# Task #1921 - Shipper notice is empty

03.03.2009 macallf
# Task #2509 Current fix saves fields correctly but does not allow for adding new fields.

28.02.2009 aravot
# Task #2374 - Bug in product type flypage path for Joomla 1.5 / VM 1.1.2
^ Compliance issue in classes/ps_userfield.php (http://forum.virtuemart.net/index.php?topic=44940.0)

28.02.2009 tkahl
# Task #2517 - VirtueMart installation fails 

25.02.2009 aravot
# Fixed Failed to open stream error when product image is missing (http://forum.virtuemart.net/index.php?topic=52081.0)
# Fixed Bug in Product Type Parameter processing (http://forum.virtuemart.net/index.php?topic=44445.0)

25.02.2009 soeren
# Task #2489 - Customer review comment length error message
# Task #2509 - Saving exisintg custom user field multiplies values - with fix
24.02.2009 soeren
# Task #2513 - Taxes ids higher than 127 cannot be used on products

22.02.2009 macallf
# Task #2511 Extra ID's doesn't work for all products, it only works for child products. Edited logic in ps_product_attribute.php

19.02.2009 macallf
# Task #2510 Altered ps_product.php to allow aplhanumeric chars in the child_class_suffix field

17.02.2009 soeren
# Task #2506 - Subtotal rounding error in savebasket
# fixed the IE "Operation Aborted" error in the frontend, when "Open Product Images in a LightBox?" is enabled (which is the default)
# fixed the IE "Operation Aborted" error in Extended Layout in the backend's product form
 
11.02.2009 aravot
^ Fixed comment typo in SQL file

05.02.2009 soeren
# Task #2490 - Add to cart issues when javascript disabled 
# fixed: no value assigned to $DescOrderBy

05.02.2009 aravot
# Task #2486 - Loading Edit Store and other administrative pages under MSIE <= 7.0 results in Operation Aborted error and unable to view.

02.02.2009 aravot
# Task #2491 - Broken links on pathway
^ Fixed Configuration table alignment in admin.show_cfg.php
^ Corrected langauge files (thank you Phil)

01.02.2009 soeren
# Task #2486 - Loading Edit Store and other administrative pages under MSIE <= 7.0 results in Operation Aborted error and unable to view.
# Task #2481 - Changes to class_currency_display.php course currency symbol to be displayed as currency type.

24.01.2009 aravot
# in Extended Layout view, menu icons are shown twice
  
24.01.2009 thepisu
# Task #2496 - Standard Shipping Module saving problem in DB (fix in ps_shipping - adding a new carrier)

22.01.2009 thepisu
# Task #2473 - Untranslated language string in checkout_register_form.php 
# added translation for strings in ps_shopper_group

--- VirtueMart 1.1.3 released (Rev. 1611 2009-01-22) ---

19.01.2009 soeren
# Task #2471 - Wrong bill_to address in email confirmation Text mail.

17.01.2009 thepisu
+ added Serbian Latin translation (serbian_lat, UTF-8); by Vlada_bgd; not yet fully translated

15.01.2009 aravot
^ updated Turkish Lira from "New Turkish Lira" to "Turkish Lira" in installation sql

14.01.2009 thepisu
^ converted Italian language to UTF-8
^ fixed French language encoding - converted to UTF-8

12.01.2009 soeren
# Task #2470 - Quantity Box JavaScript validation code fails XHTML validation 
# fixed paymenow module (login information was missing on payment processing) 

10.01.2009 aravot
# Fixed PHP5 Call-time pass-by-reference message

10.01.2009 thepisu
^ updated Finnish language (by Mauri)
^ updated Italian language

08.01.2009 soeren
# Task #2458 - order search function in account maintenance now searches for order item names/sku and order number
# Task #2469 - tax_rate problem in checkout
# Task #2434 - Modules need a statment if to check if the product has items so not to show add to cart. 
# Task #2457 - Error: CheckOut needs a valid Step! (currency switcher module)
 
06.01.2009 soeren
# Task #2455 -  Problems with sort function "Order by"
# Task #2250 - Products that contain more than one download file only have one download link listed on invoice
# merged patches for compatibility with SecurityImages 5 (http://www.waltercedric.com/joomla-mainmenu-247/304-securityimages/1364-virtuemart-112-and-securityimages-5.html), thanks Cedric for providing these!

05.01.2009 soeren
# Task #2463 - pageNavigation problem
# Task #2317 - Submit button on shop ask page is not working

24.12.08 thepisu
# address formatting:  {statename} not converted; config tip wrong
# sql update script from 1.0.x: some chars wrongly encoded; updated version info to 1.1.2 stable; missing function records
# sql sample data: removed HTML entities from shipping rates
# checkout confirmation tpl: not "make safe" old shipping sample data (cointaining "&gt;"); that was "maked safe" twice

20.12.08 thepisu
# fix in Language Manager
^ updated Swedish language (by sgagner)
^ fix in Italian language

19.12.2008 soeren
# Task #2453 - Problem with Tax State

17.12.2008 soeren
# Task #2451 - Additional downloadable files missing in account maintenance
 
15.12.2008 soeren
# Task #2448 - stock levels not reduced when order confirmed
# Task #2446 - adding custom user fields causes inability to see orders in order list 
# Task #2450 - Weekly Sales report only lists products sold on first day of week
 
10.12.2008 aravot
# Cant disable Keep Product Stock Level on Purchase (http://forum.virtuemart.net/index.php?topic=42901 - thank you patjun)

08.12.2008 soeren
# Task #2440 - Zone Shipping on checkout page shows zone value rather than zone name. 
# Task #2443 - Save button for additional file/images not working in IE (missing ajax_request var) 
# Task #2197 - Parent price shown for Child items when user is member of a shopper group other than default 
# Task #2445 - hidden user_id present 2 times in a form 
# removed additional user_id parameter from admin.user_address_form
^updated Turkish Lira to "New Turkish Lira" in installation sql
# undefined variables in ro_basket.php

08.12.2088 aravot
# Compliance issue in classes/htmlTools.class.php (Thank you Phil)

05.12.2008 aravot
# Fixed shipvalue (http://forum.virtuemart.net/index.php?topic=39883.0 Thank you chaliet)

04.12.2008 aravot
# Removed affiliate reference from configuration and language file

04.12.2008 soeren
^ Task #2441 - Updated Slimbox to latest version (1.54)
# usps.php - fatal error on curl_error
# shop.feed.php - fatal error when JoomFish is enabled
# Task #2439 - Dynamic Image Resizing ( PSHOP_IMG_RESIZE_ENABLE ) can not be switched off casuing broken thumb images on Category display
^ show_image_in_imgtag.php: changed max height+width to 600px and min to 40px;
# no resized images will be created when "Enable Dynamic Thumbnail Resizing?" is disabled; added a check for the existance of the GD library

02.12.2008 soeren
# better HTTPS detection;
#Task #2438 - fixed mod_virtuemart_manufacturers.php PHP notice

30.11.08 soeren
#Task #2437 - Undefined variable: coupon_display;
#Task #2436 - Error when trying to view order in Account Maintenance

30.11.08 aravot
# Task #2414 - Shipping zone display error

29.11.08 aravot
#2314 - Add Item MISSING in 1.1.2

29.11.2008 soeren
# fixed some security-related issues.

28.11.2008 soeren
# Task #2429 - Undefined variable: tax_display
# fix for removed affiliate module

28.11.08 aravot
# Task #2366 - Missing info message "The selected quantity exceeds quantity available in stock." with EASY FIX (Thank you Danny)
# Task #2394 - Selecting orders in the back-end causes a major slow-down with large number of orders

27.11.08 aravot
# Task #2412 - Child Products do not Display in IE7 AJAX Cart
# Task #2406 - Too many escape characters are added when writing virtuemart.cfg.php

26.11.08 aravot
Fixed missing Checkout Bar image using Joomla 1.5.8

19.11.08 thepisu
^ updated Hungarian language (by pedrohsi)
^ updated Finnish language (by mauri)
^ updated Dutch language (by Tonslag)
^ updated Spanish language (by adancer)
^ fix in Italian language

10.11.08 mainly Danny with help by Max Milbers and committed by Max Milbers
+ showing state name instead of state-2-code in order details

06.11.2008 aravot
Added missing User Group List icon

23.10.2008 aravot
Added missing forum icon

13.10.2008 aravot
# Task #2305 - VM1.1.2 frontend order layout broken

04.10.2008 by RolandD committed by Max Milbers
fix for displaying child products in a list. In IE7 and IE6 the page collapses because of a missing span tag. IE then places all subsequent child products in the previous span. This works fine unless you have more than let's say 15 child products. 

02.10.2008 aravot
Added missing product navigation link parameters to theme.xml file

19.09.2008 soeren
# Task #2371 - Moving up/down doesn't work in admin lists
# Task #2368 - order date not local language 
+ added pathway + page title to registration page
^ changed basket + ro_basket to read the basket templates using the vmtemplateClass::fetch method

15.09.2008 soeren
# Task #2331 - search_date hidden field doesn't get a value in admin product listing on backend after
# Task #2335 - mod_virtuemart_login.php contains unescaped ampersand characters
# Task #2336 - HTML entities in product name are not always escaped leading to XHTML validation errors.
# Task #2337 - Labels for the user fields 'title' and 'state' do not match the ids used on the select tags.
# Task #2338 - Option values for dropdown lists allow unescaped HTML entities leading to XHTML validation errors.
# Task #2339 - The vendor image requires the alt attribute for XHTML validation
# Task #2340 - pageNavigation.class.php specifies nowrap="true" causing XHTML validation errors.
# Task #2341 - writeSearchHeader function in htmlTools.class.php generates invalid HTML
# Task #2342 - The dropdown list of downloadable filenames for a product should exclude system files such as .htaccess and index.html etc.
# Task #2345 - Rogue double quote in basket_b2c.html.php
# Task #2344 - XHTML validation errors in basket
# Task #2346 - Username and password login boxes require unique ids for XHTML validation
# Task #2347 - XHTML validation errors in shop.downloads.php
# Task #2348 - XHTML validation error with PayPal image

13.08.2008 aravot
Corrected few spelling mistakes in English language (http://forum.virtuemart.net/index.php?topic=44169.0 - thank you Phil)

07.08.2008 aravot
Fix compatibility issues with jomcomment system plugin (http://forum.virtuemart.net/index.php?topic=42558.0 - thank you azrulrhm)

05.08.2008 aravot
Changes made to mod_virtuemart_login to make xhtml w3c compliance (http://forum.virtuemart.net/index.php?topic=44009.0 - thank you Phil)
Changes made to mod_virtuemart to make xhtml w3c compliance (http://forum.virtuemart.net/index.php?topic=44008.0 - thank you Phil)

03.08.2008
# Task #2286 - Manufacturer Description missing query mf_desc in shop.browse.php on line 121

--- VirtueMart 1.1.2 released (Rev. 1495 2008-07-31) ---

31.07.2008 soeren
# added missing </div> in update preview

31.07.2008 gregdev
# Change mkdir permissions from 755 to 0755 in updater.

30.07.2008 gregdev
# Task #2277 - Subcategories are not show if category has only one product
# Task #2219 - Attributes of Childproduct are not shown
- Removed 1.1.1->1.1.2 sql update script.
# Task #2263 - Incorrect total price rounding in mini cart and mod_virtuemart cart

25.07.2008 aravot
Minor CSS fix lightblue to #ADD8E6 (thank you Phil)

24.07.2008 soeren
# Task #2272 - added ccNewsletter Integration for VM

24.0.7.2008 aravot
# Fix productsnapshots output order when displaying a row of products (http://forum.virtuemart.net/index.php?topic=43001.0 - thanks donmarvin)
# 2176  Error in Infotip for Cofig/Layout/Category template.

23.07.2008 gregdev
# Fix disabled shipping methods when last option is chosen (http://forum.virtuemart.net/index.php?topic=40580.15 - thanks Joseph)

21.07.2008 soeren
# Task #2081 - add product page tabs empty

18.07.2008 gregdev
# Task #2260 - DHL shipping error when address 2 is present 

17.07.2008 gregdev
# Task #2256 - When Joomla Allow User Registration is set to No, VM template breaks.
^ Changed vmRedirect to use $mainframe->redirect($url, $msg) on Joomla! >= 1.5
# Task #2258 - Bottom page navigation is placed in wrong location for two product list styles.

16.07.2008 gregdev
# Task #2195 - Don't allow a user id of zero
# Fix fatal error for date-type userfield

16.07.2008 soeren
# Task #2246 - Download count and expiration still modified if file missing or unreadable when download requested.
^ language class: added the ability to retrieve a key from an arbitrary module, whose language file was loaded from within a page
	Example: $VM_LANG->load('mymodule') now gives you access to that language keys even if the current "page" belongs to a different module (e.g. "store")

15.07.2008 gregdev
# Task #2243 - User prompted for username & password when Virtuemart in "No Account Creation"
# Task #2245 - Shipping Address Selection - switching back to default address

14.07.2008 soeren
^ Task #2240 - mf description also in shop.browse.php and browse_header_manufacturer.tpl.php 
# Task #2227 - When Joomla cache is enabled Product list with table doesn't work 
# Task #2232 -  Wrong currency code value for Polish Zloty  
^ Task #2234 - added "statename" to the list of placeholders which can be used in the vendor address format. It holds the actual state name.

14.07.2008 gregdev
# Fixed product_url for featured products
# Fixed </li> typo in pageNavigation.class.php
# Xhtml compliance issues in shop.search.php
# Don't show the browse page footer when there are no products

11.07.2008 gregdev
# Task #2226 - Instead of 'Notify Me' button 'add to cart' button is shown when list box for child products is selected.

09.07.2008 gregdev
# Task #2224 - Don't show up ajax pop-up when click "Notify me"
^ Dates supplied to the browse templates are formatted now, rather plain UNIX timestamps
# Task #2204 - Fixed logic error for showing product name in product snapshot template
# Fixed missing $product_parent_id in header.php
# Task #2041 - Renamed Subtotal column to Total (English only) on order list

08.07.2008 gregdev
# Task #2168 - Fixed bank account information not saved in backend user form
# Task #2210 - product_availability_date typo in shop.browse.php 
# Task #2211 - product_url missing in shop.browse.php
# Task #2093 - Show Pagination only when needed
# Task #2220 - Add to Cart for each child is not saving
^ Added optional $force parameter to ps_product::get_field() to force reload from the database
# Task #2204 - Cannot remove product name in mod_productscroller
# Fixed missing class mosParameters (used vmParameters) when validating EUVatID during registration

08.07.2008 soeren
# Product Form: fixed Attribute Form becoming inaccessible when many attributes are added (overflow not visible)
# fixed hidden (inaccessible) Tabs when too many tabs are in the tab panel (scrolls like in FF now)
# Task #2185 - Advanced Search according to Parameters bug and fix
# Task #2214 - XTHTML Error in LoginScript
# Task #2215 -  Problems with ps_session.php after rev 1451

04.07.2008 soeren
# Task #2209 - Picture checkout2_1.png not in use
# shipping bypass didn't work
# re-enabling a product download made it impossible to resend the Download ID (user_id was set to 0)
^ adapted SEF pageNavigation.class.php by shumisha of sh404SEF

02.07.2008 gregdev
# Task #2059 - Plugins not working in child product.
^ Don't use <label> for child product titles when there is no child link

01.07.2008 gregdev
# Task #2183 - VM productsnapshots not working in J1.5 when legacy plugin enabled.
# Task #2180 - mosproductsnap mambot executed even if unpublished
# Fixed vmproductsnapshot to use parameters in Joomla! 1.5.x

01.07.2008 soeren
# Task #2198 - 0 rating doesn't work for review system. 
+ added Roland's massively improved SOAP-based EU VAT ID validitation code (thank you!)
# Task #2196 - Protected property accessed directly in class.inputfilter.php
  
30.06.2008 soeren
+ implemented "Shipping Bypass" for downloadable products
^ re-enabled Payment Bypass for checking out with zero-priced products 

26.06.2008 soeren
# Task #2189 - Tigra Tree not SEF compatible with fix
# Task #2185 - Advanced Search according to Parameters bug and fix
 
26.06.2008 gregdev
# Task #2184 - Missing 'Yes' in show in shipping form in user filed.
# Task #2188 - curl_exec() has been disabled fix
# Task #2187 - Missing url in connectionTools.class.php
# Task #2181 - Membergroup to show prices to not working without legacy plugin
^ Remove "Select" from Joomla! user groups list on user form 

24.06-2008 soeren
# Task #2175 - Minimum Purchase Order Value not updated according to new currency set in Currency Selector
# Task #2178 - Missing back button on adding additional image 
# Task #2179 - changed varname in virtuemart_parser.php
# Task #2129 - Billing address is not shown on checkout page
# Task #2182 - Please add - Select State - to beginning of State drop down list.

24.06.2008 gregdev
# Fixed vmSimpleXML to not inherit from JObject (for Joomla! 1.0.x and PHP4)

--- VirtueMart 1.1.1 released (Rev. 1436 2008-06-24) ---

19.09.2008 soeren
# new Joomla! users find Billto information of other customers (dummy user_info entries could mix up with Joomla! user records)
# fixed "operation aborted" error on some pages (due to wz_tooltip.js)
# fixed Updater to handle float numbers

18.06.2008 soeren
# updater class not able to create new subdirectories

18.06.2008 gregdev
- Removed PayPal Website Payments Pro (will restore in later release)
# Hide the Joomla admin menu in Joomla! 1.5 for the order print details

17.06.2008 gregdev
^ Added ability to print order from order details page in backend
^ Added ability to publish/unpublish products from the inventory page; also changed product link to match product list

16.06.2008 soeren
# no space between additional Images in Internet Explorer 7 
# fix for EU VAT ID check

14.06.2008 gregdev
# Task #2168 - Bankaccount informations of customers are not shown in Backend
# Task #2068 - No link in email when order status changes
# Task #1742 - Wrong redirection with virtuemart login module
# Task #2034 - Wrong URL after logging out
# Task #1889 - Publish button not working in filemanager
# Task #2118 - Shipping value doesn't change in new order change file
# Task #2062 - USPS and UPS conflict
# Need to instantiate ps_country.
^ Reformat FedEx shipping options to match UPS and USPS.
^ Clean up the order details (VM admin) page.

12.06.2008 soeren
# Task #2104 - Add to Cart 'Up' and 'Down' buttons don't display correctly in IE6 
+ added Filename-Display to all important template files (shows file names in DEBUG mode then!)
# Item Dropdown List didn't reflect actual discounted prices, but normal prices
# Task #2165 - e-mail address of customer as from in vendor_mail

12.06.2008 gregdev
# Task #2110 - Fixes for PayPal Website Payments Pro

12.06.2008 kaltokri
# Fix the divide by zero bug in "Order Edit"

11.06.2008 soeren
^ changed product form to display a "category search form" instead of a select-list with all categories when more than 200 categories are present in the store
# Task #2069 - Wrong URL in Order Status Change email when using PayPal
# Task #2166 - Add to cart broken in SVN 1408 
 
09.06.2008 soeren
# Task #1840 - Ajax call does not work with full SEF URL
# Task #2120 - Cookie check not showing warning when cookies are disabled
# Task #2119 - Product descriptions are truncated at the first instance of "&nbsp;"
# Task #2116 - Search in Country, State list not working
# Task #2109 - Captcha is shown on invoice (attempt #2)
# Task #2108 - Spaces removed form alt and title of images inserted in product descriptions 
# Task #2107 - Feature Product on shop page shows 'Notify Me' button even when product is in stock

26.05.2008 soeren
# Task #2117 - Call-time pass-by-reference error in ps_order_change.php

25.05.2008 thepisu
+ added language strings for updated "Order Edit" feature

24.05.2008 soeren
^ updated "Order Edit" feature, thanks to kaltokri!
# Task #2109 - Captcha is shown on invoice 
# Task #2111 - Payflow Pro - Call to undefined function mosgetparam error 
^ updated wz_tooltip to version 5.0

24.05.2008 thepisu
# addslashed javascript-driven text in order.order_list
+ added "default" ordering option for frontend (using the already working product_list field)
# Task #2047 Missing conversion from utf-8 to cp1251
# Task #1893 Strings hard coded 
- removed language strings related to old PBS (danish) payment module
# Updated Dutch translation (by Tonslag)
# Romanian state Vreancea corrected to Vrancea

23.05.2008 joomlacorner
# Updated Thai translation

20.05.2008 thepisu
# Task #2092 OFFLINE MODE hard coded (new string added to "common" module)
# Task #1908 list of hard coded strings (new strings added to "product" module)

19.05.2008 soeren
#  the order number in ps_checkout:add ($order_number) is 34 characters long- instead of the allowed 32 chars.

10.05.2008 gregdev (for k0nan)
# Task #2057 - Extra Bullets / dots in navigation menu when in attribute or product type section
# Task #2038 - Extended Layout view, menu icons are shown twice
# Task #2074 - W3C css 3 errors: lightgray

09.05.2008 gregdev
# Task #2089 - "Membergroup to show prices to" non-funtionsla with Joomla 1.5.3
# Task #2086 - HTML error in availability.tpl.php for the Availability line
# Task #2088 - When VM extended search plug-in is enabled in backend Joomla search gives error.
# Task #2085 - ps_DB : Function getErrorNum from not inherited from JDatabase object
09.05.2008 soeren
# Task #2086 - HTML error in availability.tpl.php for the Availability line

04.05.2008 soeren
# Task #2075 - Last Page remembered in Frontend

02.05.2008 soeren
# Task #2055 - Orders date not locale language
# Task #2060 - When registration method is 'No Account Creation' PayPal is not working.
# Task #2061 - When registration method is 'No Account Creation' no need of link in order email
# Task #2062 - USPS and UPS conflict
# Order form layout too broad [http://forum.virtuemart.net/index.php?topic=38926.0]
# Task #2064 - Vendor Address 2 and URL missing from confiramtion email and order page.

29.04.2008 soeren
# Task #2052 - mod_product_categories images link to old location

28.04.2008 soeren
# Task #1840 - Ajax call does not work with full SEF URL
# Task #2014 - group Discounts are not calculated after rev. 1368
# extra bracket in admin.styles.css
# Task #2052 - mod_product_categories images link to old location
# when caching is enabled, products don't show up in listing (but drop down lists instead)

--- VirtueMart 1.1.0 released ---

23.04.2008 soeren
# quantity in stock is checked against quantity in cart only on cartAdd and cartUpdate events, not on checkout

22.04.2008 soeren
# Task #2036 - add/edit user information page

22.04.2008 gregdev
# Task #1687 -  Don't do extra lookup for tax when in EU mode and with EU shopper; always refresh tax calculation
# Task #2037 - Fatal error using coupon submit without coupon code or with non-existent coupon code
^ Added 'none' to the list of states (for countries that have states) in tax.tax_form.

21.04.2008 gregdev
# Task #1687 -  Refactored for simplicity. It is equivalent to the previous version.

21.04.2008 soeren
# Task #2009 - Session not handed over when switching to shared SSL
# Task #2033 - Number of products will be shown even though the function is disabled.
^ updated ExtJS to version 2.1

20.04.2008 soeren
# Task #2032 - In Internet Explorer 7 width of layout in administration is to wide.

19.04.2008 gregdev
# Task #1999 - $zone_qty variable is not set
# Task #1687 -  Discount price calculated wrongly (fixed for VM 1.1.x - not VM 1.0.x)
# Fixed typo in checkout.epay_result
^ Add start date to discounts created through override method
# Choosing '0 -none-' as the product discount should clear the product discount price
# Fixed typo in English
^ Right align prices in the basket

19.04.2008 soeren
# Task #2024 - user id not set when writting review
# Task #2028 - button_ok.png in ps_image is missing
# Task #1922 - No related products list

17.04.2008 thepisu
# Task #2025 Hard coded label
^ updated portuguese translation (uploaded by Puppycare)
^ updated german translation (uploaded by iamalive)
^ updated hungarian translation (uploaded by pedrohsi)

15.04.2008 thepisu
# Task #1976 hardcoded language string in ps_checkout.php
# Task #2010 backslash in token text hides all text
# Task #2018 Hard coded strings shipping files and emails (partially: only enquiry_email.tpl.php)

14.04.2008 gregdev
# Task #2017 - ps_coupon.php 'gif' instead of 'gift'

12.04.2008 gregdev
# Corrected syntax errors (%s) in common/french.php.

10.04.2008 soeren
^ switched from ExtJS 1.1.1 to ExtJS 2.0.2 
# product list doesn't filter by parent ID when a category was selected
# dropdown list of child products had no pre-selection after selecting a child product

09.04.2008 gregdev
# Task #1687 -  Discount price calculated wrongly (fixed for VM 1.1.x - not VM 1.0.x)

09.04.2008 soeren
# Task #2007 - Account Maintenance alway visible after first login
- checkbox for "Disable Shipping ..." in the configuration form, tab "shipping". 
# Task #1933 - Security Token Error when submitting form after a while
# Task #2002 - Related products shows only 10 products
# Task #1981 - please make sure the form is complete and valid - error (removed the configuration parameter MUST_AGREE_TO_TOS, handled by the userfield manager!)
^ it's possible to place an order without having a Joomla! user account now (registration type: NO_REGISTRATION and OPTIONAL_REGISTRATION)
	the user will be logged out after having placed the order 
# Task #1998 -  Unable to save Joomla registration update in extended layout.
^ removed the requirement of being logged in to make downloads (the function downloadRequest has permissions set to "none" by default now)

08.04.2008 gregdev
# Task # 1687 -  Discount price calculated wrongly (fixed for VM 1.1.x - not VM 1.0.x)
^ In Simple Layout, the product form opens into the full browser window, not a new window
^ Go back to only storing 2 decimal places in the order_total.

07.04.2008 gregdev
# Task #1978 - If Show Manufacturer Link? is selected increasing product quantity, shifts product detail to left
# Task # 1687 -  Discount price calculated wrongly (fixed for VM 1.1.x - not VM 1.0.x)
# Task #1969 - Can't remove customer group and something is wrong, and Vendor problem.

06.04.2008 soeren
# initially hide vendor module from administration menu
# Task #1982 - Lost password link is still wrong in joomla 1.0.15
# Task #1986 - Recently Viewed Products are not aligned left

05.04.2008 gregdev
# Fixed blank cells and subtotal rounding on order.order_print
# Task #1249 - Improper price rounding

04.04.2008 soeren
# Task #1969 - Can't remove customer group and something is wrong, and Vendor problem.
# Task #1975 - Wrong # "forgot the password" - link
^ moved Javascript Libraries, which are only used by modules to the modules directory. They are part of the mod_virtuemart installation now
	(reduces size of com_virtuemart install package - we need to keep it under 2MB!)
^ reduced size of all PNG images

04.04.2008 thepisu
# translation fixes for new ePay module (some string added in "common" and "checkout" modules)

03.04.2008 soeren
^ updated to new ePay Payment Module version sent in by Thomas Knudsen of ePay

31.03.2008 soeren
# Task #1951 - Wrong display of child products when coming from account maintenance
# smaller fixes with display of child items + attributes; added label element for accessible radio/checkbox display types
# Task #1934 - forgot the password - link
# fixed Product Feed displaying ampersands for htmlentities; links were relative
# Task #1958 - Fatal error after rev 1338 when trying to look at product details
# Task #1927 - fixed PayPal IPN script on J! 1.5
# Task #1945 - 2 warnings, update one doesn't update
# Task #1957 - hardcoded image
^ update PayPal payment code
# Bugs in Product Type Parameter processing [Forum Topic 37239]
^ renamed all Log_* classes to vmLog_* to prevent namespace problems
# wrong accentuated character in Search for Products [Forum Topic 38093]
^ various changes to make upgrade from VirtueMart 1.0.x easier
# Task #1953 Manufacturer Form strips HTML and Images
# Task #1952 - User > Order List > Remove function bugs

30.03.2008 thepisu
^ updated translations: finnish

28.03.2008 thepisu
# Task #1941  hard coded string
# added various translation strings (module "common")
28.03.2008 gregdev
+ Added separate .sql files for manual installation of modules and mambots (plugins).
28.03.2008 soeren
+ added possibility to use external/remote files as product download files
+ possibility to cancel the VirtueMart Update by Patch Package (deletes the Patch File)
!! New Function Table Entry
	###
	INSERT INTO `jos_vm_function` (`function_id`, `module_id`, `function_name`, `function_class`, `function_method`, `function_description`, `function_perms`) VALUES
	(NULL, 1, 'removePatchPackage', 'update.class', 'removePackageFile', 'Removes  a Patch Package File and its extracted contents.', 'admin');
	###
+ possibility to upload a Patch Package to the server instead of relying on the server-server transfer from dev.virtuemart.net
^ updated Prototype Version to 1.5.1.2 (minified by JSMinifier [http://fmarcia.info/jsmin/test.html])
# fixed Function Form Ajax Calls
# Task #1648 - Incorrect Product Type parameter separator in product_type table

26.03.2008 gregdev
# Task #1939 - shop.savedcart.tpl.php unclosed div and few typos
26.03.2008 soeren
# Task #1929 - Image handling bugs
# Task #1935 - Currency & List of accepted currencies
# Task #1938 - "Availability" always visible?
# Task #1920 - Age Verification User Field being reset after viewing from Admin
# Task #1916 - twice description meta tag
# Task #1927 - paypal notify script
# Task #1928 - Display# is not changing

24.03.2008 soeren
^ removed eval function from list_payment_methods template, moved tooltip for "Credit Card Validation Value" to the associated label 
# Task #1924 - After account creation a warning message is shown.

23.03.2008 thepisu
# small fix in italian states data

20.03.2008 soeren
# Task #1917 - Shipvalue is still using old mosToolTip function
# Task #1915 - file rigths during installation
# Task #1914 - close fieldset tag in ps_userfield.php

19.03.2008 soeren
# fatal error in toolbar on Mambo
# Mambo didn't show correct CSS and loading JS didn't work
# Task #1910 - A closing div braking templates in checkout_register_form.php
^ non-existing or unpublished products are removed from cart on Cart Update
# Quantity Steps are checked for when updating the cart (say you have a product which can only be ordered at quantity steps of 5 - like 10 or 25) 
+ added the variable "product_price_raw" to product details page, so the "raw" product price array can be used inside the flypage templates
# Task #1907 - Cart Module not updating after first product added (IE only)

18.03.2008 soeren
# Task #1902 - Filters reset after add new product, etc.

17.03.2008 thepisu
# Task #1898 Sort Alphabetically is hard coded
# Task #1905 List of hard coded strings
# added various translation strings (module "common")
^ updated dutch translation

15.03.2008 gregdev
# Task #1891 - Add/Update product form, minor bug in prewiev link.
# Task #1896 - nowrap to remove in shopper.shopper_group_form.php
# Fully load Joomla! 1.5 framework for extlayout.js.php

12.03.2008 soeren
# List Limitstart wasn't set per page.
# Task #1401 -  Print View does order total does not include coupon discount.

--- VirtueMart 1.1.0 RC2 released ---

11.03.2008 soeren
# Task #1897 - Page navigation is wrong after default Display Items change (J1.5+VM1.1)
11.03.2008 thepisu
# Task #1867 - PHPSHOP_PRODUCT_FORM_ATTRIBUTE_LIST_EXAMPLES
# Task #1877 - Language not being picked up consistently
# Task #1883 - 'Specify the minimum Age' hard coded
# Task #1886 - confirmation_email.tpl translation

08.03.2008 gregdev
# Task #1882 - Default 'Image unavailable'
# Task #1879 - PDF generator in Product List & Product Details generates gif error

07.03.2008 gregdev
# Task #1878 - Template breaks when in Account Maintenance
# Task #1677 - When no child is presend and List box for child is selected error is shown
# Task #1880 - PDF creation fails on shop.browse page

06.03.2008 gregdev
# Task #1497 - Badly formatted Recommend the Product email
- Removed enquiry_english.html
+ Added enquiry_email.tpl.php
# Task #1412 - Don't show feed icons when feeds are disabled in the general config settings

05.03.2008 soeren
# Task #1874 - Confirmation email Ship To, State wrong
# Task #1875 - "No image" points to the wrong directory(didn't cotain the image file) in shop.browse.php

05.03.2008 gregdev
# Task #1875 - "No image" points to the wrong directory (didn't cotain the image file) in shop.browse.php
# Task #1841 - Attribute Price calculation with group price not correct
# Task #1691 - Discount price shows wrong on flypage, baket is correct
# Task #1876 - Ask a question about... is not working (restored enquiry_english.html)
# Fixed vendor confirmation email missing order items
# Fixed missing product attributes in confirmation emails

04.03.2008 soeren
# Task #1873 -  Adress is missing in Checkout adress
+ new Flypage Template Parameter "product_availability_data" (Array), so product availibility can be customized
# Task #1870 - product packaging
# Task #1869 - Custom attribute - remove "add to cart " in browse page?
# Task #1868 - double quote to remove in addtocart_drop.tpl.php
# Task #1865 - Please make sure the form is complete and valid.
# Task #1864 - Confirmation email- wrong shipping country
# Fixed "Security Token not found" Message on Joomla! 1.0.15 when magic_quotes_gpc = Off	(ps_main.php)

04.03.2008 thepisu
# Task #1845 - PHPSHOP_USER_FORM_TITLE should not be used in ps_product_attribute
# Task #1852 - Missing language in Canada post.
# added various translation strings (module "common")
# Task #1524 - During checkout state is cut offed and county uses ISO code.
# added translations strings for update check feature (modules "common" / "admin")
# "Back to Joomla! Administration" was not translated on Joomla 1.5 
  (can't initialize correctly mosConfig_lang, passed to JS throug "lang" parameter)
! added ability to pass GET parameter to JS files, if not using fetchscript (was ignored)
# vmAbstractObject validate function was considering "0" as empty
  (i.e. was unable to insert new "0" level user group)
# producted user groups not correctly checked (was able to delete core groups)

03.03.2008 soeren
# too long words in reviews break the site layout
# Task #1862 - Random Product Module: table layout don't output correct xhtml
# Task #1860 - cannot send order, error and wrong page after step 4

02.03.2008 soeren
# Task #1810 - When Product Price field is empty on the category view error is given.
# Task #1858 - Language Strings in rel 1284
^ Order Details pages show all custom user fields now
- removed email_*.html (localized email templates)
^ changed Order Confirmation Email to use a php-based template (/order_emails/confirmation_email.tpl.php)
+ custom registration/user fields are sent with confirmation email now 

29.02.2008 gregdev
# Task #1415 - no account creation bug
# Fixed missing global variable when adding custom attributes.
# Task #1832 - Flypage not change on product browsing...
29.02.2008 soeren
# Task #1815 - Cancelling user field it creates a blank / empty user field 
# Task #1816 - User fields, value section sometime is shows sometime it doesn't
# Task #1833 - quantity ,drop down list, add to cart
# Task #1834 - list of prices, thanks Valerie!
# Task #1848 - In IE7 it is possible to move a product into the same category, doing so results in product being placed in no category
# Task #1839 - Empty name, empty price in product list (extended layout)
# Task #1851 - Quantity Start and Quantity End language strings missing from List Prices
# Task #1800 - mod_virtuemart_manufacturers said "No manufacturers defined!"
# undefined var "product_parent_id" in product.product_display.php
^ Saving some queries on product price retrieval + product field retrieval
# Task #1853 -  Additional image upload not working

27.02.2008 gregdev
# Task #1849 - Missing field values in Joomla 1.5

26.02.2008 soeren
+ added basic VirtueMart Version Updater Functionality. It requires two new functions:
	###
	INSERT INTO `jos_vm_function` ( `function_id` ,`module_id` ,`function_name` ,`function_class` ,`function_method` ,`function_description` ,`function_perms`)
	VALUES ( NULL , '1', 'getupdatepackage', 'update.class', 'getPatchPackage', 'Retrieves the Patch Package from the virtuemart.net Servers.', 'admin'), 
	(NULL , '1', 'applypatchpackage', 'update.class', 'applyPatch', 'Applies the Patch using the instructions from the update.xml file in the downloaded patch.', 'admin');
	###
# Task #1844 - Moving product to another category gives error

26.02.2008 gregdev
# Task #1842 - Can't put no order-by on admin (and so in the user interface)
^ Added $Itemid to template variables for browse_orderbyform.tpl
^ Changed all theme class names to vmTheme for easier theme creation (Task #1822)
26.02.2008 thepisu
# Task #1835 - Add Product Type menu label
# added many translations (modules common/admin)
# minor fixes

20.02.2008 gregdev
# Task #1811 - first query doesn't execute in ps_product_product_type::delete_record()
20.02.2008 soeren
 # Task #1473 -  ps_product_category.php timeout
 
18.02.2008 gregdev
# Fixed - clicking save button on account.billing incorrectly returns to store.index with J! 1.5.1 SEF enabled
# Fixed - Call to a member function on a non-object ($vm_mainframe) in checkout_register_form

15.02.2008 gregdev
# Task #1556 - Added native Joomla! 1.5 .xml file for vmproductsnapshots plugin
# Added missing global $database in virtuemart_parser.php

15.02.2008 soeren
# Task #1793 - moving products to another category give fatal error
# Task #1556 - mosProduct Snapshot not working in Joomla 1.5

15.02.2008 gregdev
# Task #964 - Silent registration through mod_virtuemart shows user name/password
+ Added vmGenRandomPassword() ( to replace mosMakePassword() )
# Added missing $mosConfig_absolute_path (for com_securityimages support)

14.02.2008 thepisu
# updated languages: finnish, hungarian, russian, italian
^ updated Argentine currency
# fixed typo

14.02.2008 gregdev
# Unchecking "Show Prices including tax?" results in PHP Notice.
# Fixed unreachable code in ps_shopper_group->add().
^ Removed 'yes/no' text from "Show Prices including tax?" checkbox
# Task #1790 - User-defined "User Registration Field" of type 'Checkbox Single' doesn't work (thanks to pyh)
# Task #1779 - Notice: Undefined variable: option

13.02.2008 soeren
# Task #1785 -  Semicolon missing in theme.css file
# Bug in ps_product when using product types with apostrophes

12.02.2008 gregdev
# Task #1670 - Discount price field does not save value.

12.02.2008 soeren
# Manufacturer ID left empty in product form when only one manufacturer present
# Task # 1706 -  Deselection of Sort Order in Configuration not working

12.02.2008 gregdev
# Task #1779 - Installation archive files don't get deleted in Joomla 1.0.x

11.02.2008 soeren
# Task #1683 -  When a word in category contain ' apostrophe in mod_virtuemart the categories are not displayed
# Task #1729 -  JS cook menu type give error in Internet Explorer
# Task #1755 -  Blank Notice on Add Tax Information
# Task #1778 - Converting cent to dollar amount

09.02.2008 gregdev
^ Set default for 'Show on shipping form' to 'No'
# Ensure that system user fields (sys=1) do not have their names changed.
# Row not added to #__vm_order_user_info when order is confirmed.

09.02.2008 soeren
# Operation Aborted Error in IE when browsing products + Lightbox'ed Links enabled
# Task #1745 - mod_virtuemart_allinone error
# Task #1702 -  When save Store image missing
# Task #1766 -  Account Order Details: Fatal Error in account.order_details.tpl.php on line 322

08.02.2008 gregdev
# Task #1425 - Changing User fields have no effect in frontend Shipping Addresses section
	!!! DATABASE: TABLE STRUCTURE CHANGE
	###
	ALTER TABLE `jos_vm_userfield` ADD `shipping` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `registration`;
	INSERT INTO `jos_vm_userfield` VALUES (NULL, 'address_type_name', '_PHPSHOP_USER_FORM_ADDRESS_LABEL', '', 'text', 32, 30, 1, 6, NULL, NULL, NULL, NULL, 1, 0, 1, 0, 0, 0, 1, 1, NULL);
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='company';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='first_name';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='last_name';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='middle_name';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='address_1';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='address_2';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='city';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='zip';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='country';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='state';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='phone_1';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='phone_2';
	UPDATE `jos_vm_userfield` SET `shipping`=1 WHERE `name`='fax';
	###
	
08.02.2008 soeren
^ Added getUserStateFromRequest Handler to vmMainframe
# preventing empty Orders (throws a critical error)
# Task #370 - Sorting Products by Price on shop.browse is wrong
^ Task #1039 -  Bug in adding new Product gui - discounts
^ Task #1377 - mark downloadable Order Items

07.02.2008 gregdev
# Task #1710 - Basic and Advanced component parameters (added config.xml to virtuemart.j15.xml)

07.02.2008 thepisu
# Updated Finnish lang files (translation by Mauri)
# Task #1735 Translation bug in admin product menu
# Task #1738 Translation bug in admin special products
# Task #1736 Translation bug in product atribute

06.02.2008 gregdev
+ Added the ability to set product, category, flypage, or page in a Joomla menu item
# Fixed missing slash in install (for loading sql sample data)
^ Suppressed error messages when extracting tar.gz files during installation
^ Cleaned up the component .xml files

06.02.2008 soeren
^ tax rate list contains 0% tax rate now by default
^ when deleting files from a product, the actual file is not removed from the server when it's used by a different product
# Task #1769 - Users, Shopper Groups not functioning as expected in front-end and admin (also: # Task #1752 - Bug in adding a user to a shopper group)
# Task #1746 - downloadable products are not published
# changed default admin "layout" from extended to standard
# fixed javascript errors in the file form (standard layout)

05.02.2008 soeren
# Task #1750 -  Two slashes on paths in install.php
# Task #1747 - Problems with apostrophe in product edit window
# Registration with automatic login wasn't working on Joomla! 1.0.14
^ not all "required" registration fields must be filled in by a storeadmin when adding a new user (just system fields like name/password/email are really required)
# Logged Errors were not displayed in Simple Layout

04.02.2008 soeren
+ added new registration field types for "Captcha" and Age Verification (using simple date drop-down lists)
	Captcha uses Walter Cedric's com_securityimages (http://www.waltercedric.com) and is only available if that component is installed!
	
04.02.2008 gregdev
# Task #1745 - mod_virtuemart_allinone error
+ Restored the special.png image for the all-in-one module
# Task #1741 - Error in mod_productscroller.php
^ Adjusted dates/versions inside module .xml files

01.02.2008 gregdev
^ Ability to choose featured (special) products only for product scroller (thanks Michel Beek!)
^ Added separate module builds for Joomla! 1.5 in the ant build script.
# Added Joomla! 1.5 .xml install files for the VirtueMart modules.
^ Made module names consistent and cleaned up the module descriptions.
# Task #763 - ScrollWidth does not effect Width of Productscroller /w Fix
# Task #1692 - Payment Method List in Admin panel not work

30.01.2008 soeren
# Task #1731 Category Thumb not displayed when dynamic thumbnail resizing is enabled
30.01.2008 gregdev
# Task #1733 -  Missing argument 2 for vmredirect()

29.01.2008 soeren
# Task #1727 - Layout error after installation
# Task #1725 - Frontend admin link error

28.01.2008 soeren
# Task #1725 - Frontend admin link error
# Admin Product List in Frontend didn't allow Price Management due to wrong URLs

27.01.2008 thepisu
# Changed peruvian currency (Peruvian Nuevo Sol)
# updated SQL files
# small fixes in languages

26.01.2008 gregdev
# Restore $VMVERSION fix (for install)
# Fixed Warning: mysql_real_escape_string() expects parameter 2 to be resource (compat.joomla1.5)
# Variable can't be assigned by reference (account.order_details)

25.01.2008 soeren
# Task #1717 - Security Token message when saving shipping address in backend administration
# Task #1715 - Publish/unpublish check mark buttons not working in IE, when clicked it give 404 error
# Task #1479 - Backend - Cancel shipping address takes user back to user list

25.01.2008 gregdev
# Fixed another undefined variable $VMVERSION warning (during install)
# Removed reference to non-variable for PHP4 (virtuemart_parser)

24.01.2008 gregdev
^ Allow access to download area from mod_virtuemart_login
# Task #1711 - User registration work is not complete
# Obtain language variable correctly
# Task #1676 - Class 'mosCommonHTML' error when trying to add parameters to product type
# Task #1652 - use of deprecated method mosCommonHTML::loadCalendar()
# Task #1701 - Ask a question about this product give error with legacy plugin off
^ Cleaned up the install (especially for Joomla! 1.5)
# Added missing logging constants to the default config file
# Fixed undefined variable $VMVERSION warning
# Use vmMail (instead of mosMail) when creating a user in VM backend

21.01.2008 mwmills
! @MWM1 used in source code comments.
+ Logging & Debugging enhancements: See new modules: classes/Log/LogInit.php & classes/DebugUtil.php for more information.
^ $vmLogger is now a composite logger. Use $vmDisplayLogger for display-only msgs, use $vmFileLogger for file-only msgs, and $vmLogger to send msgs to both display and file.
# Fixed a bug in classes/Log/display.php - referred to class 'Log' instead of 'vmLog'.
^ Added some formatting options to classes/Log/file.php
^ Changed "if (DEBUG == 1)" logic to use new vmShouldDebug() function (inside DebugUtil.php), which also checks if IP address-specific debugging output is enabled (so you can debug without affecting other customers.)
^ Changed admin.show_cfg.php to add new logging & debug-related configuration parameters.
^ Modified all language files in language/admin with ENGLISH versions of new logging & debug-related text strings. Translators need to convert strings to other languages.

21.01.2008 soeren
# double/triple VirtueMart Frames after being logged out and logging back into Joomla! and going back to VirtueMart (related to Tasks #1695 and #1696, but that was a Joomla! 1.0.13 issue)
# Pressing "Cancel" after Saving returns to standard Joomla! Admin Interface (Extended Layout only)
# Task #1690 - Bad filenames for Products and Categories with non-ASCII chars
# Task #1689 - Invalid Token during login in joomla! 1.5
21.01.2008 thepisu
# Task #1637 Missing VM_RECOVER_CART language string
# Task #1673 NotifyMe language bug
# added translations for various strings (in module 'common')

16.01.2008 soeren
# Task #1681 - Problem with Page Navigation - (1st page in product list)
15.01.2008 soeren
# Task #1675 - Currency module give fatal error
# Task #1674 - When no sort order is selected in Available "Sort-by" fields configuration, error is given
# additional File Upload not working correctly on Windows Systems
# Task #1659 - ß as attribute value
# Task #1669 - Browsing categorys end in redirect error
15.01.2007 thepisu
# Task #1667 Categorylist, OderList icon names
# Task #1671 Missing PHPSHOP_ADMIN_CFG_PRICES_INCLUDE_TAX

14.01.2008 soeren
^ updated modules for J! 1.5 (still don't install with disabled Legacy Mode)
^ Updated Currencies Module to work on J! 1.5 (shows a select list with all available currencies now)
14.01.2008 thepisu
# Task #1660 Replace PHPSHOP_ERROR message with real message
# Task #1663 CVS upload link in VM backend is redirected to a not found page
! modified Javascript handling menu items, if target is '_blank' or '_top' JS is ignored

13.01.2008 soeren
# Task #1665 - Class 'mosphpmailer' not found when trying to register user in Joomla 1.5
# Task #1664 - Empty page after last step in checkout
# Task #1661 - Missing argument 6 for vmMail() when trying to registration user in Joomla 1.0.13

11.01.2008 soeren
# Task #1655 - Call to undefined function mosmail() - user registration
# Task #1656 - Mambots not working in product description
# Task #1654 - PHP Task #27626 html_entity_decode bug
# Task #1658 - Module permission form error

10.01.2008 gregdev
# Task #508 System allow a end-date before the start-date for discount
10.01.2008 soeren
# Task #1653 - Call to a member function setQuery() error message

09.01.2008 thepisu
- removed no more used CSV-related language strings
09.01.2008 soeren
# fixed InputFilter Class forTask #1574 and Task #1581:  errors in HTML in product descriptipion
^ optimized query in dynamic_state_list function (not running a query for each country, but just one now)
- removed CSV Upload from VirtueMart Core (CSVImproved handles CSV Import/Export much better: http://csvimproved.com)
^ last called page is correctly remembered now (important for Administration) and loaded when returning to VM ADMIN
 
08.01.2008 gregdev
^ Set usertype in Joomla! 1.5 shopper registration (ps_shopper)
^ Native Joomla! 1.5 compatibility changes for mod_virtuemart
^ Change how we get $my in virtuemart_parser for Joomla! 1.5 (native and legacy support)

08.01.2008 thepisu
# Added translations for various strings (modules product/shop/store/zone)

08.01.2008 soeren
# Task #1591 - Add product in Modify Order incorrectly opens Order Status Change tab
# Task #1649 - htmlTools.class listFactory->newRow() not holding class, id or attributes
# Task #1648 - Incorrect Product Type parameter separator in product_type table
# Task #1647 - Double menu buttons are shown
# Task #1645 - Call to a member function loadBotGroup error when trying to browse product

07.01.2008 thepisu
# Added translations for various strings (modules admin/affiliate/checkout/help/order)

07.01.2008 soeren
# Toolbar not visible in Product Form Popup under Joomla! 1.5
# Task #1634 - Attribute name with äöüß not work
# Task #1638 - Layout Tabs not working in Internet Explorer
# Task #1640 - require_once virtuemart_parser.php in virtuemart.php
# Task #1643 - Error message in "Parameters of Product Type"
# Category won't save when no WYSIWYG Editor is enabled [http://forum.virtuemart.net/index.php?topic=35170.0]
- removed some Javascripts for a smaller component archive 
	removed: Scriptaculous, Behaviour, Lightbox(2 + "gone wild"), LiteBox, WindowJS

05.01.2008 soeren
# Task #1636 - Manage User Fields - mosHTML Class

03.01.2008 thepisu
+ Re-added some language strings previusly deleted.
# Removed extra comma in english common language.
# Various fixes in language files.

02.01.2008 soeren
# Task #1611 - Search with Keywords that contain quotes/apostrophes doesn't work
# fixed Email Receipt not being sent by Email Server because of violation of sender policy framework (thanks to Jens Kirk Foged from Sunburst WebConsult for reporting)
+ added protection against CSRF (using the parameter "vmtoken" to verify the 
	validity of a request that will execute a function through the parameter "func" in admin mode). 

02.01.2008 thepisu
^ Task #1268 Language strings modularization; a lot of strings moved + cleaning
# Task #1624 Admin area problem, caused by error in extlayout.js.php
  (JTable class was missing, added in compat.joomla1.5)
# Task #1620 Danish language file - corrupt charset (+ fixed all langs)

29.12.2007 gregdev
# Fixed "unable to add new discounts" (ps_product_discount)

22.12.2007 gregdev
+ Added vmArrayToInts() to replace mosArrayToInts() (ps_main)
# Use vmArrayToInts() instead of mosArrayToInts() (virtuemart_parser)
# Removed call to mosCommonHTML::loadOverlib(), cleanup, added javascript to open contact form (admin.user_form)
^ Register mosUser for autoloading until a better solution is found (compat.joomla1.5)
^ Fixed spelling error (english.php)
# Removed mosGetParam() for native Joomla! 1.5 compatibility (get_final_confirmation.tpl)
# Use VirtueMart's DATE_FORMAT_LC (ps_checkout, reviews.tpl)
# Removed CAN_SELECT_STATES (admin.user_address_form, account.shipto.tpl)

21.12.2007 gregdev
^ More changes for Joomla! 1.5 native compatibility.
^ Register mosMenuBar for autoloading.

21.12.2007 soeren
# Task #1619 - Unable to install VM 1099 in Joomla 1.5 RC4+
^ when modifying a product via Frontend Admin => Click "Edit" Icon, the user is returned to the site correctly now (index.php, not index2.php)
^ more changes for Joomla! 1.5 native compatibility. VirtueMart should now run without Legacy Mode.

19.12.2007 soeren
^ added "_JEXEC" to all file headers + more changes to achieve Joomla! 1.5 native integration
18.12.2007 soeren
+ added extended Search Mambot by Alejandro Kurczyn (one version for Joomla! 1.0 + Mambo, one native for Joomla! 1.5)

18.12.2007 thepisu
^ Task #1268 Language System Modularization (1st step - made structure and moved all strings to "common")
! build scripts changed to reflect new folder structure (languages/MODULE/LANGUAGE.php)
! actually no change needed for $VM_LANG->_() calls

17.12.2007 soeren
# fixed wrong queries in Sample Data SQL file

16.12.2007 soeren
# images of products with accented chars in their name weren't showing up on frontpage and product form

14.12.2007 gregdev
# Fixed typos in ps_export
# Fixed table creation for new product type
# Closing </table> tag in payment methods list
# Obtain _BACK string from VM_LANG (flypage-ask.tlp.php)
^ Moved advanced attributes select list to the template

12.12.2007 thepisu
# states list ordered by state name
# Task #1588 Can't edit group properties in Shopper Group List
# Task #1569 Multiple Prices and shopper group with % discount
  (shopper group percent discount was not working, also with single prices)

12.12.2007 soeren
^ Task #1582 - ps_session.php - checkSessionSavePath fails on custom session handlers
# Task #1594 - Apostrophe search word returns 0 results. 
	(search for products with a single or double quote is possible now)
^ the advanced search now can handle multiple keywords (separated by a space)
^ when the Product Search returns 1 product, the customer is redirected to the details page of that product instead
	of the search result overview
	 
07.12.2007
# Task #1589 - User registration error when Affiliate is enabled / can not browse shop
# Task #1320 Adding a "Print" button in order.order_printdetails (hiding print button from print output)

--- VirtueMart 1.1.0 beta2 released (05.12.2007, Rev. 1076) ---

05.12.2007 thepisu
# Task #1320 Adding a "Print" button in order.order_printdetails (small fixes, now working)

03.12.2007 soeren
# Task #1579 - Shipping Module Form "Cancel" shows second side menu
# Task #1578 - Read Only setting in manage user fields not working.
# Task #1577 - Child/sub category; when updated it becomes top-level category 
# Task #1576 - Search Function doesn't work anymore 

03.12.2007 thepisu
# tax % not displayed correctly
# vmTooltip image alignment (absmiddle)
# hardcoded strings in store edit form
+ added link to PHP strftime manual near to store date format

02.12.2007 thepisu
# Task #1571 - Some hard coded language strings in admin panel (payment classes)
# HTML entites should not be used in SELECT states list

02.12.2007 soeren
# Task #1574 - errors in product description
# Task #1573 - Bank account language tags missing

30.11.2007 soeren
# Task #1553 - Product with multi attribute only the first attribute is shown on frontpage
# moved manufacturer- and vendor-specific out of the product class
+ Print Icon on PopUp pages
# fixed PayPal SQL install error
28.11.2007 soeren
# Task #1565 - Manage User Fields unclick able.
27.11.2007 soeren
- Task #1559 - Customers can select a state/region? Not needed anymore
# user form submission not using Ajax
# fixed empty virtuemart Cookie under Joomla! 1.5

27.11.2007 thepisu
# Task #1547 - User activation link with Joomla! 1.5
# euro symbol not converted to html entity
# typo corrections
# Task #1360 - Hardcoded language in account.order_details.tpl.php

26.11.2007 thepisu
^ language variables are now globally called by using "_" function, like: $VM_LANG->_('MYSTRING')
! language variables must be called without starting "_" underscore; for example, $VM_LANG->_('MYSTRING') will call $_MYSTRING variable
! html entities are automatically converted in strings; to prevent it (example in javascript alert), use $VM_LANG->_('MYSTRING',false)
+ language function $VM_LANG->exists('MYSTRING'), return true if string exists in language file
+ charset definition in language file, used for htmlentities PHP function; now language file charset can be different from Joomla! charset
# some minor corrections
+ added function $ps_DB->getTableFields(array), for Joomla 1.5 compatibility (function not present in legacy plugin)

24.11.2007 soeren
! Known Issue: Redirection from https => http (if "generally prevent https" enabled) not working on Joomla! 1.5 currently,
because Joomla! 1.5 doesn't know a $mainframe->getCfg('live_site') value other than the currently requested URL
// TODO: make "URL" constant editable in the Shop Configuration (just like the SECUREURL value)
# fixed add-to-cart message (ajax response) on Joomla! 1.5
# Task #1560 - Error message in product scroller module

23.11.2007 soeren
# fixed Currency Selector Module configuration
# Task #1554 - Products in unpublished category are shown in search result
# Task #1552 - Call for pricing gives 404 not found error message
# Task #1550 - In backend Product list Manufacture column does not show other manufacturer's name
# Task #1549 - Deleting a state give 'Country ID could not be found' error
# Task #1547 - User activation link with Joomla! 1.5 (secondy try)

22.11.2007 thepisu
+ Task #1533 Add Spanish and Italian states in installation SQL
+ added states for Armenia, Iran, India
+ added currency (Armenian Dram)
^ state codes correction (2char codes were not unique) for Brazil, China, Romania (source: Wikipedia/ISO_3166-2)
^ Task #1537 state_3_code UNIQUE KEY ? - Changed unique keys for vm_states table
  for update please drop and re-add table; take SQL from "sql.update.VM-1.0.x_to_VM-1.1.0.sql", line 163-628
  (the ALTER command will not work because of duplicated 2char codes)
^ product.product_discount_form: popup calendar, updated for using vmCommonHTML::scriptTag and J1.5 compatibility
^ product.product_form: translated string "Search for Products or Categories here:"

21.11.2007 soeren
# Task #1548 - Class 'mm_InputFilter' not found
# Task #1547 - User activation link with Joomla! 1.5
# Task #1536 - Back to the country from state list not functioning properly

18.11.2007 soeren
# Task #1541 - Error during installation of com_virtuemart rev. 1039
# Task #1540 - virtuemart.cfg.php - offline message, try escaping single quotes with //'
# Task #1539 - empty thankyou page, direction to paypal nomore working after rev 1039

13.11.2007 soeren

+ added support for "REPLACE" queries to buildQuery function
# States weren't deleted on Country Deletion
^ changes most deprecated mos* function calls to separate vm* functions (VirtueMart's own functions)
	Examples: mosGetParam => vmGet, mosReadDirectory => vmReadDirectory, mosRedirect => vmRedirect
^ converted more UPDATE and INSERT queries to use the "new" buildQuery function

09.11.2007 soeren
# Task #1438 - Adding product not working in IE (it was due to the Tabs being rendered before the DOM was ready)
# Task #1530 - Add Attribute - empty save message popup window.

08.11.2007 thepisu
# in left menu, corrected forum link to new server 'forum.virtuemart.net'
# changed info text in the JS box when customer click on 'Notify Me' button (waiting list feature)
# calendar for availability date was not working in J1.5 (changed lang file to calendar-en-GB.js)
# added translation to 'Select Image' for availability images box, and to 'Control Panel' tab
# in availability images tip, corrected folder reference; now is taken from theme setting
# 'global $ps_product_type_parameter' not defined in product type form

07.11.2007 thepisu
# Fixed task #1372 - Hard coded language strings in zw_waiting_list.php (used sprintf for mail translation)
# added translations to strings in Product Form / Waiting List tab
# in Product Form / Waiting List tab, added user email and notify status; if user was not logged when requested notif,
  before only "()" was displayed, now it's possibile to see his email address

05.11.2007 thepisu
#  Fixed task #1510 - Order steps are not correct (using PHP 5.2.4, foreach and key() not compatibile)

05.11.2007 thepisu
# added translations to strings in VM toolbar / menu / lists (Publish, Unpublish, Please make a selection, ...)
# fixed typo

02.11.2007 soeren
# changing the ENCODE_KEY could lead to configuration file errors + wrong re-encryption of encrypted data 
# implemented changes to prevent saving a configuration file with wrong PHP syntax (escaping single quotes and stuff)
# Task #1522 - Lost every html-tag in store description!
^ implemented a workaround for problems with the "fetchscript.php" script, which loads javascripts and stylesheets. If it
	doesn't load the Ext Library in the backend, the user is redirected to the standard layout with direct javascript and
	stylesheet references

31.10.2007 soeren
# fixed a logout problem under J! 1.5 after checkout and on viewing order details 

31.10.2007 gregdev
# Fixed task #1443 - When in product list a product is selected and New product button in clicked error is given (on simple layout)
# Added country to the list of available variables in the address on the final checkout confirmation page

30.10.2007 gregdev
# Fixed task #1365 - Order Status not updated after successfull paying with PayPal
+ Joomla! 1.5 compatibility: Added Joomla! 1.5-specific user creation  in the VM backend.
# Fixed task #1519 - Error in Login Module. PHP 4 compatibility.
# Fixed payment method extra info being cut off

29.10.2007 gregdev
# Fixed task #1439 Creating new users on Joomla! 1.5 fails. Can now create/edit users in VM backend.
^ Added $startForm parameter to ps_userfield::listUserFields() to allow not printing the <form> tag
^ Joomla! 1.5 compatibility: PayPal notify.php changes for loading Joomla! configuration and session

27.10.2007 gregdev
# Joomla! 1.5 compatibility: fixed saving new user in frontend.
^ Adjusted registration complete message to reflect being automatically logged in.

26.10.2007 soeren
+ added new request class (from Joomla! 1.5 with small modifications) to have a CMS-independent request filter and 
	handler class

25.10.2007 gregdev
# Fixed task #1479 - Backend - Cancel shipping address takes user back to user list
+ Added "Remember me" to mod_virtuemart and mod_virtuemart_login. Uses VM_SHOW_REMEMBER_ME_BOX configuration setting. 

24.10.2007 gregdev
# Task #1415 - no account creation bug (prompt to enter user name with No Account registration option)
# Joomla! 1.5 compatibility: Fixed task #1508 - Two different Registration Complete messages in Joomla 1.5 (ps_shopper.php)

24.10.2007 soeren
^ stoeradmins/admins can access the shop even if it is in offline mode
+ added support for the dompdf PDF generation library (PHP5-only and not as good as the HTML2PDF class, but better GIF- and CSS-Support)

17.10.2007 soeren
# fixed next/previous product links in Print View
+ implemented "Notify Me!" modification by Corey, which shows a "Notify Me" button instead of "Add to Cart"
+ added new QUERY_STRING filter to better prevent XSS attacks using the query string

16.10.2007 soeren
# applied some fixes to the DHL shipping module/label printing function

13.10.2007 soeren
# Task #1468 - Can not send 'Recommend this product to a friend' email
 
11.10.2007 soeren
# Task #1431 - Advanced Search Result page direction
# Task #1465 - Quantity text still shown when box set to hide
# another fix to Task #1471 - Recommend this product to a friend formating lost if form not complete 
^ moved module-accompanying javascripts from /modules to components/com_virtuemart/js
# suppressed html_entity_decode error notices, because of unsupported charsets

11.10.2007 gregdev
# Joomla! 1.5 compatibility: more elegant fix for autoloading problem

10.10.2007 gregdev
# Joomla! 1.5 compatibility: legacy classes are now registered for autoloading

09.10.2007 soeren
^ removed "eval"s from the image processing function, fixed using the disableToggle function in the product form
# fixed Mambo 4.6.2 login issue (+CSV Upload Error) - thanks to Andr� ¹s

06.10.2007 gregdev
# Joomla! 1.5 compatibility: Set $my->gid

03.10.2007 gregdev
# Fixed missing $timestamp in order add immediately after checkout

02.10.2007 soeren
# fixed Internet Explorer "Operation aborted" error by outsourcing Layout Loading code into /js/extlayout.js.php
^ Updated ExtJS from v1.1 to v1.1.1 + fix for Tabs without Text in IE on Joomla! 1.5 in Standard Layout

01.10.2007 soeren
# fixed "Recommend to a friend" form
# fixed Coupon Discount Value not adjusted when adding products or updating product quantity

01.10.2007 gregdev
# Task #1469 - Changed toggler and stretcher code for update mootools (fixes checkout login/registration page accordian)

28.09.2007 soeren
^ Updated MooTools from v1.00 to v1.11 (+ adjustments)

27.09.2007 gregdev
# Joomla! 1.5 compatibility: Fixed $mosConfig_absolute_path problem in show_image_in_imgtag.php

26.09.2007 soeren
# Task #1444 - Wrong URL when using page navigation in Order information
# Task #1455 - confirmation mail --> Orderlink
# Task #1462 - Language strings missing in Recommend this product to a friend popup.
# Task #1463 - Wrong title in Shipping Module Form

20.09.2007 gregdev
# Renamed a string in the account.billing template
^ Added account maintenance link and login/logout redirection to the VirtueMart login module; added <br /> after pre-text.
# Task #1440 - Deleting a user a Joomla user that is not yet a VM user from the VM backend deletes the user

18.09.2007 soeren
# Error when adding a product with attributes using non-ASCII characters
# Task #1442 - When in product list a product is selected and New product button in clicked error is given
# Theme Stylesheet and JS not correctly loaded when using https
^ now a HTTPS redirect is done in the admin section if the module is forced to use https (Joomla! 1.5 only)
+ added a new configuration key that allows to change the encryption function for encrypting sensible data in the database
	You now can switch to the much safer "AES_ENCRYPT" if your MySQL Server Version is >= 4.0.2
!	This means an important change for all payment modules, which rely on transaction keys from the 
	payment_method table (payment_passkey). Instead of using "ENCODE" or "DECODE" in the queries,
	from now on you must use the constants "VM_ENCRYPT_FUNCTION" and "VM_DECRYPT_FUNCTION".
	Example: $database->query( "SELECT ".VM_DECRYPT_FUNCTION."(payment_passkey,'".ENCODE_KEY."') as passkey FROM #__{vm}_payment_method ..." );
^ changed the vmIsJoomla Function to accept comparison operators
# fixed Transaction Key Change functionality for Joomla! 1.0.13

14.09.2007 gregdev
^ Joomla! 1.5 compatibility: fixed Joomla! pathways
^ Adjusted internal VirtueMart pathways (account maintenance, shop.browse, shop.product_details)
^ Added pathway handling functions in vmMainFrame class
^ Added ps_product_category->getPathway function (supports creating the category URLs)

14.09.2007 soeren
# Task #1441 - In extended layout view when saving shipping module, save message is populated by shipping module
# Task #1438 - Adding product not working in IE ("Operation aborted" Error in IE when loading the product form)

12.09.2007 gregdev
+ Added a separate VirtueMart login module.
^ Joomla! 1.5 compatibility: fixed password check on payment methods
# Fixed missing global $mosConfig_allowUserRegistration in VirtueMart login module
^ Joomla! 1.5 compatibility: Added 'Forgot your password' option to the VirtueMart module
^ Joomla! 1.5 compatibility: login/registration forms
^ Removed the login form from the the shop.registration
^ Moved logic from the template (login_form.tpl.php) to checkout.login_form.php

10.09.2007 gregdev
^ Joomla! 1.5 compatibility: load compat file in virtuemart_parser (for use in modules, etc); added global user registration settings
# Joomla! 1.5 compatibility: Task #1423 - Fixed login/logout from mod_virtuemart.
^ Joomla! 1.5 compatibility: tigratree change
# Joomla! 1.5 compatibility: Task #1427 - Selecting All in shop.browse results in no records

06.09.2007 macallf
+ Added autofill of user name and email address for logged in user when using email to a friend.
^ Added index2.php to the available pages for adding a stylesheet in function addStyleSheet - mainframe.class.php 

06.09.2007 gregdev
# Joomla! 1.5 compatibility: Task #1419 - adjusted mosConfig_cachepath

06.09.2007 macallf
# Task #1386 implemented page navigation at product level. Corrected get_neighbour_product in ps_product.php

06.09.2007 macallf
# Task #1389 Saved cart reappears after checkout. ps_checkout.php edited to delete saved cart.
# Delete saved cart pointed to wrong function. sql.virtuemart.php Corrected with the correct functio name.

05.09.2007 gregdev
# Joomla! 1.5 compatibility: Task #1410 - initialize editor correctly for front-end admin
# Task #1411 - changed to use string from Virtuemart language file

05.09.2007 macallf
# Task #1400 ps_cart.php fixed to display tip when adding 0 products to the cart using childlist

04.09.2007 gregdev
^ Added DHL shipping method strings to the language files (thanks to aravot!)
# Fixed blank page after payment confirmation (corrected the LEFT JOIN)
# Fixed terms of service checkbox layout

03.09.2007 gregdev
# Task #1387 - admin.theme_config_list.php missing
# Fixed hardcoded strings in admin.show_cfg.php and admin.theme_config_form.php.
^ Joomla! 1.5 compatibility: Added $ps_product to list of globals in virtuemart_parser.php
^ Joomla! 1.5 compatibility: change in the compatibility file
^ Joomla! 1.5 compatibility: tigratree template change to support new JApplication structure

29.08.2007 gregdev
# Added Shipping module language variables

25.08.2007 soeren
# Task #1357 - Performance problems creating new products
# Task #1394 - Change of heading level required in get_shipping_method.tpl.php

23.08.2007 gregdev
^ Joomla! 1.5 compatibility: change in the compatability file
^ Joomla! 1.5 compatibility: PayPal IPN script updated (notify.php)

17.08.2007 gregdev
^ Use month names and _DO_LOGIN from VirtueMart language file.

01.08.2007 gregdev
+ Added a cleared <br /> element so that the floated divs fill the container
 
30.07.2007 gregdev
^ Joomla! 1.5 compatibility: TigraTree product category module
^ Adjustments to Joomla! 1.5 compatibility file
^ Joomla! 1.5 compatibility: Set local version of $CURRENCY_DISPLAY in global.php
+ Get $keyword from the $_REQUEST before cleaning it (virtuemart_parser.php)
+ Joomla! 1.5 compatibility: Added $_VERSION to globals in shop.debug.php

27.07.2007 gregdev
^ Adjustments to Joomla! 1.5 compatibility file.
^ Changes in modules for Joomla! 1.5 compatibility; added string to language file.
# Removed debug code in shop.basket_short.php.

25.07.2007 soeren

^ Task #1311 - Dates in order_print / order_printdetails not localized
!!! DATABASE: TABLE STRUCTURE CHANGE
		###
		# 25.07.2007: Allow to set address and date format
		ALTER TABLE `jos_vm_vendor` 
				ADD `vendor_address_format` TEXT NOT NULL ,
				ADD `vendor_date_format` VARCHAR( 255 ) NOT NULL;
		UPDATE `jos_vm_vendor` SET
			`vendor_address_format` = '{storename}\n{address_1}\n{address_2}\n{city}, {zip}',
			`vendor_date_format` = '%A, %d %B %Y %H:%M'
			WHERE vendor_id=1;
		###
+ Global Address Format can be set in the Store Form now - as well as the global date format
# Task #1356 - problems with "implemented page navigation at product level"

24.07.2007 soeren 

# Task #1344 - related product list too long, memory exausted
^ improved the related products selection screen - it features an auto-completing search field now
+ added new JSON class to send JSON encoded responses
# fixes for Joomla! 1.0.13 compatibility
^ changed Product Review List to show reviews from all products - ordered by posting time,  
	not only filtered by one product; TODO: Notification of 

16.07.2007 gregdev
# Task #1328 - long php opening tags missing in vendor.vendor_form.php

06.07.2007 gregdev
# Check for set $_REQUEST entries before resetting values

05.07.2007 gregdev
# Corrected filename error in usps.ini

# Corrected PHP short tags in USPS shipping module

25.06.2007 soeren

^ Updated the USPS Shipping module to version 3.0 (thank you Corey!!)

20.06.2007 soeren
# integrated patches to ExtJS for Konqueror Compatibility
# Task #1306 - Page Navigation doesn't work after switching off the display at the top of the prod.listing frontend
# fixed non-array error in mod_virtuemart_currencies.php

19.06.2007 soeren
# Task #1297 - Coupon discount total does not adjust after removing item from cart (basket.php, ps_cart.php)
# Task #1299 - Credit card number accepts a string as valid (ps_payment_method.php)
# Task #1319 - Lockup issue with permissions on browse_* files. (ps_main.php)

13.06.2007 soeren

# Task #1316 - When deleting orders, records in 'order_history' and 'order_user_info' are not deleted (ps_order.php)

05.05.2007 gregdev
# Fixed DEFAULT value for product_id (#__{vm}_product_reviews) in sql installation files.
^ Use _PN_DISPLAY_NR from VirtueMart language strings.
  
03.05.2007 gregdev
# Task #966 - Cleared credit card info when using non-credit card payment method.
# Fixed a text size bug in the product scroller.

03.05.2007 soeren
+ new configuration parameter: VM_STORE_CREDITCARD_DATA (default=1), the store owner can choose wether credit card information shall be stored encrypted in the database or not
# authorize.net: Test Mode didn't work. The host test.authorize.net is not used anymore. VM will use a POST var instead to indicate a test request.
# authorize.net: Response Codes were not correctly recognized due to a wrong setting of the encapsulation character for the response string.

02.05.2007 soeren

# Task #1280 - Checkout Bar Wrong URL

27.04.2007 soeren
# Task #1273 - Error in creation of HTML confimation Email if more than a specific amount of products was ordered
# Task #1272 - Error in product attributes with attribute depending price modifier
+ all downloads from the shop can be paused and resumed now (this is extremely useful when downloading bigger files)

24.04.2007 gregdev
# Fixed task #1264 - changed error reporting to use vmLogger; changed notification to use vmMail.
# Fixed incompatibility for PHP 4.x with complex quoted string.

23.04.2007 macallf
# Added multiple prices to price table
+ Recently viewed products
+ Featured products on shop.index
^ shop.index.php changed to template system
+ added new functions 
!!! Database Table - New Entries
    Database table jos_vm_functions
    (187, 7, 'replaceSavedCart', 'ps_cart', 'replaceCart', 'Replace cart with saved cart', 'none'),
    (188, 7, 'mergeSavedCart', 'ps_cart', 'mergeSaved', 'Merge saved cart with cart', 'none'),
    (189, 7, 'deleteSavedCart', 'ps_cart', 'deleteSaved', 'Delete saved cart', 'none'),
    (190, 7, 'savedCartDelete', 'ps_cart', 'deleteSaved', 'Delete items from saved cart', 'none'),
    (191, 7, 'savedCartUpdate', 'ps_cart', 'updateSaved', 'Update saved cart items', 'none');" );
^ saved cart, now more comprehensive

16.04.2007 macallf
# Fixed task 1265: uninstall doesn't drop all tables.

14.04.2007 macallf
# Fixed task 1261: navigation pathway only showing last category.

12.04.2007 soeren

+ added a new table "jos_vm_cart" to store the contents of the cart of logged-in users
!!! DATABASE STRUCTURE CHANGED !!!
	### Permanently store the cart contents for registered users
	CREATE TABLE `jos_vm_cart` (
	`user_id` INT( 11 ) NOT NULL ,
	`cart_content` TEXT NOT NULL ,
	`last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	PRIMARY KEY ( `user_id` )
	) TYPE = MYISAM COMMENT = 'Stores the cart contents of a user';
	###

	
11.04.2007 soeren
^ updated ExtJS from 1.0 alpha3 to 1.0 beta2

10.04.2007 soeren

+ added a filtering option to the related product select list, so searching for products is easier now

05.04.2007 soeren

^ changed "related products" form in product form to use Option Tansfer from one select list to another
	for better overview what was selected as related (using OptionTransfer.js by Matt Kruse - mattkruse.com/javascript/optiontransfer )
	You can even use double click to move products from one list to the other
	
03.04.2007 soeren

+ added page navigation on product level, so customers can go directly from one product to the next in that category/manufacturer/search result
^ product details page automatically grabs the Flypage of the category of the product if the flypage parameter was omitted from the URL


30.03.2007 gregdev
^ Changed shop_browse_queries.php to use a LEFT JOIN for #__{vm}_shopper_vendor_xref (fixes empty categories when table entry is missing).

28.03.2007 gregdev
# Fixed task #1212: ship_to_info_id and shipping_rate_id were not being passed to the template.

16.03.2007 gregdev
^ Improved the FedEx shipping module.

15.03.2007 soeren

# JoomFish language setting is overwritten in virtuemart.cfg.php
# CSV Upload not recognising correct Mime Type due to case-sensitive equality check
+ added a Feed Icon to the category name in the browse page (can be toggled on/off in theme configuration)
+ added Product Syndication system that allows to provide "Product Feeds" to customers
	The URL for the feed is "index.php?option=com_virtuemart&page=shop.feed". A category_id
	can be specified in the URL to filter the syndicated products by a certain category

13.03.2007 soeren

# Task #1187 - Virtuemart does not redirect correctly if only 1 payment option is available. (ps_checkout.php)
# Task #1200 - checkout.thankyou shows empty page when order_total is 0 (checkout.thankyou.php)

11.03.2007 soeren
^ changed the product list price form to use nice MessageBoxes from ExtJS
^ changed from YUI-EXT 0.33 to new ExtJS (1.0 alpha3 Rev 1, by same author)

08.03.2007 soeren

# Prevention for negative cart values upon using a constant value coupon (ps_coupon.php, http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=20840.msg51002#msg51002)

03.03.2007 gregdev
+ Added Tigra Tree category menu

01.03.2007 gregdev
^ optimized category tree creation
 
26.02.2007 gregdev

^ moved account.billing, account.orders, and account.shipping into templates
# changed ps_shopper->update to return to $page on error, rather than just checkout.index 

23.02.2007 soeren

^ updated YUI to version 2.2.0
^ updated Scriptaculous to version 1.7.0
^ updated Prototype to version 1.5.0
# changing the ENCODE_KEY would re-encode the encrpyted data even if writing the configuration file fails and the old ENCODE_KEY is NOT changed due to missing file permissions
^ moved Login/Registration Form during Checkout into a template
# submit_form() is not defined on last step in checkout
^ moved Shipping Address form into template

--- VirtueMart 1.1.0 beta1 released (21.02.2007, Rev. 692) ---

21.02.2007 soeren
# mosproductsnap - Fatal Error (only variables should be passed by reference)
+ added "featured=y" and "discounted=y" parameters for the browse page to allow to filter by featured or discounted products

19.02.2007 soeren
# Task #1147 - shop.parameter_search_form.php error with template...
# Task #1161 - Updated PS_Linkpoint should be included in future releases
# Task #1160 - Registration - Empty state list + Fix (ps_html.php)
# Task #1150 - vmcchk=1 breaks SEO URL
# fixed the Product Enquiry Form and split it up into code and template (+added missing language tokens)

16.02.2007 soeren
+ added an algorithm to re-encode encrypted cc numbers and passkeys when the ENCODE_KEY is changed
# fixed the currency converter module to reset the selected alternative currency and return the correct amount
	when failing to retrieve the currency conversion table
+ created a new "vmMainFrame" class to handle stylesheets and scripts and bundle them for "fetchscript.php"
	This way we can remarkably reduce the number of GET Requests for linked scripts and stylesheets
	An instance of the vmMainFrame class is available globally: $vm_mainframe.
	
^ changed the simple attributes' price modifier handling from user-submitted prices to price modifiers retrieved from
	the product's attribute field in the DB. So the [+3.99] price modifiers are not longer part of the
	drop down list, but just the attribute values like "red" or "big".

13.02.2007 soeren

# several fixes for making VirtueMart work with the latest Joomla! 1.5 SVN version
+ implemented new User Registration Types: "Normal Account Creation", "Silent Account Creation", "Optional Account Creation" and "No Account Creation"
	This allows a customer to check out without the need to create an account
	
# fixed the vmcheck redirection not being SEF issue (ps_session.php)
# fixed the user field form and made it compliant to MooTools v1.00


11.02.2007 soeren

^ added input filter ("process" and "safeSQL") to all REQUEST variables when user is no admin or storeadmin
+ added an INT Cast to all variables that can't have other value types than INT or ARRAY(INT)


07.02.2007 soeren

+ added a configuration variable to enable and disable the cookie check (it seems not to be very search-engine friendly)

05.02.2007 soeren
^! completely revised the Checkout Process (WIP!)
	* created templates for all checkout stages
	* allowed to bundle steps to a stage (e.g. ShipTo and Shipping Method or all steps on the same page)
	* removed "CHECKOUT_STYLE" configuration constant, added a new configuration array "VM_CHECKOUT_MODULES"
	* moved customer_info, listing shipping methods, listing payment methods to function inside ps_checkout
		that use templates from the "/templates/checkout" folder
	* fixed the cartUpdate forms in the basket (works now and is standards compliant)
	* jumping between "checkout stages" is possible by using the parameter "checkout_stage".
	
^ added FXX, ROM and BUL to the list of European Countries (function country_in_eu_common_vat_zone, ps_checkout.php)
# fixed some issues with the new mootools and the cart highlighting function
^ Updated Mootools to release v1.00
^ Updated SlimBox to version 1.3

31.01.2007 soeren
# various XHTML standards compliance fixes 
	* added ampReplace function to URL functions in ps_session.php, plus new parameter: encodeAmpersands (default:true) )
	* fixed various wrong tags, missing closing tags and unencoded ampersands
	
30.01.2007 soeren
+ added a new PayFlow Pro class that doesn't need the Payflow Pro SDK installed on the server
# fixed an error that prevented correct storage of the CC number

28.01.2007 soeren
+ added new functions to resend the Download ID and re-enable expired or max-downloaded downloads
! two new function have been added to the function list: insertDownloadsForProduct and mailDownloadId
	####		
	INSERT INTO `jos_vm_function` (`function_id`, `module_id`, `function_name`, `function_class`, `function_method`, `function_description`, `function_perms`) VALUES (185, 2, 'insertDownloadsForProduct', 'ps_order', 'insert_downloads_for_product', '', 'admin'),
	(186, 5, 'mailDownloadId', 'ps_order', 'mail_download_id', '', 'storeadmin,admin');
	####
	
26.01.2007 soeren
# UPS: renamed "UPS Express Saver" to "UPS Saver"
# UPS: merged Deneb's improvements for the UPS module to the trunk
# product changed type parameters subtab at product.froduct_form (thanks Steelrat)

26.01.2007 eaxs
# YUI-EXT stylesheet not displaying Tab Text in IE7
^ some improvements to the "advanced attributes" javascript and system


19.01.2007 soeren
! two new function have been added to the function list: setModulePermissions and setFunctionPermissions
	####
	INSERT INTO `jos_vm_function` (`function_id`, `module_id`, `function_name`, `function_class`, `function_method`, `function_description`, `function_perms`) 
	VALUES (null, 1, 'setModulePermissions', 'ps_module', 'update_permissions', '', 'admin'),
	(null, 1, 'setFunctionPermissions', 'ps_function', 'update_permissions', '', 'admin');	
	####
	
+ added a function <=> user group matrix to the function list, so access restrictions can quickly be changed
+ added a module <=> user group matrix to the module list, so access restrictions can quickly be changed
^ changed the input field "Force HTTPS on which modules?" in the configuration to a multi-select list with all module listed

16.01.2007 soeren
# Task #1100 - Make Manufacturers module work on "Select -> xx" rather than having to click [Search] button (mod_virtuemart_manufacturers.php)
# fixed an XSS vulnerability (ps_cart.php)
# Task #1084 - Memory eating loop when non-available fetching remote files (ps_product_files.php)

12.01.2007 soeren
# updated the YUI library to version 0.12.2
# Fixed the thumbnail creation and naming according to Fedor's post: http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=24388.msg66188#msg66188


04.01.2007 gregdev
# Fixed check for authorize.net test mode (ps_authorize.php).

19.12.2006 soeren
^ updated the GreyBox script from version 3.45 to 5.16 (check it out: http://orangoo.com/labs/GreyBox/)

12.12.2006 gregdev

^ Added line to virtuemart.xml for the new favicon.ico file.

11.12.2006 soeren

+ added the order edit extension by nfischer, nico and rolf: http://virtuemart.net/index.php?option=com_flyspray&Itemid=91&do=details&task_id=27
	It allows to modify orders and order items after the order has been placed.
	
09.12.2006 soeren
# Task #1045 - ps_product_category::get_navigation_list cannot be called twice! (ps_product_category.php)
# Task #1040 - Redirect after registration (ps_shopper.php)
- removed the PayFlow Pro payment class, it can be downloaded including the necessary SDK from virtuemart.net

07.12.2006 gregdev

^ Added values (all NULL) to sample data install queries for new child-products fields.


02.12.2006 gregdev

# Task #988 - fixed path to noimage file; also changed to use VM_THEMEURL for availability images (product.product_form.php)

01.12.2006 gregdev

# Change css class formField to match formLabel (theme.css)
# Use proper pathway_separator function (account.order_details)

01.12.2006 soeren

# Task #1035 - Sorry, but the Product you\'ve requested wasn\'t found! (shop.product_details.php)
# Task #1012 - Manufacturers in Manufacturer Module List not Alpha sorted

29.11.2006 gregdev

# Adjusted so that updating an existing shipping address does not require a new address name (ps_user_address.php)
# Task #842 - fixed preselected country when editing an existing shipping address  (account.shipto.php)
# Adjusted add and update functions to save billing info for new users and Joomla only (not yet VM) users (ps_user.php)
# Adjustments to account.billing, account.shipping, acount.shipto files to use proper pathway_separator function.

27.11.2006 soeren

# Task #1011 - Cancelled Products get added to Top Ten Module (ps_order.php)

24.11.2006 soeren
# Task #1027 - Error in stock handling (ps_checkout.php)
# Task #1015 - Pathway duplicated in account.billing, account.shipping, account.shipto

23.11.2006 soeren
# Task #1014 - Authorize.net test mode error
+ added pathways and pagetitles to various pages
^ moved the function ps_product_category::pathway_separator() to the vmCommonHTML class, call it by using vmCommonHTML::pathway_separator() now!

17.11.2006 soeren

# cleaned up some old deprecated constants and language tokens
+ added extended javascript-based "simple attribute" handler by Tobias (alias eaxs, http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=22445.0)


16.11.2006 markcallf

!! DATABASE STRUCTURE CHANGED !!!
	# Marks Child list options
	ALTER TABLE `jos_vm_product` ADD `child_options` varchar(45) default NULL;
	ALTER TABLE `jos_vm_product` ADD `quantity_options` varchar(45) default NULL;
	ALTER TABLE `jos_vm_product` ADD  `child_option_ids` varchar(45) default NULL;
	ALTER TABLE `jos_vm_product` ADD  `product_order_levels` varchar(45) default NULL;
+ added child product list options


10.11.2006 gregdev

#  Fixed duplicate error message when no shipping address is chosen during checkout (Task #972).

08.11.2006 soeren

!! Database Structure changed !!
	###########################
	# Making User Groups dynamic
	###########################
	CREATE TABLE `jos_vm_auth_group` (
	  `group_id` int(11) NOT NULL auto_increment,
	  `group_name` varchar(128) default NULL,
	  `group_level` int(11) default NULL,
	  PRIMARY KEY  (`group_id`)
	) TYPE=MyISAM AUTO_INCREMENT=5 COMMENT='Holds all the user groups' ;
	# these are the default user groups
	INSERT INTO `jos_vm_auth_group` (`group_id`, `group_name`, `group_level`) VALUES (1, 'admin', 0),(2, 'storeadmin', 250),(3, 'shopper', 500),(4, 'demo', 750);
		
	CREATE TABLE `jos_vm_auth_user_group` (
	  `user_id` int(11) NOT NULL default '0',
	  `group_id` int(11) default NULL,
	  PRIMARY KEY  (`user_id`)
	) TYPE=MyISAM COMMENT='Maps the user to user groups';
	INSERT INTO `jos_vm_function` VALUES 
		(NULL, 1, 'usergroupAdd', 'usergroup.class', 'add', 'Add a new user group', 'admin'),
		(NULL, 1, 'usergroupUpdate', 'usergroup.class', 'update', 'Update an user group', 'admin'),
		(NULL, 1, 'usergroupDelete', 'usergroup.class', 'delete', 'Delete an user group', 'admin');
		
+ new user group management (admin.usergroup_form.php, admin.usergroup_list.php)
	
06.11.2006 soeren

# fixed the function form to work with the prototype ajax object
+ coupon code used for the order is stored now and displayed in the admin order details listing
!! DATABASE STRUCTURE CHANGED !!
	# adding coupon code tracking for orders
	ALTER TABLE `jos_vm_orders` ADD `coupon_code` VARCHAR( 32 ) NULL AFTER `coupon_discount` ;
	
# fixed a bug which prevented ordering in product list
^ coloured the editable price fields in the product list: added a CSS class "editable" to the admin.styles.css
^ merged the CSV improvements by RolandH into the CSV files

30.10.2006 soeren

# no title tag displayed for empty categories (shop.browse.php)


27.10.2006 soeren

+ re-integrated the "mini cart" ajax updater on any cart event
^ moved /js/vmAjax.js to /themes/default/theme.js

24-10-2006 soeren

^ moved the deprecated Mambo 4.5.x/Joomla 1.0.x language constants to the language files
+ cart action notices are put into the language files now
# added a header "Content-type: " to the connectiontools class to allow correct character encoding 
	when sending ajaxed content
^ changed the "lightbox" message-windows to these new prototype Windows
^ changed most Ajax-based functions to use Prototype
+ added WindowJS javascript functions: http://prototype-window.xilinus.com/index.html
	these windows look great and work better than the LightBox Windows,
	they can even use effects/animation from scriptaculous
+ added MooTools javascripts
- removed Moo.Fx javascripts
# bug in vmCommonHTML::parseContentByMambots, returns an empty text when this feature is turned off

18-10-2006 gregdev

#  Task #959 — Virtuemart search bot not working properly

17-10-2006 gregdev

#  Task #969 — order_id error in Dutch language file VM vs. 1.0.7
#  Task #973 — Error in mod_product_categories
!# fixed various non-critical XSS vulnerabilities

13-10-2006 gregdev

!# fixed various non-critical XSS vulnerabilities

04-10-2006 gregdev

#  Task #962 — skip_fields not initialized in checkout_register_form.php
#  Task #978 — PHP Short-Tag used in ps_paypal.php
!# fixed various non-critical XSS vulnerabilities QUERY_STRING and shopItemid

02-10-2006 soeren

^ various changes for Joomla! 1.5 compatibility
!# fixed various non-critical XSS vulnerabilities though Itemid parameter

13-09-2006 soeren

+ added the user field type "euvatid", you can now publish such a field and assign users
	who provide a valid EU VAT ID into a different shopper group (than default)
^ the order status codes 'P' (Pending), 'C' (Confirmed) and 'X' (Cancelled) have been declared as "protected order status codes". The code can't be changed or deleted (but the order status name can still be changed, of course!)

+ added an order status description field to the order status form
!!! Database Structure Changed !!!
	######
	# 13.09.2006 Allow Order Status Descriptions
	ALTER TABLE `jos_vm_order_status` ADD `order_status_description` TEXT NOT NULL AFTER `order_status_name`;
	######
	
	
12-09-2006 soeren

!! Small Database Change: Changed an "INDEX" Key to a "PRIMARY" Key in the table jos_vm_category_xref
	# http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=21452.msg53368#msg53368
	# 12.09.2006 improve category listing performance
	ALTER TABLE `jos_vm_category_xref` DROP INDEX `category_xref_category_child_id` ;
	ALTER TABLE `jos_vm_category_xref` ADD PRIMARY KEY ( `category_child_id` ) ;
		


05-09-2006 soeren

# state list not updating when country selection changed
^ user permission groups are listed in a multi-select box now (function_form and module_form)
^ core function form enhancements: 
	* all available class are listed in a drop-down list
	* function method list is fetched dynamically using ajax, so all available methods of the selected class are listed


03-09-2006 soeren

# Problem downloading larger files, e.g. >16MB (ps_main.php) (http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=20481.msg53015#msg53015)

02-09-2006 gregdev
# Task #938 - Product list select statement causes MySql out of memory error
# Task #734 - transmenu.php wrong itemid in a first menu level
# Task #933 - Reports fail with RG_EMULATION=0
# Task #870 - Wrong template used for Order Status Change link (ps_order.php)
# Task #868 - missing pathway's style class in Account Maintenance (account.billing.php, account.order_details.php, account.shipto.php, account.shipping.php)
# Task #867 - errors in german language-file
# Task #861 - Control panel when press any button on frontend administration are not displayed. (reportbasic.index.php)

31-08-2006 soeren

^ switched from Behaviour JS to moo.dom to attach events to various elements (http://www.mad4milk.net/entry/moo.dom-easily-target-html-elements)
		(it is much much much smaller by filesize!!)
+ made the usage of the Lightbox for product images optional (see theme configuration!)
+ made the Greybox checkout optional (see theme configuration!)
+ added the LiteBox script to the available Javascripts. 
	Litebox is a lightweight Lightbox derivate using just moo.fx and prototype.lite (see http://www.doknowevil.net/litebox/)

# Task #887 - Minimum Amount for Free Shipping (ps_main.php)
^ EU tax mode implementation by Sam Morris <sam@robots.org.uk>
	(http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=21124.msg52587#msg52587)
	affected files: ps_checkout.php, ps_product.php, basket.php, admin.show_cfg.php, all language files
# possible errors in tax total calculation when coupons are used in vendor-based tax mode

29-08-2006 soeren

# Task #901 - FileManager's pics > Commas in Tittle bug.
# Task #735 - attributes errors (ps_product.php) - (double currency symbols and price modifiers not adding up when one "price setter" is selected in the attributes)
# Task #839 - "Add to Cart" twice for same product removes product (ps_cart.php)
+ added cache-control / expire / last-modified headers in fetchscript.php and show_image_in_imgtag.php to 
	increase performance by using client caching capabilities
	
^ updated the vmnValidateEmail function to check for correct email addresses (ps_main.php)
+ added name & subject checks for email sending (J! 1.0.11) (ps_main.php)
^ changed the vmSpoofValue function to work with J! 1.0.11 (ps_main.php)

25-08-2006 soeren
# fixed hidden select boxes on "display lightbox"  staying hidden

^ moved a lot of global declarations from virtuemart_parser.php to global.php (what do we have this file for if not for globals ;-) ?)
+ added global variable $vmDir for being able to track different installations of VM in the same Joomla installation
	(this is to be implemented laaaaater on)
+ added function writeThemeConfig to SQL installation/update scripts

22-08-2006 soeren

^ moved /html/coupon.coupon_field.php to /templates/common/couponField.tpl.php
^ products that are already in the cart are increased in quantity now

+ added a PHP script called "fetchscript" that allows us to send gzip-compressed javascripts and stylesheets (when gzip = 1)
	All new Javascripts and Stylesheets are called using fetchscript.php now.
+ added Lighbox2 image links to Flypage + "more images"
+ added waiting list to product form, the storeadmin can decide to notify users about the stock level change or not.

14-08-2006 soeren

+ finished feature to allow customers order in a different currency
# Task #804 - On status change text showing 'rn' instead of CR (ps_order.php)

02-08-2006 soeren

+ template files for the product rating and review part
+ added theme configuration, based on the mosParameters specification
	Themes have a configuration file now: theme.config.php.

27-07-2006 soeren

# Task #850 - Order list not showing all orders

^ moved the functions "validate_image" and "process_images" from the ps_main.php to the new
	class file "imageTools.class.php", class "vmImageTools"
	
25-07-2006 soeren

^ started working on Theming support for VirtueMart. the first steps were
	* created a new directory "components/com_virtuemart/themes" with a "default" theme
	  for the start. Each theme has its own subdirectory with separate directories css, templates and images
	* themes hold a central CSS file called "theme.css", images for "checkout", "availability" and "stars" (more to follow)
	* the file admin.css controls the look of admin styles, mainly used for frontend administration
	* themes can be switched in the shop configuration -
	* the URL and path of the selected theme is stored in two new configuration constants called
		VM_THEMEURL and VM_THEMEPATH
	* all the "template files" have been moved from "administrator/components/com_virtuemart/html/templates" to "components/com_virtuemart/themes/default/templates" where they have the same dir structure as before
	* references from the old image URLs to the new theme-based image URLs have be updated

+ Content Mambots can be used now to parse product and category descriptions 
	=> new configuration constant "VM_CONTENT_PLUGINS_ENABLE"; default: disabled
	
^ Bank account information is only requested now at the "payment method selection" step
	Removed the global configuration switch
^ changed all text input fields for template names (like "shop.flypage") to dropdown lists
	where you can select the right template file.
^ changed the "payment class" input field to a dropdown list where you can select one of the 
	available payment method classes
	
+ added a new directory "currency" for holding different currency converter modules
	the globally used converter is controlled by the constant "VM_CURRENCY_CONVERTER_MODULE"
	the default setting is "convertECB"

22-07-2006 soeren

+ added a workaround for installations where the "Session Save Path" is not writable. 
	VM will try using the global cache path for storing session files instead.

18-07-2006 soeren

# various stability fixes to the "Shared SSL"-Redirect functions.
	It's now possible to jump from https to http and back without loosing
	session information (=cart and login)

28-06-2006 soeren
# Task #780 - VM don't send the confirmation order to user or admin, update status order don't run (ps_affiliate.php)
# Task #817 - relative url is missing server base (ps_product_attribute.php)
# 2Checkout order_total number format corrected
# Task #814 - mysql_escape_string issues (class.inputfilter.php, htmltools.class.php)
# Task #816 - missing "alt" attribute in category images on shop.index.php
^ adjusted login procedure to comply with Joomla 1.0.10 (ps_main.php, checkout.login_form, mod_virtuemart.php)
	+ added new functions called "vmSpoofValue" and "vmSpoofCheck" as used in Joomla 1.0.10
	
22-06-2006 soeren

^ Product Scroller now scrolls left and right with all the products in 1 row


07-06-2006 soeren

# "only variables should be assigned by reference..." errors in the file menuBar.class.php

04-05-2006 soeren

^ featured products module now accepts more than one category ID (comma-separated list possible), thanks to Ben (deneb!)
^ featured products module now randomly sorting featured products

02-05-2006 soeren

! DATABASE STRUCTURE CHANGED: table 'jos_vm_vendor' gets a new field !
	# 02.05.2006 Multi-Currency Feature
	ALTER TABLE `jos_vm_vendor` ADD `vendor_accepted_currencies` TEXT NOT NULL ;
	
	
29-04-2006 soeren

^ changed the tree script to TigraTree for the "Product Folders" list. 
	It builds the tree much faster than the JSCookTree and dTree script and even works with 10.000+ items.
+ Tigra Tree Menu Javacript
# Task #73 - Order Confirm E-Mail - Plain text & html text of Message differ (ps_checkout.php)

26-04-2006 soeren

# Task #729 - additional address links in admin (admin.user_form.php)
# Task #733 - Discount causes error message in Order Details page
+ added the possibility to add a product by product type
# product type form&list missing an object
- pay-download form removed from product form
+ allowing multiple pay-download files per product now (useful when the file size is so large that you need to split up the file)
+ allowing the file manager to manage product (main) images
- FileManager product list

23-04-2006 soeren

+ Now it is possible to easily inform your customers about their order cancellation right
	and your returns policy (as required by law in most european countries!)
	=> added 3 new configuration parameters
	! Update your configuration when updating from an earlier version
# hiding attribute price modifiers when the user has no permission to view prices

20-04-2006 soeren
# Task #722 - Undefined index: coupon_discount in ps_checkout.php
# Task #721 - Trying to get property of non-object in shop.debug.php
# Task #560 - Clone Product with Child Products (added "SHOW" as result-returning-case ps_database.php)
# Task #675 - No permissions to view products after search (virtuemart.searchbot.php)
# Task #698 - Lost password link uses relative link instead of absolute (mod_virtuemart.php)
# Task #707 - Payment method at the end of the checkout is not shown (ps_checkout.php)
# Lightbox fixes for IE
+ dynamic price form in the product list (Click on a price and it loads!)
^ admin product list now showing the prices of the default shopper group

18-04-2006 soeren
+ new vmConnector class (vmConnector.class.php). It can be used to retrieve remote URLs and documents. It tries to 
	use cURL to do the communication when available. When a proxy has been set, the proxy is
	used for all outgoing calls.
	The new function vmconnector::handleCommunication( $url, $postData='' ) is to be used by
	payment and shipping modules. No need anymore to handle that transaction part in the module itself.
+ Possibility to enter Proxy information. This is espcically useful when trying to use
	UPS/USPS on godaddy servers.
	New configuration parameters: VM_PROXY_URL, VM_PROXY_PORT, VM_PROXY_USER, VM_PROXY_PASS
+ Currency Converter implemented. From now on the store converts currencies when necessary.
	If the product price currency is "USD" and the store currency is "EUR", all prices are
	converted using an XML file with the latest rates from the European Central Bank (ECB, function convertECB).
	The XML file is cached and refreshed regularly. See /classes/currency_convert.php.
	You can change the displayed currency in the frontend by adding the parameter "product_currency" to the URL:
	
	index.php?option=com_virtuemart&page=shop.browse&category_id=3&product_currency=EUR
	
	A module to allow changing the displayed currency by selecting one from a list will follow.
	
# Task #705 - Product Type Pagelinks are not working due to wrong $num_rows (product.product_type_list.php)

12-04-2006 soeren

+ "recommend this product to a friend" mod by Benjamin (codename-matrix)
+ new configuration parameters for the review system (minium/maximum comment length...) 
! DATABASE STRUCTURE CHANGED
	^ JoomFish compatibility requires the field "attribute_id" for the table jos_vm_product_attribute, so here it is:
		Thanks, Steven and spookstaz http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=16124.msg38407#msg38407
	########
	ALTER TABLE `jos_vm_product_attribute` ADD `attribute_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;	
	# Ask a question!
	INSERT INTO `jos_vm_function` VALUES ('', 7, 'productAsk', 'ps_communication', 'mail_question', 'Lets the customer send a question about a specific product.', 'admin,storeadmin,shopper,demo');	
	# Prevent auto-publishing of product reviews
	ALTER TABLE `jos_vm_product_reviews` ADD `review_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
	ALTER TABLE `jos_vm_product_reviews` ADD `published` CHAR( 1 ) NOT NULL DEFAULT 'Y';
	#########
	
+ "ask a question" - enquiry mod by macallf (http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=17143.0)
+ new Lightbox javascript added to have a cool modal window during an Ajax request! => http://blog.feedmarker.com/2006/02/12/how-to-make-better-modal-windows-with-lightbox/
+ added Moo.Ajax javascript to provide XMLHttpRequest services (aka Ajax)

10-04-2006 soeren
^ product list now opens a new window to display the product form. Forms are to be "ajaxified" soon.
+ added the famous "Apply" button to all Save/Cancel forms, now it shows: Save / Apply / Cancel
# user fields not allowing userUpdate
# user form not working on Mambo 4.6.0
! DATABASE STRUCTURE CHANGED !
	- some non-critical INDEX corrections
	
04-04-2006 soeren

+ added "Newsletter subscription" to field type list. You can now allow users to subscribe to your newsletter 
	at the time of registration. Currently possible: Letterman subscription (YaNC, ANJEL - who knows how to hook in there?)
^ uploaded images get "real" file names now using product_name,category_name or vendor_name (before it was a random md5 hash)

02-04-2006 soeren

# Task #632 - get_flypage doesn't take into consideration parent products (ps_product.php)
# Task #631 - Customer Unable to Remove Data from Bill To / Ship To Fields
# Task #629 - PayFlow Pro does not handle 4 digit expiration dates gracefully
# Task #511 - Discount % percentage is ignored by cart (ps_product.php)
# Page redirection on error from Ship-To address from fixed, thanks TJ! (account.shipto.php)

29-03-2006 soeren

^ integrated the changes to the authorize.net class by Daniel Wagner (http://virtuemart.net/index.php?option=com_flyspray&do=details&id=634&Itemid=83)
# wrong object names in PayPal notify.php script lead to a fatal error
# Task #656 - "Remember Me" must be enabled to checkout, checkout_registration_form.php
# tooltip function: added charset parameter to encode UTF-8 strings too, htmlTools.class.php
+ introduced a new function called "vmGetCharset" to return the current charset from the _ISO setting (UTF-8 by default), ps_main.php

+ new DHL shipping method integration, thanks to Durian!

!!! DATABASE STRUCTURE CHANGED !!!
	NEW TABLE "jos_vm_shipping_label"
	
+ customer name on oder list

28-03-2006 soeren
# query error in ps_affiliate.php
# fixed reviews listing ("More..." - link when more than 5 reviews exist for a product) in the frontend (ps_reviews.php)
# fixed page navigation on product review list in adminsitration (product.review_list.php)
+ customer name on order list (thanks to deneb!), (order.order_list.php)
# Fixed PayPal notify.php script:
	- wrong field name (` order_currency` instead of `order_currency`)
	- checking received currency and amount against database
# parameter search query missing a `

27-03-2006 soeren

# version.php causing fatal error regarding "class vmVersion previously declared..."
# Prices visible to all users, although restricted
# Admin Menu not visible with chinese language file (htmlentities missing third (=Charset) parameter)
# CSV Export doesn't export parent product SKU (parent-child relationship gets lost)
# fixed a small typo in the product scroller module

---- VirtueMart 1.0.4 released ----

23-03-2006 soeren

# Order "Print View" link lead to a 404 error
+ ProductScroller module: added the category_id parameter to the XML file, so you can now specify a category_id (or a comma-separated list of more than one category_id) 
	to filter the products by (multiple) category/ies
# Product Reviews are not added to the database, although the vote is added
	
20-03-2006 soeren
^ Payment method preselecection: the first displayed payment method is always pre-selected now
# "delete from cart" fails when the custom attribute value contains quotes
# can't assign more than one product type to a product
# Task #622 - Order Update Time is Wrong
# Task #601 - Show the Number of Products in a Category
+ for debugging: added '@ini_set( 'display_errors', 1 );' to virtuemart_parser.php
	for making PHP errors visible
^ changed behaviour for HTTPS links when in HTTPS mode.
	When the user is NOT on "checkout" or "account" pages, all links are generated using the http://... URL
	This will allow leaving the HTTPS mode 2 after the order has been placed.
# Task #490 - adding attributes error on sub-items
# Task #518 - Reports miss same-day orders
# Task #558 - Bug in report basic module
^ showing "no image" image when a product thumbnail image is not available
# Task #470 - Close tablerow after Categorylisting
+ products can be viewed using the SKU now. Works for the product details page:
	Instead of "&product_id=XX" just use "&sku=YY" where YY stands for the SKU of the product
# credit card number not checked on form submit, another bug, same reason: payment method can be left unchecked
+ added: autocomplete="off" to the credit card form to prevent sensible information being prefilled
+ Order item status update by manelzaera
# Task #617 - Wrong image path in account.billing.php
# Task #615 - Cannot add multiple Product Types to a Product

15-03-2006 soeren

+ direct link to global configuration from shop configuration (where the Joomla registration settings are shown) 
+ new configuration variable: VM_SHOW_REMEMBER_ME_BOX (you can now choose whether the "Remember me" box is shown
	on login or the usercookie is forced with a hidden field.)
+ new configuration variables for better being able to switch between http and https:
	VM_GENERALLY_PREVENT_HTTPS - allows to get back from https to http in areas, where https is not necessary (as it only slows down the connection)
	VM_MODULES_FORCE_HTTPS - allows you to specify a list of shop areas (= shop core modules, e.g. account,checkout,...) where https connections are forced
# Session class fixes ( session_id( ... ) is no longer used, from now on we just don't care about the Joomla/Mambo session ID)
	
12-03-2006 soeren

# users, who are logged in, but not yet registered as customer/shopper 
        can't directly continue their "checkout" after registration as shopper
# users who are logged in, but have an empty "usertype" field don't see prices
# added $manufacturer_id support for caching pages
28-03-2006 soeren
# query error in ps_affiliate.php
# fixed reviews listing ("More..." - link when more than 5 reviews exist for a product) in the frontend (ps_reviews.php)
# fixed page navigation on product review list in adminsitration (product.review_list.php)
+ customer name on order list (thanks to deneb!), (order.order_list.php)
# Fixed PayPal notify.php script:
	- wrong field name (` order_currency` instead of `order_currency`)
	- checking received currency and amount against database
# parameter search query missing a `
11-03-2006 soeren
# syntax error in shipping.rate_form.php

10-03-2006 soeren
# Task #325 Log out does not work
# missing $mosConfig_absolute_path in currency_convert.php

07-03-2006 soeren
# many short tag fixes (< ? => < ?php )
# Task #566 - DescOrderBy doesn't work with SEF
# more ps_session class fixes to work on Joomla 1.0.8 & Mambo 4.6
        seems to me as if some Joomla 1.0.8 users are suffering serious Session problems now
^ setting memory_limit to 16M when it is lower
+ multiple tax rate details in order email

04-03-2006 soeren
# short php tags in shop.manufacturer_page.php
# Task #551 - Cart showing extra products after adding first item
# Task #562 - Discount deletion problem

02-03-2006 soeren
# Task #432 - missing ST address in order_user_info when using default address
# Task #482 - error with multiple mod_virtuemart
# Task #541 - IE gets error in admin orders
# View by Manufacturer: Products without prices not shown
+ new global variable $VM_BROWSE_ORDERBY_FIELDS, contains all sort-by fields for the browse page
^ moved $orderby code to shop.browse.php and shop_browse_queries.php
+ new configuration constant: VM_BROWSE_ORDERBY_FIELD can be [product_name|product_price|product_cdate|product_sku]
+ added "ob_start" to the session class to prevent HTML output BEFORE the template is loaded ( Task #553 - Product Display)
^ tax rates in drop-down list in product form are ordered by rate, descending now

28-02-2006 soeren

# tax total calculated based on product tax rate when TAX_MODE = 1 (store-address based tax mode)
# Task #536 - vendor info page error
# page navigation on browse pages contained the live site URL.

22-02-2006 soeren

# standard shipping module doing wrong number_format when amount is greater than 999.99
# fixed: multiple tax rates / subtotal re-calculation when discounts are applied
# ps_product_category::get_cid => category ID query not executed
# attribute prices being displayed without tax, although "show prices including tax" is active
# totals getting stored without decimals: changed "setlocale( LC_NUMERIC, 'en' )" to "setlocale( LC_NUMERIC, 'en_US' )"
+ page title on order details page in account maintenance
# checkout login form using sefRelToAbs for $return
^ using the same "Add-to-cart" image as in product_details in browse page now
# tax rates were stored with 0.0000 value

! DATABASE STRUCTURE CHANGED 
---
        # http://virtuemart.net/index.php?option=com_flyspray&Itemid=83&do=details&id=521
        ALTER TABLE `jos_vm_product_mf_xref` CHANGE `product_id` `product_id` INT( 11 ) NULL DEFAULT NULL 
        
        # Store multiple-tax-rates details for each order when applicable
        ALTER TABLE `jos_vm_orders` ADD `order_tax_details` TEXT NOT NULL AFTER `order_tax` ;
---


21-02-2006 soeren

# Task #525 - USPS shipping module: User details SQL query
# order email: text part had ugly HTML entities in it (e.g. &euro; )
^ file downloads (paid downloads): reading and sending the file is now handled by a new function 
        (previously: readfile, now: vmReadFileChunked )
# fixes for compatibility with Joomla 1.1.x, still maintaining backwards compatibility with Mambo
        - added $vmInputFilter to global declaration list in virtuemart.php
        - virtuemart module dealing with wrong module paths
        - ps_perm needed its own ACL manipulation methods
        - ps_session doesn't need to append "&Itemid=" in the backend
        
17-02-2006 soeren

# When price field left empty and product had no price, a price record (0) was added.
# Task #456 - Foreign adress give error on checkout
	If you leave the ZIP start or end fields empty, automatically "00000" or "99999"
	is inserted. This was a trap for many users.
# Task #515 - Problem with Authorize.net after upgrade
# Task #519 - Fatal error when adding a manufacturer
# linkpoint class using wrong user information query (ps_linkpoint.php)
# order list query error
+ order and user list can be filtered by full name now 
        (before it was possible to search for the first name OR the last name, not both at the same time)
        
14-02-2006 soeren
# Task #480 - Various Errors (one fatal) in vm_dtree.php
# Task #514 - add to cart URL does not always work
# Task #509 - Deleting manufacturer bug
# Task #495 - Related products list doesn't update with new products: 
        now displaying 2000 related products instead of 1000.
# Task #455 - Silent user registration not working ($mosConfig_useractivation issue)
# Task #474 - Changing default flypage is broken
# Task #473 - Free Shipping broken: SQL statement in global.php
# Task #471 - The script sleight.js isn't loaded when SEF URLs is on
# Task #468 - wrong variable in standard_shipping.php

08-02-2006 soeren
# Task #486 - HTTPS Error In Virtuemart.cfg.php (not every server uses port 443 for secure connections)
        changed ** $_SERVER['SERVER_PORT] == 443 ** to ** @$_SERVER['HTTPS'] == 'on' **
# authorize.net: Strip off quotes from the first response field to be sure the correct response is received

03-02-2006 mdennerlein
# fixed bug in vmCommonHTML::getYesNoIcon which always returned published icon

28-01-2006 soeren
# Shoppers / Users couldn't be deleted.

27-01-2006 soeren

+ order list at user form! (Thanks to Qazazz! http://virtuemart.net/index.php?option=com_smf&Itemid=71&topic=14001.msg26715#msg26715)
^ FedEx: basic implementation of FedEx' service "Rate available Services" finished
        You can now use FedEx to fetch and list available shipping rates
        
[---- VirtueMart 1.0.2 released ----]

19-01-2006 soeren
# Deleting a product didn't delete the product <-> product type relationship,
        so you couldn't delete the product type

16-01-2006 soeren
# Task #443 - Registration not possible with .info domain
# Task #418 - Can't assign multiple product types to a product
# Task #417 - Changing status to 'C' for auth net settle del. trans ID!
# product list not showing all search options
# Task 412 - no tax on attributes
# Task 413 -  wrong price on details page when using quantity-based prices
# Using recent Itemid instead of 1, when the Shop has no own Itemid
+ added Australia Post shipping module by Ben Wilson (ben@diversionware.com.au)
# mosproductsnapshot Mambot wouldn't correctly display linked images
+ Download ID "hack" by Eugene, scott, joomlasolutions!
        Customers can get their download IDs for downloading files
        directly from the order details page (products are linked)
+ showing filesize for files which are listed on the product details page (by djlongy)
        directly from the order details page (products are linked)
+ showing filesize for files which are listed on the product details page (by djlongy)


11-01-2006 soeren
+ when Caching is on, cached pages are watermarked with a timestamp ("Last updated: Wednesday, 11 January 2006 16:31") like we know from eBay
+ FedEx shipping module integration begun
# fixed minor issues with the new userfield registration system
# Task #435 - Link to component class
# Task #433 - Blocked message (popup) on registration
# Task #432 - missing ST address in order_user_info when using default address
# Task #431 - Pricelist doesn't show prices

 
09-01-2006 soeren
^ Payment method discounts/fees: Implemented a percentage discount...
        You can now charge the customer a certain percentage of the order total

        ! DATABASE STRUCTURE CHANGED: table jos_vm_payment_method
          Added 3 new fields to that table to allow percentage discounts

05-01-2006 soeren
# Task #430 - ToolTip Error when use Chinese
^ Task #427 - Add To Cart button in Browse uses Joomla button CSS.

27-12-2005 soeren
^! changed the structure of the table jos_vm_userfield_values: added a column "fieldvalue"
        for being able to list fieldtitles in an option list, using fieldvalue in the value="" attribute
        For staying up-to-date in CVS: "ALTER TABLE `jos_vm_userfield_value ADD `fieldvalue` varchar(255) NOT NULL"
# mod_productscroller not using category ID for filtering products


22-12-2005 soeren
+ new HelpTip from WebFx (http://webfx.eae.net/dhtml/helptip/helptip.html)
        this javascript allows displaying details of products in a box that 
        can be shown and hidden and doesnt vanish on mouse scrolling (used on CSV Upload)
        Usage: echo vmHelpToolTip( "My tip in the box", "The link text" );
+ step-by-step import on CSV Upload
+ CSV Upload simulation: uploaded CSV files are not instantly imported, but the
        import first is simulated and the results are shown to the user
^ admin dropdown menu now is able to display special characters (e.g. dutch and german special chars)

20-12-2005 soeren
+ showing End-User price in the admin's product list now
^ thumbnail generation: improved JPG quality, allowing gif thumbnails now
^ removed the coupon form from "shop.cart"
# order list: searching by user names won't work
^ improved "Continue shopping" link in the cart, now redirects to "shop.browse" or isn't visible when just the cart was viewed
+ new "Move Products" feature lets you move products from one category to another
# manufacturer can't be deleted although it has no real products assigned to it
^ browse page now is ordering products by product list order when a category is selected
+ added product reordering feature (a category must be selected in the product list, then you'll see the reorder fields)
^ fixed problem saving a manufacturer (category) with ' in name or description
^ moved function list_perms from class ps_user to class ps_perm
- removed property "permissions" from class ps_user

+ first version of the new "user fields" management system
!! DATABASE STRUCTURE CHANGED:  two new tables  !!
!! see /sql/UPDATE-SCRIPT_VM_1.0.x_to_1.1.0.sql                 !!

^ silently registered users don't have to remember their old usernames now (Task #385 returning hidden/silent users can't use the same email address)


16-12-2005 schirmer
+ New feature to easily translate the flypage using {vm_lang:xxx} place holder. Usage instructions in html/shop.product_details.php

15-12-2005 soeren
# product prices can be zero or empty now. When the product price is left empty in the product form, an existing price will be deleted and no price will be added.
# Tax total is zero although user's country/state combinination has a matching tax rate record (when CHECKOUT_STYLE = 3 or 4)
# Task #364 "thank you for your patience...": wrong Waiting list link
# Task #386 "New user couldn't be added"

10-12-2005 soeren
# currency_convert including wrong DOMIT files.
# user list has no valid user id in the delete link (deleting didn't work)

07-12-2005 soeren
# Task #63: Prices are stored in the session and do not change on update
# wrong xhtml syntax in mod_virtuemart_search
# Task #374: Incorrect "Title" wording on [Featured & Discounted Products] Screen
# Task #372: Product Search doesn't work when Joomla Caching is ON
	(product search pages were cached, so the search function could only be used once)

04-12-2005 soeren
# "product_list" search not working, when a category is selected
^ Extra Fields are now visually integrated in the registration form, not appended at the end
^ more debug output in standard_shipping module (only when DEBUG is turned on)

01-12-2005 soeren
^ attributes are formatted now in the order print screen - just as in the frontend
+ attributes of child products (which were selected by the customer) are stored now which each order
# fixed a bug in the frontend order listing (account maintenance section), which showed no search box and page navigation
# fixed a bug in global.php, where an administrator, which has no record in the table
  jos_vm_auth_user_vendor wouldn't get the vendor information (and see prices in the backend with no decimals)


30-11-2005 soeren
# added a routine to unpublish mambo-phpShop mambots on upgrade
# added checks for the existance of files which are to be loaded
# added a check if $ps_shopper_group is an instantiated ps_shopper_group object to admin.user_form.php
# renamed all occurences of $PHP_SELF to $_SERVER['PHP_SELF']
# fixed a bug in the page navigation on the browse page (document.adminForm is null or not an object)

---- VirtueMart 1.0.1 released ----

28-11-2005 soeren
^ renamed the vmLog function 'flush' to 'printLog' to prevent early flushing (was it caused by the function name?? would be another curious php bug)
! wrong error handling when a user is not allowed to view the requested page (Security Issue).
# wrong featured products links on storeadmin homepage
# PDF output not working
# calling html_entity_decode with an empty string crashed Apache and VM (class.phpinputfilter.php)
 
24-11-2005 soeren
# setlocale( LC_NUMERIC, 'en' ) is used globally for ensuring that numbers are handled with decimal points
# fixed a parser error in the random products module

---- VirtueMart 1.0.0 final released ----

23-11-2005 soeren
# vmPopUpLink generating window with same value for width and height
# removed whitepace at the end of ps_main.php
# even when no discount was selected in the product form, a discounted end price was filled in
# when user is assigned to a Shoppergroup which doesn't exist, the default one is used now (thanks to esteve!)
# CSV-Export: removed export of "product_special" field, because it's not included in the default CSV configuration
# CSV-Export running incorrect query (empty file received)

21-11-2005 soeren
# filenames didn't include the full path
# problem with filemanager: "The request file wasn't found"
^ small DB structure change to allow negative quantities for "jos_vm_product.product_in_stock" (just removed the UNSIGNED attribute)
	ALTER TABLE `jos_vm_product` CHANGE `product_in_stock` `product_in_stock` INT( 11 ) NULL DEFAULT NULL;
# wrong height of full-image-popUp-window in product details
^ (or bug fix?): added ob_start according to this bug report: http://virtuemart.net/index.php?option=com_flyspray&Itemid=83&do=details&id=300
^ fixed the laoyut for IE in "Your store::control panel"
+ added login form to account maintenance pages to allow quick login

17-11-2005 soeren
^ removed the "VirtueMart already installed?" check to allow manual installation.
^ extended ps_html::writableIndicator to process arrays with more than one directory
+ integrated Verisign Payflow Pro payment module into VirtueMart

16-11-2005 soeren

^ splitted up and renamed "/sql/virtuemart.installation.mysql.sql"
	into "/sql/virtuemart.installation.joomla.sql"
	and "/sql/virtuemart.installation.mambo.sql"
	for those users WHO DON'T EVEN LOOK INTO THE FILE THEY ARE UPLOADING IN PHPMYADMIN.
	
^ updated the INSTALLATION.php to be able to distribute a "Manual Installation" package,
	where it is added to as "README.txt"
# authorize.net not getting the correct billto address
^ improved the debug and error message reporting in authorize.net payment module

16-11-2005 schirmer
# switched to vmLogger in payment and shipping modules
# switched to new user_info table in payment and shipping modules


15-11-2005 soeren
# fixed a small bug in the ps_shopper.php
+ new: bulgarian language file
# "Credit Card type not found" error would prevent checkout.

12-11-2005 soeren
# users couldn't rename their username in account maintenance (ps_shopper.php)
# small notices in ps_checkout.php

10-11-2005 schirmer
# renamed 'Log' to 'vmLog' in virtuemart_parser.php

09-11-2005 soeren
# fatal error: prices can't be deleted (ps_product_price.php, product.product_price_list.php)
# renamed class 'Log' to 'vmLog'
# standard shipping module not accepting valid rates onValidate

---- 1.0.0 RC3 released ----

08-11-2005 soeren
# installation displays a log now
# installation would copy files with wrong permissions on upgrade
# product list empty when browsing child products of a product from pages no. >= 2
# ps_checkout typos
# "Empty Cart" - fixed a bug where the session id would have been changed on each page load
	what made keeping items in the cart impossible
	
07-11-2005 soeren

# task #252 (Japanese Yen Currency symbol affects attribute list line break)
# unpublished products were counted in "products in category".
# task #249 (a bug with html_entity_decode ("Warning.....MBCS not implemented"))
# fixed a small notice in vm_dtree.php

---- 1.0.0 RC2 released ----

06-11-2005 soeren
# changed all occurences to 'com_phpshop' to 'com_virtuemart' in payment methods
# Task #164 (Admin doesn't accept any input and doesn't change pages)
# fixed a fatal error in the install.php

04-11-2005 soeren
# when updating the order status from the order list, always a customer notification would be sent
# the Altbody (alternative text part of an email) is utf8_encoded now,
	when the language charset is 'utf-8' (standard in all new language files in joomla!)
+ Manufacturer ID is shown in manufacturer_list now
+ added search by product_sku to searchbot
^ payment method and shipping method are validated again on orderAdd
# fixed the shipping_rate_id validation in the standard_shipping module
# moved the coupon field back into the cart
# wrong names for new customers in overview
- removed the table prefix replacing function from ps_database
^ changed the url formatting function ps_session::url to use $mm_action_url instead of URL
# more fixes to the Shared SSL support (it now logs the user in on the https domain, even when Joomla is used)


02-11-2005 soeren
# fatal error in payment method form in frontend
# passkey change code didn't work (e.g. authorize.net)
# admin top menu didn't show up when quotes in a module name
# usps module referenced wrong DOMIT! path
# coupon add didn't work
# wrong rounding of the subtotal field
! table structure changed!
	#####
	ALTER TABLE `jos_vm_orders` 
	CHANGE `order_subtotal` `order_subtotal` DECIMAL( 10, 5 ) NULL DEFAULT NULL;
	#####
^ refreshed paypal code (removed tax field, charging amount=subtotal+tax and shipping now).
	
01-11-2005 soeren
# category_flypage was 'flypage' regardless of the category setting (changed ps_DB::sf() )
^ changed coupon field to be displayed only on the payment method selection screen
# percentage coupon was miscalculated on quantity update in cart (thanks gwen)
^ currency symbol in store form is now stored as HTML entity (?  => &euro; )
^ payment methods are surrounded by fieldsets now

28-10-2005 soeren
# changed shopmakeHtmlSafe to use hmtlspecialchars instead of htmlentities
# fixed a lot of queries using a database object instead of ps_DB
# replaced all occurences of mosToolTip by mm_ToolTip
# tax rate is automatically divided by 100 when larger than 1.0
# "view more images" wasn't shown on product details, view_images page had SQL errors

27-10-2005 soeren
# fixed a bug in ps_order.php, where the mail would have been sent to '' (nobody)
# some fixes for the wz_tooltip (using htmlentities now)
^ page navigation links only show up when more results are there to display than $limit
+ added page navigation to order list in account maintenance section
+ added tax amount to paypal payment form code
# fixed a big bug in the SQL update of the user data to VirtueMart
+ added quick (un)publish feature to category and payment method list
- files admin.user.hmtl.php, store.user.html.php
^ restricted access to the user list & form to conform with joomla's user component access
+ added new class vmAbstractObject
+ added new handlePublishState function (class vmAbstractObject)
^ changed productPublish function to handlePublishState
! Database table entry changed: 
##############
UPDATE `jos_vm_function` SET `function_name` = 'changePublishState',
`function_class` = 'vmAbstractObject.class',
`function_method` = 'handlePublishState',
`function_description` = 'Changes the publish field of an item, so that it can be published or unpublished easily.' WHERE `function_id` =139 LIMIT 1 ;
##############

26-10-2005 soeren
+ added debugging to image upload function
# Task #181 ? Can't add new prices to product

25-10-2005 soeren
# Task #174 ? Checkout using USPS Module, fixed path to xml domit! library
^ renamed /html/VERSION.php to /html/footer.php
^ changed the colors of the order list to joomla css classes (account maintance section)
# FR #127 font size in tab headings too big in safari browser
+ added new language tokens for the Log integration
# Task #166 ? virtuemart-beta4-shared SSL
# Task #173 - Registration with e-mails over 25 characters
# Task #176 - beta4: message tax included displayed even if OFF
^ FR #125 vendor name in shopper group drop-down

24-10-2005 soeren
# fixed a bug where "my-email-address@domain.com" couldn't be used for username (converting - to _ now)
^ file uploading errors are handled better now
+ introduced new global Log object for better Error Message Handling
	See http://pear.php.net/package/Log for docs.
	The class and its child classes can be found in /classes/Log. VM uses a modified version
	of the display class. Support for buffering and formatting depending on priority was added.

	
22-20-2005 soeren
+ added ability to change username + password through shop's billing form
# waiting list extension printing errors...

20-10-2005 soeren
# fixed various bugs in modules (vm_dtree, vm_transmenu, vm_JSCook, vm_product_categories, vm_productscroller)
# category_id is lost when (un)publishing a product directly from the product list

19-10-2005 soeren
# fixed session debug messages, a session isn't started in the backend now
# fixed various installation / update bugs
^ changed Mail functions
	* renamed mShop_Mailer to vmMailer
	* added the functions vmMail (similar to mosMail) and vmCreateMail( similar to mosCreateMail)
	* line-ending fix for Mac & Win problems sending mail (Could not instatiate mail function)
	
# made labels for payment methods clickable
# fixed Task #137 'unpublished products can become related products'

=======
19-10-2005 schirmer
#  fixed Top10 module showing products multiple times if it has more than one category


18-10-2005 soeren
^ Changed the field jos_vm_order_item.product_item_price from DECIMAL(10,2) to DECIMAL(10,5) to prevent rounding errors
##########
ALTER TABLE `mos_vm_order_item` CHANGE `product_item_price` `product_item_price` DECIMAL( 10, 5 ) NULL DEFAULT NULL;
##########

+ re-added shop.registration.php (includes login form and registration form)
# changed cart initialitation function from "ps_cart" to "initCart"
# fixed Task #135 Cannot use a scalar value as an array
# bug in product folder view
^ introduced new blue icons
# bug in product file form + filemanager

17-10-2005 soeren
# user registration required email, although no email field was there
# credit card payment wasn't recognized correctly on order details screens
^ added Credit Card details to order confirmation email
^ last 4 digits of a Credit Card number are masked by asterisks now (security!) in administration
# fixed the PDF function (a file was missing php code), updated HTML2FPDF to version 3.02beta
# prices from advanced attribute field didn't include shopper group discount, 
  when the price was set to a fixed price ( Color,blue,green[=45.00]; )
# dtree module crashed - missing global $db declaration

14-10-2005 soeren
# On registration an error from the Joomla registration function would empty all fields
+ added new Version check link to admin section
# keyword length is restricted to 50 from now on (security), prevents 10000 characters long keyword via URL 

12-10-2005 soeren
# wz_tooltip.js is included now whenever mm_ToolTip was called
^ The registration & billto form have been completely rewritten
	The are built out of a loop now, that runs through an array with all fields and 
	marks required fields. This prepares the integration of	a form & field management 
	component! You can already now easily re-arrange the fields by changing their order.
+ Added complete JS validation to the registration / billto forms
	Uses ps_userfield::printJS_formvalidation() to print a JS form validation function
	
11-10-2005 soeren
# fixed a bug in the shopper-registration of a registered user
+ added SwitchCard support to CC numbers validation

10-10-2005 soeren
^ moved to class vmInputFiler to prevent SQL injection
	(we always had our own basic protection against that, but vmInputFilter was especially made for that)
	To secure a variable just use $variable = $vmInputFilter->safeSQL( $variable );
# fixed a dumb bug in the function ps_product_attributes::cartGetAttributes
	(allowed to add products without choosing attributes)
^ moved ACL code for 'show_prices' authentication into ps_perm::prepareACL()
^ moved cart initialization code into a new constructor for ps_cart
^ moved Session initialization code into ps_session::initSession(); a new constructor calls this on class instantiation

09-10-2005 soeren
+ new Configuration parameter VM_SILENT_REGISTRATION
	allows users to "silently" register into Mambo/Joomla
	means they don't have to fill in a username and password at the registration.
! you can use the configuration panel to set this value; default: 1 (=enabled)

08-10-2005 soeren
+ added new configuration parameter VM_PRICE_ACCESS_LEVEL
	The value is the name of a Joomla user group, default: "Public Frontend"
	It can be used to restrict the price display to certian membergroups (including their childs)
+ added new configuration parameter VM_PRICE_SHOW_INCLUDINGTAX
	A flag to turn on or off the message (including 8.5% tax) behind a price display
+ added new configuration parameter VM_PRICE_SHOW_PACKAGING_PRICELABEL
	A flag to switch between usual price labels or packaging price labels (which are used, when Packaging Units are set)
^ re-arranged fields in the configuration panel

07-10-2005 soeren
+ new function vmPopupLink to quickly generate a JS + XHTML compliant link
# TopTen module optimized (ran 11 queries before on 10 products, now ONE)

06-10-2005 soeren
^ updated the PayPal Form Code according to this post (http://mambo-phpshop.net/index.php?option=com_smf&Itemid=71&topic=11167.msg21226#msg21226)

06-10-2005 schirmer
# tax list optional with onChange field. product_form automatically edits the price fields if tax is changed.
# public frontend fixed. New menu buttons didn't send admin state pshop_mode variable.

06-10-2005 schirmer
# typos in install script
# missing / in dummy phpshop file

05-10-2005 soeren
+ added new product discount "overrides" to the product form which can be used to
	fill in a discounted end user price, from which a discount will be calculated and added to the product discount list
# fixed a bug in install.php
+ added a new CVS module 'build_scripts', so you can build your installers


04-10-2005 soeren
^ moved the Shipping Rates and Carriers of the standard shipping module into sample data file
^ the class ps_user registers users into VirtueMart (function for admins!)
^ the class ps_shopper registers Shoppers into VirtueMart (function add for Shoppers)
^ Changed the registration process to use the registration component of Mambo/Joomla
- file shop.registration.php
! User Management no longer uses modified Mambo files, but includes needed functions.
- file admin.users.html.php

04-10-2005 schirmer
^ Updated Montrada payment class for VirtueMart
# Minor fix in url generation in ps_session. If option is specified com_virtuemart will not be appended.
# Category count now displays correct count for vendors
# Error messages from ps_product now are space seperated for better readability

01-10-2005 soeren
- Removed many fields from the table jos_vm_modules which are not longer necessary (and were actually never needed)
! Updated all SQL files and the Installation script
! Beginning to change the code to not to use mos_users table for customer information
! ### Database Structure Changes ### ! 
	Details: /sql/UPDATE-SCRIPT_mambo-phpshop_1.2_stable-pl3_to_VirtueMart_1.0.sql

^ Changed all tooltips to use wz_tooltip, this gives always working tooltips - even on tabbed forms
+ added JS ToolTip by Walter Zorn to VirtueMart


30-09-2005 schirmer
# frontend administration can't load page
# missing pshop_mode=admin in inventory for links
# ps_affiliate undefined index afid on checkout in register_sale function
^ list_year in ps_html changed to dynamic year list
# store.index only shows apropriate options and information. no links to unusable modules or non-vendor specific stats
# fixed duplicate files listed on flypage

29-09-2005
- updated all files to use com_virtuemart as path
- updated all queries to use {vm} as shop table prefix
- Changed $PHPSHOP_LANG to $VM_LANG
- fixed product file listing
- renamed *phpshop*.php to *virtuemart*.php
- added "update to virtuemart" routines to install.php

27-09-2005
- Domit! libraries are not longer included in VirtueMart, Mambo provides them
# WYSIWYG Editor not loading in frontend admin
^ Frontend Administration uses the backend toolbar now (shared administration)
^ changed the file headers of all files to carry the new name (VirtueMart) and a copyright notice

26-09-2005 soeren
# fixed the "product inventory" and "special products" list

25-09-2005 soeren
! configuration constant SEARCH_ROWS (deprecated) is to be replaced by $mosConfig_list_limit
- removed Mail configuration from configuration form (dropping support for Mambo < 4.5.1 )
- removed configuration constant MAX_ROWS.
^ changed the configuration file (virtuemart.cfg.php) to build URLs and Paths from Mambo configuration variables
  This means that you don't have to adjust your configuration file when moving a site.
^ updated all forms to use the new formFactory class and it's methods
+ new class formFactory for managing common form tasks in all administration forms in virtuemart

18-09-2005 soeren
^ Language files are updated. Language Strings can be returned as HTML Entity-encoded Strings.
	* class vmAbstractLanguage is the base class for all language files.
	* function _() returns an html entity-encoded string
! language classes extend class vmAbstractLanguage from now on. mosAbstractClass is deprecated.
- file mos_4.6_code.php will be removed.
	* vmAbstractLanguage & mosAbstractLanguage class moved into language.class.php
	* mosMailer / mosCommonHTML compat code moved into ps_main.php

13-09-2005 soeren
+ changed the product files list to show images in a tooltip
# added code to prevent that manufacturers are deleted which still have products assigned to it
# changed virtuemart_parser.php not to be greedy on variables when $option is NOT "com_virtuemart"
	this should fix conflicts with variables of the same name used by other components
^ Updated the toolbar to allow batch delete / (un)publishing of items in lists
^ Changed complete page navigation to Mambo style (also remembers list positions!)
# Product Quantity wasn't updated in cart when adding the same product again
! functions search_header and search_footer will be removed. Don't use them. Use the class listFactory and its methods instead.
^ changed all shop administration lists to use the new class listFactory. No more HTML Code in those lists!
+ added new file "htmlTools.class.php" containing a listFactory for admin lists
+ added new file "pageNavigation.class.php" (copy of the administrator/includes/pageNavigation.php)
+ added new file "/js/functions.js" for JS functions in the administration area

06-09-2005 soeren
^ mod_virtuemart: changed the default value for "Pre-Text" to "" (empty!)
# product search not handling keywords as separate search words, but as one (normal search)

01-09-2005 soeren

+ added a CSS file called shop.css to /css: will control all shop specific layout in the future
^ moved some program logic from virtuemart_parser.php to their appropriate classes


31-08-2005 soeren
# products with a single quote (') didn't have a visible product image
^ upated the CSV documentation
^ product form: moved the discount drop-down list to product information tab
	added a check to test if the IMAGEPATH is writable (see Tab "product images")
# Custom Attribute Values would allow the customer to alter the product price (thanks to "Ary Group" <AryGroup@ua.fm> for reporting that)

=======
26-08-2005 Zdenek Dvorak
+ Now is possible use EXTRA FIELDS in user_info. Just set variable _PHPSHOP_SHOPPER_FORM_EXTRA_FIELD_X (where X is from 1 to 5)
  in language file and new input field will be shown in user's billing and shipping address form and in order details. Size of 
  extra field 1, 2 and 3 is 255 chars. Size of extra field 4 and 5 is one char and they are shown as input select field.
  For reasonable using extra field 4 and 5 is needed change items of input select in functions list_extra_field_4 
  and list_extra_field_5 in file classes/ps_html.php.
  You can change position of this fields in form in files: account.shipto.php account.billing.php account.order_details.php 
  admin.users.html.php admin.user_address_form.php
+ User info in order includes EXTRA FIELDS. ## REQUIRES a DATABASE UPDATE! ##
^ ## Database structure changed ##
  ALTER TABLE mos_{vm}_order_user_info ADD  `extra_field_1` varchar(255) default NULL;
  ALTER TABLE mos_{vm}_order_user_info ADD  `extra_field_2` varchar(255) default NULL;
  ALTER TABLE mos_{vm}_order_user_info ADD  `extra_field_3` varchar(255) default NULL;
  ALTER TABLE mos_{vm}_order_user_info ADD  `extra_field_4` char(1) default NULL;
  ALTER TABLE mos_{vm}_order_user_info ADD  `extra_field_5` char(1) default NULL;
+ New input field in user's shipping and billing address: phone_2
# wrong address_type in file account.shipto.php
# wrong $missing comparision for address_type_name in files account.shipto.php and admin.user_address_form.php
# showing $missing_style in file admin.user_address_form.php
# URL for editing shipping address in file admin.users.html.php
+ New variables in language file

12-08-2005 Zdenek Dvorak
+ New feature in backend: You can search products by:
  - modified date of product (You can search products which are very old and need update or which are new and need be checked)
  - modified date of product's price (Very usefull if you use price synchronizing with other system - e.g. company accountancy)
  - products with no price
+ New features: unit & packaging ## REQUIRES a DATABASE UPDATE! ##
  You can set unit of product, number units in packaging and number units in box. For showing packaging in product_details is
  needed use in flypage {product_packaging} - see html/templates/product_details/flypage.php
^ ## Database structure changed ##
  ALTER TABLE `mos_{vm}_product` ADD `product_unit` varchar(32);
  ALTER TABLE `mos_{vm}_product` ADD `product_packaging` int(11);
^ Now is possible set default product weight unit (pounds) and default product length unit (inches) in language file:
  var $_PHPSHOP_PRODUCT_FORM_WEIGHT_UOM and var $_PHPSHOP_PRODUCT_FORM_LENGTH_UOM
+ New language file for Czech translation (czechiso.php with ISO-8859-2 and czech1250.php with CP1250 codepage)
+ New parameter for modul virtuemart: moduleclass_sfx

09-08-2005 Zdenek Dvorak
# bad showing last_page in cart and show error message if no product_id (no redirecting) (ps_cart.php)
# error message befor login to show account.order_details (ps_main.php)
# error message in no tax_rate (before show Shipping Address) (ps_product_attribute.php)
# bad redirecting if URL == SECUREURL (ps_session.php)
# vertical aligning button "Add to Cart" (shop.product_details.php)

02-08-2005 soeren
# categories from the category list were not shown in the list under some circumstances
# Slashes were stripped out of text when saving a payment method (extrainfo)
^ moved the SQL Queries out of the file shop.browse.php into shop_browse_queries.php

01-08-2005 Zdenek Dvorak
# Product Type: File mod_virtuemart.php, variable _PHPSHOP_PARAMETER_SEARCH was changed to _PHPSHOP_ADVANCED_PARAMETER_SEARCH 

26-07-2005
# Tax Total wasn't calculated correctly when MULTIPLE_TAXRATES_ENABLE was set to 1 and a disount was applied
# Product Discounts weren't calculated correctly when PAYMENT_DISCOUNT_BEFORE was enabled (ps_product::get_advanced_attribute_price())
# basket.php didn't calculate the correct Tax Amount when a Coupont has been redeemed
# Coupon Discount wasn't calculated correctly (when Percentage) - ps_coupon::process_coupon_code()
# Quantity Discounts didn't show the correct price in the basket (ps_product::get_price())
# Related Products couldn't be changed in Product Form
^ more changes for Mambelfish compatiblity (added product_id / category_id to various SQL queries)

19-07-2005 soeren
# Tax Rate for other states didn't return 0 when no tax rate was specified
# Report Basic Module doing an endless loop when showing single products
# Product Form always displaying the name of the first Shopper Group, not saving the price to the correct shopper group
+ CSV: Added the "Skip the first line" feature by Christian Lehmann (thanks!)
  so you can just keep the column names in the first line of the CSV file

01-07-2005 Zdenek Dvorak
! changed ToolTip in files ps_product_type.php, shop.parameter_search_form.php, product.product_form.php and
  product.product_type_parameter_form.php
  Now is used function mm_ToolTip.
  
^ changed the PNG Fix to this solution: http://www.skyzyx.com/scripts/sleight.php
  (this doesn't let images disappear)

27-06-2005 soeren
# Checkout not working (Minimum Purchase Order Value not reached)

---- derived from mambo-phpShop 1.2 stable - patch level 3 ----

---- mambo-phpShop 1.2 stable patch level 3 released ----


</pre>
