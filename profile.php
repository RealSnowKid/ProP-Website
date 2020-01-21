<!DOCTYPE html>
<?php

$accom_errors = array();
require_once 'formActions.php';
if (!isset($_SESSION['id'])) {
	header("Location: login.php");
	exit();
}



?>
<html>
<head>

  <title>Music Festival : Profile</title>
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

<body id="top" data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a href="index.php#start"><img class="rounded float-left" src="dj_set_2.png" height="50" width="50"></a><a class="navbar-brand" href="index.php#start">LIT MUSIC FESTIVAL</a>
    </div>


    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="index.php#start">HOME</a></li>
        <li><a href="index.php#event">ABOUT</a></li>
       	<li><a href="index.php#schedule">SCHEDULE</a></li>
<!--         <li><a href="index.php#contact">CONTACT</a></li> -->
        <li><a href="index.php#location">LOCATION</a></li>
        <?php if(isset($_SESSION['id'])): ?>
        <li><a href="profile.php">PROFILE</a></li>
        <?php else : ?>
        <li><a href="login.php">LOG IN</a></li>
	    <?php endif ?>
       </ul>
    </div>
  </div>
</nav>

<div class="container text-center">
    <img src="dj_set.png" alt="DJ Set" width="200" height="200">

	<h1>Welcome <?php if (isset($_SESSION['email'])) {
		echo $_SESSION['email'];
		// echo "<br>";
		// echo $_SESSION['id'];
		// echo "<br>";
		// echo $_SESSION['tickets'];
		// echo "<br>";
		// echo $_SESSION['reservation'];
		// echo "<br>";
		// echo $_SESSION['test'];
	} ?>,</h1>
	<h1>this is your profile page.</h1>
<!-- 	<a href="emailer.php" class="btn btn-block3">Email</a>
	<br>
	<br> -->
	<a href="profile.php?logout=1" class="btn btn-block3">Log Out</a>
	<br>
	<hr>
	<h2>Reserved Tickets:</h2>
	<br>

	<?php
		$sql = "SELECT ticketHex FROM customer WHERE email=? AND ticketHex IS NOT NULL LIMIT 5";

		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $_SESSION['email']);
		$stmt->execute();
		$stmt_result = $stmt->get_result();


		switch ($stmt_result->num_rows) {
			case '1':
			echo "<ul class=\"list-group\">";
			$i = 1;
				while($row_data = $stmt_result->fetch_assoc()) {

				    $ticketHex = $row_data['ticketHex'];

				    $t1 = substr($ticketHex, 0, 10);
					echo "
						  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=".$t1."\">Cancel Ticket</a> </li>
						 ";
					$i++;	  
				}
				echo "</ul>";
				$stmt->close();
				break;
			
			case '2':
			echo "<ul class=\"list-group\">";
			$i = 1;
				while($row_data = $stmt_result->fetch_assoc()) {

				    $ticketHex = $row_data['ticketHex'];

				    $t1 = substr($ticketHex, 0, 10);
					echo "
						  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=".$t1."\">Cancel Ticket</a> </li>
						 ";
					$i++;	  
				}
				echo "</ul>";
				$stmt->close();
				break;

			case '3':
			echo "<ul class=\"list-group\">";
			$i = 1;
				while($row_data = $stmt_result->fetch_assoc()) {

				    $ticketHex = $row_data['ticketHex'];

				    $t1 = substr($ticketHex, 0, 10);
					echo "
						  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=".$t1."\">Cancel Ticket</a> </li>
						 ";
					$i++;	  
				}
				echo "</ul>";
				$stmt->close();
				break;

			case '4':
			echo "<ul class=\"list-group\">";
			$i = 1;
				while($row_data = $stmt_result->fetch_assoc()) {

				    $ticketHex = $row_data['ticketHex'];

				    $t1 = substr($ticketHex, 0, 10);
					echo "
						  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=".$t1."\">Cancel Ticket</a> </li>
						 ";
					$i++;	  
				}
				echo "</ul>";
				$stmt->close();
				break;

			case '5':
			echo "<ul class=\"list-group\">";
			$i = 1;
				while($row_data = $stmt_result->fetch_assoc()) {

				    $ticketHex = $row_data['ticketHex'];

				    $t1 = substr($ticketHex, 0, 10);
					echo "
						  <li class=\"list-group-item\">Ticket Code: " . $t1 . " <a class=\"btn btn-ct\" href=\"profile.php?cancelticket=".$t1."\">Cancel Ticket</a> </li>
						 ";
					$i++;	  
				}
				echo "</ul>";
				$stmt->close();
				break;

			case '0':
				$email = $_SESSION['email'];
				unset($_SESSION['tickets']);

				$sql = "UPDATE accommodation SET customerEmail = NULL WHERE customerEmail = '".$email."'";

				if (!$conn->query($sql)) {
					echo "<script type='text/javascript'> 
				    window.alert('There is a big error. Please, contact someone!');
					</script>";
				}
				else {
					unset($_SESSION['reservation']);
				}

				echo "<h3>You have not yet purchased any tickets!</h3>";
				echo "<br>";
				echo "<a class=\"btn btn-block3\" href=\"index.php#schedule\">To Tickets</a>";
				break;

			default:
				$email = $_SESSION['email'];
				unset($_SESSION['tickets']);

				$sql = "UPDATE accommodation SET customerEmail = NULL WHERE customerEmail = '".$email."'";

				if (!$conn->query($sql)) {
					echo "<script type='text/javascript'> 
				    window.alert('There is a big error. Please, contact someone!');
					</script>";
				}
				else {
					unset($_SESSION['reservation']);
				}

				echo "<h3>You have not yet purchased any tickets!</h3>";
				echo "<br>";
				echo "<a class=\"btn btn-block3\" href=\"index.php#schedule\">To Tickets</a>";
				break;
		}

	?>

	<hr>

	<?php if (isset($_SESSION['tickets'])): ?>
		<?php if(isset($_SESSION['reservation'])): ?>

			<h1 style="color:black">You Have Reserved Accommodation Number: <?php echo $_SESSION['reservation']; ?>.</h1>
			<a class="btn btn-block3" href="profile.php?cancelres=<?php echo $_SESSION['reservation'] ?>">Cancel Reservation</a>

		<?php else: ?>

			<button class="btn btn-block2" data-toggle="modal" data-target="#accommodation">Reserve Accommodation</button>

		<?php endif ?>
	<?php endif ?>

	<div class="modal fade" id="accommodation" role="dialog">
	    <div class="modal-dialog">
	    
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">×</button>
	          <h4><span class="glyphicon glyphicon-bed"></span> Accommodation</h4>
	        </div>
	        <div class="modal-body">
	          <form role="form" method="POST" action="accommodation.php">
	            <div class="form-group">

	            <?php if(count($accom_errors) > 0): ?>
	              <div class="alert alert-danger">
	                <?php foreach ($ticket_errors as $error): ?>
	                  <li><?php echo $error; ?></li>
	                <?php endforeach; ?>
	              </div>
	            <?php endif; ?>

	              <label for="info">Select the Size of the Room You Want:</label>
	              <select name="accommodation">
	                <option value=2>2 person</option>
	                <option value=3>3 person</option>
	                <option value=4>4 person</option>
	                <option value=5>5 person</option>
	              </select>
	            </div>
	              <button class="btn btn-block">Confirm 
	                <span class="glyphicon glyphicon-ok"></span>
	              </button>
	          </form>
	        </div>

	        <div class="modal-footer">
	          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
	            <span class="glyphicon glyphicon-remove"></span> Cancel
	          </button>
	        </div>

	      </div>

	    </div>
	</div>


</body>
</html>