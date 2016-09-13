<html>
<?php
	require_once "head.php";
?>
<body>
<?php
	require_once "topbar.php";

	if($write_admin)		// mypage.template.php should show their page
	{
?>
		<p><?=$num_users?> users have logged in to this system. <?=$num_returning_users?> have voted and <?=$num_theorists?> have written theories.</p>

<p>this is a local change on my computer</p>
<?php
	}
?>
</body>
</html>


