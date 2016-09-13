<html>
<?php
	require_once "head.php";
?>
<body>
<div id="content">
<?php
	require_once "topbar.php";   // this will draw the navigation, title, and login status
	require "theory.sort+pagelist.template.php";
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
	require "theory.sort+pagelist.template.php";
?>
</div>
</body>
</html>
