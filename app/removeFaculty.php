<?php

  session_start();
  include('connectToServer.php');

  $faculty_id = $_GET['id'];
  $faculty_name = $_GET['name'];

  if ($faculty_id) {
    $faculty = mysql_query("SELECT * FROM faculty_profiles WHERE faculty_id='$faculty_id'");
    $faculty = mysql_fetch_assoc($faculty);
    mysql_query("DELETE from faculty WHERE faculty_id = '$faculty_id'");
    $_SESSION['alert'] = "You deleted " . $faculty_name . ".";
    header('location: ../admin.php');
    exit;
  } else {
    exit;
  }

?>