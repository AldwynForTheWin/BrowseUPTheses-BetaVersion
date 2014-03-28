<?php 
	session_start();
	include('app/connectToServer.php');

	$faculty = mysql_query("SELECT department_id FROM faculty_profiles WHERE faculty_id = " . explode('_', $_SESSION['user_id'])[1]);
	$faculty = mysql_fetch_assoc($faculty);

	$department_id = (isset($_GET['id'])) ? $_GET['id'] : $faculty['department_id'];

	$department_details = mysql_query("SELECT * FROM department WHERE department_id = '$department_id'");
	$department_details = mysql_fetch_assoc($department_details);
	
	$theses = mysql_query("SELECT * FROM thesis_department WHERE department_id = '$department_id'");
	$result_num_rows = mysql_num_rows($theses);

	$theses_array = array();
	while ($thesis = mysql_fetch_assoc($theses)) {
		$thesis = mysql_query("SELECT * FROM thesis WHERE thesis_id = ".$thesis['thesis_id']);
		$thesis = mysql_fetch_assoc($thesis);
		$thesis['researchers'] = array();
		$thesis['categories'] = array();
		$thesis['tags'] = array();

		$researchers = mysql_query(
			"SELECT * FROM researcher
				INNER JOIN thesis_researchers
				ON researcher.researcher_id = thesis_researchers.researcher_id
				WHERE thesis_researchers.thesis_id = ".$thesis['thesis_id']
			);
		while ($researcher = mysql_fetch_assoc($researchers)) {
			array_push($thesis['researchers'], $researcher);
		}

		$categories = mysql_query(
			"SELECT category.category_name FROM category
				INNER JOIN thesis_categories
				ON category.category_id = thesis_categories.category_id
				WHERE thesis_categories.thesis_id = ".$thesis['thesis_id']
			);
		while ($category = mysql_fetch_assoc($categories)) {
			array_push($thesis['categories'], $category['category_name']);
		}

		$tags = mysql_query(
			"SELECT tags.tag_name FROM tags
				INNER JOIN thesis_tags
				ON tags.tag_id = thesis_tags.tag_id
				WHERE thesis_tags.thesis_id = ".$thesis['thesis_id']
			);
		while ($tag = mysql_fetch_assoc($tags)) {
			array_push($thesis['tags'], $tag['tag_name']);
		}

		array_push($theses_array, $thesis);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Browse UP Theses | UP Cebu's Online Theses Archive</title>
		<?php include('fronts/meta.php'); ?>
	</head>
	<body>
		<div id="main-wrapper">
			<?php include('fronts/header.php'); ?>
			<div id="wrap">
				<div id="main" style="margin: 20px 0">
					<fieldset>
						<legend><h4>Theses under <em><?= $department_details['dep_name']; ?></em></h4></legend>
						<table style="width: 100%">
				    		<thead>
					    		<tr>
					    			<th>Theses Title</th>
					    			<th>Researcher/s</th>
					    			<th>Category</th>
					    			<th>Tags</th>
					    		</tr>
				    		</thead>
				    		<tbody>
				    			<?php 
					    			if ($result_num_rows == 0) { ?>
					    				<tr style="text-align: center">
							    			<td colspan="6"><h4>No Thesis Yet!</h4></td>
							    		</tr>
					    		<?php } else {
					    			foreach ($theses_array as $thesis) : ?>
										<tr>
							    			<td><a href="thesisInfo.php?id=<?= $thesis['thesis_id']; ?>"><?= $thesis['title']?></a></td>
							    			<td>
							    			<?php foreach ($thesis['researchers'] as $researcher) : ?>
							    				<span class="label secondary">
							    				<?= $researcher['res_lname'].', '.$researcher['res_fname'].' '. $researcher['res_mname']; ?>
							    				</span>
							    			<?php endforeach; ?>
							    			</td>
							    			<td>
							    			<?php foreach ($thesis['categories'] as $category) : ?>
							    				<a href="search.php?search=<?= $category ?>&category=All" class="label"><?= $category; ?></a>
							    			<?php endforeach; ?>
							    			</td>
							    			<td>
							    			<?php foreach ($thesis['tags'] as $tag) : ?>
							    				<a href="search.php?search=<?= $tag ?>&category=All" class="label"><?= $tag; ?></a>
							    			<?php endforeach; ?>
							    			</td>
							    		</tr>
								<?php endforeach;
									} ?>
							</tbody>
				    	</table>
			    	</fieldset>
		    	</div>
			</div>
		</div>

	<?php include('fronts/footer.php'); ?>
	<?php include('fronts/scripts.php'); ?>
</body>
</html>