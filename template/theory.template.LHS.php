<?php
	include "not_head.php";		// this file should not be called in the location bar

	/******************************************************************************/
	/*
	/*   Look at the $thory['tid'] and ['uid'] to tell what we should do here.
	/*   no tid means it's in preview mode
	/*   otherwise look to see if they are not logged in (cannot rate)
	/*   if they are logged in, they can rate
	/*   unless it's their theory: they can't rate, but they can delete
	/*
	/*******************************************************************************/

	if(!isset($theory['tid']))
	{
		if($_POST['action'] == "preview")
			include "theory.template.preview.php";
		// else they are submitting.  We could add the rate (therefore delete) buttons, but there is no ['tid'] in theory, so the buttons don't work.
		// preferably, if we could get ['tid'] in theory, then we wouldn't need the if($_POST['action'] == "preview") above, and it would show their rate/delete buttons nicely
	}
	else
	{
		if(!logged_in())
		{
			include "theory.template.must.login.php";  // rating coming soon<br/>(and you'll have to login)";   // "confirm ID to rate";
		}
		else
		{
			$vote_show_delete = (is_admin() OR $theory['uid'] == $_SESSION['uid']) ? true : false;
			include "theory.template.rate.php";
		}
	}
?>