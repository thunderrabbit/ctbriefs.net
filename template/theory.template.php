<?php
	include "not_head.php";		// will refresh to index.php if this is called in location bar
	if($theory['uid'] == $_SESSION['uid'])
		$whose = "my_";		// prepend to class names so CSS knows which classes to use
	else
		$whose = "";		// not my theory!

?>

<div class="<?=$whose?>theory_div">
<table class="<?=$whose?>theory_table">
<?php if(debug_for('theories',4)) { echo "<tr><td colspan='3'>"; print_rob($theory); echo "</td></tr>"; } ?>
<tr>
<td rowspan="2"><div class="<?=$whose?>lhs_div">

<?php include "theory.template.LHS.php"?>

</div></td>
	<td><span class="<?=$whose?>openid_span"><a href="<?=$theory['openid']?>"><?=ctrim($theory['openid'])?></a></span> theory 
	about <span class="<?=$whose?>topic_span"><?=$theory['topic']?></span>
	on <span class="<?=$whose?>date_span"><?=$theory['create_date']?></span></td></tr>
<tr><td><div class="<?=$whose?>theory_text"><?=$theory['theory']?></div></td></tr>
</table>
</div>