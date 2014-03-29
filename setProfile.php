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

	$faculty_id = explode('_', $_SESSION['user_id'])[1];
	
	$faculty = mysql_query("SELECT * FROM faculty WHERE faculty_id = '$faculty_id'");
	$faculty = mysql_fetch_assoc($faculty);

	$faculty_details = mysql_query("SELECT * FROM faculty_profiles WHERE faculty_id = '$faculty_id'");
	$hasProfile = mysql_num_rows($faculty_details) == 1;
	if ($hasProfile) {
		$faculty_details = mysql_fetch_assoc($faculty_details);
	}
	
	$departments = mysql_query("SELECT * FROM department ORDER BY dep_name ASC");
	$department_list = array();
	while ($department = mysql_fetch_assoc($departments)) {
		array_push($department_list, $department);
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
						    	<?php if (isset($_SESSION['alert'])) { ?>
							    	<div data-alert class="alert-box warning radius" style="background-color:#420E0E">
									  	<?= $_SESSION['alert']?>
										<a href="#" class="close" style="color: white">&times;</a>
										<?php unset($_SESSION['alert']); ?>
									</div>
								<?php } ?>
						    	<form id="profile" method="POST" action="app/editFaculty.php">
						    		<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									        	<label for="author" class="right inline">Name</label>
									        </div>
									        <div class="small-8 columns">
												<div id="researchers">
													<input id="ctr" type="hidden" value="0"/>
													<div id="researcher_0" style="margin-bottom: 5px">
														<input type="text" style="width: 32.7%; display: inline-block" name="fac_fname" required placeholder="First Name" value="<?= ($hasProfile) ? $faculty_details['fac_fname'] : ''; ?>" />
														<input type="text" style="width: 32.7%; display: inline-block" name="fac_mname" required placeholder="Middle Name" value="<?= ($hasProfile) ? $faculty_details['fac_mname'] : ''; ?>" />
														<input type="text" style="width: 32.8%; display: inline-block" name="fac_lname" required placeholder="Last Name" value="<?= ($hasProfile) ? $faculty_details['fac_lname'] : '';?>" />
													</div>
												</div>
									        </div>
								   	    </div>
									</div>
								  	<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
												<label for="username" class="right inline">Username</label>
											</div>
											<div class="small-8 columns">
									        	<input type="text" id="username" name="username" placeholder="Username" required value="<?= $faculty['fac_username']; ?>">
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
												<label for="password" class="right inline">New Password</label>
											</div>
											<div class="small-8 columns">
									        	<input type="text" id="password" name="password" onchange="document.getElementById('confirm').disabled = false"
									        	placeholder="Password" required value="<?= $faculty['fac_password']; ?>">
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
												<label for="confirm" class="right inline">Confirm Password</label>
											</div>
											<div class="small-8 columns">
									        	<input type="text" id="confirm" disabled name="confirm" placeholder="Confirm Password" required value="<?= $faculty['fac_password']; ?>">
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns">
											<div class="small-4 columns">
									        	<label for="department" class="right inline">Department</label>
									        </div>
									        <div class="small-8 columns">
									        	<div id="department" style="border: 1px solid #CCC; padding: 11px 0 0 11px;">
									        	<?php $checked = ($hasProfile) ? $faculty_details['department_id'] : 1; ?>
									        		<?php foreach ($department_list as $department) : ?>
											        	<div style="display: block; margin-bottom: 1rem">
											        		<input type="radio" id="dep_<?= $department['department_id']; ?>" name="department" 
											        		<?= ($department['department_id'] == $checked) ? 'checked' : ''; ?>
											        		value="<?= $department['department_id']; ?>">
											        		<label for="dep_<?= $department['department_id']; ?>"><?= $department['dep_name']; ?></label>
											        	</div>
											        <?php endforeach; ?>
									        	</div>
									        </div>
									    </div>
									</div>
									<div class="row">
										<div class="small-10 columns" style="margin-top: 5px; ">
											<div class="small-4 columns">
									        	<label for="gender" class="right inline">Gender</label>
									        </div>
									        <div class="small-8 columns">
									        	<div style="border: 1px solid #CCC; padding: 11px 0 11px 11px;">
									        		<input type="radio" id="male" name="gender" <?= ($hasProfile && $faculty_details['gender'] == 'M') ? 'checked' : ''; ?> value="M">
									        		<label for="male">Male</label>
									        		<input type="radio" id="female" name="gender" <?= ($hasProfile && $faculty_details['gender'] == 'F') ? 'checked' : ''; ?> value="F">
									        		<label for="female">Female</label>
									        	</div>
									        </div>
									    </div>
									</div>
									<div class="row">
										<div style="text-align: center; margin-top: 20px">
											<input type="submit" class="tiny button radius" value="Confirm"/>
										</div>
								    </div>
					    		</form>
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