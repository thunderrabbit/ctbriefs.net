<div class="right">


<?php 
	if($_SESSION['num_pages'] > 1)
	{
		echo "page:";
		for($page_num = 1; $page_num <= $_SESSION['num_pages']; $page_num++)
		{
			$start = ($page_num - 1) * $_SESSION['theories_per_page'];
			$class = ($start == $_GET['start']) ? "bold" : "no_class";
			echo "&nbsp;<a href='?start=" . $start . "'><span class='" . $class . "'>" . $page_num . "</span></a>";
		}
	}
?>
</div>