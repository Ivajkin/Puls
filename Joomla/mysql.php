
	<?php
///////////////////////////////////////////////////
// Joomla-R-Us
//
// Connection test to external MySQL database
//
///////////////////////////////////////////////////

// Configurations below!!

/////////  Bootstrap the Joomla Framework //////////////

define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );


/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$mainframe =& JFactory::getApplication('site');

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// set the language
$mainframe->initialise();

JPluginHelper::importPlugin('system');

// trigger the onAfterInitialise events
$mainframe->triggerEvent('onAfterInitialise');


/////////////////////////////////////////////////////



///////////// Configure this ////////////////////////

$hostname     = 'sql109.byethost5.com';
$username     = 'b5_10620585';
$password     = 'kre8stuf';
$database     = 'b5_10620585_jos';

///////////// End Configure ////////////////////////


// Try connecting to the database

$option = array (); 
$option ['driver'] = 'mysql'; 

$option ['host'] = $hostname;
$option ['user'] = $username;
$option ['password'] = $password;
$option ['database'] = $database;
$option ['prefix'] = '';
$db = JFactory::getDBO ();
$db = & JDatabase::getInstance ($option);
if ( get_class($db) == 'JDatabaseMySQL' ) {
    echo '<b><font color="green">OK!</font></b>';
} else {
    echo '<b><font color="red">FAILED</font></b> : ' . $db->message;
}

?> 