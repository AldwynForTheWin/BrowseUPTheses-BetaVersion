<?php
	session_start();
	include('app/connectToServer.php');
	
	$query = (isset($_POST['search'])) ? $_POST['search'] : $_GET['search'];
	$category = (isset($_POST['category'])) ? $_POST['category'] : $_GET['category'];

	$now = date("Y-m-d H:i:s");
    $term = mysql_real_escape_string(strip_tags(trim($_GET['search'])));

    if (mysql_result(mysql_query("SELECT COUNT(category_id) FROM category WHERE category_name = '$term'"), 0) > 0) {
        mysql_query("UPDATE category SET counter = counter+1, last_search = '$now' WHERE category_name = '$term'");
    }

	$sql = "SELECT * FROM thesis
		 LEFT JOIN thesis_tags ON thesis.thesis_id = thesis_tags.thesis_id
		 LEFT JOIN tags ON thesis_tags.tag_id = tags.tag_id
		 LEFT JOIN thesis_researchers ON thesis.thesis_id = thesis_researchers.thesis_id
		 LEFT JOIN researcher ON thesis_researchers.researcher_id = researcher.researcher_id
		 LEFT JOIN thesis_faculty ON thesis.thesis_id = thesis_faculty.thesis_id
		 LEFT JOIN faculty ON thesis_faculty.faculty_id = faculty.faculty_id
		 LEFT JOIN faculty_profiles ON faculty.faculty_id = faculty_profiles.faculty_id
		 LEFT JOIN thesis_categories ON thesis.thesis_id = thesis_categories.thesis_id 
	 	 LEFT JOIN category ON	thesis_categories.category_id = category.category_id
	 	 LEFT JOIN thesis_department ON thesis.thesis_id = thesis_department.thesis_id
	 	 LEFT JOIN department ON thesis_department.department_id = department.department_id
		"; 

	$thesis_query = "thesis.title LIKE '%".$query."%'";
	$tag_query = "tags.tag_name LIKE '%".$query."%'";
	$date_query = "thesis.date_published LIKE '%".$query."%'";
	$researcher_query = "researcher.res_fname LIKE '%".$query."%' 
			OR researcher.res_lname LIKE '%".$query."%' 
			OR researcher.res_mname LIKE '%".$query."%'";
	$faculty_query = "faculty_profiles.fac_fname LIKE '%".$query."%' 
			OR faculty_profiles.fac_lname LIKE '%".$query."%' 
			OR faculty_profiles.fac_mname LIKE '%".$query."%'";
	$department_query = "department.dep_name LIKE '%".$query."%'";
	$category_query = "category.category_name LIKE '%".$query."%'";
	if ($category == "Thesis") {
		$sql .= "WHERE ($thesis_query)";
	} else if ($category == "Researcher") {
		$sql .= "WHERE ($researcher_query)";
	} else if ($category == "Faculty") {
		$sql .= "WHERE ($faculty_query)";
	} else if ($category == "Department") {
		$sql .= "WHERE ($department_query)";
	} else if ($category == "Date") {
		$sql .= "WHERE ($date_query)";
	} else {
		$sql .= "WHERE ($thesis_query) OR ($researcher_query) OR ($faculty_query)
		 OR ($category_query) OR ($department_query) OR ($date_query) OR ($tag_query)";
	}

	$distinct = array();
	$prevs = array();
	$result = mysql_query($sql);
	while ($item = mysql_fetch_assoc($result)) {
		$cur = $item['title'];
	  	if (!(in_array($cur, $prevs))) {
	  		array_push($distinct, $item);
	  		array_push($prevs, $cur);
	  	}
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include('fronts/meta.php'); ?>
		<title>Browse UP Theses | UP Cebu's Online Theses Archive</title>
		<style type="text/css">
			#thesisSummary {
			    text-align:justify; 
			    float:left;
			    margin-top: 10px;
			}
		</style>
	</head>

	<body>
		<div id="main-wrapper">
			<?php include('fronts/header.php'); ?>		

			<div id="wrap">	
				<div class="row">
						<?php include('fronts/querybox.php') ?>
				</div>
				<fieldset style="margin-top: 15px">
					<legend>Search Results</legend>
					<p style="width: 100%; text-align: right">Retrieved <?= sizeof($distinct); ?> results</p>					
					<?php
						if (mysql_num_rows($result) == 0) {
							echo "No results found";
						} else {
							foreach ($distinct as $item) : ?>
								<article class="small-6 columns" style="padding: 10px 20px; min-height: 200px">
									<div style="text-align: center; padding: 20px; border: 1px solid #bdbdbd;">
										<a href="thesisInfo.php?id=<?= $item['thesis_id']; ?>" style="font-weight: bolder; font-size: 110%;"><?= substr($item['title'], 0, 40); ?> . . .</a>
										<div style="margin-top: 10px; font-size: 75%">under <a href="facultyWorks.php?id=<?= $item['faculty_id']; ?>"><?= $item['fac_lname'].', '.$item['fac_fname']; ?></a>, published on <em><?= date('F j, Y', strtotime($item['date_published'])); ?></em></div>
										<blockquote style="line-height: 99%; text-indent: 2em; text-align: justify; width: 100%; font-size: 90%"><?= substr($item['abstract'], 0, 500); ?> . . .</blockquote>
									</div>
								</article>
							<?php endforeach;
						}
					?>
				</fieldset>
				<div class="row" style="margin-top: 20px">
					<ul class="pagination">
						<li class="arrow unavailable"><a href="">&laquo;</a></li>
						<li class="current"><a href="">1</a></li>
						<li><a href="">2</a></li>
						<li><a href="">3</a></li>
						<li><a href="">4</a></li>
						<li class="unavailable"><a href="">&hellip;</a></li>
						<li><a href="">12</a></li>
						<li><a href="">13</a></li>
						<li class="arrow"><a href="">&raquo;</a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<?php include('fronts/footer.php'); ?>
		<?php include('fronts/scripts.php'); ?>
	</body>
</html>