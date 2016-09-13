<?php
require_once "common.php";      // super basic stuff, including openID login
require_once "initialize.php";  // this is after they are logged in

/*  About ctbriefs.net; *not* about openid */

?>
<html>
<?php
	require_once "head.php";
?>
  <body>
<?php
	require_once "topbar.php";
?> 

<p>Conspiracy Theory Briefs</p>

<p>Did we really land on the moon?  Who shot JFK?  What causes crop circles?  Livestock mutilations?  Is Obama our savior?</p>

<p>The official answers are one way to explain what happened.  Let's hear yours!  You can write your theory on any topic, and soon vote Yea or Nay on
theories written by others.  Any conspiracy theory is okay.</p>

<p>Keep it brief; you have only 500 characters to write! No HTML; just straight text (<a href="http://en.wikipedia.org/wiki/UTF-8">UTF-8</a>). 
Preview and double-check your entry; they cannot be edited once submitted.  But you can delete (*) theories that you've written.</p>

<p>Feel free to browse all the theories you want.  When you're ready to <?php if(!logged_in()) echo "vote or "; ?>write your own theory, 
<?php
	if(logged_in())
		echo "just click <a href='write.php'>write</a>.</p>";
	else
		echo "just confirm your OpenID.</p>";
?>

<p>(*) deleted theories are not removed from the DB; they are just marked as deleted and won't show up in normal searches.</p>

<p>CTbriefs.nET does not record any personal information except your openID.  No names, no emails, so you're relatively anonymous.  Soon all the source
code will be free.</p>

<p>Icons by <a href="http://famfamfam.com/lab/icons/silk/">famfamfam.com</a>.</p>
</body> </html>
