<?php

  session_start();
  include('sql_connect.php');

  
  $thesis = $_POST['thesis_id'];
  $title = $_POST['title'];
  $abstract = $_POST['abstract'];
  $publication = $_POST['date_published'];
  $category = $_POST['category_id'];
  $researcher = $_POST['researcher_id'];
  $faculty = $_POST['faculty_id'];

  

  if($title && $abstract && $publication && $category && $researcher && $faculty) {
     mysql_query("UPDATE thesis SET title = '$title', abstract = '$abstract', date_published = '$publication', 
      category_id = '$category', researcher_id = '$researcher', faculty_id = '$faculty' WHERE thesis_id = '$thesis'");
     $_SESSION['success'] = 'UPDATED';
  } else {
    $_SESSION['error'] = 'ERROR';
  }
?>