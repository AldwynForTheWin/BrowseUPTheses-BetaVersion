<?php
	session_start();
	include('connectToServer.php');
	$count;

	foreach ($_POST['faculty_id'] as $id) {
		mysql_query("DELETE FROM faculty WHERE faculty_id = '$id'");
		$count++;
	}
	$_SESSION['alert'] = ($count > 1) ? "You have deleted " .$count. " faculty accounts." : "You have deleted a faculty.";
	header('location: ../admin.php');
?>