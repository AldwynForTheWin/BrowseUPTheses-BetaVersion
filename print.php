<?php
	session_start();
	include('app/connectToServer.php');
	
	$faculty_id = $_POST['faculty_id'];
?>

<html>
	<head>
		<title>Browse UP Theses | UP Cebu's Online Theses Archive</title>
			<?php include('fronts/meta.php'); ?>
			<style type="text/css">		
				.accountForm {
				    width: 250px;
				    height: 160px;
				    border: 1px solid black;
				    margin: 15px 15px 30px 30px; 
				    text-align: center;
				    float: left;
				}
				#username, #password {
				    width: 122px;
				    display: inline-block;
				    border-bottom: 1px solid #bdbdbd;
				}
			</style>
	</head>
	<body>
		<?php foreach ($faculty_id as $id) :
			$faculty = mysql_query("SELECT * FROM faculty WHERE faculty_id = '$id'");
			$faculty = mysql_fetch_assoc($faculty); ?>
			<div class="accountForm">
				<div></br><h5>CVSC Faculty Account</br></h5></div>
				<div style="font-size: 13px; color:gray; font-style:italic">Please edit your account on first log-in</div></br>
				<div id="username">
					<p>Username</p>
					<div style="border-top: 1px solid #bdbdbd"><?= $faculty['fac_username']; ?></div>
				</div>
				<div id="password">
					<p>Password</p>
					<div style="border-top: 1px solid #bdbdbd"><?= $faculty['fac_password']; ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	</body>
</html>