<?php
require_once "common.php";      // super basic stuff, including openID login
require_once "initialize.php";  // this should only run after openID login is processed
if($_GET['error']) $error = $_GET['error'];	// .htaccess will refresh to index.php?error=XXX where XXX is an HTTP error.  This line will make the error displayed in topbar.php

require_once "theories.php";

$theories = new theories();	// object that will get theories from DB
$theory_array = array();	// array that will store theories

$where = array('status' => 'ok');  // only select theories that are ok
$sortby = $_SESSION['sort by'];		// something like array('date' => 'DESC', 'uid' => 'ASC')
$limit = array('start' => 1, 'qty' => 20);

// eventually make this include all three vars
// $theory_array = $theories->fetch_theories($where, $sortby, $limit);   // $where is the where clause
$theory_array = $theories->fetch_theories($where,true);   // $where is the where clause; true = wrap URLS in href tags


include "template/index.template.php";
?>
