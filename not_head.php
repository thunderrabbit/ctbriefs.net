<?php
	/*****************************************/
	/*   this code was based on head.php     */
	/* because some template.phps are called */
	/* multiple times and needs no head tag. */
	/* This refresh will cause a 404 which   */
	/* .htaccess will refresh to index.php   */
	/*****************************************/

	// look for ".template" as in mypage.template.php and take it out.  We should only visit mypage.php
	if(strstr($_SERVER["SCRIPT_URL"],".template"))
	{
//		$refresh_to = str_replace(".template", "", $_SERVER["SCRIPT_URL"])  // take .template out and refresh the screen


/*	this was outside the <?php tags
		<head><meta http-equiv="refresh" content="0;url=<?=$refresh_to?>">
		<script language=Javascript1.0>window.location.href="<?=$refresh_to?>"</script></head></html>  
*/

?>
		<head><meta http-equiv="refresh" content="0;url=/">
		<script language=Javascript1.0>window.location.href="/"</script></head></html>  
<?php
		exit;
	}
?>
