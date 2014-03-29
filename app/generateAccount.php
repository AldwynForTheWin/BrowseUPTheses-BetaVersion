<?php 
  session_start();
  include('connectToServer.php');

  if ((!isset($_POST['quantity'])) || (!$_POST['quantity']) || ($_POST['quantity'] <= 0)) {
    header('location: indexgenerateAccount.php');
    exit;
  }

  $quantity = $_POST['quantity'];
  $origQuantity = $quantity;

  while ($quantity != 0) {
    $temp_id = mysql_query("SELECT * FROM  faculty WHERE faculty_id = (SELECT MAX(faculty_id)  FROM faculty)");
    $temp2_id = mysql_fetch_assoc($temp_id);
    $faculty_username = "UPCFACULTY" . ($temp2_id['faculty_id'] + 1);
    $faculty_pw = generatePassword();
    mysql_query("INSERT INTO faculty (fac_username,  fac_password) 
    VALUES ('$faculty_username', '$faculty_pw')");
    $quantity--;
  } 
  $_SESSION['alert'] = ($origQuantity > 1) ? 'You have added ' . $origQuantity . ' new faculty accounts.' : 'You have added a new faculty account';
  header('location: ../admin.php');

  function generatePassword() {
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $length = 6;
    $i = 1;
    $pw = "";
    while ($i <= 6) {
      $n = rand(0, strlen($characters)-1);
      $pw .= $characters[$n];
      $i++;
    }
    return $pw;
  }
?>