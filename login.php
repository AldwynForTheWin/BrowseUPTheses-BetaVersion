<?php
	session_start();

	if (isset($_SESSION['user_id'])) {
		$checkAuth = explode('_', $_SESSION['user_id'])[0];
		if ($checkAuth == 'a') {
			$_SESSION['alert'] = 'You are logged in as admin.';
			header('location: admin.php');
		} else {
			$_SESSION['alert'] = 'You are logged in as faculty.';
			header('location: faculty.php');
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Browse UP Theses</title>
		<?php include('fronts/meta.php'); ?>
	</head>

	<body style="margin-top: 200px">
		<form method="POST" action="app/login.php">
			<div style="width: 30%; margin: 0 auto; border: 1px solid #bdbdbd; padding: 20px; text-align: center;">
				<h3 style="background: #800000; color: #FFF; padding: 5px 0px; margin-bottom: 20px">Log-in</h3>
				<?php if (isset($_SESSION['alert'])) { ?>
			    	<div style="margin-bottom: 20px; color: red; font-size: 14px">
					  	<?= $_SESSION['alert']?>
						<?php unset($_SESSION['alert']); ?>
					</div>
				<?php } ?>
				<input type="text" placeholder="Username" name="username" style="margin-bottom: 10px" autofocus />
				<input type="password" placeholder="Password" name="password" /><br>
				<input type="submit" class="small button radius" value="Sign in" />
				<a class="small button radius" href="index.php">Cancel</a>
			</div>
		</form>
	</body>
</html>