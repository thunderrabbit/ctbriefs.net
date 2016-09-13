<div class="right" id="verify-form">
<?php
	if(logged_in())
	{
?>
	      <form method="post" action="index.php">
	        <a href="<?=$_SESSION['openid']?>"><?=ctrim($_SESSION['openid'])?></a>
		<input type="hidden" name="logout" value="logout">
	        <input type="submit" value="logout" />
	      </form>

<?php
	}
	else
	{
?>
	      <form method="post" action="try_auth.php">
	        <input type="hidden" name="action" value="verify" />
		<label for="openid_identifier"><a href="<?=$GLOBALS['openID_info_URL']?>">OpenID</a>:</label>
	        <input type="text" class="openidlogo" id="openid_identifier" name="openid_identifier" value="" size="20" />
	        <input type="submit" value="confirm" />
	      </form>


<?php /* I don't know what PAPE is for, so I'm not showing this part 
	Also blocked out some code in finish_auth.php
        <p>Optionally, request these PAPE policies:</p>
        <p>
        <?php foreach ($GLOBALS['pape_policy_uris'] as $i => $uri) {
          print "<input type=\"checkbox\" name=\"policies[]\" value=\"$uri\" />";
          print "$uri<br/>";
        } ?>
        </p>
END not showing PAPE stuff */
?>
<?php
	}
?>
</div>

