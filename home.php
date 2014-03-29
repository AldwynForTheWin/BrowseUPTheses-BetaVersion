<?php
	session_start();
	include('app/connectToServer.php');
	
	$theses = array();
	$random_theses = mysql_query("SELECT * FROM thesis");
	while ($thesis = mysql_fetch_assoc($random_theses)) {
		array_push($theses, $thesis['thesis_id']);
	}

	$randomized = array();

	$limit = (sizeof($theses) > 10) ? 10 : sizeof($theses); 
	for ($i = 0; $i < $limit; $i++) { 
		$random_theses = mysql_query(
			"SELECT * FROM thesis INNER JOIN thesis_faculty
			ON thesis.thesis_id = thesis_faculty.thesis_id
			WHERE thesis.thesis_id = " . $theses[$i]);
		$random_theses = mysql_fetch_assoc($random_theses);
		array_push($randomized, $random_theses);
	}

	for ($i = 0; $i < sizeof($randomized); $i++) {
		$fetchFaculty = mysql_query("SELECT * FROM faculty_profiles WHERE faculty_id = " . $randomized[$i]['faculty_id']);
		$fetchFaculty = mysql_fetch_assoc($fetchFaculty);
		$randomized[$i]['full_name'] =  $fetchFaculty['fac_lname'].', '.$fetchFaculty['fac_fname'];
		$randomized[$i]['date_published'] =  date('F j, Y', strtotime($randomized[$i]['date_published']));
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
				<!-- <div class="row" style="text-align: center; border: 1px solid #bdbdbd; height: 150px; margin-bottom: 10px">
					<h1>Tag Cloud Here</h1>
				</div> -->
				<?php include('fronts/tagCloud.php'); ?>
				<div class="row">
					<?php include('fronts/querybox.php') ?>
				</div>
				<fieldset style="margin-top: 10px">
					<legend>Featured Theses</legend>
					<?php foreach ($randomized as $item) : ?>
						<article class="small-6 columns" style="padding: 10px 20px; height: 200px">
							<div style="text-align: center; padding: 10px; border: 1px solid #bdbdbd;">
								<a href="thesisInfo.php?id=<?= $item['thesis_id']; ?>" style="font-weight: bolder; padding-bottom: 10px;"><?= substr($item['title'], 0, 40); ?> . . .</a>
								<div style="margin-top: 6px; font-size: 75%;">under <a href="facultyWorks.php?id=<?= $item['faculty_id']; ?>"><?= $item['full_name']; ?></a>, published on <em><?= $item['date_published']; ?></em></div>
								<blockquote style="line-height: 99%; text-indent: 2em; text-align: justify; width: 100%; font-size: 90%"><?= substr($item['abstract'], 0, 400); ?> . . .</blockquote>
							</div>
						</article>
					<?php endforeach;?>
				</fieldset>
			</div>
		</div>
		
		<?php include('fronts/footer.php'); ?>
		<?php include('fronts/scripts.php'); ?>
	</body>
</html>