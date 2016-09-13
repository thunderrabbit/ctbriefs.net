<?php
	include "not_head.php";		// this file should not be called in the location bar
?>

<table><tr><td>
<img src="/images/icon-thumb-up.gif" alt="Yea" title="Yea" /><br/><?=$theory['plus_votes']?>
</td><td>
<img src="/images/icon-thumb-down.gif" alt="Nay" title="Nay" /><br/><?=$theory['minus_votes']?>
</td><td>|
</td><td>
<img src="/images/exclamation.png" alt="Spam" title="Spam"/><br/><?=$theory['spam_votes']?>
</td></tr>
</table>

<p class='must_login'>Confirm your<br/><a href="<?=$GLOBALS['openID_info_URL']?>">OpenID</a> to<br/>rate theories.</p>


