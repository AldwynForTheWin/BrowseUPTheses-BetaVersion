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

	$categories = mysql_query("SELECT * FROM category ORDER BY category_name ASC");
	$result_array = array();
	while ($category = mysql_fetch_assoc($categories)) {
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
								<form action="app/addEntry.php" method="POST">
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
												<label for="title" class="right inline">Title</label>
											</div>
											<div class="small-8 columns">
									        	<input type="text" id="title" style="font-size: 110%; font-weight: bolder; color: #007095;"
									        	name="title" placeholder="Title of the Thesis" required>
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
											    style="height: 20em; max-width: 100%; font-size: 14px" required></textarea>
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
														<input id="ctr" type="hidden" value="0"/>
														<div id="researcher_0" style="margin-bottom: 5px">
															<input type="text" style="width: 30%; display: inline-block" name="researcher[0][fn]" placeholder="First Name" required/>
															<input type="text" style="width: 30%; display: inline-block" name="researcher[0][mn]" placeholder="Middle Name" required/>
															<input type="text" style="width: 30%; display: inline-block" name="researcher[0][ln]" placeholder="Last Name" required/>
															<img style="display: inline-block; width: 33px" src="images/add-tiny.png" onclick="addAuth()" />
														</div>
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
										        				<input type="checkbox" id="cat_<?= $result_array[$i]['category_id']; ?>" name="category[<?= $result_array[$i]['category_id']; ?>]" style="margin-bottom: 0" value="<?= $result_array[$i]['category_id']; ?>">
												        		<label for="cat_<?= $result_array[$i]['category_id']; ?>"><?= $result_array[$i]['category_name']; ?></label>
											        		</td>
											        		<?php if ($i+1 != sizeof($result_array)) { ?>
											        		<td style="width: 50%">
										        				<input type="checkbox" id="cat_<?= $result_array[$i+1]['category_id']; ?>" name="category[<?= $result_array[$i+1]['category_id']; ?>]" style="margin-bottom: 0" value="<?= $result_array[$i+1]['category_id']; ?>">
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
									        	<input type="text" id="tags" name="tags" placeholder="Tags (separate by comma)">
									        </div>
									    </div>
									</div>
								  	<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									        	<label for="date-published" class="right inline">Date Published</label>
									        </div>
									        <div class="small-8 columns">
									        	<input type="date" id="date-published" name="pub_date" required />
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									        	<label for="date-accessioned" class="right inline">Date Accessioned</label>
									        </div>
									        <div class="small-8 columns">
									        	<input type="date" id="date-accessioned" name="acc_date" required/>
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