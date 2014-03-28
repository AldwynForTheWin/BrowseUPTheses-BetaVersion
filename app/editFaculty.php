<?php
	session_start();
	include('connectToServer.php');

	$faculty_id = explode('_', $_SESSION['user_id'])[1];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	$firstname = $_POST['fac_fname'];
	$lastname = $_POST['fac_lname'];
	$midname = $_POST['fac_mname'];
	$department_id = $_POST['department'];
	$gender = $_POST['gender'];

	$faculty_login = mysql_query("SELECT * FROM faculty WHERE faculty_id = '$faculty_id'");
	if ($password == $confirm) {
		$faculty_profiles = mysql_query("SELECT * FROM faculty_profiles WHERE faculty_id = '$faculty_id'");
        if (mysql_num_rows($faculty_profiles) == 1) {
            mysql_query(
                "UPDATE faculty_profiles SET
                    fac_fname = '$firstname',
                    fac_lname = '$lastname',
                    fac_mname = '$midname',
                    department_id = '$department_id',
                    gender = '$gender'
                WHERE faculty_id = '$faculty_id'");
            mysql_query("UPDATE faculty SET fac_username = '$username', fac_password = '$password' WHERE faculty_id = '$faculty_id'");
        } else {
            mysql_query("INSERT INTO faculty_profiles VALUES('$faculty_id', '$firstname', '$lastname', '$midname', '$department_id', '$gender')");
            mysql_query("UPDATE faculty SET fac_username = '$username', fac_password = '$password' WHERE faculty_id = '$faculty_id'");
        }
        $_SESSION['alert'] = 'Your profile has been edited.';
        header('location: ../faculty.php?id='.$faculty_id);
	} else {
        $_SESSION['alert'] = 'Incorrect password.';
        header('location: ../setProfile.php');
    }

?>