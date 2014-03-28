<?php

	$con = mysql_connect('localhost', 'root', '') or die("Cannot connect to server.");
	mysql_select_db('browseuptheses', $con) or die("Cannot connect to database.");

?>