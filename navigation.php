<div class="left" id="navigation-bar">
<div note="without this nested div, the box / shading doesn't appear in FF.  not tested in other browsers">
<?php

if(debug_for('navigation'))
{
	echo "in navigation:";
	echo "<pre>";
	print_r($GLOBALS['navigation_for_this_user']);
	echo "</pre>";
}

/*	One idea is for the navigation to actually be a hash of name and link; that way home would be included automagically */
	echo "<a href='/'>home</a> ";

	if(isset($GLOBALS['navigation_for_this_user']))
	foreach($GLOBALS['navigation_for_this_user'] as $page)
	{
		echo "<a href='" . $page . ".php'>" . $page . "</a> ";
	}
?>
</div>
</div>

