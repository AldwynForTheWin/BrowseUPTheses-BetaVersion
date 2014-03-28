<?php
	session_start();
	include("connectToServer.php");

	$username = $_POST['username'];
	$password = $_POST['password'];

	$result1 = mysql_query("SELECT * FROM faculty WHERE fac_username='$username' AND fac_password='$password'");
	if (mysql_num_rows($result1) == 1) {
		$faculty = mysql_fetch_assoc($result1);
		$_SESSION['user_id'] = 'f_' . $faculty['faculty_id'];
		$hasProfile = mysql_query("SELECT COUNT(*) FROM faculty_profiles WHERE faculty_id = " . $faculty['faculty_id']);
		if (mysql_fetch_assoc($hasProfile)['COUNT(*)'] == 0) {
			header('location: ../setProfile.php');
		} else {
			header('location: ../faculty.php');
		}
	} else {
		$result = mysql_query("SELECT * FROM admin WHERE admin_username='$username' AND admin_password='$password'");
		if (mysql_num_rows($result) == 1) {
			$admin = mysql_fetch_assoc($result);
			$_SESSION['user_id'] = 'a_'.$admin['admin_username'];
			header('location: ../admin.php');
		} else {
			$_SESSION['error'] = 0;
			header('location: ../login.php');
		}
	}
?>