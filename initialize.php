<?php

if ($_SERVER["SCRIPT_URL"] == "/finish_auth.php")
{
?>
<head><meta http-equiv="refresh" content="0;url=/">
	<script language=Javascript1.0>window.location.href="/"</script></head></html>
<?php
	exit;
}


if(debug_for('post'))
	print_rob($_POST, "we received this in the post");

/*********************

  Initialize is the first thing run after common, which handles the openID side of logging in.  After all that is done, they will still be logged in
or out or whatever they were on the previous page.  Here, we check if the logout button was clicked, and wipe the openID from memory and turn them
into a guest again.

*********************/
if($_POST['logout'] == "logout")
{
	unset($_SESSION['openid']);			// this will make a browswer refresh think they are logged out
	unset($_SESSION['uid']);			// this will keep their theories from having special CSS classes
}

/*****************************************************************

	See if they successfully logged in

*****************************************************************/

if(debug_for('openid'))
	echo $_SESSION['openid response'] . " for " . $_SESSION['openid'];
if(isset($_SESSION['openid response']))
{
	switch($_SESSION['openid response'])
	{
		case "success" :
			/* User has successfully logged in, so insert or update the entry in user DB */
			require_once "users.php";
			$users = new users();
			$user_status = $users->user_logged_in($_SESSION['openid']);
			$users->__destruct();  // this doesn't actually work, but I just want to specify the instance is unused below
		
			switch($user_status)
			{
				case "ok" : $success = "logged in as " . $_SESSION['openid'];	// tell them they logged in
					break;
				default : $error = "This account has been banned.";
					unset($_SESSION['openid']);
					unset($_SESSION['uid']);
			}
		break;
		default:
			$msg = $_SESSION['openid response'];	// alert the user as to why the are not logged in
	}
	unset($_SESSION['openid response']);	// skip this block next time 
}

/***********************************************************

	at this point, we have read their preferences from user DB.  If they don't exist, then fill them in here

****************************************************************/

if(!isset($_SESSION['date_format']))
	$_SESSION['date_format'] = DEFAULT_DATE_FORMAT;

if(!isset($_SESSION['theories_per_page']))
	$_SESSION['theories_per_page'] = DEFAULT_NUM_THEORIES_PER_PAGE;

if(!isset($_SESSION['sort_by']))
{
	$_SESSION['sort_by'] = DEFAULT_SORT_BY;
	$_SESSION['sort_dir'] = DEFAULT_SORT_DIR;
}

$user_status = $user_statii["guest"];			// assume they are just a guest
if($_SESSION['openid'] != "")
{
	$user_status = $user_statii["logged_in"];	// according to finish_auth.php, they logged in
	if($admin_hash[$_SESSION['openid']])
	{
		$user_status = $user_statii["admin"];	// according to admin_hash.php, they are admin
	}
}

/*****************************************************************

	Next, we check to see if they hit delete.  If they did and are admin, then that's fine.  
	If a user tries to delete another user's theory (via form manipulation), they should be banned.. 
	oh shit but what if they somehow spoof their uid?  can that happen??

********************************************************************/

if($_POST['action'] == "delete")
{
	include_once("theories.php");
	$theory = new theories();
	$theory->theory_set_status($_POST['tid'],'deleted');
}

/********************************************************

	Sort

*********************************************************/

if($_POST['action'] == "re-sort")
{
	$_SESSION['sort_by'] = $_POST['sort_by'];	// these will be read by theories.php->fetch_theory
	$_SESSION['sort_dir'] = $_POST['sort_dir'];
}

/*****************************************************************

	Did they hit a voting button?

********************************************************************/

if($_POST['vote'])
{
	include_once("theories.php");
	$theory = new theories();
	$theory->vote_theory($_POST['tid'], $_POST['vote']);
}

/***********************

   Easy way to check if they are logged in

***********************/
function logged_in()
{
	if(debug_for('login',3))
	{
		echo "in logged_in(): user status is " . $GLOBALS['user_status'];
		echo " and a guest is " . $GLOBALS['user_statii']["guest"] . ".";
	}
	return($GLOBALS['user_status'] > $GLOBALS['user_statii']["guest"]);
}

/***********************

   Easy way to check if they are administrative types

***********************/
function is_admin()
{
	if(debug_for('login',3))
	{
		echo "in is_admin(): user status is " . $GLOBALS['user_status'];
		echo " and a logged in user is " . $GLOBALS['user_statii']["logged_in"] . ".";
	}
	return($GLOBALS['user_status'] > $GLOBALS['user_statii']["logged_in"]);
}

/********************************************************************

    Now, according to their role, set up the components of their navigation menu

***************************************************************************/
$navigation_for_this_user = $navigation_for_all_users;
if(logged_in())
	$navigation_for_this_user = array_merge($navigation_for_all_users, $navigation_for_logged_in_users);
if(is_admin())
	$navigation_for_this_user = array_merge($navigation_for_all_users, $navigation_for_logged_in_users, $navigation_for_admins);

if(debug_for('navigation'))
{
	echo "nav:";
	echo "<pre>";
	print_r($navigation_for_this_user);
	echo "</pre>";
}


function ctrim($string)
{
	if (strlen($string) > MAX_OPENID_DISPLAY)
		return substr($string,0,15) . "[...]" . substr($string,-15);
	else
		return $string;
	
}

?>