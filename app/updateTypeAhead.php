<?php

	include('connectToServer.php');
	$input = $_GET['q'];
	$result_array = array();

	$categories = mysql_query("SELECT category_name AS val FROM category WHERE category_name LIKE '$input%' LIMIT 10");	
	while ($category = mysql_fetch_assoc($categories)) {
		array_push($result_array, $category);
	}

	$tags = mysql_query("SELECT tag_name AS val FROM tags WHERE tag_name LIKE '$input%' LIMIT 10");
	while ($tag = mysql_fetch_assoc($tags)) {
		array_push($result_array, $tag);
	}
	sort($result_array);

	$data['vector'] = $result_array;
	echo json_encode($data);

?>