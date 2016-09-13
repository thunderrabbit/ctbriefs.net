<?php
	include "not_head.php";		// this file should not be called in the location bar
?>
<table class="topbar">
<tr>
<td>
<?php
	require_once "navigation.php";
?>
</td>
<td>
<!-- <div class="center"><h2><?=$GLOBALS['site_title']?></h2></div> -->
<img src="/images/logo_shadow.png" title="CTbriefs.nET" class="logo" />
</td>
<td>
<?php
	require_once "loginbox.php";
?>
</td></tr></table>

<?php
if (isset($msg)) { print "<div class=\"alert\">$msg</div>"; }
if (isset($error)) { print "<div class=\"error\">$error</div>";  }
if (isset($success)) { print "<div class=\"success\">$success</div>"; }
?>
