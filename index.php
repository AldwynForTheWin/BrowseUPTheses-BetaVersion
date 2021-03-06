<?php
	session_start();
	include('app/connectToServer.php');

	if (isset($_SESSION['user_id'])) {
		$checkAuth = explode('_', $_SESSION['user_id'])[0];
		if ($checkAuth == 'a') {
			header('location: admin.php');
		} else {
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
	<body>
		<div style="width: 100%; position: relative">
			<a class="label radius" href="login.php" style="float: right; margin-top: -128px; margin-right: 10px;">Log-in</a>
		</div>
		<div id="wrap" class="container" style="margin-top: 10%;">
			<div class="row" style="text-align: center">
				<div style="font: 100px normal 'Magistral C'; text-shadow: 0 0 10px; max-width: 77%; margin: 0 auto">
					<span style="color: #165C08">browse</span><span style="color: #800000; font-size: 130%;">up</span><span style="color: #165C08">theses</span><br/>
				</div>
				<div>
					<span style="font: 30px normal 'Magistral C'; color: #582E03">UP Cebu's Online Theses Archive</span>
				</div>
			</div>
			<form action="search.php" method="GET" class="row collapse" style="margin-top: 40px; padding: 20px; background-color: #bdbdbd">
				<div class="small-2 columns">
					<select name="category">
						<option value="All" selected>All</option>
						<option value="Thesis">Thesis</option>
						<option value="Researcher">Researcher</option>
						<option value="Department">Department</option>
						<option value="Faculty">Faculty</option>
						<option value="Year">Year</option>
					</select>
				</div>
				<div class="small-8 columns">
					<input id="q" type="text" name="search" placeholder="Search for keywords or scopes" autofocus autocomplete="off" onkeyup="updateTypeAhead(q.value)"/>
					<div id="typeahead-data"></div>
				</div>
				<div class="small-2 columns">
					<input id="jumbo-browse" type="submit" class="postfix small button" value="Browse" />
				</div>
			</form>
		</div>
		<script>
			function updateTypeAhead(q) {
				$('#typeahead-data div').remove();
				if (q !== '') {
					$('#typeahead-data').css('display', 'block');
					$.ajax({
						url: 'app/updateTypeAhead.php?q='+q,
						dataType: 'json',
						success: function(data) {
							for (var i = 0; i < data.vector.length; i++) {
								$('#typeahead-data').append('<div id="ta_data_'+i+'" onclick="transportValue(ta_data_'+i+'.innerHTML)">' +
									data.vector[i]['val'] + '</div>');
							}
						}
					});
				} else {
					$('#typeahead-data').css('display', 'none');
				}
			}

			function transportValue(ta_data) {
				$('#q').val(ta_data);
				$('#typeahead-data').css('display', 'none');
			}
		</script>
	</body>
	<?php include('fronts/scripts.php'); ?>
</html>