<?php
require_once "common.php";      // super basic stuff, including openID login
require_once "initialize.php";  // this is after they are logged in

require_once "theories.php";

$theories = new theories();	// object that will get theories from DB
$theory_array = array();	// array that will store theories

	$write_mypage = false;	// do not write the user's page

	if(logged_in())
	{
		$write_mypage = true;						// mypage.template.php should show their page
		$where = array('uid' => $_SESSION['uid'], 'status' => 'ok');  	// only select their theories that are ok
		$theory_array = $theories->fetch_theories($where,true);   	// Where will be an array of columns and values; true = wrap URLS with html
		switch (count($theory_array))
		{
			case 0: $theory_note = "You've written no theories (or they've been marked as spam)."; break;
			case 1: $theory_note = "Here is your theory:"; break;
			default: $theory_note = "Here are your theories"; break;
		}
	}
	else
		$error = "If you want to view your page, you must confirm your OpenID.";

	include "template/mypage.template.php";
?>
