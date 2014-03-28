<?php
	session_start();
	include('connectToServer.php');

	$thesis_id = $_GET['id'];
	$thesis_title = $_GET['title'];
    
	if ($thesis_id) {
		mysql_query("DELETE from thesis WHERE thesis_id = '$thesis_id'");
		$_SESSION['alert'] = "You successfully deleted <em>".$thesis_title."</em>.";
		header('location: ../faculty.php');
	}
?>