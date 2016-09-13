<?php
ini_set(session.use_only_cookies, 1);	// disallow php session IDs to be sent in URL
ini_set(session.cookie_lifetime, 0);	// clear cookies when browser closes
session_start();
header('Content-Type: text/html; charset=utf-8');


$debug = array(1, 
'login' => 5, 
'theories' => 5,
'post' => 5,
'votes' => 5,
'navigation' => 5, 
'openid' => 5, 
'debug' => 0, 
'users' => 4,
'db' => 3
);  // this comes before global_config so global_config can cancel debug on live server

require_once "../settings/ctbriefs.net/global_config.php";

/*************************************************************************************************************

	debug_for tells if we should print debug information for a particular aspect of the website.

	Example usage:
	if(debug_for('navigation',2))
		echo "important navigation information.";

	0 = no debug information; higher numbers mean more detailed information

	the $debug array above tells what level we should be displaying for the various aspects:
	basically if (debug[0] AND debug[aspect]) then print the debug info about that aspect

**************************************************************************************************************/
function debug_for($which, $level = 1)
{
	// display debug information if the debug-level for that part is set
	if($GLOBALS['debug'][0] && $GLOBALS['debug']["debug"])
	{
		// debugging debug
		echo "debug:<pre>\n";
		print_r( $GLOBALS['debug'] );
		echo "\ndebug-what: " . $which . "  debug-level: " . $level . "</pre>";
	}
	if($GLOBALS['debug'][0])
		return ($GLOBALS['debug'][$which] >= $level);
	else
		return false;
}

$class_path = dirname(__FILE__) . "/class";		// Rob added this to allow classes to be defined in the class directory
$path_extra = dirname(dirname(dirname(__FILE__)));	// this is from the OpenID sample code
$path = ini_get('include_path');
$path = $class_path . PATH_SEPARATOR . $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

$site_title = "Conspiracy Theory Briefs: What do YOU think happened?";
$openID_info_URL = "http://www.google.com/search?q=openid";   // eventually make this an internal page explain who/what/when/whyfor

/* options for $user_status: guest, logged_in, admin */
$user_statii = array("guest" => 0, "logged_in" => 1, "admin" => 2);

/******************************************

   Easily print things surrounded with <pre> tags; 
   this is my preferred debugging method

******************************************/
function print_rob($thing,$string="")
{
	if($string != "")
		echo "<p>" . $string . "</p>";
	echo "<pre>";
	print_r($thing);
	echo "</pre>";
}

/* these will point to ____.php versions, e.g.  about.php, mypage.php  */
$navigation_for_all_users = array('about');
$navigation_for_logged_in_users = array('mypage','write');
$navigation_for_admins = array('admin');

function exception_handler($exception) {
	$error = "Uncaught exception: " . $exception->getMessage();;
	print "<div class=\"error\">$error</div>";
	exit(0);
}

set_exception_handler('exception_handler');


/******************************************************  below this line is all from the OpenID sample code, with slight changes to trust_root and return_to  ******************************************/

function displayError($message) {
    $error = $message;
    require_once 'index.php';
    exit(0);
}

function doIncludes() {
    /**
     * Require the OpenID consumer code.
     */
    require_once "Auth/OpenID/Consumer.php";

    /**
     * Require the "file store" module, which we'll need to store
     * OpenID information.
     */
    require_once "Auth/OpenID/FileStore.php";

    /**
     * Require the Simple Registration extension API.
     */
    require_once "Auth/OpenID/SReg.php";

    /**
     * Require the PAPE extension module.
     */
    require_once "Auth/OpenID/PAPE.php";
}

doIncludes();

global $pape_policy_uris;
$pape_policy_uris = array(
			  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			  PAPE_AUTH_MULTI_FACTOR,
			  PAPE_AUTH_PHISHING_RESISTANT
			  );

function &getStore() {
    /**
     * This is where the example will store its OpenID information.
     * You should change this path if you want the example store to be
     * created elsewhere.  After you're done playing with the example
     * script, you'll have to remove this directory manually.
     */
    $store_path = "/tmp/_php_consumer_test";

    if (!file_exists($store_path) &&
        !mkdir($store_path)) {
        print "Could not create the FileStore directory '$store_path'. ".
            " Please check the effective permissions.";
        exit(0);
    }

    return new Auth_OpenID_FileStore($store_path);
}

function &getConsumer() {
    /**
     * Create a consumer object using the store object created
     * earlier.
     */
    $store = getStore();
    return new Auth_OpenID_Consumer($store);
}

function getScheme() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
        $scheme .= 's';
    }
    return $scheme;
}

function getReturnTo() {
    return sprintf("%s://%s%sfinish_auth.php",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   dirname($_SERVER['PHP_SELF']));
}

function getTrustRoot() {
    return sprintf("%s://%s%s",
                   getScheme(), $_SERVER['SERVER_NAME'],
                   dirname($_SERVER['PHP_SELF']));
}

?>