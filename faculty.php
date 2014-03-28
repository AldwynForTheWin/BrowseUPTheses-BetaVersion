<?php 
	session_start();
	include('app/connectToServer.php');

	$user = explode('_', $_SESSION['user_id']);

	if (isset($_SESSION['user_id'])) {
		$checkAuth = $user[0];
		if ($checkAuth == 'a') {
			$_SESSION['alert'] = 'You have not authorization to access this page.';
			header('location: login.php');
		}
	} else {
		header('location: facultyWorks.php?id=' . $_GET['id']);
	}

	$faculty_id = $user[1];

	$faculty_details = mysql_query(
		"SELECT * FROM faculty
			INNER JOIN faculty_profiles
			INNER JOIN department
			ON faculty_profiles.department_id = department.department_id
			WHERE faculty_profiles.faculty_id = '$faculty_id'
		");
	$faculty_details = mysql_fetch_assoc($faculty_details);
	
	$theses = mysql_query("SELECT * FROM thesis_faculty WHERE faculty_id = '$faculty_id'");
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
<html lang="en">
	<head>
		<title>Browse UP Theses | UP Cebu's Online Theses Archive</title>
		<?php include('fronts/meta.php'); ?>
	</head>

	<body>
		<div id="main-wrapper">
			<?php include('fronts/header.php'); ?>

			<div id="wrap">
				<div id="main" style="margin: 20px 0">
					<div class="row">
						<div class="large-12 medium-10 small-10 columns">
							<fieldset id = "profile">
							    <legend><h4>My Profile</h4></legend>
							    <div id = "profileName">
							    	<h5><?= $faculty_details['fac_lname'].', '.$faculty_details['fac_fname'].' '.$faculty_details['fac_mname'].'. '; ?></h5>
							    </div>
							  	<?php if (isset($_SESSION['alert'])) { ?>
							    	<div data-alert class="alert-box warning radius" style="background-color:#420E0E; margin-top: 20px">
									  	<?= $_SESSION['alert']?>
										<a href="#" class="close" style="color: white">&times;</a>
										<?php unset($_SESSION['alert']); ?>
									</div>
								<?php } ?>
							    <table style="width: 100%">
						    		<thead>
							    		<tr>
							    			<th>Theses Title</th>
							    			<th>Researcher/s</th>
							    			<th>Category</th>
							    			<th>Tags</th>
							    			<th>Edit?</th>
							    			<th>Delete?</th>
							    		</tr>
						    		</thead>
						    		<tbody>
						    		<?php 
						    			if ($result_num_rows == 0) { ?>
						    				<tr style="text-align: center">
								    			<td colspan="6"><h4>No Thesis Yet! <a href="addEntry.php">Add one?</a></h4></td>
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
								    			<td>
								    				<a href="editEntry.php?id=<?= $thesis['thesis_id']; ?>">
								    					<img id="edit" src="images/edit-tiny.png" alt="Edit">
								    				</a>
								    			</td>
								    			<td>
								    				<a href="#" data-reveal-id="myModal_<?=$thesis['thesis_id']?>" data-reveal>
								    					<img id="delete" src="images/delete-tiny.png" alt="Edit">
								    				</a>
								    			</td>
								    		</tr>
								    		<div id="myModal_<?=$thesis['thesis_id']?>" class="reveal-modal tiny" data-reveal style="text-align:center">
												<h4>Do you really want to delete <em><?=$thesis['title']?></em>?</h4>
												<a href="app/removeEntry.php?id=<?= $thesis['thesis_id']; ?>&title=<?= $thesis['title']?>" class="tiny button radius">Yes</a>
												<a class="close-reveal-modal">&times;</a>
											</div>
									<?php endforeach;
										} ?>
									</tbody>
						    	</table>
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