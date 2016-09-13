<?php
require_once "common.php";      // super basic stuff, including openID login
require_once "initialize.php";  // this is after they are logged in
require_once "users.php";	// to get user counts and such

	$write_admin = false;	// do not write the admin page

	if(!is_admin())
		$error = "Must be logged in as an admin to view this page.";
	else
	{
		$write_admin = true;		// mypage.template.php should show their page

		$users = new users();
		$num_users = $users->num_users();
		$num_returning_users = $users->num_voting_users();
		$num_theorists = $users->num_theorists();
	}

	include "template/admin.template.php";
?>
