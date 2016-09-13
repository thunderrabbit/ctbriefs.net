<?php
	include "not_head.php";		// this file should not be called in the location bar
?>
<form method="POST">
<input type="hidden" name="tid" value="<?=$theory['tid']?>" />
<table><tr><td>
<input type="image" name="vote" src="/images/icon-thumb-up.gif" alt="Yea" title="Yea" value="Y"/><br/><?=$theory['plus_votes']?>
</td><td>
<input type="image" name="vote" src="/images/icon-thumb-down.gif" alt="Nay" title="Nay" value="N"/><br/><?=$theory['minus_votes']?>
</td><td>|
</td><td>
<input type="image" name="vote" src="/images/exclamation.png" alt="Spam" title="Spam" value="Spam"/><br/><?=$theory['spam_votes']?>
</td>
<?php 
if ($vote_show_delete) 
{
?>
<td>
	<input type="image" name="action" src="/images/cross.png" alt="Delete" title="Delete" value="delete"/>
</td>
<?php 
}
?>
</tr></table>
</form>
