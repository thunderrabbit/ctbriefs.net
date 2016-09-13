<?php

/*  Write theories here */

?>
<html>
<?php
	require_once "head.php";
?>
<body>
<?php
	require_once "topbar.php";

	if($write_theory['preview'])
	{	// write.template.php should show the theory preview
		$theory = array('uid' => $_SESSION['uid'],
				'openid' => $_SESSION['openid'],
				'topic' => $theory_topic,
				'theory' => $theory_text,
				'create_date' => date(preg_replace('/%/','',$_SESSION['date_format'])));
		include "theory.template.php";

	}

	if($write_theory['form'])
	{	// write.template.php should show the theory form
?>
		<p>Write a new theory:</p>
	
			<form method="POST">
		<table><tr><td>
			<label for="theory_topic">Topic</label>
		</td><td>
			<input type="text" id="theory_topic" name="topic" value="<?=$theory_topic?>">
		</td></tr>
		<tr><td></td><td>Use no HTML.  URLs will be converted to links.
		</td></tr>
		<tr><td>
			<label for="theory">Theory</label>
		</td><td>
			<textarea id="theory" name="theory" cols="40" rows="6"><?=$theory_text?></textarea>
			(max 500 chars)
		</td></tr>
		<tr><td>
		</td><td>
			<?=$theory_form_submit_buttons?>
		</td></tr>
		</table>
			</form>
<?php
	}
?>
</body>
</html>
