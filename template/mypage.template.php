<html>
<?php
	require_once "head.php";
?>
<body>
<?php
	require_once "topbar.php";

	if($write_mypage)		// mypage.template.php should show their page
	{
		echo "<p>" . $theory_note . "</p>";
		require_once "theory.sort+pagelist.template.php";
?>


		<div id="all_theories">
<?php
			foreach($theory_array as $num => $theory)
			{
				include "theory.template.php";
			}
?>
		</div>
<?php
	}
	else
	{	// we've been instructed not to show this page (they haven't logged in)
?>

<?php
	}
?>
</body>
</html>
