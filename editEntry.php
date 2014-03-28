<?php
	session_start();
	include('app/connectToServer.php');

	if (isset($_SESSION['user_id'])) {
		$checkAuth = explode('_', $_SESSION['user_id'])[0];
		if ($checkAuth == 'a') {
			$_SESSION['alert'] = 'You have not authorization to access this page.';
			header('location: login.php');
		}
	}

	$thesis_id = $_GET['id'];

	$thesis = mysql_query("SELECT * FROM thesis WHERE thesis_id = '$thesis_id'");
	$thesis = mysql_fetch_assoc($thesis);
	$thesis['date_published'] = date('Y-m-d', strtotime($thesis['date_published']));
	$thesis['date_accessioned'] = date('Y-m-d', strtotime($thesis['date_accessioned']));

	$res_array = array();
	$researchers = mysql_query(
		"SELECT * FROM researcher
			INNER JOIN thesis_researchers
			ON researcher.researcher_id = thesis_researchers.researcher_id
			WHERE thesis_researchers.thesis_id = '$thesis_id'"
		);
	while ($researcher = mysql_fetch_assoc($researchers)) {
		array_push($res_array, $researcher);
	}

	$tag_array = array();
	$tags = mysql_query(
		"SELECT tags.tag_name FROM tags
			INNER JOIN thesis_tags
			ON tags.tag_id = thesis_tags.tag_id
			WHERE thesis_tags.thesis_id = '$thesis_id'"
		);
	while ($tag = mysql_fetch_assoc($tags)) {
		array_push($tag_array, $tag['tag_name']);
	}

	$th_cat_array = array();
	$thesis_categories = mysql_query("SELECT category_id FROM thesis_categories WHERE thesis_id = '$thesis_id'");
	while ($th_cat = mysql_fetch_assoc($thesis_categories)) {
		array_push($th_cat_array, $th_cat['category_id']);
	}

	$result_array = array();
	$categories = mysql_query("SELECT * FROM category ORDER BY category_name ASC");
	while ($category = mysql_fetch_assoc($categories)) {
		$category['checked'] = (in_array($category['category_id'], $th_cat_array)) ? 'checked' : '';
		array_push($result_array, $category);
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
							<fieldset style="margin-top: 0">
								<legend>
									<h5>Enter Thesis Details</h5>
								</legend>
								<?php if (isset($_SESSION['alert'])) { ?>
							    	<div data-alert class="alert-box warning radius" style="background-color:#420E0E">
									  	<?= $_SESSION['alert']?>
										<a href="#" class="close" style="color: white">&times;</a>
										<?php unset($_SESSION['alert']); ?>
									</div>
								<?php } ?>
								<form action="app/editEntry.php" method="POST">
									<input type="hidden" name="thesis_id" value="<?= $thesis_id; ?>" />
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
												<label for="title" class="right inline">Title</label>
											</div>
											<div class="small-8 columns">
									        	<input type="text" id="title" style="font-size: 110%; font-weight: bolder; color: #007095;"
									        	name="title" placeholder="Title of the Thesis" value="<?= $thesis['title']; ?>" required>
									        </div>
									    </div>
									</div>
									<div class="row" style="margin-bottom: 5px">
										<div class="small-10 columns">
											<div class="small-4 columns">
									          	<label for="abstract" class="right inline">Abstract</label>
									        </div>
									        <div class="small-8 columns">
											    <textarea id="abstract" name="abstract" placeholder="Abstract" 
											    style="height: 20em; max-width: 100%; font-size: 14px" required><?= str_replace('\\\'', '\'', $thesis['abstract']); ?></textarea>
									        </div>
									    </div>
									</div>
									<div id="addAuthor">
										<div class="row">
											<div class="small-10 columns">
												<div class="small-4 columns">
										        	<label for="author" class="right inline">Author/s</label>
										        </div>
										        <div class="small-8 columns">
													<div id="researchers">
														<?php $i = 0; foreach ($res_array as $researcher) : ?>
															<div id="researcher_<?= $i; ?>" style="margin-bottom: 5px">
																<input type="text" style="width: 30%; display: inline-block" name="researcher[0][fn]" placeholder="First Name" value="<?= $researcher['res_fname']; ?>" required/>
																<input type="text" style="width: 30%; display: inline-block" name="researcher[0][mn]" placeholder="Middle Name" value="<?= $researcher['res_mname']; ?>" required/>
																<input type="text" style="width: 30%; display: inline-block" name="researcher[0][ln]" placeholder="Last Name" value="<?= $researcher['res_lname']; ?>" required/>
																<?php if ($i != 0) { ?>
																	<img style="display: inline-block; width: 33px" src="images/delete-tiny.png" onclick="fadeOut('#researcher_<?= $i; ?>')" />
																<?php } else { ?>
																	<img style="display: inline-block; width: 33px" src="images/add-tiny.png" onclick="addAuth()" />
																<?php } ?>
															</div>
														<?php $i++; endforeach; ?>
														<input id="ctr" type="hidden" value="<?= $i-1; ?>"/>
													</div>
										        </div>
									   	    </div>
										</div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									       		<label for="select" class="right inline">Categories</label>
									        </div>
									        <div class="small-8 columns">
									        	<table style="width: 100%; border: 1px solid #CCC; padding: 11px 0 0 11px; margin-bottom: 5px">
									        		<?php for ($i = 0; $i < sizeof($result_array); $i+=2) : ?>
									        			<tr>
									        				<td style="width: 50%">
										        				<input type="checkbox" id="cat_<?= $result_array[$i]['category_id']; ?>" name="category[<?= $result_array[$i]['category_id']; ?>]" <?= $result_array[$i]['checked']; ?> style="margin-bottom: 0" value="<?= $result_array[$i]['category_id']; ?>">
												        		<label for="cat_<?= $result_array[$i]['category_id']; ?>"><?= $result_array[$i]['category_name']; ?></label>
											        		</td>
											        		<?php if ($i+1 != sizeof($result_array)) { ?>
											        		<td style="width: 50%">
										        				<input type="checkbox" id="cat_<?= $result_array[$i+1]['category_id']; ?>" name="category[<?= $result_array[$i+1]['category_id']; ?>]" <?= $result_array[$i+1]['checked']; ?> style="margin-bottom: 0" value="<?= $result_array[$i+1]['category_id']; ?>">
												        		<label for="cat_<?= $result_array[$i+1]['category_id']; ?>"><?= $result_array[$i+1]['category_name']; ?></label>
											        		</td>
											        		<?php } else echo '<td style="width: 50%"></td>'; ?>
											        	</tr>
											        <?php endfor; ?>
									        	</table>
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
												<label for="tags" class="right inline">Tag/s</label>
											</div>
											<div class="small-8 columns">
									        	<input type="text" id="tags" name="tags" placeholder="Tags (separate by comma)" value="<?php
									        		$i = 0; foreach ($tag_array as $tag) {
									        			echo $tag;
									        			if ($i != sizeof($tag_array)-1) {
									        				echo ', ';
									        			}
									        			$i++;
									        		}
									        	?>">
									        </div>
									    </div>
									</div>
								  	<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									        	<label for="date-published" class="right inline">Date Published</label>
									        </div>
									        <div class="small-8 columns">
									        	<input type="date" id="date-published" name="pub_date" required value="<?= $thesis['date_published']; ?>"/>
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									        	<label for="date-accessioned" class="right inline">Date Accessioned</label>
									        </div>
									        <div class="small-8 columns">
									        	<input type="date" id="date-accessioned" name="acc_date" required value="<?= $thesis['date_accessioned']; ?>"/>
									        </div>
									    </div>
									</div>
									<div class="row">
										<div style="text-align: center; margin-top: 20px">
											<input type="submit" class="tiny button radius" value="Add Entry">
										</div>
								    </div>
								</form>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			var ctr = 1;
			
			function addAuth() {
				var toAppend = '\
				<div id="researcher_' + ctr + '" style="margin-bottom: 5px"> \
					<input type="text" style="width: 30%; display: inline-block" name="researcher[' + ctr + '][fn]" placeholder="First Name" /> \
					<input type="text" style="width: 30%; display: inline-block" name="researcher[' + ctr + '][mn]" placeholder="Middle Name" /> \
					<input type="text" style="width: 30%; display: inline-block" name="researcher[' + ctr + '][ln]" placeholder="Last Name" /> \
					<img style="display: inline-block; width: 33px" src="images/delete-tiny.png" onclick="fadeOut(\'#researcher_' + ctr + '\')"/> \
				</div>';
				$('#researchers').append(toAppend);
				ctr++;
			}

			function fadeOut(id) {
				$(id).remove();
			}
		</script>
		
		<?php include('fronts/footer.php'); ?>
		<?php include('fronts/scripts.php'); ?>
		
	</body>
</html>