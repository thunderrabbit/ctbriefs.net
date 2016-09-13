<?php
	// look for ".template" as in mypage.template.php and take it out.  We should only visit mypage.php
	if(strstr($_SERVER["SCRIPT_URL"],".template"))
	{
		$refresh_to = str_replace(".template", "", $_SERVER["SCRIPT_URL"])  // take .template out and refresh the screen
?>
		<head><meta http-equiv="refresh" content="0;url=<?=$refresh_to?>">
		<script language=Javascript1.0>window.location.href="<?=$refresh_to?>"</script></head></html>
<?php
		exit;
	}
?>
<head>
<meta name="description" content="Conspiracy Theory briefs" />
<meta name="keywords" content="CT, conspiracy, theory, briefs" />
<title><?=$GLOBALS['site_title']?></title>
<link rel="stylesheet" href="/css/main.css" type="text/css" />
</head>
