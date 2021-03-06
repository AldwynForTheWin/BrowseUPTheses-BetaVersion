<?php 
	session_start(); 
	include('app/connectToServer.php');

	if (isset($_SESSION['user_id'])) {
		$checkAuth = explode('_', $_SESSION['user_id'])[0];
		if ($checkAuth == 'f') {
			$_SESSION['alert'] = 'You have not authorization to access the admin.';
			header('location: login.php');
		}
	} else {
		$_SESSION['alert'] = 'Sign in first.';
		header('location: login.php');
	}

	$faculty_array = array();
	$faculties = mysql_query("SELECT * FROM faculty");

	while($faculty = mysql_fetch_assoc($faculties)) {
		$faculty_profile = mysql_query("SELECT * FROM faculty_profiles WHERE faculty_id = " . $faculty['faculty_id']);
		if (mysql_num_rows($faculty_profile) == 1) {
			$faculty_profile = mysql_fetch_assoc($faculty_profile);
			$faculty['department_id'] = $faculty_profile['department_id'];
			
			$dept = mysql_query("SELECT * FROM department WHERE department_id = " . $faculty['department_id']);
			$dept = mysql_fetch_assoc($dept);
			$faculty['department_name'] = $dept['dep_name'];
			$faculty['full_name'] = $faculty_profile['fac_lname'] . ', ' . $faculty_profile['fac_fname'];

			$thesisCount = mysql_query("SELECT COUNT(*) FROM thesis_faculty WHERE faculty_id = " . $faculty['faculty_id']);
			$thesisCount = mysql_fetch_assoc($thesisCount);
			$faculty['thesis_counts'] = $thesisCount['COUNT(*)'];
		}
		array_push($faculty_array, $faculty);
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
				<div id="main">				
					<div class="row">
						<div class="small-12 columns collapse">
						<fieldset>
							<legend><h4>CVSC Home</h4></legend>
							<h3>List of Accounts</h3>
							<?php if (isset($_SESSION['alert'])) { ?>
						    	<div data-alert class="alert-box warning radius" style="background-color:#420E0E; margin-top: 20px">
								  	<?= $_SESSION['alert']?>
									<a href="#" class="close" style="color: white">&times;</a>
									<?php unset($_SESSION['alert']); ?>
								</div>
							<?php } ?>
						    <div id="facultyList" style="margin: 20px auto">
						    	<table style="width: 100%">
						    		<thead>
							    		<tr>
							    			<th></th>
							    			<th>Username</th>
							    			<th>Name of Teacher</th>
							    			<th>Department</th>
							    			<th>No. of Theses</th>
							    			<th>Print?</th>
							    			<th>Delete?</th>
							    		</tr>
						    		</thead>
						    		<form id="toDeleteMultiple" action="app/removeMultipleFaculty.php" method="POST"></form>
						    		<form id="toPrintMultiple" action="print.php" method="POST"></form>
						    		<tbody>
					    			<?php foreach ($faculty_array as $faculty) : ?>
										<tr>
											<td style="width: 0">
												<input type="checkbox" class="checkbox" form="toDeleteMultiple" name="faculty_id[<?= $faculty['faculty_id']; ?>]" value="<?= $faculty['faculty_id']; ?>"
												onselect="document.getElementById('deleteSelected').disabled = false">
											</td>
											<td><?= $faculty['fac_username']; ?></td>
							    			<td style="text-align: left">
							    				<?php if (isset($faculty['full_name'])) { ?>
							    					<a href="facultyWorks.php?id=<?= $faculty['faculty_id']; ?>"><?= $faculty['full_name']; ?></a>
							    				<?php } else { ?>
							    					<span>Not yet set</span>
							    				<?php } ?>
							    			</td>
							    			<td style="text-align: left">
							    				<?php if (isset($faculty['department_name'])) { ?>
							    					<a href="department.php?id=<?= $faculty['department_id']; ?>"><?= $faculty['department_name']; ?></a>
							    				<?php } else { ?>
							    					<span>Not yet set</span>
							    				<?php } ?>
							    			</td>
							    			<td style="text-align: center"><?= (isset($faculty['thesis_counts'])) ? $faculty['thesis_counts'] : 0; ?></td>
							    			<td style="text-align: center">
							    				<a href="#" title="Print out this account" <?= (!isset($faculty['full_name'])) ? 'data-reveal-id="print_' . $faculty['faculty_id'] . '" data-reveal' : 'style="opacity: .4"'; ?>>
							    					<img id="print" src="images/print-tiny.png" alt="Edit" style="height: 50%" >
							    				</a>
							    			</td>
							    			<div id="print_<?=$faculty['faculty_id']?>" class="reveal-modal tiny" data-reveal style="text-align:center">
												<h4>Do you really want to print out <em><?= (isset($faculty['full_name'])) ? $faculty['full_name'] : $faculty['fac_username']; ?>'s</em> log-in details?</h4>
												<style type="text/css">		
													.accountForm {
													    width: 250px;
													    min-height: 160px;
													    border: 1px solid black;
													    text-align: center;
													    margin-bottom: 20px;
													}
													#username, #password {
													    width: 122px;
													    display: inline-block;
													    border-bottom: 1px solid #bdbdbd;
													}
												</style>
												<div class="row" style="width: 100%">
													<div class="accountForm" style="margin: 0 auto">
														<div></br><h5>CVSC Faculty Account</br></h5></div>
														<div style="font-size: 13px; color:gray; font-style:italic">Please edit your account on first log-in</div></br>
														<div id="username">
															<p>Username</p>
															<div style="border-top: 1px solid #bdbdbd"><?= $faculty['fac_username']; ?></div>
														</div>
														<div id="password">
															<p>Password</p>
															<div style="border-top: 1px solid #bdbdbd"><span style="color: #cc0000">[confidential]</span></div>
														</div>
													</div>
												</div>
												<form action="print.php" method="POST">
													<input type="hidden" name="faculty_id[<?= $faculty['faculty_id']; ?>]" value="<?= $faculty['faculty_id']; ?>"/>
													<input type="submit" class="tiny button radius block" style="margin-top: 15px" value="Yes"/>
												</form>
												<a class="close-reveal-modal">&times;</a>
											</div>
											<td style="text-align: center">
							    				<a href="#" title="Remove this account" data-reveal-id="myModal_<?=$faculty['faculty_id']?>" data-reveal>
							    					<img id="delete" src="images/delete-tiny.png" alt="Edit" style="height: 50%">
							    				</a>
							    			</td>
							    			<div id="myModal_<?=$faculty['faculty_id']?>" class="reveal-modal tiny" data-reveal style="text-align:center">
												<h4>Do you really want to delete <em><?= (isset($faculty['full_name'])) ? $faculty['full_name'] : $faculty['fac_username']; ?></em>?</h4>
												<div data-alert class="alert-box warning radius" style="margin-top: 20px">
												  	<span>Make sure you have an authorization from the owner to delete this account.</span>
												  	<a href="#" class="close" style="color: white">&times;</a>
												</div>
												<a class="tiny button radius" href="app/removeFaculty.php?id=<?= $faculty['faculty_id']; ?>&name=<?= (isset($faculty['full_name'])) ? $faculty['full_name'] : $faculty['fac_username']; ?>" >Yes</a>
												<a class="close-reveal-modal">&times;</a>
											</div>
							    		</tr>
									<?php endforeach; ?>
						    		</tbody>
						    	</table>
						    </div>
						    <a href="#" class="button tiny radius" data-reveal-id="deleteSelectedModal" data-reveal>Delete Selected</a>
						    <div id="deleteSelectedModal" class="reveal-modal tiny" data-reveal style="text-align:center">
								<h4>Do you really want to delete selected accounts?</h4>
								<input id="deleteSelected" type="submit" form="toDeleteMultiple" class="tiny button radius" value="Yes" />
								<a class="close-reveal-modal">&times;</a>
							</div>
							<a href="#" class="button tiny radius" data-reveal-id="printSelectedModal" data-reveal>Print Selected</a>
						    <div id="printSelectedModal" class="reveal-modal tiny" data-reveal style="text-align:center">
								<h4>Do you really want to print out selected accounts?</h4>
								<input id="printSelected" type="submit" form="toPrintMultiple" class="tiny button radius" value="Yes" />
								<a class="close-reveal-modal">&times;</a>
							</div>
						</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php include('fronts/footer.php'); ?>
		<?php include('fronts/scripts.php'); ?>
		<script>
			var printSelected = document.getElementById('printSelected');
			printSelected.onmouseover = function() {
				$('.checkbox').attr('form', 'toPrintMultiple');
			}

			var deleteSelected = document.getElementById('deleteSelected');
			deleteSelected.onmouseover = function() {
				$('.checkbox').attr('form', 'toDeleteMultiple');
			}
		</script>
	</body>
</html>