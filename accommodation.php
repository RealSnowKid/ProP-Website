<!DOCTYPE html>
<?php

require_once 'formActions.php';
if (!isset($_SESSION['id'])) {
	header("Location: login.php");
	exit();
}

$records = [];

if (function_exists('fetchAccom')) {
	$records = fetchAccom($conn);
}


?>
<html>
<head>
  <title>Music Festival : Accommodation</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link href="style.css" rel="stylesheet" type="text/css">
  <link href="./flags_css/flag-icon.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script type = "text/javascript" src = "functions.js" ></script>
</head>
<body>

	<div class="container text-center">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Accommodation Nr</th>
					<th>Reserve</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (count($records) > 0):

					foreach ($records as $record): ?>
						<tr>
							<td><?php echo $record['Accommodation_ID'] ?></td>
							<td><a href="accommodation.php?reserve=<?php echo $record['Accommodation_ID'] ?>" class="btn">Reserve</a></td>
						</tr>
				<?php 
				endforeach;
				
				else: ?>
					<tr>
						<td colspan="2">No Free Rooms of This Type</td>
					</tr>
					<tr>
						<td colspan="2"><a href="profile.php" class="btn">Go Back</a></td>
					</tr>
				<?php endif ?>
			</tbody>

		</table>
	</div>
</body>
</html>