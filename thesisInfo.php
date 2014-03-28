<?php
	session_start();
	include('app/connectToServer.php');

	$thesis_id = $_GET['id'];

	$thesis_details = mysql_query(
		"SELECT * FROM thesis
			INNER JOIN thesis_categories
			INNER JOIN thesis_department
			INNER JOIN thesis_faculty
			ON thesis_categories.thesis_id = thesis.thesis_id
			AND thesis_department.thesis_id = thesis.thesis_id
			AND thesis_faculty.thesis_id = thesis.thesis_id
			AND thesis.thesis_id = '$thesis_id'"
		);
	$result = mysql_fetch_assoc($thesis_details);

	$department = mysql_query("SELECT * FROM department WHERE department_id = ".$result['department_id']);
	$department = mysql_fetch_assoc($department);

	$faculty = mysql_query("SELECT * FROM faculty_profiles WHERE faculty_id = ".$result['faculty_id']);
	$faculty = mysql_fetch_assoc($faculty);
	
	$thesis_researchers = mysql_query(
		"SELECT * FROM thesis_researchers
			INNER JOIN researcher
			ON researcher.researcher_id = thesis_researchers.researcher_id
			WHERE thesis_researchers.thesis_id = '$thesis_id'"
		);

	$res_array = array();
	while ($researcher = mysql_fetch_assoc($thesis_researchers)) {
		array_push($res_array, $researcher);
	}

	$thesis_tags = mysql_query(
		"SELECT * FROM thesis_tags
			INNER JOIN tags
			ON tags.tag_id = thesis_tags.tag_id
			WHERE thesis_tags.thesis_id = '$thesis_id'"
		);

	$tag_array = array();
	while ($tag = mysql_fetch_assoc($thesis_tags)) {
		array_push($tag_array, $tag);
	}

	$thesis_categories = mysql_query(
		"SELECT * FROM thesis_categories
			INNER JOIN category
			ON thesis_categories.category_id = category.category_id
			WHERE thesis_categories.thesis_id = '$thesis_id'"
		);

	$cat_array = array();
	while ($cat = mysql_fetch_assoc($thesis_categories)) {
		array_push($cat_array, $cat);
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?= $result['title']; ?></title>
		<?php include('fronts/meta.php'); ?>
		<style type="text/css">
			fieldset {
				margin: 20px;
			}

			.abs_parag {
				text-indent: 3em;
				font-size: 14px;
				text-align: justify;
			}
		</style>
	</head>

	<body>
		<div id="main-wrapper">
			<?php include('fronts/header.php'); ?>
			
			<div id="wrap">
				<div id="main">
					<div class="row">
						<div class="browsed-infobits large-12 medium-12 small-12 columns">
							<h3 style="color: #008CBA; font-weight: bolder;"><?= $result['title']; ?></h3>
							<fieldset>
								<legend>
									<h5>Thesis Information</h5>
								</legend>
								<table style="width: 100%">
									<tbody>
										<tr>
											<th>Title</th>
											<td><?= $result['title']; ?></td>
										</tr>
										<tr>
											<th>Researcher(s)</th>
											<td>
											<?php foreach ($res_array as $researcher) : ?>
												<span class="label secondary"><?= $researcher['res_lname'].', '.$researcher['res_fname'].' '. $researcher['res_mname']; ?></span>
											<?php endforeach; ?>
											</td>
										</tr>
										<tr>
											<th>Faculty</th>
											<td>
												<a href="facultyWorks.php?id=<?= $faculty['faculty_id']; ?>"><?= $faculty['fac_lname'].', '.$faculty['fac_fname'].' '. $faculty['fac_mname']; ?></a>
											</td>
										</tr>
										<tr>
											<th>Department</th>
											<td>
												<a href="department.php?id=<?= $department['department_id']; ?>"><?= $department['dep_name']; ?></a>
											</td>
										</tr>
										<tr>
											<th>Tags</th>
											<td>
											<?php foreach ($tag_array as $tag) : ?>
												<a href="app/search.php?search=<?= $tag['tag_name']; ?>&category=All" class="label"><?= $tag['tag_name']; ?></a>
											<?php endforeach; ?>
											</td>
										</tr>
										<tr>
											<th>Categories</th>
											<td>
											<?php foreach ($cat_array as $category) : ?>
												<a href="app/search.php?search=<?= $category['category_name']; ?>&category=All" class="label"><?= $category['category_name']; ?></a>
											<?php endforeach; ?>
											</td>
										</tr>
										<tr>
											<th>Date Issued</th>
											<td><?= date('F j, Y', strtotime($result['date_published'])); ?></td>
										</tr>
										<tr>
											<th>Date Accessioned</th>
											<td><?= date('F j, Y', strtotime($result['date_accessioned'])); ?></td>
										</tr>
									</tbody>
								</table>
								<a href="#top">Back to top &raquo</a>
							</fieldset>
							<fieldset>
								<legend>
									<h5>Abstract</h5>
								</legend>
								<div>
									<p class="abs_parag"><?= str_replace('<br />', '</p><p class="abs_parag">', nl2br($result['abstract'])); ?></p>
								</div>
								<a href="#top">Back to top &raquo</a>
							</fieldset>
						</div>
					</div>
				</div>	
			</div>
		</div>
		
		<?php include('fronts/footer.php'); ?>
		<?php include('fronts/scripts.php'); ?>
	</body>
</html>