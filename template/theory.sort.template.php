<?php
	/**************************************************************************************************/
	/*
	/*	This template is only called by theory.sort+pagelist.template.php, which is included by index.template.php and mypage.template.php
	/*
	/*	This template simply shows the ways by which we can sort theories.  It basically interfaces with theories.php via initialize.php
	/*
	/*********************************************************************************************************/

	if($_SESSION['num_records'] > 1)
	{
	
		$sort_by = $_SESSION['sort_by'];	// figure out by which variable this user wants to sort
		$sort_dir = $_SESSION['sort_dir'];	// what direction do they want to sort
		$newsortdir = ($sort_dir == "desc") ? "asc" : "desc";
		$arrow_html = ($sort_dir == "desc") ? "<img src='/images/arrow_down.png' alt='descending' class='none'>" : "<img src='/images/arrow_up.png' alt='ascending' class='none'>";
?>
	
		<div class="left">
			<?php // this form is only submitted via the javascript functions below  ?>
			<form method="POST" id="sortform" action="<?=$_SERVER['SCRIPT_URL']?>"> 
			<input type="hidden" name="sort_by" value="<?=$sort_by?>" id="sortby" />
			<input type="hidden" name="sort_dir" value="<?=$sort_dir?>" id="sortdir" />
			<input type="hidden" name="action" value="re-sort" />
			</form>
			
			sort by:
			
			<script type="text/javascript">
				function setsortby (sortbyvar)
				{
					document.getElementById('sortby').value = sortbyvar;
					document.getElementById('sortform').submit();
				}
			
				function setsortdir (sortdirvar)
				{
					document.getElementById('sortdir').value = sortdirvar;
					document.getElementById('sortform').submit();
				}
			
			</script>
			
<?php
			foreach (array('Popular', 'Unpopular', 'OpenID', 'Topic', 'Date') as $sortable)
			{
				if(strtolower($sort_by) == strtolower($sortable))
					echo "\n<a href='#' class='none' onclick=\"setsortdir('" . $newsortdir . "');\">" . $arrow_html . "<b>" . $sortable . "</b>" . $arrow_html . "</a>"; 
				else
					echo "\n<a href='#' onclick=\"setsortby('" . strtolower($sortable) . "');\">" . $sortable . "</a>";
			}
?>
		</div>
<?php
	}
?>
